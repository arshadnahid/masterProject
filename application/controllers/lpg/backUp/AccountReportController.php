<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 10/30/2019
 * Time: 10:59 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class AccountReportController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Accounts_model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('AccountReport_model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
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
    public  function generalLedger($account = null)
    {
        $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        if (isPostBack()) {


            //echo '<pre>';
            //print_r($_POST);exit;
            $account = $this->input->post('accountHead');
            $group = $this->input->post('group');
            $branch_id = $this->input->post('branch_id');
            $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $dist_id = $this->dist_id;
            $total_pvsdebit = 0;
            $total_pvscredit = 0;
            $total_opendebit = $this->Finane_Model->opening_balance_dr($dist_id, $account, $branch_id, $from_date, $to_date);
            $total_opencredit = $this->Finane_Model->opening_balance_cr($dist_id, $account, $branch_id, $from_date, $to_date);
            $this->db->select('SUM(GR_DEBIT) AS totalDebit,SUM(GR_CREDIT) as totalCredit');
            $this->db->from('ac_tb_accounts_voucherdtl');
            $this->db->where('BranchAutoId', $dist_id);
            $this->db->where('CHILD_ID', $account);
            $this->db->where('date <', $from_date);
            $query_pvs = $this->db->get()->row();
            $total_pvsdebit += $query_pvs->totalDebit;
            $total_pvscredit += $query_pvs->totalCredit;
            $data['dr_pvsbal'] = $dr_pvsbal = $total_pvsdebit + $total_opendebit;
            $data['cr_pvsbal'] = $cr_pvsbal = $total_pvscredit + $total_opencredit;
            $data['total_pvsbalance'] = $dr_pvsbal - $cr_pvsbal;
            $data['gl_data'] = $this->Finane_Model->get_general_ledger_data($branch_id, $account, $from_date, $to_date);
            if ($this->input->post('is_print') == 1) {
                $footer = '';
                $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
                $footer1 = '<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>

<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>

<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>

<td width="33%" style="text-align: right; ">My document</td>

</tr></table>';
                $output_type = '';
                $header = '<table class="table table-responsive">
                    <tr>
                    <td style="text-align:center;"><h3>' . $companyInfo->companyName . '</h3><span>' . $companyInfo->address . '</span><br><strong>' . get_phrase('Phone') . ': </strong>' . $companyInfo->phone . '<br><strong>' . get_phrase('Email') . ': </strong>' . $companyInfo->email . '<br><strong>' . get_phrase('Website') . ': </strong>' . $companyInfo->website . '<br><strong>' . 'General Ledger Report
' . '</strong><strong> ' . get_phrase('') . ' :</strong>From ' . $from_date . ' To ' . $to_date . '</td></tr></table>';
                $this->load->library('tec_mpdf', '', 'pdf');
                //$header="This is hadder";
                $content = $this->load->view('distributor/finance/report/generalLedger_pdf', $data, true);
                $this->pdf->generate($content, $name = 'download.pdf', $output_type, $footer, $margin_bottom = null, $header, $margin_top = '40', $orientation = 'l');
            }
        }
        if (!empty($account)) {
            $data['dist_id'] = $this->dist_id;
            $data['account'] = $account;
        } else {
            $data['fromdate'] = 0;
            $data['todate'] = 0;
            $data['account'] = 0;
        }

        $this->db->select('id,parent_name,code,level_no');
        $this->db->from("ac_account_ledger_coa");
        $this->db->where('posted !=', 1);

        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result();


        $data['accountHeadList'] = $result;
        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);
        $list = $this->Finane_Model->getExpenseHead();
        /*page navbar details*/
        $data['title'] = 'General Ledger';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'General Ledger List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['pageTitle'] = 'General Ledger';
        $data['mainContent'] = $this->load->view('distributor/account/report/generalLedger', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function cashBook()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['title'] = 'Cash Book';
        $data['mainContent'] = $this->load->view('distributor/account/report/cashBook', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function bankBook()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['title'] = 'Bank Book';
        $data['mainContent'] = $this->load->view('distributor/account/report/bankBook', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function customerLedger()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['customerList'] = $this->Common_model->get_data_list_by_single_column('customer', 'dist_id', $this->dist_id, 'customerName', 'ASC');
        $data['title'] = get_phrase('Customer Ledger');
        $data['pageTitle'] = get_phrase('Customer Ledger');
        $data['page_type'] = get_phrase($this->page_type);
        $data['mainContent'] = $this->load->view('distributor/account/report/customerLedger', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}