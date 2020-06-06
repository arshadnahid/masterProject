<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory_Model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getImportProduct($distId = null) {
        $this->db->select('*');
        $this->db->from('productimport');
        $this->db->where('productimport.dist_id', $distId);
        /*$this->db->group_start();
        $this->db->where_in('catId', 0);
        $this->db->or_where_in('brandId', 0);
        $this->db->or_where_in('unitId', 0);
        $this->db->group_end();*/
        $result = $this->db->get()->result();
        return $result;
    }

    public function getStock($distId, $fromDate, $todate, $catId, $brandId, $stockType) {
        $sql = '';
        if ($catId != 'All' && $brandId == 'All'):
            if ($stockType == 1) :
                $sql = 'WHERE  p.category_id =1 AND   AND p.category_id=' . $catId;
            else:
                $sql = 'WHERE  p.category_id !=1 AND p.category_id=' . $catId;
            endif; //inner if end
        elseif ($catId == 'All' && $brandId != 'All'):
            if ($stockType == 1):
                $sql = 'WHERE p.category_id =1 AND p.brand_id=' . $brandId;
            else:
                $sql = 'WHERE p.category_id !=1 AND p.brand_id=' . $brandId;
            endif; //inner if end
        elseif ($catId != 'All' && $brandId != 'All'):
            if ($stockType == 1):
                $sql = 'WHERE  p.category_id =1 AND p.brand_id= ' . $brandId . ' AND p.category_id=' . $catId;
            else:
                $sql = 'WHERE  p.category_id !=1 AND p.brand_id= ' . $brandId . ' AND p.category_id=' . $catId;
            endif; //inner if end
        else:
            if ($stockType == 1):
                $sql = 'WHERE p.category_id =1';
            else:
                $sql = 'WHERE p.category_id !=1';
            endif; //inner if end
        endif;
        $query = "SELECT c.title as pCateogry,p.product_id,p.productName,p.product_code,b.brandName,tab1.product_id,
tab1.opStockIn,
tab2.opStockOut ,
tab3.opStockIn as currentStockIn,
tab4.opStockOut as currentStockOut ,
tab5.avgPurPrice,
tab6.avgSalePrice
FROM product p
LEFT JOIN
brand b ON b.brandId=p.brand_id
LEFT JOIN
productcategory c ON c.category_id=p.category_id
left JOIN
	(SELECT SUM(s.quantity) as opStockIn,s.product_id ,s.type
	 FROM stock s
	WHERE s.date < '$fromDate'
	AND s.type='In'
        AND s.dist_id='$distId'
	GROUP BY s.product_id ) tab1
ON tab1.product_id=p.product_id
left JOIN
	(SELECT SUM(s1.quantity) as opStockOut,s1.product_id
	 FROM stock s1
	WHERE s1.date < '$fromDate'
	AND s1.type='Out'
        AND s1.dist_id='$distId'
	GROUP BY s1.product_id )
tab2
ON tab2.product_id=p.product_id
LEFT JOIN
(SELECT SUM(s.quantity) as opStockIn,s.product_id ,s.type
	 FROM stock s
	WHERE s.date >='$fromDate'  AND s.date <='$todate'
	AND s.type='In'
        AND s.dist_id='$distId'
	GROUP BY s.product_id ) tab3
ON tab3.product_id=p.product_id
LEFT JOIN
	(SELECT SUM(s1.quantity) as opStockOut,s1.product_id
	 FROM stock s1
	WHERE s1.date >='$fromDate'  AND s1.date <='$todate'
	AND s1.type='Out'
        AND s1.dist_id='$distId'
	GROUP BY s1.product_id )
tab4
ON tab4.product_id=p.product_id
LEFT JOIN
	(SELECT SUM(s1.price)/SUM(s1.quantity) as avgPurPrice,s1.product_id
	 FROM stock s1
	WHERE s1.type='In'
        AND  s1.dist_id='$distId'
	GROUP BY s1.product_id )
tab5
ON tab5.product_id=p.product_id
LEFT JOIN
	(SELECT SUM(s1.price)/SUM(s1.quantity) as avgSalePrice,s1.product_id
	 FROM stock s1
	WHERE s1.type='Out'
        AND  s1.dist_id='$distId'
	GROUP BY s1.product_id )
tab6
ON tab6.product_id=p.product_id
        $sql  GROUP BY p.product_id";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function getCusSupProductSummary($type, $cusSumId, $productId, $fromDate, $todate, $distId) {
        if ($productId == 'all') {
            $pSql = 'GROUP BY p.product_id';
        } else {
            $pSql = 'WHERE  p.product_id=' . $productId;
        }
        if ($type == 2) {
            if ($productId == 'all') {
                $sql = 'AND  s.dist_id=' . $distId . ' AND s.customerId=' . $cusSumId . ' GROUP BY s.product_id';
            } else {
                $sql = 'AND  s.dist_id=' . $distId . ' AND s.customerId=' . $cusSumId . ' AND s.product_id=' . $productId;
            }
        } else {
            if ($productId == 'all') {
                $sql = 'AND  s.dist_id=' . $distId . ' AND s.supplierId=' . $cusSumId . ' GROUP BY s.product_id';
            } else {
                $sql = 'AND  s.dist_id=' . $distId . ' AND s.supplierId=' . $cusSumId . ' AND s.product_id=' . $productId;
            }
        }
        $query = "SELECT p.product_id,p.productName,p.product_code,b.brandName,tab1.product_id,
tab1.opStockIn,
tab2.opStockOut,
tab3.currentStockIn,
tab4.currentStockOut
FROM product p
LEFT JOIN
brand b ON b.brandId=p.brand_id
left JOIN
	(SELECT SUM(s.quantity) as opStockIn,s.product_id ,s.type
	 FROM stock s
	 WHERE s.date < '$fromDate'
	AND s.type='Cin'
        $sql) tab1
ON tab1.product_id=p.product_id
left JOIN
	(SELECT SUM(s.quantity) as opStockOut,s.product_id ,s.type
	 FROM stock s
	 WHERE s.date < '$fromDate'
	AND s.type='Cout'
        $sql)
tab2
ON tab2.product_id=p.product_id
LEFT JOIN
(SELECT SUM(s.quantity) as currentStockIn,s.product_id ,s.type
	 FROM stock s
	 WHERE s.date >= '$fromDate'
	 AND s.date <= '$todate'
	AND s.type='Cin'
        $sql)
tab3
ON tab3.product_id=p.product_id
LEFT JOIN
	(SELECT SUM(s.quantity) as currentStockOut,s.product_id ,s.type
	 FROM stock s
	 WHERE s.date >= '$fromDate'
	 AND s.date <= '$todate'
	AND s.type='Cout'
       $sql)
tab4
ON tab4.product_id=p.product_id
         $pSql";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function cylinder_stock_report($start_date, $end_date,  $brandId){
        /*$query="SELECT
                brand.brandId,
                brand.brandName,
                IFNULL(purchase_refill_qty_table.purchase_refill_qty,0) as purchase_refill_qty,
                IFNULL(purchase_empty_qty_table.purchase_empty_qty,0) as purchase_empty_qty,
                IFNULL(purchase_refill_qty_table.purchase_returnable_quantity,0) as purchase_returnable_quantity,
                IFNULL(purchase_refill_qty_table.purchase_return_quentity,0) as purchase_return_quentity,
                IFNULL(purchase_refill_qty_table.purchase_supplier_due,0) as purchase_supplier_due,
                IFNULL(purchase_refill_qty_table.purchase_supplier_advance,0) as purchase_supplier_advance,
                IFNULL(sales_refill_qty_table.sales_refill_qty,0) as sales_refill_qty,
                IFNULL(sales_empty_qty_table.sales_empty_qty,0) as sales_empty_qty,
                IFNULL(sales_refill_qty_table.sales_returnable_quantity,0) as sales_returnable_quantity,
                IFNULL(sales_refill_qty_table.sales_return_quentity,0) as sales_return_quentity,
                IFNULL(sales_refill_qty_table.sales_customer_due,0) as sales_customer_due,
                IFNULL(sales_refill_qty_table.sales_customer_advance,0) as sales_customer_advance
            FROM
                brand
            LEFT JOIN(
                SELECT
                    product.product_id,
                    product.brand_id,
                    SUM(IFNULL(purchase_details.quantity,0)) AS purchase_refill_qty,
                    SUM(IFNULL(purchase_details.returnable_quantity,0))AS purchase_returnable_quantity,
                    SUM(IFNULL(purchase_details.return_quentity,0))AS purchase_return_quentity,
                    SUM(IFNULL(purchase_details.supplier_due,0))AS purchase_supplier_due,
                    SUM(IFNULL(purchase_details.supplier_advance,0))AS purchase_supplier_advance,
                    product.category_id
                FROM
                    product
                LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
                WHERE
                    product.category_id = 2
                GROUP BY
                    product.brand_id
            )AS purchase_refill_qty_table ON purchase_refill_qty_table.brand_id =brand.brandId
            AND purchase_refill_qty_table.category_id = 2
            LEFT JOIN(
                SELECT
                    product.brand_id,
                    product.category_id,
                    (SUM(IFNULL(purchase_details.quantity,0))- IFNULL(SUM(purchase_return_details.return_quantity),0))AS purchase_empty_qty
                FROM
                    product
                LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
                AND purchase_details.is_package = 0
                LEFT JOIN purchase_return_details ON purchase_return_details.product_id = product.product_id
                WHERE
                    product.category_id = 1
                GROUP BY
                    product.brand_id
            )AS purchase_empty_qty_table ON purchase_empty_qty_table.brand_id = brand.brandId
            AND purchase_empty_qty_table.category_id = 1
            LEFT JOIN(
                SELECT
                    product.product_id,
                    product.brand_id,
                    SUM(IFNULL(sales_details.quantity,0)) AS sales_refill_qty,
                    SUM(IFNULL(sales_details.returnable_quantity,0))AS sales_returnable_quantity,
                    SUM(IFNULL(sales_details.return_quentity,0))AS sales_return_quentity,
                    SUM(IFNULL(sales_details.customer_due,0))AS sales_customer_due,
                    SUM(IFNULL(sales_details.customer_advance,0))AS sales_customer_advance,
                    product.category_id
                FROM
                    product
                LEFT JOIN sales_details ON sales_details.product_id = product.product_id
                WHERE
                    product.category_id = 2
                GROUP BY
                    product.brand_id
            )AS sales_refill_qty_table ON sales_refill_qty_table.brand_id =brand.brandId
            AND sales_refill_qty_table.category_id = 2
            LEFT JOIN(
                SELECT
                    product.brand_id,
                    product.category_id,
                    (SUM(IFNULL(sales_details.quantity,0))- IFNULL(SUM(sales_return_details.return_quantity),0))AS sales_empty_qty
                FROM
                    product
                LEFT JOIN sales_details ON sales_details.product_id = product.product_id
                AND sales_details.is_package = 0
                LEFT JOIN sales_return_details ON sales_return_details.product_id = product.product_id
                WHERE
                    product.category_id = 1
                GROUP BY
                    product.brand_id
            )AS sales_empty_qty_table ON sales_empty_qty_table.brand_id = brand.brandId
            AND sales_empty_qty_table.category_id = 1 WHERE 1=1";*/
        $query="SELECT
	IFNULL(cylinder_exchange_empty.exchange_qty,0)AS exchange_qty_empty,
	IFNULL(cylinder_exchange_refil.exchange_qty,0)AS exchange_qty_refil,
	brand.brandId,
	brand.brandName,
	IFNULL(purchase_refill_qty_table.purchase_refill_qty,0)AS purchase_refill_qty,
	IFNULL(purchase_empty_qty_table.purchase_empty_qty,0)AS purchase_empty_qty,
	IFNULL(purchase_refill_qty_table.purchase_returnable_quantity,0)AS purchase_returnable_quantity,
	IFNULL(purchase_refill_qty_table.purchase_return_quentity,0)AS purchase_return_quentity,
	IFNULL(purchase_refill_qty_table.purchase_supplier_due,0)AS purchase_supplier_due,
	IFNULL(purchase_refill_qty_table.purchase_supplier_advance,0)AS purchase_supplier_advance,
	IFNULL(sales_refill_qty_table.sales_refill_qty,0)AS sales_refill_qty,
	IFNULL(sales_empty_qty_table.sales_empty_qty,0)AS sales_empty_qty,
	IFNULL(sales_refill_qty_table.sales_returnable_quantity,0)AS sales_returnable_quantity,
	IFNULL(sales_refill_qty_table.sales_return_quentity,0)AS sales_return_quentity,
	IFNULL(sales_refill_qty_table.sales_customer_due,0)AS sales_customer_due,
	IFNULL(sales_refill_qty_table.sales_customer_advance,0)AS sales_customer_advance
FROM
	brand
LEFT JOIN(
	SELECT
		product.product_id,
		product.brand_id,
		SUM(IFNULL(purchase_details.quantity,0))AS purchase_refill_qty,
		SUM(IFNULL(purchase_details.returnable_quantity,0))AS purchase_returnable_quantity,
		SUM(IFNULL(purchase_details.return_quentity,0))AS purchase_return_quentity,
		SUM(IFNULL(purchase_details.supplier_due,0))AS purchase_supplier_due,
		SUM(IFNULL(purchase_details.supplier_advance,0))AS purchase_supplier_advance,
		product.category_id
	FROM
		product
	LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
	WHERE
		product.category_id = 2
	GROUP BY
		product.brand_id
)AS purchase_refill_qty_table ON purchase_refill_qty_table.brand_id = brand.brandId
 AND purchase_refill_qty_table.category_id = 2
LEFT JOIN(
	SELECT
		product.brand_id,
		product.category_id,
		(SUM(IFNULL(purchase_details.quantity,0))- IFNULL(SUM(purchase_return_details.return_quantity),0))AS purchase_empty_qty
	FROM
		product
	LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
	AND purchase_details.is_package = 0
	LEFT JOIN purchase_return_details ON purchase_return_details.product_id = product.product_id
	WHERE
		product.category_id = 1
	GROUP BY
		product.brand_id
)AS purchase_empty_qty_table ON purchase_empty_qty_table.brand_id = brand.brandId
 AND purchase_empty_qty_table.category_id = 1
LEFT JOIN(
	SELECT
		product.product_id,
		product.brand_id,
		SUM(IFNULL(sales_details.quantity,0))AS sales_refill_qty,
		SUM(IFNULL(sales_details.returnable_quantity,0))AS sales_returnable_quantity,
  	SUM(IFNULL(sales_details.return_quentity,0))AS sales_return_quentity,
		SUM(IFNULL(sales_details.customer_due,0))AS sales_customer_due,
		SUM(IFNULL(sales_details.customer_advance,0))AS sales_customer_advance,
		product.category_id
	FROM
		product
	LEFT JOIN sales_details ON sales_details.product_id = product.product_id
	WHERE
		product.category_id = 2
	GROUP BY
		product.brand_id
)AS sales_refill_qty_table ON sales_refill_qty_table.brand_id = brand.brandId
AND sales_refill_qty_table.category_id = 2
LEFT JOIN(
	SELECT
		product.brand_id,
		product.category_id,
		(SUM(IFNULL(sales_details.quantity,0))- IFNULL(SUM(sales_return_details.return_quantity),0))AS sales_empty_qty
	FROM
		product
	LEFT JOIN sales_details ON sales_details.product_id = product.product_id
	AND sales_details.is_package = 0
	LEFT JOIN sales_return_details ON sales_return_details.product_id = product.product_id
	WHERE
		product.category_id = 1
	GROUP BY
		product.brand_id
)AS sales_empty_qty_table ON sales_empty_qty_table.brand_id = brand.brandId
 AND sales_empty_qty_table.category_id = 1
LEFT JOIN(
	/* cylinder_exchange qty*/
	SELECT
		product.brand_id,
		product.productName,
		(SUM(cylinder_exchange_details.exchange_in)- SUM(cylinder_exchange_details.exchange_out))AS exchange_qty
	FROM
		product
	LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.product_id = product.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	WHERE
		/*empty cylinder_exchange_details.product_id = 11*/
		cylinder_exchange_details.is_active = 'Y'
	AND cylinder_exchange_details.is_delete = 'N'
	AND product.category_id = 1
	GROUP BY
		product.brand_id
)AS cylinder_exchange_empty ON cylinder_exchange_empty.brand_id = brand.brandId
LEFT JOIN(
	/* refill cylinder_exchange qty*/
	SELECT
		product.brand_id,
		product.productName,
		(SUM(cylinder_exchange_details.exchange_in)- SUM(cylinder_exchange_details.exchange_out))AS exchange_qty
	FROM
		product
	LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.product_id = product.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	WHERE
		/*cylinder_exchange_details.product_id = 11*/
		cylinder_exchange_details.is_active = 'Y'
	AND cylinder_exchange_details.is_delete = 'N'
	AND product.category_id = 2
	GROUP BY
		product.brand_id
)AS cylinder_exchange_refil ON cylinder_exchange_refil.brand_id = brand.brandId";
        if($brandId !='0'){
            $query.=" AND brand.brandId=".$brandId;
        }

        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function cylinder_stock_group_report($start_date, $end_date,  $brandId,$branch_id='all'){
        $where="";
        if($brandId !='0'){
            $where=" AND  brand.brandId=".$brandId;
        }
        $query1="SELECT
	cylinder_exchange_refil.exchange_qty AS exchange_qty_refill,
	cylinder_exchange_empty.exchange_qty AS exchange_qty_empty,
	brand.brandId,
	brand.brandName,
	product.productName,
	unit.unitTtile,
	IFNULL(purchase_refill_qty_table.purchase_refill_qty,0)AS purchase_refill_qty,
	IFNULL(purchase_refill_qty_table.purchase_returnable_quantity,0)AS purchase_returnable_quantity,
	IFNULL(purchase_refill_qty_table.purchase_return_quentity,0)AS purchase_return_quentity,
	IFNULL(purchase_refill_qty_table.purchase_supplier_due,0)AS purchase_supplier_due,
	IFNULL(purchase_refill_qty_table.purchase_supplier_advance,0)AS purchase_supplier_advance,
	IFNULL(purchase_empty_qty_table.purchase_empty_qty,0)AS purchase_empty_qty,
	IFNULL(sales_refill_qty_table.sales_refill_qty,0)AS sales_refill_qty,
	IFNULL(sales_empty_qty_table.sales_empty_qty,0)AS sales_empty_qty,
	IFNULL(sales_refill_qty_table.sales_returnable_quantity,0) AS sales_returnable_quantity,
	IFNULL(sales_refill_qty_table.sales_return_quentity,0)AS sales_return_quentity,
	IFNULL(sales_refill_qty_table.sales_customer_due,0)AS sales_customer_due,
	IFNULL(sales_refill_qty_table.sales_customer_advance,0)AS sales_customer_advance
FROM
	brand
LEFT JOIN product ON product.brand_id = brand.brandId
LEFT JOIN unit ON unit.unit_id = product.unit_id
LEFT JOIN(
	SELECT
		product.product_id,
		product.brand_id,
		product.productName,
		SUM(IFNULL(purchase_details.quantity,0))AS purchase_refill_qty,
		SUM(IFNULL(purchase_details.returnable_quantity,0))AS purchase_returnable_quantity,
		SUM(IFNULL(purchase_details.return_quentity,0))AS purchase_return_quentity,
		SUM(IFNULL(purchase_details.supplier_due,0))AS purchase_supplier_due,
		SUM(IFNULL(purchase_details.supplier_advance,0))AS purchase_supplier_advance,
		product.category_id
	FROM
		product
	LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
	LEFT JOIN purchase_invoice_info ON purchase_invoice_info.purchase_invoice_id=purchase_details.purchase_invoice_id
	WHERE 
		product.category_id = 2
		AND purchase_invoice_info.invoice_date >= ' $start_date '
		AND purchase_invoice_info.invoice_date <= ' $end_date '
		AND purchase_details.is_active='Y'
		AND purchase_details.is_delete='N'
	GROUP BY
		product.brand_id,
		product.productName
)AS purchase_refill_qty_table ON purchase_refill_qty_table.brand_id = brand.brandId
AND purchase_refill_qty_table.productName = product.productName
AND purchase_refill_qty_table.category_id = 2
LEFT JOIN(
	SELECT
		product.brand_id,
		product.category_id,
		product.productName,
		(SUM(IFNULL(purchase_details.quantity,0))- IFNULL(SUM(purchase_return_details.return_quantity),0))AS purchase_empty_qty
	FROM
		product
	LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
	AND purchase_details.is_package = 0
	LEFT JOIN purchase_return_details ON purchase_return_details.product_id = product.product_id
	LEFT JOIN purchase_invoice_info ON purchase_invoice_info.purchase_invoice_id=purchase_details.purchase_invoice_id
	WHERE
		product.category_id = 1
		AND purchase_invoice_info.invoice_date >= ' $start_date '
		AND purchase_invoice_info.invoice_date <= ' $end_date '
		AND purchase_details.is_active='Y'
		AND purchase_details.is_delete='N'
	GROUP BY
		product.brand_id,
		product.productName
)AS purchase_empty_qty_table ON purchase_empty_qty_table.brand_id = brand.brandId
AND purchase_empty_qty_table.productName = product.productName
AND purchase_empty_qty_table.category_id = 1
LEFT JOIN(
	SELECT
		product.product_id,
		product.brand_id,
		product.productName,
		SUM(IFNULL(sales_details.quantity,0))AS sales_refill_qty,
		SUM(IFNULL(sales_details.returnable_quantity,0))AS sales_returnable_quantity,
		SUM(IFNULL(sales_details.return_quentity,0))AS sales_return_quentity,
		SUM(IFNULL(sales_details.customer_due,0))AS sales_customer_due,
		SUM(IFNULL(sales_details.customer_advance,0))AS sales_customer_advance,
		product.category_id
	FROM
		product
	LEFT JOIN sales_details ON sales_details.product_id = product.product_id
	LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id
	
	WHERE
		product.category_id = 2
		AND sales_invoice_info.invoice_date >= ' $start_date '
		AND sales_invoice_info.invoice_date <= ' $end_date '
		AND sales_details.is_active='Y'
		AND sales_details.is_delete='N'
	GROUP BY
		product.brand_id,
		product.productName
)AS sales_refill_qty_table ON sales_refill_qty_table.brand_id = brand.brandId
AND sales_refill_qty_table.productName = product.productName
AND sales_refill_qty_table.category_id = 2
LEFT JOIN(
	SELECT
		product.brand_id,
		product.category_id,
		product.productName,
		(SUM(IFNULL(sales_details.quantity,0))- IFNULL(SUM(sales_return_details.return_quantity),0))AS sales_empty_qty
	FROM
		product
	LEFT JOIN sales_details ON sales_details.product_id = product.product_id
	
	AND sales_details.is_package = 0
	LEFT JOIN sales_return_details ON sales_return_details.product_id = product.product_id
	LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id
	WHERE
		product.category_id = 1
		AND sales_invoice_info.invoice_date >= ' $start_date '
		AND sales_invoice_info.invoice_date <= ' $end_date '
		AND sales_details.is_active='Y'
		AND sales_details.is_delete='N'
	GROUP BY
		product.brand_id,
		product.productName
)AS sales_empty_qty_table ON sales_empty_qty_table.brand_id = brand.brandId
AND sales_empty_qty_table.productName = product.productName
AND sales_empty_qty_table.category_id = 1
LEFT JOIN(
	/* cylinder_exchange qty*/
	SELECT
		product.brand_id,
		product.productName,
		(SUM(cylinder_exchange_details.exchange_in)- SUM(cylinder_exchange_details.exchange_out))AS exchange_qty
	FROM
		product
	LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.product_id = product.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	WHERE
		/*empty cylinder_exchange_details.product_id = 11*/
		cylinder_exchange_details.is_active = 'Y'
	AND cylinder_exchange_details.is_delete = 'N'
	AND product.category_id = 1
	GROUP BY
		product.brand_id
)AS cylinder_exchange_empty ON cylinder_exchange_empty.brand_id = brand.brandId
AND cylinder_exchange_empty.productName = product.productName
LEFT JOIN(
	/* refill cylinder_exchange qty*/
	SELECT
		product.brand_id,
		product.productName,
		(SUM(cylinder_exchange_details.exchange_in)- SUM(cylinder_exchange_details.exchange_out))AS exchange_qty
	FROM
		product
	LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.product_id = product.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	WHERE
		/*cylinder_exchange_details.product_id = 11*/
		cylinder_exchange_details.is_active = 'Y'
	AND cylinder_exchange_details.is_delete = 'N'
	AND product.category_id = 2
	GROUP BY
		product.brand_id
)AS cylinder_exchange_refil ON cylinder_exchange_refil.brand_id = brand.brandId
AND cylinder_exchange_refil.productName = product.productName
WHERE
	1 = 1 ".$where."
GROUP BY
	product.brand_id,
	product.productName
ORDER BY
	brand.brandId,
	product.productName";


        $query="SELECT
	cylinder_exchange_refil.exchange_qty AS exchange_qty_refill,
	cylinder_exchange_empty.exchange_qty AS exchange_qty_empty,
	brand.brandId,
	brand.brandName,
	product.productName,
	unit.unitTtile,
	IFNULL(purchase_refill_qty_table.purchase_refill_qty,0)AS purchase_refill_qty,
	IFNULL(purchase_refill_qty_table.purchase_returnable_quantity,0)AS purchase_returnable_quantity,
	IFNULL(purchase_refill_qty_table.purchase_return_quentity,0)AS purchase_return_quentity,
	IFNULL(purchase_refill_qty_table.purchase_supplier_due,0)AS purchase_supplier_due,
	IFNULL(purchase_refill_qty_table.purchase_supplier_advance,0)AS purchase_supplier_advance,
	IFNULL(purchase_empty_qty_table.purchase_empty_qty,0)AS purchase_empty_qty,
  IFNULL(purchase_return_cylinder.purchase_return_empty_qty,0)AS purchase_return_empty_qty,
	IFNULL(sales_refill_qty_table.sales_refill_qty,0)AS sales_refill_qty,
	IFNULL(sales_refill_qty_table.sales_returnable_quantity,0)AS sales_returnable_quantity,
	IFNULL(sales_refill_qty_table.sales_return_quentity,0)AS sales_return_quentity,
	IFNULL(sales_refill_qty_table.sales_customer_due,0)AS sales_customer_due,
	IFNULL(sales_refill_qty_table.sales_customer_advance,0)AS sales_customer_advance,
  IFNULL(sales_empty_qty_table.sales_empty_qty,0)AS sales_empty_qty,
IFNULL(sales_empty_return_qty.sales_empty_return_qty,0)AS sales_empty_return_qty,
IFNULL(cylinder_exchange_empty.exchange_qty,0)AS exchange_qty,
IFNULL(cylinder_exchange_empty.exchange_in,0)AS exchange_in,
IFNULL(cylinder_exchange_empty.exchange_out,0)AS exchange_out
FROM
	brand
LEFT JOIN product ON product.brand_id = brand.brandId
LEFT JOIN unit ON unit.unit_id = product.unit_id
LEFT JOIN(
	SELECT
		product.product_id,
		product.brand_id,
		product.productName,
		SUM(IFNULL(purchase_details.quantity,0))AS purchase_refill_qty,
		SUM(IFNULL(purchase_details.returnable_quantity,0))AS purchase_returnable_quantity,
		SUM(IFNULL(purchase_details.return_quentity,0))AS purchase_return_quentity,
		SUM(IFNULL(purchase_details.supplier_due,0))AS purchase_supplier_due,
		SUM(IFNULL(purchase_details.supplier_advance,0))AS purchase_supplier_advance,
		product.category_id
	FROM
		product
	LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
	LEFT JOIN purchase_invoice_info ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id
	WHERE
		product.category_id = 2
	AND purchase_invoice_info.invoice_date >= ' $start_date '
	AND purchase_invoice_info.invoice_date <= ' $end_date '
	AND purchase_details.is_active = 'Y'
	AND purchase_details.is_delete = 'N'
	GROUP BY
		product.brand_id,
		product.productName
)AS purchase_refill_qty_table ON purchase_refill_qty_table.brand_id = brand.brandId
AND purchase_refill_qty_table.productName = product.productName
AND purchase_refill_qty_table.category_id = 2
LEFT JOIN(
	SELECT
		product.brand_id,
		product.category_id,
		product.productName,
		(SUM(IFNULL(purchase_details.quantity,0)))AS purchase_empty_qty
	FROM
		product
	LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
	AND purchase_details.is_package = 0
	LEFT JOIN purchase_invoice_info ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id
	WHERE
		product.category_id = 1
	AND purchase_invoice_info.invoice_date >= ' $start_date '
	AND purchase_invoice_info.invoice_date <= ' $end_date '
	AND purchase_details.is_active = 'Y'
	AND purchase_details.is_delete = 'N'
	GROUP BY
		product.brand_id,
		product.productName
)AS purchase_empty_qty_table ON purchase_empty_qty_table.brand_id = brand.brandId
AND purchase_empty_qty_table.productName = product.productName
AND purchase_empty_qty_table.category_id = 1
/*purchase return empty cylinder*/
LEFT JOIN(
SELECT
	product.brand_id,
	product.category_id,
	product.productName,
	(SUM(IFNULL(purchase_return_details.return_quantity,0)))AS purchase_return_empty_qty
FROM
	product
LEFT JOIN purchase_return_details ON purchase_return_details.product_id = product.product_id
LEFT JOIN purchase_invoice_info ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id
WHERE
	product.category_id = 1
AND purchase_invoice_info.invoice_date >= ' $start_date '
AND purchase_invoice_info.invoice_date <= ' $end_date '
AND purchase_return_details.is_active = 'Y'
AND purchase_return_details.is_delete = 'N'
GROUP BY
	product.brand_id,
	product.productName
) AS purchase_return_cylinder ON purchase_return_cylinder.brand_id = brand.brandId
AND purchase_return_cylinder.productName = product.productName
AND purchase_return_cylinder.category_id = 1
/*purchase return empty cylinder*/
LEFT JOIN(
	SELECT
		product.product_id,
		product.brand_id,
		product.productName,
		SUM(IFNULL(sales_details.quantity,0))AS sales_refill_qty,
		SUM(IFNULL(sales_details.returnable_quantity,0))AS sales_returnable_quantity,
		SUM(IFNULL(sales_details.return_quentity,0))AS sales_return_quentity,
		SUM(IFNULL(sales_details.customer_due,0))AS sales_customer_due,
		SUM(IFNULL(sales_details.customer_advance,0))AS sales_customer_advance,
		product.category_id
	FROM
		product
	LEFT JOIN sales_details ON sales_details.product_id = product.product_id
	LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
	WHERE
		product.category_id = 2
	AND sales_invoice_info.invoice_date >= ' $start_date '
	AND sales_invoice_info.invoice_date <= ' $end_date '
	AND sales_details.is_active = 'Y'
	AND sales_details.is_delete = 'N'
	GROUP BY
		product.brand_id,
		product.productName
)AS sales_refill_qty_table ON sales_refill_qty_table.brand_id = brand.brandId
AND sales_refill_qty_table.productName = product.productName
AND sales_refill_qty_table.category_id = 2
LEFT JOIN(
	SELECT
		product.brand_id,
		product.category_id,
		product.productName,
		(SUM(IFNULL(sales_details.quantity,0)))AS sales_empty_qty
	FROM
		product
	LEFT JOIN sales_details ON sales_details.product_id = product.product_id
	AND sales_details.is_package = 0
	LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
	WHERE
		product.category_id = 1
	AND sales_invoice_info.invoice_date >= ' $start_date '
	AND sales_invoice_info.invoice_date <= ' $end_date '
	AND sales_details.is_active = 'Y'
	AND sales_details.is_delete = 'N'
	GROUP BY
		product.brand_id,
		product.productName
)AS sales_empty_qty_table ON sales_empty_qty_table.brand_id = brand.brandId
AND sales_empty_qty_table.productName = product.productName
AND sales_empty_qty_table.category_id = 1
/*sales return empty cylinder*/
LEFT JOIN(
SELECT
		product.brand_id,
		product.category_id,
		product.productName,
		(SUM(IFNULL(sales_return_details.return_quantity,0)))AS sales_empty_return_qty
	FROM
		product
	LEFT JOIN sales_return_details ON sales_return_details.product_id = product.product_id
	LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id
	WHERE
		product.category_id = 1
	AND sales_invoice_info.invoice_date >= ' $start_date '
	AND sales_invoice_info.invoice_date <= ' $end_date '
	AND sales_return_details.is_active = 'Y'
	AND sales_return_details.is_delete = 'N'
	GROUP BY
		product.brand_id,
		product.productName

) AS sales_empty_return_qty ON sales_empty_return_qty.brand_id = brand.brandId
AND sales_empty_return_qty.productName = product.productName
AND sales_empty_return_qty.category_id = 1
LEFT JOIN(
	/* cylinder_exchange qty*/
	SELECT
		product.brand_id,
		product.productName,
		SUM(cylinder_exchange_details.exchange_in) as exchange_in,
		SUM(cylinder_exchange_details.exchange_out) as exchange_out,
		(
			SUM(
				cylinder_exchange_details.exchange_in
			)- SUM(
				cylinder_exchange_details.exchange_out
			)
		)AS exchange_qty
	FROM
		product
	LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.product_id = product.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	WHERE
		/*empty cylinder_exchange_details.product_id = 11*/
		cylinder_exchange_details.is_active = 'Y'
	AND cylinder_exchange_details.is_delete = 'N'
	AND product.category_id = 1
	GROUP BY
		product.brand_id,product.productName
)AS cylinder_exchange_empty ON cylinder_exchange_empty.brand_id = brand.brandId
AND cylinder_exchange_empty.productName = product.productName
LEFT JOIN(
	/* refill cylinder_exchange qty*/
	SELECT
		product.brand_id,
		product.productName,
		(
			SUM(
				cylinder_exchange_details.exchange_in
			)- SUM(
				cylinder_exchange_details.exchange_out
			)
		)AS exchange_qty
	FROM
		product
	LEFT JOIN cylinder_exchange_details ON cylinder_exchange_details.product_id = product.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	WHERE
		/*cylinder_exchange_details.product_id = 11*/
		cylinder_exchange_details.is_active = 'Y'
	AND cylinder_exchange_details.is_delete = 'N'
	AND product.category_id = 2
	GROUP BY
		product.brand_id
)AS cylinder_exchange_refil ON cylinder_exchange_refil.brand_id = brand.brandId
AND cylinder_exchange_refil.productName = product.productName
WHERE
	1 = 1 ".$where."  AND product.category_id in(1,2)
GROUP BY
	product.brand_id,
	product.productName
ORDER BY
	brand.brandId,
	product.productName";








        log_message('error','cylinder_stock_report query'.print_r($query,true));
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function checkOpenigValid($distId) {
        $this->db->select("sum(credit) as totalAmount");
        $this->db->from("opening_balance");
        $this->db->where('dist_id', $distId);
        $result = $this->db->get()->row();
        if (!empty($result->totalAmount)):
            return $result->totalAmount;
        else:
            return 0;
        endif;
    }

    public function getProductLedger($fromDate, $toDate, $productId, $distId,$branch_id) {
        $this->db->select("purchase_invoice_info.invoice_no,
        purchase_invoice_info.invoice_date,
        purchase_details.quantity,
        brand.brandName,
        productcategory.title,
        product.productName,product.product_code,
       
        supplier.supName,IFNULL(branch.branch_name,'NA') as branch_name");
        //customer.customerName,
        $this->db->from("purchase_details");
        $this->db->join('purchase_invoice_info', 'purchase_invoice_info.purchase_invoice_id=purchase_details.purchase_invoice_id', 'left');
        $this->db->join('product', 'product.product_id=purchase_details.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id=product.category_id', 'left');
        $this->db->join('supplier', 'supplier.sup_id=purchase_invoice_info.supplier_id', 'left');
        $this->db->join('branch', 'branch.branch_id=purchase_invoice_info.branch_id', 'left');
        //$this->db->join('customer', 'customer.customer_id=generals.customer_id', 'left');
        $this->db->join('brand', 'brand.brandId=product.brand_id', 'left');
        $this->db->where('purchase_invoice_info.dist_id', $distId);
        if (!empty($productId) && $productId != 'all') {
            $this->db->where('purchase_details.product_id', $productId);
        }
        if (!empty($branch_id) && $branch_id != 'all') {
            $this->db->where('purchase_details.branch_id', $branch_id);
        }
        $this->db->where('purchase_invoice_info.invoice_date >=', $fromDate);
        $this->db->where('purchase_invoice_info.invoice_date <=', $toDate);
        $this->db->where('purchase_details.is_active ', 'Y');
        $this->db->where('purchase_details.is_delete ', 'N');
        $this->db->where('purchase_details.show_in_invoice ', '1');
        $this->db->order_by('branch.branch_name', 'ASC');
        $this->db->order_by('purchase_invoice_info.invoice_date', 'asc');
        //$this->db->where_in('stock.type', array('In', 'Out'));
        $ledgerReport['purchase'] = $this->db->get()->result();

        $this->db->select("sales_invoice_info.invoice_no,
        sales_invoice_info.invoice_date,
        sales_details.quantity,
        brand.brandName,
        productcategory.title,
        product.productName,product.product_code,
        customer.customerName,IFNULL(branch.branch_name,'NA') as branch_name");
        //customer.customerName,
        $this->db->from("sales_details");
        $this->db->join('sales_invoice_info', 'sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id', 'left');
        $this->db->join('product', 'product.product_id=sales_details.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id=product.category_id', 'left');
        $this->db->join('branch', 'branch.branch_id=sales_invoice_info.branch_id', 'left');
        //$this->db->join('supplier', 'supplier.sup_id=purchase_invoice_info.supplier_id', 'left');
        $this->db->join('customer', 'customer.customer_id=sales_invoice_info.customer_id', 'left');
        $this->db->join('brand', 'brand.brandId=product.brand_id', 'left');
        $this->db->where('sales_invoice_info.dist_id', $distId);
        if (!empty($productId) && $productId != 'all') {
            $this->db->where('sales_details.product_id', $productId);
        }
        if (!empty($branch_id) && $branch_id != 'all') {
            $this->db->where('sales_details.branch_id', $branch_id);
        }
        $this->db->where('sales_invoice_info.invoice_date >=', $fromDate);
        $this->db->where('sales_invoice_info.invoice_date <=', $toDate);
        $this->db->where('sales_details.is_active ', 'Y');
        $this->db->where('sales_details.is_delete ', 'N');
        $this->db->order_by('sales_invoice_info.invoice_date', 'asc');
        $this->db->order_by('sales_invoice_info.invoice_date', 'asc');
        //$this->db->where_in('stock.type', array('In', 'Out'));
        $ledgerReport['sales'] = $this->db->get()->result();
        return $ledgerReport;
    }

    public function getProductLedgerOpening($fromDate, $toDate, $productId, $distId) {
        $this->db->select("sum(stock.quantity) as totalIn");
        $this->db->from("stock");
        $this->db->where('stock.dist_id', $distId);
        if (!empty($productId) && $productId != 'all') {
            $this->db->where('stock.product_id', $productId);
        }
        $this->db->where('stock.date <', $fromDate);
        $this->db->where('stock.form_id !=', 10);
        $this->db->where('stock.type', 'In');
        $ledgerIn = $this->db->get()->row();

        $this->db->select("sum(stock.quantity) as totalOut");
        $this->db->from("stock");
        $this->db->where('stock.dist_id', $distId);
        if (!empty($productId) && $productId != 'all') {
            $this->db->where('stock.product_id', $productId);
        }
        $this->db->where('stock.date <', $fromDate);
        $this->db->where('stock.form_id !=', 10);
        $this->db->where('stock.type', 'Out');
        $ledgerOut = $this->db->get()->row();
        return $ledgerIn->totalIn - $ledgerOut->totalOut;
    }

    public function checkProductFormate($productFormate, $disttId) {
        $this->db->select("*");
        $this->db->from("product");
        $this->db->where('category_id', $productFormate[1]);
        $this->db->where('product_id', $productFormate[2]);
        /*$this->db->group_start();
        $this->db->where('dist_id', $disttId);
        $this->db->or_where('dist_id', 1);
        $this->db->group_end();*/
        $exits = $this->db->get()->row();
        if (!empty($exits)):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function getPaymentDueSupplierCustomer($distId, $supCus) {
        if ($supCus == 1) {
           /* $this->db->select("sum(dr - cr) as totalDue,customer.customer_id,customer.customerID,customer.customerName");
            $this->db->from("client_vendor_ledger");
            $this->db->join('customer', 'customer.customer_id=client_vendor_ledger.client_vendor_id', 'left');
            $this->db->where('client_vendor_ledger.dist_id', $distId);
            $this->db->where('client_vendor_ledger.ledger_type', $supCus);
            $this->db->group_by('customer.customer_id');
            $this->db->having('sum(dr - cr) > ', 0);*/

            $this->db->select("*");
            $this->db->from("customer");
            
            
            $ledgerReport = $this->db->get()->result();
            return $ledgerReport;
        } else {
            $this->db->select("sum(dr - cr) as totalDue,supplier.sup_id,supplier.supID,supplier.supName");
            $this->db->from("client_vendor_ledger");
            $this->db->join('supplier', 'supplier.sup_id=client_vendor_ledger.client_vendor_id', 'left');
            $this->db->where('client_vendor_ledger.dist_id', $distId);
            $this->db->where('client_vendor_ledger.ledger_type', $supCus);
            $this->db->group_by('supplier.sup_id');
            $this->db->having('sum(dr - cr) > ', 0);
            $ledgerReport = $this->db->get()->result();
            return $ledgerReport;
        }
    }

    function getCylinderLedger($distId, $fromDate, $toDate) {
        $this->db->select("generals.voucher_no,stock.type,brand.brandName,productcategory.title,product.productName,product.product_code,stock.quantity,stock.date,customer.customerName,supplier.supName");
        $this->db->from("stock");
        $this->db->join('generals', 'generals.generals_id=stock.generals_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id=stock.category_id', 'left');
        $this->db->join('product', 'product.product_id=stock.product_id', 'left');
        $this->db->join('supplier', 'supplier.sup_id=generals.supplier_id', 'left');
        $this->db->join('customer', 'customer.customer_id=generals.customer_id', 'left');
        $this->db->join('brand', 'brand.brandId=product.brand_id', 'left');
        $this->db->where('stock.dist_id', $distId);
        $this->db->where('stock.date >=', $fromDate);
        $this->db->where('stock.date <=', $toDate);
        $this->db->where('stock.form_id !=', 10);
        $this->db->order_by('stock.date', 'asc');
        $this->db->where_in('stock.type', array('Cin', 'Cout'));
        $ledgerReport = $this->db->get()->result();
        return $ledgerReport;
    }

    function getSupplierClosingBalance($distId, $supplierId) {


        $customer_ledger=get_customer_supplier_product_ledger_id($supplierId, 2);
        $this->db->select(" sum(GR_CREDIT)-sum(GR_DEBIT) as totalCurrentBalance");
        $this->db->from("ac_tb_accounts_voucherdtl");

        $this->db->where('CHILD_ID', $customer_ledger->id);
        $result = $this->db->get()->row();
        return $result->totalCurrentBalance;
        /*$this->db->select("sum(dr) - sum(cr) as totalCurrentBalance");
        $this->db->from("client_vendor_ledger");
        $this->db->where('dist_id', $distId);
        $this->db->where('ledger_type', 2);
        $this->db->where('client_vendor_id', $supplierId);
        $result = $this->db->get()->row();
        return $result->totalCurrentBalance;*/
    }

    function getPurchasesList() {
        $this->db->select("form.name,generals.generals_id,generals.voucher_no,generals.narration,generals.date,generals.debit,supplier.supID,supplier.supName,supplier.sup_id,supplier.sup_id");
        $this->db->from("generals");
        $this->db->join('supplier', 'supplier.sup_id=generals.supplier_id');
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->where('generals.dist_id', $this->dist_id);
        $this->db->where('generals.form_id', 11);
        $this->db->order_by('generals.date', 'desc');
        $result = $this->db->get()->result();
        return $result;
    }

    function getCreditAmount($invoiceId) {
        $this->db->select("credit,generals_id,");
        $this->db->from("generals");
        $this->db->where("mainInvoiceId", $invoiceId);
        $this->db->where("form_id", 14);
        $this->db->where('dist_id', $this->dist_id);
        $creditAmount = $this->db->get()->row();
        if (!empty($creditAmount)) {
            return $creditAmount;
        }
    }

    function getPurchasesAccountId($invoiceId) {
        $this->db->select("account");
        $this->db->from("generalledger");
        $this->db->where("generals_id", $invoiceId);
        $this->db->where("form_id", 14);
        $this->db->where("credit >=", '1');
        $this->db->where('dist_id', $this->dist_id);
        $this->db->order_by('generalledger_id', 'ASC');
        $creditAccount = $this->db->get()->row();

        if (!empty($creditAccount->account)) {
            return $creditAccount->account;
        }
    }

    function getReturnAbleCylinder($distId, $invoiceId, $productId) {
        $this->db->select("generals_id");
        $this->db->from("generals");
        $this->db->where("dist_id", $distId);
        //form Id 24 means cylinder receive,form Id 23 means cylinder out
        $this->db->where("form_id", 24);
        $this->db->where("mainInvoiceId", $invoiceId);
        $generalId = $this->db->get()->row();
        if (!empty($generalId)) {
            $this->db->select('stock.quantity');
            $this->db->from('stock');
            $this->db->join('productcategory', 'stock.category_id = productcategory.category_id');
            $this->db->join('product', 'stock.product_id = product.product_id');
            $this->db->join('unit', 'stock.unit = unit.unit_id');
            $this->db->join('brand', 'product.brand_id = brand.brandId');
            $this->db->where('stock.dist_id', $distId);
            $this->db->where('stock.type', 'Cin');
            $this->db->where('stock.product_id', $productId);
            //type 24 means cylinder receive,type 23 means cylinder out
            $this->db->where('stock.form_id', 24);
            $this->db->where('stock.generals_id', $generalId->generals_id);
            $cylinderInOutResult = $this->db->get()->row();
            return $cylinderInOutResult;
        }
    }

    function getReturnAbleCylinder2($distId, $invoiceId, $productId) {
        $this->db->select("quantity");
        $this->db->from("stock");
        $this->db->where("dist_id", $distId);
        //form Id 24 means cylinder receive,form Id 23 means cylinder out
        $this->db->where("form_id", 24);
        $this->db->where("product_id", $productId);
        $this->db->where("generals_id", $invoiceId);
        $generalId = $this->db->get()->row();
        return $generalId;
//        if (!empty($generalId)) {
//            $this->db->select('stock.quantity');
//            $this->db->from('stock');
//            $this->db->join('productcategory', 'stock.category_id = productcategory.category_id');
//            $this->db->join('product', 'stock.product_id = product.product_id');
//            $this->db->join('unit', 'stock.unit = unit.unit_id');
//            $this->db->join('brand', 'product.brand_id = brand.brandId');
//            $this->db->where('stock.dist_id', $distId);
//            $this->db->where('stock.type', 'Cin');
//            $this->db->where('stock.product_id', $productId);
//            //type 24 means cylinder receive,type 23 means cylinder out
//            $this->db->where('stock.form_id', 24);
//            $this->db->where('stock.generals_id', $generalId->generals_id);
//            $cylinderInOutResult = $this->db->get()->row();
//            return $cylinderInOutResult;
//        }
    }

    function getVoucherIdByGeneralId($generalId) {
        $voucehrInfo = $this->db->get_where('generals', array('generals_id' => $generalId))->row();
        if (!empty($voucehrInfo)) {
            return $voucehrInfo->voucher_no;
        } else {
            return 0;
        }
    }

    function getCustomerOrSupplierIdByGeneralId($subCusId,$type="cus") {

        if ($type=="cus") {
            $customerInfo = $this->db->get_where('customer', array('customer_id' => $subCusId))->row();
            if (!empty($customerInfo)) {
                return $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
            } else {
                return 0;
            }
        } elseif ($type=="sup") {
            $supplierInfo = $this->db->get_where('supplier', array('sup_id' => $subCusId))->row();
            if (!empty($supplierInfo)) {
                return $supplierInfo->supID . ' [ ' . $supplierInfo->supName . ' ] ';
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function getProductSummationReport($dist_id, $productid, $startDate, $endDate,$branch_id) {

        $query = "SELECT purchase_details.product_id,
SUM(purchase_details.quantity) AS QTY,
(SUM((purchase_details.quantity*purchase_details.unit_price))/SUM(purchase_details.quantity)) AS unitPrice,
product.productName,
product.product_code,
productcategory.title,
brand.brandName,

IFNULL(branch.branch_name,'NA') as branch_name
FROM 
purchase_details
LEFT JOIN purchase_invoice_info ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id
 JOIN product ON purchase_details.product_id=product.product_id
LEFT JOIN productcategory on productcategory.category_id=product.category_id
LEFT JOIN brand on brand.brandId=product.brand_id
LEFT JOIN branch on branch.branch_id=purchase_invoice_info.branch_id
WHERE 
purchase_details.is_active='Y'
AND purchase_details.is_delete='N'
AND purchase_details.show_in_invoice=1
             AND purchase_invoice_info.invoice_date >= '" . $startDate . "'
                AND purchase_invoice_info.invoice_date <= '" . $endDate . "'" ;
if($branch_id!='all'){
    $query  .= " AND purchase_invoice_info.branch_id = " . $branch_id ;
    $query  .= "     GROUP BY purchase_details.product_id";
}else{
    $query  .= "     GROUP BY purchase_details.product_id,purchase_invoice_info.branch_id";
}

$query.=" ORDER BY branch.branch_name";


        $query = $this->db->query($query);
        $result = $query->result();
        return $result;



        /*$this->db->select("sum(stock.quantity) as totalQty,avg(stock.rate) as totalAvgRate,product.productName,brand.brandName");
        $this->db->from("stock");
        $this->db->join('product', 'product.product_id=stock.product_id');
        $this->db->join('brand', 'brand.brandId=product.brand_id');
        //$this->db->join('supplier', 'supplier.sup_id=generals.supplier_id');
        $this->db->where("stock.product_id", $productid);
        $this->db->where("stock.type", "In");
        $this->db->where('stock.date >=', $startDate);
        $this->db->where('stock.date <=', $endDate);
        $this->db->where("stock.dist_id", $dist_id);
        $totalStockIn = $this->db->get()->row();
        return $totalStockIn;*/
    }

    function getProductWiseSalesReport($dist_id, $productid, $startDate, $endDate,$branch_id) {
        $this->db->select("purchase_details.product_id,
	purchase_details.quantity,
	purchase_details.unit_price,
	purchase_invoice_info.invoice_no,
	purchase_invoice_info.invoice_date,
	purchase_invoice_info.purchase_invoice_id,
	purchase_invoice_info.supplier_id,IFNULL(branch.branch_name,'NA') as branch_name");
        $this->db->from("purchase_details");
        $this->db->join('purchase_invoice_info', 'purchase_invoice_info.purchase_invoice_id=purchase_details.purchase_invoice_id');
        $this->db->join('branch', 'branch.branch_id=purchase_invoice_info.branch_id', 'left');
        $this->db->where("purchase_details.product_id", $productid);
        //$this->db->where("purchase_details.is_opening", "0");
        $this->db->where("purchase_details.is_active", "Y");
        $this->db->where("purchase_details.is_delete", "N");
        $this->db->where("purchase_details.show_in_invoice", "1");
        $this->db->where('purchase_invoice_info.invoice_date >=', $startDate);
        $this->db->where('purchase_invoice_info.invoice_date <=', $endDate);

        if($branch_id!='all'){
            $this->db->where("purchase_invoice_info.branch_id", $branch_id);
        }
        $this->db->order_by('branch.branch_name', 'ASC');
        $totalStockIn = $this->db->get()->result();
        return $totalStockIn;
    }

    function generals_supplier($supplier_id,$BranchAutoId=null) {






        $query="SELECT
                    purchase_invoice_info.invoice_no,
                    purchase_invoice_info.invoice_date,
                    purchase_invoice_info.purchase_invoice_id,
                    purchase_invoice_info.invoice_amount,
                    purchase_invoice_info.paid_amount,
                    IFNULL(supplier_due_collection_details.due_paid_amount,0	)AS due_paid_amount,
                    (purchase_invoice_info.invoice_amount -(purchase_invoice_info.paid_amount + IFNULL(supplier_due_collection_details.due_paid_amount,0)))AS amount
                FROM
                    purchase_invoice_info
                LEFT JOIN(SELECT
                        purchase_invoice_id,
                        SUM(supplier_due_collection_details.paid_amount)AS due_paid_amount
                    FROM
                        supplier_due_collection_details /*WHERE
                                        cus_due_collection_details.sales_invoice_id = 1*/
                    GROUP BY
                        supplier_due_collection_details.purchase_invoice_id 
                )AS supplier_due_collection_details ON supplier_due_collection_details.purchase_invoice_id = purchase_invoice_info.purchase_invoice_id
                WHERE
                    purchase_invoice_info.payment_type IN(2, 4)/*payment type chash and full credit*/
                AND purchase_invoice_info.supplier_id =" .$supplier_id  ;

        if($BranchAutoId!='all' || $BranchAutoId!=null){
            $query.=" AND branch_id=".$BranchAutoId;
        }

                $query.="  HAVING
                    amount > 0";
        $query = $this->db->query($query);
        $result['invoice_list'] = $query->result_array();
        return $result;





        //$query = $this->db->get_where('generals', array('form_id' => 11, 'supplier_id' => $supplier_id));
        //return $query->result_array();
    }

    function generals_voucher($voucher_no) {
        $dr = '';
        $cr = '';
        $query = $this->db->get_where('generals', array('voucher_no' => $voucher_no, 'dist_id' => $this->dist_id))->result_array();
        foreach ($query as $row) {
            $dr += $row['debit'];
            $cr += $row['credit'];
        }
        $bal = $dr - $cr;
        return $bal;
    }

    public function getStockReport($startDate, $endDate, $productid, $brandId, $distributor = null) {
        if (!empty($distributor)):
            $distID = $distributor;
        else:
            $distID = $this->dist_id;
        endif;
        //openingStock
        $this->db->select('avg(stock.rate) totalAvgSalesPrice');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('stock.type', 'Out');
        $this->db->where('stock.dist_id', $distID);
        if ($brandId != 'All') {
            $this->db->where('product.brand_id', $brandId);
        }
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $openingAvgSalesPrice = $this->db->get()->row_array();
        $data['totalAvgSalesPrice'] = $openingAvgSalesPrice['totalAvgSalesPrice'];
        $this->db->select('avg(stock.rate) totalAvgPusPrice');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('stock.type', 'In');
        if ($brandId != 'All') {
            $this->db->where('product.brand_id', $brandId);
        }
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $openingAvgPurcPrice = $this->db->get()->row_array();
        $data['totalAvgPurcPrice'] = $openingAvgPurcPrice['totalAvgPusPrice'];
        $this->db->select('product.product_id,product.category_id,product.productName,product.product_code,productcategory.title as catName,sum(stock.quantity) as totalOpeQty,avg(stock.rate) totalAvgOpePusPrice,');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('stock.type', 'In');
        if ($brandId != 'All') {
            $this->db->where('product.brand_id', $brandId);
        }
        $this->db->where('stock.openingStatus', 1);
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $data['mainOpeningStock'] = $this->db->get()->row_array();
        $this->db->select('product.product_id,product.category_id,product.productName,product.product_code,productcategory.title as catName,sum(stock.quantity) as totalOpeQty,avg(stock.rate) totalAvgOpePusPrice,');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('date <', $startDate);
        if ($brandId != 'All') {
            $this->db->where('product.brand_id', $brandId);
        }
        $this->db->where('stock.type', 'In');
        $this->db->where('stock.openingStatus !=', 1);
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $data['openingStock'] = $this->db->get()->row_array();
        $this->db->select('avg(stock.rate) as averagePurchasesPrice,product.product_id,product.category_id,product.productName,product.product_code,productcategory.title as catName,sum(stock.quantity) as totalOpeQty,avg(stock.rate) totalAvgOpePusPrice,');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('date <', $startDate);
        if ($brandId != 'All') {
            $this->db->where('product.brand_id', $brandId);
        }
        $this->db->where('stock.type', 'Out');
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $data['openingOut'] = $this->db->get()->row_array();
        //purchases stock
        $this->db->select('product.product_id,product.category_id,product.productName,product.product_code,productcategory.title as catName,sum(stock.quantity) as totalPurcQty,avg(stock.rate) totalAvgPusPrice,');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        if ($brandId != 'All') {
            $this->db->where('product.brand_id', $brandId);
        }
        $this->db->where('stock.type', 'In');
        $this->db->where('stock.openingStatus !=', 1);
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $data['purchasesStock'] = $this->db->get()->row_array();
        //Sale Out
        $this->db->select('product.product_id,product.category_id,product.productName,product.product_code,productcategory.title as catName,sum(stock.quantity) as totalSaleQty,avg(stock.rate) totalAvgSalePusPrice,');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('date >=', $startDate);
        $this->db->where('date <=', $endDate);
        if ($brandId != 'All') {
            $this->db->where('product.brand_id', $brandId);
        }
        $this->db->where('stock.type', 'Out');
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $data['saleStock'] = $this->db->get()->row_array();
        return $data;
    }
    //$type_id, $balance_type, $sub_cus_id, $from_date, $to_date, $this->dist_id
    function getCusSupProductdetails($type_id, $balance_type, $sub_cus_id, $fromDate, $to_date, $distId) {
        $sql = "";
        if ($type_id == 2) {
            //customer
            if ($sub_cus_id != 'all') {
                $sql = "AND subCusTable.customer_id = " . $sub_cus_id;
            }


            $query = "SELECT
                        subCusTable.customer_id,
                        subCusTable.customerID AS subCusId,
                        subCusTable.customerName AS subCusName,
                        s.product_id,
                        s.type,
                        s.quantity,
                        InTable.StockIn,
                        OutTable.StockOut,
                        (
                            InTable.StockIn - OutTable.StockOut
                        ) AS balance,
                        InTable.product_id,
                        productTable.productName,
                        productTable.product_code,
                        productTable.brandName
                    FROM
                        customer subCusTable
                    LEFT JOIN stock s ON
                        s.customerId = subCusTable.customer_id
                                        LEFT JOIN(
                    SELECT p.product_id,
                        p.brand_id,
                        p.category_id,
                        p.productName,
                        p.product_code,
                        b.brandName,
                        pc.title
                    FROM
                        product p
                    LEFT JOIN productcategory pc ON
                        pc.category_id = p.category_id
                    LEFT JOIN brand b ON
                        b.brandId = p.brand_id
                ) AS productTable
                ON
                    productTable.product_id = s.product_id
                    LEFT JOIN(
                        SELECT
                            SUM(s.quantity) AS StockIn,
                            s.product_id,
                            s.type,
                            s.customerId
                        FROM
                            stock s
                        WHERE
                            s.type = 'Cin' AND s.dist_id = " . $distId . "
                        GROUP BY
                            s.product_id,
                            s.customerId
                    ) AS InTable
                    ON
                        InTable.customerId = s.customerId AND InTable.product_id = s.product_id
                    LEFT JOIN(
                        SELECT
                            SUM(s.quantity) AS StockOut,
                            s.product_id,
                            s.type,
                            s.customerId
                        FROM
                            stock s
                        WHERE
                            s.type = 'Cout' AND s.dist_id = " . $distId . "
                        GROUP BY
                            s.product_id,
                            s.customerId
                    ) AS OutTable
                    ON
                        OutTable.customerId = s.customerId AND OutTable.product_id = s.product_id
                    WHERE
                        subCusTable.dist_id = " . $distId . "  " . $sql . " AND  s.date >= '$fromDate' AND s.date <= '$to_date'
                    GROUP BY
                        s.product_id,
                        s.customerId  order  BY subCusTable.customer_id ASC   ";
        }else
         {

            //customer
            if ($sub_cus_id != 'all') {
                $sql = "AND subCusTable.sup_id = " . $sub_cus_id;
            }


            $query = "SELECT
                        subCusTable.sup_id,
                        subCusTable.supID AS subCusId,
                        subCusTable.supName AS subCusName,
                        s.product_id,
                        s.type,
                        s.quantity,
                        InTable.StockIn,
                        OutTable.StockOut,
                        (
                            InTable.StockIn - OutTable.StockOut
                        ) AS balance,
                        InTable.product_id,
                        productTable.productName,
                        productTable.product_code,
                        productTable.brandName
                    FROM
                        supplier subCusTable
                    LEFT JOIN stock s ON
                        s.supplierId = subCusTable.sup_id
                                        LEFT JOIN(
                    SELECT p.product_id,
                        p.brand_id,
                        p.category_id,
                        p.productName,
                        p.product_code,
                        b.brandName,
                        pc.title
                    FROM
                        product p
                    LEFT JOIN productcategory pc ON
                        pc.category_id = p.category_id
                    LEFT JOIN brand b ON
                        b.brandId = p.brand_id
                ) AS productTable
                ON
                    productTable.product_id = s.product_id
                    LEFT JOIN(
                        SELECT
                            SUM(s.quantity) AS StockIn,
                            s.product_id,
                            s.type,
                            s.supplierId
                        FROM
                            stock s
                        WHERE
                            s.type = 'Cin' AND s.dist_id = " . $distId . "
                        GROUP BY
                            s.product_id,
                            s.supplierId
                    ) AS InTable
                    ON
                        InTable.supplierId = s.supplierId AND InTable.product_id = s.product_id
                    LEFT JOIN(
                        SELECT
                            SUM(s.quantity) AS StockOut,
                            s.product_id,
                            s.type,
                            s.supplierId
                        FROM
                            stock s
                        WHERE
                            s.type = 'Cout' AND s.dist_id = " . $distId . "
                        GROUP BY
                            s.product_id,
                            s.supplierId
                    ) AS OutTable
                    ON
                        OutTable.supplierId = s.supplierId AND OutTable.product_id = s.product_id
                    WHERE
                        subCusTable.dist_id = " . $distId . "  " . $sql . " AND  s.date >= '$fromDate' AND s.date <= '$to_date'
                    GROUP BY
                        s.product_id,
                        s.customerId  order  BY subCusTable.sup_id ASC   ";
        }
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    function sales_report_brand_wise($start_date, $end_date,  $brandId) {


                        $query = "SELECT
                            brand.brandId,
                            brand.brandName,
                            product.productName,
                            CAST(product.productName AS UNSIGNED) as p_name,
                            unit.unitTtile,
                            IFNULL(sales_package_qty_table.sales_package_qty,	0	)AS sales_package_qty,
                            IFNULL(sales_refill_qty_table.sales_refill_qty,	0	)AS sales_refill_qty,
                            IFNULL(sales_empty_qty_table.sales_empty_qty,0)AS sales_empty_qty,
                            IFNULL(sales_refill_qty_table.sales_returnable_quantity,0	)AS sales_returnable_quantity,
                            IFNULL(sales_refill_qty_table.sales_return_quentity,0	)AS sales_return_quentity,
                            IFNULL(sales_refill_qty_table.sales_customer_due,0	)AS sales_customer_due,
                            IFNULL(sales_refill_qty_table.sales_customer_advance,	0	)AS sales_customer_advance
                        FROM
                            brand
                        LEFT JOIN product ON product.brand_id = brand.brandId
                        LEFT JOIN unit ON unit.unit_id=product.unit_id
                        LEFT JOIN(
                        SELECT
                                product.product_id,
                                product.brand_id,
                                product.productName, 
                                sales_details.insert_date,
                                SUM(IFNULL(sales_details.quantity, 0))AS sales_package_qty,
                                SUM(IFNULL(sales_details.returnable_quantity,0))AS sales_returnable_quantity,
                                SUM(IFNULL(sales_details.return_quentity,	0))AS sales_return_quentity,
                                SUM(IFNULL(sales_details.customer_due,0))AS sales_customer_due,
                                SUM(IFNULL(sales_details.customer_advance,0))AS sales_customer_advance,
                                product.category_id
                            FROM
                                product
                            LEFT JOIN sales_details ON sales_details.product_id = product.product_id
                            LEFT JOIN sales_invoice_info on sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id
                            WHERE
                                product.category_id = 2
                        AND sales_details.is_package = 1
                        AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                        AND sales_invoice_info.invoice_date <= '" . $end_date . "'
                            GROUP BY
                                product.brand_id,
                                product.productName
                        )AS sales_package_qty_table ON sales_package_qty_table.brand_id = brand.brandId
                        AND sales_package_qty_table.productName = product.productName
                        AND sales_package_qty_table.category_id = 2
                        LEFT JOIN(
                        SELECT
                                product.product_id,
                                product.brand_id,
                                product.productName,
                                SUM(IFNULL(sales_details.quantity, 0))AS sales_refill_qty,
                                SUM(IFNULL(sales_details.returnable_quantity,0))AS sales_returnable_quantity,
                                SUM(IFNULL(sales_details.return_quentity,	0))AS sales_return_quentity,
                                SUM(IFNULL(sales_details.customer_due,0))AS sales_customer_due,
                                SUM(IFNULL(sales_details.customer_advance,0))AS sales_customer_advance,
                                product.category_id
                            FROM
                                product
                            LEFT JOIN sales_details ON sales_details.product_id = product.product_id
                            LEFT JOIN sales_invoice_info on sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id
                        
                            WHERE
                                product.category_id = 2
                        AND sales_details.is_package = 0
                         AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                        AND sales_invoice_info.invoice_date <= '" . $end_date . "'
                            GROUP BY
                                product.brand_id,
                                product.productName
                        )AS sales_refill_qty_table ON sales_refill_qty_table.brand_id = brand.brandId
                        AND sales_refill_qty_table.productName = product.productName
                        AND sales_refill_qty_table.category_id = 2
                        LEFT JOIN(
                            SELECT
                                product.brand_id,
                                product.category_id,
                            product.productName,
                                (SUM(IFNULL(sales_details.quantity, 0))- IFNULL(SUM(sales_return_details.return_quantity),0))AS sales_empty_qty
                            FROM
                                product
                            LEFT JOIN sales_details ON sales_details.product_id = product.product_id
                            AND sales_details.is_package = 0
                            LEFT JOIN sales_return_details ON sales_return_details.product_id = product.product_id
                            LEFT JOIN sales_invoice_info on sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id
                            WHERE
                                product.category_id = 1
                                 AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                        AND sales_invoice_info.invoice_date <= '" . $end_date . "'
                            GROUP BY
                                product.brand_id,
                                product.productName
                        )AS sales_empty_qty_table ON sales_empty_qty_table.brand_id = brand.brandId
                        AND sales_empty_qty_table.productName = product.productName
                        AND sales_empty_qty_table.category_id = 1
                        WHERE
                            1 = 1
                             AND product.category_id IN (1,2)
                            ";
                        if($brandId !='0'){
                            $query.=" AND brand.brandId=".$brandId;
                        }
                         $query.="  GROUP BY
                            product.brand_id,
                            product.productName
                        ORDER BY brand.brandId,product.productName";

                                    log_message('error', 'error'. print_r($query,TRUE));

        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }


    public function  current_stock_backup($distId){
        $query="SELECT
CONCAT(productcategory.title,' ',product.productName,' [ ',	brand.brandName,' ]')AS productName,
product.product_id,
/*	product.productName,
	product.product_code,
	product.category_id,
	product.unit_id,
	product.brand_id,
	productcategory.title AS product_category,
	unit.unitTtile AS product_unit,
	brand.brandName,*/
  IFNULL(product_purchase.product_purchase_qty,0) as product_purchase_qty,
  IFNULL(product_purchase.purchase_price,0) AS purchase_price,
  
  IFNULL(product_purchase_return.product_pur_return_quantity,0) as product_pur_return_quantity,

  IFNULL(product_sales.product_sales_qty,0) as product_sales_qty,
  IFNULL(product_sales.sales_price,0) AS sales_price,
IFNULL(product_sales_return.product_sales_return_quantity,0) as product_sales_return_quantity
  

FROM
	product
LEFT JOIN productcategory ON productcategory.category_id = product.category_id
LEFT JOIN unit ON unit.unit_id = product.unit_id
LEFT JOIN brand ON brand.brandId = product.brand_id
LEFT JOIN (/*get all product purchase quentity sum  and average price by product id*/
   SELECT
    purchase_details.product_id,
		SUM(purchase_details.quantity) AS product_purchase_qty,
		AVG(purchase_details.unit_price)AS purchase_price
		FROM
			purchase_details
		WHERE
			purchase_details.is_active = 'Y'
		AND purchase_details.is_delete = 'N'
		/*AND purchase_details.is_package = 0*/
		GROUP BY  purchase_details.product_id) AS product_purchase 
ON product_purchase.product_id=product.product_id
LEFT JOIN (/*get all product purchase RETURN quentity sum by product id*/
		SELECT
			purchase_return_details.product_id,
			SUM(purchase_return_details.return_quantity	)AS product_pur_return_quantity
		FROM
			purchase_return_details
		WHERE
			purchase_return_details.is_active = 'Y'
		AND purchase_return_details.is_delete = 'N'
		GROUP BY
			purchase_return_details.product_id
) AS product_purchase_return
ON product_purchase_return.product_id=product.product_id
LEFT JOIN(/*get all product sales quentity sum  and average sales price by product id*/
		SELECT
      sales_details.product_id,
			SUM(sales_details.quantity) AS product_sales_qty,
		AVG(sales_details.unit_price)AS sales_price
		FROM
			sales_details
		WHERE
			sales_details.is_active = 'Y'
		AND sales_details.is_delete = 'N'
		AND sales_details.is_package = 0
		GROUP BY  sales_details.product_id

) AS product_sales 
ON product_sales.product_id=product.product_id

LEFT JOIN (/*get all product purchase RETURN quentity sum by product id*/
		SELECT
			sales_return_details.product_id,
			SUM(sales_return_details.return_quantity	)AS product_sales_return_quantity
		FROM
			sales_return_details
		WHERE
			sales_return_details.is_active = 'Y'
		AND sales_return_details.is_delete = 'N'
		GROUP BY
			sales_return_details.product_id
) AS product_sales_return
ON product_sales_return.product_id=product.product_id
WHERE
	1 = 1";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }


    public function  current_stock($distId,$startDate,$endDate,$productId,$branch_id='all'){


        $query="SELECT
	product.product_id,
CONCAT(productcategory.title,' ',product.productName,' [ ',	brand.brandName,' ]')AS productName,
	SUM(purchase_details.quantity)+ IFNULL(purchase_return_details.return_empty_qty,0) AS tt_quantity,
	purchase_return_details.return_empty_qty,
sales_details.quantity AS sales_qty,
	(IFNULL((SUM(purchase_details.quantity * purchase_details.unit_price)),0)- IFNULL((SUM(purchase_return_details.return_empty_qty * purchase_return_details.avg_purchase_return_price))+ exchange_cylinder.exchange_cylinder_price,0)-IFNULL((SUM(sales_details.quantity * sales_details.unit_price)),0)- IFNULL((SUM(sales_return_details.product_id * sales_return_details.avg_purchase_return_price_sales)),0))AS tt_price,
	SUM(purchase_details.quantity * purchase_details.unit_price)/ SUM(purchase_details.quantity)AS avg_purchase_price,
	product.category_id,pii.branch_id,branch.branch_name
FROM
	product
LEFT JOIN productcategory ON productcategory.category_id = product.category_id
LEFT JOIN unit ON unit.unit_id = product.unit_id
LEFT JOIN brand ON brand.brandId = product.brand_id
LEFT JOIN purchase_details ON purchase_details.product_id = product.product_id
Left JOIN purchase_invoice_info pii ON pii.purchase_invoice_id=purchase_details.purchase_invoice_id
Left JOIN branch branch ON branch.branch_id=pii.branch_id
LEFT JOIN(
	SELECT
		SUM(purchase_return_details.return_quantity * purchase_return_details.unit_price)/ SUM(return_quantity)AS avg_purchase_return_price,
		IFNULL(sum(purchase_return_details.return_quantity),0)AS return_empty_qty,
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
Left JOIN sales_invoice_info sii ON sales_details.sales_invoice_id=sii.sales_invoice_id
AND sii.invoice_date >= '" . $startDate . "'
AND sii.invoice_date <= '" . $endDate . "'
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
AND pii.invoice_date >= '" . $startDate . "'
        AND pii.invoice_date <= '" . $endDate . "'
";
        if($productId !='all'){
            $query.=" AND product.product_id=".$productId   ;
        }
        if($branch_id !='all'){
            $query.=" AND pii.branch_id=".$branch_id   ;
        }
        $query.=" GROUP BY	product.product_id,pii.branch_id     ORDER BY branch.branch_name";
//log_message('error','this is a query '.print_r($query.true));
        $query2="SELECT
CONCAT(productcategory.title,' ',product.productName,' [ ',	brand.brandName,' ]')AS productName,
product.product_id,

  IFNULL(product_purchase.product_purchase_qty,0) as product_purchase_qty,
  IFNULL(product_purchase.purchase_price,0) AS purchase_price,
  
  IFNULL(product_purchase_return.product_pur_return_quantity,0) as product_pur_return_quantity,

  IFNULL(product_sales.product_sales_qty,0) as product_sales_qty,
  IFNULL(product_sales.sales_price,0) AS sales_price,
IFNULL(product_sales_return.product_sales_return_quantity,0) as product_sales_return_quantity,
product_purchase.invoice_date
  

FROM
	product
LEFT JOIN productcategory ON productcategory.category_id = product.category_id
LEFT JOIN unit ON unit.unit_id = product.unit_id
LEFT JOIN brand ON brand.brandId = product.brand_id
LEFT JOIN (/*get all product purchase quentity sum  and average price by product id*/
   SELECT
    pd.product_id,
		SUM(pd.quantity) AS product_purchase_qty,
		AVG(pd.unit_price)AS purchase_price,
		pii.invoice_date
		FROM
			purchase_details AS pd
Left JOIN purchase_invoice_info pii ON pii.purchase_invoice_id=pd.purchase_invoice_id
		WHERE
			pd.is_active = 'Y'
		AND pd.is_delete = 'N'
		AND pii.invoice_date >= '" . $startDate . "'
        AND pii.invoice_date <= '" . $endDate . "'
		GROUP BY  pd.product_id) AS product_purchase 
ON product_purchase.product_id=product.product_id
LEFT JOIN (/*get all product purchase RETURN quentity sum by product id*/
		SELECT
			prd.product_id,
			SUM( prd.return_quantity	)AS product_pur_return_quantity,
      pii.invoice_date
		FROM
			purchase_return_details as prd
Left JOIN purchase_invoice_info pii ON pii.purchase_invoice_id=prd.purchase_invoice_id
		WHERE
			 prd.is_active = 'Y'
		AND  prd.is_delete = 'N'
		AND pii.invoice_date >= '" . $startDate . "'
        AND pii.invoice_date <= '" . $endDate . "'
		GROUP BY
			 prd.product_id
) AS product_purchase_return
ON product_purchase_return.product_id=product.product_id
LEFT JOIN(/*get all product sales quentity sum  and average sales price by product id*/
		SELECT
      sd.product_id,
			SUM(sd.quantity) AS product_sales_qty,
		AVG(sd.unit_price)AS sales_price,
		sii.invoice_date
		FROM
			sales_details as sd
Left JOIN sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
		WHERE
			sd.is_active = 'Y'
		AND sd.is_delete = 'N'
		AND sii.invoice_date >= '" . $startDate . "'
        AND sii.invoice_date <= '" . $endDate . "'
		
		GROUP BY  sd.product_id

) AS product_sales 
ON product_sales.product_id=product.product_id

LEFT JOIN (/*get all product purchase RETURN quentity sum by product id*/
		SELECT
			srd.product_id,
			SUM(srd.return_quantity	)AS product_sales_return_quantity,
			sii.invoice_date
		FROM
			sales_return_details AS srd
Left JOIN sales_invoice_info sii ON sii.sales_invoice_id=srd.sales_invoice_id
		WHERE
			srd.is_active = 'Y'
		AND srd.is_delete = 'N'
		AND sii.invoice_date >= '" . $startDate . "'
        AND sii.invoice_date <= '" . $endDate . "'
		GROUP BY
			srd.product_id
) AS product_sales_return
ON product_sales_return.product_id=product.product_id
WHERE
	1 = 1";

        if($productId !='all'){
            $query.=" AND product.product_id=".$productId;
        }
        //log_message('error','This is stock query  '.print_r($query,true));
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function stock_report($productCatagory,$productBrand,$productId,$startDate,$endDate){
        $Catagory=" AND 1=1";
        $Brand=" AND 1=1";
        $product=" AND 1=1";
        if($productCatagory !='All'){
            $Catagory=" AND AllOPSC.category_id=".$productCatagory;
        }
        if($productBrand !='All'){
            $Brand=" AND AllOPSC.brand_id=".$productBrand;
        }if($productId !='All'){
            $product=" AND AllOPSC.product_id=".$productId;
        }



        $query2="SELECT  product_id,  productName,  category_id, title, unit_id,  unitTtile , brand_id, brandName,
/*Opening*/
IFNULL(IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0),0) Opening_Qty,
IFNULL(((IFNULL(quantity_Open,0) * IFNULL(U_Price_opening,0)) - (IFNULL(quantity_OpenRe,0) * IFNULL(U_Price_openingRe,0)))
/ NULLIF((IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0)),0),0 ) Opening_U_Price,

 IFNULL((IFNULL(IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0),0)) *
IFNULL(((IFNULL(quantity_Open,0) * IFNULL(U_Price_opening,0)) - (IFNULL(quantity_OpenRe,0) * IFNULL(U_Price_openingRe,0)))
/ NULLIF((IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0)),0),0 ),0) Opening_Amonut,
/*END Opening*/

/*--Purchase_In*/
IFNULL(IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0),0) Purchase_Qty,
IFNULL(((IFNULL(quantity_P,0) * IFNULL(U_Price_Purchase_In,0)) - (IFNULL(quantity_PRe,0) * IFNULL(U_Price_PRe,0)))
/ NULLIF((IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0)),0),0 ) Purchase_U_Price,

 IFNULL((IFNULL(IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0),0)) *
IFNULL(((IFNULL(quantity_P,0) * IFNULL(U_Price_Purchase_In,0)) - (IFNULL(quantity_PRe,0) * IFNULL(U_Price_PRe,0)))
/ NULLIF((IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0)),0),0 ),0) Purchase_Amount,
/*END Purchase_In*/


/*Sales_Out*/
IFNULL(IFNULL(sales_P,0) + IFNULL(sales_SRe,0),0) Sales_Qty,

IFNULL(((IFNULL(sales_P,0) * IFNULL(U_Price_sales_out,0)) + (IFNULL(sales_SRe,0) * IFNULL(U_Price_sales_SRe,0)))
/ NULLIF((IFNULL(sales_P,0) + IFNULL(sales_SRe,0)),0),0 ) Sales_U_Price,

 IFNULL((IFNULL(IFNULL(sales_P,0) + IFNULL(sales_SRe,0),0)) *
IFNULL(((IFNULL(sales_P,0) * IFNULL(U_Price_sales_out,0)) + (IFNULL(sales_SRe,0) * IFNULL(U_Price_sales_SRe,0)))
/ NULLIF((IFNULL(sales_P,0) + IFNULL(sales_SRe,0)),0),0 ),0) Sales_Amount,
/*END Sales_Out*/
/*Closing*/
IFNULL((Closing_Qty+Closing_QtyRe),0) Closing_Qty,

IFNULL(((Closing_Qty*Closing_U_price)+(Closing_QtyRe*Closing_U_price_Re))/  NULLIF((Closing_Qty+Closing_QtyRe),0),0) Closing_U_Price,
IFNULL((Closing_Qty+Closing_QtyRe)* (((Closing_Qty*Closing_U_price)+(Closing_QtyRe*Closing_U_price_Re))/  NULLIF((Closing_Qty+Closing_QtyRe),0)),0) Closing_Amount
/*END Closing*/
FROM

(

SELECT
 BaseT.product_id, BaseT.productName, BaseT.category_id,productcategory.title,BaseT.unit_id, unit.unitTtile ,BaseT.brand_id,brand.brandName,
/*Opening*/
IFNULL(Opening.quantity_Open,0) quantity_Open ,
IFNULL(Opening.U_Price_opening,0) U_Price_opening,
IFNULL(IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0),0) Amount_opening,

IFNULL(Ret_Opening.quantity_OpenRe,0) quantity_OpenRe ,
IFNULL(Ret_Opening.U_Price_openingRe,0) U_Price_openingRe,
IFNULL(IFNULL(Ret_Opening.quantity_OpenRe,0)*
IFNULL(Ret_Opening.U_Price_openingRe,0),0) Amount_U_Price_openingRe,
/*END Opening*/

/*Purchase_In*/
 
IFNULL(Purchase_In.quantity_P,0) quantity_P,

IFNULL(Purchase_In.U_Price_Purchase_In,0) U_Price_Purchase_In,

IFNULL(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0),0) Amount_Purchase_In ,

IFNULL(Ret_Purchase.quantity_PRe ,0) quantity_PRe,
IFNULL(Ret_Purchase.U_Price_PRe,0) U_Price_PRe,

IFNULL(IFNULL(Ret_Purchase.quantity_PRe,0) *
IFNULL(Ret_Purchase.U_Price_PRe,0),0) Amount_Purchase_In_Re  ,

/*END Purchase_In*/

/*sales_out*/
 
IFNULL(sales_out.sales_P,0) sales_P , 
IFNULL(sales_out.U_Price_sales_out,0) U_Price_sales_out,

IFNULL(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0),0) Amount_sales_out,


IFNULL(Ret_Sales.sales_SRe ,0) sales_SRe ,
IFNULL(Ret_Sales.U_Price_sales_SRe,0) U_Price_sales_SRe,

IFNULL(IFNULL(Ret_Sales.sales_SRe ,0) *
IFNULL(Ret_Sales.U_Price_sales_SRe,0),0) Amount_sales_SRe

/*END sales_out*/

/*Closing*/
, IFNULL((IFNULL(Opening.quantity_Open,0)+IFNULL(Purchase_In.quantity_P,0)-IFNULL(sales_out.sales_P,0)),0) AS Closing_Qty

,IFNULL( (((IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0))+(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0) )-(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0))) )/ 
NULLIF((IFNULL((IFNULL(Opening.quantity_Open,0)+IFNULL(Purchase_In.quantity_P,0)-IFNULL(sales_out.sales_P,0)),0)),0),0) AS Closing_U_price
,
IFNULL(((IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0))+(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0) )-(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0))),0)  Amount_Closing


