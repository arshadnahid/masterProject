<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 11/17/2019
 * Time: 12:51 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class BankBranchController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('BankBranch_Model');
        $this->load->model('Finane_Model');
        $this->load->model('Accounts_model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'Accounts';
        $this->link_icon_add = "<i class='ace-icon fa fa-plus'></i>";
        $this->link_icon_list = "<i class='ace-icon fa fa-list'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }
    public function bankAccountInfo($updateId = null)
    {
        if (isPostBack()) {
            $bank_name = trim($this->input->post('bank_name'));
            $bank_name = preg_replace('/\s+/', ' ', $bank_name);
            $account_no = trim($this->input->post('account_no'));
            $account_no = preg_replace('/\s+/', ' ', $account_no);
            $Cash_at_Bank = $this->config->item("Cash_at_Bank");
            $exits = $this->checkDuplicateBranch($bank_name, $account_no, $updateId);
            if (empty($exits)) {
                $activeValue = $this->input->post('is_active');
                $account_name=substr( $this->input->post('account_no'), -5);
                $parent_name = $this->input->post('bank_name') . ' # A/C :  ' . $account_name;
                if ($activeValue == 'Y'):
                    $data['is_active'] = 'Y';
                else:
                    $data['is_active'] = 'N';
                endif;
                $data['bank_name'] = $bank_name;
                $data['account_no'] = $account_no;
                $data['address'] = trim($this->input->post('address'));
                // $data['phone'] = trim($this->input->post('phone'));
                if (empty($updateId)) {
                    $insertId = $this->Common_model->insert_data('bank_account_info', $data);


                    create_ledger_cus_sup_product($insertId, $parent_name, $Cash_at_Bank, 6, $this->admin_id);
                    if (!empty($insertId)) {
                        $msg = 'Your data successfully inserted into database';
                        $this->session->set_flashdata('success', $msg);
                        redirect(site_url($this->project . '/bankAccountInfo'));
                    }
                } else {
                    $updatedId = $this->Common_model->update_data('bank_account_info', $data, 'bank_account_info_id', $updateId);
                    //echo $updatedId;;exit;

                    update_ledger_cus_sup_product($updateId, $parent_name, $Cash_at_Bank , 6, $this->admin_id);
                    if (!empty($updatedId)) {
                        $msg = 'Your data successfully updated into database.';
                        $this->session->set_flashdata('success', $msg);
                        redirect(site_url($this->project . '/bankAccountInfo'));
                    } else {
                        $msg = 'You have made no change to update.';
                        $this->session->set_flashdata('error', $msg);
                        redirect(site_url($this->project . '/bankAccountInfo'));
                    }
                }
            } else {

                $msg = 'The Bank Account Already Save.';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/bankAccountInfo'));
            }
        }
        if (!empty($updateId)) {
            $data['updateData'] = $this->Common_model->get_single_data_by_single_column('bank_account_info', 'bank_account_info_id', $updateId);
        }
        $data['title'] = 'Bank Account Info';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $condition = array(
            //'is_active' => "Y",
            'is_delete' => "N",
        );
        $data['branchList'] = $this->Common_model->get_data_list_by_many_columns('bank_account_info', $condition);
        $data['mainContent'] = $this->load->view('distributor/setup/bank_account/bank_account', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function deletebankAccountInfo($deleteId)
    {
        $data['is_delete'] = 'Y';
        $deleteInfo = $this->Common_model->update_data('bank_account_info', $data, 'bank_account_info_id', $deleteId);
        $msg = 'Your data successfylly deleted from database.';
        $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/bankAccountInfo'));
    }
    function checkDuplicateBranch($bank_name, $account_no, $bank_account_info_id)
    {
        $bank_name = $bank_name;
        $account_no = $account_no;
        $bank_account_info_id = $bank_account_info_id;
        if ($bank_account_info_id != null) {
            $condition = array(
                'bank_name' => $bank_name,
                'account_no' => $account_no,
                'bank_account_info_id !=' => $bank_account_info_id,
            );
        } else {
            $condition = array(
                'bank_name' => $bank_name,
                'account_no' => $account_no,
            );
        }
        $exits = $this->Common_model->get_single_data_by_many_columns('bank_account_info', $condition);
        //if (!empty($exits)) {
        return $exits;
        //}
    }
}