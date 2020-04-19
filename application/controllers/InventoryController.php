<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class InventoryController extends CI_Controller
{

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
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

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    function updateProductInfo()
    {

        $importId = $this->input->post('importId');
        $data['productInfo'] = $this->Common_model->get_single_data_by_single_column('productimport', 'importId', $importId);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['catList'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        return $this->load->view('distributor/ajax/importProduct', $data);
    }

    public function lowInventoryReport()
    {
        $data['title'] = 'Low Inventory Report';
        $data['dist_id'] = $this->dist_id;
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/lowInventoryReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function brandWiseProfit()
    {
        if (isPostBack()) {
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $brandId = $this->input->post('brandId');
            $data['productLedger'] = $this->Inventory_Model->getProductLedger($start_date, $end_date, $productId, $this->dist_id);
            if ($productId != 'all') {
                $data['productOpening'] = $this->Inventory_Model->getProductLedgerOpening($start_date, $end_date, $productId, $this->dist_id);
            }
        }
        $data['title'] = 'Product Ledger';
        $data['dist_id'] = $this->dist_id;
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/productledger', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }



    function productWisePurchasesReport()
    {
        $data['title'] = 'Product Wise Purchases Report';
        $data['dist_id'] = $this->dist_id;
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/productWisePurchases', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function checkDuplicateProductUpdate()
    {
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

    function productList()
    {
        $data['title'] = 'Product List';
        $data['mainContent'] = $this->load->view('distributor/inventory/product/productList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }


    function addProduct()
    {
        if (isPostBack()) {
//validation rules
            $this->form_validation->set_rules('product_code', 'Product Code', 'required');
            $this->form_validation->set_rules('category_id', 'Product Category', 'required');
            $this->form_validation->set_rules('brand', 'Product Branch', 'required');
            $this->form_validation->set_rules('productName', 'Product Name', 'required');
            $this->form_validation->set_rules('product_type_id', 'Product Type', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('addProduct'));
            } else {
                $data['category_id'] = $this->input->post('category_id');
                $data['unit_id'] = $this->input->post('unit');
                $data['product_code'] = $this->input->post('product_code');
                $data['productName'] = $this->input->post('productName');
                $data['purchases_price'] = $this->input->post('purchases_price');
                $data['salesPrice'] = $this->input->post('salesPrice');
                $data['retailPrice'] = $this->input->post('retailPrice');
                $data['brand_id'] = $this->input->post('brand');
                $data['alarm_qty'] = $this->input->post('alarm_qty');
                $data['product_type_id'] = $this->input->post('product_type_id');

                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertid = $this->Common_model->insert_data('product', $data);

//                echo "<pre>";
//                print_r($this->db->last_query());
//                exit;

                if (!empty($insertid)):
                    message("New product inserted successfully.");
                    redirect(site_url('productList'));
                else:
                    message("Product Can't inserted.");
                    redirect(site_url('addProduct'));
                endif;
            }
        }
        $productOrgId = $this->db->where('dist_id', $this->dist_id)->or_where('dist_id', 1)->count_all_results('product') + 1;
        $data['productid'] = "PID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);
        $data['title'] = 'Add Product';
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['product_type_list'] = $this->Common_model->getProductType($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/product/addNewProduct', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }


    function Supplier()
    {
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

    function supplierList()
    {
        $data['title'] = 'Setup || Supplier';
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderSummaryReport()
    {
        if (isPostBack()) {
            $type_id = $data['type_id'] = $this->input->post('type_id');
            $searchId = $data['searchId'] = $this->input->post('searchId');
            $productId = $data['productId'] = $this->input->post('productId');
            $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['dataresult'] = $this->Inventory_Model->getCusSupProductSummary($type_id, $searchId, $productId, $from_date, $to_date, $this->dist_id);
        }
        $data['productList'] = $this->Common_model->getProductListByCategory(2, $this->dist_id);
        $data['title'] = 'Cylinder Summary Report';
        $data['dist_id'] = $this->dist_id;
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinderCombineReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderLedger()
    {
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

    public function cylinderStockReport()
    {
        $data['title'] = 'Customer Wise Cylinder';
        $data['dist_id'] = $this->dist_id;
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinderStockReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function supplierPurchasesReport()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Supplier Purchases Report';
        $data['title'] = 'Supplier Purchases Report';
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supplierPurchasesReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function supplierPurchasesReport_export_excel()
    {
        $file = 'Supplier Purchases Report_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['supplierList'] = $this->Common_model->get_data_list_by_single_column('supplier', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/inventory/supplierPurchasesReport_export_excel', $data);
        unset($_SESSION['full_array']);
    }

    public function purchasesReport()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Purchases Report';
        $data['title'] = 'Purchases || Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/purchasesReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function purchasesReport_export_excel()
    {
        $file = 'Purchases Report_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/inventory/purchasesReport_export_excel', $data);
        unset($_SESSION['full_array']);
    }


    function deleteProduct($deletedId)
    {
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


    public function openigInventoryImport()
    {
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
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function deleteInventoryOpening($deleteId)
    {
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

    public function getImportProductList()
    {
        $data['productList'] = $productList = $this->Common_model->getImportProduct($this->dist_id);
       
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=importProductList.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('#', 'Category ID(Not Change)', 'Product ID(Not Change)', 'Product Code(Not Change)', 'Product Category(Not Change)', 'Product Name(Not Change)', 'Quantity*', 'Unit price*', 'Total Price','Brand'));
        foreach ($productList as $key => $eachProduct):
            fputcsv($output, $eachProduct);
        endforeach;
        fclose($output);
    }

    public function getImportProductListExcl()
    {

        $this->load->library("excel");
        require_once './application/libraries/PHPExcel.php';
        require_once './application/libraries/PHPExcel/IOFactory.php';
        $objPHPExcel = new PHPExcel();
//        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
//        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Product List');
        $default_border = array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000'),
        );

        $acc_default_border = array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => 'c7c7c7'),
        );
        $outlet_style_header = array(
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 10,
                'name' => 'Arial',
                'bold' => true,
            ),
        );
        $top_header_style = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff03'),
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 15,
                'name' => 'Arial',
                'bold' => true,
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        $style_header = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff03'),
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Arial',
                'bold' => true,
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
        );
        $account_value_style_header = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Arial',
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
        );
        $text_align_style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff03'),
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Arial',
                'bold' => true,
            ),
        );
        /*$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Product List');*/

        /*$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($top_header_style);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($top_header_style);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($top_header_style);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($top_header_style);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($top_header_style);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($top_header_style);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($top_header_style);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($top_header_style);*/

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sl');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Category ID(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Product ID(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Product Code(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Product Category(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Product Name(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Quantity*');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Unit price');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Total Price');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Brand');


        $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($style_header);
        /*$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($style_header);*/

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

        $row = 2;
        $custDtaData  =  $productList = $this->Common_model->getImportProduct($this->dist_id);

        foreach ($custDtaData as $value)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $value['sl']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $value['category_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $value['product_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $value['product_code']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $value['productCat']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $value['productName']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $value['quantity']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $value['unitPrice']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $value['totalPrice']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $value['Brand']);
            $row++;
        }

        header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
        header('Content-Disposition: attachment;filename="importProductOpening.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');

    }





    public function inventoryAdjustment()
    {
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

    public function cylinderPurchases()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 23,
        );
        $data['title'] = 'Cylinder Purchases';
        $data['cylinderList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinder/cylinderPurchases', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function cylinderExchange()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 24,
        );
        $data['title'] = 'Cylinder Exchange';
        $data['cylinderExchangeList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderSale/saleList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function viewCylinderExchange($exchangeId)
    {
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

    public function cylinderExchangeAdd()
    {
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
                $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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

    public function viewCylinder($viewId)
    {
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

    public function cylinderPurchases_add()
    {
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
                $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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

    public function purchases_list()
    {
        $data['title'] = 'Purchases List';
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchases_list', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function suppay_ajax()
    {
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
                    $result .= '<td align="right"><input type="hidden" value="' . $this->Sales_Model->generals_voucher($row['voucher_no']) . '" id="dueAmount_' . $key . '">' . number_format((float)$this->Sales_Model->generals_voucher($row['voucher_no']), 2, '.', ',') . '</td>';
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

    public function supplierPaymentAdd()
    {
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
        /* page navbar details */
        $data['title'] = 'Supplier Payment';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Supplier Payment List';
        $data['link_page_url'] = 'supplierPayment';
        $data['link_icon'] = $this->link_icon_list;
        /* page navbar details */
        
        $data['purchasesList'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supplierPayment', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function viewMoneryReceiptSup($receiptId, $voucherId = NULL)
    {
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

    public function supplierPayment()
    {
        $data['title'] = 'Supplier Payment List';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/supplierPaymentList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function editPurchases()
    {
//edit code here.
    }



    public function purchases_edit($ivnoiceId = null)
    {
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
        $data['creditAmount2'] = $paymentInfo = $this->Inventory_Model->getCreditAmount($ivnoiceId);

        //echo $this->db->last_query();die;
        $data['accountIdForEdit'] = $this->Inventory_Model->getPurchasesAccountId($paymentInfo->generals_id);
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['cylinserOut'] = $this->Sales_Model->getCylinderInOutResult2($this->dist_id, $ivnoiceId, 23);
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

        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);

        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchasesEdit', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function purchases_add($confirId = null)
    {
        if (isPostBack()) {
//form validation rules
            $this->form_validation->set_rules('supplierID', 'Supplier ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher ID', 'required');
            $this->form_validation->set_rules('purchasesDate', 'Purchases Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Date', 'required');
            $this->form_validation->set_rules('category_id[]', 'Product Category', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product Name', 'required');
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
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);

        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);

        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);

        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchasesWithPos', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function creditTransactionInsert()
    {


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
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);

//cylinder calculation start here....
        $addReturnAble = $this->input->post('add_returnAble');
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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                /*
                 * EDIT By Nahid
                 *
                 * $stock1['generals_id'] = $cylinderId;
                 *                  */
                $stock1['generals_id'] = $generals_id;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;

//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):

            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $generals_id;
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
        );
        $this->db->insert('client_vendor_ledger', $supp);


//insert into client vendor ledger

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

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
//LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

        if ($generals_id) {
            return $generals_id;
        }
    }

    function creditTransactionInsertForUpdate($invoiceId)
    {

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
//loader
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                //$stock1['generals_id'] = $cylinderId;
                $stock1['generals_id'] = $invoiceId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                //$stockReceive['generals_id'] = $CylinderReceiveid;
                $stockReceive['generals_id'] = $invoiceId;
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
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

        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function chequeTransactionInsert()
    {
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

        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
        $returnQty = array_sum($this->input->post('add_returnAble'));

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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                $stock1['generals_id'] = $generals_id;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $generals_id;
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
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

        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
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

    function chequeTransactionInsertForUpdate($invoiceId)
    {
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
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):

            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                //$stockReceive['generals_id'] = $cylinderReceiveId;
                $stockReceive['generals_id'] = $invoiceId;
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
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

        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
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

    function partialTransactionInsert()
    {
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
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
//insert in returnAlbe

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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                $stock1['generals_id'] = $generals_id;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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

        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock table
//Cylinder Stock transaction here
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):

            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $generals_id;
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
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

        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

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

    function partialTransactionInsertForUpdate($invoiceId)
    {
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
        $data['debit'] = $this->input->post('netTotal');
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                //$stock1['generals_id'] = $cylinderId;
                $stock1['generals_id'] = $invoiceId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock table
//Cylinder Stock transaction here
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive)):

            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                //$stockReceive['generals_id'] = $cylinderReceiveid;
                $stockReceive['generals_id'] = $invoiceId;
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
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

        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
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

    function cashTransactionInsert()
    {
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
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
        $retualAble = array_sum($this->input->post('add_returnAble'));

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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                $stock1['generals_id'] = $generals_id;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock tabl
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):

            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $generals_id;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
            //  dumpVar($cylinderAllStock);
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
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


        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

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
            'amount' => $this->input->post('netTotal'),
            'cr' => $this->input->post('netTotal'),
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
            'debit' => $this->input->post('netTotal'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);
        if ($generals_id) {
            return $generals_id;
        }
    }

    function cashTransactionInsertForUpdate($invoiceId)
    {
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
        $data['debit'] = $this->input->post('netTotal');
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['discount'] = $this->input->post('discount');
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
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
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
                //$stock1['generals_id'] = $cylinderId;
                $stock1['generals_id'] = $invoiceId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
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
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock tabl
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                //$stockReceive['generals_id'] = $CylinderReceiveid;
                $stockReceive['generals_id'] = $invoiceId;
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
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
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

        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
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
            'amount' => $this->input->post('netTotal'),
            'cr' => $this->input->post('netTotal'),
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




    function getProductPrice()
    {
        $product_id = $this->input->post('product_id');
        $productDetails = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $product_id);
        if (!empty($productDetails)):
            echo $productDetails->purchases_price;
        endif;
    }


    function viewPurchasesWithCylinder($purchases_id = NULL)
    {
        $data['title'] = 'Purchases View';
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $purchases_id);
        $data['stockList'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $purchases_id);
        // $data['returanAbleCylinder'] = $this->Common_model->getReturnAbleCylinder($purchases_id);
        $data['creditAmount'] = $paymentInfo = $this->Inventory_Model->getCreditAmount($purchases_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['purchasesList']->supplier_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/viewPurchasesWithCylinder', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function supplierDashboard($supId)
    {
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



    function cylinderOpeningView($id)
    {
        $data['cylinderOpening'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $id);
        $data['cylinderItem'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $id);
        $data['title'] = 'Cylinder Openig';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/inOutOpeningView', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function cylinderOpening()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 28,
        );
        $data['cylinderOpening'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['title'] = 'Cylinder Openig';
        $data['mainContent'] = $this->load->view('distributor/inventory/cylinderInOut/inOutOpenigList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function cylinderOpeningAdd()
    {
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

    function getSupplierClosingBalance()
    {
        $supplierId = $this->input->post('supplierid');
        $closingAmount = $this->Inventory_Model->getSupplierClosingBalance($this->dist_id, $supplierId);
        if ($closingAmount) {
            echo $closingAmount;
        }
    }







    function stockReport()
    {
        if (isPostBack()) {
            $categoryId = $this->input->post('categoryId');
            $brandId = $this->input->post('brandId');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['allStock'] = $this->Inventory_Model->getStock($this->dist_id, $start_date, $end_date, $categoryId, $brandId, 2);
            // echo $this->db->last_query();
            // die;
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Inventory Stock Report';
        $data['categoryList'] = $this->Common_model->getPublicProductCatbyCilinder($this->dist_id, 2);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = 'Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/mainStock', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
        $this->output->enable_profiler(false);
    }

    function productWiseCylinderStock()
    {
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

    function newCylinderStockReport()
    {
        if (isPostBack()) {
            $categoryId = $this->input->post('categoryId');
            $brandId = $this->input->post('brandId');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['allStock'] = $this->Inventory_Model->getStock($this->dist_id, $start_date, $end_date, $categoryId, $brandId, 1);
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Cylinder Stock Report';
        $data['categoryList'] = $this->Common_model->getPublicProductCatbyCilinder($this->dist_id, 1);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = 'Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinderStockUpdated', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function stockReport_export_excel()
    {
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

    function categoryReport()
    {
        $data['title'] = 'Category Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/categoryReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function itemeport()
    {
        $data['title'] = 'Item Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/itemStockReport', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function abstest()
    {
        print_r(getProductUnit(576));
    }

    public function get_product_list_by_dist_id()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            if (isset($_GET['receiveStatus'])) {
                $status = strtolower($_GET['receiveStatus']);
            } else {
                $status = 0;
            }
            echo json_encode($this->Common_model->get_purchases_product_list_by_dist_id($this->dist_id, $q, $status));
        }
    }

    public function cylinderDetailsReport()
    {

        if (isPostBack()) {
//            echo '----------';
//            echo '<pre>';
//            print_r($_POST);//exit;
            $type_id = $data['type_id'] = $this->input->post('type_id');
            $balance_type = $data['balance_type'] = $this->input->post('balance_type');
            $sub_cus_id = $data['sub_cus_id'] = $this->input->post('sub_cus_id');
            $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['dataresult'] = $this->Inventory_Model->getCusSupProductdetails($type_id, $balance_type, $sub_cus_id, $from_date, $to_date, $this->dist_id);
            echo '<pre>';
            print_r($data['dataresult']);
            print_r($this->db->last_query());
            exit;
        }
        $data['productList'] = $this->Common_model->getProductListByCategory(2, $this->dist_id);
        $data['title'] = 'Cylinder A Report';
        $data['dist_id'] = $this->dist_id;
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinderCombineReport_1', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function supplierCusstomerLoad()
    {
        $type_id = $this->input->post('type_id');
        $balance_type = $this->input->post('balance_type');
        $append = '';
        $append .= '<option value="all"> Select </option>';
        $sub_cus_id = $this->input->post('sub_cus_id');

        if ($type_id == 2) {
            $customerList = $this->Common_model->get_data_list_by_single_column('customer', 'dist_id', $this->dist_id);
            if (!empty($customerList)):
                $append .= '<option value="all"> All </option>';
                foreach ($customerList as $eachInfo):
                    $selec = '';
                    if (!empty($sub_cus_id) && $sub_cus_id == $eachInfo->customer_id):
                        $selec = 'selected';
                    endif;
                    $append .= '<option ' . $selec . ' value="' . $eachInfo->customer_id . '">' . $eachInfo->customerName . '</option>';
                endforeach;
            else:
                $append .= '<option>Empty!</option>';
            endif;
        } else {
            $supplierList = $this->Common_model->get_data_list_by_single_column('supplier', 'dist_id', $this->dist_id);
            if (!empty($supplierList)):
                $append .= '<option value="all"> All </option>';
                foreach ($supplierList as $eachInfo):
                    $selec = '';
                    if (!empty($sub_cus_id) && $sub_cus_id == $eachInfo->sup_id):
                        $selec = 'selected';
                    endif;
                    $append .= '<option ' . $selec . ' value="' . $eachInfo->sup_id . '">' . $eachInfo->supName . '</option>';
                endforeach;
            else:
                $append .= '<option>Empty!</option>';
            endif;
        }


        echo $append;
    }

}
