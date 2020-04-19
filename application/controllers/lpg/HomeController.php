<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends CI_Controller
{
    private $timestamp;
    public $admin_id;
    public $dist_id;
    public $project;
    public $business_type;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Dashboard_Model');
        $this->load->model('Accounts_model');
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
        $this->business_type = $this->session->userdata('business_type');
        $this->db->close();
        //$this->load->helper('db_dinamic_helper');
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }



    public function moduleDashboard()
    {

        $query = $this->db->field_exists('ime_no', 'purchase_details');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'ime_no' => array(
                    'type' => 'TEXT',
                    'null' => TRUE,
                    //'unsigned' => TRUE,
                    'after' => 'product_id')
            );
            $this->dbforge->add_column('purchase_details', $fields);
        }
        $query = $this->db->field_exists('ime_no', 'sales_details');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'ime_no' => array(
                    'type' => 'TEXT',
                    'null' => TRUE,
                    //'unsigned' => TRUE,
                    'after' => 'product_id')
            );
            $this->dbforge->add_column('sales_details', $fields);
        }





        $array = array(
            'title' => "Damage Stock",
        );
        $DamageStock=$this->Common_model->get_data_list_by_many_columns('productcategory', $array);
        if(empty($DamageStock)){
            $this->db->query("INSERT INTO `productcategory` (`category_id`, `dist_id`, `title`, `updated_at`, `created_at`, `updated_by`, `is_active`, `is_delete`) VALUES (NULL, '2', 'Damage Stock', '0000-00-00 00:00:00', '2020-04-12 21:04:39', '2', 'N', 'N');");

        }


        $this->db->query("UPDATE navigation SET active = '0' WHERE navigation_id = 110");
        $this->db->query("UPDATE ac_account_ledger_coa SET show_in_income_stetement = 1 WHERE ac_account_ledger_coa.id = 58");


        $query = $this->db->field_exists('product_table_update', 'system_config');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'product_table_update' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    //'unsigned' => TRUE,
                    'after' => 'Reference')
            );
            $this->dbforge->add_column('system_config', $fields);

            $this->load->dbforge();
            $fields = array(
                'subcategoryID' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'default' => '0',
                    'null' => TRUE,

                ),
                'modelID' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'default' => '0',
                    'null' => TRUE,

                ),
                'colorID' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'default' => '0',
                    'null' => TRUE,

                ),
                'sizeID' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'default' => '0',
                    'null' => TRUE,

                ),
                'vat' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'default' => '0',
                    'null' => TRUE,

                ),

                'description' => array(
                    'type' => 'TEXT',
                    'null' => TRUE,

                ),
            );
            $this->dbforge->add_column('product', $fields);
        }





        $query = $this->db->field_exists('ReferenceForBackEnd', 'ac_tb_accounts_voucherdtl');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'ReferenceForBackEnd' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    //'unsigned' => TRUE,
                    'after' => 'Reference')
            );
            $this->dbforge->add_column('ac_tb_accounts_voucherdtl', $fields);
        }


        $queryNeedToRunOrNor=0;

        // table exists
        $query = $this->db->field_exists('make_triger_history_table', 'system_config');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'delete_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    //'unsigned' => TRUE,
                    'after' => 'updated_at')
            );
            $this->dbforge->add_column('system_config', $fields);
        }else{
            $this->db->where('make_triger_history_table', 1);
            $result= $this->db->get('system_config')->row();
            if(!empty($result)){
                $queryNeedToRunOrNor=1;
            }else{
                $queryNeedToRunOrNor=0;
            }
        }


        if($queryNeedToRunOrNor != 1){
            if ($this->db->table_exists('ac_accounts_vouchermst_history')) {
                // table exists
                $query = $this->db->field_exists('delete_by', 'ac_accounts_vouchermst_history');
                if ($query != TRUE) {
                    $this->load->dbforge();
                    $fields = array(
                        'delete_by' => array(
                            'type' => 'INT',
                            'constraint' => 11,
                            //'unsigned' => TRUE,
                            'after' => 'Changed_Date')
                    );
                    $this->dbforge->add_column('ac_accounts_vouchermst_history', $fields);
                }
            } else {
                // table does not exist
                $sql = "CREATE TABLE `ac_accounts_vouchermst_history` (
	`Accounts_VoucherMst_AutoID` INT(11) NOT NULL,
	`AccouVoucherType_AutoID` INT(11) NULL DEFAULT NULL COMMENT 'accounts_vouchertype_autoid table Accounts_VoucherType_AutoID col',
	`for` INT(11) NULL DEFAULT '0' COMMENT '1->purchase invoice,2->sales invoice,3->purchase invoice supplier Payment money recive,4->purchase invoice supplier Pending Caque money recive,5->Sales invoice Customer Payment money recive,6->sales invoice customer Pending Cheque money recive ',
	`Accounts_Voucher_No` VARCHAR(100) NULL DEFAULT NULL,
	`Accounts_Voucher_Date` DATE NULL DEFAULT NULL,
	`BackReferenceInvoiceNo` VARCHAR(255) NULL DEFAULT '0' COMMENT 'sales/purchase invoice no',
	`BackReferenceInvoiceID` INT(11) NULL DEFAULT '0' COMMENT 'sales/purchase invoice id',
	`Narration` VARCHAR(250) NULL DEFAULT NULL,
	`CompanyId` INT(10) NULL DEFAULT NULL,
	`BranchAutoId` INT(10) NULL DEFAULT NULL COMMENT 'branch id',
	`Reference` VARCHAR(200) NULL DEFAULT NULL,
	`customer_id` INT(11) NULL DEFAULT '0',
	`supplier_id` INT(11) NULL DEFAULT NULL,
	`miscellaneous` VARCHAR(250) NULL DEFAULT NULL,
	`IsActive` TINYINT(3) NULL DEFAULT '1',
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`delete_by` INT(11) NOT NULL,
	`deleteDate` DATETIME NULL DEFAULT NULL
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;";
                $this->db->query($sql);
            }
            if ($this->db->table_exists('ac_tb_accounts_voucherdtl_history')) {
                $query = $this->db->field_exists('delete_by', 'ac_tb_accounts_voucherdtl_history');
                if ($query != TRUE) {
                    $this->load->dbforge();
                    $fields = array(
                        'delete_by' => array(
                            'type' => 'INT',
                            'constraint' => 11,
                            //'unsigned' => TRUE,
                            'after' => 'Changed_Date')
                    );
                    $this->dbforge->add_column('ac_tb_accounts_voucherdtl_history', $fields);
                }
            } else {
                $sql = "CREATE TABLE `ac_tb_accounts_voucherdtl_history` (
	`Accounts_VoucherDtl_AutoID` INT(11) NOT NULL,
	`Accounts_VoucherMst_AutoID` INT(10) NULL DEFAULT NULL,
	`TypeID` INT(10) NULL DEFAULT NULL COMMENT '1->Dr,2->Cr',
	`CHILD_ID` INT(10) NULL DEFAULT NULL,
	`GR_DEBIT` DECIMAL(10,2) NULL DEFAULT NULL,
	`GR_CREDIT` DECIMAL(10,2) NULL DEFAULT NULL,
	`date` DATE NULL DEFAULT NULL,
	`Reference` VARCHAR(200) NULL DEFAULT NULL,
	`IsActive` TINYINT(3) NULL DEFAULT '1',
	`BranchAutoId` INT(11) NULL DEFAULT '0',
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`delete_by` INT(11) NOT NULL,
	`deleteDate` DATETIME NULL DEFAULT NULL
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;";
                $this->db->query($sql);
            }
            $trigerac_accounts_vouchermst_history_trigger = "CREATE DEFINER=`" . $this->db_username . "`@`localhost` TRIGGER `ac_accounts_vouchermst_history_trigger` BEFORE UPDATE ON `ac_accounts_vouchermst` FOR EACH ROW INSERT INTO ac_accounts_vouchermst_history VALUES ( old.Accounts_VoucherMst_AutoID, old.AccouVoucherType_AutoID, old.for, old.Accounts_Voucher_No, old.Accounts_Voucher_Date, old.BackReferenceInvoiceNo, old.BackReferenceInvoiceID, old.Narration, old.CompanyId, old.BranchAutoId, old.Reference, old.customer_id, old.supplier_id, old.miscellaneous, old.IsActive, old.Created_By, old.Created_Date, old.Changed_By, old.Changed_Date,new.Changed_By, now())";
            $this->db->query("DROP TRIGGER IF EXISTS ac_accounts_vouchermst_history_trigger");
            $this->db->query($trigerac_accounts_vouchermst_history_trigger);
            $ac_tb_accounts_voucherdtl_history_trigger = "CREATE DEFINER=`" . $this->db_username . "`@`localhost` TRIGGER `ac_tb_accounts_voucherdtl_history_trigger` BEFORE DELETE ON `ac_tb_accounts_voucherdtl` FOR EACH ROW INSERT INTO  ac_tb_accounts_voucherdtl_history  VALUES (
old.Accounts_VoucherDtl_AutoID,
old.Accounts_VoucherMst_AutoID,
old.TypeID,
old.CHILD_ID,
old.GR_DEBIT,
old.GR_CREDIT,
old.date,
old.Reference,
old.IsActive,
old.BranchAutoId,
old.Created_By,
old.Created_Date,
old.Changed_By,
old.Changed_Date,
old.Changed_By,
now())";
            $this->db->query("DROP TRIGGER IF EXISTS ac_tb_accounts_voucherdtl_history_trigger");
            $this->db->query($ac_tb_accounts_voucherdtl_history_trigger);

            /*$this->db->query("DROP TRIGGER IF EXISTS `ac_accounts_vouchermst_history_trigger`;
    CREATE DEFINER=`root`@`localhost` TRIGGER `ac_accounts_vouchermst_history_trigger` BEFORE UPDATE ON `ac_accounts_vouchermst` FOR EACH ROW INSERT INTO ac_accounts_vouchermst_history VALUES ( old.Accounts_VoucherMst_AutoID, old.AccouVoucherType_AutoID, old.for, old.Accounts_Voucher_No, old.Accounts_Voucher_Date, old.BackReferenceInvoiceNo, old.BackReferenceInvoiceID, old.Narration, old.CompanyId, old.BranchAutoId, old.Reference, old.customer_id, old.supplier_id, old.miscellaneous, old.IsActive, old.Created_By, old.Created_Date, old.Changed_By, old.Changed_Date, new.Changed_By, now())");*/

            $query = $this->db->field_exists('m_distributorid', 'admin');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'm_distributorid' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        //'unsigned' => TRUE,
                        'after' => 'distributor_id')
                );
                $this->dbforge->add_column('admin', $fields);
            }


            $query = $this->db->field_exists('credit_limit', 'customer');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'credit_limit' => array(
                        'type' => 'DECIMAL',
                        //'constraint' => 11,
                        'default' => '0',
                        'null' => TRUE,
                        //'unsigned' => TRUE,
                        // 'after' => 'distributor_id'
                    )
                );
                $this->dbforge->add_column('customer', $fields);
            }

            $query = $this->db->field_exists('credit_days', 'customer');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'credit_days' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'default' => '0',
                        'null' => TRUE,
                        //'unsigned' => TRUE,
                        // 'after' => 'distributor_id'
                    )
                );
                $this->dbforge->add_column('customer', $fields);
            }

        }




        if (empty($admin_id)) {

        }
        $Customer_Receivable = $this->config->item("Customer_Receivable");
        $Supplier_Payables = $this->config->item("Supplier_Payables");
        $Cash_at_Hand = $this->config->item("Cash_at_Hand");
        $Cash_at_Bank = $this->config->item("Cash_at_Bank");
        $accountReceiable = $this->Accounts_model->get_third_level_group_sum(1, $Customer_Receivable);
        // echo  "<pre>";
        //print_r($this->db->last_query());exit;
        $data['accountReceiable'] = $accountReceiable->Balance;
        $accountPayable = $this->Accounts_model->get_third_level_group_sum(2, $Supplier_Payables);


        $data['accountPayable'] = $accountPayable->Balance;//$accountPayable->Balance < 0 ?  : $accountPayable->Balance;
        $cashInHand = $this->Accounts_model->get_third_level_group_sum(1, $Cash_at_Hand);
        $data['cashInHand'] = $cashInHand->Balance;
        $data['totalSalesAmount'] = $totalSales = $this->Dashboard_Model->getTotalSales($searchDay = '');
        $data['inventoryAmount'] = $this->Dashboard_Model->getinventoryAmount($searchDay = '');
        $CashAtBank = $this->Accounts_model->get_third_level_group_sum(1, $Cash_at_Bank);
        $data['totalCashAtBank'] = $CashAtBank->Balance;
        /*date wise sales  graph start*/
        $date_wise_sales = $this->Dashboard_Model->day_wise_sales_grap($this->dist_id);
        $date_wise_sales_array = array();
        array_push($date_wise_sales_array, array('Sales', 'Amount'));
        foreach ($date_wise_sales as $key => $value) {
            if ($key % 2 == 0) {
                $point = array($value->day . '/' . $value->month, $value->amount);
            } else {
                $point = array($value->day, $value->amount);
            }
            array_push($date_wise_sales_array, $point);
        }
        $data['date_wise_sales_array'] = json_encode($date_wise_sales_array, JSON_NUMERIC_CHECK);
        /*date wise sales  graph end*/
        /*date wise purchase  graph start*/
        $date_wise_purchase = $this->Dashboard_Model->day_wise_purchase_grap($this->dist_id);
        $date_wise_purchase_array = array();
        array_push($date_wise_purchase_array, array('Purchase', 'Amount'));
        foreach ($date_wise_purchase as $key => $value) {
            if ($key % 2 == 0) {
                $point = array($value->day . '/' . $value->month, $value->amount);
            } else {
                $point = array($value->day, $value->amount);
            }
            array_push($date_wise_purchase_array, $point);
        }
        $data['date_wise_purchase_array'] = json_encode($date_wise_purchase_array, JSON_NUMERIC_CHECK);
        /*date wise purchase  graph end*/
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
        $data['page_type'] = get_phrase('Dashboard');
        $data['adminName'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $this->admin_id)->name;
        $data['title'] = get_phrase('Admin_Dashboard');
        $data['mainContent'] = $this->load->view('distributor/dashboard', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function moduleDashboard2()
    {


        if ($this->db->table_exists('ac_accounts_vouchermst_history')) {
            // table exists
            $query = $this->db->field_exists('delete_by', 'ac_accounts_vouchermst_history');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'delete_by' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        //'unsigned' => TRUE,
                        'after' => 'Changed_Date')
                );
                $this->dbforge->add_column('ac_accounts_vouchermst_history', $fields);
            }
        } else {
            // table does not exist
            $sql = "CREATE TABLE `ac_accounts_vouchermst_history` (
	`Accounts_VoucherMst_AutoID` INT(11) NOT NULL,
	`AccouVoucherType_AutoID` INT(11) NULL DEFAULT NULL COMMENT 'accounts_vouchertype_autoid table Accounts_VoucherType_AutoID col',
	`for` INT(11) NULL DEFAULT '0' COMMENT '1->purchase invoice,2->sales invoice,3->purchase invoice supplier Payment money recive,4->purchase invoice supplier Pending Caque money recive,5->Sales invoice Customer Payment money recive,6->sales invoice customer Pending Cheque money recive ',
	`Accounts_Voucher_No` VARCHAR(100) NULL DEFAULT NULL,
	`Accounts_Voucher_Date` DATE NULL DEFAULT NULL,
	`BackReferenceInvoiceNo` VARCHAR(255) NULL DEFAULT '0' COMMENT 'sales/purchase invoice no',
	`BackReferenceInvoiceID` INT(11) NULL DEFAULT '0' COMMENT 'sales/purchase invoice id',
	`Narration` VARCHAR(250) NULL DEFAULT NULL,
	`CompanyId` INT(10) NULL DEFAULT NULL,
	`BranchAutoId` INT(10) NULL DEFAULT NULL COMMENT 'branch id',
	`Reference` VARCHAR(200) NULL DEFAULT NULL,
	`customer_id` INT(11) NULL DEFAULT '0',
	`supplier_id` INT(11) NULL DEFAULT NULL,
	`miscellaneous` VARCHAR(250) NULL DEFAULT NULL,
	`IsActive` TINYINT(3) NULL DEFAULT '1',
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`delete_by` INT(11) NOT NULL,
	`deleteDate` DATETIME NULL DEFAULT NULL
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;";
            $this->db->query($sql);
        }
        if ($this->db->table_exists('ac_tb_accounts_voucherdtl_history')) {
            $query = $this->db->field_exists('delete_by', 'ac_tb_accounts_voucherdtl_history');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'delete_by' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        //'unsigned' => TRUE,
                        'after' => 'Changed_Date')
                );
                $this->dbforge->add_column('ac_tb_accounts_voucherdtl_history', $fields);
            }
        } else {
            $sql = "CREATE TABLE `ac_tb_accounts_voucherdtl_history` (
	`Accounts_VoucherDtl_AutoID` INT(11) NOT NULL,
	`Accounts_VoucherMst_AutoID` INT(10) NULL DEFAULT NULL,
	`TypeID` INT(10) NULL DEFAULT NULL COMMENT '1->Dr,2->Cr',
	`CHILD_ID` INT(10) NULL DEFAULT NULL,
	`GR_DEBIT` DECIMAL(10,2) NULL DEFAULT NULL,
	`GR_CREDIT` DECIMAL(10,2) NULL DEFAULT NULL,
	`date` DATE NULL DEFAULT NULL,
	`Reference` VARCHAR(200) NULL DEFAULT NULL,
	`IsActive` TINYINT(3) NULL DEFAULT '1',
	`BranchAutoId` INT(11) NULL DEFAULT '0',
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`delete_by` INT(11) NOT NULL,
	`deleteDate` DATETIME NULL DEFAULT NULL
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;";
            $this->db->query($sql);
        }

        $this->db->query("DROP TRIGGER IF EXISTS ac_accounts_vouchermst_history_trigger");
        $this->db->query("CREATE DEFINER=`root`@`localhost` TRIGGER `ac_accounts_vouchermst_history_trigger` BEFORE UPDATE ON `ac_accounts_vouchermst` FOR EACH ROW INSERT INTO ac_accounts_vouchermst_history VALUES ( old.Accounts_VoucherMst_AutoID, old.AccouVoucherType_AutoID, old.for, old.Accounts_Voucher_No, old.Accounts_Voucher_Date, old.BackReferenceInvoiceNo, old.BackReferenceInvoiceID, old.Narration, old.CompanyId, old.BranchAutoId, old.Reference, old.customer_id, old.supplier_id, old.miscellaneous, old.IsActive, old.Created_By, old.Created_Date, old.Changed_By, old.Changed_Date,new.Changed_By, now())");

        $this->db->query("DROP TRIGGER IF EXISTS ac_tb_accounts_voucherdtl_history_trigger");
        $this->db->query("CREATE DEFINER=`root`@`localhost` TRIGGER `ac_tb_accounts_voucherdtl_history_trigger` BEFORE DELETE ON `ac_tb_accounts_voucherdtl` FOR EACH ROW INSERT INTO  ac_tb_accounts_voucherdtl_history  VALUES (
old.Accounts_VoucherDtl_AutoID,
old.Accounts_VoucherMst_AutoID,
old.TypeID,
old.CHILD_ID,
old.GR_DEBIT,
old.GR_CREDIT,
old.date,
old.Reference,
old.IsActive,
old.BranchAutoId,
old.Created_By,
old.Created_Date,
old.Changed_By,
old.Changed_Date,
old.Changed_By,
now())");

        /*$this->db->query("DROP TRIGGER IF EXISTS `ac_accounts_vouchermst_history_trigger`;
CREATE DEFINER=`root`@`localhost` TRIGGER `ac_accounts_vouchermst_history_trigger` BEFORE UPDATE ON `ac_accounts_vouchermst` FOR EACH ROW INSERT INTO ac_accounts_vouchermst_history VALUES ( old.Accounts_VoucherMst_AutoID, old.AccouVoucherType_AutoID, old.for, old.Accounts_Voucher_No, old.Accounts_Voucher_Date, old.BackReferenceInvoiceNo, old.BackReferenceInvoiceID, old.Narration, old.CompanyId, old.BranchAutoId, old.Reference, old.customer_id, old.supplier_id, old.miscellaneous, old.IsActive, old.Created_By, old.Created_Date, old.Changed_By, old.Changed_Date, new.Changed_By, now())");*/

        $query = $this->db->field_exists('m_distributorid', 'admin');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'm_distributorid' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    //'unsigned' => TRUE,
                    'after' => 'distributor_id')
            );
            $this->dbforge->add_column('admin', $fields);
        }


        $query = $this->db->field_exists('credit_limit', 'customer');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'credit_limit' => array(
                    'type' => 'DECIMAL',
                    //'constraint' => 11,
                    'default' => '0',
                    'null' => TRUE,
                    //'unsigned' => TRUE,
                    // 'after' => 'distributor_id'
                )
            );
            $this->dbforge->add_column('customer', $fields);
        }

        $query = $this->db->field_exists('credit_days', 'customer');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'credit_days' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0',
                    'null' => TRUE,
                    //'unsigned' => TRUE,
                    // 'after' => 'distributor_id'
                )
            );
            $this->dbforge->add_column('customer', $fields);
        }


        if (empty($admin_id)) {

        }
        /*$Customer_Receivable = $this->config->item("Customer_Receivable");
        $Supplier_Payables = $this->config->item("Supplier_Payables");
        $Cash_at_Hand = $this->config->item("Cash_at_Hand");
        $Cash_at_Bank = $this->config->item("Cash_at_Bank");
        $accountReceiable = $this->Accounts_model->get_third_level_group_sum(1, $Customer_Receivable);
       // echo  "<pre>";
        //print_r($this->db->last_query());exit;
        $data['accountReceiable'] = $accountReceiable->Balance ;
        $accountPayable = $this->Accounts_model->get_third_level_group_sum(2, $Supplier_Payables);



        $data['accountPayable'] = $accountPayable->Balance;//$accountPayable->Balance < 0 ?  : $accountPayable->Balance;
        $cashInHand = $this->Accounts_model->get_third_level_group_sum(1, $Cash_at_Hand);
        $data['cashInHand'] = $cashInHand->Balance ;
        $data['totalSalesAmount'] = $totalSales = $this->Dashboard_Model->getTotalSales($searchDay = '');
        $data['inventoryAmount'] = $this->Dashboard_Model->getinventoryAmount($searchDay = '');
        $CashAtBank = $this->Accounts_model->get_third_level_group_sum(1, $Cash_at_Bank);
        $data['totalCashAtBank'] = $CashAtBank->Balance;*/
        /*date wise sales  graph start*/
        $date_wise_sales = $this->Dashboard_Model->day_wise_sales_grap($this->dist_id);
        $date_wise_sales_array = array();
        array_push($date_wise_sales_array, array('Sales', 'Amount'));
        foreach ($date_wise_sales as $key => $value) {
            if ($key % 2 == 0) {
                $point = array($value->day . '/' . $value->month, $value->amount);
            } else {
                $point = array($value->day, $value->amount);
            }
            array_push($date_wise_sales_array, $point);
        }
        $data['date_wise_sales_array'] = json_encode($date_wise_sales_array, JSON_NUMERIC_CHECK);
        /*date wise sales  graph end*/
        /*date wise purchase  graph start*/
        $date_wise_purchase = $this->Dashboard_Model->day_wise_purchase_grap($this->dist_id);
        $date_wise_purchase_array = array();
        array_push($date_wise_purchase_array, array('Purchase', 'Amount'));
        foreach ($date_wise_purchase as $key => $value) {
            if ($key % 2 == 0) {
                $point = array($value->day . '/' . $value->month, $value->amount);
            } else {
                $point = array($value->day, $value->amount);
            }
            array_push($date_wise_purchase_array, $point);
        }
        $data['date_wise_purchase_array'] = json_encode($date_wise_purchase_array, JSON_NUMERIC_CHECK);
        /*date wise purchase  graph end*/
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
        $data['page_type'] = get_phrase('Dashboard');
        $data['adminName'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $this->admin_id)->name;
        $data['title'] = get_phrase('Admin_Dashboard');
        $data['mainContent'] = $this->load->view('distributor/dashboard', $data, true);

        if ($this->business_type == "LPG") {
            $this->load->view('distributor/masterTemplate', $data);
        } else if ($this->business_type == "MOBILE") {
            $this->load->view('distributor/masterTemplateSmeMobile', $data);
        } else {
            $this->load->view('distributor/masterTemplate', $data);
        }
    }

    public function userAccess()
    {
        $array = array(
            'status' => 1,
            // 'distributor_id' => $this->dist_id,
            'accessType' => 2,
        );
        $data['title'] = get_phrase('User Access');
        $data['page_type'] = get_phrase('Configuration');
        $data['adminList'] = $this->Common_model->get_data_list_by_many_columns('admin', $array);


        if ($this->business_type == "LPG") {
            $data['mainContent'] = $this->load->view('distributor/userAccess', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
        } else if ($this->business_type == "MOBILE") {
            $data['mainContent'] = $this->load->view('distributor/userAccessMobileWithUserRole', $data, true);
            $this->load->view('distributor/masterTemplateSmeMobile', $data);
        } else {
            $data['mainContent'] = $this->load->view('distributor/userAccess', $data, true);
            $this->load->view('distributor/masterTemplate', $data);
        }
    }

    public function adminLoginHistory()
    {
        $data['adminInfo'] = $this->Common_model->get_data_list_by_single_column('adminloghistory', 'distId', $this->dist_id, 'logId', 'DESC');
        $data['title'] = 'Admin Login History';
        $data['mainContent'] = $this->load->view('distributor/adminLoginHistory', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
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


        if ($this->business_type == "LPG") {
            return $this->load->view('distributor/ajax/menuListShow', $data);
        } else if ($this->business_type == "MOBILE") {
            return $this->load->view('distributor/ajax/menuListShowByUserRole', $data);
        } else {
            return $this->load->view('distributor/ajax/menuListShow', $data);
        }

    }

    public function insert_menu_accessList()
    {
        $this->db->trans_start();
        /*echo "<pre>";
        print_r($_POST);*/

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

            // $this->Common_model->insert_data('admin_role',$data);
        endforeach;
        /* print_r($allAccess);
         exit;*/
        $this->db->insert_batch('admin_role', $allAccess);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = 'Admin Access Can Not Save added';
            $this->session->set_flashdata('error', $msg);
        else:
            $msg = 'Admin Access  Save Successfully';
            $this->session->set_flashdata('success', $msg);
        endif;
        redirect(site_url($this->project . '/userAccess'));
    }

    public function insert_menu_accessListByUserRole()
    {

        $this->db->trans_start();
        $user_id = $this->input->post('user_id');
        $navigation = $this->input->post('navigation');
        $this->Common_model->delete_data('admin_role', 'user_role', $user_id);
        $allAccess = array();
        foreach ($navigation as $key => $value):
            unset($data);
            $get_parent_id = $this->Common_model->get_single_data_by_single_column('navigation', 'navigation_id', $value);
            $data['user_role'] = $user_id;
            $data['navigation_id'] = $value;
            $data['parent_id'] = isset($get_parent_id->parent_id) ? $get_parent_id->parent_id : 0;
            $allAccess[] = $data;
        endforeach;
        /*echo  "<pre>";
        print_r($navigation);
        print_r($allAccess);
        exit;*/
        $this->db->insert_batch('admin_role', $allAccess);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE):
            $msg = 'Admin Access Can Not Save added';
            $this->session->set_flashdata('error', $msg);
        else:
            $msg = 'Admin Access  Save Successfully';
            $this->session->set_flashdata('success', $msg);

        endif;
        redirect(site_url($this->project . '/userAccess'));
    }

    public function dashbordTodaySummary()
    {
        if ($this->input->is_ajax_request()) {
            $searchDay = $this->input->post('searchDay');
            $Customer_Receivable = $this->config->item("Customer_Receivable");
            $Supplier_Payables = $this->config->item("Supplier_Payables");
            $Cash_at_Hand = $this->config->item("Cash_at_Hand");
            $Cash_at_Bank = $this->config->item("Cash_at_Bank");
            $accountReceiable = $this->Accounts_model->get_third_level_group_sum(1, $Customer_Receivable);
            // echo  "<pre>";
            //print_r($this->db->last_query());exit;
            $data['accountReceiable'] = $accountReceiable->Balance;
            $accountPayable = $this->Accounts_model->get_third_level_group_sum(2, $Supplier_Payables);


            $data['accountPayable'] = $accountPayable->Balance;//$accountPayable->Balance < 0 ?  : $accountPayable->Balance;
            $cashInHand = $this->Accounts_model->get_third_level_group_sum(1, $Cash_at_Hand);
            $data['cashInHand'] = $cashInHand->Balance;
            $data['totalSalesAmount'] = $totalSales = $this->Dashboard_Model->getTotalSales($searchDay = '');
            $data['inventoryAmount'] = $this->Dashboard_Model->getinventoryAmount($searchDay = '');
            $CashAtBank = $this->Accounts_model->get_third_level_group_sum(1, $Cash_at_Bank);
            $data['totalCashAtBank'] = $CashAtBank->Balance;
            log_message('error', 'this is dash bord summary' . print_r($data, true));
            $this->load->view('distributor/ajax/dashbordSummary', $data);
        }

    }
}
