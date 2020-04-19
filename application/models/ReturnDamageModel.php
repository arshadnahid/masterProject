<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReturnDamageModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getInvoiceListByDate($date, $distId) {
        $this->db->distinct('sales_invoice_id');
        $this->db->select('invoice_no, 	sales_invoice_id');
        $this->db->from('sales_invoice_info');
        //$this->db->where('form_id', 5);
        $this->db->where('invoice_date', $date);
        $this->db->where('dist_id', $distId);
        $result = $this->db->get()->result();

        return $result;
    }
    function salesReturnInfo($id, $distId) {
        $sql = "SELECT
	sr.return_date as date,
	sr.amount,
        g. 	invoice_no as voucher_no,
        sr.sales_return_id,
CONCAT(c.customerName,' [ ', c.customerID,' ] ') as customerName,
c.customerAddress as address

FROM
	sales_return AS sr
LEFT JOIN sales_return_details as srd ON srd.sales_return_id=sr.sales_return_id
LEFT JOIN customer as c ON c.customer_id=sr.customer_id
LEFT JOIN sales_invoice_info AS g ON g.sales_invoice_id = sr.sales_invoice_id
WHERE sr.is_active = 'Y' AND sr.is_delete = 'N' AND sr.dist_id = '$distId' AND sr.sales_return_id='$id' ORDER BY sr.return_date asc";

        $reslt = $this->db->query($sql)->row();
        return $reslt;
    }

    function salesDetailsInfo($id, $distId) {
        $sql = "SELECT srd.return_quantity,
	srd.unit_price,
	CONCAT(
		p.productName,
		' [ ',
		b.brandName,
		' ] '
	)AS productName,
	u.unitTtile as unit
FROM
	sales_return AS sr
LEFT JOIN sales_return_details AS srd ON srd.sales_return_id = sr.sales_return_id
LEFT JOIN product AS p ON p.product_id = srd.product_id
LEFT JOIN brand AS b ON b.brandId = p.brand_id
LEFT JOIN unit as u ON u.unit_id=p.unit_id
LEFT JOIN customer AS c ON c.customer_id = sr.customer_id
LEFT JOIN sales_invoice_info AS g ON g.sales_invoice_id = sr.sales_invoice_id
WHERE sr.is_active = 'Y' AND sr.is_delete = 'N' AND sr.dist_id = '$distId' AND sr.sales_return_id='$id' ORDER BY sr.return_date asc";
        $reslt = $this->db->query($sql)->result();
        return $reslt;
    }

    function getReturnProduct($distId) {

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

    function getDamageProduct($distId) {

        $sql = "SELECT CONCAT(p.productName,' [ ',b.brandName,' ] ') as productName,dp.date,dp.quantity,dp.unit_price,dp.damage_id FROM damageProduct AS dp
LEFT JOIN product as p ON p.product_id = dp.product_id
LEFT JOIN brand as b ON b.brandId = p.brand_id where dp.is_active = 'Y' AND dp.is_delete = 'N' AND dp.dist_id='$distId'";
        $result = $this->db->query($sql)->result();
        return $result;
    }

    function getInvoiceReturnProduct($invoiceId) {

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
        log_message('error','query '.print_r($this->db->last_query(),true));
        return $reslt;
    }
    function productCost($productId, $dist_id) {
        /*$this->db->select('SUM(purchase_details.price)/SUM(stock.quantity) as totalAvgPurchasesPrice');
        $this->db->from('purchase_details');
        $this->db->where('purchase_details.product_id', $productId);
        //$this->db->where('stock.dist_id', $dist_id);
        //$this->db->where('stock.type', 'In');
        $results = $this->db->get()->row_array();
        return $results['totalAvgPurchasesPrice'];*/


        $query2="SELECT
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
AND product.product_id=".$productId."
GROUP BY
	product.product_id";

        $query = $this->db->query($query2);
        $result = $query->row();
        return $result->totalAvgPurchasesPrice;


    }

    function getCustomerList() {
        $sql = "SELECT c.customer_id,CONCAT(c.customerName,' [ ',c.customerID,' ] ') AS cusName FROM customer as c";
        $result = $this->db->query($sql)->result();
        return $result;
    }
}
