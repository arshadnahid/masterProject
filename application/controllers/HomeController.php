<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends CI_Controller
{

    private $timestamp;
    public $admin_id;
    public $dist_id;
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
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }



    public function moduleDashboard()
    {
        if (empty($admin_id)) {
            //$this->session->set_userdata('last_page', current_url());
            //redirect(base_url(), 'refresh');
        }







        $data['accountReceiable'] = $accountReceiable = $this->Dashboard_Model->accountBalance(58,$searchDay='');

        $data['accountPayable'] = $accountPayable = $this->Dashboard_Model->accountBalance(50,$searchDay='');

        $data['cashInHand'] = $cashInHand = $this->Dashboard_Model->accountBalance(54,$searchDay='');
        $data['totalSalesAmount'] = $totalSales = $this->Dashboard_Model->getTotalSales($searchDay='');
        $data['inventoryAmount']=$this->Dashboard_Model->getinventoryAmount($searchDay='');
        /*echo "<pre>";
        print_r($data);exit;*/
        $condition = array(
            'parentId' => 55,
            'dist_id' => $this->dist_id
        );
        $cashAtBankList = $this->Common_model->get_data_list_by_many_columns('generaldata', $condition);
        //  dumpVar($cashAtBankList);
        /* Cash at bank */
        $total_balance = '';
        foreach ($cashAtBankList as $key => $row_cma):
            $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
            $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
            $total_debit = '';
            $total_credit = '';
            $accountBalance = $this->Finane_Model->accountBalance($row_cma->chartId);
            $this->db->select("sum(debit) as debit,sum(credit) as credit");
            $this->db->from("generalledger");
            $this->db->where('dist_id', $this->dist_id);
            $this->db->where('account', $row_cma->chartId);
            $result = $this->db->get()->row();
            $total_debit += $result->debit;
            $total_credit += $result->credit;
            $total_debit += $total_opendebit;
            $total_credit += $total_opencredit;
            $total_balance += $total_debit - $total_credit;
        endforeach;
        $data['totalCashAtBank'] = $total_balance;



        /*date wise sales  graph start*/
        $date_wise_sales = $this->Dashboard_Model->day_wise_sales_grap($this->dist_id);
        $date_wise_sales_array = array();
        array_push($date_wise_sales_array, array('Sales', 'Amount'));
        foreach ($date_wise_sales as $key => $value) {
            $point = array($value->day, $value->amount);
            array_push($date_wise_sales_array, $point);
        }
        $data['date_wise_sales_array'] = json_encode($date_wise_sales_array, JSON_NUMERIC_CHECK);
        /*date wise sales  graph end*/


        /*month wise sales  graph start*/
        $month_wise_sales = $this->Dashboard_Model->month_wise_sales($this->dist_id);
        $month_wise_sales_array = array();
        foreach ($month_wise_sales as $key => $value) {
            $point = array($value->month_name, $value->amount);
            array_push($month_wise_sales_array, $point);
        }
        $data['month_wise_sales_array'] = json_encode($month_wise_sales_array, JSON_NUMERIC_CHECK);
        /*month wise sales  graph end*/

        /*month wise purchase graph start*/
        $month_wise_purchase = $this->Dashboard_Model->month_wise_purchase($this->dist_id);
        $month_wise_purchase_array = array();
        foreach ($month_wise_purchase as $key => $value) {
            $point = array($value->month_name, $value->amount);
            array_push($month_wise_purchase_array, $point);
        }
        $data['month_wise_purchase_array'] = json_encode($month_wise_purchase_array, JSON_NUMERIC_CHECK);
        /*month wise purchase graph end*/

        /* ----page_type--- is for  active the selected module name*/
        $data['page_type']='dashboard';

        $data['adminName'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $this->admin_id)->name;
        $data['title'] = 'Admin Dashbord';
        $data['mainContent'] = $this->load->view('distributor/dashboard', $data, true);
        $this->load->view('distributor/masterDashboard', $data);

    }
     function get_menu_list() {
        $data['user_id'] = $this->input->post('user_id');
        $module = array(1001, 1002, 1003, 1004, 1005);
        $setupCondition = array(
            'parent_id' => '1001',
            'active' => '1',
        );
        $data['systemMenu'] = $this->Common_model->get_data_list_by_many_columns('navigation', $setupCondition);
        $invenCondition = array(
            'parent_id' => '1002',
            'active' => '1',
        );
        $data['inventoryMenu'] = $this->Common_model->get_data_list_by_many_columns('navigation', $invenCondition);
        $saleCondition = array(
            'parent_id' => '1003',
            'active' => '1',
        );
        $data['salesMenu'] = $this->Common_model->get_data_list_by_many_columns('navigation', $saleCondition);
        $accountCondition = array(
            'parent_id' => '1004',
            'active' => '1',
        );
        $data['accountMenu'] = $this->Common_model->get_data_list_by_many_columns('navigation', $accountCondition);
        return $this->load->view('distributor/ajax/menuListShow', $data);
    }




}
