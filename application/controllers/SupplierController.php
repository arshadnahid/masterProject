<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/6/2019
 * Time: 12:37 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class SupplierController extends CI_Controller
{

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $folder;
    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }
    function supplierList() {
        $data['page_type']=$this->page_type ;


        $data['title'] = 'Setup || Supplier';
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierList', $data, true);
        $this->load->view($this->folder, $data);
    }

    function Supplier() {
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);
        if (isPostBack()) {


            $this->form_validation->set_rules('supName', 'Supplier Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', "Product Package Can't Save.");
                redirect(site_url('Supplier111'));
            } else {
                $this->db->trans_start();
                $dataInsert['supID'] = $data['supplierID'];
                //$data['supID'] = $this->input->post('supplierId');
                $dataInsert['supName'] = $this->input->post('supName');
                $dataInsert['supEmail'] = $this->input->post('supEmail');
                $dataInsert['supPhone'] = $this->input->post('supPhone');
                $dataInsert['supAddress'] = $this->input->post('supAddress');
                $dataInsert['dist_id'] = $this->dist_id;
                $dataInsert['updated_by'] = $this->admin_id;
                $insertID = $this->Common_model->insert_data('supplier', $dataInsert);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $this->session->set_flashdata('error', "Product Package Can't Save.");

                    redirect(site_url('Supplier/' ));
                else:
                    $this->session->set_flashdata('success', "Product Package Save successfully.");
                    //message("Product Package Save successfully.");
                    redirect(site_url('supplierUpdate/' . $insertID));
                endif;
            }
        }
        $data['title'] = 'Setup || Supplier';

        $data['colorList'] = $this->Common_model->get_data_list('color', 'colorID', 'ASC');
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierAdd', $data, true);
        $this->load->view($this->folder, $data);
    }

    function supplierUpdate($updateid = null) {
        if (isPostBack()) {

            $this->form_validation->set_rules('supName', 'Supplier Name', 'required');

            if ($this->form_validation->run() == FALSE) {
                $m=validation_errors();

                $msg= $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url('supplierUpdate/' . $updateid));
            } else {
                $this->db->trans_start();
                $data['supID'] = $this->input->post('supplierId');
                $data['supName'] = $this->input->post('supName');
                $data['supEmail'] = $this->input->post('supEmail');
                $data['supPhone'] = $this->input->post('supPhone');
                $data['supAddress'] = $this->input->post('supAddress');

                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->update_data('supplier', $data, 'sup_id', $updateid);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg= 'Supplier '.' '.$this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url('Supplier/' ));
                else:
                    $msg= 'Supplier '.' '.$this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url('supplierUpdate/' . $updateid));
                endif;
            }
        }
        $data['title'] = 'Supplier || Edit';
        $data['supplierEdit'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $updateid);
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierEdit', $data, true);
        $this->load->view($this->folder, $data);
    }

    function checkDuplicateEmail() {
        $phone = $this->input->post('phone');
        if (!empty($phone)):
            $array = array(
                'supPhone' => $phone,
                'dist_id' => $this->dist_id,
            );
            $exitsSup = $this->Common_model->get_single_data_by_many_columns('supplier', $array);
            if (!empty($exitsSup)) {
                echo "1";
            } else {
                echo 2;
            }
        else:
            echo 2;
        endif;
    }
    function suplierStatusChange() {
        $this->db->trans_start();
        $supid = $this->input->post('supID');
        $data['status'] = $this->input->post('status');
        $this->Common_model->update_data('supplier', $data, 'sup_id', $supid);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg= 'Supplier Status'.' '.$this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
        else:
            $msg= 'Supplier Status'.' '.$this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
        endif;
        return 1;
    }





}
?>
