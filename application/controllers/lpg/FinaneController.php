<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FinaneController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    private $admin_name;
    public $dist_id;
    public $page_type;
    public $dataRow = array();
    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->admin_name = $this->session->userdata('username');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'Accounts';
        $this->link_icon_add = "<i class='ace-icon fa fa-plus'></i>";
        $this->link_icon_list = "<i class='ace-icon fa fa-list'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }
    public function billInvoice()
    {
        $condition = array(
            'form_id' => 29,
            'dist_id' => $this->dist_id,
        );
        /*page navbar details*/
        $data['title'] = 'Bill Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Bill Voucher Add';
        $data['link_page_url'] = $this->project . '/receiveVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['billInfo'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition, 'date', 'DESC');
        $data['mainContent'] = $this->load->view('distributor/finance/bill/billInvoice', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function billInvoice_view($invoiceId)
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['title'] = 'Bill Invoice View';
        $data['billInfo'] = $billInfo = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $invoiceId);
        if ($billInfo->customer_id):
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $billInfo->customer_id);
        elseif ($billInfo->supplier_id):
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $billInfo->supplier_id);
        else:
        endif;
        $data['ledgerInfo'] = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $invoiceId);
        $data['mainContent'] = $this->load->view('distributor/finance/bill/billView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function billInvoicePayment($billId)
    {
        if (isPostBack()) {
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('accountCr', 'Voucher Id', 'required');
            $this->form_validation->set_rules('paymentCr', 'Payment Type', 'required');
            $getVoucherInfo = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $billId);
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/billInvoicePayment/' . $billId));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 30;
                $data['dist_id'] = $this->dist_id;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['voucher_no'] = $getVoucherInfo->voucher_no;
                if (!empty($getVoucherInfo->customer_id)):
                    $data['customer_id'] = $getVoucherInfo->customer_id;
                    $data['payType'] = 2;
                elseif (!empty($getVoucherInfo->supplier_id)):
                    $data['supplier_id'] = $getVoucherInfo->supplier_id;
                    $data['payType'] = 3;
                else:
                    $data['payType'] = 1;
                    $data['miscellaneous'] = $getVoucherInfo->miscellaneous;
                endif;
                $data['credit'] = $this->input->post('paymentCr');
                $data['updated_by'] = $this->admin_id;
                $data['mainInvoiceId'] = $billId;
                $general_id = $this->Common_model->insert_data('generals', $data);
                if (!empty($getVoucherInfo->customer_id)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Bill Payment',
                        'history_id' => $general_id,
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $getVoucherInfo->customer_id,
                        'amount' => $this->input->post('paymentCr'),
                        'cr' => $this->input->post('paymentCr'),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
                if (!empty($getVoucherInfo->supplier_id)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $general_id,
                        'paymentType' => 'Bill Payment',
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $getVoucherInfo->supplier_id,
                        'amount' => $this->input->post('paymentCr'),
                        'cr' => $this->input->post('paymentCr'),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;
                $accountDr = $this->input->post('accountDr');
                /* acount payable credit */
                $dr['generals_id'] = $general_id;
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $dr['account'] = 50;
                $dr['debit'] = $this->input->post('paymentCr');
                $dr['form_id'] = 30;
                $dr['dist_id'] = $this->dist_id;
                $dr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $dr);
//credit account
                $cr['generals_id'] = $general_id;
                $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $cr['account'] = $this->input->post('accountCr');
                $cr['credit'] = $this->input->post('paymentCr');
                $cr['form_id'] = 30;
                $cr['dist_id'] = $this->dist_id;
                $cr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $cr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted");
                    redirect(site_url($this->project . '/billInvoicePayment/' . $billId));
                } else {
                    message("Your data successfully inserted into database.");
                    redirect(site_url($this->project . '/billInvoice_view/' . $billId));
                }
            }
            /* Pay account Credit */
        }
        $data['billInfo'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $billId);
        $data['ledgerInfo'] = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $billId);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['title'] = 'Bill Invoice Payment';
        $data['mainContent'] = $this->load->view('distributor/finance/bill/billPayment', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function billInvoice_edit($invoiceId)
    {
        if (is_numeric($invoiceId)) {
//is invoice id is valid?
            $validInvoiecId = $this->Sales_Model->checkInvoiceIdAndDistributor($this->dist_id, $invoiceId);
//invoice id this distributor??
            if ($validInvoiecId === FALSE) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url($this->project . '/receiveVoucher'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url($this->project . '/receiveVoucher'));
        }
        if (isPostBack()) {
            $billUser = $this->input->post('billUser');
//set some validation for input fields
            $this->form_validation->set_rules('paymentCr', 'Payment Credit', 'required');
            $this->form_validation->set_rules('accountCr', 'Payment Account', 'required');
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('billDate', 'Payment Date', 'required');
            $this->form_validation->set_rules('billVoucher', 'Voucher Id', 'required');
            $this->form_validation->set_rules('billUser', 'Payment Type', 'required');
            $this->form_validation->set_rules('accountDr[]', 'Account Debit', 'required');
            $this->form_validation->set_rules('amountDr[]', 'Amount Debit', 'required');
            if ($billUser == 1) {
                $this->form_validation->set_rules('miscellaneous', 'Miscellaneous', 'required');
            } else if ($billUser == 2) {
                $this->form_validation->set_rules('customer_id', 'Customer Id', 'required');
            } else {
                $this->form_validation->set_rules('supplier_id', 'Supplier Id', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/billInvoice_add'));
            } else {
                $this->db->trans_start();
                /* Bill General start */
                $data['form_id'] = 29;
                $data['dist_id'] = $this->dist_id;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('billDate')));
                $data['voucher_no'] = $this->input->post('billVoucher');
                $data['payType'] = $this->input->post('billUser');
                $cust = $this->input->post('customer_id');
                $supid = $this->input->post('supplier_id');
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                endif;
                if (!empty($supid)):
                    $data['supplier_id'] = $this->input->post('supplier_id');
                endif;
                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                endif;
                $data['debit'] = array_sum($this->input->post('amountDr'));
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
                $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
                $paymentGeneral = $this->Finane_Model->getPaymentId($invoiceId);
                $this->Common_model->delete_data('generals', 'generals_id', $paymentGeneral);
                $this->Common_model->delete_data('generalledger', 'generals_id', $paymentGeneral);
                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Bill Voucher',
                        'history_id' => $invoiceId,
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('billDate'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $invoiceId,
                        'paymentType' => 'Bill Voucher',
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $this->input->post('supplier_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('billDate'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;
                $accountDr = $this->input->post('accountDr');
                /* acount payable credit */
                $cr['generals_id'] = $invoiceId;
                $cr['date'] = date('Y-m-d', strtotime($this->input->post('billDate')));
                $cr['account'] = 50;
                $cr['credit'] = array_sum($this->input->post('amountDr'));
                $cr['form_id'] = 29;
                $cr['dist_id'] = $this->dist_id;
                $cr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $cr);
                $allDr = array();
                foreach ($accountDr as $key => $value) {
                    unset($cr);
                    $dr['generals_id'] = $invoiceId;
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('billDate')));
                    $dr['account'] = $this->input->post('accountDr')[$key];
                    $dr['debit'] = $this->input->post('amountDr')[$key];
                    $dr['memo'] = $this->input->post('memoDr')[$key];
                    $dr['form_id'] = 29;
                    $dr['dist_id'] = $this->dist_id;
                    $dr['updated_by'] = $this->admin_id;
                    $allDr[] = $dr;
                }
                $this->db->insert_batch('generalledger', $allDr);
                /* Bill general complete */
                /* Payment general start */
                $payment['form_id'] = 30;
                $payment['dist_id'] = $this->dist_id;
                $payment['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $payment['voucher_no'] = $this->input->post('billVoucher');
                $payment['payType'] = $this->input->post('billUser');
                $cust = $this->input->post('customer_id');
                $supid = $this->input->post('supplier_id');
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($cust)):
                    $payment['customer_id'] = $cust;
                endif;
                if (!empty($supid)):
                    $payment['supplier_id'] = $this->input->post('supplier_id');
                endif;
                if (!empty($miscellaneous)):
                    $payment['miscellaneous'] = $this->input->post('miscellaneous');
                endif;
                $payment['credit'] = $this->input->post('paymentCr');
                $payment['updated_by'] = $this->admin_id;
                $payment['mainInvoiceId'] = $invoiceId;
                $general_id = $this->Common_model->insert_data('generals', $payment);
                if (!empty($cust)):
                    $paymentCustLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Bill Payment',
                        'history_id' => $general_id,
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $cust,
                        'amount' => $this->input->post('paymentCr'),
                        'cr' => $this->input->post('paymentCr'),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $paymentCustLedger);
                endif;
                if (!empty($supid)):
                    $paymentSupLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $general_id,
                        'paymentType' => 'Bill Payment',
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $supid,
                        'amount' => $this->input->post('paymentCr'),
                        'cr' => $this->input->post('paymentCr'),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $paymentSupLedger);
                endif;
                $accountDr = $this->input->post('accountDr');
                /* acount payable credit */
                $paymentdr['generals_id'] = $general_id;
                $paymentdr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $paymentdr['account'] = 50;
                $paymentdr['debit'] = $this->input->post('paymentCr');
                $paymentdr['form_id'] = 30;
                $paymentdr['dist_id'] = $this->dist_id;
                $paymentdr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $paymentdr);
