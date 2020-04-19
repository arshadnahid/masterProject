<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class InventoryController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
    }

    public function lowInventoryReport() {
        $data['title'] = 'Low Inventory Report';
        $data['dist_id'] = $this->dist_id;
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/lowInventoryReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function productWisePurchasesReport() {
        $data['title'] = 'Product Wise Purchases Report';
        $data['dist_id'] = $this->dist_id;
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/productWisePurchases', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function checkDuplicateProductUpdate() {
        $productName = $this->input->post('productName');
        $productId = $this->input->post('productId');
        $this->db->select("productName,product_id,dist_id");
        $this->db->from('product');
        $this->db->where('productName', $productName);
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('product_id !=', $productId);
        $result = $this->db->get()->row();
        if (empty($result)) {
            echo 1;
        } else {
            echo 2;
        }
    }

    function productList() {
        $data['title'] = 'Product List';
       
        $data['mainContent'] = $this->load->view('distributor/inventory/product/productList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function updateProduct($productid) {

        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('product_code', 'Product Code', 'required');
            $this->form_validation->set_rules('category_id', 'Product Category', 'required');
            $this->form_validation->set_rules('brand', 'Product Branch', 'required');
            $this->form_validation->set_rules('productName', 'Product Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('updateProduct/' . $productid));
            } else {

                $data['category_id'] = $this->input->post('category_id');
                $data['product_code'] = $this->input->post('product_code');
                $data['productName'] = $this->input->post('productName');
                $data['purchases_price'] = $this->input->post('purchases_price');
                $data['salesPrice'] = $this->input->post('salesPrice');
                $data['retailPrice'] = $this->input->post('retailPrice');
                $data['brand_id'] = $this->input->post('brand');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $updateid = $this->Common_model->update_data('product', $data, 'product_id', $productid);
                if (!empty($updateid)):
                    message("Product update successfully.");
                    redirect(site_url('productList'));
                else:
                    message("Product Can't update.");
                    redirect(site_url('updateProduct/' . $productid));
                endif;
            }
        }
        $data['title'] = 'Update Product';
        $data['productEdit'] = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $productid);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/product/editProduct', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function addProduct() {

        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('product_code', 'Product Code', 'required');
            $this->form_validation->set_rules('category_id', 'Product Category', 'required');
            $this->form_validation->set_rules('brand', 'Product Branch', 'required');
            $this->form_validation->set_rules('productName', 'Product Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('addProduct'));
            } else {
                $data['category_id'] = $this->input->post('category_id');
                $data['product_code'] = $this->input->post('product_code');
                $data['productName'] = $this->input->post('productName');
                $data['purchases_price'] = $this->input->post('purchases_price');
                $data['salesPrice'] = $this->input->post('salesPrice');
                $data['retailPrice'] = $this->input->post('retailPrice');
                $data['brand_id'] = $this->input->post('brand');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->insert_data('product', $data);
                if (!empty($insertid)):
                    message("New product inserted successfully.");
                    redirect(site_url('productList'));
                else:
                    message("Product Can't inserted.");
                    redirect(site_url('addProduct'));
                endif;
            }
        }
        $productOrgId = $this->db->where('dist_id', $this->dist_id)->count_all_results('product') + 1;
        $data['productid'] = "PID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);
        $data['title'] = 'Add Product';
// $data['supplierList'] = $this->Common_model->get_data_list_by_single_column('productcategory', 'dist_id', $this->dist_id, 'title', 'ASC');
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/product/addNewProduct', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function productCatList() {
        $data['title'] = 'Product || Category';
       
        $data['mainContent'] = $this->load->view('distributor/inventory/productCat/productCatList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function addProductCat() {
        if (isPostBack()) {

            $this->form_validation->set_rules('title', 'Product Category', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('addProductCat'));
            } else {
                $data['title'] = $this->input->post('title');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertID = $this->Common_model->insert_data('productcategory', $data);
                if (!empty($insertID)) {
                    message("Product Category inserted successfully.");
                    unset($_POST);
                    redirect(site_url('productCatList'));
                }
            }
        }
        $data['title'] = 'Add Product Category ';
        $data['mainContent'] = $this->load->view('distributor/inventory/productCat/productCatAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function updateProductCat($updated_id) {
        if (isPostBack()) {
            $this->form_validation->set_rules('title', 'Product Category', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('addProductCat'));
            } else {
                $data['title'] = $this->input->post('title');
                $data['dist_id'] = $this->dist_id;
                $data['updated_at'] = $this->timestamp;
                $data['updated_by'] = $this->admin_id;
                $updateID = $this->Common_model->update_data('productcategory', $data, 'category_id', $updated_id);
                if (!empty($updateID)) {
                    message("Product Category updated successfully.");
                    unset($_POST);
                    redirect(site_url('productCatList'));
                }
            }
        }
        $data['updateCatInfo'] = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $updated_id);
        $data['title'] = 'Update Product Category ';
        $data['mainContent'] = $this->load->view('distributor/inventory/productCat/updateProductCat', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function supplierUpdate($updateid = null) {

        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('supName', 'Supplier Name', 'required');
            //$this->form_validation->set_rules('supPhone', 'Supplier Phone', 'required');
            //$this->form_validation->set_rules('supAddress', 'Supplier Address', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('supplierUpdate/' . $updateid));
            } else {

                $data['supID'] = $this->input->post('supplierId');
                $data['supName'] = $this->input->post('supName');
                $data['supEmail'] = $this->input->post('supEmail');
                $data['supPhone'] = $this->input->post('supPhone');
                $data['supAddress'] = $this->input->post('supAddress');
//$data['colorCode'] = $this->input->post('colorCode');
//            if (!empty($this->input->post('image')[0])) {
//                $data['supLogo'] = $this->input->post('image')[0];
//            }
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->update_data('supplier', $data, 'sup_id', $updateid);
                if (!empty($updateid)) {
                    unset($_POST);
                    message("Supplier updated successfully.");
                    redirect(site_url('supplierList'));
                }
            }
        }
        $data['title'] = 'Supplier || Edit';
        $data['supplierEdit'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $updateid);
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierEdit', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function Supplier() {
        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('supName', 'Supplier Name', 'required');
