<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 6/29/2019
 * Time: 3:29 PM
 */



if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_Model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->dist_id = $this->session->userdata('dis_id');
    }


    public function  index(){

    }
    public function month_wise_sales($option=''){
        $query = "SELECT
                    SUM(invoice_amount) AS  amount,
                    MONTHNAME(invoice_date) AS  month_name
                FROM
                    sales_invoice_info
                    WHERE dist_id=".$this->dist_id."
                    AND is_active='Y' AND is_delete='N'
                    AND YEAR(invoice_date) = YEAR(CURDATE())
                GROUP BY
                    MONTHNAME(invoice_date)";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;

    }
    public function month_wise_purchase($option=''){
        $query = "SELECT
                    SUM(invoice_amount) AS  amount,
                    MONTHNAME(invoice_date) AS  month_name
                FROM
                    purchase_invoice_info
                    WHERE dist_id=".$this->dist_id."
                    AND is_active='Y' AND is_delete='N'
                    AND YEAR(invoice_date) = YEAR(CURDATE())
                GROUP BY
                    MONTHNAME(invoice_date)";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;

    }
    public function day_wise_sales_grap($option=''){
        $query2 = "SELECT
	SUM(invoice_amount)AS amount,
	
DATE_FORMAT(invoice_date, ' %d ') AS day
FROM
	sales_invoice_info
WHERE
	dist_id = ".$this->dist_id."
AND is_active = 'Y'
AND is_delete = 'N'
AND YEAR(invoice_date)= YEAR(CURDATE())
AND invoice_date BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
GROUP BY
	invoice_date";


        $query="SELECT
DATE_FORMAT(td.db_date, '%d') as day,
DATE_FORMAT(td.db_date, '%m') as month,
	td.db_date,CURDATE() as aaa,
  IFNULL(SUM(pii.invoice_amount),0)AS amount
FROM
	time_dimension td
left JOIN sales_invoice_info pii ON pii.invoice_date=td.db_date
WHERE
	td.db_date BETWEEN( CURDATE()- INTERVAL 1 MONTH )
AND CURDATE()
GROUP BY td.db_date
ORDER BY td.db_date DESC";

        $query = $this->db->query($query);
        $result = $query->result();
        return $result;

    }
    public function day_wise_purchase_grap($option=''){

        /* '%m/%d'*/
        $query="SELECT
DATE_FORMAT(td.db_date, '%d') as day,
DATE_FORMAT(td.db_date, '%m') as month,
	td.db_date,CURDATE() as aaa,
  IFNULL(SUM(pii.invoice_amount),0)AS amount
FROM
	time_dimension td
left JOIN purchase_invoice_info pii ON pii.invoice_date=td.db_date
WHERE
	td.db_date BETWEEN( CURDATE()- INTERVAL 1 MONTH )
AND CURDATE()
GROUP BY td.db_date
ORDER BY td.db_date DESC";



        $query2 = "SELECT
	SUM(invoice_amount)AS amount,
	
DATE_FORMAT(invoice_date, ' %d ') AS day
FROM
	purchase_invoice_info
WHERE
	dist_id = ".$this->dist_id."
AND is_active = 'Y'
AND is_delete = 'N'
AND YEAR(invoice_date)= YEAR(CURDATE())
AND invoice_date BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
GROUP BY
	invoice_date";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;

    }
    function accountBalance($code, $fromDate = NULL, $toDate = NULL) {


        $debit = 0;
        $credit = 0;
        $result = 0;
        $open_debit = $this->db->select_sum('debit')
            ->get_where('opening_balance', array('account' => $code, 'dist_id' => $this->dist_id))
            ->row()
            ->debit;
        $open_credit = $this->db->select_sum('credit')
            ->get_where('opening_balance', array('account' => $code, 'dist_id' => $this->dist_id))
            ->row()
            ->credit;
        // Previous Balance - This Period
        $this->db->select("sum(GR_DEBIT) as debit,sum(GR_CREDIT) as credit");
        $this->db->from("ac_tb_accounts_voucherdtl");
        //$this->db->where('dist_id', $this->dist_id);
        $this->db->where('IsActive', 1);
        $this->db->where('CHILD_ID', $code);
        if (!empty($fromDate)) {
            $this->db->where('date >=', $fromDate);
        }
        if (!empty($toDate)) {
            $this->db->where('date <=', $toDate);
        }
        $result = $this->db->get()->row();
        $debit += $result->debit;
        $credit += $result->credit;
        $debit += $open_debit;
        $credit += $open_credit;
        $result = $debit - $credit;
        return $result;
    }
    function  getTotalSales($searchDay){

        $query = "select SUM(quantity*unit_price) AS total_sales FROM sales_details WHERE is_active='Y' AND is_delete='N' AND show_in_invoice=1";//AND dist_id=".$this->dist_id;
        $query = $this->db->query($query);
        $result = $query->row();
        return $result->total_sales;
    }
    function  getinventoryAmount(){

        $query2 = "SELECT SUM(table1.Opening) AS Opening,
SUM(table1.GR_DEBIT) AS GR_DEBIT,
SUM(table1.GR_CREDIT) AS GR_CREDIT,
SUM(table1.Balance) AS Balance,
table1.PARENT_ID,
table1.PN,
table1.PN_Code,
table1.PARENT_ID1,
table1.PN1,
table1.PN1_Code,
table1.PARENT_ID2,
table1.PN2,
table1.PN2_Code,
table1.PARENT_ID3,
table1.PN3,
table1.PN3_Code,
table1.PARENT_ID4,
table1.PN4,
table1.PN4_Code

 FROM ( 
SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   
   
 
  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103) < CONVERT(DATETIME, @FDate,103) */

 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 1 AND AC_TCOA.PARENT_ID2 = '" . $this->config->item("Inventory_Stock") . "' AND AC_TAVDtl.IsActive = 1

 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC

) table1


GROUP BY table1.PARENT_ID,
table1.PN,
table1.PN_Code,
table1.PARENT_ID1,
table1.PN1,
table1.PN1_Code,
table1.PARENT_ID2,
table1.PN2,
table1.PN2_Code";
        $query = $this->db->query($query2);

        $result_assets = $query->row();
        return $result_assets->Balance;
    }

}