<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/16/2019
 * Time: 12:05 PM
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class SalesReport_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
    }
    function date_wise_product_sales($start_date, $end_date, $brandId, $branch_id = 'all')
    {
        $query = "SELECT
                    sales_details.product_id,
                    CONCAT(productcategory.title, ' ',product.productName, ' [ ',brand.brandName,' ]') as productName,
                    product.product_code,
                    productcategory.title,
                    brand.brandName,
                    SUM(sales_details.quantity)AS sales_qty,
                    AVG(sales_details.unit_price)AS sales_price2,
                    avarage_price_table.price AS sales_price,
                    branch.branch_id,
                    branch.branch_name
                FROM
                    sales_details
                JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
                LEFT JOIN product ON product.product_id = sales_details.product_id
                LEFT JOIN productcategory ON productcategory.category_id = product.category_id
                LEFT JOIN brand ON brand.brandId = product.brand_id     
                LEFT JOIN branch ON branch.branch_id=sales_details.branch_id  
                LEFT JOIN (
                    SELECT
                        (SUM(sales_details.unit_price * sales_details.quantity)/ sum(sales_details.quantity))AS price,
                        sales_details.branch_id,
                        sales_details.product_id
                    FROM
                        sales_details
                    LEFT JOIN sales_invoice_info sii ON sii.sales_invoice_id=sales_details.sales_invoice_id
                    WHERE
                        1=1 ";
                            if ($branch_id != 'all') {
                                $query .= " and sales_details.branch_id=" . $branch_id;
                            }
                    $query .= " AND sales_details.is_active = 'Y'
                    AND sales_details.is_delete = 'N'
                    AND sii.is_active = 'Y'
                    AND sii.is_delete = 'N'
                        /*sales_details.product_id = 1247*/
                    AND sii.invoice_date >= '" . $start_date . "'
                    AND sii.invoice_date <= '" . $end_date . "'
                    GROUP BY sales_details.product_id,sales_details.branch_id 
                ) avarage_price_table ON avarage_price_table.product_id=sales_details.product_id
                AND avarage_price_table.branch_id=sales_details.branch_id        
                WHERE
                    sales_details.is_active = 'Y'
                AND sales_details.is_delete = 'N'
                AND sales_invoice_info.is_active = 'Y'
                AND sales_invoice_info.is_delete = 'N'
                AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                AND sales_invoice_info.invoice_date <= '" . $end_date . "'";
        if ($branch_id != 'all') {
            $query .= " and sales_details.branch_id=" . $branch_id;
        }
        $query .= " GROUP BY
                    sales_details.product_id,sales_details.branch_id  ORDER BY branch.branch_name,sales_invoice_info.invoice_date DESC ";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    function date_wise_product_sales_by_date($start_date, $end_date, $brandId, $branch_id)
    {
        $query = "SELECT
                    sales_invoice_info.invoice_date,
                    sales_details.product_id,
                    CONCAT(productcategory.title, ' ',product.productName, ' [ ',brand.brandName,' ]') as productName,
                    product.product_code,
                    productcategory.title,
                    brand.brandName,
                    SUM(sales_details.quantity)AS sales_qty,
                    AVG(sales_details.unit_price)AS sales_price1,
                    avarage_price_table.price AS sales_price,
                    branch.branch_id,
                    branch.branch_name
                FROM
                    sales_details
                JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
                LEFT JOIN product ON product.product_id = sales_details.product_id
                LEFT JOIN productcategory ON productcategory.category_id = product.category_id
                LEFT JOIN brand ON brand.brandId = product.brand_id   
                LEFT JOIN branch ON branch.branch_id=sales_details.branch_id 
                LEFT JOIN (
                    SELECT
                        (SUM(sales_details.unit_price * sales_details.quantity)/ sum(sales_details.quantity))AS price,
                        sales_details.branch_id,
                        sii.invoice_date,
                        sales_details.product_id
                    FROM
                        sales_details
                    LEFT JOIN sales_invoice_info sii ON sii.sales_invoice_id=sales_details.sales_invoice_id
                    WHERE
                        1=1
                    AND sales_details.is_active = 'Y'
                    AND sales_details.is_delete = 'N'
                    AND sii.is_active = 'Y'
                    AND sii.is_delete = 'N'
                        /*sales_details.product_id = 1247*/
                    AND sii.invoice_date >= '" . $start_date . "'
                    AND sii.invoice_date <= '" . $end_date . "'
                    GROUP BY sales_details.product_id,sii.invoice_date,sales_details.branch_id 
                ) avarage_price_table ON avarage_price_table.product_id=sales_details.product_id
                AND avarage_price_table.branch_id=sales_details.branch_id
                AND avarage_price_table.invoice_date=sales_invoice_info.invoice_date
                WHERE
                    sales_details.is_active = 'Y'
                AND sales_details.is_delete = 'N'
                AND sales_invoice_info.is_active = 'Y'
                AND sales_invoice_info.is_delete = 'N'
                AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                AND sales_invoice_info.invoice_date <= '" . $end_date . "'
                ";
        if ($branch_id != 'all') {
            $query .= " and sales_details.branch_id=" . $branch_id;
        }
        $query .= "  GROUP BY
                    sales_details.product_id,sales_invoice_info.invoice_date,sales_details.branch_id
                               ORDER BY branch.branch_name,sales_invoice_info.invoice_date DESC ";
//AND sales_invoice_info.dist_id ='".$this->dist_id."'
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function sales_report($start_date, $end_date, $brandId, $branch_id,$type="all",$customer_id='all')
    {
        $query = "SELECT
                        sales_invoice_info.invoice_no,
                        sales_invoice_info.invoice_date,
                        sales_invoice_info.sales_invoice_id,
                        (sales_product.sales_product_price + sales_invoice_info.transport_charge + sales_invoice_info.loader_charge)AS invoice_amount,
                        paid_against_invoice.paid_amount,
                        (sales_product.sales_product_price + sales_invoice_info.transport_charge + sales_invoice_info.loader_charge)-paid_against_invoice.paid_amount AS balance,
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

                AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                AND sales_invoice_info.invoice_date <= '" . $end_date . "'
                ";
        if ($branch_id != 'all') {
            $query .= " AND sales_invoice_info.branch_id=" . $branch_id;
        }
        if ($customer_id != 'all') {
            $query .= " AND sales_invoice_info.customer_id=" . $customer_id;
        }
        $query .= " AND sales_invoice_info.is_active=Y" ;
        if($type !='all'){
            $query .= "  HAVING
                    balance > 0 ";
        }

        $query .= " ORDER BY branch.branch_name,sales_invoice_info.due_date";
        //log_message('error', 'quert sales ' . print_r($query, true));
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }
    public function getCustomerSalesList($start_date, $end_date, $cusType, $customer_id)
    {
        $query = "SELECT
                    sales_invoice_info.sales_invoice_id,
                    sales_invoice_info.invoice_no,
                    sales_invoice_info.invoice_date,
                    sales_invoice_info.invoice_amount,
                    sales_invoice_info.payment_type,
                    sales_invoice_info.narration,
                    customer.customerName,
                    customer.customerType,
                    customer.customerID,
                    customertype.typeTitle,
                    sales_invoice_info.customer_id
                FROM
                    sales_invoice_info
                LEFT JOIN customer ON customer.customer_id = sales_invoice_info.customer_id
                LEFT JOIN customertype ON customertype.type_id = customer.customerType
                WHERE
                    sales_invoice_info.is_active = 'Y'
                AND sales_invoice_info.is_delete = 'N'
                AND sales_invoice_info.invoice_date >= '" . $start_date . "'
                AND sales_invoice_info.invoice_date <= '" . $end_date . "'
                AND sales_invoice_info.dist_id ='" . $this->dist_id . "' ";
        $query = $this->db->query($query);
        $result = $query->result();
        $salesInvoice = array();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $returnProduct = "SELECT
	sales_details.sales_details_id,
	sales_details.is_package,
	sales_details.product_id,
	sales_details.quantity,
	sales_details.unit_price,
	product.productName,
	product.product_code,
	productcategory.title,
	brand.brandName,
	sales_return_details.return_quantity,
	sales_return_details.product_id AS return_product_id,
	sales_return_details.productName AS return_product,
	sales_return_details.product_code AS return_product_code,
	sales_return_details.title AS return_product_cat,
	sales_return_details.brandName AS return_product_brand
FROM
	sales_details
LEFT JOIN product ON product.product_id = sales_details.product_id
LEFT JOIN productcategory ON productcategory.category_id = product.category_id
LEFT JOIN brand ON brand.brandId = product.brand_id
LEFT JOIN(
	SELECT
		sales_return_details.sales_details_id,
		sales_return_details.product_id,
		sales_return_details.return_quantity,
		sales_return_details.unit_price,
		product.productName,
		product.product_code,
		productcategory.title,
		brand.brandName
	FROM
		sales_return_details
	LEFT JOIN product ON product.product_id = sales_return_details.product_id
	LEFT JOIN productcategory ON productcategory.category_id = product.category_id
	LEFT JOIN brand ON brand.brandId = product.brand_id
	WHERE
		sales_return_details.is_active = 'Y'
	AND sales_return_details.is_delete = 'N'
)AS sales_return_details ON sales_return_details.sales_details_id = sales_details.sales_details_id
WHERE
	sales_details.is_active = 'Y'
AND sales_details.is_delete = 'N' AND  sales_details.sales_invoice_id =" . $value->sales_invoice_id;
                $returnProduct = $this->db->query($returnProduct);
                $resultReturn = $returnProduct->result();
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_invoice_id'] = 1;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['invoice_no'] = $value->invoice_no;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['invoice_date'] = $value->invoice_date;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['invoice_amount'] = $value->invoice_amount;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['payment_type'] = $value->payment_type;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['narration'] = $value->narration;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['customerName'] = $value->customerName;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['customerType'] = $value->customerType;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['customerID'] = $value->customerID;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['typeTitle'] = $value->typeTitle;
                $salesInvoice[$value->customer_id][$value->sales_invoice_id]['customer_id'] = $value->customer_id;
                foreach ($resultReturn as $key1 => $value1) {
                    $salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_item'][$value1->product_id]['quantity'] = $value1->quantity;
                    $salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_item'][$value1->product_id]['unit_price'] = $value1->unit_price;
                    $salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_item'][$value1->product_id]['productName'] = $value1->productName;
                    $salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_item'][$value1->product_id]['product_code'] = $value1->product_code;
                    $salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_item'][$value1->product_id]['title'] = $value1->title;
                    //$salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_item'][$value1->product_id]['return_product'][]=$resultReturn[$key1];
                    //$salesInvoice[$value->customer_id][$value->sales_invoice_id]['sales_item'][$value1->product_id]['return_product'][]=$resultReturn[$key1];
                }
            }
        }
        return $salesInvoice;
    }
    function daily_sales_statement($start_date, $end_date, $brandId,$branch_id)
    {
        /* $query = "SELECT
             sales_invoice_info.invoice_date,
                 SUM(sales_invoice_info.invoice_amount) as sales_amount,
                 SUM(sales_invoice_info.paid_amount) as customer_paid_amount,
                 SUM(sales_invoice_info.discount_amount) as discount_amount,
                 branch.branch_id,
                 branch.branch_name,
                 clint_vendor.paid
             FROM
                 sales_invoice_info
                 LEFT JOIN (
                 SELECT SUM(cvl.cr) as paid, cvl.date,cvl.BranchAutoId FROM client_vendor_ledger cvl WHERE
                     cvl.date >= '" . $start_date . "'
                 AND cvl.date <= '" . $end_date . "'
                 AND cvl.ledger_type=2

                 GROUP BY cvl.date,cvl.BranchAutoId
                 ) as clint_vendor on clint_vendor.date=sales_invoice_info.invoice_date
                 AND sales_invoice_info.branch_id=clint_vendor.BranchAutoId
                 LEFT JOIN branch ON branch.branch_id=sales_invoice_info.branch_id
                 WHERE
              sales_invoice_info.invoice_date >= '" . $start_date . "'
         AND sales_invoice_info.invoice_date <= '" . $end_date . "'";
         if($branch_id!='all'){
             $query.=" and sales_invoice_info.branch_id=".$branch_id;
         }
         $query.=" GROUP BY sales_invoice_info.invoice_date,sales_invoice_info.branch_id ORDER BY branch.branch_name,sales_invoice_info.invoice_date DESC";

         */


        $query="SELECT
sii.branch_id,
branch.branch_name,
	sii.invoice_date,
-- 	SUM(sii.discount_amount)AS discount_amount,
-- 	SUM(sii.`loader_charge`)AS loader_charge,
-- 	SUM(sii.`transport_charge`)AS transport_charge,
	SUM(sd.quantity * sd.unit_price)AS sales_amount
FROM
	sales_details sd
LEFT JOIN sales_invoice_info sii ON sii.sales_invoice_id = sd.sales_invoice_id
LEFT JOIN branch  ON branch.branch_id=sii.branch_id
WHERE
	sd.show_in_invoice = 1
AND sii.invoice_date >= '" . $start_date . "'
AND sii.invoice_date <= '" . $end_date . "'";
        if($branch_id!='all'){
            $query.=" and sales_invoice_info.branch_id=".$branch_id;
        }
        $query.="GROUP BY
	sii.invoice_date,sii.branch_id
ORDER BY branch.branch_name,sii.invoice_date";



        log_message('error','This is sales report query'.print_r($query,true));

        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }




}