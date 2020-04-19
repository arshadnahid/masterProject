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
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/brand/';
        $this->link_icon_add = "<i class='fa fa-plus'></i>";
        $this->link_icon_list = "<i class='fa fa-list'></i>";
        $this->link_icon_view = "<i class='fa fa-view'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }
    public function salesLpgInvoice_add($confirmId = null)
    {
        $this->load->helper('sales_invoice_no_helper');
        $this->load->helper('branch_dropdown_helper');
        //echo  branch_dropdown();
        //exit;
        if (isPostBack()) {
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
                //$sales_inv['invoice_amount'] = array_sum($this->input->post('price'));
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
                $sales_inv['dist_id'] = $this->dist_id;
                $sales_inv['branch_id'] = $branch_id;
                $sales_inv['due_date'] = $this->input->post('dueDate') != '' ? date('Y-m-d', strtotime($this->input->post('dueDate'))) : '';
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
                $accountingMasterTable['for'] = 2;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);


                $EmptyCylinderProductCost = 0;
                $RefillProductCost = 0;
                $OtherProductCost = 0;
                $allEmptyCylinderArray = array();
                $allRefillCylinderArray = array();
                $allOtherProductArray = array();
                $allEmptyCylinderWithRefillArray = array();
                foreach ($_POST['slNo'] as $key => $value) {
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
                    $productCost = $this->Sales_Model->productCostNew($product_id, $this->dist_id);
                    $totalProductCost += ($_POST['quantity_' . $value] * $productCost);
                    $category_id = $this->Common_model->tableRow('product', 'product_id', $product_id)->category_id;
                    if ($category_id == 1) {
                        //Empty Cylinder
                        $EmptyCylinderProductCost += ($_POST['quantity_' . $value] * $productCost);
                        $emptyCylindet['product_id'] = $_POST['product_id_' . $value];
                        $emptyCylindet['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allEmptyCylinderArray[] = $emptyCylindet;
                    } elseif ($category_id == 2) {
                        //Refill
                        //$RefillProductCost += ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $OtherProductCost += ($_POST['quantity_' . $value] * $productCost);
                        $refillCylindet['product_id'] = $_POST['product_id_' . $value];
                        $refillCylindet['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allRefillCylinderArray[] = $refillCylindet;
                    } else {
                        //OtherProduct
                        $OtherProductCost += ($_POST['quantity_' . $value] * $productCost);
                        $otherProduct['product_id'] = $_POST['product_id_' . $value];
                        $otherProduct['price'] = ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                        $allOtherProductArray[] = $otherProduct;
                    }
                    if ($category_id == 2 && $_POST['is_package_' . $value] == 0) {
                        $packageEmptyProductId = $this->getPackageEmptyProductId($_POST['product_id_' . $value]);
                        //sitehelper
                        $product_last_sales_price = get_product_last_sales_price($packageEmptyProductId);
                        $emptyCylindetWithRefill['product_id'] = $packageEmptyProductId;
                        $emptyCylindetWithRefill['price'] = ($product_last_sales_price * $_POST['quantity_' . $value]);
                        $allEmptyCylinderWithRefillArray[] = $emptyCylindetWithRefill;
                    }
                    if (isset($_POST['returnproduct_' . $value])) {
                        foreach ($_POST['returnproduct_' . $value] as $key1 => $value1) {
                            unset($stock2);
                            $product_last_purchase_price=get_product_last_purchase_price($value1);
                            $emptyCylindetReturn['product_id'] = $value1;
                            $emptyCylindetReturn['price'] = ($product_last_purchase_price * $_POST['returnQuentity_' . $value][$key1]);
                            $allEmptyCylinderReturnArray[]=$emptyCylindetReturn;

                            $stock2['sales_details_id'] = $sales_details_id;
                            $stock2['product_id'] = $value1;
                            $stock2['sales_invoice_id'] = $this->invoice_id;
                            $stock2['customer_id'] = $customer_id;
                            $stock2['returnable_quantity'] = $_POST['returnedQuantity_' . $value][$key1];
                            $stock2['return_quantity'] = $_POST['returnedQuantity_' . $value][$key1];
                            $stock2['insert_by'] = $this->admin_id;
                            $stock2['insert_date'] = $this->timestamp;
                            $stock2['branch_id'] = $branch_id;
                            $allStock[] = $stock2;
                        }
                    }
                }
                $this->db->insert_batch('sales_return_details', $allStock);
                /*ac_accounts_vouchermst*/

                $condtion = array(
                    'related_id' => $customer_id,
                    'related_id_for' => 3,
                    'is_active' => "Y",
                );
                $CustomerReceivable = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                /*Customer Receivable   =>>33*/
                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCustomerReceivable['TypeID'] = '1';//Dr
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
                $accountingDetailsTableCustomerReceivable = array();
                /*Customer Receivable   =>>25*/
                if ($this->input->post('discount') > 0) {
                    /*Discount   =>>52*/
                    $accountingDetailsTableDiscount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableDiscount['TypeID'] = '1';//Dr
                    $accountingDetailsTableDiscount['CHILD_ID'] = $this->config->item("Discount");//'52';
                    $accountingDetailsTableDiscount['GR_DEBIT'] = $this->input->post('discount');
                    $accountingDetailsTableDiscount['GR_CREDIT'] = '0.00';
                    $accountingDetailsTableDiscount['Reference'] = 'Discount';
                    $accountingDetailsTableDiscount['IsActive'] = 1;
                    $accountingDetailsTableDiscount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableDiscount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableDiscount['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableDiscount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableDiscount;
                    $accountingDetailsTableDiscount = array();
                    /*Customer Receivable   =>>25*/
                }
                /*Cost of Goods Product =>>45*/
                $accountingDetailsTableCostofGoodsProduct['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableCostofGoodsProduct['TypeID'] = '1';//Dr
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
                $accountingDetailsTableCostofGoodsProduct = array();
                if ($EmptyCylinderProductCost > 0) {
                    /*ac_tb_accounts_voucherdtl*/
                    /*Inventory Stock New Cylinder Stock=>22*/
                    /*Inventory stock=>20*/
                    $accountingDetailsTableNewCylinderStock['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableNewCylinderStock['TypeID'] = '2';//Cr
                    $accountingDetailsTableNewCylinderStock['CHILD_ID'] = $this->config->item("New_Cylinder_Stock");//'22';
                    $accountingDetailsTableNewCylinderStock['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableNewCylinderStock['GR_CREDIT'] = $EmptyCylinderProductCost;
                    $accountingDetailsTableNewCylinderStock['Reference'] = 'New Cylinder Stock';
                    $accountingDetailsTableNewCylinderStock['IsActive'] = 1;
                    $accountingDetailsTableNewCylinderStock['Created_By'] = $this->admin_id;
                    $accountingDetailsTableNewCylinderStock['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableNewCylinderStock['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableNewCylinderStock['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableNewCylinderStock;
                    $accountingDetailsTableNewCylinderStock = array();
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
                        $accountingDetailsTableRefill['TypeID'] = '2';//Cr
                        $accountingDetailsTableRefill['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Refill");//'95';
                        $accountingDetailsTableRefill['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableRefill['GR_CREDIT'] = $valueRefill['price'];
                        $accountingDetailsTableRefill['Reference'] = 'Refill  Cylinder Sales';
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableRefill;
                        $accountingDetailsTable = array();
                    }
                }

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
                        $accountingDetailsTableRefill['TypeID'] = '2';//Cr
                        $accountingDetailsTableRefill['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Refill");//'95';
                        $accountingDetailsTableRefill['GR_DEBIT'] = '0.00';
                        $accountingDetailsTableRefill['GR_CREDIT'] = $valueEmptyWithRefill['price'];
                        $accountingDetailsTableRefill['Reference'] = 'Empty Cylinder With Refill Cylinder Sales';
                        $accountingDetailsTableRefill['IsActive'] = 1;
                        $accountingDetailsTableRefill['Created_By'] = $this->admin_id;
                        $accountingDetailsTableRefill['Created_Date'] = $this->timestamp;
                        $accountingDetailsTableRefill['BranchAutoId'] = $branch_id;
                        $accountingDetailsTableRefill['date'] = $saleDate;
                        $finalDetailsArray[] = $accountingDetailsTableRefill;
                        $accountingDetailsTable = array();
                    }
                }
                if ($OtherProductCost > 0) {
                    /*Inventory stock Refill=>95*/
                    $accountingDetailsTableOtherProductCost['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableOtherProductCost['TypeID'] = '2';//Cr
                    $accountingDetailsTableOtherProductCost['CHILD_ID'] = $this->config->item("Inventory_Stock");//'20';
                    $accountingDetailsTableOtherProductCost['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableOtherProductCost['GR_CREDIT'] = $OtherProductCost;
                    $accountingDetailsTableOtherProductCost['Reference'] = 'Inventory stock';
                    $accountingDetailsTableOtherProductCost['IsActive'] = 1;
                    $accountingDetailsTableOtherProductCost['Created_By'] = $this->admin_id;
                    $accountingDetailsTableOtherProductCost['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableOtherProductCost['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableOtherProductCost['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableOtherProductCost;
                    $accountingDetailsTable = array();
                }
                /*Sales=>37*/
                $accountingDetailsTableSales['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTableSales['TypeID'] = '2';//Cr
                $accountingDetailsTableSales['CHILD_ID'] = $this->config->item("Sales");//'37';
                $accountingDetailsTableSales['GR_DEBIT'] = '0.00';
                $accountingDetailsTableSales['GR_CREDIT'] = array_sum($this->input->post('price'));
                $accountingDetailsTableSales['Reference'] = 'Refill  Stock';
                $accountingDetailsTableSales['IsActive'] = 1;
                $accountingDetailsTableSales['Created_By'] = $this->admin_id;
                $accountingDetailsTableSales['Created_Date'] = $this->timestamp;
                $accountingDetailsTableSales['BranchAutoId'] = $branch_id;
                $accountingDetailsTableSales['date'] = $saleDate;
                $finalDetailsArray[] = $accountingDetailsTableSales;
                $accountingDetailsTableSales = array();
                /*Sales=>37*/
                /*Loading and wages*/
                if ($this->input->post('loaderAmount') > 0) {
                    $accountingDetailsTableloaderAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableloaderAmount['TypeID'] = '2';//Cr
                    $accountingDetailsTableloaderAmount['CHILD_ID'] = $this->config->item("Loading_Wages");//'47';
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
                }
                /*Loading and wages*/
                /*Transportation*/
                if ($this->input->post('transportationAmount') > 0) {
                    $accountingDetailsTableTransportationAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableTransportationAmount['TypeID'] = '2';//Cr
                    $accountingDetailsTableTransportationAmount['CHILD_ID'] = $this->config->item("Transportation");//'42';
                    $accountingDetailsTableTransportationAmount['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableTransportationAmount['GR_CREDIT'] = $this->input->post('transportationAmount');
                    $accountingDetailsTableTransportationAmount['Reference'] = 'transportationAmount';
                    $accountingDetailsTableTransportationAmount['IsActive'] = 1;
                    $accountingDetailsTableTransportationAmount['Created_By'] = $this->admin_id;
                    $accountingDetailsTableTransportationAmount['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableTransportationAmount['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableTransportationAmount['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableTransportationAmount;
                    $accountingDetailsTableTransportationAmount = array();
                }
                /*Transportation*/
                if ($payType == 4) {
                    /*Customer Receivable  /account Receiveable  =>>25*/
                    //account Receiveable
                    $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableCustomerReceivable['TypeID'] = '2';//Cr
                    $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $this->config->item("Customer_Receivable");//'25';
                    $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = '0.00';
                    $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = $this->input->post('partialPayment');
                    $accountingDetailsTableCustomerReceivable['Reference'] = 'Supplier paid amount';
                    $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                    $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                    $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                    $accountingDetailsTableCustomerReceivable['date'] = $saleDate;
                    $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                    $accountingDetailsTableCustomerReceivable = array();
                    //account Receiveable
                    /*Cash in hand*/
                    $accountingDetailsTableCashinhand['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTableCashinhand['TypeID'] = '1';//Dr
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
                    $accountingDetailsTableCashinhand = array();
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
                        'receiveType' => 2,
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
                // $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                $mrid = "CMR" . date("y") . date("m") . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                /* if ($payType == 1) {
                     //when payment type cash
                     $this->cashTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                 } elseif ($payType == 2) {
                     //when payment type credit
                     $this->creditTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                 } elseif ($payType == 3) {
                     //when payment type cheque.
                     $this->chequeTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                 } elseif ($payType == 4) {
                     //when partial paymet start from here.
                     $this->partialTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                 }*/
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
        $data['configInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
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
        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);
        $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceLpg/sale_add', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function cashTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid)
    {
        // when cash transction start from here
        //58 account receiable head debit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts
        if (!empty($data['discount'])) :
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //7501 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.
        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //loader account head credit
        //transportation account head credit
        //loader and transportation account receiaveable head debit against credit
        $loaderAmount = $this->input->post('loaderAmount');
        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($loaderAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '391',
                'credit' => $loaderAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        if (!empty($transportationAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '392',
                'credit' => $transportationAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //customer payment ledger
        $generals_data = array(
            'form_id' => '7',
            'customer_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'mainInvoiceId' => $generals_id,
            'voucher_no' => $this->input->post('voucherid'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'credit' => $this->input->post('netTotal'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $generalPaymentId = $this->Common_model->insert_data('generals', $generals_data);
        //1301 Cash in Hand  head debit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '54',
            'debit' => $this->input->post('netTotal'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //58 Account Receivable head credit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'credit' => $this->input->post('netTotal'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //client vendor ledger
        $customerLedger1 = array(
            'ledger_type' => 1,
            'trans_type' => 'Sales Payment',
            'history_id' => $generalPaymentId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'updated_by' => $this->admin_id,
            'amount' => $this->input->post('netTotal'),
            'cr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate')))
        );
        $this->db->insert('client_vendor_ledger', $customerLedger1);
        //money Receite General
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'invoiceID' => json_encode($this->input->post('voucherid')),
            'totalPayment' => $this->input->post('netTotal'),
            'receitID' => $mrid,
            'mainInvoiceId' => $generals_id,
            'dist_id' => $this->dist_id,
            'customerid' => $this->input->post('customer_id'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'paymentType' => 1
        );
        $this->db->insert('moneyreceit', $moneyReceit);
    }
    function creditTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost)
    {
        //when due transction start from here.
        //58 account receiable head debit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts
        if (!empty($data['discount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //62 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.
        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //loader account head credit
        //transportation account head credit
        //loader and transportation account receiaveable head debit against credit
        $loaderAmount = $this->input->post('loaderAmount');
        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($loaderAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '391',
                'credit' => $loaderAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        if (!empty($transportationAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '392',
                'credit' => $transportationAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
    }
    function chequeTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid)
    {
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts
        if (!empty($data['discount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //62 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.
        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //loader account head credit
        //transportation account head credit
        //loader and transportation account receiaveable head debit against credit
        $loaderAmount = $this->input->post('loaderAmount');
        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($loaderAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '391',
                'credit' => $loaderAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        if (!empty($transportationAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '392',
                'credit' => $transportationAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'invoiceID' => json_encode($this->input->post('voucherid')),
            'totalPayment' => $this->input->post('netTotal'),
            //'totalPayment' => $this->input->post('chaque_amount'),
            'receitID' => $mrid,
            'customerid' => $this->input->post('customer_id'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'mainInvoiceId' => $generals_id,
            'paymentType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
    }
    function partialTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid)
    {
        $this->form_validation->set_rules('partialPayment', 'Partial Payment', 'required');
        $this->form_validation->set_rules('accountCrPartial', 'Account Head', 'required');
        if ($this->form_validation->run() == FALSE) {
            exception("Required field can't be empty.");
            redirect(site_url('salesInvoice_add'));
        }
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts
        if (!empty($data['discount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
//49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //7501 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.
        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //loader account head credit
        //transportation account head credit
        //loader and transportation account receiaveable head debit against credit
        $loaderAmount = $this->input->post('loaderAmount');
        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($loaderAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '391',
                'credit' => $loaderAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        if (!empty($transportationAmount)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '392',
                'credit' => $transportationAmount,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //cash or partial payment start here.
        $generals_data = array(
            'form_id' => '7',
            'customer_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'mainInvoiceId' => $generals_id,
            'voucher_no' => $this->input->post('voucherid'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'credit' => $this->input->post('partialPayment'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $generalPaymentId = $this->Common_model->insert_data('generals', $generals_data);
        //1301 Cash in Hand  head debit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '7',
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCrPartial'),
            'debit' => $this->input->post('partialPayment'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //58 Account Receivable head credit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '7',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'credit' => $this->input->post('partialPayment'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //client vendor ledger
        $customerLedger = array(
            'ledger_type' => 1,
            'trans_type' => 'Sales Payment',
            'history_id' => $generalPaymentId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('partialPayment'),
            'cr' => $this->input->post('partialPayment'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate')))
        );
        $this->db->insert('client_vendor_ledger', $customerLedger);
        //money Receite General
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'invoiceID' => json_encode($this->input->post('voucherid')),
            'totalPayment' => $this->input->post('partialPayment'),
            'receitID' => $mrid,
            'customerid' => $this->input->post('customer_id'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 1,
            'mainInvoiceId' => $generalPaymentId
        );
        $this->db->insert('moneyreceit', $moneyReceit);
        //partial payment stop here.
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
        $data['third_link_page_url'] = 'salesInvoice_edit/' . $salesID;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/
        $data['saleslist'] = $this->Common_model->get_single_data_by_single_column('sales_invoice_info', 'sales_invoice_id', $salesID);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /* echo '<pre>';
         print_r($data['saleslist']);
         exit;*/
        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['saleslist']->customer_id);
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
        $data['mainContent'] = $this->load->view('distributor/sales/salesInvoiceLpg/sales_view', $data, true);
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
            //dumpVar($_POST);
            $this->form_validation->set_rules('netTotal', 'Net Total', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('saleDate', 'Sales Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Type', 'required');
            $this->form_validation->set_rules('category_id[]', 'Category ID', 'required');
            //$this->form_validation->set_rules('product_id[]', 'Product', 'required');
            //$this->form_validation->set_rules('quantity[]', 'Product Quantity', 'required');
            //$this->form_validation->set_rules('rate[]', 'Product Rate', 'required');
            //$this->form_validation->set_rules('price[]', 'Product Price', 'required');
            $allData = $this->input->post();
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('salesInvoice_add'));
            } else {
                $this->invoice_id = $this->input->post('sales_invoice_id');
                $allStock = array();
                $stockUpdate = array();
                $stockAdd = array();
                $Delete['is_active'] = 'N';
                $Delete['is_delete'] = 'Y';
                $Delete['update_by'] = $this->admin_id;
                $Delete['update_date'] = $this->timestamp;
                $Delete['delete_by'] = $this->admin_id;
                $Delete['delete_date'] = $this->timestamp;
                $this->db->where('sales_invoice_id', $this->invoice_id);
                $this->db->update('sales_details', $Delete);
                $invoice_amount = 0;
                foreach ($_POST['sales_details_id'] as $key => $value) {
                    $returnable_quantity = $_POST['returnAbleQuantity_' . $value] != '' ? $_POST['returnAbleQuantity_' . $value] : 0;
                    $return_quentity_edit = empty($_POST['returnQuentityEdit_' . $value]) ? 0 : array_sum($_POST['returnQuentityEdit_' . $value]);
                    $return_quentity_add = empty($_POST['returnedQuantityAdd_' . $value]) ? 0 : array_sum($_POST['returnedQuantityAdd_' . $value]);
                    $return_quentity = $return_quentity_add + $return_quentity_edit;
                    $supplier_advance = 0;
                    $supplier_due = 0;
                    unset($stock);
                    unset($stockNew);
                    $stock['sales_invoice_id'] = $this->invoice_id;
                    //$stock['sales_details_id'] = $value;
                    $stock['product_id'] = $_POST['product_id_' . $value];
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
                    $stock['unit_price'] = $_POST['unit_price_' . $value];
                    $invoice_amount = $invoice_amount + ($_POST['quantity_' . $value] * $_POST['unit_price_' . $value]);
                    $stock['update_by'] = $this->admin_id;
                    $stock['update_date'] = $this->timestamp;
                    $stock['is_active'] = 'Y';
                    $stock['is_delete'] = 'N';
                    $this->db->where('sales_details_id', $value);
                    $this->db->update('sales_details', $stock);
                    $this->db->where_in('sales_details_id', $value);
                    $this->db->update('sales_return_details', $Delete);
                    $stockUpdate[] = $stock;
                    if (isset($_POST['returnproductEdit_' . $value])) {
                        foreach ($_POST['returnproductEdit_' . $value] as $key1 => $value1) {
                            unset($stock2);
                            $stock2['sales_return_id'] = $value1;
                            //$stock2['product_id'] = $value1;
                            $stock2['returnable_quantity'] = $_POST['returnQuentityEdit_' . $value][$key1];
                            $stock2['return_quantity'] = $_POST['returnQuentityEdit_' . $value][$key1];
                            $stock2['update_by'] = $this->admin_id;
                            $stock2['update_date'] = $this->timestamp;
                            $stock2['is_active'] = 'Y';
                            $stock2['is_delete'] = 'N';
                            $allEditStock[] = $stock2;
                        }
                    }
                    if (isset($_POST['returnproductAdd_' . $value])) {
                        unset($alladdStock);
                        foreach ($_POST['returnproductAdd_' . $value] as $key1 => $value1) {
                            unset($stock2);
                            $stock2['sales_details_id'] = $value;
                            $stock2['product_id'] = $value1;
                            $stock2['returnable_quantity'] = $_POST['returnedQuantityAdd_' . $value][$key1];
                            $stock2['return_quantity'] = $_POST['returnedQuantityAdd_' . $value][$key1];
                            $stock2['update_by'] = $this->admin_id;
                            $stock2['update_date'] = $this->timestamp;
                            $alladdStock[] = $stock2;
                        }
                    }
                }
                $this->db->update_batch('sales_return_details', $allEditStock, 'sales_return_id');
                if (!empty($alladdStock)) {
                    $this->db->insert_batch('sales_return_details', $alladdStock);
                }
                if (!empty($_POST['slNo'])) {
                    foreach ($_POST['slNo'] as $key => $value) {
                        $returnable_quantity = $_POST['AddreturnQuantity']  [$value] != '' ? $_POST['AddreturnQuantity']  [$value] : 0;
                        $return_quentity = empty($_POST['AddreturnedQuantity_' . $value]) ? 0 : array_sum($_POST['AddreturnedQuantity_' . $value]);
                        $supplier_advance = 0;
                        $supplier_due = 0;
                        unset($stockNew);
                        $stockNew['sales_invoice_id'] = $this->invoice_id;
                        //$stock['sales_details_id'] = $value;
                        $stockNew['product_id'] = $_POST['Addproduct_id_' . $value];
                        $stockNew['is_package '] = $_POST['Addis_package_' . $value];
                        $stockNew['returnable_quantity '] = $returnable_quantity;
                        $stockNew['return_quentity '] = $return_quentity;
                        if ($return_quentity < $returnable_quantity) {
                            $supplier_due = $returnable_quantity - $return_quentity;
                        } else {
                            $supplier_advance = $return_quentity - $returnable_quantity;
                        }
                        $stockNew['customer_due'] = $supplier_due;
                        $stockNew['customer_advance'] = $supplier_advance;
                        $stockNew['quantity'] = $_POST['Addquantity_' . $value];
                        $stockNew['unit_price'] = $_POST['Addrate_' . $value];
                        $invoice_amount = $invoice_amount + ($_POST['Addquantity_' . $value] * $_POST['Addrate_' . $value]);
                        $stockNew['update_by'] = $this->admin_id;
                        $stockNew['update_date'] = $this->timestamp;
                        $stockNew['is_active'] = 'Y';
                        $stockNew['is_delete'] = 'N';
                        $this->db->insert('sales_details', $stockNew);
                        $sales_details_id = $this->db->insert_id();
                        //$stockAddNew[] = $stockNew;
                        if (isset($_POST['Addreturnproduct_' . $value])) {
                            //unset($alladdStockNew2);
                            foreach ($_POST['Addreturnproduct_' . $value] as $key1 => $value1) {
                                unset($stockNew2);
                                $stockNew2['sales_details_id'] = $sales_details_id;
                                $stockNew2['product_id'] = $value1;
                                $stockNew2['returnable_quantity'] = $_POST['AddreturnedQuantity_' . $value][$key1];
                                $stockNew2['return_quantity'] = $_POST['AddreturnedQuantity_' . $value][$key1];
                                $stockNew2['update_by'] = $this->admin_id;
                                $stockNew2['update_date'] = $this->timestamp;
                                $alladdStockNew2[] = $stockNew2;
                            }
                            if (!empty($alladdStockNew2)) {
                                $this->db->insert_batch('sales_return_details', $alladdStockNew2);
                            }
                            log_message('error', 'the return able cylinder' . print_r($alladdStockNew2, true));
                            log_message('error', 'the return able cylinder' . print_r($_POST['Addreturnproduct_' . $value], true));
                            log_message('error', 'the return able cylinder' . print_r($_POST, true));
                        }
                    }
                }
                $payType = $this->input->post('paymentType');
                $sales_inv['invoice_no'] = $this->input->post('voucherid');
                $sales_inv['sales_invoice_id'] = $this->input->post('sales_invoice_id');
                $sales_inv['customer_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_inv['customer_id'] = $this->input->post('customer_id');
                $sales_inv['payment_type'] = $payType;
                $sales_inv['invoice_amount'] = $invoice_amount;
                $sales_inv['vat_amount'] = $this->input->post('vat');
                $sales_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $sales_inv['paid_amount'] = $this->input->post('partialPayment') != '' ? $this->input->post('partialPayment') : 0;
                $sales_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $sales_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $sales_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $sales_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $sales_inv['refference_person_id'] = $this->input->post('reference');
                $sales_inv['company_id'] = $this->dist_id;
                $sales_inv['dist_id'] = $this->dist_id;
                $sales_inv['branch_id'] = 0;
                $sales_inv['due_date'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
                $sales_inv['invoice_date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                $sales_inv['insert_date'] = $this->timestamp;
                $sales_inv['insert_by'] = $this->admin_id;
                $sales_inv['is_active'] = 'Y';
                $sales_inv['is_delete'] = 'N';
                $this->Common_model->update_data('sales_invoice_info', $sales_inv, 'sales_invoice_id', $this->invoice_id);
                /* echo  '<pre>';
                 //print_r($_POST);
                 echo  'stockAdd<br>';
                 print_r($stockAdd);

                 echo  'alladdStockNew<br>';
                 print_r($alladdStockNew);

                 echo  'stockUpdate<br>';
                 print_r($stockUpdate);

                 echo  'allEditStock<br>';
                 print_r($allEditStock);
                 echo  'alladdStock<br>';
                 print_r($alladdStock);
                 exit;*/
                $productId = $this->input->post('product_id');
                $pmtype = $this->input->post('paymentType');
                $ppAmount = $this->input->post('partialPayment');
                $this->db->trans_start();
                // echo $payType;die;
                $data['customer_id'] = $this->input->post('customer_id');
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['reference'] = $this->input->post('reference');
                $data['payType'] = $this->input->post('paymentType');
                $data['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                $data['discount'] = $this->input->post('discount');
                $data['vat'] = $this->input->post('vat');
                $data['narration'] = $this->input->post('narration');
                $data['shipAddress'] = $this->input->post('shippingAddress');
                $data['loader'] = $this->input->post('loader');
                $data['loaderAmount'] = $this->input->post('loaderAmount');
                $data['transportation'] = $this->input->post('transportation');
                $data['transportationAmount'] = $this->input->post('transportationAmount');
                $data['form_id'] = 5;
                $data['debit'] = $this->input->post('netTotal');
                $data['dist_id'] = $this->dist_id;
                $data['updated_at'] = $this->timestamp;
                $data['updated_by'] = $this->admin_id;
                $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
                $grandtotal = $this->input->post('grandtotal');
                $data['vatAmount'] = ($grandtotal / 100) * $data['vat'];
                $returnQty = array();//array_sum($this->input->post('returnQuantity'));
                $this->Common_model->update_data('generals', $data, 'invoice_id', $this->invoice_id);
                $generals_id = $invoiceId;
                /* Delete query fro this invoice id */
                //delete stock table
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
                if (!empty($returnQty)) {
                    /*
                     * Edit By Nahid
                     * or Stop Inserting data to general table
                     *
                     *
                     * $cylinder['customer_id'] = $this->input->post('customer_id');
                      $cylinder['voucher_no'] = $this->input->post('voucherid');
                      $cylinder['reference'] = $this->input->post('reference');
                      $cylinder['payType'] = $this->input->post('paymentType');
                      $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                      $cylinder['discount'] = $this->input->post('discount');
                      $cylinder['vat'] = $this->input->post('vat');
                      $cylinder['narration'] = $this->input->post('narration');
                      $cylinder['shipAddress'] = $this->input->post('shippingAddress');
                      $cylinder['form_id'] = 23;
                      $cylinder['debit'] = $this->input->post('netTotal');
                      $cylinder['dist_id'] = $this->dist_id;
                      $cylinder['mainInvoiceId'] = $generals_id;
                      $cylinder['updated_by'] = $this->admin_id;
                      $cylinder['vatAmount'] = ($grandtotal / 100) * $data['vat'];
                      $cylinderId = $this->Common_model->insert_data('generals', $cylinder); */
                }
                $customerName = $this->Common_model->tableRow('customer', 'customer_id', $data['customer_id'])->customerName;
                $mobile = $this->Common_model->tableRow('customer', 'customer_id', $data['customer_id'])->customerPhone;
                $category_cat = $this->input->post('category_id');
                $allStock = array();
                $allStock1 = array();
                $totalProductCost = 0;
                $newCylinderProductCost = 0;
                $otherProductCost = 0;
                /*foreach ($category_cat as $key => $value):
                    unset($stock);
                    $productCost = $this->Sales_Model->productCost($this->input->post('product_id')[$key], $this->dist_id);
                    $totalProductCost += $this->input->post('quantity')[$key] * $productCost;
                    if ($value == 1) {
                        //get cylinder product cost
                        $newCylinderProductCost += $this->input->post('quantity')[$key] * $productCost;
                    } else {
                        //get without cylinder product cost
                        $otherProductCost += $this->input->post('quantity')[$key] * $productCost;
                    }
                    $stock['generals_id'] = $generals_id;
                    $stock['category_id'] = $value;
                    $stock['product_id'] = $this->input->post('product_id')[$key];
                    $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
                    $stock['quantity'] = $this->input->post('quantity')[$key];
                    $stock['rate'] = $this->input->post('rate')[$key];
                    $stock['price'] = $this->input->post('price')[$key];
                    $stock['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                    $stock['form_id'] = 5;
                    $stock['type'] = 'Out';
                    $stock['dist_id'] = $this->dist_id;
                    $stock['updated_by'] = $this->admin_id;
                    $stock['created_at'] = $this->timestamp;
                    $allStock[] = $stock;
                    $returnQty = $this->input->post('returnQuantity')[$key];
                    //If cylinder stock out than transaction store here.
                    if (!empty($returnQty)) {
                        // $productCost = $this->Sales_Model->productCost($this->input->post('product_id')[$key], $this->dist_id);
                        //$stock1['generals_id'] = $cylinderId;
                        $stock1['generals_id'] = $generals_id;
                        $stock1['category_id'] = $value;
                        $stock1['product_id'] = $this->input->post('product_id')[$key];
                        $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
                        $stock1['quantity'] = $this->input->post('returnQuantity')[$key];
                        $stock1['rate'] = $this->input->post('rate')[$key];
                        $stock1['price'] = $this->input->post('price')[$key];
                        $stock1['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                        $stock1['form_id'] = 23;
                        $stock1['type'] = 'Cout';
                        $stock1['dist_id'] = $this->dist_id;
                        $stock1['customerId'] = $this->input->post('customer_id');
                        $stock1['updated_by'] = $this->admin_id;
                        $stock1['created_at'] = $this->timestamp;
                        $allStock1[] = $stock1;
                    }
                endforeach;
                $cylinderRecive = $this->input->post('category_id2');
                $cylinderAllStock = array();*/
                /* if (!empty($cylinderRecive)):
                     /*
                      * Edit By Nahid
                      * or Stop Inserting data to general table
                      *
                      *
                      * $cylinderData['customer_id'] = $this->input->post('customer_id');
                       $cylinderData['voucher_no'] = $this->input->post('voucherid');
                       $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                       $cylinderData['narration'] = $this->input->post('narration');
                       $cylinderData['form_id'] = 24;
                       $cylinderData['dist_id'] = $this->dist_id;
                       $cylinderData['mainInvoiceId'] = $generals_id;
                       $cylinderData['updated_by'] = $this->admin_id;
                       $CylinderReceive = $this->Common_model->insert_data('generals', $cylinderData); */
                /* foreach ($cylinderRecive as $key => $value) :
                     //$stock1['generals_id'] = $cylinderId;
                     $stockReceive['generals_id'] = $generals_id;
                     //$stockReceive['generals_id'] = $CylinderReceive;
                     $stockReceive['category_id'] = $value;
                     $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                     $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                     $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                     $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                     $stockReceive['form_id'] = 24;
                     $stockReceive['type'] = 'Cin';
                     $stockReceive['dist_id'] = $this->dist_id;
                     $stockReceive['customerId'] = $this->input->post('customer_id');
                     $stockReceive['updated_by'] = $this->admin_id;
                     $stockReceive['created_at'] = $this->timestamp;
                     $cylinderAllStock[] = $stockReceive;
                 endforeach;
                 //insert for culinder receive
                 $this->db->insert_batch('stock', $cylinderAllStock);
                 $this->db->insert_batch('stock', $cylinderAllStock);
             endif;
             //insert for quantity out stock
             $this->db->insert_batch('stock', $allStock);
             //insert for cylinder stock out
             $this->db->insert_batch('stock', $allStock1);*/
                //insert in stock table
                $customerLedger = array(
                    'ledger_type' => 1,
                    'trans_type' => 'Sales',
                    'history_id' => $generals_id,
                    'trans_type' => $this->input->post('voucherid'),
                    'client_vendor_id' => $this->input->post('customer_id'),
                    'updated_by' => $this->admin_id,
                    'dist_id' => $this->dist_id,
                    'amount' => $this->input->post('netTotal'),
                    'dr' => $this->input->post('netTotal'),
                    'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                );
                $this->db->insert('client_vendor_ledger', $customerLedger);
                $mrCondition = array(
                    'dist_id' => $this->dist_id,
                    'receiveType' => 1,
                );
                $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                $mrid = "CMR" . date("y") . date("m") . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                if ($payType == 1) {
                    //when payment type cash
                    $this->cashTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                } elseif ($payType == 2) {
                    //when paymnet type credit
                    $this->creditTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                } elseif ($payType == 3) {
                    //when payment type cheque
                    $this->chequeTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                } else {
                    //when payment transaction is partial.but now it use as cash.
                    $this->partialTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Sales Invoice  ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect('salesInvoice_add/', 'refresh');
                } else {
                    $msg = 'Sales Invoice  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect('salesInvoice_view/' . $this->invoice_id, 'refresh');
                }
                /* Delete query fro this invoice id */
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('Sale_Invoice_Edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Invoice');
        $data['link_page_url'] = 'salesInvoice_add';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . $invoiceId;
        $data['third_link_icon'] = $this->link_icon_edit;
        /*page navbar details*/
        $data['editInvoice'] = $this->Common_model->get_single_data_by_single_column('sales_invoice_info', 'sales_invoice_id', $invoiceId);
//echo "<pre>";
//print_r($data['editInvoice']);exit;
        $data['bank_check_details'] = array();
        if ($data['editInvoice']->payment_type == 3) {
            $data['bank_check_details'] = $this->Common_model->get_single_data_by_single_column('moneyreceit', 'mainInvoiceId', $data['editInvoice']->sales_invoice_id);
        }
        //$data['editStock'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $invoiceId);
        $stockList = $this->Common_model->get_sales_product_detaild2($invoiceId);
        foreach ($stockList as $ind => $element) {
            $result[$element->sales_invoice_id][$element->sales_details_id]['sales_invoice_id'] = $element->sales_invoice_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['sales_details_id'] = $element->sales_details_id;
            $result[$element->sales_invoice_id][$element->sales_details_id]['is_package'] = $element->is_package;
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
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        //get only cylinder product
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        // dumpVar($data['accountHeadList']);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        //echo '<pre>';
        //print_r($data['cylinderProduct']);exit;
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['referenceList'] = $this->Sales_Model->getReferenceList($this->dist_id);
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['cylinserOut'] = $this->Sales_Model->getCylinderInOutResult($this->dist_id, $invoiceId, 23);
        $data['cylinderReceive'] = $this->Sales_Model->getCylinderInOutResult2($this->dist_id, $invoiceId, 24);
        //echo $this->db->last_query();exit;
        $data['creditAmount'] = $paymentInfo = $this->Sales_Model->getCreditAmount($invoiceId);
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition);
        $data['accountId'] = $this->Sales_Model->getAccountId($paymentInfo->generals_id);
        //echo '<pre>';
        //print_r($data);exit;
        // echo $this->db->last_query();die;
        $data['mainContent'] = $this->load->view('distributor/sales/saleInvoice/editInvoice', $data, true);
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
    public function customerPaymentAdd()
    {
        if (isPostBack()) {
            $this->form_validation->set_rules('customerid', 'Customer ID', 'required');
            $this->form_validation->set_rules('paymentDate', 'Payment Date', 'required');
            $this->form_validation->set_rules('receiptId', 'Money Receit ID', 'required');
            $this->form_validation->set_rules('payType', 'Payment Type', 'required');
            $this->form_validation->set_rules('ttl_amount', 'Total Payment Amount', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url('referenceAdd'));
            } else {
                /*echo "<pre>";
                print_r($_POST);
                exit;*/
                $updated_by = $this->admin_id;
                $created_at = date('Y-m-d H:i:s');
                $voucher = $this->input->post('voucher');
                $paymentType = $this->input->post('payType');
                $accountDr = $this->input->post('accountDr');
                $branch_id = $this->input->post('branch_id');
                $voucherID = 0;
                $this->db->trans_start();
                if (!empty($voucher)) {
                    $moneyReceitNo = $this->db->where(array('dist_id' => $this->dist_id))->count_all_results('cus_due_collection_info') + 1;
                    $ReceitVoucher = "CDR" . date("y") . date("m") . str_pad($moneyReceitNo, 4, "0", STR_PAD_LEFT);
                    $due_collection_info['total_paid_amount'] = $this->input->post('paid_amount');
                    $due_collection_info['customer_id'] = $this->input->post('customerid');
                    $due_collection_info['cus_due_coll_no'] = $ReceitVoucher;
                    $due_collection_info['payment_type'] = $this->input->post('payType');
                    $due_collection_info['bank_name'] = $this->input->post('bankName');
                    $due_collection_info['branch_name'] = $this->input->post('branchName');
                    $due_collection_info['check_no'] = $this->input->post('checkNo');
                    $due_collection_info['check_date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $due_collection_info['date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                    $due_collection_info['dist_id'] = $this->dist_id;
                    $due_collection_info['insert_date'] = $this->timestamp;
                    $due_collection_info['insert_by'] = $this->admin_id;
                    $due_collection_info['is_active'] = 'Y';
                    $due_collection_info['is_delete'] = 'N';
                    $cus_due_collection_info_id = $this->Common_model->insert_data('cus_due_collection_info', $due_collection_info);
                    if ($paymentType == 1) {
//when payment type cash than transaction here.
//check account head empty or not
                        /* if (empty($accountCr)) {
                             notification("Account Head must be selected!!");
                             redirect(site_url($this->project . '/customerPaymentAdd'));
                         }*/
                        $totalAmount = 0;
                        foreach ($voucher as $a => $b) {
                            $amount = $this->input->post('amount[' . $a . ']');
                            if (!empty($amount)) {
                                $totalAmount += $amount;
                                $this->load->helper('create_receive_voucher_no_helper');
                                $reciveVoucherNo = create_receive_voucher_no();
                                $dataMaster['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                                $dataMaster['Accounts_Voucher_No'] = $reciveVoucherNo;
                                $dataMaster['Narration'] = $this->input->post('narration');
                                $dataMaster['CompanyId'] = $this->dist_id;
                                $dataMaster['BranchAutoId'] = $branch_id;
                                $dataMaster['Reference'] = 0;
                                $dataMaster['AccouVoucherType_AutoID'] = 1;
                                $dataMaster['IsActive'] = 1;
                                $dataMaster['for'] = 2;
                                $dataMaster['Created_By'] = $this->admin_id;
                                $dataMaster['Created_Date'] = $this->timestamp;
                                $dataMaster['customer_id'] = $this->input->post('customerid');
                                $dataMaster['BackReferenceInvoiceNo'] = $this->input->post('voucher[' . $a . ']');
                                $dataMaster['BackReferenceInvoiceID'] = $this->input->post('invoiceID[' . $a . ']');
                                $dataMaster['Narration'] = $this->input->post('narration');
                                $accounting_vouchaer_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $dataMaster);
                                /*Customer Receivable  /account Receiveable  =>>25*/
                                //account Receiveable
                                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accounting_vouchaer_id;
                                $accountingDetailsTableCustomerReceivable['TypeID'] = '2';//Cr
                                $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $this->config->item("Customer_Receivable");//'25';
                                $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = '0.00';
                                $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = $this->input->post('amount[' . $a . ']');
                                $accountingDetailsTableCustomerReceivable['Reference'] = 'Supplier paid amount';
                                $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
                                $accountingDetailsTableCustomerReceivable['Created_By'] = $this->admin_id;
                                $accountingDetailsTableCustomerReceivable['Created_Date'] = $this->timestamp;
                                $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
                                $accountingDetailsTableCustomerReceivable['date'] = date('Y-m-d', strtotime($this->input->post('date')));;
                                $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
                                $accountingDetailsTableCustomerReceivable = array();
                                //account Receiveable
                                /*Cash in hand*/
                                $accountingDetailsTableCashinhand['Accounts_VoucherMst_AutoID'] = $accounting_vouchaer_id;
                                $accountingDetailsTableCashinhand['TypeID'] = '1';//Dr
                                $accountingDetailsTableCashinhand['CHILD_ID'] = $this->input->post('accountDr');
                                $accountingDetailsTableCashinhand['GR_DEBIT'] = $this->input->post('amount[' . $a . ']');
                                $accountingDetailsTableCashinhand['GR_CREDIT'] = '0.00';
                                $accountingDetailsTableCashinhand['Reference'] = '';
                                $accountingDetailsTableCashinhand['IsActive'] = 1;
                                $accountingDetailsTableCashinhand['Created_By'] = $this->admin_id;
                                $accountingDetailsTableCashinhand['Created_Date'] = $this->timestamp;
                                $accountingDetailsTableCashinhand['BranchAutoId'] = $branch_id;
                                $accountingDetailsTableCashinhand['date'] = date('Y-m-d', strtotime($this->input->post('date')));;
                                $finalDetailsArray[] = $accountingDetailsTableCashinhand;
                                $accountingDetailsTableCashinhand = array();
                                if (!empty($finalDetailsArray)) {
                                    $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                                }
                                $supp = array(
                                    'ledger_type' => 1,
                                    'dist_id' => $this->dist_id,
                                    'trans_type' => $this->input->post('invoiceID[' . $a . ']'),
                                    'client_vendor_id' => $this->input->post('customerid'),
                                    'amount' => $this->input->post('amount[' . $a . ']'),
                                    'cr' => $this->input->post('amount[' . $a . ']'),
                                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                                    'updated_by' => $this->admin_id,
                                    'history_id' => $accounting_vouchaer_id,
                                    'invoice_id' => $this->input->post('invoiceID[' . $a . ']'),
                                    'invoice_type' => 2,//purchase
                                    'BranchAutoId' => $branch_id,//purchase
                                    'paymentType' => 'Customer Payment'
                                );
                                $this->db->insert('client_vendor_ledger', $supp);
                                $due_collection['sales_invoice_id'] = $this->input->post('invoiceID[' . $a . ']');
                                $due_collection['due_collection_info_id'] = $cus_due_collection_info_id;
                                $due_collection['customer_id'] = $this->input->post('customerid');
                                $due_collection['payment_type'] = $paymentType;
                                $due_collection['paid_amount'] = $this->input->post('amount[' . $a . ']');
                                $due_collection['insert_date'] = $this->timestamp;
                                $due_collection['date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                                $due_collection['insert_by'] = $this->admin_id;
                                $due_collection['is_active'] = 'Y';
                                $due_collection['is_delete'] = 'N';
                                $allStock[] = $due_collection;
                                $postedInvoiceNo[] = $this->input->post('invoiceID[' . $a . ']');;
                            }
                        }
                    } else {
                        $totalAmount = 0;
                        foreach ($voucher as $a => $b) {
                            $amount = $this->input->post('amount[' . $a . ']');
                            if (!empty($amount)) {
                                $totalAmount += $amount;
                                $due_collection['sales_invoice_id'] = $this->input->post('invoiceID[' . $a . ']');
                                $due_collection['due_collection_info_id'] = $cus_due_collection_info_id;
                                $due_collection['customer_id'] = $this->input->post('customerid');
                                $due_collection['payment_type'] = $paymentType;
                                $due_collection['paid_amount'] = $this->input->post('amount[' . $a . ']');
                                $due_collection['insert_date'] = $this->timestamp;
                                $due_collection['date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                                $due_collection['insert_by'] = $this->admin_id;
                                $due_collection['is_active'] = 'Y';
                                $due_collection['is_delete'] = 'N';
                                $allStock[] = $due_collection;
                                $postedInvoiceNo[] = $this->input->post('invoiceID[' . $a . ']');;
                            }
                        }
                    }
                    $mrCondition = array(
                        'dist_id' => $this->dist_id,
                        'receiveType' => 2,
                    );
                    $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                    $mrid = "CMR" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                    $bankName = $this->input->post('bankName');
                    $checkNo = $this->input->post('checkNo');
                    $checkDate = $this->input->post('date');
                    $branchName = $this->input->post('branchName');
                    $mreceit = array(
                        'date' => date('Y-m-d', strtotime($this->input->post('paymentDate'))),
                        'due_collection_info_id' => json_encode($cus_due_collection_info_id),
                        'Accounts_VoucherMst_AutoID' => $voucherID,
                        'totalPayment' => $totalAmount,
                        'receitID' => $mrid,
                        'customerid' => $this->input->post('customerid'),
                        'narration' => $this->input->post('narration'),
                        'updated_by' => $this->admin_id,
                        'dist_id' => $this->dist_id,
                        'receiveType' => 1,
                        'paymentType' => $this->input->post('payType'),
                        'bankName' => isset($bankName) ? $bankName : '0',
                        'checkNo' => isset($checkNo) ? $checkNo : '0',
                        'checkDate' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'BranchAutoId' => $branch_id,//purchase
                        'branchName' => isset($branchName) ? $branchName : '0'
                    );
                    $this->db->insert('moneyreceit', $mreceit);
                    if (!empty($allStock)) {
                        $this->db->insert_batch('cus_due_collection_details', $allStock);
                    }
                    if (!empty($postedInvoiceNo)) {
                        $cus_due_collection_info['ref_invoice_ids'] = implode(",", $postedInvoiceNo);
                        $this->Common_model->update_data('cus_due_collection_info', $cus_due_collection_info, 'id', $cus_due_collection_info_id);
                    }
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Customer Payment ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/customerPayment'));
                } else {
                    $msg = 'Customer Payment ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    //message("Your data successfully inserted into database.");
                    redirect(site_url($this->project . '/customerPayment'));
                }
            }
        }
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $moneyReceitNo = $this->db->where(array('dist_id' => $this->dist_id, 'receiveType' => 1))->count_all_results('moneyreceit') + 1;
        $data['moneyReceitVoucher'] = "CMR" . date("y") . date("m") . str_pad($moneyReceitNo, 4, "0", STR_PAD_LEFT);
        $condition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['title'] = 'Customer Payment Receive';
        /*page navbar details*/
        $data['title'] = get_phrase('Customer Payment Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Customer Payment List');
        $data['link_page_url'] = $this->project . '/customerPayment';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['customerList'] = $this->Inventory_Model->getPaymentDueSupplierCustomer($this->dist_id, 1);
        $data['mainContent'] = $this->load->view('distributor/sales/report/customerPayment', $data, true);
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
}