<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InvProductController extends CI_Controller
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

    function addProduct()
    {
        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('product_code', 'Product Code', 'required');
            $this->form_validation->set_rules('category_id', 'Product Category', 'required');
            $this->form_validation->set_rules('brand', 'Product Branch', 'required');
            $this->form_validation->set_rules('productName', 'Product Name', 'required');
            //$this->form_validation->set_rules('product_type_id', 'Product Type', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/addProduct'));
            } else {
                $productOrgId = $this->db->where('dist_id', $this->dist_id)
                        ->where('status', 1)
                        ->count_all_results('product') + 1;
                $productid = "PID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);
                $this->db->trans_start();
                $data['category_id'] = $this->input->post('category_id');
                $data['unit_id'] = $this->input->post('unit');
                // $data['product_code'] = $this->input->post('product_code');$productid
                $data['product_code'] = $productid;
                $data['productName'] = $this->input->post('productName');
                $data['purchases_price'] = $this->input->post('purchases_price');
                $data['salesPrice'] = $this->input->post('salesPrice');
                $data['retailPrice'] = $this->input->post('retailPrice');
                $data['brand_id'] = $this->input->post('brand');
                $data['alarm_qty'] = $this->input->post('alarm_qty');
                $data['product_type_id'] = $this->input->post('product_type_id');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;


                $data['subcategoryID'] = $this->input->post('subcategory');
                $data['modelID'] = $this->input->post('model');
                $data['colorID'] = $this->input->post('color');
                $data['sizeID'] = $this->input->post('size');
                $data['vat'] = $this->input->post('vat');
                $data['description'] = $this->input->post('description');


                $insertid = $this->Common_model->insert_data('product', $data);

                $category_id = $this->input->post('category_id');
                $checDamageStock = '';

                $DamageStock = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $category_id);
                if (!empty($DamageStock)) {
                    $checDamageStock = $DamageStock->title;
                }


                $brand_id = $this->input->post('brand');

                if ($category_id == 1) {
                    //Empty Cylinder
                    $ledger_parent_id = $this->config->item("New_Cylinder_Stock");//25,;
                } else if ($category_id == 2) {
                    $ledger_parent_id = $this->config->item("Refill_Stock");//26,;
                }else if($checDamageStock=="Damage Stock"){
                    $ledger_parent_id = 22;//21,;
                } else {
                    $ledger_parent_id = $this->config->item("Inventory_Finished_Goods_Stock");//21,;
                }
                $for = 1;
                $productcategory_info = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $category_id);
                $brand_info = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $brand_id);
                $unit_info = $this->Common_model->get_single_data_by_single_column('unit', 'unit_id', $this->input->post('unit'));
                $productName = $productcategory_info->title . ' ' . $this->input->post('productName') . ' ' . $unit_info->unitTtile . ' ' . $brand_info->brandName;
                create_ledger_cus_sup_product($insertid, $productName, $ledger_parent_id, $for, $this->admin_id);
                if ($category_id == 1) {
                    $ledger_parent_id = $this->config->item("Empty_Cylinder_Transfer");
                    $productName = "Transfer " . $productcategory_info->title . ' ' . $this->input->post('productName') . ' ' . $unit_info->unitTtile . ' ' . $brand_info->brandName;
                    create_ledger_cus_sup_product($insertid, $productName, $ledger_parent_id, 5, $this->admin_id);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg = 'product  ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/productList'));
                else:
                    $msg = 'Product   ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/addProduct'));
                endif;
            }
        }
        $productOrgId = $this->db->where('dist_id', $this->dist_id)->or_where('dist_id', 1)->count_all_results('product') + 1;
        $data['productid'] = "PID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);
        /*page navbar details*/



        $data['subcategory'] = $this->db->where('IsActive', '1')->get('tb_subcategory')->result();
        $data['model'] = $this->db->where('IsActive', '1')->get('tb_model')->result();
        $data['color'] = $this->db->where('IsActive', '1')->get('tb_color')->result();
        $data['size'] = $this->db->where('IsActive', '1')->get('tb_size')->result();

        $data['title'] = get_phrase('Add Product');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Product List');
        $data['link_page_url'] = $this->project . '/productList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['product_type_list'] = $this->Common_model->getProductType($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);


        $data['mainContent'] = $this->load->view($this->folderSub . 'addNewProduct', $data, true);


        $this->load->view($this->folder, $data);
    }

    function checkDuplicateProduct()
    {
        $productName = $this->input->post('productName');
        $productCategory = $this->input->post('productCategory');
        $brandId = $this->input->post('brandId');
        $productId = $this->input->post('productId');
        if (!empty($productId)):
            $productNameExits = $this->Common_model->checkDuplicateModel($this->dist_id, $productName, $productCategory, $brandId, $productId);
        else:
            $productNameExits = $this->Common_model->checkDuplicateModel($this->dist_id, $productName, $productCategory, $brandId);
        endif;
        if (!empty($productNameExits)) {
            echo 1;
        }
    }

    function productList()
    {
        $data['page_type'] = $this->page_type;
        /*page navbar details*/
        $data['title'] = get_phrase('Product List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Product');
        $data['link_page_url'] = $this->project . '/addProduct';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view($this->folderSub . 'productList', $data, true);
        $this->load->view($this->folder, $data);
    }

    function productStatusChange()
    {
        $product = $this->input->post('productid');
        $data['status'] = $this->input->post('status');
        $this->db->trans_start();
        $this->Common_model->update_data('product', $data, 'product_id', $product);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = 'Product  ' . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
        else:
            $msg = 'Product  ' . ' ' . $this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
        endif;
        echo 1;
    }

    function updateProduct($productid)
    {
        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('product_code', 'Product Code', 'required');
            $this->form_validation->set_rules('category_id', 'Product Category', 'required');
            $this->form_validation->set_rules('brand', 'Product Branch', 'required');
            $this->form_validation->set_rules('productName', 'Product Name', 'required');
            //$this->form_validation->set_rules('product_type_id', 'Product Type', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/updateProduct/' . $productid));
            } else {
                $this->db->trans_start();
                $groupId = $this->input->post('groupId');
                $data['category_id'] = $this->input->post('category_id');
                $data['unit_id'] = $this->input->post('unit');
                $data['product_code'] = $this->input->post('product_code');
                $data['productName'] = $this->input->post('productName');
                $data['purchases_price'] = $this->input->post('purchases_price');
                $data['salesPrice'] = $this->input->post('salesPrice');
                $data['retailPrice'] = $this->input->post('retailPrice');
                $data['brand_id'] = $this->input->post('brand');
                $data['product_type_id'] = $this->input->post('product_type_id');
                $data['alarm_qty'] = $this->input->post('alarm_qty');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $updateid = $this->Common_model->update_data('product', $data, 'product_id', $productid);


                $category_id = $this->input->post('category_id');
                $brand_id = $this->input->post('brand');

                $checDamageStock = '';

                $DamageStock = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $category_id);
                if (!empty($DamageStock)) {
                    $checDamageStock = $DamageStock->title;
                }

                if ($category_id == 1) {
                    //Empty Cylinder
                    $ledger_parent_id = $this->config->item("New_Cylinder_Stock");//25,;
                } else if ($category_id == 2) {
                    $ledger_parent_id = $this->config->item("Refill_Stock");//26,;
                } else if($checDamageStock=="Damage Stock"){
                    $ledger_parent_id = 22;//21,;
                }else {
                    $ledger_parent_id = $this->config->item("Inventory_Finished_Goods_Stock");//21,;
                }
                $for = 1;
                $productcategory_info = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $category_id);
                $brand_info = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $brand_id);
                $productName = $productcategory_info->title . ' ' . $this->input->post('productName') . ' ' . $brand_info->brandName;
                //create_ledger_cus_sup_product($updateid, $productName, $ledger_parent_id, $for,$this->admin_id);
                update_ledger_cus_sup_product($updateid, $productName, $ledger_parent_id, $for, $this->admin_id);
                if ($category_id == 1) {
                    $ledger_parent_id = $this->config->item("Empty_Cylinder_Transfer");
                    $productName = " Transfer " . $productName;
                    //create_ledger_cus_sup_product($updateid, $productName, $ledger_parent_id, 5,$this->admin_id);
                    update_ledger_cus_sup_product($updateid, $productName, $ledger_parent_id, 5, $this->admin_id);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg = 'Product  ' . ' ' . $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/updateProduct/' . $productid));
                else:
                    $msg = 'Product  ' . ' ' . $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/productList'));
                endif;
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('Update Product');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Product List');
        $data['link_page_url'] = $this->project . '/productList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['productEdit'] = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $productid);
        $data['product_type_list'] = $this->Common_model->getProductType($this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['mainContent'] = $this->load->view($this->folderSub . 'editProduct', $data, true);
        $this->load->view($this->folder, $data);
    }

    function productBarcode1()
    {
        if (isPostBack()) {
            $category = $this->input->post('category');
            $productid = $this->input->post('productid');
            if ($category == 'all' && $productid == 'all') {
                $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
            } elseif ($category != 'all' && $productid == 'all') {
                $data['productList'] = $this->Common_model->getProductListByCategory($category, $this->dist_id);
            } else {
                $data['productList'] = $this->Common_model->get_data_list_by_single_column('product', 'product_id', $productid);
            }
            $productSelectList = $this->input->post('productId');
            $quantity = $this->input->post('quantity');
            if (!empty($productSelectList)) {
                $data['selectList'] = $productSelectList;
                $data['quantity'] = $quantity;
            }
        }
        $data['categoryList'] = $this->Common_model->getPublicProductCat($this->dist_id);
        /*page navbar details*/
        $data['title'] = get_phrase('Product Barcode');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Product Barcode List');
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view($this->folderSub . 'barcode', $data, true);
        $this->load->view($this->folder, $data);
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
        $data['mainContent'] = $this->load->view($this->folderSub . 'barcode', $data, true);
        $this->load->view($this->folder, $data);
    }

    function getProductListForBarcode()
    {
        $cat_id = $this->input->post('cat_id');
        $productList = $this->Common_model->getPublicProduct($this->dist_id, $cat_id);
        $add = '';
        if (!empty($productList)):
            $add .= "<option value=''></option>";
            $add .= "<option selected value='all'>All</option>";
            foreach ($productList as $key => $value):
                $add .= "<option productName='" . $value->productName . ' [ ' . $value->brandName . ' ]' . "'   value='" . $value->product_id . "'>$value->productName [ $value->brandName ] </option>";
            endforeach;
            echo $add;
            DIE;
        else:
            echo "<option value='all' selected>All</option>";
            DIE;
        endif;
    }

    public function productTypeList()
    {
        /*page navbar details*/
        $data['title'] = 'Product Type List';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Product Type Add';
        $data['link_page_url'] = $this->project . '/addproductType';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['pageName'] = 'productTypeList';
        $data['mainContent'] = $this->load->view($this->folderSub . 'productTypeList', $data, true);
        $this->load->view($this->folder, $data);
    }

    public function productType()
    {
        if (isPostBack()) {
            //validation rules
            $this->form_validation->set_rules('product_type_name', 'Product Type Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/addproductType'));
            } else {
                $this->db->trans_start();
                $data['product_type_name'] = $this->input->post('product_type_name');
                $data['description'] = $this->input->post('description');
                $data['is_active'] = $this->input->post('is_active');
                $data['dist_id'] = $this->dist_id;
                $data['insert_by'] = $this->admin_id;
                $data['insert_date'] = $this->timestamp;
                $data['company_id'] = 0;
                $data['branch_id'] = 0;
                $insertid = $this->Common_model->insert_data('product_type', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg = 'Product Type ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/productType'));
                else:
                    $msg = 'Product Type ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/addproductType'));
                endif;
            }
        }
        /*page navbar details*/
        $data['title'] = 'Add Product Type';
        $data['page_type'] = $this->page_type;
        $data['pageName'] = 'addProductType';
        $data['mainContent'] = $this->load->view($this->folderSub . 'addProductType', $data, true);
        $this->load->view($this->folder, $data);
    }

    function checkDuplicateProductType()
    {
        $product_type_name = $this->input->post('product_type_name');
        $product_type_id = $this->input->post('product_type_id');
        if (!empty($product_type_id)):
            $productTypeExits = $this->Common_model->checkDuplicateProductType($this->dist_id, $product_type_name, $product_type_id);
        else:
            $productTypeExits = $this->Common_model->checkDuplicateProductType($this->dist_id, $product_type_name);
        endif;
        if (!empty($productTypeExits)) {
            echo 1;
        }
    }

    public function editProductType($product_type_id)
    {
        if (isPostBack()) {
            $this->form_validation->set_rules('product_type_name', 'Product Type Name', 'required');
            $this->form_validation->set_rules('product_type_id', 'Product Type Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url('editProductType/' . $product_type_id));
            } else {
                $this->db->trans_start();
                $data['product_type_name'] = $this->input->post('product_type_name');
                $data['description'] = $this->input->post('description');
                $data['is_active'] = $this->input->post('is_active');
                $data['dist_id'] = $this->dist_id;
                $data['insert_by'] = $this->admin_id;
                $data['insert_date'] = $this->timestamp;
                $data['company_id'] = 0;
                $data['branch_id'] = 0;
                $insertid = $this->Common_model->update_data('product_type', $data, 'product_type_id', $product_type_id);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg = 'Product Type ' . ' ' . $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url('editproductType/' . $product_type_id));
                else:
                    $msg = 'Product Type ' . ' ' . $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url('productType'));
                endif;
            }
        }
        $data['product_type'] = $this->Common_model->get_single_data_by_single_column('product_type', 'product_type_id', $product_type_id);
        $data['pageName'] = 'editProductType';
        /*page navbar details*/
        $data['title'] = 'Edit product Type';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'product type list';
        $data['link_page_url'] = 'productType';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view($this->folderSub . 'editProductType', $data, true);
        $this->load->view($this->folder, $data);
    }

    function productTypeStatusChange()
    {
        $this->db->trans_start();
        $product_type_id = $this->input->post('product_type_id');
        $data['is_active'] = $this->input->post('status') == 1 ? 'Y' : 'N';
        $data['update_by'] = $this->admin_id;
        $data['update_date'] = $this->timestamp;
        $this->Common_model->update_data('product_type', $data, 'product_type_id', $product_type_id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = 'Product Type ' . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url('productType'));
        else:
            $msg = 'Product Type ' . ' ' . $this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url('productType'));
        endif;
        echo 1;
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

    function getProductPriceForSale()
    {
        $product_id = $this->input->post('product_id');
        $productDetails = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $product_id);
        if (!empty($productDetails->salesPrice)):
            echo $productDetails->salesPrice;
        else:
            echo '';
        endif;
    }

    function getProductPriceForPurchase()
    {
        $product_id = $this->input->post('product_id');
        $productDetails = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $product_id);
        if (!empty($productDetails->purchases_price)):
            echo $productDetails->purchases_price;
        else:
            echo '';
        endif;
    }

    function getProductStock()
    {
        //log_message('error','getProductStock POST '.print_r($_POST,true));
        $product_id = $this->input->post('product_id');
        $category_id = $this->input->post('category_id');
        $ispackage = $this->input->post('ispackage');
        $branchId = $this->input->post('branchId');
        $productStock = $this->Sales_Model->getProductStock($product_id, $category_id, $ispackage, $branchId);
        if (!empty($productStock) && $productStock > 0):
            echo $productStock;
        endif;
    }
}
