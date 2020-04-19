<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/7/2019
 * Time: 3:00 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class UnitController extends CI_Controller
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
        $this->folderSub = 'distributor/inventory/unit/';

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }


    function unit() {

        $data['page_type'] =  get_phrase($this->page_type);
   
        /*page navbar details*/
        $data['title'] = get_phrase('Unit List');

        $data['link_page_name']=get_phrase('Unit Add');
        $data['link_page_url']=$this->project.'/unitAdd';
        $data['link_icon']="<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view($this->folderSub.'unit', $data, true);
        $this->load->view($this->folder, $data);
    }

    function unitAdd() {
        if (isPostBack()) {
// unitTtile
            $this->form_validation->set_rules('unitTtile', 'Unit Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg= $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/unitAdd'));
            } else {
                $this->db->trans_start();
                $data['unitTtile'] = $this->input->post('unitTtile');
                $data['code'] = $this->input->post('code');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->insert_data('unit', $data);
                $this->db->trans_complete();


                if ($this->db->trans_status() === FALSE):
                    $msg= 'Product Unit '.' '.$this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project.'/unitAdd/' ));
                else:
                    $msg= 'Product Unit '.' '.$this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project.'/unitEdit/' . $insertid));
                endif;
            }
        }
        
        /*page navbar details*/
        $data['title'] = 'Unit Add';
        $data['page_type']=$this->page_type;
        $data['link_page_name']='Unit List';
        $data['link_page_url']=$this->project.'/unit';
        $data['link_icon']="<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view($this->folderSub.'unitAdd', $data, true);
        $this->load->view($this->folder, $data);
    }

    function unitEdit($updatedid) {
        if (isPostBack()) {
            $this->form_validation->set_rules('unitTtile', 'Unit Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project.'/unitEdit/' . $updatedid));
            } else {
                $this->db->trans_start();
                $data['unitTtile'] = $this->input->post('unitTtile');
                $data['code'] = $this->input->post('code');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->update_data('unit', $data, 'unit_id', $updatedid);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg= 'Product Unit '.' '.$this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project.'/unitEdit/'.$updatedid ));
                else:
                    $msg= 'Product Unit '.' '.$this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project.'/unit'  ));
                endif;
            }
        }
        $data['unitList'] = $this->Common_model->get_single_data_by_single_column('unit', 'unit_id', $updatedid);
        $data['title'] = 'Unit Edit';
        $data['page_type'] =  $this->page_type;
        $data['mainContent'] = $this->load->view($this->folderSub.'unitEdit', $data, true);
        $this->load->view($this->folder, $data);
    }

    function deleteUnit($deletedId) {
        $inventoryCondition = array(
            'dist_id' => $this->dist_id,
            'unit' => $deletedId,
        );

        $exits = $this->Common_model->get_data_list_by_many_columns('stock', $inventoryCondition);
        if (empty($exits)) {
            $condition = array(
                'dist_id' => $this->dist_id,
                'unit_id' => $deletedId
            );
            $this->db->trans_start();
            $this->Common_model->delete_data_with_condition('unit', $condition);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE):
                $msg= 'Product Unit '.' '.$this->config->item("delete_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/unit' ));
            else:
                $msg= 'Product Unit '.' '.$this->config->item("delete_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project.'/unit'  ));
            endif;
        } else {
            $msg= "This Category can't be deleted.already have a product created by this category";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project.'/unit'));
        }
    }
}