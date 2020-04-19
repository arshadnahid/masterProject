<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReturnDagameController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public $page_type;
    public $TypeDR;
    public $TypeCR;
    public function __construct() {
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


    function deleteDamageProduct($damageId){
        $data['is_active']='N';
        $data['is_delete']='Y';
        $this->Common_model->update_data('damageproduct',$data,'damage_id',$damageId);
        message("Your data successfully deleted from database.");
        redirect(site_url('damageProduct'));

    }

    function damageProductAdd() {

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

    function damageProduct() {


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

    function salesReturn() {
        $condition = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'dist_id' => 'N',
        );
        $data['title'] = 'Sales Return ';
        $data['pageTitle'] = 'Sales Return ';
        $data['dist_id'] = $this->dist_id;
        $data['salesReturnList'] = $this->ReturnDamageModel->getReturnProduct($this->dist_id);


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/salesReturn/salesReturn', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function showAllInvoiceListByDate() {
        if ($this->input->is_ajax_request()) {
            $date = date('Y-m-d', strtotime($this->input->post('date')));
            $allInvoice = $this->ReturnDamageModel->getInvoiceListByDate($date, $this->dist_id);
            $append="";
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

    function getInvoiceProductList() {
        if ($this->input->is_ajax_request()) {
            $invoiceId = $this->input->post('invoiceId');
            $data['productList'] = $this->Common_model->get_data_list_by_single_column('sales_details', 'sales_invoice_id', $invoiceId);
            $data['returnProductList'] = $this->ReturnDamageModel->getInvoiceReturnProduct($invoiceId);
            return $this->load->view('distributor/ajax/loadInvoiceProduct', $data);
        }
    }

    function viewSalesReturn($id) {
        $data['title'] = 'Sales Return View';
        $data['pageTitle'] = 'Sales Return ';
        $data['dist_id'] = $this->dist_id;
        $data['saleReturnInfo'] = $this->ReturnDamageModel->salesReturnInfo($id, $this->dist_id);
        $data['salesDetailsInfo'] = $this->ReturnDamageModel->salesDetailsInfo($id, $this->dist_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/salesReturn/salesReturnView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    function salesReturnAdd() {

        if (isPostBack()) {
            //dumpVar($_POST);
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
            $accountingVoucherId=$sales_invoice_voucher->Accounts_VoucherMst_AutoID;
            $Delete['is_active'] = 'N';
            $Delete['is_delete'] = 'Y';
            $Delete['update_by'] = $this->admin_id;
            $Delete['update_date'] = $this->timestamp;
            $Delete['delete_by'] = $this->admin_id;
            $Delete['delete_date'] = $this->timestamp;
            $qty=0;
            $price=0;
            $this->db->trans_start();




            foreach ($stockId as $a => $value) {
                $returnProduct = $this->Common_model->get_single_data_by_single_column('sales_details', 'sales_details_id', $value);
                $qty+=$stockQty[$a];
                $price+=$stockQty[$a]*$returnProduct->unit_price;
            }



            $returnPrice = 0;
            $totalProductCost=0;
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
        $data['customerList'] = $this->ReturnDamageModel->getCustomerList();
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales/salesReturn/salesReturnAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }



}
