<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReturnDagameController extends CI_Controller
{

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public $page_type;
    public $TypeDR;
    public $TypeCR;

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Common_model', 'Finane_Model', 'Inventory_Model', 'Sales_Model');
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('ReturnDamageModel');
        $this->load->model('Sales_Model');
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
        $this->TypeDR = 1;
        $this->TypeCR = 2;
    }


    function deleteDamageProduct($damageId)
    {
        $data['is_active'] = 'N';
        $data['is_delete'] = 'Y';
        $this->Common_model->update_data('damageproduct', $data, 'damage_id', $damageId);
        message("Your data successfully deleted from database.");
        redirect(site_url('damageProduct'));

    }

    function damageProductAdd()
    {

        if (isPostBack()) {

            $this->db->trans_start();
            $category_id = $this->input->post('category_id');
            foreach ($category_id as $key => $value):
                unset($damage);
                $damage['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $damage['category_id'] = $_POST['category_id'][$key];
                $damage['product_id'] = $_POST['product_id'][$key];
                $damage['quantity'] = $_POST['quantity'][$key];
                $damage['unit_price'] = $_POST['rate'][$key];
                $damage['created_by'] = $this->admin_id;
                $damage['dist_id'] = $this->dist_id;
                $damage['is_delete'] = 'N';
                $damage['is_active'] = 'Y';
                $this->Common_model->insert_data('damageproduct', $damage);
            endforeach;


            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE):
                $msg = 'Your data can not be inserted';
                $this->session->set_flashdata('error', $msg);
            //redirect(site_url($this->project . '/salesReturnAdd'));
            else:
                $msg = 'Your data successfully inserted into database';
                $this->session->set_flashdata('success', $msg);
                //redirect(site_url($this->project . '/salesReturnAdd'));
            endif;
            redirect($this->project . '/damageProduct');
        }


        $data['title'] = 'Damage Product ';
        $data['pageTitle'] = 'Damage Product Add';


        /*page navbar details*/
        $data['title'] = get_phrase('Damage Product Add');
        $data['page_type'] = get_phrase('inventory');
        $data['link_page_name'] = get_phrase('Damage Product  List');
        $data['link_page_url'] = $this->project . '/damageProduct';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/


        $data['dist_id'] = $this->dist_id;
        // $data['damageProduct'] = $this->ReturnDamageModel->getDamageProduct();
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/damage/damageProductAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function damageProduct()
    {


        //die();

        $data['title'] = '';
        $data['pageTitle'] = 'Damage Product';

        /*page navbar details*/
        $data['title'] = get_phrase('Damage Product ');
        $data['page_type'] = get_phrase('inventory');
        $data['link_page_name'] = get_phrase('Damage Product  Add');
        $data['link_page_url'] = $this->project . '/damageProductAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/


        $data['dist_id'] = $this->dist_id;
        $data['damageProduct'] = $this->ReturnDamageModel->getDamageProduct($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/damage/damageProduct', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function salesReturn()
    {

        /*page navbar details*/
        $data['title'] = get_phrase('Sales Return ');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_return_Invoice');
        $data['link_page_url'] = $this->project . '/salesReturnAdd';
        $data['link_icon'] = "<i class='ace-icon fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/sales/salesReturn/salesReturn', $data, true);

        $this->load->view('distributor/masterTemplate', $data);


    }

    function showAllInvoiceListByDate()
    {
        if ($this->input->is_ajax_request()) {
            $date = date('Y-m-d', strtotime($this->input->post('date')));
            $allInvoice = $this->ReturnDamageModel->getInvoiceListByDate($date, $this->dist_id);
            $append = "";
            if (!empty($allInvoice)):
                $append .= '<option value="" disabled selected> Select Invoice </option>';
                foreach ($allInvoice as $eachInfo):
                    $selec = '';
                    $append .= '<option value="' . $eachInfo->sales_invoice_id . '">' . $eachInfo->invoice_no . '</option>';
                endforeach;
            else:
                $append .= '<option>Empty!</option>';
            endif;
            echo $append;
        }
    }

    function getInvoiceProductList()
    {
        if ($this->input->is_ajax_request()) {
            $invoiceId = $this->input->post('invoiceId');
            $data['productList'] = $this->Common_model->get_data_list_by_single_column('sales_details', 'sales_invoice_id', $invoiceId);
            $data['returnProductList'] = $this->ReturnDamageModel->getInvoiceReturnProduct($invoiceId);
            return $this->load->view('distributor/ajax/loadInvoiceProduct', $data);
        }
    }

    function getInvoiceProductList2()
    {


        $json = array();
        $list = $this->ReturnDamageModel->get_sales_invoice_details_for_return();


        $property_1 = get_property_list_for_show_hide(1);
        $property_2 = get_property_list_for_show_hide(2);
        $property_3 = get_property_list_for_show_hide(3);
        $property_4 = get_property_list_for_show_hide(4);
        $property_5 = get_property_list_for_show_hide(5);


        $data = array();
        foreach ($list as $element) {
            $condition = array(
                'form_id' => 5,
                'type' => 1,
                'parent_stock_id' => $element['sales_details_id'],
                'product_id' => $element['product_id'],
            );
            $sales_return_qty = $this->ReturnDamageModel->get_product_total_return_qty($condition);

            $quantity = $element['quantity'] - $sales_return_qty;
            if ($element['quantity'] - $sales_return_qty == 0) {
                $check_box = "";
            } else {
                $check_box = "<input class='form-check-input checkbox_for_return' name='sales_details_id[]' type='checkbox' value='" . $element['sales_details_id'] . "' id='defaultCheck1" . $element['sales_details_id'] . "'>";

            }

            $row = array();
            $row[] = $element['sales_details_id'];
            $row[] = $element['invoice_no'];
            $row[] = $element['invoice_date'];
            $row[] = $element['productName'] . "<input type='hidden'  id='sales_invoice_id_" . $element['sales_invoice_id'] . "' name='sales_invoice_id[" . $element['sales_details_id'] . "]' value='" . $element['sales_invoice_id'] . "'/>";;
            $row[] = "<input type='text' class='form-control quantity decimal' attr-sales-details-id='" . $element['sales_details_id'] . "'  id='quantity_" . $element['sales_details_id'] . "' name='quantity[" . $element['sales_details_id'] . "]' value='" . $quantity . "' placeholder='" . $element['quantity'] . "' attr-actual-quantity='" . $element['quantity'] . "' readonly='true'  onclick='this.select();'/>";
            $row[] = "<input type='text' class='form-control unit_price decimal' attr-sales-details-id='" . $element['sales_details_id'] . "' id='unit_price_" . $element['sales_details_id'] . "' name='unit_price[" . $element['sales_details_id'] . "]' value='" . $element['unit_price'] . "' placeholder='" . $element['unit_price'] . "' arrt-actual-unit-price='" . $element['unit_price'] . "' readonly='true' onclick='this.select();'/>";;
            $row[] = "<input type='text' class='form-control tt_price decimal' attr-sales-details-id='" . $element['sales_details_id'] . "' id='tt_price_" . $element['sales_details_id'] . "' name='tt_price[" . $element['sales_details_id'] . "]' value='" . $quantity * $element['unit_price'] . "' placeholder='" . $element['quantity'] * $element['unit_price'] . "' readonly='true' onclick='this.select();'/>";
            if ($property_1 != 'dont_have_this_property') {
                $row[] = $element['property_1'];
            }
            if ($property_2 != 'dont_have_this_property') {
                $row[] = $element['property_2'];
            }
            if ($property_3 != 'dont_have_this_property') {
                $row[] = $element['property_3'];
            }
            if ($property_4 != 'dont_have_this_property') {
                $row[] = $element['property_4'];
            }
            if ($property_5 != 'dont_have_this_property') {
                $row[] = $element['property_5'];
            }
            $row[] = $check_box;
            $data[] = $row;
        }

        $json['data'] = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ReturnDamageModel->countAll(),
            "recordsFiltered" => $this->ReturnDamageModel->countFiltered(),
            "data" => $data,
        );
        //output to json format
        $this->output->set_header('Content-Type: application/json');
        echo json_encode($json['data']);
    }

    function viewSalesReturn($id)
    {
        $data['title'] = 'Sales Return View';
        $data['pageTitle'] = 'Sales Return ';
        $data['dist_id'] = $this->dist_id;
        $data['saleReturnInfo'] = $this->ReturnDamageModel->salesReturnInfo($id, $this->dist_id);

        $data['salesDetailsInfo'] = $this->ReturnDamageModel->salesDetailsInfo($id, $this->dist_id);
        
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/salesReturn/salesReturnView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    function salesReturnAdd_bk()
    {

        if (isPostBack()) {
            dumpVar($_POST);
            $sales_invoice_id = $this->input->post('sales_invoice_id');
            $stockId = $this->input->post('sales_details_id');
            $stockQty = $this->input->post('sales_details_qty');
            $return_date = date('Y-m-d');
            $branch_id = 1;
            $qty = '';
            $price = '';
            $condition = array(
                'for' => 2,
                'BackReferenceInvoiceID' => $sales_invoice_id
            );
            $sales_invoice_voucher = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $condition);
            $accountingVoucherId = $sales_invoice_voucher->Accounts_VoucherMst_AutoID;
            $Delete['is_active'] = 'N';
            $Delete['is_delete'] = 'Y';
            $Delete['update_by'] = $this->admin_id;
            $Delete['update_date'] = $this->timestamp;
            $Delete['delete_by'] = $this->admin_id;
            $Delete['delete_date'] = $this->timestamp;
            $qty = 0;
            $price = 0;
            $this->db->trans_start();


            foreach ($stockId as $a => $value) {
                $returnProduct = $this->Common_model->get_single_data_by_single_column('sales_details', 'sales_details_id', $value);
                $qty += $stockQty[$a];
                $price += $stockQty[$a] * $returnProduct->unit_price;
            }


            $returnPrice = 0;
            $totalProductCost = 0;
            foreach ($stockId as $a => $value) {
                $returnProduct = $this->Common_model->get_single_data_by_single_column('sales_details', 'sales_details_id', $value);
                $productCost = $this->Sales_Model->productCostNew($returnProduct->product_id, $this->dist_id);
                $totalProductCost += $stockQty[$a] * $productCost;
                unset($return);
                $return['sales_invoice_id'] = $sales_invoice_id;
                $return['sales_details_id'] = $returnProduct->sales_details_id;
                $return['customer_id'] = $this->Common_model->tableRow('sales_invoice_info', 'sales_invoice_id', $sales_invoice_id)->customer_id;
                $return['return_date'] = $return_date;
                $return['product_id'] = $returnProduct->product_id;
                $return['return_quantity'] = $stockQty[$a];
                $return['unit_price'] = $returnProduct->unit_price;
                $return['last_purchase_price'] = 0;
                $return['branch_id'] = $this->input->post('branch_id');
                $return['created_at'] = $this->timestamp;
                $return['insert_by'] = $this->admin_id;
                $return['is_active'] = 'Y';
                $return['is_delete'] = 'N';


                $this->Common_model->insert_data('sales_return', $return);
                $this->db->where('sales_details_id', $returnProduct->sales_details_id);
                $this->db->update('sales_details', $Delete);


                $condition = array(
                    'related_id' => $returnProduct->product_id,
                    'related_id_for' => 1,
                    'is_active' => "Y",
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable['TypeID'] = $this->TypeDR;//'2';//Cr
                $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("Refill");//'95';
                $accountingDetailsTable['GR_DEBIT'] = $stockQty[$a] * $productCost;
                $accountingDetailsTable['GR_CREDIT'] = '0.00';
                $accountingDetailsTable['Reference'] = '';
                $accountingDetailsTable['IsActive'] = 1;
                $accountingDetailsTable['Changed_By'] = $this->admin_id;
                $accountingDetailsTable['Changed_Date'] = $this->timestamp;
                $accountingDetailsTable['BranchAutoId'] = $branch_id;
                $accountingDetailsTable['date'] = $return_date;
                $finalDetailsArray[] = $accountingDetailsTable;
                $accountingDetailsTable = array();

            }


            $accountingDetailsTableSales = array();
            $accountingDetailsTableSales['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
            $accountingDetailsTableSales['TypeID'] = $this->TypeDR;//'2';//Cr
            $accountingDetailsTableSales['CHILD_ID'] = $this->config->item("Sales");
            $accountingDetailsTableSales['GR_DEBIT'] = $price;
            $accountingDetailsTableSales['GR_CREDIT'] = '0.00';
            $accountingDetailsTableSales['Reference'] = 'Sales Return  Amount';
            $accountingDetailsTableSales['IsActive'] = 1;
            $accountingDetailsTableSales['Changed_By'] = $this->admin_id;
            $accountingDetailsTableSales['Changed_Date'] = $this->timestamp;
            $accountingDetailsTableSales['BranchAutoId'] = $branch_id;
            $accountingDetailsTableSales['date'] = $return_date;
            $finalDetailsArray[] = $accountingDetailsTableSales;
            $accountingDetailsTableSales = array();

            $condtion = array(
                'related_id' => $this->Common_model->tableRow('sales_invoice_info', 'sales_invoice_id', $sales_invoice_id)->customer_id,
                'related_id_for' => 3,
                'is_active' => "Y",
            );
            $accountingDetailsTableCustomerReceivable = array();
            $CustomerReceivable = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
            /*Customer Receivable   =>>33*/
            $accountingDetailsTableCustomerReceivable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
            $accountingDetailsTableCustomerReceivable['TypeID'] = $this->TypeCR;//'1';//Dr
            $accountingDetailsTableCustomerReceivable['CHILD_ID'] = $CustomerReceivable->id;//$this->config->item("Customer_Receivable");
            $accountingDetailsTableCustomerReceivable['GR_DEBIT'] = '0.00';
            $accountingDetailsTableCustomerReceivable['GR_CREDIT'] = $price;
            $accountingDetailsTableCustomerReceivable['Reference'] = 'Customer Receivable For Return';
            $accountingDetailsTableCustomerReceivable['IsActive'] = 1;
            $accountingDetailsTableCustomerReceivable['Changed_By'] = $this->admin_id;
            $accountingDetailsTableCustomerReceivable['Changed_Date'] = $this->timestamp;
            $accountingDetailsTableCustomerReceivable['BranchAutoId'] = $branch_id;
            $accountingDetailsTableCustomerReceivable['date'] = $return_date;
            $finalDetailsArray[] = $accountingDetailsTableCustomerReceivable;
            $accountingDetailsTableCustomerReceivable = array();


            $accountingDetailsTableCostofGoodsProduct['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
            $accountingDetailsTableCostofGoodsProduct['TypeID'] = $this->TypeCR;//'1';//Dr
            $accountingDetailsTableCostofGoodsProduct['CHILD_ID'] = $this->config->item("Cost_of_Goods_Product");//'45';
            $accountingDetailsTableCostofGoodsProduct['GR_DEBIT'] = '0.00';
            $accountingDetailsTableCostofGoodsProduct['GR_CREDIT'] = $totalProductCost;
            $accountingDetailsTableCostofGoodsProduct['Reference'] = 'Cost of Goods Product Sales Return';
            $accountingDetailsTableCostofGoodsProduct['IsActive'] = 1;
            $accountingDetailsTableCostofGoodsProduct['Changed_By'] = $this->admin_id;
            $accountingDetailsTableCostofGoodsProduct['Changed_Date'] = $this->timestamp;
            $accountingDetailsTableCostofGoodsProduct['BranchAutoId'] = $branch_id;
            $accountingDetailsTableCostofGoodsProduct['date'] = $return_date;
            $finalDetailsArray[] = $accountingDetailsTableCostofGoodsProduct;
            $accountingDetailsTableCostofGoodsProduct = array();


            if (!empty($finalDetailsArray)) {
                $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
            }


            unset($data);
            $data['customer_id'] = $this->Common_model->tableRow('sales_invoice_info', 'sales_invoice_id', $sales_invoice_id)->customer_id;
            $data['date'] = date('Y-m-d');
            $data['form_id'] = '31';
            $data['debit'] = $price;
            $data['dist_id'] = $this->dist_id;
            $data['updated_by'] = $this->admin_id;
            $generalsId = $this->Common_model->insert_data('generals', $data);
//ccustomeeer receiiiive
            $singleLedger = array(
                'generals_id' => $generalsId,
                'date' => date('Y-m-d'),
                'form_id' => '31',
                'dist_id' => $this->dist_id,
                'account' => '58',
                'credit' => $price,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);

//49  Sales head credit
            $singleLedger = array(
                'generals_id' => $generalsId,
                'date' => date('Y-m-d'),
                'form_id' => '31',
                'dist_id' => $this->dist_id,
                'account' => '49',
                'debit' => $price,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);

//Cost of Goods Product
            $singleLedger = array(
                'generals_id' => $generalsId,
                'date' => date('Y-m-d'),
                'form_id' => '31',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'credit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);

//Inventory stock
            $singleLedger = array(
                'generals_id' => $generalsId,
                'date' => date('Y-m-d'),
                'form_id' => '31',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE):
                $msg = 'Your data can not be inserted';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/salesReturnAdd'));
            else:
                $msg = 'Your data successfully inserted into database';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/salesReturnAdd'));
            endif;


        }
        /*page navbar details*/
        $data['title'] = get_phrase('Sales Return Add');
        $data['page_type'] = get_phrase('sales');
        $data['link_page_name'] = get_phrase('Sales Return List');
        $data['link_page_url'] = $this->project . '/salesReturn';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['dist_id'] = $this->dist_id;
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/salesReturn/salesReturnAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function salesReturnAdd()
    {

        if (isPostBack()) {

            $sales_invoice_id = $this->input->post('sales_invoice_id');
            $sales_details_id = $this->input->post('sales_details_id');
            $customer_id = $this->input->post('customer_id');
            $quantity = $this->input->post('quantity');
            $unit_price = $this->input->post('unit_price');
            $branch_id = $this->input->post('branch_id');
            $naration = $this->input->post('naration');
            $return_date=date('Y-m-d', strtotime($this->input->post('return_date')));
            //$return_date = date('Y-m-d');


            $this->db->trans_start();


            $query = $this->db->field_exists('parent_invoice_id', 'stock');
            if ($query != TRUE) {
                $this->load->dbforge();
                $fields = array(
                    'parent_invoice_id' => array(
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'default' => '0',
                        'null' => TRUE
                    )
                );
                $this->dbforge->add_column('stock', $fields);
            }



            $condition_sales_invoice_info = array(
                'for' => 7,

            );
            $number_of_sales_return_voucher = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $condition_sales_invoice_info);
            if (empty($number_of_sales_return_voucher)) {
                $query = "ALTER TABLE `ac_accounts_vouchermst` CHANGE `for` `for` INT(11) NOT NULL DEFAULT '0' COMMENT '1->purchase invoice,2->sales invoice,3->purchase invoice supplier Payment money recive,4->purchase invoice supplier Pending Caque money recive,5->Sales invoice Customer Payment money recive,6->sales invoice customer Pending Cheque money recive ,7->sales return';
";
                $this->db->query($query);
            }


            if ($this->db->table_exists('return_info') == false) {


                $sql = "CREATE TABLE `return_info` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`return_invoice_no` VARCHAR(255) NOT NULL,
	`return_type` INT(2) NOT NULL COMMENT '1->purchase_return,2->sales_return',
	`sup_cus_id` INT(11) NOT NULL COMMENT 'supplier/customer id',
	`return_date` DATE NOT NULL,
	`narration` TEXT NULL,
	`insert_by` INT(11) NULL DEFAULT NULL,
	`insert_date` TIMESTAMP NULL DEFAULT NULL,
	`update_by` INT(11) NULL DEFAULT NULL,
	`update_date` DATETIME NULL DEFAULT NULL,
	`delete_by` INT(11) NULL DEFAULT NULL,
	`delete_date` DATETIME NULL DEFAULT NULL,
	`is_active` VARCHAR(10) NULL DEFAULT 'Y',
	`is_delete` VARCHAR(10) NULL DEFAULT 'N',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;";
                $this->db->query($sql);
            }


            $sql = "SELECT COUNT(id) AS sales_return_number FROM return_info WHERE return_type =2";
            //$result = row_array($sql);

            $query = $this->db->query($sql);
            $result = $query->row_array();

            if (!empty($result['sales_return_info_number'])):
                $total_sales_return_number = $result['sales_return_info_number'];
            else:
                $total_sales_return_number = 0;
            endif;

            $sales_return_invoice_no = "SR" . date('y') . date('m') . str_pad(($total_sales_return_number) + 1, 4, "0", STR_PAD_LEFT);

            $sales_return_invoiceTable['return_invoice_no'] = $sales_return_invoice_no;
            $sales_return_invoiceTable['return_type'] = 2;
            $sales_return_invoiceTable['sup_cus_id'] = $customer_id;
            $sales_return_invoiceTable['return_date'] = $return_date;
            $sales_return_invoiceTable['insert_by'] = $this->admin_id;
            $sales_return_invoiceTable['insert_date'] = $this->timestamp;
            $sales_return_invoiceTable['is_active'] = 'Y';
            $sales_return_invoiceTable['is_delete'] = "N";
            $sales_return_invoiceTable['narration'] = $naration;
            $sales_return_invoice_id = $this->Common_model->save_and_check('return_info', $sales_return_invoiceTable);


            $voucher_no = create_journal_voucher_no();
            $AccouVoucherType_AutoID = 3;

            $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
            $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
            $accountingMasterTable['Accounts_Voucher_Date'] = $return_date;
            $accountingMasterTable['BackReferenceInvoiceNo'] = $sales_return_invoice_no;
            $accountingMasterTable['BackReferenceInvoiceID'] = $sales_return_invoice_id;
            $accountingMasterTable['Narration'] = $naration;
            $accountingMasterTable['CompanyId'] = $this->dist_id;
            $accountingMasterTable['BranchAutoId'] = $branch_id;
            $accountingMasterTable['customer_id'] = $customer_id;
            $accountingMasterTable['IsActive'] = 1;
            $accountingMasterTable['Created_By'] = $this->admin_id;
            $accountingMasterTable['Created_Date'] = $this->timestamp;
            $accountingMasterTable['for'] = 7;
            $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);

            foreach ($sales_details_id as $a => $value) {

                $condition_sales_details_id = array(

                    'sales_details_id' => $value
                );
                $sales_details = $this->Common_model->get_single_data_by_many_columns('sales_details', $condition_sales_details_id);
                $product_id = $sales_details->product_id;
                $category_id = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $sales_details->product_id)->category_id;
                $product_ledger_condition = array(
                    'related_id' => $product_id,
                    'related_id_for' => 1,
                    'is_active' => "Y",
                );
                $ac_account_product_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $product_ledger_condition);
                /*Inventory stock=>20*/
                $accountingDetailsTable = array();
                $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable['TypeID'] = '1';//Dr
                $accountingDetailsTable['CHILD_ID'] = $ac_account_product_ledger_coa_info->id;
                $accountingDetailsTable['GR_DEBIT'] = $quantity[$value] * $unit_price[$value];
                $accountingDetailsTable['GR_CREDIT'] = '0.00';
                $accountingDetailsTable['Reference'] = 'Sales Rerurn Produnt Recive';
                $accountingDetailsTable['IsActive'] = 1;
                $accountingDetailsTable['Created_By'] = $this->admin_id;
                $accountingDetailsTable['Created_Date'] = $this->timestamp;
                $accountingDetailsTable['BranchAutoId'] = $branch_id;
                $accountingDetailsTable['date'] = $return_date;

                //$finalDetailsArray[] = $accountingDetailsTable;

                $ac_tb_accounts_voucherdtl_id = $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTable);


                $lastPurchasepriceArray = $this->db->where('product_id', $product_id)
                    ->where('branch_id', $branch_id)
                    ->order_by('purchase_details_id', "desc")
                    ->limit(1)
                    ->get('purchase_details')
                    ->row();
                $lastPurchaseprice = !empty($lastPurchasepriceArray) ? $lastPurchasepriceArray->unit_price : 0;


                $stockNewTable = array();
                $stockNewTable['parent_stock_id'] = $value;
                $stockNewTable['invoice_id'] = $sales_return_invoice_id;
                $stockNewTable['form_id'] = 5;
                $stockNewTable['type'] = 1;
                $stockNewTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $stockNewTable['Accounts_VoucherDtl_AutoID'] = $ac_tb_accounts_voucherdtl_id;
                $stockNewTable['customer_id'] = $customer_id;
                $stockNewTable['supplier_id'] = 0;
                $stockNewTable['branch_id'] = $branch_id;
                $stockNewTable['invoice_date'] = $return_date;
                $stockNewTable['category_id'] = $category_id;
                $stockNewTable['product_id'] = $product_id;
                $stockNewTable['empty_cylinder_id'] = 0;
                $stockNewTable['is_package'] = 0;
                $stockNewTable['show_in_invoice'] = 0;
                $stockNewTable['unit'] = getProductUnit($product_id);

                $stockNewTable['quantity'] = $quantity[$value];
                $stockNewTable['quantity_out'] = 0;
                $stockNewTable['quantity_in'] = $quantity[$value];
                $stockNewTable['returnable_quantity'] = 0;
                $stockNewTable['return_quentity'] = 0;
                $stockNewTable['due_quentity'] = 0;
                $stockNewTable['advance_quantity'] = 0;
                $stockNewTable['price'] = $unit_price[$value];
                $stockNewTable['price_in'] = $unit_price[$value];
                $stockNewTable['price_out'] = 0;
                $stockNewTable['last_purchase_price'] = $lastPurchaseprice;
                $stockNewTable['product_details'] = "";
                $stockNewTable['property_1'] = $sales_details->property_1;
                $stockNewTable['property_2'] = $sales_details->property_2;
                $stockNewTable['property_3'] = $sales_details->property_3;
                $stockNewTable['property_4'] = $sales_details->property_4;
                $stockNewTable['property_5'] = $sales_details->property_5;
                $stockNewTable['openingStatus'] = 0;
                $stockNewTable['insert_by'] = $this->admin_id;
                $stockNewTable['insert_date'] = $this->timestamp;
                $stockNewTable['update_by'] = '';
                $stockNewTable['update_date'] = '';
                $stockNewTable['parent_invoice_id'] = $sales_invoice_id[$value];

                $this->Common_model->insert_data('stock', $stockNewTable);


                $condtion = array(
                    'related_id' => $customer_id,
                    'related_id_for' => 3,
                    'is_active' => "Y",
                );
                $customer_ledger = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $accountingDetailsTable = array();
                $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                $accountingDetailsTable['TypeID'] = '1';//Dr
                $accountingDetailsTable['CHILD_ID'] = $customer_ledger->id;//Supplier_Payables'34';
                $accountingDetailsTable['GR_DEBIT'] = '0.00';
                $accountingDetailsTable['GR_CREDIT'] = $quantity[$value] * $unit_price[$value];
                $accountingDetailsTable['Reference'] = 'Customer Paid Balance for sales Return';
                $accountingDetailsTable['IsActive'] = 1;
                $accountingDetailsTable['Created_By'] = $this->admin_id;
                $accountingDetailsTable['Created_Date'] = $this->timestamp;
                $accountingDetailsTable['BranchAutoId'] = $branch_id;
                $accountingDetailsTable['date'] = $return_date;
                $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $accountingDetailsTable);


            }


            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE):
                $msg = 'Your data can not be inserted';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/salesReturnAdd'));
            else:
                $msg = 'Your data successfully inserted into database';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/salesReturnAdd'));
            endif;


        }
        /*page navbar details*/
        $data['title'] = get_phrase('Sales Return Add');
        $data['page_type'] = get_phrase('sales');
        $data['link_page_name'] = get_phrase('Sales Return List');
        $data['link_page_url'] = $this->project . '/salesReturn';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['dist_id'] = $this->dist_id;
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/salesReturn/salesReturnAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


}
