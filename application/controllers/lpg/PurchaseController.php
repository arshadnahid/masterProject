<?php

/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 4/1/2019
 * Time: 12:15 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseController extends CI_Controller
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
    public $link_icon_edit;
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
        $this->link_icon_add = "<i class='fa fa-plus'></i>";
        $this->link_icon_list = "<i class='fa fa-list'></i>";
        $this->link_icon_edit = "<i class='fa fa-edit'></i>";

        $this->lang->load('content', 'english');

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function purchases_add($confirId = null)
    {
        $this->load->helper('purchase_invoice_no_helper');
        if (isPostBack()) {
            /*echo "<pre>";
            print_r($_POST);
            exit;*/

//form validation rules
            $this->form_validation->set_rules('supplierID', 'Supplier ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher ID', 'required');
            $this->form_validation->set_rules('purchasesDate', 'Purchases Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Date', 'required');
            $this->form_validation->set_rules('slNo[]', 'Payment Date', 'required');

            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/purchases_add'));
            } else {
                $paymentType = $this->input->post('paymentType');
                $accountCr = $this->input->post('accountCr');
                $arrayIndex = count($this->input->post('product_id'));
                $supplier_id = $this->input->post('supplierID');
                $accountCr = $this->input->post('accountCrPartial');
                $thisAllotment = $this->input->post('thisAllotment');


                if ($paymentType == 4) {
                    if (empty($accountCr) || empty($thisAllotment)) {
                        $msg = 'Purchase  ' . ' ' . $this->config->item("save_error_message");
                        $this->session->set_flashdata('error', $msg);
                        redirect(site_url($this->project . '/purchases_add'));
                    }
                }


                $this->db->trans_start();


                $ProductCost = 0;
                $invoice_no = create_purchase_invoice_no();
                $purchase_inv['invoice_no'] = $invoice_no;
                /* this invoice no is comming  from purchase_invoice_no_helper */
                $purchase_inv['supplier_invoice_no'] = $this->input->post('userInvoiceId');
                $purchase_inv['supplier_id'] = $this->input->post('supplierID');
                $purchase_inv['payment_type'] = $paymentType;
                $purchase_inv['invoice_amount'] = array_sum($this->input->post('price'));
                $purchase_inv['vat_amount'] = 0;
                $purchase_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $purchase_inv['paid_amount'] = $this->input->post('thisAllotment') != '' ? $this->input->post('thisAllotment') : 0;

                $purchase_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $purchase_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $purchase_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $purchase_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                $purchase_inv['refference_person'] = $this->input->post('reference');
                $purchase_inv['company_id'] = $this->dist_id;
                $purchase_inv['dist_id'] = $this->dist_id;
                $purchase_inv['branch_id'] = 0;
                $purchase_inv['due_date'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
                $purchase_inv['invoice_date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $purchase_inv['insert_date'] = $this->timestamp;
                $purchase_inv['insert_by'] = $this->admin_id;
                $purchase_inv['is_active'] = 'Y';
                $purchase_inv['is_delete'] = 'N';
                if ($paymentType == 3) {
                    $purchase_inv['bank_id'] = $this->input->post('bankName');
                    $purchase_inv['bank_branch_id'] = $this->input->post('branchName');
                    $purchase_inv['check_date'] = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $purchase_inv['check_no'] = $this->input->post('checkNo');
                }


                $this->invoice_id = $this->Common_model->insert_data('purchase_invoice_info', $purchase_inv);
                $productCate = $this->input->post('category_id');
                $allStock = array();
                foreach ($_POST['slNo'] as $key => $value) {
                    $supplier_advance = 0;
                    $supplier_due = 0;
                    unset($stock);
                    $returnable_quantity = $_POST['add_returnAble'][$value] != '' ? $_POST['add_returnAble'][$value] : 0;
                    $return_quentity = empty($_POST['returnQuentity_' . $value]) ? 0 : array_sum($_POST['returnQuentity_' . $value]);
                    $stock['purchase_invoice_id'] = $this->invoice_id;
                    $stock['product_id'] = $_POST['product_id_' . $value];
                    $stock['is_package '] = $_POST['is_package_' . $value];
                    $stock['returnable_quantity '] = $returnable_quantity;
                    $stock['return_quentity '] = $return_quentity;
                    $stock['supplier_due'] = $supplier_due;
                    $stock['supplier_advance'] = $supplier_advance;
                    $stock['quantity'] = $_POST['quantity_' . $value];
                    $stock['unit_price'] = $_POST['rate_' . $value];
                    $ProductCost = $ProductCost + ($_POST['rate_' . $value] * $_POST['quantity_' . $value]);
                    $stock['insert_by'] = $this->admin_id;
                    $stock['insert_date'] = $this->timestamp;
                    $allStock[] = $stock;


                }
                /*echo $ProductCost;
                exit;*/
                $this->db->insert_batch('purchase_details', $allStock);


                if ($paymentType == 2) {
                    //if payment type credit than transaction here
                    $generals_id = $this->creditTransactionInsert($ProductCost, $invoice_no, $this->invoice_id);
                } elseif ($paymentType == 4) {
                    //if payment type partial than calculate from here
                    $generals_id = $this->partialTransactionInsert($ProductCost, $invoice_no, $this->invoice_id);
                } else {
                    $generals_id = $this->chequeTransactionInsert($ProductCost, $invoice_no, $this->invoice_id);
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/purchases_add'));

                } else {

                    $msg = 'Purchase Invoice ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/viewPurchases/' . $this->invoice_id));

                }
            }
        }
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);
        $data['title'] = 'Purchases Add';
        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $costCondition = array(
            'dist_id' => $this->dist_id,
            'parentId' => 62,
        );
        $data['costList'] = $this->Common_model->get_data_list_by_many_columns('generaldata', $costCondition);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['packageList'] = $this->Common_model->getPublicPackageList($this->dist_id);

        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        $data['cylinderProduct_jason'] = json_encode($this->Common_model->getPublicProduct($this->dist_id, 1));
        //echo "<pre>";
        //print_r($data['cylinderProduct_jason']);exit;
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);

        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);


        /* page navbar details */
        $data['title'] = get_phrase('New Purchase Invoice');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Invoice List');
        $data['link_page_url'] = $this->project . '/purchases_list';
        $data['link_icon'] = $this->link_icon_list;
        /* page navbar details */
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);

        $data['voucherID'] = create_purchase_invoice_no();
        /* this invoice no is comming  from purchase_invoice_no_helper */
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchasesWithPos', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function package_product_list()
    {
        $package_id = $this->input->post('package_id');


        $packageProductDetails = $this->Common_model->get_package_product($package_id);
        echo json_encode($packageProductDetails);
        //echo "<pre>";
        //print_r($productDetails);
        // if (!empty($productDetails)):
        //    echo $productDetails->purchases_price;
        // endif;
    }

    function creditTransactionInsert($newCylinderProductCost, $invoice_no, $invoice_id)
    {


        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        //$data['voucher_no'] = $this->input->post('voucherid');
        $data['voucher_no'] = $invoice_no;
        $data['invoice_id'] = $invoice_id;
        $data['reference'] = $this->input->post('reference');
        $data['dueDate'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 2;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['form_id'] = 11;
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);

//cylinder calculation start here....
        $addReturnAble = $this->input->post('add_returnAble');
        $newCylinderProductCost1 = 0;
        $otherProductCost = 0;
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost1 += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            //$stock['generals_id'] = $invoice_id;
            $stock['generals_id'] = $generals_id;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];

