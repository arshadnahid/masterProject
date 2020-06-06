<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ServerFilterModel extends CI_Model
{

    public $table;
    public $column_order; //set column field database for datatable orderable
    public $column_search; //set column field database for datatable searchable
    public $order; // default order
    public $join1; // default order
    public $join2; // default order
    public $dist_id;

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function filterData($table, $column_order, $coumn_search, $order, $distId)
    {
        $this->table = $table;
        $this->column_order = $column_order;
        $this->column_search = $coumn_search;
        $this->order = $order;
        $this->dist_id = $distId;
    }

    function get_payment_datatables()
    {
        $this->_get_payment_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        $this->db->or_where('dist_id', 1);
        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function _get_productCat_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->where('dist_id', $this->dist_id);
        $this->db->or_where('dist_id', 1);
        $this->db->group_end();
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function _get_datatables_unit_query()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        $this->db->or_where('dist_id', 1);
        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_productBrand_datatables()
    {
        $this->_get_productBrand_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_productBrand_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->where('dist_id', $this->dist_id);
        $this->db->or_where('dist_id', 1);
        $this->db->group_end();
        //$this->db->where('is_active', 'Y');
        //$this->db->where('is_delete', 'N');
        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function _get_product_datatables_query()
    {
        $this->db->select("productcategory.title,brand.brandName,product.product_code,product.productName,product.purchases_price,product.salesPrice,product.retailPrice,product.status,product.dist_id,product.product_id,unit.unitTtile");
        $this->db->from($this->table);
        $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
        $this->db->group_start();
        $this->db->where('product.dist_id', $this->dist_id);
        $this->db->or_where('product.dist_id', 1);
        $this->db->group_end();
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('product.productName', 'ASC');
            $this->db->order_by('productcategory.title', 'ASC');
            $this->db->order_by('brand.brandName', 'ASC');

        }
    }

    /*private function _get_sales_datatables_query() {
        $this->db->select("form.name,generals.generals_id,generals.voucher_no,generals.narration,generals.date,generals.debit,customer.customerID,customer.customerName,customer.customer_id");
        $this->db->from("generals");
        $this->db->join('customer', 'customer.customer_id=generals.customer_id');
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->where('generals.dist_id', $this->dist_id);
        $this->db->where('generals.form_id', 5);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('generals.date', 'desc');
        }
    }*/

    private function _get_sales_datatables_query()
    {
        $this->db->select("sales_invoice_info.sales_invoice_id,
        sales_invoice_info.invoice_no,
        sales_invoice_info.payment_type,
        sales_invoice_info.narration,
        DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') as invoice_date,
        sales_invoice_info.invoice_amount,
        sales_invoice_info.paid_amount,
        customer.customerID,
        customer.customerName,
        customer.customer_id,
        GROUP_CONCAT(sales_details.property_1 SEPARATOR ', ') as property_1,
        GROUP_CONCAT(sales_details.property_2 SEPARATOR ', ') as property_2,
        GROUP_CONCAT(sales_details.property_3 SEPARATOR ', ') as property_3,
        GROUP_CONCAT(sales_details.property_4 SEPARATOR ', ') as property_4,
        GROUP_CONCAT(sales_details.property_5 SEPARATOR ', ')as property_5
       
        ");
        $this->db->from("sales_invoice_info");
        $this->db->join('customer', 'customer.customer_id=sales_invoice_info.customer_id');
        $this->db->join('sales_details', 'sales_details.sales_invoice_id=sales_invoice_info.sales_invoice_id');

        // $this->db->where('sales_invoice_info.dist_id', $this->dist_id);
        $this->db->where('sales_invoice_info.is_active', 'Y');
        $this->db->where('sales_invoice_info.invoice_for !=', '3');

        if (!empty($this->input->post('property_1'))) {
            $this->db->like('sales_details.property_1', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_2'))) {
            $this->db->like('sales_details.property_2', $this->input->post('property_2'), 'both');
        }
        if (!empty($this->input->post('property_3'))) {
            $this->db->like('sales_details.property_3', $this->input->post('property_3'), 'both');
        }
        $this->db->group_by("sales_invoice_info.sales_invoice_id");
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('sales_invoice_info.sales_invoice_id', 'desc');
            $this->db->order_by('sales_invoice_info.invoice_date', 'desc');
        }
    }

    function get_sales_return_datatables()
    {
        $this->_get_sales_return_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _get_sales_return_datatables_query()
    {
        $this->db->select("return_info.id,
        return_info.return_invoice_no,
      
        return_info.narration,
        DATE_FORMAT(return_info.return_date, '%b %e, %Y') as invoice_date,
        SUM(stock.quantity*stock.price) as amount ,
      
        customer.customerID,
        customer.customerName,
        customer.customer_id,
        GROUP_CONCAT(stock.property_1 SEPARATOR ', ') as property_1,
        GROUP_CONCAT(stock.property_2 SEPARATOR ', ') as property_2,
        GROUP_CONCAT(stock.property_3 SEPARATOR ', ') as property_3,
        GROUP_CONCAT(stock.property_4 SEPARATOR ', ') as property_4,
        GROUP_CONCAT(stock.property_5 SEPARATOR ', ')as property_5
       
        ");
        $this->db->from("return_info");
        $this->db->join('customer', 'customer.customer_id=return_info.sup_cus_id');
        $this->db->join('stock', 'stock.invoice_id=return_info.id');
        $this->db->join('product', 'product.product_id=stock.product_id');
        $this->db->join('productcategory', 'product.category_id=productcategory.category_id');

        // $this->db->where('sales_invoice_info.dist_id', $this->dist_id);
        $this->db->where('return_info.is_active', 'Y');
        $this->db->where('return_info.return_type ', '2');
        $this->db->where('stock.form_id ', '5');

        if (!empty($this->input->post('property_1'))) {
            $this->db->like('stock.property_1', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_2'))) {
            $this->db->like('stock.property_2', $this->input->post('property_2'), 'both');
        }
        if (!empty($this->input->post('property_3'))) {
            $this->db->like('stock.property_3', $this->input->post('property_3'), 'both');
        }
        $this->db->group_by("return_info.id");
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('return_info.id', 'desc');
            $this->db->order_by('return_info.return_date', 'desc');
        }
    }
    private function _get_warranty_claim_voucher_datatables_query()
    {
        $this->db->select("sales_invoice_info.sales_invoice_id,
        sales_invoice_info.invoice_no,
        sales_invoice_info.payment_type,
        sales_invoice_info.narration,
        DATE_FORMAT(sales_invoice_info.invoice_date, '%b %e, %Y') as invoice_date,
        sales_invoice_info.invoice_amount,
        sales_invoice_info.paid_amount,
        customer.customerID,
        customer.customerName,
        customer.customer_id,
        GROUP_CONCAT(sales_details.property_1 SEPARATOR ', ') as property_1,
        GROUP_CONCAT(sales_details.property_2 SEPARATOR ', ') as property_2,
        GROUP_CONCAT(sales_details.property_3 SEPARATOR ', ') as property_3,
        GROUP_CONCAT(sales_details.property_4 SEPARATOR ', ') as property_4,
        GROUP_CONCAT(sales_details.property_5 SEPARATOR ', ')as property_5,
        GROUP_CONCAT(sales_details.product_details SEPARATOR ', ')as product_details,
        ");
        $this->db->from("sales_invoice_info");
        $this->db->join('customer', 'customer.customer_id=sales_invoice_info.customer_id');
        $this->db->join('sales_details', 'sales_details.sales_invoice_id=sales_invoice_info.sales_invoice_id');

        // $this->db->where('sales_invoice_info.dist_id', $this->dist_id);
        $this->db->where('sales_invoice_info.is_active', 'Y');
        $this->db->where('sales_invoice_info.invoice_for ', '3');

        if (!empty($this->input->post('property_1'))) {
            $this->db->like('sales_details.property_1', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_2'))) {
            $this->db->like('sales_details.property_2', $this->input->post('property_2'), 'both');
        }
        if (!empty($this->input->post('property_3'))) {
            $this->db->like('sales_details.property_3', $this->input->post('property_3'), 'both');
        }
        $this->db->group_by("sales_invoice_info.sales_invoice_id");
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('sales_invoice_info.sales_invoice_id', 'desc');
            $this->db->order_by('sales_invoice_info.invoice_date', 'desc');
        }
    }

    private function _get_customer_due_datatables_query()
    {
        $this->db->select("cus_due_collection_info.id,cus_due_collection_info.cus_due_coll_no,cus_due_collection_info.payment_type,cus_due_collection_info.date,cus_due_collection_info.total_paid_amount,customer.customerID,customer.customerName,customer.customer_id");
        $this->db->from("cus_due_collection_info");
        $this->db->join('customer', 'customer.customer_id=cus_due_collection_info.customer_id');

        $this->db->where('cus_due_collection_info.dist_id', $this->dist_id);

        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('cus_due_collection_info.date', 'desc');
        }
    }

    private function _get_purchases_datatables_query()
    {

        $this->db->select("purchase_invoice_info.purchase_invoice_id,purchase_invoice_info.invoice_no,purchase_invoice_info.invoice_amount,
        purchase_invoice_info.narration,purchase_invoice_info.invoice_date,
        purchase_invoice_info.paid_amount,supplier.supID,supplier.supName,supplier.sup_id,supplier.sup_id");
        $this->db->from("purchase_invoice_info");
        $this->db->join('supplier', 'supplier.sup_id=purchase_invoice_info.supplier_id');

        $this->db->where('purchase_invoice_info.dist_id', $this->dist_id);

        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('purchase_invoice_info.purchase_invoice_id', 'desc');
            $this->db->order_by('purchase_invoice_info.invoice_date', 'desc');

        }
    }

    private function _get_purchases_cylinder_datatables_query()
    {

        $this->db->select('purchase_invoice_info.purchase_invoice_id,purchase_invoice_info.invoice_no,purchase_invoice_info.invoice_amount,
        purchase_invoice_info.narration,purchase_invoice_info.invoice_date,
        purchase_invoice_info.paid_amount,supplier.supID,supplier.supName,supplier.sup_id,supplier.sup_id,
        
         GROUP_CONCAT(purchase_details.property_1 SEPARATOR ", ") as property_1,
        GROUP_CONCAT(purchase_details.property_2 SEPARATOR ", ") as property_2,
        GROUP_CONCAT(purchase_details.property_3 SEPARATOR ", ") as property_3,
        GROUP_CONCAT(purchase_details.property_4 SEPARATOR ", ") as property_4,
        GROUP_CONCAT(purchase_details.property_5 SEPARATOR ", ") as property_5
        
        ');
        $this->db->from("purchase_invoice_info");
        $this->db->join('supplier', 'supplier.sup_id=purchase_invoice_info.supplier_id');
        $this->db->join('purchase_details', 'purchase_details.purchase_invoice_id=purchase_invoice_info.purchase_invoice_id');
        //$this->db->where('purchase_invoice_info.dist_id', $this->dist_id);
        $this->db->where('purchase_invoice_info.for', 2);
        $this->db->where('purchase_invoice_info.is_active', "Y");
        if (!empty($this->input->post('property_1'))) {
            $this->db->like('purchase_details.property_1', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_1'))) {
            $this->db->like('purchase_details.property_1', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_2'))) {
            $this->db->like('purchase_details.property_2', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_3'))) {
            $this->db->like('purchase_details.property_3', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_4'))) {
            $this->db->like('purchase_details.property_4', $this->input->post('property_1'), 'both');
        }
        $this->db->group_by("purchase_invoice_info.purchase_invoice_id");
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('purchase_invoice_info.purchase_invoice_id', 'desc');
            $this->db->order_by('purchase_invoice_info.invoice_date', 'desc');

        }
    }

    private function _get_warranty_receipt_voucher_list_query()
    {

        $this->db->select('purchase_invoice_info.purchase_invoice_id,purchase_invoice_info.invoice_no,purchase_invoice_info.invoice_amount,
        purchase_invoice_info.narration,purchase_invoice_info.invoice_date,
        purchase_invoice_info.paid_amount,supplier.supID,supplier.supName,supplier.sup_id,supplier.sup_id,
        
         GROUP_CONCAT(purchase_details.property_1 SEPARATOR ", ") as property_1,
        GROUP_CONCAT(purchase_details.property_2 SEPARATOR ", ") as property_2,
        GROUP_CONCAT(purchase_details.property_3 SEPARATOR ", ") as property_3,
        GROUP_CONCAT(purchase_details.property_4 SEPARATOR ", ") as property_4,
        GROUP_CONCAT(purchase_details.property_5 SEPARATOR ", ") as property_5
        
        ');
        $this->db->from("purchase_invoice_info");
        $this->db->join('supplier', 'supplier.sup_id=purchase_invoice_info.supplier_id');
        $this->db->join('purchase_details', 'purchase_details.purchase_invoice_id=purchase_invoice_info.purchase_invoice_id');
        //$this->db->where('purchase_invoice_info.dist_id', $this->dist_id);
        $this->db->where('purchase_invoice_info.for', 3);
        $this->db->where('purchase_invoice_info.is_active', "Y");
        if (!empty($this->input->post('property_1'))) {
            $this->db->like('purchase_details.property_1', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_1'))) {
            $this->db->like('purchase_details.property_1', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_2'))) {
            $this->db->like('purchase_details.property_2', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_3'))) {
            $this->db->like('purchase_details.property_3', $this->input->post('property_1'), 'both');
        }
        if (!empty($this->input->post('property_4'))) {
            $this->db->like('purchase_details.property_4', $this->input->post('property_1'), 'both');
        }
        $this->db->group_by("purchase_invoice_info.purchase_invoice_id");
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('purchase_invoice_info.purchase_invoice_id', 'desc');
            $this->db->order_by('purchase_invoice_info.invoice_date', 'desc');

        }
    }

    private function _get_payment_datatables_query()
    {

        $this->db->select("admin.name,
        customer.customerName,customer.customerID,
        supplier.supName,supplier.supID,
        accounts_vouchertype_autoid.Accounts_VoucherType,
        (SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID )  as amount ,
        ac_accounts_vouchermst.Accounts_VoucherMst_AutoID,
        ac_accounts_vouchermst.for,
        ac_accounts_vouchermst.BackReferenceInvoiceNo,
        ac_accounts_vouchermst.BackReferenceInvoiceID,
        ac_accounts_vouchermst.customer_id,
        DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date,
        ac_accounts_vouchermst.Accounts_Voucher_No,
         ac_accounts_vouchermst.miscellaneous,
        ac_accounts_vouchermst.Narration,
        ac_accounts_vouchermst.AccouVoucherType_AutoID,
        branch.branch_name");
        $this->db->from("ac_accounts_vouchermst ");
        //$this->db->join('ac_tb_accounts_voucherdtl ', 'ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID=ac_accounts_vouchermst.Accounts_VoucherMst_AutoID', 'left');
        $this->db->join('accounts_vouchertype_autoid ', 'accounts_vouchertype_autoid.Accounts_VoucherType_AutoID=ac_accounts_vouchermst.AccouVoucherType_AutoID', 'left');
        $this->db->join('admin ', 'admin.admin_id=ac_accounts_vouchermst.Created_By', 'left');
        $this->db->join('customer ', 'customer.customer_id=ac_accounts_vouchermst.customer_id', 'left');
        $this->db->join('supplier ', 'supplier.sup_id=ac_accounts_vouchermst.supplier_id', 'left');
        $this->db->join('branch ', 'branch.branch_id=ac_accounts_vouchermst.BranchAutoId', 'left');
        // $this->db->where('ac_accounts_vouchermst.BranchAutoId', $this->dist_id);
        $this->db->where('ac_accounts_vouchermst.AccouVoucherType_AutoID', 2);
        $this->db->where('ac_accounts_vouchermst.IsActive', 1);
        /*$this->db->where('ac_accounts_vouchermst.BackReferenceInvoiceNo', 0);
        $this->db->where('ac_accounts_vouchermst.BackReferenceInvoiceID', 0);*/

        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        //$this->db->group_by('ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID');
        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('ac_accounts_vouchermst.Accounts_Voucher_Date', 'desc');
        }
    }

    private function _get_receive_datatables_query_old()
    {

        $this->db->select("admin.name,accounts_vouchertype_autoid.Accounts_VoucherType,
        sum(ac_tb_accounts_voucherdtl.GR_DEBIT) as amount ,
        ac_accounts_vouchermst.Accounts_VoucherMst_AutoID,
        ac_accounts_vouchermst.Accounts_Voucher_Date,
        ac_accounts_vouchermst.Accounts_Voucher_No,
        ac_accounts_vouchermst.Narration,
        ac_accounts_vouchermst.AccouVoucherType_AutoID,branch.branch_name");
        $this->db->from("ac_accounts_vouchermst");
        $this->db->join('ac_tb_accounts_voucherdtl ', 'ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID=ac_accounts_vouchermst.Accounts_VoucherMst_AutoID', 'left');
        $this->db->join('accounts_vouchertype_autoid ', 'accounts_vouchertype_autoid.Accounts_VoucherType_AutoID=ac_accounts_vouchermst.AccouVoucherType_AutoID', 'left');
        $this->db->join('admin ', 'admin.admin_id=ac_accounts_vouchermst.Created_By', 'left');
        $this->db->join('branch ', 'branch.branch_id=ac_accounts_vouchermst.BranchAutoId', 'left');
        //$this->db->where('ac_accounts_vouchermst.BranchAutoId', $this->dist_id);
        /*$this->db->where('BackReferenceInvoiceNo', 0);
        $this->db->where('BackReferenceInvoiceID', 0);*/
        $this->db->where('ac_accounts_vouchermst.AccouVoucherType_AutoID', 1);
        $this->db->where('ac_accounts_vouchermst.IsActive', 1);
        $this->db->group_by('ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('ac_accounts_vouchermst.Accounts_Voucher_Date', 'desc');
        }
    }

    private function _get_receive_datatables_query()
    {

        $this->db->select("admin.name,
        customer.customerName,customer.customerID,
        supplier.supName,supplier.supID,
        accounts_vouchertype_autoid.Accounts_VoucherType,
        (SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID )  as amount ,
        ac_accounts_vouchermst.Accounts_VoucherMst_AutoID,
        ac_accounts_vouchermst.for,
        ac_accounts_vouchermst.BackReferenceInvoiceNo,
        ac_accounts_vouchermst.BackReferenceInvoiceID,
        ac_accounts_vouchermst.customer_id,
        DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date,
        ac_accounts_vouchermst.Accounts_Voucher_No,
        ac_accounts_vouchermst.miscellaneous,
        ac_accounts_vouchermst.Narration,
        ac_accounts_vouchermst.AccouVoucherType_AutoID,
        branch.branch_name");
        $this->db->from("ac_accounts_vouchermst");
        //$this->db->join('ac_tb_accounts_voucherdtl ', 'ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID=ac_accounts_vouchermst.Accounts_VoucherMst_AutoID', 'left');
        $this->db->join('accounts_vouchertype_autoid ', 'accounts_vouchertype_autoid.Accounts_VoucherType_AutoID=ac_accounts_vouchermst.AccouVoucherType_AutoID', 'left');
        $this->db->join('admin ', 'admin.admin_id=ac_accounts_vouchermst.Created_By', 'left');
        $this->db->join('customer ', 'customer.customer_id=ac_accounts_vouchermst.customer_id', 'left');
        $this->db->join('supplier ', 'supplier.sup_id=ac_accounts_vouchermst.supplier_id', 'left');
        $this->db->join('branch ', 'branch.branch_id=ac_accounts_vouchermst.BranchAutoId', 'left');
        //$this->db->where('ac_accounts_vouchermst.BranchAutoId', $this->dist_id);
        /*$this->db->where('BackReferenceInvoiceNo', 0);
        $this->db->where('BackReferenceInvoiceID', 0);*/
        $this->db->where('ac_accounts_vouchermst.AccouVoucherType_AutoID', 1);
        $this->db->where('ac_accounts_vouchermst.IsActive', 1);
        //$this->db->group_by('ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('ac_accounts_vouchermst.Accounts_Voucher_Date', 'desc');
        }
    }


    private function _get_receive_datatables_query____old__mamun()
    {

        $this->db->select("miscellaneous,generals.customer_id,generals.supplier_id,form.name,generals.generals_id,generals.voucher_no,generals.narration,generals.date,generals.debit,supplier.supID,supplier.supName,customer.customerID,customer.customerName");
        $this->db->from("generals");
        $this->db->join('supplier', 'supplier.sup_id=generals.supplier_id', 'left');
        $this->db->join('customer', 'customer.customer_id=generals.customer_id', 'left');
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->where('generals.dist_id', $this->dist_id);
        $this->db->where('generals.form_id', 3);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('generals.date', 'desc');
        }
    }

    private function _get_journal_datatables_query()
    {

        $this->db->select("ac_accounts_vouchermst.for,
        accounts_vouchertype_autoid.Accounts_VoucherType,
        ac_accounts_vouchermst.Accounts_VoucherMst_AutoID,
        ac_accounts_vouchermst.Accounts_Voucher_No,
        ac_accounts_vouchermst.BackReferenceInvoiceNo,
        ac_accounts_vouchermst.Narration,
        DATE_FORMAT(ac_accounts_vouchermst.Accounts_Voucher_Date, '%b %e, %Y') as Accounts_Voucher_Date,
        (SELECT sum(ac_tb_accounts_voucherdtl.GR_DEBIT) FROM ac_tb_accounts_voucherdtl WHERE Accounts_VoucherMst_AutoID=`ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` GROUP BY Accounts_VoucherMst_AutoID )  as amount,
        branch.branch_name");
        $this->db->from("ac_accounts_vouchermst");
        $this->db->join('accounts_vouchertype_autoid', 'accounts_vouchertype_autoid.Accounts_VoucherType_AutoID=ac_accounts_vouchermst.AccouVoucherType_AutoID');
        $this->db->join('branch ', 'branch.branch_id=ac_accounts_vouchermst.BranchAutoId', 'left');
        //$this->db->join('ac_tb_accounts_voucherdtl', 'ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID=ac_accounts_vouchermst.Accounts_VoucherMst_AutoID');
        // $this->db->where('ac_accounts_vouchermst.BranchAutoId', $this->dist_id);
        $this->db->where('ac_accounts_vouchermst.AccouVoucherType_AutoID', 3);
        $this->db->where('ac_accounts_vouchermst.IsActive', 1);
        // $this->db->group_by('ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }


        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('ac_accounts_vouchermst.Accounts_Voucher_Date', 'desc');
            $this->db->order_by('ac_accounts_vouchermst.Accounts_Voucher_No', 'desc');
        }
    }

    private function _get_cusPay_datatables_query()
    {

        //'moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid','moneyreceit.totalPayment','moneyreceit.checkStatus','moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'
        $this->db->select("moneyreceit.date,moneyreceit.receitID,moneyreceit.moneyReceitid,moneyreceit.totalPayment,moneyreceit.checkStatus,moneyreceit.paymentType,customer.customerID,customer.customer_id,customer.customerName");
        $this->db->from("moneyreceit");
        $this->db->join('customer', 'customer.customer_id=moneyreceit.customerid');
       // $this->db->where('moneyreceit.dist_id', $this->dist_id);
        $this->db->where('moneyreceit.receiveType', 1);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('moneyreceit.date', 'desc');
        }
    }

    private function _get_supPay_datatables_query()
    {

        //'moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid','moneyreceit.totalPayment','moneyreceit.checkStatus','moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'
        $this->db->select("supplier.sup_id,moneyreceit.date,moneyreceit.receitID,moneyreceit.moneyReceitid,moneyreceit.totalPayment,moneyreceit.checkStatus,moneyreceit.paymentType,supplier.supID,supplier.supName");
        $this->db->from("moneyreceit");
        $this->db->join('supplier', 'supplier.sup_id=moneyreceit.customerid', 'left');
        //$this->db->where('moneyreceit.dist_id', $this->dist_id);
        $this->db->where('moneyreceit.receiveType', 2);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('moneyreceit.date', 'desc');
        }
    }

    private function _get_cus_datatables_query()
    {

        //'moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid','moneyreceit.totalPayment','moneyreceit.checkStatus','moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'
        $this->db->select("*");
        $this->db->from("customer");
        $this->db->where('dist_id', $this->dist_id);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('customer.customer_id', 'desc');
        }
    }

    private function _get_sup_datatables_query()
    {

        //'moneyreceit.date', 'moneyreceit.receitID', 'moneyreceit.moneyReceitid','moneyreceit.totalPayment','moneyreceit.checkStatus','moneyreceit.paymentType', 'customer.customerID', 'customer.customerName'
        $this->db->select("*");
        $this->db->from("supplier");
        $this->db->where('dist_id', $this->dist_id);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('supplier.sup_id', 'desc');
        }
    }


    private function _get_bill_datatables_query()
    {

        $this->db->select("miscellaneous,generals.customer_id,generals.supplier_id,form.name,generals.generals_id,generals.voucher_no,generals.narration,generals.date,generals.debit,supplier.supID,supplier.supName,customer.customerID,customer.customerName");
        $this->db->from("generals");
        $this->db->join('supplier', 'supplier.sup_id=generals.supplier_id', 'left');
        $this->db->join('customer', 'customer.customer_id=generals.customer_id', 'left');
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->where('generals.dist_id', $this->dist_id);
        $this->db->where('generals.form_id', 29);
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('generals.date', 'desc');
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_productCat_datatables()
    {
        $this->_get_productCat_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_datatables_unit()
    {
        $this->_get_datatables_unit_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_product_datatables()
    {
        $this->_get_product_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_sales_datatables()
    {
        $this->_get_sales_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_warranty_claim_voucher_datatables()
    {
        $this->_get_warranty_claim_voucher_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_customer_due_datatables()
    {
        $this->_get_customer_due_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_purchases_datatables()
    {
        $this->_get_purchases_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_purchases_cylinder_datatables()
    {
        $this->_get_purchases_cylinder_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function get_warranty_receipt_voucher_list_query()
    {
        $this->_get_warranty_receipt_voucher_list_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    function get_receive_datatables()
    {
        $this->_get_receive_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_journal_datatables()
    {
        $this->_get_journal_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_bill_datatables()
    {
        $this->_get_bill_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_cusPay_datatables()
    {
        $this->_get_cusPay_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_supPay_datatables()
    {
        $this->_get_supPay_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_cus_datatables()
    {
        $this->_get_cus_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_sup_datatables()
    {
        $this->_get_sup_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_productCat()
    {
        $this->_get_productCat_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_unit()
    {
        $this->_get_datatables_unit_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_product()
    {
        $this->_get_product_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }


    function count_filtered_sales()
    {
        $this->_get_sales_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }  function count_filtered_warranty_claim_voucher_list()
    {
        $this->_get_warranty_claim_voucher_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_cus_due_payment()
    {
        $this->_get_customer_due_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_purchases()
    {
        $this->_get_purchases_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_cylinder_purchases()
    {
        $this->_get_purchases_cylinder_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_filtered_arranty_receipt_voucher()
    {
        $this->_get_warranty_receipt_voucher_list_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_payment()
    {
        $this->_get_payment_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_receive()
    {
        $this->_get_receive_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_journal()
    {
        $this->_get_journal_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_bill()
    {
        $this->_get_bill_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_cusPay()
    {
        $this->_get_cusPay_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_supPay()
    {
        $this->_get_supPay_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_cus()
    {
        $this->_get_cus_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_sup()
    {
        $this->_get_sup_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_all_unit()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_all_productCat()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_all_product()
    {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->where('product.dist_id', $this->dist_id);
        $this->db->or_where('product.dist_id', 1);
        $this->db->group_end();

        return $this->db->count_all_results();
    }

    /*public function count_all_sales() {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('form_id', 5);
        return $this->db->count_all_results();
    }*/
    public function count_all_warranty_claim_voucher_list()
    {
        $this->db->from($this->table);
        $this->db->where('invoice_for', 3);
        //$this->db->where('form_id', 5);
        return $this->db->count_all_results();
    }

    public function count_all_sales()
    {
        $this->db->from($this->table);
        //$this->db->where('dist_id', $this->dist_id);
        //$this->db->where('form_id', 5);
        return $this->db->count_all_results();
    }

    public function count_all_purchases()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        //$this->db->where('form_id', 11);
        return $this->db->count_all_results();
    }

    public function count_all_cylinder_purchases()
    {
        $this->db->from($this->table);

        $this->db->where('for', 2);
        //$this->db->where('form_id', 11);
        return $this->db->count_all_results();
    }
    public function count_all_warranty_receipt_voucher()
    {
        $this->db->from($this->table);

        $this->db->where('for', 3);
        //$this->db->where('form_id', 11);
        return $this->db->count_all_results();
    }

    public function count_all_payment()
    {
        $this->db->from($this->table);
        $this->db->where('BackReferenceInvoiceNo', 0);
        $this->db->where('BackReferenceInvoiceID', 0);
        $this->db->where('AccouVoucherType_AutoID', 2);
        $this->db->where('IsActive', 1);
        return $this->db->count_all_results();
    }

    public function count_all_receive()
    {
        $this->db->from($this->table);
        $this->db->where('BackReferenceInvoiceNo', 0);
        $this->db->where('BackReferenceInvoiceID', 0);
        $this->db->where('AccouVoucherType_AutoID', 1);
        return $this->db->count_all_results();
    }

    public function count_all_journal()
    {
        $this->db->from($this->table);
        $this->db->where('BranchAutoId', $this->dist_id);
        $this->db->where('AccouVoucherType_AutoID', 3);
        return $this->db->count_all_results();
    }

    public function count_all_bill()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('form_id', 29);
        return $this->db->count_all_results();
    }

    public function count_all_cusPay()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('receiveType', 1);
        return $this->db->count_all_results();
    }

    public function count_all_supPay()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('receiveType', 2);
        return $this->db->count_all_results();
    }

    public function count_all_cus()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        return $this->db->count_all_results();
    }

    public function count_all_sup()
    {
        $this->db->from($this->table);
        $this->db->where('dist_id', $this->dist_id);
        return $this->db->count_all_results();
    }

    function get_product_type_datatables()
    {
        $this->_get_product_type_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_product_type()
    {
        $this->_get_product_type_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_productType()
    {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->where('product_type.dist_id', $this->dist_id);
        $this->db->or_where('product_type.dist_id', 1);
        $this->db->group_end();

        $this->db->where('product_type.is_delete', 'N');

        return $this->db->count_all_results();
    }

    private function _get_product_type_datatables_query()
    {
        $this->db->select("product_type.product_type_name,product_type.product_type_id,product_type.is_active");
        $this->db->from($this->table);

        $this->db->group_start();
        $this->db->where('product_type.dist_id', $this->dist_id);
        $this->db->or_where('product_type.dist_id', 1);
        $this->db->group_end();

        $this->db->where('product_type.is_delete', 'N');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('product_type.product_type_id', 'desc');
        }
    }

    function get_product_package_datatables()
    {
        $this->_get_product_package_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_product_package()
    {
        $this->_get_product_package_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_product_package_datatables_query()
    {
        $this->db->select("package.package_id,package.package_name,package.is_active,package.dist_id");
        $this->db->from($this->table);

        $this->db->group_start();
        $this->db->where('package.dist_id', $this->dist_id);
        $this->db->or_where('package.dist_id', 1);
        $this->db->group_end();

        $this->db->where('package.is_delete', 'N');
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            $this->db->order_by('package.package_id', 'desc');
        }
    }

    public function count_all_productPackage()
    {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->where('package.dist_id', $this->dist_id);
        $this->db->or_where('package.dist_id', 1);
        $this->db->group_end();

        $this->db->where('package.is_delete', 'N');

        return $this->db->count_all_results();
    }

}

?>