<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReturnDamageModel extends CI_Model
{
    var $column_search = array('sii.sales_invoice_id', 'sii.invoice_no', 'sii.invoice_date', 'sii.customer_id', 'sd.sales_details_id', 'sd.product_id', 'p.productName', 'p.product_code', 'pc.title', 'b.brandName', 'sd.quantity', 'sd.unit_price', 'sd.is_package', 'sd.product_details', 'sd.property_1', 'sd.property_2', 'sd.property_3', 'sd.property_4', 'sd.property_5');

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getInvoiceListByDate($date, $distId)
    {
        $this->db->distinct('sales_invoice_id');
        $this->db->select('invoice_no, 	sales_invoice_id');
        $this->db->from('sales_invoice_info');
        //$this->db->where('form_id', 5);
        $this->db->where('invoice_date', $date);
        $this->db->where('dist_id', $distId);
        $result = $this->db->get()->result();

        return $result;
    }

    function salesReturnInfo($id, $distId)
    {
        $sql = "SELECT
	sri.return_date as return_date,
	sri.narration as narration,
	sri.return_invoice_no as voucher_no,
CONCAT(c.customerName,' [ ', c.customerID,' ] ') as customerName,
c.customerAddress as address

FROM
	return_info AS sri
LEFT JOIN customer as c ON c.customer_id=sri.sup_cus_id

WHERE 1=1
 AND  sri.id='$id' ORDER BY sri.return_date asc";

        $reslt = $this->db->query($sql)->row();
        return $reslt;
    }
    function purchaseReturnInfo($id, $distId)
    {
        $sql = "SELECT
	sri.return_date as return_date,
	sri.narration as narration,
	sri.return_invoice_no as voucher_no,
CONCAT(c.supName,' [ ', c.supID,' ] ') as customerName,
c.supAddress as address

FROM
	return_info AS sri
LEFT JOIN supplier as c ON c.sup_id=sri.sup_cus_id

WHERE 1=1
 AND  sri.id='$id' ORDER BY sri.return_date asc";

        $reslt = $this->db->query($sql)->row();
        return $reslt;
    }

    function salesDetailsInfo($id, $distId)
    {
        $sql = "SELECT sr.quantity,
	sr.price,
	CONCAT(
		p.productName,
		' [ ',
		b.brandName,
		' ] '
	)AS productName,
	u.unitTtile as unit
FROM
	stock AS sr

LEFT JOIN product AS p ON p.product_id = sr.product_id
LEFT JOIN brand AS b ON b.brandId = p.brand_id
LEFT JOIN unit as u ON u.unit_id=p.unit_id
LEFT JOIN customer AS c ON c.customer_id = sr.customer_id
LEFT JOIN sales_invoice_info AS g ON g.sales_invoice_id = sr.parent_invoice_id
WHERE 1=1
 AND sr.form_id = '5' 
 AND sr.invoice_id = '$id' 
  ";
        $reslt = $this->db->query($sql)->result();
        return $reslt;
    }
    function purchaseDetailsInfo($id, $distId)
    {
        $sql = "SELECT sr.quantity,
	sr.price,
	CONCAT(
		p.productName,
		' [ ',
		b.brandName,
		' ] '
	)AS productName,
	u.unitTtile as unit
FROM
	stock AS sr

LEFT JOIN product AS p ON p.product_id = sr.product_id
LEFT JOIN brand AS b ON b.brandId = p.brand_id
LEFT JOIN unit as u ON u.unit_id=p.unit_id
LEFT JOIN supplier AS c ON c.supID = sr.supplier_id
LEFT JOIN sales_invoice_info AS g ON g.sales_invoice_id = sr.parent_invoice_id
WHERE 1=1
 AND sr.form_id = '6' 
 AND sr.invoice_id = '$id' 
  ";
        $reslt = $this->db->query($sql)->result();
        return $reslt;
    }

    function getReturnProduct($distId)
    {

        $sql = "SELECT
    sr.customer_id,
    sr.sales_return_id,
    sr.sales_invoice_id,
    si.invoice_no,
    sr.amount,sr.return_date
   
FROM
    sales_return AS sr
LEFT JOIN sales_invoice_info AS si
ON
    si.sales_invoice_id = sr.sales_invoice_id

WHERE
    sr.is_active = 'Y' AND sr.is_delete = 'N' AND si.dist_id = '$distId'";
        $reslt = $this->db->query($sql)->result();
        return $reslt;
    }

    function getDamageProduct($distId)
    {

        $sql = "SELECT CONCAT(p.productName,' [ ',b.brandName,' ] ') as productName,dp.date,dp.quantity,dp.unit_price,dp.damage_id FROM damageProduct AS dp
LEFT JOIN product as p ON p.product_id = dp.product_id
LEFT JOIN brand as b ON b.brandId = p.brand_id where dp.is_active = 'Y' AND dp.is_delete = 'N' AND dp.dist_id='$distId'";
        $result = $this->db->query($sql)->result();
        return $result;
    }

    function getInvoiceReturnProduct($invoiceId)
    {

        $sql = "SELECT
	g.invoice_date as date,
	g.invoice_no as voucher_no,
	concat(
		p.productName,
		' [ ',
		b.brandName,
		' ] '
	)AS productName,sr.unit_price,sr.return_quantity
FROM
	sales_return_details AS sr
LEFT JOIN sales_invoice_info AS g ON g.sales_invoice_id = sr.sales_invoice_id
LEFT JOIN product AS p ON p.product_id = sr.product_id
LEFT JOIN brand AS b ON b.brandId = p.brand_id
WHERE
	sr.is_active = 'Y'
AND sr.is_delete = 'N' AND sr.sales_invoice_id = '$invoiceId'";
        $reslt = $this->db->query($sql)->result();
        log_message('error', 'query ' . print_r($this->db->last_query(), true));
        return $reslt;
    }

    function productCost($productId, $dist_id)
    {
        /*$this->db->select('SUM(purchase_details.price)/SUM(stock.quantity) as totalAvgPurchasesPrice');
        $this->db->from('purchase_details');
        $this->db->where('purchase_details.product_id', $productId);
        //$this->db->where('stock.dist_id', $dist_id);
        //$this->db->where('stock.type', 'In');
        $results = $this->db->get()->row_array();
        return $results['totalAvgPurchasesPrice'];*/


        $query2 = "SELECT
	product.product_id,
	SUM(purchase_details.quantity)+ purchase_return_details.return_empty_qty AS tt_quantity,
	purchase_return_details.return_empty_qty,
	(IFNULL((SUM(purchase_details.quantity * purchase_details.unit_price)),0)- IFNULL((SUM(purchase_return_details.return_empty_qty * purchase_return_details.avg_purchase_return_price))+ exchange_cylinder.exchange_cylinder_price,0)-IFNULL((SUM(sales_details.quantity * sales_details.unit_price)),0)- IFNULL((SUM(sales_return_details.product_id * sales_return_details.avg_purchase_return_price_sales)),0))AS tt_price,
	SUM(purchase_details.quantity * purchase_details.unit_price)/ SUM(purchase_details.quantity)AS totalAvgPurchasesPrice,
	product.category_id
FROM
	product
LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
LEFT JOIN(
	SELECT
		SUM(purchase_return_details.return_quantity * purchase_return_details.unit_price)/ SUM(return_quantity)AS avg_purchase_return_price,
		sum(purchase_return_details.return_quantity)AS return_empty_qty,
		purchase_return_details.product_id
	FROM
		purchase_return_details
	GROUP BY
		purchase_return_details.product_id
)AS purchase_return_details ON purchase_return_details.product_id = product.product_id
LEFT JOIN(
	SELECT
		product_id,
		IFNULL((SUM(exchange_in * unit_price)- SUM(exchange_out * unit_price))/ SUM(exchange_in)- SUM(exchange_out),0)AS exchange_cylinder_price
	FROM
		cylinder_exchange_details
	WHERE
		is_active = 'Y'
	AND is_delete = 'N'
	GROUP BY
		product_id
)AS exchange_cylinder ON exchange_cylinder.product_id = product.product_id
LEFT JOIN sales_details ON sales_details.product_id = product.product_id
LEFT JOIN(
	SELECT
		SUM(sales_return_details.return_quantity * sales_return_details.unit_price)/ SUM(return_quantity)AS avg_purchase_return_price_sales,
		sum(sales_return_details.return_quantity)AS return_empty_qty,
		sales_return_details.product_id
	FROM
		sales_return_details
	GROUP BY
		sales_return_details.product_id
)AS sales_return_details ON sales_return_details.product_id = product.product_id

WHERE purchase_details.is_active = 'Y' AND purchase_details.is_delete='N'
AND product.product_id=" . $productId . "
GROUP BY
	product.product_id";

        $query = $this->db->query($query2);
        $result = $query->row();
        return $result->totalAvgPurchasesPrice;


    }

    function getCustomerList()
    {
        $sql = "SELECT c.customer_id,CONCAT(c.customerName,' [ ',c.customerID,' ] ') AS cusName FROM customer as c";
        $result = $this->db->query($sql)->result();
        return $result;
    }


    private function getQuery()
    {


        //add custom filter here
        if (!empty($this->input->post('startDate'))) {
            $this->db->where('sii.invoice_date >=', date('Y-m-d', strtotime($this->input->post('startDate'))));
        }
        if (!empty($this->input->post('endDate'))) {
            $this->db->where('sii.invoice_date', date('Y-m-d', strtotime($this->input->post('endDate'))));
        }
        if (!empty($this->input->post('customerid'))) {
            $this->db->where('sii.customer_id', $this->input->post('customerid'));
        }
        if (!empty($this->input->post('BranchAutoId'))) {
            $this->db->where('sii.branch_id', $this->input->post('BranchAutoId'));
        }

        $this->db->select("sii.sales_invoice_id,
	sii.invoice_no,
	sii.invoice_date,
	sii.customer_id,
	sd.sales_details_id,
	sd.product_id,
	p.productName,
	p.product_code,
	pc.title AS productcategory,
	b.brandName,
	sd.quantity,
	sd.unit_price,
	sd.is_package,
	sd.product_details,
	sd.property_1,
	sd.property_2,
	sd.property_3,
	sd.property_4,
	sd.property_5");

        $this->db->from('sales_details sd');
        $this->db->join('sales_invoice_info sii ', 'sii.sales_invoice_id = sd.sales_invoice_id', 'left');
        $this->db->join('product p ', 'p.product_id = sd.product_id ', 'left');
        $this->db->join('productcategory pc   ', 'pc.category_id = p.category_id', 'left');
        $this->db->join('brand b  ', 'b.brandId = p.brand_id', 'left');

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

        if (!empty($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (!empty($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    private function getQueryPurchase()
    {


        //add custom filter here
        if (!empty($this->input->post('startDate'))) {
            $this->db->where('pii.invoice_date >=', date('Y-m-d', strtotime($this->input->post('startDate'))));
        }
        if (!empty($this->input->post('endDate'))) {
            $this->db->where('pii.invoice_date', date('Y-m-d', strtotime($this->input->post('endDate'))));
        }
        if (!empty($this->input->post('supplierid'))) {
            $this->db->where('pii.supplier_id', $this->input->post('supplierid'));
        }
        if (!empty($this->input->post('BranchAutoId'))) {
            $this->db->where('pii.branch_id', $this->input->post('BranchAutoId'));
        }

        $this->db->select("pii.purchase_invoice_id,
	pii.invoice_no,
	pii.invoice_date,
	pii.supplier_id,
	pii.branch_id,
	pd.purchase_details_id,
	pd.product_id,
	p.productName,
	p.product_code,
	p.category_id,
	pc.title AS productcategory,
	b.brandName,
	pd.quantity,
	pd.unit_price,
	pd.is_package,
	pd.product_details,
	pd.property_1,
	pd.property_2,
	pd.property_3,
	pd.property_4,
	pd.property_5");

        $this->db->from('purchase_details pd');
        $this->db->join('purchase_invoice_info pii ', 'pii.purchase_invoice_id = pd.purchase_invoice_id', 'left');
        $this->db->join('product p ', 'p.product_id = pd.product_id ', 'left');
        $this->db->join('productcategory pc   ', 'pc.category_id = p.category_id', 'left');
        $this->db->join('brand b  ', 'b.brandId = p.brand_id', 'left');

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

        if (!empty($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (!empty($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_sales_invoice_details_for_return()
    {
        $this->getQuery();
        if (!empty($_POST['length']) && $_POST['length'] < 1) {
            $_POST['length'] = '10';
        } else {
            $_POST['length'] = $_POST['length'];
        }

        if (!empty($_POST['start']) && $_POST['start'] > 1) {
            $_POST['start'] = $_POST['start'];
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        //print_r($_POST);die;
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_purchase_invoice_details_for_return()
    {
        $this->getQueryPurchase();
        if (!empty($_POST['length']) && $_POST['length'] < 1) {
            $_POST['length'] = '10';
        } else {
            $_POST['length'] = $_POST['length'];
        }

        if (!empty($_POST['start']) && $_POST['start'] > 1) {
            $_POST['start'] = $_POST['start'];
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        //print_r($_POST);die;
        $query = $this->db->get();
        return $query->result_array();
    }

    public function countFiltered()
    {
        /*$this->getQuery();
        $query = $this->db->get();
        return $query->num_rows();*/
        return 0;
    }

    public function countAll()
    {
        /*$this->db->from($this->table);
        return $this->db->count_all_results();*/
        return 0;
    }

    function get_sales_invoice_details_for_return2()
    {


        $query = "SELECT
	sii.sales_invoice_id,
	sii.invoice_no,
	sii.invoice_date,
	sii.customer_id,
	sd.sales_details_id,
	sd.product_id,
	p.productName,
	p.product_code,
	pc.title AS productcategory,
	b.brandName,
	sd.quantity,
	sd.unit_price,
	sd.is_package,
	sd.product_details,
	sd.property_1,
	sd.property_2,
	sd.property_3,
	sd.property_4,
	sd.property_5
FROM
	sales_details sd
LEFT JOIN sales_invoice_info sii ON sii.sales_invoice_id = sd.sales_invoice_id
LEFT JOIN product p ON p.product_id = sd.product_id
LEFT JOIN productcategory pc ON pc.category_id = p.category_id
LEFT JOIN brand b ON b.brandId = p.brand_id
WHERE
	1 = 1
ORDER BY
	sii.sales_invoice_id,
	sii.invoice_date,
	sd.product_id";


        if ($customerId != "all") {
            $query .= " AND sales_invoice_info.customer_id =" . $customerId;
        }
        if ($branch_id != "all") {
            $query .= " AND sales_invoice_info.branch_id =" . $branch_id;
        }
        if ($type != "all") {
            $query .= " AND customer.customerType =" . $type;
        }
        $query .= " ORDER BY branch.branch_name,customer.customerName,sales_invoice_info.invoice_date";

        $query = $this->db->query($query);
        $result = $query->result();
    }

    function get_product_total_return_qty($condition)
    {

        $this->db->select('sum(IFNULL(quantity,0)) as total_return_qty');
        $this->db->from("stock");

        $this->db->where($condition);

        $this->db->group_by('parent_stock_id,product_id,form_id');

        $result = $this->db->get()->row();
        return $result->total_return_qty;

    }


}
