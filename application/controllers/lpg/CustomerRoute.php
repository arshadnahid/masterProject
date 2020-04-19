<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/28/2020
 * Time: 10:52 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerRoute extends CI_Controller
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


    public function route_info()
    {


        //$this->load->library('session');


        if (isPostBack()) {

            $this->db->trans_start();
            /*id	name	description	is_active	created_by	created_at	updated_by	updated_at*/
            $userdata = $this->session->userdata();

            $data['route_name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['is_active'] = $this->input->post('is_active');
            $data['created_by'] = $this->admin_id;
            $data['created_at'] = $this->timestamp;
            $insertId = $this->db->insert('route_info', $data);


            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = ' ' . ' ' . $this->config->item("save_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/route_info'));
            } else {
                $msg = ' ' . ' ' . $this->config->item("save_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/route_info/'));
            }

        }


        $data['title'] = 'Route Info';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        /*customer*/

        $data['routes'] = $this->db->where('is_active', 'Y')->get('route_info')->result();


        $data['mainContent'] = $this->load->view('distributor/sales/route/route_info', $data, true);

        $this->load->view('distributor/masterTemplate', $data);

    }

    function route_info_edit($route_id)
    {

        //$this->load->library('session');
        if (isPostBack()) {

            $this->db->trans_start();

            $route_id= $this->input->post('route_id');
            $userdata = $this->session->userdata();
            $data['route_name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $this->admin_id;
            $updatedId = $this->Common_model->update_data('route_info', $data, 'id', $route_id);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg =  $this->config->item("update_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/route_info'));
            } else {
                $msg =  $this->config->item("update_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/route_info/'));
            }

        }

        $data['title'] = 'Custromer Edit';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        /*customer*/


        $data['route_info'] = $this->db->where('is_active', 'Y')->where('id', $route_id)->get('route_info')->row();


        $data['mainContent'] = $this->load->view('distributor/sales/route/route_edit', $data, true);

        $this->load->view('distributor/masterTemplate', $data);


    }


}