<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 9:48 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class InventoryAdjustmentController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;
    public $page_type;
    public $folder;
    public $folderSub;
    public $link_icon_add;
    public $link_icon_list;
    public $project;
    public $TypeDR;
    public $TypeCR;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';
        $this->TypeDR = 1;
        $this->TypeCR = 2;
        $this->folderSub = 'distributor/inventory/InventoryAdjustment/';
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

    public function index()
    {
        if (isPostBack()) {

            $this->form_validation->set_rules('branchId', 'Branch Name', 'required');
            $this->form_validation->set_rules('date', 'Date ', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/InventoryAdjustmentWithAccount/'));
            } else {
                $this->load->helper('create_inventory_adjustment_no');
                $branch_id = $this->input->post('branchId');
                $date = date('Y-m-d', strtotime($this->input->post('date')));

                $accountingDetailsTableNewCylinderStock = array();
                $inv_adjustment_no = create_inventory_adjustment_no();
                $this->db->trans_start();
                $data = array();
                $data['inv_adjustment_no'] = $inv_adjustment_no;
                $data['BranchAutoId'] = $branch_id;
                $data['date'] = $date;
                $data['insert_date'] = $this->timestamp;
                $data['insert_by'] = $this->admin_id;
                $id = $this->Common_model->insert_data('inventory_adjustment_info', $data);


                /*Accounting Voucher*/

                $voucherCondition = array(
                    'AccouVoucherType_AutoID' => 3,
                    'BranchAutoId' => $branch_id,
                );
                $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
                $voucherID = "JV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
                $data2['Accounts_Voucher_Date'] = $date;
                $data2['Accounts_Voucher_No'] = create_journal_voucher_no();
                $data2['Narration'] = $this->input->post('narration');
                $data2['CompanyId'] = $this->dist_id;
                $data2['BranchAutoId'] = $this->input->post('branchId');
                $data2['Reference'] = 0;
                $data2['AccouVoucherType_AutoID'] = 3;
                $data2['IsActive'] = 1;
                $data2['Created_By'] = $this->admin_id;
                $data2['Created_Date'] = $this->timestamp;
                $data2['BackReferenceInvoiceNo'] = $inv_adjustment_no;
                $data2['BackReferenceInvoiceID'] = $id;
                $data2['for'] = $this->config->item("accounting_master_table_for_invoice_Adjustment");
                $general_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $data2);


                if ($id) {
                    $last_inserted_id = $id;
                    $inventory_adjustment_details = array();
                    if (!empty($_POST['productID'])) {
                        foreach ($_POST['productID'] as $key => $value) {
                            $inventory_adjustment = array();
                            $inventory_adjustment['inv_adjustment_info_id'] = $last_inserted_id;
                            $inventory_adjustment['product_id'] = $value;
                            $inventory_adjustment['in_qty'] = $_POST['quantity'][$key];
                            $inventory_adjustment['out_qty'] = 0;
                            $inventory_adjustment['BranchAutoId'] = $_POST['branchId'];
                            $inventory_adjustment['unit_price'] = $_POST['rate'][$key];
                            $inventory_adjustment['insert_date'] = $this->timestamp;
                            $inventory_adjustment['insert_by'] = $this->admin_id;
                            $inventory_adjustment['is_active'] = 'Y';
                            $inventory_adjustment_details[] = $inventory_adjustment;


                            $condition = array(
                                'related_id' => $value,
                                'related_id_for' => 1,
                                'is_active' => "Y",
                            );
                            $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                            $accountingDetailsTableNewCylinderStock['Accounts_VoucherMst_AutoID'] = $general_id;
                            $accountingDetailsTableNewCylinderStock['TypeID'] = $this->TypeDR;//'2';//Cr
                            $accountingDetailsTableNewCylinderStock['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("New_Cylinder_Stock");//'22';
                            $accountingDetailsTableNewCylinderStock['GR_DEBIT'] = $_POST['quantity'][$key] * $_POST['rate'][$key];
                            $accountingDetailsTableNewCylinderStock['GR_CREDIT'] = 0;
                            $accountingDetailsTableNewCylinderStock['Reference'] = 'Stock In';
                            $accountingDetailsTableNewCylinderStock['IsActive'] = 1;
                            $accountingDetailsTableNewCylinderStock['Created_By'] = $this->admin_id;
                            $accountingDetailsTableNewCylinderStock['Created_Date'] = $this->timestamp;
                            $accountingDetailsTableNewCylinderStock['BranchAutoId'] = $branch_id;
                            $accountingDetailsTableNewCylinderStock['date'] = $date;
                            $finalDetailsArray[] = $accountingDetailsTableNewCylinderStock;
                            $accountingDetailsTableNewCylinderStock = array();
                        }
                    }
                    if (!empty($_POST['productIdOut'])) {
                        foreach ($_POST['productIdOut'] as $key => $value) {
                            $inventory_adjustment = array();
                            $inventory_adjustment['product_id'] = $value;
                            $inventory_adjustment['inv_adjustment_info_id'] = $last_inserted_id;
                            $inventory_adjustment['product_id'] = $value;
                            $inventory_adjustment['in_qty'] = 0;
                            $inventory_adjustment['out_qty'] = $_POST['quantityOut'][$key];
                            $inventory_adjustment['BranchAutoId'] = $_POST['branchId'];
                            $inventory_adjustment['unit_price'] = $_POST['rateOut'][$key];
                            $inventory_adjustment['insert_date'] = $this->timestamp;
                            $inventory_adjustment['insert_by'] = $this->admin_id;
                            $inventory_adjustment['is_active'] = 'Y';
                            $inventory_adjustment_details[] = $inventory_adjustment;


                            $condition = array(
                                'related_id' => $value,
                                'related_id_for' => 1,
                                'is_active' => "Y",
                            );
                            $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                            $accountingDetailsTableNewCylinderStock['Accounts_VoucherMst_AutoID'] = $general_id;
                            $accountingDetailsTableNewCylinderStock['TypeID'] = $this->TypeCR;//'2';//Cr
                            $accountingDetailsTableNewCylinderStock['CHILD_ID'] = $ac_account_ledger_coa_info->id;//$this->config->item("New_Cylinder_Stock");//'22';
                            $accountingDetailsTableNewCylinderStock['GR_DEBIT'] = 0;
                            $accountingDetailsTableNewCylinderStock['GR_CREDIT'] = $_POST['quantityOut'][$key] * $_POST['rateOut'][$key];
                            $accountingDetailsTableNewCylinderStock['Reference'] = 'Stock Out';
                            $accountingDetailsTableNewCylinderStock['IsActive'] = 1;
                            $accountingDetailsTableNewCylinderStock['Created_By'] = $this->admin_id;
                            $accountingDetailsTableNewCylinderStock['Created_Date'] = $this->timestamp;
                            $accountingDetailsTableNewCylinderStock['BranchAutoId'] = $branch_id;
                            $accountingDetailsTableNewCylinderStock['date'] = $date;
                            $finalDetailsArray[] = $accountingDetailsTableNewCylinderStock;
                            $accountingDetailsTableNewCylinderStock = array();
                        }
                    }
                }
                $account = $this->input->post('accountIN');
                $alldr = array();
                foreach ($account as $key => $value) {
                    unset($jv);
                    $jv['Accounts_VoucherMst_AutoID'] = $general_id;
                    if ($this->input->post('amountDrIN')[$key] > 0) {
                        $jv['TypeID'] = 1;
                    } else {
                        $jv['TypeID'] = 2;
                    }

                    $jv['CHILD_ID'] = $value;
                    $jv['GR_CREDIT'] = $this->input->post('amountCrIN')[$key];
                    $jv['GR_DEBIT'] = $this->input->post('amountDrIN')[$key];
                    $jv['Reference'] = $this->input->post('memoIN')[$key];
                    $jv['ReferenceForBackEnd'] = "Stock-In Additional Transition";
                    $jv['IsActive'] = 1;
                    $jv['Created_By'] = $this->dist_id;
                    $jv['Created_Date'] = $this->timestamp;
                    $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $jv['date'] = $date;
                    $finalDetailsArray[] = $jv;
                }
                $accountOut = $this->input->post('accountOut');
                foreach ($accountOut as $key => $value) {
                    unset($jv);
                    if ($this->input->post('amountDrOut')[$key] > 0) {
                        $jv['TypeID'] = 1;
                    } else {
                        $jv['TypeID'] = 2;
                    }
                    $jv['Accounts_VoucherMst_AutoID'] = $general_id;
                    $jv['CHILD_ID'] = $value;
                    $jv['GR_CREDIT'] = $this->input->post('amountCrOut')[$key];
                    $jv['GR_DEBIT'] = $this->input->post('amountDrOut')[$key];
                    $jv['Reference'] = $this->input->post('memoOut')[$key];
                    $jv['ReferenceForBackEnd'] = "Stock-Out Additional Transition";
                    $jv['IsActive'] = 1;
                    $jv['Created_By'] = $this->dist_id;
                    $jv['Created_Date'] = $this->timestamp;
                    $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $jv['date'] = $date;
                    $finalDetailsArray[] = $jv;
                }
                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                $this->db->insert_batch('inventory_adjustment_details', $inventory_adjustment_details);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Inventory Adjustment ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/InventoryAdjustmentWithAccount'));
                } else {
                    $msg = 'Inventory Adjustment ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/invoiceAdjustmentShow/'.$id));
                }
            }
        }
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['title'] = get_phrase('Inventory Adjustment Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Inventory Adjustment List');
        $data['link_page_url'] = $this->project . '/InventoryAdjustmentList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['branch'] = $this->Common_model->branchList();
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $data['mainContent'] = $this->load->view('distributor/inventory/InventoryAdjustment/InventoryAdjustmentAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public  function  InventoryAdjustmentList(){
        $data['title'] = get_phrase('Inventory Adjustment List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Inventory Adjustment Add');
        $data['link_page_url'] = $this->project.'/InventoryAdjustmentWithAccount';
        $data['adjustmentBrand'] = $this->Common_model->getAllAdjustmentbrand();
        $data['branch'] = $this->Common_model->branchList();
        $data['mainContent'] = $this->load->view('distributor/inventory/InventoryAdjustment/InventoryAdjustmentList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function invoiceAdjustmentShow($id)
    {
        $data['adjustment'] = $this->Common_model->getAllAdjustmentList2($id);
        $Condition = array(
            'for' => 7,
            'BackReferenceInvoiceID' => $id,

        );
        $invoiceAdjustmentVoucher=$this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $Condition);

        $ConditionDetailsIn = array(
            'Accounts_VoucherMst_AutoID' => $invoiceAdjustmentVoucher->Accounts_VoucherMst_AutoID,
            'ReferenceForBackEnd' =>'Stock-In Additional Transition'
        );

        $data['invoiceAdjustmentVoucherIndetails'] = $this->Common_model->get_data_list_by_many_columns('ac_tb_accounts_voucherdtl', $ConditionDetailsIn);
        $ConditionDetailsOut = array(
            'Accounts_VoucherMst_AutoID' => $invoiceAdjustmentVoucher->Accounts_VoucherMst_AutoID,
            'ReferenceForBackEnd' => 'Stock-Out Additional Transition'
        );

        $data['invoiceAdjustmentVoucherOutdetails'] = $this->Common_model->get_data_list_by_many_columns('ac_tb_accounts_voucherdtl', $ConditionDetailsOut);

        $data['title'] = get_phrase('Inventory Adjustment View');
        $data['page_type'] = get_phrase('Configuration');
        $data['link_page_name'] = get_phrase('Inventory Adjustment List');
        $data['link_page_url'] = $this->project . '/InventoryAdjustmentList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['mainContent'] = $this->load->view('distributor/inventory/InventoryAdjustment/invoiceAdjustmentShow', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function invoiceAdjustmentDelete($id)
    {
        $result = $this->db->delete('inventory_adjustment_details', array('inv_adjustment_info_id' => $id));
        $result2 = $this->db->delete('inventory_adjustment_info', array('id' => $id));
        if (!empty($result || $result2)) {
            message("InventoryAdjustment successfully deleted.");
            redirect(site_url($this->project . '/InventoryAdjustmentList'));
        } else {
            exception("You have made no change to deleted.");
            redirect(site_url($this->project . '/InventoryAdjustmentList'));
        }
    }

    function singleInvoiceAdjustment($id)
    {
        $data['adjustment'] = $this->Common_model->getAllAdjustmentList($id);
        echo "<pre>";
        $str = $this->db->last_query();
        print_r($str);
        exit;
    }

    function InventoryAdjustmentEdit($id)
    {
        $data['adjustment'] = $this->Common_model->getAllAdjustmentList($id);
        if (isPostBack()) {
            $this->form_validation->set_rules('branchId', 'Branch Name', 'required');
            // $this->form_validation->set_rules('productIdIn','Caregory Name','trim|required');
            // $this->form_validation->set_rules('priceIn','Price In','trim|required');
            // $this->form_validation->set_rules('rateIn','Rate In','trim|required');
            // $this->form_validation->set_rules('quantityIn','Quantity In','trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/InventoryAdjustment/'));
            } else {
                $this->db->from('inventory_adjustment_info');
                $query = $this->db->get();
                $rowcount = $query->num_rows();
                // $query = $this->db->query('SELECT * FROM inventory_adjustment_info');
                $totalInventoryAdj = $rowcount;
                $data['adjustment'] = $this->Common_model->getAllAdjustmentList($id);
                $inv_adjustment_no = "INVADJ" . date('y') . date('m') . str_pad(($totalInventoryAdj) + 1, 4, "0", STR_PAD_LEFT);
                $this->db->trans_start();
                $data = array();
                $data['BranchAutoId'] = $this->input->post('branchId');
                $data['insert_date'] = $this->timestamp;
                $data['inv_adjustment_no'] = $inv_adjustment_no;
                $data['insert_by'] = $this->admin_id;
                $data['is_active'] = 'Y';
                $this->Common_model->insert_data('inventory_adjustment_info', $data);
                $inventory_adjustrment_details = array();
                if (!empty($_POST['poductIdIn'])) {
                    foreach ($_POST['productIdIn'] as $key => $value) {
                        $inventory_adjustment = array();
                        $inventory_adjustment['product_id'] = $value;
                        $inventory_adjustment['in_qty'] = $_POST['quantityIn'][$key];
                        $inventory_adjustment['out_qty'] = 0;
                        $inventory_adjustment['inv_adjustment_info_id'] = $id;
                        $inventory_adjustment['BranchAutoId'] = $_POST['branchId'][$key];
                        $inventory_adjustment['unit_price'] = $_POST['price'][$key];
                        $inventory_adjustment['insert_date'] = $this->timestamp;
                        $inventory_adjustment['insert_by'] = $this->admin_id;
                        $inventory_adjustment['is_active'] = 'Y';
                        $inventory_adjustment_details[] = $inventory_adjustment;
                    }
                }
                if (!empty($_POST['productIdOut'])) {
                    foreach ($_POST['productIdOut'] as $key => $value) {
                        $inventory_adjustment = array();
                        $inventory_adjustment['product_id'] = $value;
                        $inventory_adjustment['in_qty'] = 0;
                        $inventory_adjustment['out_qty'] = $_POST['quantityOut'][$key];
                        $inventory_adjustment['inv_adjustment_info_id'] = $id;
                        $inventory_adjustment['BranchAutoId'] = $_POST['branchId'][$key];
                        $inventory_adjustment['unit_price'] = $_POST['priceOut'][$key];
                        $inventory_adjustment['insert_date'] = $this->timestamp;
                        $inventory_adjustment['insert_by'] = $this->admin_id;
                        $inventory_adjustment['is_active'] = 'Y';
                        $inventory_adjustment_details[] = $inventory_adjustment;
                    }
                }
                $this->db->update_batch('inventory_adjustment_details', $inventory_adjustment_details);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Invented Adjustment ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/InventoryAdjustment'));
                } else {
                    $msg = 'Invented Adjustment ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/InventoryAdjustment'));
                }
            }
        }
        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['title'] = get_phrase('Inventory Adjustment Update');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Inventory Adjustment List');
        $data['link_page_url'] = $this->project . '/InventoryAdjustmentList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['branch'] = $this->Common_model->branchList();
        $data['mainContent'] = $this->load->view('distributor/inventory/InventoryAdjustment/InventoryAdjustmentEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
}