<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class employeeVoucherController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('HR_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Pos_Model');
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



    public function employeeVoucher()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 3,
        );

        $data['title'] = 'Employee Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Employee Voucher Add';
        $data['link_page_url'] = $this->project . '/employeeVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucher', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function employeeList()
    {

        $this->HR_Model->filterData('ac_accounts_vouchermst',
            array('ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            array('ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            array('ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            $this->dist_id);



        $list = $this->HR_Model->get_employee_datatables();

        // log_message('error', 'Hi mamun receiveList ' . print_r($list, true));


        $data = array();
        $no = $_POST['start'];
        foreach ($list as $receive) {
            $no++;
            $row = array();
            $row[] = $no;

            $row[] = '<a title="View Employee Voucher" href="">' . $receive->Accounts_Voucher_No . '</a></td>';
            $row[] = date('M d, Y', strtotime($receive->Accounts_Voucher_Date));
            $row[] = $receive->Accounts_VoucherType;//$payment->name;
            $row[] = $receive->branch_name;
            $row[] = $receive->name;
            $row[] = $receive->Narration;

            $row[] = number_format((float)$receive->amount, 2, '.', ',');





            $row[] = '
              <a class="btn btn-icon-only red financeEditPermission" href="' . site_url($this->project . '/employeeVoucherEdit/' . $receive->Accounts_VoucherMst_AutoID) . '">
            <i class="ace-icon fa fa-pencil bigger-130"></i></a>
            <a class="btn btn-icon-only blue" href="' . site_url($this->project . '/employeeVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>

    ';
            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->HR_Model->count_all_receive(),
            "recordsFiltered" => $this->HR_Model->count_filtered_receive(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function employeeVoucherAdd($postingId = null)
    {
        $this->load->helper('create_receive_voucher_no_helper');
        if (isPostBack()) {
//validation rules set here.
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('accountCr[]', 'Account Debit', 'required');

            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/employeeVoucherAdd'));
            } else {

                $this->db->trans_start();


                $voucherCondition = array(
                    'AccouVoucherType_AutoID' => 5,
                    //'BranchAutoId' => $this->dist_id,
                );
                $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
                $voucherID = "EPS" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);

                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $voucherID;
                $data['Narration'] = $this->input->post('narration');
                $data['CompanyId'] = $this->dist_id;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Reference'] = 0;
                $data['AccouVoucherType_AutoID'] = 8;
                $data['IsActive'] = 1;
                $data['Created_By'] = $this->admin_id;
                $data['Created_Date'] = $this->timestamp;

                $general_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $data);

                $acountDr = $this->input->post('accountCr'); // account Head bellow

                $cashCrId = $this->input->post('cash');
                $bankId = $this->input->post('accountDr');
                $cashCr = $this->input->post('cashCr');
                $bankCr = $this->input->post('bankCr');

                /* Pay account DR */
                if (!empty($cashCrId))
                {
                    $dr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $cashCrId;
                    $dr['GR_CREDIT'] = $cashCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Created_By'] = $this->dist_id;
                    $dr['Created_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $dr);

                }
                if (!empty($bankId))
                {
                    $dr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $bankId;
                    $dr['GR_CREDIT'] = $bankCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Created_By'] = $this->dist_id;
                    $dr['Created_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $dr);

                }



                $allCr = array();
                foreach ($acountDr as $key => $value) {
                    unset($cr);
                    $cr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $cr['TypeID'] = 2;
                    $cr['CHILD_ID'] = $this->input->post('accountCr')[$key];
                    $cr['GR_CREDIT'] = 0;
                    $cr['Reference'] = $this->input->post('memoCr')[$key];
                    $cr['GR_DEBIT'] = $this->input->post('amountCr')[$key];
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


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeVoucherAdd/'));
                } else {
                    $msg = $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeVoucherAdd/' . $general_id));

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

        $conditionaccountHeadListByCash = array(
            'status' => '1',
            'posted' => '1',
            'parent_id'=>'28'
        );

        $data['accountHeadListByCash'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByCash);

        $conditionaccountHeadListByBank = array(
            'status' => '1',
            'posted' => '1',
            'parent_id'=>'32'
        );

        $data['accountHeadListByBank'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByBank);

        $voucherCondition = array(
            'AccouVoucherType_AutoID' => 8,
            'BranchAutoId' => $this->dist_id,
        );

        $data['voucherID'] = create_receive_voucher_no();
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
        $data['voucherID'] = "EPS" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);


        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);


        $data['title'] = 'Add Employee Voucher';
        /*page navbar details*/
        $data['title'] = 'Add Employee Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Employee Voucher List';
        $data['link_page_url'] = $this->project . '/employeeVoucher';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function employeeVoucherEdit($invoiceId)
    {


        if (isPostBack()) {
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('accountCr[]', 'Account Debit', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/receiveVoucherAdd'));
            } else {
                $this->db->trans_start();

                $data['AccouVoucherType_AutoID'] = 8;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $this->input->post('voucherid');
                // $data['AccouVoucherType_AutoID'] = $this->input->post('payType');
                $data['Narration'] = $this->input->post('narration');
                $data['IsActive'] = 1;
                $data['CompanyId'] = $this->dist_id;
                $data['Changed_By'] = $this->admin_id;
                $data['Changed_Date'] = $this->timestamp;


                $this->Common_model->update_data('ac_accounts_vouchermst', $data, 'Accounts_VoucherMst_AutoID', $invoiceId);


                $acountDr = $this->input->post('accountCr'); // account Head bellow

                $cashCrId = $this->input->post('cash');
                $bankId = $this->input->post('accountDr');
                $cashCr = $this->input->post('cashCr');
                $bankCr = $this->input->post('bankCr');


                $allDataUpdate = array();
                $allDatainsert = array();


                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $del=$this->db->delete('ac_tb_accounts_voucherdtl');


                /*   $Delete['IsActive'] = 0;
                   $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                   $this->db->update('ac_tb_accounts_voucherdtl', $Delete);*/

                $cashCrCondition = array(
                    'TypeID' => 1,
                    'Accounts_VoucherMst_AutoID' => $invoiceId,
                    'CHILD_ID' => $this->input->post('cash')
                );

                if (!empty($cashCrId))
                {
                    $dr['Accounts_VoucherMst_AutoID'] = $invoiceId;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $cashCrId;
                    $dr['GR_CREDIT'] = $cashCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Changed_By'] = $this->admin_id;
                    $dr['Changed_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $dr, $cashCrCondition);

                }
                $bankCrCondition = array(
                    'TypeID' => 1,
                    'Accounts_VoucherMst_AutoID' => $invoiceId,
                    'CHILD_ID' => $this->input->post('accountDr')
                );

                if (!empty($bankId))
                {
                    $dr['Accounts_VoucherMst_AutoID'] = $invoiceId;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $bankId;
                    $dr['GR_CREDIT'] = $bankCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Changed_By'] = $this->admin_id;
                    $dr['Changed_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $dr, $bankCrCondition);

                }

                foreach ($acountDr as $key => $value) {
                    $costCondition = array(
                        'Accounts_VoucherMst_AutoID' => $invoiceId,
                        'CHILD_ID' => $value,
                    );
                    $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_tb_accounts_voucherdtl', $costCondition);

                    if (!empty($checkArray)) {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = $this->input->post('amountCr')[$key];
                        $jv['GR_CREDIT'] = 0;
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memo')[$key];
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
                        $jv['GR_DEBIT'] = $this->input->post('amountCr')[$key];
                        $jv['GR_CREDIT'] = 0;
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
                    redirect(site_url($this->project . '/employeeVoucherAdd/'));
                } else {

                    $msg = $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeVoucherView/' . $invoiceId));

                }
            }
        }

        $data['receiveVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $invoiceId);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();

        $conditionaccountHeadListByCash = array(
            'status' => '1',
            'posted' => '1',
            'parent_id'=>'28'
        );

        $data['accountHeadListByCash'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByCash);

        $conditionaccountHeadListByBank = array(
            'status' => '1',
            'posted' => '1',
            'parent_id'=>'32'
        );

        $data['accountHeadListByBank'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByBank);

        $data['getDebitAccountId'] = $this->HR_Model->getDebitAccountIdEmployeeVoucherNew($this->dist_id, $invoiceId);

        $data['getCreditAccountId'] = $this->HR_Model->getCreditAccountIdEmployeeVoucherNew($this->dist_id, $invoiceId);

        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);


        /*page navbar details*/
        $data['title'] = get_phrase('Edit Employee Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Employee Voucher');
        $data['link_page_url'] = $this->project . '/employeeVoucherAdd';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase(' Employee Voucher');
        $data['second_link_page_url'] = $this->project . '/employeeVoucher';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('View Employee Voucher');
        $data['third_link_page_url'] = $this->project . '/employeeVoucherView/' . $invoiceId;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/

        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function employeeVoucherView($voucherID)
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
        $data['title'] = 'Employee Voucher';


        /*page navbar details*/
        $data['title'] = get_phrase('Employee Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Employee Voucher');
        $data['link_page_url'] = $this->project . '/employeeVoucherAdd';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase('Employee Voucher');
        $data['second_link_page_url'] = $this->project . '/employeeVoucher';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';

        //$data['third_link_page_url'] = $this->project . '/receiveVoucherEdit/' . $voucherID;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucherView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);


    }

}
