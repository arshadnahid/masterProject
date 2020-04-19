<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReturnDagameController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public $page_type;
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
            $qty = '';
            $price = '';

            $Delete['is_active'] = 'N';
            $Delete['is_delete'] = 'Y';
            $Delete['update_by'] = $this->admin_id;
            $Delete['update_date'] = $this->timestamp;
            $Delete['delete_by'] = $this->admin_id;
            $Delete['delete_date'] = $this->timestamp;
            $qty=0;
            $price=0;
            $this->db->trans_start();
            foreach ($stockId as $value) {
                $returnProduct = $this->Common_model->get_single_data_by_single_column('sales_details', 'sales_details_id', $value);
                $qty+=$returnProduct->quantity;
                $price+=$returnProduct->unit_price;
            }
            $data = array();
            $data['return_date'] = date('Y-m-d');
            $data['dist_id'] = $this->dist_id;
            $data['sales_invoice_id'] = $sales_invoice_id;
            $data['customer_id'] = $this->Common_model->tableRow('sales_invoice_info', 'sales_invoice_id', $sales_invoice_id)->customer_id;
            $data['amount'] = $price;
            $data['qty'] = $qty;
            $data['insert_by'] = $this->admin_id;
            $data['is_active'] = 'Y';
            $data['is_delete'] = 'N';
            $insertId = $this->Common_model->insert_data('sales_return', $data);
            $returnPrice = 0;
            $totalProductCost=0;
            foreach ($stockId as $value) {
                $returnProduct = $this->Common_model->get_single_data_by_single_column('sales_details', 'sales_details_id', $value);
                $productCost = $this->ReturnDamageModel->productCost($returnProduct->product_id, $this->dist_id);
                $totalProductCost += $returnProduct->quantity * $productCost;
                unset($return);
                $return['sales_return_id'] = $insertId;
                //$return['date'] = date('Y-m-d');
                //$return['dist_id'] = $this->dist_id;
                $return['product_id'] = $returnProduct->product_id;
                $return['return_quantity'] = $returnProduct->quantity;
                $return['unit_price'] = $returnProduct->unit_price;
                $return['sales_invoice_id'] = $sales_invoice_id;
                $return['insert_by'] = $this->admin_id;
                $return['is_active'] = 'Y';
                $return['is_delete'] = 'N';
                $this->Common_model->insert_data('sales_return_details', $return);

                $this->db->where('sales_details_id', $returnProduct->sales_details_id);
                $this->db->update('sales_details', $Delete);

                //$this->Common_model->delete_data('sales_details', 'sales_details_id', $returnProduct->sales_details_id);
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
            //7501 Cost of Goods-Retail head debit

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
