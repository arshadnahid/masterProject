<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ImportController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        //$this->load->model('Common_model', 'Finane_Model', 'Inventory_Model', 'Sales_Model');
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
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

    function updateImportProduct() {
        $updateId = $this->input->post('updateId');
        $data['catId'] = $this->input->post('category_id');
        $data['brandId'] = $this->input->post('brand');
        $data['unitId'] = $this->input->post('unit');
        $data['productName'] = $this->input->post('productName');
        $data['catName'] = $this->Common_model->tableRow('productcategory', 'category_id', $data['catId'])->title;
        $data['brandName'] = $this->Common_model->tableRow('brand', 'brandId', $data['brandId'])->brandName;
        $data['unitName'] = $this->Common_model->tableRow('unit', 'unit_id', $data['unitId'])->unitTtile;
        $data['purchasesPrice'] = $this->input->post('purchases_price');
        $data['salesPrice'] = $this->input->post('salesPrice');
        $data['retailprice'] = $this->input->post('retailPrice');
        $this->Common_model->update_data('productimport', $data, 'importId', $updateId);
        message("Updated Successfully");
        redirect(site_url('productImport'));
    }

    function saveImportProduct() {
        $allResult = $this->Common_model->get_data_list_by_single_column('productimport', 'dist_id', $this->dist_id);
        $allData = array();
        $productOrgId = $this->db->where('dist_id', $this->dist_id)->or_where('dist_id', 1)->count_all_results('product');
        $increment = 1;
        foreach ($allResult as $key => $value) {
            $result = $this->Common_model->checkDuplicateModel($this->dist_id, $value->productName, $value->catId, $value->brandId);
            if (empty($result)) {
                $productId = "PID" . date('y') . date('m') . str_pad($productOrgId + $increment, 4, "0", STR_PAD_LEFT);
                $data['category_id'] = $value->catId;
                $data['unit_id'] = $value->unitId;
                $data['brand_id'] = $value->brandId;
                $data['product_code'] = $this->input->post('product_code');
                $data['productName'] = $value->productName;
                $data['purchases_price'] = $value->purchasesPrice;
                $data['salesPrice'] = $value->salesPrice;
                $data['retailPrice'] = $value->retailprice;
                $data['alarm_qty'] = 0;
                $data['product_code'] = $productId;
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $allData[] = $data;
                $increment++;

                $insertID = $this->Common_model->insert_data('product', $data);
                $category_id = $value->catId;
                $brand_id = $value->brandId;

                if ($category_id == 1) {
                    //Empty Cylinder
                    $ledger_parent_id = $this->config->item("New_Cylinder_Stock");//25,;
                } else if ($category_id == 2) {
                    $ledger_parent_id = $this->config->item("Refill_Stock");//26,;
                } else {
                    $ledger_parent_id = $this->config->item("Inventory_Finished_Goods_Stock");//21,;
                }
                $for = 1;
                $productcategory_info=$this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $category_id);
                $brand_info=$this->Common_model->get_single_data_by_single_column('brand', 'brandId', $brand_id);
                $productName = $productcategory_info->title .' '.$this->input->post('productName').' '.$brand_info->brandName ;
                create_ledger_cus_sup_product($insertID, $productName, $ledger_parent_id, $for,$this->admin_id);
            }
        }
        if(!empty($allData)){
              $this->db->insert_batch('product', $allData);
                message("Your product successfully save into product list");
        }else{
            notification("You have made no change to save");
        }


        $this->Common_model->delete_data('productimport', 'dist_id', $this->dist_id);



        if (!empty($allData)):
            $msg= 'Your csv file '.' '.$this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url('setupImport/' ));

        else:
            $msg= 'Your csv file '.' '.$this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url('setupImport' ));
        endif;
        redirect(site_url('productList'));
    }

    function productImport() {
        if (isPostBack()) {
            if (!empty($_FILES['proImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //if ($row != 0):
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $catId = isset($readRowData[0]) ? $readRowData[0] : ''; //get category id
                        $brandId = isset($readRowData[1]) ? $readRowData[1] : ''; //get brand id
                        $unitId = isset($readRowData[2]) ? $readRowData[2] : ''; //get unit id
                        $productId = isset($readRowData[3]) ? $readRowData[3] : ''; //get product id
                        $purchasPrice = isset($readRowData[4]) ? $readRowData[4] : ''; //get purchases price
                        $retailPrice = isset($readRowData[5]) ? $readRowData[5] : ''; //get retail price
                        $wholesalePrice = isset($readRowData[6]) ? $readRowData[6] : ''; //get whole sale price
                        /* check duplicate supplier id end */
                        //product Category
                        $catCondition = array('title' => $catId);
                        $catInfo = $this->Common_model->rowResult('productcategory', $catCondition, $this->dist_id);
                        $data['catId'] = $catInfo->category_id;
                        $data['catName'] = $catId;
                        //brand
                        $brandCondition = array('brandName' => $brandId);
                        $brandInfo = $this->Common_model->rowResult('brand', $brandCondition, $this->dist_id);
                        $data['brandId'] = $brandInfo->brandId;
                        $data['brandName'] = $brandId;
                        //unit
                        $unitCondition = array('unitTtile' => $unitId);
                        $unitInfo = $this->Common_model->rowResult('unit', $unitCondition, $this->dist_id);
                        $data['unitId'] = $unitInfo->unit_id;
                        $data['unitName'] = $unitId;
                        $data['productName'] = $productId;
                        $data['purchasesPrice'] = $purchasPrice;
                        $data['salesPrice'] = $wholesalePrice;
                        $data['retailprice'] = $retailPrice;
                        $data['dist_id'] = $this->dist_id;
                        //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                        $storeData[] = $data; //store each single row;
                    //endif;
                    endif;
                    $row++;
                }
                
                
             
                
                
                if (!empty($storeData)):
                    $this->Common_model->delete_data('productimport', 'dist_id', $this->dist_id);
                    $this->db->insert_batch('productimport', $storeData);
                   // echo $this->db->last_query();die;
                    
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv successfully inserted into database.");
                    redirect(site_url('productImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('productImport'));
                endif;
            endif;
        }
        $data['productList'] = $this->Inventory_Model->getImportProduct($this->dist_id);
        $data['title'] = 'Product Import';
        $data['mainContent'] = $this->load->view('distributor/inventory/import/productImport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function setupImport() {
        if (isPostBack()) {
            if (!empty($_FILES['supplierImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('supplierImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['supplierImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                $incrementSerial = 1;
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $subCondition = array('dist_id' => $this->dist_id);
                        $dynamicId = $this->Common_model->getDynamicId('supplier', $subCondition, 'supID', 'sup_id', 7, $incrementSerial, 'SID');
                        /* check duplicate supplier id end */
                        $data['supID'] = $dynamicId;
                        $data['supName'] = isset($readRowData[0]) ? $readRowData[0] : '';
                        $data['supEmail'] = isset($readRowData[1]) ? $readRowData[1] : '';
                        $data['supPhone'] = isset($readRowData[2]) ? $readRowData[2] : '';
                        $data['supAddress'] = isset($readRowData[3]) ? $readRowData[3] : '';
                        $data['dist_id'] = $this->dist_id;
                        $data['updated_by'] = $this->admin_id;
                        if (!empty($data['supID']) && !empty($data['supName'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                    $incrementSerial++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('supplier', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('setupImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('setupImport'));
                endif;
            elseif (!empty($_FILES['customerImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('customerImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['customerImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                $incrementSerial = 1;
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $cusCondition = array('dist_id' => $this->dist_id);
                        $dynamicId = $this->Common_model->getDynamicId('customer', $cusCondition, 'customerID', 'customer_id', 7, $incrementSerial, 'CID');
                        /* check duplicate supplier id end */
                        $data['customerID'] = $dynamicId;
                        $data['customerName'] = isset($readRowData[0]) ? $readRowData[0] : '';
                        $data['customerPhone'] = isset($readRowData[1]) ? $readRowData[1] : '';
                        $data['customerEmail'] = isset($readRowData[2]) ? $readRowData[2] : '';
                        $data['customerAddress'] = isset($readRowData[3]) ? $readRowData[3] : '';
                        $data['dist_id'] = $this->dist_id;
                        $data['updated_by'] = $this->admin_id;
                        if (!empty($data['customerName'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                        $incrementSerial++;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('customer', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('setupImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('setupImport'));
                endif;
            elseif (!empty($_FILES['proCatImport']['name']))://product cat list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proCatImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proCatImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //dumpVar($readRowData);
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'title' => $readRowData[0],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('productcategory', $condition);
                        if (empty($exitsID)):
                            $data['title'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        /* check duplicate supplier id end */
                        if (!empty($data['title'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('productcategory', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('setupImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('setupImport'));
                endif;
            elseif (!empty($_FILES['proImport']['name']))://product list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'product_code' => $readRowData[3],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('product', $condition);
                        if (empty($exitsID)):
                            $data['product_code'] = isset($readRowData[0]) ? $readRowData[0] : '';
                        else:
                            $proID = $this->Common_model->getProID($this->dist_id);
                            $data['product_code'] = $this->Common_model->checkDuplicateProID($proID, $this->dist_id);
                        endif;
                        /* check duplicate supplier id end */
                        $data['brand_id'] = isset($readRowData[0]) ? $readRowData[0] : '';
                        $data['category_id'] = isset($readRowData[1]) ? $readRowData[1] : '';
                        $data['productName'] = isset($readRowData[2]) ? $readRowData[2] : '';
                        $data['product_code'] = isset($readRowData[3]) ? $readRowData[3] : '';
                        $data['purchases_price'] = isset($readRowData[4]) ? $readRowData[4] : '';
                        $data['salesPrice'] = isset($readRowData[5]) ? $readRowData[5] : '';
                        $data['retailPrice'] = isset($readRowData[6]) ? $readRowData[6] : '';
                        $data['dist_id'] = $this->dist_id;
                        $data['updated_by'] = $this->admin_id;
                        if (!empty($data['product_code']) && !empty($data['productName'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('product', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('setupImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('setupImport'));
                endif;
            elseif (!empty($_FILES['BrandImport']['name']))://product cat list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('BrandImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['BrandImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //dumpVar($readRowData);
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'brandName' => $readRowData[0],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('brand', $condition);
                        if (empty($exitsID)):
                            $data['brandName'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        /* check duplicate supplier id end */
                        if (!empty($data['brandName'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('brand', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('setupImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('setupImport'));
                endif;
            elseif (!empty($_FILES['UnitImport']['name']))://product cat list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('UnitImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['UnitImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //dumpVar($readRowData);
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'unitTtile' => $readRowData[0],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('unit', $condition);
                        if (empty($exitsID)):
                            $data['unitTtile'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        /* check duplicate supplier id end */
                        if (!empty($data['unitTtile'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('unit', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('setupImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('setupImport'));
                endif;
            elseif (!empty($_FILES['referenceImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('referenceImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['referenceImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $condition = array(
                            'referencePhone' => $readRowData[1],
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('reference', $condition);
                        if (empty($exitsID)):
                            $data['referenceName'] = isset($readRowData[0]) ? $readRowData[0] : '';
                            /* check duplicate supplier id end */
                            $data['referencePhone'] = isset($readRowData[1]) ? $readRowData[1] : '';
                            $data['referenceEmail'] = isset($readRowData[2]) ? $readRowData[2] : '';
                            $data['referenceAddress'] = isset($readRowData[3]) ? $readRowData[3] : '';
                            $data['dist_id'] = $this->dist_id;
                            $data['updated_by'] = $this->admin_id;
                        endif;
                        if (!empty($data['referenceName']) && !empty($data['referencePhone'])):
                            $storeData[] = $data; //store each single row;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->insert_batch('reference', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('setupImport'));
                else:
                    notification("Your csv file not inserted.please check csv file format properly.");
                    redirect(site_url('setupImport'));
                endif;
            endif;
        }
        $data['title'] = 'Import-Setup';
        $data['mainContent'] = $this->load->view('distributor/inventory/import/setup', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function upload_data($param1 = '') {
        if ($param1 == 'do_upload') {
            $config['upload_path'] = './uploads/attendance_file/';
            $config['allowed_types'] = 'xl|txt|csv|mdb';
            $config['file_name'] = date("Y-m-d");
            $this->load->library('upload');
            $this->upload->initialize($config);
            $upload = $this->upload->do_upload('attendance_file');
            $data = $this->upload->data();
            $this->load->helper('file');
            $file = fopen($data['full_path'], "r");
            $file_date = $data['client_name'];
            $file_date = substr($file_date, 3, 8);
            $line = FALSE;
            $att_data = array();
            $file = $_FILES['attendance_file']['tmp_name'];
            $handle = fopen($file, "r");
            $c = 0;
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
//
//                echo "<pre>";
//                print_r($filesop);
                $count_value = count($filesop);
                if ($count_value == '42') {
                    $punch_card = str_replace("'", "", trim($filesop[25]));
                    $date = substr($filesop[37], 1, 10);
                    $time = str_replace("'", "", substr($filesop[22], 0, 6));
                    //$time = date('h:m', strtotime($time2));
//                    echo $time;
//                    echo "<br>";
                    if ($time and $punch_card) {
                        $att_data[] = array('date' => $date, 'time' => $time, 'punch_card' => $punch_card);
                    }
                }
            }
            $this->db->where('date', $date);
            $already_exit = $this->db->get('punch_info2')->row_array();
//
//          die;
//            echo "<pre>";
//            print_r($att_data);
//            die;
            if (empty($already_exit)) {
                $this->db->insert_batch('punch_info2', $att_data);
            }
            //$this->session->set_flashdata('success', 'File uploaded.');
            $query = $this->db->get('punch_info2');
            $all_punch_info = $query->result_array();
            if (!empty($all_punch_info)) {
                foreach ($all_punch_info as $each_punch_info):
                    $this->db->select_min('time');
                    $this->db->where('date', $each_punch_info['date']);
                    $this->db->where('punch_card', $each_punch_info['punch_card	']);
                    $intime = $this->db->get('punch_info')->row_array();
                    $this->db->select_max('time');
                    $this->db->where('date', $each_punch_info['date']);
                    $this->db->where('punch_card', $each_punch_info['punch_card	']);
                    $outtime = $this->db->get('punch_info')->row_array();
                    $date2 = $date = $each_punch_info['date'];
                    $punch_card2 = $each_punch_info['punch_card	'];
                    $intime2 = $intime['time'];
                    $outtime2 = $outtime['time'];
                    $query = $this->db->get_where('punch_info', array('punch_card' => $each_punch_info['punch_card'], 'date' => $each_punch_info['date']));
                    $already_insert = $query->result_array();
                    if (empty($already_insert)) {
                        if ($intime2 and $punch_card)
                            $att_data[] = array('date' => $date2, 'time' => $intime2, 'punch_card' => $punch_card2);
                        if ($outtime2 and $punch_card)
                            $att_data[] = array('date' => $date2, 'time' => $outtime2, 'punch_card' => $punch_card2);
                        $this->db->insert_batch('punch_info', $att_data);
                    }
                endforeach;
            }
            $this->session->set_flashdata('success', 'File uploaded.');
        }
        $this->defaults['page_title'] = translate('upload_data');
        $this->defaults['page_name'] = 'upload_data';
        $this->load->view('index', $this->defaults);
    }

}
