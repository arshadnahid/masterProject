<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/22/2019
 * Time: 3:35 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class PurchaseLpgController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;
    public $page_type;
    public $folder;
    public $folderSub;
    public $link_icon_add;
    public $link_icon_list;
    public $link_icon_edit;

    public $business_type;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Purchases_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->invoice_type = 1;
        $this->page_type = 'inventory';


        $this->folderSub = 'distributor/inventory/cylinderInOut/';
        $this->link_icon_add = "<i class='fa fa-plus'></i>";
        $this->link_icon_list = "<i class='fa fa-list'></i>";
        $this->link_icon_edit = "<i class='fa fa-edit'></i>";
        $this->lang->load('content', 'english');
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->business_type = $this->session->userdata('business_type');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);


        if ($this->business_type == "MOBILE") {
            $this->folder = 'distributor/masterTemplateSmeMobile';

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        }else{
            $this->folder = 'distributor/masterTemplate';
        }
    }
    public function purchases_lpg_add($confirId = null)
    {
        $this->load->helper('purchase_invoice_no_helper');
        //$this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {
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
                $invoice_no = create_purchase_invoice_no();
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
                $purchase_inv['for'] = 2;
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
                $accountingMasterTable['Narration'] = 'Purchase Voucher ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $branch_id;
                $accountingMasterTable['supplier_id'] = $this->input->post('supplierID');
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingMasterTable['for'] = 1;
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

                    $purchase_details_id = $this->Common_model->insert_data('purchase_details', $stock);
                    $category_id = $this->Common_model->tableRow('product', 'product_id', $product_id)->category_id;
                    //$newCylinderProductCost = $newCylinderProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);


                    $packageEmptyProductId=0;
                    if ($category_id == 2 && $_POST['is_package_' . $value] == 0) {
                        $packageEmptyProductId = $this->getPackageEmptyProductId($_POST['product_id_' . $value]);
                    }


                    $stockNewTable['parent_stock_id']=0;
                    $stockNewTable['invoice_id']=$this->invoice_id;
                    $stockNewTable['form_id']=2;
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
                        $stockNewTable['form_id']=2;
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
                            $stockNewTable['form_id']=2;
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


                log_message('error',"allEmptyCylinderWithRefillArray".print_r($allEmptyCylinderWithRefillArray,true));
                log_message('error',"allEmptyCylinderReturnArray".print_r($allEmptyCylinderReturnArray,true));

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
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message").' There is something wrong please try again .contact with Customer Care';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/purchases_lpg_add'));
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
                    redirect(site_url($this->project . '/purchases_lpg_add'));
                } else {
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/viewPurchasesCylinder/' . $this->invoice_id));
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
        $data['title'] = get_phrase('New Purchase Invoice');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Invoice List');
        $data['link_page_url'] = $this->project . '/purchases_cylinder_list';
        $data['link_icon'] = $this->link_icon_list;
        /* page navbar details */
        $condition2 = array(
            //'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_account_info', $condition2);
        $data['voucherID'] = create_purchase_invoice_no();
        /* this invoice no is comming  from purchase_invoice_no_helper */
        if ($this->business_type != "LPG") {
            $data['mainContent'] = $this->load->view('distributor/inventory/purchases_mobile/purchasesWithPos', $data, true);

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        }else{
            $data['mainContent'] = $this->load->view('distributor/inventory/purchases_lpg/purchasesWithPos', $data, true);
        }



        $this->load->view($this->folder, $data);
    }
    function getPackageEmptyProductId($RefillproductId){
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
    function package_product_list()
    {
        $package_id = $this->input->post('package_id');
        $packageProductDetails = $this->Common_model->get_package_product($package_id);
        echo json_encode($packageProductDetails);
        //echo "<pre>";l
        //print_r($productDetails);
        // if (!empty($productDetails)):
        //    echo $productDetails->purchases_price;
        // endif;
    }
    public function cylinder_stock_report()
    {
        if (isPostBack()) {
            $brandId = $this->input->post('brandId');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['allStock'] = $this->Inventory_Model->cylinder_stock_report($start_date, $end_date, $brandId);
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Cylinder Stock Report';
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = 'Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinder_stock_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function current_stock_value()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['allStock'] = $this->Inventory_Model->current_stock($this->dist_id);
//        echo '<pre>';
//        echo $this->db->last_query();
//        exit;
        $data['title'] = get_phrase('Current Stock');
        $data['page_type'] = get_phrase($this->page_type);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/current_stock_value', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function purchases_lpg_edit($ivnoiceId = null)
    {
        /*if (is_numeric($ivnoiceId)) {
            //is invoice id is valid
            $validInvoiecId = $this->Purchases_Model->checkInvoiceIdAndDistributor($this->dist_id, $ivnoiceId);

            if ($validInvoiecId === NULL) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url($this->project . '/purchases_cylinder_list'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url($this->project . '/purchases_cylinder_list'));
        }*/
        if (isPostBack()) {
            /*echo '<pre>';
            print_r($_POST);
             exit;*/
            $this->db->trans_start();
            $this->invoice_id = $this->input->post('purchase_invoice_id');
            $Delete['is_active'] = 'N';
            $Delete['is_delete'] = 'Y';
            $Delete['update_by'] = $this->admin_id;
            $Delete['update_date'] = $this->timestamp;
            $Delete['delete_by'] = $this->admin_id;
            $Delete['delete_date'] = $this->timestamp;
            $this->db->where('purchase_invoice_id', $this->invoice_id);
            $this->db->update('purchase_details', $Delete);
            $invoice_amount = 0;
            foreach ($_POST['purchase_details_id'] as $key => $value) {
                $returnable_quantity = $_POST['returnAbleQuantity_' . $value] != '' ? $_POST['returnAbleQuantity_' . $value] : 0;
                $return_quentity_edit = empty($_POST['returnQuentityEdit_' . $value]) ? 0 : array_sum($_POST['returnQuentityEdit_' . $value]);
                $return_quentity_add = empty($_POST['returnQuentityAdd_' . $value]) ? 0 : array_sum($_POST['returnQuentityAdd_' . $value]);
                $return_quentity = $return_quentity_add + $return_quentity_edit;
                $supplier_advance = 0;
                $supplier_due = 0;
                unset($stock);
                unset($stockNew);
                $stock['purchase_invoice_id'] = $this->invoice_id;
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
                $stock['supplier_due'] = $supplier_due;
                $stock['supplier_advance'] = $supplier_advance;
                $stock['quantity'] = $_POST['quantity_' . $value];
                $stock['unit_price'] = $_POST['unit_price_' . $value];
                $invoice_amount = $invoice_amount + ($_POST['quantity_' . $value] * $_POST['unit_price_' . $value]);
                $stock['update_by'] = $this->admin_id;
                $stock['update_date'] = $this->timestamp;
                $stock['is_active'] = 'Y';
                $stock['is_delete'] = 'N';
                $this->db->where('purchase_details_id', $value);
                $this->db->update('purchase_details', $stock);
                $this->db->where_in('purchase_details_id', $value);
                $this->db->update('purchase_return_details', $Delete);
                $stockUpdate[] = $stock;
                if (isset($_POST['returnproductEdit_' . $value])) {
                    foreach ($_POST['returnproductEdit_' . $value] as $key1 => $value1) {
                        unset($stock2);
                        $stock2['purchase_return_id'] = $value1;
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
                if (isset($_POST['Addreturnproduct_' . $value])) {
                    unset($alladdStock);
                    foreach ($_POST['Addreturnproduct_' . $value] as $key1 => $value1) {
                        unset($stock2);
                        $stock2['purchase_details_id'] = $value;
                        $stock2['product_id'] = $value1;
                        $stock2['returnable_quantity'] = $_POST['AddreturnQuentity_' . $value][$key1];
                        $stock2['return_quantity'] = $_POST['AddreturnQuentity_' . $value][$key1];
                        $stock2['update_by'] = $this->admin_id;
                        $stock2['update_date'] = $this->timestamp;
                        $alladdStock[] = $stock2;
                    }
                }
            }
            if (!empty($allEditStock)) {
                $this->db->update_batch('purchase_return_details', $allEditStock, 'purchase_return_id');
            }
            if (!empty($alladdStock)) {
                $this->db->insert_batch('purchase_return_details', $alladdStock);
            }
            if (!empty($_POST['slNo'])) {
                foreach ($_POST['slNo'] as $key => $value) {
                    $returnable_quantity = $_POST['add_returnAble']  [$value] != '' ? $_POST['add_returnAble']  [$value] : 0;
                    $return_quentity = empty($_POST['AddreturnQuentity_' . $value]) ? 0 : array_sum($_POST['AddreturnQuentity_' . $value]);
                    $supplier_advance = 0;
                    $supplier_due = 0;
                    unset($stockNew);
                    $stockNew['purchase_invoice_id'] = $this->invoice_id;
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
                    $stockNew['supplier_due'] = $supplier_due;
                    $stockNew['supplier_advance'] = $supplier_advance;
                    $stockNew['quantity'] = $_POST['Addquantity_' . $value];
                    $stockNew['unit_price'] = $_POST['Addrate_' . $value];
                    $invoice_amount = $invoice_amount + ($_POST['Addquantity_' . $value] * $_POST['Addrate_' . $value]);
                    $stockNew['update_by'] = $this->admin_id;
                    $stockNew['update_date'] = $this->timestamp;
                    $stockNew['is_active'] = 'Y';
                    $stockNew['is_delete'] = 'N';
                    $this->db->insert('purchase_details', $stockNew);
                    //$sales_details_id = 1;
                    $sales_details_id = $this->db->insert_id();
                    $stockAddNew[] = $stockNew;
                    if (isset($_POST['Addreturnproduct_' . $value])) {
                        //unset($alladdStockNew2);
                        foreach ($_POST['Addreturnproduct_' . $value] as $key1 => $value1) {
                            unset($stockNew2);
                            $stockNew2['purchase_details_id'] = $sales_details_id;
                            $stockNew2['product_id'] = $value1;
                            $stockNew2['returnable_quantity'] = $_POST['AddreturnQuentity_' . $value][$key1];
                            $stockNew2['return_quantity'] = $_POST['AddreturnQuentity_' . $value][$key1];
                            $stockNew2['update_by'] = $this->admin_id;
                            $stockNew2['update_date'] = $this->timestamp;
                            $alladdStockNew2[] = $stockNew2;
                        }
                        if (!empty($alladdStockNew2)) {
                            $this->db->insert_batch('purchase_return_details', $alladdStockNew2);
                        }
                        //log_message('error','the return able cylinder'.print_r($alladdStockNew2,true));
                        //log_message('error','the return able cylinder'.print_r($_POST['Addreturnproduct_' . $value],true));
                        //log_message('error','the return able cylinder'.print_r($_POST,true));
                    }
                }
            }
            $paymentType = $this->input->post('paymentType');
            $purchase_inv['invoice_no'] = $this->input->post('voucherid');
            $purchase_inv['supplier_invoice_no'] = $this->input->post('userInvoiceId');
            $purchase_inv['supplier_id'] = $this->input->post('supplierID');
            $purchase_inv['payment_type'] = $paymentType;
            $purchase_inv['invoice_amount'] = $invoice_amount;
            $purchase_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
            $purchase_inv['paid_amount'] = $this->input->post('thisAllotment') != '' ? $this->input->post('thisAllotment') : 0;
            $purchase_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
            $purchase_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
            $purchase_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
            $purchase_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
            $purchase_inv['refference_person'] = $this->input->post('reference');
            $purchase_inv['company_id'] = $this->dist_id;
            $purchase_inv['dist_id'] = $this->dist_id;
            $purchase_inv['branch_id'] = 0;
            $purchase_inv['due_date'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
            $purchase_inv['invoice_date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $purchase_inv['insert_date'] = $this->timestamp;
            $purchase_inv['insert_by'] = $this->admin_id;
            $purchase_inv['is_active'] = 'Y';
            $purchase_inv['is_delete'] = 'N';
            if ($paymentType == 3) {
                $purchase_inv['bank_id'] = $this->input->post('bankName');
                $purchase_inv['bank_branch_id'] = $this->input->post('branchName');
                $purchase_inv['check_date'] = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                $purchase_inv['check_no'] = $this->input->post('checkNo');
            }
            $this->Common_model->update_data('purchase_invoice_info', $purchase_inv, 'purchase_invoice_id', $this->invoice_id);
            /*ac_accounts_vouchermst*/
            $condition = array(
                'AccouVoucherType_AutoID' => 5,
                'BackReferenceInvoiceID' => $this->invoice_id,
            );
            $accountingMasterTable = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $condition);
            $accountingMasterTableUpdate['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $accountingMasterTableUpdate['Narration'] = 'Purchase Voucher edit';
            $accountingMasterTableUpdate['CompanyId'] = $this->dist_id;
            $accountingMasterTableUpdate['BranchAutoId'] = $this->dist_id;
            $accountingMasterTableUpdate['supplier_id'] = $this->input->post('supplierID');
            $accountingMasterTableUpdate['IsActive'] = 1;
            $accountingMasterTableUpdate['Changed_By'] = $this->admin_id;
            $accountingMasterTableUpdate['Changed_Date'] = $this->timestamp;
            $this->db->where('AccouVoucherType_AutoID', 5);
            $this->db->where('BackReferenceInvoiceID', $this->invoice_id);
            $this->db->where('Accounts_VoucherMst_AutoID', $accountingMasterTable->Accounts_VoucherMst_AutoID);
            $this->db->update('ac_accounts_vouchermst', $accountingMasterTableUpdate);
            $inactive['IsActive'] = 0;
            $this->db->where('Accounts_VoucherMst_AutoID', $accountingMasterTable->Accounts_VoucherMst_AutoID);
            $this->db->update('ac_tb_accounts_voucherdtl', $inactive);
            /*New Cylinder Stock =>22*/
            /*Inventory stock =>20*/
            $NewCylinderStockCondition = array(
                'TypeID' => 1,
                'CHILD_ID' => 20,
                'Accounts_VoucherMst_AutoID' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
            );
            $accountingDetailsTableNewCylinderStock['GR_DEBIT'] = '101010';
            $accountingDetailsTableNewCylinderStock['GR_CREDIT'] = '0.00';
            $accountingDetailsTableNewCylinderStock['Reference'] = 'New Cylinder Stock';
            $accountingDetailsTableNewCylinderStock['IsActive'] = 1;
            $accountingDetailsTableNewCylinderStock['Changed_By'] = $this->admin_id;
            $accountingDetailsTableNewCylinderStock['Changed_Date'] = $this->timestamp;
            $accountingDetailsTableNewCylinderStock['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
            $result = $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $accountingDetailsTableNewCylinderStock, $NewCylinderStockCondition);
            /*Loading and wages*/
            if ($this->input->post('loaderAmount') > 0) {
                $LoadingWagesCondition = array(
                    'TypeID' => 1,
                    'CHILD_ID' => 47,
                    'Accounts_VoucherMst_AutoID' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                );
                $accountingDetailsTableLoadingWages['Accounts_VoucherMst_AutoID'] = $accountingMasterTable->Accounts_VoucherMst_AutoID;
                $accountingDetailsTableLoadingWages['TypeID'] = '1';//Dr
                $accountingDetailsTableLoadingWages['CHILD_ID'] = '47';
                $accountingDetailsTableLoadingWages['GR_DEBIT'] = $this->input->post('loaderAmount');
                $accountingDetailsTableLoadingWages['GR_CREDIT'] = '0.00';
                $accountingDetailsTableLoadingWages['Reference'] = 'Loading and wages';
                $accountingDetailsTableLoadingWages['IsActive'] = 1;
                $accountingDetailsTableLoadingWages['Changed_By'] = $this->admin_id;
                $accountingDetailsTableLoadingWages['Changed_Date'] = $this->timestamp;
                $accountingDetailsTableLoadingWages['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
                $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $accountingDetailsTableLoadingWages, $LoadingWagesCondition);
            }
            /*Loading and wages*/
            /*Transportation*/
            if ($this->input->post('transportationAmount') > 0) {
                $TransportationCondition = array(
                    'TypeID' => 1,
                    'CHILD_ID' => 42,
                    'Accounts_VoucherMst_AutoID' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                );
                $accountingDetailsTableTransportation['Accounts_VoucherMst_AutoID'] = $accountingMasterTable->Accounts_VoucherMst_AutoID;
                $accountingDetailsTableTransportation['TypeID'] = '1';//Dr
                $accountingDetailsTableTransportation['CHILD_ID'] = '42';
                $accountingDetailsTableTransportation['GR_DEBIT'] = $this->input->post('transportationAmount');
                $accountingDetailsTableTransportation['GR_CREDIT'] = '0.00';
                $accountingDetailsTableTransportation['Reference'] = 'transportationAmount';
                $accountingDetailsTableTransportation['IsActive'] = 1;
                $accountingDetailsTableTransportation['Changed_By'] = $this->admin_id;
                $accountingDetailsTableTransportation['Changed_Date'] = $this->timestamp;
                $accountingDetailsTableTransportation['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
                $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $accountingDetailsTableTransportation, $TransportationCondition);
            }
            /*Transportation*/
            /*Supplier Payable*/
            $SupplierPayableCondition = array(
                'TypeID' => 2,
                'CHILD_ID' => 34,
                'Accounts_VoucherMst_AutoID' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
            );
            $accountingDetailsTableSupplierPayable['Accounts_VoucherMst_AutoID'] = $accountingMasterTable->Accounts_VoucherMst_AutoID;
            $accountingDetailsTableSupplierPayable['TypeID'] = '2';//Cr
            $accountingDetailsTableSupplierPayable['CHILD_ID'] = '34';
            $accountingDetailsTableSupplierPayable['GR_DEBIT'] = '0.00';
            $accountingDetailsTableSupplierPayable['GR_CREDIT'] = $this->input->post('netTotal');
            $accountingDetailsTableSupplierPayable['Reference'] = 'Supplier Payable';
            $accountingDetailsTableSupplierPayable['IsActive'] = 1;
            $accountingDetailsTableSupplierPayable['Changed_By'] = $this->admin_id;
            $accountingDetailsTableSupplierPayable['Changed_Date'] = $this->timestamp;
            $accountingDetailsTableSupplierPayable['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
            $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $accountingDetailsTableSupplierPayable, $SupplierPayableCondition);
            /*Supplier Payable*/
            if ($paymentType == 4) {
                //supplier paid amount
                /*Supplier Payable*/
                $supplierPaidAmountCondition = array(
                    'TypeID' => 1,
                    'CHILD_ID' => 34,
                    'Accounts_VoucherMst_AutoID' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                );
                $accountingDetailsTablesupplierPaidAmount['Accounts_VoucherMst_AutoID'] = $accountingMasterTable->Accounts_VoucherMst_AutoID;
                $accountingDetailsTablesupplierPaidAmount['TypeID'] = '1';//Dr
                $accountingDetailsTablesupplierPaidAmount['CHILD_ID'] = '34';
                $accountingDetailsTablesupplierPaidAmount['GR_DEBIT'] = $this->input->post('thisAllotment');
                $accountingDetailsTablesupplierPaidAmount['GR_CREDIT'] = '0.00';
                $accountingDetailsTablesupplierPaidAmount['Reference'] = 'Supplier paid amount';
                $accountingDetailsTablesupplierPaidAmount['IsActive'] = 1;
                $accountingDetailsTablesupplierPaidAmount['Changed_By'] = $this->admin_id;
                $accountingDetailsTablesupplierPaidAmount['Changed_Date'] = $this->timestamp;
                $accountingDetailsTablesupplierPaidAmount['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
                $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $accountingDetailsTablesupplierPaidAmount, $supplierPaidAmountCondition);
                //supplier paid amount
                /*Cash in hand*/
                $CashInHandCondition = array(
                    'TypeID' => 2,
                    'CHILD_ID' => $this->input->post('accountCrPartial'),
                    'Accounts_VoucherMst_AutoID' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                );
                $accountingDetailsTableCashInHand['Accounts_VoucherMst_AutoID'] = $accountingMasterTable->Accounts_VoucherMst_AutoID;
                $accountingDetailsTableCashInHand['TypeID'] = '2';//Dr
                $accountingDetailsTableCashInHand['CHILD_ID'] = $this->input->post('accountCrPartial');
                $accountingDetailsTableCashInHand['GR_DEBIT'] = '0.00';
                $accountingDetailsTableCashInHand['GR_CREDIT'] = $this->input->post('thisAllotment');
                $accountingDetailsTableCashInHand['Reference'] = '';
                $accountingDetailsTableCashInHand['IsActive'] = 1;
                $accountingDetailsTableCashInHand['Changed_By'] = $this->admin_id;
                $accountingDetailsTableCashInHand['Changed_Date'] = $this->timestamp;
                $accountingDetailsTableCashInHand['date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
                $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $accountingDetailsTableCashInHand, $CashInHandCondition);
            }
            $inactive['IsActive'] = 0;
            $this->db->where('history_id', $accountingMasterTable->Accounts_VoucherMst_AutoID);
            $this->db->where('invoice_id', $this->invoice_id);
            $this->db->where('invoice_type', 1);
            $this->db->update('client_vendor_ledger', $inactive);
            /*client_vendor_ledger table data insert*/
            $client_vendor_ledgerCondition = array(
                'invoice_type' => 1,
                'invoice_id' => $this->invoice_id,
                'history_id' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                'Accounts_VoucherType_AutoID' => '5'
            );
            $supp = array(
                'ledger_type' => 2,
                'trans_type' => 'purchase',
                'history_id' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                'trans_type' => $this->input->post('voucherid'),
                'client_vendor_id' => $this->input->post('supplierID'),
                'invoice_id' => $this->invoice_id,
                'invoice_type' => '1',
                'IsActive' => '1',
                'Accounts_VoucherType_AutoID' => '5',
                'updated_by' => $this->admin_id,
                'dist_id' => $this->dist_id,
                'amount' => $this->input->post('netTotal'),
                'dr' => $this->input->post('netTotal'),
                'date' => $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '',
                'paymentType' => 'Purchase Voucher'
            );
            $this->Common_model->save_and_check('client_vendor_ledger', $supp, $client_vendor_ledgerCondition);
            if ($paymentType == 4) {
                $client_vendor_ledgerCondition2 = array(
                    'invoice_type' => 1,
                    'invoice_id' => $this->invoice_id,
                    'history_id' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                    'Accounts_VoucherType_AutoID' => '2'
                );
                $supp1 = array(
                    'ledger_type' => 2,
                    'dist_id' => $this->dist_id,
                    'trans_type' => $this->input->post('voucherid'),
                    'history_id' => $accountingMasterTable->Accounts_VoucherMst_AutoID,
                    'client_vendor_id' => $this->input->post('supplierID'),
                    'invoice_id' => $this->invoice_id,
                    'invoice_type' => '2',
                    'IsActive' => '1',
                    'Accounts_VoucherType_AutoID' => '2',
                    'amount' => $this->input->post('thisAllotment'),
                    'cr' => $this->input->post('thisAllotment'),
                    'date' => $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '',
                    'updated_by' => $this->admin_id,
                    'paymentType' => 'Supplier Payment',
                );
                $this->Common_model->save_and_check('client_vendor_ledger', $supp1, $client_vendor_ledgerCondition2);
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = 'Purchase Invoice ' . ' ' . $this->config->item("update_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/purchases_lpg_edit/' . $this->invoice_id));
            } else {
                $msg = 'Purchase Invoice ' . ' ' . $this->config->item("update_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/viewPurchasesCylinder/' . $this->invoice_id));
            }
        }
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $ivnoiceId);
        $stockList = $this->Common_model->get_purchase_product_detaild2($ivnoiceId);
        foreach ($stockList as $ind => $element) {
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_invoice_id'] = $element->purchase_invoice_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_details_id'] = $element->purchase_details_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['category_id'] = $element->category_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['category'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['is_package'] = $element->is_package;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_id'] = $element->product_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['package_id'] = $element->package_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['productName'] = $element->productName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_code'] = $element->product_code;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['title'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['brandName'] = $element->brandName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['quantity'] = $element->quantity;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unit_price'] = $element->unit_price;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['tt_returnable_quantity'] = $element->tt_returnable_quantity;
            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'purchase_return_id' => $element->purchase_return_id,
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
        $data['stockListEdit'] = $result;
        /*echo "<pre>";
        print_r($data['stockListEdit']);
        print_r($stockList);
        exit;*/
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['cylinserOut'] = $this->Sales_Model->getCylinderInOutResult2($this->dist_id, $ivnoiceId, 23);
        $data['cylinderIn'] = $this->Sales_Model->getCylinderInOutResult($this->dist_id, $ivnoiceId, 24);
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);
        /*page navbar details*/
        $data['title'] = get_phrase('Purchases Edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Purchase_Invoice');
        $data['link_page_url'] = $this->project . '/purchases_lpg_add';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Invoice List');
        $data['second_link_page_url'] = $this->project . '/purchases_cylinder_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('View Invoice');
        $data['third_link_page_url'] = $this->project . '/viewPurchasesCylinder/' . $ivnoiceId;
        $data['third_link_icon'] = $this->link_icon_edit;
        /*page navbar details*/
        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $costCondition = array(
            'dist_id' => $this->dist_id,
            'parentId' => 62,
        );
        $data['costList'] = $this->Common_model->get_data_list_by_many_columns('generaldata', $costCondition);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases_lpg/purchasesEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function purchases_cylinder_list()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('Purchases Cylinder List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Purchase_Invoice');
        $data['link_page_url'] = $this->project . '/purchases_lpg_add';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases_lpg/purchases_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function viewPurchasesCylinder($purchases_id = NULL)
    {
        /*page navbar details*/
        $data['title'] = get_phrase(' Purchases View');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Purchases_Add');
        $data['link_page_url'] = $this->project . '/purchases_lpg_add';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Purchases_List');
        $data['second_link_page_url'] = $this->project . '/purchases_cylinder_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Edit_Invoice');
        $data['third_link_page_url'] = $this->project . '/purchases_lpg_edit/' . $purchases_id;
        $data['third_link_icon'] = $this->link_icon_edit;
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

        if ($this->business_type == "LPG") {
            $data['mainContent'] = $this->load->view('distributor/inventory/purchases_lpg/purchases_view', $data, true);

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        }else{
            $data['mainContent'] = $this->load->view('distributor/inventory/purchases_mobile/purchases_view', $data, true);
        }

        $this->load->view('distributor/masterTemplate', $data);
    }
    public function supPendingCheque111()
    {
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
                redirect($this->project . '/supPendingCheque', 'refresh');
            } else {
                message("Your data successfully inserted into database.");
                redirect($this->project . '/supPendingCheque', 'refresh');
            }
        }
//pending check condition
        $pendingChequeCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
            'paymentType' => 2,
            'checkStatus' => 1,
        );
        $data['title'] = '';
        /*page navbar details*/
        $data['title'] = get_phrase('Supplier Pending Cheque');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $data['supplierPendingCheck'] = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $pendingChequeCondition);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['mainContent'] = $this->load->view('distributor/inventory/report/pendingCheck', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function supPendingCheque()
    {
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
            if ($receiteInfo->due_collection_info_id != 0) {

                $condition = array(
                    'related_id' => $this->input->post('supplierid'),
                    'related_id_for' => 2,
                    'is_active' => "Y",
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);




                //come from supplier payment from
                $mrCondition = array(
                    'due_collection_info_id' => $receiteInfo->due_collection_info_id,
                    'is_active' => "Y",
                    'is_delete' => "N",
                );
                //purchase invoice that come form purchase Invoice add form payment type Bank
                $dueCollectionInvoices = $this->Common_model->get_data_list_by_many_columns('supplier_due_collection_details', $mrCondition);


                if (!empty($dueCollectionInvoices)) {
                    foreach ($dueCollectionInvoices as $a => $b) {
                        $purchaseInvoiceInfo = $this->Common_model->tableRow('purchase_invoice_info', 'purchase_invoice_id', $b->purchase_invoice_id);
                        $this->load->helper('create_payment_voucher_no');

                        $condition = array(
                            'related_id' => $b->supplier_id,
                            'related_id_for' => 2,
                            'is_active' => "Y",
                        );
                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);



                        /*ac_accounts_vouchermst*/
                        $accountingMasterTable = array();
                        $voucher_no = create_payment_voucher_no();
                        $accountingMasterTable['AccouVoucherType_AutoID'] = 2;
                        $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                        $accountingMasterTable['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                        $accountingMasterTable['BackReferenceInvoiceNo'] = $purchaseInvoiceInfo->invoice_no;
                        $accountingMasterTable['BackReferenceInvoiceID'] = $b->purchase_invoice_id;
                        $accountingMasterTable['Narration'] = 'Supplier Payment';
                        $accountingMasterTable['CompanyId'] = $this->dist_id;
                        $accountingMasterTable['BranchAutoId'] = $purchaseInvoiceInfo->branch_id;
                        $accountingMasterTable['supplier_id'] = $b->supplier_id;
                        $accountingMasterTable['IsActive'] = 1;
                        $accountingMasterTable['Created_By'] = $this->admin_id;
                        $accountingMasterTable['Created_Date'] = $this->timestamp;
                        $accountingMasterTable['for'] = 4;
                        //$accountingMasterTable['BranchAutoId'] = $branch_id;
                        $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);
                        //supplier Payables
                        $accountingDetailsTable_DR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable_DR['TypeID'] = '1';//Dr
                        $accountingDetailsTable_DR['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Supplier_Payables");//'34';
                        $accountingDetailsTable_DR['GR_DEBIT'] = $b->paid_amount;
                        $accountingDetailsTable_DR['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable_DR['Reference'] = 'Supplier Pending Cheque Recive';
                        $accountingDetailsTable_DR['IsActive'] = 1;
                        $accountingDetailsTable_DR['Created_By'] = $this->admin_id;
                        $accountingDetailsTable_DR['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable_DR['BranchAutoId'] = $purchaseInvoiceInfo->branch_id;
                        $accountingDetailsTable_DR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                        $finalDetailsArray[] = $accountingDetailsTable_DR;
                        $accountingDetailsTable_DR = array();
                        //supplier paid amount
                        /*Cash in hand*/
                        $accountingDetailsTable_CR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable_CR['TypeID'] = '2';//Dr
                        $accountingDetailsTable_CR['CHILD_ID'] = $this->input->post('accountDr');
                        $accountingDetailsTable_CR['GR_DEBIT'] = '0.00';
                        $accountingDetailsTable_CR['GR_CREDIT'] = $b->paid_amount;
                        $accountingDetailsTable_CR['Reference'] = '';
                        $accountingDetailsTable_CR['IsActive'] = 1;
                        $accountingDetailsTable_CR['Created_By'] = $this->admin_id;
                        $accountingDetailsTable_CR['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable_CR['BranchAutoId'] = $purchaseInvoiceInfo->branch_id;
                        $accountingDetailsTable_CR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                        $finalDetailsArray[] = $accountingDetailsTable_CR;
                        $accountingDetailsTable_CR = array();
                        $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                        //log_message('error', 'this is nahid error massage' . print_r($finalDetailsArray, true));
                        $finalDetailsArray = array();
                        $customerData = array(
                            'ledger_type' => 2,
                            'history_id' => $accountingVoucherId,
                            'trans_type' => $receiteInfo->receitID,
                            'paymentType' => 'Check Received',
                            'client_vendor_id' => $b->supplier_id,
                            'invoice_id' => $purchaseInvoiceInfo->purchase_invoice_id,
                            'invoice_type' => '1',
                            'Accounts_VoucherType_AutoID' => '2',
                            'amount' => $b->paid_amount,
                            'cr' => $b->paid_amount,
                            'dist_id' => $this->dist_id,
                            'BranchAutoId' => $purchaseInvoiceInfo->branch_id,
                            'date' => date('Y-m-d', strtotime($this->input->post('paymentDate')))
                        );
                        $this->db->insert('client_vendor_ledger', $customerData);
                    }
                }
            } else {
                //this is come from purchase invoice from
                $Accounts_VoucherDtl_AutoID = $receiteInfo->Accounts_VoucherDtl_AutoID;
                $purchaseInvoiceInfo = $this->Common_model->tableRow('purchase_invoice_info', 'purchase_invoice_id', $receiteInfo->invoiceID);
                $this->load->helper('create_payment_voucher_no');
                /*ac_accounts_vouchermst*/
                $voucher_no = create_payment_voucher_no();
                $accountingMasterTable['AccouVoucherType_AutoID'] = 2;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                $accountingMasterTable['BackReferenceInvoiceNo'] = $purchaseInvoiceInfo->invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $receiteInfo->invoiceID;
                $accountingMasterTable['Narration'] = 'Supplier Pending Cheque Recived';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['supplier_id'] = $receiteInfo->supplier_id;
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingMasterTable['BranchAutoId'] = $purchaseInvoiceInfo->branch_id;
                $accountingMasterTable['for'] = 4;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);
                //supplier Payables


                $condition = array(
                    'related_id' => $receiteInfo->customerid,
                    'related_id_for' => 2,
                    'is_active' => "Y",
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);


                $accountingDetailsTable_DR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable_DR['TypeID'] = '1';//Dr
                $accountingDetailsTable_DR['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Supplier_Payables");//'34';
                $accountingDetailsTable_DR['GR_DEBIT'] = $receiteInfo->totalPayment;
                $accountingDetailsTable_DR['GR_CREDIT'] = '0.00';
                $accountingDetailsTable_DR['Reference'] = 'Supplier paid amount';
                $accountingDetailsTable_DR['IsActive'] = 1;
                $accountingDetailsTable_DR['Created_By'] = $this->admin_id;
                $accountingDetailsTable_DR['Created_Date'] = $this->timestamp;
                $accountingDetailsTable_DR['BranchAutoId'] = $purchaseInvoiceInfo->branch_id;
                $accountingDetailsTable_DR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                $finalDetailsArray[] = $accountingDetailsTable_DR;
                $accountingDetailsTable = array();
                //supplier paid amount
                /*Cash in hand*/
                $accountingDetailsTable_CR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable_CR['TypeID'] = '2';//Dr
                $accountingDetailsTable_CR['CHILD_ID'] = $this->input->post('accountDr');
                $accountingDetailsTable_CR['GR_DEBIT'] = '0.00';
                $accountingDetailsTable_CR['GR_CREDIT'] = $receiteInfo->totalPayment;
                $accountingDetailsTable_CR['Reference'] = '';
                $accountingDetailsTable_CR['IsActive'] = 1;
                $accountingDetailsTable_CR['Created_By'] = $this->admin_id;
                $accountingDetailsTable_CR['Created_Date'] = $this->timestamp;
                $accountingDetailsTable_CR['BranchAutoId'] = $purchaseInvoiceInfo->branch_id;
                $accountingDetailsTable_CR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                $finalDetailsArray[] = $accountingDetailsTable_CR;
                $accountingDetailsTable = array();
                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                $customerData = array(
                    'ledger_type' => 2,
                    'history_id' => $accountingVoucherId,
                    'trans_type' => $receiteInfo->receitID,
                    'paymentType' => 'Check Received',
                    'client_vendor_id' => $clientID,
                    'invoice_id' => $purchaseInvoiceInfo->purchase_invoice_id,
                    'invoice_type' => '1',
                    'Accounts_VoucherType_AutoID' => '2',
                    'amount' => $receiteInfo->totalPayment,
                    'cr' => $receiteInfo->totalPayment,
                    'dist_id' => $this->dist_id,
                    'BranchAutoId' => $purchaseInvoiceInfo->branch_id,
                    'date' => date('Y-m-d', strtotime($this->input->post('paymentDate')))
                );
                $this->db->insert('client_vendor_ledger', $customerData);
            }
            $changeStatus['checkStatus'] = 2;
            $changeStatus['received_date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
            $this->db->where('moneyReceitid', $receiteID);
            $this->db->update('moneyreceit', $changeStatus);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = ' ' . ' ' . $this->config->item("save_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect($this->project . '/supPendingCheque', 'refresh');
            } else {
                $msg = '' . ' ' . $this->config->item("save_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect($this->project . '/supPendingCheque', 'refresh');
            }
        }
//pending check condition
        $pendingChequeCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
            'paymentType' => 2,
            'checkStatus' => 1,
        );
        $data['title'] = '';
        /*page navbar details*/
        $data['title'] = get_phrase('Supplier Pending Cheque');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $this->db->select('moneyreceit.*,bank_account_info.bank_name,bank_account_info.account_no');
        $this->db->from('moneyreceit');
        $this->db->join('bank_account_info', 'bank_account_info.bank_account_info_id=moneyreceit.bankName', 'left');
        //$this->db->join('bank_branch_info', 'bank_branch_info.bank_branch_id=moneyreceit.branchName', 'left');
        $this->db->where('moneyreceit.receiveType', 2);
        $this->db->where('moneyreceit.paymentType', 2);
        $this->db->where('moneyreceit.checkStatus', 1);
        $this->db->where('moneyreceit.dist_id', $this->dist_id);
        $sql = $this->db->get();
        $data['supplierPendingCheck'] = $sql->result();
        //$data['supplierPendingCheck'] = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $pendingChequeCondition);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $data['mainContent'] = $this->load->view('distributor/inventory/report/pendingCheck', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    /**
     * @return mixed
     */
    public function test()
    {
        $data['supplierPendingCheck'] = '';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/test', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}