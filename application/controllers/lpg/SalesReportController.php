<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/15/2019
 * Time: 12:35 PM
 */

class SalesReportController extends CI_Controller
{

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $folder;
    public $folderSub;
    public $page_type;
    public $link_icon_add;
    public $link_icon_list;

    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('SalesReport_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'sales';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/sales/report/';
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

    public function daily_sales_statement($start_date = '', $end_date = '')
    {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
        if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['daily_sales_statement'] = $this->SalesReport_Model->daily_sales_statement($start_date, $end_date, $this->dist_id,$branch_id);

        }
        /*page navbar details*/
        $data['title'] = get_phrase('Daily Sales Statement');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Date Wise Sales Report';

        $data['mainContent'] = $this->load->view($this->folderSub . 'daily_sales_statement', $data, true);
        $this->load->view($this->folder, $data);
    }

    public function sales_report_brand_wise()
    {


        if (isPostBack()) {
            $brandId = $this->input->post('brandId');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['allStock'] = $this->Inventory_Model->sales_report_brand_wise($start_date, $end_date, $brandId);
            /* echo $this->db->last_query();
             echo '<pre>';
             print_r($data['allStock']);
             exit;*/


            $product = array();
            foreach ($data['allStock'] as $ind => $element) {
                if ($element->p_name != '') {
                    array_push($product, $element->productName );
                }
                $result[$element->brandId]['brand_name'] = $element->brandName;
                $result[$element->brandId][$element->p_name . '_package'] = $element->sales_package_qty;
                $result[$element->brandId][$element->p_name . '_empty'] = $element->sales_empty_qty;
                $result[$element->brandId][$element->p_name . '_refial'] = $element->sales_refill_qty;
            }
            $product = array_unique($product);
            $data['product'] = $product;
            $data['sales_list'] = $result;
            /*echo '<pre>';

            $product=sort($product);
            print_r($result);
            print_r($data['product']);
            exit;*/


        }


        /*page navbar details*/
        $data['title'] = 'Daily Sales Statement';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/


        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Customer Wise Sales Report';
        $data['title'] = 'Sales Rreport Brand Wise';
        $data['mainContent'] = $this->load->view($this->folderSub . 'sales_report_brand_wise', $data, true);
        $this->load->view($this->folder, $data);
    }

