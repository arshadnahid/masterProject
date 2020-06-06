<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Sales_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    function getCustomerList2()
    {
        $this->db->select();
        $this->db->where('dist_id', '1');
    }
    function getInvoicePayment($distId, $invoiceId)
    {
        $voaucherNo = $this->db->get_where('generals', array('sales_invoice_info' => $invoiceId, 'dist_id' => $distId))->row()->invoice_no;
        $this->db->select("sum(generals.credit) as totalAmount");
        $this->db->from("generals");
        $this->db->where('dist_id', $distId);
        $this->db->where('form_id', 7);
        $this->db->where('voucher_no', $voaucherNo);
        return $this->db->get()->row()->totalAmount;
    }
    function getTopSaleProduct($fromDate, $toDate, $distId)
    {
        $this->db->select("SUM(stock.quantity) as totalSale,brand.brandName,productcategory.title,product.productName,product.product_code");
        $this->db->from("stock");
        $this->db->join('productcategory', 'productcategory.category_id=product.category_id', 'left');
        $this->db->join('product', 'product.product_id=stock.product_id', 'left');
        $this->db->join('brand', 'brand.brandId=product.brand_id', 'left');
        $this->db->where('stock.dist_id', $distId);
        $this->db->where('stock.date >=', date('Y-m-d', strtotime($fromDate)));
        $this->db->where('stock.date <=', date('Y-m-d', strtotime($toDate)));
        $this->db->where('stock.type', 'Out');
        $this->db->group_by('stock.product_id');
        $this->db->order_by('SUM(stock.quantity)', 'DESC');
        $ledgerReport = $this->db->get()->result();
        return $ledgerReport;
    }
    public function getSalesVoucherList()
    {
        $this->db->select("form.name,generals.generals_id,generals.voucher_no,generals.narration,generals.date,generals.debit,customer.customerID,customer.customerName,customer.customer_id");
        $this->db->from("generals");
        $this->db->join('customer', 'customer.customer_id=generals.customer_id');
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->where('generals.dist_id', $this->dist_id);
        $this->db->where('generals.form_id', 5);
        $this->db->order_by('generals.date', 'desc');
        $result = $this->db->get()->result();
        return $result;
    }
    function getCustomerSalesList($distId, $fromDate, $endDate, $cusId = null, $type = null, $branch_id = 'all')
    {
        if ($cusId != 'all') {
            $query = "SELECT
                sii.invoice_amount,
                sii.invoice_date,
                sii.payment_type,
                cu.customerID,
                sii.invoice_no,
                sii.sales_invoice_id,
                cu.customerName,
                ct.typeTitle,
                 branch.branch_name,
                 branch.branch_id
            FROM
                sales_invoice_info sii
            LEFT  JOIN branch On  branch.branch_id=sii.branch_id
            LEFT JOIN customer cu ON cu.customer_id = sii.customer_id
            LEFT JOIN customertype ct ON ct.type_id = cu.customerType
            WHERE
                1 = 1 AND branch.is_active='1'  AND sii.customer_id =" . $cusId . " AND sii.invoice_date >= '" . $fromDate . "'
                AND sii.invoice_date <= '" . $endDate . "'";
            if ($branch_id != 'all') {
                $query .= " AND sii.branch_id=" . $branch_id;
            }
            $query .= " ORDER BY  branch.branch_name";
        }
        if ($cusId == 'all') {
            $query = "SELECT
                SUM(sii.invoice_amount) as tt_invoice_amount,
                cu.customerID,
                cu.customerName,
                ct.typeTitle,
                 branch.branch_name,
                 branch.branch_id
            FROM
                sales_invoice_info sii
            LEFT JOIN customer cu ON cu.customer_id = sii.customer_id
            LEFT JOIN customertype ct ON ct.type_id = cu.customerType
            LEFT  JOIN branch On  branch.branch_id=sii.branch_id
            WHERE
                1 = 1 AND branch.is_active='1' ";
            if ($type != 'all') {
                $query .= " AND ct.type_id =" . $type;
            }
            $query .= " AND sii.invoice_date >= '" . $fromDate . "'
                AND sii.invoice_date <= '" . $endDate . "'";
            if ($branch_id != 'all') {
                $query .= " AND sii.branch_id=" . $branch_id;
            }
            $query .= " GROUP BY cu.customer_id,sii.branch_id  ORDER BY  branch.branch_name";
        }
        log_message('error', 'Last Query :-' . print_r($this->db->last_query(), true));
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    function getReferenceSalesListById($distId, $fromDate, $endDate, $refId)
    {
        $query = "SELECT tab1.date,tab1.voucher_no,tab1.narration as memo,tab1.payType,tab1.referenceName,tab1.refCode,tab1.individualAmount,tab2.totalOpening from
            (SELECT r.referenceName,r.refCode,r.reference_id,g.date,g.voucher_no,g.payType,g.narration,g.debit as individualAmount
            FROM generals g
            LEFT JOIN reference r
            ON r.reference_id=g.reference
            WHERE g.form_id =5 AND r.reference_id='$refId' AND g.dist_id ='$distId' AND g.date >='$fromDate' AND g.date <='$endDate' ORDER BY g.date ASC
            ) tab1
LEFT JOIN
(SELECT  SUM(g.debit) as totalOpening,r.reference_id
FROM reference r
LEFT JOIN generals g
on g.reference=r.reference_id
WHERE g.form_id =5 AND r.reference_id='$refId' AND g.dist_id ='$distId' AND g.date < '$fromDate') tab2
on tab1.reference_id=tab2.reference_id ORDER BY tab2.totalOpening DESC";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    function getReferenceSalesList($distId, $fromDate, $endDate)
    {
        $query = "SELECT tab1.referenceName,tab1.refCode,tab1.individualAmount,tab2.totalOpening from
            (SELECT r.referenceName,r.refCode,r.reference_id,sum(g.debit) as individualAmount
            FROM generals g
            LEFT JOIN reference r
            ON r.reference_id=g.reference
            WHERE g.form_id =5 AND g.dist_id ='$distId' AND g.date >='$fromDate' AND g.date <='$endDate'
            GROUP BY r.reference_id) tab1
LEFT JOIN
(SELECT  SUM(g.debit) as totalOpening,r.reference_id
FROM reference r
LEFT JOIN generals g
on g.reference=r.reference_id
WHERE g.form_id =5 AND g.dist_id ='$distId' AND g.date < '$fromDate') tab2
on tab1.reference_id=tab2.reference_id ORDER BY tab2.totalOpening DESC";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function getSalesPosVoucherList()
    {
        $this->db->select("form.name,generals.generals_id,generals.voucher_no,generals.narration,generals.date,generals.debit,customer.customerID,customer.customerName,customer.customer_id");
        $this->db->from("generals");
        $this->db->join('customer', 'customer.customer_id=generals.customer_id');
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->where('generals.dist_id', $this->dist_id);
        $this->db->where('generals.form_id', 31);
        $this->db->order_by('generals.date', 'desc');
        $result = $this->db->get()->result();
        return $result;
    }
    function checkInvoiceIdAndDistributor($distId, $invoiceId)
    {
        $this->db->select("*");
        $this->db->from("generals");
        $this->db->where("dist_id", $distId);
        $this->db->where("generals_id", $invoiceId);
        $dataExits = $this->db->get()->row();
        if (!empty($dataExits)) {
            return true;
        }
    }
    function checkVoucherPaymentAlreadyReceive($distId, $voucher)
    {
        $this->db->select('sum(debit - credit) as totalPayment');
        $this->db->from('generals');
        $this->db->where('voucher_no', $voucher);
        $this->db->where('dist_id', $distId);
        $result = $this->db->get()->row();
        return $result->totalPayment;
    }
    function getReturnAbleCylinder($distId, $invoiceId, $productId)
    {
        $this->db->select("generals_id");
        $this->db->from("generals");
        $this->db->where("dist_id", $distId);
        //form Id 24 means cylinder receive,form Id 23 means cylinder out
        $this->db->where("form_id", 23);
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
            $this->db->where('stock.product_id', $productId);
            $this->db->where('stock.type', 'Cout');
            //type 24 means cylinder receive,type 23 means cylinder out
            $this->db->where('stock.form_id', 23);
            $this->db->where('stock.generals_id', $generalId->generals_id);
            $cylinderInOutResult = $this->db->get()->row();
            return $cylinderInOutResult;
        }
    }
    function getCylinderInOutResult($distId, $invoiceId, $formId)
    {
        $this->db->select("generals_id");
        $this->db->from("generals");
        $this->db->where("dist_id", $distId);
        //form Id 24 means cylinder receive,form Id 23 means cylinder out
        $this->db->where("form_id", $formId);
        $this->db->where("mainInvoiceId", $invoiceId);
        $generalId = $this->db->get()->row();
        if (!empty($generalId)) {
            $this->db->select('product.productName,brand.brandName,unit.unitTtile,productcategory.title,stock.quantity,stock.category_id,stock.product_id,stock.unit');
            $this->db->from('stock');
            $this->db->join('productcategory', 'stock.category_id = productcategory.category_id');
            $this->db->join('product', 'stock.product_id = product.product_id');
            $this->db->join('unit', 'stock.unit = unit.unit_id');
            $this->db->join('brand', 'product.brand_id = brand.brandId');
            $this->db->where('stock.dist_id', $distId);
            //type 24 means cylinder receive,type 23 means cylinder out
            $this->db->where('stock.form_id', $formId);
            $this->db->where('stock.generals_id', $generalId->generals_id);
            $cylinderInOutResult = $this->db->get()->result();
            return $cylinderInOutResult;
        }
    }
    function getInvoiceIdList($distId, $invoiceId)
    {
        $this->db->select("generals_id");
        $this->db->from("generals");
        $this->db->where("dist_id", $distId);
        $this->db->where("mainInvoiceId", $invoiceId);
        return $this->db->get()->result();
    }
    function getCustomerInvoiceAmount($customerId, $invoiceId)
    {
        $this->db->select("sum(dr - cr) as totalBalance");
        $this->db->from("client_vendor_ledger");
        $this->db->where("dist_id", $this->dist_id);
        $this->db->where("ledger_type", 1);
        $this->db->where("history_id", $invoiceId);
        $this->db->where("client_vendor_id", $customerId);
        $result = $this->db->get()->row();
        return $result->totalBalance;
    }
    function getCustomerBalance($distId, $customerId)
    {
        $this->db->select("*");
        $this->db->from("customer");
        $this->db->where('customer_id', $customerId);
        $supplier_details = $this->db->get()->row();
        $returnResult['customer_details'] = $supplier_details;


        $customer_ledger=get_customer_supplier_product_ledger_id($customerId, 3);
        $this->db->select("sum(GR_DEBIT) - sum(GR_CREDIT) as totalCurrentBalance");
        $this->db->from("ac_tb_accounts_voucherdtl");

        $this->db->where('CHILD_ID', $customer_ledger->id);
        $result = $this->db->get()->row();
        $returnResult['customer_due'] = $result->totalCurrentBalance;



       /* $queryAd = "SELECT
    SUM(sales_details.customer_due) AS customer_due,
    sales_details.product_id,
product.productName,brand.brandName
FROM
    sales_invoice_info

JOIN sales_details ON sales_details.sales_invoice_id = sales_invoice_info.sales_invoice_id
JOIN product ON product.product_id=sales_details.product_id
JOIN brand ON brand.brandId=product.brand_id
WHERE
sales_invoice_info.is_active='Y' AND sales_invoice_info.is_delete='N' AND
    sales_invoice_info.customer_id =" . $customerId . "   GROUP BY
    sales_details.product_id  HAVING customer_due>0";
        */

        /*$queryCylinderAdvanceDue="SELECT
	(SUM(sales_details.customer_due)) AS customer_due,
(SUM(sales_details.customer_advance)+cylinder_exchange.exchange) AS customer_advance,
inv_adj.in_qty,
inv_adj.out_qty,
cylinder_exchange.exchange,
((IFNULL(SUM(sales_details.customer_due),0)+IFNULL(inv_adj.out_qty,0)+IFNULL(cylinder_exchange.exchange,0))-(IFNULL(SUM(sales_details.customer_advance),0)+IFNULL(inv_adj.in_qty,0)) )as advanceOrDue
FROM
	customer cu
LEFT JOIN sales_invoice_info sii on  sii.customer_id=cu.customer_id
LEFT JOIN sales_details ON sales_details.sales_invoice_id = sii.sales_invoice_id
LEFT JOIN (
SELECT iai.customer_id,SUM(iad.in_qty) as in_qty,sum(iad.out_qty) as out_qty  FROM inventory_adjustment_info iai 
LEFT JOIN inventory_adjustment_details iad ON iad.inv_adjustment_info_id=iai.id GROUP BY iai.customer_id
) inv_adj ON inv_adj.customer_id=cu.customer_id
LEFT JOIN (
SELECT iai.customer_id,SUM(iai.exchange_out) as in_qty,sum(iai.exchange_in) as out_qty,(SUM(iai.exchange_in)-sum(iai.exchange_out)) as exchange  FROM cylinder_exchange_details iai 
 GROUP BY iai.customer_id
) cylinder_exchange on cylinder_exchange.customer_id=cu.customer_id
WHERE
	cu.customer_id =". $customerId;*/
        $queryCylinderAdvanceDue="SELECT
Tol1.customer_id,
SUM(IFNULL(Tol1.cylinder_exchange_qty_out,0)) cylinder_exchange_qty_out,
SUM(IFNULL(Tol1.cylinder_exchange_qty_in,0)) cylinder_exchange_qty_in,
SUM(IFNULL(Tol1.sales_qty,0)) sales_qty,
SUM(IFNULL(Tol1.sales_return_qty,0)) sales_return_qty,
SUM(IFNULL(Tol1.in_qty,0)) in_qty,
SUM(IFNULL(Tol1.out_qty,0)) out_qty,
(SUM(IFNULL(Tol1.in_qty,0))+SUM(IFNULL(Tol1.cylinder_exchange_qty_in,0))+SUM(IFNULL(Tol1.sales_return_qty,0)))-(SUM(IFNULL(Tol1.out_qty,0))+SUM(IFNULL(Tol1.sales_qty,0))+SUM(IFNULL(Tol1.cylinder_exchange_qty_out,0))) as advanceOrDue
FROM(
SELECT DISTINCT  

Tol.customer_id,
(IFNULL(Tol.cylinder_exchange_qty_out,0)) cylinder_exchange_qty_out,
(IFNULL(Tol.cylinder_exchange_qty_in,0)) cylinder_exchange_qty_in,

(IFNULL(Tol.sales_qty,0)) sales_qty,
(IFNULL(Tol.sales_return_qty,0)) sales_return_qty,
(IFNULL(Tol.in_qty,0)) in_qty,
(IFNULL(Tol.out_qty,0)) out_qty


FROM (SELECT BASE.customer_id,IFNULL(empty_cylinder_id,0) empty_cylinder_id,BASE.product_id,IFNULL(sales_qty,0) sales_qty,IFNULL(sales_return_qty,0) sales_return_qty,
IFNULL(in_qty,0) in_qty ,  IFNULL(out_qty,0) out_qty,


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

AND inv_adj_info.form_id = 2
AND product.category_id=1
GROUP BY iad.product_id,inv_adj_info.customer_id

) Inv_Adj ON 
Inv_Adj.product_id=BASE.product_id AND Inv_Adj.customer_id=BASE.customer_id 


 

LEFT OUTER JOIN(
SELECT
	ced.customer_id,IFNULL(SUM(ced.exchange_out),0) cylinder_exchange_qty_out ,IFNULL(SUM(ced.exchange_in),0) cylinder_exchange_qty_in ,ced.product_id
FROM
	cylinder_exchange_details ced
LEFT JOIN cylinder_exchange_info cei ON cei.exchange_info_id=ced.exchange_info_id
WHERE
	1=1

GROUP BY ced.product_id,ced.customer_id


 ) customer_due_cylinder_ex on customer_due_cylinder_ex.product_id=BASE.product_id AND customer_due_cylinder_ex.customer_id=BASE.customer_id
) Tol

  LEFT JOIN product on product.product_id=Tol.product_id
  LEFT JOIN productcategory on productcategory.category_id=product.category_id
LEFT JOIN brand ON brand.brandId=product.brand_id
LEFT JOIN customer ON customer.customer_id=Tol.customer_id
LEFT JOIN customertype on customertype.type_id=customer.customerType  WHERE 1=1 AND Tol.customer_id=". $customerId."  /*HAVING Tol.empty_cylinder_id !=0 AND product_id !=0 */ ORDER BY customer.customerName,product.productName,brand.brandName
)Tol1";



        $queryAd = $this->db->query($queryCylinderAdvanceDue);
        $result = $queryAd->row();
        //$returnResult['CylinderDue'] = $result->advanceOrDue-$result->exchange;
        $returnResult['CylinderDue'] = $result->advanceOrDue;
        return $returnResult;
    }
    function getCreditAmount($invoiceId)
    {
        $this->db->select("credit,generals_id,");
        $this->db->from("generals");
        $this->db->where("mainInvoiceId", $invoiceId);
        $this->db->where("form_id", 7);
        $this->db->where('dist_id', $this->dist_id);
        $creditAmount = $this->db->get()->row();
        if (!empty($creditAmount)) {
            return $creditAmount;
        }
    }
    function getAccountId($invoiceId)
    {
        $this->db->select("account");
        $this->db->from("generalledger");
        $this->db->where("generals_id", $invoiceId);
        $this->db->where("form_id", 7);
        $this->db->where("debit >=", '1');
        $this->db->where('dist_id', $this->dist_id);
        $this->db->order_by('generalledger_id', 'ASC');
        $creditAccount = $this->db->get()->row();
        if (!empty($creditAccount->account)) {

            return $creditAccount->account;
        }
    }
    function cashSalesInsertLedger($distId, $generalId, $allData)
    {
    }
    function getGpAmountByInvoiceId($distId, $invoiceId)
    {
        $stock = $this->db->get_where('stock', array('generals_id' => $invoiceId, 'type' => 'Out'))->result();
        $purchasesPrice = 0;
        $salesPrice = 0;
        foreach ($stock as $eachInfo) {
            $purchasesAvg = $this->getAveragePurchasesPrice($distId, $eachInfo->product_id);
            $invoiceSales = $eachInfo->rate;
            $purchasesPrice += $purchasesAvg * $eachInfo->quantity;
            $salesPrice += $eachInfo->rate * $eachInfo->quantity;
        }
        $gpAmount = $salesPrice - $purchasesPrice;
        if (!empty($gpAmount)) {
            return $gpAmount;
        } else {
            return '0.00';
        }
    }
    function getCustomerList($distID)
    {
        $this->db->select('customer_id,customerID,customerName,customer.dist_id,typeTitle');
        $this->db->from('customer');
        $this->db->join('customertype', 'customertype.type_id =customer.customertype');
        //$this->db->where('customer.dist_id', $distID);
        $this->db->order_by('customerName', 'ASC');
        return $this->db->get()->result();
    }
    function getReferenceList($distID)
    {
        $this->db->select('reference_id,refCode,referenceName,dist_id');
        $this->db->from('reference');
        $this->db->where('dist_id', $distID);
        $this->db->order_by('referenceName', 'ASC');
        return $this->db->get()->result();
    }
    function getAveragePurchasesPrice($distID, $productid)
    {
        $this->db->select('SUM(stock.price)/SUM(stock.quantity) as totalAvgPurchasesPrice');
        $this->db->from('stock');
        $this->db->join('product', 'stock.product_id = product.product_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = stock.category_id', 'left');
        $this->db->where('stock.type', 'In');
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $totalAvgPurchasesPrice = $this->db->get()->row();
        return $totalAvgPurchasesPrice->totalAvgPurchasesPrice;
    }
    function getAveragePurchasesPriceAll($distID, $productid)
    {
        $this->db->select('SUM(stock.price)/SUM(stock.quantity) AS totalAvgSalesPrice');
        $this->db->from('stock');
        $this->db->where('stock.type', 'In');
        $this->db->where('stock.dist_id', $distID);
        $this->db->where('stock.product_id', $productid);
        $this->db->order_by('stock.category_id', 'ASC');
        $openingAvgSalesPrice = $this->db->get()->row();
        return $openingAvgSalesPrice->totalAvgSalesPrice;
    }
    function getProductWiseSalesReport($dist_id, $productid, $startDate, $endDate)
    {
        $this->db->select("*");
        $this->db->from("stock");
        $this->db->join('generals', 'generals.generals_id=stock.generals_id');
        $this->db->join('customer', 'customer.customer_id=generals.customer_id');
        $this->db->where("stock.product_id", $productid);
        $this->db->where("stock.type", "Out");
        $this->db->where('stock.date >=', $startDate);
        $this->db->where('stock.date <=', $endDate);
        $this->db->where("stock.dist_id", $dist_id);
        $totalStockIn = $this->db->get()->result();
        return $totalStockIn;
    }
    function getProductWiseSalesReportAll($dist_id, $productid, $startDate, $endDate)
    {
        /* $this->db->select("SUM(stock.price)/SUM(stock.quantity) as totalAvgSalesPrice,sum(quantity) as totalSalesQty");
         $this->db->from("stock");
         $this->db->where("stock.product_id", $productid);
         $this->db->where("stock.type", "Out");
         $this->db->where('stock.date >=', $startDate);
         $this->db->where('stock.date <=', $endDate);
         $this->db->where("stock.dist_id", $dist_id);
         $totalStockIn = $this->db->get()->row();
         return $totalStockIn;*/
        $this->db->select("sales_details.product_id,
	sales_details.quantity,
	sales_details.unit_price,
	sales_invoice_info.invoice_no,
	sales_invoice_info.invoice_date,
	sales_invoice_info.sales_invoice_id,
	sales_invoice_info.customer_id");
        $this->db->from("sales_details");
        $this->db->join('sales_invoice_info', 'sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id');
        //$this->db->join('supplier', 'supplier.sup_id=generals.supplier_id');
        $this->db->where("sales_details.product_id", $productid);
        //$this->db->where("sales_details.is_opening", "0");
        $this->db->where("sales_details.is_active", "Y");
        $this->db->where("sales_details.is_delete", "N");
        $this->db->where('sales_invoice_info.invoice_date >=', $startDate);
        $this->db->where('sales_invoice_info.invoice_date <=', $endDate);
        $this->db->where("sales_invoice_info.dist_id", $dist_id);
        $totalStockIn = $this->db->get()->result();
        return $totalStockIn;
    }
    function getProductWisePurchasesReport($dist_id, $productid)
    {
        $this->db->select("");
        $this->db->from("stock");
        $this->db->where("stock.product_id", $productId);
        $this->db->where("stock.type", "In");
        $this->db->where("stock.dist_id", $this->dist_id);
        $totalStockIn = $this->db->get()->result();
    }
    /*function getProductStock($productId) {
        $this->db->select("sum(stock.quantity) as totalStockIn");
        $this->db->from("stock");
        $this->db->where("stock.product_id", $productId);
        $this->db->where("stock.type", "In");
        $this->db->where("stock.dist_id", $this->dist_id);
        $totalStockIn = $this->db->get()->row();
        $this->db->select("sum(stock.quantity) as totalStockOut");
        $this->db->from("stock");
        $this->db->where("stock.product_id", $productId);
        $this->db->where("stock.type", "Out");
        $this->db->where("stock.dist_id", $this->dist_id);
        $totalStockOut = $this->db->get()->row();
        return $totalStockIn->totalStockIn - $totalStockOut->totalStockOut;
    }*/
    function getProductStock($productId, $category_id = null, $ispackage = null, $branchId)
    {
        $this->db->select("category_id");
        $this->db->from("product");
        $this->db->where("product.product_id", $productId);
        $category_id = $this->db->get()->row();
        $is_package = $ispackage;
        if ($category_id->category_id == 1) {
            $query = "SELECT
                            IFNULL(purchase_details.purchase_qty,0) AS purchase_qty,
                            IFNULL(purchase_return_details.purchase_return_quantity,0) AS purchase_return_quantity,
                            IFNULL(sales_details.sales_qty,0) AS sales_qty,
                            IFNULL(sales_return_details.sales_return_quantity,0) AS sales_return_quantity,
                            IFNULL(exchange_details.exchange_qty,0) AS exchange_qty,
                            (IFNULL(purchase_details.purchase_qty,0) -IFNULL(purchase_return_details.purchase_return_quantity,0)+IFNULL(exchange_details.exchange_qty,0) )-(IFNULL(sales_details.sales_qty,0)-IFNULL(sales_return_details.sales_return_quantity,0)) AS qty
                        FROM
                            product
                        LEFT JOIN(
                            SELECT
                                purchase_details.product_id,
                                SUM(purchase_details.quantity)AS purchase_qty
                            FROM
                                purchase_details
                            WHERE
                                purchase_details.is_package = 0
                                AND purchase_details.is_active='Y'
                                AND purchase_details.is_delete='N'
                                AND purchase_details.branch_id=" . $branchId . "
                            GROUP BY
                                purchase_details.product_id
                        )AS purchase_details ON purchase_details.product_id = product.product_id
                        LEFT JOIN(
                            SELECT
                                purchase_return_details.product_id,
                                SUM(purchase_return_details.return_quantity)AS purchase_return_quantity
                            FROM
                                purchase_return_details
                            WHERE
                                1 = 1
                            AND purchase_return_details.is_active='Y'
                                AND purchase_return_details.is_delete='N'
                                AND purchase_return_details.branch_id=" . $branchId . "
                            GROUP BY
                                purchase_return_details.product_id
                        )AS purchase_return_details ON purchase_return_details.product_id = product.product_id
                        LEFT JOIN(
                            SELECT
                                sales_details.product_id,
                                SUM(sales_details.quantity)AS sales_qty
                            FROM
                                sales_details
                            WHERE
                                sales_details.is_package = 0
                            AND sales_details.is_active='Y'
                                AND sales_details.is_delete='N'
                                AND sales_details.branch_id=" . $branchId . "
                            GROUP BY
                                sales_details.product_id
                        ) AS sales_details ON sales_details.product_id = product.product_id
                        LEFT JOIN(
                            SELECT
                                sales_return_details.product_id,
                                IFNULL(SUM(sales_return_details.return_quantity),0) AS sales_return_quantity
                            FROM
                                sales_return_details
                            WHERE
                                1 = 1
                            AND sales_return_details.is_active='Y'
                                AND sales_return_details.is_delete='N'
                                AND sales_return_details.branch_id=" . $branchId . "
                            GROUP BY
                                sales_return_details.product_id
                        ) AS sales_return_details ON sales_return_details.product_id = product.product_id
                        left JOIN (
							/* cylinder_exchange qty*/
							SELECT
								(SUM(cylinder_exchange_details.exchange_in)-SUM(cylinder_exchange_details.exchange_out))AS exchange_qty,cylinder_exchange_details.product_id
							FROM
								cylinder_exchange_details
						WHERE
						/*cylinder_exchange_details.product_id = 11*/
						cylinder_exchange_details.is_active='Y'
                        AND cylinder_exchange_details.is_delete='N'
                        AND cylinder_exchange_details.branch_id=" . $branchId . "
							GROUP BY
								cylinder_exchange_details.product_id
						) AS exchange_details ON exchange_details.product_id=product.product_id
                        WHERE
                            product.product_id = " . $productId;
            //$query="select sum(purchase_details.quantity) AS purchase_qty FROM purchase_details WHERE purchase_details.is_package=".$is_package."  AND  purchase_details.product_id=".$productId;
            $query = $this->db->query($query);
            $result = $query->row();
            log_message('error','getProductStock from sales mode '.print_r($this->db->last_query(),true));
            //log_message('error','getProductStock '.print_r('NAHID 1',true));
            return $result->qty;
        } else if ($category_id->category_id != 1) {
            //$query="select sum(purchase_details.quantity) AS purchase_qty FROM purchase_details WHERE purchase_details.is_package=".$is_package."  AND  purchase_details.product_id=".$productId;
            $query2 = "select sum(purchase_details.quantity) AS purchase_qty,(sales_details.sales_qty) as sales_qty,IFNULL(sales_return.sales_return_qty,0) as sales_return_qty
                          FROM 
                          purchase_details 
                          LEFT JOIN(
                                SELECT
                                sales_details.product_id,	
                                sum(sales_details.quantity)AS sales_qty
                                FROM
                                    sales_details 
                                    WHERE 1=1
                                    AND sales_details.branch_id=" . $branchId . "
                                GROUP BY sales_details.product_id
                        ) AS sales_details  ON sales_details.product_id=purchase_details.product_id
                        LEFT JOIN(
                        SELECT
                                sales_return.product_id,	
                                sum(sales_return.return_quantity)AS sales_return_qty
                                FROM
                                    sales_return 
                                    WHERE 1=1
                                    AND sales_return.branch_id=" . $branchId . "
                                GROUP BY sales_return.product_id
                        )AS sales_return  ON sales_return.product_id=purchase_details.product_id
                          WHERE   purchase_details.product_id=" . $productId . " AND purchase_details.branch_id=" . $branchId;



           $query="SELECT
                    p.product_id,
                    sum(pd.quantity)AS purchase_qty,
                    IFNULL(sales_details.sales_qty,0)AS sales_qty,
                    IFNULL(sales_return.sales_return_qty,0)AS sales_return_qty,
                    IFNULL(inv_adj_table.adj_in,0) as adj_in,
                    IFNULL(inv_adj_table.adj_out,0) as adj_out
                FROM
                    product p
                LEFT JOIN purchase_details pd ON pd.product_id = p.product_id
                AND pd.branch_id = " . $branchId . "
                LEFT JOIN(
                    SELECT
                        sales_details.product_id,
                        sum(sales_details.quantity)AS sales_qty
                    FROM
                        sales_details
                    WHERE
                        1 = 1
                    AND sales_details.product_id=" . $productId . "
                    AND sales_details.branch_id = " . $branchId . "
                    GROUP BY
                        sales_details.product_id
                )AS sales_details ON sales_details.product_id = p.product_id
                LEFT JOIN(
                    SELECT
                        sales_return.product_id,
                        sum(sales_return.return_quantity)AS sales_return_qty
                    FROM
                        sales_return
                    WHERE
                        1 = 1
                    AND sales_return.product_id=" . $productId . "
                    AND sales_return.branch_id = " . $branchId . "
                    GROUP BY
                        sales_return.product_id
                )AS sales_return ON sales_return.product_id = p.product_id
                LEFT JOIN (
                SELECT
                    iad.product_id,
                    SUM(IFNULL(iad.in_qty, 0))adj_in,
                    SUM(IFNULL(iad.out_qty, 0))adj_out
                FROM
                    inventory_adjustment_details iad
                WHERE
                    iad.BranchAutoId = " . $branchId . "
                AND iad.product_id =" . $productId . "
                GROUP BY iad.product_id 
                ) AS inv_adj_table ON inv_adj_table.product_id= p.product_id
                
                WHERE
                    p.product_id = ".$productId;

            if ($ispackage != null && $ispackage == '1') {
                //$query .= "  AND purchase_details.is_package=" . $ispackage;
            }
            $query = $this->db->query($query);
            $result = $query->row();
            log_message('error', 'getProductStock ' . print_r($this->db->last_query(), true));
            //log_message('error','getProductStock '.print_r('NAHID 2',true));
            return $result->purchase_qty - $result->sales_qty +$result->sales_return_qty+$result->adj_in-$result->adj_out;
        }
    }
    function generals_customer($customer_id, $BranchAutoId)
    {



$query="SELECT
	sales_invoice_info.invoice_no,
	sales_invoice_info.invoice_date,
	sales_invoice_info.sales_invoice_id,
	(sales_product.sales_product_price + sales_invoice_info.transport_charge + sales_invoice_info.loader_charge-sales_invoice_info.discount_amount)AS invoice_amount,
	(sales_product.sales_product_price + sales_invoice_info.transport_charge + sales_invoice_info.loader_charge-sales_invoice_info.discount_amount)-paid_against_invoice.paid_amount AS amount,
	paid_against_invoice.paid_amount,
	sales_invoice_info.due_date,
	sales_invoice_info.payment_type,
	sales_invoice_info.narration,
	customer.customerName,
	customer.customerType,
	customer.customerID,
	customertype.typeTitle,
	sales_invoice_info.customer_id,
	branch.branch_name,
	branch.branch_id
FROM
	sales_invoice_info
LEFT JOIN(
	SELECT
		SUM(sd.quantity * sd.unit_price)sales_product_price,
		sd.sales_invoice_id
	FROM
		sales_details sd
	WHERE
		sd.show_in_invoice = 1
	GROUP BY
		sd.sales_invoice_id
)sales_product ON sales_product.sales_invoice_id = sales_invoice_info.sales_invoice_id
LEFT JOIN(
	SELECT
		SUM(acd.GR_CREDIT)AS paid_amount,
		acd.invoice_id
	FROM
		ac_tb_accounts_voucherdtl acd
	WHERE
		acd.`for` = 5
	GROUP BY
		acd.invoice_id
)paid_against_invoice ON paid_against_invoice.invoice_id = sales_invoice_info.sales_invoice_id
LEFT JOIN customer ON customer.customer_id = sales_invoice_info.customer_id
LEFT JOIN customertype ON customertype.type_id = customer.customerType
LEFT JOIN branch ON branch.branch_id = sales_invoice_info.branch_id
WHERE
	sales_invoice_info.is_active = 'Y'
AND sales_invoice_info.is_delete = 'N'
AND branch.is_active = '1'
 AND sales_invoice_info.customer_id =" . $customer_id;

        if ($BranchAutoId != 'all' || $BranchAutoId != null) {
            $query .= " AND sales_invoice_info.branch_id=" . $BranchAutoId;
        }
        $query .= "  HAVING
                    amount > 0 ";
$query .=" ORDER BY
	branch.branch_name ";







        $query = $this->db->query($query);
        $result['invoice_list'] = $query->result_array();
        return $result;
        //$query = $this->db->get_where('generals', array('form_id' => 5, 'customer_id' => $customer_id));
        //return $query->result_array();
    }
    function customer_cylinder_due_recive($customer_id, $BranchAutoId)
    {
        $query = "SELECT
	sii.sales_invoice_id,
	sii.invoice_for,
	sii.invoice_no,
	sii.insert_date,
    sii.customer_id,
	sii.customer_invoice_no,
	sii.invoice_amount,
	cus.customerID,
	cus.customerName,
	sd.sales_details_id,
	sd.product_id,
	pro.productName,
	proc.title,
	br.brandName,
	sd.returnable_quantity,
	sd.return_quentity,
sd.quantity,
	sd.customer_advance,
	sd.customer_due
FROM
	sales_invoice_info sii
LEFT JOIN customer cus ON cus.customer_id = sii.customer_id
LEFT JOIN sales_details sd ON sii.sales_invoice_id = sd.sales_invoice_id
LEFT JOIN product pro ON pro.product_id = sd.product_id
LEFT JOIN productcategory proc ON proc.category_id = pro.category_id
LEFT JOIN brand br ON br.brandId = pro.brand_id
WHERE
	sii.is_active = 'Y'
AND sii.is_delete = 'N'
AND sii.customer_id = " . $customer_id;
        if ($BranchAutoId != 'all' || $BranchAutoId != null) {
            $query .= " AND sii.invoice_for = 2 AND sii.branch_id=" . $BranchAutoId;
        }
        $query .= "  HAVING
	                sd.customer_due > 0";
        $query = $this->db->query($query);
        $result['invoice_list'] = $query->result_array();
        return $result;
        //$query = $this->db->get_where('generals', array('form_id' => 5, 'customer_id' => $customer_id));
        //return $query->result_array();
    }
    function generals_voucher($voucher_no)
    {
        $dr = '';
        $cr = '';
        $query = $this->db->get_where('generals', array('voucher_no' => $voucher_no))->result_array();
        foreach ($query as $row) {
            $dr += $row['debit'];
            $cr += $row['credit'];
        }
        $bal = $dr - $cr;
        return $bal;
    }
    function productCost($productId, $dist_id)
    {
        $this->db->select('SUM(stock.price)/SUM(stock.quantity) as totalAvgPurchasesPrice');
        $this->db->from('stock');
        $this->db->where('stock.product_id', $productId);
        $this->db->where('stock.dist_id', $dist_id);
        $this->db->where('stock.type', 'In');
        $results = $this->db->get()->row_array();
        return $results['totalAvgPurchasesPrice'];
    }
    function productCostNew($productId, $branch_id)
    {
        $query = "SELECT
	(SUM(purchase_details.quantity * purchase_details.unit_price)/ SUM(purchase_details.quantity)) AS totalAvgPurchasesPrice
FROM
	purchase_details
WHERE
  purchase_details.is_active='Y'
	AND purchase_details.product_id =" . $productId;
        if($branch_id!=''){
            $query.=" AND purchase_details.branch_id=".$branch_id;
        }

        $query = $this->db->query($query);
        $result = $query->row_array();
        return $result['totalAvgPurchasesPrice'];
    }


    function  emptyCylinderPurchasePrice($id){
        $query="SELECT  BaseT.product_id, 
 

IFNULL(((IFNULL(PD.quantity_P,0) * IFNULL(U_Price_Purchase_In,0)) + (IFNULL(SRD.sales_SRe,0) * IFNULL(U_Price_sales_SRe,0) ) + (IFNULL(INV_in_qty,0) * IFNULL(INV_IN_UPrice,0)) )/
(NULLIF((IFNULL(PD.quantity_P,0)+IFNULL(SRD.sales_SRe,0)+IFNULL(INV_in_qty,0)),0) ),0)  Avg_Pur_Rate



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
 
  
 

GROUP BY   purchase_details.product_id  
 ) PD ON  BaseT.product_id= PD.product_id  Left outer JOIN
 

(SELECT   sales_return_details.product_id,  

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

     

GROUP BY   sales_return_details.product_id
) SRD ON  BaseT.product_id= SRD.product_id  Left outer JOIN
 

(SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
      
         
       
       GROUP BY sr.product_id
) SR    ON  BaseT.product_id= SR.product_id  Left outer JOIN
    
    (   SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  

       
       GROUP BY iad.product_id ) Inv_Adj_In ON  BaseT.product_id= Inv_Adj_In.product_id
       WHERE BaseT.product_id=".$id;
        $query = $this->db->query($query);
        $result = $query->row_array();
        return $result['Avg_Pur_Rate'];
    }
    function getCustomerID($distributorid)
    {
        $cusOrgId = $this->db->where('dist_id', $distributorid)->count_all_results('customer') + 1;
        $CustomerGeneratedID = "CID" . date("y") . date("m") . str_pad($cusOrgId, 4, "0", STR_PAD_LEFT);
        return $CustomerGeneratedID;
    }
    public function checkDuplicateCusID($customerGeneratedID, $distributorid)
    {
        $checkExits = $this->db->get_where('customer', array('dist_id' => $distributorid, 'customerID' => $customerGeneratedID))->row();
        if (!empty($checkExits)) {
            $supOriId = $this->db->where('dist_id', $distributorid)->count_all_results('customer') + 2;
            return $newID = "CID" . date("y") . date("m") . str_pad($supOriId, 4, "0", STR_PAD_LEFT);
        } else {
            if (!empty($customerGeneratedID)) {
                return $customerGeneratedID;
            }
        }
    }
    function getReturnAbleCylinder2($distId, $invoiceId, $productId, $qty = null)
    {
        $this->db->select("quantity");
        $this->db->from("stock");
        $this->db->where("dist_id", $distId);
        //form Id 24 means cylinder receive,form Id 23 means cylinder out
        $this->db->where("form_id", 23);
        if (!empty($qty)):
            $this->db->where("quantity", $qty);
        endif;
        $this->db->where("product_id", $productId);
        $this->db->where("generals_id", $invoiceId);
        $generalId = $this->db->get()->row();
        return $generalId;
    }
    function getCylinderInOutResult2($distId, $invoiceId, $formId)
    {
        $this->db->select('product.productName,brand.brandName,unit.unitTtile,productcategory.title,stock.quantity,stock.category_id,stock.product_id,stock.unit');
        $this->db->from('stock');
        $this->db->join('productcategory', 'stock.category_id = productcategory.category_id');
        $this->db->join('product', 'stock.product_id = product.product_id');
        $this->db->join('unit', 'stock.unit = unit.unit_id');
        $this->db->join('brand', 'product.brand_id = brand.brandId');
        $this->db->where('stock.dist_id', $distId);
        //type 24 means cylinder receive,type 23 means cylinder out
        $this->db->where('stock.form_id', $formId);
        $this->db->where('stock.generals_id', $invoiceId);
        $cylinderInOutResult = $this->db->get()->result();
        return $cylinderInOutResult;
    }
    public function customer_list($dist_id)
    {
        $query = "SELECT DISTINCT
	customer.customerID,
	customer.customerType,
	customer.customerName,
	customer.customer_id,
	customer.dist_id,
	(
		SELECT
			sales_invoice_info.sales_invoice_id
		FROM
			sales_invoice_info
		WHERE
			sales_invoice_info.is_active = 'Y'
		AND sales_invoice_info.is_delete = 'N'
		AND sales_invoice_info.customer_id = customer.customer_id
		LIMIT 1
	)AS have_invoice
FROM
	customer
WHERE
	1 = 1";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function customer_due($customer_id, $start_date, $end_date, $dist_id)
    {
        $query = "SELECT
                sales_invoice_info.sales_invoice_id,
                sales_invoice_info.invoice_no,
                sales_invoice_info.invoice_date,
                sales_invoice_info.invoice_amount,
                sales_invoice_info.paid_amount,
                sales_invoice_info.customer_id,
                customer.customerID,
                customer.customerName,
            CONCAT(customer.customerName, ' [ ',customer.customerID,' ]') as productName
            FROM
                sales_invoice_info
            LEFT JOIN customer ON customer.customer_id = sales_invoice_info.customer_id
            WHERE
                sales_invoice_info.is_active = 'Y'
            AND sales_invoice_info.is_delete = 'N'
             AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                AND sales_invoice_info.invoice_date <= '" . $end_date . "'";
        if ($customer_id != 'All') {
            $query .= "AND sales_invoice_info.customer_id=" . $customer_id;
        }
        $query .= "ORDER BY sales_invoice_info.invoice_date ASC";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function customer_due_invoice_list($customer_id, $dist_id)
    {
        $query = "SELECT
                    sales_invoice_info.invoice_no,
                    sales_invoice_info.sales_invoice_id,
                    sales_invoice_info.invoice_amount,
                    sales_invoice_info.paid_amount,
                    IFNULL(cus_due_collection_details.due_paid_amount,0	)AS due_paid_amount,
                    (sales_invoice_info.invoice_amount -(sales_invoice_info.paid_amount + IFNULL(cus_due_collection_details.due_paid_amount,0)))AS amount
                FROM
                    sales_invoice_info
                LEFT JOIN(SELECT
                        sales_invoice_id,
                        SUM(cus_due_collection_details.paid_amount)AS due_paid_amount
                    FROM
                        cus_due_collection_details /*WHERE
                                        cus_due_collection_details.sales_invoice_id = 1*/
                    GROUP BY
                        cus_due_collection_details.sales_invoice_id
                )AS cus_due_collection_details ON cus_due_collection_details.sales_invoice_id = sales_invoice_info.sales_invoice_id
                WHERE
                    sales_invoice_info.payment_type IN(2, 4)/*payment type chash and full credit*/
                AND sales_invoice_info.customer_id =" . $customer_id . "
                HAVING
                    amount > 0";
        $query = $this->db->query($query);
        $result['invoice_list'] = $query->result();
        return $result;
    }
    public function customer_due_invoice_data($cus_due_collection_info_id, $dist_id)
    {
        $query = "SELECT
	cus_due_collection_info.id,
	cus_due_collection_info.total_paid_amount,
	cus_due_collection_info.cus_due_coll_no,
	cus_due_collection_info.customer_id,
	cus_due_collection_info.date,
	cus_due_collection_info.bank_name,
	cus_due_collection_info.branch_name,
	cus_due_collection_info.check_no,
	cus_due_collection_info.check_date,
	customer.customerID,
	customer.customerName,
	cus_due_collection_details.sales_invoice_id,
	sales_invoice_info.invoice_no,
	cus_due_collection_details.paid_amount,
	customer_advance.advance_amount,
	customer_advance.advance_recive_voucher
FROM
	cus_due_collection_info
LEFT JOIN customer ON customer.customer_id = cus_due_collection_info.customer_id
LEFT JOIN cus_due_collection_details ON cus_due_collection_details.due_collection_info_id = cus_due_collection_info.id
LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = cus_due_collection_details.sales_invoice_id
LEFT JOIN(
	SELECT
		customer_advance.advance_amount,
		customer_advance.advance_recive_voucher,
		customer_advance.due_collection_id
	FROM
		customer_advance
	WHERE
		customer_advance.is_active = 'Y'
	AND customer_advance.is_delete = 'N'
)AS customer_advance ON customer_advance.due_collection_id = cus_due_collection_info.id
WHERE
	cus_due_collection_info.is_active = 'Y'
AND cus_due_collection_info.is_delete = 'N'
AND cus_due_collection_info.id = " . $cus_due_collection_info_id;
        $query = $this->db->query($query);
        $result['invoice_list'] = $query->result();
        return $result;
    }
    function getInvoiceIdListByVoucher($disId, $invoiceId, $type)
    {
        $this->db->select("generals_id");
        $this->db->from("generals");
        $this->db->where("form_id", $type);
        $this->db->where("dist_id", $disId);
        $this->db->where("voucher_no", $invoiceId);
        return $this->db->get()->result();
    }
    function getProductSummationSalesReport($dist_id, $productid, $startDate, $endDate, $branch_id)
    {
        $query = "SELECT sales_details.product_id,
SUM(sales_details.quantity) AS QTY,
(SUM((sales_details.quantity*sales_details.unit_price))/SUM(sales_details.quantity)) AS unitPrice,
product.productName,
product.product_code,
productcategory.title,
brand.brandName,
brand.brandName,
branch.branch_name
FROM 
sales_details
LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
 JOIN product ON sales_details.product_id=product.product_id
LEFT JOIN productcategory on productcategory.category_id=product.category_id
LEFT JOIN brand on brand.brandId=product.brand_id
LEFT JOIN branch on branch.branch_id=sales_invoice_info.branch_id
WHERE 
sales_details.is_active='Y'
AND sales_details.is_delete='N'
AND sales_details.show_in_invoice='1'
             AND sales_invoice_info.invoice_date >= '" . $startDate . "'
                AND sales_invoice_info.invoice_date <= '" . $endDate . "'";
        if ($branch_id != 'all') {
            $query .= " AND    sales_invoice_info.brand_id=" . $branch_id;
        }
        $query .= "     GROUP BY sales_details.product_id,branch.branch_id   ORDER BY brand.brandName,productcategory.title,product.productName";
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
    function getProductWiseSalesReport2($dist_id, $productid, $startDate, $endDate, $branch_id)
    {
        $this->db->select("sales_details.product_id,
	sales_details.quantity,
	sales_details.unit_price,
	sales_invoice_info.invoice_no,
	sales_invoice_info.invoice_date,
	sales_invoice_info.sales_invoice_id,
	
	customer.customerID,
	customer.customerName,
	
	sales_invoice_info.customer_id,branch.branch_name,branch.branch_id");
        $this->db->from("sales_details");
        $this->db->join('sales_invoice_info', 'sales_invoice_info.sales_invoice_id=sales_details.sales_invoice_id');
        $this->db->join('customer', 'customer.customer_id=sales_invoice_info.customer_id');
        $this->db->join('branch', 'branch.branch_id=sales_invoice_info.branch_id');
        $this->db->where("sales_details.product_id", $productid);
        //$this->db->where("sales_details.is_opening", "0");
        $this->db->where("sales_details.is_active", "Y");
        $this->db->where("sales_details.is_delete", "N");
        $this->db->where("sales_details.show_in_invoice", "1");
        $this->db->where('sales_invoice_info.invoice_date >=', $startDate);
        $this->db->where('sales_invoice_info.invoice_date <=', $endDate);
        if ($branch_id != 'all') {
            $this->db->where('sales_invoice_info.branch_id ', $branch_id);
        }
        //$this->db->group_by("branch.branch_id");
        $this->db->order_by('branch.branch_name');
        $totalStockIn = $this->db->get()->result();
        return $totalStockIn;
    }

    function getrootSalesList($distId, $fromDate, $endDate, $cusId = null, $root = null, $branch_id = 'all')
    {

        $query="SELECT
	sii.sales_invoice_id,
    IFNULL(root_info.`name`,'No Root') AS root_name,
	customer.customerName,
	customer.customerID,
	sii.customer_id,
	sii.branch_id,
	sii.customer_invoice_no,
	sii.invoice_date,
	sii.invoice_no,
	branch.branch_name,
	sales_product.otherLedger
FROM
	sales_invoice_info sii
LEFT JOIN branch ON sii.branch_id = branch.branch_id
LEFT JOIN customer ON sii.customer_id = customer.customer_id
LEFT JOIN root_info ON root_info.root_id=customer.root_id
LEFT JOIN(
	SELECT
		sd.sales_invoice_id,
		GROUP_CONCAT(
			CONCAT(
				productcategory.title,
				' ',
				product.productName
			),
			'¥',
			sd.unit_price,
			'¥',
			sd.quantity,
			'*¥¥*'
		)AS otherLedger
	FROM
		sales_details sd
	LEFT JOIN product ON product.product_id = sd.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	WHERE
		1 = 1
	AND sd.show_in_invoice = 1
	GROUP BY
		sd.sales_invoice_id
)AS sales_product ON sales_product.sales_invoice_id = sii.sales_invoice_id
WHERE
	1 = 1 AND branch.is_active = '1'";

        if ($branch_id != 'all') {
        $query .= " AND sii.branch_id=" . $branch_id;
    }
        if ($cusId != 'all' ) {
        $query .= " AND sii.customer_id=" . $cusId;
    }
    if ($root != 'all' ) {
        $query .= " AND customer.root_id=" . $root;
    }

        $query .= "
AND sii.invoice_date >= '". $fromDate ."'
AND sii.invoice_date <= '". $endDate ."'
ORDER BY
	branch.branch_name,root_name,
customer.customerName ";

        $query = $this->db->query($query);
        log_message('error', 'Last Query :-' . print_r($this->db->last_query(), true));
        $result = $query->result();

        foreach ($result as $key => $value) {
            $array[$value->branch_name][$value->root_name][] = $value;
        }

        return $array;
    }
}
?>