,IFNULL((IFNULL(Ret_Opening.quantity_OpenRe,0)+IFNULL(Ret_Purchase.quantity_PRe,0)-IFNULL(Ret_Sales.sales_SRe,0)),0) AS Closing_QtyRe


,IFNULL( IFNULL( (IFNULL(Ret_Opening.quantity_OpenRe,0) * IFNULL(Ret_Opening.U_Price_openingRe,0)) + 
(IFNULL(Ret_Purchase.quantity_PRe,0) * IFNULL(Ret_Purchase.U_Price_PRe,0) )-
(IFNULL(Ret_Sales.sales_SRe,0) * IFNULL(Ret_Sales.U_Price_sales_SRe,0)),0) / 
NULLIF((IFNULL(Ret_Opening.quantity_OpenRe,0)+IFNULL(Ret_Purchase.quantity_PRe,0)-IFNULL(Ret_Sales.sales_SRe,0)),0),0) AS Closing_U_price_Re

,
IFNULL(((IFNULL(Ret_Opening.quantity_OpenRe,0)*
IFNULL(Ret_Opening.U_Price_openingRe,0))+(IFNULL(Ret_Opening.quantity_OpenRe,0) *
IFNULL(Ret_Purchase.U_Price_PRe,0) )-(IFNULL(Ret_Sales.sales_SRe,0) *
IFNULL(Ret_Sales.U_Price_sales_SRe,0))),0)  Amount_Closing_Re


