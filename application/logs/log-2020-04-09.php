INFO - 2020-04-09 12:08:48 --> Config Class Initialized
INFO - 2020-04-09 12:08:48 --> Hooks Class Initialized
DEBUG - 2020-04-09 12:08:48 --> UTF-8 Support Enabled
INFO - 2020-04-09 12:08:48 --> Utf8 Class Initialized
INFO - 2020-04-09 12:08:48 --> URI Class Initialized
INFO - 2020-04-09 12:08:48 --> Router Class Initialized
INFO - 2020-04-09 12:08:48 --> Output Class Initialized
INFO - 2020-04-09 12:08:48 --> Security Class Initialized
DEBUG - 2020-04-09 12:08:48 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 12:08:48 --> Input Class Initialized
INFO - 2020-04-09 12:08:48 --> Language Class Initialized
INFO - 2020-04-09 12:08:49 --> Loader Class Initialized
INFO - 2020-04-09 12:08:49 --> Helper loaded: url_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: file_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: utility_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: unit_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: site_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 12:08:49 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 12:08:49 --> Database Driver Class Initialized
INFO - 2020-04-09 12:08:49 --> Email Class Initialized
DEBUG - 2020-04-09 12:08:49 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 12:08:49 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 12:08:49 --> Helper loaded: form_helper
INFO - 2020-04-09 12:08:49 --> Form Validation Class Initialized
INFO - 2020-04-09 12:08:49 --> Controller Class Initialized
INFO - 2020-04-09 12:08:49 --> Model "Common_model" initialized
INFO - 2020-04-09 12:08:49 --> Model "Finane_Model" initialized
INFO - 2020-04-09 12:08:49 --> Model "Accounts_model" initialized
INFO - 2020-04-09 12:08:49 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 12:08:49 --> Model "Sales_Model" initialized
INFO - 2020-04-09 12:08:49 --> Database Driver Class Initialized
INFO - 2020-04-09 12:08:49 --> Helper loaded: language_helper
INFO - 2020-04-09 12:08:49 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 12:08:49 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 12:08:49 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 12:08:49 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Employee"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "2019"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:08:49 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 12:08:49 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 12:08:49 --> Final output sent to browser
DEBUG - 2020-04-09 12:08:49 --> Total execution time: 0.6669
INFO - 2020-04-09 12:57:19 --> Config Class Initialized
INFO - 2020-04-09 12:57:19 --> Hooks Class Initialized
DEBUG - 2020-04-09 12:57:19 --> UTF-8 Support Enabled
INFO - 2020-04-09 12:57:19 --> Utf8 Class Initialized
INFO - 2020-04-09 12:57:19 --> URI Class Initialized
INFO - 2020-04-09 12:57:20 --> Router Class Initialized
INFO - 2020-04-09 12:57:20 --> Output Class Initialized
INFO - 2020-04-09 12:57:20 --> Security Class Initialized
DEBUG - 2020-04-09 12:57:20 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 12:57:20 --> Input Class Initialized
INFO - 2020-04-09 12:57:20 --> Language Class Initialized
INFO - 2020-04-09 12:57:20 --> Loader Class Initialized
INFO - 2020-04-09 12:57:20 --> Helper loaded: url_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: file_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: utility_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: unit_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: site_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 12:57:20 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 12:57:20 --> Database Driver Class Initialized
INFO - 2020-04-09 12:57:20 --> Email Class Initialized
DEBUG - 2020-04-09 12:57:20 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 12:57:20 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 12:57:20 --> Helper loaded: form_helper
INFO - 2020-04-09 12:57:20 --> Form Validation Class Initialized
INFO - 2020-04-09 12:57:20 --> Controller Class Initialized
INFO - 2020-04-09 12:57:20 --> Model "Common_model" initialized
INFO - 2020-04-09 12:57:20 --> Model "Finane_Model" initialized
INFO - 2020-04-09 12:57:20 --> Model "Accounts_model" initialized
INFO - 2020-04-09 12:57:20 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 12:57:20 --> Model "Sales_Model" initialized
INFO - 2020-04-09 12:57:20 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 12:57:20 --> Database Driver Class Initialized
INFO - 2020-04-09 12:57:20 --> Helper loaded: language_helper
INFO - 2020-04-09 12:57:20 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 12:58:05 --> Config Class Initialized
INFO - 2020-04-09 12:58:05 --> Hooks Class Initialized
DEBUG - 2020-04-09 12:58:05 --> UTF-8 Support Enabled
INFO - 2020-04-09 12:58:05 --> Utf8 Class Initialized
INFO - 2020-04-09 12:58:05 --> URI Class Initialized
INFO - 2020-04-09 12:58:05 --> Router Class Initialized
INFO - 2020-04-09 12:58:05 --> Output Class Initialized
INFO - 2020-04-09 12:58:05 --> Security Class Initialized
DEBUG - 2020-04-09 12:58:05 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 12:58:05 --> Input Class Initialized
INFO - 2020-04-09 12:58:05 --> Language Class Initialized
INFO - 2020-04-09 12:58:05 --> Loader Class Initialized
INFO - 2020-04-09 12:58:05 --> Helper loaded: url_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: file_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: utility_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: unit_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: site_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 12:58:05 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 12:58:05 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:05 --> Email Class Initialized
DEBUG - 2020-04-09 12:58:06 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 12:58:06 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 12:58:06 --> Helper loaded: form_helper
INFO - 2020-04-09 12:58:06 --> Form Validation Class Initialized
INFO - 2020-04-09 12:58:06 --> Controller Class Initialized
INFO - 2020-04-09 12:58:06 --> Model "Common_model" initialized
INFO - 2020-04-09 12:58:06 --> Model "Finane_Model" initialized
INFO - 2020-04-09 12:58:06 --> Model "Accounts_model" initialized
INFO - 2020-04-09 12:58:06 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 12:58:06 --> Model "Sales_Model" initialized
INFO - 2020-04-09 12:58:06 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 12:58:06 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:06 --> Helper loaded: language_helper
INFO - 2020-04-09 12:58:06 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Commission"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 12:58:06 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Employee"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "2019"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:06 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 12:58:06 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 12:58:06 --> Final output sent to browser
DEBUG - 2020-04-09 12:58:06 --> Total execution time: 0.9956
INFO - 2020-04-09 12:58:27 --> Config Class Initialized
INFO - 2020-04-09 12:58:27 --> Hooks Class Initialized
DEBUG - 2020-04-09 12:58:27 --> UTF-8 Support Enabled
INFO - 2020-04-09 12:58:27 --> Utf8 Class Initialized
INFO - 2020-04-09 12:58:27 --> URI Class Initialized
INFO - 2020-04-09 12:58:27 --> Router Class Initialized
INFO - 2020-04-09 12:58:27 --> Output Class Initialized
INFO - 2020-04-09 12:58:27 --> Security Class Initialized
DEBUG - 2020-04-09 12:58:27 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 12:58:27 --> Input Class Initialized
INFO - 2020-04-09 12:58:27 --> Language Class Initialized
INFO - 2020-04-09 12:58:27 --> Loader Class Initialized
INFO - 2020-04-09 12:58:27 --> Helper loaded: url_helper
INFO - 2020-04-09 12:58:27 --> Helper loaded: file_helper
INFO - 2020-04-09 12:58:27 --> Helper loaded: utility_helper
INFO - 2020-04-09 12:58:27 --> Helper loaded: unit_helper
INFO - 2020-04-09 12:58:27 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 12:58:27 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 12:58:27 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 12:58:27 --> Helper loaded: site_helper
INFO - 2020-04-09 12:58:28 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 12:58:28 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 12:58:28 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:28 --> Email Class Initialized
DEBUG - 2020-04-09 12:58:28 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 12:58:28 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 12:58:28 --> Helper loaded: form_helper
INFO - 2020-04-09 12:58:28 --> Form Validation Class Initialized
INFO - 2020-04-09 12:58:28 --> Controller Class Initialized
INFO - 2020-04-09 12:58:28 --> Model "Common_model" initialized
INFO - 2020-04-09 12:58:28 --> Model "Finane_Model" initialized
INFO - 2020-04-09 12:58:28 --> Model "Accounts_model" initialized
INFO - 2020-04-09 12:58:28 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 12:58:28 --> Model "Sales_Model" initialized
INFO - 2020-04-09 12:58:28 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 12:58:28 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:28 --> Helper loaded: language_helper
INFO - 2020-04-09 12:58:28 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Commission"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 12:58:28 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Employee"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "2019"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:28 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 12:58:28 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 12:58:28 --> Final output sent to browser
DEBUG - 2020-04-09 12:58:28 --> Total execution time: 0.9708
INFO - 2020-04-09 12:58:32 --> Config Class Initialized
INFO - 2020-04-09 12:58:32 --> Hooks Class Initialized
DEBUG - 2020-04-09 12:58:32 --> UTF-8 Support Enabled
INFO - 2020-04-09 12:58:32 --> Utf8 Class Initialized
INFO - 2020-04-09 12:58:32 --> URI Class Initialized
INFO - 2020-04-09 12:58:32 --> Router Class Initialized
INFO - 2020-04-09 12:58:32 --> Output Class Initialized
INFO - 2020-04-09 12:58:32 --> Security Class Initialized
DEBUG - 2020-04-09 12:58:32 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 12:58:32 --> Input Class Initialized
INFO - 2020-04-09 12:58:32 --> Language Class Initialized
INFO - 2020-04-09 12:58:32 --> Loader Class Initialized
INFO - 2020-04-09 12:58:32 --> Helper loaded: url_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: file_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: utility_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: unit_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: site_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 12:58:32 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 12:58:32 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:32 --> Email Class Initialized
DEBUG - 2020-04-09 12:58:32 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 12:58:32 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 12:58:32 --> Helper loaded: form_helper
INFO - 2020-04-09 12:58:32 --> Form Validation Class Initialized
INFO - 2020-04-09 12:58:32 --> Controller Class Initialized
INFO - 2020-04-09 12:58:32 --> Model "Common_model" initialized
INFO - 2020-04-09 12:58:32 --> Model "Finane_Model" initialized
INFO - 2020-04-09 12:58:32 --> Model "Accounts_model" initialized
INFO - 2020-04-09 12:58:32 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 12:58:32 --> Model "Sales_Model" initialized
INFO - 2020-04-09 12:58:32 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 12:58:32 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:32 --> Helper loaded: language_helper
INFO - 2020-04-09 12:58:32 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 12:58:32 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Commission"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 12:58:33 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Employee"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "2019"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:33 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 12:58:33 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 12:58:33 --> Final output sent to browser
DEBUG - 2020-04-09 12:58:33 --> Total execution time: 1.1097
INFO - 2020-04-09 12:58:52 --> Config Class Initialized
INFO - 2020-04-09 12:58:52 --> Hooks Class Initialized
DEBUG - 2020-04-09 12:58:52 --> UTF-8 Support Enabled
INFO - 2020-04-09 12:58:52 --> Utf8 Class Initialized
INFO - 2020-04-09 12:58:52 --> URI Class Initialized
INFO - 2020-04-09 12:58:52 --> Router Class Initialized
INFO - 2020-04-09 12:58:52 --> Output Class Initialized
INFO - 2020-04-09 12:58:52 --> Security Class Initialized
DEBUG - 2020-04-09 12:58:52 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 12:58:52 --> Input Class Initialized
INFO - 2020-04-09 12:58:52 --> Language Class Initialized
INFO - 2020-04-09 12:58:52 --> Loader Class Initialized
INFO - 2020-04-09 12:58:52 --> Helper loaded: url_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: file_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: utility_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: unit_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: site_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 12:58:52 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 12:58:52 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:52 --> Email Class Initialized
DEBUG - 2020-04-09 12:58:52 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 12:58:52 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 12:58:52 --> Helper loaded: form_helper
INFO - 2020-04-09 12:58:52 --> Form Validation Class Initialized
INFO - 2020-04-09 12:58:52 --> Controller Class Initialized
INFO - 2020-04-09 12:58:52 --> Model "Common_model" initialized
INFO - 2020-04-09 12:58:52 --> Model "Finane_Model" initialized
INFO - 2020-04-09 12:58:52 --> Model "Accounts_model" initialized
INFO - 2020-04-09 12:58:52 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 12:58:52 --> Model "Sales_Model" initialized
INFO - 2020-04-09 12:58:52 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 12:58:52 --> Database Driver Class Initialized
INFO - 2020-04-09 12:58:52 --> Helper loaded: language_helper
INFO - 2020-04-09 12:58:52 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 12:58:52 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Commission"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 12:58:53 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Employee"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "2019"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Report"
ERROR - 2020-04-09 12:58:53 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 12:58:53 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 12:58:53 --> Final output sent to browser
DEBUG - 2020-04-09 12:58:53 --> Total execution time: 0.9214
INFO - 2020-04-09 15:55:53 --> Config Class Initialized
INFO - 2020-04-09 15:55:53 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:55:53 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:55:53 --> Utf8 Class Initialized
INFO - 2020-04-09 15:55:53 --> URI Class Initialized
INFO - 2020-04-09 15:55:53 --> Router Class Initialized
INFO - 2020-04-09 15:55:53 --> Output Class Initialized
INFO - 2020-04-09 15:55:53 --> Security Class Initialized
DEBUG - 2020-04-09 15:55:53 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:55:53 --> Input Class Initialized
INFO - 2020-04-09 15:55:53 --> Language Class Initialized
INFO - 2020-04-09 15:55:53 --> Loader Class Initialized
INFO - 2020-04-09 15:55:53 --> Helper loaded: url_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: file_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: site_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:55:53 --> Database Driver Class Initialized
INFO - 2020-04-09 15:55:53 --> Email Class Initialized
DEBUG - 2020-04-09 15:55:53 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:55:53 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:55:53 --> Helper loaded: form_helper
INFO - 2020-04-09 15:55:53 --> Form Validation Class Initialized
INFO - 2020-04-09 15:55:53 --> Controller Class Initialized
INFO - 2020-04-09 15:55:53 --> Model "Common_model" initialized
INFO - 2020-04-09 15:55:53 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:55:53 --> Model "Accounts_model" initialized
INFO - 2020-04-09 15:55:53 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:55:53 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:55:53 --> Config Class Initialized
INFO - 2020-04-09 15:55:53 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:55:53 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:55:53 --> Utf8 Class Initialized
INFO - 2020-04-09 15:55:53 --> URI Class Initialized
DEBUG - 2020-04-09 15:55:53 --> No URI present. Default controller set.
INFO - 2020-04-09 15:55:53 --> Router Class Initialized
INFO - 2020-04-09 15:55:53 --> Output Class Initialized
INFO - 2020-04-09 15:55:53 --> Security Class Initialized
DEBUG - 2020-04-09 15:55:53 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:55:53 --> Input Class Initialized
INFO - 2020-04-09 15:55:53 --> Language Class Initialized
INFO - 2020-04-09 15:55:53 --> Loader Class Initialized
INFO - 2020-04-09 15:55:53 --> Helper loaded: url_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: file_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:55:53 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:55:54 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:55:54 --> Helper loaded: site_helper
INFO - 2020-04-09 15:55:54 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:55:54 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:55:54 --> Database Driver Class Initialized
INFO - 2020-04-09 15:55:54 --> Email Class Initialized
DEBUG - 2020-04-09 15:55:54 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:55:54 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:55:54 --> Helper loaded: form_helper
INFO - 2020-04-09 15:55:54 --> Form Validation Class Initialized
INFO - 2020-04-09 15:55:54 --> Controller Class Initialized
INFO - 2020-04-09 15:55:54 --> Model "Common_model" initialized
INFO - 2020-04-09 15:55:54 --> Helper loaded: language_helper
INFO - 2020-04-09 15:55:54 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:55:54 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 15:55:54 --> Final output sent to browser
DEBUG - 2020-04-09 15:55:54 --> Total execution time: 0.4636
INFO - 2020-04-09 15:55:54 --> Config Class Initialized
INFO - 2020-04-09 15:55:54 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:55:54 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:55:54 --> Utf8 Class Initialized
INFO - 2020-04-09 15:55:54 --> URI Class Initialized
INFO - 2020-04-09 15:55:54 --> Router Class Initialized
INFO - 2020-04-09 15:55:54 --> Output Class Initialized
INFO - 2020-04-09 15:55:54 --> Security Class Initialized
DEBUG - 2020-04-09 15:55:54 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:55:54 --> Input Class Initialized
INFO - 2020-04-09 15:55:54 --> Language Class Initialized
ERROR - 2020-04-09 15:55:54 --> 404 Page Not Found: Assets/global
INFO - 2020-04-09 15:55:54 --> Config Class Initialized
INFO - 2020-04-09 15:55:54 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:55:54 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:55:54 --> Utf8 Class Initialized
INFO - 2020-04-09 15:55:54 --> URI Class Initialized
INFO - 2020-04-09 15:55:54 --> Router Class Initialized
INFO - 2020-04-09 15:55:55 --> Output Class Initialized
INFO - 2020-04-09 15:55:55 --> Security Class Initialized
DEBUG - 2020-04-09 15:55:55 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:55:55 --> Input Class Initialized
INFO - 2020-04-09 15:55:55 --> Language Class Initialized
INFO - 2020-04-09 15:55:55 --> Loader Class Initialized
INFO - 2020-04-09 15:55:55 --> Helper loaded: url_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: file_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: site_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:55:55 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:55:55 --> Database Driver Class Initialized
INFO - 2020-04-09 15:55:55 --> Email Class Initialized
DEBUG - 2020-04-09 15:55:55 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:55:55 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:55:55 --> Helper loaded: form_helper
INFO - 2020-04-09 15:55:55 --> Form Validation Class Initialized
INFO - 2020-04-09 15:55:55 --> Controller Class Initialized
INFO - 2020-04-09 15:55:55 --> Model "Common_model" initialized
INFO - 2020-04-09 15:55:55 --> Helper loaded: language_helper
INFO - 2020-04-09 15:55:55 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:55:55 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 15:55:55 --> Final output sent to browser
DEBUG - 2020-04-09 15:55:55 --> Total execution time: 0.3176
INFO - 2020-04-09 15:55:59 --> Config Class Initialized
INFO - 2020-04-09 15:55:59 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:55:59 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:55:59 --> Utf8 Class Initialized
INFO - 2020-04-09 15:55:59 --> URI Class Initialized
INFO - 2020-04-09 15:55:59 --> Router Class Initialized
INFO - 2020-04-09 15:55:59 --> Output Class Initialized
INFO - 2020-04-09 15:55:59 --> Security Class Initialized
DEBUG - 2020-04-09 15:55:59 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:55:59 --> Input Class Initialized
INFO - 2020-04-09 15:55:59 --> Language Class Initialized
INFO - 2020-04-09 15:55:59 --> Loader Class Initialized
INFO - 2020-04-09 15:55:59 --> Helper loaded: url_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: file_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: site_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:55:59 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:55:59 --> Database Driver Class Initialized
INFO - 2020-04-09 15:55:59 --> Email Class Initialized
DEBUG - 2020-04-09 15:55:59 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:55:59 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:55:59 --> Helper loaded: form_helper
INFO - 2020-04-09 15:55:59 --> Form Validation Class Initialized
INFO - 2020-04-09 15:55:59 --> Controller Class Initialized
INFO - 2020-04-09 15:55:59 --> Model "Common_model" initialized
INFO - 2020-04-09 15:55:59 --> Helper loaded: language_helper
INFO - 2020-04-09 15:55:59 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:55:59 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 15:55:59 --> Final output sent to browser
DEBUG - 2020-04-09 15:55:59 --> Total execution time: 0.3413
INFO - 2020-04-09 15:55:59 --> Config Class Initialized
INFO - 2020-04-09 15:55:59 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:55:59 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:55:59 --> Utf8 Class Initialized
INFO - 2020-04-09 15:55:59 --> URI Class Initialized
INFO - 2020-04-09 15:55:59 --> Router Class Initialized
INFO - 2020-04-09 15:55:59 --> Output Class Initialized
INFO - 2020-04-09 15:55:59 --> Security Class Initialized
DEBUG - 2020-04-09 15:55:59 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:00 --> Input Class Initialized
INFO - 2020-04-09 15:56:00 --> Language Class Initialized
ERROR - 2020-04-09 15:56:00 --> 404 Page Not Found: Assets/global
INFO - 2020-04-09 15:56:00 --> Config Class Initialized
INFO - 2020-04-09 15:56:00 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:00 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:00 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:00 --> URI Class Initialized
INFO - 2020-04-09 15:56:00 --> Router Class Initialized
INFO - 2020-04-09 15:56:00 --> Output Class Initialized
INFO - 2020-04-09 15:56:00 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:00 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:00 --> Input Class Initialized
INFO - 2020-04-09 15:56:00 --> Language Class Initialized
INFO - 2020-04-09 15:56:00 --> Loader Class Initialized
INFO - 2020-04-09 15:56:00 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:00 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:00 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:00 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:00 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:00 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:00 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:00 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:00 --> Controller Class Initialized
INFO - 2020-04-09 15:56:00 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:00 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:00 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:56:00 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 15:56:00 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:00 --> Total execution time: 0.3432
INFO - 2020-04-09 15:56:04 --> Config Class Initialized
INFO - 2020-04-09 15:56:04 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:04 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:04 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:04 --> URI Class Initialized
INFO - 2020-04-09 15:56:04 --> Router Class Initialized
INFO - 2020-04-09 15:56:04 --> Output Class Initialized
INFO - 2020-04-09 15:56:04 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:04 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:04 --> Input Class Initialized
INFO - 2020-04-09 15:56:04 --> Language Class Initialized
INFO - 2020-04-09 15:56:04 --> Loader Class Initialized
INFO - 2020-04-09 15:56:04 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:04 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:04 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:05 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:05 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:05 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:05 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:05 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:05 --> Controller Class Initialized
INFO - 2020-04-09 15:56:05 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:05 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:05 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:56:05 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 15:56:05 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:05 --> Total execution time: 0.4102
INFO - 2020-04-09 15:56:05 --> Config Class Initialized
INFO - 2020-04-09 15:56:05 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:05 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:05 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:05 --> URI Class Initialized
INFO - 2020-04-09 15:56:05 --> Router Class Initialized
INFO - 2020-04-09 15:56:05 --> Output Class Initialized
INFO - 2020-04-09 15:56:05 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:05 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:05 --> Input Class Initialized
INFO - 2020-04-09 15:56:05 --> Language Class Initialized
ERROR - 2020-04-09 15:56:05 --> 404 Page Not Found: Assets/global
INFO - 2020-04-09 15:56:05 --> Config Class Initialized
INFO - 2020-04-09 15:56:05 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:05 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:05 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:05 --> URI Class Initialized
INFO - 2020-04-09 15:56:05 --> Router Class Initialized
INFO - 2020-04-09 15:56:05 --> Output Class Initialized
INFO - 2020-04-09 15:56:05 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:05 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:05 --> Input Class Initialized
INFO - 2020-04-09 15:56:05 --> Language Class Initialized
INFO - 2020-04-09 15:56:05 --> Loader Class Initialized
INFO - 2020-04-09 15:56:05 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:05 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:05 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:05 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:05 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:05 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:05 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:05 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:05 --> Controller Class Initialized
INFO - 2020-04-09 15:56:05 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:05 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:05 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:56:05 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 15:56:05 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:05 --> Total execution time: 0.3653
INFO - 2020-04-09 15:56:10 --> Config Class Initialized
INFO - 2020-04-09 15:56:10 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:10 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:10 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:10 --> URI Class Initialized
INFO - 2020-04-09 15:56:10 --> Router Class Initialized
INFO - 2020-04-09 15:56:10 --> Output Class Initialized
INFO - 2020-04-09 15:56:10 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:10 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:10 --> Input Class Initialized
INFO - 2020-04-09 15:56:10 --> Language Class Initialized
INFO - 2020-04-09 15:56:10 --> Loader Class Initialized
INFO - 2020-04-09 15:56:10 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:10 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:10 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:10 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:10 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:10 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:10 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:10 --> Controller Class Initialized
INFO - 2020-04-09 15:56:10 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:10 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:10 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:56:10 --> Config Class Initialized
INFO - 2020-04-09 15:56:10 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:10 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:10 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:10 --> URI Class Initialized
INFO - 2020-04-09 15:56:10 --> Router Class Initialized
INFO - 2020-04-09 15:56:10 --> Output Class Initialized
INFO - 2020-04-09 15:56:10 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:10 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:10 --> Input Class Initialized
INFO - 2020-04-09 15:56:10 --> Language Class Initialized
INFO - 2020-04-09 15:56:10 --> Loader Class Initialized
INFO - 2020-04-09 15:56:10 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:10 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:10 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:10 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:10 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:10 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:10 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:10 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:10 --> Controller Class Initialized
INFO - 2020-04-09 15:56:10 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:10 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:56:10 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:56:10 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:56:10 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 15:56:10 --> Model "Accounts_model" initialized
INFO - 2020-04-09 15:56:10 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:10 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:10 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Month_Wise_Sales"
INFO - 2020-04-09 15:56:12 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/dashboard.php
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:12 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:56:12 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplateSmeMobile.php
INFO - 2020-04-09 15:56:12 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:12 --> Total execution time: 2.4631
INFO - 2020-04-09 15:56:33 --> Config Class Initialized
INFO - 2020-04-09 15:56:33 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:34 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:34 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:34 --> URI Class Initialized
INFO - 2020-04-09 15:56:34 --> Router Class Initialized
INFO - 2020-04-09 15:56:34 --> Output Class Initialized
INFO - 2020-04-09 15:56:34 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:34 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:34 --> Input Class Initialized
INFO - 2020-04-09 15:56:34 --> Language Class Initialized
INFO - 2020-04-09 15:56:34 --> Loader Class Initialized
INFO - 2020-04-09 15:56:34 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:34 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:34 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:34 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:34 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:34 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:34 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:34 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:34 --> Controller Class Initialized
INFO - 2020-04-09 15:56:34 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:34 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:56:34 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:56:34 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:56:34 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:34 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:34 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:56:34 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/product/addProductType.php
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:34 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:56:34 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplateSmeMobile.php
INFO - 2020-04-09 15:56:34 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:34 --> Total execution time: 0.7389
INFO - 2020-04-09 15:56:42 --> Config Class Initialized
INFO - 2020-04-09 15:56:42 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:42 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:42 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:42 --> URI Class Initialized
INFO - 2020-04-09 15:56:42 --> Router Class Initialized
INFO - 2020-04-09 15:56:42 --> Output Class Initialized
INFO - 2020-04-09 15:56:42 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:42 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:42 --> Input Class Initialized
INFO - 2020-04-09 15:56:42 --> Language Class Initialized
INFO - 2020-04-09 15:56:42 --> Loader Class Initialized
INFO - 2020-04-09 15:56:42 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:42 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:42 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:42 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:42 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:42 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:42 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:42 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:42 --> Controller Class Initialized
INFO - 2020-04-09 15:56:42 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:42 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:56:42 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:56:42 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:56:42 --> Model "Purchases_Model" initialized
INFO - 2020-04-09 15:56:42 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:42 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:42 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Product_Category_List"
INFO - 2020-04-09 15:56:42 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/productCat/productCatAdd.php
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:42 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:56:42 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 15:56:42 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:42 --> Total execution time: 0.7398
INFO - 2020-04-09 15:56:45 --> Config Class Initialized
INFO - 2020-04-09 15:56:45 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:45 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:45 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:45 --> URI Class Initialized
INFO - 2020-04-09 15:56:45 --> Router Class Initialized
INFO - 2020-04-09 15:56:45 --> Output Class Initialized
INFO - 2020-04-09 15:56:45 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:45 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:45 --> Input Class Initialized
INFO - 2020-04-09 15:56:45 --> Language Class Initialized
INFO - 2020-04-09 15:56:45 --> Loader Class Initialized
INFO - 2020-04-09 15:56:45 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:45 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:45 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:45 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:45 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:45 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:45 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:45 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:45 --> Controller Class Initialized
INFO - 2020-04-09 15:56:45 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:45 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:56:45 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:56:45 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:56:45 --> Model "Purchases_Model" initialized
INFO - 2020-04-09 15:56:45 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:45 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:45 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Si"
INFO - 2020-04-09 15:56:45 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/productCat/productCatList.php
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:45 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:56:45 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 15:56:45 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:45 --> Total execution time: 0.6662
INFO - 2020-04-09 15:56:46 --> Config Class Initialized
INFO - 2020-04-09 15:56:46 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:46 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:46 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:46 --> URI Class Initialized
INFO - 2020-04-09 15:56:46 --> Router Class Initialized
INFO - 2020-04-09 15:56:46 --> Output Class Initialized
INFO - 2020-04-09 15:56:46 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:46 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:46 --> Input Class Initialized
INFO - 2020-04-09 15:56:46 --> Language Class Initialized
INFO - 2020-04-09 15:56:46 --> Loader Class Initialized
INFO - 2020-04-09 15:56:46 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:46 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:46 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:46 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:46 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:46 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:46 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:46 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:46 --> Controller Class Initialized
INFO - 2020-04-09 15:56:46 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:46 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:56:46 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:56:46 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:56:46 --> Model "ServerFilterModel" initialized
INFO - 2020-04-09 15:56:46 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:46 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:46 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:56:46 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:46 --> Total execution time: 0.5411
INFO - 2020-04-09 15:56:53 --> Config Class Initialized
INFO - 2020-04-09 15:56:53 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:53 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:53 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:53 --> URI Class Initialized
INFO - 2020-04-09 15:56:53 --> Router Class Initialized
INFO - 2020-04-09 15:56:53 --> Output Class Initialized
INFO - 2020-04-09 15:56:53 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:53 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:53 --> Input Class Initialized
INFO - 2020-04-09 15:56:53 --> Language Class Initialized
ERROR - 2020-04-09 15:56:53 --> 404 Page Not Found: KustiaGeneralErp/subCategory
INFO - 2020-04-09 15:56:55 --> Config Class Initialized
INFO - 2020-04-09 15:56:55 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:55 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:55 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:55 --> URI Class Initialized
INFO - 2020-04-09 15:56:55 --> Router Class Initialized
INFO - 2020-04-09 15:56:55 --> Output Class Initialized
INFO - 2020-04-09 15:56:55 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:55 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:55 --> Input Class Initialized
INFO - 2020-04-09 15:56:55 --> Language Class Initialized
INFO - 2020-04-09 15:56:55 --> Loader Class Initialized
INFO - 2020-04-09 15:56:55 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:55 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:55 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:55 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:55 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:55 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:55 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:55 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:55 --> Controller Class Initialized
INFO - 2020-04-09 15:56:55 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:55 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:56:55 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:56:55 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:56:56 --> Model "Purchases_Model" initialized
INFO - 2020-04-09 15:56:56 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:56 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:56 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Si"
INFO - 2020-04-09 15:56:56 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/productCat/productCatList.php
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:56:56 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:56:56 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 15:56:56 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:56 --> Total execution time: 0.6150
INFO - 2020-04-09 15:56:57 --> Config Class Initialized
INFO - 2020-04-09 15:56:57 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:56:57 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:56:57 --> Utf8 Class Initialized
INFO - 2020-04-09 15:56:57 --> URI Class Initialized
INFO - 2020-04-09 15:56:57 --> Router Class Initialized
INFO - 2020-04-09 15:56:57 --> Output Class Initialized
INFO - 2020-04-09 15:56:57 --> Security Class Initialized
DEBUG - 2020-04-09 15:56:57 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:56:57 --> Input Class Initialized
INFO - 2020-04-09 15:56:57 --> Language Class Initialized
INFO - 2020-04-09 15:56:57 --> Loader Class Initialized
INFO - 2020-04-09 15:56:57 --> Helper loaded: url_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: file_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: site_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:56:57 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:56:57 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:57 --> Email Class Initialized
DEBUG - 2020-04-09 15:56:57 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:56:57 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:56:57 --> Helper loaded: form_helper
INFO - 2020-04-09 15:56:57 --> Form Validation Class Initialized
INFO - 2020-04-09 15:56:57 --> Controller Class Initialized
INFO - 2020-04-09 15:56:57 --> Model "Common_model" initialized
INFO - 2020-04-09 15:56:57 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:56:57 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:56:57 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:56:57 --> Model "ServerFilterModel" initialized
INFO - 2020-04-09 15:56:57 --> Database Driver Class Initialized
INFO - 2020-04-09 15:56:57 --> Helper loaded: language_helper
INFO - 2020-04-09 15:56:57 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:56:57 --> Final output sent to browser
DEBUG - 2020-04-09 15:56:57 --> Total execution time: 0.4501
INFO - 2020-04-09 15:57:00 --> Config Class Initialized
INFO - 2020-04-09 15:57:00 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:57:00 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:57:00 --> Utf8 Class Initialized
INFO - 2020-04-09 15:57:00 --> URI Class Initialized
INFO - 2020-04-09 15:57:00 --> Router Class Initialized
INFO - 2020-04-09 15:57:00 --> Output Class Initialized
INFO - 2020-04-09 15:57:00 --> Security Class Initialized
DEBUG - 2020-04-09 15:57:00 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:57:00 --> Input Class Initialized
INFO - 2020-04-09 15:57:00 --> Language Class Initialized
INFO - 2020-04-09 15:57:01 --> Loader Class Initialized
INFO - 2020-04-09 15:57:01 --> Helper loaded: url_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: file_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: site_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:57:01 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:57:01 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:01 --> Email Class Initialized
DEBUG - 2020-04-09 15:57:01 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:57:01 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:57:01 --> Helper loaded: form_helper
INFO - 2020-04-09 15:57:01 --> Form Validation Class Initialized
INFO - 2020-04-09 15:57:01 --> Controller Class Initialized
INFO - 2020-04-09 15:57:01 --> Model "Common_model" initialized
INFO - 2020-04-09 15:57:01 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:57:01 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:57:01 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:57:01 --> Model "Purchases_Model" initialized
INFO - 2020-04-09 15:57:01 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:01 --> Helper loaded: language_helper
INFO - 2020-04-09 15:57:01 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Brand_Name"
INFO - 2020-04-09 15:57:01 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/brand/brandAdd.php
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:01 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:57:01 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 15:57:01 --> Final output sent to browser
DEBUG - 2020-04-09 15:57:01 --> Total execution time: 0.8612
INFO - 2020-04-09 15:57:04 --> Config Class Initialized
INFO - 2020-04-09 15:57:04 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:57:04 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:57:04 --> Utf8 Class Initialized
INFO - 2020-04-09 15:57:04 --> URI Class Initialized
INFO - 2020-04-09 15:57:04 --> Router Class Initialized
INFO - 2020-04-09 15:57:04 --> Output Class Initialized
INFO - 2020-04-09 15:57:04 --> Security Class Initialized
DEBUG - 2020-04-09 15:57:04 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:57:04 --> Input Class Initialized
INFO - 2020-04-09 15:57:04 --> Language Class Initialized
INFO - 2020-04-09 15:57:04 --> Loader Class Initialized
INFO - 2020-04-09 15:57:04 --> Helper loaded: url_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: file_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: site_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:57:04 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:57:04 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:04 --> Email Class Initialized
DEBUG - 2020-04-09 15:57:04 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:57:04 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:57:04 --> Helper loaded: form_helper
INFO - 2020-04-09 15:57:04 --> Form Validation Class Initialized
INFO - 2020-04-09 15:57:04 --> Controller Class Initialized
INFO - 2020-04-09 15:57:04 --> Model "Common_model" initialized
INFO - 2020-04-09 15:57:04 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:57:04 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:57:04 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:57:04 --> Model "Purchases_Model" initialized
INFO - 2020-04-09 15:57:04 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:04 --> Helper loaded: language_helper
INFO - 2020-04-09 15:57:04 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Product_Unit"
INFO - 2020-04-09 15:57:04 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/unit/unitAdd.php
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:04 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:57:04 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 15:57:05 --> Final output sent to browser
DEBUG - 2020-04-09 15:57:05 --> Total execution time: 0.7471
INFO - 2020-04-09 15:57:07 --> Config Class Initialized
INFO - 2020-04-09 15:57:07 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:57:07 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:57:07 --> Utf8 Class Initialized
INFO - 2020-04-09 15:57:07 --> URI Class Initialized
INFO - 2020-04-09 15:57:07 --> Router Class Initialized
INFO - 2020-04-09 15:57:07 --> Output Class Initialized
INFO - 2020-04-09 15:57:07 --> Security Class Initialized
DEBUG - 2020-04-09 15:57:07 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:57:07 --> Input Class Initialized
INFO - 2020-04-09 15:57:07 --> Language Class Initialized
INFO - 2020-04-09 15:57:07 --> Loader Class Initialized
INFO - 2020-04-09 15:57:07 --> Helper loaded: url_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: file_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: site_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:57:07 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:57:07 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:07 --> Email Class Initialized
DEBUG - 2020-04-09 15:57:07 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:57:07 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:57:07 --> Helper loaded: form_helper
INFO - 2020-04-09 15:57:07 --> Form Validation Class Initialized
INFO - 2020-04-09 15:57:07 --> Controller Class Initialized
INFO - 2020-04-09 15:57:07 --> Model "Common_model" initialized
INFO - 2020-04-09 15:57:07 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:57:07 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:57:07 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:57:07 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:07 --> Helper loaded: language_helper
INFO - 2020-04-09 15:57:07 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:57:07 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/product/barcode.php
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:07 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:57:07 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplateSmeMobile.php
INFO - 2020-04-09 15:57:07 --> Final output sent to browser
DEBUG - 2020-04-09 15:57:07 --> Total execution time: 0.7702
INFO - 2020-04-09 15:57:08 --> Config Class Initialized
INFO - 2020-04-09 15:57:08 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:57:08 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:57:08 --> Utf8 Class Initialized
INFO - 2020-04-09 15:57:08 --> URI Class Initialized
INFO - 2020-04-09 15:57:08 --> Router Class Initialized
INFO - 2020-04-09 15:57:08 --> Output Class Initialized
INFO - 2020-04-09 15:57:08 --> Security Class Initialized
DEBUG - 2020-04-09 15:57:08 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:57:08 --> Input Class Initialized
INFO - 2020-04-09 15:57:08 --> Language Class Initialized
INFO - 2020-04-09 15:57:08 --> Loader Class Initialized
INFO - 2020-04-09 15:57:08 --> Helper loaded: url_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: file_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: site_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:57:08 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:57:08 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:08 --> Email Class Initialized
DEBUG - 2020-04-09 15:57:08 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:57:08 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:57:08 --> Helper loaded: form_helper
INFO - 2020-04-09 15:57:08 --> Form Validation Class Initialized
INFO - 2020-04-09 15:57:08 --> Controller Class Initialized
INFO - 2020-04-09 15:57:08 --> Model "Common_model" initialized
INFO - 2020-04-09 15:57:08 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:57:08 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:57:08 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:57:08 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:08 --> Helper loaded: language_helper
INFO - 2020-04-09 15:57:08 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:57:10 --> Config Class Initialized
INFO - 2020-04-09 15:57:10 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:57:10 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:57:10 --> Utf8 Class Initialized
INFO - 2020-04-09 15:57:10 --> URI Class Initialized
INFO - 2020-04-09 15:57:10 --> Router Class Initialized
INFO - 2020-04-09 15:57:10 --> Output Class Initialized
INFO - 2020-04-09 15:57:10 --> Security Class Initialized
DEBUG - 2020-04-09 15:57:10 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:57:10 --> Input Class Initialized
INFO - 2020-04-09 15:57:10 --> Language Class Initialized
INFO - 2020-04-09 15:57:10 --> Loader Class Initialized
INFO - 2020-04-09 15:57:10 --> Helper loaded: url_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: file_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: site_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:57:10 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:57:10 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:10 --> Email Class Initialized
DEBUG - 2020-04-09 15:57:11 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:57:11 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:57:11 --> Helper loaded: form_helper
INFO - 2020-04-09 15:57:11 --> Form Validation Class Initialized
INFO - 2020-04-09 15:57:11 --> Controller Class Initialized
INFO - 2020-04-09 15:57:11 --> Model "Common_model" initialized
INFO - 2020-04-09 15:57:11 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:57:11 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:57:11 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:57:11 --> Database Driver Class Initialized
INFO - 2020-04-09 15:57:11 --> Helper loaded: language_helper
INFO - 2020-04-09 15:57:11 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 15:57:11 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/product/addProductType.php
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:57:11 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:57:11 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplateSmeMobile.php
INFO - 2020-04-09 15:57:11 --> Final output sent to browser
DEBUG - 2020-04-09 15:57:11 --> Total execution time: 0.6157
INFO - 2020-04-09 15:57:17 --> Config Class Initialized
INFO - 2020-04-09 15:57:17 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:57:17 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:57:17 --> Utf8 Class Initialized
INFO - 2020-04-09 15:57:17 --> URI Class Initialized
INFO - 2020-04-09 15:57:17 --> Router Class Initialized
INFO - 2020-04-09 15:57:17 --> Output Class Initialized
INFO - 2020-04-09 15:57:17 --> Security Class Initialized
DEBUG - 2020-04-09 15:57:17 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:57:17 --> Input Class Initialized
INFO - 2020-04-09 15:57:17 --> Language Class Initialized
ERROR - 2020-04-09 15:57:17 --> 404 Page Not Found: KustiaGeneralErp/subCategory
INFO - 2020-04-09 15:59:16 --> Config Class Initialized
INFO - 2020-04-09 15:59:16 --> Hooks Class Initialized
DEBUG - 2020-04-09 15:59:16 --> UTF-8 Support Enabled
INFO - 2020-04-09 15:59:16 --> Utf8 Class Initialized
INFO - 2020-04-09 15:59:16 --> URI Class Initialized
INFO - 2020-04-09 15:59:16 --> Router Class Initialized
INFO - 2020-04-09 15:59:16 --> Output Class Initialized
INFO - 2020-04-09 15:59:16 --> Security Class Initialized
DEBUG - 2020-04-09 15:59:16 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 15:59:16 --> Input Class Initialized
INFO - 2020-04-09 15:59:16 --> Language Class Initialized
INFO - 2020-04-09 15:59:16 --> Loader Class Initialized
INFO - 2020-04-09 15:59:16 --> Helper loaded: url_helper
INFO - 2020-04-09 15:59:16 --> Helper loaded: file_helper
INFO - 2020-04-09 15:59:16 --> Helper loaded: utility_helper
INFO - 2020-04-09 15:59:16 --> Helper loaded: unit_helper
INFO - 2020-04-09 15:59:16 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 15:59:16 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 15:59:17 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 15:59:17 --> Helper loaded: site_helper
INFO - 2020-04-09 15:59:17 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 15:59:17 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 15:59:17 --> Database Driver Class Initialized
INFO - 2020-04-09 15:59:17 --> Email Class Initialized
DEBUG - 2020-04-09 15:59:17 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 15:59:17 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 15:59:17 --> Helper loaded: form_helper
INFO - 2020-04-09 15:59:17 --> Form Validation Class Initialized
INFO - 2020-04-09 15:59:17 --> Controller Class Initialized
INFO - 2020-04-09 15:59:17 --> Model "Common_model" initialized
INFO - 2020-04-09 15:59:17 --> Model "Finane_Model" initialized
INFO - 2020-04-09 15:59:17 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 15:59:17 --> Model "Sales_Model" initialized
INFO - 2020-04-09 15:59:17 --> Model "Purchases_Model" initialized
INFO - 2020-04-09 15:59:17 --> Database Driver Class Initialized
INFO - 2020-04-09 15:59:17 --> Helper loaded: language_helper
INFO - 2020-04-09 15:59:17 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Product_Category_List"
INFO - 2020-04-09 15:59:17 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/productCat/productCatAdd.php
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Employee"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "2019"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Report"
ERROR - 2020-04-09 15:59:17 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 15:59:17 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 15:59:17 --> Final output sent to browser
DEBUG - 2020-04-09 15:59:18 --> Total execution time: 1.4605
INFO - 2020-04-09 16:00:47 --> Config Class Initialized
INFO - 2020-04-09 16:00:47 --> Hooks Class Initialized
DEBUG - 2020-04-09 16:00:47 --> UTF-8 Support Enabled
INFO - 2020-04-09 16:00:47 --> Utf8 Class Initialized
INFO - 2020-04-09 16:00:47 --> URI Class Initialized
INFO - 2020-04-09 16:00:47 --> Router Class Initialized
INFO - 2020-04-09 16:00:47 --> Output Class Initialized
INFO - 2020-04-09 16:00:47 --> Security Class Initialized
DEBUG - 2020-04-09 16:00:47 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 16:00:47 --> Input Class Initialized
INFO - 2020-04-09 16:00:47 --> Language Class Initialized
INFO - 2020-04-09 16:00:47 --> Loader Class Initialized
INFO - 2020-04-09 16:00:47 --> Helper loaded: url_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: file_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: utility_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: unit_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: site_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 16:00:47 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 16:00:47 --> Database Driver Class Initialized
INFO - 2020-04-09 16:00:47 --> Email Class Initialized
DEBUG - 2020-04-09 16:00:47 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 16:00:47 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 16:00:47 --> Helper loaded: form_helper
INFO - 2020-04-09 16:00:47 --> Form Validation Class Initialized
INFO - 2020-04-09 16:00:47 --> Controller Class Initialized
INFO - 2020-04-09 16:00:47 --> Model "Common_model" initialized
INFO - 2020-04-09 16:00:47 --> Model "Finane_Model" initialized
INFO - 2020-04-09 16:00:47 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 16:00:47 --> Model "Sales_Model" initialized
INFO - 2020-04-09 16:00:47 --> Database Driver Class Initialized
INFO - 2020-04-09 16:00:47 --> Helper loaded: language_helper
INFO - 2020-04-09 16:00:47 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 16:00:47 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/product/addNewProduct.php
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Product_Category_Model_Color_Size"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Sales_Return"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Report"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Report"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Payment_/_Receive_Voucher_Report"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Employee"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Sales_Import"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Finance_Import"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Purchases_Import"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "2019"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Report"
ERROR - 2020-04-09 16:00:47 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 16:00:47 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplateSmeMobile.php
INFO - 2020-04-09 16:00:47 --> Final output sent to browser
DEBUG - 2020-04-09 16:00:47 --> Total execution time: 0.7456
INFO - 2020-04-09 21:38:27 --> Config Class Initialized
INFO - 2020-04-09 21:38:27 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:27 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:27 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:27 --> URI Class Initialized
INFO - 2020-04-09 21:38:27 --> Router Class Initialized
INFO - 2020-04-09 21:38:28 --> Output Class Initialized
INFO - 2020-04-09 21:38:28 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:28 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:28 --> Input Class Initialized
INFO - 2020-04-09 21:38:28 --> Language Class Initialized
INFO - 2020-04-09 21:38:28 --> Loader Class Initialized
INFO - 2020-04-09 21:38:28 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:28 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:29 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:30 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:30 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:30 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:30 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:30 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:30 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:30 --> Controller Class Initialized
INFO - 2020-04-09 21:38:30 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:30 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:38:31 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:38:31 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:38:31 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:38:31 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 21:38:31 --> Config Class Initialized
INFO - 2020-04-09 21:38:31 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:31 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:31 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:31 --> URI Class Initialized
DEBUG - 2020-04-09 21:38:31 --> No URI present. Default controller set.
INFO - 2020-04-09 21:38:31 --> Router Class Initialized
INFO - 2020-04-09 21:38:31 --> Output Class Initialized
INFO - 2020-04-09 21:38:31 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:31 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:31 --> Input Class Initialized
INFO - 2020-04-09 21:38:31 --> Language Class Initialized
INFO - 2020-04-09 21:38:32 --> Loader Class Initialized
INFO - 2020-04-09 21:38:32 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:32 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:32 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:32 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:32 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:32 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:32 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:32 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:32 --> Controller Class Initialized
INFO - 2020-04-09 21:38:32 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:32 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:32 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:38:32 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 21:38:32 --> Final output sent to browser
DEBUG - 2020-04-09 21:38:32 --> Total execution time: 0.8125
INFO - 2020-04-09 21:38:32 --> Config Class Initialized
INFO - 2020-04-09 21:38:32 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:32 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:32 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:32 --> URI Class Initialized
INFO - 2020-04-09 21:38:32 --> Router Class Initialized
INFO - 2020-04-09 21:38:32 --> Output Class Initialized
INFO - 2020-04-09 21:38:32 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:32 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:32 --> Input Class Initialized
INFO - 2020-04-09 21:38:33 --> Language Class Initialized
ERROR - 2020-04-09 21:38:33 --> 404 Page Not Found: Assets/global
INFO - 2020-04-09 21:38:34 --> Config Class Initialized
INFO - 2020-04-09 21:38:34 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:34 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:34 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:34 --> URI Class Initialized
INFO - 2020-04-09 21:38:34 --> Router Class Initialized
INFO - 2020-04-09 21:38:34 --> Output Class Initialized
INFO - 2020-04-09 21:38:34 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:34 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:34 --> Input Class Initialized
INFO - 2020-04-09 21:38:34 --> Language Class Initialized
INFO - 2020-04-09 21:38:34 --> Loader Class Initialized
INFO - 2020-04-09 21:38:34 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:34 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:34 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:34 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:34 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:34 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:34 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:34 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:34 --> Controller Class Initialized
INFO - 2020-04-09 21:38:34 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:34 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:34 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:38:34 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 21:38:34 --> Final output sent to browser
DEBUG - 2020-04-09 21:38:34 --> Total execution time: 0.3212
INFO - 2020-04-09 21:38:38 --> Config Class Initialized
INFO - 2020-04-09 21:38:38 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:38 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:38 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:38 --> URI Class Initialized
INFO - 2020-04-09 21:38:38 --> Router Class Initialized
INFO - 2020-04-09 21:38:38 --> Output Class Initialized
INFO - 2020-04-09 21:38:38 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:38 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:38 --> Input Class Initialized
INFO - 2020-04-09 21:38:38 --> Language Class Initialized
INFO - 2020-04-09 21:38:38 --> Loader Class Initialized
INFO - 2020-04-09 21:38:38 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:38 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:38 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:38 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:38 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:38 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:38 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:38 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:38 --> Controller Class Initialized
INFO - 2020-04-09 21:38:38 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:38 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:38 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:38:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 21:38:38 --> Final output sent to browser
DEBUG - 2020-04-09 21:38:38 --> Total execution time: 0.3274
INFO - 2020-04-09 21:38:38 --> Config Class Initialized
INFO - 2020-04-09 21:38:38 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:38 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:38 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:38 --> URI Class Initialized
INFO - 2020-04-09 21:38:38 --> Router Class Initialized
INFO - 2020-04-09 21:38:38 --> Output Class Initialized
INFO - 2020-04-09 21:38:38 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:38 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:38 --> Input Class Initialized
INFO - 2020-04-09 21:38:38 --> Language Class Initialized
ERROR - 2020-04-09 21:38:38 --> 404 Page Not Found: Assets/global
INFO - 2020-04-09 21:38:39 --> Config Class Initialized
INFO - 2020-04-09 21:38:39 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:39 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:39 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:39 --> URI Class Initialized
INFO - 2020-04-09 21:38:39 --> Router Class Initialized
INFO - 2020-04-09 21:38:39 --> Output Class Initialized
INFO - 2020-04-09 21:38:39 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:39 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:39 --> Input Class Initialized
INFO - 2020-04-09 21:38:39 --> Language Class Initialized
INFO - 2020-04-09 21:38:39 --> Loader Class Initialized
INFO - 2020-04-09 21:38:39 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:39 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:39 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:39 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:39 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:39 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:39 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:39 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:39 --> Controller Class Initialized
INFO - 2020-04-09 21:38:39 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:39 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:39 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:38:40 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-09 21:38:40 --> Final output sent to browser
DEBUG - 2020-04-09 21:38:40 --> Total execution time: 0.4403
INFO - 2020-04-09 21:38:44 --> Config Class Initialized
INFO - 2020-04-09 21:38:44 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:44 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:44 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:44 --> URI Class Initialized
INFO - 2020-04-09 21:38:44 --> Router Class Initialized
INFO - 2020-04-09 21:38:44 --> Output Class Initialized
INFO - 2020-04-09 21:38:44 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:44 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:44 --> Input Class Initialized
INFO - 2020-04-09 21:38:44 --> Language Class Initialized
INFO - 2020-04-09 21:38:44 --> Loader Class Initialized
INFO - 2020-04-09 21:38:44 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:44 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:44 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:44 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:44 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:44 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:44 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:44 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:44 --> Controller Class Initialized
INFO - 2020-04-09 21:38:44 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:44 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:44 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:38:44 --> Config Class Initialized
INFO - 2020-04-09 21:38:44 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:45 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:45 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:45 --> URI Class Initialized
INFO - 2020-04-09 21:38:45 --> Router Class Initialized
INFO - 2020-04-09 21:38:45 --> Output Class Initialized
INFO - 2020-04-09 21:38:45 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:45 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:45 --> Input Class Initialized
INFO - 2020-04-09 21:38:45 --> Language Class Initialized
INFO - 2020-04-09 21:38:45 --> Loader Class Initialized
INFO - 2020-04-09 21:38:45 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:45 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:45 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:45 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:45 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:45 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:45 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:45 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:45 --> Controller Class Initialized
INFO - 2020-04-09 21:38:45 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:45 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:38:45 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:38:45 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:38:45 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 21:38:45 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:38:45 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:45 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:45 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:38:48 --> Could not find the language line "Month_Wise_Sales"
INFO - 2020-04-09 21:38:48 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/dashboard.php
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:38:50 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:38:50 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:38:50 --> Final output sent to browser
DEBUG - 2020-04-09 21:38:50 --> Total execution time: 6.0302
INFO - 2020-04-09 21:38:53 --> Config Class Initialized
INFO - 2020-04-09 21:38:53 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:53 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:53 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:53 --> URI Class Initialized
INFO - 2020-04-09 21:38:53 --> Router Class Initialized
INFO - 2020-04-09 21:38:53 --> Output Class Initialized
INFO - 2020-04-09 21:38:53 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:53 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:53 --> Input Class Initialized
INFO - 2020-04-09 21:38:53 --> Language Class Initialized
INFO - 2020-04-09 21:38:53 --> Loader Class Initialized
INFO - 2020-04-09 21:38:53 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:53 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:53 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:53 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:53 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:54 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:54 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:54 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:54 --> Controller Class Initialized
INFO - 2020-04-09 21:38:54 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:54 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:38:54 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:38:54 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:38:54 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 21:38:54 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:38:54 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:54 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:54 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:38:54 --> this is dash bord summaryArray
(
    [accountReceiable] => -121500.00
    [accountPayable] => -26000.00
    [cashInHand] => 142000.00
    [totalSalesAmount] => 27500.0000
    [inventoryAmount] => -9600.00
    [totalCashAtBank] => 
)

