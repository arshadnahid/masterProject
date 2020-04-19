<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/27/2019
 * Time: 3:20 PM
 */
class OpeningController extends CI_Controller
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
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Purchases_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->invoice_type = 1;
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/cylinderInOut/';
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
    public function inventoryAdjustment()
    {
        /* page navbar details */
        $data['title'] = get_phrase('Inventory Adjustment');
        $data['page_type'] = get_phrase('Configuration');
        $data['link_page_name'] = get_phrase('Add Opening');
        $data['link_page_url'] = $this->project . '/inventoryAdjustmentAdd';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Import');
        $data['second_link_page_url'] = $this->project . '/openigInventoryImport';
        $data['second_link_icon'] = "<i class='ace-icon fa fa-upload'></i>";
        $condition = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
            'is_opening' => '1',
        );
        $data['openingShowHide'] = $this->Inventory_Model->checkOpenigValid($this->dist_id);
        $data['inventoryAdjustmentList'] = $this->Common_model->get_data_list_by_many_columns('purchase_invoice_info', $condition);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryAdjustment', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function inventoryAdjustmentAdd()
    {
        $this->load->helper('purchase_invoice_no_helper');
        if (isPostBack()) {
            /*  echo '<pre>';
              print_r($_POST);
              exit;*/
            $arrayIndex = count($this->input->post('product_id'));
            if ($arrayIndex == 0) {
                exception("Adjustment item can't be empty!!");
                redirect(site_url('inventoryAdjustmentAdd'));
            }
            $finalDate = $this->input->post('purchasesDate');
            $todays = date('Y-m-d', strtotime('-1 day', strtotime($finalDate)));
//dumpVar($_POST);
            $this->db->trans_start();
            $branch_id = 1;
            $invoice_no = create_purchase_invoice_no();
            $purchase_inv['invoice_no'] = $invoice_no;
            /* this invoice no is comming  from purchase_invoice_no_helper */
            $purchase_inv['supplier_invoice_no'] = '';
            $purchase_inv['supplier_id'] = 0;
            $purchase_inv['payment_type'] = 1;
            $purchase_inv['invoice_amount'] = array_sum($this->input->post('price'));
            $purchase_inv['vat_amount'] = 0;
            $purchase_inv['discount_amount'] = 0;
            $purchase_inv['paid_amount'] = array_sum($this->input->post('price'));
            $purchase_inv['is_opening'] = 1;
            $purchase_inv['tran_vehicle_id'] = 0;
            $purchase_inv['transport_charge'] = 0;
            $purchase_inv['loader_charge'] = 0;
            $purchase_inv['loader_emp_id'] = 0;
            $purchase_inv['refference_person_id'] = 0;
            $purchase_inv['company_id'] = $this->dist_id;
            $purchase_inv['dist_id'] = $this->dist_id;
            $purchase_inv['branch_id'] = $branch_id;
            $purchase_inv['invoice_date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $purchase_inv['insert_date'] = $this->timestamp;
            $purchase_inv['insert_by'] = $this->admin_id;
            $purchase_inv['is_active'] = 'Y';
            $purchase_inv['is_delete'] = 'N';
            $invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);
            $productCate = $this->input->post('category_id');
            $newCylinderProductCost = 0;
            $otherProductCost = 0;
            $allStock = array();
            foreach ($productCate as $key => $value) {
                $supplier_advance = 0;
                $supplier_due = 0;
                unset($stock);
                $stock['purchase_invoice_id'] = $invoice_id;
                $stock['product_id'] = $this->input->post('product_id')[$key];
                $stock['is_package '] = $this->input->post('is_package')[$key];
                $stock['is_opening '] = 1;
                $stock['returnable_quantity '] = 0;
                $stock['return_quentity '] = 0;
                $stock['branch_id '] = $branch_id;
                $stock['supplier_due'] = $supplier_due;
                $stock['supplier_advance'] = $supplier_advance;
                $stock['quantity'] = $this->input->post('quantity')[$key];
                $stock['unit_price'] = $this->input->post('rate')[$key];
                if ($value == 1) {
                    $newCylinderProductCost += $this->input->post('price')[$key];
                } else {
                    $otherProductCost += $this->input->post('price')[$key];
                }
                //$newCylinderProductCost += $this->input->post('price')[$key];
                $stock['insert_by'] = $this->admin_id;
                $stock['insert_date'] = $this->timestamp;
                $allStock[] = $stock;
                $category_id = $value;
                $condition = array(
                    'related_id' => $this->input->post('product_id')[$key],
                    'related_id_for' => 1,
                    'is_active' => "Y",
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                if ($category_id == 1) {
                    //Empty Cylinder
                    $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                } elseif ($category_id == 2) {
                    //Refill
                    $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                } else {
                    $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                }
                $opening_balanceTable['debit'] = $this->input->post('quantity')[$key] * $this->input->post('rate')[$key];
                $opening_balanceTable['credit'] = '0.00';
                $opening_balanceTable['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $finalDetailsArray[] = $opening_balanceTable;
            }
            $purchase_details_id = $this->db->insert_batch('purchase_details', $allStock);
            if (!empty($finalDetailsArray)):
                $this->db->insert_batch('opening_balance', $finalDetailsArray);
            endif;
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = "Your data Can not Save into database.";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/inventoryAdjustmentAdd'));
            } else {
                $msg = "Your data successfully inserted into database.";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/inventoryAdjustment'));
            }
        }
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 10,
        );
        /* page navbar details */
        $data['title'] = get_phrase('Inventory Adjustment');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Opening Inventory List');
        $data['link_page_url'] = $this->project . '/inventoryAdjustment';
        $data['link_icon'] = $this->link_icon_list;
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $totalAdjustment = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "AV" . date('y') . date('m') . str_pad(count($totalAdjustment) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryAdjustmentAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function inventoryAdjustmentAdd_back_up()
    {
        if (isPostBack()) {
            $arrayIndex = count($this->input->post('product_id'));
            if ($arrayIndex == 0) {
                exception("Adjustment item can't be empty!!");
                redirect(site_url('inventoryAdjustmentAdd'));
            }
            $finalDate = $this->input->post('purchasesDate');
            $todays = date('Y-m-d', strtotime('-1 day', strtotime($finalDate)));
//dumpVar($_POST);
            $this->db->trans_start();
            $data['dist_id'] = $this->dist_id;
            $data['voucher_no'] = $this->input->post('voucherid');
            $data['date'] = $todays;
            $data['debit'] = array_sum($this->input->post('price'));
            $data['narration'] = $this->input->post('narration');
            $data['form_id'] = 10;
            $data['updated_by'] = $this->admin_id;
            $data['created_at'] = $this->timestamp;
            $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
            $productCate = $this->input->post('category_id');
            $allStock = array();
            $newCylinderProductCost = 0;
            $otherProductCost = 0;
            foreach ($productCate as $key => $value):
                unset($stock);
                unset($stock);
                if ($value == 1) {
                    $newCylinderProductCost += $this->input->post('price')[$key];
                } else {
                    $otherProductCost += $this->input->post('price')[$key];
                }
                $stock['generals_id'] = $generals_id;
                $stock['category_id'] = $value;
                $stock['product_id'] = $this->input->post('product_id')[$key];
                $stock['quantity'] = $this->input->post('quantity')[$key];
                $stock['rate'] = $this->input->post('rate')[$key];
                $stock['price'] = $this->input->post('price')[$key];
                $stock['date'] = $todays;
                $stock['form_id'] = 10;
                $stock['type'] = 'In';
                $stock['openingStatus'] = '1';
                $stock['dist_id'] = $this->dist_id;
                $stock['updated_by'] = $this->admin_id;
                $stock['created_at'] = $this->timestamp;
                $allStock[] = $stock;
            endforeach;
            $this->db->insert_batch('stock', $allStock);
//new cylinder product stock
            if (!empty($newCylinderProductCost) && $newCylinderProductCost > 0):
                $gl_data = array(
                    'generals_id' => $generals_id,
                    'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                    'form_id' => '10',
                    'dist_id' => $this->dist_id,
                    'account' => 173,
                    'debit' => $newCylinderProductCost,
                    'memo' => 'purchases',
                    'updated_by' => $this->admin_id,
                    'created_at' => $this->timestamp
                );
                $this->db->insert('generalledger', $gl_data);
            endif;
//Others product inventory stock.
            if (!empty($otherProductCost) && $otherProductCost > 0):
                $gl_data = array(
                    'generals_id' => $generals_id,
                    'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                    'form_id' => '10',
                    'dist_id' => $this->dist_id,
                    'account' => 52,
                    'debit' => $otherProductCost,
                    'memo' => 'purchases',
                    'updated_by' => $this->admin_id,
                    'created_at' => $this->timestamp
                );
                $this->db->insert('generalledger', $gl_data);
            endif;
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                notification("Your data can't be inserted.Somthing is wrong!!");
                redirect(site_url('inventoryAdjustmentAdd'));
            } else {
                message("Your data successfully inserted into database.");
                redirect(site_url('inventoryAdjustment'));
            }
        }
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 10,
        );
        $data['title'] = 'Inventory Adjustment';
        /* page navbar details */
        $data['title'] = 'Inventory Adjustment';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Opening Inventory List';
        $data['link_page_url'] = 'inventoryAdjustment';
        $data['link_icon'] = $this->link_icon_list;
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $totalAdjustment = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "AV" . date('y') . date('m') . str_pad(count($totalAdjustment) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryAdjustmentAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function viewAdjustment($adjustmentid = null)
    {
        /* page navbar details */
        $data['title'] = get_phrase('Inventory Adjustment');
        $data['page_type'] = get_phrase('Configuration');
        $data['link_page_name'] = get_phrase('List');
        $data['link_page_url'] = $this->project . '/inventoryAdjustment';
        $data['link_icon'] = $this->link_icon_list;
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $adjustmentid);
        $data['stockList'] = $this->Common_model->get_data_list_by_single_column('purchase_details', 'purchase_invoice_id', $adjustmentid);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
// $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['purchasesList']->supplier_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/viewAdjustment', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    /**
     *
     */
    public function openigInventoryImport1()
    {
        if (isPostBack()):
            if (!empty($_FILES['openingInventory']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('openingInventory');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['openingInventory']['tmp_name'];
                $importFile = fopen($file, "r");
                //dumpVar($importFile);
                $this->db->trans_start();
                $BranchAutoId = 1;
                $openingDate = date('Y-m-d', strtotime(date('Y-m-d')));
                $this->load->helper('purchase_invoice_no_helper');
                $invoice_no = create_purchase_invoice_no();
                $purchase_inv['invoice_no'] = $invoice_no;
                /* this invoice no is comming  from purchase_invoice_no_helper */
                $purchase_inv['supplier_invoice_no'] = '';
                $purchase_inv['supplier_id'] = 0;
                $purchase_inv['payment_type'] = 1;
                $purchase_inv['vat_amount'] = 0;
                $purchase_inv['discount_amount'] = 0;
                $purchase_inv['is_opening'] = 1;
                $purchase_inv['tran_vehicle_id'] = 0;
                $purchase_inv['transport_charge'] = 0;
                $purchase_inv['loader_charge'] = 0;
                $purchase_inv['loader_emp_id'] = 0;
                $purchase_inv['refference_person_id'] = 0;
                $purchase_inv['company_id'] = $this->dist_id;
                $purchase_inv['dist_id'] = $this->dist_id;
                $purchase_inv['branch_id'] = $BranchAutoId;
                $purchase_inv['invoice_date'] = $openingDate;
                $purchase_inv['insert_date'] = $this->timestamp;
                $purchase_inv['insert_by'] = $this->admin_id;
                $purchase_inv['is_active'] = 'Y';
                $purchase_inv['is_delete'] = 'N';
                $invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);
                /*ac_accounts_vouchermst*/
                $voucher_no = create_journal_voucher_no();
                $accountingMasterTable['AccouVoucherType_AutoID'] = 7;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = $openingDate;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $invoice_id;
                $accountingMasterTable['Narration'] = 'Purchase Voucher Opening ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $BranchAutoId;
                $accountingMasterTable['supplier_id'] = 0;
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);
                $row = 0;
                $allStock = array();
                $newCylinderProductCost = 0;
                $RefillProductCost = 0;
                $otherProductCost = 0;
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        $productFormatExits = $this->Inventory_Model->checkProductFormate($readRowData, $this->dist_id);
                        unset($stock);
                        if ($productFormatExits === true):
                            //check empty or not
                            if (!empty($readRowData[6]) && !empty($readRowData[7])):
                                //check numeric or string
                                if (is_numeric($readRowData[6]) && is_numeric($readRowData[7])):
                                    unset($stock);
                                    $stock['purchase_invoice_id'] = $invoice_id;
                                    $stock['product_id'] = isset($readRowData[2]) ? $readRowData[2] : '';
                                    $productPackageFormatExits = $this->checkProductPackage($readRowData[3], $this->dist_id);
                                    if ($productPackageFormatExits === true) {
                                        $stock['is_package '] = 1;
                                    } else {
                                        $stock['is_package '] = 0;
                                    }
                                    $stock['is_opening '] = 1;
                                    $stock['branch_id '] = $BranchAutoId;
                                    $stock['returnable_quantity '] = 0;
                                    $stock['return_quentity '] = 0;
                                    $stock['supplier_due'] = 0;
                                    $stock['supplier_advance'] = 0;
                                    $stock['quantity'] = isset($readRowData[6]) ? $readRowData[6] : '';
                                    $stock['unit_price'] = isset($readRowData[7]) ? $readRowData[7] : '';
                                    //$stock['unit_price'] = $readRowData[6] * $readRowData[7];
                                    //$newCylinderProductCost = $newCylinderProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                                    $stock['insert_by'] = $this->admin_id;
                                    $stock['insert_date'] = $this->timestamp;
                                    $allStock[] = $stock;
                                    if ($readRowData[1] == 1) {
                                        $newCylinderProductCost += $readRowData[6] * $readRowData[7];
                                    } elseif ($readRowData[1] == 2) {
                                        //$otherProductCost += $readRowData[6] * $readRowData[7];
                                        $RefillProductCost += $RefillProductCost[6] * $RefillProductCost[7];
                                    } else {
                                        $otherProductCost += $readRowData[6] * $readRowData[7];
                                    }
                                endif;
                            endif;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($allStock)):
                    $this->db->insert_batch('purchase_details', $allStock);
                    /*Inventory Stock New Cylinder Stock=>22*/
                    if (!empty($newCylinderProductCost) && $newCylinderProductCost > 0):
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = '22';
                        $accountingDetailsTable['GR_DEBIT'] = $newCylinderProductCost;
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'New Cylinder Stock';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $BranchAutoId;
                        $accountingDetailsTable['date'] = $openingDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();
                    endif;
                    if (!empty($RefillProductCost) && $RefillProductCost > 0) {
                        /*Inventory stock Refill=>95*/
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = '95';
                        $accountingDetailsTable['GR_DEBIT'] = $RefillProductCost;
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'Refill  Stock';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $BranchAutoId;
                        $accountingDetailsTable['date'] = $openingDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();
                    }
                    //Others product inventory stock.
                    if (!empty($otherProductCost) && $otherProductCost > 0):
                        /*Inventory stock=>20*/
                        $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                        $accountingDetailsTable['TypeID'] = '1';//Dr
                        $accountingDetailsTable['CHILD_ID'] = '20';
                        $accountingDetailsTable['GR_DEBIT'] = $otherProductCost;
                        $accountingDetailsTable['GR_CREDIT'] = '0.00';
                        $accountingDetailsTable['Reference'] = 'Inventory Stock';
                        $accountingDetailsTable['IsActive'] = 1;
                        $accountingDetailsTable['Created_By'] = $this->admin_id;
                        $accountingDetailsTable['Created_Date'] = $this->timestamp;
                        $accountingDetailsTable['BranchAutoId'] = $BranchAutoId;
                        $accountingDetailsTable['date'] = $openingDate;
                        $finalDetailsArray[] = $accountingDetailsTable;
                        $accountingDetailsTable = array();
                    endif;
                endif;
                $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                $geneAmount['debit'] = $invoice_amount = $newCylinderProductCost + $RefillProductCost + $otherProductCost;
                $purchase_invUpdate['invoice_amount'] = $invoice_amount;
                $purchase_invUpdate['paid_amount'] = $invoice_amount;
                $this->Common_model->update_data('purchase_invoice_info', $purchase_invUpdate, 'purchase_invoice_id', $invoice_id);
                /*$geneAmount['invoice_id']=$invoice_id;
                $this->Common_model->update_data('generals', $geneAmount, 'generals_id', $generals_id);*/
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your csv file not inserted.please check csv file properly.";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/inventoryAdjustmentAdd'));
                } else {
                    $msg = "Your csv file successfully inserted into database.";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/inventoryAdjustmentAdd'));
                }
            endif;
        endif;
        /* page navbar details */
        $data['title'] = 'Inventory Adjustment';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'List';
        $data['link_page_url'] = $this->project . '/inventoryAdjustment';
        $data['link_icon'] = $this->link_icon_list;
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function openigInventoryImport()
    {
        if (isPostBack()):

            $this->db->trans_start();
                if (!empty($_FILES['openingInventory']['name'])){//supplier list import operation start this block
                    $config['upload_path'] = './uploads/import/setup/';
                    $config['allowed_types'] = 'xl|txt|csv|mdb';
                    $config['file_name'] = $this->project . 'InventoryOpenig' . date("Y-m-d");
                    $this->load->library('upload');
                    $this->upload->initialize($config);
                    $upload = $this->upload->do_upload('openingInventory');
                    $data = $this->upload->data();
                    $this->load->helper('file');
                    $file = $_FILES['openingInventory']['tmp_name'];
                    $importFile = fopen($file, "r");
                    //dumpVar($importFile);

                    $BranchAutoId = 1;
                    $openingDate = date('Y-m-d', strtotime($this->input->post('inventory_opening_date')));
                    $this->load->helper('purchase_invoice_no_helper');
                    $invoice_no = create_purchase_invoice_no();
                    $purchase_inv['invoice_no'] = $invoice_no;
                    /* this invoice no is comming  from purchase_invoice_no_helper */
                    $purchase_inv['supplier_invoice_no'] = '';
                    $purchase_inv['supplier_id'] = 0;
                    $purchase_inv['payment_type'] = 1;
                    $purchase_inv['vat_amount'] = 0;
                    $purchase_inv['discount_amount'] = 0;
                    $purchase_inv['is_opening'] = 1;
                    $purchase_inv['tran_vehicle_id'] = 0;
                    $purchase_inv['transport_charge'] = 0;
                    $purchase_inv['loader_charge'] = 0;
                    $purchase_inv['loader_emp_id'] = 0;
                    $purchase_inv['refference_person_id'] = 0;
                    $purchase_inv['company_id'] = $this->dist_id;
                    $purchase_inv['dist_id'] = $this->dist_id;
                    $purchase_inv['branch_id'] = $BranchAutoId;
                    $purchase_inv['invoice_date'] = $openingDate;
                    $purchase_inv['insert_date'] = $this->timestamp;
                    $purchase_inv['insert_by'] = $this->admin_id;
                    $purchase_inv['is_active'] = 'Y';
                    $purchase_inv['is_delete'] = 'N';
                    $invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);
                    /*ac_accounts_vouchermst*/
                    $row = 0;
                    $allStock = array();
                    $newCylinderProductCost = 0;
                    $RefillProductCost = 0;
                    $otherProductCost = 0;
                    $finalDetailsArray = array();
                    while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                        $emptyCylindet = array();
                        $refillCylindet = array();
                        $otherProduct = array();
                        if ($row != 0):
                            $productFormatExits = $this->Inventory_Model->checkProductFormate($readRowData, $this->dist_id);
                            unset($stock);
                            if ($productFormatExits === true):
                                //check empty or not
                                if (!empty($readRowData[6]) && !empty($readRowData[7])):
                                    //check numeric or string
                                    if (is_numeric($readRowData[6]) && is_numeric($readRowData[7])):
                                        unset($stock);
                                        $opening_balanceTable = array();
                                        $product_id = isset($readRowData[2]) ? $readRowData[2] : '';
                                        $stock['purchase_invoice_id'] = $invoice_id;
                                        $stock['product_id'] = $product_id;
                                        $productPackageFormatExits = $this->checkProductPackage($readRowData[3], $this->dist_id);
                                        if ($productPackageFormatExits === true) {
                                            $stock['is_package '] = 1;
                                        } else {
                                            $stock['is_package '] = 0;
                                        }
                                        $stock['is_opening '] = 1;
                                        $stock['branch_id '] = $BranchAutoId;
                                        $stock['returnable_quantity '] = 0;
                                        $stock['return_quentity '] = 0;
                                        $stock['supplier_due'] = 0;
                                        $stock['supplier_advance'] = 0;
                                        $stock['quantity'] = isset($readRowData[6]) ? $readRowData[6] : '';
                                        $stock['unit_price'] = isset($readRowData[7]) ? $readRowData[7] : '';
                                        //$stock['unit_price'] = $readRowData[6] * $readRowData[7];
                                        //$newCylinderProductCost = $newCylinderProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                                        $stock['insert_by'] = $this->admin_id;
                                        $stock['insert_date'] = $this->timestamp;
                                        $allStock[] = $stock;
                                        if ($readRowData[1] == 1) {
                                            $newCylinderProductCost += $readRowData[6] * $readRowData[7];
                                        } elseif ($readRowData[1] == 2) {
                                            //$otherProductCost += $readRowData[6] * $readRowData[7];
                                            $RefillProductCost += $RefillProductCost[6] * $RefillProductCost[7];
                                        } else {
                                            $otherProductCost += $readRowData[6] * $readRowData[7];
                                        }
                                        $category_id = $readRowData[1];
                                        $condition = array(
                                            'related_id' => $product_id,
                                            'related_id_for' => 1,
                                            'is_active' => "Y",
                                        );
                                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                                        if ($category_id == 1) {
                                            //Empty Cylinder
                                            $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                                        } elseif ($category_id == 2) {
                                            //Refill
                                            $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                                        } else {
                                            $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                                        }
                                        $opening_balanceTable['debit'] = $readRowData[6] * $readRowData[7];
                                        $opening_balanceTable['credit'] = '0.00';
                                        $opening_balanceTable['date'] = $openingDate;
                                        $finalDetailsArray[] = $opening_balanceTable;
                                    endif;
                                endif;
                            endif;
                        endif;
                        $row++;
                    }
                }
                if (!empty($_FILES['openingInventoryExcl']['name'])) {


                    $this->load->library('excel');
                    $this->load->helper('file');
                    $file = $_FILES['openingInventoryExcl']['tmp_name'];
                    $object = PHPExcel_IOFactory::load($file);
                    $BranchAutoId = 1;
                    $openingDate = date('Y-m-d', strtotime($this->input->post('inventory_opening_date')));
                    $this->load->helper('purchase_invoice_no_helper');
                    $invoice_no = create_purchase_invoice_no();
                    $purchase_inv['invoice_no'] = $invoice_no;
                    /* this invoice no is comming  from purchase_invoice_no_helper */
                    $purchase_inv['supplier_invoice_no'] = '';
                    $purchase_inv['supplier_id'] = 0;
                    $purchase_inv['payment_type'] = 1;
                    $purchase_inv['vat_amount'] = 0;
                    $purchase_inv['discount_amount'] = 0;
                    $purchase_inv['is_opening'] = 1;
                    $purchase_inv['tran_vehicle_id'] = 0;
                    $purchase_inv['transport_charge'] = 0;
                    $purchase_inv['loader_charge'] = 0;
                    $purchase_inv['loader_emp_id'] = 0;
                    $purchase_inv['refference_person_id'] = 0;
                    $purchase_inv['company_id'] = $this->dist_id;
                    $purchase_inv['dist_id'] = $this->dist_id;
                    $purchase_inv['branch_id'] = $BranchAutoId;
                    $purchase_inv['invoice_date'] = $openingDate;
                    $purchase_inv['insert_date'] = $this->timestamp;
                    $purchase_inv['insert_by'] = $this->admin_id;
                    $purchase_inv['is_active'] = 'Y';
                    $purchase_inv['is_delete'] = 'N';
                    $invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);


                    foreach ($object->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $this->db->select("*");
                            $this->db->from("product");
                            $this->db->where('category_id', $worksheet->getCellByColumnAndRow(1, $row)->getValue());
                            $this->db->where('product_id', $worksheet->getCellByColumnAndRow(2, $row)->getValue());
                            $exits = $this->db->get()->row();
                            if (!empty($exits)):
                                $Quantity = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                                $unitPrice = $worksheet->getCellByColumnAndRow(7, $row)->getValue();


                                if (!empty($Quantity) && !empty($unitPrice)):
                                    //check numeric or string
                                    if (is_numeric($Quantity) && is_numeric($unitPrice)):
                                        unset($stock);
                                        $opening_balanceTable = array();

                                        $category_id = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                                        $product_id = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                        $product_id = isset($product_id) ? $product_id : '';
                                        $stock['purchase_invoice_id'] = $invoice_id;
                                        $stock['product_id'] = $product_id;
                                        $productPackageFormatExits = $this->checkProductPackage($worksheet->getCellByColumnAndRow(3, $row)->getValue(), $this->dist_id);
                                        if ($productPackageFormatExits === true) {
                                            $stock['is_package '] = 1;
                                        } else {
                                            $stock['is_package '] = 0;
                                        }
                                        $stock['is_opening '] = 1;
                                        $stock['branch_id '] = $BranchAutoId;
                                        $stock['returnable_quantity '] = 0;
                                        $stock['return_quentity '] = 0;
                                        $stock['supplier_due'] = 0;
                                        $stock['supplier_advance'] = 0;
                                        $stock['quantity'] = isset($Quantity) ? $Quantity : '';
                                        $stock['unit_price'] = isset($unitPrice) ? $unitPrice : '';
                                        //$stock['unit_price'] = $readRowData[6] * $readRowData[7];
                                        //$newCylinderProductCost = $newCylinderProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                                        $stock['insert_by'] = $this->admin_id;
                                        $stock['insert_date'] = $this->timestamp;
                                        $allStock[] = $stock;
                                        if ($category_id == 1) {
                                            $newCylinderProductCost += $Quantity * $unitPrice;
                                        } elseif ($category_id == 2) {
                                            //$otherProductCost += $readRowData[6] * $readRowData[7];
                                            $RefillProductCost += $Quantity * $unitPrice;
                                        } else {
                                            $otherProductCost += $Quantity * $unitPrice;
                                        }

                                        $condition = array(
                                            'related_id' => $product_id,
                                            'related_id_for' => 1,
                                            'is_active' => "Y",
                                        );
                                        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                                        if ($category_id == 1) {
                                            //Empty Cylinder
                                            $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                                        } elseif ($category_id == 2) {
                                            //Refill
                                            $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                                        } else {
                                            $opening_balanceTable['account'] = $ac_account_ledger_coa_info->id;//'95';
                                        }
                                        $opening_balanceTable['debit'] = $Quantity * $unitPrice;
                                        $opening_balanceTable['credit'] = '0.00';
                                        $opening_balanceTable['date'] = $openingDate;
                                        $finalDetailsArray[] = $opening_balanceTable;
                                    endif;
                                endif;
                            endif;
                        }
                    }
                }
                if (!empty($allStock)):
                    $this->db->insert_batch('purchase_details', $allStock);
                    /*Inventory Stock New Cylinder Stock=>22*/
                    if (!empty($finalDetailsArray)):
                        $this->db->insert_batch('opening_balance', $finalDetailsArray);
                    endif;
                endif;
                $invoice_amount = $newCylinderProductCost + $RefillProductCost + $otherProductCost;
                $purchase_invUpdate['invoice_amount'] = $invoice_amount;
                $purchase_invUpdate['paid_amount'] = $invoice_amount;
                $this->Common_model->update_data('purchase_invoice_info', $purchase_invUpdate, 'purchase_invoice_id', $invoice_id);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = "Your csv file not inserted.please check csv file properly.";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/inventoryAdjustmentAdd'));
            } else {
                $msg = "Your csv file successfully inserted into database.";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/inventoryAdjustmentAdd'));
            }

        endif;
        /* page navbar details */
        $data['title'] = 'Inventory Adjustment';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'List';
        $data['link_page_url'] = $this->project . '/inventoryAdjustment';
        $data['link_icon'] = $this->link_icon_list;
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function openigInventoryImport_BK()
    {
        if (isPostBack()):
            if (!empty($_FILES['openingInventory']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = $this->project . 'InventoryOpenig' . date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('openingInventory');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['openingInventory']['tmp_name'];
                $importFile = fopen($file, "r");
                //dumpVar($importFile);
                $this->db->trans_start();
                $BranchAutoId = 1;
                $openingDate = date('Y-m-d', strtotime($this->input->post('inventory_opening_date')));
                $this->load->helper('purchase_invoice_no_helper');
                $invoice_no = create_purchase_invoice_no();
                $purchase_inv['invoice_no'] = $invoice_no;
                /* this invoice no is comming  from purchase_invoice_no_helper */
                $purchase_inv['supplier_invoice_no'] = '';
                $purchase_inv['supplier_id'] = 0;
                $purchase_inv['payment_type'] = 1;
                $purchase_inv['vat_amount'] = 0;
                $purchase_inv['discount_amount'] = 0;
                $purchase_inv['is_opening'] = 1;
                $purchase_inv['tran_vehicle_id'] = 0;
                $purchase_inv['transport_charge'] = 0;
                $purchase_inv['loader_charge'] = 0;
                $purchase_inv['loader_emp_id'] = 0;
                $purchase_inv['refference_person_id'] = 0;
                $purchase_inv['company_id'] = $this->dist_id;
                $purchase_inv['dist_id'] = $this->dist_id;
                $purchase_inv['branch_id'] = $BranchAutoId;
                $purchase_inv['invoice_date'] = $openingDate;
                $purchase_inv['insert_date'] = $this->timestamp;
                $purchase_inv['insert_by'] = $this->admin_id;
                $purchase_inv['is_active'] = 'Y';
                $purchase_inv['is_delete'] = 'N';
                $invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);
                /*ac_accounts_vouchermst*/
                $voucher_no = create_journal_voucher_no();
                $accountingMasterTable['AccouVoucherType_AutoID'] = 7;
                $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
                $accountingMasterTable['Accounts_Voucher_Date'] = $openingDate;
                $accountingMasterTable['BackReferenceInvoiceNo'] = $invoice_no;
                $accountingMasterTable['BackReferenceInvoiceID'] = $invoice_id;
                $accountingMasterTable['Narration'] = 'Purchase Voucher Opening ';
                $accountingMasterTable['CompanyId'] = $this->dist_id;
                $accountingMasterTable['BranchAutoId'] = $BranchAutoId;
                $accountingMasterTable['supplier_id'] = 0;
                $accountingMasterTable['IsActive'] = 1;
                $accountingMasterTable['Created_By'] = $this->admin_id;
                $accountingMasterTable['Created_Date'] = $this->timestamp;
                $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);
                $row = 0;
                $allStock = array();
                $newCylinderProductCost = 0;
                $RefillProductCost = 0;
                $otherProductCost = 0;
                $finalDetailsArray = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    $emptyCylindet = array();
                    $refillCylindet = array();
                    $otherProduct = array();
                    if ($row != 0):
                        $productFormatExits = $this->Inventory_Model->checkProductFormate($readRowData, $this->dist_id);
                        unset($stock);
                        if ($productFormatExits === true):
                            //check empty or not
                            if (!empty($readRowData[6]) && !empty($readRowData[7])):
                                //check numeric or string
                                if (is_numeric($readRowData[6]) && is_numeric($readRowData[7])):
                                    unset($stock);
                                    $accountingDetailsTable = array();
                                    $product_id = isset($readRowData[2]) ? $readRowData[2] : '';
                                    $stock['purchase_invoice_id'] = $invoice_id;
                                    $stock['product_id'] = $product_id;
                                    $productPackageFormatExits = $this->checkProductPackage($readRowData[3], $this->dist_id);
                                    if ($productPackageFormatExits === true) {
                                        $stock['is_package '] = 1;
                                    } else {
                                        $stock['is_package '] = 0;
                                    }
                                    $stock['is_opening '] = 1;
                                    $stock['branch_id '] = $BranchAutoId;
                                    $stock['returnable_quantity '] = 0;
                                    $stock['return_quentity '] = 0;
                                    $stock['supplier_due'] = 0;
                                    $stock['supplier_advance'] = 0;
                                    $stock['quantity'] = isset($readRowData[6]) ? $readRowData[6] : '';
                                    $stock['unit_price'] = isset($readRowData[7]) ? $readRowData[7] : '';
                                    //$stock['unit_price'] = $readRowData[6] * $readRowData[7];
                                    //$newCylinderProductCost = $newCylinderProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                                    $stock['insert_by'] = $this->admin_id;
                                    $stock['insert_date'] = $this->timestamp;
                                    $allStock[] = $stock;
                                    if ($readRowData[1] == 1) {
                                        $newCylinderProductCost += $readRowData[6] * $readRowData[7];
                                    } elseif ($readRowData[1] == 2) {
                                        //$otherProductCost += $readRowData[6] * $readRowData[7];
                                        $RefillProductCost += $RefillProductCost[6] * $RefillProductCost[7];
                                    } else {
                                        $otherProductCost += $readRowData[6] * $readRowData[7];
                                    }
                                    $category_id = $readRowData[1];
                                    $condition = array(
                                        'related_id' => $product_id,
                                        'related_id_for' => 1,
                                        'is_active' => "Y",
                                    );
                                    $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                                    if ($category_id == 1) {
                                        //Empty Cylinder
                                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//'95';
                                        $accountingDetailsTable['Reference'] = 'Empty  Cylinder Opening';
                                    } elseif ($category_id == 2) {
                                        //Refill
                                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//'95';
                                        $accountingDetailsTable['Reference'] = 'Refill   Opening';
                                    } else {
                                        $accountingDetailsTable['CHILD_ID'] = $ac_account_ledger_coa_info->id;//'95';
                                        $accountingDetailsTable['Reference'] = 'Other Product Opening';
                                    }
                                    $accountingDetailsTable['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
                                    $accountingDetailsTable['TypeID'] = '1';//Dr
                                    $accountingDetailsTable['GR_DEBIT'] = $readRowData[6] * $readRowData[7];
                                    $accountingDetailsTable['GR_CREDIT'] = '0.00';
                                    $accountingDetailsTable['IsActive'] = 1;
                                    $accountingDetailsTable['Created_By'] = $this->admin_id;
                                    $accountingDetailsTable['Created_Date'] = $this->timestamp;
                                    $accountingDetailsTable['BranchAutoId'] = $BranchAutoId;
                                    $accountingDetailsTable['date'] = $openingDate;
                                    $finalDetailsArray[] = $accountingDetailsTable;
                                endif;
                            endif;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($allStock)):
                    $this->db->insert_batch('purchase_details', $allStock);
                    /*Inventory Stock New Cylinder Stock=>22*/
                    if (!empty($finalDetailsArray)):
                        $this->db->insert_batch('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                    endif;
                endif;
                $this->Common_model->insert_batch_save('ac_tb_accounts_voucherdtl', $finalDetailsArray);
                $geneAmount['debit'] = $invoice_amount = $newCylinderProductCost + $RefillProductCost + $otherProductCost;
                $purchase_invUpdate['invoice_amount'] = $invoice_amount;
                $purchase_invUpdate['paid_amount'] = $invoice_amount;
                $this->Common_model->update_data('purchase_invoice_info', $purchase_invUpdate, 'purchase_invoice_id', $invoice_id);
                /*$geneAmount['invoice_id']=$invoice_id;
                $this->Common_model->update_data('generals', $geneAmount, 'generals_id', $generals_id);*/
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your csv file not inserted.please check csv file properly.";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/inventoryAdjustmentAdd'));
                } else {
                    $msg = "Your csv file successfully inserted into database.";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/inventoryAdjustmentAdd'));
                }
            endif;
        endif;
        /* page navbar details */
        $data['title'] = 'Inventory Adjustment';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'List';
        $data['link_page_url'] = $this->project . '/inventoryAdjustment';
        $data['link_icon'] = $this->link_icon_list;
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function checkProductPackage($productFormate, $disttId)
    {
        $this->db->select("*");
        $this->db->from("package");
        $this->db->where('package_code', $productFormate);
        $this->db->group_start();
        $this->db->where('dist_id', $disttId);
        $this->db->or_where('dist_id', 1);
        $this->db->group_end();
        $exits = $this->db->get()->row();
        if (!empty($exits)):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    public function openigInventoryImport_back_up()
    {
        if (isPostBack()):
            if (!empty($_FILES['openingInventory']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('openingInventory');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['openingInventory']['tmp_name'];
                $importFile = fopen($file, "r");
                //dumpVar($importFile);
                $this->db->trans_start();
                $general['dist_id'] = $this->dist_id;
                $general['voucher_no'] = 'OP' . mt_rand(100000, 999999);
                $general['date'] = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                $general['narration'] = 'opening Inventory Imported';
                $general['form_id'] = 10;
                $general['updated_by'] = $this->admin_id;
                $general['created_at'] = $this->timestamp;
                $generals_id = $this->Common_model->insert_data('generals', $general);
                $row = 0;
                $storeData = array();
                $allStock = array();
                $newCylinderProductCost = 0;
                $otherProductCost = 0;
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    if ($row != 0):
                        $productFormatExits = $this->Inventory_Model->checkProductFormate($readRowData, $this->dist_id);
                        unset($stock);
                        if ($productFormatExits === true):
                            //check empty or not
                            if (!empty($readRowData[6]) && !empty($readRowData[7])):
                                //check numeric or string
                                if (is_numeric($readRowData[6]) && is_numeric($readRowData[7])):
                                    if ($readRowData[1] == 1) {
                                        $newCylinderProductCost += $readRowData[6] * $readRowData[7];
                                    } else {
                                        $otherProductCost += $readRowData[6] * $readRowData[7];
                                    }
                                    $stock['generals_id'] = $generals_id;
                                    $stock['category_id'] = isset($readRowData[1]) ? $readRowData[1] : '';
                                    $stock['product_id'] = isset($readRowData[2]) ? $readRowData[2] : '';
                                    $stock['quantity'] = isset($readRowData[6]) ? $readRowData[6] : '';
                                    $stock['rate'] = isset($readRowData[7]) ? $readRowData[7] : '';
                                    $stock['price'] = $readRowData[6] * $readRowData[7];
                                    $stock['date'] = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                                    $stock['form_id'] = 10;
                                    $stock['type'] = 'In';
                                    $stock['openingStatus'] = '1';
                                    $stock['dist_id'] = $this->dist_id;
                                    $stock['updated_by'] = $this->admin_id;
                                    $stock['created_at'] = $this->timestamp;
                                    $allStock[] = $stock;
                                endif;
                            endif;
                        endif;
                    endif;
                    $row++;
                }
                if (!empty($allStock)):
                    $geneAmount['debit'] = $newCylinderProductCost + $otherProductCost;
                    $this->Common_model->update_data('generals', $geneAmount, 'generals_id', $generals_id);
                    $this->db->insert_batch('stock', $allStock);
                    if (!empty($newCylinderProductCost) && $newCylinderProductCost > 0):
                        $gl_data = array(
                            'generals_id' => $generals_id,
                            'date' => date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))),
                            'form_id' => '10',
                            'dist_id' => $this->dist_id,
                            'account' => 173,
                            'debit' => $newCylinderProductCost,
                            'memo' => 'Opening Inventory',
                            'updated_by' => $this->admin_id,
                            'created_at' => $this->timestamp
                        );
                        $this->db->insert('generalledger', $gl_data);
                    endif;
                    //Others product inventory stock.
                    if (!empty($otherProductCost) && $otherProductCost > 0):
                        $gl_data = array(
                            'generals_id' => $generals_id,
                            'date' => date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))),
                            'form_id' => '10',
                            'dist_id' => $this->dist_id,
                            'account' => 52,
                            'debit' => $otherProductCost,
                            'memo' => 'Opening Inventory',
                            'updated_by' => $this->admin_id,
                            'created_at' => $this->timestamp
                        );
                        $this->db->insert('generalledger', $gl_data);
                    endif;
                endif;
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your csv file not inserted.please check csv file properly.");
                    redirect(site_url('inventoryAdjustmentAdd'));
                } else {
                    message("Your csv file successfully inserted into database.");
                    redirect(site_url('inventoryAdjustment'));
                }
            endif;
        endif;
        $data['title'] = 'Opening Inventory Import';
        $data['mainContent'] = $this->load->view('distributor/inventory/setup/inventoryImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function updateAndSaveopening_balance()
    {
        $this->db->trans_start();
        $id = $this->input->post('id');
        $drAmount = $this->input->post('drAmount');
        $crAmount = $this->input->post('crAmount');
        $Condition = array(
            'account' => $id
        );
        $dataSaveUpdate['debit'] = $drAmount;
        $dataSaveUpdate['credit'] = $crAmount;
        $dataSaveUpdate['account'] = $id;
        $this->Common_model->save_and_check('opening_balance', $dataSaveUpdate, $Condition);
        $condition = array(
            'id' => $id,
        );
        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
        if ($ac_account_ledger_coa_info->related_id_for == 2 || $ac_account_ledger_coa_info->related_id_for == 1) {
            if ($ac_account_ledger_coa_info->related_id_for == 2) {
                $ledger_type = 2;
            } else if ($ac_account_ledger_coa_info->related_id_for == 3) {
                $ledger_type = 1;
            }
            $Condition2 = array(
                'client_vendor_id' => $ac_account_ledger_coa_info->related_id,
                'ledger_type' => $ledger_type,
            );
            if ($drAmount > 0) {
                $amount = $drAmount;
            } else {
                $amount = $crAmount;
            }
            $dataSaveUpdate2['amount'] = $amount;
            $dataSaveUpdate2['dr'] = $drAmount;
            $dataSaveUpdate2['cr'] = $crAmount;
            $dataSaveUpdate2['ledger_type'] = $ledger_type;
            $dataSaveUpdate2['client_vendor_id'] = $ac_account_ledger_coa_info->related_id;
            $this->Common_model->save_and_check('client_vendor_ledger', $dataSaveUpdate2, $Condition2);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo 0;
        } else {
            echo 1;
        }
    }
    function save_openning_balance_to_main_table()
    {



        $this->db->trans_start();
        $allResult = $this->Common_model->get_data_list('opening_balance');


        $Date = date('Y-m-d', strtotime($this->input->post('date')));
        $voucher_no = create_journal_voucher_no();
        $AccouVoucherType_AutoID = 0;
        $accountingMasterTable['AccouVoucherType_AutoID'] = $AccouVoucherType_AutoID;
        $accountingMasterTable['Accounts_Voucher_No'] = $voucher_no;
        $accountingMasterTable['Accounts_Voucher_Date'] = $Date;
        $accountingMasterTable['BackReferenceInvoiceNo'] = '';
        $accountingMasterTable['BackReferenceInvoiceID'] = '';
        $accountingMasterTable['Narration'] = 'Opening Balance  Voucher ';
        $accountingMasterTable['CompanyId'] = $this->dist_id;
        $accountingMasterTable['BranchAutoId'] = 1;
        $accountingMasterTable['customer_id'] = 0;
        $accountingMasterTable['IsActive'] = 1;
        $accountingMasterTable['for'] = 2;
        $accountingMasterTable['Created_By'] = $this->admin_id;
        $accountingMasterTable['Created_Date'] = $this->timestamp;
        $accountingVoucherId = $this->Common_model->save_and_check('ac_accounts_vouchermst', $accountingMasterTable);

        foreach ($allResult as $key => $value) {
            $accountingDetailsTableTransportationAmount=array();


            if($value->debit >0){

                $Type=1;
            }
            if($value->credit >0){

                $Type=2;
            }
            $accountingDetailsTableTransportationAmount['Accounts_VoucherMst_AutoID'] = $accountingVoucherId;
            $accountingDetailsTableTransportationAmount['TypeID'] = $Type;
            $accountingDetailsTableTransportationAmount['CHILD_ID'] = $value->account;//'42';
            $accountingDetailsTableTransportationAmount['GR_DEBIT'] = $value->debit;
            $accountingDetailsTableTransportationAmount['GR_CREDIT'] = $value->credit;
            $accountingDetailsTableTransportationAmount['Reference'] = 'Oening Balance';
            $accountingDetailsTableTransportationAmount['IsActive'] = 1;
            $accountingDetailsTableTransportationAmount['Created_By'] = $this->admin_id;
            $accountingDetailsTableTransportationAmount['Created_Date'] = $this->timestamp;
            $accountingDetailsTableTransportationAmount['BranchAutoId'] = 1;
            $accountingDetailsTableTransportationAmount['date'] = $Date;
            $this->db->insert('ac_tb_accounts_voucherdtl', $accountingDetailsTableTransportationAmount);
            //$finalDetailsArray[] = $accountingDetailsTableTransportationAmount;
        }

     //   $this->db->empty_table('opening_balance');



        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = $this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/openingBalance'));
        else:
            $msg = $this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/balanceSheet'));
        endif;
    }
}