<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/7/2019
 * Time: 1:53 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class BrandController extends CI_Controller
{


    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;
    public $page_type;
    public $folder;
    public $folderSub;
    public $project;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Purchases_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->invoice_type = 1;
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/brand/';

        
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);


    }

    function brand()
    {
        $data['page_type'] = get_phrase($this->page_type);
        /*page navbar details*/
        $data['title'] = get_phrase('Brand List');

        $data['link_page_name'] = get_phrase('Brand Add');
        $data['link_page_url'] = $this->project.'/brandAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/

        $data['mainContent'] = $this->load->view($this->folderSub . 'brandList', $data, true);
        $this->load->view($this->folder, $data);
    }

    function brandEdit($updateId)
    {
        if (isPostBack()) {
            $this->form_validation->set_rules('brandName', 'Brand Name', 'required');
            if ($this->form_validation->run() == FALSE) {

                $msg = $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/brandEdit/' . $updateId));
            } else {
                $this->db->trans_start();
                $data['brandName'] = $this->input->post('brandName');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->update_data('brand', $data, 'brandId', $updateId);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE):
                    $msg = 'Brand ' . ' ' . $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project.'/brand/'));
                else:
                    $msg = 'Brand ' . ' ' . $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project.'/brandEdit/' . $updateId));
                endif;
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('Brand Edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Brand List');
        $data['link_page_url'] = $this->project.'/brand';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['page_type'] = $this->page_type;
        $data['brandList'] = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $updateId);
        $data['mainContent'] = $this->load->view($this->folderSub . 'brandEdit', $data, true);
        $this->load->view($this->folder, $data);
    }

    function brandAdd()
    {
        if (isPostBack()) {
//Validation Rules
            $this->form_validation->set_rules('brandName', 'Brand Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/brandAdd'));
            } else {
                $this->db->trans_start();
                $data['brandName'] = $this->input->post('brandName');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->insert_data('brand', $data);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE):
                    $msg = 'Brand ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project.'/brandAdd/'));
                else:
                    $msg = 'Brand ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project.'/brandAdd'));
                endif;
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('Brand Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Brand List');
        $data['link_page_url'] = $this->project.'/brand';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/

        $data['mainContent'] = $this->load->view($this->folderSub . 'brandAdd', $data, true);
        $this->load->view($this->folder, $data);
    }

    function deleteBrand($deletedId)
    {
        $inventoryCondition = array(
            'dist_id' => $this->dist_id,
            'brand_id' => $deletedId,
        );
        $exits = $this->Common_model->get_data_list_by_many_columns('product', $inventoryCondition);
        if (empty($exits)) {
            $this->db->trans_start();
            $condition = array(
                'dist_id' => $this->dist_id,
                'brandId' => $deletedId
            );
            $this->Common_model->delete_data_with_condition('brand', $condition);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE):
                $msg = 'Brand ' . ' ' . $this->config->item("delete_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/brand/'));
            else:
                $msg = 'Brand ' . ' ' . $this->config->item("delete_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project.'/brand'));
            endif;
        } else {

            $msg = 'This Brand can not be deleted.already have a product created by this brand ';
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project.'/brand'));
        }
    }
}