<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 4/26/2020
 * Time: 12:48 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductBarcodeController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $folder;
    public $folderSub;
    public $page_type;
    public $project;
    public $business_type;

    public function __construct()
    {

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
        $this->folderSub = 'distributor/inventory/product/';
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->business_type = $this->session->userdata('business_type');

        if ($this->business_type == "MOBILE") {
            $this->folder = 'distributor/masterTemplateSmeMobile';
            $this->folderSub = 'distributor/inventory/product_mobile/';
        }


        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    function productBarcode()
    {
        if (isPostBack()) {
            /*  echo '<pre>';
              print_r($_POST);
              exit;*/
            $category = $this->input->post('category');
            $productid = $this->input->post('productid');
            /*By Mamun*/
            $quantity = $this->input->post('quantity');
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
        $data['categoryList'] = $this->Common_model->getPublicProductCat($this->dist_id);
        /*page navbar details*/
        $data['title'] = get_phrase('Product Barcode');
        $data['quantity'] = $quantity;
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Product Barcode List');
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        /* print_r($data['quantity'] );
         exit();*/
        $data['mainContent'] = $this->load->view("distributor/inventory/product/barcode_new", $data, true);
        $this->load->view($this->folder, $data);
    }

    /**
     * @return mixed
     */
    public function createProductListForBarcode()
    {
        $category_id = $this->input->post('category_id');
        $productID = $this->input->post('productID');
        $qty = $this->input->post('qty');


        $this->db->select("*");
        $this->db->from("product");
        if ($productID != 'all') {
            $this->db->where('product_id', $productID);
        }
        if ($category_id != 'all') {
            $this->db->where('category_id', $category_id);
        }
        $result = $this->db->get()->result();
        $result_tr="";
        foreach ($result as $key => $row):
            $result_tr .= '<tr>';
            $result_tr .= '<td>'.$this->Common_model->tableRow('productcategory', 'category_id', $row->category_id)->title.'</td>';
            $result_tr .= '<td>' . $this->Common_model->tableRow('product', 'product_id', $row->product_id)->productName .'[ ' .$this->Common_model->tableRow('product', 'product_id', $row->product_id)->product_code .' ]'. '</td>';
            $result_tr .= '<td>' . $qty . '</td>';
            $result_tr .= '<td></td>';
            $result_tr .= '</tr>';
        endforeach;
        echo $result_tr;
    }
}