//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                /*
                 * EDIT By Nahid
                 *
                 * $stock1['generals_id'] = $cylinderId;
                 *                  */
                //$stock1['generals_id'] = $invoice_id;
                $stock1['generals_id'] = $generals_id;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
        $this->db->insert_batch('stock', $allStock);
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;

//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):

            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                //$stockReceive['generals_id'] = $invoice_id;
                $stockReceive['generals_id'] = $generals_id;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;

        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            //'history_id' => $invoice_id,
            'history_id' => $generals_id,
            'trans_type' => $this->input->post('voucherid'),
            //'trans_type' => $invoice_no,
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'paymentType' => 'Purcahses Voucher',
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
        );
        $this->db->insert('client_vendor_ledger', $supp);


//insert into client vendor ledger
//50 supplier Payable
        $gl_data = array(
            //'generals_id' => $invoice_id,
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

        /*   if (!empty($newCylinderProductCost)):
               $gl_data = array(
                   'generals_id' => $invoice_id,
                   //'generals_id' => $generals_id,
                   'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                   'form_id' => '11',
                   'dist_id' => $this->dist_id,
                   'account' => 173,
                   'debit' => $newCylinderProductCost,
                   'memo' => 'purchases',
                   'updated_by' => $this->admin_id,
                   'created_at' => $this->timestamp
               );
               $this->db->insert('generalledger', $gl_data);
           endif;*/
//Others product inventory stock.
        if (!empty($newCylinderProductCost)):
            $gl_data = array(
                //'generals_id' => $invoice_id,
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//LOADING AND UNLOADING
        $gl_data = array(
            //'generals_id' => $invoice_id,
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            //'generals_id' => $invoice_id,
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

        if ($generals_id) {
            return $generals_id;
        }
    }

    function partialTransactionInsert($newCylinderProductCost, $invoice_no, $invoice_id)
    {

        $accountCr = $this->input->post('accountCrPartial');
        $thisAllotment = $this->input->post('thisAllotment');
        if (empty($accountCr) || empty($thisAllotment)) {
            $msg = 'Purchase  ' . ' ' . $this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/purchases_add'));
        }
        $add_returnAble = array_sum($this->input->post('add_returnAble'));
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        //$data['voucher_no'] = $this->input->post('voucherid');
        $data['voucher_no'] = $invoice_no;
        $data['invoice_id'] = $invoice_id;
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 4;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
//insert in returnAlbe

        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStockCylinder = array();
        $newCylinderProductCost1 = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost1 += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            //$stock['generals_id'] = $generals_id;
            $stock['generals_id'] = $generals_id;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                //$stock1['generals_id'] = $invoice_id;
                $stock1['generals_id'] = $generals_id;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
//data insert in stock table
        $this->db->insert_batch('stock', $allStock);

        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock table
//Cylinder Stock transaction here
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):

            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                //$stockReceive['generals_id'] = $invoice_id;
                $stockReceive['generals_id'] = $generals_id;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            //'history_id' => $invoice_id,
            'history_id' => $generals_id,
            //'trans_type' => $this->input->post('voucherid'),
            'trans_type' => $invoice_no,
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger
//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            //'generals_id' => $invoice_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock
        /*  if (!empty($newCylinderProductCost)):
              $gl_data = array(
                  'generals_id' => $invoice_id,
                  //'generals_id' => $generals_id,
                  'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                  'form_id' => '11',
                  'dist_id' => $this->dist_id,
                  'account' => 173,
                  'debit' => $newCylinderProductCost,
                  'memo' => 'purchases',
                  'updated_by' => $this->admin_id,
                  'created_at' => $this->timestamp
              );
              $this->db->insert('generalledger', $gl_data);
          endif;*/
//Others product inventory stock.
        if (!empty($newCylinderProductCost)):
            $gl_data = array(
                //'generals_id' => $invoice_id,
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

        //LOADING AND UNLOADING
        $gl_data = array(
            //'generals_id' => $invoice_id,
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            //'generals_id' => $invoice_id,
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//cash calculation here.
        $generals_data['supplier_id'] = $this->input->post('supplierID');
        $generals_data['dist_id'] = $this->dist_id;
        //$generals_data['voucher_no'] = $this->input->post('voucherid');
        $generals_data['voucher_no'] = $invoice_no;
        $generals_data['reference'] = $this->input->post('reference');
        $generals_data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $generals_data['payType'] = 1;
        $generals_data['credit'] = $this->input->post('thisAllotment');
        $generals_data['narration'] = $this->input->post('narration');
        $generals_data['form_id'] = 14;
        $generals_data['updated_by'] = $this->admin_id;
        //$generals_data['mainInvoiceId'] = $invoice_id;
        $generals_data['mainInvoiceId'] = $generals_id;
        $generals_data['created_at'] = $this->timestamp;
        $paymentGeneralId = $this->Common_model->insert_data('generals', $generals_data);
        $supp1 = array(
            'ledger_type' => 2,
            'dist_id' => $this->dist_id,
            //'trans_type' => $this->input->post('voucherid'),
            'trans_type' => $invoice_no,
            'client_vendor_id' => $this->input->post('supplierID'),
            'amount' => $this->input->post('thisAllotment'),
            'cr' => $this->input->post('thisAllotment'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'updated_by' => $this->admin_id,
            'history_id' => $paymentGeneralId,
            'paymentType' => 'Supplier Payment',
        );
        $this->db->insert('client_vendor_ledger', $supp1);
// generalledger: Pay From
        $cr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCrPartial'),
            'credit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
        $dr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => 50,
            'debit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);
        $totalMoneyReceite = $this->Common_model->get_data_list_by_single_column('moneyreceit', 'dist_id', $this->dist_id);
        $mrid = "RID" . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            //'invoiceID' => $this->input->post('voucherid'),
            'invoiceID' => $invoice_no,
            'totalPayment' => $this->input->post('thisAllotment'),
            'receitID' => $mrid,
            'mainInvoiceId' => $generals_id,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 1,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
        if ($generals_id) {
            return $generals_id;
        }
    }

    function chequeTransactionInsert($newCylinderProductCost, $invoice_no, $invoice_id)
    {
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        if (empty($bankName) || empty($checkNo)) {
            exception("Required field can't be empty.");
            redirect(site_url($this->project . '/purchases_add'));
        }
//when bank transaction than start here.
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        //$data['voucher_no'] = $this->input->post('voucherid');
        $data['voucher_no'] = $invoice_no;
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 2;
        $data['debit'] = array_sum($this->input->post('price'));
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;

        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $generals_id = $this->Common_model->insert_data('generals', $data);
//insert in generall table
        $returnQty = array_sum($this->input->post('add_returnAble'));

        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();
        $newCylinderProductCost1 = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
//dd
            if ($value == 1) {
                $newCylinderProductCost1 += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $generals_id;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty)) {
                unset($stock1);
                $stock1['generals_id'] = $generals_id;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
        $this->db->insert_batch('stock', $allStock);
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock table
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                $stockReceive['generals_id'] = $generals_id;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $generals_id,
            'trans_type' => $this->input->post('voucherid'),
            //'trans_type' => $invoice_no,
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock
        /* if (!empty($newCylinderProductCost)):
             $gl_data = array(
                 'generals_id' => $generals_id,
                 'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                 'form_id' => '11',
                 'dist_id' => $this->dist_id,
                 'account' => 173,
                 'debit' => $newCylinderProductCost,
                 'memo' => 'purchases',
                 'updated_by' => $this->admin_id,
                 'created_at' => $this->timestamp
             );
             $this->db->insert('generalledger', $gl_data);
         endif;*/
//Others product inventory stock.
        if (!empty($newCylinderProductCost)):
            $gl_data = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $newCylinderProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

        //LOADING AND UNLOADING
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 336,
            'debit' => $this->input->post('loaderAmount'),
            'memo' => 'Loading and wages',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
//transportation
        $gl_data = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 339,
            'debit' => $this->input->post('transportationAmount'),
            'memo' => 'Transportation',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);
        $mrCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
        );
        $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
        $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'invoiceID' => $this->input->post('voucherid'),
            //'invoiceID' => $invoice_no,
            'totalPayment' => array_sum($this->input->post('price')),
            'receitID' => $mrid,
            'mainInvoiceId' => $generals_id,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 2,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
        if ($generals_id) {
            return $generals_id;
        }
    }

    public function cylinder_stock_report()
    {

        if (isPostBack()) {

            $brandId = $this->input->post('brandId');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['allStock'] = $this->Inventory_Model->cylinder_stock_report($start_date, $end_date, $brandId);
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Cylinder Stock Report';

        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = 'Stock Report';
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinder_stock_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function current_stock_value2()
    {


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        if (isPostBack()) {
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $productId = $this->input->post('productId');
            //$this->Dashboard_Model->getinventoryAmount($searchDay='');
            $data['allStock'] = $this->Inventory_Model->current_stock($this->dist_id, $start_date, $end_date, $productId);
/*echo "<pre>";
echo $this->db->last_query();
print_r($data['allStock']);exit;*/
            if ($productId != 'all') {
                $data['productOpening'] = $this->Inventory_Model->getProductLedgerOpening($start_date, $end_date, $productId, $this->dist_id);
            }
        }

        //$data['allStock'] = $this->Inventory_Model->current_stock_backup($this->dist_id);
        /*echo '<pre>';
        echo $this->db->last_query();
        print_r($data['allStock']);
        exit;*/

        $data['pageTitle'] = get_phrase('Current_Stock_Value');
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = get_phrase('Current_Stock_Value');
        $data['mainContent'] = $this->load->view('distributor/inventory/report/current_stock_value__', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function current_stock_value()
    {


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        if (isPostBack()) {
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $productId = $this->input->post('productId');
            //$this->Dashboard_Model->getinventoryAmount($searchDay='');
            $data['allStock'] = $this->Inventory_Model->current_stock($this->dist_id, $start_date, $end_date, $productId);
            /*echo "<pre>";
            echo $this->db->last_query();
            print_r($data['allStock']);exit;*/
            if ($productId != 'all') {
                $data['productOpening'] = $this->Inventory_Model->getProductLedgerOpening($start_date, $end_date, $productId, $this->dist_id);
            }
        }

        //$data['allStock'] = $this->Inventory_Model->current_stock_backup($this->dist_id);
        /*echo '<pre>';
        echo $this->db->last_query();
        print_r($data['allStock']);
        exit;*/

        $data['pageTitle'] = get_phrase('Current_Stock_Value');
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = get_phrase('Current_Stock_Value');
        $data['mainContent'] = $this->load->view('distributor/inventory/report/current_stock_value', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function purchases_edit($ivnoiceId = null)
    {
        if (is_numeric($ivnoiceId)) {
            //is invoice id is valid
            $validInvoiecId = $this->Purchases_Model->checkInvoiceIdAndDistributor($this->dist_id, $ivnoiceId);

            if ($validInvoiecId === NULL) {
                exception("Sorry invoice id is invalid!!");
                redirect(site_url($this->project . '/purchases_list'));
            }
        } else {
            exception("Sorry invoice id is invalid!!");
            redirect(site_url($this->project . '/purchases_list'));
        }


        if (isPostBack()) {


            $this->form_validation->set_rules('supplierID', 'Supplier ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher ID', 'required');
            //$this->form_validation->set_rules('purchasesDate', 'Purchases Date', 'required');
            //$this->form_validation->set_rules('paymentType', 'Payment Date', 'required');
            //$this->form_validation->set_rules('slNo[]', 'Payment Date', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = 'Purchase  ' . ' ' . $this->config->item("save_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/purchases_edit/' . $ivnoiceId));
            } else {

                $this->invoice_id = $this->input->post('purchase_invoice_id');
                $payType = $this->input->post('paymentType');
                $accountCr = $this->input->post('accountCrPartial');
                $thisAllotment = $this->input->post('thisAllotment');


                if ($payType == 4) {
                    if (empty($accountCr) || empty($thisAllotment)) {
                        $msg = 'Purchase  ' . ' ' . $this->config->item("save_error_message");
                        $this->session->set_flashdata('error', $msg);
                        redirect(site_url($this->project . '/purchases_edit/' . $this->invoice_id));
                    }
                }


                $this->db->trans_start();

                $Delete['is_active'] = 'N';
                $Delete['is_delete'] = 'Y';
                $Delete['update_by'] = $this->admin_id;
                $Delete['update_date'] = $this->timestamp;
                $Delete['delete_by'] = $this->admin_id;
                $Delete['delete_date'] = $this->timestamp;

                $this->db->where('purchase_invoice_id', $this->invoice_id);
                $this->db->update('purchase_details', $Delete);


                $invoice_amount = 0;

                foreach ($_POST['purchase_details_id'] as $key => $value) {
                    $returnable_quantity = $_POST['returnAbleQuantity_' . $value] != '' ? $_POST['returnAbleQuantity_' . $value] : 0;
                    $return_quentity_edit = empty($_POST['returnQuentityEdit_' . $value]) ? 0 : array_sum($_POST['returnQuentityEdit_' . $value]);
                    $return_quentity_add = empty($_POST['returnQuentityAdd_' . $value]) ? 0 : array_sum($_POST['returnQuentityAdd_' . $value]);
                    $return_quentity = $return_quentity_add + $return_quentity_edit;

                    $supplier_advance = 0;
                    $supplier_due = 0;
                    unset($stock);
                    unset($stockNew);
                    $stock['purchase_invoice_id'] = $this->invoice_id;
                    //$stock['sales_details_id'] = $value;
                    $stock['product_id'] = $_POST['product_id_' . $value];
                    $stock['is_package '] = $_POST['is_package_' . $value];
                    $stock['returnable_quantity '] = $returnable_quantity;
                    $stock['return_quentity '] = $return_quentity;
                    if ($return_quentity < $returnable_quantity) {
                        $supplier_due = $returnable_quantity - $return_quentity;
                    } else {
                        $supplier_advance = $return_quentity - $returnable_quantity;
                    }
                    $stock['supplier_due'] = $supplier_due;
                    $stock['supplier_advance'] = $supplier_advance;
                    $stock['quantity'] = $_POST['quantity_' . $value];
                    $stock['unit_price'] = $_POST['unit_price_' . $value];
                    $invoice_amount = $invoice_amount + ($_POST['quantity_' . $value] * $_POST['unit_price_' . $value]);
                    $stock['update_by'] = $this->admin_id;
                    $stock['update_date'] = $this->timestamp;
                    $stock['is_active'] = 'Y';
                    $stock['is_delete'] = 'N';

                    $this->db->where('purchase_details_id', $value);
                    $this->db->update('purchase_details', $stock);


                    $this->db->where_in('purchase_details_id', $value);
                    $this->db->update('purchase_return_details', $Delete);

                    $stockUpdate[] = $stock;

                    if (isset($_POST['returnproductEdit_' . $value])) {
                        foreach ($_POST['returnproductEdit_' . $value] as $key1 => $value1) {
                            unset($stock2);
                            $stock2['purchase_return_id'] = $value1;
                            //$stock2['product_id'] = $value1;
                            $stock2['returnable_quantity'] = $_POST['returnQuentityEdit_' . $value][$key1];
                            $stock2['return_quantity'] = $_POST['returnQuentityEdit_' . $value][$key1];
                            $stock2['update_by'] = $this->admin_id;
                            $stock2['update_date'] = $this->timestamp;
                            $stock2['is_active'] = 'Y';
                            $stock2['is_delete'] = 'N';
                            $allEditStock[] = $stock2;
                        }
                    }

                }


                if (!empty($allEditStock)) {
                    $this->db->update_batch('purchase_return_details', $allEditStock, 'purchase_return_id');
                }

                if (!empty($alladdStock)) {
                    $this->db->insert_batch('purchase_return_details', $alladdStock);
                }


                if (!empty($_POST['slNo'])) {
                    foreach ($_POST['slNo'] as $key => $value) {
                        $returnable_quantity = $_POST['add_returnAble']  [$value] != '' ? $_POST['add_returnAble']  [$value] : 0;

                        $return_quentity = empty($_POST['AddreturnQuentity_' . $value]) ? 0 : array_sum($_POST['AddreturnQuentity_' . $value]);


                        $supplier_advance = 0;
                        $supplier_due = 0;
                        unset($stockNew);
                        $stockNew['purchase_invoice_id'] = $this->invoice_id;
                        //$stock['sales_details_id'] = $value;
                        $stockNew['product_id'] = $_POST['Addproduct_id_' . $value];
                        $stockNew['is_package '] = $_POST['Addis_package_' . $value];
                        $stockNew['returnable_quantity '] = $returnable_quantity;
                        $stockNew['return_quentity '] = $return_quentity;
                        if ($return_quentity < $returnable_quantity) {
                            $supplier_due = $returnable_quantity - $return_quentity;

                        } else {
                            $supplier_advance = $return_quentity - $returnable_quantity;
                        }
                        $stockNew['supplier_due'] = $supplier_due;
                        $stockNew['supplier_advance'] = $supplier_advance;
                        $stockNew['quantity'] = $_POST['Addquantity_' . $value];
                        $stockNew['unit_price'] = $_POST['Addrate_' . $value];
                        $invoice_amount = $invoice_amount + ($_POST['Addquantity_' . $value] * $_POST['Addrate_' . $value]);
                        $stockNew['update_by'] = $this->admin_id;
                        $stockNew['update_date'] = $this->timestamp;
                        $stockNew['is_active'] = 'Y';
                        $stockNew['is_delete'] = 'N';


                        $this->db->insert('purchase_details', $stockNew);
                        //$sales_details_id = 1;
                        $sales_details_id = $this->db->insert_id();

                        $stockAddNew[] = $stockNew;


                    }
                }


                $purchase_inv['invoice_no'] = $this->input->post('voucherid');
                //$purchase_inv['purchase_invoice_id'] = $this->input->post('purchase_invoice_id');
                $purchase_inv['supplier_invoice_no'] = $this->input->post('userInvoiceId');
                $purchase_inv['supplier_id'] = $this->input->post('supplierID');
                $purchase_inv['payment_type'] = $payType;
                $purchase_inv['invoice_amount'] = $invoice_amount;
                //$purchase_inv['vat_amount'] = $this->input->post('vat');
                $purchase_inv['discount_amount'] = $this->input->post('discount') != '' ? $this->input->post('discount') : 0;
                $purchase_inv['paid_amount'] = $this->input->post('thisAllotment') != '' ? $this->input->post('thisAllotment') : 0;
                $purchase_inv['tran_vehicle_id'] = $this->input->post('transportation') != '' ? $this->input->post('transportation') : 0;
                $purchase_inv['transport_charge'] = $this->input->post('transportationAmount') != '' ? $this->input->post('transportationAmount') : 0;
                $purchase_inv['loader_charge'] = $this->input->post('loaderAmount') != '' ? $this->input->post('loaderAmount') : 0;
                $purchase_inv['loader_emp_id'] = $this->input->post('loader') != '' ? $this->input->post('loader') : 0;
                //$purchase_inv['refference_person_id'] = $this->input->post('reference');
                $purchase_inv['refference_person'] = $this->input->post('reference');
                $purchase_inv['company_id'] = $this->dist_id;
                $purchase_inv['dist_id'] = $this->dist_id;
                $purchase_inv['branch_id'] = 0;
                $purchase_inv['due_date'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
                $purchase_inv['insert_date'] = $this->timestamp;
                $purchase_inv['insert_by'] = $this->admin_id;
                $purchase_inv['invoice_date'] = $this->input->post('purchasesDate') != '' ? date('Y-m-d', strtotime($this->input->post('purchasesDate'))) : '';
                $purchase_inv['is_active'] = 'Y';
                $purchase_inv['is_delete'] = 'N';

                if ($payType == 3) {
                    $purchase_inv['bank_id'] = $this->input->post('bankName');
                    $purchase_inv['bank_branch_id'] = $this->input->post('branchName');
                    $purchase_inv['check_date'] = $this->input->post('checkDate') != '' ? date('Y-m-d', strtotime($this->input->post('checkDate'))) : '';
                    $purchase_inv['check_no'] = $this->input->post('checkNo');
                }

                $this->Common_model->update_data('purchase_invoice_info', $purchase_inv, 'purchase_invoice_id', $this->invoice_id);

                $this->db->select("*");
                $this->db->from("purchase_invoice_info");
                $this->db->where('dist_id', $this->dist_id);
                $this->db->where('purchase_invoice_id', $this->invoice_id);

                $result_invoice = $this->db->get()->row();

                $this->db->select("*");
                $this->db->from("generals");
                $this->db->where_in('form_id', 11);
                $this->db->where('dist_id', $this->dist_id);
                $this->db->where('voucher_no', $result_invoice->invoice_no);
                //$this->db->order_by('generals_id', 'DESC');
                $result = $this->db->get()->row();
                //echo '<pre>';print_r($result);exit;

                if ($payType == 2) {
                    //if payment type credit than transaction here
                    $generals_id = $this->creditTransactionInsertForUpdate($result->generals_id, $invoice_amount, $invStock);
                } elseif ($payType == 1) {
                    //if payment type Cash than transaction here
                    $generals_id = $this->cashTransactionInsertForUpdate($result->generals_id, $invoice_amount, $invStock);
                } elseif ($payType == 4) {
                    //if payment type partial than calculate from here
                    $generals_id = $this->partialTransactionInsertForUpdate($result->generals_id, $invoice_amount, $invStock);
                } else {
                    $generals_id = $this->chequeTransactionInsertForUpdate($result->generals_id, $invoice_amount, $invStock);
                }


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Purchase  ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/purchases_edit/' . $this->invoice_id));

                } else {

                    $msg = 'Purchase  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/viewPurchases/' . $this->invoice_id));

                }
            }
        }


        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $ivnoiceId);


        $stockList = $this->Common_model->get_purchase_product_detaild2($ivnoiceId);


        foreach ($stockList as $ind => $element) {
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_invoice_id'] = $element->purchase_invoice_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_details_id'] = $element->purchase_details_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['category_id'] = $element->category_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['category'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['is_package'] = $element->is_package;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_id'] = $element->product_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['productName'] = $element->productName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_code'] = $element->product_code;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['title'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['brandName'] = $element->brandName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['quantity'] = $element->quantity;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unit_price'] = $element->unit_price;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['tt_returnable_quantity'] = $element->tt_returnable_quantity;
            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'purchase_return_id' => $element->purchase_return_id,
                    'return_product_cat' => $element->return_product_cat,
                    'return_product_name' => $element->return_product_name,
                    'return_product_unit' => $element->return_product_unit,
                    'return_product_brand' => $element->return_product_brand,
                    'returnable_quantity' => $element->returnable_quantity,
                );
            } else {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id] = '';
            }
        }
        $data['stockListEdit'] = $result;
        /*echo "<pre>";
        print_r($data['stockListEdit']);
        print_r($stockList);
        exit;*/


        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 11,
        );
        $data['editPurchases'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $ivnoiceId);
        $data['editStock'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $ivnoiceId);
        //if cash payment not empty.
        $data['creditAmount2'] = $paymentInfo = $this->Inventory_Model->getCreditAmount($ivnoiceId);

        //echo $this->db->last_query();die;
        $data['accountIdForEdit'] = $this->Inventory_Model->getPurchasesAccountId($paymentInfo->generals_id);
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['cylinserOut'] = $this->Sales_Model->getCylinderInOutResult2($this->dist_id, $ivnoiceId, 23);
        $data['cylinderIn'] = $this->Sales_Model->getCylinderInOutResult($this->dist_id, $ivnoiceId, 24);
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);


        /*page navbar details*/
        $data['title'] = get_phrase('Purchases Edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Purchase_Invoice');
        $data['link_page_url'] = $this->project . '/purchases_add';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase('Invoice List');
        $data['second_link_page_url'] = $this->project . '/purchases_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('View Invoice');
        $data['third_link_page_url'] = $this->project . '/viewPurchases/' . $ivnoiceId;
        $data['third_link_icon'] = $this->link_icon_edit;
        /*page navbar details*/


        $data['unitList'] = $this->Common_model->get_data_list_by_single_column('unit', 'dist_id', $this->dist_id);
        $costCondition = array(
            'dist_id' => $this->dist_id,
            'parentId' => 62,
        );
        $data['costList'] = $this->Common_model->get_data_list_by_many_columns('generaldata', $costCondition);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['cylinderProduct'] = $this->Common_model->getPublicProduct($this->dist_id, 1);
        /* echo "<pre>";
         print_r($data['cylinderProduct']);
         exit;*/


        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['supplierList'] = $this->Common_model->getPublicSupplier($this->dist_id);

        $condition1 = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition1);
        $data['vehicleList'] = $this->Common_model->get_data_list_by_many_columns('vehicle', $condition1);
        $condition2 = array(
            'dist_id' => $this->dist_id,
            'is_active' => 'Y',
            'is_delete' => 'N',
        );
        $data['bankList'] = $this->Common_model->get_data_list_by_many_columns('bank_info', $condition2);
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('generals', $condition);
        $data['voucherID'] = "PV" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchasesEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function partialTransactionInsertForUpdate($invoiceId, $invoice_amount, $invStock)
    {
        $accountCr = $this->input->post('accountCrPartial');
        $thisAllotment = $this->input->post('thisAllotment');
        if (empty($accountCr) || empty($thisAllotment)) {
            $msg = 'Purchase  ' . ' ' . $this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/purchases_edit/' . $this->invoice_id));
        }
        $add_returnAble = array_sum($this->input->post('add_returnAble'));
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $voucherId = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 4;
        $data['debit'] = $this->input->post('netTotal');
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['mainInvoiceId'] = "PUID#-" . $this->input->post('userInvoiceId');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);

        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId, 14);

        $invoiceListByVoucher = $this->Sales_Model->getInvoiceIdListByVoucher($this->dist_id, $voucherId, 14);
        //delete general table
        if (!empty($invoiceListByVoucher)) {
            foreach ($invoiceListByVoucher as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }


        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//insert in generall table
//insert in returnAlbe

        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStockCylinder = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';

        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);


