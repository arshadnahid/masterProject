<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends CI_Controller
{

    private $timestamp;
    public $admin_id;
    public $dist_id;

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
    }

    public function loadCalculator()
    {
        return $this->load->view('distributor/ajax/calculator');
    }

    public function dbExport()
    {
        if (isPostBack()) {
            $format = $this->input->post('format');
            $this->load->dbutil();
            $db_name = 'dipsyl ' . date('F d, Y _ g-i-s A') . '.' . $format;
            $prefs = array('format' => $format, 'filename' => $db_name);
            $backup = &$this->dbutil->backup($prefs);
            $save = 'C:\SoftwareBackup/' . $db_name;
            $this->load->helper('file');
            //write_file($save, $backup);
            $this->load->helper('download');
            force_download($db_name, $backup);
            $this->session->set_flashdata('success', 'Database has been backed up into' . $save);
            redirect('common/dbExport');
        }
        $data['title'] = 'Database Backup';
        $data['dashboardContent'] = $this->load->view('dbBackup/dbExport', $data, TRUE);
        $this->load->view('dashboard/master_dashboard_panel', $data);
    }

    public function getIncentiveMonthWise()
    {
        if ($this->input->is_ajax_request()) {
            $monthId = $this->input->post('monthId');
            $monthNyear = explode('-', $monthId);
            $startDate = $monthNyear[0] . '-' . $monthNyear[1] . '-' . '01';
            $endDate = $monthNyear[0] . '-' . $monthNyear[1] . '-' . '31';
            $data['IncentiveList'] = $this->Dashboard_Model->getIncentiveCompany($startDate, $endDate, $this->dist_id);
            $this->load->view('distributor/ajax/incentive', $data);
        }
    }

    public function getTodaySummary()
    {
        if ($this->input->is_ajax_request()) {
            $searchDay = $this->input->post('searchDay');
            $data['todaysSalesList'] = $this->Dashboard_Model->getTodaySales($searchDay, $this->dist_id);
            $data['todaysPurchases'] = $this->Dashboard_Model->getTodayPurchases($searchDay, $this->dist_id);
            $data['todaysPayment'] = $this->Dashboard_Model->getTodaysPayment($searchDay, $this->dist_id);
            $data['todaysReceive'] = $this->Dashboard_Model->getTodaysReceive($searchDay, $this->dist_id);
            $this->load->view('distributor/ajax/showTodaySummary', $data);
        }
    }

    public function getCompanySummary()
    {
        if ($this->input->is_ajax_request()) {
            $searchDay = $this->input->post('searchDay');

            //dumpVar($_POST);

            $data['accountReceiable'] = $accountReceiable = $this->Finane_Model->accountBalance(58, $searchDay);
            $data['accountPayable'] = $accountPayable = $this->Finane_Model->accountBalance(50, $searchDay);
            $data['cashInHand'] = $cashInHand = $this->Finane_Model->accountBalance(54, $searchDay);

            //echo $this->db->last_query();die;

            $data['totalSalesAmount'] = $totalSales = $this->Dashboard_Model->getTotalSales($searchDay);
            $allProduct = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
            $totalProductPrice = '';
            foreach ($allProduct as $key => $value) {
                $productQty = $this->Dashboard_Model->getInventoryStock($this->dist_id, $value->product_id, $searchDay);
                $productAvgPrice = $this->Dashboard_Model->getProductAvgPrice($this->dist_id, $value->product_id);
                if (!empty($productQty)) {
                    $totalProductPrice += $productQty * $productAvgPrice;
                }
            }
            $data['inventoryAmount'] = $totalProductPrice;
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
            $this->load->view('distributor/ajax/companySummary', $data);
        }
    }

    public function moduleDashboard()
    {
        if (empty($admin_id)) {
            //$this->session->set_userdata('last_page', current_url());
            //redirect(base_url(), 'refresh');
        }
        $month_wise_sales = $this->Dashboard_Model->month_wise_sales($this->dist_id);
        $month_wise_purchase = $this->Dashboard_Model->month_wise_purchase($this->dist_id);






        $month_wise_sales_array = array();
        foreach ($month_wise_sales as $key => $value) {
            $point = array($value->month_name, $value->amount);
            array_push($month_wise_sales_array, $point);
        }
        $data['month_wise_sales_array'] = json_encode($month_wise_sales_array, JSON_NUMERIC_CHECK);

        $month_wise_purchase_array = array();
        foreach ($month_wise_purchase as $key => $value) {
            $point = array($value->month_name, $value->amount);
            array_push($month_wise_purchase_array, $point);
        }
        $data['month_wise_purchase_array'] = json_encode($month_wise_purchase_array, JSON_NUMERIC_CHECK);



        $data['page_type']='dashboard';

        $data['adminName'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $this->admin_id)->name;
        $data['title'] = 'Admin || Module';
        $data['mainContent'] = $this->load->view('distributor/dashboard', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
        //
    }

    public function adminLoginHistory()
    {
        $data['adminInfo'] = $this->Common_model->get_data_list_by_single_column('adminloghistory', 'distId', $this->dist_id, 'logId', 'DESC');
        $data['title'] = 'Admin Login History';
        $data['mainContent'] = $this->load->view('distributor/adminLoginHistory', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function dashboard($moduleId = null)
    {
        if (!empty($moduleId)) {
            $_SESSION['moduleList'] = $moduleId;
        }
        $condition = array(
            'admin_id' => $this->admin_id,
            'navigation_id' => '104',
        );
        $data['companySummaryPrmission'] = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
        $condition = array(
            'admin_id' => $this->admin_id,
            'navigation_id' => '105',
        );
        $data['todaySummaryPermission'] = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
        $condition = array(
            'admin_id' => $this->admin_id,
            'navigation_id' => '106',
        );
        $data['dailySummaryPermission'] = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
        $condition = array(
            'admin_id' => $this->admin_id,
            'navigation_id' => '107',
        );
        $data['companySummaryGrapePermission'] = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
        $condition = array(
            'admin_id' => $this->admin_id,
            'navigation_id' => '108',
        );
        $data['inventoryProductStockPermission'] = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
        $condition = array(
            'admin_id' => $this->admin_id,
            'navigation_id' => '109',
        );
        $data['topSaleProductStockPermission'] = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);

        $condition = array(
            'admin_id' => $this->admin_id,
            'navigation_id' => '119',
        );
        $data['incentivePermition'] = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);

        $data['topSalesProduct'] = $topSalesProduct = $this->Dashboard_Model->getTopSalesProduct($this->dist_id);
        $data['accountReceiable'] = $accountReceiable = $this->Finane_Model->accountBalance(58);
        $data['accountPayable'] = $accountPayable = $this->Finane_Model->accountBalance(50);
        //dumpVar($data['accountPayable']);
        $data['cashInHand'] = $cashInHand = $this->Finane_Model->accountBalance(54);
        $data['totalSalesAmount'] = $totalSales = $this->Dashboard_Model->getTotalSales();

        $allProduct = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $totalProductPrice = '';
        foreach ($allProduct as $key => $value) {
            $productQty = $this->Dashboard_Model->getInventoryStock($this->dist_id, $value->product_id);
            $productAvgPrice = $this->Dashboard_Model->getProductAvgPrice($this->dist_id, $value->product_id);
            if (!empty($productQty)) {
                $totalProductPrice += $productQty * $productAvgPrice;
            }
        }
        $data['inventoryAmount'] = $totalProductPrice;
        /* Get inventory stock */
        $totalStockValue = '';
        $allProductStock = array();
        foreach ($allProduct as $key => $value) {
            $productStock = $this->Dashboard_Model->getInventoryStock($this->dist_id, $value->product_id);
            if (!empty($productStock)):
                $totalStockValue += $productStock;
                $allProductStock[] = $productStock . '-' . $value->productName;
            endif;
        }
        $data['inventoryAllStock'] = $allProductStock;
        /* get inventory stock */
        //echo $totalStockValue;die;
        $condition = array(
            'parentId' => 55,
            'dist_id' => $this->dist_id
        );
        $cashAtBankList = $this->Common_model->get_data_list_by_many_columns('generaldata', $condition);
        // dumpVar($cashAtBankList);
        /* Cash at bank */
        $total_balance = '';
        foreach ($cashAtBankList as $key => $row_cma):
            // dumpVar($row_cma);
            $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
            $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
            $total_debit = '';
            $total_credit = '';
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
        // dumpVar($total_balance);
        $data['totalCashAtBank'] = $total_balance;
        /* Cash at continure */
        $totalSales = array();
        for ($i = 1; $i < 32; $i++) {
            if ($i < 10) {
                $date = date('Y-m-0' . $i);
            } else {
                $date = date('Y-m-' . $i);
            }
            $orgDate = date('Y-m-d');
            if ($date <= $orgDate) {
                $amount = $this->Common_model->getDailySalesAmount($date, $this->dist_id);
                $totalSales[] = isset($amount) ? $amount : null;
            }
        }
        $totalPurchases = array();
        for ($i = 1; $i < 32; $i++) {
            if ($i < 10) {
                $date = date('Y-m-0' . $i);
            } else {
                $date = date('Y-m-' . $i);
            }
            $orgDate = date('Y-m-d');
            if ($date <= $orgDate) {
                $amount = $this->Common_model->getDailyPurchasesAmount($date, $this->dist_id);
                $totalPurchases[] = isset($amount) ? $amount : null;
            }
        }
        $data['fullMonthPurchasesList'] = $totalPurchases;
        $data['fullMonthSalesList'] = $totalSales;
        $data['title'] = 'Distributor || Dashboard';
        $data['mainContent'] = $this->load->view('distributor/dashboard', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function adminAccess()
    {
        $data['title'] = 'Admin || Access';
        $data['mainContent'] = $this->load->view('distributor/dashboard', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function userAccess()
    {
        $array = array(
            'status' => 1,
            'distributor_id' => $this->dist_id,
            'accessType' => 2,
        );
        $data['title'] = 'User || Access';
        $data['adminList'] = $this->Common_model->get_data_list_by_many_columns('admin', $array);
        $data['mainContent'] = $this->load->view('distributor/userAccess', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    public function insert_menu_accessList()
    {
        $user_id = $this->input->post('user_id');
        $navigation = $this->input->post('navigation');
        $this->Common_model->delete_data('admin_role', 'admin_id', $user_id);
        $allAccess = array();
        foreach ($navigation as $key => $value):
            unset($data);
            $get_parent_id = $this->Common_model->get_single_data_by_single_column('navigation', 'navigation_id', $value);
            $data['admin_id'] = $user_id;
            $data['navigation_id'] = $value;
            $data['parent_id'] = isset($get_parent_id->parent_id) ? $get_parent_id->parent_id : '';
            $allAccess[] = $data;
        endforeach;
        $this->db->insert_batch('admin_role', $allAccess);
//        $this->Common_model->insert_data('admin_role', $data1);
        message("Admin Access successfully added.");
        redirect(site_url('userAccess'));
    }

    function get_menu_list()
    {
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