// $this->form_validation->set_rules('supPhone', 'Supplier Phone', 'required');
//  $this->form_validation->set_rules('supAddress', 'Supplier Address', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('Supplier'));
            } else {
                $data['supID'] = $this->input->post('supplierId');
                $data['supName'] = $this->input->post('supName');
                $data['supEmail'] = $this->input->post('supEmail');
                $data['supPhone'] = $this->input->post('supPhone');
                $data['supAddress'] = $this->input->post('supAddress');
//$data['colorCode'] = $this->input->post('colorCode');
//                if (!empty($this->input->post('image')[0])) {
//                    $data['supLogo'] = $this->input->post('image')[0];
//                }
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertID = $this->Common_model->insert_data('supplier', $data);
                if (!empty($insertID)) {
                    unset($_POST);
                    message("New Supplier created successfully.");
                    redirect(site_url('supplierList'));
                }
            }
        }

        $data['title'] = 'Setup || Supplier';
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);
        $data['colorList'] = $this->Common_model->get_data_list('color', 'colorID', 'ASC');
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function supplierList() {
        $data['title'] = 'Setup || Supplier';
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderLedger() {

        if (isPostBack()) {
            $productId = $this->input->post('productId');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['cylinderLedger'] = $this->Inventory_Model->getCylinderLedger($this->dist_id, $start_date, $end_date);
        }

        $data['title'] = 'Cylinder Redger';
        $data['dist_id'] = $this->dist_id;
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinderLedger', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderStockReport() {
        $data['title'] = 'Customer Wise Cylinder';
        $data['dist_id'] = $this->dist_id;
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinderStockReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function supplierPurchasesReport() {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Supplier Purchases Report';
        $data['title'] = 'Supplier Purchases Report';
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supplierPurchasesReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function supplierPurchasesReport_export_excel() {
        $file = 'Supplier Purchases Report_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['supplierList'] = $this->Common_model->get_data_list_by_single_column('supplier', 'dist_id', $this->dist_id);

        $this->load->view('excel_report/inventory/supplierPurchasesReport_export_excel', $data);
        unset($_SESSION['full_array']);
    }

    public function purchasesReport() {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Purchases Report';
        $data['title'] = 'Purchases || Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/purchasesReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function purchasesReport_export_excel() {
        $file = 'Purchases Report_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/inventory/purchasesReport_export_excel', $data);
        unset($_SESSION['full_array']);
    }

    function productBarcode() {
        $data['title'] = 'Product Barcode';
        $data['productList'] = $this->Common_model->get_data_list_by_single_column('product', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/barcode', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function unit() {
        $data['title'] = 'Unit List';
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/unit', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function unitAdd() {
        if (isPostBack()) {

// unitTtile
            $this->form_validation->set_rules('unitTtile', 'Unit Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('unitAdd'));
            } else {
                $data['unitTtile'] = $this->input->post('unitTtile');
                $data['code'] = $this->input->post('code');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->insert_data('unit', $data);
                if ($insertid) {
                    message("Your data successfully inserted into database.");
                    redirect('unit');
                }
            }
        }
        $data['title'] = 'Unit Add';
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/unitAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function unitEdit($updatedid) {
        if (isPostBack()) {
            $this->form_validation->set_rules('unitTtile', 'Unit Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('unitEdit/' . $updatedid));
            } else {
                $data['unitTtile'] = $this->input->post('unitTtile');
                $data['code'] = $this->input->post('code');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->update_data('unit', $data, 'unit_id', $updatedid);
                if ($insertid) {
                    message("Your data successfully update into database.");
                    redirect('unit');
                }
            }
        }
        $data['unitList'] = $this->Common_model->get_single_data_by_single_column('unit', 'unit_id', $updatedid);
        $data['title'] = 'Unit Add';
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/unitEdit', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function brandAdd() {
        if (isPostBack()) {
//Validation Rules
            $this->form_validation->set_rules('brandName', 'Brand Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('brandAdd'));
            } else {
                $data['brandName'] = $this->input->post('brandName');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->insert_data('brand', $data);
                if ($insertid) {
                    message("Your data successfully inserted into database.");
                    redirect('brand');
                }
            }
        }
        $data['title'] = 'Brand Add';
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/brandAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function brandEdit($updateId) {
        if (isPostBack()) {
            $this->form_validation->set_rules('brandName', 'Brand Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('brandAdd'));
            } else {
                $data['brandName'] = $this->input->post('brandName');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->update_data('brand', $data, 'brandId', $updateId);
                if ($insertid) {
                    message("Your data successfully updated into database.");
                    redirect('brand');
                }
            }
        }
        $data['title'] = 'Brand Add';
        $data['brandList'] = $this->Common_model->get_single_data_by_single_column('brand', 'brandId', $updateId);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/brandEdit', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function brand() {
        $data['title'] = 'Brand List';
       
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/brandList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function deleteProductCategory($deletedId) {

        $inventoryCondition = array(
            'dist_id' => $this->dist_id,
            'category_id' => $deletedId,
        );
        $exits = $this->Common_model->get_data_list_by_many_columns('product', $inventoryCondition);
        if (empty($exits)) {
            $condition = array(
                'dist_id' => $this->dist_id,
                'category_id' => $deletedId
            );
            $this->Common_model->delete_data_with_condition('productcategory', $condition);
            message("Your data successully deleted from database.");
            redirect(site_url('productCatList'));
        } else {
            exception("This Category can't be deleted.already have a product created by this category!");
            redirect(site_url('productCatList'));
        }
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
            $this->Common_model->delete_data_with_condition('unit', $condition);
            message("Your data successully deleted from database.");
            redirect(site_url('unit'));
        } else {
            exception("This Unit can't be deleted.already have a transaction by this unit!");
            redirect(site_url('unit'));
        }
    }

    function deleteProduct($deletedId) {

        $inventoryCondition = array(
            'dist_id' => $this->dist_id,
            'product_id' => $deletedId,
        );
        $exits = $this->Common_model->get_data_list_by_many_columns('stock', $inventoryCondition);
        if (empty($exits)) {
            $condition = array(
                'dist_id' => $this->dist_id,
                'product_id' => $deletedId
            );
            $this->Common_model->delete_data_with_condition('product', $condition);
            message("Your data successully deleted from database.");
            redirect(site_url('productList'));
        } else {
            exception("This Product can't be deleted.already have a transaction!");
            redirect(site_url('productList'));
        }
    }

    function deleteBrand($deletedId) {

        $inventoryCondition = array(
            'dist_id' => $this->dist_id,
            'brand_id' => $deletedId,
        );
        $exits = $this->Common_model->get_data_list_by_many_columns('product', $inventoryCondition);
        if (empty($exits)) {
            $condition = array(
                'dist_id' => $this->dist_id,
                'brandId' => $deletedId
            );
            $this->Common_model->delete_data_with_condition('brand', $condition);
            message("Your data successully deleted from database.");
            redirect(site_url('brand'));
        } else {
            exception("This Brand can't be deleted.already have a product created by this brand!");
            redirect(site_url('brand'));
        }
    }

    public function openigInventoryImport() {
        if (isPostBack()):
            if (!empty($_FILES['openingInventory']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('openingInventory');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['openingInventory']['tmp_name'];
                $importFile = fopen($file, "r");
                //dumpVar($importFile);
                $this->db->trans_start();
                $general['dist_id'] = $this->dist_id;
                $general['voucher_no'] = 'OP' . mt_rand(100000, 999999);
                $general['date'] = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                $general['narration'] = 'opening Inventory Imported';
                $general['form_id'] = 10;
                $general['updated_by'] = $this->admin_id;
                $general['created_at'] = $this->timestamp;
                $generals_id = $this->Common_model->insert_data('generals', $general);
                $row = 0;
                $storeData = array();
                $allStock = array();
                $newCylinderProductCost = 0;
                $otherProductCost = 0;
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        $productFormatExits = $this->Inventory_Model->checkProductFormate($readRowData, $this->dist_id);
                        unset($stock);
                        if ($productFormatExits === true):
                            //check empty or not
                            if (!empty($readRowData[6]) && !empty($readRowData[7])):
                                //check numeric or string
                                if (is_numeric($readRowData[6]) && is_numeric($readRowData[7])):

                                    if ($readRowData[1] == 1) {
                                        $newCylinderProductCost += $readRowData[6] * $readRowData[7];
                                    } else {
                                        $otherProductCost += $readRowData[6] * $readRowData[7];
                                    }
                                    $stock['generals_id'] = $generals_id;
                                    $stock['category_id'] = isset($readRowData[1]) ? $readRowData[1] : '';
                                    $stock['product_id'] = isset($readRowData[2]) ? $readRowData[2] : '';
                                    $stock['quantity'] = isset($readRowData[6]) ? $readRowData[6] : '';
                                    $stock['rate'] = isset($readRowData[7]) ? $readRowData[7] : '';
                                    $stock['price'] = $readRowData[6] * $readRowData[7];
                                    $stock['date'] = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                                    $stock['form_id'] = 10;
                                    $stock['type'] = 'In';
                                    $stock['openingStatus'] = '1';
                                    $stock['dist_id'] = $this->dist_id;
                                    $stock['updated_by'] = $this->admin_id;
                                    $stock['created_at'] = $this->timestamp;
                                    $allStock[] = $stock;
                                endif;
                            endif;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($allStock)):
                    $geneAmount['debit'] = $newCylinderProductCost + $otherProductCost;
                    $this->Common_model->update_data('generals', $geneAmount, 'generals_id', $generals_id);
                    $this->db->insert_batch('stock', $allStock);
                    if (!empty($newCylinderProductCost) && $newCylinderProductCost > 0):
                        $gl_data = array(
                            'generals_id' => $generals_id,
                            'date' => date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))),
                            'form_id' => '10',
                            'dist_id' => $this->dist_id,
                            'account' => 173,
                            'debit' => $newCylinderProductCost,
                            'memo' => 'Opening Inventory',
                            'updated_by' => $this->admin_id,
                            'created_at' => $this->timestamp
                        );
                        $this->db->insert('generalledger', $gl_data);
                    endif;
                    //Others product inventory stock.
                    if (!empty($otherProductCost) && $otherProductCost > 0):
                        $gl_data = array(
                            'generals_id' => $generals_id,
                            'date' => date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))),
                            'form_id' => '10',
                            'dist_id' => $this->dist_id,
                            'account' => 52,
                            'debit' => $otherProductCost,
                            'memo' => 'Opening Inventory',
                            'updated_by' => $this->admin_id,
                            'created_at' => $this->timestamp
                        );
                        $this->db->insert('generalledger', $gl_data);
                    endif;
                endif;
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your csv file not inserted.please check csv file properly.");
                    redirect(site_url('inventoryAdjustmentAdd'));
                } else {
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('inventoryAdjustment'));
                }
            endif;
        endif;
        $data['title'] = 'Opening Inventory Import';
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryImport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function deleteInventoryOpening($deleteId) {
        $this->db->trans_start();
        $this->Common_model->delete_data('generals', 'generals_id', $deleteId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $deleteId);
        $this->Common_model->delete_data('stock', 'generals_id', $deleteId);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            notification("Your data can't be daleted!");
            redirect(site_url('inventoryAdjustment'));
        } else {
            message("Your data successfully deleted from database.");
            redirect(site_url('inventoryAdjustment'));
        }
    }

    public function getImportProductList() {
        $data['productList'] = $productList = $this->Common_model->getImportProduct($this->dist_id);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=importProductList.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('#', 'Category ID(Not Change)', 'Product ID(Not Change)', 'Product Code(Not Change)', 'Product Category(Not Change)', 'Product Name(Not Change)', 'Quantity*', 'Unit price*', 'Total Price'));
        foreach ($productList as $key => $eachProduct):
            fputcsv($output, $eachProduct);
        endforeach;
        fclose($output);
    }

    public function inventoryAdjustmentAdd() {

        if (isPostBack()) {

            $arrayIndex = count($this->input->post('product_id'));
            if ($arrayIndex == 0) {
                exception("Adjustment item can't be empty!!");
                redirect(site_url('inventoryAdjustmentAdd'));
            }
            $finalDate = $this->input->post('purchasesDate');
            $todays = date('Y-m-d', strtotime('-1 day', strtotime($finalDate)));
//dumpVar($_POST);
            $this->db->trans_start();
            $data['dist_id'] = $this->dist_id;
            $data['voucher_no'] = $this->input->post('voucherid');
            $data['date'] = $todays;
            $data['debit'] = array_sum($this->input->post('price'));
            $data['narration'] = $this->input->post('narration');
            $data['form_id'] = 10;
            $data['updated_by'] = $this->admin_id;
            $data['created_at'] = $this->timestamp;
            $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
            $productCate = $this->input->post('category_id');
            $allStock = array();
            $newCylinderProductCost = 0;
            $otherProductCost = 0;

            foreach ($productCate as $key => $value):
                unset($stock);

                unset($stock);
                if ($value == 1) {
                    $newCylinderProductCost += $this->input->post('price')[$key];
                } else {
                    $otherProductCost += $this->input->post('price')[$key];
                }

                $stock['generals_id'] = $generals_id;
                $stock['category_id'] = $value;
                $stock['product_id'] = $this->input->post('product_id')[$key];
                $stock['quantity'] = $this->input->post('quantity')[$key];
                $stock['rate'] = $this->input->post('rate')[$key];
                $stock['price'] = $this->input->post('price')[$key];
                $stock['date'] = $todays;
                $stock['form_id'] = 10;
                $stock['type'] = 'In';
                $stock['openingStatus'] = '1';
                $stock['dist_id'] = $this->dist_id;
                $stock['updated_by'] = $this->admin_id;
                $stock['created_at'] = $this->timestamp;
                $allStock[] = $stock;
            endforeach;
            $this->db->insert_batch('stock', $allStock);
//new cylinder product stock
            if (!empty($newCylinderProductCost) && $newCylinderProductCost > 0):
                $gl_data = array(
                    'generals_id' => $generals_id,
                    'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                    'form_id' => '10',
                    'dist_id' => $this->dist_id,
                    'account' => 173,
                    'debit' => $newCylinderProductCost,
                    'memo' => 'purchases',
                    'updated_by' => $this->admin_id,
                    'created_at' => $this->timestamp
                );
                $this->db->insert('generalledger', $gl_data);
            endif;
//Others product inventory stock.
            if (!empty($otherProductCost) && $otherProductCost > 0):
                $gl_data = array(
                    'generals_id' => $generals_id,
                    'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                    'form_id' => '10',
                    'dist_id' => $this->dist_id,
                    'account' => 52,
                    'debit' => $otherProductCost,
                    'memo' => 'purchases',
                    'updated_by' => $this->admin_id,
                    'created_at' => $this->timestamp
                );
                $this->db->insert('generalledger', $gl_data);
            endif;
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your data can't be inserted.Somthing is wrong!!");
                redirect(site_url('inventoryAdjustmentAdd'));
            } else {
                message("Your data successfully inserted into database.");
                redirect(site_url('inventoryAdjustment'));
            }
        }
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 10,
        );
        $data['title'] = 'Inventory Adjustment';
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $totalAdjustment = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "AV" . date('y') . date('m') . str_pad(count($totalAdjustment) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryAdjustmentAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function viewAdjustment($adjustmentid = null) {

        $data['title'] = 'Inventory Adjustment View';
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $adjustmentid);
        $data['stockList'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $adjustmentid);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
// $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['purchasesList']->supplier_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/viewAdjustment', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function inventoryAdjustment() {



        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 10,
        );
        $data['openingShowHide'] = $this->Inventory_Model->checkOpenigValid($this->dist_id);

        $data['title'] = 'Inventory Adjustment';
        $data['inventoryAdjustmentList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryAdjustment', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderPurchases() {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 23,
        );
        $data['title'] = 'Cylinder Purchases';
        $data['cylinderList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinder/cylinderPurchases', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderExchange() {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 24,
        );
        $data['title'] = 'Cylinder Exchange';
        $data['cylinderExchangeList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);


        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderSale/saleList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function viewCylinderExchange($exchangeId) {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 24,
        );
        $data['cylinderList'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $exchangeId);
        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['cylinderList']->supplier_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['cylinderStock'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $exchangeId);


        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderSale/viewCylinder', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderExchangeAdd() {
        if (isPostBack()) {
            $this->db->trans_start();
            $data['dist_id'] = $this->dist_id;
            $data['supplier_id'] = $this->input->post('supplierID');
            $data['voucher_no'] = $this->input->post('voucherid');
            $data['reference'] = $this->input->post('reference');
            $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $data['debit'] = array_sum($this->input->post('price'));
            $data['narration'] = $this->input->post('narration');
            $data['form_id'] = 24;
            $data['updated_by'] = $this->admin_id;
            $data['created_at'] = $this->timestamp;
            $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
            $productCate = $this->input->post('category_id');
            $allStock = array();
            foreach ($productCate as $key => $value):
                unset($stock);
                $stock['generals_id'] = $generals_id;
                $stock['category_id'] = $value;
                $stock['product_id'] = $this->input->post('product_id')[$key];
                $stock['quantity'] = $this->input->post('quantity')[$key];
                $stock['rate'] = $this->input->post('rate')[$key];
                $stock['price'] = $this->input->post('price')[$key];
                $stock['date'] = date('Y-m-d');
                $stock['form_id'] = 24;
                $stock['type'] = 'Out';
                $stock['dist_id'] = $this->dist_id;
                $stock['updated_by'] = $this->admin_id;
                $stock['created_at'] = $this->timestamp;
                $allStock[] = $stock;
            endforeach;
            $this->db->insert_batch('stock', $allStock);
//52 inventory stock
            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '24',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'credit' => array_sum($this->input->post('price')),
                'memo' => 'Cylinder Exchange',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
//52 inventory stock
            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '24',
                'dist_id' => $this->dist_id,
                'account' => 65,
                'debit' => array_sum($this->input->post('price')),
                'memo' => 'Cylinder Exchange',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your data can't be inserted.Somthing is wrong!!");
                redirect(site_url('cylinderExchangeAdd'));
            } else {
                message("Your data successfully inserted into database.");
                redirect(site_url('cylinderExchange'));
            }
        }

        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 24,
        );

        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 24,
        );
        $supCondition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['supplierList'] = $this->Common_model->get_data_list_by_many_columns('supplier', $supCondition);
        $data['productCat'] = $this->Common_model->get_data_list_by_single_column('productcategory', 'dist_id', $this->dist_id);
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "CE" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['title'] = 'Cylinder Exchange Add';
        $data['cylinderList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderSale/saleAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function viewCylinder($viewId) {

        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 23,
        );

        $data['title'] = 'Cylinder Purchases';
        $data['cylinderList'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $viewId);
        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['cylinderList']->supplier_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['cylinderStock'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $viewId);

        $data['mainContent'] = $this->load->view('distributor/inventory/cylinder/cylinderView', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderPurchases_add() {

        if (isPostBack()) {

//dumpVar($_POST);

            $this->db->trans_start();
            $data['dist_id'] = $this->dist_id;
            $data['supplier_id'] = $this->input->post('supplierID');
            $data['voucher_no'] = $this->input->post('voucherid');
            $data['reference'] = $this->input->post('reference');
            $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $data['debit'] = array_sum($this->input->post('price'));
            $data['narration'] = $this->input->post('narration');
            $data['form_id'] = 23;
            $data['updated_by'] = $this->admin_id;
            $data['created_at'] = $this->timestamp;
            $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
            $productCate = $this->input->post('category_id');
            $allStock = array();
            foreach ($productCate as $key => $value):
                unset($stock);
                $stock['generals_id'] = $generals_id;
                $stock['category_id'] = $value;
                $stock['product_id'] = $this->input->post('product_id')[$key];
                $stock['quantity'] = $this->input->post('quantity')[$key];
                $stock['rate'] = $this->input->post('rate')[$key];
                $stock['price'] = $this->input->post('price')[$key];
                $stock['date'] = date('Y-m-d');
                $stock['form_id'] = 23;
                $stock['type'] = 'In';
                $stock['dist_id'] = $this->dist_id;
                $stock['updated_by'] = $this->admin_id;
                $stock['created_at'] = $this->timestamp;
                $allStock[] = $stock;
            endforeach;
            $this->db->insert_batch('stock', $allStock);
//52 inventory stock
            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '23',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => array_sum($this->input->post('price')),
                'memo' => 'Inventory Stock',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
//52 Purchase price Variance
            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '23',
                'dist_id' => $this->dist_id,
                'account' => 65,
                'credit' => array_sum($this->input->post('price')),
                'memo' => 'Inventory Adjustment',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);


            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your data can't be inserted.Somthing is wrong!!");
                redirect(site_url('cylinderPurchases_add'));
            } else {
                message("Your data successfully inserted into database.");
                redirect(site_url('cylinderPurchases'));
            }
        }
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 23,
        );
        $supCondition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['supplierList'] = $this->Common_model->get_data_list_by_many_columns('supplier', $supCondition);
        $data['productCat'] = $this->Common_model->get_data_list_by_single_column('productcategory', 'dist_id', $this->dist_id);
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "CP" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['title'] = 'Cylinder Purchases Add';
        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinder/cylinderPurchasesAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function purchases_list() {
        $data['title'] = 'Purchases List';
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchases_list', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function suppay_ajax() {
        $supplier = $this->input->post('supplier');
        $result = '<table class="table table-bordered table-hover">';
        $result .= '<thead><tr><td align="center"><strong>Voucher No.</strong></td><td align="center"><strong>Date</strong></td><td align="center"><strong>Type</strong></td><td align="center"><strong>Amount Due (In BDT.)</strong></td><td align="center"><strong>Allocation (In BDT.)</strong></td></tr></thead>';
        $result .= '<tbody>';
        $query = $this->Inventory_Model->generals_supplier($supplier);
        foreach ($query as $key => $row):
            $value = $this->Inventory_Model->generals_voucher($row['voucher_no']);
            if (!empty($value)):
                if ($this->Inventory_Model->generals_voucher($row['voucher_no']) != 0):
                    $result .= '<tr>';
                    $result .= '<td><a href="' . site_url('viewPurchases/' . $row['generals_id']) . '">' . $row['voucher_no'] . '<input type="hidden" name="voucher[]" value="' . $row['voucher_no'] . '"></a></td>';
                    $result .= '<td>' . date('d.m.Y', strtotime($row['date'])) . '</td>';
                    $result .= '<td>' . $this->Common_model->tableRow('form', 'form_id', $row['form_id'])->name . '</td>';
                    $result .= '<td align="right"><input type="hidden" value="' . $this->Sales_Model->generals_voucher($row['voucher_no']) . '" id="dueAmount_' . $key . '">' . number_format((float) $this->Sales_Model->generals_voucher($row['voucher_no']), 2, '.', ',') . '</td>';
                    $result .= '<td><input id="paymentAmount_' . $key . '" type="text" onkeyup="checkOverAmount(' . $key . ')"  type="text" class="form-control amount" name="amount[]"  placeholder="0.00"></td>';
                    $result .= '</tr>';
                endif;
            endif;
        endforeach;
        $result .= '<tr>';
        $result .= '<td align="right" colspan="4"><strong>Total (In BDT.)<span style="color:red;">*</span></strong></td>';
        $result .= '<td><input type="text" class="form-control ttl_amount required" name="ttl_amount" placeholder="0.00" readonly="readonly"></td>';
        $result .= '</tr>';
        $result .= '</tbody></table>';
        $result .= '<script type="text/javascript">';
        $result .= "$(document).ready(function(){ $('.amount').change(function(){ ttl_amount=0; $.each($('.amount'), function(){ aamount = $(this).val(); aamount=Number(aamount); ttl_amount+=aamount; }); $(this).val(parseFloat($(this).val()).toFixed(2)); $('.ttl_amount').val(parseFloat(ttl_amount).toFixed(2)); }); });";
        $result .= '</script>';
        echo $result;
    }

    public function supplierPaymentAdd() {


        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('receiptId', 'Receipt ID', 'required');
            $this->form_validation->set_rules('paymentDate', 'Payment Date', 'required');
            $this->form_validation->set_rules('supplierid', 'Supplier ID', 'required');
            $this->form_validation->set_rules('ttl_amount', 'Total Amount', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('supplierPaymentAdd'));
            } else {

// dumpVar($_POST);
                $updated_by = $this->admin_id;
                $created_at = date('Y-m-d H:i:s');
                $voucher = $this->input->post('voucher');
                $payType = $this->input->post('payType');
                $accountCr = $this->input->post('accountCr');
                $this->db->trans_start();
                if (!empty($voucher)) {

//when cash payment than transaction here
                    if ($payType == 1) {
//when payment type cash than transaction here.
//check account head empty or not
                        if (empty($accountCr)) {
                            notification("Account Head must be selected!!");
                            redirect(site_url('supplierPaymentAdd'));
                        }

                        $totalAmount = 0;
                        $allVoucher = array();
                        foreach ($voucher as $a => $b) {
                            $amount = $this->input->post('amount[' . $a . ']');
                            if (!empty($amount)) {
                                $allVoucher[] = $voucherId . '_' . $amount;
                                $totalAmount += $amount;
                                $generals_data = array(
                                    'form_id' => '14',
                                    'supplier_id' => $this->input->post('supplierid'),
                                    'dist_id' => $this->dist_id,
                                    'voucher_no' => $this->input->post('voucher[' . $a . ']'),
                                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                    'payType' => $this->input->post('payType'),
                                    'credit' => $this->input->post('amount[' . $a . ']'),
                                    'narration' => 'Supplier Payment',
                                    'updated_by' => $updated_by,
                                    'created_at' => $created_at
                                );
                                $this->db->insert('generals', $generals_data);
                                $generals_id = $this->db->insert_id();

                                $supp = array(
                                    'ledger_type' => 2,
                                    'dist_id' => $this->dist_id,
                                    'trans_type' => $this->input->post('voucher[' . $a . ']'),
                                    'client_vendor_id' => $this->input->post('supplierid'),
                                    'amount' => $this->input->post('amount[' . $a . ']'),
                                    'cr' => $this->input->post('amount[' . $a . ']'),
                                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                    'updated_by' => $this->admin_id,
                                    'history_id' => $generals_id,
                                    'paymentType' => 'Supplier Payment'
                                );
                                $this->db->insert('client_vendor_ledger', $supp);
// generalledger: Pay From
                                $cr_data = array(
                                    'form_id' => '14',
                                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                    'generals_id' => $generals_id,
                                    'dist_id' => $this->dist_id,
                                    'account' => $this->input->post('accountCr'),
                                    'credit' => $this->input->post('amount[' . $a . ']'),
                                    'updated_by' => $updated_by,
                                    'created_at' => $created_at
                                );
                                $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
                                $dr_data = array(
                                    'form_id' => '14',
                                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                    'generals_id' => $generals_id,
                                    'dist_id' => $this->dist_id,
                                    'account' => 50,
                                    'debit' => $this->input->post('amount[' . $a . ']'),
                                    'updated_by' => $updated_by,
                                    'created_at' => $created_at
                                );
                                $this->db->insert('generalledger', $dr_data);
                            }
                        }


//end cash paymet type from here.
                    } else {

//when bank paymet transaction start from here
                        $totalAmount = 0;
                        $allVoucher = array();
                        foreach ($voucher as $a => $b) {
                            $amount = $this->input->post('amount[' . $a . ']');
                            if (!empty($amount)) {
                                $allVoucher[] = $voucherId . '_' . $amount;
                                $totalAmount += $amount;
                                $generals_data = array(
                                    'form_id' => '14',
                                    'supplier_id' => $this->input->post('supplierid'),
                                    'dist_id' => $this->dist_id,
                                    'voucher_no' => $this->input->post('voucher[' . $a . ']'),
                                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                    'payType' => $this->input->post('payType'),
                                    'credit' => $this->input->post('amount[' . $a . ']'),
                                    'narration' => 'Supplier Payment',
                                    'updated_by' => $updated_by,
                                    'created_at' => $created_at
                                );
                                $this->db->insert('generals', $generals_data);
                                $generals_id = $this->db->insert_id();
                                /*
                                  $supp = array(
                                  'ledger_type' => 2,
                                  'dist_id' => $this->dist_id,
                                  'trans_type' => $this->input->post('voucher[' . $a . ']'),
                                  'client_vendor_id' => $this->input->post('supplierid'),
                                  'amount' => $this->input->post('amount[' . $a . ']'),
                                  'cr' => $this->input->post('amount[' . $a . ']'),
                                  'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                  'updated_by' => $this->admin_id,
                                  'history_id' => $generals_id,
                                  'paymentType' => 'Supplier Payment'
                                  );
                                  $this->db->insert('client_vendor_ledger', $supp);
                                  // generalledger: Pay From
                                  $cr_data = array(
                                  'form_id' => '14',
                                  'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                  'generals_id' => $generals_id,
                                  'dist_id' => $this->dist_id,
                                  'account' => $this->input->post('accountCr'),
                                  'credit' => $this->input->post('amount[' . $a . ']'),
                                  'updated_by' => $updated_by,
                                  'created_at' => $created_at
                                  );
                                  $this->db->insert('generalledger', $cr_data);
                                  // generalledger: Account Payables
                                  $dr_data = array(
                                  'form_id' => '14',
                                  'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                  'generals_id' => $generals_id,
                                  'dist_id' => $this->dist_id,
                                  'account' => 50,
                                  'debit' => $this->input->post('amount[' . $a . ']'),
                                  'updated_by' => $updated_by,
                                  'created_at' => $created_at
                                  );
                                  $this->db->insert('generalledger', $dr_data);



                                 */
                            }
                        }

//when bank paymet transaction s from here
                    }

                    $bankName = $this->input->post('bankName');
                    $checkNo = $this->input->post('checkNo');
                    $checkDate = $this->input->post('checkDate');
                    $branchName = $this->input->post('branchName');
                    $mreceit = array(
                        'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                        'invoiceID' => json_encode($allVoucher),
                        'totalPayment' => $totalAmount,
                        'receitID' => $this->input->post('receiptId'),
                        'customerid' => $this->input->post('supplierid'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                        'paymentType' => $this->input->post('payType'),
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? $checkDate : '0',
                        'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $mreceit);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted.Somthing is wrong!!");
                    redirect(site_url('supplierPaymentAdd'));
                } else {
                    message("Your data successfully inserted into database.");
                    redirect(site_url('supplierPayment'));
                }
            }
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $supCondition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );


        $mrCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
        );

        $exitsingReceit = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
        $data['voucherId'] = "MID" . date("y") . date("m") . str_pad(count($exitsingReceit) + 1, 4, "0", STR_PAD_LEFT);
        $data['supplierList'] = $this->Inventory_Model->getPaymentDueSupplierCustomer($this->dist_id, 2);
        // dumpVar($data['supplierList']);
        $data['title'] = 'Supplier Payment';
        $data['purchasesList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supplierPayment', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function viewMoneryReceiptSup($receiptId, $voucherId = NULL) {
        $data['title'] = 'Money Receipt';
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        if (!empty($voucherId)) {
            $moneyReceiteId = $this->Common_model->tableRow('moneyreceit', 'receitID', $voucherId)->moneyReceitid;

            $condition = array(
                'dist_id' => $this->dist_id,
                'moneyReceitid' => $receiptId,
                'receiveType' => '2',
            );
        } else {
            $condition = array(
                'dist_id' => $this->dist_id,
                'moneyReceitid' => $receiptId,
                'receiveType' => '2',
            );
        }


        $data['moneyReceitInfo'] = $this->Common_model->get_single_data_by_many_columns('moneyreceit', $condition);
        $data['suplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['moneyReceitInfo']->customerid);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/viewMoneyReceipt', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function supplierPayment() {
        $data['title'] = 'Supplier Payment List';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supplierPaymentList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function editPurchases() {
//edit code here.
    }

    public function supPendingCheque() {
        if (isPostBack()) {

            $receiteID = $this->input->post('receiteID');
            $receiteInfo = $this->Common_model->tableRow('moneyreceit', 'moneyReceitid', $receiteID);
            $updated_by = $this->session->userdata('admin_id');
            $created_at = date('Y-m-d H:i:s');
            $voucher = json_decode($receiteInfo->invoiceID);
            $paymentType = $this->input->post('paymentType');
            $clientID = $this->input->post('clientID');
            $account = $this->input->post('accountDr');

            $this->db->trans_start();
            if (!empty($voucher)) {
                if (count($voucher) == 1) {
                    if (!empty($receiteInfo->totalPayment)) {
// die("hello");
                        $customerData = array(
                            'ledger_type' => 2,
                            'trans_type' => $receiteInfo->receitID,
                            'paymentType' => 'Check Received',
                            'client_vendor_id' => $clientID,
                            'amount' => $receiteInfo->totalPayment,
                            'cr' => $receiteInfo->totalPayment,
                            'dist_id' => $this->dist_id,
                            'date' => date('Y-m-d', strtotime($this->input->post('paymentDate')))
                        );
                        $this->db->insert('client_vendor_ledger', $customerData);
                        $generals_data = array(
                            'form_id' => 14,
                            'supplier_id' => $clientID,
                            'dist_id' => $this->dist_id,
                            'voucher_no' => $receiteInfo->receitID,
                            'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                            'credit' => $receiteInfo->totalPayment,
                            'narration' => $this->input->post('narration'),
                            'updated_by' => $updated_by,
                            'created_at' => $created_at
                        );
                        $this->db->insert('generals', $generals_data);
                        $generals_id = $this->db->insert_id();

// Cash in hand debit
                        $dr_data = array(
                            'form_id' => 14,
                            'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                            'generals_id' => $generals_id,
                            'dist_id' => $this->dist_id,
                            'account' => $account,
                            'credit' => $receiteInfo->totalPayment,
                            'updated_by' => $updated_by,
                            'created_at' => $created_at
                        );
                        $this->db->insert('generalledger', $dr_data);
// account payable credit
                        $cr_data = array(
                            'form_id' => 14,
                            'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                            'generals_id' => $generals_id,
                            'dist_id' => $this->dist_id,
                            'account' => '50',
                            'debit' => $receiteInfo->totalPayment,
                            'updated_by' => $updated_by,
                            'created_at' => $created_at
                        );
                        $this->db->insert('generalledger', $cr_data);
                        $changeStatus['checkStatus'] = 2;
                        $changeStatus['received_date'] = date('Y-m-d');
                        $this->db->where('moneyReceitid', $receiteID);
                        $this->db->update('moneyreceit', $changeStatus);
                    }
                } else {

                    $totalAmount = 0;
                    $allVoucher = array();
                    foreach ($voucher as $a => $b) {
                        $moneyReceit = explode("_", $b);
                        $payment = $moneyReceit[1];
                        if (!empty($payment)) {
                            $allVoucher[] = $moneyReceit[0];
                            $totalAmount += $payment;
                            $customerData = array(
                                'ledger_type' => 1,
                                'trans_type' => $moneyReceit[0],
                                'paymentType' => 'Check Receive',
                                'client_vendor_id' => $clientID,
                                'amount' => $moneyReceit[1],
                                'cr' => $moneyReceit[1],
                                'dist_id' => $this->dist_id,
                                'date' => date('Y-m-d', strtotime($this->input->post('paymentDate')))
                            );
                            $this->db->insert('client_vendor_ledger', $customerData);
                            $generals_data = array(
                                'form_id' => 14,
                                'customer_id' => $clientID,
                                'dist_id' => $this->dist_id,
                                'voucher_no' => $moneyReceit[0],
                                'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                'credit' => $moneyReceit[1],
                                'narration' => $this->input->post('narration'),
                                'updated_by' => $updated_by,
                                'created_at' => $created_at
                            );
                            $this->db->insert('generals', $generals_data);
                            $generals_id = $this->db->insert_id();
// Cash in hand credit
                            $dr_data = array(
                                'form_id' => 14,
                                'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                'generals_id' => $generals_id,
                                'dist_id' => $this->dist_id,
                                'account' => $this->input->post('accountCr'),
                                'credit' => $this->input->post('amount[' . $a . ']'),
                                'updated_by' => $updated_by,
                                'created_at' => $created_at
                            );
                            $this->db->insert('generalledger', $dr_data);
// account payable debit
                            $cr_data = array(
                                'form_id' => 14,
                                'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                                'generals_id' => $generals_id,
                                'dist_id' => $this->dist_id,
                                'account' => '50',
                                'debit' => $moneyReceit[1],
                                'updated_by' => $updated_by,
                                'created_at' => $created_at
                            );
                            $this->db->insert('generalledger', $cr_data);
                            $changeStatus['checkStatus'] = 2;
                            $changeStatus['received_date'] = date('Y-m-d');
                            $this->db->where('moneyReceitid', $receiteID);
                            $this->db->update('moneyreceit', $changeStatus);
                        }
                    }
                }
            } else {


// dumpVar($_POST);
                $supplierLedger = array(
                    'ledger_type' => 2,
                    'trans_type' => $receiteInfo->receitID,
                    'paymentType' => 'Check Received',
                    'client_vendor_id' => $clientID,
                    'amount' => $receiteInfo->totalPayment,
                    'cr' => $receiteInfo->totalPayment,
                    'dist_id' => $this->dist_id,
                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate')))
                );
                $this->db->insert('client_vendor_ledger', $supplierLedger);
                $generals_data = array(
                    'form_id' => 14,
                    'supplier_id' => $clientID,
                    'dist_id' => $this->dist_id,
                    'voucher_no' => $receiteInfo->invoiceID,
                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                    'credit' => $receiteInfo->totalPayment,
                    'narration' => $this->input->post('narration'),
                    'updated_by' => $updated_by,
                    'created_at' => $created_at
                );
                $this->db->insert('generals', $generals_data);
                $generals_id = $this->db->insert_id();

// Cash in hand credit
                $dr_data = array(
                    'form_id' => 14,
                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                    'generals_id' => $generals_id,
                    'dist_id' => $this->dist_id,
                    'account' => $account,
                    'credit' => $receiteInfo->totalPayment,
                    'updated_by' => $updated_by,
                    'created_at' => $created_at
                );
                $this->db->insert('generalledger', $dr_data);
// account payable credit
                $cr_data = array(
                    'form_id' => 14,
                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                    'generals_id' => $generals_id,
                    'dist_id' => $this->dist_id,
                    'account' => '50',
                    'debit' => $receiteInfo->totalPayment,
                    'updated_by' => $updated_by,
                    'created_at' => $created_at
                );
                $this->db->insert('generalledger', $cr_data);
                $changeStatus['checkStatus'] = 2;
                $changeStatus['received_date'] = date('Y-m-d');
                $this->db->where('moneyReceitid', $receiteID);
                $this->db->update('moneyreceit', $changeStatus);
            }




            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                exception("Data could not save.something is wrong");
                redirect('supPendingCheque', 'refresh');
            } else {
                message("Your data successfully inserted into database.");
                redirect('supPendingCheque', 'refresh');
            }
        }
//pending check condition
        $pendingChequeCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
            'paymentType' => 2,
            'checkStatus' => 1,
        );
        $data['title'] = 'Supplier Pending cheque';
        $data['supplierPendingCheck'] = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $pendingChequeCondition);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['mainContent'] = $this->load->view('distributor/inventory/report/pendingCheck', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function purchases_edit($ivnoiceId = null) {

        /* check Invoice id valid ? or not */
        if (is_numeric($ivnoiceId)) {
//is invoice id is valid
            $validInvoiecId = $this->Sales_Model->checkInvoiceIdAndDistributor($this->dist_id, $ivnoiceId);
            if ($validInvoiecId === FALSE) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url('salesInvoice'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url('salesInvoice'));
        }
        /* check Invoice id valid ? or not */

        if (isPostBack()) {
            $this->form_validation->set_rules('supplierID', 'Supplier ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher ID', 'required');
            $this->form_validation->set_rules('purchasesDate', 'Purchases Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Date', 'required');
            $this->form_validation->set_rules('category_id[]', 'Product Category', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product Name', 'required');
            $this->form_validation->set_rules('unit_id[]', 'Product Unit', 'required');
            $this->form_validation->set_rules('quantity[]', 'Product Quantigy', 'required');
            $this->form_validation->set_rules('rate[]', 'Product Rate', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('purchases_add'));
            } else {
                $paymentType = $this->input->post('paymentType');
                $accountCr = $this->input->post('accountCr');
                $arrayIndex = count($this->input->post('product_id'));
                $supplier_id = $this->input->post('supplierID');
                $this->db->trans_start();
                if ($paymentType == 2) {
                    //if payment type credit than transaction here
                    $generals_id = $this->creditTransactionInsertForUpdate($ivnoiceId);
                } elseif ($paymentType == 1) {
                    //if payment type Cash than transaction here 
                    $generals_id = $this->cashTransactionInsertForUpdate($ivnoiceId);
                } elseif ($paymentType == 4) {
                    //if payment type partial than calculate from here
                    $generals_id = $this->partialTransactionInsertForUpdate($ivnoiceId);
                } else {
                    $generals_id = $this->chequeTransactionInsertForUpdate($ivnoiceId);
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted.Somthing is wrong!!");
                    if (!empty($confirId)):
                        redirect(site_url('PurchaseDemoConfirm/' . $confirId));
                    else:
                        redirect(site_url('purchases_add'));
                    endif;
                } else {
                    if (!empty($confirId)):
                        message("Your posted ledger successfully inserted into database.");
                        $update['ConfirmStatus'] = 1;
                        $this->Common_model->update_data('purchase_demo', $update, 'purchase_demo_id', $confirId);

                        redirect(site_url('demolist'));
                    else:
                        message("Your data successfully inserted into database.");
                        redirect(site_url('viewPurchases/' . $generals_id));
                    endif;
                }
            }
        }

        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );
        $data['editPurchases'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $ivnoiceId);
        $data['editStock'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $ivnoiceId);
        //if cash payment not empty.
        $data['creditAmount'] = $paymentInfo = $this->Inventory_Model->getCreditAmount($ivnoiceId);
        $data['accountId'] = $this->Inventory_Model->getPurchasesAccountId($paymentInfo->generals_id);

        $data['cylinserOut'] = $this->Sales_Model->getCylinderInOutResult($this->dist_id, $ivnoiceId, 23);
        $data['cylinderIn'] = $this->Sales_Model->getCylinderInOutResult($this->dist_id, $ivnoiceId, 24);

        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);
        $data['title'] = 'Purchases Edit';
        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $costCondition = array(
            'dist_id' => $this->dist_id,
            'parentId' => 62,
        );
        $data['costList'] = $this->Common_model->get_data_list_by_many_columns('generaldata', $costCondition);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 2);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchasesEdit', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function purchases_add($confirId = null) {
        if (isPostBack()) {
//form validation rules
            $this->form_validation->set_rules('supplierID', 'Supplier ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher ID', 'required');
            $this->form_validation->set_rules('purchasesDate', 'Purchases Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Date', 'required');
            $this->form_validation->set_rules('category_id[]', 'Product Category', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product Name', 'required');
            $this->form_validation->set_rules('unit_id[]', 'Product Unit', 'required');
            $this->form_validation->set_rules('quantity[]', 'Product Quantigy', 'required');
            $this->form_validation->set_rules('rate[]', 'Product Rate', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('purchases_add'));
            } else {
                $paymentType = $this->input->post('paymentType');
                $accountCr = $this->input->post('accountCr');
                $arrayIndex = count($this->input->post('product_id'));
                $supplier_id = $this->input->post('supplierID');
                $this->db->trans_start();
                if ($paymentType == 2) {
                    //if payment type credit than transaction here
                    $generals_id = $this->creditTransactionInsert();
                } elseif ($paymentType == 1) {
                    //if payment type Cash than transaction here 
                    $generals_id = $this->cashTransactionInsert();
                } elseif ($paymentType == 4) {
                    //if payment type partial than calculate from here
                    $generals_id = $this->partialTransactionInsert();
                } else {
                    $generals_id = $this->chequeTransactionInsert();
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted.Somthing is wrong!!");
                    if (!empty($confirId)):
                        redirect(site_url('PurchaseDemoConfirm/' . $confirId));
                    else:
                        redirect(site_url('purchases_add'));
                    endif;
                } else {
                    if (!empty($confirId)):
                        message("Your posted ledger successfully inserted into database.");
                        $update['ConfirmStatus'] = 1;
                        $this->Common_model->update_data('purchase_demo', $update, 'purchase_demo_id', $confirId);

                        redirect(site_url('demolist'));
                    else:
                        message("Your data successfully inserted into database.");
                        redirect(site_url('viewPurchases/' . $generals_id));
                    endif;
                }
            }
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );

        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);
        $data['title'] = 'Purchases Add';
        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $costCondition = array(
            'dist_id' => $this->dist_id,
            'parentId' => 62,
        );
        $data['costList'] = $this->Common_model->get_data_list_by_many_columns('generaldata', $costCondition);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 2);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchases_add', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    //
    function creditTransactionInsert() {

        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['dueDate'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 2;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['form_id'] = 11;
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//cylinder calculation start here....
        $addReturnAble = $this->input->post('add_returnAble');
        if (!empty($addReturnAble) && $addReturnAble > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['mainInvoiceId'] = $generals_id;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }
        $newCylinderProductCost = 0;
        $otherProductCost = 0;
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $generals_id;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                $stock1['generals_id'] = $cylinderId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['mainInvoiceId'] = $generals_id;
            $cylinderData['updated_by'] = $this->admin_id;
            $CylinderReceiveid = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $CylinderReceiveid;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $generals_id,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'paymentType' => 'Purcahses Voucher',
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//new cylinder product stock
        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.
        if (!empty($otherProductCost)):

            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
        if ($generals_id) {
            return $generals_id;
        }
    }

    function creditTransactionInsertForUpdate($invoiceId) {

        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['dueDate'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 2;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['form_id'] = 11;
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId);
        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//cylinder calculation start here....
        $addReturnAble = $this->input->post('add_returnAble');
        if (!empty($addReturnAble) && $addReturnAble > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['mainInvoiceId'] = $invoiceId;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }
        $newCylinderProductCost = 0;
        $otherProductCost = 0;
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $invoiceId;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                $stock1['generals_id'] = $cylinderId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['mainInvoiceId'] = $invoiceId;
            $cylinderData['updated_by'] = $this->admin_id;
            $CylinderReceiveid = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $CylinderReceiveid;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'paymentType' => 'Purcahses Voucher',
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//new cylinder product stock
        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.
        if (!empty($otherProductCost)):

            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function chequeTransactionInsert() {

        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        if (empty($bankName) || empty($checkNo)) {
            exception("Required field can't be empty.");
            redirect(site_url('purchases_add'));
        }
//when bank transaction than start here.
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 2;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
        $returnQty = array_sum($this->input->post('add_returnAble'));
        if (!empty($returnQty) && $returnQty > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['mainInvoiceId'] = $generals_id;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
//dd 
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $generals_id;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty)) {
                unset($stock1);
                $stock1['generals_id'] = $cylinderId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['mainInvoiceId'] = $generals_id;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['updated_by'] = $this->admin_id;
            $cylinderReceiveId = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $cylinderReceiveId;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $generals_id,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

        //53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//new cylinder product stock

        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.

        if (!empty($otherProductCost)):

            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
        $mrCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
        );
        $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
        $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'invoiceID' => $this->input->post('voucherid'),
            'totalPayment' => array_sum($this->input->post('price')),
            'receitID' => $mrid,
            'mainInvoiceId' => $generals_id,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 2,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
        if ($generals_id) {
            return $generals_id;
        }
    }

    function chequeTransactionInsertForUpdate($invoiceId) {

        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        if (empty($bankName) || empty($checkNo)) {
            exception("Required field can't be empty.");
            redirect(site_url('purchases_add'));
        }
//when bank transaction than start here.
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 2;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId);
        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//insert in generall table
        $returnQty = array_sum($this->input->post('add_returnAble'));
        if (!empty($returnQty) && $returnQty > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['mainInvoiceId'] = $invoiceId;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
//dd
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $invoiceId;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty)) {
                unset($stock1);
                $stock1['generals_id'] = $invoiceId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['mainInvoiceId'] = $invoiceId;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['updated_by'] = $this->admin_id;
            $cylinderReceiveId = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $cylinderReceiveId;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

        //53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//new cylinder product stock

        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.

        if (!empty($otherProductCost)):

            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
        $mrCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
        );
        $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
        $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'invoiceID' => $this->input->post('voucherid'),
            'totalPayment' => array_sum($this->input->post('price')),
            'receitID' => $mrid,
            'mainInvoiceId' => $invoiceId,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 2,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function partialTransactionInsert() {

        $accountCr = $this->input->post('accountCrPartial');
        $thisAllotment = $this->input->post('thisAllotment');
        if (empty($accountCr) || empty($thisAllotment)) {
            exception("Required field can't be empty.");
            redirect(site_url('purchases_add'));
        }
        $add_returnAble = array_sum($this->input->post('add_returnAble'));
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 4;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
//insert in returnAlbe
        if (!empty($add_returnAble) && $add_returnAble > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['mainInvoiceId'] = $generals_id;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }

        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStockCylinder = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $generals_id;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                $stock1['generals_id'] = $cylinderId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
//data insert in stock table
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock table
//Cylinder Stock transaction here
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['mainInvoiceId'] = $generals_id;
            $cylinderData['updated_by'] = $this->admin_id;
            $cylinderReceiveid = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $cylinderReceiveid;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
           
        endif;

        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $generals_id,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

//insert into client vendor ledger
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock

        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.
        if (!empty($otherProductCost)):
            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

//cash calculation here.

        $generals_data['supplier_id'] = $this->input->post('supplierID');
        $generals_data['dist_id'] = $this->dist_id;
        $generals_data['voucher_no'] = $this->input->post('voucherid');
        $generals_data['reference'] = $this->input->post('reference');
        $generals_data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $generals_data['payType'] = 1;
        $generals_data['credit'] = $this->input->post('thisAllotment');
        $generals_data['narration'] = $this->input->post('narration');
        $generals_data['form_id'] = 14;
        $generals_data['updated_by'] = $this->admin_id;
        $generals_data['mainInvoiceId'] = $generals_id;
        $generals_data['created_at'] = $this->timestamp;

        $paymentGeneralId = $this->Common_model->insert_data('generals', $generals_data);



        $supp1 = array(
            'ledger_type' => 2,
            'dist_id' => $this->dist_id,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'amount' => $this->input->post('thisAllotment'),
            'cr' => $this->input->post('thisAllotment'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'updated_by' => $this->admin_id,
            'history_id' => $paymentGeneralId,
            'paymentType' => 'Supplier Payment',
        );
        $this->db->insert('client_vendor_ledger', $supp1);

// generalledger: Pay From
        $cr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCrPartial'),
            'credit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
        $dr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => 50,
            'debit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);

        $totalMoneyReceite = $this->Common_model->get_data_list_by_single_column('moneyreceit', 'dist_id', $this->dist_id);
        $mrid = "RID" . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'invoiceID' => $this->input->post('voucherid'),
            'totalPayment' => $this->input->post('thisAllotment'),
            'receitID' => $mrid,
            'mainInvoiceId' => $generals_id,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 1,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
        if ($generals_id) {
            return $generals_id;
        }
    }

    function partialTransactionInsertForUpdate($invoiceId) {

        $accountCr = $this->input->post('accountCrPartial');
        $thisAllotment = $this->input->post('thisAllotment');
        if (empty($accountCr) || empty($thisAllotment)) {
            exception("Required field can't be empty.");
            redirect(site_url('purchases_add'));
        }
        $add_returnAble = array_sum($this->input->post('add_returnAble'));
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 4;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId);
        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//insert in generall table
//insert in returnAlbe
        if (!empty($add_returnAble) && $add_returnAble > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['mainInvoiceId'] = $invoiceId;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }

        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStockCylinder = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $invoiceId;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                $stock1['generals_id'] = $cylinderId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
//data insert in stock table
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock table
//Cylinder Stock transaction here
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive)):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['mainInvoiceId'] = $invoiceId;
            $cylinderData['updated_by'] = $this->admin_id;
            $cylinderReceiveid = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $cylinderReceiveid;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;

        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