//insert into client vendor ledger
//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock
        if (!empty($newCylinderProductCost)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
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
        if (!empty($invoice_amount)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $invoice_amount,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;


        $loadingAmount = $this->input->post('loaderAmount');
        if (!empty($loadingAmount)):
//LOADING AND UNLOADING
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 336,
                'debit' => $this->input->post('loaderAmount'),
                'memo' => 'Loading and wages',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($transportationAmount)):

//transportation
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 339,
                'debit' => $this->input->post('transportationAmount'),
                'memo' => 'Transportation',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//cash calculation here.
        $generals_data['supplier_id'] = $this->input->post('supplierID');
        $generals_data['dist_id'] = $this->dist_id;
        $generals_data['voucher_no'] = $this->input->post('voucherid');
        $generals_data['reference'] = $this->input->post('reference');
        $generals_data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $generals_data['payType'] = 1;
        $generals_data['credit'] = $this->input->post('thisAllotment');
        $generals_data['narration'] = $this->input->post('narration');
        $generals_data['form_id'] = 14;
        $generals_data['updated_by'] = $this->admin_id;
        $generals_data['mainInvoiceId'] = $invoiceId;
        $generals_data['created_at'] = $this->timestamp;
        $paymentGeneralId = $this->Common_model->insert_data('generals', $generals_data);
        $supp1 = array(
            'ledger_type' => 2,
            'dist_id' => $this->dist_id,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'amount' => $this->input->post('thisAllotment'),
            'cr' => $this->input->post('thisAllotment'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'updated_by' => $this->admin_id,
            'history_id' => $paymentGeneralId,
            'paymentType' => 'Supplier Payment',
        );
        $this->db->insert('client_vendor_ledger', $supp1);
// generalledger: Pay From
        $cr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCrPartial'),
            'credit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
        $dr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $paymentGeneralId,
            'dist_id' => $this->dist_id,
            'account' => 50,
            'debit' => $this->input->post('thisAllotment'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);
        $totalMoneyReceite = $this->Common_model->get_data_list_by_single_column('moneyreceit', 'dist_id', $this->dist_id);
        $mrid = "RID" . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'invoiceID' => $this->input->post('voucherid'),
            'totalPayment' => $this->input->post('thisAllotment'),
            'receitID' => $mrid,
            'mainInvoiceId' => $invoiceId,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 1,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function chequeTransactionInsertForUpdate($invoiceId, $invoice_amount)
    {
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        if (empty($bankName) || empty($checkNo)) {
            exception("Required field can't be empty.");
            redirect(site_url($this->project . '/purchases_add'));
        }
//when bank transaction than start here.
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $voucherId = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 3;
        $data['debit'] = $this->input->post('netTotal');
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['loader'] = $this->input->post('loader');
        $data['mainInvoiceId'] = "PUID#-" . $this->input->post('userInvoiceId');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);

        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId, 14);

        $invoiceListByVoucher = $this->Sales_Model->getInvoiceIdListByVoucher($this->dist_id, $voucherId, 14);
        //delete general table
        if (!empty($invoiceListByVoucher)) {
            foreach ($invoiceListByVoucher as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }

        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//insert in generall table
        $returnQty = array_sum($this->input->post('add_returnAble'));

        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';


        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);

//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock
        if (!empty($newCylinderProductCost)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
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
        if (!empty($invoice_amount)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $invoice_amount,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;


        $loadingAmount = $this->input->post('loaderAmount');
        if (!empty($loadingAmount)):
//LOADING AND UNLOADING
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 336,
                'debit' => $this->input->post('loaderAmount'),
                'memo' => 'Loading and wages',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($transportationAmount)):

//transportation
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 339,
                'debit' => $this->input->post('transportationAmount'),
                'memo' => 'Transportation',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
        $mrCondition = array(
            'dist_id' => $this->dist_id,
            'receiveType' => 2,
        );
        $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
        $mrid = "RID" . date('y') . date('m') . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'invoiceID' => $this->input->post('voucherid'),
            'totalPayment' => $this->input->post('netTotal'),
            //'totalPayment' => array_sum($this->input->post('price')),
            'receitID' => $mrid,
            'mainInvoiceId' => $invoiceId,
            'customerid' => $this->input->post('supplierID'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 2,
            'receiveType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);

        //echo $this->db->last_query();die;

        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function creditTransactionInsertForUpdate($invoiceId, $invoice_amount)
    {

        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $voucherId = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['dueDate'] = date('Y-m-d', strtotime($this->input->post('dueDate')));
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 2;
        $data['debit'] = $invoice_amount;
        $data['narration'] = $this->input->post('narration');
        $data['mainInvoiceId'] = "PUID#-" . $this->input->post('userInvoiceId');
        $data['form_id'] = 11;
//loader
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');

        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);
        // echo $this->db->last_query();die;


        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId, 14);

        $invoiceListByVoucher = $this->Sales_Model->getInvoiceIdListByVoucher($this->dist_id, $voucherId, 14);
        //delete general table
        if (!empty($invoiceListByVoucher)) {
            foreach ($invoiceListByVoucher as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }

        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//cylinder calculation start here....
        $addReturnAble = $this->input->post('add_returnAble');
        $newCylinderProductCost = 0;
        $otherProductCost = 0;
        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStock1 = array();


//insert in stock table

        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'paymentType' => 'Purcahses Voucher',
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger
//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock
        if (!empty($newCylinderProductCost)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
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
        if (!empty($invoice_amount)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $invoice_amount,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
        $loadingAmount = $this->input->post('loaderAmount');
        if (!empty($loadingAmount)):
//LOADING AND UNLOADING
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 336,
                'debit' => $this->input->post('loaderAmount'),
                'memo' => 'Loading and wages',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($transportationAmount)):

//transportation
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 339,
                'debit' => $this->input->post('transportationAmount'),
                'memo' => 'Transportation',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    function cashTransactionInsertForUpdate($invoiceId)
    {
        $accountCr = $this->input->post('accountCr');
        if (!empty($accountCr) && $accountCr > 0) {
            exception("Required field can't be empty.");
            redirect(site_url($this->project . '/purchases_add'));
        }
        $add_returnAble = $this->input->post('add_returnAble');
// echo count($add_returnAble);die;
        $data['supplier_id'] = $this->input->post('supplierID');
        $data['dist_id'] = $this->dist_id;
        $data['voucher_no'] = $voucherId = $this->input->post('voucherid');
        $data['reference'] = $this->input->post('reference');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $data['payType'] = 1;
        $data['debit'] = $this->input->post('netTotal');
        $data['narration'] = $this->input->post('narration');
        $data['form_id'] = 11;
        $data['loader'] = $this->input->post('loader');
        $data['transportation'] = $this->input->post('transportation');
        $data['loaderAmount'] = $this->input->post('loaderAmount');
        $data['transportationAmount'] = $this->input->post('transportationAmount');
        $data['mainInvoiceId'] = "PUID#-" . $this->input->post('userInvoiceId');
        $data['discount'] = $this->input->post('discount');
        $data['updated_by'] = $this->admin_id;
        $data['created_at'] = $this->timestamp;
        $this->Common_model->update_data('generals', $data, 'generals_id', $invoiceId);

//echo $this->db->last_query();die;


        $this->Common_model->delete_data('stock', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('generalledger', 'generals_id', $invoiceId);
        $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $invoiceId);
        $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $invoiceId);
        $invoiceList = $this->Sales_Model->getInvoiceIdList($this->dist_id, $invoiceId, 14);

        $invoiceListByVoucher = $this->Sales_Model->getInvoiceIdListByVoucher($this->dist_id, $voucherId, 14);
        //delete general table
        if (!empty($invoiceListByVoucher)) {
            foreach ($invoiceListByVoucher as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }


        //delete general table
        if (!empty($invoiceList)) {
            foreach ($invoiceList as $eachId):
                $this->Common_model->delete_data('generals', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('stock', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('generalledger', 'generals_id', $eachId->generals_id);
                $this->Common_model->delete_data('client_vendor_ledger', 'history_id', $eachId->generals_id);
                $this->Common_model->delete_data('moneyreceit', 'mainInvoiceId', $eachId->generals_id);
            endforeach;
        }
//insert in generall table
        $retualAble = array_sum($this->input->post('add_returnAble'));

        $productCate = $this->input->post('category_id');
        $allStock = array();
        $allStockCylinder = array();
        $newCylinderProductCost = '';
        $otherProductCost = '';
        foreach ($productCate as $key => $value):
            unset($stock);
            if ($value == 1) {
                $newCylinderProductCost += $this->input->post('price')[$key];
            } else {
                $otherProductCost += $this->input->post('price')[$key];
            }
            $stock['generals_id'] = $invoiceId;
            $stock['category_id'] = $value;
            $stock['product_id'] = $this->input->post('product_id')[$key];
            $stock['quantity'] = $this->input->post('quantity')[$key];
            $stock['rate'] = $this->input->post('rate')[$key];
            $stock['price'] = $this->input->post('price')[$key];
            $stock['unit'] = getProductUnit($this->input->post('product_id')[$key]);
            $stock['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
            $stock['form_id'] = 11;
            $stock['type'] = 'In';
            $stock['dist_id'] = $this->dist_id;
            $stock['updated_by'] = $this->admin_id;
            $stock['created_at'] = $this->timestamp;
            $allStock[] = $stock;
            $returnQty = $this->input->post('add_returnAble')[$key];
//If cylinder stock out than transaction store here.
            if (!empty($returnQty) && $returnQty > 0) {
                unset($stock1);
                //$stock1['generals_id'] = $cylinderId;
                $stock1['generals_id'] = $invoiceId;
                $stock1['category_id'] = $value;
                $stock1['product_id'] = $this->input->post('product_id')[$key];
                $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
                $stock1['quantity'] = $this->input->post('add_returnAble')[$key];
                $stock1['rate'] = $this->input->post('rate')[$key];
                $stock1['price'] = $this->input->post('price')[$key];
                $stock1['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stock1['form_id'] = 24;
                $stock1['type'] = 'Cin';
                $stock1['dist_id'] = $this->dist_id;
                $stock1['supplierId'] = $this->input->post('supplierID');
                $stock1['updated_by'] = $this->admin_id;
                $stock1['created_at'] = $this->timestamp;
                $allStock1[] = $stock1;
            }
        endforeach;
//data insert in stock table
        $this->db->insert_batch('stock', $allStock);
        if (!empty($allStock1)):
            $this->db->insert_batch('stock', $allStock1);
        endif;
//insert in stock tabl
        $cylinderRecive = $this->input->post('category_id2');
        $cylinderAllStock = array();
        if (!empty($cylinderRecive) && $cylinderRecive > 0):
            foreach ($cylinderRecive as $key => $value) :
                unset($stockReceive);
                $stockReceive['category_id'] = $value;
                $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
                $stockReceive['form_id'] = 23;
                //$stockReceive['generals_id'] = $CylinderReceiveid;
                $stockReceive['generals_id'] = $invoiceId;
                $stockReceive['type'] = 'Cout';
                $stockReceive['dist_id'] = $this->dist_id;
                $stockReceive['supplierId'] = $this->input->post('supplierID');
                $stockReceive['updated_by'] = $this->admin_id;
                $stockReceive['created_at'] = $this->timestamp;
                $cylinderAllStock[] = $stockReceive;
            endforeach;
//insert for culinder receive
            $this->db->insert_batch('stock', $cylinderAllStock);
        endif;
        $supp = array(
            'ledger_type' => 2,
            'trans_type' => 'purchase',
            'history_id' => $invoiceId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('netTotal'),
            'dr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'paymentType' => 'Purchase Voucher'
        );
        $this->db->insert('client_vendor_ledger', $supp);
//insert into client vendor ledger
//50 supplier Payable
        $gl_data = array(
            'generals_id' => $invoiceId,
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'form_id' => '11',
            'dist_id' => $this->dist_id,
            'account' => 50,
            'credit' => $this->input->post('netTotal'),
            'memo' => 'purchases',
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $gl_data);

//new cylinder product stock
        if (!empty($newCylinderProductCost)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
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
        if (!empty($otherProductCost)):
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 52,
                'debit' => $otherProductCost,
                'memo' => 'purchases',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;


        $loadingAmount = $this->input->post('loaderAmount');
        if (!empty($loadingAmount)):
//LOADING AND UNLOADING
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 336,
                'debit' => $this->input->post('loaderAmount'),
                'memo' => 'Loading and wages',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;

        $transportationAmount = $this->input->post('transportationAmount');
        if (!empty($transportationAmount)):

//transportation
            $gl_data = array(
                'generals_id' => $invoiceId,
                'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
                'form_id' => '11',
                'dist_id' => $this->dist_id,
                'account' => 339,
                'debit' => $this->input->post('transportationAmount'),
                'memo' => 'Transportation',
                'updated_by' => $this->admin_id,
                'created_at' => $this->timestamp
            );
            $this->db->insert('generalledger', $gl_data);
        endif;
//cash calculation here.
        $generals_data['supplier_id'] = $this->input->post('supplierID');
        $generals_data['dist_id'] = $this->dist_id;
        $generals_data['voucher_no'] = $this->input->post('voucherid');
        $generals_data['reference'] = $this->input->post('reference');
        $generals_data['date'] = date('Y-m-d', strtotime($this->input->post('purchasesDate')));
        $generals_data['payType'] = 1;
        $generals_data['credit'] = array_sum($this->input->post('price'));
        $generals_data['narration'] = $this->input->post('narration');
        $generals_data['form_id'] = 14;
        $generals_data['mainInvoiceId'] = $invoiceId;
        $generals_data['updated_by'] = $this->admin_id;
        $generals_data['created_at'] = $this->timestamp;
        $this->db->insert('generals', $generals_data);
        $generalsIdPayment = $this->db->insert_id();
        $supp1 = array(
            'ledger_type' => 2,
            'dist_id' => $this->dist_id,
            'history_id' => $generalsIdPayment,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('supplierID'),
            'amount' => $this->input->post('netTotal'),
            'cr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'updated_by' => $this->admin_id,
            'paymentType' => 'Supplier Payment',
        );
        $this->db->insert('client_vendor_ledger', $supp1);
// generalledger: Pay From
        $cr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $generalsIdPayment,
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCr'),
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $cr_data);
// generalledger: Account Payables
        $dr_data = array(
            'form_id' => '14',
            'date' => date('Y-m-d', strtotime($this->input->post('purchasesDate'))),
            'generals_id' => $generalsIdPayment,
            'dist_id' => $this->dist_id,
            'account' => 50,
            'debit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $this->db->insert('generalledger', $dr_data);
        if ($invoiceId) {
            return $invoiceId;
        }
    }

    public function purchases_list()
    {

        /*page navbar details*/
        $data['title'] = get_phrase('Purchases List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Purchase_Invoice');
        $data['link_page_url'] = $this->project . '/purchases_add';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/


        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchases_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function viewPurchases($purchases_id = NULL)
    {

        /*page navbar details*/
        $data['title'] = get_phrase(' Purchases View');
        $data['page_type'] = $this->page_type;

        $data['link_page_name'] = get_phrase('Purchases_Add');
        $data['link_page_url'] = $this->project . '/purchases_add';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase('Purchases_List');
        $data['second_link_page_url'] = $this->project . '/purchases_list';
        $data['second_link_icon'] = $this->link_icon_list;

        $data['third_link_page_name'] = get_phrase('Edit_Invoice');
        $data['third_link_page_url'] = $this->project . '/purchases_edit/' . $purchases_id;
        $data['third_link_icon'] = $this->link_icon_edit;


        //$data['title'] = 'Purchases View';
        $data['purchasesList'] = $this->Common_model->get_single_data_by_single_column('purchase_invoice_info', 'purchase_invoice_id', $purchases_id);

        $stockList = $this->Common_model->get_purchase_product_detaild2($purchases_id);

        foreach ($stockList as $ind => $element) {
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_invoice_id'] = $element->purchase_invoice_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['purchase_details_id'] = $element->purchase_details_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['is_package'] = $element->is_package;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_id'] = $element->product_id;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['productName'] = $element->productName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['product_code'] = $element->product_code;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['title'] = $element->title;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unitTtile'] = $element->unitTtile;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['brandName'] = $element->brandName;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['quantity'] = $element->quantity;
            $result[$element->purchase_invoice_id][$element->purchase_details_id]['unit_price'] = $element->unit_price;
            //$result[$element->sales_invoice_id][$element->sales_details_id]['unit_price'] = $element->unit_price;
            if ($element->returnable_quantity > 0) {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id][] = array('return_product_name' => $element->return_product_name,
                    'return_product_id' => $element->return_product_id,
                    'return_product_cat' => $element->return_product_cat,
                    'return_product_name' => $element->return_product_name,
                    'return_product_unit' => $element->return_product_unit,
                    'return_product_brand' => $element->return_product_brand,
                    'returnable_quantity' => $element->returnable_quantity,
                );
            } else {
                $result[$element->purchase_invoice_id][$element->purchase_details_id]['return'][$element->purchase_details_id] = '';
            }
        }
        /*echo  $this->db->last_query();
         echo "<pre>";
         print_r($stockList);
         print_r($result);
         exit;*/
        $data['stockList'] = $result;

        $data['creditAmount'] = $paymentInfo = $this->Inventory_Model->getCreditAmount($purchases_id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['purchasesList']->supplier_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/purchases/purchases_view', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }



}
