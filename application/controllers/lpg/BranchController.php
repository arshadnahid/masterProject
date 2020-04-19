<?php
/**
 * Created by PhpStorm.
 * User: AEL
 * Date: 10/9/2019
 * Time: 1:51 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class BranchController extends CI_Controller
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
    function checkDuplicateBranch()
    {
        $branch = trim($this->input->post('branch'));
        $branch = preg_replace('/\s+/', ' ', $branch);
        $condition = array(
            'branch_name' => $branch,
            'is_active' => 1,
            'company_id' => $this->dist_id,
        );
        $exits = $this->Common_model->get_single_data_by_many_columns('branch', $condition);
        if (!empty($exits)) {
            echo 1;
        }
    }
    function checkDuplicateBranchUpdate()
    {
        $branch = trim($this->input->post('branch'));
        $branch_id = trim($this->input->post('branch_id'));
        $branch = preg_replace('/\s+/', ' ', $branch);
        $condition = array(
            'branch_name' => $branch,
            'is_active' => 1,
            'company_id' => $this->dist_id,
            'branch_id !=' => $branch_id,
        );
        $exits = $this->Common_model->get_single_data_by_many_columns('branch', $condition);
        if (!empty($exits)) {
            echo 1;
        }
    }
    public function deleteBranch($deleteId)
    {
        $data['is_active'] = 0;
        $deleteInfo = $this->Common_model->update_data('branch', $data, 'branch_id', $deleteId);
        message("Your data successfylly deleted from database.");
        redirect(site_url($this->project . '/branchInfo'));
    }
    public function branchInfo($updateId = null)
    {
        if (isPostBack()) {
            $activeValue = $this->input->post('activeValue');
            if ($activeValue == 1):
                $data['is_active'] = 1;
            else:
                $data['is_active'] = 0;
            endif;
            $data['company_id'] = $this->input->post('company_id');
            $data['branch_code'] = $this->input->post('branch_code');
            $data['branch_name'] = trim($this->input->post('branch_name'));
            $data['phone'] = trim($this->input->post('phone'));
            $data['branch_address'] = trim($this->input->post('branch_address'));
            $data['remarks'] = trim($this->input->post('remarks'));
            if (empty($updateId)) {
                $insertId = $this->Common_model->insert_data('branch', $data);
                if (!empty($insertId)) {
                    message("Your data successfully inserted into database.");
                    redirect(site_url($this->project . '/branchInfo'));
                }
            } else {
                $updatedId = $this->Common_model->update_data('branch', $data, 'branch_id', $updateId);
                if (!empty($updatedId)) {
                    message("Your data successfully updated into database.");
                    redirect(site_url($this->project . '/branchInfo'));
                } else {
                    exception("You have made no change to update.");
                    redirect(site_url($this->project . '/branchInfo'));
                }
            }
        }
        if (!empty($updateId)) {
            $data['updateData'] = $this->Common_model->get_single_data_by_single_column('branch', 'branch_id', $updateId);
        }
        $data['companyList'] = $this->Common_model->get_data_list_by_single_column('tbl_distributor', 'dist_id', $this->dist_id);
        $data['title'] = 'Branch Info';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $exitsBranch = $this->db->where('company_id', $this->dist_id)->count_all_results('branch') + 1;
        $data['branchCode'] = str_pad($exitsBranch, 4, "0", STR_PAD_LEFT);
        $data['branchList'] = $this->Common_model->getBranchInfo($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/setup/branch/branchInfo', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}