//credit account
                $paymentcr['generals_id'] = $general_id;
                $paymentcr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $paymentcr['account'] = $this->input->post('accountCr');
                $paymentcr['credit'] = $this->input->post('paymentCr');
                $paymentcr['form_id'] = 30;
                $paymentcr['dist_id'] = $this->dist_id;
                $paymentcr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $paymentcr);
                /* Payment general start */
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted");
                    redirect(site_url($this->project . '/billInvoice_add'));
                } else {
                    message("Your data successfully inserted into database.");
                    redirect(site_url($this->project . '/billInvoice_edit/' . $invoiceId));
                }
            }
        }
        $data['title'] = 'Bill Invoice Edit';
        $data['billInfo'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $invoiceId);
        $data['paymentBillInfo'] = $paymentInfo = $this->Common_model->get_single_data_by_single_column('generals', 'mainInvoiceId', $invoiceId);
        $data['ledgerInfo'] = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $invoiceId);
        $data['paymentLedgerInfo'] = $this->Finane_Model->getPaymentLedger($paymentInfo->generals_id);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['mainContent'] = $this->load->view('distributor/finance/bill/billInvoiceEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function billInvoice_add()
    {
        if (isPostBack()) {
            $billUser = $this->input->post('billUser');
//set some validation for input fields
            $this->form_validation->set_rules('billDate', 'Payment Date', 'required');
            $this->form_validation->set_rules('billVoucher', 'Voucher Id', 'required');
            $this->form_validation->set_rules('billUser', 'Payment Type', 'required');
            $this->form_validation->set_rules('accountDr[]', 'Account Debit', 'required');
            $this->form_validation->set_rules('amountDr[]', 'Amount Debit', 'required');
            if ($billUser == 1) {
                $this->form_validation->set_rules('miscellaneous', 'Miscellaneous', 'required');
            } else if ($billUser == 2) {
                $this->form_validation->set_rules('customer_id', 'Customer Id', 'required');
            } else {
                $this->form_validation->set_rules('supplier_id', 'Supplier Id', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/billInvoice_add'));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 29;
                $data['dist_id'] = $this->dist_id;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('billDate')));
                $data['voucher_no'] = $this->input->post('billVoucher');
                $data['payType'] = $this->input->post('billUser');
                $cust = $this->input->post('customer_id');
                $supid = $this->input->post('supplier_id');
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                endif;
                if (!empty($supid)):
                    $data['supplier_id'] = $this->input->post('supplier_id');
                endif;
                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                endif;
                $data['debit'] = array_sum($this->input->post('amountDr'));
                $data['updated_by'] = $this->admin_id;
                $general_id = $this->Common_model->insert_data('generals', $data);
                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Bill Voucher',
                        'history_id' => $general_id,
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('billDate'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $general_id,
                        'paymentType' => 'Bill Voucher',
                        'trans_type' => $this->input->post('billVoucher'),
                        'client_vendor_id' => $this->input->post('supplier_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('billDate'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;
                $accountDr = $this->input->post('accountDr');
                /* acount payable credit */
                $cr['generals_id'] = $general_id;
                $cr['date'] = date('Y-m-d', strtotime($this->input->post('billDate')));
                $cr['account'] = 50;
                $cr['credit'] = array_sum($this->input->post('amountDr'));
                $cr['form_id'] = 29;
                $cr['dist_id'] = $this->dist_id;
                $cr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $cr);
                $allDr = array();
                foreach ($accountDr as $key => $value) {
                    unset($cr);
                    $dr['generals_id'] = $general_id;
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('billDate')));
                    $dr['account'] = $this->input->post('accountDr')[$key];
                    $dr['debit'] = $this->input->post('amountDr')[$key];
                    $dr['memo'] = $this->input->post('memoDr')[$key];
                    $dr['form_id'] = 29;
                    $dr['dist_id'] = $this->dist_id;
                    $dr['updated_by'] = $this->admin_id;
                    $allDr[] = $dr;
                }
                $this->db->insert_batch('generalledger', $allDr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted");
                    redirect(site_url($this->project . '/billInvoice_add'));
                } else {
                    message("Your data successfully inserted into database.");
                    redirect(site_url($this->project . '/billInvoicePayment/' . $general_id));
                }
            }
            /* Pay account Credit */
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
//echo $this->db->last_query();die;
        $voucherCondition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 29,
        );
        $totalPurchases = $this->Common_model->count_all_data('generals', $voucherCondition);
        $data['voucherID'] = "BID" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/finance/bill/billInvoice_add', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function supplierOpening()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'paymentType' => 'Opening',
            'ledger_type' => '2'
        );
        $data['allBalance'] = $this->Common_model->get_data_list_by_many_columns('client_vendor_ledger', $condition);
        //dumpVar($data['allBalance']);
        $data['openingShowHide'] = $this->Inventory_Model->checkOpenigValid($this->dist_id);
        $data['title'] = get_phrase('Supplier Opening List');
        $data['page_type'] = get_phrase('Configuration');
        $data['link_page_name'] = get_phrase('Add');
        $data['link_page_url'] = $this->project . '/supplierOpeningAdd';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Import');
        $data['second_link_page_url'] = $this->project . '/supplierOpeningImport';
        $data['second_link_icon'] = "<i class='ace-icon fa fa-upload'></i>";
        $data['mainContent'] = $this->load->view('distributor/finance/setup/supOpe', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function customerOpneingEdit($editId)
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'paymentType' => 'Opening',
            'ledger_type' => '1',
            'client_vendor_id' => $editId,
        );
        $data['openingShowHide'] = $this->Inventory_Model->checkOpenigValid($this->dist_id);
        $data['customerEditBalance'] = $this->Common_model->get_single_data_by_single_column('client_vendor_ledger', $condition);
        $data['title'] = get_phrase('Customer Opening List');
        $data['page_type'] = get_phrase('Configuration');
        $data['mainContent'] = $this->load->view('distributor/finance/setup/cusOpeEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function customerOpneing()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'paymentType' => 'Opening',
            'ledger_type' => '1'
        );
        $data['openingShowHide'] = $this->Inventory_Model->checkOpenigValid($this->dist_id);
        $data['allBalance'] = $this->Common_model->get_data_list_by_many_columns('client_vendor_ledger', $condition);
        /*page navbar details*/
        $data['title'] = get_phrase('Customer Opening List');
        $data['page_type'] = get_phrase('Configuration');
        $data['link_page_name'] = get_phrase('Customer Opening Add');
        $data['link_page_url'] = $this->project . '/customerOpneingAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Import');
        $data['second_link_page_url'] = $this->project . '/customerOpeningImport';
        $data['second_link_icon'] = $this->link_icon_list;
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/setup/cusOpe', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function supplierOpeningImport()
    {
        if (isPostBack()) {
            if (!empty($_FILES['supplierOpening']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('supplierOpening');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['supplierOpening']['tmp_name'];
                $importFile = fopen($file, "r");
                $this->db->trans_start();
                $row = 0;
                $storeData = array();
                $allSup = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        $supplierExits = $this->Finane_Model->checkSupplierExits($readRowData[1], $this->dist_id);
                        unset($stock);
                        if ($supplierExits === true):
//check empty or not
                            if (!empty($readRowData[1]) && !empty($readRowData[4]) && is_numeric($readRowData[4])):
//check numeric or string
                                $data = array();
                                $opening_balanceTable = array();
                                $data['trans_type'] = 'SO' . mt_rand(100000, 999999);
                                $data['date'] = date('Y-m-d');
                                $data['ledger_type'] = 2;
                                $data['dist_id'] = $this->dist_id;
                                $data['amount'] = $readRowData[4];
                                $data['date'] = date('Y-m-d');
                                $data['dr'] = $readRowData[4];
                                $data['paymentType'] = 'Opening';
                                $data['BranchAutoId'] = 1;
                                $data['client_vendor_id'] = $readRowData[1];
                                $allSup[] = $data;
                                $condition = array(
                                    'related_id' => $readRowData[1],
                                    'related_id_for' => 2,
                                    'is_active' => "Y",
                                );
                                $dr_amount=0;
                                $cr_amount=0;
                                if($readRowData[4]<0){
                                    $dr_amount=(-1*$readRowData[4]);
                                }else{
                                    $cr_amount=$readRowData[4];
                                }
                                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                                $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                                $opening_balanceTable['debit'] = $dr_amount;
                                $opening_balanceTable['credit'] = $cr_amount;
                                $opening_balanceTable['date'] = date('Y-m-d');
                                $finalDetailsArray[] = $opening_balanceTable;
                            endif;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($finalDetailsArray) ):
                    $this->db->insert_batch('opening_balance', $finalDetailsArray);
                endif;
                if (!empty($allSup)):
                    $this->db->insert_batch('client_vendor_ledger', $allSup);
                endif;
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your csv file not inserted.please check csv file properly.";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/supplierOpeningImport'));
                } else {
                    $msg = "Your csv file successfully inserted into database.";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/supplierOpening'));
                }
            endif;
        }
        $condition = array(
            'dist_id' => $this->dist_id,
            'paymentType' => 'Opening',
            'ledger_type' => '1'
        );
        /*page navbar details*/
        $data['title'] = get_phrase('Supplier Opening Import');
        $data['page_type'] = get_phrase('Configuration');
        $data['link_page_name'] = get_phrase('Supplier Opening Add');
        $data['link_page_url'] = $this->project . '/supplierOpeningAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['therd_link_page_name'] = get_phrase('Customer Opening Import');
        $data['therd_link_page_url'] = $this->project . '/customerOpeningImport';
        $data['therd_link_icon'] = '<i class="fa fa-upload"></i>';
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/setup/supplierOpeningAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function getImportSupplierList()
    {
        $data['customerList'] = $supplierList = $this->Finane_Model->getImportSupplier($this->dist_id);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=importSupplierList.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('#', 'Supplier ID(Not Change)', 'Supplier Code(Not Change)', 'Supplier Name(Not Change)', 'Payable *'));
        foreach ($supplierList as $key => $eachSupplier):
            fputcsv($output, $eachSupplier);
        endforeach;
        fclose($output);
    }
    function getImportCustomerList()
    {
        $data['customerList'] = $customerList = $this->Finane_Model->getImportCustomer($this->dist_id);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=importCustomerList.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('#', 'Customer ID(Not Change)', 'Customer Code(Not Change)', 'Customer Name(Not Change)', 'Receivable *'));
        foreach ($customerList as $key => $eachCustomer):
            fputcsv($output, $eachCustomer);
        endforeach;
        fclose($output);
    }
    function deleteCustomerOpening($ledgerId)
    {
        $this->Common_model->delete_data('client_vendor_ledger', 'ledger_id', $ledgerId);
        message("Your customer opening successfully deleted.");
        redirect(site_url($this->project . '/customerOpneing'));
    }
    function deleteSupplierOpening($ledgerId)
    {
        $this->Common_model->delete_data('client_vendor_ledger', 'ledger_id', $ledgerId);
        message("Your supplier opening successfully deleted.");
        redirect(site_url($this->project . '/supplierOpening'));
    }
    function allSupCusOpeDelete($type)
    {
        $conditon = array(
            'dist_id' => $this->dist_id,
            'ledger_type' => $type,
        );
        $result = $this->Common_model->delete_data_with_condition('client_vendor_ledger', $conditon);
        if (!empty($result)) {
            if ($type == 1) {
                message("Your customer opening successfully deleted.");
                redirect(site_url($this->project . '/customerOpneing'));
            } else {
                message("Your supplier opening successfully deleted.");
                redirect(site_url($this->project . '/supplierOpening'));
            }
        } else {
            if ($type == 1) {
                exception("You have made no change to deleted.");
                redirect(site_url($this->project . '/customerOpneing'));
            } else {
                exception("You have made no change to deleted.");
                redirect(site_url($this->project . '/supplierOpening'));
            }
        }
    }
    function customerOpeningImport_backUp()
    {
        if (isPostBack()) {
            if (!empty($_FILES['customerOpening']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = 'Customer_Opening_By_' . $this->admin_name . '_' . $this->admin_id . date("Y-m-d");
                $this->load->library('upload');
                //$this->load->helper('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('customerOpening');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['customerOpening']['tmp_name'];
                $importFile = fopen($file, "r");
                $this->db->trans_start();
                $row = 0;
                $storeData = array();
                $allCus = array();
                $insertDataOpeningAccount = array();
                $customer_opening_date = $this->input->post('customer_opening_date');
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        $customerExits = $this->Finane_Model->checkCustomerExits($readRowData[1], $this->dist_id);
                        unset($stock);
                        if ($customerExits === true):
//check empty or not
                            if (!empty($readRowData[1]) && !empty($readRowData[4]) && is_numeric($readRowData[4])):
//check numeric or string
                                $data = array();
                                $dataAccountOpen = array();
                                $data['trans_type'] = 'CO' . mt_rand(100000, 999999);
                                $data['date'] = $customer_opening_date;
                                $data['ledger_type'] = 1;
                                $data['dist_id'] = $this->dist_id;
                                $data['amount'] = $readRowData[4];
                                $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                                $data['dr'] = $readRowData[4];
                                $data['paymentType'] = 'Opening';
                                $data['BranchAutoId'] = 1;
                                $data['client_vendor_id'] = $readRowData[1];
                                $allCus[] = $data;
                                $ac_account_ledger_coa_info = get_customer_supplier_product_ledger_id($readRowData[1], 3);
                                $customer_ledger_id = $ac_account_ledger_coa_info->id;
                                $dataAccountOpen['account'] = $customer_ledger_id;
                                //inventory account head not insert opening balance.its come from stock table as a price.
                                $credit=0;
                                $debit=0;
                                if($readRowData[4]<0){
                                    $credit=(-1*$readRowData[4]);
                                }else{
                                    $debit=(1*$readRowData[4]);
                                }
                                $dataAccountOpen['debit'] = $debit;
                                $dataAccountOpen['credit'] = $credit;
                                $dataAccountOpen['updated_by'] = $this->admin_id;
                                $insertData[] = $data;
                                $insertDataOpeningAccount[] = $dataAccountOpen;
                            endif;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($allCus)):
                    $this->db->insert_batch('client_vendor_ledger', $allCus);
                endif;
                if (!empty($insertDataOpeningAccount)):
                    $this->db->insert_batch('opening_balance', $insertDataOpeningAccount);
                endif;
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {

                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/customerOpeningImport/'));

                    /*notification("Your csv file not inserted.please check csv file properly.");
                    redirect(site_url($this->project . '/'));*/
                } else {
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/customerOpneing'));
                    /* message("Your csv file successfully inserted into database.");
                     redirect(site_url($this->project . '/customerOpneing'));*/
                }
            endif;
        }
        $condition = array(
            'dist_id' => $this->dist_id,
            'paymentType' => 'Opening',
            'ledger_type' => '1'
        );
        /*page navbar details*/
        $data['title'] = 'Customer Opening Import';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Customer Opening Add';
        $data['link_page_url'] = $this->project . '/customerOpneingAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/setup/customerOpeningAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function customerOpeningImport()
    {
        if (isPostBack()) {
            $this->db->trans_start();
            if (!empty($_FILES['customerOpening']['name'])) {
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = 'Customer_Opening_By_' . $this->admin_name . '_' . $this->admin_id . date("Y-m-d");
                $this->load->library('upload');
                //$this->load->helper('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('customerOpening');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['customerOpening']['tmp_name'];
                $importFile = fopen($file, "r");

                $row = 0;
                $storeData = array();
                $allCus = array();
                $insertDataOpeningAccount = array();
                $customer_opening_date = $this->input->post('customer_opening_date');
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0){
                        $customerExits = $this->Finane_Model->checkCustomerExits($readRowData[1], $this->dist_id);
                        unset($stock);
                        if ($customerExits === true){

                            if (!empty($readRowData[1]) && !empty($readRowData[4]) && is_numeric($readRowData[4])){

                                $data = array();
                                $dataAccountOpen = array();
                                $data['trans_type'] = 'CO' . mt_rand(100000, 999999);
                                $data['date'] = $customer_opening_date;
                                $data['ledger_type'] = 1;
                                $data['dist_id'] = $this->dist_id;
                                $data['amount'] = $readRowData[4];
                                $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                                $data['dr'] = $readRowData[4];
                                $data['paymentType'] = 'Opening';
                                $data['BranchAutoId'] = 1;
                                $data['client_vendor_id'] = $readRowData[1];
                                $allCus[] = $data;
                                $ac_account_ledger_coa_info = get_customer_supplier_product_ledger_id($readRowData[1], 3);
                                $customer_ledger_id = $ac_account_ledger_coa_info->id;
                                $dataAccountOpen['account'] = $customer_ledger_id;
                                //inventory account head not insert opening balance.its come from stock table as a price.
                                $credit=0;
                                $debit=0;
                                if($readRowData[4]<0){
                                    $credit=(-1*$readRowData[4]);
                                }else{
                                    $debit=(1*$readRowData[4]);
                                }
                                $dataAccountOpen['debit'] = $debit;
                                $dataAccountOpen['credit'] = $credit;
                                $dataAccountOpen['updated_by'] = $this->admin_id;
                                $insertData[] = $data;
                                $insertDataOpeningAccount[] = $dataAccountOpen;
                            }
                        }
                    }
                    $row++;
                }
            }


            if (!empty($_FILES['customerOpeningExcel']['name'])) {


                $this->load->library('excel');
                $this->load->helper('file');
                $file = $_FILES['customerOpeningExcel']['tmp_name'];
                $object = PHPExcel_IOFactory::load($file);
                $allCus = array();
                $insertDataOpeningAccount = array();
                $customer_opening_date = $this->input->post('customer_opening_date');
                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $this->db->select("*");
                        $this->db->from("customer");
                        $this->db->where('customer_id', $worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $this->db->where('customerID', $worksheet->getCellByColumnAndRow(2, $row)->getValue());

                        $exits = $this->db->get()->row();
                        if (!empty($exits)){
                            $reaciveable = $worksheet->getCellByColumnAndRow(4, $row)->getValue();



                            if (!empty($reaciveable)){
                                //check numeric or string
                                if (is_numeric($reaciveable)){
                                    $data = array();
                                    $dataAccountOpen = array();
                                    $data['trans_type'] = 'CO' . mt_rand(100000, 999999);
                                    $data['date'] = $customer_opening_date;
                                    $data['ledger_type'] = 1;
                                    $data['dist_id'] = $this->dist_id;
                                    $data['amount'] = $readRowData[4];
                                    $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                                    $data['dr'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                                    $data['paymentType'] = 'Opening';
                                    $data['BranchAutoId'] = 1;
                                    $data['client_vendor_id'] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                    $allCus[] = $data;
                                    $ac_account_ledger_coa_info = get_customer_supplier_product_ledger_id($worksheet->getCellByColumnAndRow(1, $row)->getValue(), 3);
                                    $customer_ledger_id = $ac_account_ledger_coa_info->id;
                                    $dataAccountOpen['account'] = $customer_ledger_id;
                                    //inventory account head not insert opening balance.its come from stock table as a price.
                                    $credit=0;
                                    $debit=0;
                                    if($worksheet->getCellByColumnAndRow(4, $row)->getValue()<0){
                                        $credit=(-1*$worksheet->getCellByColumnAndRow(4, $row)->getValue());
                                    }else{
                                        $debit=(1*$worksheet->getCellByColumnAndRow(4, $row)->getValue());
                                    }
                                    $dataAccountOpen['debit'] = $debit;
                                    $dataAccountOpen['credit'] = $credit;
                                    $dataAccountOpen['updated_by'] = $this->admin_id;
                                    $insertData[] = $data;
                                    $insertDataOpeningAccount[] = $dataAccountOpen;

                                }
                            }
                        }
                    }
                }
            }



            if (!empty($allCus)){
                $this->db->insert_batch('client_vendor_ledger', $allCus);
            }

            if (!empty($insertDataOpeningAccount)){

                $this->db->insert_batch('opening_balance', $insertDataOpeningAccount);
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {

                $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/customerOpeningImport/'));

                /*notification("Your csv file not inserted.please check csv file properly.");
                redirect(site_url($this->project . '/'));*/
            } else {
                $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/customerOpneing'));
                /* message("Your csv file successfully inserted into database.");
                 redirect(site_url($this->project . '/customerOpneing'));*/
            }

        }
        $condition = array(
            'dist_id' => $this->dist_id,
            'paymentType' => 'Opening',
            'ledger_type' => '1'
        );
        /*page navbar details*/
        $data['title'] = 'Customer Opening Import';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Customer Opening Add';
        $data['link_page_url'] = $this->project . '/customerOpneingAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/setup/customerOpeningAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public  function getImportCustomerListExcel(){

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


        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sl');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Customer ID(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Customer Code(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Customer Name(Not Change)');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Receivable *');



        $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($style_header);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);


        $row = 2;
        $customerList = $this->Finane_Model->getImportCustomer($this->dist_id);

        foreach ($customerList as $value)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $value['sl']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $value['customer_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $value['customerID']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $value['customerName']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, '');

            $row++;
        }

        header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
        header('Content-Disposition: attachment;filename="importCustomerOpening.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
    }