INFO - 2020-04-09 21:38:54 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/ajax/dashbordSummary.php
INFO - 2020-04-09 21:38:54 --> Final output sent to browser
DEBUG - 2020-04-09 21:38:54 --> Total execution time: 1.0144
INFO - 2020-04-09 21:38:56 --> Config Class Initialized
INFO - 2020-04-09 21:38:56 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:38:56 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:38:56 --> Utf8 Class Initialized
INFO - 2020-04-09 21:38:56 --> URI Class Initialized
INFO - 2020-04-09 21:38:57 --> Router Class Initialized
INFO - 2020-04-09 21:38:57 --> Output Class Initialized
INFO - 2020-04-09 21:38:57 --> Security Class Initialized
DEBUG - 2020-04-09 21:38:57 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:38:57 --> Input Class Initialized
INFO - 2020-04-09 21:38:57 --> Language Class Initialized
INFO - 2020-04-09 21:38:57 --> Loader Class Initialized
INFO - 2020-04-09 21:38:57 --> Helper loaded: url_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: file_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: site_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:38:57 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:38:57 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:57 --> Email Class Initialized
DEBUG - 2020-04-09 21:38:57 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:38:57 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:38:57 --> Helper loaded: form_helper
INFO - 2020-04-09 21:38:57 --> Form Validation Class Initialized
INFO - 2020-04-09 21:38:57 --> Controller Class Initialized
INFO - 2020-04-09 21:38:57 --> Model "Common_model" initialized
INFO - 2020-04-09 21:38:57 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:38:57 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:38:57 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:38:57 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:38:57 --> Database Driver Class Initialized
INFO - 2020-04-09 21:38:57 --> Helper loaded: language_helper
INFO - 2020-04-09 21:38:57 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:38:57 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:38:57 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:38:57 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:38:57 --> Final output sent to browser
DEBUG - 2020-04-09 21:38:57 --> Total execution time: 0.9325
INFO - 2020-04-09 21:39:04 --> Config Class Initialized
INFO - 2020-04-09 21:39:04 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:39:04 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:39:04 --> Utf8 Class Initialized
INFO - 2020-04-09 21:39:04 --> URI Class Initialized
INFO - 2020-04-09 21:39:04 --> Router Class Initialized
INFO - 2020-04-09 21:39:04 --> Output Class Initialized
INFO - 2020-04-09 21:39:04 --> Security Class Initialized
DEBUG - 2020-04-09 21:39:04 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:39:04 --> Input Class Initialized
INFO - 2020-04-09 21:39:04 --> Language Class Initialized
INFO - 2020-04-09 21:39:04 --> Loader Class Initialized
INFO - 2020-04-09 21:39:04 --> Helper loaded: url_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: file_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: site_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:39:04 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:39:04 --> Database Driver Class Initialized
INFO - 2020-04-09 21:39:04 --> Email Class Initialized
DEBUG - 2020-04-09 21:39:04 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:39:04 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:39:04 --> Helper loaded: form_helper
INFO - 2020-04-09 21:39:04 --> Form Validation Class Initialized
INFO - 2020-04-09 21:39:04 --> Controller Class Initialized
INFO - 2020-04-09 21:39:04 --> Model "Common_model" initialized
INFO - 2020-04-09 21:39:04 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:39:04 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:39:04 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:39:04 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:39:04 --> Database Driver Class Initialized
INFO - 2020-04-09 21:39:04 --> Helper loaded: language_helper
INFO - 2020-04-09 21:39:04 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:39:05 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 21:39:05 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 21:39:05 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 21:39:05 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 21:39:05 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:39:05 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:05 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:06 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:39:06 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:39:06 --> Final output sent to browser
DEBUG - 2020-04-09 21:39:06 --> Total execution time: 2.0509
INFO - 2020-04-09 21:39:22 --> Config Class Initialized
INFO - 2020-04-09 21:39:22 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:39:22 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:39:22 --> Utf8 Class Initialized
INFO - 2020-04-09 21:39:22 --> URI Class Initialized
INFO - 2020-04-09 21:39:23 --> Router Class Initialized
INFO - 2020-04-09 21:39:23 --> Output Class Initialized
INFO - 2020-04-09 21:39:23 --> Security Class Initialized
DEBUG - 2020-04-09 21:39:23 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:39:23 --> Input Class Initialized
INFO - 2020-04-09 21:39:23 --> Language Class Initialized
INFO - 2020-04-09 21:39:23 --> Loader Class Initialized
INFO - 2020-04-09 21:39:23 --> Helper loaded: url_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: file_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: site_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:39:23 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:39:23 --> Database Driver Class Initialized
INFO - 2020-04-09 21:39:23 --> Email Class Initialized
DEBUG - 2020-04-09 21:39:23 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:39:23 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:39:23 --> Helper loaded: form_helper
INFO - 2020-04-09 21:39:23 --> Form Validation Class Initialized
INFO - 2020-04-09 21:39:23 --> Controller Class Initialized
INFO - 2020-04-09 21:39:23 --> Model "Common_model" initialized
INFO - 2020-04-09 21:39:23 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:39:23 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:39:23 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:39:23 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:39:23 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 21:39:23 --> Database Driver Class Initialized
INFO - 2020-04-09 21:39:23 --> Helper loaded: language_helper
INFO - 2020-04-09 21:39:23 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 21:39:23 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Commission"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 21:39:24 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:24 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:39:24 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:39:24 --> Final output sent to browser
DEBUG - 2020-04-09 21:39:24 --> Total execution time: 1.5608
INFO - 2020-04-09 21:39:30 --> Config Class Initialized
INFO - 2020-04-09 21:39:30 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:39:30 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:39:30 --> Utf8 Class Initialized
INFO - 2020-04-09 21:39:30 --> URI Class Initialized
INFO - 2020-04-09 21:39:30 --> Router Class Initialized
INFO - 2020-04-09 21:39:30 --> Output Class Initialized
INFO - 2020-04-09 21:39:30 --> Security Class Initialized
DEBUG - 2020-04-09 21:39:30 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:39:30 --> Input Class Initialized
INFO - 2020-04-09 21:39:30 --> Language Class Initialized
INFO - 2020-04-09 21:39:30 --> Loader Class Initialized
INFO - 2020-04-09 21:39:30 --> Helper loaded: url_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: file_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: site_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:39:30 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:39:30 --> Database Driver Class Initialized
INFO - 2020-04-09 21:39:30 --> Email Class Initialized
DEBUG - 2020-04-09 21:39:30 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:39:30 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:39:30 --> Helper loaded: form_helper
INFO - 2020-04-09 21:39:30 --> Form Validation Class Initialized
INFO - 2020-04-09 21:39:30 --> Controller Class Initialized
INFO - 2020-04-09 21:39:30 --> Model "Common_model" initialized
INFO - 2020-04-09 21:39:30 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:39:30 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:39:30 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:39:30 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:39:30 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 21:39:30 --> Database Driver Class Initialized
INFO - 2020-04-09 21:39:30 --> Helper loaded: language_helper
INFO - 2020-04-09 21:39:30 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:39:35 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Commission"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 21:39:36 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 21:39:36 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:39:37 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:39:37 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:39:37 --> Final output sent to browser
DEBUG - 2020-04-09 21:39:37 --> Total execution time: 6.8616
INFO - 2020-04-09 21:41:13 --> Config Class Initialized
INFO - 2020-04-09 21:41:13 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:41:13 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:41:13 --> Utf8 Class Initialized
INFO - 2020-04-09 21:41:13 --> URI Class Initialized
INFO - 2020-04-09 21:41:13 --> Router Class Initialized
INFO - 2020-04-09 21:41:13 --> Output Class Initialized
INFO - 2020-04-09 21:41:13 --> Security Class Initialized
DEBUG - 2020-04-09 21:41:13 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:41:13 --> Input Class Initialized
INFO - 2020-04-09 21:41:13 --> Language Class Initialized
INFO - 2020-04-09 21:41:13 --> Loader Class Initialized
INFO - 2020-04-09 21:41:13 --> Helper loaded: url_helper
INFO - 2020-04-09 21:41:13 --> Helper loaded: file_helper
INFO - 2020-04-09 21:41:13 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:41:13 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:41:14 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:41:14 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:41:14 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:41:14 --> Helper loaded: site_helper
INFO - 2020-04-09 21:41:14 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:41:14 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:41:14 --> Database Driver Class Initialized
INFO - 2020-04-09 21:41:14 --> Email Class Initialized
DEBUG - 2020-04-09 21:41:14 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:41:14 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:41:14 --> Helper loaded: form_helper
INFO - 2020-04-09 21:41:14 --> Form Validation Class Initialized
INFO - 2020-04-09 21:41:14 --> Controller Class Initialized
INFO - 2020-04-09 21:41:14 --> Model "Common_model" initialized
INFO - 2020-04-09 21:41:14 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:41:14 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:41:14 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:41:14 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:41:14 --> Database Driver Class Initialized
INFO - 2020-04-09 21:41:14 --> Helper loaded: language_helper
INFO - 2020-04-09 21:41:14 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:41:15 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 21:41:15 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 21:41:15 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 21:41:15 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:41:18 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:41:19 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:41:19 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:41:19 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:41:19 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:41:19 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:41:19 --> Final output sent to browser
DEBUG - 2020-04-09 21:41:19 --> Total execution time: 5.6004
INFO - 2020-04-09 21:44:08 --> Config Class Initialized
INFO - 2020-04-09 21:44:08 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:44:08 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:44:08 --> Utf8 Class Initialized
INFO - 2020-04-09 21:44:08 --> URI Class Initialized
INFO - 2020-04-09 21:44:08 --> Router Class Initialized
INFO - 2020-04-09 21:44:08 --> Output Class Initialized
INFO - 2020-04-09 21:44:08 --> Security Class Initialized
DEBUG - 2020-04-09 21:44:08 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:44:08 --> Input Class Initialized
INFO - 2020-04-09 21:44:08 --> Language Class Initialized
INFO - 2020-04-09 21:44:08 --> Loader Class Initialized
INFO - 2020-04-09 21:44:08 --> Helper loaded: url_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: file_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: site_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:44:08 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:44:08 --> Database Driver Class Initialized
INFO - 2020-04-09 21:44:08 --> Email Class Initialized
DEBUG - 2020-04-09 21:44:08 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:44:08 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:44:08 --> Helper loaded: form_helper
INFO - 2020-04-09 21:44:08 --> Form Validation Class Initialized
INFO - 2020-04-09 21:44:08 --> Controller Class Initialized
INFO - 2020-04-09 21:44:08 --> Model "Common_model" initialized
INFO - 2020-04-09 21:44:08 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:44:08 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:44:08 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:44:08 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:44:08 --> Database Driver Class Initialized
INFO - 2020-04-09 21:44:08 --> Helper loaded: language_helper
INFO - 2020-04-09 21:44:08 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:44:08 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 21:44:08 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 21:44:09 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 21:44:09 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:44:09 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:44:09 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:44:09 --> Final output sent to browser
DEBUG - 2020-04-09 21:44:09 --> Total execution time: 0.7499
INFO - 2020-04-09 21:52:31 --> Config Class Initialized
INFO - 2020-04-09 21:52:31 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:52:31 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:52:31 --> Utf8 Class Initialized
INFO - 2020-04-09 21:52:31 --> URI Class Initialized
INFO - 2020-04-09 21:52:31 --> Router Class Initialized
INFO - 2020-04-09 21:52:31 --> Output Class Initialized
INFO - 2020-04-09 21:52:31 --> Security Class Initialized
DEBUG - 2020-04-09 21:52:31 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:52:31 --> Input Class Initialized
INFO - 2020-04-09 21:52:31 --> Language Class Initialized
INFO - 2020-04-09 21:52:31 --> Loader Class Initialized
INFO - 2020-04-09 21:52:31 --> Helper loaded: url_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: file_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: site_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:52:31 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:52:31 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:31 --> Email Class Initialized
DEBUG - 2020-04-09 21:52:31 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:52:31 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:52:31 --> Helper loaded: form_helper
INFO - 2020-04-09 21:52:31 --> Form Validation Class Initialized
INFO - 2020-04-09 21:52:31 --> Controller Class Initialized
INFO - 2020-04-09 21:52:31 --> Model "Common_model" initialized
INFO - 2020-04-09 21:52:31 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:52:31 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:52:31 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:52:31 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:52:31 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:31 --> Helper loaded: language_helper
INFO - 2020-04-09 21:52:31 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:52:32 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/setup/ViewChartOfAccount.php
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:32 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:52:32 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:52:32 --> Final output sent to browser
DEBUG - 2020-04-09 21:52:32 --> Total execution time: 0.7053
INFO - 2020-04-09 21:52:36 --> Config Class Initialized
INFO - 2020-04-09 21:52:36 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:52:36 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:52:36 --> Utf8 Class Initialized
INFO - 2020-04-09 21:52:36 --> URI Class Initialized
INFO - 2020-04-09 21:52:36 --> Router Class Initialized
INFO - 2020-04-09 21:52:36 --> Output Class Initialized
INFO - 2020-04-09 21:52:36 --> Security Class Initialized
DEBUG - 2020-04-09 21:52:36 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:52:36 --> Input Class Initialized
INFO - 2020-04-09 21:52:36 --> Language Class Initialized
INFO - 2020-04-09 21:52:36 --> Loader Class Initialized
INFO - 2020-04-09 21:52:36 --> Helper loaded: url_helper
INFO - 2020-04-09 21:52:36 --> Helper loaded: file_helper
INFO - 2020-04-09 21:52:36 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:52:36 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:52:36 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:52:36 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:52:36 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:52:37 --> Helper loaded: site_helper
INFO - 2020-04-09 21:52:37 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:52:37 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:52:37 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:37 --> Email Class Initialized
DEBUG - 2020-04-09 21:52:37 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:52:37 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:52:37 --> Helper loaded: form_helper
INFO - 2020-04-09 21:52:37 --> Form Validation Class Initialized
INFO - 2020-04-09 21:52:37 --> Controller Class Initialized
INFO - 2020-04-09 21:52:37 --> Model "Common_model" initialized
INFO - 2020-04-09 21:52:37 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:52:37 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:52:37 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:52:37 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:52:37 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:37 --> Helper loaded: language_helper
INFO - 2020-04-09 21:52:37 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Chart_List"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Capital_&_Liabilities"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Non-current_assets"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Land_and_Land_Development"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Building_&_Decoration"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Plant_&_Machinarie"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Computer_&_IT_Equipments"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Electrical_Equipment_&_Appliances"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "VAN_&_Vehicles"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Intercompany_Account_-Assets"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Investment_to_Projects"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Investment_to_Other"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Inventory_&_Finished_Goods_Stock"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Damage_Stock"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Raw_Materials_&_WIP"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Refill_Stock"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Empty_Cylinder_Transfer"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Petty_Cash"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Cash_to_Locker"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Investment_in_FDR_-"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Advance_Income_TAX_(AIT)_-Asset"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Advance_VAT_-Asset"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Equity_Capital"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Non-Current_Liability"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Sales_Revenue"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Other_Income"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Loader's_Payable_Sales"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Transportation_Payable_sales"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Cost_of_Goods_Sold"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Loading_&_Wages"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Common_Loading_&_Wages"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Common_Transportation"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Cost_of_Goods_Product-"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Marketing_Expenses"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Tex_AIT_&_VAT_Expenses"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Selling_&_Distribution_Expenses"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Financial_Expenses"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Commission"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 21:52:37 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Difference_In_Opening_Balance"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Sales_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Cost_of_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Transfer_Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Refill_LAF_Refill_12KG_Only_Gas_800Tk/Btl_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Capital_Loan_OWNER_-1"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Cash_with_Mr._Rahman"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "LAF_GAS_Supplier_LPG_12KG_[_]"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "002548_new_[_CID20010003_]"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "002548_new_[_CID20010003_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Helal_Uddin_[_CID20010004_]"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Helal_Uddin_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Refill_Refill_BM_12Kg_600Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Transfer_Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Office_Rent_#_Hasan_Plaza_#_2020"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Marketing_Tour_Allowance_#_2020"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "SK_Courier_Bill_(Outward)_#_2020"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Bank_Chage_#_IBBL_#_3206"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Monthly_Expense_Provision_#_2020"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Bank_&_Financial_Expenses_(IBBL#_2145)"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Bank_CA_#_IBBL_3206"
INFO - 2020-04-09 21:52:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/setup/listChartOfAccount.php
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:38 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:52:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:52:38 --> Final output sent to browser
DEBUG - 2020-04-09 21:52:38 --> Total execution time: 1.6749
INFO - 2020-04-09 21:52:38 --> Config Class Initialized
INFO - 2020-04-09 21:52:38 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:52:38 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:52:38 --> Utf8 Class Initialized
INFO - 2020-04-09 21:52:38 --> URI Class Initialized
INFO - 2020-04-09 21:52:38 --> Router Class Initialized
INFO - 2020-04-09 21:52:38 --> Output Class Initialized
INFO - 2020-04-09 21:52:38 --> Security Class Initialized
DEBUG - 2020-04-09 21:52:38 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:52:38 --> Input Class Initialized
INFO - 2020-04-09 21:52:38 --> Language Class Initialized
ERROR - 2020-04-09 21:52:38 --> 404 Page Not Found: Assets/setu1p.js
INFO - 2020-04-09 21:52:39 --> Config Class Initialized
INFO - 2020-04-09 21:52:39 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:52:39 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:52:39 --> Utf8 Class Initialized
INFO - 2020-04-09 21:52:39 --> URI Class Initialized
INFO - 2020-04-09 21:52:39 --> Router Class Initialized
INFO - 2020-04-09 21:52:39 --> Output Class Initialized
INFO - 2020-04-09 21:52:39 --> Security Class Initialized
DEBUG - 2020-04-09 21:52:39 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:52:39 --> Input Class Initialized
INFO - 2020-04-09 21:52:39 --> Language Class Initialized
INFO - 2020-04-09 21:52:39 --> Loader Class Initialized
INFO - 2020-04-09 21:52:39 --> Helper loaded: url_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: file_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: site_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:52:39 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:52:39 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:39 --> Email Class Initialized
DEBUG - 2020-04-09 21:52:39 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:52:39 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:52:39 --> Helper loaded: form_helper
INFO - 2020-04-09 21:52:39 --> Form Validation Class Initialized
INFO - 2020-04-09 21:52:39 --> Controller Class Initialized
INFO - 2020-04-09 21:52:39 --> Model "Common_model" initialized
INFO - 2020-04-09 21:52:39 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:52:39 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:52:39 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:52:39 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:52:39 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:39 --> Helper loaded: language_helper
INFO - 2020-04-09 21:52:39 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:52:39 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/setup/chartOfAccount.php
ERROR - 2020-04-09 21:52:39 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 21:52:39 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:39 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 21:52:39 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:39 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 21:52:40 --> Could not find the language line "Employee"
ERROR - 2020-04-09 21:52:40 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 21:52:40 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 21:52:40 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 21:52:40 --> Could not find the language line "2019"
ERROR - 2020-04-09 21:52:40 --> Could not find the language line "Report"
ERROR - 2020-04-09 21:52:40 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 21:52:40 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 21:52:40 --> Final output sent to browser
DEBUG - 2020-04-09 21:52:40 --> Total execution time: 0.6005
INFO - 2020-04-09 21:52:42 --> Config Class Initialized
INFO - 2020-04-09 21:52:42 --> Config Class Initialized
INFO - 2020-04-09 21:52:42 --> Hooks Class Initialized
INFO - 2020-04-09 21:52:42 --> Hooks Class Initialized
DEBUG - 2020-04-09 21:52:42 --> UTF-8 Support Enabled
DEBUG - 2020-04-09 21:52:42 --> UTF-8 Support Enabled
INFO - 2020-04-09 21:52:42 --> Utf8 Class Initialized
INFO - 2020-04-09 21:52:42 --> Utf8 Class Initialized
INFO - 2020-04-09 21:52:42 --> URI Class Initialized
INFO - 2020-04-09 21:52:42 --> URI Class Initialized
INFO - 2020-04-09 21:52:42 --> Router Class Initialized
INFO - 2020-04-09 21:52:42 --> Router Class Initialized
INFO - 2020-04-09 21:52:42 --> Output Class Initialized
INFO - 2020-04-09 21:52:42 --> Output Class Initialized
INFO - 2020-04-09 21:52:42 --> Security Class Initialized
INFO - 2020-04-09 21:52:42 --> Security Class Initialized
DEBUG - 2020-04-09 21:52:42 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2020-04-09 21:52:42 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 21:52:42 --> Input Class Initialized
INFO - 2020-04-09 21:52:42 --> Input Class Initialized
INFO - 2020-04-09 21:52:42 --> Language Class Initialized
INFO - 2020-04-09 21:52:42 --> Language Class Initialized
INFO - 2020-04-09 21:52:42 --> Loader Class Initialized
INFO - 2020-04-09 21:52:42 --> Loader Class Initialized
INFO - 2020-04-09 21:52:42 --> Helper loaded: url_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: url_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: file_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: file_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: utility_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: unit_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: site_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: site_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:52:42 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 21:52:42 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:42 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:42 --> Email Class Initialized
INFO - 2020-04-09 21:52:42 --> Email Class Initialized
DEBUG - 2020-04-09 21:52:42 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
DEBUG - 2020-04-09 21:52:42 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 21:52:42 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:52:42 --> Helper loaded: form_helper
INFO - 2020-04-09 21:52:42 --> Form Validation Class Initialized
INFO - 2020-04-09 21:52:42 --> Controller Class Initialized
INFO - 2020-04-09 21:52:42 --> Model "Common_model" initialized
INFO - 2020-04-09 21:52:42 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:52:42 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:52:43 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:52:43 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:52:43 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:43 --> Helper loaded: language_helper
INFO - 2020-04-09 21:52:43 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:52:43 --> Final output sent to browser
DEBUG - 2020-04-09 21:52:43 --> Total execution time: 0.7289
INFO - 2020-04-09 21:52:43 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 21:52:43 --> Helper loaded: form_helper
INFO - 2020-04-09 21:52:43 --> Form Validation Class Initialized
INFO - 2020-04-09 21:52:43 --> Controller Class Initialized
INFO - 2020-04-09 21:52:43 --> Model "Common_model" initialized
INFO - 2020-04-09 21:52:43 --> Model "Finane_Model" initialized
INFO - 2020-04-09 21:52:43 --> Model "Accounts_model" initialized
INFO - 2020-04-09 21:52:43 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 21:52:43 --> Model "Sales_Model" initialized
INFO - 2020-04-09 21:52:43 --> Database Driver Class Initialized
INFO - 2020-04-09 21:52:43 --> Helper loaded: language_helper
INFO - 2020-04-09 21:52:43 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 21:52:43 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/ajax/showTree.php
ERROR - 2020-04-09 21:52:43 --> Severity: 4096 --> Object of class CI_Loader could not be converted to string H:\XAMPP\codeigniter\htdocs\masterProject\application\controllers\lpg\AccountController.php 238
INFO - 2020-04-09 21:52:43 --> Final output sent to browser
DEBUG - 2020-04-09 21:52:43 --> Total execution time: 0.9862
INFO - 2020-04-09 22:09:15 --> Config Class Initialized
INFO - 2020-04-09 22:09:15 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:09:15 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:09:15 --> Utf8 Class Initialized
INFO - 2020-04-09 22:09:15 --> URI Class Initialized
INFO - 2020-04-09 22:09:15 --> Router Class Initialized
INFO - 2020-04-09 22:09:15 --> Output Class Initialized
INFO - 2020-04-09 22:09:15 --> Security Class Initialized
DEBUG - 2020-04-09 22:09:15 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:09:15 --> Input Class Initialized
INFO - 2020-04-09 22:09:15 --> Language Class Initialized
INFO - 2020-04-09 22:09:15 --> Loader Class Initialized
INFO - 2020-04-09 22:09:15 --> Helper loaded: url_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: file_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: site_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:09:15 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:09:15 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:15 --> Email Class Initialized
DEBUG - 2020-04-09 22:09:15 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:09:15 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:09:15 --> Helper loaded: form_helper
INFO - 2020-04-09 22:09:15 --> Form Validation Class Initialized
INFO - 2020-04-09 22:09:15 --> Controller Class Initialized
INFO - 2020-04-09 22:09:15 --> Model "Common_model" initialized
INFO - 2020-04-09 22:09:15 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:09:15 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:09:15 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:09:15 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:15 --> Helper loaded: language_helper
INFO - 2020-04-09 22:09:15 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:09:15 --> Helper loaded: sales_invoice_no_helper
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Refill_Stock"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Refill_LAF_Refill_12KG_Only_Gas_800Tk/Btl_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Refill_Refill_BM_12Kg_600Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Empty_Cylinder_Transfer"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Transfer_Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Transfer_Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Cash_with_Mr._Rahman"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "002548_new_[_CID20010003_]"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Helal_Uddin_[_CID20010004_]"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Helal_Uddin_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "002548_new_[_CID20010003_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Equity_Capital"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Capital_Loan_OWNER_-1"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "LAF_GAS_Supplier_LPG_12KG_[_]"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Bank_CA_#_IBBL_3206"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Monthly_Expense_Provision_#_2020"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Sales_Revenue"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Sales_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Loader's_Payable_Sales"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Cost_of_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Cost_of_Goods_Product-"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Loading_&_Wages"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Common_Loading_&_Wages"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Common_Transportation"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Marketing_Expenses"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Marketing_Tour_Allowance_#_2020"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Selling_&_Distribution_Expenses"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "SK_Courier_Bill_(Outward)_#_2020"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Financial_Expenses"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Bank_Chage_#_IBBL_#_3206"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Office_Rent_#_Hasan_Plaza_#_2020"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Bank_&_Financial_Expenses_(IBBL#_2145)"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Difference_In_Opening_Balance"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Bank_A/C"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Price"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Chq_Amount"
INFO - 2020-04-09 22:09:16 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/sale_add.php
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:16 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:09:16 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:09:16 --> Final output sent to browser
DEBUG - 2020-04-09 22:09:17 --> Total execution time: 1.9930
INFO - 2020-04-09 22:09:19 --> Config Class Initialized
INFO - 2020-04-09 22:09:19 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:09:19 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:09:19 --> Utf8 Class Initialized
INFO - 2020-04-09 22:09:19 --> URI Class Initialized
INFO - 2020-04-09 22:09:19 --> Router Class Initialized
INFO - 2020-04-09 22:09:19 --> Output Class Initialized
INFO - 2020-04-09 22:09:19 --> Security Class Initialized
DEBUG - 2020-04-09 22:09:19 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:09:19 --> Input Class Initialized
INFO - 2020-04-09 22:09:19 --> Language Class Initialized
INFO - 2020-04-09 22:09:19 --> Loader Class Initialized
INFO - 2020-04-09 22:09:19 --> Helper loaded: url_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: file_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: site_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:09:19 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:09:19 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:19 --> Email Class Initialized
DEBUG - 2020-04-09 22:09:19 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:09:19 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:09:19 --> Helper loaded: form_helper
INFO - 2020-04-09 22:09:19 --> Form Validation Class Initialized
INFO - 2020-04-09 22:09:19 --> Controller Class Initialized
INFO - 2020-04-09 22:09:19 --> Model "Common_model" initialized
INFO - 2020-04-09 22:09:19 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:09:19 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:09:19 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:09:19 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:19 --> Helper loaded: language_helper
INFO - 2020-04-09 22:09:19 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:09:19 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/saleList.php
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:09:19 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:09:20 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:09:20 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:09:20 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:20 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:09:20 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:09:20 --> Final output sent to browser
DEBUG - 2020-04-09 22:09:20 --> Total execution time: 0.6606
INFO - 2020-04-09 22:09:20 --> Config Class Initialized
INFO - 2020-04-09 22:09:20 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:09:20 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:09:20 --> Utf8 Class Initialized
INFO - 2020-04-09 22:09:20 --> URI Class Initialized
INFO - 2020-04-09 22:09:20 --> Router Class Initialized
INFO - 2020-04-09 22:09:20 --> Output Class Initialized
INFO - 2020-04-09 22:09:20 --> Security Class Initialized
DEBUG - 2020-04-09 22:09:20 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:09:20 --> Input Class Initialized
INFO - 2020-04-09 22:09:20 --> Language Class Initialized
INFO - 2020-04-09 22:09:20 --> Loader Class Initialized
INFO - 2020-04-09 22:09:20 --> Helper loaded: url_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: file_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: site_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:09:20 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:09:20 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:20 --> Email Class Initialized
DEBUG - 2020-04-09 22:09:20 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:09:20 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:09:20 --> Helper loaded: form_helper
INFO - 2020-04-09 22:09:20 --> Form Validation Class Initialized
INFO - 2020-04-09 22:09:20 --> Controller Class Initialized
INFO - 2020-04-09 22:09:20 --> Model "Common_model" initialized
INFO - 2020-04-09 22:09:20 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:09:20 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:09:20 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:09:20 --> Model "ServerFilterModel" initialized
INFO - 2020-04-09 22:09:20 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:20 --> Helper loaded: language_helper
INFO - 2020-04-09 22:09:21 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:09:21 --> Final output sent to browser
DEBUG - 2020-04-09 22:09:21 --> Total execution time: 0.6122
INFO - 2020-04-09 22:09:28 --> Config Class Initialized
INFO - 2020-04-09 22:09:28 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:09:28 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:09:28 --> Utf8 Class Initialized
INFO - 2020-04-09 22:09:29 --> URI Class Initialized
INFO - 2020-04-09 22:09:29 --> Router Class Initialized
INFO - 2020-04-09 22:09:29 --> Output Class Initialized
INFO - 2020-04-09 22:09:29 --> Security Class Initialized
DEBUG - 2020-04-09 22:09:29 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:09:29 --> Input Class Initialized
INFO - 2020-04-09 22:09:29 --> Language Class Initialized
INFO - 2020-04-09 22:09:29 --> Loader Class Initialized
INFO - 2020-04-09 22:09:29 --> Helper loaded: url_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: file_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: site_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:09:29 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:09:29 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:29 --> Email Class Initialized
DEBUG - 2020-04-09 22:09:29 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:09:29 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:09:29 --> Helper loaded: form_helper
INFO - 2020-04-09 22:09:29 --> Form Validation Class Initialized
INFO - 2020-04-09 22:09:29 --> Controller Class Initialized
INFO - 2020-04-09 22:09:29 --> Model "Common_model" initialized
INFO - 2020-04-09 22:09:29 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:09:29 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:09:29 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:09:29 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:29 --> Helper loaded: language_helper
INFO - 2020-04-09 22:09:29 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Branch"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Advance"
INFO - 2020-04-09 22:09:29 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/sales_view_final.php
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:29 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:09:29 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:09:29 --> Final output sent to browser
DEBUG - 2020-04-09 22:09:29 --> Total execution time: 0.8127
INFO - 2020-04-09 22:09:30 --> Config Class Initialized
INFO - 2020-04-09 22:09:30 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:09:30 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:09:30 --> Utf8 Class Initialized
INFO - 2020-04-09 22:09:32 --> Config Class Initialized
INFO - 2020-04-09 22:09:32 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:09:32 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:09:32 --> Utf8 Class Initialized
INFO - 2020-04-09 22:09:32 --> URI Class Initialized
INFO - 2020-04-09 22:09:32 --> Router Class Initialized
INFO - 2020-04-09 22:09:32 --> Output Class Initialized
INFO - 2020-04-09 22:09:32 --> Security Class Initialized
DEBUG - 2020-04-09 22:09:32 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:09:32 --> Input Class Initialized
INFO - 2020-04-09 22:09:32 --> Language Class Initialized
INFO - 2020-04-09 22:09:32 --> Loader Class Initialized
INFO - 2020-04-09 22:09:32 --> Helper loaded: url_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: file_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: site_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:09:32 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:09:33 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:33 --> Email Class Initialized
DEBUG - 2020-04-09 22:09:33 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:09:33 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:09:33 --> Helper loaded: form_helper
INFO - 2020-04-09 22:09:33 --> Form Validation Class Initialized
INFO - 2020-04-09 22:09:33 --> Controller Class Initialized
INFO - 2020-04-09 22:09:33 --> Model "Common_model" initialized
INFO - 2020-04-09 22:09:33 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:09:33 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:09:33 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:09:33 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:33 --> Helper loaded: language_helper
INFO - 2020-04-09 22:09:33 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:09:33 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/saleList.php
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:09:33 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:09:33 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:09:33 --> Final output sent to browser
DEBUG - 2020-04-09 22:09:33 --> Total execution time: 0.5323
INFO - 2020-04-09 22:09:33 --> Config Class Initialized
INFO - 2020-04-09 22:09:33 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:09:33 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:09:33 --> Utf8 Class Initialized
INFO - 2020-04-09 22:09:33 --> URI Class Initialized
INFO - 2020-04-09 22:09:33 --> Router Class Initialized
INFO - 2020-04-09 22:09:33 --> Output Class Initialized
INFO - 2020-04-09 22:09:33 --> Security Class Initialized
DEBUG - 2020-04-09 22:09:33 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:09:33 --> Input Class Initialized
INFO - 2020-04-09 22:09:33 --> Language Class Initialized
INFO - 2020-04-09 22:09:33 --> Loader Class Initialized
INFO - 2020-04-09 22:09:33 --> Helper loaded: url_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: file_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: site_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:09:33 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:09:33 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:33 --> Email Class Initialized
DEBUG - 2020-04-09 22:09:33 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:09:34 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:09:34 --> Helper loaded: form_helper
INFO - 2020-04-09 22:09:34 --> Form Validation Class Initialized
INFO - 2020-04-09 22:09:34 --> Controller Class Initialized
INFO - 2020-04-09 22:09:34 --> Model "Common_model" initialized
INFO - 2020-04-09 22:09:34 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:09:34 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:09:34 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:09:34 --> Model "ServerFilterModel" initialized
INFO - 2020-04-09 22:09:34 --> Database Driver Class Initialized
INFO - 2020-04-09 22:09:34 --> Helper loaded: language_helper
INFO - 2020-04-09 22:09:34 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:09:34 --> Final output sent to browser
DEBUG - 2020-04-09 22:09:34 --> Total execution time: 0.5568
INFO - 2020-04-09 22:26:50 --> Config Class Initialized
INFO - 2020-04-09 22:26:50 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:26:51 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:26:51 --> Utf8 Class Initialized
INFO - 2020-04-09 22:26:51 --> URI Class Initialized
INFO - 2020-04-09 22:26:51 --> Router Class Initialized
INFO - 2020-04-09 22:26:51 --> Output Class Initialized
INFO - 2020-04-09 22:26:51 --> Security Class Initialized
DEBUG - 2020-04-09 22:26:51 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:26:51 --> Input Class Initialized
INFO - 2020-04-09 22:26:51 --> Language Class Initialized
INFO - 2020-04-09 22:26:51 --> Loader Class Initialized
INFO - 2020-04-09 22:26:51 --> Helper loaded: url_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: file_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: site_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:26:51 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:26:51 --> Database Driver Class Initialized
INFO - 2020-04-09 22:26:51 --> Email Class Initialized
DEBUG - 2020-04-09 22:26:51 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:26:51 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:26:51 --> Helper loaded: form_helper
INFO - 2020-04-09 22:26:51 --> Form Validation Class Initialized
INFO - 2020-04-09 22:26:51 --> Controller Class Initialized
INFO - 2020-04-09 22:26:51 --> Model "Common_model" initialized
INFO - 2020-04-09 22:26:51 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:26:51 --> Model "Accounts_model" initialized
INFO - 2020-04-09 22:26:51 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:26:51 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:26:51 --> Database Driver Class Initialized
INFO - 2020-04-09 22:26:51 --> Helper loaded: language_helper
INFO - 2020-04-09 22:26:51 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:26:52 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 22:26:52 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 22:26:52 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 22:26:52 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:26:53 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:26:53 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:26:53 --> Final output sent to browser
DEBUG - 2020-04-09 22:26:53 --> Total execution time: 2.4922
INFO - 2020-04-09 22:26:57 --> Config Class Initialized
INFO - 2020-04-09 22:26:57 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:26:57 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:26:57 --> Utf8 Class Initialized
INFO - 2020-04-09 22:26:57 --> URI Class Initialized
INFO - 2020-04-09 22:26:57 --> Router Class Initialized
INFO - 2020-04-09 22:26:57 --> Output Class Initialized
INFO - 2020-04-09 22:26:57 --> Security Class Initialized
DEBUG - 2020-04-09 22:26:57 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:26:57 --> Input Class Initialized
INFO - 2020-04-09 22:26:57 --> Language Class Initialized
INFO - 2020-04-09 22:26:57 --> Loader Class Initialized
INFO - 2020-04-09 22:26:57 --> Helper loaded: url_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: file_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: site_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:26:57 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:26:57 --> Database Driver Class Initialized
INFO - 2020-04-09 22:26:57 --> Email Class Initialized
DEBUG - 2020-04-09 22:26:57 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:26:57 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:26:58 --> Helper loaded: form_helper
INFO - 2020-04-09 22:26:58 --> Form Validation Class Initialized
INFO - 2020-04-09 22:26:58 --> Controller Class Initialized
INFO - 2020-04-09 22:26:58 --> Model "Common_model" initialized
INFO - 2020-04-09 22:26:58 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:26:58 --> Model "Accounts_model" initialized
INFO - 2020-04-09 22:26:58 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:26:58 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:26:58 --> Database Driver Class Initialized
INFO - 2020-04-09 22:26:58 --> Helper loaded: language_helper
INFO - 2020-04-09 22:26:58 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:26:58 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 22:26:58 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 22:26:58 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:26:58 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:26:58 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:26:58 --> Final output sent to browser
DEBUG - 2020-04-09 22:26:58 --> Total execution time: 0.6180
INFO - 2020-04-09 22:27:00 --> Config Class Initialized
INFO - 2020-04-09 22:27:00 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:27:00 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:27:00 --> Utf8 Class Initialized
INFO - 2020-04-09 22:27:00 --> URI Class Initialized
INFO - 2020-04-09 22:27:00 --> Router Class Initialized
INFO - 2020-04-09 22:27:00 --> Output Class Initialized
INFO - 2020-04-09 22:27:00 --> Security Class Initialized
DEBUG - 2020-04-09 22:27:00 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:27:00 --> Input Class Initialized
INFO - 2020-04-09 22:27:00 --> Language Class Initialized
INFO - 2020-04-09 22:27:00 --> Loader Class Initialized
INFO - 2020-04-09 22:27:00 --> Helper loaded: url_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: file_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: site_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:27:00 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:27:01 --> Database Driver Class Initialized
INFO - 2020-04-09 22:27:01 --> Email Class Initialized
DEBUG - 2020-04-09 22:27:01 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:27:01 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:27:01 --> Helper loaded: form_helper
INFO - 2020-04-09 22:27:01 --> Form Validation Class Initialized
INFO - 2020-04-09 22:27:01 --> Controller Class Initialized
INFO - 2020-04-09 22:27:01 --> Model "Common_model" initialized
INFO - 2020-04-09 22:27:01 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:27:01 --> Model "Accounts_model" initialized
INFO - 2020-04-09 22:27:01 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:27:01 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:27:01 --> Database Driver Class Initialized
INFO - 2020-04-09 22:27:01 --> Helper loaded: language_helper
INFO - 2020-04-09 22:27:01 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:27:01 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 22:27:01 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 22:27:01 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:01 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:27:01 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:27:01 --> Final output sent to browser
DEBUG - 2020-04-09 22:27:01 --> Total execution time: 0.6205
INFO - 2020-04-09 22:27:02 --> Config Class Initialized
INFO - 2020-04-09 22:27:02 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:27:02 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:27:02 --> Utf8 Class Initialized
INFO - 2020-04-09 22:27:02 --> URI Class Initialized
INFO - 2020-04-09 22:27:02 --> Router Class Initialized
INFO - 2020-04-09 22:27:02 --> Output Class Initialized
INFO - 2020-04-09 22:27:02 --> Security Class Initialized
DEBUG - 2020-04-09 22:27:02 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:27:02 --> Input Class Initialized
INFO - 2020-04-09 22:27:02 --> Language Class Initialized
INFO - 2020-04-09 22:27:02 --> Loader Class Initialized
INFO - 2020-04-09 22:27:02 --> Helper loaded: url_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: file_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: site_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:27:02 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:27:03 --> Database Driver Class Initialized
INFO - 2020-04-09 22:27:03 --> Email Class Initialized
DEBUG - 2020-04-09 22:27:03 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:27:03 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:27:03 --> Helper loaded: form_helper
INFO - 2020-04-09 22:27:03 --> Form Validation Class Initialized
INFO - 2020-04-09 22:27:03 --> Controller Class Initialized
INFO - 2020-04-09 22:27:03 --> Model "Common_model" initialized
INFO - 2020-04-09 22:27:03 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:27:03 --> Model "Accounts_model" initialized
INFO - 2020-04-09 22:27:03 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:27:03 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:27:03 --> Database Driver Class Initialized
INFO - 2020-04-09 22:27:03 --> Helper loaded: language_helper
INFO - 2020-04-09 22:27:03 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:27:03 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 22:27:03 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 22:27:03 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:03 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:27:03 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:27:03 --> Final output sent to browser
DEBUG - 2020-04-09 22:27:03 --> Total execution time: 0.6406
INFO - 2020-04-09 22:27:11 --> Config Class Initialized
INFO - 2020-04-09 22:27:11 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:27:11 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:27:11 --> Utf8 Class Initialized
INFO - 2020-04-09 22:27:11 --> URI Class Initialized
INFO - 2020-04-09 22:27:11 --> Router Class Initialized
INFO - 2020-04-09 22:27:11 --> Output Class Initialized
INFO - 2020-04-09 22:27:11 --> Security Class Initialized
DEBUG - 2020-04-09 22:27:11 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:27:11 --> Input Class Initialized
INFO - 2020-04-09 22:27:11 --> Language Class Initialized
INFO - 2020-04-09 22:27:11 --> Loader Class Initialized
INFO - 2020-04-09 22:27:11 --> Helper loaded: url_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: file_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: site_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:27:11 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:27:11 --> Database Driver Class Initialized
INFO - 2020-04-09 22:27:11 --> Email Class Initialized
DEBUG - 2020-04-09 22:27:11 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:27:11 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:27:11 --> Helper loaded: form_helper
INFO - 2020-04-09 22:27:11 --> Form Validation Class Initialized
INFO - 2020-04-09 22:27:11 --> Controller Class Initialized
INFO - 2020-04-09 22:27:11 --> Model "Common_model" initialized
INFO - 2020-04-09 22:27:11 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:27:11 --> Model "Accounts_model" initialized
INFO - 2020-04-09 22:27:11 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:27:11 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:27:11 --> Database Driver Class Initialized
INFO - 2020-04-09 22:27:11 --> Helper loaded: language_helper
INFO - 2020-04-09 22:27:11 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:27:11 --> This is cost of good soldSELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=68  AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1  AND AC_TAVDtl.BranchAutoId=1 GROUP BY  AC_TAVDtl.BranchAutoId,
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
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code
ERROR - 2020-04-09 22:27:11 --> Could not find the language line "Less_Expance"
ERROR - 2020-04-09 22:27:11 --> this is get_sales_revenue SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
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
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=68 AND
  AC_TAVMst.Accounts_Voucher_Date < '2020-03-07' and AC_TAVDtl.IsActive=1
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
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=68 AND  AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07' AND  AC_TAVMst.Accounts_Voucher_Date <= '2020-03-07' and AC_TAVDtl.IsActive=1

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
INFO - 2020-04-09 22:27:11 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/incomeStatement.php
ERROR - 2020-04-09 22:27:11 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:27:11 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:27:12 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:27:12 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:27:12 --> Final output sent to browser
DEBUG - 2020-04-09 22:27:12 --> Total execution time: 0.9033
INFO - 2020-04-09 22:28:24 --> Config Class Initialized
INFO - 2020-04-09 22:28:24 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:28:24 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:28:24 --> Utf8 Class Initialized
INFO - 2020-04-09 22:28:24 --> URI Class Initialized
INFO - 2020-04-09 22:28:24 --> Router Class Initialized
INFO - 2020-04-09 22:28:24 --> Output Class Initialized
INFO - 2020-04-09 22:28:24 --> Security Class Initialized
DEBUG - 2020-04-09 22:28:24 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:28:24 --> Input Class Initialized
INFO - 2020-04-09 22:28:24 --> Language Class Initialized
INFO - 2020-04-09 22:28:24 --> Loader Class Initialized
INFO - 2020-04-09 22:28:24 --> Helper loaded: url_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: file_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: site_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:28:24 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:28:24 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:24 --> Email Class Initialized
DEBUG - 2020-04-09 22:28:24 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:28:24 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:28:24 --> Helper loaded: form_helper
INFO - 2020-04-09 22:28:24 --> Form Validation Class Initialized
INFO - 2020-04-09 22:28:24 --> Controller Class Initialized
INFO - 2020-04-09 22:28:24 --> Model "Common_model" initialized
INFO - 2020-04-09 22:28:24 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:28:24 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:28:24 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:28:24 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:24 --> Helper loaded: language_helper
INFO - 2020-04-09 22:28:24 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:28:24 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/saleList.php
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:25 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:28:25 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:28:25 --> Final output sent to browser
DEBUG - 2020-04-09 22:28:25 --> Total execution time: 0.7654
INFO - 2020-04-09 22:28:26 --> Config Class Initialized
INFO - 2020-04-09 22:28:26 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:28:26 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:28:26 --> Utf8 Class Initialized
INFO - 2020-04-09 22:28:26 --> URI Class Initialized
INFO - 2020-04-09 22:28:26 --> Router Class Initialized
INFO - 2020-04-09 22:28:26 --> Output Class Initialized
INFO - 2020-04-09 22:28:26 --> Security Class Initialized
DEBUG - 2020-04-09 22:28:26 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:28:26 --> Input Class Initialized
INFO - 2020-04-09 22:28:26 --> Language Class Initialized
INFO - 2020-04-09 22:28:26 --> Loader Class Initialized
INFO - 2020-04-09 22:28:26 --> Helper loaded: url_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: file_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: site_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:28:26 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:28:26 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:26 --> Email Class Initialized
DEBUG - 2020-04-09 22:28:26 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:28:26 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:28:26 --> Helper loaded: form_helper
INFO - 2020-04-09 22:28:26 --> Form Validation Class Initialized
INFO - 2020-04-09 22:28:26 --> Controller Class Initialized
INFO - 2020-04-09 22:28:26 --> Model "Common_model" initialized
INFO - 2020-04-09 22:28:26 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:28:26 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:28:26 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:28:26 --> Model "ServerFilterModel" initialized
INFO - 2020-04-09 22:28:26 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:26 --> Helper loaded: language_helper
INFO - 2020-04-09 22:28:26 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:28:26 --> Final output sent to browser
DEBUG - 2020-04-09 22:28:26 --> Total execution time: 0.5364
INFO - 2020-04-09 22:28:28 --> Config Class Initialized
INFO - 2020-04-09 22:28:28 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:28:28 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:28:28 --> Utf8 Class Initialized
INFO - 2020-04-09 22:28:28 --> URI Class Initialized
INFO - 2020-04-09 22:28:28 --> Router Class Initialized
INFO - 2020-04-09 22:28:28 --> Output Class Initialized
INFO - 2020-04-09 22:28:28 --> Security Class Initialized
DEBUG - 2020-04-09 22:28:28 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:28:28 --> Input Class Initialized
INFO - 2020-04-09 22:28:28 --> Language Class Initialized
INFO - 2020-04-09 22:28:28 --> Loader Class Initialized
INFO - 2020-04-09 22:28:28 --> Helper loaded: url_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: file_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: site_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:28:28 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:28:28 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:28 --> Email Class Initialized
DEBUG - 2020-04-09 22:28:28 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:28:28 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:28:28 --> Helper loaded: form_helper
INFO - 2020-04-09 22:28:28 --> Form Validation Class Initialized
INFO - 2020-04-09 22:28:28 --> Controller Class Initialized
INFO - 2020-04-09 22:28:28 --> Model "Common_model" initialized
INFO - 2020-04-09 22:28:28 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:28:28 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:28:28 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:28:28 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:28 --> Helper loaded: language_helper
INFO - 2020-04-09 22:28:28 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Branch"
ERROR - 2020-04-09 22:28:28 --> Severity: Warning --> Invalid argument supplied for foreach() H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor\sales\salesInvoiceLpg\sales_view_final.php 230
ERROR - 2020-04-09 22:28:28 --> Severity: Warning --> Invalid argument supplied for foreach() H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor\sales\salesInvoiceLpg\sales_view_final.php 230
INFO - 2020-04-09 22:28:28 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/sales_view_final.php
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:28 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:28:29 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:28:29 --> Final output sent to browser
DEBUG - 2020-04-09 22:28:29 --> Total execution time: 0.8870
INFO - 2020-04-09 22:28:29 --> Config Class Initialized
INFO - 2020-04-09 22:28:29 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:28:29 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:28:29 --> Utf8 Class Initialized
INFO - 2020-04-09 22:28:38 --> Config Class Initialized
INFO - 2020-04-09 22:28:38 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:28:38 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:28:38 --> Utf8 Class Initialized
INFO - 2020-04-09 22:28:38 --> URI Class Initialized
INFO - 2020-04-09 22:28:38 --> Router Class Initialized
INFO - 2020-04-09 22:28:38 --> Output Class Initialized
INFO - 2020-04-09 22:28:38 --> Security Class Initialized
DEBUG - 2020-04-09 22:28:38 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:28:38 --> Input Class Initialized
INFO - 2020-04-09 22:28:38 --> Language Class Initialized
INFO - 2020-04-09 22:28:38 --> Loader Class Initialized
INFO - 2020-04-09 22:28:38 --> Helper loaded: url_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: file_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: site_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:28:38 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:28:38 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:38 --> Email Class Initialized
DEBUG - 2020-04-09 22:28:38 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:28:38 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:28:38 --> Helper loaded: form_helper
INFO - 2020-04-09 22:28:38 --> Form Validation Class Initialized
INFO - 2020-04-09 22:28:38 --> Controller Class Initialized
INFO - 2020-04-09 22:28:38 --> Model "Common_model" initialized
INFO - 2020-04-09 22:28:38 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:28:38 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:28:38 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:28:38 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 22:28:38 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:38 --> Helper loaded: language_helper
INFO - 2020-04-09 22:28:38 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Si"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "JV_No"
INFO - 2020-04-09 22:28:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/journal/journalVoucher.php
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:38 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:28:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:28:38 --> Final output sent to browser
DEBUG - 2020-04-09 22:28:38 --> Total execution time: 0.8016
INFO - 2020-04-09 22:28:39 --> Config Class Initialized
INFO - 2020-04-09 22:28:39 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:28:39 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:28:39 --> Utf8 Class Initialized
INFO - 2020-04-09 22:28:39 --> URI Class Initialized
INFO - 2020-04-09 22:28:39 --> Router Class Initialized
INFO - 2020-04-09 22:28:39 --> Output Class Initialized
INFO - 2020-04-09 22:28:39 --> Security Class Initialized
DEBUG - 2020-04-09 22:28:39 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:28:39 --> Input Class Initialized
INFO - 2020-04-09 22:28:39 --> Language Class Initialized
INFO - 2020-04-09 22:28:39 --> Loader Class Initialized
INFO - 2020-04-09 22:28:40 --> Helper loaded: url_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: file_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: site_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:28:40 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:28:40 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:40 --> Email Class Initialized
DEBUG - 2020-04-09 22:28:40 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:28:40 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:28:40 --> Helper loaded: form_helper
INFO - 2020-04-09 22:28:40 --> Form Validation Class Initialized
INFO - 2020-04-09 22:28:40 --> Controller Class Initialized
INFO - 2020-04-09 22:28:40 --> Model "Common_model" initialized
INFO - 2020-04-09 22:28:40 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:28:40 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:28:40 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:28:40 --> Model "ServerFilterModel" initialized
INFO - 2020-04-09 22:28:40 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:40 --> Helper loaded: language_helper
INFO - 2020-04-09 22:28:40 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:28:40 --> Final output sent to browser
DEBUG - 2020-04-09 22:28:40 --> Total execution time: 0.5023
INFO - 2020-04-09 22:28:43 --> Config Class Initialized
INFO - 2020-04-09 22:28:43 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:28:43 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:28:43 --> Utf8 Class Initialized
INFO - 2020-04-09 22:28:43 --> URI Class Initialized
INFO - 2020-04-09 22:28:43 --> Router Class Initialized
INFO - 2020-04-09 22:28:43 --> Output Class Initialized
INFO - 2020-04-09 22:28:43 --> Security Class Initialized
DEBUG - 2020-04-09 22:28:43 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:28:43 --> Input Class Initialized
INFO - 2020-04-09 22:28:43 --> Language Class Initialized
INFO - 2020-04-09 22:28:43 --> Loader Class Initialized
INFO - 2020-04-09 22:28:43 --> Helper loaded: url_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: file_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: site_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:28:43 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:28:43 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:43 --> Email Class Initialized
DEBUG - 2020-04-09 22:28:43 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:28:43 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:28:43 --> Helper loaded: form_helper
INFO - 2020-04-09 22:28:43 --> Form Validation Class Initialized
INFO - 2020-04-09 22:28:43 --> Controller Class Initialized
INFO - 2020-04-09 22:28:43 --> Model "Common_model" initialized
INFO - 2020-04-09 22:28:43 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:28:43 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:28:43 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:28:43 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 22:28:43 --> Database Driver Class Initialized
INFO - 2020-04-09 22:28:43 --> Helper loaded: language_helper
INFO - 2020-04-09 22:28:43 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Journal_Voucher_List"
INFO - 2020-04-09 22:28:43 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/journal/journalVoucherView.php
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:28:43 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:28:43 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:28:43 --> Final output sent to browser
DEBUG - 2020-04-09 22:28:43 --> Total execution time: 0.7332
INFO - 2020-04-09 22:29:37 --> Config Class Initialized
INFO - 2020-04-09 22:29:37 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:29:37 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:29:37 --> Utf8 Class Initialized
INFO - 2020-04-09 22:29:37 --> URI Class Initialized
INFO - 2020-04-09 22:29:37 --> Router Class Initialized
INFO - 2020-04-09 22:29:37 --> Output Class Initialized
INFO - 2020-04-09 22:29:37 --> Security Class Initialized
DEBUG - 2020-04-09 22:29:37 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:29:37 --> Input Class Initialized
INFO - 2020-04-09 22:29:37 --> Language Class Initialized
INFO - 2020-04-09 22:29:37 --> Loader Class Initialized
INFO - 2020-04-09 22:29:37 --> Helper loaded: url_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: file_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: site_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:29:37 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:29:37 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:37 --> Email Class Initialized
DEBUG - 2020-04-09 22:29:37 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:29:37 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:29:37 --> Helper loaded: form_helper
INFO - 2020-04-09 22:29:37 --> Form Validation Class Initialized
INFO - 2020-04-09 22:29:37 --> Controller Class Initialized
INFO - 2020-04-09 22:29:37 --> Model "Common_model" initialized
INFO - 2020-04-09 22:29:37 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:29:37 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:29:37 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:29:37 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:37 --> Helper loaded: language_helper
INFO - 2020-04-09 22:29:37 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:29:37 --> Helper loaded: sales_invoice_no_helper
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Refill_Stock"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Refill_LAF_Refill_12KG_Only_Gas_800Tk/Btl_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Refill_Refill_BM_12Kg_600Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Empty_Cylinder_Transfer"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Transfer_Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Transfer_Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Cash_with_Mr._Rahman"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "002548_new_[_CID20010003_]"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Helal_Uddin_[_CID20010004_]"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Helal_Uddin_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "002548_new_[_CID20010003_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Equity_Capital"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Capital_Loan_OWNER_-1"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "LAF_GAS_Supplier_LPG_12KG_[_]"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Bank_CA_#_IBBL_3206"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Monthly_Expense_Provision_#_2020"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Sales_Revenue"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Sales_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Loader's_Payable_Sales"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Cost_of_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Cost_of_Goods_Product-"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Loading_&_Wages"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Common_Loading_&_Wages"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Common_Transportation"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Marketing_Expenses"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Marketing_Tour_Allowance_#_2020"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Selling_&_Distribution_Expenses"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "SK_Courier_Bill_(Outward)_#_2020"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Financial_Expenses"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Bank_Chage_#_IBBL_#_3206"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Office_Rent_#_Hasan_Plaza_#_2020"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Bank_&_Financial_Expenses_(IBBL#_2145)"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Difference_In_Opening_Balance"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Bank_A/C"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Price"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Chq_Amount"
INFO - 2020-04-09 22:29:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/sale_add.php
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:29:38 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:29:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:29:38 --> Final output sent to browser
DEBUG - 2020-04-09 22:29:38 --> Total execution time: 1.0475
INFO - 2020-04-09 22:29:43 --> Config Class Initialized
INFO - 2020-04-09 22:29:43 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:29:43 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:29:43 --> Utf8 Class Initialized
INFO - 2020-04-09 22:29:43 --> URI Class Initialized
INFO - 2020-04-09 22:29:43 --> Router Class Initialized
INFO - 2020-04-09 22:29:43 --> Output Class Initialized
INFO - 2020-04-09 22:29:43 --> Security Class Initialized
DEBUG - 2020-04-09 22:29:43 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:29:43 --> Input Class Initialized
INFO - 2020-04-09 22:29:43 --> Language Class Initialized
INFO - 2020-04-09 22:29:43 --> Loader Class Initialized
INFO - 2020-04-09 22:29:43 --> Helper loaded: url_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: file_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: site_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:29:43 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:29:43 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:43 --> Email Class Initialized
DEBUG - 2020-04-09 22:29:43 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:29:43 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:29:43 --> Helper loaded: form_helper
INFO - 2020-04-09 22:29:43 --> Form Validation Class Initialized
INFO - 2020-04-09 22:29:43 --> Controller Class Initialized
INFO - 2020-04-09 22:29:43 --> Model "Common_model" initialized
INFO - 2020-04-09 22:29:43 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:29:43 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:29:43 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:29:43 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:43 --> Helper loaded: language_helper
INFO - 2020-04-09 22:29:43 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:29:47 --> Config Class Initialized
INFO - 2020-04-09 22:29:47 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:29:47 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:29:47 --> Utf8 Class Initialized
INFO - 2020-04-09 22:29:47 --> URI Class Initialized
INFO - 2020-04-09 22:29:47 --> Router Class Initialized
INFO - 2020-04-09 22:29:47 --> Output Class Initialized
INFO - 2020-04-09 22:29:47 --> Security Class Initialized
DEBUG - 2020-04-09 22:29:47 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:29:47 --> Input Class Initialized
INFO - 2020-04-09 22:29:47 --> Language Class Initialized
INFO - 2020-04-09 22:29:47 --> Loader Class Initialized
INFO - 2020-04-09 22:29:47 --> Helper loaded: url_helper
INFO - 2020-04-09 22:29:47 --> Helper loaded: file_helper
INFO - 2020-04-09 22:29:47 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:29:47 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:29:47 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:29:47 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:29:48 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:29:48 --> Helper loaded: site_helper
INFO - 2020-04-09 22:29:48 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:29:48 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:29:48 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:48 --> Email Class Initialized
DEBUG - 2020-04-09 22:29:48 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:29:48 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:29:48 --> Helper loaded: form_helper
INFO - 2020-04-09 22:29:48 --> Form Validation Class Initialized
INFO - 2020-04-09 22:29:48 --> Controller Class Initialized
INFO - 2020-04-09 22:29:48 --> Model "Common_model" initialized
INFO - 2020-04-09 22:29:48 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:29:48 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:29:48 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:29:48 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:48 --> Helper loaded: language_helper
INFO - 2020-04-09 22:29:48 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:29:48 --> Helper loaded: sales_invoice_no_helper
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Refill_Stock"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Refill_LAF_Refill_12KG_Only_Gas_800Tk/Btl_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Refill_Refill_BM_12Kg_600Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Empty_Cylinder_Transfer"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Transfer_Empty_Cylinder_LAF_Empty_12KG_1000Tk/Pcs_Pcs_Laugfs_Gas"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Transfer_Empty_Cylinder_BM_Empty_Cyl_12Kg_400Tk/Pcs_Pcs_Bm"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Cash_with_Mr._Rahman"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "002548_new_[_CID20010003_]"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Customer_LFG_12KG_2000Tk/Pkg_[_CID20010001_]"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "CUSTOMER_NEW_LFG_12KG_2000Tk/Pack_[_CID20010002_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Monir_Hossen_[_CID20010004_]"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Monir_Hossen_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Helal_Uddin_[_CID20010004_]"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Helal_Uddin_[_CID20010004_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "002548_new_[_CID20010003_]_Empty_Cylinder_Due"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Equity_Capital"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Capital_Loan_OWNER_-1"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "LAF_GAS_Supplier_LPG_12KG_[_]"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Bank_CA_#_IBBL_3206"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Monthly_Expense_Provision_#_2020"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Sales_Revenue"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Sales_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Loader's_Payable_Sales"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Cost_of_Empty_Cylinder_With_Refill"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Cost_of_Goods_Product-"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Loading_&_Wages"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Common_Loading_&_Wages"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Common_Transportation"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Marketing_Expenses"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Marketing_Tour_Allowance_#_2020"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Selling_&_Distribution_Expenses"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "SK_Courier_Bill_(Outward)_#_2020"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Financial_Expenses"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Bank_Chage_#_IBBL_#_3206"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Office_Rent_#_Hasan_Plaza_#_2020"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Bank_&_Financial_Expenses_(IBBL#_2145)"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Difference_In_Opening_Balance"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Bank_A/C"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Price"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Chq_Amount"
INFO - 2020-04-09 22:29:48 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/sale_add.php
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:29:48 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:29:48 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:29:48 --> Final output sent to browser
DEBUG - 2020-04-09 22:29:48 --> Total execution time: 1.2003
INFO - 2020-04-09 22:29:53 --> Config Class Initialized
INFO - 2020-04-09 22:29:53 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:29:54 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:29:54 --> Utf8 Class Initialized
INFO - 2020-04-09 22:29:54 --> URI Class Initialized
INFO - 2020-04-09 22:29:54 --> Router Class Initialized
INFO - 2020-04-09 22:29:54 --> Output Class Initialized
INFO - 2020-04-09 22:29:54 --> Security Class Initialized
DEBUG - 2020-04-09 22:29:54 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:29:54 --> Input Class Initialized
INFO - 2020-04-09 22:29:54 --> Language Class Initialized
INFO - 2020-04-09 22:29:54 --> Loader Class Initialized
INFO - 2020-04-09 22:29:54 --> Helper loaded: url_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: file_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: site_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:29:54 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:29:54 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:54 --> Email Class Initialized
DEBUG - 2020-04-09 22:29:54 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:29:54 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:29:54 --> Helper loaded: form_helper
INFO - 2020-04-09 22:29:54 --> Form Validation Class Initialized
INFO - 2020-04-09 22:29:54 --> Controller Class Initialized
INFO - 2020-04-09 22:29:54 --> Model "Common_model" initialized
INFO - 2020-04-09 22:29:54 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:29:54 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:29:54 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:29:54 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:54 --> Helper loaded: language_helper
INFO - 2020-04-09 22:29:54 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:29:55 --> Config Class Initialized
INFO - 2020-04-09 22:29:55 --> Config Class Initialized
INFO - 2020-04-09 22:29:55 --> Hooks Class Initialized
INFO - 2020-04-09 22:29:55 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:29:55 --> UTF-8 Support Enabled
DEBUG - 2020-04-09 22:29:55 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:29:55 --> Utf8 Class Initialized
INFO - 2020-04-09 22:29:55 --> Utf8 Class Initialized
INFO - 2020-04-09 22:29:55 --> URI Class Initialized
INFO - 2020-04-09 22:29:55 --> URI Class Initialized
INFO - 2020-04-09 22:29:55 --> Router Class Initialized
INFO - 2020-04-09 22:29:55 --> Router Class Initialized
INFO - 2020-04-09 22:29:55 --> Output Class Initialized
INFO - 2020-04-09 22:29:55 --> Output Class Initialized
INFO - 2020-04-09 22:29:55 --> Security Class Initialized
INFO - 2020-04-09 22:29:55 --> Security Class Initialized
DEBUG - 2020-04-09 22:29:55 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2020-04-09 22:29:55 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:29:55 --> Input Class Initialized
INFO - 2020-04-09 22:29:55 --> Input Class Initialized
INFO - 2020-04-09 22:29:55 --> Language Class Initialized
INFO - 2020-04-09 22:29:55 --> Language Class Initialized
INFO - 2020-04-09 22:29:55 --> Loader Class Initialized
INFO - 2020-04-09 22:29:55 --> Loader Class Initialized
INFO - 2020-04-09 22:29:55 --> Helper loaded: url_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: url_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: file_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: file_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: site_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: site_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:29:55 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:29:55 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:55 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:55 --> Email Class Initialized
DEBUG - 2020-04-09 22:29:55 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:29:55 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:29:55 --> Helper loaded: form_helper
INFO - 2020-04-09 22:29:55 --> Email Class Initialized
INFO - 2020-04-09 22:29:55 --> Form Validation Class Initialized
DEBUG - 2020-04-09 22:29:55 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:29:55 --> Controller Class Initialized
INFO - 2020-04-09 22:29:55 --> Model "Common_model" initialized
INFO - 2020-04-09 22:29:55 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:29:55 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:29:55 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:29:55 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:55 --> Helper loaded: language_helper
INFO - 2020-04-09 22:29:55 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:29:55 --> Final output sent to browser
DEBUG - 2020-04-09 22:29:55 --> Total execution time: 0.4176
INFO - 2020-04-09 22:29:55 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:29:55 --> Helper loaded: form_helper
INFO - 2020-04-09 22:29:55 --> Form Validation Class Initialized
INFO - 2020-04-09 22:29:55 --> Controller Class Initialized
INFO - 2020-04-09 22:29:55 --> Model "Common_model" initialized
INFO - 2020-04-09 22:29:55 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:29:55 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:29:55 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:29:55 --> Database Driver Class Initialized
INFO - 2020-04-09 22:29:55 --> Helper loaded: language_helper
INFO - 2020-04-09 22:29:55 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:29:55 --> Final output sent to browser
DEBUG - 2020-04-09 22:29:55 --> Total execution time: 0.5446
INFO - 2020-04-09 22:30:05 --> Config Class Initialized
INFO - 2020-04-09 22:30:05 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:05 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:05 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:05 --> URI Class Initialized
INFO - 2020-04-09 22:30:05 --> Router Class Initialized
INFO - 2020-04-09 22:30:05 --> Output Class Initialized
INFO - 2020-04-09 22:30:05 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:05 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:05 --> Input Class Initialized
INFO - 2020-04-09 22:30:05 --> Language Class Initialized
INFO - 2020-04-09 22:30:06 --> Loader Class Initialized
INFO - 2020-04-09 22:30:06 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:06 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:06 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:06 --> Email Class Initialized
DEBUG - 2020-04-09 22:30:06 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:06 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:06 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:06 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:06 --> Controller Class Initialized
INFO - 2020-04-09 22:30:06 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:06 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:06 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:06 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:06 --> Model "Purchases_Model" initialized
INFO - 2020-04-09 22:30:06 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:30:06 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:06 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:06 --> Final output sent to browser
DEBUG - 2020-04-09 22:30:06 --> Total execution time: 0.5321
INFO - 2020-04-09 22:30:15 --> Config Class Initialized
INFO - 2020-04-09 22:30:15 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:15 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:15 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:15 --> URI Class Initialized
INFO - 2020-04-09 22:30:15 --> Router Class Initialized
INFO - 2020-04-09 22:30:15 --> Output Class Initialized
INFO - 2020-04-09 22:30:15 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:15 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:15 --> Input Class Initialized
INFO - 2020-04-09 22:30:15 --> Language Class Initialized
INFO - 2020-04-09 22:30:15 --> Config Class Initialized
INFO - 2020-04-09 22:30:15 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:15 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:15 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:15 --> URI Class Initialized
INFO - 2020-04-09 22:30:15 --> Router Class Initialized
INFO - 2020-04-09 22:30:15 --> Output Class Initialized
INFO - 2020-04-09 22:30:15 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:15 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:15 --> Input Class Initialized
INFO - 2020-04-09 22:30:15 --> Loader Class Initialized
INFO - 2020-04-09 22:30:15 --> Language Class Initialized
INFO - 2020-04-09 22:30:15 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:15 --> Loader Class Initialized
INFO - 2020-04-09 22:30:15 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:15 --> Config Class Initialized
INFO - 2020-04-09 22:30:15 --> Hooks Class Initialized
INFO - 2020-04-09 22:30:15 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: branch_dropdown_helper
DEBUG - 2020-04-09 22:30:15 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:15 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:15 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:15 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:15 --> URI Class Initialized
INFO - 2020-04-09 22:30:15 --> Email Class Initialized
INFO - 2020-04-09 22:30:15 --> Database Driver Class Initialized
DEBUG - 2020-04-09 22:30:15 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:15 --> Email Class Initialized
INFO - 2020-04-09 22:30:15 --> Session: Class initialized using 'files' driver.
DEBUG - 2020-04-09 22:30:15 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:15 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:15 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:15 --> Router Class Initialized
INFO - 2020-04-09 22:30:15 --> Controller Class Initialized
INFO - 2020-04-09 22:30:15 --> Output Class Initialized
INFO - 2020-04-09 22:30:15 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:15 --> Security Class Initialized
INFO - 2020-04-09 22:30:15 --> Model "Finane_Model" initialized
DEBUG - 2020-04-09 22:30:15 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:15 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:15 --> Input Class Initialized
INFO - 2020-04-09 22:30:15 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:15 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:15 --> Language Class Initialized
INFO - 2020-04-09 22:30:15 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:15 --> Loader Class Initialized
INFO - 2020-04-09 22:30:15 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:15 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:30:15 --> Helper loaded: file_helper
ERROR - 2020-04-09 22:30:15 --> Query error: Unknown column 'NaN' in 'where clause' - Invalid query: SELECT
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
LEFT JOIN customertype on customertype.type_id=customer.customerType  WHERE 1=1 AND Tol.customer_id=NaN  /*HAVING Tol.empty_cylinder_id !=0 AND product_id !=0 */ ORDER BY customer.customerName,product.productName,brand.brandName
)Tol1
INFO - 2020-04-09 22:30:15 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:15 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: db_dinamic_helper
ERROR - 2020-04-09 22:30:16 --> Severity: Error --> Call to a member function row() on boolean H:\XAMPP\codeigniter\htdocs\masterProject\application\models\Sales_Model.php 463
INFO - 2020-04-09 22:30:16 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:16 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:16 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:16 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:16 --> Controller Class Initialized
INFO - 2020-04-09 22:30:16 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:16 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:16 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:16 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:16 --> Email Class Initialized
DEBUG - 2020-04-09 22:30:16 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:16 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:16 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:16 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:16 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:16 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:30:16 --> Query error: Unknown column 'NaN' in 'where clause' - Invalid query: SELECT
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
LEFT JOIN customertype on customertype.type_id=customer.customerType  WHERE 1=1 AND Tol.customer_id=NaN  /*HAVING Tol.empty_cylinder_id !=0 AND product_id !=0 */ ORDER BY customer.customerName,product.productName,brand.brandName
)Tol1
ERROR - 2020-04-09 22:30:16 --> Severity: Error --> Call to a member function row() on boolean H:\XAMPP\codeigniter\htdocs\masterProject\application\models\Sales_Model.php 463
INFO - 2020-04-09 22:30:16 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:16 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:16 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:16 --> Controller Class Initialized
INFO - 2020-04-09 22:30:16 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:16 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:16 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:16 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:16 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:16 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:16 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:30:16 --> Query error: Unknown column 'NaN' in 'where clause' - Invalid query: SELECT
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
LEFT JOIN customertype on customertype.type_id=customer.customerType  WHERE 1=1 AND Tol.customer_id=NaN  /*HAVING Tol.empty_cylinder_id !=0 AND product_id !=0 */ ORDER BY customer.customerName,product.productName,brand.brandName
)Tol1
ERROR - 2020-04-09 22:30:16 --> Severity: Error --> Call to a member function row() on boolean H:\XAMPP\codeigniter\htdocs\masterProject\application\models\Sales_Model.php 463
INFO - 2020-04-09 22:30:16 --> Config Class Initialized
INFO - 2020-04-09 22:30:16 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:16 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:16 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:16 --> URI Class Initialized
INFO - 2020-04-09 22:30:16 --> Router Class Initialized
INFO - 2020-04-09 22:30:16 --> Output Class Initialized
INFO - 2020-04-09 22:30:16 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:16 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:16 --> Input Class Initialized
INFO - 2020-04-09 22:30:16 --> Language Class Initialized
INFO - 2020-04-09 22:30:16 --> Loader Class Initialized
INFO - 2020-04-09 22:30:16 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:16 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:16 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:16 --> Email Class Initialized
DEBUG - 2020-04-09 22:30:16 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:16 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:16 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:16 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:16 --> Controller Class Initialized
INFO - 2020-04-09 22:30:16 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:16 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:16 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:16 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:16 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:16 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:16 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:30:16 --> Query error: Unknown column 'NaN' in 'where clause' - Invalid query: SELECT
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
LEFT JOIN customertype on customertype.type_id=customer.customerType  WHERE 1=1 AND Tol.customer_id=NaN  /*HAVING Tol.empty_cylinder_id !=0 AND product_id !=0 */ ORDER BY customer.customerName,product.productName,brand.brandName
)Tol1
ERROR - 2020-04-09 22:30:16 --> Severity: Error --> Call to a member function row() on boolean H:\XAMPP\codeigniter\htdocs\masterProject\application\models\Sales_Model.php 463
INFO - 2020-04-09 22:30:28 --> Config Class Initialized
INFO - 2020-04-09 22:30:28 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:28 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:28 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:28 --> URI Class Initialized
INFO - 2020-04-09 22:30:28 --> Router Class Initialized
INFO - 2020-04-09 22:30:28 --> Output Class Initialized
INFO - 2020-04-09 22:30:28 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:28 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:28 --> Input Class Initialized
INFO - 2020-04-09 22:30:28 --> Language Class Initialized
INFO - 2020-04-09 22:30:28 --> Loader Class Initialized
INFO - 2020-04-09 22:30:29 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:29 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:29 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:29 --> Email Class Initialized
DEBUG - 2020-04-09 22:30:29 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:29 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:29 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:29 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:29 --> Controller Class Initialized
INFO - 2020-04-09 22:30:29 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:29 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:29 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:29 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:29 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:29 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:29 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:30:29 --> Final output sent to browser
DEBUG - 2020-04-09 22:30:29 --> Total execution time: 0.4198
INFO - 2020-04-09 22:30:47 --> Config Class Initialized
INFO - 2020-04-09 22:30:47 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:47 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:47 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:47 --> URI Class Initialized
INFO - 2020-04-09 22:30:47 --> Router Class Initialized
INFO - 2020-04-09 22:30:47 --> Output Class Initialized
INFO - 2020-04-09 22:30:47 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:47 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:47 --> Input Class Initialized
INFO - 2020-04-09 22:30:47 --> Language Class Initialized
INFO - 2020-04-09 22:30:47 --> Loader Class Initialized
INFO - 2020-04-09 22:30:47 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:47 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:47 --> Email Class Initialized
DEBUG - 2020-04-09 22:30:47 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:47 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:47 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:47 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:47 --> Controller Class Initialized
INFO - 2020-04-09 22:30:47 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:47 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:47 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:47 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:47 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:47 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:47 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 22:30:47 --> Helper loaded: sales_invoice_no_helper
INFO - 2020-04-09 22:30:47 --> Language file loaded: language/english/form_validation_lang.php
INFO - 2020-04-09 22:30:47 --> Config Class Initialized
INFO - 2020-04-09 22:30:47 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:47 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:47 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:47 --> URI Class Initialized
INFO - 2020-04-09 22:30:47 --> Router Class Initialized
INFO - 2020-04-09 22:30:47 --> Output Class Initialized
INFO - 2020-04-09 22:30:47 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:47 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:47 --> Input Class Initialized
INFO - 2020-04-09 22:30:47 --> Language Class Initialized
INFO - 2020-04-09 22:30:47 --> Loader Class Initialized
INFO - 2020-04-09 22:30:47 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:47 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:48 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:48 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:48 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:48 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:48 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:48 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:48 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:48 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:48 --> Email Class Initialized
DEBUG - 2020-04-09 22:30:48 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:48 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:48 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:48 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:48 --> Controller Class Initialized
INFO - 2020-04-09 22:30:48 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:48 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:48 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:48 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:48 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:48 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:48 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Branch"
ERROR - 2020-04-09 22:30:48 --> Severity: Warning --> Invalid argument supplied for foreach() H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor\sales\salesInvoiceLpg\sales_view_final.php 230
ERROR - 2020-04-09 22:30:48 --> Severity: Warning --> Invalid argument supplied for foreach() H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor\sales\salesInvoiceLpg\sales_view_final.php 230
INFO - 2020-04-09 22:30:48 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/sales/salesInvoiceLpg/sales_view_final.php
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:30:48 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:30:48 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:30:48 --> Final output sent to browser
DEBUG - 2020-04-09 22:30:48 --> Total execution time: 0.5425
INFO - 2020-04-09 22:30:48 --> Config Class Initialized
INFO - 2020-04-09 22:30:48 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:48 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:48 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:58 --> Config Class Initialized
INFO - 2020-04-09 22:30:58 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:30:58 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:30:58 --> Utf8 Class Initialized
INFO - 2020-04-09 22:30:58 --> URI Class Initialized
INFO - 2020-04-09 22:30:58 --> Router Class Initialized
INFO - 2020-04-09 22:30:58 --> Output Class Initialized
INFO - 2020-04-09 22:30:58 --> Security Class Initialized
DEBUG - 2020-04-09 22:30:58 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:30:58 --> Input Class Initialized
INFO - 2020-04-09 22:30:58 --> Language Class Initialized
INFO - 2020-04-09 22:30:58 --> Loader Class Initialized
INFO - 2020-04-09 22:30:58 --> Helper loaded: url_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: file_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: site_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:30:58 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:30:58 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:58 --> Email Class Initialized
DEBUG - 2020-04-09 22:30:58 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:30:58 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:30:58 --> Helper loaded: form_helper
INFO - 2020-04-09 22:30:58 --> Form Validation Class Initialized
INFO - 2020-04-09 22:30:58 --> Controller Class Initialized
INFO - 2020-04-09 22:30:58 --> Model "Common_model" initialized
INFO - 2020-04-09 22:30:58 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:30:58 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:30:58 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:30:58 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 22:30:58 --> Database Driver Class Initialized
INFO - 2020-04-09 22:30:58 --> Helper loaded: language_helper
INFO - 2020-04-09 22:30:58 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Journal_Voucher_List"
INFO - 2020-04-09 22:30:58 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/journal/journalVoucherView.php
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:30:58 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:30:58 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:30:58 --> Final output sent to browser
DEBUG - 2020-04-09 22:30:58 --> Total execution time: 0.5531
INFO - 2020-04-09 22:31:09 --> Config Class Initialized
INFO - 2020-04-09 22:31:09 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:31:09 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:31:09 --> Utf8 Class Initialized
INFO - 2020-04-09 22:31:09 --> URI Class Initialized
INFO - 2020-04-09 22:31:09 --> Router Class Initialized
INFO - 2020-04-09 22:31:09 --> Output Class Initialized
INFO - 2020-04-09 22:31:09 --> Security Class Initialized
DEBUG - 2020-04-09 22:31:09 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:31:09 --> Input Class Initialized
INFO - 2020-04-09 22:31:09 --> Language Class Initialized
INFO - 2020-04-09 22:31:09 --> Loader Class Initialized
INFO - 2020-04-09 22:31:09 --> Helper loaded: url_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: file_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: site_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:31:09 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:31:09 --> Database Driver Class Initialized
INFO - 2020-04-09 22:31:09 --> Email Class Initialized
DEBUG - 2020-04-09 22:31:09 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:31:09 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:31:09 --> Helper loaded: form_helper
INFO - 2020-04-09 22:31:09 --> Form Validation Class Initialized
INFO - 2020-04-09 22:31:09 --> Controller Class Initialized
INFO - 2020-04-09 22:31:09 --> Model "Common_model" initialized
INFO - 2020-04-09 22:31:09 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:31:09 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:31:09 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:31:09 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 22:31:09 --> Database Driver Class Initialized
INFO - 2020-04-09 22:31:09 --> Helper loaded: language_helper
INFO - 2020-04-09 22:31:09 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Journal_Voucher_List"
INFO - 2020-04-09 22:31:09 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/journal/journalVoucherView.php
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:31:09 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:31:10 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:31:10 --> Final output sent to browser
DEBUG - 2020-04-09 22:31:10 --> Total execution time: 0.5917
INFO - 2020-04-09 22:31:13 --> Config Class Initialized
INFO - 2020-04-09 22:31:13 --> Hooks Class Initialized
DEBUG - 2020-04-09 22:31:13 --> UTF-8 Support Enabled
INFO - 2020-04-09 22:31:13 --> Utf8 Class Initialized
INFO - 2020-04-09 22:31:13 --> URI Class Initialized
INFO - 2020-04-09 22:31:13 --> Router Class Initialized
INFO - 2020-04-09 22:31:13 --> Output Class Initialized
INFO - 2020-04-09 22:31:13 --> Security Class Initialized
DEBUG - 2020-04-09 22:31:13 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 22:31:13 --> Input Class Initialized
INFO - 2020-04-09 22:31:13 --> Language Class Initialized
INFO - 2020-04-09 22:31:13 --> Loader Class Initialized
INFO - 2020-04-09 22:31:13 --> Helper loaded: url_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: file_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: utility_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: unit_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: site_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 22:31:13 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 22:31:13 --> Database Driver Class Initialized
INFO - 2020-04-09 22:31:13 --> Email Class Initialized
DEBUG - 2020-04-09 22:31:13 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 22:31:13 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 22:31:13 --> Helper loaded: form_helper
INFO - 2020-04-09 22:31:13 --> Form Validation Class Initialized
INFO - 2020-04-09 22:31:13 --> Controller Class Initialized
INFO - 2020-04-09 22:31:13 --> Model "Common_model" initialized
INFO - 2020-04-09 22:31:13 --> Model "Finane_Model" initialized
INFO - 2020-04-09 22:31:13 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 22:31:13 --> Model "Sales_Model" initialized
INFO - 2020-04-09 22:31:13 --> Model "Dashboard_Model" initialized
INFO - 2020-04-09 22:31:13 --> Database Driver Class Initialized
INFO - 2020-04-09 22:31:13 --> Helper loaded: language_helper
INFO - 2020-04-09 22:31:13 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Journal_Voucher_List"
INFO - 2020-04-09 22:31:13 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/journal/journalVoucherView.php
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Employee"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "2019"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Report"
ERROR - 2020-04-09 22:31:13 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 22:31:13 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 22:31:13 --> Final output sent to browser
DEBUG - 2020-04-09 22:31:13 --> Total execution time: 0.5412
INFO - 2020-04-09 23:00:17 --> Config Class Initialized
INFO - 2020-04-09 23:00:17 --> Hooks Class Initialized
DEBUG - 2020-04-09 23:00:17 --> UTF-8 Support Enabled
INFO - 2020-04-09 23:00:17 --> Utf8 Class Initialized
INFO - 2020-04-09 23:00:17 --> URI Class Initialized
INFO - 2020-04-09 23:00:17 --> Router Class Initialized
INFO - 2020-04-09 23:00:17 --> Output Class Initialized
INFO - 2020-04-09 23:00:17 --> Security Class Initialized
DEBUG - 2020-04-09 23:00:17 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 23:00:17 --> Input Class Initialized
INFO - 2020-04-09 23:00:17 --> Language Class Initialized
INFO - 2020-04-09 23:00:18 --> Loader Class Initialized
INFO - 2020-04-09 23:00:18 --> Helper loaded: url_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: file_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: utility_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: unit_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: site_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 23:00:18 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 23:00:18 --> Database Driver Class Initialized
INFO - 2020-04-09 23:00:18 --> Email Class Initialized
DEBUG - 2020-04-09 23:00:18 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 23:00:18 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 23:00:18 --> Helper loaded: form_helper
INFO - 2020-04-09 23:00:18 --> Form Validation Class Initialized
INFO - 2020-04-09 23:00:18 --> Controller Class Initialized
INFO - 2020-04-09 23:00:18 --> Model "Common_model" initialized
INFO - 2020-04-09 23:00:18 --> Model "Finane_Model" initialized
INFO - 2020-04-09 23:00:18 --> Model "Accounts_model" initialized
INFO - 2020-04-09 23:00:18 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 23:00:18 --> Model "Sales_Model" initialized
INFO - 2020-04-09 23:00:18 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 23:00:18 --> Database Driver Class Initialized
INFO - 2020-04-09 23:00:18 --> Helper loaded: language_helper
INFO - 2020-04-09 23:00:18 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Commission"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 23:00:19 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 23:00:19 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Employee"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "2019"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:00:20 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 23:00:20 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 23:00:20 --> Final output sent to browser
DEBUG - 2020-04-09 23:00:20 --> Total execution time: 2.9689
INFO - 2020-04-09 23:00:25 --> Config Class Initialized
INFO - 2020-04-09 23:00:25 --> Hooks Class Initialized
DEBUG - 2020-04-09 23:00:25 --> UTF-8 Support Enabled
INFO - 2020-04-09 23:00:25 --> Utf8 Class Initialized
INFO - 2020-04-09 23:00:25 --> URI Class Initialized
INFO - 2020-04-09 23:00:25 --> Router Class Initialized
INFO - 2020-04-09 23:00:25 --> Output Class Initialized
INFO - 2020-04-09 23:00:25 --> Security Class Initialized
DEBUG - 2020-04-09 23:00:25 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 23:00:25 --> Input Class Initialized
INFO - 2020-04-09 23:00:25 --> Language Class Initialized
INFO - 2020-04-09 23:00:25 --> Loader Class Initialized
INFO - 2020-04-09 23:00:25 --> Helper loaded: url_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: file_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: utility_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: unit_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: site_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 23:00:25 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 23:00:25 --> Database Driver Class Initialized
INFO - 2020-04-09 23:00:25 --> Email Class Initialized
DEBUG - 2020-04-09 23:00:25 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 23:00:25 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 23:00:25 --> Helper loaded: form_helper
INFO - 2020-04-09 23:00:25 --> Form Validation Class Initialized
INFO - 2020-04-09 23:00:25 --> Controller Class Initialized
INFO - 2020-04-09 23:00:25 --> Model "Common_model" initialized
INFO - 2020-04-09 23:00:25 --> Model "Finane_Model" initialized
INFO - 2020-04-09 23:00:25 --> Model "Accounts_model" initialized
INFO - 2020-04-09 23:00:25 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 23:00:25 --> Model "Sales_Model" initialized
INFO - 2020-04-09 23:00:25 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 23:00:25 --> Database Driver Class Initialized
INFO - 2020-04-09 23:00:25 --> Helper loaded: language_helper
INFO - 2020-04-09 23:00:25 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Commission"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 23:00:25 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Employee"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "2019"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:00:25 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 23:00:25 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 23:00:25 --> Final output sent to browser
DEBUG - 2020-04-09 23:00:25 --> Total execution time: 0.9561
INFO - 2020-04-09 23:10:50 --> Config Class Initialized
INFO - 2020-04-09 23:10:50 --> Hooks Class Initialized
DEBUG - 2020-04-09 23:10:50 --> UTF-8 Support Enabled
INFO - 2020-04-09 23:10:50 --> Utf8 Class Initialized
INFO - 2020-04-09 23:10:50 --> URI Class Initialized
INFO - 2020-04-09 23:10:50 --> Router Class Initialized
INFO - 2020-04-09 23:10:50 --> Output Class Initialized
INFO - 2020-04-09 23:10:50 --> Security Class Initialized
DEBUG - 2020-04-09 23:10:50 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 23:10:50 --> Input Class Initialized
INFO - 2020-04-09 23:10:50 --> Language Class Initialized
INFO - 2020-04-09 23:10:50 --> Loader Class Initialized
INFO - 2020-04-09 23:10:50 --> Helper loaded: url_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: file_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: utility_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: unit_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: site_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 23:10:50 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 23:10:50 --> Database Driver Class Initialized
INFO - 2020-04-09 23:10:50 --> Email Class Initialized
DEBUG - 2020-04-09 23:10:50 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 23:10:50 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 23:10:50 --> Helper loaded: form_helper
INFO - 2020-04-09 23:10:50 --> Form Validation Class Initialized
INFO - 2020-04-09 23:10:50 --> Controller Class Initialized
INFO - 2020-04-09 23:10:50 --> Model "Common_model" initialized
INFO - 2020-04-09 23:10:50 --> Model "Finane_Model" initialized
INFO - 2020-04-09 23:10:50 --> Model "Accounts_model" initialized
INFO - 2020-04-09 23:10:50 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 23:10:50 --> Model "Sales_Model" initialized
INFO - 2020-04-09 23:10:50 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 23:10:50 --> Database Driver Class Initialized
INFO - 2020-04-09 23:10:50 --> Helper loaded: language_helper
INFO - 2020-04-09 23:10:50 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 23:16:33 --> Config Class Initialized
INFO - 2020-04-09 23:16:33 --> Hooks Class Initialized
DEBUG - 2020-04-09 23:16:33 --> UTF-8 Support Enabled
INFO - 2020-04-09 23:16:33 --> Utf8 Class Initialized
INFO - 2020-04-09 23:16:33 --> URI Class Initialized
INFO - 2020-04-09 23:16:33 --> Router Class Initialized
INFO - 2020-04-09 23:16:33 --> Output Class Initialized
INFO - 2020-04-09 23:16:33 --> Security Class Initialized
DEBUG - 2020-04-09 23:16:33 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 23:16:33 --> Input Class Initialized
INFO - 2020-04-09 23:16:33 --> Language Class Initialized
INFO - 2020-04-09 23:16:33 --> Loader Class Initialized
INFO - 2020-04-09 23:16:33 --> Helper loaded: url_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: file_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: utility_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: unit_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: site_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 23:16:33 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 23:16:33 --> Database Driver Class Initialized
INFO - 2020-04-09 23:16:33 --> Email Class Initialized
DEBUG - 2020-04-09 23:16:33 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 23:16:33 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 23:16:33 --> Helper loaded: form_helper
INFO - 2020-04-09 23:16:33 --> Form Validation Class Initialized
INFO - 2020-04-09 23:16:33 --> Controller Class Initialized
INFO - 2020-04-09 23:16:33 --> Model "Common_model" initialized
INFO - 2020-04-09 23:16:33 --> Model "Finane_Model" initialized
INFO - 2020-04-09 23:16:33 --> Model "Accounts_model" initialized
INFO - 2020-04-09 23:16:33 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 23:16:33 --> Model "Sales_Model" initialized
INFO - 2020-04-09 23:16:33 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 23:16:33 --> Database Driver Class Initialized
INFO - 2020-04-09 23:16:33 --> Helper loaded: language_helper
INFO - 2020-04-09 23:16:33 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-09 23:23:37 --> Config Class Initialized
INFO - 2020-04-09 23:23:37 --> Hooks Class Initialized
DEBUG - 2020-04-09 23:23:37 --> UTF-8 Support Enabled
INFO - 2020-04-09 23:23:37 --> Utf8 Class Initialized
INFO - 2020-04-09 23:23:37 --> URI Class Initialized
INFO - 2020-04-09 23:23:37 --> Router Class Initialized
INFO - 2020-04-09 23:23:37 --> Output Class Initialized
INFO - 2020-04-09 23:23:37 --> Security Class Initialized
DEBUG - 2020-04-09 23:23:37 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-09 23:23:37 --> Input Class Initialized
INFO - 2020-04-09 23:23:37 --> Language Class Initialized
INFO - 2020-04-09 23:23:37 --> Loader Class Initialized
INFO - 2020-04-09 23:23:37 --> Helper loaded: url_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: file_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: utility_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: unit_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: multi_language_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: site_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-09 23:23:37 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-09 23:23:37 --> Database Driver Class Initialized
INFO - 2020-04-09 23:23:37 --> Email Class Initialized
DEBUG - 2020-04-09 23:23:37 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-09 23:23:37 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-09 23:23:37 --> Helper loaded: form_helper
INFO - 2020-04-09 23:23:37 --> Form Validation Class Initialized
INFO - 2020-04-09 23:23:37 --> Controller Class Initialized
INFO - 2020-04-09 23:23:37 --> Model "Common_model" initialized
INFO - 2020-04-09 23:23:37 --> Model "Finane_Model" initialized
INFO - 2020-04-09 23:23:37 --> Model "Accounts_model" initialized
INFO - 2020-04-09 23:23:37 --> Model "Inventory_Model" initialized
INFO - 2020-04-09 23:23:37 --> Model "Sales_Model" initialized
INFO - 2020-04-09 23:23:37 --> Model "AccountReport_model" initialized
INFO - 2020-04-09 23:23:37 --> Database Driver Class Initialized
INFO - 2020-04-09 23:23:37 --> Helper loaded: language_helper
INFO - 2020-04-09 23:23:37 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-09 23:23:37 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-09 23:23:37 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-09 23:23:37 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-09 23:23:37 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-09 23:23:37 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Entertainment"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Commission"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-09 23:23:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Day_Book"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Employee"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "2019"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Report"
ERROR - 2020-04-09 23:23:38 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-09 23:23:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-09 23:23:38 --> Final output sent to browser
DEBUG - 2020-04-09 23:23:38 --> Total execution time: 1.3274