    public function date_wise_product_sales($start_date = '', $end_date = '')
    {

        /*page navbar details*/
        $data['title'] = get_phrase('Date Range Wise Product Sales Statement');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/


        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';


        if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['daily_sales_statement'] = $this->SalesReport_Model->date_wise_product_sales($start_date, $end_date, $this->dist_id,$branch_id);
            /*echo"<pre>";
            echo $this->db->last_query();
            print_r($data['daily_sales_statement']);
            exit;*/

        }

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);


        $data['mainContent'] = $this->load->view($this->folderSub . 'date_wise_product_sales', $data, true);
        $this->load->view($this->folder, $data);
    }
    public function date_wise_product_sales_print($start_date = '', $end_date = '')
    {

        /*page navbar details*/
        $data['title'] = 'Date Wise Product Sales Statement';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/


        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';


        if ($start_date != '' && $end_date != '') {

            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['daily_sales_statement'] = $this->SalesReport_Model->date_wise_product_sales($start_date, $end_date, $this->dist_id);

            /*echo $this->db->last_query();
              echo '<pre>';
              print_r($data['daily_sales_statement']);
              exit;*/


        }

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Customer Wise Sales Report';

        $this->load->view($this->folderSub . 'date_wise_product_sales_print_pdf', $data);
        // Load pdf library
        $this->load->library('pdf');

        $html = $this->output->get_output();
        // Load HTML content


        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portraint');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
        //$this->load->view($this->folder, $data);
    }

    public function date_wise_product_sales_by_date($start_date = '', $end_date = '')
    {


        /*page navbar details*/
        $data['title'] = get_phrase('Date  Wise Product Sales');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/


        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
        if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['daily_sales_statement'] = $this->SalesReport_Model->date_wise_product_sales_by_date($start_date, $end_date, $this->dist_id,$branch_id);
            /*  echo $this->db->last_query();
              echo '<pre>';
              print_r($data['daily_sales_statement']);
              exit;*/

        }

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Customer Wise Sales Report';

        $data['mainContent'] = $this->load->view($this->folderSub .'date_wise_product_sales_by_date', $data, true);
        $this->load->view($this->folder, $data);
    }
    public function salesReport() {

        /*page navbar details*/
        $data['title'] =  get_phrase('Sales Report');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/


        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : '';
        if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['sales_data'] = $this->SalesReport_Model->sales_report($start_date, $end_date, $this->dist_id,$branch_id);
            /*echo $this->db->last_query();
            echo '<pre>';
            print_r($data['sales_data']);
            exit;*/

        }


        $data['customerType'] = $this->Common_model->get_data_list('customertype');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Sales Report';

        $data['mainContent'] = $this->load->view($this->folderSub .'salesReport', $data, true);
        $this->load->view($this->folder, $data);
    }


    public function customerSalesReport() {
        /*page navbar details*/
        $data['title'] = get_phrase('Customer Sales Report');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/

        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
        $start_date=date('Y-m-d', strtotime($start_date));
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $end_date=date('Y-m-d', strtotime($end_date));
        $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
        $cusType = isset($_GET['cusType']) ? $_GET['cusType'] : 'all';
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
        $customer_id = $customer_id!='' ? $customer_id : 'all';

        /*if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['sales_data'] = $this->SalesReport_Model->getCustomerSalesList($start_date, $end_date,$cusType,$customer_id);
            echo '<pre>';
            print_r($data['sales_data']);
            exit;

        }*/

        if ($start_date != '' && $end_date != '') {

            $data['customerId'] = $customer_id;

            $data['salesList'] = $this->Sales_Model->getCustomerSalesList($this->dist_id, $start_date, $end_date, $customer_id, $cusType,$branch_id);
            //echo $this->db->last_query();exit;
        }
        $data['customerType'] = $this->Common_model->get_data_list('customertype');
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Customer Wise Sales Report';
        $data['customerList'] = $this->Common_model->get_data_list_by_single_column('customer', 'dist_id', $this->dist_id);

        if ($data['customerList'] != null) {

            foreach ($data['customerList'] as $key => $value) {
                $items_array[$value->customerType][0] = array(
                    'label' => 'ALL',
                    'value' => 'all'
                );
                $items_array[$value->customerType][] = array(
                    'label' => $value->customerName,
                    'value' => $value->customer_id
                );
                $items_array['all'][0] = array(
                    'label' => 'ALL',
                    'value' => 'all'
                );
                $items_array['all'][] = array(
                    'label' => $value->customerName,
                    'value' => $value->customer_id
                );

            };



        }
        $data['customerList_jason'] = json_encode($items_array);

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /*echo "<pre>";
        print_r($data['companyInfo']);exit;*/
        $data['mainContent'] = $this->load->view($this->folderSub .'customerSalesReport', $data, true);
        $this->load->view($this->folder, $data);
    }


    public function rootWiseSalesReport() {
        /*page navbar details*/
        $data['title'] = get_phrase('Root Wise Sales Report');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/

        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
        $start_date=date('Y-m-d', strtotime($start_date));
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $end_date=date('Y-m-d', strtotime($end_date));
        $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
        $root = isset($_GET['root_id']) ? $_GET['root_id'] : 'all';
        $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
        $customer_id = $customer_id!='' ? $customer_id : 'all';

        /*if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data['sales_data'] = $this->SalesReport_Model->getCustomerSalesList($start_date, $end_date,$cusType,$customer_id);
            echo '<pre>';
            print_r($data['sales_data']);
            exit;

        }*/

        if ($start_date != '' && $end_date != '') {

            $data['customerId'] = $customer_id;

            $data['salesList'] = $this->Sales_Model->getrootSalesList($this->dist_id, $start_date, $end_date, $customer_id, $root,$branch_id);

        }
        $data['customerType'] = $this->Common_model->get_data_list('customertype');
        $data['rootInfo'] = $this->db->where('is_active', 1)->where('is_delete', 0)->get('root_info')->result();
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Customer Wise Sales Report';
        $data['customerList'] = $this->Common_model->get_data_list_by_single_column('customer', 'dist_id', $this->dist_id);

        if ($data['customerList'] != null) {

            foreach ($data['customerList'] as $key => $value) {
                $items_array[$value->root_id][0] = array(
                    'label' => 'ALL',
                    'value' => 'all'
                );
                $items_array[$value->root_id][] = array(
                    'label' => $value->customerName,
                    'value' => $value->customer_id
                );
                $items_array['all'][0] = array(
                    'label' => 'ALL',
                    'value' => 'all'
                );
                $items_array['all'][] = array(
                    'label' => $value->customerName,
                    'value' => $value->customer_id
                );

            };



        }
        $data['customerList_jason'] = json_encode($items_array);

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        /*echo "<pre>";
        print_r($data['companyInfo']);exit;*/
        $data['mainContent'] = $this->load->view($this->folderSub .'rootWiseSalesReport', $data, true);
        $this->load->view($this->folder, $data);
    }

    public function pendingCheck() {
        if (isPostBack()) {
            $this->form_validation->set_rules('accountDr', 'Account Head', 'required');
            $this->form_validation->set_rules('paymentDate', 'Payment Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project.'/pendingCheck'));
            } else {

                $this->db->trans_start();


                //dumpVar($_POST);
                $receiteID = $this->input->post('receiteID');
                $receiteInfo = $this->Common_model->tableRow('moneyreceit', 'moneyReceitid', $receiteID);
                $updated_by = $this->session->userdata('admin_id');
                $created_at = date('Y-m-d H:i:s');
                $voucher = json_decode($receiteInfo->invoiceID);
                $paymentType = $this->input->post('paymentType');
                $clientID = $this->input->post('clientID');
                $account = $this->input->post('accountDr');
                $for = 3;
                $ledger_id = get_customer_supplier_product_ledger_id($receiteInfo->customerid, $for);
                if ($receiteInfo->due_collection_info_id != 0) {



                    //come from supplier payment from
                    $mrCondition = array(
                        'due_collection_info_id' => $receiteInfo->due_collection_info_id,
                        'is_active' => "Y",
                        'is_delete' => "N",
                    );
                    //purchase invoice that come form purchase Invoice add form payment type Bank
                    $dueCollectionInvoices=$this->Common_model->get_data_list_by_many_columns('cus_due_collection_details',$mrCondition);

                    if(!empty($dueCollectionInvoices)){
                        foreach ($dueCollectionInvoices as $a => $b) {


                            $salesInvoiceInfo = $this->Common_model->tableRow('sales_invoice_info', 'sales_invoice_id', $b->sales_invoice_id);
                            $this->load->helper('create_receive_voucher_no');
                            /*ac_accounts_vouchermst*/
                            $voucher_no = create_receive_voucher_no();


                            $accountingMasterTable['AccouVoucherType_AutoID'] = 1;
                            $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                            $accountingMasterTable['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                            $accountingMasterTable['BackReferenceInvoiceNo'] = $salesInvoiceInfo->invoice_no;
                            $accountingMasterTable['BackReferenceInvoiceID'] = $salesInvoiceInfo->sales_invoice_id;
                            $accountingMasterTable['Narration'] = 'Customer Pending Cheque Recive';
                            $accountingMasterTable['CompanyId'] = $this->dist_id;

                            $accountingMasterTable['BranchAutoId'] = $salesInvoiceInfo->branch_id;
                            $accountingMasterTable['customer_id'] = $receiteInfo->customerid;
                            $accountingMasterTable['IsActive'] = 1;
                            $accountingMasterTable['for'] = 6;
                            $accountingMasterTable['Created_By'] = $this->admin_id;
                            $accountingMasterTable['Created_Date'] = $this->timestamp;
                            $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);

                            /*Customer Receivable  /account Receiveable  =>>25*/
                            //account Receiveable
                            $accountingDetailsTable_CR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                            $accountingDetailsTable_CR['TypeID'] = '2';//CR
                            $accountingDetailsTable_CR['CHILD_ID'] = $ledger_id->id;//$this->config->item("Customer_Receivable");//'25';
                            $accountingDetailsTable_CR['GR_DEBIT'] = '0.00';
                            $accountingDetailsTable_CR['GR_CREDIT'] = $receiteInfo->totalPayment;
                            $accountingDetailsTable_CR['Reference'] = 'Customer Recivable';
                            $accountingDetailsTable_CR['IsActive'] = 1;
                            $accountingDetailsTable_CR['Created_By'] = $this->admin_id;
                            $accountingDetailsTable_CR['BranchAutoId'] = $salesInvoiceInfo->branch_id;
                            $accountingDetailsTable_CR['Created_Date'] = $this->timestamp;
                            $accountingDetailsTable_CR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                            $finalDetailsArray[] = $accountingDetailsTable_CR;
                            $accountingDetailsTable_CR = array();
                            //supplier paid amount

                            /*Cash in hand*/
                            $accountingDetailsTable_DR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                            $accountingDetailsTable_DR['TypeID'] = '1';//CR
                            $accountingDetailsTable_DR['CHILD_ID'] = $this->input->post('accountDr');
                            $accountingDetailsTable_DR['GR_DEBIT'] = $receiteInfo->totalPayment;
                            $accountingDetailsTable_DR['GR_CREDIT'] = '0.00';
                            $accountingDetailsTable_DR['Reference'] = 'Customer Pending Cheque Recive';
                            $accountingDetailsTable_DR['IsActive'] = 1;
                            $accountingDetailsTable_DR['Created_By'] = $this->admin_id;
                            $accountingDetailsTable_DR['BranchAutoId'] = $salesInvoiceInfo->branch_id;
                            $accountingDetailsTable_DR['Created_Date'] = $this->timestamp;
                            $accountingDetailsTable_DR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                            $finalDetailsArray[] = $accountingDetailsTable_DR;
                            $accountingDetailsTable_DR = array();
                            $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                            $finalDetailsArray=array();

                            $customerData = array(
                                'ledger_type' => 1,
                                'history_id' => $accountingVoucherId,
                                'trans_type' => $receiteInfo->receitID,
                                'paymentType' => 'Customer Check Received',
                                'client_vendor_id' => $clientID,
                                'invoice_id' => $salesInvoiceInfo->sales_invoice_id,
                                'invoice_type' => '1',
                                'Accounts_VoucherType_AutoID' => '1',
                                'amount' => $receiteInfo->totalPayment,
                                'cr' => $receiteInfo->totalPayment,
                                'dist_id' => $this->dist_id,
                                'BranchAutoId' => $salesInvoiceInfo->branch_id,
                                'date' => date('Y-m-d', strtotime($this->input->post('paymentDate')))
                            );
                            $this->db->insert('client_vendor_ledger', $customerData);


                        }

                    }




                }else{

                    $salesInvoiceInfo = $this->Common_model->tableRow('sales_invoice_info', 'sales_invoice_id', $receiteInfo->invoiceID);
                    $this->load->helper('create_receive_voucher_no');
                    /*ac_accounts_vouchermst*/
                    $voucher_no = create_receive_voucher_no();
                    $accountingMasterTable['AccouVoucherType_AutoID'] = 1;
                    $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                    $accountingMasterTable['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                    $accountingMasterTable['BackReferenceInvoiceNo'] = $salesInvoiceInfo->invoice_no;
                    $accountingMasterTable['BackReferenceInvoiceID'] = $receiteInfo->invoiceID;
                    $accountingMasterTable['Narration'] = 'Customer Pending Cheque Recive';
                    $accountingMasterTable['CompanyId'] = $this->dist_id;
                    $accountingMasterTable['BranchAutoId'] = $salesInvoiceInfo->branch_id;
                    $accountingMasterTable['customer_id'] = $receiteInfo->customerid;
                    $accountingMasterTable['IsActive'] = 1;
                    $accountingMasterTable['for'] = 6;
                    $accountingMasterTable['Created_By'] = $this->admin_id;
                    $accountingMasterTable['Created_Date'] = $this->timestamp;
                    $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);

                    /*Customer Receivable  /account Receiveable  =>>25*/
                    //account Receiveable
                    $accountingDetailsTable_CR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable_CR['TypeID'] = '2';//CR
                    $accountingDetailsTable_CR['CHILD_ID'] = $ledger_id->id;$this->config->item("Customer_Receivable");//'25';
                    $accountingDetailsTable_CR['GR_DEBIT'] = '0.00';
                    $accountingDetailsTable_CR['GR_CREDIT'] = $receiteInfo->totalPayment;
                    $accountingDetailsTable_CR['Reference'] = 'Customer Recivable';
                    $accountingDetailsTable_CR['IsActive'] = 1;
                    $accountingDetailsTable_CR['Created_By'] = $this->admin_id;
                    $accountingDetailsTable_CR['BranchAutoId'] = $salesInvoiceInfo->branch_id;
                    $accountingDetailsTable_CR['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable_CR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                    $finalDetailsArray[] = $accountingDetailsTable_CR;
                    $accountingDetailsTable = array();
                    //supplier paid amount

                    /*Cash in hand*/
                    $accountingDetailsTable_DR['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                    $accountingDetailsTable_DR['TypeID'] = '1';//CR
                    $accountingDetailsTable_DR['CHILD_ID'] = $this->input->post('accountDr');
                    $accountingDetailsTable_DR['GR_DEBIT'] = $receiteInfo->totalPayment;
                    $accountingDetailsTable_DR['GR_CREDIT'] = '0.00';
                    $accountingDetailsTable_DR['Reference'] = '';
                    $accountingDetailsTable_DR['IsActive'] = 1;
                    $accountingDetailsTable_DR['Created_By'] = $this->admin_id;
                    $accountingDetailsTable_DR['BranchAutoId'] = $salesInvoiceInfo->branch_id;
                    $accountingDetailsTable_DR['Created_Date'] = $this->timestamp;
                    $accountingDetailsTable_DR['date'] = $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                    $finalDetailsArray[] = $accountingDetailsTable_DR;
                    $accountingDetailsTable = array();
                    $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);


                    $customerData = array(
                        'ledger_type' => 1,
                        'history_id' => $accountingVoucherId,
                        'trans_type' => $receiteInfo->receitID,
                        'paymentType' => 'Customer Check Received',
                        'client_vendor_id' => $clientID,
                        'invoice_id' => $salesInvoiceInfo->sales_invoice_id,
                        'invoice_type' => '1',
                        'Accounts_VoucherType_AutoID' => '1',
                        'amount' => $receiteInfo->totalPayment,
                        'cr' => $receiteInfo->totalPayment,
                        'dist_id' => $this->dist_id,
                        'BranchAutoId' => $salesInvoiceInfo->branch_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('paymentDate')))
                    );
                    $this->db->insert('client_vendor_ledger', $customerData);
                }

                $changeStatus['checkStatus'] = 2;
                $changeStatus['received_date'] =  $this->input->post('paymentDate') != '' ? date('Y-m-d', strtotime($this->input->post('paymentDate'))) : '';
                $this->db->where('moneyReceitid', $receiteID);
                $this->db->update('moneyreceit', $changeStatus);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $msg = ' ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect($this->project.'/pendingCheck', 'refresh');
                } else {
                    $msg = '' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect($this->project.'/viewMoneryReceipt/' . $receiteID, 'refresh');

                }

            }


        }
        // $data['accountHeadList'] = $assetsList;
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $data['title'] = get_phrase('Customer_Pending_Cheque');
        $data['page_type'] = get_phrase($this->page_type);
        //money Recit condition
        $moneyReceitCond = array(
            'receiveType' => 1, //customer
            'paymentType' => 2, //cheque
            'checkStatus' => 1, //pending
            'dist_id' => $this->dist_id, //distributor id
        );


        /*page navbar details
        $data['title'] = 'Customer Pending Cheque';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = '';
        /*page navbar details*/
        $this->db->select('moneyreceit.*,bank_info.bank_name,bank_branch_info.bank_branch_name');
        $this->db->from('moneyreceit');
        $this->db->join('bank_info', 'bank_info.bank_info_id=moneyreceit.bankName','left');
        $this->db->join('bank_branch_info', 'bank_branch_info.bank_branch_id=moneyreceit.branchName','left');
        $this->db->where('moneyreceit.receiveType',1);
        $this->db->where('moneyreceit.paymentType',2);
        $this->db->where('moneyreceit.checkStatus',1);
        $this->db->where('moneyreceit.dist_id',$this->dist_id);
        $sql = $this->db->get();
        $data['customerPendingCheque']= $sql->result();


        //$data['customerPendingCheque'] = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $moneyReceitCond, 'moneyReceitid', 'DESC');
        //$data['mainContent'] = $this->load->view('distributor/sales/report/pendingCheck', $data, true);
        //$this->load->view('distributor/masterDashboard', $data);

        $data['mainContent'] = $this->load->view($this->folderSub .'pendingCheck', $data, true);
        $this->load->view($this->folder, $data);
    }
    function brandWiseProfit() {
        if (isPostBack()) {
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $brandId = $this->input->post('brandId');
            $data['productLedger'] = $this->Inventory_Model->getProductLedger($start_date, $end_date, $productId, $this->dist_id);
            if ($productId != 'all') {
                $data['productOpening'] = $this->Inventory_Model->getProductLedgerOpening($start_date, $end_date, $productId, $this->dist_id);
            }
        }
        /*page navbar details*/
        $data['title'] = 'Product Ledger';
        $data['page_type']=$this->page_type;
        $data['link_page_name']='';
        $data['link_page_url']='';
        $data['link_icon']="";
        /*page navbar details*/
        $data['dist_id'] = $this->dist_id;
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/productledger', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function productWiseSalesReport()
    {
        $data['title'] = get_phrase('Product Wise Sales');
        $data['page_type'] = get_phrase($this->page_type);
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/report/proudctWiseSalesReport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}