<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/22/2019
 * Time: 11:42 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class VoucherController extends CI_Controller
{

    private $timestamp;
    public $admin_id;
    public $dist_id;
    public $page_type;

    public $project;

    public function __construct()
    {

        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Dashboard_Model');
        $thisdis = $this->session->userdata('dis_id');
        $admin_id = $this->session->userdata('admin_id');

        if (empty($thisdis) || empty($admin_id)) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');

        }

        $this->page_type = 'Accounts';
        $this->link_icon_add = "<i class='ace-icon fa fa-plus'></i>";
        $this->link_icon_list = "<i class='ace-icon fa fa-list'></i>";

        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        //$this->load->helper('db_dinamic_helper');
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function receiveVoucher()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 3,
        );
        //  $data['receiveVoucher'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition, 'date', 'DESC');

        /*page navbar details*/
        $data['title'] = 'Receive Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Receive Voucher Add';
        $data['link_page_url'] = $this->project . '/receiveVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/receiv_voucher/receiveVoucher', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function receiveVoucherView($voucherID)
    {


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $costCondition = array(
            'Accounts_VoucherMst_AutoID' => $voucherID,
            'IsActive' => 1,
        );
        $data['receiveVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $voucherID);
        $data['receiveJournal'] = $this->Common_model->get_data_list_by_many_columns('ac_tb_accounts_voucherdtl', $costCondition);
        if ($data['receiveVoucher']->customer_id):
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['receiveVoucher']->customer_id);
        elseif ($data['receiveVoucher']->supplier_id):
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['receiveVoucher']->supplier_id);
        else:
        endif;
        $data['title'] = 'Receive Voucher';


        /*page navbar details*/
        $data['title'] = get_phrase('Receive Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Receive  Voucher');
        $data['link_page_url'] = $this->project . '/receiveVoucherAdd';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase(' Receive Voucher List');
        $data['second_link_page_url'] = $this->project . '/receiveVoucher';
        $data['second_link_icon'] = $this->link_icon_list;


        if ($data['receiveVoucher']->for == 0) {
            $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
            $data['third_link_page_name'] = get_phrase('Edit Receive Voucher');
            $data['third_link_page_url'] = $this->project . '/receiveVoucherEdit/' . $voucherID;
            $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        }

        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/receiv_voucher/receiveVoucherView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);


    }

    public function receiveVoucherAdd($postingId = null)
    {
        $this->load->helper('create_receive_voucher_no_helper');
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


                $voucherCondition = array(
                    'AccouVoucherType_AutoID' => 1,
                    //'BranchAutoId' => $this->dist_id,
                );
                /*$totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
                $voucherID = "RV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);*/

                $voucherID = create_receive_voucher_no();
                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $voucherID;
                $data['Narration'] = $this->input->post('narration');
                $data['CompanyId'] = $this->dist_id;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Reference'] = 0;
                $data['AccouVoucherType_AutoID'] = 1;
                $data['IsActive'] = 1;
                $data['Created_By'] = $this->admin_id;
                $data['Created_Date'] = $this->timestamp;


                /* Pay account DR */


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

                $general_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $data);

                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');

                /* Pay account DR */
                $dr['Accounts_VoucherMst_AutoID'] = $general_id;
                $dr['TypeID'] = 1;
                $dr['CHILD_ID'] = $accountDr;
                //$dr['CHILD_ID'] = $acountCr;
                $dr['GR_CREDIT'] = 0;
                //$dr['GR_CREDIT'] = array_sum($this->input->post('amountDr'));
                $dr['GR_DEBIT'] = array_sum($this->input->post('amountCr'));
                // $dr['GR_DEBIT'] = 0;
                $dr['IsActive'] = 1;
                $dr['Created_By'] = $this->dist_id;
                $dr['Created_Date'] = $this->timestamp;
                $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $dr);


                $allCr = array();
                foreach ($acountCr as $key => $value) {
                    unset($cr);
                    $cr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $cr['TypeID'] = 2;
                    $cr['CHILD_ID'] = $this->input->post('accountCr')[$key];
                    $cr['GR_CREDIT'] = $this->input->post('amountCr')[$key];
                    $cr['Reference'] = $this->input->post('memoCr')[$key];;
                    $cr['GR_DEBIT'] = 0;
                    $cr['IsActive'] = 1;
                    $cr['Created_By'] = $this->dist_id;
                    $cr['Created_Date'] = $this->timestamp;
                    $cr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $allCr[] = $cr;
                }

                /* echo '<pre>';
                 print_r($allCr);
                 exit();*/
                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $allCr);


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
                        'BranchAutoId' => $this->input->post('BranchAutoId')
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
                        'BranchAutoId' => $this->input->post('BranchAutoId')
                    );
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/receiveVoucherAdd/'));
                } else {
                    $msg = $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/receiveVoucherView/' . $general_id));

                }


                /* Pay account Credit */
            }
        }
        //$data['accountHeadList'] = $this->Common_model->getAccountHead();


        $conditionaccountHeadList = array(
            'status' => '1',
            'posted' => '1'
        );

        $data['accountHeadList'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadList);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();

        $voucherCondition = array(
            'AccouVoucherType_AutoID' => 2,
            'BranchAutoId' => $this->dist_id,
        );

        $data['voucherID'] = create_receive_voucher_no();
        //$totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
        //$data['voucherID'] = "RV" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);


        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);


        $data['title'] = 'Add Receive Voucher';
        /*page navbar details*/
        $data['title'] = 'Add Receive Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Receive Voucher List';
        $data['link_page_url'] = $this->project . '/receiveVoucher';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/receiv_voucher/receiveVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function receiveVoucherEdit($invoiceId)
    {


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

                $this->_save_data_to_accounting_history_table($invoiceId, 'edit', 'Receive Voucher', 'receiveVoucherEdit');
                $this->db->trans_start();

                $data['AccouVoucherType_AutoID'] = 1;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $this->input->post('voucherid');
                // $data['AccouVoucherType_AutoID'] = $this->input->post('payType');
                $data['Narration'] = $this->input->post('narration');


                $cust = $this->input->post('customer_id');
                $miscellaneous = $this->input->post('miscellaneous');
                $supid = $this->input->post('supplier_id');

                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                    $data['supplier_id'] = 0;
                    $data['miscellaneous'] = null;
                endif;

                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                    $data['supplier_id'] = 0;
                    $data['customer_id'] = null;
                endif;

                if (!empty($supid)):
                    $data['supplier_id'] = $this->input->post('supplier_id');
                    $data['customer_id'] = 0;
                    $data['miscellaneous'] = null;
                endif;

                // $data['debit'] = array_sum($this->input->post('amountCr'));

                $data['IsActive'] = 1;
                $data['CompanyId'] = $this->dist_id;
                $data['Changed_By'] = $this->admin_id;
                $data['Changed_Date'] = $this->timestamp;


                $this->Common_model->update_data('ac_accounts_vouchermst', $data, 'Accounts_VoucherMst_AutoID', $invoiceId);

                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');
                $allDataUpdate = array();
                $allDatainsert = array();


                /*$Delete['Changed_By'] = $this->admin_id;
                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update('ac_tb_accounts_voucherdtl', $Delete);

                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $del = $this->db->delete('ac_tb_accounts_voucherdtl');*/


                $paymentCrCondition = array(
                    'TypeID' => 1,
                    'Accounts_VoucherMst_AutoID' => $invoiceId,
                    'CHILD_ID' => $this->input->post('accountDr')
                );

                $dr['Accounts_VoucherMst_AutoID'] = $invoiceId;
                $dr['CHILD_ID'] = $this->input->post('accountDr');
                $dr['GR_DEBIT'] = array_sum($this->input->post('amountCr'));
                $dr['TypeID'] = 1;
                $dr['GR_CREDIT'] = 0;
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $dr['Reference'] = '';
                $dr['IsActive'] = 1;
                $dr['Changed_By'] = $this->admin_id;
                $dr['Changed_Date'] = $this->timestamp;
                $dr['BranchAutoId'] = $this->input->post('BranchAutoId');

                $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $dr, $paymentCrCondition);


                foreach ($acountCr as $key => $value) {
                    $costCondition = array(
                        'Accounts_VoucherMst_AutoID' => $invoiceId,
                        'CHILD_ID' => $value,
                    );
                    $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_tb_accounts_voucherdtl', $costCondition);

                    if (!empty($checkArray)) {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = 0;
                        $jv['GR_CREDIT'] = $this->input->post('amountCr')[$key];
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memoCr')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['TypeID'] = 2;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $allDataUpdate[] = $jv;
                        unset($jv);
                    } else {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = 0;
                        $jv['GR_CREDIT'] = $this->input->post('amountCr')[$key];
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memoCr')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $jv['TypeID'] = 2;
                        $allDatainsert[] = $jv;
                        unset($jv);
                    }
                }
                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update_batch('ac_tb_accounts_voucherdtl', $allDataUpdate, 'CHILD_ID');

                if (!empty($allDatainsert)) {
                    $this->db->insert_batch('ac_tb_accounts_voucherdtl', $allDatainsert);
                }


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/receiveVoucherAdd/'));
                } else {

                    $msg = $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/receiveVoucherView/' . $invoiceId));

                }
            }
        }

        $data['receiveVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $invoiceId);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();

//payment voucher Credit Account
        $data['getDebitAccountId'] = $this->Finane_Model->getDebitAccountIdReceiveVoucherNew($this->dist_id, $invoiceId);


//payment voucher debit account
        $data['getCreditAccountId'] = $this->Finane_Model->getCreditAccountIdRecieveVoucherNew($this->dist_id, $invoiceId);

        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);


        /*page navbar details*/
        $data['title'] = get_phrase('Edit Receive Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Payment Voucher');
        $data['link_page_url'] = $this->project . '/paymentVoucherAdd';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase(' Receive Voucher');
        $data['second_link_page_url'] = $this->project . '/receiveVoucher';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('View Recive Voucher');
        $data['third_link_page_url'] = $this->project . '/receiveVoucherView/' . $invoiceId;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/

        $data['mainContent'] = $this->load->view('distributor/account/receiv_voucher/receiveVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function paymentVoucherAdd($postingId = null)
    {
        $this->load->helper('create_payment_voucher_no');
        if (isPostBack()) {
            //set some validation for input fields
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('payType', 'Payment Type', 'required');
            $this->form_validation->set_rules('accountCr', 'Payment Account', 'required');
            $this->form_validation->set_rules('accountDr[]', 'Account Debit', 'required');
            $this->form_validation->set_rules('amountDr[]', 'Amount Debit', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = "Required field can't be empty";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/paymentVoucherAdd'));
            } else {
                $this->db->trans_start();
                $voucherCondition = array(
                    'AccouVoucherType_AutoID' => 2,
                    'BranchAutoId' => $this->dist_id,
                );


                // $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
                //$voucherID = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);

                $voucherID = create_payment_voucher_no();

                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $voucherID;
                $data['Narration'] = $this->input->post('narration');
                $data['CompanyId'] = $this->dist_id;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Reference'] = 0;
                $data['AccouVoucherType_AutoID'] = 2;
                $data['IsActive'] = 1;
                $data['Created_By'] = $this->admin_id;
                $data['Created_Date'] = $this->timestamp;

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


                $general_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $data);


                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');
                /* Pay account credit */
                $dr['Accounts_VoucherMst_AutoID'] = $general_id;
                $dr['TypeID'] = 2;
                $dr['CHILD_ID'] = $acountCr;
                $dr['GR_CREDIT'] = array_sum($this->input->post('amountDr'));
                $dr['GR_DEBIT'] = 0;
                $dr['IsActive'] = 1;
                $dr['Created_By'] = $this->dist_id;
                $dr['Created_Date'] = $this->timestamp;
                $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $dr);

                $allCr = array();
                foreach ($accountDr as $key => $value) {
                    unset($cr);
                    $cr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $cr['TypeID'] = 1;
                    $cr['CHILD_ID'] = $this->input->post('accountDr')[$key];
                    $cr['GR_CREDIT'] = 0;
                    $cr['Reference'] = $this->input->post('memoDr')[$key];
                    $cr['GR_DEBIT'] = $this->input->post('amountDr')[$key];
                    $cr['IsActive'] = 1;
                    $cr['Created_By'] = $this->dist_id;
                    $cr['Created_Date'] = $this->timestamp;
                    $cr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $allCr[] = $cr;
                }
                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $allCr);


                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Payment Voucher Customer Payment',
                        'history_id' => $general_id,
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                        'Accounts_VoucherType_AutoID' => 2,
                        'BranchAutoId' => $this->input->post('BranchAutoId')
                    );
                    $this->db->insert('client_vendor_ledger', $custLedger);
                endif;
                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $general_id,
                        'paymentType' => 'Payment Voucher supplier payment',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('supplier_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'cr' => array_sum($this->input->post('amountDr')),
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                        'Accounts_VoucherType_AutoID' => 2,
                        'BranchAutoId' => $this->input->post('BranchAutoId')
                    );
                    $this->db->insert('client_vendor_ledger', $supLedger);
                endif;


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/paymentVoucherAdd'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/paymentVoucherView/' . $general_id));

                }
            }
            /* Pay account Credit */
        }
        $condition = array(
            'status' => '1',
            'posted' => '1'
        );
        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();


        /*page navbar details*/
        $data['title'] = get_phrase('Add Payment Voucher');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Payment Voucher List');
        $data['link_page_url'] = $this->project . '/paymentVoucher';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/payment_voucher/paymentVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function paymentVoucher()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('Payment Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Payment Voucher Add');
        $data['link_page_url'] = $this->project . '/paymentVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/payment_voucher/paymentVoucher', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function paymentVoucherView($voucherID)
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 2,
        );
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['paymentVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $voucherID);
        $data['paymentJournal'] = $this->Common_model->get_data_list_by_single_column('ac_tb_accounts_voucherdtl', 'Accounts_VoucherMst_AutoID', $voucherID);
        if ($data['paymentVoucher']->customer_id):
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['paymentVoucher']->customer_id);
        elseif ($data['paymentVoucher']->supplier_id):
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['paymentVoucher']->supplier_id);
        else:
        endif;
        /* echo "<pre>";
         print_r($data);
         exit;*/


        /*page navbar details*/
        $data['title'] = get_phrase('Payment Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Payment Voucher Add');
        $data['link_page_url'] = $this->project . '/paymentVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/

        /*page navbar details*/
        $data['title'] = get_phrase('Payment Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Payment Voucher Add');
        $data['link_page_url'] = $this->project . '/paymentVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Payment Voucher List');
        $data['second_link_page_url'] = $this->project . '/paymentVoucher';
        $data['second_link_icon'] = $this->link_icon_list;
        if ($data['paymentVoucher']->for == 0) {

            $data['third_link_page_name'] = get_phrase('Payment Voucher Edit');
            $data['third_link_page_url'] = $this->project . '/paymentVoucherEdit/' . $voucherID;
            $data['third_link_icon'] = $this->link_icon_edit;
            /*page navbar details*/
        }
        $data['mainContent'] = $this->load->view('distributor/account/payment_voucher/paymentVoucherView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function paymentVoucherEdit($invoiceId)
    {


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
                $this->_save_data_to_accounting_history_table($invoiceId, 'edit', 'Payment  Voucher', 'paymentVoucherEdit');

                $this->db->trans_start();
                $data['AccouVoucherType_AutoID'] = 2;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $this->input->post('voucherid');
                // $data['AccouVoucherType_AutoID'] = $this->input->post('payType');
                $data['Narration'] = $this->input->post('narration');
                $cust = $this->input->post('customer_id');
                $supid = $this->input->post('supplier_id');
                if (!empty($cust)):
                    $data['customer_id'] = $cust;
                    $data['supplier_id'] = 0;
                    $data['miscellaneous'] = null;
                endif;
                if (!empty($supid)):
                    $data['supplier_id'] = $this->input->post('supplier_id');
                    $data['customer_id'] = 0;
                    $data['miscellaneous'] = null;
                endif;
                $miscellaneous = $this->input->post('miscellaneous');
                if (!empty($miscellaneous)):
                    $data['miscellaneous'] = $this->input->post('miscellaneous');
                    $data['supplier_id'] = 0;
                    $data['customer_id'] = null;
                endif;
                //$data['debit'] = array_sum($this->input->post('amountDr'));
                $data['IsActive'] = 1;
                $data['CompanyId'] = $this->dist_id;
                $data['Changed_By'] = $this->admin_id;
                $data['Changed_Date'] = $this->timestamp;


                $this->Common_model->update_data('ac_accounts_vouchermst', $data, 'Accounts_VoucherMst_AutoID', $invoiceId);

                $acountCr = $this->input->post('accountCr');
                $accountDr = $this->input->post('accountDr');
                /* Pay account credit */

                $allDataUpdate = array();
                $allDatainsert = array();

               /* $Delete['Changed_By'] = $this->admin_id;
                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update('ac_tb_accounts_voucherdtl', $Delete);


                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->delete('ac_tb_accounts_voucherdtl');*/


                /*$Delete['IsActive'] = 0;
                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update('ac_tb_accounts_voucherdtl', $Delete);*/
                $paymentCrCondition = array(
                    'TypeID' => 2,
                    'Accounts_VoucherMst_AutoID' => $invoiceId,
                    'CHILD_ID' => $this->input->post('accountCr')
                );
                $cr['Accounts_VoucherMst_AutoID'] = $invoiceId;
                $cr['CHILD_ID'] = $this->input->post('accountCr');
                $cr['GR_DEBIT'] = 0;
                $cr['TypeID'] = 2;
                $cr['GR_CREDIT'] = array_sum($this->input->post('amountDr'));
                $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $cr['Reference'] = 'Cr';
                $cr['IsActive'] = 1;
                $cr['Changed_By'] = $this->admin_id;
                $cr['BranchAutoId'] = $this->input->post('BranchAutoId');
                $cr['Changed_Date'] = $this->timestamp;

                $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $cr, $paymentCrCondition);


                foreach ($accountDr as $key => $value) {
                    $costCondition = array(
                        'Accounts_VoucherMst_AutoID' => $invoiceId,
                        'CHILD_ID' => $value,
                    );
                    $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_tb_accounts_voucherdtl', $costCondition);
                    if (!empty($checkArray)) {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = $this->input->post('amountDr')[$key];
                        $jv['GR_CREDIT'] = 0;
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memoDr')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['TypeID'] = 1;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $allDataUpdate[] = $jv;
                        unset($jv);
                    } else {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = $this->input->post('amountDr')[$key];
                        $jv['GR_CREDIT'] = 0;
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memoDr')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $jv['TypeID'] = 1;
                        $allDatainsert[] = $jv;
                        unset($jv);
                    }
                }

                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update_batch('ac_tb_accounts_voucherdtl', $allDataUpdate, 'CHILD_ID');


                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $allDatainsert);


                if (!empty($cust)):
                    $custLedger = array(
                        'ledger_type' => 1,
                        'paymentType' => 'Payment Voucher Customer Payment',
                        'history_id' => $invoiceId,
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('customer_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'dr' => array_sum($this->input->post('amountDr')),
                        'cr' => 0,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                        'Accounts_VoucherType_AutoID' => 2,
                        'BranchAutoId' => $this->input->post('BranchAutoId')
                    );
                    $this->db->where('Accounts_VoucherType_AutoID', 2);
                    $this->db->where('history_id', $invoiceId);
                    $this->db->update('client_vendor_ledger', $custLedger);
                    //$this->Common_model->update_data('client_vendor_ledger', $custLedger, 'history_id', $invoiceId);

                endif;

                if (!empty($supid)):
                    $supLedger = array(
                        'ledger_type' => 2,
                        'history_id' => $invoiceId,
                        'paymentType' => 'Payment Voucher supplier payment',
                        'trans_type' => $this->input->post('voucherid'),
                        'client_vendor_id' => $this->input->post('supplier_id'),
                        'amount' => array_sum($this->input->post('amountDr')),
                        'cr' => array_sum($this->input->post('amountDr')),
                        'dr' => 0,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'dist_id' => $this->dist_id,
                        'Accounts_VoucherType_AutoID' => 2,
                        'BranchAutoId' => $this->input->post('BranchAutoId')
                    );
                    $this->db->where('Accounts_VoucherType_AutoID', 2);
                    $this->db->where('history_id', $invoiceId);
                    $this->db->update('client_vendor_ledger', $supLedger);
                    /*$this->Common_model->update_data('client_vendor_ledger', $supLedger, 'history_id', $invoiceId);*/
                endif;

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Payment Voucher ' . ' ' . $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/paymentVoucherPosting/' . $invoiceId));
                } else {
                    $msg = 'Payment Voucher ' . ' ' . $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/paymentVoucherView/' . $invoiceId));
                }


            }
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdate();

        $data['editVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $invoiceId);


//payment voucher Credit Account
        $data['getCreditAccountId'] = $this->Finane_Model->getCreditAccountIdNew($this->dist_id, $invoiceId);


//payment voucher debit account
        $data['getDebitAccountId'] = $this->Finane_Model->getDebitAccountIdNew($this->dist_id, $invoiceId);

        /* echo '<pre>';
         print_r($data['getDebitAccountId']);
         exit();*/

        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);

        /*page navbar details*/
        $data['title'] = get_phrase('Edit Payment Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Payment Voucher Add');
        $data['link_page_url'] = $this->project . '/paymentVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Payment Voucher List');
        $data['second_link_page_url'] = $this->project . '/paymentVoucher';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Payment Voucher');
        $data['third_link_page_url'] = $this->project . '/paymentVoucherView/' . $invoiceId;
        $data['third_link_icon'] = $this->link_icon_edit;
        /*page navbar details*/

        $data['mainContent'] = $this->load->view('distributor/account/payment_voucher/paymentVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function paymentVoucherEdit__old($invoiceId)
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
        $data['mainContent'] = $this->load->view('distributor/account/payment_voucher/paymentVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function journalVoucherAdd()
    {

        if (isPostBack()) {

            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('account[]', 'Payment Type', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = "Required field can't be empty";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/journalVoucherAdd'));

            } else {
                $this->db->trans_start();
                $voucherCondition = array(
                    'AccouVoucherType_AutoID' => 3,
                    //'BranchAutoId' => $this->dist_id,
                );
                $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
                $voucherID = "JV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);


                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $voucherID;
                $data['Narration'] = $this->input->post('narration');
                $data['CompanyId'] = $this->dist_id;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Reference'] = 0;
                $data['AccouVoucherType_AutoID'] = 3;
                $data['IsActive'] = 1;
                $data['Created_By'] = $this->admin_id;
                $data['Created_Date'] = $this->timestamp;
                $general_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $data);


                $account = $this->input->post('account');
                /* Pay account credit */
                $alldr = array();
                foreach ($account as $key => $value) {
                    unset($jv);
                    if ($this->input->post('amountDr')[$key] > 0) {
                        $jv['TypeID'] = 1;
                    } else {
                        $jv['TypeID'] = 2;
                    }
                    $jv['Accounts_VoucherMst_AutoID'] = $general_id;
                    $jv['CHILD_ID'] = $value;
                    $jv['GR_CREDIT'] = $this->input->post('amountCr')[$key];
                    $jv['GR_DEBIT'] = $this->input->post('amountDr')[$key];
                    $jv['Reference'] = $this->input->post('memo')[$key];
                    $jv['IsActive'] = 1;
                    $jv['Created_By'] = $this->dist_id;
                    $jv['Created_Date'] = $this->timestamp;
                    $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $alldr[] = $jv;
                }
                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $alldr);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/journalVoucher'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/journalVoucherView/' . $general_id));
                }
                /* Pay account Credit */
            }
        }


        $condition = array(
            'status' => '1',
            'posted' => '1'
        );
        //$data['accountHeadList'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition);

        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        //echo $this->db->last_query();exit;


        $voucherCondition = array(
            'AccouVoucherType_AutoID' => 3,
            //'BranchAutoId' => $this->dist_id,
        );
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);

        $data['voucherID'] = "JV" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);


        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);


        $data['title'] = 'Add Journal Voucher';
        $data['mainContent'] = $this->load->view('distributor/account/journal/journalVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function journalVoucher()
    {


        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 1,
        );

        // $data['journalVoucher'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition, 'date', 'DESC');

        /*page navbar details*/
        $data['title'] = 'Journal Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Journal Voucher Add';
        $data['link_page_url'] = $this->project . '/journalVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/journal/journalVoucher', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function journalVoucherView($voucherID)
    {


        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 1,
        );


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        //$data['journalVoucher'] = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $voucherID);

        $data['journalVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $voucherID);


        // $data['journalJournal'] = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $voucherID);

        $data['journalJournal'] = $this->Common_model->get_data_list_by_single_column('ac_tb_accounts_voucherdtl', 'Accounts_VoucherMst_AutoID', $voucherID);


        if ($data['journalVoucher']->customer_id):
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['journalVoucher']->customer_id);
        elseif ($data['journalVoucher']->supplier_id):
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['journalVoucher']->supplier_id);
        else:
        endif;
        /*page navbar details*/
        $data['title'] = get_phrase('Journal Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Journal Voucher Add');
        $data['link_page_url'] = $this->project . '/journalVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Journal Voucher List');
        $data['second_link_page_url'] = $this->project . '/journalVoucher';
        $data['second_link_icon'] = $this->link_icon_list;

        if ($data['journalVoucher']->for == 0) {
            $data['third_link_page_name'] = get_phrase('Journal Voucher');
            $data['third_link_page_url'] = $this->project . '/journalVoucherEdit/' . $voucherID;
            $data['third_link_icon'] = $this->link_icon_edit;
        }

        /*page navbar details*/


        $data['title'] = 'Journal Voucher';
        $data['mainContent'] = $this->load->view('distributor/account/journal/journalVoucherView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function journalVoucherEdit($invoiceId)
    {

        /*if (is_numeric($invoiceId)) {
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
        }*/
        if (isPostBack()) {

            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('account[]', 'Payment Type', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/journalVoucherAdd'));
            } else {

                /*   $data = $this->input->post();
                   echo '<pre>';
                   print_r($data);
                   exit();*/
                $this->_save_data_to_accounting_history_table($invoiceId, 'edit', 'Journal Voucher', 'journalVoucherEdit');
                $this->db->trans_start();
                $data['AccouVoucherType_AutoID'] = 3;
                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $this->input->post('voucherid');
                $data['Reference'] = $this->input->post('narration');
                // $data['debit'] = array_sum($this->input->post('amountDr'));\
                $data['IsActive'] = 1;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['CompanyId'] = $this->dist_id;
                $data['Changed_By'] = $this->admin_id;
                $data['Changed_Date'] = date('Y-m-d');


                $test = $this->Common_model->update_data('ac_accounts_vouchermst', $data, 'Accounts_VoucherMst_AutoID', $invoiceId);


                /*$Delete['Changed_By'] = $this->admin_id;
                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update('ac_tb_accounts_voucherdtl', $Delete);

                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $del = $this->db->delete('ac_tb_accounts_voucherdtl');*/


                /* Pay account credit */
                $allDataUpdate = array();
                $allDatainsert = array();
                $account = $this->input->post('account');
                foreach ($account as $key => $value) {


                    $costCondition = array(
                        'Accounts_VoucherMst_AutoID' => $invoiceId,
                        'CHILD_ID' => $value,
                    );
                    $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_tb_accounts_voucherdtl', $costCondition);


                    if ($this->input->post('amountDr')[$key] > 0) {
                        $jv['TypeID'] = 1;
                    } else {
                        $jv['TypeID'] = 2;
                    }

                    if (!empty($checkArray)) {

                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = $this->input->post('amountDr')[$key];
                        $jv['GR_CREDIT'] = $this->input->post('amountCr')[$key];
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memo')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $allDataUpdate[] = $jv;
                        unset($jv);

                    } else {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = $this->input->post('amountDr')[$key];
                        $jv['GR_CREDIT'] = $this->input->post('amountCr')[$key];
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memo')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $allDatainsert[] = $jv;
                        unset($jv);
                    }
                }


                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update_batch('ac_tb_accounts_voucherdtl', $allDataUpdate, 'CHILD_ID');


                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $allDatainsert);


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {

                    $msg = 'Your data can not be Updated.';
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/journalVoucherEdit/' . $invoiceId));
                } else {
                    $msg = 'Your data successfully updated into database.';
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/journalVoucherEdit/' . $invoiceId));
                }
                /* Pay account Credit */
            }
        }
        $costCondition = array(
            'Accounts_VoucherMst_AutoID' => $invoiceId,
            'IsActive' => 1,
        );

        $data['journalVoucher'] = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $costCondition);


        $data['generalledger'] = $this->Common_model->get_data_list_by_single_column('ac_tb_accounts_voucherdtl', 'Accounts_VoucherMst_AutoID', $invoiceId);


        //$data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);

        /*page navbar details*/
        $data['title'] = get_phrase('EditJournal Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Journal Voucher Add');
        $data['link_page_url'] = $this->project . '/journalVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['second_link_page_name'] = get_phrase('Journal Voucher List');
        $data['second_link_page_url'] = $this->project . '/journalVoucher';
        $data['second_link_icon'] = $this->link_icon_list;

        $data['third_link_page_name'] = get_phrase('Journal Voucher View');
        $data['third_link_page_url'] = $this->project . '/journalVoucherView/' . $invoiceId;
        $data['third_link_icon'] = $this->link_icon_edit;


        $data['mainContent'] = $this->load->view('distributor/account/journal/journalVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function load_account_ledgers()
    {
        $ledgerFor = $this->input->post('ledgerFor');
        $group = $this->input->post('group');
        $ledgerId = $this->input->post('ledgerId');

        $data['ledgerFor'] = $ledgerFor;

        $data['accountHeadList'] = $this->Common_model->getAccountHeadUpdateWithCondition($group, $ledgerId);
        return $this->load->view('distributor/ajax/load_account_ledgers', $data);
    }

    public function delete_voucher()
    {
        $voucherType = $this->input->post('voucherType');
        $voucherName = $this->input->post('voucherName');
        $Accounts_VoucherMst_AutoID = $this->input->post('id');

        $error_mes = $voucherName."  ".$this->config->item("delete_error_message");
        $success_mes = $voucherName."  ". $this->config->item("delete_success_message");

        $this->db->trans_start();
        $data['IsActive'] = 0;
        $data['CompanyId'] = $this->dist_id;
        $data['Changed_By'] = $this->admin_id;
        $data['Changed_Date'] = $this->timestamp;
        $this->Common_model->update_data('ac_accounts_vouchermst', $data, 'Accounts_VoucherMst_AutoID', $Accounts_VoucherMst_AutoID);

        $this->_save_data_to_accounting_history_table($Accounts_VoucherMst_AutoID, 'delete', $voucherName, $voucherType);;
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $msg = $error_mes;
            $this->session->set_flashdata('error', $msg);
            echo 1;

        } else {
            $msg = $success_mes;
            $this->session->set_flashdata('success', $msg);
            echo 1;

        }

    }

    private function _save_data_to_accounting_history_table($Accounts_VoucherMst_AutoID, $action = array('edit', 'delete'), $voucher_name = array('Payment Voucher', "Receive Voucher", 'Journal Voucher'), $redrict_page,$invoice_id='null')
    {

        if ($action == "edit") {
            $error_mes = $this->config->item("update_error_message");
            $success_mes = $this->config->item("update_success_message");
        } else {
            $error_mes = $this->config->item("delete_error_message");
            $success_mes = $this->config->item("delete_success_message");
        }


        $this->db->trans_begin();
        $ac_accounts_vouchermst_audit = "create table IF NOT EXISTS ac_accounts_vouchermst_audit(ac_accounts_vouchermst_audit_id int not null auto_increment, PRIMARY KEY (ac_accounts_vouchermst_audit_id)) as select * from ac_accounts_vouchermst where 1=3";
        $this->db->query($ac_accounts_vouchermst_audit);
        $ac_tb_accounts_voucherdtl_audit = "create table IF NOT EXISTS ac_tb_accounts_voucherdtl_audit(ac_accounts_vouchermst_audit_id int not null ) as select * from ac_tb_accounts_voucherdtl where 1=3";
        $this->db->query($ac_tb_accounts_voucherdtl_audit);

        $query = $this->db->field_exists('is_active', 'ac_accounts_vouchermst_audit');
        if ($query != TRUE) {
            $query_ac_accounts_vouchermst_audit = "ALTER TABLE `ac_accounts_vouchermst_audit` ADD `is_active` ENUM('Y','N') NULL , ADD `is_delete` ENUM('Y','N') NULL , ADD `update_by` INT(11) NULL , ADD `update_date` DATETIME NULL , ADD `delete_by` INT(11) NULL , ADD `delete_date` DATETIME NULL ";
            $this->db->query($query_ac_accounts_vouchermst_audit);
            $ac_tb_accounts_voucherdtl_audit = "ALTER TABLE `ac_tb_accounts_voucherdtl_audit` ADD `is_active` ENUM('Y','N') NULL , ADD `is_delete` ENUM('Y','N') NULL , ADD `update_by` INT(11) NULL , ADD `update_date` DATETIME NULL , ADD `delete_by` INT(11) NULL , ADD `delete_date` DATETIME NULL ";
            $this->db->query($ac_tb_accounts_voucherdtl_audit);
        }

        $ac_accounts_vouchermst_old_array = array();
        $stock_old_old_condition = array(
            'Accounts_VoucherMst_AutoID' => $Accounts_VoucherMst_AutoID,

        );
        $ac_accounts_vouchermst_old_array = $this->Common_model->get_data_list_by_many_columns_array('ac_accounts_vouchermst', $stock_old_old_condition);
        foreach ($ac_accounts_vouchermst_old_array as $key => $csm) {
            if ($action == 'edit') {
                $ac_accounts_vouchermst_old_array[$key]['is_active'] = 'Y';
                $ac_accounts_vouchermst_old_array[$key]['is_delete'] = 'N';
                $ac_accounts_vouchermst_old_array[$key]['update_by'] = $this->admin_id;
                $ac_accounts_vouchermst_old_array[$key]['update_date'] = $this->timestamp;;
                $ac_accounts_vouchermst_old_array[$key]['delete_by'] = '';
                $ac_accounts_vouchermst_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $ac_accounts_vouchermst_old_array[$key]['is_active'] = 'N';
                $ac_accounts_vouchermst_old_array[$key]['is_delete'] = 'Y';
                $ac_accounts_vouchermst_old_array[$key]['update_by'] = "";
                $ac_accounts_vouchermst_old_array[$key]['update_date'] = NULL;
                $ac_accounts_vouchermst_old_array[$key]['delete_by'] = $this->admin_id;
                $ac_accounts_vouchermst_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $ac_accounts_vouchermst_audit_id = $this->Common_model->insert_data('ac_accounts_vouchermst_audit', $ac_accounts_vouchermst_old_array[0]);

        $ac_tb_accounts_voucherdtl_old_array = array();
        $ac_tb_accounts_voucherdtl_old_condition = array(
            'Accounts_VoucherMst_AutoID' => $Accounts_VoucherMst_AutoID,
        );
        $ac_tb_accounts_voucherdtl_old_array = $this->Common_model->get_data_list_by_many_columns_array('ac_tb_accounts_voucherdtl', $ac_tb_accounts_voucherdtl_old_condition);
        foreach ($ac_tb_accounts_voucherdtl_old_array as $key => $csm) {
            if ($action == 'edit') {
                $ac_tb_accounts_voucherdtl_old_array[$key]['ac_accounts_vouchermst_audit_id'] = $ac_accounts_vouchermst_audit_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_active'] = 'Y';
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_delete'] = 'N';
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_by'] = $this->admin_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_date'] = $this->timestamp;;
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_by'] = '';
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_date'] = NULL;
            } elseif ($action == 'delete') {
                $ac_tb_accounts_voucherdtl_old_array[$key]['ac_accounts_vouchermst_audit_id'] = $ac_accounts_vouchermst_audit_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_active'] = 'N';
                $ac_tb_accounts_voucherdtl_old_array[$key]['is_delete'] = 'Y';
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_by'] = "";
                $ac_tb_accounts_voucherdtl_old_array[$key]['update_date'] = NULL;
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_by'] = $this->admin_id;
                $ac_tb_accounts_voucherdtl_old_array[$key]['delete_date'] = $this->timestamp;
            }
        }
        $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl_audit', $ac_tb_accounts_voucherdtl_old_array);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = $voucher_name . ' ' . $error_mes;
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/' . $redrict_page . '/' . $Accounts_VoucherMst_AutoID));
        } else {
            $this->db->trans_commit();
            // $msg = $voucher_name . ' ' . $success_mes;
            // $this->session->set_flashdata('success', $msg);
            // redirect(site_url($this->project . '/'.$redrict_page.'/' . $Accounts_VoucherMst_AutoID));
        }

        $this->db->trans_begin();
        $DeleteCondition = array(
            'Accounts_VoucherMst_AutoID' => $Accounts_VoucherMst_AutoID
        );
        $this->Common_model->delete_data_with_condition('ac_tb_accounts_voucherdtl', $DeleteCondition);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = $voucher_name . ' ' . $error_mes;
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/' . $redrict_page . '/' . $Accounts_VoucherMst_AutoID));
        } else {
            $this->db->trans_commit();
            // $msg = $voucher_name . ' ' . $success_mes;
            // $this->session->set_flashdata('success', $msg);
            // redirect(site_url($this->project . '/'.$redrict_page.'/' . $Accounts_VoucherMst_AutoID));
        }
    }

}