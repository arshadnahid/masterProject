<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PosController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Pos_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');

        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function salesPosAdd() {
        //die('OK');

        if (isPostBack()) {




            // dumpVar($_POST);
            $this->form_validation->set_rules('netTotal', 'Net Total', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('saleDate', 'Sales Date', 'required');
            $this->form_validation->set_rules('paymentType', 'Payment Type', 'required');
            $this->form_validation->set_rules('category_id[]', 'Category ID', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product', 'required');
            $this->form_validation->set_rules('quantity[]', 'Product Quantity', 'required');
            $this->form_validation->set_rules('rate[]', 'Product Rate', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');

            $forPrint = $this->input->post('forPrint');

            $allData = $this->input->post();


            if ($this->form_validation->run() === FALSE) {

                exception("Required field can't be empty.");
                redirect(site_url('salesPos'));
            } else {

                $customerId = $this->input->post('customer_id');
                $productId = $this->input->post('product_id');
                $pmtype = $this->input->post('paymentType');
                $ppAmount = $this->input->post('partialPayment');
                $this->db->trans_start();
                $payType = $this->input->post('paymentType');
                // echo $payType;die;
                $data['customer_id'] = $this->input->post('customer_id');
                $data['voucher_no'] = $this->input->post('voucherid');
                $data['reference'] = $this->input->post('reference');
                $data['payType'] = $this->input->post('paymentType');
                $data['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                $data['discount'] = $this->input->post('discount');

                $data['narration'] = $this->input->post('narration');
                $data['shipAddress'] = $this->input->post('shippingAddress');
                $data['mainInvoiceId'] = $this->input->post('userInvoiceId');
                $data['form_id'] = 31;
                $data['debit'] = $this->input->post('netTotal');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;

                $grandtotal = $this->input->post('grandtotal');

                if (!empty($this->input->post('vat'))) {
                    $data['vat'] = $this->input->post('vat');
                } else {
                    $data['vat'] = 0;
                }


                $data['vatAmount'] = ($grandtotal / 100) * $data['vat'];
                //die('hello');

                $generals_id = $this->Common_model->insert_data('generals', $data);
                $returnQty = array_sum($this->input->post('returnQuantity'));

                //cylinder calculation start here....
                if (!empty($returnQty)) {
                    /*
                     *
                     *
                     * $cylinder['customer_id'] = $this->input->post('customer_id');
                      $cylinder['voucher_no'] = $this->input->post('voucherid');
                      $cylinder['reference'] = $this->input->post('reference');
                      $cylinder['payType'] = $this->input->post('paymentType');
                      $cylinder['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                      $cylinder['discount'] = $this->input->post('discount');
                      $cylinder['vat'] = $this->input->post('vat');
                      $cylinder['narration'] = $this->input->post('narration');
                      $cylinder['shipAddress'] = $this->input->post('shippingAddress');
                      $cylinder['form_id'] = 23;
                      $cylinder['debit'] = $this->input->post('netTotal');
                      $cylinder['dist_id'] = $this->dist_id;
                      $cylinder['mainInvoiceId'] = $generals_id;
                      $cylinder['updated_by'] = $this->admin_id;
                      $cylinder['vatAmount'] = ($grandtotal / 100) * $data['vat'];
                      $cylinderId = $this->Common_model->insert_data('generals', $cylinder); */
                }
                $customerName = $this->Common_model->tableRow('customer', 'customer_id', $data['customer_id'])->customerName;
                $mobile = $this->Common_model->tableRow('customer', 'customer_id', $data['customer_id'])->customerPhone;
                $category_cat = $this->input->post('category_id');
                $allStock = array();
                $allStock1 = array();
                $totalProductCost = 0;
                $newCylinderProductCost = 0;
                $otherProductCost = 0;
                foreach ($category_cat as $key => $value):
                    unset($stock);
                    $productCost = $this->Sales_Model->productCost($this->input->post('product_id')[$key], $this->dist_id);
                    $totalProductCost += $this->input->post('quantity')[$key] * $productCost;

                    if ($value == 1) {
                        //get cylinder product cost
                        $newCylinderProductCost += $this->input->post('quantity')[$key] * $productCost;
                    } else {
                        //get without cylinder product cost
                        $otherProductCost += $this->input->post('quantity')[$key] * $productCost;
                    }
                    $stock['generals_id'] = $generals_id;
                    $stock['category_id'] = $value;
                    $stock['product_id'] = $this->input->post('product_id')[$key];
                    $stock['unit'] =getProductUnit($this->input->post('product_id')[$key]);
                    $stock['quantity'] = $this->input->post('quantity')[$key];
                    $stock['rate'] = $this->input->post('rate')[$key];
                    $stock['price'] = $this->input->post('price')[$key];
                    $stock['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                    $stock['form_id'] = 31;
                    $stock['type'] = 'Out';
                    $stock['dist_id'] = $this->dist_id;
                    $stock['updated_by'] = $this->admin_id;
                    $stock['created_at'] = $this->timestamp;
                    $allStock[] = $stock;
                    $returnQty = $this->input->post('returnQuantity')[$key];
                    //If cylinder stock out than transaction store here.
                    if (!empty($returnQty)) {
                        // $productCost = $this->Sales_Model->productCost($this->input->post('product_id')[$key], $this->dist_id);
                        $stock1['generals_id'] = $generals_id;
                        $stock1['category_id'] = $value;
                        $stock1['product_id'] = $this->input->post('product_id')[$key];
                        $stock1['unit'] = getProductUnit($this->input->post('product_id')[$key]);
                        $stock1['quantity'] = $this->input->post('returnQuantity')[$key];
                        $stock1['rate'] = $this->input->post('rate')[$key];
                        $stock1['price'] = $this->input->post('price')[$key];
                        $stock1['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                        $stock1['form_id'] = 23;
                        $stock1['type'] = 'Cout';
                        $stock1['dist_id'] = $this->dist_id;
                        $stock1['customerId'] = $this->input->post('customer_id');
                        $stock1['updated_by'] = $this->admin_id;
                        $stock1['created_at'] = $this->timestamp;
                        $allStock1[] = $stock1;
                    }
                endforeach;
                $cylinderRecive = $this->input->post('category_id2');
                $cylinderAllStock = array();
                if (!empty($cylinderRecive)):
                    /* $cylinderData['customer_id'] = $this->input->post('customer_id');
                      $cylinderData['voucher_no'] = $this->input->post('voucherid');
                      $cylinderData['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                      $cylinderData['narration'] = $this->input->post('narration');
                      $cylinderData['form_id'] = 24;
                      $cylinderData['dist_id'] = $this->dist_id;
                      $cylinderData['mainInvoiceId'] = $generals_id;
                      $cylinderData['updated_by'] = $this->admin_id;
                      $CylinderReceive = $this->Common_model->insert_data('generals', $cylinderData); */
                    foreach ($cylinderRecive as $key => $value) :
                        //$stock1['generals_id'] = $cylinderId;
                        $stockReceive['generals_id'] = $generals_id;
                        $stockReceive['category_id'] = $value;
                        $stockReceive['product_id'] = $this->input->post('product_id2')[$key];
                        $stockReceive['unit'] = getProductUnit($this->input->post('product_id2')[$key]);
                        $stockReceive['quantity'] = $this->input->post('quantity2')[$key];
                        $stockReceive['date'] = date('Y-m-d', strtotime($this->input->post('saleDate')));
                        $stockReceive['form_id'] = 24;
                        $stockReceive['type'] = 'Cin';
                        $stockReceive['dist_id'] = $this->dist_id;
                        $stockReceive['customerId'] = $this->input->post('customer_id');
                        $stockReceive['updated_by'] = $this->admin_id;
                        $stockReceive['created_at'] = $this->timestamp;
                        $cylinderAllStock[] = $stockReceive;
                    endforeach;
                    //insert for culinder receive
                    $this->db->insert_batch('stock', $cylinderAllStock);
                endif;
                //insert for quantity out stock
                $this->db->insert_batch('stock', $allStock);
                //insert for cylinder stock out
                $this->db->insert_batch('stock', $allStock1);
                //insert in stock table
                $customerLedger = array(
                    'ledger_type' => 1,
                    'trans_type' => 'Sales',
                    'history_id' => $generals_id,
                    'trans_type' => $this->input->post('voucherid'),
                    'client_vendor_id' => $this->input->post('customer_id'),
                    'updated_by' => $this->admin_id,
                    'dist_id' => $this->dist_id,
                    'amount' => $this->input->post('netTotal'),
                    'dr' => $this->input->post('netTotal'),
                    'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                );
                $this->db->insert('client_vendor_ledger', $customerLedger);
                $mrCondition = array(
                    'dist_id' => $this->dist_id,
                    'receiveType' => 1,
                );
                $totalMoneyReceite = $this->Common_model->get_data_list_by_many_columns('moneyreceit', $mrCondition);
                $mrid = "CMR" . date("y") . date("m") . str_pad(count($totalMoneyReceite) + 1, 4, "0", STR_PAD_LEFT);
                if ($payType == 1) {
                    //when payment type cash
                    $this->cashTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                } elseif ($payType == 2) {
                    //when payment type credit
                    $this->creditTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                } elseif ($payType == 3) {
                    //when payment type cheque.
                    $this->chequeTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                } elseif ($payType == 4) {
                    //when partial paymet start from here.
                    $this->partialTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid);
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    notification("Your sales can't be inserted.Something is wrong.");
                    if (!empty($confirmId)) {
                        redirect('salesImportConfirm/' . $confirmId, 'refresh');
                    } else {
                        redirect('salesInvoice_add/', 'refresh');
                    }
                } else {
                    if ($forPrint == 'WithPrint') {

                        $data['saleslist'] = $this->Pos_Model->get_pos_data($pos_id = $generals_id, $this->dist_id);
                        // echo $this->db->last_query();;
                        $data['appConfig'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
                        $file_name = $this->load->view('distributor/sales/salesPos/salepos_print', $data, TRUE);
                        echo $file_name;
                        die;
                    } else {
                        message("Your data successfully inserted into database.");
                        if (!empty($confirmId)) {
                            $updateStata['ConfirmStatus'] = 1;
                            $this->Common_model->update_data('purchase_demo', $updateStata, 'purchase_demo_id', $confirmId);
                            redirect('salesImport', 'refresh');
                        } else {
                            redirect('salesInvoice_view/' . $generals_id, 'refresh');
                        }
                    }
                }
            }
        }

        $salesCondition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 31,
        );
        $cusCondition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $customerID = $this->Sales_Model->getCustomerID($this->dist_id);
        $data['customerID'] = $this->Sales_Model->checkDuplicateCusID($customerID, $this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['configInfo'] = $this->Common_model->get_single_data_by_single_column('tbl_distributor', 'dist_id', $this->dist_id);
        $data['title'] = 'Sale POS';
        $data['accountHeadList'] = $this->Common_model->getAccountHead();
        $data['productList'] = $this->Common_model->getPublicProduct($this->dist_id, 2);
        $data['productListWithCat'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['customerList'] = $this->Common_model->get_data_list_by_many_columns('customer', $cusCondition);
        $totalSale = $this->Common_model->get_data_list_by_many_columns('generals', $salesCondition);
        $data['voucherID'] = "SID" . date("y") . date("m") . str_pad(count($totalSale) + 1, 4, "0", STR_PAD_LEFT);
        $data['pageName'] = 'salePosAdd';
        $data['mainContent'] = $this->load->view('distributor/sales/salesPos/salePosAdd', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function creditTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost) {
        //when due transction start from here.
        //58 account receiable head debit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts


        if (!empty($data['discount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //62 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.

        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
    }

    function cashTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid) {

        // when cash transction start from here
        //58 account receiable head debit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts
        if (!empty($data['discount'])) :
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //7501 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.

        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //customer payment ledger
        $generals_data = array(
            'form_id' => '7',
            'customer_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'mainInvoiceId' => $generals_id,
            'voucher_no' => $this->input->post('voucherid'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'credit' => $this->input->post('netTotal'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $generalPaymentId = $this->Common_model->insert_data('generals', $generals_data);


        //1301 Cash in Hand  head debit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '54',
            'debit' => $this->input->post('netTotal'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //58 Account Receivable head credit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'credit' => $this->input->post('netTotal'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //client vendor ledger
        $customerLedger1 = array(
            'ledger_type' => 1,
            'trans_type' => 'Sales Payment',
            'history_id' => $generalPaymentId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'updated_by' => $this->admin_id,
            'amount' => $this->input->post('netTotal'),
            'cr' => $this->input->post('netTotal'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate')))
        );
        $this->db->insert('client_vendor_ledger', $customerLedger1);
        //money Receite General
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'invoiceID' => json_encode($this->input->post('voucherid')),
            'totalPayment' => $this->input->post('netTotal'),
            'receitID' => $mrid,
            'mainInvoiceId' => $generals_id,
            'dist_id' => $this->dist_id,
            'customerid' => $this->input->post('customer_id'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'paymentType' => 1
        );
        $this->db->insert('moneyreceit', $moneyReceit);
    }

    function chequeTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid) {

        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts
        if (!empty($data['discount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //62 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.

        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }

        $bankName = $this->input->post('bankName');
        $checkNo = $this->input->post('checkNo');
        $checkDate = $this->input->post('checkDate');
        $branchName = $this->input->post('branchName');
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'invoiceID' => json_encode($this->input->post('voucherid')),
            'totalPayment' => $this->input->post('netTotal'),
            'receitID' => $mrid,
            'customerid' => $this->input->post('customer_id'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'mainInvoiceId' => $generals_id,
            'paymentType' => 2,
            'bankName' => isset($bankName) ? $bankName : '0',
            'checkNo' => isset($checkNo) ? $checkNo : '0',
            'checkDate' => isset($checkDate) ? date('Y-m-d', strtotime($checkDate)) : '0',
            'branchName' => isset($branchName) ? $branchName : '0');
        $this->db->insert('moneyreceit', $moneyReceit);
    }

    function partialTransactionInsert($generals_id, $data, $totalProductCost, $otherProductCost, $newCylinderProductCost, $mrid) {

        $this->form_validation->set_rules('partialPayment', 'Partial Payment', 'required');
        $this->form_validation->set_rules('accountCrPartial', 'Account Head', 'required');
        if ($this->form_validation->run() == FALSE) {
            exception("Required field can't be empty.");
            redirect(site_url('salesInvoice_add'));
        }
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'debit' => $this->input->post('netTotal'), //sales - discount= grand + vat =newNettotal
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //59  Prompt Given Discounts
        if (!empty($data['discount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '59',
                'debit' => $data['discount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
//49  Sales head credit
        $singleLedger = array(
            'generals_id' => $generals_id,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '5',
            'dist_id' => $this->dist_id,
            'account' => '49',
            'credit' => array_sum($this->input->post('price')),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //60 Sales tax/vat head credit
        if (!empty($data['vatAmount'])):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '60',
                'credit' => $data['vatAmount'],
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //7501 Cost of Goods-Retail head debit
        if (!empty($totalProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '62',
                'debit' => $totalProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //52 account Inventory head credit
        if (!empty($otherProductCost)):
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '52',
                'credit' => $otherProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        endif;
        //cylinder product stock.
        if (!empty($newCylinderProductCost)) {
            $singleLedger = array(
                'generals_id' => $generals_id,
                'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
                'form_id' => '5',
                'dist_id' => $this->dist_id,
                'account' => '173',
                'credit' => $newCylinderProductCost,
                'updated_by' => $this->admin_id,
            );
            $this->db->insert('generalledger', $singleLedger);
        }
        //cash or partial payment start here.
        $generals_data = array(
            'form_id' => '7',
            'customer_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'mainInvoiceId' => $generals_id,
            'voucher_no' => $this->input->post('voucherid'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'credit' => $this->input->post('partialPayment'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'created_at' => $this->timestamp
        );
        $generalPaymentId = $this->Common_model->insert_data('generals', $generals_data);
        //1301 Cash in Hand  head debit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '7',
            'dist_id' => $this->dist_id,
            'account' => $this->input->post('accountCrPartial'),
            'debit' => $this->input->post('partialPayment'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //58 Account Receivable head credit
        $singleLedger = array(
            'generals_id' => $generalPaymentId,
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'form_id' => '7',
            'dist_id' => $this->dist_id,
            'account' => '58',
            'credit' => $this->input->post('partialPayment'),
            'updated_by' => $this->admin_id,
        );
        $this->db->insert('generalledger', $singleLedger);
        //client vendor ledger
        $customerLedger = array(
            'ledger_type' => 1,
            'trans_type' => 'Sales Payment',
            'history_id' => $generalPaymentId,
            'trans_type' => $this->input->post('voucherid'),
            'client_vendor_id' => $this->input->post('customer_id'),
            'dist_id' => $this->dist_id,
            'amount' => $this->input->post('partialPayment'),
            'cr' => $this->input->post('partialPayment'),
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate')))
        );
        $this->db->insert('client_vendor_ledger', $customerLedger);
        //money Receite General
        $moneyReceit = array(
            'date' => date('Y-m-d', strtotime($this->input->post('saleDate'))),
            'invoiceID' => json_encode($this->input->post('voucherid')),
            'totalPayment' => $this->input->post('partialPayment'),
            'receitID' => $mrid,
            'customerid' => $this->input->post('customer_id'),
            'narration' => $this->input->post('narration'),
            'updated_by' => $this->admin_id,
            'dist_id' => $this->dist_id,
            'paymentType' => 1,
            'mainInvoiceId' => $generalPaymentId
        );
        $this->db->insert('moneyreceit', $moneyReceit);
        //partial payment stop here.
    }

    public function salesPosList() {
        $data['title'] = 'Sale || Pos';
        $data['saleslist'] = $this->Pos_Model->getSalesPosVoucherList();

        $data['mainContent'] = $this->load->view('distributor/sales/salesPos/salePosList', $data, true);
        $this->load->view('distributor/masterDashboard', $data);
    }

    function add_pos_with_print() {
        log_message('error', 'save lab pos record nahid result: ' . print_r($_POST, TRUE));
        // print_r($_POST);
        // return $_POST;
    }

    public function get_invoice_data() {
        $data['saleslist'] = $this->Pos_Model->get_pos_data($pos_id = 36, $dist_id = 15);
        // echo $this->db->last_query();;
        $data['appConfig'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        echo $file_name = $this->load->view('distributor/sales/salesPos/salepos_print', $data, TRUE);
        //echo '<pre>';
        // print_r($data['saleslist']);
    }

}
