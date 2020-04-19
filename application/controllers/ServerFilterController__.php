<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ServerFilterController extends CI_Controller {

    private $timestamp;
    public $admin_id;
    public $dist_id;

    public function __construct() {
        parent::__construct();
        //$this->load->model('Common_model', 'Finane_Model', 'Inventory_Model', 'Sales_Model');
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('ServerFilterModel', 'Filter_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
    }

    public function brandList() {
        $this->Filter_Model->filterData('brand', array('brandName'), array('brandName'), array('brandName'), $this->dist_id);
        $list = $this->Filter_Model->get_datatables();



        $data = array();
        $no = $_POST['start'];
        foreach ($list as $brands) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $brands->brandName;
            if ($brands->dist_id != 1):
                $row[] = '<a class="blue" href="' . site_url('brandEdit/' . $brands->brandId) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteBrand(' . $brands->brandId . ')">
    <i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all(),
            "recordsFiltered" => $this->Filter_Model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function productCatList() {
        $this->Filter_Model->filterData('productcategory', array('title'), array('title'), array('title'), $this->dist_id);
        $list = $this->Filter_Model->get_productCat_datatables();



        $data = array();
        $no = $_POST['start'];
        foreach ($list as $productCat) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $productCat->title;
            if ($productCat->dist_id != 1):
                $row[] = '<a class="blue inventoryEditPermission" href="' . site_url('updateProductCat/' . $productCat->category_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteProductCategory(' . $productCat->category_id . ')">
    <i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_productCat(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_productCat(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function unitList() {
        $this->Filter_Model->filterData('unit', array('unitTtile', 'code'), array('unitTtile', 'code'), array('unitTtile', 'code'), $this->dist_id);
        $list = $this->Filter_Model->get_datatables_unit();



        $data = array();
        $no = $_POST['start'];
        foreach ($list as $unit) {


            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $unit->unitTtile;
            $row[] = $unit->code;
            if ($unit->dist_id != 1):
                $row[] = '<a class="blue inventoryEditPermission" href="' . site_url('unitEdit/' . $unit->unit_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteUnit(' . $unit->unit_id . ')">
    <i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_unit(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_unit(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function supplierList() {
        $this->Filter_Model->filterData('supplier', array('supID', 'supName', 'supEmail', 'supPhone', 'supAddress', 'status'), array('supID', 'supName', 'supEmail', 'supPhone', 'supAddress', 'status'), array('supID', 'supName', 'supEmail', 'supPhone', 'supAddress', 'status'), $this->dist_id);
        $list = $this->Filter_Model->get_sup_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $supplier) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $supplier->supID;
            $row[] = $supplier->supName;
            $row[] = $supplier->supEmail;
            $row[] = $supplier->supPhone;
            $row[] = $supplier->supAddress;
            if ($supplier->status == 1):
                $row[] = '<a href="javascript:void(0)" onclick="supplierStatusChange(' . $supplier->sup_id . ',2)" class="label label-danger arrowed">
                    <i class="ace-icon fa fa-fire bigger-110"></i>
                    Inactive</a>';
            else:
                $row[] = '<a href="javascript:void(0)" onclick="supplierStatusChange(' . $supplier->sup_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
            endif;
            $row[] = '<a class="blue inventoryEditPermission" href="' . site_url('supplierUpdate/' . $supplier->sup_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteSupplier(' . $supplier->sup_id . ',2)">
                                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                </a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all(),
            "recordsFiltered" => $this->Filter_Model->count_filtered(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }
    public function customerList() {
        $this->Filter_Model->filterData('customer', array('customerID', 'customerName', 'customerPhone', 'customerEmail', 'customerAddress'), array('customerID', 'customerName', 'customerPhone', 'customerEmail', 'customerAddress',), array('customerID', 'customerName', 'customerPhone', 'customerEmail', 'customerAddress',), $this->dist_id);
        $list = $this->Filter_Model->get_cus_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $customer) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $this->Common_model->tableRow('customertype','type_id',$customer->customerType)->typeTitle;
            $row[] = $customer->customerID;
            $row[] = $customer->customerName;
            $row[] = $customer->customerPhone;
            $row[] = $customer->customerEmail;
            $row[] = $customer->customerAddress;

            if ($customer->status == 1):
                $row[] = '<a href="javascript:void(0)" onclick="customerStatusChange(' . $customer->customer_id . ',2)" class="label label-danger arrowed">
                    <i class="ace-icon fa fa-fire bigger-110"></i>
                    Inactive</a>';
            else:
                $row[] = '<a href="javascript:void(0)" onclick="customerStatusChange(' . $customer->customer_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
            endif;
            $row[] = '<a class="blue inventoryEditPermission" href="' . site_url('editCustomer/' . $customer->customer_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteCustomer(' . $customer->customer_id . ',1)">
                                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                </a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_cus(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_cus(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }






    public function productList() {
        $this->Filter_Model->filterData('product', array('productcategory.title', 'brand.brandName', 'product.product_code', 'product.productName', 'product.purchases_price', 'product.retailPrice', 'product.salesPrice', 'product.status'), array('productcategory.title', 'brand.brandName', 'product.product_code', 'product.productName', 'product.purchases_price', 'product.retailPrice', 'product.salesPrice', 'product.status'), array('productcategory.title', 'brand.brandName', 'product.product_code', 'product.productName', 'product.purchases_price', 'product.retailPrice', 'product.salesPrice', 'product.status'), $this->dist_id);
        $list = $this->Filter_Model->get_product_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $products) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $products->title;
            $row[] = $products->brandName;
            $row[] = $products->product_code;
            $row[] = $products->productName .' '. $products->unitTtile;
            $row[] = $products->purchases_price;
            $row[] = $products->retailPrice;
            $row[] = $products->salesPrice;
            if ($products->dist_id != 1):
                if ($products->status == 1):
                    $row[] = '<a href="javascript:void(0)" onclick="productStatusChange(' . $products->product_id . ',2)" class="label label-danger arrowed">
                    <i class="ace-icon fa fa-fire bigger-110"></i>
                    Inactive</a>';
                else:
                    $row[] = '<a href="javascript:void(0)" onclick="productStatusChange(' . $products->product_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
                endif;
            else:
                $row[] = '';
            endif;
            if ($products->dist_id != 1):
                $row[] = '<a class="blue inventoryEditPermission" href="' . site_url('updateProduct/' . $products->product_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteProduct(' . $products->product_id . ',2)">
          <i class="ace-icon fa fa-trash-o bigger-130"></i>
        </a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_product(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_product(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function salesList() {

        $this->Filter_Model->filterData('sales_invoice_info', array('sales_invoice_info.invoice_date', 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount'), array('sales_invoice_info.invoice_date', 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount'), array('sales_invoice_info.invoice_date', 'sales_invoice_info.invoice_no', 'customer.customerID', 'customer.customerName', 'sales_invoice_info.narration', 'sales_invoice_info.paid_amount'), $this->dist_id);
        $list = $this->Filter_Model->get_sales_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($sale->invoice_date));
            $row[] = '<a title="view invoice" href="' . site_url('salesInvoice_view/' . $sale->sales_invoice_id) . '">' . $sale->invoice_no . '</a></td>';
            /*$row[] = $sale->name;*/
            $row[] = '<a title="View Customer Dashboard" href="' . site_url('customerDashboard/' . $sale->customer_id) . '">' . $sale->customerID . ' [ ' . $sale->customerName . ' ] ' . '</a>';
            $row[] = $sale->narration;
            $row[] = number_format((float) $sale->invoice_amount, 2, '.', ',');
            /*$row[] = number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $sale->sales_invoice_id), 2, '.', ',');*/
            $row[] = '<a class="blue" href="' . site_url('salesInvoice_view/' . $sale->sales_invoice_id) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="green saleEditPermission" href="' . site_url('salesInvoice_edit/' . $sale->sales_invoice_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_sales(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_sales(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }
    public function customer_due_collection_list() {

        $this->Filter_Model->filterData('cus_due_collection_info',
            array('cus_due_collection_info.id', 'cus_due_collection_info.date', 'cus_due_collection_info.cus_due_coll_no', 'customer.customerID', 'customer.customerName', 'cus_due_collection_info.payment_type', 'cus_due_collection_info.total_paid_amount'),
            array('cus_due_collection_info.id', 'cus_due_collection_info.date', 'cus_due_collection_info.cus_due_coll_no', 'customer.customerID', 'customer.customerName', 'cus_due_collection_info.payment_type', 'cus_due_collection_info.total_paid_amount'),
            array('cus_due_collection_info.id', 'cus_due_collection_info.date', 'cus_due_collection_info.cus_due_coll_no', 'customer.customerID', 'customer.customerName', 'cus_due_collection_info.payment_type', 'cus_due_collection_info.total_paid_amount'), $this->dist_id);
        $list = $this->Filter_Model->get_customer_due_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sale) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($sale->date));
            $row[] = '<a title="View Customer Dashboard" href="' . site_url('customerDashboard/' . $sale->customer_id) . '">' . $sale->customerID . ' [ ' . $sale->customerName . ' ] ' . '</a>';
            $row[] = $sale->cus_due_coll_no;
            $row[] =   $sale->payment_type==2?' Cash ':' Chaque ' ;
            $row[] = $sale->total_paid_amount;




            $row[] = '<a class="green saleEditPermission" href="' . site_url('customer_due_collection_inv/' . $sale->id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => 1,
            "recordsFiltered" => 1,
            "recordsTotal" => $this->Filter_Model->count_all_sales(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_cus_due_payment(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function purchasesList() {

        $this->Filter_Model->filterData('purchase_invoice_info', array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '), array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount '), array('purchase_invoice_info.invoice_date', 'purchase_invoice_info.invoice_no', 'supplier.supID', 'supplier.supName', 'purchase_invoice_info.narration', 'purchase_invoice_info.paid_amount'), $this->dist_id);
        $list = $this->Filter_Model->get_purchases_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $purchases) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($purchases->invoice_date));
            $row[] = '<a title="view Voucher" href="' . site_url('viewPurchases/' . $purchases->purchase_invoice_id) . '">' . $purchases->invoice_no . '</a></td>';
            $row[] = $purchases->name;
            $row[] = '<a title="View Supplier Dashboard" href="' . site_url('supplierDashboard/' . $purchases->sup_id) . '">' . $purchases->supID . ' [ ' . $purchases->supName . ' ] ' . '</a>';
            $row[] = $purchases->narration;
            $row[] = number_format((float) $purchases->debit, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url('viewPurchases/' . $purchases->purchase_invoice_id) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="green inventoryEditPermission" href="' . site_url('purchases_edit/' . $purchases->purchase_invoice_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_purchases(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_purchases(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function paymentList() {

        $this->Filter_Model->filterData('generals', array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), $this->dist_id);
        $list = $this->Filter_Model->get_payment_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $payment) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($payment->date));
            $row[] = '<a title="View Payment Voucher" href="' . site_url('paymentVoucherView/' . $payment->generals_id) . '">' . $payment->voucher_no . '</a></td>';
            $row[] = $payment->name;

            if (!empty($payment->customer_id)) {
                $row[] = '<a title="View Customer  Dashboard" href="' . site_url('customerDashboard/' . $payment->customer_id) . '">' . $payment->customerID . ' [ ' . $payment->customerName . ' ] ' . '</a>';
            } else if (!empty($payment->supplier_id)) {
                $row[] = '<a title="View Supplier  Dashboard" href="' . site_url('supplierDashboard/' . $payment->supplier_id) . '">' . $payment->supID . ' [ ' . $payment->supName . ' ] ' . '</a>';
            } else {
                $row[] = $payment->miscellaneous;
            }

            $row[] = $payment->narration;
            $row[] = number_format((float) $payment->debit, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url('paymentVoucherView/' . $payment->generals_id) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="green financeEditPermission" href="' . site_url('paymentVoucherEdit/' . $payment->generals_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_payment(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_payment(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function receiveList() {

        $this->Filter_Model->filterData('generals', array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), $this->dist_id);
        $list = $this->Filter_Model->get_receive_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $receive) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($receive->date));
            $row[] = '<a title="View Payment Voucher" href="' . site_url('paymentVoucherView/' . $receive->generals_id) . '">' . $receive->voucher_no . '</a></td>';
            $row[] = $receive->name;

            if (!empty($receive->customer_id)) {
                $row[] = '<a title="View Customer  Dashboard" href="' . site_url('customerDashboard/' . $receive->customer_id) . '">' . $receive->customerID . ' [ ' . $receive->customerName . ' ] ' . '</a>';
            } else if (!empty($receive->supplier_id)) {
                $row[] = '<a title="View Supplier  Dashboard" href="' . site_url('supplierDashboard/' . $receive->supplier_id) . '">' . $receive->supID . ' [ ' . $receive->supName . ' ] ' . '</a>';
            } else {
                $row[] = $receive->miscellaneous;
            }

            $row[] = $receive->narration;
            $row[] = number_format((float) $receive->debit, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url('receiveVoucherView/' . $receive->generals_id) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="green financeEditPermission" href="' . site_url('receiveVoucherEdit/' . $receive->generals_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_receive(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_receive(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function journalList() {

        $this->Filter_Model->filterData('generals', array('generals.date', 'generals.voucher_no', 'form.name', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'generals.narration', 'generals.debit'), $this->dist_id);
        $list = $this->Filter_Model->get_journal_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $journal) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($journal->date));
            $row[] = '<a title="View Payment Voucher" href="' . site_url('paymentVoucherView/' . $journal->generals_id) . '">' . $journal->voucher_no . '</a></td>';
            $row[] = $journal->name;
            $row[] = $journal->narration;
            $row[] = number_format((float) $journal->debit, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url('journalVoucherView/' . $journal->generals_id) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="green financeEditPermission" href="' . site_url('journalVoucherEdit/' . $journal->generals_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_journal(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_journal(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function billVoucherList() {

        $this->Filter_Model->filterData('generals', array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), array('generals.date', 'generals.voucher_no', 'form.name', 'supplier.supID', 'supplier.supName', 'customer.customerID', 'customer.customerName', 'generals.narration', 'generals.debit'), $this->dist_id);
        $list = $this->Filter_Model->get_bill_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $bill) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($bill->date));
            $row[] = '<a title="View Payment Voucher" href="' . site_url('paymentVoucherView/' . $bill->generals_id) . '">' . $bill->voucher_no . '</a></td>';
            $row[] = $bill->name;

            if (!empty($bill->customer_id)) {
                $row[] = '<a title="View Customer  Dashboard" href="' . site_url('customerDashboard/' . $bill->customer_id) . '">' . $bill->customerID . ' [ ' . $bill->customerName . ' ] ' . '</a>';
            } else if (!empty($bill->supplier_id)) {
                $row[] = '<a title="View Supplier  Dashboard" href="' . site_url('supplierDashboard/' . $bill->supplier_id) . '">' . $bill->supID . ' [ ' . $bill->supName . ' ] ' . '</a>';
            } else {
                $row[] = $bill->miscellaneous;
            }
            $row[] = $bill->narration;
            $row[] = number_format((float) $bill->debit, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url('billInvoice_view/' . $bill->generals_id) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>
    <a class="green financeEditPermission" href="' . site_url('billInvoice_edit/' . $bill->generals_id) . '">
    <i class="ace-icon fa fa-pencil bigger-130"></i></a>
    ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_bill(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_bill(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function cusPayList() {

        $this->Filter_Model->filterData('moneyreceit', array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'), $this->dist_id);
        $list = $this->Filter_Model->get_cusPay_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $cusPay) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($cusPay->date));
            $row[] = '<a title="View Money Receit" href="' . site_url('viewMoneryReceipt/' . $cusPay->moneyReceitid) . '">' . $cusPay->receitID . '</a></td>';

            $row[] = '<a title="View Customer  Dashboard" href="' . site_url('customerDashboard/' . $cusPay->customer_id) . '">' . $cusPay->customerID . ' [ ' . $cusPay->customerName . ' ] ' . '</a>';

            if ($cusPay->paymentType == 1):
                $row[] = "Cash";
            else:
                if ($cusPay->checkStatus == 1):
                    $row[] = '<p style="color:red;"> Bank &nbsp; <i class="fa fa-refresh fa-spin fa-fw"></i></p>';
                else:
                    $row[] = '<p style="color:green;"> Bank &nbsp;<i class="fa fa-check"></i></p>';
                endif;
            endif;
            $row[] = number_format((float) $cusPay->totalPayment, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url('viewMoneryReceipt/' . $cusPay->moneyReceitid) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_cusPay(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_cusPay(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }
    public function supPayList() {

        $this->Filter_Model->filterData('moneyreceit', array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'supplier.supID', 'supplier.supName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'supplier.supID', 'supplier.supName'), array('moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid', 'moneyreceit.totalPayment', 'moneyreceit.checkStatus', 'moneyreceit.paymentType', 'supplier.supID', 'supplier.supName'), $this->dist_id);
        $list = $this->Filter_Model->get_supPay_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $supPay) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('M d, Y', strtotime($supPay->date));
            $row[] = '<a title="View Money Receit" href="' . site_url('viewMoneryReceiptSup/' . $supPay->moneyReceitid) . '">' . $supPay->receitID . '</a></td>';

            $row[] = '<a title="View Supplier  Dashboard" href="' . site_url('supplierDashboard/' . $supPay->sup_id) . '">' . $supPay->supID . ' [ ' . $supPay->supName . ' ] ' . '</a>';

            if ($supPay->paymentType == 1):
                $row[] = "Cash";
            else:
                if ($supPay->checkStatus == 1):
                    $row[] = '<p style="color:red;"> Bank &nbsp; <i class="fa fa-refresh fa-spin fa-fw"></i></p>';
                else:
                    $row[] = '<p style="color:green;"> Bank &nbsp;<i class="fa fa-check"></i></p>';
                endif;
            endif;
            $row[] = number_format((float) $supPay->totalPayment, 2, '.', ',');
            $row[] = '<a class="blue" href="' . site_url('viewMoneryReceiptSup/' . $supPay->moneyReceitid) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_supPay(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_supPay(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }
    public function productTypeList() {
        $this->Filter_Model->filterData('product_type', array('product_type.product_type_name'), array('product_type.product_type_name'), array('product_type.product_type_name'), $this->dist_id);
        $list = $this->Filter_Model->get_product_type_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $products) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $products->product_type_name;
            
            if ($products->dist_id != 1):
                if ($products->is_active == "Y"):
                    $row[] = '<a href="javascript:void(0)" onclick="productTypeStatusChange(' . $products->product_type_id . ',0)" class="label label-danger arrowed">
                    <i class="ace-icon fa fa-fire bigger-110"></i>
                    Inactive</a>';
                else:
                    $row[] = '<a href="javascript:void(0)" onclick="productTypeStatusChange(' . $products->product_type_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
                endif;
            else:
                $row[] = '';
            endif;
            if ($products->dist_id != 1):
                $row[] = '<a class="blue inventoryEditPermission" href="' . site_url('editproductType/' . $products->product_type_id) . '">
                <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteProductType(' . $products->product_type_id . ',2)">
                  <i class="ace-icon fa fa-trash-o bigger-130"></i>
                </a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_productType(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_product_type(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }
    public function productPackageList() {
        $this->Filter_Model->filterData('package', array('package.package_name'), array('package.package_name'), array('package.package_name'), $this->dist_id);
        $list = $this->Filter_Model->get_product_package_datatables();
        //$list = $this->Filter_Model->get_product_type_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $products) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $products->package_name;

            if ($products->dist_id != 1):
                if ($products->is_active == "Y"):
                    $row[] = '<a href="javascript:void(0)" onclick="productPackageStatusChange(' . $products->package_id . ',0)" class="label label-danger arrowed">
                    <i class="ace-icon fa fa-fire bigger-110"></i>
                    Inactive</a>';
                else:
                    $row[] = '<a href="javascript:void(0)" onclick="productPackageStatusChange(' . $products->package_id . ',1)" class="label label-success arrowed">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Active
                </a>';
                endif;
            else:
                $row[] = '';
            endif;
            if ($products->dist_id != 1):
                $row[] = '<a class="inventoryEditPermission" href="' . site_url('productPackageView/' . $products->package_id) . '">
                        <i class="ace-icon fa fa-search-plus bigger-130"></i> 
                    </a>
                    <a class="blue inventoryEditPermission" href="' . site_url('productPackageEdit/' . $products->package_id) . '">
                <i class="ace-icon fa fa-pencil bigger-130"></i></a>
                <a class="red inventoryDeletePermission" href="javascript:void(0)" onclick="deleteProductpackage(' . $products->package_id . ',2)">
                  <i class="ace-icon fa fa-trash-o bigger-130"></i>
                </a>';
            else:
                $row[] = '';
            endif;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_productPackage(),
            //"recordsTotal" => $this->Filter_Model->count_all_productType(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_product_package(),
            //"recordsFiltered" => $this->Filter_Model->count_filtered_product_type(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }
    

}
