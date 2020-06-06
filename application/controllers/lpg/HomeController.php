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

        if ($this->db->table_exists('user_role') == false) {

            $queryUserRole = "CREATE TABLE `user_role` (
            `id`  int(11) NOT NULL AUTO_INCREMENT ,
            `role_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
            `details`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
            PRIMARY KEY (`id`)
            )
            ENGINE=InnoDB
            DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
            ROW_FORMAT=Compact
            ;";
            $this->db->query($queryUserRole);
        } if ($this->db->table_exists('product_property') == false) {

        $query_product_property = "CREATE TABLE `product_property` (
            `property_id`  int(11) NOT NULL AUTO_INCREMENT ,
            `property_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
            `property_name_show`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            `is_show`  int(11) NOT NULL DEFAULT 0 ,
            PRIMARY KEY (`property_id`)
            )
            ENGINE=InnoDB
            DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
            ROW_FORMAT=Compact
            ;";
        $this->db->query($query_product_property);
    }

        if ($this->db->table_exists('tb_color') == false) {


            $sql = "CREATE TABLE `tb_color` (
	`ColorID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`Color` VARCHAR(255) NOT NULL,
	`BranchAutoId` INT(11) NOT NULL,
	`IsActive` INT(2) NULL DEFAULT NULL,
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`dist_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`ColorID`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;
";
            $this->db->query($sql);
        }
        if ($this->db->table_exists('tb_model') == false) {


            $sql = "CREATE TABLE `tb_model` (
	`ModelID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`Model` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`BranchAutoId` INT(11) NOT NULL,
	`IsActive` INT(2) NULL DEFAULT NULL,
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`dist_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`ModelID`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;
";
            $this->db->query($sql);
        }
        if ($this->db->table_exists('tb_size') == false) {


            $sql = "CREATE TABLE `tb_size` (
	`SizeID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`Size` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`BranchAutoId` INT(11) NOT NULL,
	`IsActive` INT(2) NULL DEFAULT NULL,
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`dist_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`SizeID`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;
";
            $this->db->query($sql);
        }

        if ($this->db->table_exists('tb_subcategory') == false) {


            $sql = "CREATE TABLE `tb_subcategory` (
	`SubCatID` INT(11) NOT NULL AUTO_INCREMENT,
	`SubCatName` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`BranchAutoId` INT(11) NOT NULL,
	`IsActive` INT(2) NULL DEFAULT NULL,
	`Created_By` INT(11) NULL DEFAULT NULL,
	`Created_Date` DATETIME NULL DEFAULT NULL,
	`Changed_By` INT(11) NULL DEFAULT NULL,
	`Changed_Date` DATETIME NULL DEFAULT NULL,
	`dist_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`SubCatID`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

";
            $this->db->query($sql);
        }


        if ($this->db->table_exists('voucher_mapping_table') == false) {


            $sql = "CREATE TABLE `voucher_mapping_table` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`module_name` INT(11) NULL DEFAULT NULL,
	`voucher_name` INT(11) NULL DEFAULT NULL,
	`voucher_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_voucher_mapping_table_ac_account_ledger_coa` (`voucher_id`),
	CONSTRAINT `FK_voucher_mapping_table_ac_account_ledger_coa` FOREIGN KEY (`voucher_id`) REFERENCES `ac_account_ledger_coa` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;";
            $this->db->query($sql);
        }

        $query = $this->db->field_exists('property_1', 'stock');
        if ($query != TRUE) {
            $this->db->query("DROP TABLE stock;");


            $sql = "CREATE TABLE `stock` (
	`stock_id` INT(11) NOT NULL AUTO_INCREMENT,
	`parent_stock_id` INT(11) NULL DEFAULT '0',
	`invoice_id` INT(11) NOT NULL,
	`form_id` INT(3) NOT NULL,
	`type` INT(11) NOT NULL COMMENT '1=inventory in,2=inventory out,3=cylinder stock in,4=cylinder stock out',
	`Accounts_VoucherMst_AutoID` INT(11) NULL DEFAULT NULL,
	`Accounts_VoucherDtl_AutoID` INT(11) NULL DEFAULT NULL,
	`customer_id` INT(11) NULL DEFAULT '0',
	`supplier_id` INT(11) NULL DEFAULT '0',
	`branch_id` INT(11) NOT NULL,
	`invoice_date` DATE NOT NULL,
	`category_id` INT(3) NOT NULL,
	`product_id` INT(4) NOT NULL,
	`empty_cylinder_id` INT(11) NULL DEFAULT '0',
	`is_package` INT(11) NULL DEFAULT '0',
	`show_in_invoice` INT(11) NULL DEFAULT '1',
	`unit` INT(3) NOT NULL,
	`quantity` DECIMAL(10,2) NOT NULL,
	`quantity_in` DECIMAL(10,2) NULL DEFAULT '0.00',
	`quantity_out` DECIMAL(10,2) NULL DEFAULT '0.00',
	`returnable_quantity` FLOAT NULL DEFAULT '0',
	`return_quentity` FLOAT NULL DEFAULT '0',
	`due_quentity` FLOAT NULL DEFAULT '0',
	`advance_quantity` FLOAT NULL DEFAULT '0',
	`price` DECIMAL(10,4) NOT NULL DEFAULT '0.0000',
	`price_in` DECIMAL(10,4) NULL DEFAULT '0.0000',
	`price_out` DECIMAL(10,4) NULL DEFAULT '0.0000',
	`last_purchase_price` DECIMAL(10,4) NULL DEFAULT '0.0000',
	`product_details` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`property_1` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`property_2` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`property_3` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`property_4` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`property_5` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`openingStatus` TINYINT(4) NOT NULL COMMENT '1=opening,0=normal',
	`insert_by` INT(11) NULL DEFAULT NULL,
	`insert_date` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
	`update_by` INT(11) NULL DEFAULT NULL,
	`update_date` DATETIME NULL DEFAULT NULL,
	`delete_by` INT(11) NULL DEFAULT NULL,
	`delete_date` DATETIME NULL DEFAULT NULL,
	`is_active` ENUM('Y','N') NOT NULL COLLATE 'utf8_unicode_ci',
	`is_delete` ENUM('Y','N') NOT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`stock_id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=32
;
";
            $this->db->query($sql);
        }
        $query = $this->db->field_exists('property_2', 'product');
        if ($query != TRUE) {
            $sql = "ALTER TABLE product ADD `property_1` VARCHAR(255) NULL , ADD `property_2` VARCHAR(255) NULL , ADD `property_3` VARCHAR(255) NULL , ADD `property_4` VARCHAR(255) NULL , ADD `property_5` VARCHAR(255) NULL ";
            $this->db->query($sql);
        }
        $query = $this->db->field_exists('property_2', 'purchase_details');
        if ($query != TRUE) {
            $sql = "ALTER TABLE purchase_details ADD `property_1` VARCHAR(255) NULL , ADD `property_2` VARCHAR(255) NULL , ADD `property_3` VARCHAR(255) NULL , ADD `property_4` VARCHAR(255) NULL , ADD `property_5` VARCHAR(255) NULL , ADD `product_details` TEXT NULL";
            $this->db->query($sql);
        }
        $query = $this->db->field_exists('property_2', 'sales_details');
        if ($query != TRUE) {
            $sql = "ALTER TABLE sales_details ADD `property_1` VARCHAR(255) NULL , ADD `property_2` VARCHAR(255) NULL , ADD `property_3` VARCHAR(255) NULL , ADD `property_4` VARCHAR(255) NULL , ADD `property_5` VARCHAR(255) NULL, ADD `product_details` TEXT NULL ";
            $this->db->query($sql);
        }


        $query = $this->db->field_exists('SubCatID', 'productimport');
        if ($query != TRUE) {
            $sql = "ALTER TABLE `productimport` ADD `SubCatID` INT(11) NULL , ADD `SubCatName` VARCHAR(255) NULL , ADD `ModelID` INT(11) NULL , ADD `Model` VARCHAR(255) NULL , ADD `ColorID` INT(11) NULL , ADD `Color` VARCHAR(255) NULL , ADD `SizeID` INT(11) NULL , ADD `Size` VARCHAR(255) NULL , ADD `property_1` VARCHAR(255) NULL , ADD `property_2` VARCHAR(255) NULL , ADD `property_3` VARCHAR(255) NULL, ADD `property_4` VARCHAR(255) NULL, ADD `property_5` VARCHAR(255) NULL ; ";


            $this->db->query($sql);


        }

        $query = $this->db->field_exists('narration', 'cus_due_collection_info');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'narration' => array(
                    'type' => 'TEXT',
                    //'constraint' => '255',
                    //'unsigned' => TRUE,
                    //'after' => 'Reference'
                )
            );
            $this->dbforge->add_column('cus_due_collection_info', $fields);

        }
        $query = $this->db->field_exists('details', 'customer');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'details' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                    //'unsigned' => TRUE,
                    //'after' => 'Reference'
                )
            );
            $this->dbforge->add_column('customer', $fields);
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

        $query = $this->db->field_exists('ReferenceForBackEnd', 'ac_tb_accounts_voucherdtl_history');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'ReferenceForBackEnd' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    //'unsigned' => TRUE,
                    'after' => 'Reference')
            );
            $this->dbforge->add_column('ac_tb_accounts_voucherdtl_history', $fields);
        }
        $query = $this->db->field_exists('for', 'ac_tb_accounts_voucherdtl');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'for' => array(
                    'type' => 'INT',
                    'null' => TRUE,
                    'default' => '0',
                    //'unsigned' => TRUE,
                    'after' => 'ReferenceForBackEnd')
            );
            $this->dbforge->add_column('ac_tb_accounts_voucherdtl', $fields);
        }


        $query = $this->db->field_exists('cus_due_collection_details_id', 'ac_tb_accounts_voucherdtl');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'cus_due_collection_details_id' => array(
                    'type' => 'INT',
                    'null' => TRUE,
                    'default' => '0',
                    //'unsigned' => TRUE,
                    'after' => 'ReferenceForBackEnd')
            );
            $this->dbforge->add_column('ac_tb_accounts_voucherdtl', $fields);
        }


        $query = $this->db->field_exists('invoice_id', 'ac_tb_accounts_voucherdtl');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'invoice_id' => array(
                    'type' => 'INT',
                    'null' => TRUE,
                    'default' => '0',
                    //'unsigned' => TRUE,
                    'after' => 'ReferenceForBackEnd')
            );
            $this->dbforge->add_column('ac_tb_accounts_voucherdtl', $fields);
        }


        $query = $this->db->field_exists('invoice_no', 'ac_tb_accounts_voucherdtl');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'invoice_no' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'default' => '0',
                    //'unsigned' => TRUE,
                    'after' => 'ReferenceForBackEnd')
            );
            $this->dbforge->add_column('ac_tb_accounts_voucherdtl', $fields);
        }


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
        $DamageStock = $this->Common_model->get_data_list_by_many_columns('productcategory', $array);
        if (empty($DamageStock)) {
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
                )
            );
            $this->dbforge->add_column('system_config', $fields);

            $query = $this->db->field_exists('subcategoryID', 'product');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'subcategoryID' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'default' => '0',
                        'null' => TRUE,

                    )
                );
                $this->dbforge->add_column('product', $fields);
            }
            $this->load->dbforge();
            $fields = array(

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


        $queryNeedToRunOrNor = 0;

        $this->db->query("DROP TRIGGER IF EXISTS `ac_accounts_vouchermst_history_trigger`;");
        $this->db->query("DROP TRIGGER IF EXISTS `ac_tb_accounts_voucherdtl_history_trigger`;");
        $this->db->query("DROP TRIGGER IF EXISTS `adminAdd`;");
        $this->db->query("DROP TRIGGER IF EXISTS `sales_details_history_trigger`;");
        $this->db->query("DROP TRIGGER IF EXISTS `sales_invoice_info_history_trigger`;");
        $this->db->query("DROP TRIGGER IF EXISTS `sales_return_details_history_trigger`;");


        //$trigerac_accounts_vouchermst_history_trigger = "CREATE DEFINER=`" . $this->db_username . "`@`localhost` TRIGGER `ac_accounts_vouchermst_history_trigger` BEFORE UPDATE ON `ac_accounts_vouchermst` FOR EACH ROW INSERT INTO ac_accounts_vouchermst_history VALUES ( old.Accounts_VoucherMst_AutoID, old.AccouVoucherType_AutoID, old.for, old.Accounts_Voucher_No, old.Accounts_Voucher_Date, old.BackReferenceInvoiceNo, old.BackReferenceInvoiceID, old.Narration, old.CompanyId, old.BranchAutoId, old.Reference, old.customer_id, old.supplier_id, old.miscellaneous, old.IsActive, old.Created_By, old.Created_Date, old.Changed_By, old.Changed_Date,new.Changed_By, now())";

        // $this->db->query($ac_tb_accounts_voucherdtl_history_trigger);

        /*$this->db->query("DROP TRIGGER IF EXISTS `ac_accounts_vouchermst_history_trigger`;
CREATE DEFINER=`root`@`localhost` TRIGGER `ac_accounts_vouchermst_history_trigger` BEFORE UPDATE ON `ac_accounts_vouchermst` FOR EACH ROW INSERT INTO ac_accounts_vouchermst_history VALUES ( old.Accounts_VoucherMst_AutoID, old.AccouVoucherType_AutoID, old.for, old.Accounts_Voucher_No, old.Accounts_Voucher_Date, old.BackReferenceInvoiceNo, old.BackReferenceInvoiceID, old.Narration, old.CompanyId, old.BranchAutoId, old.Reference, old.customer_id, old.supplier_id, old.miscellaneous, old.IsActive, old.Created_By, old.Created_Date, old.Changed_By, old.Changed_Date, new.Changed_By, now())");*/



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
        $query = $this->db->field_exists('root_id', 'customer');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'credit_limit' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '1',
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

        $query = $this->db->field_exists('user_role', 'admin_role');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'user_role' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0',
                    'null' => TRUE,
                    //'unsigned' => TRUE,
                    // 'after' => 'distributor_id'
                )
            );
            $this->dbforge->add_column('admin_role', $fields);
        }
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

        $query = $this->db->field_exists('user_role', 'admin');
        if ($query != TRUE) {
            $this->load->dbforge();
            $fields = array(
                'user_role' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0',
                    'null' => TRUE,
                    //'unsigned' => TRUE,
                    // 'after' => 'distributor_id'
                )
            );
            $this->dbforge->add_column('admin', $fields);
        }


        $this->db->query("ALTER TABLE `system_config` MODIFY COLUMN `companyName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `dist_id`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `updated_by`  smallint(6) NULL DEFAULT NULL AFTER `created_at`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `created_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `updated_at`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `updated_at`  datetime NULL DEFAULT NULL AFTER `status`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `status`  enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '1=active,2=inactive' AFTER `supAddress`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `supAddress`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `supPhone`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `supPhone`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `supEmail`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `supEmail`  varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `supName`;");
        $this->db->query("ALTER TABLE `supplier` MODIFY COLUMN `supName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `supID`;");
        $this->db->query("ALTER TABLE `sales_invoice_info` ADD COLUMN `cash_ledger_id`  int(11) NULL DEFAULT 0 AFTER `invoice_for`;");
        $this->db->query("ALTER TABLE `sales_invoice_info` MODIFY COLUMN `invoice_for`  tinyint(4) NULL DEFAULT 1 COMMENT '1->general,2->lpg,3-> Warranty Claim Voucher' AFTER `check_date`;");
        $this->db->query("ALTER TABLE `productimport` MODIFY COLUMN `catName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `unitId`;
ALTER TABLE `productimport` MODIFY COLUMN `brandName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `catName`;
ALTER TABLE `productimport` MODIFY COLUMN `unitName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `brandName`;
ALTER TABLE `productimport` MODIFY COLUMN `productName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `unitName`;");
        $this->db->query("ALTER TABLE `productcategory` MODIFY COLUMN `title`  varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `dist_id`;");
        $this->db->query("ALTER TABLE `product` MODIFY COLUMN `productName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `product_type_id`;");
        $this->db->query("ALTER TABLE `package` MODIFY COLUMN `package_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `package_id`;");
        $this->db->query("ALTER TABLE `customer` MODIFY COLUMN `customerType`  tinyint(4) NOT NULL DEFAULT 1 AFTER `customerID`;
ALTER TABLE `customer` MODIFY COLUMN `customerName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `customerType`;
ALTER TABLE `customer` MODIFY COLUMN `customerPhone`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `root_id`;
ALTER TABLE `customer` MODIFY COLUMN `customerEmail`  varchar(56) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `customerPhone`;");
        $this->db->query("ALTER TABLE `bank_branch_info` MODIFY COLUMN `bank_branch_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `bank_branch_id`;
ALTER TABLE `bank_branch_info` MODIFY COLUMN `bank_branch_address`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `bank_branch_name`;
ALTER TABLE `bank_info` MODIFY COLUMN `bank_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `bank_info_id`;");




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


         if ($this->business_type != "LPG") {
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
        } else if ($this->business_type == "MOBILE" || $this->business_type == "MOTORCYCLE") {
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
