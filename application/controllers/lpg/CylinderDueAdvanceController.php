<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 12/26/2019
 * Time: 11:40 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class CylinderDueAdvanceController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    private $admin_name;
    public $dist_id;
    public $page_type;
    public $dataRow = array();
    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->admin_name = $this->session->userdata('username');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'Inventory';
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
    function customer_cylinder_due_advance()
    {
        if (isPostBack()) {

            $this->form_validation->set_rules('paymentDate', 'Sales Date', 'required');
            //$this->form_validation->set_rules('sales_details_id_main[]', 'Sales Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/customerCylinderDueAdvance/'));
            } else {
                $sales_details_id_main=$this->input->post('sales_details_id_main');

                $moneyReceitNo = $this->db->where(array('1' => 1))->count_all_results('cus_empty_cylinder_advance_recive') + 1;
                $ReceitVoucher = "CDR" . date("y") . date("m") . str_pad($moneyReceitNo, 4, "0", STR_PAD_LEFT);
                $due_collection_info['customer_id'] = $this->input->post('customerid');
                $due_collection_info['cus_cylinder_due_coll_no'] = $ReceitVoucher;
                $due_collection_info['date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                $due_collection_info['insert_date'] = $this->timestamp;
                $due_collection_info['insert_by'] = $this->admin_id;
                $due_collection_info['is_active'] = 'Y';
                $due_collection_info['is_delete'] = 'N';
                $cus_due_collection_info_id = $this->Common_model->insert_data('cus_empty_cylinder_advance_recive', $due_collection_info);

                foreach ($sales_details_id_main as $a => $b) {
                    $amount = $this->input->post('returnedProductPrice[' . $a . ']');
                    if (!empty($amount)) {

                        $due_collection['sales_invoice_id'] = $this->input->post('sales_invoice_id[' . $a . ']');
                        $due_collection['sales_details_id'] = $this->input->post('sales_details_id[' . $a . ']');
                        $due_collection['cus_due_advancerecive_info_id'] = $cus_due_collection_info_id;
                        $due_collection['customer_id'] = $this->input->post('customerid');
                        $due_collection['quentity'] = $_POST['ReturnedQty'][$a];
                        $due_collection['product_id'] = $_POST['returned_cylinder_id'][$a];
                        $due_collection['insert_date'] = $this->timestamp;
                        $due_collection['date'] = date('Y-m-d', strtotime($this->input->post('paymentDate')));
                        $due_collection['insert_by'] = $this->admin_id;
                        $due_collection['is_active'] = 'Y';
                        $due_collection['is_delete'] = 'N';
                        $allStock[] = $due_collection;

                    }
                }

            }

            echo "<pre>";
            print_r($allStock);
            exit;
        }

        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['title'] = get_phrase('Customer Empty Cylinder Recive');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('List');
        $data['link_page_url'] = $this->project . '/salesInvoiceLpg';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        $data['mainContent'] = $this->load->view('distributor/sales/customerCylinderDueAdvance/customer_due_cylinder_receive', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function customer_due_cylinder_ajax()
    {
        $dueAmount = 0;
        $customer = $this->input->post('customer');
        $BranchAutoId = $this->input->post('BranchAutoId');
        $result = '<table class="table table-bordered table-hover">';
        $result .= '<thead><tr><td align="center"><strong>Voucher No.</strong></td><td align="center"><strong>Date</strong></td><td align="center"><strong>Due Cylinder</strong></td><td align="center"><strong>Due Qty.</strong></td><td align="center"><strong>Returned Cylinder</strong></td><td align="center"><strong>Returned Qty</strong></td><td align="center"><strong>Price</strong></td><td align="center"><strong>Total Price</strong></td><td align="center"><strong>Action</strong></td></tr></thead>';
        $result .= '<tbody>';
        $query = $this->Sales_Model->customer_cylinder_due_recive($customer,$BranchAutoId);
        log_message('error','cylinder due :'.print_r($query,true));
        //log_message('error','cylinder due :'.print_r($this->db->last_query(),true));
        $cylinderProduct=$this->Common_model->getPublicProduct($this->dist_id, 1);




        foreach ($query['invoice_list'] as $key => $row) {

            $packageEmptyProductId = $this->getPackageEmptyProductId($row['product_id']);




            //if(){}

            $select='<select id="productID2_'.$row['sales_details_id'].'" class="chosen-select form-control received_cylilder_id"   onchange="received_cylilder_price(' . $row['sales_details_id'] . ')"
                                data-placeholder="Select  product ">
                            <option value="">Select</option>';
            foreach ($cylinderProduct as $eachProduct):
                if ($eachProduct->category_id == 1):
                    $selected='';
                    if($packageEmptyProductId==$eachProduct->product_id){
                        $selected='selected';
                    }
                    $select.=' <option categoryName2='. $eachProduct->productCat .' brand_id='. $eachProduct->brand_id .'  productName2="'. $eachProduct->productName . ' ' . $eachProduct->unitTtile . ' [ ' . $eachProduct->brandName . ' ]" '.' value="'.$eachProduct->product_id.'"'.$selected.' >'.$eachProduct->productName . ' ' . $eachProduct->unitTtile . ' [ ' . $eachProduct->brandName . '] '.' </option>';
                endif;
            endforeach;
            $select.='</select>';


            $dueAmount = 0;
            $result .= '<tr id="trID_'.$row['sales_details_id'].'">';
            $result .= '<td class="text-center"><a href="' . site_url($this->project . '/viewLpgCylinder/' . $row['sales_invoice_id']) . '">' . $row['invoice_no'] . '<input type="hidden" name="voucher[]" value="' . $row['invoice_no'] . '"></a></td>';
            $result .= '<td class="text-center">' . date('d.m.Y', strtotime($row['invoice_date'])) . '</td>';
            $result .= '<td class="text-right">' . $row['title'].' '.$row['productName'] .' '.$row['brandName']. '</td>';
            $result .= '<td align="right"><input type="hidden" value="' . $row['customer_due'] . '" id="dueAmount_' . $row['sales_details_id'] . '">' . $row['customer_due']. '</td>';
            $result .= '<td class="text-center">'.$select.'</td>';
            $result .= '<td class="text-right"><input type="hidden" name="invoiceID[]" id="invoice_id_' . $row['sales_details_id'] . '" class="invoice_id" value="' . $row['sales_invoice_id'] . '"/><input id="ReturnedQty_' .$row['sales_details_id']. '"    type="text" class="form-control amount" value="'.$row['customer_due'].'"  placeholder="0.00" autocomplete="off" onclick="this.select();"></td>';
            $result .= '<td class="text-right"><input id="returnedProductPrice_' . $row['sales_details_id'] . '" type="text"    class="form-control amount"   placeholder="0.00" autocomplete="off" onclick="this.select();"></td>';
            $result .= '<td class="text-right"><input id="total_price_' . $row['sales_details_id'] . '" type="text"   type="text" class="form-control amount" name="amount[]"  placeholder="0.00" autocomplete="off" onclick="this.select();"></td>';
            $result .= '<td class="text-right"><a id="add_item" class="btn blue form-control" href="javascript:void(0);" onclick="add_cylinder(' . $row['sales_details_id'] . ')"  title="Add Item">
                            <i class="fa fa-plus" style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;
                        </a></td>';
            $result .= '</tr>';
        }
        $result .= '<tr>';
        $result .= '<td colspan="3"></td>';
        $result .= '<td align="right" ><span style="color:red;">' . number_format((float)$dueAmount, 2, '.', ',') . '</span></strong></td>';
        $result .= '<td><input type="text" class="form-control ttl_amount required" name="ttl_amount" placeholder="0.00" readonly="readonly"></td>';
        $result .= '</tr>';
        $result .= '</tbody></table>';
        $result .= '<script type="text/javascript">';
        $result .= "$(document).ready(function(){ $('.amount').change(function(){ ttl_amount=0;var thisValue = 0;if ($(this).val() != '') {thisValue = $(this).val();} $.each($('.amount'), function(){ aamount = $(this).val(); aamount=Number(aamount); ttl_amount+=aamount; }); $(this).val(parseFloat(thisValue).toFixed(2)); $('.ttl_amount').val(parseFloat(ttl_amount).toFixed(2)); }); $('.chosen-select').chosen();});";
        $result .= '</script>';
        //log_message('error','this is a massage from inventory model'.print_r($result,true));
        echo $result;





    }

    function getPackageEmptyProductId($RefillproductId)
    {
        $this->db->select("package_products.*");
        $this->db->from("package_products");
        $this->db->where('package_products.product_id', $RefillproductId);
        $package = $this->db->get()->row();
        $this->db->select("package_products.*");
        $this->db->from("package_products");
        $this->db->join('product', 'product.product_id = package_products.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->where('package_products.package_id', $package->package_id);
        $this->db->where('productcategory.category_id', 1);
        $package_empty_product = $this->db->get()->row();
        return $package_empty_product->product_id;
    }
}