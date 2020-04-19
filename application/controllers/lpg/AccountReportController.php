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

    public function generalLedger($account = null)
    {
        $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        if (isPostBack()) {
            $account = $this->input->post('accountHead');
            $group = $this->input->post('group');
            $branch_id = isset($_POST['branch_id'])?$this->input->post('branch_id'):0;

            $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['gl_data'] = $this->Accounts_model->get_general_ledger_summery($group, $account, $from_date, $to_date, $branch_id);

            if ($this->input->post('is_print') == 1) {
                $footer = '';
                $data['start_date']=$this->input->post('start_date');
                $data['end_date']=$this->input->post('end_date');
                $footer = $this->load->view('distributor/account/report/pdf/footer', $data, true);
                $output_type = '';
                $header = $this->load->view('distributor/account/report/pdf/header', $data, true);
                $this->load->library('tec_mpdf', '', 'pdf');

                $content = $this->load->view('distributor/account/report/generalLedger_pdf', $data, true);
                $this->pdf->generate($content, $name = 'download.pdf', $output_type, $footer, $margin_bottom = null, $header, $margin_top = '45', $orientation = 'l');
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


    public function generalLedger2($account = null)
    {
        $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

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


        if (isPostBack()) {
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
            $this->db->where('BranchAutoId', $branch_id);
            $this->db->where('CHILD_ID', $account);
            $this->db->where('IsActive', 1);
            $this->db->where('date <', $from_date);
            $query_pvs = $this->db->get()->row();


            $total_pvsdebit += $query_pvs->totalDebit;
            $total_pvscredit += $query_pvs->totalCredit;
            $data['dr_pvsbal'] = $dr_pvsbal = $total_pvsdebit + $total_opendebit;
            $data['cr_pvsbal'] = $cr_pvsbal = $total_pvscredit + $total_opencredit;
            $data['total_pvsbalance'] = $query_pvs->totalDebit - $query_pvs->totalCredit;

            $data['gl_data'] = $this->Finane_Model->get_general_ledger_data($branch_id, $account, $from_date, $to_date);



            if ($this->input->post('is_print') == 1) {
                $footer = '';
                $data['gl_data'] = array();
                $account = $this->input->post('Ledger_id');
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
                $this->db->where('BranchAutoId', $branch_id);
                $this->db->where('CHILD_ID', $account);
                $this->db->where('IsActive', 1);
                $this->db->where('date <', $from_date);
                $query_pvs = $this->db->get()->row();


                $total_pvsdebit += $query_pvs->totalDebit;
                $total_pvscredit += $query_pvs->totalCredit;
                $data['dr_pvsbal'] = $dr_pvsbal = $total_pvsdebit + $total_opendebit;
                $data['cr_pvsbal'] = $cr_pvsbal = $total_pvscredit + $total_opencredit;
                $data['total_pvsbalance'] = $query_pvs->totalDebit - $query_pvs->totalCredit;


                $data['gl_data'] = $this->Finane_Model->get_general_ledger_data($branch_id, $account, $from_date, $to_date);
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

                /*echo "<pre>";
                print_r($data);exit;*/

                $content = $this->load->view('distributor/account/report/generalLedger2_pdf', $data, true);
                $this->pdf->generate($content, $name = 'download.pdf', $output_type, $footer, $margin_bottom = null, $header, $margin_top = '40', $orientation = '');
            }
        }


        $list = $this->Finane_Model->getExpenseHead();
        /*page navbar details*/
        $data['title'] = 'General Ledger';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'General Ledger List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['pageTitle'] = 'General Ledger';
        $data['mainContent'] = $this->load->view('distributor/account/report/generalLedger_BK', $data, true);
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

    /**
     * @return mixed
     */
    public function day_book_report($start_date = '')
    {
        $start_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : '1';
        if ($start_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            //$data['getalldayBookSummery'] = $this->Accounts_model->getalldayBookSummery($start_date, $branch_id);
            $data['getalldayBookSummery'] = $this->Accounts_model->getalldayBookSummeryNew($start_date, $branch_id);
            /*echo "<pre>";
            echo $this->db->last_query();
            print_r($data['getalldayBookSummery']);exit;*/
            $data['getalldayBookDetails'] = $this->Accounts_model->getalldayBookDetails($start_date, $branch_id);
            /*echo"<pre>";
            echo $this->db->last_query();
            print_r($data['getalldayBookDetails']);
            exit;*/

        }


        $data['dayBookCofig'] = $this->Accounts_model->getalldayBook();

        $data['title'] = 'Day Book Report';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $data['pageTitle'] = 'General Ledger';
        $data['mainContent'] = $this->load->view('distributor/account/report/day_book_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function day_book_report_configuration()
    {
        $data['title'] = 'Day Book Report';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'General Ledger List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['pageTitle'] = 'General Ledger';
        $data['mainContent'] = $this->load->view('distributor/account/report/generalLedger_BK', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    /**
     * @return mixed
     */
    public function saveBankBookConfig()
    {
        if ($_POST['action_type'] == 'add') {
            $data['acc_group_id'] = $acc_group_id = $this->input->post('daybook');


            $this->db->select('count(*) as number_of_result');
            $this->db->from("day_book_report_config");
            $this->db->where('day_book_report_config.acc_group_id', $acc_group_id);
            $number_of_result = $this->db->get()->row();
            log_message('error', 'thsi is nahid' . print_r($number_of_result->number_of_result, true));

            if ($number_of_result->number_of_result > 0) {
                echo '0';
            } else {
                $this->db->trans_start();
                $this->db->insert('day_book_report_config', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    echo '0';
                } else {
                    echo '1';
                }
            }


        } else if ($_POST['action_type'] == 'delete') {
            $id = $this->input->post('id');
            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->delete('day_book_report_config');
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                echo '0';
            } else {
                echo '1';
            }


        }
    }

    /**
     * @return mixed
     */
    public function getconfig()
    {
        $this->db->select('day_book_report_config.id,ac_account_ledger_coa.id as groupId,ac_account_ledger_coa.parent_name,ac_account_ledger_coa.code');
        $this->db->from("day_book_report_config");
        $this->db->join('ac_account_ledger_coa', 'ac_account_ledger_coa.id=day_book_report_config.acc_group_id');
        $this->db->where('ac_account_ledger_coa.level_no ', 3);
        $this->db->order_by('day_book_report_config.id ', 'desc');
        $this->db->limit('1');
        $users = $this->db->get()->row();


        $dayBookCofig = $this->Accounts_model->getalldayBook();

        if (!empty($dayBookCofig)) {
            $count = 0;
            foreach ($dayBookCofig as $key => $value): $count++;
                echo '<tr>';
                echo '<td>#' . $count . '</td>';
                echo '<td>' . $value->parent_name . ' [ ' . $value->code . ' ]' . '</td>';

                echo '<td><a href="javascript:void(0);" class="btn btn-danger pull-left" onclick="return confirm(\'Are you sure to delete data?\')?saveconfigaration(\'delete\',\'' . $value->id . '\'):false;"><i class="fa fa-remove"></i></a></td>';
                echo '</tr>';
            endforeach;
        } else {
            echo '<tr><td colspan="5">No user(s) found......</td></tr>';
        }
    }
}