/*END Closing*/
 FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  product LEFT OUTER JOIN 
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  union  
  SELECT product_id FROM   inventory_adjustment_details
  )  T2 ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_Purchase_In  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
 AND purchase_invoice_info.invoice_date >= '".$startDate."'
AND purchase_invoice_info.invoice_date <= '".$endDate."'  
AND purchase_invoice_info.is_opening=0

GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening /*--,*/

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id /*--LEFT OUTER JOIN*/
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
  AND purchase_invoice_info.invoice_date < '".$startDate."'  
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_P, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out /* --,*/


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id  
 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  
 AND sales_invoice_info.invoice_date >= '".$startDate."'
AND sales_invoice_info.invoice_date <= '".$endDate."'
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date >= '".$startDate."'
AND purchase_invoice_info.invoice_date <= '".$endDate."'  AND purchase_details.is_opening=0

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  /*--purchase_invoice_info.branch_id= AND */ 
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND sales_invoice_info.invoice_date >= '".$startDate."'
AND sales_invoice_info.invoice_date <= '".$endDate."'    

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id 
/*END ReturnSales*/

) AllOPSC  WHERE 1=1  "." ".$Catagory." ".$Brand." ".$product;

        $query2.=" AND (  Closing_Qty >0) ";
        $query2.=" ORDER BY title,brandName,productName";





        $query3="SELECT 
