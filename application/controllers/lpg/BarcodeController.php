

<?php

/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 9:48 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class BarcodeController extends CI_Controller {

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
       
        $this->load->model('Barcode_Model');
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

        $this->page_type = 'BarCode';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/';
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

        $data['title'] = get_phrase('BarCode Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('BarCode List');
        $data['link_page_url'] = $this->project . '/';
        $data['link_icon'] = "<i class='fa fa-list'></i>";


        // $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        // $data['branch'] = $this->Common_model->branchList();

        $data['mainContent'] = $this->load->view('distributor/barCode/productBarcode', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

     function getProductList()
    {
        $cat_id = $this->input->post('cat_id');
        if ($cat_id != 'package') {
            $productList = $this->Common_model->getPublicProduct($this->dist_id, $cat_id);
            $add = '';
            if (!empty($productList)):
                $add .= "<option value=''></option>";
                foreach ($productList as $key => $value):
                    $add .= "<option  ispackage='0'  categoryName='" . $value->productCat . " '  categoryId='" . $value->category_id . "' productName='" . $value->productName . " [" . $value->brandName . "]' value='" . $value->product_id . "' >$value->productName  [" . $value->brandName . "]</option>";
                endforeach;
                echo $add;
                DIE;
            else:
                echo "<option value='' selected disabled>Product Not Available</option>";
                DIE;
            endif;
        } else if ($cat_id == 'package') {
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

    public function ProductCatBarcode()
    {

        if (isPostBack()) {
              // echo '<pre>';
              // print_r($_POST);
              // exit;
            $category = $this->input->post('productCatID')[0];
            $productid = $this->input->post('productID')[0];
            /*By Mamun*/
            $quantity = $this->input->post('quantity')[0];
            if ($category == 'all' && $productid == 'all') {
                $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
            } elseif ($category != 'all' && $productid == 'all') {
                $data['productList'] = $this->Common_model->getProductListByCategory($category, $this->dist_id);
            } else {
                $data['productList'] = $this->Common_model->get_data_list_by_single_column('product', 'product_id', $productid);
            }
            $productSelectList = $this->input->post('productId');
            $numberOfBarcode = 0;
            foreach ($productSelectList as $key => $eachProduct) {
                $numberOfBarcode = $numberOfBarcode + $quantity[$productInfo->product_id];
                $productInfo = $this->Common_model->getProductInfo($eachProduct);
                for ($i = 0; $i < $quantity[$productInfo->product_id]; $i++) {
                    $barcodes[] = $productInfo;
                }
            }
            if (!empty($productSelectList)) {
                $data['selectList'] = $productSelectList;
                $data['barcodes'] = $barcodes;
                $data['numberOfBarcode'] = $numberOfBarcode;
            }
        }

        /* page navbar details */
        $data['title'] = get_phrase('BarCode Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('BarCode List');
        $data['link_page_url'] = '';
        $data['link_icon'] = $this->link_icon_list;
        $data['productCat'] = $this->Barcode_Model->getPublicProductCat($this->dist_id);
        
        $data['mainContent'] = $this->load->view('distributor/barCode/productBarcode', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


}
