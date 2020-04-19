<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController extends CI_Controller {

    private $timestamp;
    public $admin_id;
    public $dist_id;
public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Dashboard_Model');
        $thisdis = $this->session->userdata('dis_id');
        $admin_id = $this->session->userdata('admin_id');
        if (empty($thisdis) || empty($admin_id)) {
            redirect(site_url('DistributorDashboard'));
        }
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');

$this->project = $this->session->userdata('project');
         $this->db_hostname = $this->session->userdata('db_hostname');
                $this->db_username = $this->session->userdata('db_username');
                $this->db_password = $this->session->userdata('db_password');
                $this->db_name = $this->session->userdata('db_name');
                $this->db->close();
                $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
                $this->db = $this->load->database($config_app, TRUE);
    }



    function deleteProductType($product_type_id) {
        $inventoryCondition = array(
            'dist_id' => $this->dist_id,
            'product_type_id' => $deletedId,
        );
        $data['is_active'] = 'N';
        $data['is_delete'] = 'Y';
        $data['update_by'] = $this->admin_id;
        $data['update_date'] = $this->timestamp;
        $result = $this->Common_model->update_data_by_dist_id('product_type', $data, 'product_type_id', $product_type_id, $this->dist_id);
        if ($result != 0) {
            message("Your data successully deleted from database.");
        } else {
            exception("This Product Type can't be deleted.already have a transaction!");
        }
        redirect(site_url('productType'));
    }


    

}
