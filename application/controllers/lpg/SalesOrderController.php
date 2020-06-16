<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 6/13/2020
 * Time: 10:02 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesOrderController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $link_icon_add;
    public $link_icon_list;
    public $link_icon_view;
    public $TypeDR;
    public $TypeCR;
    public $salesEmptyCylinderWithRefill;
    public $CostOfEmptyCylinderWithRefill;
    public $discountOnSales;


    public $business_type;
    public $folder;
    public $folderSub;

    public $db_hostname;
    public $db_username;
    public $db_password;
    public $db_name;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $this->page_type = 'Sales';

        $this->link_icon_add = "<i class='fa fa-plus'></i>";
        $this->link_icon_list = "<i class='fa fa-list'></i>";
        $this->link_icon_view = "<i class='fa fa-view'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->business_type = $this->session->userdata('business_type');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
        $this->TypeDR = 1;
        $this->TypeCR = 2;
        $this->discountOnSales = $this->config->item("Discount");;

        if ($this->project == 'farabitraders') {
            $this->salesEmptyCylinderWithRefill = 618;
            $this->CostOfEmptyCylinderWithRefill = 617;
        } else if ($this->project == 'rftraders') {
            $this->salesEmptyCylinderWithRefill = 508;
            $this->CostOfEmptyCylinderWithRefill = 509;
        } else if ($this->project == 'tuhinEnterprise') {
            $this->discountOnSales = 338;
        } else if ($this->project == 'msak_enterprise') {
            $this->discountOnSales = 478;
        } else if ($this->project == 'rajTraders') {
            $this->discountOnSales = 762;
        } else {
            $this->salesEmptyCylinderWithRefill = $this->config->item("salesEmptyCylinderWithRefill");
            $this->CostOfEmptyCylinderWithRefill = $this->config->item("CostOfEmptyCylinderWithRefill");
        }


        if ($this->business_type != "LPG") {
            $this->folder = 'distributor/masterTemplateSmeMobile';

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        } else {
            $this->folder = 'distributor/masterTemplate';
        }


        $this->folderSub = 'distributor/inventory/brand/';
    }

    public function sales_order_add()
    {

        $this->load->helper('sales_invoice_no_helper');
        $this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {


            $this->form_validation->set_rules('so_date', 'So Date', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('slNo[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/sales_order/'));
            } else {


                $this->db->trans_start();

                $branch_id = $this->input->post('branch_id');
                $customer_id = $this->input->post('customer_id');
                $so_date = $this->input->post('so_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_date'))) : 'NULL';
                $delivery_date = $this->input->post('so_delivery_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_delivery_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shippingAddress = $this->input->post('shippingAddress');


                $all_so_product = array();
                $invoice_no = create_sales_invoice_no();

                $sales_so['form_id'] = $customer_id;
                $sales_so['so_po_no'] = $invoice_no;
                $sales_so['customer_id'] = $customer_id;
                $sales_so['supplier_id'] = 0;

                $sales_so['refference_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_so['shipping_address'] = $shippingAddress;
                $sales_so['delivery_date'] = $delivery_date;
                $sales_so['so_po_date'] = $so_date;
                $sales_so['refference_person_id'] = $this->input->post('reference');
                $sales_so['narration'] = $narration;
                $sales_so['status'] = 0;//1->complete
                $sales_so['company_id'] = $this->dist_id;

                $sales_so['branch_id'] = $branch_id;
                $sales_so['insert_date'] = $this->timestamp;
                $sales_so['insert_by'] = $this->admin_id;
                $sales_so['update_by'] = '';
                $sales_so['update_date'] = '';
                $sales_so['delete_by'] = '';
                $sales_so['delete_date'] = '';
                $sales_so['is_active'] = 'Y';
                $sales_so['is_delete'] = 'N';


                $so_id = $this->Common_model->insert_data('so_po_info', $sales_so);


                foreach ($_POST['slNo'] as $key => $value) {
                    $so_product = array();
                    $product_id = $_POST['product_id_' . $value];
                    $quantity = $_POST['quantity_' . $value];
                    $unit_price = $_POST['rate_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];

                    $so_product['so_po_id'] = $so_id;
                    $so_product['customer_id'] = $customer_id;
                    $so_product['supplier_id'] = 0;
                    $so_product['form_id'] = $customer_id;
                    $so_product['delivery_date'] = $delivery_date;
                    $so_product['so_po_date'] = $so_date;
                    $so_product['product_id'] = $product_id;
                    $so_product['so_po_qty'] = $quantity;
                    $so_product['so_po_unit_price'] = $unit_price;
                    $so_product['so_po_approve_qty'] = 0;
                    $so_product['status'] = 0;//1->complete
                    $so_product['branch_id'] = $branch_id;
                    $so_product['property_1'] = $property_1;
                    $so_product['property_2'] = $property_2;
                    $so_product['property_3'] = $property_3;
                    $so_product['property_4'] = $property_4;
                    $so_product['property_5'] = $property_5;
                    $so_product['insert_date'] = $this->timestamp;
                    $so_product['insert_by'] = $this->admin_id;
                    $so_product['update_by'] = '';
                    $so_product['update_date'] = '';
                    $so_product['delete_by'] = '';
                    $so_product['delete_date'] = '';
                    $so_product['is_active'] = 'Y';
                    $so_product['is_delete'] = 'N';


                    $all_so_product[] = $so_product;


                }
                if (!empty($all_so_product)) {
                    $this->Common_model->insert_batch_save('so_po_productes', $all_so_product);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Sales Order ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/sales_order/'));
                } else {
                    $msg = 'Sales Order  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/sales_order/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }

        $data = array();


        $data['title'] = get_phrase('slaes_order_add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['second_link_page_name'] = get_phrase('sales_order_list');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;


        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_add', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);
    }

    public function sales_order_list()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('sales_order_list');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sale_Order');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = "<i class='ace-icon fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_list', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function get_product_list_by_dist_id()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            if (isset($_GET['receiveStatus'])) {
                $status = strtolower($_GET['receiveStatus']);
            } else {
                $status = 0;
            }
            echo json_encode($this->Common_model->get_product_list_for_auto_complete($this->dist_id, $q, $status));
        }
    }

    public function so_po_view($id)
    {
        $data['title'] = get_phrase('Sale Order View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sales_Order');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Sales Order List');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('Sale_Order_Edit');
        $data['third_link_page_url'] = $this->project . '/sales_order_edit/' . $id;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';

        $data['so_po_info'] = $this->Common_model->get_single_data_by_single_column('so_po_info', 'id', $id);
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);

        $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['so_po_info']->customer_id);


        $this->db->select('
        
        so_po_productes.so_po_qty,
        so_po_productes.so_po_unit_price,
        so_po_productes.so_po_approve_qty,
        so_po_productes.status,
        product.product_id,productcategory.title as productCat,
        product.brand_id,product.category_id,product.productName,
        product.dist_id,product.status,
        brand.brandName,
        unit.unitTtile,
        unit.unit_id,
        tb_subcategory.SubCatName,
        tb_model.Model,
        tb_color.Color,
        so_po_productes.property_1,
        so_po_productes.property_2,
        so_po_productes.property_3,
        so_po_productes.property_4,
        so_po_productes.property_5,
        product.salesPrice,
        tb_size.Size');
        $this->db->from('so_po_productes');
        $this->db->join('product', 'so_po_productes.product_id = product.product_id', 'left');
        $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
        $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->join('tb_subcategory', 'tb_subcategory.SubCatID = product.subcategoryID', 'left');
        $this->db->join('tb_model', 'tb_model.ModelID = product.modelID', 'left');
        $this->db->join('tb_color', 'tb_color.ColorID = product.colorID', 'left');
        $this->db->join('tb_size', 'tb_size.SizeID = product.SizeID', 'left');


        $this->db->where('so_po_productes.so_po_id', $id);


        $data['so_po_details']   = $this->db->get()->result_array();



        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_view', $data, true);
        $this->load->view('distributor/masterTemplate', $data);


    }



    public function sales_order_edit($id)
    {

        $this->load->helper('sales_invoice_no_helper');
        $this->load->helper('branch_dropdown_helper');
        if (isPostBack()) {


            $this->form_validation->set_rules('so_date', 'So Date', 'required');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required');
            $this->form_validation->set_rules('branch_id', 'Branch  ID', 'required');
            $this->form_validation->set_rules('slNo[]', 'Voucehr ID', 'required');
            $this->form_validation->set_rules('price[]', 'Product Price', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/sales_order/'));
            } else {


                $this->db->trans_start();

                $branch_id = $this->input->post('branch_id');
                $customer_id = $this->input->post('customer_id');
                $so_date = $this->input->post('so_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_date'))) : 'NULL';
                $delivery_date = $this->input->post('so_delivery_date') != '' ? date('Y-m-d', strtotime($this->input->post('so_delivery_date'))) : 'NULL';
                $narration = $this->input->post('narration');
                $shippingAddress = $this->input->post('shippingAddress');


                $all_so_product = array();
                $invoice_no = create_sales_invoice_no();

                $sales_so['form_id'] = $customer_id;
                $sales_so['so_po_no'] = $invoice_no;
                $sales_so['customer_id'] = $customer_id;
                $sales_so['supplier_id'] = 0;

                $sales_so['refference_invoice_no'] = $this->input->post('userInvoiceId');
                $sales_so['shipping_address'] = $shippingAddress;
                $sales_so['delivery_date'] = $delivery_date;
                $sales_so['so_po_date'] = $so_date;
                $sales_so['refference_person_id'] = $this->input->post('reference');
                $sales_so['narration'] = $narration;
                $sales_so['status'] = 0;//1->complete
                $sales_so['company_id'] = $this->dist_id;

                $sales_so['branch_id'] = $branch_id;
                $sales_so['insert_date'] = $this->timestamp;
                $sales_so['insert_by'] = $this->admin_id;
                $sales_so['update_by'] = '';
                $sales_so['update_date'] = '';
                $sales_so['delete_by'] = '';
                $sales_so['delete_date'] = '';
                $sales_so['is_active'] = 'Y';
                $sales_so['is_delete'] = 'N';


                $so_id = $this->Common_model->insert_data('so_po_info', $sales_so);


                foreach ($_POST['slNo'] as $key => $value) {
                    $so_product = array();
                    $product_id = $_POST['product_id_' . $value];
                    $quantity = $_POST['quantity_' . $value];
                    $unit_price = $_POST['rate_' . $value];
                    $property_1 = $_POST['property_1_' . $value];
                    $property_2 = $_POST['property_2_' . $value];
                    $property_3 = $_POST['property_3_' . $value];
                    $property_4 = $_POST['property_4_' . $value];
                    $property_5 = $_POST['property_5_' . $value];

                    $so_product['so_po_id'] = $so_id;
                    $so_product['customer_id'] = $customer_id;
                    $so_product['supplier_id'] = 0;
                    $so_product['form_id'] = $customer_id;
                    $so_product['delivery_date'] = $delivery_date;
                    $so_product['so_po_date'] = $so_date;
                    $so_product['product_id'] = $product_id;
                    $so_product['so_po_qty'] = $quantity;
                    $so_product['so_po_unit_price'] = $unit_price;
                    $so_product['so_po_approve_qty'] = 0;
                    $so_product['status'] = 0;//1->complete
                    $so_product['branch_id'] = $branch_id;
                    $so_product['property_1'] = $property_1;
                    $so_product['property_2'] = $property_2;
                    $so_product['property_3'] = $property_3;
                    $so_product['property_4'] = $property_4;
                    $so_product['property_5'] = $property_5;
                    $so_product['insert_date'] = $this->timestamp;
                    $so_product['insert_by'] = $this->admin_id;
                    $so_product['update_by'] = '';
                    $so_product['update_date'] = '';
                    $so_product['delete_by'] = '';
                    $so_product['delete_date'] = '';
                    $so_product['is_active'] = 'Y';
                    $so_product['is_delete'] = 'N';


                    $all_so_product[] = $so_product;


                }
                if (!empty($all_so_product)) {
                    $this->Common_model->insert_batch_save('so_po_productes', $all_so_product);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Sales Order ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/sales_order/'));
                } else {
                    $msg = 'Sales Order  ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/sales_order/'));
                    //redirect(site_url($this->project . '/viewLpgCylinder/' . $this->invoice_id));
                }
            }
        }

        $data = array();


        $data['title'] = get_phrase('slaes_order_edit');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('New_Sales_Order');
        $data['link_page_url'] = $this->project . '/sales_order';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('sales_order_list');
        $data['second_link_page_url'] = $this->project . '/sales_order_list';
        $data['second_link_icon'] = $this->link_icon_list;


        $data['customerList'] = $this->Sales_Model->getCustomerList($this->dist_id);
        $data['referenceList'] = $this->Common_model->get_data_list_by_single_column('reference', 'dist_id', $this->dist_id);
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/sales_order/sales_order_add', $data, true);
        $this->load->view("distributor/masterTemplateSmeMobile", $data);
    }
}