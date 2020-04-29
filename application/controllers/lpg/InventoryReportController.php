<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 8/4/2019
 * Time: 10:13 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class InventoryReportController extends CI_Controller {

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

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }

        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/cylinderInOut/';
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

    public function current_stock() {


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        if (isPostBack()) {
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $productId = $this->input->post('productId');
            $branch_id = $this->input->post('branch_id');
            //$this->Dashboard_Model->getinventoryAmount($searchDay='');
            $data['allStock'] = $this->Inventory_Model->current_stock($this->dist_id,$start_date,$end_date,$productId,$branch_id);
            /*echo '<pre>';
            echo $this->db->last_query();
            print_r($data['allStock']);exit;*/
            if ($productId != 'all') {
                $data['productOpening'] = $this->Inventory_Model->getProductLedgerOpening($start_date, $end_date, $productId, $this->dist_id);
            }
        }




        /*page navbar details*/
        $data['title'] = get_phrase('Current Stock  Report');
        $data['page_type']=get_phrase($this->page_type);
        /*page navbar details*/

        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/current_stock', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function productLedger() {
        if (isPostBack()) {
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $productId = $this->input->post('productId');
            $branch_id = $this->input->post('branch_id');
            $data['productLedger'] = $this->Inventory_Model->getProductLedger($start_date, $end_date, $productId, $this->dist_id,$branch_id);
            if ($productId != 'all') {
                $data['productOpening'] = $this->Inventory_Model->getProductLedgerOpening($start_date, $end_date, $productId, $this->dist_id);
            }
        }

        /*page navbar details*/
        $data['title'] = 'Product Ledger';
        $data['page_type']=$this->page_type;

        /*page navbar details*/
        $data['dist_id'] = $this->dist_id;
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/productledger', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function purchasesReport() {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Purchases Report';
        $data['title'] = 'Purchases || Report';
        /*page navbar details*/
        $data['title'] = 'Purchases Report';
        $data['page_type']=$this->page_type;
        $data['link_page_name']='';
        $data['link_page_url']='#';
        $data['link_icon']="";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/inventory/report/purchasesReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function productWisePurchasesReport() {

        /*page navbar details*/
        $data['title'] = 'Product Wise Purchases Report';
        $data['page_type']=$this->page_type;
        $data['link_page_name']='';
        $data['link_page_url']='#';
        $data['link_icon']="";
        $data['dist_id'] = $this->dist_id;
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/productWisePurchases', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function stockReport()
    {
        if (isPostBack()) {
            $categoryId = $this->input->post('categoryId');
            $brandId = $this->input->post('brandId');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['allStock'] = $this->Inventory_Model->getStock($this->dist_id, $start_date, $end_date, $categoryId, $brandId, 2);
            echo $this->db->last_query();
            die;
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Inventory Stock Report';
        $data['categoryList'] = $this->Common_model->getPublicProductCatbyCilinder($this->dist_id, 2);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        /*page navbar details*/
        $data['title'] = 'Stock Report';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Stock Report List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/inventory/report/mainStock', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
        $this->output->enable_profiler(false);
    }

    public function supplierPurchasesReport()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Supplier Purchases Report';
        $data['title'] = 'Supplier Purchases Report';
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supplierPurchasesReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    /**
     * @return mixed
     */
    public function current_stock_report()
    {

        if (isPostBack()) {
/*echo "<pre>";
print_r($_POST);*/

            $branch_id = $this->input->post('branch_id');
            $productCatagory = $this->input->post('category_id');
            $productBrand = $this->input->post('brandId');
            $productId = $this->input->post('productId');
            $subcategory = $this->input->post('subcategory');
            $color = $this->input->post('color');
            $size = $this->input->post('size');
            $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
            $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
            //$data['stock']=$this->Inventory_Model->stock_report($branch_id,$productCatagory,$productBrand,$productId,$startDate,$endDate);
            $data['stockBranch']=$this->Inventory_Model->stock_reportNew($branch_id,$productCatagory,$productBrand,$productId,$startDate,$endDate,$subcategory,$color,$size);

            //$data['stockEmptyCylinder']=$this->Inventory_Model->get_empty_cylinder_with_refill_with_out_refill($productCatagory,$productBrand,$productId,$startDate,$endDate);
/*echo '<pre>';
print_r($data['stockEmptyCylinder']);
exit;*/
        }
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = 'Stock Report';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";



        $data['subcategory'] = $this->db->where('IsActive', '1')->get('tb_subcategory')->result();
        $data['model'] = $this->db->where('IsActive', '1')->get('tb_model')->result();
        $data['color'] = $this->db->where('IsActive', '1')->get('tb_color')->result();
        $data['size'] = $this->db->where('IsActive', '1')->get('tb_size')->result();
        /*page navbar details*/
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/current_stock_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
        $this->output->enable_profiler(false);
    }
}