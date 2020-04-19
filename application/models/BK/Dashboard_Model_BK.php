<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_ModelBK extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getIncentiveCompany($startDate, $endDate, $distId) {

        $sql = "select i.targetQty,tab1.purchase_qty,brand.brandName
            from incentive i
            join brand on brand.brandId=i.company
           LEFT JOIN
      (SELECT p.product_id,p.productName,p.brand_id,sum(s.quantity) as purchase_qty
      from product p
      JOIN stock s on s.product_id=p.product_id
      WHERE s.date >= '$startDate'
	    AND s.date <= '$endDate'
           AND s.dist_id='$distId'
           AND s.type='In'
GROUP BY p.brand_id)
tab1 on tab1.brand_id=i.company
WHERE i.start >='$startDate'
    AND i.end <='$endDate'
        AND i.dist_id='$distId'
group by i.company";
        $result = $this->db->query($sql)->result();
        return $result;
    }

    function getTodaySales($day, $distId) {
        $this->db->select('customer.customerName,customer.customerID,form.name,generals.voucher_no,generals.date,generals.debit,generals.narration,generals.customer_id,generals.generals_id');
        $this->db->from("generals");
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->join('customer', 'customer.customer_id=generals.customer_id');
        $this->db->where('generals.dist_id', $distId);
        $this->db->where('generals.form_id', '5');
        $this->db->where('generals.date', $day);
        $result = $this->db->get()->result();
        return $result;
    }

    function getTodayPurchases($day, $distId) {
        $this->db->select('supplier.supName,supplier.supID,form.name,generals.voucher_no,generals.date,generals.debit,generals.narration,generals.supplier_id,generals.generals_id');
        $this->db->from("generals");
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->join('supplier', 'supplier.sup_id=generals.supplier_id');
        $this->db->where('generals.dist_id', $distId);
        $this->db->where('generals.form_id', '11');
        $this->db->where('generals.date', $day);
        $result = $this->db->get()->result();
        return $result;
    }

    function getTodaysPayment($day, $distId) {
        $this->db->select('customer.customerName,customer.customerID,supplier.supName,supplier.supID,form.name,generals.voucher_no,generals.date,generals.debit,generals.narration,generals.supplier_id,generals.generals_id,generals.miscellaneous');
        //$this->db->select('form.name,generals.voucher_no,generals.date,generals.debit,generals.narration,generals.supplier_id,generals.generals_id,generals.payType');
        $this->db->from("generals");
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->join('supplier', 'supplier.sup_id=generals.supplier_id', 'left');
        $this->db->join('customer', 'customer.customer_id=generals.customer_id', 'left');
        $this->db->where('generals.dist_id', $distId);
        $this->db->where('generals.form_id', '2');
        $this->db->where('generals.date', $day);
        $result = $this->db->get()->result();
        return $result;
    }

    function getTodaysReceive($day, $distId) {
        $this->db->select('customer.customerName,customer.customerID,supplier.supName,supplier.supID,form.name,generals.voucher_no,generals.date,generals.debit,generals.narration,generals.supplier_id,generals.generals_id,generals.miscellaneous');
        //$this->db->select('form.name,generals.voucher_no,generals.date,generals.debit,generals.narration,generals.supplier_id,generals.generals_id,generals.payType');
        $this->db->from("generals");
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->join('supplier', 'supplier.sup_id=generals.supplier_id', 'left');
        $this->db->join('customer', 'customer.customer_id=generals.customer_id', 'left');
        $this->db->where('generals.dist_id', $distId);
        $this->db->where('generals.form_id', '3');
        $this->db->where('generals.date', $day);
        $result = $this->db->get()->result();
        return $result;
    }

    function salesListByDate($distId) {
        $this->db->select('sum(price) as totalSales,date');
        $this->db->from("stock");
        $this->db->where('dist_id', $distId);
        $this->db->where('type', 'Out');
        $where = 'MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())';
        $this->db->where($where);
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $result = $this->db->get()->result();
        return $result;
    }

    function getTopSalesProduct($distid) {
        $this->db->select("sum(quantity) as totalQty,product.productName");
        $this->db->from("stock");
        $this->db->join('product', 'product.product_id=stock.product_id');
        $this->db->where_in('stock.dist_id', array(1, $distid));
        $this->db->group_by('stock.product_id');
        $this->db->order_by('totalQty', 'desc');
        $result = $this->db->get()->result();
        return $result;
    }

//    function getInventoryStock($distid) {
//
//
//
//        $this->db->select("sum(quantity) as totalQty,product.productName");
//        $this->db->from("stock");
//        $this->db->join('product', 'product.product_id=stock.product_id');
//        $this->db->where('stock.dist_id', $distid);
//        $this->db->group_by('stock.product_id');
//        $this->db->order_by('totalQty', 'desc');
//        $result = $this->db->get()->result();
//        return $result;
//    }
    public function getTotalSales($date = null) {

        if (!empty($date)):
            $month = date('m', strtotime($date));
        else:
            $month = date('m');
        endif;

        $this->db->select("sum(debit) as totalSalesAmount");
        $this->db->from("generals");
        $this->db->where("dist_id", $this->dist_id);
        $this->db->where('form_id', 5);
        $this->db->where('MONTH(date)', $month);
        $totalSales = $this->db->get()->row();
        return $totalSales->totalSalesAmount;
    }

    public function getAvgPurchasesPrice() {
        $this->db->select('avg(stock.rate) totalAvgPusPrice');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('stock.type', 'In');
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $openingAvgPurcPrice = $this->db->get()->row_array();
        $data['totalAvgPurcPrice'] = $openingAvgPurcPrice['totalAvgPusPrice'];
    }

    public function getProductAvgPrice($distID, $productid) {
        $this->db->select('SUM(stock.quantity) as totalQty,SUM(stock.price) as totalPrice,SUM(stock.price)/SUM(stock.quantity) AS totalAvgPurchasesPrice');
        $this->db->from('stock');
        $this->db->where('stock.product_id', $productid);
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.type', 'In');
        $results = $this->db->get()->row_array();
        return $results['totalAvgPurchasesPrice'];
    }

    public function getInventoryStock($distID, $productid, $date = null) {
        $this->db->select('sum(stock.quantity) as totalPurchasesQty');
        $this->db->from('stock');
        $this->db->where('date <=', date('Y-m-d', strtotime($date)));
        $this->db->where('stock.type', 'In');
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $totalIn = $this->db->get()->row();
        $this->db->select('sum(stock.quantity) as totalSales');
        $this->db->from('stock');
        $this->db->where('date <=', date('Y-m-d', strtotime($date)));
        $this->db->where('stock.type', 'Out');
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $totalOut = $this->db->get()->row();
        $totalStock = $totalIn->totalPurchasesQty - $totalOut->totalSales;
        return $totalStock;
    }

}

?>
