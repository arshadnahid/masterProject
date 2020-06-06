<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/26/2019
 * Time: 9:38 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesLpgController extends CI_Controller
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

    public function salesLpgInvoice_add($confirmId = null)
    {
        $this->load->helper('sales_invoice_no_helper');
        $this->load->helper('branch_dropdown_helper');

        if (isPostBack()) {


            $query = $this->db->field_exists('cash_ledger_id', 'sales_invoice_info');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'cash_ledger_id' => array(
                        'type' => 'INT',
                        'null' => TRUE,
                        'default' => '0',
                        )
                );
                $this->dbforge->add_column('sales_invoice_info', $fields);
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
                $invoice_no = create_sales_invoice_no();
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
                $sales_inv['invoice_for'] = 2;
                $sales_inv['is_active'] = 'Y';
                $sales_inv['is_delete'] = 'N';

                if ($payType == 3) {
                    $sales_inv['bank_id'] = $bankName;
                    //$sales_inv['bank_branch_id'] = $branchName = $this->input->post('branchName');
                    $sales_inv['check_date'] = $checkDate = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $sales_inv['check_no'] = $checkNo = $this->input->post('checkNo');
                }
                $cash_ledger_id=0;
                if($payType == 4){
                    $cash_ledger_id= $this->input->post('accountCrPartial');
                }
                $sales_inv['cash_ledger_id'] = $cash_ledger_id;
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

                $query = $this->db->field_exists('due_collection_details_id', 'ac_accounts_vouchermst');
                if ($query != TRUE) {
                    $this->load->dbforge();
                    $fields = array(
                        'for' => array(
                            'type' => 'INT',
                            'null' => TRUE,
                            'default' => '0',
                            //'unsigned' => TRUE,
                            'after' => 'for')
                    );
                    $this->dbforge->add_column('ac_accounts_vouchermst', $fields);
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
                $accountingMasterTable['for'] = 2;
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
                    $stockNewTable['form_id'] = 3;
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
                            ->where('branch_id', $branch_id)
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
                        $stockNewTable['form_id'] = 3;
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
                            $stockNewTable['form_id'] = 3;
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
                            $stockNewTable['type'] = 1;
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
                $accountingDetailsTableSales['CHILD_ID'] = $this->config->item("Sales");
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
                    'related_id_for' => 3,
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
                $accountingDetailsTableCostofGoodsProduct['CHILD_ID'] = $this->config->item("Cost_of_Goods_Product");//'45';
                $accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = $totalProductCost;
                $accountingDetailsTableCostofGoodsProduct['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCostofGoodsProduct['Reference'] = 'Cost of Goods Product';
                $accountingDetailsTableCostofGoodsProduct['IsActive'] = 1;
                $accountingDetailsTableCostofGoodsProduct['Created_By'] = $this->admin_id;
                $accountingDetailsTableCostofGoodsProduct['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCostofGoodsProduct['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCostofGoodsProduct['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCostofGoodsProduct;

                $totalGR_DEBIT = $totalGR_DEBIT + $totalProductCost;
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
                    redirect(site_url($this->project . '/salesLpgInvoice_add'));
                }


                if (!empty($finalDetailsArray)) {
                    $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                }
                /*client_vendor_ledger table data insert*/

                if ($payType == 4) {

                    $mrCondition = array(

                        'receiveType' => 1,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "CMR" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $moneyReceit = array(
                        'date' => $saleDate,
                        'invoiceID' => $this->invoice_id,
                        'totalPayment' => $this->input->post('partialPayment'),
                        'receitID' => $mrid,
                        'mainInvoiceId' =>  $this->invoice_id,
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
                    redirect(site_url($this->project . '/salesLpgInvoice_add/'));
                } else {
                    $msg = 'sales Invoice ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('New Sale Invoice');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Invoice List');
        $data['link_page_url'] = $this->project . '/salesInvoiceLpg';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $customerID = $this->Sales_Model->getCustomerID($this->dist_id);
        $data['customerID'] = $this->Sales_Model->checkDuplicateCusID($customerID, $this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);

        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);

        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
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
        $data['voucherID'] = create_sales_invoice_no();
        /*this invoice no is comming  from sales_invoice_no_helper*/


        if ($this->business_type != "LPG") {
            $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceMobile/sale_add', $data, true);
        } else {
            $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceLpg/sale_add', $data, true);
        }


        $this->load->view($this->folder, $data);
    }

    public function salesInvoice_edit($invoiceId = null)
    {







        /* check Invoice id valid ? or not */
        if (is_numeric($invoiceId)) {
            //is invoice id is valid
            $validInvoiecId = $this->Sales_Model->checkInvoiceIdAndDistributor($this->dist_id, $invoiceId);
            if ($validInvoiecId === FALSE) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url('salesInvoice'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url('salesInvoice_edit/' . $invoiceId));
        }
        /* check Invoice id valid ? or not */
        if (isPostBack()) {
            $invoiceId = $invoiceId;

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

                $invoice_no = $this->input->post('voucherid');
                /*this invoice no is comming  from sales_invoice_no_helper*/

                $sales_inv['customer_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_inv['customer_id'] = $customer_id;
                $sales_inv['payment_type'] = $payType;
                $sales_inv['invoice_amount'] = $this->input->post('netTotal');
                $sales_inv['vat_amount'] = 0;
                $sales_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $sales_inv['paid_amount'] = $this->input->post('partialPayment') != '' ? $this->input->post('partialPayment') : 0;
                $sales_inv['delivery_address'] = $this->input->post('shippingAddress');
                $sales_inv['delivery_date'] = $this->input->post('delivery_date') != '' ? date($this->input->post('delivery_date')) : '';
                $sales_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $sales_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $sales_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $sales_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $sales_inv['refference_person_id'] = $this->input->post('reference');
                $sales_inv['company_id'] = $this->dist_id;
                $sales_inv['narration'] = $this->input->post('narration');
                $sales_inv['dist_id'] = $this->dist_id;
                $sales_inv['branch_id'] = $branch_id;

                if ($this->input->post('dueDate') != '') {
                    $sales_inv['due_date'] = $this->input->post('dueDate') != '' ? date('Y-m-d', strtotime($this->input->post('dueDate'))) : 'NULL';
                }
                $sales_inv['invoice_date'] = $saleDate;
                $sales_inv['insert_date'] = $this->timestamp;
                $sales_inv['insert_by'] = $this->admin_id;
                $sales_inv['invoice_for'] = 2;
                $sales_inv['is_active'] = 'Y';
                $sales_inv['is_delete'] = 'N';
                if ($payType == 3) {
                    $sales_inv['bank_id'] = $bankName;
                    //$sales_inv['bank_branch_id'] = $branchName = $this->input->post('branchName');
                    $sales_inv['check_date'] = $checkDate = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $sales_inv['check_no'] = $checkNo = $this->input->post('checkNo');
                }
                $sales_invUpdateCondition = array(
                    'sales_invoice_id' => $invoiceId,
                    'invoice_for' => 2,
                );

                $this->Common_model->save_and_check('sales_invoice_info', $sales_inv, $sales_invUpdateCondition);
                $this->invoice_id = $invoiceId;
                $DeleteCondition = array(
                    'sales_invoice_id' => $invoiceId
                );
                $this->Common_model->delete_data_with_condition('sales_details', $DeleteCondition);
                $this->Common_model->delete_data_with_condition('sales_return_details', $DeleteCondition);
                $UpdateAccountsMasterCondition = array(
                    'for' => 2,
                    'BackReferenceInvoiceNo' => $this->input->post('voucherid'),
                    'BackReferenceInvoiceID' => $invoiceId,
                );


                $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $UpdateAccountsMasterCondition);

                if ($payType == 2) {
                    //for due invoice  Journal Voucher
                    $AccouVoucherType_AutoID = 3;
                } else {
                    //Payment Voucher
                    $AccouVoucherType_AutoID = 1;
                }

                $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
                $accountingMasterTable['Accounts_Voucher_Date'] = $saleDate;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $this->input->post('voucherid');
                $accountingMasterTable['BackReferenceInvoiceID'] = $invoiceId;
                $accountingMasterTable['Narration'] = 'Sales Voucher ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['customer_id'] = $this->input->post('customer_id');
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['for'] = 2;
                $accountingMasterTable['Changed_By'] = $this->admin_id;
                $accountingMasterTable['Changed_Date'] = $this->timestamp;
                $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable, $UpdateAccountsMasterCondition);

                $DeleteAccountDetailsCondition = array(
                    'Accounts_VoucherMst_AutoID' => $checkArray->Accounts_VoucherMst_AutoID,
                );

                $this->Common_model->delete_data_with_condition('ac_tb_accounts_voucherdtl', $DeleteAccountDetailsCondition);


                $accountingVoucherId = $checkArray->Accounts_VoucherMst_AutoID;


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
                    $sales_details_id = $this->Common_model->insert_data('sales_details', $stock);


                    $productCost = $this->Sales_Model->productCostNew($product_id, $branch_id);

                    $totalProductCost += ($_POST['quantity_' . $value] * $productCost);
                    $category_id = $this->Common_model->tableRow('product', 'product_id', $product_id)->category_id;
                    if ($category_id == 1) {
                        //Empty Cylinder
                        $EmptyCylinderProductCost += ($_POST['quantity_' . $value] * $productCost);
                        $emptyCylindet['product_id'] = $_POST['product_id_' . $value];
                        $emptyCylindet['price'] = ($_POST['quantity_' . $value] * $productCost);
                        $allEmptyCylinderSalesArray[] = $emptyCylindet;
                    } elseif ($category_id == 2) {
                        //Refill
                        $RefillProductCost += ($_POST['rate_' . $value] * $productCost);
                        $refillCylindet['product_id'] = $_POST['product_id_' . $value];
                        $refillCylindet['price'] = ($_POST['quantity_' . $value] * $productCost);
                        $allRefillCylinderArray[] = $refillCylindet;
                    } else {
                        //OtherProduct
                        $OtherProductCost += ($_POST['quantity_' . $value] * $productCost);
                        $otherProduct['product_id'] = $_POST['product_id_' . $value];
                        $otherProduct['price'] = ($_POST['quantity_' . $value] * $productCost);
                        $allOtherProductArray[] = $otherProduct;
                    }
                    if ($category_id == 2 && $_POST['is_package_' . $value] == 0) {
                        $packageEmptyProductId = $this->getPackageEmptyProductId($_POST['product_id_' . $value]);
                        //sitehelper
                        $product_last_sales_price = get_product_last_sales_price($packageEmptyProductId);
                        $productCost = $this->Sales_Model->emptyCylinderPurchasePrice($packageEmptyProductId, $this->dist_id);
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


                        $emptyCylindetWithRefill['product_id'] = $packageEmptyProductId;
                        $emptyCylindetWithRefill['price'] = ($productCost * $_POST['quantity_' . $value]);
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
                            $emptyCylindetReturn['product_id'] = $value1;
                            //$emptyCylindetReturn['price'] = $product_last_purchase_price * $_POST['returnedQuantity_' . $value][$key1];
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
                                $accountingDetailsTableCustomerReceivable['Changed_By'] = $this->admin_id;
                                $accountingDetailsTableCustomerReceivable['Changed_Date'] = $this->timestamp;
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
                                $accountingDetailsTableCustomerReceivable['Changed_By'] = $this->admin_id;
                                $accountingDetailsTableCustomerReceivable['Changed_Date'] = $this->timestamp;
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
                /*echo "<pre>";
                print_r($customerReceivableForEmptyCylinderTotal);
                exit;*/
                $salesPriceForRefillCylinder = 0;
                $this->db->insert_batch('sales_return_details', $allStock);
                /*ac_accounts_vouchermst*/
                /*     Sales   */
                $accountingDetailsTableSales['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableSales['TypeID'] = $this->TypeCR;//'2';//Cr
                $accountingDetailsTableSales['CHILD_ID'] = $this->config->item("Sales");
                $accountingDetailsTableSales['GR_DEBIT'] = '0.00';
                $accountingDetailsTableSales['GR_CREDIT'] = array_sum($this->input->post('price'));
                $accountingDetailsTableSales['Reference'] = 'Sales Amount';
                $accountingDetailsTableSales['IsActive'] = 1;
                $accountingDetailsTableSales['Created_By'] = $this->admin_id;
                $accountingDetailsTableSales['Created_Date'] = $this->timestamp;
                $accountingDetailsTableSales['Changed_By'] = $this->admin_id;
                $accountingDetailsTableSales['Changed_Date'] = $this->timestamp;
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
                    $accountingDetailsTableSales['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableSales['Changed_Date'] = $this->timestamp;
                    $accountingDetailsTableSales['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableSales['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableSales;
                    $accountingDetailsTableSales = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + 0;
                    $totalGR_CREDIT = $totalGR_CREDIT + $customerReceivableForEmptyCylinderTotal;
                }
                $condtion = array(
                    'related_id' => $customer_id,
                    'related_id_for' => 3,
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
                $accountingDetailsTableCustomerReceivable['Changed_By'] = $this->admin_id;
                $accountingDetailsTableCustomerReceivable['Changed_Date'] = $this->timestamp;
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
                    $accountingDetailsTableCustomerReceivable['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableCustomerReceivable['Changed_Date'] = $this->timestamp;
                    $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                    $accountingDetailsTableCustomerReceivable = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + $customerReceivableForEmptyCylinderTotal;
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                }
                /*Customer Receivable   =>>25*/
                if ($this->input->post('discount') > 0) {
                    /*Discount   =>>52*/
                    $accountingDetailsTableDiscount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableDiscount['TypeID'] = $this->TypeDR;//'1';//Dr
                    $accountingDetailsTableDiscount['CHILD_ID'] = $this->config->item("Discount");//'52';
                    $accountingDetailsTableDiscount['GR_DEBIT'] = $this->input->post('discount');
                    $accountingDetailsTableDiscount['GR_CREDIT'] = '0.00';
                    $accountingDetailsTableDiscount['Reference'] = 'Discount On Sales';
                    $accountingDetailsTableDiscount['IsActive'] = 1;
                    $accountingDetailsTableDiscount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableDiscount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableDiscount['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableDiscount['Changed_Date'] = $this->timestamp;
                    $accountingDetailsTableDiscount['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableDiscount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableDiscount;
                    $accountingDetailsTableDiscount = array();
                    $totalGR_DEBIT = $totalGR_DEBIT + $this->input->post('discount');
                    $totalGR_CREDIT = $totalGR_CREDIT + 0;
                    /*Customer Receivable   =>>25*/
                }
                /*Cost of Goods Product =>>45*/
                $accountingDetailsTableCostofGoodsProduct['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCostofGoodsProduct['TypeID'] = $this->TypeDR;//'1';//Dr
                $accountingDetailsTableCostofGoodsProduct['CHILD_ID'] = $this->config->item("Cost_of_Goods_Product");//'45';
                $accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = $totalProductCost;
                $accountingDetailsTableCostofGoodsProduct['GR_CREDIT'] = '0.00';
                $accountingDetailsTableCostofGoodsProduct['Reference'] = 'Cost of Goods Product';
                $accountingDetailsTableCostofGoodsProduct['IsActive'] = 1;
                $accountingDetailsTableCostofGoodsProduct['Created_By'] = $this->admin_id;
                $accountingDetailsTableCostofGoodsProduct['Created_Date'] = $this->timestamp;
                $accountingDetailsTableCostofGoodsProduct['Changed_By'] = $this->admin_id;
                $accountingDetailsTableCostofGoodsProduct['Changed_Date'] = $this->timestamp;
                $accountingDetailsTableCostofGoodsProduct['BranchAutoId'] = $branch_id;
                $accountingDetailsTableCostofGoodsProduct['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableCostofGoodsProduct;
                $accountingDetailsTableCostofGoodsProduct = array();
                $totalGR_DEBIT = $totalGR_DEBIT + $totalProductCost;
                $totalGR_CREDIT = $totalGR_CREDIT + 0;
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
                    $accountingDetailsTableCostofGoodsProduct['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableCostofGoodsProduct['Changed_Date'] = $this->timestamp;
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
                        $accountingDetailsTableNewCylinderStock['Reference'] = 'New Cylinder Sales Stock Out ';
                        $accountingDetailsTableNewCylinderStock['IsActive'] = 1;
                        $accountingDetailsTableNewCylinderStock['Created_By'] = $this->admin_id;
                        $accountingDetailsTableNewCylinderStock['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableNewCylinderStock['Changed_By'] = $this->admin_id;
                        $accountingDetailsTableNewCylinderStock['Changed_Date'] = $this->timestamp;
                        $accountingDetailsTableNewCylinderStock['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableNewCylinderStock['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableNewCylinderStock;
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
                        $accountingDetailsTableRefill['Reference'] = 'Refill  Cylinder Sales(Stock Out)';
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['Changed_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Changed_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableRefill;
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
                        $accountingDetailsTableRefill['Reference'] = 'Empty Cylinder With Refill Cylinder Sales(Stock Out)';
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['Changed_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Changed_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableRefill;
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
                        $accountingDetailsTableRefill['Changed_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Changed_Date'] = $this->timestamp;
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
                        $accountingDetailsTableRefill['Changed_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Changed_Date'] = $this->timestamp;
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
                        $accountingDetailsTableOtherProductCost['Reference'] = 'Inventory stock Out Of ' . $ac_account_ledger_coa_info->parent_name;
                        $accountingDetailsTableOtherProductCost['IsActive'] = 1;
                        $accountingDetailsTableOtherProductCost['Created_By'] = $this->admin_id;
                        $accountingDetailsTableOtherProductCost['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableOtherProductCost['Changed_By'] = $this->admin_id;
                        $accountingDetailsTableOtherProductCost['Changed_Date'] = $this->timestamp;
                        $accountingDetailsTableOtherProductCost['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableOtherProductCost['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableOtherProductCost;
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
                    $accountingDetailsTableloaderAmount['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableloaderAmount['Changed_Date'] = $this->timestamp;
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
                    $accountingDetailsTableTransportationAmount['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableTransportationAmount['Changed_Date'] = $this->timestamp;
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
                    $accountingDetailsTableCustomerReceivable['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableCustomerReceivable['Changed_Date'] = $this->timestamp;
                    $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;

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
                    $accountingDetailsTableCashinhand['Changed_By'] = $this->admin_id;
                    $accountingDetailsTableCashinhand['Changed_Date'] = $this->timestamp;
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
                    redirect(site_url($this->project . '/salesLpgInvoice_add'));
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
                    $msg = 'sales Invoice ' . ' ' . $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/salesInvoice_edit/' . $invoiceId));
                } else {
                    $msg = 'sales Invoice ' . ' ' . $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/viewLpgCylinder/' . $invoiceId));
                }
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('Sale_Invoice_Edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Invoice');
        $data['link_page_url'] = 'salesInvoice_add';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = $this->project . '/salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = $this->project . '/viewLpgCylinder/' . $invoiceId;
        $data['third_link_icon'] = $this->link_icon_edit;
        /*page navbar details*/
        $data['editInvoice'] = $this->Common_model->get_single_data_by_single_column('sales_invoice_info', 'sales_invoice_id', $invoiceId);
        /* SELECT acm.Accounts_VoucherMst_AutoID,(SELECT
     acd.CHILD_ID
 FROM
     ac_tb_accounts_voucherdtl acd
 WHERE
     acd.Accounts_VoucherMst_AutoID = acm.Accounts_VoucherMst_AutoID
     AND acd.CHILD_ID IN(
         SELECT
         CHILD_ID
     FROM
         ac_tb_coa
     WHERE
         PARENT_ID = 27
         AND CHILD_ID <> 0
     UNION ALL
         SELECT
             CHILD_ID
         FROM
             ac_tb_coa
         WHERE
             PARENT_ID1 = 27
             AND CHILD_ID <> 0
         UNION ALL
             SELECT
                 CHILD_ID
             FROM
                 ac_tb_coa
             WHERE
                 PARENT_ID2 = 27
                 AND CHILD_ID <> 0
             UNION ALL
                 SELECT
                     CHILD_ID
                 FROM
                     ac_tb_coa
                 WHERE
                     PARENT_ID3 = 27
                     AND CHILD_ID <> 0
                 UNION ALL
                     SELECT
                         CHILD_ID
                     FROM
                         ac_tb_coa
                     WHERE
                         PARENT_ID4 = 27
                         AND CHILD_ID <> 0
                     UNION ALL
                         SELECT
                             CHILD_ID
                         FROM
                             ac_tb_coa
                         WHERE
                             PARENT_ID5 = 27
                             AND CHILD_ID <> 0
                         UNION ALL
                             SELECT
                                 CHILD_ID
                             FROM
                                 ac_tb_coa
                             WHERE
                                 PARENT_ID6 = 27
                                 AND CHILD_ID <> 0
                             UNION ALL
                                 SELECT
                                     CHILD_ID
                                 FROM
                                     ac_tb_coa
                                 WHERE
                                     PARENT_ID7 = 27
                                     AND CHILD_ID <> 0
 ))  as cash_ledger
 FROM ac_accounts_vouchermst acm
 WHERE
 acm.for = 2
     AND acm.BackReferenceInvoiceID = 39 */

        if ($data['editInvoice'] == 4) {
            $condition2 = array(
                'dist_id' => $this->dist_id,
                'is_active' => 'Y',
                'is_delete' => 'N',
            );
            $cashLedger = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);

        }

        $data['bank_check_details'] = array();
        if ($data['editInvoice']->payment_type == 3) {
            $data['bank_check_details'] = $this->Common_model->get_single_data_by_single_column('moneyreceit', 'mainInvoiceId', $data['editInvoice']->sales_invoice_id);
        }
        //$data['editStock'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $invoiceId);
        $stockList = $this->Common_model->get_sales_product_detaild2($invoiceId);
        foreach ($stockList as $ind => $element) {
            $result[$element->sales_invoice_id][$element->sales_details_id]['sales_invoice_id'] = $element->sales_invoice_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['sales_details_id'] = $element->sales_details_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['brand_id'] = $element->brand_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['is_package'] = $element->is_package;
            $result[$element->sales_invoice_id][$element->sales_details_id]['show_in_invoice'] = $element->show_in_invoice;
            $result[$element->sales_invoice_id][$element->sales_details_id]['category_id'] = $element->category_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['product_id'] = $element->product_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['productName'] = $element->productName;
            $result[$element->sales_invoice_id][$element->sales_details_id]['product_code'] = $element->product_code;
            $result[$element->sales_invoice_id][$element->sales_details_id]['title'] = $element->title;
            $result[$element->sales_invoice_id][$element->sales_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->sales_invoice_id][$element->sales_details_id]['brandName'] = $element->brandName;
            $result[$element->sales_invoice_id][$element->sales_details_id]['quantity'] = $element->quantity;
            $result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            $result[$element->sales_invoice_id][$element->sales_details_id]['tt_returnable_quantity'] = $element->tt_returnable_quantity;
            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->sales_invoice_id][$element->sales_details_id]['return'][$element->sales_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'sales_return_id' => $element->sales_return_id,
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


        $data['editStock'] = $result;
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);
        $data['configInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();
        //get only cylinder product
        //$data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        // dumpVar($data['accountHeadList']);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        //echo '<pre>';
        //print_r($data['cylinderProduct']);exit;
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['referenceList'] = $this->Sales_Model->getReferenceList($this->dist_id);
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        //$data['cylinserOut'] = $this->Sales_Model->getCylinderInOutResult($this->dist_id, $invoiceId, 23);
        //$data['cylinderReceive'] = $this->Sales_Model->getCylinderInOutResult2($this->dist_id, $invoiceId, 24);
        //echo $this->db->last_query();exit;
        $data['creditAmount'] = $paymentInfo = $this->Sales_Model->getCreditAmount($invoiceId);
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition);
        // $data['accountId'] = $this->Sales_Model->getAccountId($paymentInfo->generals_id);

        $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceLpg/editInvoiceNew', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function salesInvoice_view($salesID)
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
        $data['title'] = get_phrase('Sale Invoice View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Invoice');
        $data['link_page_url'] = $this->project . '/salesLpgInvoice_add';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Invoice List');
        $data['second_link_page_url'] = $this->project . '/salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('Sale_Invoice_Edit');
        $data['third_link_page_url'] =  $this->project . '/salesInvoice_edit/' . $salesID;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/
        $data['saleslist'] = $this->Common_model->get_single_data_by_single_column('sales_invoice_info', 'sales_invoice_id', $salesID);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /* echo '<pre>';
         print_r($data['saleslist']);
         exit;*/
        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['saleslist']->customer_id);
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
            $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceLpg/sales_view_final', $data, true);

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        } else {
            $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceMobile/sales_view_final', $data, true);
        }


        $this->load->view('distributor/masterTemplate', $data);
    }
    public function viewLpgCylinder_pdf($salesID)
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
        $data['title'] = get_phrase('Sale Invoice View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Invoice');
        $data['link_page_url'] = $this->project . '/salesLpgInvoice_add';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Invoice List');
        $data['second_link_page_url'] = $this->project . '/salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('Sale_Invoice_Edit');
        $data['third_link_page_url'] = 'salesInvoice_edit/' . $salesID;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/
        $data['saleslist'] = $this->Common_model->get_single_data_by_single_column('sales_invoice_info', 'sales_invoice_id', $salesID);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /* echo '<pre>';
         print_r($data['saleslist']);
         exit;*/
        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['saleslist']->customer_id);
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
        $this->load->library('tec_mpdf', '', 'pdf');
        $footer ='';
        $output_type = '';
        $header = '';
        $content = $this->load->view('distributor/sales/salesInvoiceMobile/sales_view_final', $data, true);

        $this->pdf->generate($content, $name = 'download.pdf', $output_type, $footer, $margin_bottom = null, $header, $margin_top = '45', $orientation = 'l');



    }

    public function salesInvoice_view2($salesID)
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
        $data['title'] = get_phrase('Sale Invoice View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Invoice');
        $data['link_page_url'] = $this->project . '/salesLpgInvoice_add';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Invoice List');
        $data['second_link_page_url'] = $this->project . '/salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('Sale_Invoice_Edit');
        $data['third_link_page_url'] = 'salesInvoice_edit/' . $salesID;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/
        $data['saleslist'] = $this->Common_model->get_single_data_by_single_column('sales_invoice_info', 'sales_invoice_id', $salesID);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /* echo '<pre>';
         print_r($data['saleslist']);
         exit;*/
        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['saleslist']->customer_id);
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
        $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceLpg/sales_view_jahid', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function cylinder_sales_report()
    {
        if (isPostBack()) {
            $customerId = $this->input->post('customer_id');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $data['type'] = $type = $this->input->post('cusType');
            $data['customerId'] = $customerId;
            if ($customerId == 'all'):
                $data['salesList'] = $this->Sales_Model->getCustomerSalesList($this->dist_id, $start_date, $end_date, '', $type);
            else:
                $data['salesList'] = $this->Sales_Model->getCustomerSalesList($this->dist_id, $start_date, $end_date, $customerId, $type);
            endif;
            $stockList = $this->Common_model->cylinder_sales_report($customerId, $start_date, $end_date);
            //echo'<pre>';
            //echo $this->db->last_query();
            ///print_r($stockList);exit;
            foreach ($stockList as $ind => $element) {
                $customer = $element->customerName . ' [' . $element->customerID . ']';
                $result[$element->sales_invoice_id]['invoice_no'] = $element->invoice_no;
                $result[$element->sales_invoice_id]['invoice_date'] = $element->invoice_date;
                $result[$element->sales_invoice_id]['customer'] = $customer;
                $result[$element->sales_invoice_id]['customer_id'] = $customer;
                $result[$element->sales_invoice_id]['payment_type'] = $element->payment_type;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['sales_invoice_id'] = $element->sales_invoice_id;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['sales_details_id'] = $element->sales_details_id;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['is_package'] = $element->is_package;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['product_id'] = $element->product_id;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['productName'] = $element->productName;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['product_code'] = $element->product_code;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['title'] = $element->title;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['unitTtile'] = $element->unitTtile;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['brandName'] = $element->brandName;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['quantity'] = $element->quantity;
                $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['unit_price'] = $element->unit_price;
                //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
                if ($element->returnable_quantity > 0) {
                    $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['return'][$element->sales_details_id][] = array('return_product_name' => $element->return_product_name,
                        'return_product_id' => $element->return_product_id,
                        'return_product_cat' => $element->return_product_cat,
                        'return_product_name' => $element->return_product_name,
                        'return_product_unit' => $element->return_product_unit,
                        'return_product_brand' => $element->return_product_brand,
                        'returnable_quantity' => $element->returnable_quantity,
                        'return_quantity' => $element->return_quantity,
                    );
                } else {
                    $result[$element->sales_invoice_id]['sales_producr'][$element->sales_details_id]['return'][$element->sales_details_id][] = array('return_product_name' => '',
                        'return_product_id' => '',
                        'return_product_cat' => '',
                        'return_product_name' => '',
                        'return_product_unit' => '',
                        'return_product_brand' => '',
                        'returnable_quantity' => '',
                        'return_quantity' => '',
                    );
                }
            }
            $data['salesList'] = $result;
        }
        $data['customerType'] = $this->Common_model->get_data_list('customertype');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Customer Wise Sales Report';
        $data['customerList'] = $this->Common_model->get_data_list_by_single_column('customer', 'dist_id', $this->dist_id);
        /*page navbar details*/
        $data['title'] = get_phrase('Cylinder_Sales_Report');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/sales/report/cylinder_sales_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function referenceSalesReport()
    {
        if (isPostBack()) {
            $referenceId = $this->input->post('referenceId');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $data['referenceId'] = $referenceId;
            if ($referenceId == 'all'):
                $data['refOpList'] = $this->Sales_Model->getReferenceSalesList($this->dist_id, $start_date, $end_date);
            else:
                $data['refOpList'] = $this->Sales_Model->getReferenceSalesListById($this->dist_id, $start_date, $end_date, $referenceId);
            endif;
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /*page navbar details*/
        $data['title'] = get_phrase('Reference Sales Report');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/report/referenceSalesReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function customer_due($start_date = '', $end_date = '')
    {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $customer_id = isset($_GET['start_date']) ? $_GET['customer_id'] : 'All';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['customer_due'] = $this->Sales_Model->customer_due($customer_id, $start_date, $end_date, $this->dist_id);
            echo $this->db->last_query();
            echo '<pre>';
            print_r($data['customer_due']);
            exit;
        }
        $customerList = array();
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $cus_list = $this->Sales_Model->customer_list($this->dist_id);
        $cus_items_array[] = array(
            'label' => "All",
            'value' => 'All'
        );
        foreach ($cus_list as $key => $value) {
            $cus_items_array[] = array(
                'label' => $value->customerName,
                'value' => $value->customer_id,
                'have_invoice' => $value->customer_id,
            );
        };
        $data['pageName'] = 'salePosAdd';
        $data['page_type'] = get_phrase($this->page_type);
        $data['customer_info'] = json_encode($cus_items_array);
        //echo '<pre>';
        //print_r($page_data['customer_info']);exit;
        $data['pageTitle'] = get_phrase('Customer_Due');
        $data['title'] = get_phrase('Customer_Due');
        $data['mainContent'] = $this->load->view('distributor/sales/report/customer_due', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function customer_due_collection($start_date = '', $end_date = '')
    {
        if (isPostBack()) {
            //echo '<pre>';
            //print_r($_POST);            exit();
            $this->db->trans_start();
            $moneyReceitNo = $this->db->where(array('dist_id' => $this->dist_id))->count_all_results('cus_due_collection_info') + 1;
            $ReceitVoucher = "CMR" . date("y") . date("m") . str_pad($moneyReceitNo, 4, "0", STR_PAD_LEFT);
            $due_collection_info['total_paid_amount'] = $this->input->post('paid_amount');
            $due_collection_info['customer_id'] = $this->input->post('customer_id');
            $due_collection_info['cus_due_coll_no'] = $ReceitVoucher;
            $due_collection_info['payment_type'] = $this->input->post('paymentType');
            $due_collection_info['bank_name'] = $this->input->post('bankName');
            $due_collection_info['branch_name'] = $this->input->post('branchName');
            $due_collection_info['check_no'] = $this->input->post('checkNo');
            $due_collection_info['check_date'] = date('Y-m-d', strtotime($this->input->post('checkDate')));
            $due_collection_info['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $due_collection_info['dist_id'] = $this->dist_id;
            $due_collection_info['insert_date'] = $this->timestamp;
            $due_collection_info['insert_by'] = $this->admin_id;
            $due_collection_info['is_active'] = 'Y';
            $due_collection_info['is_delete'] = 'N';
            $cus_due_collection_info_id = $this->Common_model->insert_data('cus_due_collection_info', $due_collection_info);
            foreach ($this->input->post('invoiceId') as $key => $value):
                if (isset($_POST['posted_' . $value])) {
                    $due_collection['sales_invoice_id'] = $value;
                    $due_collection['due_collection_info_id'] = $cus_due_collection_info_id;
                    $due_collection['customer_id'] = $this->input->post('customer_id');
                    $due_collection['payment_type'] = $this->input->post('paymentType');
                    $due_collection['paid_amount'] = $_POST['paidAmount_' . $value];
                    $due_collection['insert_date'] = $this->timestamp;
                    $due_collection['insert_by'] = $this->admin_id;
                    $due_collection['is_active'] = 'Y';
                    $due_collection['is_delete'] = 'N';
                    $allStock[] = $due_collection;
                    $postedInvoiceNo[] = $value;
                }
            endforeach;
            $this->db->insert_batch('cus_due_collection_details', $allStock);
            $cus_due_collection_info['ref_invoice_ids'] = implode(",", $postedInvoiceNo);
            $this->Common_model->update_data('cus_due_collection_info', $cus_due_collection_info, 'id', $cus_due_collection_info_id);
            if ($this->input->post('advance_amount') > 0) {
                $moneyReceitNo = $this->db->where(array('dist_id' => $this->dist_id))->count_all_results('customer_advance') + 1;
                $ReceitVoucher = "CMA" . date("y") . date("m") . str_pad($moneyReceitNo, 4, "0", STR_PAD_LEFT);
                $customer_advance['due_collection_id'] = $cus_due_collection_info_id;
                $customer_advance['advance_amount'] = $this->input->post('advance_amount');
                $customer_advance['advance_recive_voucher'] = $ReceitVoucher;
                $customer_advance['customer_id'] = $this->input->post('customer_id');
                $customer_advance['payment_type'] = $this->input->post('paymentType');
                $customer_advance['advance_date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $customer_advance['insert_date'] = $this->timestamp;
                $customer_advance['insert_by'] = $this->admin_id;
                $customer_advance['is_active'] = 'Y';
                $customer_advance['is_delete'] = 'N';
                $customer_advance['dist_id'] = $this->dist_id;
                $customer_advance['bank_name'] = $this->input->post('bankName');
                $customer_advance['branch_name'] = $this->input->post('branchName');
                $customer_advance['check_no'] = $this->input->post('checkNo');
                $customer_advance['check_date'] = date('Y-m-d', strtotime($this->input->post('checkDate')));
                $this->Common_model->insert_data('customer_advance', $customer_advance);
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your sales can't be inserted.Something is wrong.");
                redirect('customer_due_collection', 'refresh');
            } else {
                message("Your data successfully inserted into database.");
                redirect('customer_due_collection', 'refresh');
            }
        }
        $cus_list = $this->Sales_Model->customer_list($this->dist_id);
        foreach ($cus_list as $key => $value) {
            $cus_items_array[] = array(
                'label' => $value->customerName,
                'value' => $value->customer_id,
                'have_invoice' => $value->customer_id,
            );
        };
        $moneyReceitNo = $this->db->where(array('dist_id' => $this->dist_id))->count_all_results('cus_due_collection_info') + 1;
        $data['moneyReceitVoucher'] = "CMR" . date("y") . date("m") . str_pad($moneyReceitNo, 4, "0", STR_PAD_LEFT);
        //echo '<pre>';
        //print_r($data['moneyReceitVoucher']);exit;
        $data['pageName'] = 'salePosAdd';
        $data['customer_info'] = json_encode($cus_items_array);
        $data['pageTitle'] = 'Customer Due Collection';
        $data['title'] = 'Customer Due Collection';
        $data['mainContent'] = $this->load->view('distributor/sales/saleInvoice/customer_due_collection', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function get_customer_due_invoice_list()
    {
        //log_message('error','POST DATA '.print_r($_POST,true));
        $this->form_validation->set_rules('customer_id', 'Customer Need To Selected', 'required');
        if ($this->form_validation->run() == FALSE) {
            exception("Required field can't be empty.");
            redirect(site_url('salesInvoice_add'));
        } else {
            $customer_id = $this->input->post('customer_id');
            $cus_due_invoice_list = $this->Sales_Model->customer_due_invoice_list($customer_id, $this->dist_id);
            echo json_encode($cus_due_invoice_list);
        }
    }

    public function cus_due_coll_list()
    {
        $data['pageTitle'] = 'Customer Due Collection';
        $data['title'] = 'Customer Due Collection';
        $data['mainContent'] = $this->load->view('distributor/sales/saleInvoice/customer_due_collection_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function customer_due_collection_inv($id)
    {
        $data['due_collectuon_data'] = $this->Sales_Model->customer_due_invoice_data($id, $this->dist_id);
        $data['pageTitle'] = 'Customer Due Collection';
        $data['title'] = 'Customer Due Collection';
        $data['mainContent'] = $this->load->view('distributor/sales/saleInvoice/customer_due_collection_inv', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function salesInvoice()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('Invoice List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Invoice');
        $data['link_page_url'] = $this->project . '/salesLpgInvoice_add';
        $data['link_icon'] = "<i class='ace-icon fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceLpg/saleList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function getCustomerCurrentBalance($customerId = null)
    {
        if (!empty($customerId)):
            $customerId = $customerId;
        else:
            $customerId = $this->input->post('customerId');
        endif;
        $presentBalance = $this->Sales_Model->getCustomerBalance($this->dist_id, $customerId);
        echo json_encode($presentBalance);
    }

    function getbankbranchList()
    {
        $bank_id = $this->input->post('bank_id');
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
            'bank_id' => $bank_id
        );
        $branchList = $this->Common_model->get_data_list_by_many_columns('bank_branch_info', $condition2);
        echo json_encode($branchList);
    }

    public function customerPayment()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('Customer Payment List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Customer Payment Add');
        $data['link_page_url'] = 'customerPaymentAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/sales/report/customerPaymentList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function viewMoneryReceipt($receiptId, $voucherId = null)
    {
        /*page navbar details*/
        $data['title'] = get_phrase('Customer Money Receipt');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Customer Payment List');
        $data['link_page_url'] = 'customerPayment';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['second_link_page_name'] = get_phrase('Customer Payment Add');
        $data['second_link_page_url'] = 'customerPaymentAdd';
        $data['second_link_icon'] = $this->link_icon_add;
        /*page navbar details*/
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['moneyReceitInfo'] = $this->Common_model->get_single_data_by_single_column('moneyreceit', 'moneyReceitid', $receiptId);
        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['moneyReceitInfo']->customerid);
        $data['mainContent'] = $this->load->view('distributor/sales/report/viewMoneyReceipt', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }



    function productWiseSalesReport()
    {
        $data['title'] = get_phrase('Product_Wise_Sales_Report');
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/report/proudctWiseSalesReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function reference()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('Reference_List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Reference Add');
        $data['link_page_url'] = 'referenceAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/reference/reference', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function referenceAdd()
    {
        if (isPostBack()) {
            $this->form_validation->set_rules('refCode', 'Product Code', 'required');
            $this->form_validation->set_rules('referenceName', 'Reference Name', 'required');
            //$this->form_validation->set_rules('customerPhone', 'Product Branch', 'required');
            // $this->form_validation->set_rules('customerAddress', 'Product Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('referenceAdd'));
            } else {
                $data['refCode'] = $this->input->post('refCode');
                $data['referenceName'] = $this->input->post('referenceName');
                $data['referencePhone'] = $this->input->post('referencePhone');
                $data['referenceEmail'] = $this->input->post('referenceEmail');
                $data['referenceAddress'] = $this->input->post('referenceAddress');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('reference', $data);
                message("Your data successfully stored into database.");
                redirect(site_url('reference'));
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('Reference Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Reference List');
        $data['link_page_url'] = 'reference';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $totalReferece = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['refCode'] = "RID" . date("y") . date("m") . str_pad(count($totalReferece) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/sales/reference/referenceAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function editReference($editId)
    {
        if (isPostBack()) {
            //$this->form_validation->set_rules('refCode', 'Product Code', 'required');
            $this->form_validation->set_rules('referenceName', 'Reference Name', 'required');
            //$this->form_validation->set_rules('customerPhone', 'Product Branch', 'required');
            // $this->form_validation->set_rules('customerAddress', 'Product Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('editReference/' . $editId));
            } else {
                $data['refCode'] = $this->input->post('refCode');
                $data['referenceName'] = $this->input->post('referenceName');
                $data['referencePhone'] = $this->input->post('referencePhone');
                $data['referenceEmail'] = $this->input->post('referenceEmail');
                $data['referenceAddress'] = $this->input->post('referenceAddress');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->update_data('reference', $data, 'reference_id', $editId);
                message("Your data successfully update into database.");
                redirect(site_url('reference'));
            }
        }
        $data['referenceList'] = $this->Common_model->get_single_data_by_single_column('reference', 'reference_id', $editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Reference_Edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Reference List');
        $data['link_page_url'] = 'reference';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['second_link_page_name'] = get_phrase('Reference Add');
        $data['second_link_page_url'] = 'referenceAdd';
        $data['second_link_icon'] = $this->link_icon_add;
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/sales/reference/referenceEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function customer_ajax()
    {
        $customer = $this->input->post('customer');
        $result = '<table class="table table-bordered table-hover">';
        $result .= '<thead><tr><td align="center"><strong> ' . get_phrase('Voucher No') . '</strong></td><td align="center"><strong>' . get_phrase('Date') . '</strong></td><td align="center"><strong>' . get_phrase('Type') . '</strong></td><td align="center"><strong>' . get_phrase('Amount_Due_In_Bdt') . '</strong></td><td align="center"><strong>' . get_phrase('Allocation_In_Bdt') . '</strong></td></tr></thead>';
        $result .= '<tbody>';
        $query = $this->Sales_Model->generals_customer($customer);
        foreach ($query as $key => $row):
            if ($this->Sales_Model->generals_voucher($row['voucher_no']) != 0):
                $result .= '<tr>';
                $result .= '<td><a href="' . site_url('salesInvoice_view/' . $row['generals_id']) . '">' . $row['voucher_no'] . '<input type="hidden" name="voucher[]" value="' . $row['voucher_no'] . '"></a></td>';
                $result .= '<td>' . date('d.m.Y', strtotime($row['date'])) . '</td>';
                $result .= '<td>' . $this->Common_model->tableRow('form', 'form_id', $row['form_id'])->name . '</td>';
                $result .= '<td align="right"><input type="hidden" value="' . $this->Sales_Model->generals_voucher($row['voucher_no']) . '" id="dueAmount_' . $key . '">' . number_format((float)$this->Sales_Model->generals_voucher($row['voucher_no']), 2, '.', ',') . '</td>';
                $result .= '<td><input id="paymentAmount_' . $key . '" type="text" onkeyup="checkOverAmount(' . $key . ')" class="form-control amount " name="amount[]"   placeholder="0.00"></td>';
                $result .= '</tr>';
            endif;
        endforeach;
        $result .= '<tr>';
        $result .= '<td align="right" colspan="4"><strong>' . get_phrase('Total_Balance_In_Bdt') . '</strong></td>';
        $result .= '<td><input type="text" class="form-control ttl_amount required" name="ttl_amount" placeholder="0.00" readonly="readonly"></td>';
        $result .= '</tr>';
        $result .= '</tbody></table>';
        $result .= '<script type="text/javascript">';
        $result .= "$(document).ready(function(){ $('.amount').change(function(){ ttl_amount=0; $.each($('.amount'), function(){ aamount = $(this).val(); aamount=Number(aamount); ttl_amount+=aamount; }); $(this).val(parseFloat($(this).val()).toFixed(2)); $('.ttl_amount').val(parseFloat(ttl_amount).toFixed(2)); }); });";
        $result .= '</script>';
        echo $result;
    }

    public function customerDashboard($customerId)
    {
        $data['customerDetails'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $customerId);
        $salesCon = array(
            'customer_id' => $customerId,
            'form_id' => 5,
        );
        $data['salesList'] = $this->Common_model->get_data_list_by_many_columns('generals', $salesCon);
        //dumpVar($data['salesList']);
        $paymentCon = array(
            'customer_id' => $customerId,
            'form_id' => 7,
        );
        // $data['salesPayment'] = $this->Common_model->get_data_list_by_many_columns('generals', $paymentCon);
        $data['salesPayment'] = $this->Common_model->get_data_list_by_single_column('moneyreceit', 'customerid', $customerId);
        //dumpVar($data['salesPayment']);
        $salesOrderCon = array(
            'customer_id' => $customerId,
            'form_id' => 19,
        );
        $data['salesOrder'] = $this->Common_model->get_data_list_by_many_columns('generals', $salesOrderCon);
        // dumpVar($data['supplierPayment']);
        $data['title'] = 'Supplier Dashboard';
        $data['mainContent'] = $this->load->view('distributor/sales/customer/customerDashboard', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function checkHaveEmptyCylinderOrPrice()
    {
        $product_id = $this->input->post('product_id');
        $packageEmptyProductId = $this->getPackageEmptyProductId($product_id);
        $product_last_sales_price = get_product_purchase_price($packageEmptyProductId);
        echo $product_last_sales_price;
    }

    public function load_account_ledgers()
    {
        $ledgerId = $this->input->post('ledgerId');
        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();
        return $this->load->view('distributor/ajax/load_account_ledgers', $data);
    }

    function getPackageEmptyProductId($RefillproductId)
    {
        $this->db->select("package_products.*");
        $this->db->from("package_products");
        $this->db->where('package_products.product_id', $RefillproductId);
        $package = $this->db->get()->row();
        $this->db->select("package_products.*");
        $this->db->from("package_products");
        $this->db->join('product', 'product.product_id = package_products.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->where('package_products.package_id', $package->package_id);
        $this->db->where('productcategory.category_id', 1);
        $package_empty_product = $this->db->get()->row();
        return $package_empty_product->product_id;
    }
}