product_id,category_id,unit_id,brand_id, brandName BrandName,productName,CategoryName,unitTtile,
IFNULL(OP_quantity,0) OP_quantity,IFNULL(OP_UPrice,0) OP_UPrice ,IFNULL(OP_Amount,0) OP_Amount,
IFNULL(Pur_quantity,0) Pur_quantity,IFNULL(Pur_UPrice,0) Pur_UPrice,IFNULL(Pur_Amount,0) Pur_Amount,
IFNULL(S_quantity,0) S_quantity,IFNULL(S_UPrice,0) S_UPrice ,IFNULL(S_Amount,0) S_Amount,

IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0) C_quantity ,

IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0) C_UPrice,
 
 IFNULL(((IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0))*
 (IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0))),0) C_Amount

FROM (
SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brandName BrandName,BaseT.productName, title CategoryName,unitTtile,
IFNULL(OP_quantity,0) OP_quantity,IFNULL(OP_UPrice,0) OP_UPrice ,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
IFNULL((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,

 
IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) Pur_Amount,

 
IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,
 
IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
 (
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'  
       
       GROUP BY pd.product_id  
       
        
       ) opening    
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'
         
       
       GROUP BY pd.product_id 
       
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."'
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."'
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."' 
       
       GROUP BY iad.product_id 
       
       
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
             ) Tol WHERE 1=1";



        $query4="SELECT 
 
  product_id, category_id, unit_id, brand_id,
    brandName BrandName, productName,   CategoryName, unitTtile,

IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice ,
IFNULL(OP_Amount,0) OP_Amount,
IFNULL(Pur_quantity,0) Pur_quantity,
IFNULL(Pur_UPrice,0) Pur_UPrice,
IFNULL(Pur_Amount,0) Pur_Amount,
IFNULL(S_quantity,0) S_quantity,
IFNULL(S_UPrice,0) S_UPrice,
IFNULL(S_Amount,0) S_Amount,

IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0) C_quantity ,

IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0) C_UPrice,
 

 IFNULL(((IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0))*
 (IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0))),0) C_Amount


FROM


(SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  union  
  SELECT product_id FROM   inventory_adjustment_details
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  (SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(Pur_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
FROM 
(
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details 
   union  
  SELECT product_id FROM   inventory_adjustment_details 
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
     productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
     unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
     brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
(
 
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'  
       
       GROUP BY pd.product_id  
       
        
       ) opening  ON opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date < '".$startDate."' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."'  
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."'  
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."'   
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol WHERE 1=1";



        if($productCatagory !='All'){
            $query4.=" AND category_id=".$productCatagory;
        }
        if($productBrand !='All'){
            $query4.=" AND brand_id=".$productBrand;
        }if($productId !='All'){
            $query4.=" AND product_id=".$productId;
        }
        log_message('error','this is stock report'.print_r($query4,true));
        $query = $this->db->query($query4);
        $result = $query->result();
        foreach ($result as $key => $value) {
            $array[$value->CategoryName ][$value->BrandName][] = $value;
        }
        return $array;

    }

    public function stock_report_empty_cylinder($productCatagory,$productBrand,$productId,$startDate,$endDate){

        $query4="SELECT product_id,category_id,unit_id,brand_id, 
 BrandName, productName,CategoryName, unitTtile,
  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice ,
IFNULL(OP_Amount,0) OP_Amount ,

IFNULL(Pur_Qty,0) AS Pur_Qty,
IFNULL(Pur_UPrice,0) AS Pur_UPrice,
IFNULL(Pur_Amount,0) AS Pur_Amount,

IFNULL(Sales_Qty,0) Sales_Qty,
IFNULL(Sales_UPrice,0) Sales_UPrice,
IFNULL(Sales_Amount,0) Sales_Amount,

IFNULL((IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)-IFNULL(Sales_Qty,0)),0) Closing_Qnty,

IFNULL((IFNULL(OP_Amount,0)+ IFNULL(Pur_Amount,0) )/ NULLIF(( IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)),0)  ,0)  Closing_UPrice,

IFNULL((IFNULL((IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)-IFNULL(Sales_Qty,0)),0))*(IFNULL((IFNULL(OP_Amount,0)+ IFNULL(Pur_Amount,0) )/ NULLIF(( IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)),0)  ,0) ),0) Closing_Amount

 FROM 


( SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, productcategory.title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount ,

(IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)) Pur_Qty, 
((IFNULL(INV_in_qty,0)*IFNULL(INV_IN_UPrice,0))+(IFNULL(Srt_quantity,0)*IFNULL(Srt_UPrice,0))+(IFNULL(U_Price_Purchase_In,0)*IFNULL(quantity_P,0)) +
(IFNULL(sales_SRe,0)* IFNULL(U_Price_sales_SRe,0))  ) /
NULLIF(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0))),0) Pur_UPrice,
IFNULL(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)))*(((IFNULL(INV_in_qty,0)*IFNULL(INV_IN_UPrice,0))+(IFNULL(Srt_quantity,0)*IFNULL(Srt_UPrice,0))+(IFNULL(U_Price_Purchase_In,0)*IFNULL(quantity_P,0)) +
(IFNULL(sales_SRe,0)* IFNULL(U_Price_sales_SRe,0))  ) /
NULLIF(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0))),0)),0) Pur_Amount,

 (IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) Sales_Qty,
 
 ((IFNULL(INV_OUT_UPrice,0)*IFNULL(INV_out_qty,0)) + (IFNULL(sales_out_Qty,0) * IFNULL(U_Price_sales_out,0))
  + (IFNULL(quantity_PRe,0) * IFNULL(U_Price_Pre,0) ) ) /
  NULLIF(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) ),0) Sales_UPrice,
 
 IFNULL(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)))*( ((IFNULL(INV_OUT_UPrice,0)*IFNULL(INV_out_qty,0)) + (IFNULL(sales_out_Qty,0) * IFNULL(U_Price_sales_out,0))
  + (IFNULL(quantity_PRe,0) * IFNULL(U_Price_Pre,0) ) ) /
  NULLIF(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) ),0)),0) Sales_Amount
 
FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id=1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  ( 

SELECT
 BaseT.product_id, BaseT.productName, BaseT.category_id,productcategory.title,
 BaseT.unit_id, unit.unitTtile , BaseT.brand_id,brand.brandName,
/*Opening*/
 

(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0) ),0)) -
(IFNULL(( IFNULL(sales_out_Qty,0) + IFNULL(INV_out_qty,0)   + IFNULL(quantity_PRe,0)),0))  
OP_quantity  ,
 
 
IFNULL((IFNULL(Amount_Purchase_In,0) +  IFNULL(Srt_Amount,0)+IFNULL(Amount_sales_SRe,0) + IFNULL(INV_IN_Amount,0))
 /NULLIF(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)),0) ,0 ),0)
  OP_UPrice,



IFNULL(((IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0) ),0)) -
(IFNULL(( IFNULL(sales_out_Qty,0) + IFNULL(INV_out_qty,0)   + IFNULL(quantity_PRe,0)),0)) )*(IFNULL((IFNULL(Amount_Purchase_In,0) +  IFNULL(Srt_Amount,0)+IFNULL(Amount_sales_SRe,0) + IFNULL(INV_IN_Amount,0))
 /NULLIF(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)),0) ,0 ),0)),0) OP_Amount
 
 
/*END Opening*/
 

 
 FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  product LEFT OUTER JOIN 
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )  T2 ON product.product_id=T2.product_id  WHERE product.category_id=1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0) U_Price_Purchase_In,

IFNULL((SUM(IFNULL( purchase_details.quantity,0)))*( 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0)),0) Amount_Purchase_In 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
 AND purchase_invoice_info.invoice_date < '".$startDate."'
  
 

GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening  ,
 
 IFNULL((SUM(IFNULL( purchase_details.quantity,0))) *  (
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0)),0) Amount_opening

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id 
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
  AND purchase_invoice_info.invoice_date < '".$startDate."'   
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_out_Qty, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out  ,

IFNULL((SUM(IFNULL( sales_details.quantity,0))) * (

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0)),0) Amount_sales_out 


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  
 AND sales_invoice_info.invoice_date < '".$startDate."'
  
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe,
 
 IFNULL((IFNULL(SUM( purchase_return_details.return_quantity ),0)) * (
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0),0)),0)  Amount_PRe
 
 

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe ,
 
 IFNULL( (SUM(IFNULL( purchase_return_details.return_quantity ,0))) *( 

  SUM(IFNULL(purchase_return_details.return_quantity,0)  * IFNULL(purchase_return_details.unit_price,0)) 
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0)),0)  Amount_openingRe
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  ,
 
 
IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) *
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) Amount_sales_SRe  
 

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND sales_invoice_info.invoice_date < '".$startDate."'
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."'
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN  
       
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."'
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
       
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0) U_Price_Purchase_In,

IFNULL((SUM(IFNULL( purchase_details.quantity,0)))*( 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0)),0) Amount_Purchase_In 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  


AND purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND purchase_invoice_info.invoice_date  <= '".$endDate."'    
 
 
GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening  ,
 
 IFNULL((SUM(IFNULL( purchase_details.quantity,0))) *  (
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0)),0) Amount_opening

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id 
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' 


AND purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND purchase_invoice_info.invoice_date  <= '".$endDate."'   
 
    
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_out_Qty, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out  ,

IFNULL((SUM(IFNULL( sales_details.quantity,0))) * (

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0)),0) Amount_sales_out 


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  

AND sales_invoice_info.invoice_date  >= '".$startDate."'
 AND sales_invoice_info.invoice_date  <= '".$endDate."'    
 
 
  
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe,
 
 IFNULL((IFNULL(SUM( purchase_return_details.return_quantity ),0)) * (
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0),0)),0)  Amount_PRe
 
 

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 


AND  purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND  purchase_invoice_info.invoice_date <= '".$endDate."'   
 
 
  

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe ,
 
 IFNULL( (SUM(IFNULL( purchase_return_details.return_quantity ,0))) *( 

  SUM(IFNULL(purchase_return_details.return_quantity,0)  * IFNULL(purchase_return_details.unit_price,0)) 
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0)),0)  Amount_openingRe
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 

AND  purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND  purchase_invoice_info.invoice_date  <= '".$endDate."'    
 
 
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  ,
 
 
IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) *
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) Amount_sales_SRe  
 

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND  sales_invoice_info.invoice_date  >= '".$startDate."'
 AND  sales_invoice_info.invoice_date  <= '".$endDate."'   
 
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
        
         AND sr.return_date  >= '".$startDate."'
 AND sr.return_date  <= '".$endDate."'    
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN  
       
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."'  
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        )
         Tol WHERE 1=1";



        if($productCatagory !='All'){
            $query4.=" AND category_id=".$productCatagory;
        }
        if($productBrand !='All'){
            $query4.=" AND brand_id=".$productBrand;
        }if($productId !='All'){
            $query4.=" AND product_id=".$productId;
        }
        log_message('error','query2 :'.print_r($query4,true));
        $query = $this->db->query($query4);
        $result = $query->result();
        foreach ($result as $key => $value) {
            $array[$value->CategoryName ][$value->BrandName][] = $value;
        }
        return $array;
    }

    function get_empty_cylinder_with_refill_with_out_refill($productCatagory,$productBrand,$productId,$startDate,$endDate){

        $query4="SELECT 
  pp2.product_id AS empty_cylinder_id,
  
TolEmpty.product_id,TolEmpty.category_id,TolEmpty.unit_id,TolEmpty.brand_id, 
 TolEmpty.BrandName, TolEmpty.productName,TolEmpty.CategoryName, TolEmpty.unitTtile,
  IFNULL(TolEmpty.OP_quantity,0) OP_quantity,
IFNULL(TolEmpty.OP_UPrice,0) OP_UPrice ,
IFNULL(TolEmpty.OP_Amount,0) OP_Amount ,

IFNULL(TolEmpty.Pur_Qty,0) AS Pur_Qty,
IFNULL(TolEmpty.Pur_UPrice,0) AS Pur_UPrice,
IFNULL(TolEmpty.Pur_Amount,0) AS Pur_Amount,

IFNULL(TolEmpty.Sales_Qty,0) Sales_Qty,
IFNULL(TolEmpty.Sales_UPrice,0) Sales_UPrice,
IFNULL(TolEmpty.Sales_Amount,0) Sales_Amount,

IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0) Closing_Qnty,

IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0)  Closing_UPrice,

IFNULL((IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0))*(IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0) ),0) Closing_Amount,

IFNULL(Tol.OP_quantity,0) OP_quantity_refill,
IFNULL(Tol.Pur_quantity,0) Pur_quantity_refill,

IFNULL(Tol.S_quantity,0) S_quantity_refill,


IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0) C_quantity_refill 
 


 



FROM


(SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  (SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(Pur_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
FROM 
(
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
     productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
     unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
     brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
(
 
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'   
       
       GROUP BY pd.product_id  
       
        
       ) opening  ON opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date < '".$startDate."' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."' 
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."'  
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol LEFT JOIN package_products on Tol.product_id=package_products.product_id
LEFT JOIN (
SELECT product.category_id,package_products.product_id,package_products.package_id 
FROM package_products LEFT JOIN product 
ON product.product_id=package_products.product_id 
WHERE product.category_id=1
) pp2 on pp2.package_id=package_products.package_id




WHERE 1=1";

$query5="SELECT 
  pp2.product_id AS empty_cylinder_id,
  
TolEmpty.product_id,TolEmpty.category_id,TolEmpty.unit_id,TolEmpty.brand_id, 
 TolEmpty.BrandName, TolEmpty.productName,TolEmpty.CategoryName, TolEmpty.unitTtile,
  IFNULL(TolEmpty.OP_quantity,0) OP_quantity,
IFNULL(TolEmpty.OP_UPrice,0) OP_UPrice ,
IFNULL(TolEmpty.OP_Amount,0) OP_Amount ,

IFNULL(TolEmpty.Pur_Qty,0) AS Pur_Qty,
IFNULL(TolEmpty.Pur_UPrice,0) AS Pur_UPrice,
IFNULL(TolEmpty.Pur_Amount,0) AS Pur_Amount,

IFNULL(TolEmpty.Sales_Qty,0) Sales_Qty,
IFNULL(TolEmpty.Sales_UPrice,0) Sales_UPrice,
IFNULL(TolEmpty.Sales_Amount,0) Sales_Amount,

IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0) Closing_Qnty,

IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0)  Closing_UPrice,

IFNULL((IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0))*(IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0) ),0) Closing_Amount,

IFNULL(Tol.OP_quantity,0) OP_quantity_refill,
IFNULL(Tol.Pur_quantity,0) Pur_quantity_refill,

IFNULL(Tol.S_quantity,0) S_quantity_refill,


IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0) C_quantity_refill 
 


 



FROM
(

SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, productcategory.title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount ,

(IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)) Pur_Qty, 
((IFNULL(INV_in_qty,0)*IFNULL(INV_IN_UPrice,0))+(IFNULL(Srt_quantity,0)*IFNULL(Srt_UPrice,0))+(IFNULL(U_Price_Purchase_In,0)*IFNULL(quantity_P,0)) +
(IFNULL(sales_SRe,0)* IFNULL(U_Price_sales_SRe,0))  ) /
NULLIF(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0))),0) Pur_UPrice,
IFNULL(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)))*(((IFNULL(INV_in_qty,0)*IFNULL(INV_IN_UPrice,0))+(IFNULL(Srt_quantity,0)*IFNULL(Srt_UPrice,0))+(IFNULL(U_Price_Purchase_In,0)*IFNULL(quantity_P,0)) +
(IFNULL(sales_SRe,0)* IFNULL(U_Price_sales_SRe,0))  ) /
NULLIF(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0))),0)),0) Pur_Amount,

 (IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) Sales_Qty,
 
 ((IFNULL(INV_OUT_UPrice,0)*IFNULL(INV_out_qty,0)) + (IFNULL(sales_out_Qty,0) * IFNULL(U_Price_sales_out,0))
  + (IFNULL(quantity_PRe,0) * IFNULL(U_Price_Pre,0) ) ) /
  NULLIF(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) ),0) Sales_UPrice,
 
 IFNULL(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)))*( ((IFNULL(INV_OUT_UPrice,0)*IFNULL(INV_out_qty,0)) + (IFNULL(sales_out_Qty,0) * IFNULL(U_Price_sales_out,0))
  + (IFNULL(quantity_PRe,0) * IFNULL(U_Price_Pre,0) ) ) /
  NULLIF(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) ),0)),0) Sales_Amount
 
FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id=1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  ( 

SELECT
 BaseT.product_id, BaseT.productName, BaseT.category_id,productcategory.title,
 BaseT.unit_id, unit.unitTtile , BaseT.brand_id,brand.brandName,
/*Opening*/
 

(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0) ),0)) -
(IFNULL(( IFNULL(sales_out_Qty,0) + IFNULL(INV_out_qty,0)   + IFNULL(quantity_PRe,0)),0))  
OP_quantity  ,
 
 
IFNULL((IFNULL(Amount_Purchase_In,0) +  IFNULL(Srt_Amount,0)+IFNULL(Amount_sales_SRe,0) + IFNULL(INV_IN_Amount,0))
 /NULLIF(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)),0) ,0 ),0)
  OP_UPrice,



IFNULL(((IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0) ),0)) -
(IFNULL(( IFNULL(sales_out_Qty,0) + IFNULL(INV_out_qty,0)   + IFNULL(quantity_PRe,0)),0)) )*(IFNULL((IFNULL(Amount_Purchase_In,0) +  IFNULL(Srt_Amount,0)+IFNULL(Amount_sales_SRe,0) + IFNULL(INV_IN_Amount,0))
 /NULLIF(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)),0) ,0 ),0)),0) OP_Amount
 
 
/*END Opening*/
 

 
 FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  product LEFT OUTER JOIN 
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )  T2 ON product.product_id=T2.product_id  WHERE product.category_id=1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0) U_Price_Purchase_In,

