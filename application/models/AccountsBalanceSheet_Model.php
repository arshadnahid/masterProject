<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/19/2019
 * Time: 12:39 PM
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AccountsBalanceSheet_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }








    function balance_sheet_query_with_all_branch($end_date, $branch, $optional = "")
    {
        $query = "SELECT 

table1.BranchAutoId,
SUM(table1.GR_DEBIT) AS GR_DEBIT,
SUM(table1.GR_CREDIT) AS GR_CREDIT,
CASE
    WHEN table1.PARENT_ID = 1 THEN (SUM(table1.GR_DEBIT)-SUM(table1.GR_CREDIT))
    ELSE (SUM(table1.GR_CREDIT)-SUM(table1.GR_DEBIT))
END AS Balance,
table1.PARENT_ID,
table1.PN,
table1.PN_Code,
table1.PARENT_ID1,
table1.PN1,
table1.PN1_Code,
table1.PARENT_ID2,
CASE
    WHEN table1.PN2 !='' THEN table1.PN2
    ELSE table1.CN
END AS PN2,
CASE
    WHEN table1.PN2_Code !='' THEN table1.PN2_Code
    ELSE table1.PN2_Code
END AS PN2_Code,
table1.PARENT_ID3,
table1.PN3,
table1.PN3_Code,
table1.PARENT_ID4,
table1.PN4,
table1.PN4_Code,table1.CN   ,table1.CN_Code
 FROM ( 
SELECT    

 AC_TAVDtl.BranchAutoId,
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (  SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))-SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance
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
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID IN (1,2) AND AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' AND AC_TAVDtl.IsActive = 1  ";

        $query .= "  GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  
 ORDER BY  AC_TAVDtl.BranchAutoId ASC,IFNULL(AC_TALCOA.code,'')      ASC
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


        $query = $this->db->query($query);
        $result = $query->result();

        foreach ($result as $key => $value) {

            $array['All Branch Summary' . '~#@~' . 'a'][$value->PN . '~#@~' . $value->PARENT_ID][$value->PN1_Code . '~#@~' . $value->PN1][] = $value;


        }


        return $array;
    }
    function balance_sheet_query_with_branch($end_date, $branch, $optional = "")
    {
        $query = "SELECT 
branch.branch_name,
table1.BranchAutoId,
SUM(table1.GR_DEBIT) AS GR_DEBIT,
SUM(table1.GR_CREDIT) AS GR_CREDIT,
CASE
    WHEN table1.PARENT_ID = 1 THEN (SUM(table1.GR_DEBIT)-SUM(table1.GR_CREDIT))
    ELSE (SUM(table1.GR_CREDIT)-SUM(table1.GR_DEBIT))
END AS Balance,
table1.PARENT_ID,
table1.PN,
table1.PN_Code,
table1.PARENT_ID1,
table1.PN1,
table1.PN1_Code,
table1.PARENT_ID2,
CASE
    WHEN table1.PN2 !='' THEN table1.PN2
    ELSE table1.CN
END AS PN2,
CASE
    WHEN table1.PN2_Code !='' THEN table1.PN2_Code
    ELSE table1.PN2_Code
END AS PN2_Code,
table1.PARENT_ID3,
table1.PN3,
table1.PN3_Code,
table1.PARENT_ID4,
table1.PN4,
table1.PN4_Code,table1.CN   ,table1.CN_Code
 FROM ( 
SELECT    

 AC_TAVDtl.BranchAutoId,
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (  SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))-SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance
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
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID IN (1,2) AND AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' AND AC_TAVDtl.IsActive = 1  ";
        if ($branch != 'all') {
            $query .= " AND AC_TAVDtl.BranchAutoId = " . $branch;
        }
        $query .= "  GROUP BY  
 AC_TAVDtl.BranchAutoId,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  
 ORDER BY  AC_TAVDtl.BranchAutoId ASC,IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC

) table1
LEFT JOIN branch on branch.branch_id=table1.BranchAutoId

GROUP BY table1.BranchAutoId,table1.PARENT_ID,
table1.PN,
table1.PN_Code,
table1.PARENT_ID1,
table1.PN1,
table1.PN1_Code,
table1.PARENT_ID2,
table1.PN2,
table1.PN2_Code";


        $query = $this->db->query($query);
        $result = $query->result();

        foreach ($result as $key => $value) {

            $array[$value->branch_name . '~#@~' . $value->BranchAutoId][$value->PN . '~#@~' . $value->PARENT_ID][$value->PN1_Code . '~#@~' . $value->PN1][] = $value;


        }


        return $array;
    }


    /*$this->db->select('product.product_id,productcategory.title as productCat,product.brand_id,product.category_id,product.productName,product.dist_id,product.status,brand.brandName,unit.unitTtile');
    $this->db->from('product');
    $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
    $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
    $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
    $this->db->group_start();
    $this->db->where('product.dist_id', $distId);
    $this->db->or_where('product.dist_id', 1);
    $this->db->group_end();
    $this->db->where('product.status', 1);
    if ($catid == 'all') {
    } else {
        $this->db->where('product.category_id', $catid);
    }
    $this->db->order_by('product.productName', 'ASE');
    $getProductList = $this->db->get()->result();
    return $getProductList;*/



}