//insert into client vendor ledger
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock

        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.
        if (!empty($otherProductCost)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

//cash calculation here.

        $generals_data['supplier_id'] = $this->input->post('supplierID');
        $generals_data['dist_id'] = $this->dist_id;
        $generals_data['voucher_no'] = $this->input->post('voucherid');
        $generals_data['reference'] = $this->input->post('reference');
        $generals_data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $generals_data['payType'] = 1;
        $generals_data['credit'] = $this->input->post('thisAllotment');
        $generals_data['narration'] = $this->input->post('narration');
        $generals_data['form_id'] = 14;
        $generals_data['updated_by'] = $this->admin_id;
        $generals_data['mainInvoiceId'] = $invoiceId;
        $generals_data['created_at'] = $this->timestamp;
        $paymentGeneralId = $this->Common_model->insert_data('generals', $generals_data);
        $supp1 = array(
            'ledger_type' => 2,
            'dist_id' => $this->dist_id,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'amount' => $this->input->post('thisAllotment'),
            'cr' => $this->input->post('thisAllotment'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'updated_by' => $this->admin_id,
            'history_id' => $paymentGeneralId,
            'paymentType' => 'Supplier Payment',
        );
        $this->db->insert('client_vendor_ledger', $supp1);

// generalledger: Pay From
        $cr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCrPartial'),
            'credit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
        $dr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => 50,
            'debit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);

        $totalMoneyReceite = $this->Common_model->get_data_list_by_single_column('moneyreceit', 'dist_id', $this->dist_id);
        $mrid = "RID" . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'invoiceID' => $this->input->post('voucherid'),
            'totalPayment' => $this->input->post('thisAllotment'),
            'receitID' => $mrid,
            'mainInvoiceId' => $invoiceId,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 1,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function cashTransactionInsert() {

        $accountCr = $this->input->post('accountCr');
        if (!empty($accountCr) && $accountCr > 0) {
            exception("Required field can't be empty.");
            redirect(site_url('purchases_add'));
        }
        $add_returnAble = $this->input->post('add_returnAble');
// echo count($add_returnAble);die;
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 1;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
        $retualAble = array_sum($this->input->post('add_returnAble'));
        if (!empty($retualAble) && $retualAble > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['mainInvoiceId'] = $generals_id;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStockCylinder = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $generals_id;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                $stock1['generals_id'] = $cylinderId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
//data insert in stock table
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock tabl
        $cylinderRecive = $this->input->post('category_id2');
        
       
        
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['mainInvoiceId'] = $generals_id;
            $cylinderData['updated_by'] = $this->admin_id;
            $CylinderReceiveid = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $CylinderReceiveid;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
            
            dumpVar($cylinderAllStock);
            
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $generals_id,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//new cylinder product stock
        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.
        if (!empty($otherProductCost)):
            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//cash calculation here.
        $generals_data['supplier_id'] = $this->input->post('supplierID');
        $generals_data['dist_id'] = $this->dist_id;
        $generals_data['voucher_no'] = $this->input->post('voucherid');
        $generals_data['reference'] = $this->input->post('reference');
        $generals_data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $generals_data['payType'] = 1;
        $generals_data['credit'] = array_sum($this->input->post('price'));
        $generals_data['narration'] = $this->input->post('narration');
        $generals_data['form_id'] = 14;
        $generals_data['mainInvoiceId'] = $generals_id;
        $generals_data['updated_by'] = $this->admin_id;
        $generals_data['created_at'] = $this->timestamp;
        $this->db->insert('generals', $generals_data);
        $generalsIdPayment = $this->db->insert_id();
        $supp1 = array(
            'ledger_type' => 2,
            'dist_id' => $this->dist_id,
            'history_id' => $generalsIdPayment,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'amount' => array_sum($this->input->post('price')),
            'cr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'updated_by' => $this->admin_id,
            'paymentType' => 'Supplier Payment',
        );

        $this->db->insert('client_vendor_ledger', $supp1);
// generalledger: Pay From
        $cr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $generalsIdPayment,
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCr'),
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
        $dr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $generalsIdPayment,
            'dist_id' => $this->dist_id,
            'account' => 50,
            'debit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);
        if ($generals_id) {
            return $generals_id;
        }
    }

    function cashTransactionInsertForUpdate($invoiceId) {

        $accountCr = $this->input->post('accountCr');
        if (!empty($accountCr) && $accountCr > 0) {
            exception("Required field can't be empty.");
            redirect(site_url('purchases_add'));
        }
        $add_returnAble = $this->input->post('add_returnAble');
// echo count($add_returnAble);die;
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 1;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId);
        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//insert in generall table
        $retualAble = array_sum($this->input->post('add_returnAble'));
        if (!empty($retualAble) && $retualAble > 0) {
            $cylinder['supplier_id'] = $this->input->post('supplierID');
            $cylinder['voucher_no'] = $this->input->post('voucherid');
            $cylinder['reference'] = $this->input->post('reference');
            $cylinder['payType'] = $this->input->post('paymentType');
            $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinder['narration'] = $this->input->post('narration');
            $cylinder['form_id'] = 24;
            $cylinder['dist_id'] = $this->dist_id;
            $cylinder['mainInvoiceId'] = $invoiceId;
            $cylinder['updated_by'] = $this->admin_id;
            $cylinderId = $this->Common_model->insert_data('generals', $cylinder);
        }
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStockCylinder = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $invoiceId;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = $this->input->post('unit_id')[$key];
            $stock['date'] = date('Y-m-d');
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                $stock1['generals_id'] = $cylinderId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = $this->input->post('unit_id')[$key];
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
//data insert in stock table
        $this->db->insert_batch('stock', $allStock);
        $this->db->insert_batch('stock', $allStock1);
//insert in stock tabl
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            $cylinderData['supplier_id'] = $this->input->post('supplierID');
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['form_id'] = 23;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['mainInvoiceId'] = $invoiceId;
            $cylinderData['updated_by'] = $this->admin_id;
            $CylinderReceiveid = $this->Common_model->insert_data('generals', $cylinderData);
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = $this->input->post('unit_id2')[$key];
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $CylinderReceiveid;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => array_sum($this->input->post('price')),
            'dr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'debit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//53 Goods Received Clearing account
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 53,
            'credit' => array_sum($this->input->post('price')),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//new cylinder product stock
        if (!empty($newCylinderProductCost)):

            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 173,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//Others product inventory stock.
        if (!empty($otherProductCost)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//cash calculation here.
        $generals_data['supplier_id'] = $this->input->post('supplierID');
        $generals_data['dist_id'] = $this->dist_id;
        $generals_data['voucher_no'] = $this->input->post('voucherid');
        $generals_data['reference'] = $this->input->post('reference');
        $generals_data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $generals_data['payType'] = 1;
        $generals_data['credit'] = array_sum($this->input->post('price'));
        $generals_data['narration'] = $this->input->post('narration');
        $generals_data['form_id'] = 14;
        $generals_data['mainInvoiceId'] = $invoiceId;
        $generals_data['updated_by'] = $this->admin_id;
        $generals_data['created_at'] = $this->timestamp;
        $this->db->insert('generals', $generals_data);
        $generalsIdPayment = $this->db->insert_id();
        $supp1 = array(
            'ledger_type' => 2,
            'dist_id' => $this->dist_id,
            'history_id' => $generalsIdPayment,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'amount' => array_sum($this->input->post('price')),
            'cr' => array_sum($this->input->post('price')),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'updated_by' => $this->admin_id,
            'paymentType' => 'Supplier Payment',
        );

        $this->db->insert('client_vendor_ledger', $supp1);
// generalledger: Pay From
        $cr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $generalsIdPayment,
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCr'),
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
        $dr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $generalsIdPayment,
            'dist_id' => $this->dist_id,
            'account' => 50,
            'debit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function getProductList() {
        $cat_id = $this->input->post('cat_id');
        $productList = $this->Common_model->getPublicProduct($this->dist_id, $cat_id);
        $add = '';
        if (!empty($productList)):
            $add .= "<option value=''></option>";
            foreach ($productList as $key => $value):
                $add .= "<option productName='" . $value->productName . ' [ ' . $value->brandName . ' ]' . "'   value='" . $value->product_id . "'>$value->productName [ $value->brandName ] </option>";
            endforeach;
            echo $add;
            DIE;
        else:
            echo "<option value='' selected disabled>Product Not Available</option>";
            DIE;
        endif;
    }

    function getProductPrice() {
        $product_id = $this->input->post('product_id');
        $productDetails = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $product_id);
        if (!empty($productDetails)):
            echo $productDetails->purchases_price;
        endif;
    }

    function viewPurchases($purchases_id = NULL) {
        $data['title'] = 'Purchases View';
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $purchases_id);
        $data['stockList'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $purchases_id);
        // $data['returanAbleCylinder'] = $this->Common_model->getReturnAbleCylinder($purchases_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['purchasesList']->supplier_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchases_view', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function supplierDashboard($supId) {
        $data['supplierDetails'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $supId);

        $purchasCon = array(
            'supplier_id' => $supId,
            'form_id' => 11,
        );
        $data['purchasesList'] = $this->Common_model->get_data_list_by_many_columns('generals', $purchasCon);
        $paymentCon = array(
            'supplier_id' => $supId,
            'form_id' => 14,
        );
        $data['supplierPayment'] = $this->Common_model->get_data_list_by_many_columns('generals', $paymentCon);
// dumpVar($data['supplierPayment']);
        $data['title'] = 'Supplier Dashboard';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supDashboard', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderInOutJournal() {
        $condtion = array(
            'dist_id' => $this->dist_id,
            'form_id' => 27,
        );
        $data['cylinderList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condtion);
        $data['title'] = 'Cylinder In/Out List';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/cylinderInOutList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function cylinderOpeningView($id) {

        $data['cylinderOpening'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $id);
        $data['cylinderItem'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $id);
        $data['title'] = 'Cylinder Openig';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/inOutOpeningView', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function cylinderOpening() {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 28,
        );
        $data['cylinderOpening'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);

        $data['title'] = 'Cylinder Openig';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/inOutOpenigList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function cylinderOpeningAdd() {




        if (isPostBack()) {
            $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $data['voucher_no'] = $this->input->post('voucherid');
            $data['form_id'] = 28;
            $data['dist_id'] = $this->dist_id;
            $data['narration'] = $this->input->post('narration');
            $insert = $this->Common_model->insert_data('generals', $data);
            $productId = $this->input->post('productId');
            $qtyInAll = array();
            foreach ($productId as $key => $value) :
                unset($qtyIn1);
                unset($qtyOut1);
                $qtyIn = $this->input->post('qtyIn')[$key];
                if (!empty($qtyIn)) {
                    $qtyIn1['category_id'] = 2;
                    $qtyIn1['product_id'] = $value;

                    $qtyIn1['quantity'] = $qtyIn;
                    $qtyIn1['generals_id'] = $insert;
                    $qtyIn1['type'] = 'Cin';
                    $qtyIn1['form_id'] = 28;
                    $qtyIn1['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $qtyIn1['dist_id'] = $this->dist_id;
                    $qtyIn1['updated_by'] = $this->admin_id;
                    $qtyIn1['created_at'] = $this->timestamp;
                    $qtyInAll[] = $qtyIn1;
                }
            endforeach;
            $this->db->insert_batch('stock', $qtyInAll);
            message("Your cylinder opening stock inserted successfully.");
            redirect(site_url('cylinderOpening'));
        }
        $data['Cylinder'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['title'] = 'Cylinder Openig';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/inOutOpenig', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function getSupplierClosingBalance() {
        $supplierId = $this->input->post('supplierId');
        $closingAmount = $this->Inventory_Model->getSupplierClosingBalance($distId, $supplierId);
        if ($closingAmount) {
            echo $closingAmount;
        }
    }

    function cylinderInOutJournalAdd() {
        if (isPostBack()) {

//dumpVar($_POST);
            $this->db->trans_start();
            $payType = $this->input->post('payType');
            $cust = $this->input->post('customer_id');
            $supid = $this->input->post('supplier_id');
            if (!empty($cust)):
                $cylinderData['customer_id'] = $cust;
            endif;
            if (!empty($supid)):
                $cylinderData['supplier_id'] = $supid;
            endif;
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['qtyIn'] = array_sum($this->input->post('qtyIn'));
            $cylinderData['qtyOut'] = array_sum($this->input->post('qtyOut'));
            $cylinderData['form_id'] = 27;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['updated_by'] = $this->admin_id;
            $CylinderReceiveid = $this->Common_model->insert_data('generals', $cylinderData);



            $productId = $this->input->post('productId');
            $qtyOutAll = array();
            $qtyInAll = array();
            foreach ($productId as $key => $value) :
                unset($qtyIn1);
                unset($qtyOut1);
                $qtyOut = $this->input->post('qtyOut')[$key];
                $qtyIn = $this->input->post('qtyIn')[$key];
                if (!empty($qtyIn)) {
                    $qtyIn1['category_id'] = 2;
                    $qtyIn1['product_id'] = $value;
                    $qtyIn1['quantity'] = $qtyIn;
                    $qtyIn1['type'] = 'Cin';
                    $qtyIn1['form_id'] = 24;
                    $qtyIn1['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $qtyIn1['generals_id'] = $CylinderReceiveid;
                    $qtyIn1['dist_id'] = $this->dist_id;
                    if (!empty($cust)):
                        $qtyIn1['customerId'] = $cust;
                    endif;
                    if (!empty($supid)):
                        $qtyIn1['supplierId'] = $supid;
                    endif;

                    $qtyIn1['updated_by'] = $this->admin_id;
                    $qtyIn1['created_at'] = $this->timestamp;
                    $qtyInAll[] = $qtyIn1;
                }
                if (!empty($qtyOut)) {
                    $qtyOut1['category_id'] = 2;
                    $qtyOut1['product_id'] = $value;
                    $qtyOut1['quantity'] = $qtyOut;
                    $qtyOut1['type'] = 'Cout';
                    $qtyOut1['form_id'] = 23;
                    $qtyOut1['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $qtyOut1['generals_id'] = $CylinderReceiveid;
                    $qtyOut1['dist_id'] = $this->dist_id;
                    if (!empty($cust)):
                        $qtyOut1['customerId'] = $cust;
                    endif;
                    if (!empty($supid)):
                        $qtyOut1['supplierId'] = $supid;
                    endif;
                    $qtyOut1['updated_by'] = $this->admin_id;
                    $qtyOut1['created_at'] = $this->timestamp;
                    $qtyOutAll[] = $qtyOut1;
                }
            endforeach;

            $this->db->insert_batch('stock', $qtyInAll);
            $this->db->insert_batch('stock', $qtyOutAll);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your data can't be inserted into database.");
                redirect(site_url('cylinderInOutJournalAdd'));
            } else {
                message("Your data successfylly inserted into database.");
                redirect(site_url('cylinderInOutJournal'));
            }
        }

        $vCondition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 27,
        );
        $totalVoucher = $this->Common_model->get_data_list_by_many_columns('generals', $vCondition);
        $data['voucherID'] = "CV" . date("y") . date("m") . str_pad(count($totalVoucher) + 1, 4, "0", STR_PAD_LEFT);

        $data['Cylinder'] = $this->Common_model->getPublicProduct($this->dist_id, 2);
        $data['title'] = 'Cylinder In/Out Journal';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/cylinderInOutAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function cylinderInOutJournalView($id = null) {

        $condition = array(
            'dist_id' => $this->dist_id,
            'category_id' => 1
        );

        $data['inOutView'] = $inOutView = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $id);
        if (!empty($inOutView->supplier_id)) {
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $inOutView->supplier_id);
        } else {
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $inOutView->customer_id);
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['inOutItem'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $id);
        $data['title'] = 'Cylinder In/Out View';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/cylinderInOutView', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function cylinderInOutJournalEdit($id = null) {

        if (isPostBack()) {



            $this->db->trans_start();
            $payType = $this->input->post('payType');
            $cust = $this->input->post('customer_id');
            $supid = $this->input->post('supplier_id');
            if (!empty($cust)):
                $cylinderData['customer_id'] = $cust;
            endif;
            if (!empty($supid)):
                $cylinderData['supplier_id'] = $supid;
            endif;
            $cylinderData['voucher_no'] = $this->input->post('voucherid');
            $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $cylinderData['narration'] = $this->input->post('narration');
            $cylinderData['qtyIn'] = array_sum($this->input->post('qtyIn'));
            $cylinderData['qtyOut'] = array_sum($this->input->post('qtyOut'));
            $cylinderData['form_id'] = 27;
            $cylinderData['payType'] = $payType;
            $cylinderData['dist_id'] = $this->dist_id;
            $cylinderData['updated_by'] = $this->admin_id;
            $this->Common_model->update_data('generals', $cylinderData, 'generals_id', $id);
            $productId = $this->input->post('productId');
            $qtyOutAll = array();
            $qtyInAll = array();
            foreach ($productId as $key => $value) :
                unset($qtyIn1);
                unset($qtyOut1);
                $qtyOut = $this->input->post('qtyOut')[$key];
                $qtyIn = $this->input->post('qtyIn')[$key];
                if (!empty($qtyIn)) {
                    $qtyIn1['category_id'] = 2;
                    $qtyIn1['product_id'] = $value;
                    $qtyIn1['quantity'] = $qtyIn;
                    $qtyIn1['type'] = 'Cin';
                    $qtyIn1['form_id'] = 24;
                    $qtyIn1['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $qtyIn1['generals_id'] = $id;
                    $qtyIn1['dist_id'] = $this->dist_id;
                    if (!empty($cust)):
                        $qtyIn1['customerId'] = $cust;
                    endif;
                    if (!empty($supid)):
                        $qtyIn1['supplierId'] = $supid;
                    endif;

                    $qtyIn1['updated_by'] = $this->admin_id;
                    $qtyIn1['created_at'] = $this->timestamp;
                    $qtyInAll[] = $qtyIn1;
                }
                if (!empty($qtyOut)) {
                    $qtyOut1['category_id'] = 2;
                    $qtyOut1['product_id'] = $value;
                    $qtyOut1['quantity'] = $qtyOut;
                    $qtyOut1['type'] = 'Cout';
                    $qtyOut1['form_id'] = 23;
                    $qtyOut1['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $qtyOut1['generals_id'] = $id;
                    $qtyOut1['dist_id'] = $this->dist_id;
                    if (!empty($cust)):
                        $qtyOut1['customerId'] = $cust;
                    endif;
                    if (!empty($supid)):
                        $qtyOut1['supplierId'] = $supid;
                    endif;
                    $qtyOut1['updated_by'] = $this->admin_id;
                    $qtyOut1['created_at'] = $this->timestamp;
                    $qtyOutAll[] = $qtyOut1;
                }
            endforeach;
            $this->Common_model->delete_data('stock', 'generals_id', $id);
            $this->db->insert_batch('stock', $qtyInAll);
            $this->db->insert_batch('stock', $qtyOutAll);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your data can't be inserted into database.");
                redirect(site_url('cylinderInOutJournalAdd'));
            } else {
                message("Your data successfylly inserted into database.");
                redirect(site_url('cylinderInOutJournal'));
            }
        }
        $data['cylinderInfo'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $id);
        $data['stockInfo'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $id);
        $data['Cylinder'] = $this->Common_model->getPublicProduct($this->dist_id, 2);
        $data['title'] = 'Cylinder In/Out Journal';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/cylinderInOutEdit', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function stockReport() {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Inventory Stock Report';
        $data['categoryList'] = $this->Common_model->getPublicProductCatbyCilinder($this->dist_id, 2);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = 'Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/stockReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function productWiseCylinderStock() {

        $condtion = array(
            'dist_id' => $this->dist_id,
            'category_id' => 2,
        );
        $data['title'] = 'Product Wise Cylinder Stock';
        $data['dist_id'] = $this->dist_id;
        $data['productList'] = $this->Common_model->getPublicProduct($this->dist_id, 2);
        $data['productList2'] = $this->Common_model->getPublicProduct($this->dist_id, 2);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/productWiseCylinderStock', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function newCylinderStockReport() {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Cylinder Stock Report';
        $data['categoryList'] = $this->Common_model->getPublicProductCatbyCilinder($this->dist_id, 1);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = 'Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinderStock', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function stockReport_export_excel() {
        $file = 'Stock Report_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');

        $account = $data['account'] = $_SESSION['account'];
        $start_date = $data['fromdate'] = $_SESSION['start_date'];
        $end_date = $data['todate'] = $_SESSION['end_date'];

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['categoryList'] = $this->Common_model->get_data_list_by_single_column('productcategory', 'dist_id', $this->dist_id);

        $this->load->view('excel_report/inventory/stockReport_export_excel', $data);
        unset($_SESSION['full_array']);
    }

    function categoryReport() {
        $data['title'] = 'Category Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/categoryReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function itemeport() {
        $data['title'] = 'Item Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/itemStockReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

}
