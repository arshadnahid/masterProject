<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 5/27/2020
 * Time: 8:16 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class customerPaymentController extends CI_Controller
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

                    $query = $this->db->field_exists('due_collection_details_id', 'ac_accounts_vouchermst');
                    if ($query != TRUE) {
                        $this->load->dbforge();
                        $fields = array(
                            'due_collection_details_id' => array(
                                'type' => 'INT',
                                'null' => TRUE,
                                'default' => '0',
                                //'unsigned' => TRUE,
                                'after' => 'for')
                        );
                        $this->dbforge->add_column('ac_accounts_vouchermst', $fields);
                    }

                    $cus_due_collection_infoNo = $this->db->where(array('1' => 1))->count_all_results('cus_due_collection_info') + 1;
                    $ReceitVoucher = "CDR" . date("y") . date("m") . str_pad($cus_due_collection_infoNo, 4, "0", STR_PAD_LEFT);
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
                    $due_collection_info['narration'] = $this->input->post('narration');
                    $cus_due_collection_info_id = $this->Common_model->insert_data('cus_due_collection_info', $due_collection_info);


                    $for = 3;
                    $ledger_id = get_customer_supplier_product_ledger_id($this->input->post('customerid'), $for);
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
                                /*Customer Receivable  /account Receiveable  =>>25*/
                                //account Receiveable

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
                                $cus_due_collection_details_id = $this->Common_model->insert_data('cus_due_collection_details', $due_collection);
                                $due_collection = array();

                                $dataMaster['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                                $dataMaster['Accounts_Voucher_No'] = $reciveVoucherNo;
                                $dataMaster['CompanyId'] = $this->dist_id;
                                $dataMaster['BranchAutoId'] = $branch_id;
                                $dataMaster['Reference'] = 0;
                                $dataMaster['AccouVoucherType_AutoID'] = 1;
                                $dataMaster['IsActive'] = 1;
                                $dataMaster['for'] = 5;
                                $dataMaster['due_collection_details_id'] = $cus_due_collection_details_id;
                                $dataMaster['Created_By'] = $this->admin_id;
                                $dataMaster['Created_Date'] = $this->timestamp;
                                $dataMaster['customer_id'] = $this->input->post('customerid');
                                $dataMaster['BackReferenceInvoiceNo'] = $this->input->post('voucher[' . $a . ']');
                                $dataMaster['BackReferenceInvoiceID'] = $this->input->post('invoiceID[' . $a . ']');
                                $dataMaster['Narration'] = $this->input->post('narration');
                                $accounting_vouchaer_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $dataMaster);

                                $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accounting_vouchaer_id;
                                $accountingDetailsTableCustomerReceivable['TypeID'] = '2';//Cr
                                $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $ledger_id->id;//;$this->config->item("Customer_Receivable");//'25';
                                $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = '0.00';
                                $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = $this->input->post('amount[' . $a . ']');
                                $accountingDetailsTableCustomerReceivable['Reference'] = 'Customer paid amount';
                                $accountingDetailsTableCustomerReceivable['cus_due_collection_details_id'] = $cus_due_collection_details_id;
                                $accountingDetailsTableCustomerReceivable['for'] = 5;
                                $accountingDetailsTableCustomerReceivable['invoice_id'] = $this->input->post('invoiceID[' . $a . ']');
                                $accountingDetailsTableCustomerReceivable['invoice_no'] = $this->input->post('voucher[' . $a . ']');
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
                                $accountingDetailsTableCashinhand['cus_due_collection_details_id'] = $cus_due_collection_details_id;
                                $accountingDetailsTableCashinhand['for'] = 5;
                                $accountingDetailsTableCashinhand['invoice_id'] = $this->input->post('invoiceID[' . $a . ']');
                                $accountingDetailsTableCashinhand['invoice_no'] = $this->input->post('voucher[' . $a . ']');
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
                                $finalDetailsArray = array();


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
                                $due_collectionDetailsForBankTran[] = $due_collection;
                                $postedInvoiceNo[] = $this->input->post('invoiceID[' . $a . ']');;
                            }
                        }
                    }
                    $mrCondition = array(

                        'receiveType' => 1,
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
                    if (!empty($due_collectionDetailsForBankTran)) {
                        $this->db->insert_batch('cus_due_collection_details', $due_collectionDetailsForBankTran);
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
        $moneyReceitNo  = $this->db->where(array( 'receiveType' => 1))->count_all_results('moneyreceit') + 1;
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
        $data['customerList'] = $this->Inventory_Model->getPaymentDueSupplierCustomer(1, 1);
        $data['mainContent'] = $this->load->view('distributor/sales/report/customerPayment', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}