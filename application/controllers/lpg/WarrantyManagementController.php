<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/26/2019
 * Time: 9:38 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class WarrantyManagementController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $link_icon_add;
    public $link_icon_list;
    public $link_icon_view;
    public $TypeDR;
    public $TypeCR;
    public $salesEmptyCylinderWithRefill;
    public $CostOfEmptyCylinderWithRefill;
    public $discountOnSales;


    public $business_type;
    public $folder;
    public $folderSub;

    public $db_hostname;
    public $db_username;
    public $db_password;
    public $db_name;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $this->page_type = 'Sales';

        $this->link_icon_add = "<i class='fa fa-plus'></i>";
        $this->link_icon_list = "<i class='fa fa-list'></i>";
        $this->link_icon_view = "<i class='fa fa-view'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->business_type = $this->session->userdata('business_type');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
        $this->TypeDR = 1;
        $this->TypeCR = 2;
        $this->discountOnSales = $this->config->item("Discount");;

        if ($this->project == 'farabitraders') {
            $this->salesEmptyCylinderWithRefill = 618;
            $this->CostOfEmptyCylinderWithRefill = 617;
        } else if ($this->project == 'rftraders') {
            $this->salesEmptyCylinderWithRefill = 508;
            $this->CostOfEmptyCylinderWithRefill = 509;
        } else if ($this->project == 'tuhinEnterprise') {
            $this->discountOnSales = 338;
        } else if ($this->project == 'msak_enterprise') {
            $this->discountOnSales = 478;
        } else if ($this->project == 'rajTraders') {
            $this->discountOnSales = 762;
        } else {
            $this->salesEmptyCylinderWithRefill = $this->config->item("salesEmptyCylinderWithRefill");
            $this->CostOfEmptyCylinderWithRefill = $this->config->item("CostOfEmptyCylinderWithRefill");
        }


        if ($this->business_type != "LPG") {
            $this->folder = 'distributor/masterTemplateSmeMobile';

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        } else {
            $this->folder = 'distributor/masterTemplate';
        }


        $this->folderSub = 'distributor/inventory/brand/';
    }

    public function warranty_claim_voucher($confirmId = null)
    {



















        $this->load->helper('sales_invoice_no_helper');
        $this->load->helper('branch_dropdown_helper');

        if (isPostBack()) {


            $query = $this->db->field_exists('product_details', 'sales_details');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'product_details' => array(
                        'type' => 'TEXT',
                        'null' => TRUE,
                        //'constraint' => '255',
                        //'unsigned' => TRUE,
                        //'after' => 'Reference'
                    )
                );
                $this->dbforge->add_column('sales_details', $fields);
            }



            $this->form_validation->set_rules('netTotal', 'Net Total', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('saleDate', 'Sales Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Type', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/salesLpgInvoice_add/' . $this->invoice_id));
            } else {

                $totalGR_DEBIT = 0;
                $totalGR_CREDIT = 0;


                $bankName = $this->input->post('bankName');
                $this->db->trans_start();
                $payType = $this->input->post('paymentType');
                $branch_id = $this->input->post('branch_id');
                $customer_id = $this->input->post('customer_id');
                $saleDate = $this->input->post('saleDate') != '' ? date('Y-m-d', strtotime($this->input->post('saleDate'))) : '';
                $allStock = array();
                $allStock1 = array();
                $totalProductCost = 0;
                $newCylinderProductCost = 0;
                $otherProductCost = 0;
                $invoice_no = create_warranty_claim_no();
                $sales_inv['invoice_no'] = $invoice_no;
                /*this invoice no is comming  from sales_invoice_no_helper*/
                $sales_inv['customer_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_inv['customer_id'] = $customer_id;
                $sales_inv['payment_type'] = $payType;

                $sales_inv['invoice_amount'] = $this->input->post('netTotal');
                $sales_inv['vat_amount'] = 0;
                $sales_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $sales_inv['paid_amount'] = $this->input->post('partialPayment') != '' ? $this->input->post('partialPayment') : 0;
                $sales_inv['delivery_address'] = $this->input->post('shippingAddress');
                $sales_inv['delivery_date'] = $this->input->post('delivery_date') != '' ? date($this->input->post('delivery_date')) : 'NULL';
                $sales_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $sales_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $sales_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $sales_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $sales_inv['refference_person_id'] = $this->input->post('reference');
                $sales_inv['narration'] = $this->input->post('narration');
                $sales_inv['company_id'] = $this->dist_id;
                $sales_inv['dist_id'] = $this->dist_id;
                $sales_inv['branch_id'] = $branch_id;
                if ($this->input->post('creditDueDate') != '') {


                    $sales_inv['due_date'] = $this->input->post('creditDueDate') != '' ? date('Y-m-d', strtotime($this->input->post('creditDueDate'))) : 'NULL';
                }
                $sales_inv['invoice_date'] = $saleDate;
                $sales_inv['insert_date'] = $this->timestamp;
                $sales_inv['insert_by'] = $this->admin_id;
                $sales_inv['invoice_for'] = 3;
                $sales_inv['is_active'] = 'Y';
                $sales_inv['is_delete'] = 'N';
                if ($payType == 3) {
                    $sales_inv['bank_id'] = $bankName;
                    //$sales_inv['bank_branch_id'] = $branchName = $this->input->post('branchName');
                    $sales_inv['check_date'] = $checkDate = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $sales_inv['check_no'] = $checkNo = $this->input->post('checkNo');
                }
                $this->invoice_id = $this->Common_model->insert_data('sales_invoice_info', $sales_inv);
                if ($payType == 2) {
                    //for due invoice  Journal Voucher
                    $voucher_no = create_journal_voucher_no();
                    $AccouVoucherType_AutoID = 3;
                } else {
                    //Payment Voucher
                    $this->load->helper('create_receive_voucher_no_helper');
                    $voucher_no = create_receive_voucher_no();
                    $AccouVoucherType_AutoID = 1;
                }
                $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = $saleDate;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $this->invoice_id;
                $accountingMasterTable['Narration'] = 'Sales Voucher ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['customer_id'] = $this->input->post('customer_id');
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['for'] = $this->config->item("accounting_master_table_for_warranty_claim_voucher");;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);
                $EmptyCylinderProductCost = 0;
                $customerReceivableForEmptyCylinderTotal = 0;
                $RefillProductCost = 0;
                $OtherProductCost = 0;
                $allEmptyCylinderSalesArray = array();
                $allRefillCylinderArray = array();
                $allOtherProductArray = array();
                $allEmptyCylinderWithRefillArray = array();
                $allEmptyCylinderWithRefillPrice = 0;
                foreach ($_POST['slNo'] as $key => $value) {
                    $customerReceivableForEmptyCylinder = 0;
                    $returnEmptyCylinderPrice = 0;
                    $emptyCylindetWithRefill = array();
                    $refillCylindet = array();
                    $emptyCylindet = array();
                    $otherProduct = array();
                    $lastPurchasepriceArray = $this->db->where('product_id', $_POST['product_id_' . $value])
                        ->where('branch_id', $branch_id)
                        ->order_by('purchase_details_id', "desc")
                        ->limit(1)
                        ->get('purchase_details')
                        ->row();
                    $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;
                    $returnable_quantity = $_POST['returnQuantity'][$value] != '' ? $_POST['returnQuantity'][$value] : 0;
                    $return_quentity = empty($_POST['returnedQuantity_' . $value]) ? 0 : array_sum($_POST['returnedQuantity_' . $value]);
                    $supplier_advance = 0;
                    $supplier_due = 0;
                    unset($stock);
                    $stock['sales_invoice_id'] = $this->invoice_id;
                    $stock['customer_id'] = $customer_id;
                    $stock['product_id'] = $product_id = $_POST['product_id_' . $value];
                    $stock['is_package '] = $_POST['is_package_' . $value];
                    $stock['returnable_quantity '] = $returnable_quantity;
                    $stock['return_quentity '] = $return_quentity;
                    if ($return_quentity < $returnable_quantity) {
                        $supplier_due = $returnable_quantity - $return_quentity;
                    } else {
                        $supplier_advance = $return_quentity - $returnable_quantity;
                    }
                    $stock['customer_due'] = $supplier_due;
                    $stock['customer_advance'] = $supplier_advance;
                    $stock['quantity'] = $_POST['quantity_' . $value];
                    $stock['unit_price'] = $_POST['rate_' . $value];
                    $stock['last_purchase_price '] = $lastPurchaseprice;
                    $stock['insert_by'] = $this->admin_id;
                    $stock['insert_date'] = $this->timestamp;
                    $stock['branch_id'] = $branch_id;
                    $stock['property_1'] = $_POST['property_1_' . $value];
                    $stock['property_2'] = $_POST['property_2_' . $value];
                    $stock['property_3'] = $_POST['property_3_' . $value];
                    $stock['property_4'] = $_POST['property_4_' . $value];
                    $stock['property_5'] = $_POST['property_5_' . $value];
                    $stock['product_details'] = $_POST['narration_of_product_' . $value];
                    $sales_details_id = $this->Common_model->insert_data('sales_details', $stock);


                    $productCost = $this->Sales_Model->productCostNew($product_id, $branch_id);


                    $totalProductCost += ($_POST['quantity_' . $value] * $productCost);
                    $category_id = $this->Common_model->tableRow('product', 'product_id', $product_id)->category_id;
                    $packageEmptyProductId = 0;
                    if ($category_id == 2 && $_POST['is_package_' . $value] == 0) {
                        $packageEmptyProductId = $this->getPackageEmptyProductId($_POST['product_id_' . $value]);
                    }
                    $stockNewTable['parent_stock_id'] = 0;
                    $stockNewTable['invoice_id'] = $this->invoice_id;
                    $stockNewTable['form_id'] = 5;
                    $stockNewTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                    $stockNewTable['customer_id'] = $customer_id;
                    $stockNewTable['supplier_id'] = 0;
                    $stockNewTable['branch_id'] = $branch_id;
                    $stockNewTable['invoice_date'] = $saleDate;
                    $stockNewTable['category_id'] = $category_id;
                    $stockNewTable['product_id'] = $_POST['product_id_' . $value];
                    $stockNewTable['empty_cylinder_id'] = $packageEmptyProductId;
                    $stockNewTable['is_package'] = $_POST['is_package_' . $value];
                    $stockNewTable['show_in_invoice'] = 1;
                    $stockNewTable['unit'] = getProductUnit($_POST['product_id_' . $value]);
                    $stockNewTable['type'] = 2;
                    $stockNewTable['quantity'] = $_POST['quantity_' . $value];
                    $stockNewTable['quantity_out'] = $_POST['quantity_' . $value];
                    $stockNewTable['quantity_in'] = 0;
                    $stockNewTable['returnable_quantity'] = $returnable_quantity;
                    $stockNewTable['return_quentity'] = $return_quentity;
                    $stockNewTable['due_quentity'] = $supplier_due;
                    $stockNewTable['advance_quantity'] = $supplier_advance;
                    $stockNewTable['price'] = $_POST['rate_' . $value];
                    $stockNewTable['price_in'] = 0;
                    $stockNewTable['price_out'] = $_POST['rate_' . $value];
                    $stockNewTable['last_purchase_price'] = $lastPurchaseprice;
                    $stockNewTable['product_details'] = "";
                    $stockNewTable['property_1'] = $_POST['property_1_' . $value];
                    $stockNewTable['property_2'] = $_POST['property_2_' . $value];
                    $stockNewTable['property_3'] = $_POST['property_3_' . $value];
                    $stockNewTable['property_4'] = $_POST['property_4_' . $value];
                    $stockNewTable['property_5'] = $_POST['property_5_' . $value];
                    $stockNewTable['openingStatus'] = 0;
                    $stockNewTable['insert_by'] = $this->admin_id;
                    $stockNewTable['insert_date'] = $this->timestamp;
                    $stockNewTable['update_by'] = '';
                    $stockNewTable['update_date'] = '';
                    $stock_id = $this->Common_model->insert_data('stock', $stockNewTable);


                    if ($category_id == 1) {
                        //Empty Cylinder
                        $EmptyCylinderProductCost += ($_POST['quantity_' . $value] * $productCost);
                        $emptyCylindet['product_id'] = $_POST['product_id_' . $value];
                        $emptyCylindet['price'] = ($_POST['quantity_' . $value] * $productCost);
                        $emptyCylindet['quantity'] = ($_POST['quantity_' . $value]);
                        $emptyCylindet['unit_price'] = ($productCost);
                        $emptyCylindet['stock_id'] = $stock_id;

                        $allEmptyCylinderSalesArray[] = $emptyCylindet;
                    } elseif ($category_id == 2) {
                        //Refill
                        $RefillProductCost += ($_POST['rate_' . $value] * $productCost);
                        $refillCylindet['product_id'] = $_POST['product_id_' . $value];
                        $refillCylindet['price'] = ($_POST['quantity_' . $value] * $productCost);
                        $refillCylindet['quantity'] = ($_POST['quantity_' . $value]);
                        $refillCylindet['unit_price'] = ($productCost);
                        $refillCylindet['stock_id'] = $stock_id;

                        $allRefillCylinderArray[] = $refillCylindet;
                    } else {
                        //OtherProduct
                        $OtherProductCost += ($_POST['quantity_' . $value] * $productCost);
                        $otherProduct['product_id'] = $_POST['product_id_' . $value];
                        $otherProduct['price'] = ($_POST['quantity_' . $value] * $productCost);
                        $otherProduct['quantity'] = ($_POST['quantity_' . $value]);
                        $otherProduct['unit_price'] = ($productCost);
                        $otherProduct['stock_id'] = $stock_id;

                        $allOtherProductArray[] = $otherProduct;
                    }
                    if ($category_id == 2 && $_POST['is_package_' . $value] == 0) {
                        //$packageEmptyProductId = $this->getPackageEmptyProductId($_POST['product_id_' . $value]);
                        //sitehelper
                        $product_last_sales_price = get_product_last_sales_price($packageEmptyProductId);
                        //$productCost = $this->Sales_Model->emptyCylinderPurchasePrice($packageEmptyProductId, $this->dist_id);
                        $productCost = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $packageEmptyProductId)->purchases_price;


                        //log_message('error','allEmptyCylinderWithRefillArray '.print_r($this->db->last_query(),true));
                        $allEmptyCylinderWithRefillPrice += ($_POST['quantity_' . $value] * $productCost);
                        $lastPurchasepriceArray = $this->db->where('product_id', $packageEmptyProductId)
                            ->order_by('purchase_details_id', "desc")
                            ->limit(1)
                            ->get('purchase_details')
                            ->row();
                        $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;


                        unset($stock);
                        $stock['sales_invoice_id'] = $this->invoice_id;
                        $stock['customer_id'] = $customer_id;
                        $stock['product_id'] = $packageEmptyProductId;
                        $stock['is_package '] = 0;
                        $stock['returnable_quantity '] = 0;
                        $stock['return_quentity '] = 0;
                        $stock['customer_due'] = 0;
                        $stock['customer_advance'] = 0;
                        $stock['quantity'] = $_POST['quantity_' . $value];
                        $stock['unit_price'] = $productCost;
                        $stock['last_purchase_price '] = $lastPurchaseprice;
                        $stock['insert_by'] = $this->admin_id;
                        $stock['insert_date'] = $this->timestamp;
                        $stock['branch_id'] = $branch_id;
                        $stock['show_in_invoice'] = 0;
                        $this->Common_model->insert_data('sales_details', $stock);


                        $stockNewTable = array();
                        $stockNewTable['parent_stock_id'] = 0;
                        $stockNewTable['invoice_id'] = $this->invoice_id;
                        $stockNewTable['form_id'] = 5;
                        $stockNewTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                        $stockNewTable['customer_id'] = $customer_id;
                        $stockNewTable['supplier_id'] = 0;
                        $stockNewTable['branch_id'] = $branch_id;
                        $stockNewTable['invoice_date'] = $saleDate;
                        $stockNewTable['category_id'] = 1;
                        $stockNewTable['product_id'] = $packageEmptyProductId;
                        $stockNewTable['empty_cylinder_id'] = $packageEmptyProductId;
                        $stockNewTable['is_package'] = 0;
                        $stockNewTable['show_in_invoice'] = 0;
                        $stockNewTable['unit'] = getProductUnit($packageEmptyProductId);
                        $stockNewTable['type'] = 2;
                        $stockNewTable['quantity'] = $_POST['quantity_' . $value];
                        $stockNewTable['quantity_out'] = $_POST['quantity_' . $value];
                        $stockNewTable['quantity_in'] = 0;
                        $stockNewTable['returnable_quantity'] = 0;
                        $stockNewTable['return_quentity'] = 0;
                        $stockNewTable['due_quentity'] = 0;
                        $stockNewTable['advance_quantity'] = 0;
                        $stockNewTable['price'] = $productCost;
                        $stockNewTable['price_in'] = 0;
                        $stockNewTable['price_out'] = $productCost;
                        $stockNewTable['last_purchase_price'] = $lastPurchaseprice;
                        $stockNewTable['product_details'] = "";
                        $stockNewTable['property_1'] = '';
                        $stockNewTable['property_2'] = "";
                        $stockNewTable['property_3'] = "";
                        $stockNewTable['property_4'] = "";
                        $stockNewTable['property_5'] = "";
                        $stockNewTable['openingStatus'] = 0;
                        $stockNewTable['insert_by'] = $this->admin_id;
                        $stockNewTable['insert_date'] = $this->timestamp;
                        $stockNewTable['update_by'] = '';
                        $stockNewTable['update_date'] = '';
                        $stock_id_empty_cylinder_out_with_refill = $this->Common_model->insert_data('stock', $stockNewTable);


                        $emptyCylindetWithRefill['product_id'] = $packageEmptyProductId;
                        $emptyCylindetWithRefill['price'] = ($productCost * $_POST['quantity_' . $value]);
                        $emptyCylindetWithRefill['stock_id'] = $stock_id_empty_cylinder_out_with_refill;
                        $allEmptyCylinderWithRefillArray[] = $emptyCylindetWithRefill;
                        $product_last_sales_price = get_product_purchase_price($packageEmptyProductId);
                        $customerReceivableForEmptyCylinderTotal += ($product_last_sales_price * $_POST['quantity_' . $value]);
                        $customerReceivableForEmptyCylinder = ($product_last_sales_price * $_POST['quantity_' . $value]);
                    }
                    if (isset($_POST['returnproduct_' . $value])) {
                        foreach ($_POST['returnproduct_' . $value] as $key1 => $value1) {
                            //$productCost = $this->Sales_Model->productCostNew($value1, $this->dist_id);
                            //$totalProductCost += ($_POST['returnedQuantity_' . $value][$key1] * $productCost);
                            unset($stock2);
                            $product_last_purchase_price = $this->Sales_Model->productCostNew($value1, $this->dist_id);

                            //$emptyCylindetReturn['price'] = $product_last_purchase_price * $_POST['returnedQuantity_' . $value][$key1];

                            $stockNewTable = array();
                            $stockNewTable['parent_stock_id'] = $stock_id;
                            $stockNewTable['invoice_id'] = $this->invoice_id;
                            $stockNewTable['form_id'] = 5;
                            $stockNewTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                            $stockNewTable['Accounts_VoucherDtl_AutoID'] = 0;
                            $stockNewTable['customer_id'] = $customer_id;
                            $stockNewTable['supplier_id'] = 0;
                            $stockNewTable['branch_id'] = $branch_id;
                            $stockNewTable['invoice_date'] = $saleDate;
                            $stockNewTable['category_id'] = 1;
                            $stockNewTable['product_id'] = $value1;
                            $stockNewTable['empty_cylinder_id'] = $value1;
                            $stockNewTable['is_package'] = 0;
                            $stockNewTable['show_in_invoice'] = 1;
                            $stockNewTable['unit'] = getProductUnit($value1);
                            $stockNewTable['type'] = 2;
                            $stockNewTable['quantity'] = $_POST['returnedQuantity_' . $value][$key1];
                            $stockNewTable['quantity_out'] = 0;
                            $stockNewTable['quantity_in'] = $_POST['returnedQuantity_' . $value][$key1];
                            $stockNewTable['returnable_quantity'] = 0;
                            $stockNewTable['return_quentity'] = 0;
                            $stockNewTable['due_quentity'] = 0;
                            $stockNewTable['advance_quantity'] = 0;
                            $stockNewTable['price'] = $productCost;
                            $stockNewTable['price_in'] = 0;
                            $stockNewTable['price_out'] = $productCost;
                            $stockNewTable['last_purchase_price'] = $lastPurchaseprice;
                            $stockNewTable['product_details'] = "";
                            $stockNewTable['property_1'] = '';
                            $stockNewTable['property_2'] = "";
                            $stockNewTable['property_3'] = "";
                            $stockNewTable['property_4'] = "";
                            $stockNewTable['property_5'] = "";
                            $stockNewTable['openingStatus'] = 0;
                            $stockNewTable['insert_by'] = $this->admin_id;
                            $stockNewTable['insert_date'] = $this->timestamp;
                            $stockNewTable['update_by'] = '';
                            $stockNewTable['update_date'] = '';
                            $stock_id_empty_cylinder_out_with_refill = $this->Common_model->insert_data('stock', $stockNewTable);


                            $emptyCylindetReturn['product_id'] = $value1;
                            $emptyCylindetReturn['price'] = $_POST['returnedQuantity_' . $value][$key1] * $_POST['returnedQuantityPrice_' . $value][$key1];//returnedQuantityPrice_2[]
                            $emptyCylindetReturn['Reference'] = "";
                            $allEmptyCylinderReturnArray[] = $emptyCylindetReturn;

                            $stock2['sales_details_id'] = $sales_details_id;
                            $stock2['product_id'] = $value1;
                            $stock2['sales_invoice_id'] = $this->invoice_id;
                            $stock2['customer_id'] = $customer_id;
                            $stock2['returnable_quantity'] = $_POST['returnedQuantity_' . $value][$key1];
                            $stock2['return_quantity'] = $_POST['returnedQuantity_' . $value][$key1];
                            $stock2['unit_price'] = $_POST['returnedQuantityPrice_' . $value][$key1];
                            $stock2['insert_by'] = $this->admin_id;
                            $stock2['insert_date'] = $this->timestamp;
                            $stock2['branch_id'] = $branch_id;
                            $allStock[] = $stock2;
                            $returnEmptyCylinderPrice += $_POST['returnedQuantity_' . $value][$key1] * $_POST['returnedQuantityPrice_' . $value][$key1];

                        }
                        $profitOrLose = $customerReceivableForEmptyCylinder - $returnEmptyCylinderPrice;
                        //$profitOrLose=$salesPriceForRefillCylinder-$totalProductCost-($EmptyCylinderWithRefillStockOutPrice-$EmptyCylinderReturnStockIn);
                        if ($category_id == 2 && ($returnable_quantity == $return_quentity)) {
                            $GR_DEBIT = 0;
                            $GR_CREDIT = 0;
                            if ($profitOrLose < 0) {
                                $payment_type = $this->TypeCR;
                                $GR_CREDIT = ($profitOrLose * -1);
                                $GR_DEBIT = 0;
                            } else {


                                $payment_type = $this->TypeDR;
                                $GR_DEBIT = ($profitOrLose);
                                $GR_CREDIT = 0;
                            }


                            if ($GR_DEBIT > 0 || $GR_CREDIT > 0) {
                                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                                $accountingDetailsTableCustomerReceivable['TypeID'] = $payment_type;
                                $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $this->config->item("EmptyCylinderProfitLoss");//$CustomerReceivable->id;//'25';
                                $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = $GR_DEBIT;
                                $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = $GR_CREDIT;
                                $accountingDetailsTableCustomerReceivable['Reference'] = 'Profit Or lose for empty Cylinder sales with refill';
                                $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                                $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                                $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                                $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                                $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                                $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;

                                $totalGR_DEBIT = $totalGR_DEBIT + $GR_DEBIT;
                                $totalGR_CREDIT = $totalGR_CREDIT + $GR_CREDIT;

                                $accountingDetailsTableCustomerReceivable = array();
                                $condtion = array(
                                    'related_id' => $customer_id,
                                    'related_id_for' => 4,
                                    'is_active' => "Y",
                                );
                                $CustomerReceivableForEmptyCylinder = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                                $accountingDetailsTableCustomerReceivable['TypeID'] = $this->TypeCR;
                                $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $CustomerReceivableForEmptyCylinder->id;//$CustomerReceivable->id;//'25';
                                $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = $GR_CREDIT;
                                $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = $GR_DEBIT;
                                $accountingDetailsTableCustomerReceivable['Reference'] = ' Customer Recivable For Empty Cylinder ';
                                $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                                $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                                $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                                $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                                $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                                $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                                $accountingDetailsTableCustomerReceivable = array();

                                $totalGR_DEBIT = $totalGR_DEBIT + $GR_CREDIT;
                                $totalGR_CREDIT = $totalGR_CREDIT + $GR_DEBIT;
                            }

                        }
                    }
                }


                //log_message('error','allEmptyCylinderWithRefillArray '.print_r($allEmptyCylinderWithRefillArray,true));
                //log_message('error','allEmptyCylinderReturnArray '.print_r($allEmptyCylinderReturnArray,true));
                /*echo "<pre>";
                print_r($customerReceivableForEmptyCylinderTotal);
                exit;*/
                $salesPriceForRefillCylinder = 0;
                $this->db->insert_batch('sales_return_details', $allStock);
                /*ac_accounts_vouchermst*/
                /*     Sales   */
                $accountingDetailsTableSales['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableSales['TypeID'] = $this->TypeCR;//'2';//Cr
                $accountingDetailsTableSales['CHILD_ID'] = 128;
                $accountingDetailsTableSales['GR_DEBIT'] = '0.00';
                $accountingDetailsTableSales['GR_CREDIT'] = array_sum($this->input->post('price'));
                $accountingDetailsTableSales['Reference'] = 'Sales Amount';
                $accountingDetailsTableSales['IsActive'] = 1;
                $accountingDetailsTableSales['Created_By'] = $this->admin_id;
                $accountingDetailsTableSales['Created_Date'] = $this->timestamp;
                $accountingDetailsTableSales['BranchAutoId'] = $branch_id;
                $accountingDetailsTableSales['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableSales;

                $totalGR_DEBIT = $totalGR_DEBIT + 0;
                $totalGR_CREDIT = $totalGR_CREDIT + array_sum($this->input->post('price'));

                $accountingDetailsTableSales = array();
                if ($customerReceivableForEmptyCylinderTotal > 0) {
                    $accountingDetailsTableSales['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableSales['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableSales['CHILD_ID'] = $this->salesEmptyCylinderWithRefill;
                    $accountingDetailsTableSales['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableSales['GR_CREDIT'] = $customerReceivableForEmptyCylinderTotal;
                    $accountingDetailsTableSales['Reference'] = 'Sales Amount of empty cylinder';
                    $accountingDetailsTableSales['IsActive'] = 1;
                    $accountingDetailsTableSales['Created_By'] = $this->admin_id;
                    $accountingDetailsTableSales['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableSales['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableSales['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableSales;
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $customerReceivableForEmptyCylinderTotal;
                    $accountingDetailsTableSales = array();
                }
                $condtion = array(
                    'related_id' => $customer_id,
                    'related_id_for' => 2,
                    'is_active' => "Y",
                );
                $CustomerReceivable = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                /*Customer Receivable   =>>33*/
                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCustomerReceivable['TypeID'] = $this->TypeDR;//'1';//Dr
                $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $CustomerReceivable->id;//$this->config->item("Customer_Receivable");
                $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = $this->input->post('netTotal');
                $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCustomerReceivable['Reference'] = 'Customer Receivable';
                $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('netTotal');
                $totalGR_CREDIT = $totalGR_CREDIT + 0;
                $salesPriceForRefillCylinder = $salesPriceForRefillCylinder + $this->input->post('netTotal');
                $accountingDetailsTableCustomerReceivable = array();
                if ($customerReceivableForEmptyCylinderTotal > 0) {
                    $condtion = array(
                        'related_id' => $customer_id,
                        'related_id_for' => 4,
                        'is_active' => "Y",
                    );
                    $CustomerReceivableForEmptyCylinder = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                    /*Customer Receivable   =>>33*/
                    $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableCustomerReceivable['TypeID'] = $this->TypeDR;//'1';//Dr
                    $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $CustomerReceivableForEmptyCylinder->id;//$this->config->item("Customer_Receivable");
                    $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = $customerReceivableForEmptyCylinderTotal;
                    $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTableCustomerReceivable['Reference'] = 'Customer Receivable For Empty Cylinder';
                    $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                    $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                    $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                    $totalGR_DEBIT = $totalGR_DEBIT + $customerReceivableForEmptyCylinderTotal;
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;

                    $accountingDetailsTableCustomerReceivable = array();
                }
                /*Customer Receivable   =>>25*/
                if ($this->input->post('discount') > 0) {
                    /*Discount   =>>52*/
                    $accountingDetailsTableDiscount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableDiscount['TypeID'] = $this->TypeDR;//'1';//Dr
                    $accountingDetailsTableDiscount['CHILD_ID'] = $this->discountOnSales;//'52';
                    $accountingDetailsTableDiscount['GR_DEBIT'] = $this->input->post('discount');
                    $accountingDetailsTableDiscount['GR_CREDIT'] = '0.00';
                    $accountingDetailsTableDiscount['Reference'] = 'Discount On Sales';
                    $accountingDetailsTableDiscount['IsActive'] = 1;
                    $accountingDetailsTableDiscount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableDiscount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableDiscount['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableDiscount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableDiscount;

                    $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('discount');
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    $accountingDetailsTableDiscount = array();
                    /*Customer Receivable   =>>25*/
                }
                /*Cost of Goods Product =>>45*/
                $accountingDetailsTableCostofGoodsProduct['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCostofGoodsProduct['TypeID'] = $this->TypeDR;//'1';//Dr
                $accountingDetailsTableCostofGoodsProduct['CHILD_ID'] = 130;//'45';
                //$accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = $totalProductCost;
                $accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = array_sum($this->input->post('price'));
                $accountingDetailsTableCostofGoodsProduct['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCostofGoodsProduct['Reference'] = 'Liability For Warranty Claim Amount';
                $accountingDetailsTableCostofGoodsProduct['IsActive'] = 1;
                $accountingDetailsTableCostofGoodsProduct['Created_By'] = $this->admin_id;
                $accountingDetailsTableCostofGoodsProduct['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCostofGoodsProduct['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCostofGoodsProduct['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCostofGoodsProduct;

               // $totalGR_DEBIT = $totalGR_DEBIT + $totalProductCost;
                $totalGR_DEBIT = $totalGR_DEBIT + array_sum($this->input->post('price'));
                $totalGR_CREDIT = $totalGR_CREDIT + 0;
                $accountingDetailsTableCostofGoodsProduct = array();
                if ($allEmptyCylinderWithRefillPrice > 0) {
                    $accountingDetailsTableCostofGoodsProduct['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableCostofGoodsProduct['TypeID'] = $this->TypeDR;//'1';//Dr
                    $accountingDetailsTableCostofGoodsProduct['CHILD_ID'] = $this->CostOfEmptyCylinderWithRefill;//'45';
                    $accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = $allEmptyCylinderWithRefillPrice;
                    $accountingDetailsTableCostofGoodsProduct['GR_CREDIT'] = '0.00';
                    $accountingDetailsTableCostofGoodsProduct['Reference'] = 'Cost of Empty Cylinder With Refill ';
                    $accountingDetailsTableCostofGoodsProduct['IsActive'] = 1;
                    $accountingDetailsTableCostofGoodsProduct['Created_By'] = $this->admin_id;
                    $accountingDetailsTableCostofGoodsProduct['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableCostofGoodsProduct['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableCostofGoodsProduct['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableCostofGoodsProduct;
                    $accountingDetailsTableCostofGoodsProduct = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + $allEmptyCylinderWithRefillPrice;
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                }

                if (!empty($allEmptyCylinderSalesArray)) {
                    foreach ($allEmptyCylinderSalesArray as $keyEmpCly => $valueEmpCly) {
                        $condition = array(
                            'related_id' => $valueEmpCly['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTableNewCylinderStock['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableNewCylinderStock['TypeID'] = $this->TypeCR;//'2';//Cr
                        $accountingDetailsTableNewCylinderStock['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("New_Cylinder_Stock");//'22';
                        $accountingDetailsTableNewCylinderStock['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableNewCylinderStock['GR_CREDIT'] = $valueEmpCly['price'];
                        $accountingDetailsTableNewCylinderStock['Reference'] = 'New Cylinder Sales Stock Out (' . $valueEmpCly['quantity'] . '*' . $valueEmpCly['unit_price'] . ')';
                        $accountingDetailsTableNewCylinderStock['IsActive'] = 1;
                        $accountingDetailsTableNewCylinderStock['Created_By'] = $this->admin_id;
                        $accountingDetailsTableNewCylinderStock['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableNewCylinderStock['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableNewCylinderStock['date'] = $saleDate;
                        //$finalDetailsArray[] = $accountingDetailsTableNewCylinderStock;

                        $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTableNewCylinderStock);

                        $data['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                        $this->Common_model->update_data('stock', $data, 'stock_id', $valueEmpCly['stock_id']);


                        $accountingDetailsTableNewCylinderStock = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueEmpCly['price'];
                    }
                }
                if (!empty($allRefillCylinderArray)) {
                    /*Inventory stock Refill=*/
                    foreach ($allRefillCylinderArray as $keyRefill => $valueRefill) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueRefill['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTableRefill['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableRefill['TypeID'] = $this->TypeCR;//'2';//Cr
                        $accountingDetailsTableRefill['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Refill");//'95';
                        $accountingDetailsTableRefill['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableRefill['GR_CREDIT'] = $valueRefill['price'];
                        $accountingDetailsTableRefill['Reference'] = 'Refill  Cylinder Sales(Stock Out)(' . $valueRefill['quantity'] . '*' . $valueRefill['unit_price'] . ')';;
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        //$finalDetailsArray[] = $accountingDetailsTableRefill;
                        $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTableRefill);

                        $data['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                        $this->Common_model->update_data('stock', $data, 'stock_id', $valueRefill['stock_id']);


                        $accountingDetailsTable = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueRefill['price'];
                    }
                }
                $EmptyCylinderWithRefillStockOutPrice = 0;
                if (!empty($allEmptyCylinderWithRefillArray)) {
                    /*Inventory stock Refill=*/
                    foreach ($allEmptyCylinderWithRefillArray as $keyEmptyWithRefill => $valueEmptyWithRefill) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueEmptyWithRefill['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTableRefill['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableRefill['TypeID'] = $this->TypeCR;//'2';//Cr
                        $accountingDetailsTableRefill['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Refill");//'95';
                        $accountingDetailsTableRefill['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableRefill['GR_CREDIT'] = $valueEmptyWithRefill['price'];
                        $accountingDetailsTableRefill['Reference'] = 'Empty Cylinder With Refill Cylinder Sales(Stock Out)(' . $valueEmptyWithRefill['quantity'] . '*' . $valueEmptyWithRefill['unit_price'] . ')';
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        //$finalDetailsArray[] = $accountingDetailsTableRefill;


                        $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTableRefill);

                        $data['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                        $this->Common_model->update_data('stock', $data, 'stock_id', $valueEmptyWithRefill['stock_id']);
                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueEmptyWithRefill['price'];
                        $accountingDetailsTable = array();
                        $EmptyCylinderWithRefillStockOutPrice = $EmptyCylinderWithRefillStockOutPrice + $valueEmptyWithRefill['price'];
                    }
                }
                $EmptyCylinderReturnStockIn = 0;
                $returnEmptyCylinderPriceTotal = 0;
                if (!empty($allEmptyCylinderReturnArray)) {
                    /*Inventory stock Refill=*/
                    foreach ($allEmptyCylinderReturnArray as $keyEmptyWithRefillReturn => $valueEmptyWithRefillReturn) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueEmptyWithRefillReturn['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTableRefill['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableRefill['TypeID'] = $this->TypeDR;//'1';//Dr
                        $accountingDetailsTableRefill['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Refill");//'95';
                        $accountingDetailsTableRefill['GR_DEBIT'] = $valueEmptyWithRefillReturn['price'];
                        //$accountingDetailsTableRefill['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableRefill['GR_CREDIT'] = '0.00';
                        $accountingDetailsTableRefill['Reference'] = 'Empty Cylinder Return (Stock In)';
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableRefill;
                        $totalGR_DEBIT = $totalGR_DEBIT + $valueEmptyWithRefillReturn['price'];
                        $totalGR_CREDIT = $totalGR_CREDIT + 0;
                        $EmptyCylinderReturnStockIn = $valueEmptyWithRefillReturn['price'] + $EmptyCylinderReturnStockIn;
                        $accountingDetailsTable = array();
                        $condtion = array(
                            'related_id' => $customer_id,
                            'related_id_for' => 4,
                            'is_active' => "Y",
                        );
                        $CustomerReceivableForEmptyCylinder = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                        $accountingDetailsTableRefill['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableRefill['TypeID'] = $this->TypeCR;//;'1';//Dr
                        $accountingDetailsTableRefill['CHILD_ID'] = $CustomerReceivableForEmptyCylinder->id;//$this->config->item("Refill");//'95';
                        $accountingDetailsTableRefill['GR_DEBIT'] = '0.00';
                        //$accountingDetailsTableRefill['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableRefill['GR_CREDIT'] = $valueEmptyWithRefillReturn['price'];
                        $accountingDetailsTableRefill['Reference'] = 'Customer Receivable Paid  For Empty Cylinder';
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableRefill;
                        $accountingDetailsTable = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueEmptyWithRefillReturn['price'];
                        $returnEmptyCylinderPriceTotal = $returnEmptyCylinderPriceTotal + $valueEmptyWithRefillReturn['price'];
                    }
                }
                if (!empty($allOtherProductArray)) {
                    /*Inventory stock Refill=*/
                    foreach ($allOtherProductArray as $keyOtherProduct => $valueOtherProduct) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueOtherProduct['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        /*Inventory stock Refill=>95*/
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTableOtherProductCost['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTableOtherProductCost['TypeID'] = $this->TypeCR;//'2';//Cr
                        $accountingDetailsTableOtherProductCost['CHILD_ID'] = $ac_account_ledger_coa_info->id;// $this->config->item("Inventory_Stock");//'20';
                        $accountingDetailsTableOtherProductCost['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableOtherProductCost['GR_CREDIT'] = $valueOtherProduct['price'];// $OtherProductCost;
                        $accountingDetailsTableOtherProductCost['Reference'] = 'Inventory stock Out Of ' . $ac_account_ledger_coa_info->parent_name . ' (' . $valueEmpCly['quantity'] . '*' . $valueEmpCly['unit_price'] . ')';
                        $accountingDetailsTableOtherProductCost['IsActive'] = 1;
                        $accountingDetailsTableOtherProductCost['Created_By'] = $this->admin_id;
                        $accountingDetailsTableOtherProductCost['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableOtherProductCost['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableOtherProductCost['date'] = $saleDate;
                        //$finalDetailsArray[] = $accountingDetailsTableOtherProductCost;

                        $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTableOtherProductCost);

                        $data['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                        $this->Common_model->update_data('stock', $data, 'stock_id', $valueOtherProduct['stock_id']);

                        $accountingDetailsTable = array();
                        $totalGR_DEBIT = $totalGR_DEBIT + 0;
                        $totalGR_CREDIT = $totalGR_CREDIT + $valueOtherProduct['price'];
                    }
                }
                /*Sales=>37*/
                /*Loading and wages*/
                if ($this->input->post('loaderAmount') > 0) {
                    $accountingDetailsTableloaderAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableloaderAmount['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableloaderAmount['CHILD_ID'] = $this->config->item("LoaderPayableSales");//'47';
                    $accountingDetailsTableloaderAmount['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableloaderAmount['GR_CREDIT'] = $this->input->post('loaderAmount');
                    $accountingDetailsTableloaderAmount['Reference'] = 'Loading and wages';
                    $accountingDetailsTableloaderAmount['IsActive'] = 1;
                    $accountingDetailsTableloaderAmount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableloaderAmount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableloaderAmount['BranchAutoId'] = $branch_id;;
                    $accountingDetailsTableloaderAmount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableloaderAmount;
                    $accountingDetailsTableloaderAmount = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('loaderAmount');
                }
                /*Loading and wages*/
                /*Transportation*/
                if ($this->input->post('transportationAmount') > 0) {
                    $accountingDetailsTableTransportationAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableTransportationAmount['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableTransportationAmount['CHILD_ID'] = $this->config->item("TransportationPayableSales");//'42';
                    $accountingDetailsTableTransportationAmount['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableTransportationAmount['GR_CREDIT'] = $this->input->post('transportationAmount');
                    $accountingDetailsTableTransportationAmount['Reference'] = 'Transportation Payable Sales';
                    $accountingDetailsTableTransportationAmount['IsActive'] = 1;
                    $accountingDetailsTableTransportationAmount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableTransportationAmount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableTransportationAmount['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableTransportationAmount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableTransportationAmount;
                    $accountingDetailsTableTransportationAmount = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('transportationAmount');
                }
                /*Transportation*/
                if ($payType == 4) {
                    /*Customer Receivable  /account Receiveable  =>>25*/
                    //account Receiveable
                    $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableCustomerReceivable['TypeID'] = $this->TypeCR;//'2';//Cr
                    $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $CustomerReceivable->id;//'25';
                    $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = $this->input->post('partialPayment');
                    $accountingDetailsTableCustomerReceivable['Reference'] = 'Customer paid amount';
                    $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                    $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                    $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                    $accountingDetailsTableCustomerReceivable['cus_due_collection_details_id'] = 0;
                    $accountingDetailsTableCustomerReceivable['for'] = 5;
                    $accountingDetailsTableCustomerReceivable['invoice_id'] = $this->invoice_id;
                    $accountingDetailsTableCustomerReceivable['invoice_no'] = $invoice_no;

                    $cus_due_collection_details_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTableCustomerReceivable);


                    //$finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $this->input->post('partialPayment');
                    $accountingDetailsTableCustomerReceivable = array();
                    //account Receiveable
                    /*Cash in hand*/
                    $accountingDetailsTableCashinhand['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableCashinhand['TypeID'] = $this->TypeDR;//'1';//Dr
                    $accountingDetailsTableCashinhand['CHILD_ID'] = $this->input->post('accountCrPartial');
                    $accountingDetailsTableCashinhand['GR_DEBIT'] = $this->input->post('partialPayment');
                    $accountingDetailsTableCashinhand['GR_CREDIT'] = '0.00';
                    $accountingDetailsTableCashinhand['Reference'] = '';
                    $accountingDetailsTableCashinhand['IsActive'] = 1;
                    $accountingDetailsTableCashinhand['Created_By'] = $this->admin_id;
                    $accountingDetailsTableCashinhand['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableCashinhand['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableCashinhand['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableCashinhand;
                    $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('partialPayment');
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    $accountingDetailsTableCashinhand = array();
                }


                $totalGR_DEBIT = number_format($totalGR_DEBIT, 2, '.', '');

                $totalGR_CREDIT = number_format($totalGR_CREDIT, 2, '.', '');


                if ($totalGR_DEBIT != $totalGR_CREDIT) {
                    $this->db->trans_rollback();
                    $msg = 'Sales Invoice ' . ' ' . $this->config->item("save_error_message") . ' There is something wrong please try again .contact with Customer Care';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/warranty_claim_voucher'));
                }


                if (!empty($finalDetailsArray)) {
                    $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                }
                /*client_vendor_ledger table data insert*/
                $supp = array(
                    'ledger_type' => 1,
                    'history_id' => $accountingVoucherId,
                    'trans_type' => $invoice_no,
                    'client_vendor_id' => $this->input->post('customer_id'),
                    'invoice_id' => $this->invoice_id,
                    'invoice_type' => '2',
                    'Accounts_VoucherType_AutoID' => '6',
                    'updated_by' => $this->admin_id,
                    'dist_id' => $this->dist_id,
                    'amount' => $this->input->post('netTotal'),
                    'dr' => $this->input->post('netTotal'),
                    'date' => $saleDate,
                    'BranchAutoId' => $branch_id,
                    'paymentType' => 'Sales Voucher'
                );
                $this->db->insert('client_vendor_ledger', $supp);
                if ($payType == 4) {
                    $supp1 = array(
                        'ledger_type' => 1,
                        'dist_id' => $this->dist_id,
                        'trans_type' => $invoice_no,
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => $this->input->post('partialPayment'),
                        'cr' => $this->input->post('partialPayment'),
                        'invoice_id' => $this->invoice_id,
                        'invoice_type' => '2',
                        'Accounts_VoucherType_AutoID' => '6',
                        'date' => $saleDate,
                        'updated_by' => $this->admin_id,
                        'history_id' => $accountingVoucherId,
                        'BranchAutoId' => $branch_id,
                        'paymentType' => 'Customer Payment',
                    );
                    $this->db->insert('client_vendor_ledger', $supp1);
                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "CMR" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => $saleDate,
                        'invoiceID' => $this->invoice_id,
                        'totalPayment' => $this->input->post('partialPayment'),
                        'receitID' => $mrid,
                        'mainInvoiceId' => $accountingVoucherId,
                        'Accounts_VoucherMst_AutoID' => $accountingVoucherId,
                        'customerid' => $this->input->post('customer_id'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $branch_id,
                        'paymentType' => 1,
                        'receiveType' => 1,
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
                        //'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $moneyReceit);
                }
                if ($payType == 3) {
                    //check payment
                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 1,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "CMR" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => $saleDate,
                        'invoiceID' => $this->invoice_id,
                        //'invoiceID' => $invoice_no,
                        'totalPayment' => $this->input->post('netTotal'),
                        'receitID' => $mrid,
                        'mainInvoiceId' => $this->invoice_id,
                        'Accounts_VoucherMst_AutoID' => $accountingVoucherId,
                        'customerid' => $this->input->post('customer_id'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $branch_id,
                        'paymentType' => 2,
                        'receiveType' => 1,
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
                        //'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $moneyReceit);
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'sales Invoice ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/warranty_claim_voucher/'));
                } else {
                    $msg = 'sales Invoice ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/warranty_claim_voucher_view/' . $this->invoice_id));
                }
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('warranty_claim_voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('warranty_claim_list');
        $data['link_page_url'] = $this->project . '/warranty_claim_voucher_list';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $customerID = $this->Sales_Model->getCustomerID($this->dist_id);
        $data['customerID'] = $this->Sales_Model->checkDuplicateCusID($customerID, $this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);

        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        //$data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition);
        $condition2 = array(
            //'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_account_info', $condition2);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition);
        $data['voucherID'] = create_warranty_claim_no();
        /*this invoice no is comming  from sales_invoice_no_helper*/


        if ($this->business_type != "LPG") {
            $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_claim_voucher', $data, true);
        } else {
            $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_claim_voucher', $data, true);
        }


        $this->load->view($this->folder, $data);
    }



    public function warranty_claim_voucher_view($salesID)
    {
        /*if (is_numeric($salesID)) {
            //is invoice id is valid
            $validInvoiecId = $this->Sales_Model->checkInvoiceIdAndDistributor($this->dist_id, $salesID);
            if ($validInvoiecId === FALSE) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url('salesInvoice'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url('salesInvoice'));
        }*/
        /*page navbar details*/
        $data['title'] = get_phrase('warranty_claim_voucher_view');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_warranty_claim_voucher');
        $data['link_page_url'] = $this->project . '/warranty_claim_voucher';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('_warranty_claim_voucher_List');
        $data['second_link_page_url'] = $this->project . '/warranty_claim_voucher_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        /*$data['third_link_page_name'] = get_phrase('Sale_Invoice_Edit');
        $data['third_link_page_url'] = 'salesInvoice_edit/' . $salesID;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';*/
        /*page navbar details*/
        $data['saleslist'] = $this->Common_model->get_single_data_by_single_column('sales_invoice_info', 'sales_invoice_id', $salesID);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /* echo '<pre>';
         print_r($data['saleslist']);
         exit;*/

        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['saleslist']->customer_id);
        //$data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['saleslist']->customer_id);
        //$data['customerDue'] = $this->Sales_Model->getCustomerBalance($this->dist_id, $data['saleslist']->customer_id);
        $related_id = $data['saleslist']->customer_id;
        $for = 3;
        $ledger_id = get_customer_supplier_product_ledger_id($related_id, $for);
        $this->db->select('(sum(GR_DEBIT) -sum(GR_CREDIT)) as totalbalance');
        $this->db->where('CHILD_ID', $ledger_id->id);
        $this->db->where('IsActive', 1);
        $data['customerDue'] = $this->db->get('ac_tb_accounts_voucherdtl')->row();
        $stockList = $this->Common_model->get_sales_product_detaild2($salesID);
        foreach ($stockList as $ind => $element) {
            $result[$element->sales_invoice_id][$element->sales_details_id]['sales_invoice_id'] = $element->sales_invoice_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['sales_details_id'] = $element->sales_details_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['is_package'] = $element->is_package;
            $result[$element->sales_invoice_id][$element->sales_details_id]['product_id'] = $element->product_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['productName'] = $element->productName;
            $result[$element->sales_invoice_id][$element->sales_details_id]['product_code'] = $element->product_code;
            $result[$element->sales_invoice_id][$element->sales_details_id]['title'] = $element->title;
            $result[$element->sales_invoice_id][$element->sales_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->sales_invoice_id][$element->sales_details_id]['brandName'] = $element->brandName;
            $result[$element->sales_invoice_id][$element->sales_details_id]['quantity'] = $element->quantity;
            $result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;


            $result[$element->sales_invoice_id][$element->sales_details_id]['property_1'] = $element->property_1;
            $result[$element->sales_invoice_id][$element->sales_details_id]['property_2'] = $element->property_2;
            $result[$element->sales_invoice_id][$element->sales_details_id]['property_3'] = $element->property_3;
            $result[$element->sales_invoice_id][$element->sales_details_id]['property_4'] = $element->property_4;
            $result[$element->sales_invoice_id][$element->sales_details_id]['property_5'] = $element->property_5;
            $result[$element->sales_invoice_id][$element->sales_details_id]['property_5'] = $element->property_5;
            $result[$element->sales_invoice_id][$element->sales_details_id]['product_details'] = $element->product_details;


            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->sales_invoice_id][$element->sales_details_id]['return'][$element->sales_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'return_product_cat' => $element->return_product_cat,
                    'return_product_name' => $element->return_product_name,
                    'return_product_unit' => $element->return_product_unit,
                    'return_product_brand' => $element->return_product_brand,
                    'returnable_quantity' => $element->returnable_quantity,
                );
            } else {
                $result[$element->sales_invoice_id][$element->sales_details_id]['return'][$element->sales_details_id] = '';
            }
        }
        $data['stockList'] = $result;
        /*echo '<pre>';
        print_r( $data['stockList']);exit;*/
        if ($this->business_type == "LPG") {
            $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_claim_voucher_view', $data, true);

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        } else {
            $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_claim_voucher_view', $data, true);
        }


        $this->load->view('distributor/masterTemplate', $data);
    }














    public function warranty_claim_voucher_list()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('warranty_claim_voucher_list');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('warranty_claim_voucher_add');
        $data['link_page_url'] = $this->project . '/warranty_claim_voucher';
        $data['link_icon'] = "<i class='ace-icon fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_claim_voucher_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function warranty_receipt_voucher($confirId = null)
    {
        $this->load->helper('purchase_invoice_no_helper');
        //$this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {


            $query = $this->db->field_exists('product_details', 'purchase_details');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'product_details' => array(
                        'type' => 'TEXT',
                        'null' => TRUE,
                        //'constraint' => '255',
                        //'unsigned' => TRUE,
                        //'after' => 'Reference'
                    )
                );
                $this->dbforge->add_column('purchase_details', $fields);
            }


//form validation rules
            $this->form_validation->set_rules('supplierID', 'Supplier ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher ID', 'required');
            $this->form_validation->set_rules('purchasesDate', 'Purchases Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Date', 'required');
            $this->form_validation->set_rules('slNo[]', 'Payment Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/purchases_lpg_add'));
            } else {
                $totalGR_DEBIT=0;
                $totalGR_CREDIT=0;
                $bankName = $this->input->post('bankName');
                $purchasesDate=$this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
                $paymentType = $this->input->post('paymentType');
                $branch_id = $this->input->post('branch_id');
                $supplierID = $this->input->post('supplierID');
                $this->db->trans_start();
                $newCylinderProductCost = 0;
                $invoice_no = create_warranty_receipt_no();
                $purchase_inv['invoice_no'] = $invoice_no;
                /* this invoice no is comming  from purchase_invoice_no_helper */
                if ($paymentType == 3) {
                    $paid_amount = $this->input->post('netTotal');
                } else {
                    $paid_amount = $this->input->post('thisAllotment') != '' ? $this->input->post('thisAllotment') : 0;
                }
                $purchase_inv['supplier_invoice_no'] = $this->input->post('userInvoiceId');
                $purchase_inv['supplier_id'] = $supplierID;
                $purchase_inv['payment_type'] = $paymentType;
                $purchase_inv['invoice_amount'] = $this->input->post('netTotal');
                $purchase_inv['vat_amount'] = 0;
                $purchase_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $purchase_inv['paid_amount'] = $paid_amount;
                $purchase_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $purchase_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $purchase_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $purchase_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $purchase_inv['refference_person'] = $this->input->post('reference');
                $purchase_inv['narration'] = $this->input->post('narration');
                $purchase_inv['company_id'] = $this->dist_id;
                $purchase_inv['dist_id'] = $this->dist_id;
                $purchase_inv['branch_id'] = $branch_id;
                $purchase_inv['due_date'] = $this->input->post('dueDate') != '' ? date('Y-m-d', strtotime($this->input->post('dueDate'))) : '';//date('Y-m-d', strtotime($this->input->post('')));
                $purchase_inv['invoice_date'] =$purchasesDate; //date('Y-m-d', strtotime($this->input->post('')));
                $purchase_inv['insert_date'] = $this->timestamp;
                $purchase_inv['insert_by'] = $this->admin_id;
                $purchase_inv['is_active'] = 'Y';
                $purchase_inv['is_delete'] = 'N';
                $purchase_inv['for'] = 8;
                if ($paymentType == 3) {
                    $purchase_inv['bank_id'] = $bankName;
                    //$purchase_inv['bank_branch_id'] = $branchName = $this->input->post('branchName');
                    $purchase_inv['check_date'] = $checkDate = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $purchase_inv['check_no'] = $checkNo = $this->input->post('checkNo');
                }
                $this->invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);
                $allStock = array();




                if ($paymentType == 2) {
                    //for due invoice  Journal Voucher
                    $voucher_no = create_journal_voucher_no();
                    $AccouVoucherType_AutoID = 3;
                } else {
                    //Payment Voucher
                    $this->load->helper('create_payment_voucher_no_helper');
                    $voucher_no = create_payment_voucher_no();
                    $AccouVoucherType_AutoID = 2;
                }
                $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = $purchasesDate;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $this->invoice_id;
                $accountingMasterTable['Narration'] = 'Warranty Receipt Voucher ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['supplier_id'] = $this->input->post('supplierID');
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingMasterTable['for'] =$this->config->item("accounting_master_table_for_warranty_receipt_voucher");
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);


                $EmptyCylinderProductCost = 0;
                $RefillProductCost = 0;
                $otherProductCost = 0;
                $allEmptyCylinderArray = array();
                $allEmptyCylinderReturnArray = array();
                $allEmptyCylinderWithRefillArray = array();
                $allRefillCylinderArray = array();
                $allOtherProductArray = array();
                foreach ($_POST['slNo'] as $key => $value) {
                    $supplier_advance = 0;
                    $supplier_due = 0;
                    $emptyCylindet = array();
                    $emptyCylindetReturn=array();
                    $emptyCylindetWithRefill=array();
                    $refillCylindet = array();
                    $otherProduct = array();

                    $lastPurchasepriceArray = $this->db->where('product_id', $_POST['product_id_' . $value])
                        ->where('branch_id', $branch_id)
                        ->order_by('purchase_details_id', "desc")
                        ->limit(1)
                        ->get('purchase_details')
                        ->row();
                    $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;

                    unset($stock);
                    $returnable_quantity = $_POST['add_returnAble'][$value] != '' ? $_POST['add_returnAble'][$value] : 0;
                    $return_quentity = empty($_POST['returnQuentity_' . $value]) ? 0 : array_sum($_POST['returnQuentity_' . $value]);
                    if ($returnable_quantity < $return_quentity) {
                        $supplier_advance = $return_quentity - $returnable_quantity;
                    } else {
                        $supplier_due = $returnable_quantity - $return_quentity;
                    }
                    $stock['purchase_invoice_id'] = $this->invoice_id;
                    $stock['product_id'] = $product_id = $_POST['product_id_' . $value];
                    $stock['package_id'] = $_POST['package_id_' . $value];
                    $stock['is_package '] = $_POST['is_package_' . $value];
                    $stock['returnable_quantity '] = $returnable_quantity;
                    $stock['return_quentity '] = $return_quentity;
                    $stock['supplier_due'] = $supplier_due;
                    $stock['supplier_advance'] = $supplier_advance;
                    $stock['quantity'] = $_POST['quantity_' . $value];
                    $stock['unit_price'] = $_POST['rate_' . $value];
                    $stock['insert_by'] = $this->admin_id;
                    $stock['insert_date'] = $this->timestamp;
                    $stock['branch_id'] = $branch_id;
                    $stock['supplier_id '] = $supplierID;
                    $stock['property_1'] =$_POST['property_1_' . $value];
                    $stock['property_2'] =$_POST['property_2_' . $value];
                    $stock['property_3'] =$_POST['property_3_' . $value];
                    $stock['property_4'] =$_POST['property_4_' . $value];
                    $stock['property_5'] =$_POST['property_5_' . $value];
                    $stock['product_details'] =$_POST['narration_of_product_' . $value];

                    $purchase_details_id = $this->Common_model->insert_data('purchase_details', $stock);
                    $category_id = $this->Common_model->tableRow('product', 'product_id', $product_id)->category_id;
                    //$newCylinderProductCost = $newCylinderProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);


                    $packageEmptyProductId=0;
                    if ($category_id == 2 && $_POST['is_package_' . $value] == 0) {
                        $packageEmptyProductId = $this->getPackageEmptyProductId($_POST['product_id_' . $value]);
                    }


                    $stockNewTable['parent_stock_id']=0;
                    $stockNewTable['invoice_id']=$this->invoice_id;
                    $stockNewTable['form_id']=6;
                    $stockNewTable['type']=1;
                    $stockNewTable['Accounts_VoucherMst_AutoID']=$accountingVoucherId;
                    $stockNewTable['Accounts_VoucherDtl_AutoID']=0;
                    $stockNewTable['customer_id']=0;
                    $stockNewTable['supplier_id']=$this->input->post('supplierID');
                    $stockNewTable['branch_id']=$branch_id;
                    $stockNewTable['invoice_date']=$purchasesDate;
                    $stockNewTable['category_id']=$category_id;
                    $stockNewTable['product_id']=$_POST['product_id_' . $value];
                    $stockNewTable['empty_cylinder_id']=$packageEmptyProductId;
                    $stockNewTable['is_package']=$_POST['is_package_' . $value];
                    $stockNewTable['show_in_invoice']=1;
                    $stockNewTable['unit']=getProductUnit($_POST['product_id_' . $value]);

                    $stockNewTable['quantity']=$_POST['quantity_' . $value];
                    $stockNewTable['quantity_out']=0;
                    $stockNewTable['quantity_in']=$_POST['quantity_' . $value];
                    $stockNewTable['returnable_quantity']=$returnable_quantity;
                    $stockNewTable['return_quentity']=$return_quentity;
                    $stockNewTable['due_quentity']=$supplier_due;
                    $stockNewTable['advance_quantity']=$supplier_advance;
                    $stockNewTable['price']=$_POST['rate_' . $value];
                    $stockNewTable['price_in']=$_POST['rate_' . $value];
                    $stockNewTable['price_out']=0;
                    $stockNewTable['last_purchase_price']=$lastPurchaseprice;
                    $stockNewTable['product_details']="";
                    $stockNewTable['property_1'] =$_POST['property_1_' . $value];
                    $stockNewTable['property_2'] =$_POST['property_2_' . $value];
                    $stockNewTable['property_3'] =$_POST['property_3_' . $value];
                    $stockNewTable['property_4'] =$_POST['property_4_' . $value];
                    $stockNewTable['property_5'] =$_POST['property_5_' . $value];
                    $stockNewTable['openingStatus']=0;
                    $stockNewTable['insert_by'] = $this->admin_id;
                    $stockNewTable['insert_date'] = $this->timestamp;
                    $stockNewTable['update_by']='';
                    $stockNewTable['update_date']='';
                    $stock_id = $this->Common_model->insert_data('stock', $stockNewTable);

                    if ($category_id == 1) {
                        //Empty Cylinder
                        $EmptyCylinderProductCost += ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $emptyCylindet['product_id'] = $_POST['product_id_' . $value];
                        $emptyCylindet['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allEmptyCylinderArray[] = $emptyCylindet;
                    } elseif ($category_id == 2) {
                        //Refill
                        $RefillProductCost += ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $refillCylindet['product_id'] = $_POST['product_id_' . $value];
                        $refillCylindet['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allRefillCylinderArray[] = $refillCylindet;
                    } else {
                        $otherProductCost += ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $otherProduct['product_id'] = $_POST['product_id_' . $value];
                        $otherProduct['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $otherProduct['quantity'] = ($_POST['quantity_' . $value]);
                        $otherProduct['unit_price'] = ($_POST['rate_' . $value]);
                        $otherProduct['stock_id'] = $stock_id;

                        $allOtherProductArray[] = $otherProduct;
                    }
                    if($category_id==2 && $_POST['is_package_' . $value]==0){
                        $packageEmptyProductId=$this->getPackageEmptyProductId($_POST['product_id_' . $value]);


                        if($packageEmptyProductId==""){
                            $this->db->trans_rollback();
                            $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message").' There is something wrong please try again .contact with Customer Care';
                            $this->session->set_flashdata('error', $msg);
                            redirect(site_url($this->project . '/purchases_lpg_add'));
                        }

                        $lastPurchasepriceArray = $this->db->where('product_id', $_POST['product_id_' . $value])
                            ->where('branch_id', $branch_id)
                            ->order_by('purchase_details_id', "desc")
                            ->limit(1)
                            ->get('purchase_details')
                            ->row();
                        $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;

                        //sitehelper
                        //$product_last_purchase_price=get_product_last_purchase_price($packageEmptyProductId);
                        //$product_last_purchase_price=$this->Sales_Model->emptyCylinderPurchasePrice($packageEmptyProductId, $this->dist_id);;
                        $product_last_purchase_price=$this->Common_model->get_single_data_by_single_column('product', 'product_id', $packageEmptyProductId)->purchases_price;

                        $emptyCylindetWithRefill['product_id'] = $packageEmptyProductId;
                        $emptyCylindetWithRefill['price'] = ($product_last_purchase_price * $_POST['quantity_' . $value]);
                        $allEmptyCylinderWithRefillArray[] = $emptyCylindetWithRefill;
                        $productCost = $this->Sales_Model->emptyCylinderPurchasePrice($packageEmptyProductId, $this->dist_id);




                        unset($stock);

                        $stock['purchase_invoice_id'] = $this->invoice_id;
                        $stock['product_id'] = $packageEmptyProductId;
                        $stock['package_id'] = 0;
                        $stock['is_package '] = 0;
                        $stock['returnable_quantity '] =0;
                        $stock['return_quentity '] = 0;
                        $stock['supplier_due'] = 0;
                        $stock['supplier_advance'] = 0;
                        $stock['quantity'] = $_POST['quantity_' . $value];
                        $stock['unit_price'] = ($product_last_purchase_price );
                        $stock['insert_by'] = $this->admin_id;
                        $stock['insert_date'] = $this->timestamp;
                        $stock['branch_id'] = $branch_id;
                        $stock['supplier_id '] = $supplierID;
                        $stock['show_in_invoice'] = 0;
                        $this->Common_model->insert_data('purchase_details', $stock);



                        $stockNewTable=array();
                        $stockNewTable['parent_stock_id']=0;
                        $stockNewTable['invoice_id']=$this->invoice_id;
                        $stockNewTable['form_id']=6;
                        $stockNewTable['type']=1;
                        $stockNewTable['Accounts_VoucherMst_AutoID']=$accountingVoucherId;
                        $stockNewTable['Accounts_VoucherDtl_AutoID']=0;
                        $stockNewTable['customer_id']=0;
                        $stockNewTable['supplier_id']=$this->input->post('supplierID');
                        $stockNewTable['branch_id']=$branch_id;
                        $stockNewTable['invoice_date']=$purchasesDate;
                        $stockNewTable['category_id']=$category_id;
                        $stockNewTable['product_id']=$packageEmptyProductId;
                        $stockNewTable['empty_cylinder_id']=$packageEmptyProductId;
                        $stockNewTable['is_package']=0;
                        $stockNewTable['show_in_invoice']=0;
                        $stockNewTable['unit']=getProductUnit($packageEmptyProductId);

                        $stockNewTable['quantity']=$_POST['quantity_' . $value];
                        $stockNewTable['quantity_out']=0;
                        $stockNewTable['quantity_in']=$_POST['quantity_' . $value];
                        $stockNewTable['returnable_quantity']=0;
                        $stockNewTable['return_quentity']=0;
                        $stockNewTable['due_quentity']=0;
                        $stockNewTable['advance_quantity']=0;
                        $stockNewTable['price']=$product_last_purchase_price;
                        $stockNewTable['price_in']=$product_last_purchase_price;
                        $stockNewTable['price_out']=0;
                        $stockNewTable['last_purchase_price']=$lastPurchaseprice;
                        $stockNewTable['product_details']="";
                        $stockNewTable['property_1'] =$_POST['property_1_' . $value];
                        $stockNewTable['property_2'] =$_POST['property_2_' . $value];
                        $stockNewTable['property_3'] =$_POST['property_3_' . $value];
                        $stockNewTable['property_4'] =$_POST['property_4_' . $value];
                        $stockNewTable['property_5'] =$_POST['property_5_' . $value];
                        $stockNewTable['openingStatus']=0;
                        $stockNewTable['insert_by'] = $this->admin_id;
                        $stockNewTable['insert_date'] = $this->timestamp;
                        $stockNewTable['update_by']='';
                        $stockNewTable['update_date']='';
                        $this->Common_model->insert_data('stock', $stockNewTable);





                    }
                    if (isset($_POST['returnproduct_' . $value])) {
                        foreach ($_POST['returnproduct_' . $value] as $key1 => $value1) {
                            unset($stock2);

                            $product_last_purchase_price=$_POST['returnQuentity_Price_' . $value][$key1];

                            $emptyCylindetReturn['product_id'] = $value1;
                            $emptyCylindetReturn['price'] = ($product_last_purchase_price * $_POST['returnQuentity_' . $value][$key1]);
                            $allEmptyCylinderReturnArray[]=$emptyCylindetReturn;
                            $stock2['purchase_details_id'] = $purchase_details_id;
                            $stock2['product_id'] = $value1;
                            $stock2['returnable_quantity'] = $_POST['returnQuentity_' . $value][$key1];
                            $stock2['purchase_invoice_id'] = $this->invoice_id;
                            $stock2['supplier_id '] = $supplierID;
                            $stock2['return_quantity'] = $_POST['returnQuentity_' . $value][$key1];
                            $stock2['insert_by'] = $this->admin_id;
                            $stock2['insert_date'] = $this->timestamp;
                            $stock2['branch_id'] = $branch_id;
                            //$stock2['unit_price'] = get_product_purchase_price($value1);;
                            $stock2['unit_price'] = $product_last_purchase_price;
                            $allStock[] = $stock2;

                            $stockNewTable=array();
                            $stockNewTable['parent_stock_id']=$stock_id;
                            $stockNewTable['invoice_id']=$this->invoice_id;
                            $stockNewTable['form_id']=6;
                            $stockNewTable['type']=2;
                            $stockNewTable['Accounts_VoucherMst_AutoID']=$accountingVoucherId;
                            $stockNewTable['Accounts_VoucherDtl_AutoID']=0;
                            $stockNewTable['customer_id']=0;
                            $stockNewTable['supplier_id']=$this->input->post('supplierID');
                            $stockNewTable['branch_id']=$branch_id;
                            $stockNewTable['invoice_date']=$purchasesDate;
                            $stockNewTable['category_id']=$category_id;
                            $stockNewTable['product_id']=$value1;
                            $stockNewTable['empty_cylinder_id']=$value1;
                            $stockNewTable['is_package']=0;
                            $stockNewTable['show_in_invoice']=1;
                            $stockNewTable['unit']=getProductUnit($value1);

                            $stockNewTable['quantity']=$_POST['returnQuentity_' . $value][$key1];
                            $stockNewTable['quantity_out']=$_POST['returnQuentity_' . $value][$key1];
                            $stockNewTable['quantity_in']=0;
                            $stockNewTable['returnable_quantity']=0;
                            $stockNewTable['return_quentity']=0;
                            $stockNewTable['due_quentity']=0;
                            $stockNewTable['advance_quantity']=0;
                            $stockNewTable['price']=$product_last_purchase_price;
                            $stockNewTable['price_in']=0;
                            $stockNewTable['price_out']=$product_last_purchase_price;
                            $stockNewTable['last_purchase_price']=$lastPurchaseprice;
                            $stockNewTable['product_details']="";
                            $stockNewTable['property_1'] =$_POST['property_1_' . $value];
                            $stockNewTable['property_2'] =$_POST['property_2_' . $value];
                            $stockNewTable['property_3'] =$_POST['property_3_' . $value];
                            $stockNewTable['property_4'] =$_POST['property_4_' . $value];
                            $stockNewTable['property_5'] =$_POST['property_5_' . $value];
                            $stockNewTable['openingStatus']=0;
                            $stockNewTable['insert_by'] = $this->admin_id;
                            $stockNewTable['insert_date'] = $this->timestamp;
                            $stockNewTable['update_by']='';
                            $stockNewTable['update_date']='';
                            $this->Common_model->insert_data('stock', $stockNewTable);

                        }
                    }
                }
                $this->db->insert_batch('purchase_return_details', $allStock);




                $accountingDetailsTable = array();



                if (!empty($allEmptyCylinderArray)) {
                    foreach ($allEmptyCylinderArray as $keyEmpCly => $valueEmpCly) {
                        $condition = array(
                            'related_id' => $valueEmpCly['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;
                        $accountingDetailsTable['GR_DEBIT'] = $valueEmpCly['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'New Cylinder Stock';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();
                        $totalGR_DEBIT=$totalGR_DEBIT+$valueEmpCly['price'];
                        $totalGR_CREDIT=$totalGR_CREDIT+0;

                    }
                }
                if (!empty($allRefillCylinderArray)) {
                    foreach ($allRefillCylinderArray as $keyRefill => $valueRefill) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueRefill['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//'95';
                        $accountingDetailsTable['GR_DEBIT'] = $valueRefill['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'Refill  Cylinder Purchase';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();
                        $totalGR_DEBIT=$totalGR_DEBIT+$valueRefill['price'];
                        $totalGR_CREDIT=$totalGR_CREDIT+0;
                    }
                }
                if (!empty($allEmptyCylinderWithRefillArray)) {
                    foreach ($allEmptyCylinderWithRefillArray as $keyEmptyWithRefill => $valueEmptyWithRefill) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueEmptyWithRefill['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//'95';
                        $accountingDetailsTable['GR_DEBIT'] = $valueEmptyWithRefill['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'Empty Cylinder With Refill Cylinder Purchase';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();

                        $totalGR_DEBIT=$totalGR_DEBIT+$valueEmptyWithRefill['price'];
                        $totalGR_CREDIT=$totalGR_CREDIT+0;
                    }
                }
                if (!empty($allEmptyCylinderReturnArray)) {
                    foreach ($allEmptyCylinderReturnArray as $keyEmpClyReturn => $valueEmpClyReturn) {
                        $condition = array(
                            'related_id' => $valueEmpClyReturn['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '2';//Cr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;
                        $accountingDetailsTable['GR_DEBIT'] = '0.00';
                        $accountingDetailsTable['GR_CREDIT'] = $valueEmpClyReturn['price'];
                        $accountingDetailsTable['Reference'] = 'Empty Cylinder Return';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();

                        $totalGR_DEBIT=$totalGR_DEBIT+0;
                        $totalGR_CREDIT=$totalGR_CREDIT+$valueEmpClyReturn['price'];
                    }
                }
                if (!empty($allOtherProductArray)) {
                    foreach ($allOtherProductArray as $keyOtherProduct => $valueOtherProduct) {
                        //Refill====>95
                        $condition = array(
                            'related_id' => $valueOtherProduct['product_id'],
                            'related_id_for' => 1,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        /*Inventory stock=>20*/
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;
                        $accountingDetailsTable['GR_DEBIT'] = $valueOtherProduct['price'];
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'General Product ';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $branch_id;
                        $accountingDetailsTable['date'] = $purchasesDate;
                        //$finalDetailsArray[] = $accountingDetailsTable;

                        $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTable);

                        $data['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                        $this->Common_model->update_data('stock', $data, 'stock_id', $valueOtherProduct['stock_id']);
                        $accountingDetailsTable = array();

                        $totalGR_DEBIT=$totalGR_DEBIT+$valueOtherProduct['price'];
                        $totalGR_CREDIT=$totalGR_CREDIT+0;
                    }
                }
                /*Inventory stock Refill=>95*/
                /*Inventory Stock New Cylinder Stock*/
                /*Loading and wages*/
                if ($this->input->post('loaderAmount') > 0) {
                    //Loading & Wages====>47  Loading_Wages
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $this->config->item("Common_Loading_Wages");//47';
                    $accountingDetailsTable['GR_DEBIT'] = $this->input->post('loaderAmount');
                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable['Reference'] = 'Loading and wages Cost Of Goods';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;
                    $accountingDetailsTable = array();

                    $totalGR_DEBIT=$totalGR_DEBIT+$this->input->post('loaderAmount');
                    $totalGR_CREDIT=$totalGR_CREDIT+0;
                }
                /*Loading and wages*/
                /*Transportation =====>42*/
                if ($this->input->post('transportationAmount') > 0) {
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $this->config->item("Common_Transportation");//Transportation'42';
                    $accountingDetailsTable['GR_DEBIT'] = $this->input->post('transportationAmount');
                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable['Reference'] = 'Transportation Cost Of Goods';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;
                    $accountingDetailsTable = array();

                    $totalGR_DEBIT=$totalGR_DEBIT+$this->input->post('transportationAmount');
                    $totalGR_CREDIT=$totalGR_CREDIT+0;
                }
                /*Transportation*/
                $condtion = array(
                    'related_id' => $supplierID,
                    'related_id_for' => 2,
                    'is_active' => "Y",
                );
                $supplier_payable = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                /*Supplier Payable =======>34*/
                $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable['TypeID'] = '2';//Cr
                $accountingDetailsTable['CHILD_ID'] = $supplier_payable->id;//Supplier_Payables'34';
                $accountingDetailsTable['GR_DEBIT'] = '0.00';
                $accountingDetailsTable['GR_CREDIT'] = $this->input->post('netTotal');
                $accountingDetailsTable['Reference'] = 'Supplier Payable';
                $accountingDetailsTable['IsActive'] = 1;
                $accountingDetailsTable['Created_By'] = $this->admin_id;
                $accountingDetailsTable['Created_Date'] = $this->timestamp;
                $accountingDetailsTable['BranchAutoId'] = $branch_id;
                $accountingDetailsTable['date'] = $purchasesDate;
                $finalDetailsArray[] = $accountingDetailsTable;
                $accountingDetailsTable = array();
                $totalGR_DEBIT=$totalGR_DEBIT+0;
                $totalGR_CREDIT=$totalGR_CREDIT+$this->input->post('netTotal');
                // }
                /*Supplier Payable*/
                if ($paymentType == 4) {
                    //cash payment
                    /*Supplier Payable =======>34*/
                    //supplier paid amount
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $supplier_payable->id;//Supplier_Payables'34';
                    $accountingDetailsTable['GR_DEBIT'] = $this->input->post('thisAllotment');
                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable['Reference'] = 'Supplier paid amount';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;
                    $accountingDetailsTable = array();

                    $totalGR_DEBIT=$totalGR_DEBIT+$this->input->post('thisAllotment');
                    $totalGR_CREDIT=$totalGR_CREDIT+0;
                    //supplier paid amount
                    /*Cash in hand*/
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable['TypeID'] = '2';//Dr
                    $accountingDetailsTable['CHILD_ID'] = $this->input->post('accountCrPartial');
                    $accountingDetailsTable['GR_DEBIT'] = '0.00';
                    $accountingDetailsTable['GR_CREDIT'] = $this->input->post('thisAllotment');
                    $accountingDetailsTable['Reference'] = 'Supplier Paid Amount in Cash In Hand Group';
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTable['date'] = $purchasesDate;
                    $finalDetailsArray[] = $accountingDetailsTable;


                    $accountingDetailsTable = array();

                    $totalGR_DEBIT=$totalGR_DEBIT+0;
                    $totalGR_CREDIT=$totalGR_CREDIT+$this->input->post('thisAllotment');
                }
                $totalGR_DEBIT = number_format($totalGR_DEBIT, 2, '.', '');

                $totalGR_CREDIT = number_format($totalGR_CREDIT, 2, '.', '');
                if($totalGR_DEBIT!=$totalGR_CREDIT){
                    $this->db->trans_rollback();
                    $msg = 'Warranty Receipt Voucher ' . ' ' . $this->config->item("save_error_message").' There is something wrong please try again .contact with Customer Care';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/warranty_receipt_voucher'));
                }


                if (!empty($finalDetailsArray)) {

                    $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                }
                /*client_vendor_ledger table data insert*/
                $supp = array(
                    'ledger_type' => 2,
                    'trans_type' => 'purchase',
                    'history_id' => 0,
                    'trans_type' => $this->input->post('voucherid'),
                    'client_vendor_id' => $this->input->post('supplierID'),
                    'invoice_id' => $this->invoice_id,
                    'invoice_type' => '1',
                    'Accounts_VoucherType_AutoID' => '5',
                    'updated_by' => $this->admin_id,
                    'dist_id' => $this->dist_id,
                    'amount' => $this->input->post('netTotal'),
                    'dr' => $this->input->post('netTotal'),
                    'date' => $purchasesDate,
                    'paymentType' => 'Purchase Voucher',
                    'BranchAutoId' => $branch_id
                );
                $this->db->insert('client_vendor_ledger', $supp);
                if ($paymentType == 4) {
                    //cash payment
                    $supp1 = array(
                        'ledger_type' => 2,
                        'dist_id' => $this->dist_id,
                        'trans_type' => $invoice_no,
                        'client_vendor_id' => $this->input->post('supplierID'),
                        'amount' => $this->input->post('thisAllotment'),
                        'cr' => $this->input->post('thisAllotment'),
                        'invoice_id' => $this->invoice_id,
                        'invoice_type' => '1',
                        'Accounts_VoucherType_AutoID' => '5',
                        'date' => $purchasesDate,
                        'updated_by' => $this->admin_id,
                        'history_id' => $accountingVoucherId,
                        'paymentType' => 'Supplier Payment',
                        'BranchAutoId' => $branch_id
                    );
                    $this->db->insert('client_vendor_ledger', $supp1);
                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => $purchasesDate,
                        'invoiceID' => $this->invoice_id,
                        'totalPayment' => $this->input->post('thisAllotment'),
                        'receitID' => $mrid,
                        'mainInvoiceId' => $accountingVoucherId,
                        'Accounts_VoucherMst_AutoID' => $accountingVoucherId,
                        'customerid' => $this->input->post('supplierID'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $branch_id,
                        'paymentType' => 1,
                        'receiveType' => 2,
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
                        //'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $moneyReceit);
                }
                if ($paymentType == 3) {
                    //check payment
                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => $purchasesDate,
                        'invoiceID' => $this->invoice_id,
                        //'invoiceID' => $invoice_no,
                        'totalPayment' => $this->input->post('netTotal'),
                        'receitID' => $mrid,
                        'mainInvoiceId' => $this->invoice_id,
                        'Accounts_VoucherMst_AutoID' => $accountingVoucherId,
                        'customerid' => $this->input->post('supplierID'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $branch_id,
                        'paymentType' => 2,
                        'receiveType' => 2,
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
                        //'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $moneyReceit);
                }
                /*client_vendor_ledger table data insert*/
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/warranty_receipt_voucher'));
                } else {
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/warranty_receipt_voucher_view/' . $this->invoice_id));
                }
            }
        }

        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();

        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);
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
        $data['packageList'] = $this->Common_model->getPublicPackageList($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        $data['cylinderProduct_jason'] = json_encode($this->Common_model->getPublicProduct($this->dist_id, 1));
        //echo "<pre>";
        //print_r($data['cylinderProduct_jason']);exit;
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);
        /* page navbar details */
        $data['title'] = get_phrase('warranty_receipt_voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('warranty_receipt_voucherList');
        $data['link_page_url'] = $this->project . '/warranty_receipt_voucher_list';
        $data['link_icon'] = $this->link_icon_list;
        /* page navbar details */
        $condition2 = array(
            //'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_account_info', $condition2);
        $data['voucherID'] = create_warranty_receipt_no();
        /* this invoice no is comming  from purchase_invoice_no_helper */
        $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_receipt_voucher', $data, true);


        $this->load->view($this->folder, $data);
    }


    public function warranty_receipt_voucher_list()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('warranty_receipt_voucher_list');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('warranty_receipt_voucher_add');
        $data['link_page_url'] = $this->project . '/warranty_receipt_voucher';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_receipt_voucher_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }



    function warranty_receipt_voucher_view($purchases_id = NULL)
    {
        /*page navbar details*/
        $data['title'] = get_phrase(' warranty_receipt_voucher_view');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('warranty_receipt_voucher_add');
        $data['link_page_url'] = $this->project . '/warranty_receipt_voucher';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('warranty_receipt_voucher_list');
        $data['second_link_page_url'] = $this->project . '/warranty_receipt_voucher_list';
        $data['second_link_icon'] = $this->link_icon_list;
        /*$data['third_link_page_name'] = get_phrase('Edit_Invoice');
        $data['third_link_page_url'] = $this->project . '/purchases_lpg_edit/' . $purchases_id;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        //$data['title'] = 'Purchases View';
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $purchases_id);
        //$data['supplier_due']=$this->Inventory_Model->getSupplierClosingBalance($this->dist_id, $data['purchasesList']->supplier_id);
        $stockList = $this->Common_model->get_purchase_product_detaild2($purchases_id);
        foreach ($stockList as $ind => $element) {
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_invoice_id'] = $element->purchase_invoice_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_details_id'] = $element->purchase_details_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['is_package'] = $element->is_package;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_id'] = $element->product_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['productName'] = $element->productName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_code'] = $element->product_code;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['title'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['brandName'] = $element->brandName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['quantity'] = $element->quantity;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unit_price'] = $element->unit_price;

            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_1'] = $element->property_1;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_2'] = $element->property_2;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_3'] = $element->property_3;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_4'] = $element->property_4;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['property_5'] = $element->property_5;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_details'] = $element->product_details;
            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'return_product_cat' => $element->return_product_cat,
                    'return_product_name' => $element->return_product_name,
                    'return_product_unit' => $element->return_product_unit,
                    'return_product_brand' => $element->return_product_brand,
                    'returnable_quantity' => $element->returnable_quantity,
                );
            } else {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id] = '';
            }
        }
        /*echo  $this->db->last_query();
         echo "<pre>";
         print_r($stockList);
         print_r($result);
         exit;*/
        $data['stockList'] = $result;
        $data['creditAmount'] = $paymentInfo = $this->Inventory_Model->getCreditAmount($purchases_id);
        $data['supplier_due'] = $paymentInfo = $this->Inventory_Model->getCustomerBalance('',$data['purchasesList']->supplier_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['purchasesList']->supplier_id);

        $data['mainContent'] = $this->load->view('distributor/warranty_management/warranty_receipt_voucher_view', $data, true);

        $this->load->view('distributor/masterTemplate', $data);
    }













}