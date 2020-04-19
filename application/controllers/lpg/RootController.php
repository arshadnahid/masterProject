<?php

/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 9:48 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class RootController extends CI_Controller
{

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;
    public $page_type;
    public $folder;
    public $folderSub;
    public $link_icon_add;
    public $link_icon_list;
    public $project;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Incentive_Model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Root_Model');
        //$this->load->model('Datatable');
        $this->load->library('pagination');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }

        $this->page_type = 'Incentive';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/InventoryAdjustment/';
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

    public function root_info()
    {
        $data['title'] = 'Root Info';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        $data['root_info'] = $this->db->where('is_delete', 1)->get('root_info')->result();
        $data['mainContent'] = $this->load->view('distributor/sales/rootInfo/root_info', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }


    public function root_info_insert()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|is_unique[root_info.name]');
        if ($this->form_validation->run() === FALSE) {
            $msg = "Required field can't be empty Or Not Same Value";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/root_info'));
        } else {
            $this->db->trans_start();
            $data['name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['created_by'] = $this->admin_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['is_delete'] = 1;
            $data['is_active'] = 1;
            $this->db->insert('root_info', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = "Your data can't be inserted";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/root_info'));
            } else {
                $msg = "Your data successfully inserted into database";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/root_info'));

            }
        }

    }

    public function root_info_delete($deleteId)
    {
        $data['is_delete'] = 0;
        $deleteInfo = $this->Common_model->update_data('root_info', $data, 'root_id', $deleteId);
        message("Your data successfylly deleted from database.");
        redirect(site_url($this->project . '/root_info'));
    }

    public function root_info_edit($user_id)
    {
        $data['title'] = 'Root Edit';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        $data['root_info'] = $this->db->where('root_id', $user_id)->get('root_info')->row();
        $data['mainContent'] = $this->load->view('distributor/sales/rootInfo/root_info_edit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function root_info_update()
    {
        //$root_id = $this->input->post('root_id');
        //$update_data['root_info'] = $this->db->where('root_id', $root_id)->get('root_info')->row();

        //$this->form_validation->set_rules('name', 'Name', 'required|is_unique[root_info.name]');
        $this->form_validation->set_rules('name', 'Name', 'required');


        if ($this->form_validation->run() === FALSE) {
            $msg = "Required field can't be empty Or Not Updated Value";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/root_info'));
        } else {
            $this->db->trans_start();
            $root_id = $this->input->post('root_id');
            $data['updated_by'] = $this->admin_id;
            $data['name'] = $this->input->post('name');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['description'] = $this->input->post('description');
            $data['is_delete'] = 1;
            $data['is_active'] = 1;
            $this->Common_model->update_data('root_info', $data, 'root_id', $root_id);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = "Your data can't be Updated";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/root_info'));
            } else {
                $msg = "Your data successfully Updated into database";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/root_info'));

            }
        }


    }

    function status($id)
    {

        $result = $this->Root_Model->unpublished_root_info($id);

        if ($result) {
            $this->session->set_flashdata('message', 'Info change Sucessfully');
            redirect(site_url($this->project . '/root_info'));
        } else {
            $this->session->set_flashdata('message', 'Info change Failed');
            redirect(site_url($this->project . '/root_info'));
        }
    }

    function status2($id)
    {

        $result = $this->Root_Model->published_root_info($id);

        if ($result) {
            $this->session->set_flashdata('message', 'Info change Sucessfully');
            redirect(site_url($this->project . '/root_info'));
        } else {
            $this->session->set_flashdata('message', 'Info change Failed');
            redirect(site_url($this->project . '/root_info'));
        }
    }


}