//product
    function supplierOpeningAdd()
    {
        if (isPostBack()) {
            $this->db->trans_start();
            $supID = $this->input->post('suplierID');
            $debit = $this->input->post('debit');
            $credit = $this->input->post('credit');
            foreach ($supID as $key => $eachInfo):
                $condition = array(
                    'ledger_type' => 2,
                    'client_vendor_id' => $supID[$key],
                    'paymentType' => 'Opening',
                );
                $alReadyExits = $this->Common_model->get_single_data_by_many_columns('client_vendor_ledger', $condition);
                if (empty($alReadyExits)):
                    if (!empty($debit[$key])):
                        $allAmount = $debit[$key];
                    endif;
                    $data['trans_type'] = $this->input->post('voucherid');
                    $data['ledger_type'] = 2;
                    $data['dist_id'] = $this->dist_id;
                    $data['amount'] = $allAmount;
                    $data['dr'] = $debit[$key];
                    $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                    $data['paymentType'] = 'Opening';
                    $data['client_vendor_id'] = $supID[$key];
                    $this->Common_model->insert_data('client_vendor_ledger', $data);

                    $condition = array(
                        'related_id' => $supID[$key],
                        'related_id_for' => 2,
                        'is_active' => "Y",
                    );
                    $dr_amount=0;
                    $cr_amount=0;
                    if($allAmount <0){
                        $dr_amount=(-1*$allAmount);
                    }else{
                        $cr_amount=$allAmount;
                    }
                    $opening_balanceTable=array();
                    $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                    $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                    $opening_balanceTable['debit'] = $dr_amount;
                    $opening_balanceTable['credit'] = $cr_amount;
                    $opening_balanceTable['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                    $finalDetailsArray[] = $opening_balanceTable;




                endif;
            endforeach;

            if (!empty($finalDetailsArray) ):
                $this->db->insert_batch('opening_balance', $finalDetailsArray);
            endif;


            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = $this->config->item("error_success_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/supplierOpeningAdd'));
            } else {
                $msg = $this->config->item("save_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/supplierOpening'));
            }


        }
        $data['supList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['title'] = 'Supplier Opening Add';
        $data['mainContent'] = $this->load->view('distributor/finance/setup/supliOpneAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function customerOpneingAdd()
    {
        if (isPostBack()) {
            $cusID = $this->input->post('suplierID');
            $debit = $this->input->post('debit');
            $allCus = array();
            foreach ($cusID as $key => $eachInfo):
                unset($data);
                $condition = array(
                    'ledger_type' => 1,
                    'client_vendor_id' => $cusID[$key],
                    'paymentType' => 'Opening',
                );
                $alReadyExits = $this->Common_model->get_single_data_by_many_columns('client_vendor_ledger', $condition);
                if (empty($alReadyExits)):
                    if (!empty($debit[$key])):
                        $allAmount = $debit[$key];
                    endif;
                    $data['trans_type'] = $this->input->post('voucherid');
                    $data['ledger_type'] = 1;
                    $data['dist_id'] = $this->dist_id;
                    $data['amount'] = $allAmount;
                    $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                    $data['dr'] = $debit[$key];
//$data['cr'] = $credit[$key];
                    $data['paymentType'] = 'Opening';
                    $data['client_vendor_id'] = $cusID[$key];


                    $allCus[] = $data;
                    $condition = array(
                        'related_id' => $cusID[$key],
                        'related_id_for' => 3,
                        'is_active' => "Y",
                    );
                    $opening_balanceTable=array();
                    $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                    $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                    $opening_balanceTable['debit'] = $allAmount;
                    $opening_balanceTable['credit'] = 0;
                    $opening_balanceTable['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                    $finalDetailsArray[] = $opening_balanceTable;
                endif;
            endforeach;
            if (!empty($finalDetailsArray) ):
                $this->db->insert_batch('opening_balance', $finalDetailsArray);
            endif;
//$this->Common_model->insert_data('client_vendor_ledger', $data);
            if (!empty($allCus)):
                $this->db->insert_batch('client_vendor_ledger', $allCus);
                $msg = $this->config->item("save_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/customerOpneing'));
            else:
                $msg = $this->config->item("save_error_message");
                $this->session->set_flashdata('error', $msg);;
                redirect(site_url($this->project . '/customerOpneing'));
            endif;
        }
        $data['cusList'] = $this->Common_model->get_data_list_by_single_column('customer', 'dist_id', $this->dist_id);
        /*page navbar details*/
        $data['title'] = 'Customer Opening Add';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Customer Opening Import';
        $data['link_page_url'] = $this->project . '/customerOpeningImport';
        $data['link_icon'] = "<i class='fa fa-upload'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/setup/customerOpeAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function customerLedger_export_excel()
    {
        $file = 'Customer Ledger_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $from = $data['fromdate'] = $_SESSION['start_date'];
        $to = $data['todate'] = $_SESSION['end_date'];
        $data['customerList'] = $this->Common_model->get_data_list_by_single_column('customer', 'dist_id', $this->dist_id, 'customerName', 'ASC');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/customerLedger_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    public function detailsLedger($parentid)
    {
    }
    public function supplierLedger()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);
        $data['title'] = get_phrase('Supplier_Ledger');
        $data['page_type'] = get_phrase($this->page_type);
        $data['pageTitle'] = get_phrase('Supplier_Ledger');
        $data['mainContent'] = $this->load->view('distributor/finance/report/supplierLedger', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function supplierLedger_export_excel()
    {
        $file = 'Supplier Ledger_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $supplierId = $data['supplierId'] = $_SESSION['supplierId'];
        $from = $data['fromdate'] = $_SESSION['start_date'];
        $to = $data['todate'] = $_SESSION['end_date'];
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['supplierList'] = $this->Common_model->get_data_list_by_single_column('supplier', 'dist_id', $this->dist_id, 'supName', 'ASC');
        $this->load->view('excel_report/supplierLedger_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    public function cashFlow()
    {
        $data['title'] = 'Cash Flow';
        $data['mainContent'] = $this->load->view('distributor/finance/report/cashflow', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function cashBook_export_excel()
    {
        $file = 'Cash Book_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $start_date = $data['fromdate'] = $_SESSION['start_date'];
        $end_date = $data['todate'] = $_SESSION['end_date'];
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/cashBook_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    /*public function bankBook()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['title'] = 'Bank Book';
        $data['mainContent'] = $this->load->view('distributor/finance/report/bankBook', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }*/
    public function bankBook_export_excel()
    {
        $file = 'Bank Book_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $bankAccount = $data['bankAccount'] = $_SESSION['bankAccount'];
        $start_date = $data['fromdate'] = $_SESSION['start_date'];
        $end_date = $data['todate'] = $_SESSION['end_date'];
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/bankBook_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    public function trialBalance()
    {
        //echo "LL";exit;
        $condition = array(
            'status' => 1,
        );
        $this->load->library('Site_library', '', 'site_library');
        $chartList = $this->Common_model->get_data_list_by_many_columns_array('ac_account_ledger_coa', $condition);
        $result = $this->site_library->rows_to_tree($chartList, 'id', 'parent_id');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Trial Balance';
        $data['assetList'] = $this->tree_recursive(0, $result[1], "children", 0, false);
        $data['liabilityList'] = $this->tree_recursive(0, $result[2], "children", 0, false);
        $data['income'] = $this->tree_recursive(0, $result[3], "children", 0, false);
        $data['expense'] = $this->tree_recursive(0, $result[4], "children", 0, false);
        //dumpVar($data['expense']);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        /*page navbar details*/
        $data['title'] = 'Trial Balance';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Trial Balance List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/report/newTrialBalance', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function tree_recursive($k = 0, $tree_array, $children_field, $recursionDepth = 0, $maxDepth = false)
    {
        $final_array = array();
        $labelOneDr = 0;
        $labelOneCr = 0;
        $c = 1;
        $d = 1;
        $e = 1;
        $f = 1;
        $g = 1;
        $h = 1;
        $i = 1;
        $tree_array['node']['label'] = 0;
        $rowC['node']['label_for'] = $c;
        array_push($final_array, $tree_array['node']);
        if (!empty($tree_array['children'])) {
            foreach ($tree_array['children'] as $idC => $rowC) {
                $rowC['node']['label'] = 1;
                $rowC['node']['label_for'] = $c . '.' . $d;
                array_push($final_array, $rowC['node']);
                if (!empty($rowC['children'])) {
                    foreach ($rowC['children'] as $idD => $rowD) {
                        $rowD['node']['label'] = 2;
                        $rowD['node']['label_for'] = $c . '.' . $d . '.' . $e;
                        array_push($final_array, $rowD['node']);
                        if (!empty($rowD['children'])) {
                            foreach ($rowD['children'] as $idE => $rowE) {
                                $rowE['node']['label'] = 3;
                                $rowE['node']['label_for'] = $c . '.' . $d . '.' . $e . '.' . $f;
                                array_push($final_array, $rowE['node']);
                                if (!empty($rowE['children'])) {
                                    foreach ($rowE['children'] as $idF => $rowF) {
                                        $rowF['node']['label_for'] = $c . '.' . $d . '.' . $e . '.' . $f . '.' . $g;
                                        $rowF['node']['label'] = 4;
                                        array_push($final_array, $rowF['node']);
                                        if (!empty($rowF['children'])) {
                                            foreach ($rowF['children'] as $idG => $rowG) {
                                                $rowG['node']['label'] = 5;
                                                $rowG['node']['label_for'] = $c . '.' . $d . '.' . $e . '.' . $f . '.' . $g . '.' . $h;
                                                array_push($final_array, $rowG['node']);
                                                $h = $h + 1;
                                            }
                                        }
                                        $g = $g + 1;
                                    }
                                }
                                $f = $f + 1;
                            }
                        }
                        $e = $e + 1;
                    }
                }
                $labelOneDr = $labelOneDr + $labelOneDr;
                $labelOneCr = $labelOneCr + $labelOneCr;
                $d = $d + 1;
            }
        }
        return $final_array;
    }
    public
    function trialBalance_export_excel()
    {
        $file = 'Trial Balanc_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['assetList'] = $this->Common_model->getAccountListByRoodId(1);
        $data['liabilityList'] = $this->Common_model->getAccountListByRoodId(2);
        $data['income'] = $this->Common_model->getAccountListByRoodId(3);
        $data['expense'] = $this->Common_model->getAccountListByRoodId(4);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $start_date = $data['fromdate'] = $_SESSION['start_date'];
        $end_date = $data['todate'] = $_SESSION['end_date'];
        $this->load->view('excel_report/trialBalance_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    public
    function balanceSheet()
    {
        $condition = array(
            'status' => 1,
        );
        $this->load->library('Site_library', '', 'site_library');
        $chartList = $this->Common_model->get_data_list_by_many_columns_array('ac_account_ledger_coa', $condition);
        $result = $this->site_library->rows_to_tree($chartList, 'id', 'parent_id');
        $data['assetList'] = $result[1];
        //$data['liabilityList'] = $this->Common_model->getAccountListByRoodId(2);
        $data['liabilityList'] = $result[2];;
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Balance Sheet';
        $data['expense'] = $this->Common_model->getAccountListByRoodId(4);
        $data['title'] = 'Balance Sheet';
        $data['income'] = $this->Common_model->getAccountListByRoodId(3);
        $data['mainContent'] = $this->load->view('distributor/finance/report/balanceSheetNew', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function balanceSheet_export_excel()
    {
        $file = 'Balance Sheet_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $to_date = $data['todate'] = $_SESSION['to_date'];
        $data['assetList'] = $this->Common_model->getAccountListByRoodId(1);
        $data['liabilityList'] = $this->Common_model->getAccountListByRoodId(2);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/balanceSheet_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    public
    function incomeStetement()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Income Statement';
        $data['expense'] = $this->Common_model->getAccountListByRoodId(4);
        $data['income'] = $this->Common_model->getAccountListByRoodId(3);
        /*page navbar details*/
        $data['title'] = 'Income Statement';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Income Statement List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/report/incomeStatement', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function incomeStatement_export_excel()
    {
        $file = 'Income Statement_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $account = $data['account'] = $_SESSION['account'];
        $start_date = $data['fromdate'] = $_SESSION['start_date'];
        $end_date = $data['todate'] = $_SESSION['end_date'];
//$dist_id = $data['dist_id'] = $_SESSION['dist_id'];
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $this->load->view('excel_report/incomeStatement_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    public
    function getPayUserList()
    {

        $payid = $this->input->post('payid');
        $condition = array(
            //'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['payType'] = $payid;
        if ($payid == 1) {
            return $this->load->view('distributor/ajax/payList', $data);
        } elseif ($payid == 2) {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('customer', $condition);



            return $this->load->view('distributor/ajax/payList', $data);
        } else {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('supplier', $condition);
            return $this->load->view('distributor/ajax/payList', $data);
        }
    }
    public
    function getPayUserListForUpdate()
    {
        $payid = $this->input->post('payid');
        $data['userId'] = $this->input->post('payUserId');
        $condition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['payType'] = $payid;
        if ($payid == 1) {
            return $this->load->view('distributor/ajax/payList', $data);
        } elseif ($payid == 2) {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('customer', $condition);
            return $this->load->view('distributor/ajax/payList', $data);
        } else {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('supplier', $condition);
            return $this->load->view('distributor/ajax/payList', $data);
        }
    }
    public
    function getPayUserList2()
    {
        $payid = $this->input->post('payid');
        $data['userId'] = $this->input->post('searchId');
        $condition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['payType'] = $payid;
        if ($payid == 1) {
            return $this->load->view('distributor/ajax/paylist2', $data);
        } elseif ($payid == 2) {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('customer', $condition);
            return $this->load->view('distributor/ajax/paylist2', $data);
        } else {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('supplier', $condition);
            return $this->load->view('distributor/ajax/paylist2', $data);
        }
    }
    public
    function cylinderCombine()
    {
        $payid = $this->input->post('payid');
        $data['userId'] = $this->input->post('searchId');
        $condition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['payType'] = $payid;
        if ($payid == 1) {
            return $this->load->view('distributor/ajax/cylinderCombine', $data);
        } elseif ($payid == 2) {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('customer', $condition);
            return $this->load->view('distributor/ajax/cylinderCombine', $data);
        } else {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('supplier', $condition);
            return $this->load->view('distributor/ajax/cylinderCombine', $data);
        }
    }
    public
    function getPayUserListPosting()
    {
        $payid = $this->input->post('payid');
        $payUserId = $this->input->post('payUserId');
        $condition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $data['payType'] = $payid;
        $data['userId'] = $payUserId;
        if ($payid == 1) {
            return $this->load->view('distributor/ajax/disable', $data);
        } elseif ($payid == 2) {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('customer', $condition);
            return $this->load->view('distributor/ajax/disable', $data);
        } else {
            $data['payList'] = $this->Common_model->get_data_list_by_many_columns('supplier', $condition);
            return $this->load->view('distributor/ajax/disable', $data);
        }
    }
    public
    function paymentVoucherAdd($postingId = null)
    {
        if (isPostBack()) {
//set some validation for input fields
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('payType', 'Payment Type', 'required');
            $this->form_validation->set_rules('accountCr', 'Payment Account', 'required');
            $this->form_validation->set_rules('accountDr[]', 'Account Debit', 'required');
            $this->form_validation->set_rules('amountDr[]', 'Amount Debit', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/paymentVoucherAdd'));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 2;
                $data['dist_id'] = $this->dist_id;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['payType'] = $this->input->post('payType');
                $data['narration'] = $this->input->post('narration');
                $cust = $this->input->post('customer_id');
                $supid = $this->input->post('supplier_id');
                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                endif;
                if (!empty($supid)):
                    $data['supplier_id'] = $this->input->post('supplier_id');
                endif;
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                endif;
                $data['debit'] = array_sum($this->input->post('amountDr'));
                $data['updated_by'] = $this->admin_id;
                $general_id = $this->Common_model->insert_data('generals', $data);
                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Payment Voucher',
                        'history_id' => $general_id,
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $general_id,
                        'paymentType' => 'Payment Voucher',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('supplier_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'cr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;
                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');
                /* Pay account credit */
                $dr['generals_id'] = $general_id;
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $dr['account'] = $acountCr;
                $dr['credit'] = array_sum($this->input->post('amountDr'));
                $dr['form_id'] = 2;
                $dr['dist_id'] = $this->dist_id;
                $dr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $dr);
                $allCr = array();
                foreach ($accountDr as $key => $value) {
                    unset($cr);
                    $cr['generals_id'] = $general_id;
                    $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $cr['account'] = $this->input->post('accountDr')[$key];
                    $cr['debit'] = $this->input->post('amountDr')[$key];
                    $cr['memo'] = $this->input->post('memoDr')[$key];
                    $cr['form_id'] = 2;
                    $cr['dist_id'] = $this->dist_id;
                    $cr['updated_by'] = $this->admin_id;
                    $allCr[] = $cr;
                }
                $this->db->insert_batch('generalledger', $allCr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted");
                    if (!empty($postingId)) {
                        redirect(site_url($this->project . '/paymentVoucherPosting/' . $postingId));
                    } else {
                        redirect(site_url($this->project . '/paymentVoucherView/' . $general_id));
                    }
                } else {
                    message("Your data successfully inserted into database.");
                    if (!empty($postingId)) {
                        $dataUpdate['ConfirmStatus'] = 1;
                        $this->Common_model->update_data('purchase_demo', $dataUpdate, 'purchase_demo_id', $postingId);
                        redirect(site_url('financeImport'));
                    } else {
                        redirect(site_url($this->project . '/paymentVoucherView/' . $general_id));
                    }
                }
            }
            /* Pay account Credit */
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
//$data['accountHeadList'] = $this->Common_model->getAccountHeadCashAndBank();
//echo $this->db->last_query();die;
        $voucherCondition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 2,
        );
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $voucherCondition);
        $data['voucherID'] = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['title'] = 'Add Payment Voucher';
        /*page navbar details*/
        $data['title'] = 'Add Payment Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Payment Voucher List';
        $data['link_page_url'] = $this->project . '/paymentVoucher';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/payment/paymentVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function paymentVoucherEdit($invoiceId)
    {
        if (is_numeric($invoiceId)) {
//is invoice id is valid?
            $validInvoiecId = $this->Sales_Model->checkInvoiceIdAndDistributor($this->dist_id, $invoiceId);
//invoice id this distributor??
            if ($validInvoiecId === FALSE) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url('salesInvoice'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url('salesInvoice'));
        }
        if (isPostBack()) {
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('payType', 'Payment Type', 'required');
            $this->form_validation->set_rules('accountCr', 'Payment Account', 'required');
            $this->form_validation->set_rules('accountDr[]', 'Account Debit', 'required');
            $this->form_validation->set_rules('amountDr[]', 'Amount Debit', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/paymentVoucherAdd'));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 2;
                $data['dist_id'] = $this->dist_id;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['payType'] = $this->input->post('payType');
                $data['narration'] = $this->input->post('narration');
                $cust = $this->input->post('customer_id');
                $supid = $this->input->post('supplier_id');
                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                endif;
                if (!empty($supid)):
                    $data['supplier_id'] = $this->input->post('supplier_id');
                endif;
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                endif;
                $data['debit'] = array_sum($this->input->post('amountDr'));
                $data['updated_by'] = $this->admin_id;
                $data['updated_at'] = $this->timestamp;
                $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
//delete data client ledger
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Payment Voucher',
                        'history_id' => $invoiceId,
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $invoiceId,
                        'paymentType' => 'Payment Voucher',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('supplier_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'cr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;
//delete from general table
                $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');
                /* Pay account credit */
                $dr['generals_id'] = $invoiceId;
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $dr['account'] = $acountCr;
                $dr['credit'] = array_sum($this->input->post('amountDr'));
                $dr['form_id'] = 2;
                $dr['dist_id'] = $this->dist_id;
                $dr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $dr);
                $allCr = array();
                foreach ($accountDr as $key => $value) {
                    unset($cr);
                    $cr['generals_id'] = $invoiceId;
                    $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $cr['account'] = $this->input->post('accountDr')[$key];
                    $cr['debit'] = $this->input->post('amountDr')[$key];
                    $cr['memo'] = $this->input->post('memoDr')[$key];
                    $cr['form_id'] = 2;
                    $cr['dist_id'] = $this->dist_id;
                    $cr['updated_by'] = $this->admin_id;
                    $allCr[] = $cr;
                }
                $this->db->insert_batch('generalledger', $allCr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted");
                    if (!empty($postingId)) {
                        redirect(site_url($this->project . '/paymentVoucherPosting/' . $postingId));
                    } else {
                        redirect(site_url($this->project . '/paymentVoucherView/' . $invoiceId));
                    }
                } else {
                    message("Your data successfully inserted into database.");
                    if (!empty($postingId)) {
                        $dataUpdate['ConfirmStatus'] = 1;
                        $this->Common_model->update_data('purchase_demo', $dataUpdate, 'purchase_demo_id', $postingId);
                        redirect(site_url('financeImport'));
                    } else {
                        redirect(site_url($this->project . '/paymentVoucherView/' . $invoiceId));
                    }
                }
            }
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['editVoucher'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $invoiceId);
//payment voucher Credit Account
        $data['getCreditAccountId'] = $this->Finane_Model->getCreditAccountId($this->dist_id, $invoiceId);
//payment voucher debit account
        $data['getDebitAccountId'] = $this->Finane_Model->getDebitAccountId($this->dist_id, $invoiceId);
        $data['title'] = 'Edit Payment Voucher';
        $data['mainContent'] = $this->load->view('distributor/finance/payment/paymentVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function checkBalancePayment()
    {
        $account = $this->input->post('account');
        $result2 = $this->Finane_Model->accountBalance($account);
//$result = '<label style="color:red!important" class="control-label col-sm-4 text-right">Balance.</label>';
        $result .= '<div class="col-sm-12"> ';
        $result .= '<input type="text" class="form-control" id="closeAmount" value="' . $result2 . '" readonly="readonly">';
        $result .= '</div>';
        echo $result;
    }
    public
    function checkBalance()
    {
        //die("test");
        $account = $this->input->post('account');
        $result2 = $this->Finane_Model->accountBalance($account);
        $result = '<div class="form-group">';
        $result = '<label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red;font-size:12px"> * <strong>Balance TK</strong> </span></label>';
        $result .= '<div class="col-sm-7">';
        $result .= '<input type="text" class="form-control"  id="closeAmount" value="' . $result2 . '" readonly="readonly">';
        $result .= '</div>';
        echo $result;
    }
    public
    function checkBalanceForModal()
    {
        $account = $this->input->post('account');
        $result2 = $this->Finane_Model->accountBalance($account);
        $result = '<label style="color:red!important" class="control-label col-md-3 text-right">Balance TK.</label>';
        $result .= '<div class="col-md-7"> ';
        $result .= '<input type="text" class="form-control" id="closeAmount" value="' . $result2 . '" readonly="readonly">';
        $result .= '</div>';
        echo $result;
    }
    public
    function checkBalanceForPayment()
    {
        $account = $this->input->post('account');
        $result2 = $this->Finane_Model->accountBalance($account);
        $result = '<label style="color:red!important" class="control-label col-md-3 text-right">Balance TK.</label>';
        $result .= '<div class="col-md-6"> ';
        $result .= '<input type="text" class="form-control" id="closeAmount" value="' . $result2 . '" readonly="readonly">';
        $result .= '</div>';
        echo $result;
    }
    public
    function checkOnlyBalanceForPayment()
    {
        $account = $this->input->post('account');
        $result2 = $this->Finane_Model->accountBalance($account);
        $result = '<label style="color:red!important" class="control-label col-md-3 text-right">Balance TK.</label>';
        $result .= '<div class="col-md-6"> ';
        $result .= '<input type="text" class="form-control" id="closeAmount" value="' . $result2 . '" readonly="readonly">';
        $result .= '</div>';
        echo $result2;
    }
    public
    function receiveVoucher()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 3,
        );
        $data['receiveVoucher'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition, 'date', 'DESC');
        /*page navbar details*/
        $data['title'] = 'Receive Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Receive Voucher Add';
        $data['link_page_url'] = $this->project . '/receiveVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/receive/receiveVoucher', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function receiveVoucherView($voucherID)
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 3,
        );
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['receiveVoucher'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $voucherID);
        $data['receiveJournal'] = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $voucherID);
        if ($data['receiveVoucher']->customer_id):
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['receiveVoucher']->customer_id);
        elseif ($data['receiveVoucher']->supplier_id):
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['receiveVoucher']->supplier_id);
        else:
        endif;
        $data['title'] = 'Receive Voucher';
        $data['mainContent'] = $this->load->view('distributor/finance/receive/receiveVoucherView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function receiveVoucherAdd($postingId = null)
    {
        if (isPostBack()) {
//validation rules set here.
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('payType', 'Payment Type', 'required');
            $this->form_validation->set_rules('accountDr', 'Payment Account', 'required');
            $this->form_validation->set_rules('accountCr[]', 'Account Debit', 'required');
            $this->form_validation->set_rules('amountCr[]', 'Amount Debit', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/receiveVoucherAdd'));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 3;
                $data['dist_id'] = $this->dist_id;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['payType'] = $this->input->post('payType');
                $data['narration'] = $this->input->post('narration');
                $cust = $this->input->post('customer_id');
                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                endif;
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                endif;
                $supid = $this->input->post('supplier_id');
                if (!empty($supid)):
                    $data['supplier_id'] = $supid;
                endif;
                $data['debit'] = array_sum($this->input->post('amountCr'));
                $data['updated_by'] = $this->admin_id;
                $general_id = $this->Common_model->insert_data('generals', $data);
                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $general_id,
                        'paymentType' => 'Receive Voucher',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $supid,
                        'amount' => array_sum($this->input->post('amountCr')),
                        'dr' => array_sum($this->input->post('amountCr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;
                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'history_id' => $general_id,
                        'paymentType' => 'Receive Voucher',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountCr')),
                        'cr' => array_sum($this->input->post('amountCr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
// dumpVar($_POST);
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');
                /* Pay account credit */
                $dr['generals_id'] = $general_id;
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $dr['account'] = $accountDr;
                $dr['debit'] = array_sum($this->input->post('amountCr'));
                $dr['form_id'] = 3;
                $dr['dist_id'] = $this->dist_id;
                $dr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $dr);
                $alldr = array();
                foreach ($acountCr as $key => $value) {
                    unset($cr);
                    $cr['generals_id'] = $general_id;
                    $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $cr['account'] = $this->input->post('accountCr')[$key];
                    $cr['credit'] = $this->input->post('amountCr')[$key];
                    $cr['memo'] = $this->input->post('memoCr')[$key];
                    $cr['form_id'] = 3;
                    $cr['dist_id'] = $this->dist_id;
                    $cr['updated_by'] = $this->admin_id;
                    $allCr[] = $cr;
                }
                $this->db->insert_batch('generalledger', $allCr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted");
                    if (!empty($postingId)) {
                        redirect(site_url($this->project . '/receiveVoucherPosting/' . $postingId));
                    } else {
                        redirect(site_url($this->project . '/receiveVoucherAdd'));
                    }
                } else {
                    message("Your data successfully inserted into database.");
                    if (!empty($postingId)) {
                        $updateData['ConfirmStatus'] = 1;
                        $this->Common_model->update_data('purchase_demo', $updateData, 'purchase_demo_id', $postingId);
                        redirect(site_url($this->project . '/financeImport'));
                    } else {
                        redirect(site_url($this->project . '/receiveVoucherView/' . $general_id));
                    }
                }
                /* Pay account Credit */
            }
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $voucherCondition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 3,
        );
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $voucherCondition);
        $data['voucherID'] = "RV" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['title'] = 'Add Receive Voucher';
        /*page navbar details*/
        $data['title'] = 'Add Receive Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Receive Voucher List';
        $data['link_page_url'] = $this->project . '/receiveVoucher';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/receive/receiveVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function receiveVoucherEdit($invoiceId)
    {
        if (is_numeric($invoiceId)) {
//is invoice id is valid?
            $validInvoiecId = $this->Sales_Model->checkInvoiceIdAndDistributor($this->dist_id, $invoiceId);
//invoice id this distributor??
            if ($validInvoiecId === FALSE) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url($this->project . '/receiveVoucher'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url($this->project . '/receiveVoucher'));
        }
        if (isPostBack()) {
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('payType', 'Payment Type', 'required');
            $this->form_validation->set_rules('accountDr', 'Payment Account', 'required');
            $this->form_validation->set_rules('accountCr[]', 'Account Debit', 'required');
            $this->form_validation->set_rules('amountCr[]', 'Amount Debit', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/receiveVoucherAdd'));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 3;
                $data['dist_id'] = $this->dist_id;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['payType'] = $this->input->post('payType');
                $data['narration'] = $this->input->post('narration');
                $cust = $this->input->post('customer_id');
                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                endif;
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                endif;
                $supid = $this->input->post('supplier_id');
                if (!empty($supid)):
                    $data['supplier_id'] = $supid;
                endif;
                $data['debit'] = array_sum($this->input->post('amountCr'));
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
//delete client vendor ledger table
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $invoiceId,
                        'paymentType' => 'Receive Voucher',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $supid,
                        'amount' => array_sum($this->input->post('amountCr')),
                        'dr' => array_sum($this->input->post('amountCr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;
                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'history_id' => $invoiceId,
                        'paymentType' => 'Receive Voucher',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountCr')),
                        'cr' => array_sum($this->input->post('amountCr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                    );
// dumpVar($_POST);
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
//delete general table.
                $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');
                /* Pay account credit */
                $dr['generals_id'] = $invoiceId;
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $dr['account'] = $accountDr;
                $dr['debit'] = array_sum($this->input->post('amountCr'));
                $dr['form_id'] = 3;
                $dr['dist_id'] = $this->dist_id;
                $dr['updated_by'] = $this->admin_id;
                $this->Common_model->insert_data('generalledger', $dr);
                $alldr = array();
                foreach ($acountCr as $key => $value) {
                    unset($cr);
                    $cr['generals_id'] = $invoiceId;
                    $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $cr['account'] = $this->input->post('accountCr')[$key];
                    $cr['credit'] = $this->input->post('amountCr')[$key];
                    $cr['memo'] = $this->input->post('memoCr')[$key];
                    $cr['form_id'] = 3;
                    $cr['dist_id'] = $this->dist_id;
                    $cr['updated_by'] = $this->admin_id;
                    $allCr[] = $cr;
                }
                $this->db->insert_batch('generalledger', $allCr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted");
                    if (!empty($postingId)) {
                        redirect(site_url($this->project . '/receiveVoucherPosting/' . $postingId));
                    } else {
                        redirect(site_url($this->project . '/receiveVoucherView/' . $invoiceId));
                    }
                } else {
                    message("Your data successfully inserted into database.");
                    if (!empty($postingId)) {
                        $updateData['ConfirmStatus'] = 1;
                        $this->Common_model->update_data('purchase_demo', $updateData, 'purchase_demo_id', $postingId);
                        redirect(site_url($this->project . '/financeImport'));
                    } else {
                        redirect(site_url($this->project . '/receiveVoucherView/' . $invoiceId));
                    }
                }
            }
        }
//payment voucher Credit Account
        $data['getDebitAccountId'] = $this->Finane_Model->getDebitAccountIdReceiveVoucher($this->dist_id, $invoiceId);
//payment voucher debit account
        $data['getCreditAccountId'] = $this->Finane_Model->getCreditAccountIdRecieveVoucher($this->dist_id, $invoiceId);
        $data['receiveVoucher'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $invoiceId);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['title'] = 'Edit Receive Voucher';
        $data['mainContent'] = $this->load->view('distributor/finance/receive/receiveVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function journalVoucherView($voucherID)
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 1,
        );
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['journalVoucher'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $voucherID);
        $data['journalJournal'] = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $voucherID);
        if ($data['journalVoucher']->customer_id):
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['journalVoucher']->customer_id);
        elseif ($data['journalVoucher']->supplier_id):
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['journalVoucher']->supplier_id);
        else:
        endif;
        $data['title'] = 'Receive Voucher';
        $data['mainContent'] = $this->load->view('distributor/finance/journal/journalVoucherView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function journalVoucherAdd()
    {
        if (isPostBack()) {
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('account[]', 'Payment Type', 'required');
            // $this->form_validation->set_rules('amountDr[]', 'Account Debit', 'required');
            //$this->form_validation->set_rules('amountCr[]', 'Amount Credit', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/journalVoucherAdd'));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 1;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['narration'] = $this->input->post('narration');
                $data['debit'] = array_sum($this->input->post('amountDr'));
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $general_id = $this->Common_model->insert_data('generals', $data);
                $account = $this->input->post('account');
                /* Pay account credit */
                $alldr = array();
                foreach ($account as $key => $value) {
                    unset($jv);
                    $jv['generals_id'] = $general_id;
                    $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $jv['account'] = $value;
                    $jv['debit'] = $this->input->post('amountDr')[$key];
                    $jv['credit'] = $this->input->post('amountCr')[$key];
                    $jv['memo'] = $this->input->post('memo')[$key];
                    $jv['form_id'] = 1;
                    $jv['dist_id'] = $this->dist_id;
                    $jv['updated_by'] = $this->admin_id;
                    $alldr[] = $jv;
                }
                $this->db->insert_batch('generalledger', $alldr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be inserted.");
                    redirect(site_url($this->project . '/journalVoucher'));
                } else {
                    message("Your journal inserted into database.");
                    redirect(site_url($this->project . '/journalVoucherView/' . $general_id));
                }
                /* Pay account Credit */
            }
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $voucherCondition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 1,
        );
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $voucherCondition);
        $data['voucherID'] = "JV" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['title'] = 'Add Journal Voucher';
        $data['mainContent'] = $this->load->view('distributor/finance/journal/journalVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function journalVoucherEdit($invoiceId)
    {
        if (is_numeric($invoiceId)) {
//is invoice id is valid?
            $validInvoiecId = $this->Sales_Model->checkInvoiceIdAndDistributor($this->dist_id, $invoiceId);
//invoice id this distributor??
            if ($validInvoiecId === FALSE) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url($this->project . '/receiveVoucher'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url($this->project . '/receiveVoucher'));
        }
        if (isPostBack()) {
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('account[]', 'Payment Type', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/journalVoucherAdd'));
            } else {
                $this->db->trans_start();
                $data['form_id'] = 1;
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['narration'] = $this->input->post('narration');
                $data['debit'] = array_sum($this->input->post('amountDr'));
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
                $account = $this->input->post('account');
                /* Pay account credit */
                $alldr = array();
                $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
                foreach ($account as $key => $value) {
                    unset($jv);
                    $jv['generals_id'] = $invoiceId;
                    $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $jv['account'] = $value;
                    $jv['debit'] = $this->input->post('amountDr')[$key];
                    $jv['credit'] = $this->input->post('amountCr')[$key];
                    $jv['memo'] = $this->input->post('memo')[$key];
                    $jv['form_id'] = 1;
                    $jv['dist_id'] = $this->dist_id;
                    $jv['updated_by'] = $this->admin_id;
                    $alldr[] = $jv;
                }
                $this->db->insert_batch('generalledger', $alldr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your data can't be Updated.");
                    redirect(site_url($this->project . '/journalVoucherEdit/' . $invoiceId));
                } else {
                    message("Your data successfully updated into database.");
                    redirect(site_url($this->project . '/journalVoucherView/' . $invoiceId));
                }
                /* Pay account Credit */
            }
        }
        $data['journalVoucher'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $invoiceId);
        $data['generalledger'] = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $invoiceId);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['title'] = 'Edit Journal Voucher';
        $data['mainContent'] = $this->load->view('distributor/finance/journal/journalVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function chartOfAccount()
    {
        if (isPostBack()) {
            $rootAccount = $this->input->post('rootAccount');
            $parentAccount = $this->input->post('parentAccount');
            $childAccount = $this->input->post('childAccount');
            $accountHead = $this->input->post('accountHead');
            $accountCode = $this->input->post('accountCode');
            if (empty($rootAccount) || empty($accountHead) || empty($accountCode)) :
                exception("Required Field Can't be Empty!!");
                redirect(site_url($this->project . '/chartOfAccount'), 'refresh');
            endif;
            unset($data);
            if (!empty($rootAccount) && empty($parentAccount) && empty($childAccount)):
//Parent Account Inserted.
                $data['rootId'] = $rootAccount;
                $data['parentId'] = $rootAccount;
                $data['accountCode'] = $accountCode;
                $data['title'] = $accountHead;
                $data['status'] = 1;
                $data['dist_id'] = $this->dist_id;
                $inserted_id = $this->Common_model->insert_data('chartofaccount', $data);
                if (!empty($inserted_id)) {
                    $this->Common_model->makeGeneralLedger();
                }
                if (!empty($inserted_id)) :
                    unset($_POST);
                    message("Your chart of head successfully created.");
                    redirect(site_url($this->project . '/chartOfAccount'));
                endif;
            elseif (!empty($parentAccount) && !empty($rootAccount) && empty($childAccount)) :
//Child Account Inserted.
                $data['rootId'] = $rootAccount;
                $data['parentId'] = $parentAccount;
                $data['accountCode'] = $accountCode;
                $data['title'] = $accountHead;
                $data['status'] = 1;
                $data['dist_id'] = $this->dist_id;
                $exits = $this->Finane_Model->accountBalanceDebitOrCredit($parentAccount);
                if ($exits === false):
                    $inserted_id = $this->Common_model->insert_data('chartofaccount', $data);
                    if (!empty($inserted_id)) {
                        $this->Common_model->makeGeneralLedger();
                    }
                    if (!empty($inserted_id)) :
                        unset($_POST);
                        message("Your chart of head successfully created.");
                        redirect(site_url($this->project . '/chartOfAccount'));
                    endif;
                else:
                    exception("This parent head already created transaction.Can't create child head.");
                    redirect(site_url($this->project . '/chartOfAccount'));
                endif;
            else:
                // Account Head Inserted.
                $data['rootId'] = $rootAccount;
                $data['parentId'] = $childAccount;
                $data['accountCode'] = $accountCode;
                $data['title'] = $accountHead;
                $data['status'] = 1;
                $data['dist_id'] = $this->dist_id;
                $exits = $this->Finane_Model->accountBalanceDebitOrCredit($childAccount);
                if ($exits === false) {
                    $inserted_id = $this->Common_model->insert_data('chartofaccount', $data);
                    if (!empty($inserted_id)) {
                        $this->Common_model->makeGeneralLedger();
                    }
                    if (!empty($inserted_id)) :
                        unset($_POST);
                        message("Your chart of head successfully created.");
                        redirect(site_url($this->project . '/chartOfAccount'));
                    endif;
                } else {
                    exception("This parent head already created transaction.Can't create child head.");
                    redirect(site_url($this->project . '/chartOfAccount'));
                }
            endif;
        }
        $data['rootAccount'] = $this->Common_model->get_data_list_by_single_column('chartofaccount', 'parentId', '0');
        /*page navbar details*/
        $data['title'] = 'Chart of Account';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Chart of Account List';
        $data['link_page_url'] = $this->project . '/listChartOfAccount';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/finance/setup/chartOfAccount', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function editChartOfAccount($id)
    {
        if (isPostBack()) {
            $this->db->trans_start();
            $condtion = array(
                'dist_id' => $this->dist_id,
                'chartId' => $id,
            );
            $generalId = $this->Common_model->get_single_data_by_many_columns('generaldata', $condtion);
            $data1['title'] = $this->input->post('accountHead');
            $this->Common_model->update_data('generaldata', $data1, 'generalId', $generalId->generalId);
            $data['title'] = $this->input->post('accountHead');
            $this->Common_model->update_data('chartofaccount', $data, 'chart_id', $id);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your data can't be updated.Somthing is wrong!!");
                redirect(site_url($this->project . '/editChartOfAccount/' . $id));
            } else {
                message("Your chart of account successfully updated into database   .");
                redirect(site_url($this->project . '/listChartOfAccount'));
            }
        }
        $data['title'] = 'Edit Chart of Account';
        $condition = array(
            'dist_id' => $this->dist_id,
            'rootId !=' => 0,
        );
        $data['editChartAccount'] = $rootAccount = $this->Common_model->get_single_data_by_single_column('chartofaccount', 'chart_id', $id);
        $data['mainContent'] = $this->load->view('distributor/finance/setup/editChartAccount', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function listChartOfAccount()
    {
        $data['title'] = get_phrase('Chart Of Account');
        /*page navbar details*/
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Chart Of Account Add');
        $data['link_page_url'] = $this->project . '/chartOfAccount';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['chartList'] = $this->Finane_Model->getChartList();
        $data['mainContent'] = $this->load->view('distributor/finance/setup/listChartOfAccount', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function changeChartStatus__old_al()
    {
        $chartid = $this->input->post('chartid');
        $data['status'] = $this->input->post('chartStatus');
        $update_status = $this->Common_model->update_data('chartofaccount', $data, 'chart_id', $chartid);
        $this->Common_model->makeGeneralLedger();
        message("Chart of account status successfully change");
        if (!empty($update_status)):
            echo 1;
        else:
            echo 2;
        endif;
    }


    function changeChartStatus()
    {
        $chartid = $this->input->post('chartid');
        $data['status'] = $this->input->post('chartStatus');
        $update_status = $this->Common_model->update_data('ac_account_ledger_coa', $data, 'id', $chartid);
        $this->Common_model->makeGeneralLedger();
        message("Chart of account status successfully change");
        if (!empty($update_status)):
            echo 1;
        else:
            echo 2;
        endif;
    }



    function getProductList()
    {
        $cat_id = $this->input->post('cat_id');
        $productList = $this->Common_model->get_data_list_by_single_column('product', 'category_id', $cat_id);
        $add = '';
        if (!empty($productList)):
            $add .= "<option value=''></option>";
            foreach ($productList as $key => $value):
                $add .= "<option productName='" . $value->productName . "'   value='" . $value->product_id . "'>$value->productName</option>";
            endforeach;
            echo $add;
            DIE;
        else:
            echo "<option value='' selected disabled>No Product Available</option>";
            DIE;
        endif;
    }
    function getProductPrice()
    {
        $product_id = $this->input->post('product_id');
        $productDetails = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $product_id);
        if (!empty($productDetails)):
            echo $productDetails->purchases_price;
        endif;
    }
    function deleteOpneningBalance()
    {
        $this->Common_model->delete_data('opening_balance', 'dist_id', $this->dist_id);
        message("Your Opening Balance Successfully deleted from database.");
        redirect(site_url('openingBalance'));
    }
    function openingBalance2($reloadId = null)
    {
        if (isPostBack()) {
            $headDebit = $this->input->post('headDebit');
            $headCredit = $this->input->post('headCredit');
            $this->Common_model->delete_data('opening_balance', 'dist_id', $this->dist_id);
//$this->db->empty_table('opening_balance');
            $accountid = $this->input->post('accountid');
            $headDebit = $this->input->post('headDebit');
            $headCredit = $this->input->post('headCredit');
            $insertData = array();
            foreach ($accountid as $key => $value):
                $data['account'] = $value;
                //inventory account head not insert opening balance.its come from stock table as a price.
                if (!in_array($value, array('52', '173'))) {
                    $data['debit'] = $headDebit[$key];
                } else {
                    $data['debit'] = 0;
                }
                $data['credit'] = $headCredit[$key];
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertData[] = $data;
            endforeach;
            $this->db->insert_batch('opening_balance', $insertData);
            message("Your opening balance successfully inserted into database.");
            redirect(site_url($this->project . '/openingBalance'));
        }
        if (!empty($reloadId)) {
            $data['reloadData'] = $reloadId;
        } else {
            $data['reloadData'] = '';
        }
        $data['openingBalanceDeleteCheck'] = $this->Finane_Model->checkOpeningDeleteValid($this->dist_id);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        /* echo "<pre>";
         print_r($data['accountHeadList']);exit;*/
        $data['title'] = get_phrase('Opening Balance');
        $data['page_type'] = get_phrase('Configuration');
        $data['mainContent'] = $this->load->view('distributor/finance/setup/opengingBalance', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function openingBalance($reloadId = null)
    {
        if (isPostBack()) {
            //echo "<pre>";
            $this->Common_model->delete_data('opening_balance', 'dist_id', $this->dist_id);
            ///ini_set('max_input_vars', '2000');
//$this->db->empty_table('opening_balance');
            $accountid = $this->input->post('accountid');
            /*ac_accounts_vouchermst*/
            $voucher_no = create_journal_voucher_no();
            $accountingMasterTable['AccouVoucherType_AutoID'] = 7;
            $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
            $accountingMasterTable['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));;
            $accountingMasterTable['BackReferenceInvoiceNo'] = 0;
            $accountingMasterTable['BackReferenceInvoiceID'] = 0;
            $accountingMasterTable['Narration'] = 'Opening balance for accounting';
            $accountingMasterTable['CompanyId'] = $this->dist_id;
            $accountingMasterTable['BranchAutoId'] = 1;
            $accountingMasterTable['supplier_id'] = 0;
            $accountingMasterTable['IsActive'] = 1;
            $accountingMasterTable['Created_By'] = $this->admin_id;
            $accountingMasterTable['Created_Date'] = $this->timestamp;
            $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);
            foreach ($accountid as $key => $value):
                $data['account'] = $value;
                //inventory account head not insert opening balance.its come from stock table as a price.
                $data['debit'] = $_POST['headDebit_' . $value];
                $data['credit'] = $_POST['headCredit_' . $value];
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertData[] = $data;
                $accountingDetailsTable['CHILD_ID'] = $value;//'95';
                $accountingDetailsTable['Reference'] = 'Opening balance for accounting';
                $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                if ($_POST['headDebit_' . $value] > 0) {
                    $accountingDetailsTable['TypeID'] = '1';//Dr
                } else {
                    $accountingDetailsTable['TypeID'] = '2';//Cr
                }
                $accountingDetailsTable['GR_DEBIT'] = $_POST['headDebit_' . $value];
                $accountingDetailsTable['GR_CREDIT'] = $_POST['headCredit_' . $value];
                $accountingDetailsTable['IsActive'] = 1;
                $accountingDetailsTable['Created_By'] = $this->admin_id;
                $accountingDetailsTable['Created_Date'] = $this->timestamp;
                $accountingDetailsTable['BranchAutoId'] = 1;
                $accountingDetailsTable['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $finalDetailsArray[] = $accountingDetailsTable;
            endforeach;
            $this->db->insert_batch('opening_balance', $insertData);
            if (!empty($finalDetailsArray)):
                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
            endif;
            $msg = $this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/openingBalance'));
        }
        if (!empty($reloadId)) {
            $data['reloadData'] = $reloadId;
        } else {
            $data['reloadData'] = '';
        }
        $data['openingBalanceDeleteCheck'] = $this->Finane_Model->checkOpeningDeleteValid($this->dist_id);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
//echo "<pre>";
//print_r($data['accountHeadList']);exit;
        $data['title'] = get_phrase('Opening Balance');
        $data['page_type'] = get_phrase('Configuration');
        $data['mainContent'] = $this->load->view('distributor/finance/setup/opengingBalance2', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public
    function generalLedger_export_excel()
    {
        $file = 'General Ledger_' . date('d.m.Y') . '.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        header('Cache-Control: max-age=0');
        $account = $data['account'] = $_SESSION['account'];
        $start_date = $data['fromdate'] = $_SESSION['start_date'];
        $end_date = $data['todate'] = $_SESSION['end_date'];
//$dist_id = $data['dist_id'] = $_SESSION['dist_id'];
        if (!empty($account)) {
            $data['dist_id'] = $this->dist_id;
            $data['account'] = $account;
        } else {
            $data['fromdate'] = 0;
            $data['todate'] = 0;
            $data['account'] = 0;
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $list = $this->Finane_Model->getExpenseHead();
        $this->load->view('excel_report/generalLedger_export_excel', $data);
        unset($_SESSION['full_array']);
    }
    public function paymentReceiveVoucherReport()
    {
        if (isPostBack()) {
            $reportType = $data['reportType'] = $this->input->post('reportType');
            $branch_id = $data['reportType'] = $this->input->post('branch_id');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['result'] = $this->Finane_Model->get_recive_payment_voucher($branch_id, $reportType, $start_date, $end_date);




        }
        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['title'] = 'Payment / Receive Voucher Report';
        $data['mainContent'] = $this->load->view('distributor/finance/report/paymentReceiveVoucherReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function rows_to_tree($raw, $id_key = 'id', $parent_key = 'parent_id')
    {
        // First, transform $raw to $rows so that array key == id
        $rows = array();
        foreach ($raw as $row) {
            $rows[$row[$id_key]] = $row;
        }
        $tree = array();
        $tree_index = array(); // Storing the reference to each node
        while (count($rows)) {
            foreach ($rows as $id => $row) {
                if ($row[$parent_key]) { // If it has parent
                    // Abnormal case: has parent id but no such id exists
                    if (!array_key_exists($row[$parent_key], $rows) AND !array_key_exists($row[$parent_key], $tree_index)) {
                        unset($rows[$id]);
                    } // If the parent id exists in $tree_index, insert itself
                    else if (array_key_exists($row[$parent_key], $tree_index)) {
                        $parent = &$tree_index[$row[$parent_key]];
                        $parent['children'][$id] = array('node' => $row, 'children' => array());
                        $tree_index[$id] = &$parent['children'][$id];
                        unset($rows[$id]);
                    }
                } else { // Top parent
                    $tree[$id] = array('node' => $row, 'children' => array());
                    $tree_index[$id] = &$tree[$id];
                    unset($rows[$id]);
                }
            }
        }
        return $tree;
    }
    function openingBalance3($reloadId = null)
    {
        if (isPostBack()) {
            $this->db->select('count(*)');
            $this->db->from('ac_accounts_vouchermst');
            $this->db->where('AccouVoucherType_AutoID', '7');
            $query = $this->db->get();


            if ($query->num_rows() <= 0) {
                //echo "<pre>";

                $this->Common_model->delete_data('opening_balance', '1', 1);
                ///ini_set('max_input_vars', '2000');
//$this->db->empty_table('opening_balance');
                $accountid = $this->input->post('accountid');
                /*ac_accounts_vouchermst*/
                $voucher_no = create_journal_voucher_no();
                $accountingMasterTable['AccouVoucherType_AutoID'] = 7;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));;
                $accountingMasterTable['BackReferenceInvoiceNo'] = 0;
                $accountingMasterTable['BackReferenceInvoiceID'] = 0;
                $accountingMasterTable['Narration'] = 'Opening balance for accounting';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = 1;
                $accountingMasterTable['supplier_id'] = 0;
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);
                foreach ($accountid as $key => $value):
                    $data['account'] = $value;
                    //inventory account head not insert opening balance.its come from stock table as a price.
                    $data['debit'] = $_POST['headDebit_' . $value];
                    $data['credit'] = $_POST['headCredit_' . $value];
                    $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $data['dist_id'] = $this->dist_id;
                    $data['updated_by'] = $this->admin_id;
                    $insertData[] = $data;
                    $accountingDetailsTable['CHILD_ID'] = $value;//'95';
                    $accountingDetailsTable['Reference'] = 'Opening balance for accounting';
                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    if ($_POST['headDebit_' . $value] > 0) {
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                    } else {
                        $accountingDetailsTable['TypeID'] = '2';//Cr
                    }
                    $accountingDetailsTable['GR_DEBIT'] = $_POST['headDebit_' . $value];
                    $accountingDetailsTable['GR_CREDIT'] = $_POST['headCredit_' . $value];
                    $accountingDetailsTable['IsActive'] = 1;
                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable['BranchAutoId'] = 1;
                    $accountingDetailsTable['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $finalDetailsArray[] = $accountingDetailsTable;
                endforeach;
                $this->db->insert_batch('opening_balance', $insertData);
                if (!empty($finalDetailsArray)):
                    $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                endif;
                $msg = $this->config->item("save_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/openingBalance'));
            }
        }

        $condition = array(
            'status' => 1,
        );
        if (!empty($reloadId)) {
            $data['reloadData'] = $reloadId;
        } else {
            $data['reloadData'] = '';
        }
        $chartList = $this->Common_model->get_data_list_by_many_columns_array('ac_account_ledger_coa', $condition);
        $chartListTree = $this->rows_to_tree($chartList, 'id', 'parent_id');
        $final_array = array();
        foreach ($chartListTree as $id => $row) {
            // $final_array[$id]=$row['node'];
            $row['node']['label'] = 0;
            array_push($final_array, $row['node']);
            if (!empty($row['children'])) {
                foreach ($row['children'] as $idC => $rowC) {
                    $rowC['node']['label'] = 1;
                    array_push($final_array, $rowC['node']);
                    if (!empty($rowC['children'])) {
                        foreach ($rowC['children'] as $idD => $rowD) {
                            $rowD['node']['label'] = 2;
                            array_push($final_array, $rowD['node']);
                            if (!empty($rowD['children'])) {
                                foreach ($rowD['children'] as $idE => $rowE) {
                                    $rowE['node']['label'] = 3;
                                    array_push($final_array, $rowE['node']);
                                    if (!empty($rowE['children'])) {
                                        foreach ($rowE['children'] as $idF => $rowF) {
                                            $rowF['node']['label'] = 4;
                                            array_push($final_array, $rowF['node']);
                                            if (!empty($rowF['children'])) {
                                                foreach ($rowF['children'] as $idG => $rowG) {
                                                    $rowG['node']['label'] = 5;
                                                    array_push($final_array, $rowG['node']);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['chartList'] = $final_array;


        $data['title'] = get_phrase('Opening Balance');
        $data['page_type'] = get_phrase('Configuration');
        $data['mainContent'] = $this->load->view('distributor/finance/setup/opengingBalanc_3', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}
