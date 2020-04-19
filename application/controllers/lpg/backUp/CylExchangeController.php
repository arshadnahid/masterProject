<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/13/2019
 * Time: 11:16 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class CylExchangeController extends CI_Controller
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


    function cylinderInOutJournalAdd()
    {

        if (isPostBack()) {


//dumpVar($_POST);
            $this->db->trans_start();

            $this->db->from('inventory_adjustment_info');
            $query = $this->db->get();
            $rowcount = $query->num_rows();
            $totalInventoryAdj = $rowcount;
            $inv_adjustment_no = "INVADJ" . date('y') . date('m') . str_pad(($totalInventoryAdj) + 1, 4, "0", STR_PAD_LEFT);



            $payType = $this->input->post('payType');
            $cust = $this->input->post('customer_id');
            $supid = $this->input->post('supplier_id');
            if (!empty($cust)):
                $cylinder_exchange_info['customer_id'] = $cust;
            endif;
            if (!empty($supid)):
                $cylinder_exchange_info['supplier_id'] = $supid;
            endif;
            $cylinder_exchange_info['voucher_no'] = $this->input->post('voucherid');
            $cylinder_exchange_info['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $cylinder_exchange_info['narration'] = $this->input->post('narration');
            $cylinder_exchange_info['dist_id'] = $this->dist_id;
            $cylinder_exchange_info['insert_by'] = $this->admin_id;
            $cylinder_exchange_info['insert_date'] = $this->timestamp;
            $CylinderReceiveid = $this->Common_model->insert_data('cylinder_exchange_info', $cylinder_exchange_info);
            $productId = $this->input->post('productId');
            $qtyOutAll = array();
            $qtyInAll = array();
            foreach ($productId as $key => $value) :
                unset($qtyIn1);
                unset($qtyOut1);
                $qtyOut = $this->input->post('qtyOut')[$key];
                $qtyIn = $this->input->post('qtyIn')[$key];

                $qtyIn1['exchange_info_id'] = $CylinderReceiveid;
                $qtyIn1['product_id'] = $value;
                $qtyIn1['exchange_in'] = $qtyIn;
                $qtyIn1['exchange_out'] = $qtyOut;
                $qtyIn1['dist_id'] = $this->dist_id;
                if (!empty($cust)):
                    $qtyIn1['customer_id'] = $cust;
                endif;
                if (!empty($supid)):
                    $qtyIn1['supplier_id'] = $supid;
                endif;
                $qtyIn1['insert_by'] = $this->admin_id;
                $qtyIn1['insert_date'] = $this->timestamp;
                $qtyInAll[] = $qtyIn1;


            endforeach;
            $this->db->insert_batch('cylinder_exchange_details', $qtyInAll);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE):
                $msg = 'Cylinder Exchange ' . ' ' . $this->config->item("save_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/cylinderInOutJournalAdd/'));
            else:
                $msg = 'Cylinder Exchange ' . ' ' . $this->config->item("save_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project.'/cylinderInOutJournalView/' . $CylinderReceiveid));
            endif;
        }
        $vCondition = array(
            'dist_id' => $this->dist_id,
            //'form_id' => 27,
        );
        $totalVoucher = $this->Common_model->get_data_list_by_many_columns('cylinder_exchange_info', $vCondition);
        $data['voucherID'] = "CV" . date("y") . date("m") . str_pad(count($totalVoucher) + 1, 4, "0", STR_PAD_LEFT);
        $data['Cylinder'] = $this->Common_model->getPublicProduct($this->dist_id, 1);


        /*page navbar details*/
        $data['title'] = 'Cylinder Exchange Add';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Exchange List';
        $data['link_page_url'] = 'cylinderInOutJournal';
        $data['link_icon'] = $this->link_icon_list;
        /*page navbar details*/


        $data['mainContent'] = $this->load->view($this->folderSub . 'cylinderInOutAdd', $data, true);
        $this->load->view($this->folder, $data);
    }

    public function cylinderInOutJournal()
    {
        $condtion = array(
            'dist_id' => $this->dist_id,
            'form_id' => 27,
        );

        $query = "SELECT cylinder_exchange_info.exchange_info_id,
                cylinder_exchange_info.voucher_no,
                cylinder_exchange_info.date,
                cylinder_exchange_info.narration,
                cylinder_exchange_info.customer_id,
                cylinder_exchange_info.supplier_id,
                SUM(cylinder_exchange_details.exchange_out) AS out_qty,
                SUM(cylinder_exchange_details.exchange_in) AS in_qty,
                supplier.supName,
                customer.customerName
                FROM cylinder_exchange_info 
                LEFT JOIN customer ON customer.customer_id=cylinder_exchange_info.customer_id
                LEFT JOIN supplier ON supplier.sup_id=cylinder_exchange_info.supplier_id
                LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.exchange_info_id=cylinder_exchange_info.exchange_info_id
                WHERE cylinder_exchange_info.is_active='Y' AND cylinder_exchange_info.is_delete='N'
                GROUP BY cylinder_exchange_info.exchange_info_id";
        $query = $this->db->query($query);
        $result = $query->result();
        /* echo '<pre>';
        print_r($result);
        die;*/

        /*page navbar details*/
        $data['title'] = 'Cylinder Exchange List';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Add Exchange';
        $data['link_page_url'] = $this->project.'/cylinderInOutJournalAdd';
        $data['link_icon'] = $this->link_icon_add;
        /*page navbar details*/


        $data['cylinderList'] = $result;
        $data['mainContent'] = $this->load->view($this->folderSub . 'cylinderInOutList', $data, true);
        $this->load->view($this->folder, $data);
    }

    function cylinderInOutJournalView($id = null)
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'category_id' => 1
        );
        $query = "SELECT cylinder_exchange_info.exchange_info_id,
                cylinder_exchange_info.voucher_no,
                cylinder_exchange_info.date,
                cylinder_exchange_info.narration,
                cylinder_exchange_info.customer_id,
                cylinder_exchange_info.supplier_id,
                cylinder_exchange_details.exchange_out,
                cylinder_exchange_details.product_id,
                cylinder_exchange_details.exchange_in,
                supplier.supName,
                customer.customerName
                FROM cylinder_exchange_info 
                LEFT JOIN customer ON customer.customer_id=cylinder_exchange_info.customer_id
                LEFT JOIN supplier ON supplier.sup_id=cylinder_exchange_info.supplier_id
                LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.exchange_info_id=cylinder_exchange_info.exchange_info_id
                WHERE cylinder_exchange_info.is_active='Y' AND cylinder_exchange_info.is_delete='N' AND  cylinder_exchange_info.exchange_info_id=" . $id;
        $query = $this->db->query($query);
        $result = $query->result();
        /*echo '<pre>';
        print_r($result);
        exit;*/

        $data['inOutView'] = $inOutView = $result;// = $this->Common_model->get_single_data_by_single_column('generals', 'generals_id', $id);
        if (!empty($inOutView->supplier_id)) {
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $inOutView[0]->supplier_id);
        } else {
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $inOutView[0]->customer_id);
        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['inOutItem'] = $this->Common_model->get_data_list_by_single_column('stock', 'generals_id', $id);
        $data['title'] = 'Cylinder In/Out View';
        $data['mainContent'] = $this->load->view($this->folderSub . 'cylinderInOutView', $data, true);
        $this->load->view($this->folder, $data);
    }

    function cylinderInOutJournalEdit($id = null)
    {
        if (isPostBack()) {

            $this->db->trans_start();

            $inactive['is_active'] = 'N';
            $inactive['is_delete'] = 'Y';

            //inactive all data of cylinder_exchange_info
            $this->db->where('exchange_info_id', $id);
            $this->db->update('cylinder_exchange_info', $inactive);
            //inactive all data of cylinder_exchange_details
            $this->db->where('exchange_info_id', $id);
            $this->db->update('cylinder_exchange_details', $inactive);


            $cust = $this->input->post('customer_id');
            $supid = $this->input->post('supplier_id');
            if (!empty($cust)):
                $cylinderData['customer_id'] = $cust;
            endif;
            if (!empty($supid)):
                $cylinderData['supplier_id'] = $supid;
            endif;

            $cylinder_exchange_info['voucher_no'] = $this->input->post('voucherid');
            $cylinder_exchange_info['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $cylinder_exchange_info['narration'] = $this->input->post('narration');
            $cylinder_exchange_info['dist_id'] = $this->dist_id;
            $cylinder_exchange_info['is_active'] = 'Y';
            $cylinder_exchange_info['is_delete'] = 'N';
            $cylinder_exchange_info['update_by'] = $this->admin_id;
            $cylinder_exchange_info['update_date'] = $this->timestamp;
            $this->Common_model->update_data('cylinder_exchange_info', $cylinder_exchange_info, 'exchange_info_id', $id);

            $payType = $this->input->post('payType');


            $productId = $this->input->post('productId');
            $qtyOldAll = array();
            $qtynewAll = array();
            foreach ($productId as $key => $value) :
                unset($qty);
                unset($qtyIn1);
                $qtyOut = $this->input->post('qtyOut')[$key];
                $qtyIn = $this->input->post('qtyIn')[$key];
                $exchange_details_id = $this->input->post('exchange_details_id')[$key];
                if ($exchange_details_id == 0) {
                    //for new
                    $qty['exchange_info_id'] = $id;
                    $qty['product_id'] = $value;
                    $qty['exchange_in'] = $qtyIn;
                    $qty['exchange_out'] = $qtyOut;
                    $qty['dist_id'] = $this->dist_id;
                    if (!empty($cust)):
                        $qty['customer_id'] = $cust;
                    endif;
                    if (!empty($supid)):
                        $qty['supplier_id'] = $supid;
                    endif;
                    $qty['insert_by'] = $this->admin_id;
                    $qty['insert_date'] = $this->timestamp;
                    $qty['is_active'] = 'Y';
                    $qty['is_delete'] = 'N';
                    $qtynewAll[] = $qty;
                } else if ($exchange_details_id != 0) {
                    //for update all active product
                    $qtyIn1['exchange_details_id'] = $exchange_details_id;
                    $qtyIn1['product_id'] = $value;
                    $qtyIn1['exchange_in'] = $qtyIn;
                    $qtyIn1['exchange_out'] = $qtyOut;
                    $qtyIn1['dist_id'] = $this->dist_id;
                    if (!empty($cust)):
                        $qtyIn1['customer_id'] = $cust;
                    endif;
                    if (!empty($supid)):
                        $qtyIn1['supplier_id'] = $supid;
                    endif;
                    $qtyIn1['update_by'] = $this->admin_id;
                    $qtyIn1['update_date'] = $this->timestamp;
                    $qtyIn1['is_active'] = 'Y';
                    $qtyIn1['is_delete'] = 'N';
                    $qtyOldAll[] = $qtyIn1;
                }
            endforeach;

            $this->db->insert_batch('cylinder_exchange_details', $qtynewAll);
            $this->db->update_batch('cylinder_exchange_details', $qtyOldAll, 'exchange_details_id');


            if ($this->db->trans_status() === FALSE):
                $msg = 'Cylinder Exchange ' . ' ' . $this->config->item("update_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url('cylinderInOutJournalEdit/' . $exchange_details_id));
            else:
                $msg = 'Cylinder Exchange ' . ' ' . $this->config->item("update_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url('cylinderInOutJournalView/' . $exchange_details_id));
            endif;


        }

        $query = "SELECT cylinder_exchange_info.exchange_info_id,
                cylinder_exchange_details.exchange_details_id,
                cylinder_exchange_info.voucher_no,
                cylinder_exchange_info.date,
                cylinder_exchange_info.narration,
                cylinder_exchange_info.customer_id,
                cylinder_exchange_info.supplier_id,
                cylinder_exchange_details.exchange_out,
                cylinder_exchange_details.product_id,
                cylinder_exchange_details.exchange_in,
                supplier.supName,
                customer.customerName
                FROM cylinder_exchange_info 
                LEFT JOIN customer ON customer.customer_id=cylinder_exchange_info.customer_id
                LEFT JOIN supplier ON supplier.sup_id=cylinder_exchange_info.supplier_id
                LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.exchange_info_id=cylinder_exchange_info.exchange_info_id
                WHERE cylinder_exchange_info.is_active='Y' AND cylinder_exchange_info.is_delete='N' AND cylinder_exchange_details.is_active='Y' AND cylinder_exchange_details.is_delete='N'AND  cylinder_exchange_info.exchange_info_id=" . $id;
        $query = $this->db->query($query);
        $result = $query->result();


        $data['cylinderInfo'] = $this->Common_model->get_single_data_by_single_column('cylinder_exchange_info', 'exchange_info_id', $id);
        $data['stockInfo'] = $result;
        $data['Cylinder'] = $this->Common_model->getPublicProduct($this->dist_id, 2);


        /*page navbar details*/
        $data['title'] = 'Cylinder Exchange Edit';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Add Exchange';
        $data['link_page_url'] = 'cylinderInOutJournalAdd';
        $data['link_icon'] = $this->link_icon_add;
        /*page navbar details*/


        $data['mainContent'] = $this->load->view($this->folderSub . 'cylinderInOutEdit', $data, true);
        $this->load->view($this->folder, $data);
    }
}