IFNULL((SUM(IFNULL( purchase_details.quantity,0)))*( 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0)),0) Amount_Purchase_In 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
 AND purchase_invoice_info.invoice_date < '".$startDate."'
  
 

GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening  ,
 
 IFNULL((SUM(IFNULL( purchase_details.quantity,0))) *  (
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0)),0) Amount_opening

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id 
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
  AND purchase_invoice_info.invoice_date < '".$startDate."'   
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_out_Qty, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out  ,

IFNULL((SUM(IFNULL( sales_details.quantity,0))) * (

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0)),0) Amount_sales_out 


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  
 AND sales_invoice_info.invoice_date < '".$startDate."'
  
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe,
 
 IFNULL((IFNULL(SUM( purchase_return_details.return_quantity ),0)) * (
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0),0)),0)  Amount_PRe
 
 

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe ,
 
 IFNULL( (SUM(IFNULL( purchase_return_details.return_quantity ,0))) *( 

  SUM(IFNULL(purchase_return_details.return_quantity,0)  * IFNULL(purchase_return_details.unit_price,0)) 
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0)),0)  Amount_openingRe
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  ,
 
 
IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) *
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) Amount_sales_SRe  
 

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND sales_invoice_info.invoice_date < '".$startDate."'
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."'
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN  
       
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."'
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
       
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0) U_Price_Purchase_In,

IFNULL((SUM(IFNULL( purchase_details.quantity,0)))*( 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0)),0) Amount_Purchase_In 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  


AND purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND purchase_invoice_info.invoice_date  <= '".$endDate."'   
 
 
GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening  ,
 
 IFNULL((SUM(IFNULL( purchase_details.quantity,0))) *  (
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0)),0) Amount_opening

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id 
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' 


AND purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND purchase_invoice_info.invoice_date  <= '".$endDate."'    
 
    
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_out_Qty, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out  ,

IFNULL((SUM(IFNULL( sales_details.quantity,0))) * (

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0)),0) Amount_sales_out 


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  

AND sales_invoice_info.invoice_date  >= '".$startDate."'
 AND sales_invoice_info.invoice_date  <= '".$endDate."'    
 
 
  
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe,
 
 IFNULL((IFNULL(SUM( purchase_return_details.return_quantity ),0)) * (
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0),0)),0)  Amount_PRe
 
 

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 


AND  purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND  purchase_invoice_info.invoice_date <= '".$endDate."'  
 
 
  

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe ,
 
 IFNULL( (SUM(IFNULL( purchase_return_details.return_quantity ,0))) *( 

  SUM(IFNULL(purchase_return_details.return_quantity,0)  * IFNULL(purchase_return_details.unit_price,0)) 
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0)),0)  Amount_openingRe
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 

AND  purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND  purchase_invoice_info.invoice_date  <= '".$endDate."'  
 
 
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  ,
 
 
IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) *
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) Amount_sales_SRe  
 

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND  sales_invoice_info.invoice_date  >= '".$startDate."'
 AND  sales_invoice_info.invoice_date  <= '".$endDate."' 
 
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
        
         AND sr.return_date  >= '".$startDate."'
 AND sr.return_date  <= '".$endDate."' 
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN  
       
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
) TolEmpty

 LEFT JOIN package_products on TolEmpty.product_id=package_products.product_id
LEFT JOIN (
SELECT product.category_id,package_products.product_id,package_products.package_id 
FROM package_products LEFT JOIN product 
ON product.product_id=package_products.product_id 
WHERE product.category_id=2
) pp2 on pp2.package_id=package_products.package_id

LEFT JOIN (SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  (SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(Pur_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
FROM 
(
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
     productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
     unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
     brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
(
 
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'   
       
       GROUP BY pd.product_id  
       
        
       ) opening  ON opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date < '".$startDate."' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."'
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date  <= '".$endDate."'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date  <= '".$endDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date  <= '".$endDate."' 
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date >= '".$startDate."'
AND iai.date  <= '".$endDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol on Tol.product_id=pp2.product_id


WHERE 1=1";

        if($productCatagory !='All'){
            $query5.=" AND TolEmpty.category_id=".$productCatagory;
        }
        if($productBrand !='All'){
            $query5.=" AND TolEmpty.brand_id=".$productBrand;
        }if($productId !='All'){
            $query5.=" AND TolEmpty.product_id=".$productId;
        }
        log_message('error','query2 :'.print_r($query5,true));
        $query = $this->db->query($query5);
        $result = $query->result();
        foreach ($result as $key => $value) {
            $array[$value->CategoryName ][$value->BrandName][] = $value;
        }
        return $array;
    }






    function getCusSupcylinderDue ($type_id, $searchId, $productId, $from_date, $to_date){
$query="SELECT DISTINCT  customer.customerName,customer.customerID,customertype.typeTitle,
Tol.cylinder_exchange_qty_op_out,
Tol.cylinder_exchange_qty_op_in,
Tol.cylinder_exchange_qty_out,
Tol.cylinder_exchange_qty_in,
Tol.customer_id,Tol.empty_cylinder_id,IFNULL(Tol.product_id,0) product_id,
Tol.sales_qty_op,Tol.sales_return_qty_op,
Tol.in_qty_op,Tol.out_qty_op,
Tol.sales_qty,Tol.sales_return_qty,Tol.in_qty,Tol.out_qty,
product.productName ,
productcategory.title as product_category,
brand.brandName


FROM (SELECT BASE.customer_id,IFNULL(empty_cylinder_id,0) empty_cylinder_id,BASE.product_id,IFNULL(sales_qty,0) sales_qty,IFNULL(sales_return_qty,0) sales_return_qty,
IFNULL(in_qty,0) in_qty ,  IFNULL(out_qty,0) out_qty,sales_opening.sales_qty_op,sales_opening.sales_return_qty_op,
Inv_Adj_opening.in_qty_op,Inv_Adj_opening.out_qty_op,
customer_due_cylinder_opening.cylinder_exchange_qty_op_out,customer_due_cylinder_opening.cylinder_exchange_qty_op_in,
customer_due_cylinder_ex.cylinder_exchange_qty_out,customer_due_cylinder_ex.cylinder_exchange_qty_in

 FROM 

(
SELECT
					iai.customer_id,
					iad.product_id
				FROM
					inventory_adjustment_info iai
				LEFT OUTER JOIN inventory_adjustment_details iad ON iai.id = iad.inv_adjustment_info_id
				WHERE
					form_id = 2
				UNION ALL
					SELECT
						sii.customer_id,
						
empty_cylinder_package.product_id as product_id
					FROM
						sales_details sd
					LEFT OUTER JOIN sales_invoice_info sii ON sd.sales_invoice_id = sii.sales_invoice_id
					LEFT OUTER JOIN product ON sd.product_id = product.product_id
LEFT JOIN(
				SELECT
					package_id,
					product_id
				FROM
					package_products
			)AS package_info ON package_info.product_id = sd.product_id

LEFT JOIN(
				SELECT
					package_products.product_id,
					package_products.package_id
				FROM
					package_products
				LEFT JOIN product ON product.product_id = package_products.product_id
				WHERE
					product.category_id = 1
			)AS empty_cylinder_package ON empty_cylinder_package.package_id = package_info.package_id
					WHERE
						is_package = 0 
						UNION ALL
SELECT
						customer_id,
						
product_id as product_id
					FROM 
cylinder_exchange_details
) BASE LEFT OUTER JOIN 


(SELECT
	sii.customer_id,
sd.product_id,
IFNULL(SUM(sd.quantity),0) as sales_qty ,
IFNULL(SUM(sd.return_quentity),0) as sales_return_qty,
empty_cylinder_package.product_id as empty_cylinder_id
FROM
	sales_invoice_info sii
LEFT JOIN sales_details sd ON sd.sales_invoice_id=sii.sales_invoice_id 
LEFT JOIN product on product.product_id=sd.product_id
LEFT JOIN (
SELECT package_id,product_id FROM package_products 
) as package_info on package_info.product_id=sd.product_id
LEFT JOIN (
SELECT package_products.product_id,package_products.package_id FROM package_products 
 LEFT JOIN product on product.product_id=package_products.product_id
WHERE product.category_id=1
) AS empty_cylinder_package ON empty_cylinder_package.package_id=package_info.package_id

-- LEFT JOIN productcategory ON productcategory.category_id=product.category_id
WHERE
	sii.is_active = 'Y'
 AND sii.invoice_date <='".$to_date."'
 AND sii.invoice_date >='".$from_date."'
AND product.category_id=2
AND sd.is_package=0 
GROUP BY sd.product_id,sii.customer_id,empty_cylinder_package.product_id 

) Sales_package ON 
Sales_package.empty_cylinder_id=BASE.product_id AND Sales_package.customer_id=BASE.customer_id 


LEFT OUTER JOIN 

(SELECT
	inv_adj_info.customer_id,IFNULL(SUM(iad.in_qty),0) in_qty ,IFNULL(SUM(iad.out_qty),0) out_qty ,iad.product_id
FROM
	inventory_adjustment_info inv_adj_info
LEFT JOIN inventory_adjustment_details iad ON iad.inv_adjustment_info_id=inv_adj_info.id
LEFT JOIN product on product.product_id=iad.product_id
WHERE
	inv_adj_info.is_active = 'Y'
AND inv_adj_info.date <= '".$to_date."'
AND inv_adj_info.date >= '".$from_date."'
AND inv_adj_info.form_id = 2
AND product.category_id=1
GROUP BY iad.product_id,inv_adj_info.customer_id

) Inv_Adj ON 
Inv_Adj.product_id=BASE.product_id AND Inv_Adj.customer_id=BASE.customer_id 

LEFT JOIN (

SELECT
	sii.customer_id,
sd.product_id,
IFNULL(SUM(sd.quantity),0) as sales_qty_op ,
IFNULL(SUM(sd.return_quentity),0) as sales_return_qty_op,
empty_cylinder_package.product_id as empty_cylinder_id_op
FROM
	sales_invoice_info sii
LEFT JOIN sales_details sd ON sd.sales_invoice_id=sii.sales_invoice_id 
LEFT JOIN product on product.product_id=sd.product_id
LEFT JOIN (
SELECT package_id,product_id FROM package_products 
) as package_info on package_info.product_id=sd.product_id
LEFT JOIN (
SELECT package_products.product_id,package_products.package_id FROM package_products 
 LEFT JOIN product on product.product_id=package_products.product_id
WHERE product.category_id=1
) AS empty_cylinder_package ON empty_cylinder_package.package_id=package_info.package_id


WHERE
	sii.is_active = 'Y'
 AND sii.invoice_date <'".$from_date."'
 
AND product.category_id=2
AND sd.is_package=0 
GROUP BY sd.product_id,sii.customer_id,empty_cylinder_package.product_id 
 
) as sales_opening on sales_opening.empty_cylinder_id_op=BASE.product_id AND sales_opening.customer_id=BASE.customer_id

LEFT OUTER JOIN 

(SELECT
	inv_adj_info.customer_id,IFNULL(SUM(iad.in_qty),0) in_qty_op ,IFNULL(SUM(iad.out_qty),0) out_qty_op ,iad.product_id
FROM
	inventory_adjustment_info inv_adj_info
LEFT JOIN inventory_adjustment_details iad ON iad.inv_adjustment_info_id=inv_adj_info.id
LEFT JOIN product on product.product_id=iad.product_id
WHERE
	inv_adj_info.is_active = 'Y'
AND inv_adj_info.date <'".$from_date."'
AND inv_adj_info.form_id = 2
AND product.category_id=1
GROUP BY iad.product_id,inv_adj_info.customer_id

) Inv_Adj_opening ON 
Inv_Adj_opening.product_id=BASE.product_id AND Inv_Adj_opening.customer_id=BASE.customer_id 
LEFT OUTER JOIN(
SELECT
	ced.customer_id,IFNULL(SUM(ced.exchange_out),0) cylinder_exchange_qty_op_out ,IFNULL(SUM(ced.exchange_in),0) cylinder_exchange_qty_op_in ,ced.product_id
FROM
	cylinder_exchange_details ced
LEFT JOIN cylinder_exchange_info cei ON cei.exchange_info_id=ced.exchange_info_id
WHERE
	1=1
AND cei.date <'".$from_date."'

GROUP BY ced.product_id,ced.customer_id


 ) customer_due_cylinder_opening on customer_due_cylinder_opening.product_id=BASE.product_id AND customer_due_cylinder_opening.customer_id=BASE.customer_id 

LEFT OUTER JOIN(
SELECT
	ced.customer_id,IFNULL(SUM(ced.exchange_out),0) cylinder_exchange_qty_out ,IFNULL(SUM(ced.exchange_in),0) cylinder_exchange_qty_in ,ced.product_id
FROM
	cylinder_exchange_details ced
LEFT JOIN cylinder_exchange_info cei ON cei.exchange_info_id=ced.exchange_info_id
WHERE
	1=1
AND cei.date <= '".$to_date."'
AND cei.date >= '".$from_date."'
GROUP BY ced.product_id,ced.customer_id


 ) customer_due_cylinder_ex on customer_due_cylinder_ex.product_id=BASE.product_id AND customer_due_cylinder_ex.customer_id=BASE.customer_id
) Tol

  LEFT JOIN product on product.product_id=Tol.product_id
  LEFT JOIN productcategory on productcategory.category_id=product.category_id
LEFT JOIN brand ON brand.brandId=product.brand_id
LEFT JOIN customer ON customer.customer_id=Tol.customer_id
LEFT JOIN customertype on customertype.type_id=customer.customerType  WHERE 1=1";

        if($searchId !='all'){
            $query.=" AND Tol.customer_id=".$searchId;
        }
        if($productId !='all') {
            $query .= " AND Tol.product_id=" . $productId;
        }
        $query.=" /*HAVING Tol.empty_cylinder_id !=0 AND product_id !=0 */ ORDER BY customer.customerName,product.productName,brand.brandName";
        log_message('error','this is cylinder due'.print_r($query,true));
        $query = $this->db->query($query);
        $result = $query->result();
        foreach ($result as $key => $value) {
           // if($value->sales_qty >0 && $value->sales_return_qty >0 && $value->in_qty >0 && $value->out_qty >0){
                $array[$value->customerName ][$value->brandName] []= $value;
            //}

        }
        return $array;

    }
    function getCustomerBalance($distId, $supplierId)
    {



        $customer_ledger=get_customer_supplier_product_ledger_id($supplierId, 2);
        $this->db->select("IFNULL(sum(GR_DEBIT) - sum(GR_CREDIT),0) as totalCurrentBalance");
        $this->db->from("ac_tb_accounts_voucherdtl");

        $this->db->where('CHILD_ID', $customer_ledger->id);
        $result = $this->db->get()->row();
        $customer_due = $result->totalCurrentBalance;

        return $customer_due;
    }


}

?>