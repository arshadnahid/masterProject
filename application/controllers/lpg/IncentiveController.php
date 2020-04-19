

<?php

/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 9:48 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class IncentiveController extends CI_Controller {

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
        $this->load->model('Incentive_Model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
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

      public function index() {


        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['title'] = get_phrase('Incentive Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Incentive List');
        $data['link_page_url'] = $this->project . '/InventoryAdjustmentList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";


        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['branch'] = $this->Common_model->branchList();

        $data['mainContent'] = $this->load->view('distributor/Incentive/inventoryNewAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

     function getBrandList()
    {
        $brandId= $this->input->post('brandId');
        if ($brandId != 'package') {
            $productList = $this->Common_model->getPublicProduct($this->dist_id, $brandId);
            $add = '';
            if (!empty($productList)):
                $add .= "<option value=''></option>";
                foreach ($productList as $key => $value):
                    $add .= "<option  ispackage='0' brandName='" . $value->brandName . " '  brandId='" . $value->brand_id . "'  categoryName='" . $value->productCat . " '  categoryId='" . $value->category_id . "' productName='" . $value->productName . " [" . $value->brandName . "]' value='" . $value->product_id . "' >$value->productName  [" . $value->brandName . "]</option>";
                endforeach;
                echo $add;
                DIE;
            else:
                echo "<option value='' selected disabled>Product Not Available</option>";
                DIE;
            endif;
        } else if ($brandId == 'package') {
            $productList = $this->Common_model->getPublicPackageList($this->dist_id);
            $add = '';
            if (!empty($productList)):
                $add .= "<option value=''></option>";
                foreach ($productList as $key => $value):
                    $add .= "<option  ispackage='1'    categoryId='" . '2' . "' product_id='" . $value->product_id . "' value='" . $value->package_id . "' >$value->package_name  </option>";
                endforeach;
                echo $add;
                DIE;
            else:
                echo "<option value='' selected disabled>Product Not Available</option>";
                DIE;
            endif;
        }
    }

     public function inventoryNewAdd()
    {

          if (isPostBack()) {


            $this->form_validation->set_rules('startDate', 'Date', 'required');


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/inventoryNewAdd/'));
            } else {
                $this->db->from('incentive_info');
                $query = $this->db->get();
                $rowcount = $query->num_rows();

                $totalInvoice = $rowcount;


                $invoice_no = "INCENTIVE" . date('y') . date('m') . str_pad(($totalInvoice) + 1, 4, "0", STR_PAD_LEFT);

                $this->db->trans_start();
                $data = array();
                $data['insert_date'] = $this->timestamp;
                $data['invoice_no'] = $invoice_no;
                $data['company_id'] = 2;
                $data['dist_id'] = 2;
                $data['from_date'] = date('Y-m-d', strtotime($this->input->post('startDate')));
                $data['to_date'] = date('Y-m-d', strtotime($this->input->post('endDate')));
                $data['insert_by'] = $this->admin_id;
                $data['is_active'] = 'Y';
                $id = $this->Common_model->insert_data('incentive_info', $data);

                if ($id) {
                    $last_inserted_id = $id;

                    $inventory_details = array();
                    if (!empty($_POST['productID'])) {
                        foreach ($_POST['productID'] as $key => $value) {
                            $inventory = array();
                            $inventory['incentive_info_id'] = $last_inserted_id;
                            $inventory['product_id'] = $_POST['productID'][$key];
                            $inventory['quantity'] = $_POST['quantity'][$key];
                            $inventory['brand_id'] = $_POST['productBrandID'][$key];;
                            $inventory['insert_date'] = $this->timestamp;
                            $inventory['insert_by'] = $this->admin_id;
                            $inventory['is_active'] = 'Y';
                            $inventory['CHILD_ID'] = $_POST['productID'][$key];
                            $inventory_details[] = $inventory;
                        }
                    }
                }

                $this->db->insert_batch('incentive_details', $inventory_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Incentive ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/inventoryNewAdd'));
                } else {
                    $msg = 'Insentive  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/inventoryNewAdd'));
                }
            }
        }

        /* page navbar details */
        $data['title'] = get_phrase('Incentive Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Incentive List');
        $data['link_page_url'] = $this->project . '/newProductAddList';
        $data['link_icon'] = $this->link_icon_list;

        $data['productBrand'] = $this->Incentive_Model->getPublicProductBrand($this->dist_id);

        $data['mainContent'] = $this->load->view('distributor/Incentive/inventoryNewAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function newProductAddList(){
        $data['title'] = get_phrase('Incentive List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Incentive Add');
        $data['link_page_url'] = $this->project . '/inventoryNewAdd';
        $data['second_link_page_name'] = get_phrase('Incentive');
        $data['second_link_page_url'] = $this->project . '/IncentiveCheck';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['newProduct'] = $this->Incentive_Model->getAllNewProduct();
        $data['salesDetails'] = $this->Incentive_Model->saleDetailsProduct();


        $data['mainContent'] = $this->load->view('distributor/Incentive/newProductAddList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
     function invoiceView($id) {
        $data['invoice'] = $this->Incentive_Model->getAllProductById($id);
//         echo "<pre>";
//         $data = $this->db->last_query();
//         print_r($data);
//         exit;
        $data['title'] = get_phrase('Incentive View');
        $data['page_type'] = get_phrase('Configuration');
        $data['link_page_name'] = get_phrase('Inentive List');
        $data['link_page_url'] = $this->project . '/newProductAddList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['second_link_page_name'] = get_phrase('Incentive');
        $data['second_link_page_url'] = $this->project . '/IncentiveCheck';
        $data['second_link_icon'] = $this->link_icon_list;

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);



        $data['mainContent'] = $this->load->view('distributor/Incentive/newProductView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function  deleteNew($id){
        $result = $this->Incentive_Model->delete_New($id);
        if ($result) {
            $this->session->set_flashdata('message', 'Info Deleted Sucessfully');
            redirect(site_url($this->project . '/newProductAddList'));
        } else {
            $this->session->set_flashdata('message', 'Info Deleted Failed');
             redirect(site_url($this->project . '/newProductAddList'));
        }
    }
    function  newProductDelete($id){
        $result = $this->db->delete('incentive_details', array('incentive_info_id' => $id));
        $result2 = $this->db->delete('incentive_info', array('incentive_info_id' => $id));

        if (!empty($result)) {

            message("Product successfully deleted.");
            redirect(site_url($this->project . '/newProductAddList'));
        } else {

            exception("You have made no change to deleted.");
            redirect(site_url($this->project . '/newProductAddList'));
        }
        if (!empty($result2)) {

            message("Product successfully deleted.");
            redirect(site_url($this->project . '/newProductAddList'));
        } else {

            exception("You have made no change to deleted.");
            redirect(site_url($this->project . '/newProductAddList'));
        }
    }

    function editNewProduct($id){

        if (isPostBack()) {
            $this->form_validation->set_rules('purchasesDate', 'Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/inventoryNewAdd/'));
            } else {

                $this->db->trans_start();
                $data = array();
                //$data['brandId'] = $this->input->post('productBrandID');
                $data['update_date'] = $this->timestamp;
                $data['company_id'] = 2;
                $data['dist_id'] = 2;
                $data['from_date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $data['to_date'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
                $data['insert_by'] = $this->admin_id;
                $data['is_active'] = 'Y';
                $this->Common_model->update_data('incentive_info', $data, 'incentive_info_id', $id);

                  $allDataUpdate = array();
                  $allDatainsert = array();
                $productcheck = $this->input->post('productID');
                $brandUpdate = $this->input->post('brandUpdate');

               $this->db->where('incentive_info_id', $id);
               $del=$this->db->delete('incentive_details');

                foreach ($brandUpdate as $key => $value) {
                $paymentCrCondition = array(
                    'incentive_info_id' => $id,
                    //'CHILD_ID' => $this->input->post('brandUpdate'),
                );
                            $jv['incentive_info_id'] = $id;
                            $jv['product_id'] = $_POST['productID'][$key];
                            $jv['quantity'] = $_POST['quantity'][$key];
                            $jv['brand_id'] = $_POST['productBrandID'][$key];;
                            $jv['insert_date'] = $this->timestamp;
                            $jv['insert_by'] = $this->admin_id;
                            $jv['is_active'] = 'Y';
              //  $dr['CHILD_ID'] = $this->input->post('productID');

                $this->Common_model->save_and_check('incentive_details', $jv, $paymentCrCondition);
                }
                foreach ($productcheck as $key => $value) {
                    $costCondition = array(

                        'incentive_info_id' => $id,
                       //  'CHILD_ID' => $value,
                    );
                    $checkArray = $this->Common_model->get_single_data_by_many_columns('incentive_details', $costCondition);

                    if (!empty($checkArray)) {
                            $inv['incentive_info_id'] = $id;
                            $inv['product_id'] = $_POST['productID'][$key];
                            $inv['quantity'] = $_POST['quantity'][$key];
                            $inv['brand_id'] = $_POST['productBrandID'][$key];;
                            $inv['update_date'] = $this->timestamp;
                            $inv['insert_by'] = $this->admin_id;
                            $inv['is_active'] = 'Y';
                            $allDataUpdate[] = $inv;
                             unset($inv);
                   } else{
                            $inv['incentive_info_id'] = $id;
                            $inv['product_id'] = $_POST['productID'][$key];
                            $inv['quantity'] = $_POST['quantity'][$key];
                            $inv['brand_id'] = $_POST['productBrandID'][$key];;
                            $inv['insert_date'] = $this->timestamp;
                            $inv['insert_by'] = $this->admin_id;
                            $inv['is_active'] = 'Y';
                            $allDatainsert[] = $inv;
                            unset($inv);
                }
                }
                $this->db->where('incentive_info_id', $id);
                $this->db->update_batch('incentive_details', $allDataUpdate, 'incentive_info_id');

                $this->db->insert_batch('incentive_details', $allDatainsert);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Incentive Product Not save ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/newProductAddList'));
                } else {
                    $msg = 'Incentive Product Save' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/newProductAddList'));
                }
            }
        }
         $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 10,
        );
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['productBrand'] = $this->Incentive_Model->getPublicProductBrand($this->dist_id);
        $totalAdjustment = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['invoice'] = $this->Incentive_Model->getAllProductById($id);
        $data['title'] = get_phrase('Incentive List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase(' Incentive List');
        $data['link_page_url'] = $this->project . '/newProductAddList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['second_link_page_name'] = get_phrase('Incentive Check');
        $data['second_link_page_url'] = $this->project . '/IncentiveCheck';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['mainContent'] = $this->load->view('distributor/Incentive/editNewProduct', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function IncentiveCheck(){
        $data['title'] = get_phrase('Incentive Check List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Incentive Check List');
        $data['link_page_url'] = $this->project . '/newProductAddList';
        $data['incentivelist'] = $this->Incentive_Model->getAllIncentiveList();
      //  $data['data'] = json_encode($data['incentivelist']);
        $data['mainContent'] = $this->load->view('distributor/Incentive/IncentiveCheck', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


}
