<?php
if (isset($_POST['to_date'])):
    $to_date = $this->input->post('to_date');
    $end_date = date('Y-m-d', strtotime($this->input->post('to_date')));
    $branch_id=$this->input->post('branch_id');
else:
    $branch_id = '';
    $group = '';
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Balance Sheet
                </div>
            </div>
            <div class="portlet-body">
                <div class="row noPrint">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="">
                                                Branch </label>
                                            <div class="col-sm-8">
                                                <select name="branch_id" class="chosen-select form-control"
                                                        id=""
                                                        data-placeholder="Search Branch"
                                                        onchange="check_pretty_cash(this.value)">

                                                    <?php
                                                    echo branch_dropdown('all', $branch_id);
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="form-field-1"><span style="color:red;"> *</span> As At </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="to_date" value="<?php
                                                if (!empty($to_date)) {
                                                    echo $to_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-sm-2">

                                            </div>
                                            <div class="col-sm-5">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-sm-5">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="window.print();" style="cursor:pointer;">
                                                    <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                                    Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <?php
                if (isset($_POST['to_date'])):
                    //  dumpVar($_POST);
                    $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
                    unset($_SESSION["to_date"]);
                    $_SESSION["to_date"] = $to_date;
                    $dist_id = $this->dist_id;
                    $total_income = '';
                    $total_costs = '';
                    $total_assets = '';
                    $total_liabilityies = '';
                    $total_equity_govt = '';
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        <?php echo $to_date; ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered table-hover" id="BalanceSheet">
                                <thead>
                                <tr>
                                    <td align="center" style="width: 12%;"><strong>Code</strong></td>
                                    <td align="center" style=" width: 40%;"><strong>Description</strong></td>

                                    <td align="center"><strong>Amount</strong></td>
                                    <td align="center" style="display: none"><strong>Amount</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="3" align="center">
                                        <strong><b>ASSETS</b>
                                        </strong>
                                    </td>
                                </tr>
                                <?php
                                $query_liability = "SELECT SUM(table1.Opening) AS Opening,
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
WHERE AC_TCOA.PARENT_ID = 1 AND AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' AND AC_TAVDtl.IsActive = 1

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


                                $query = $this->db->query($query_liability);

                                $result_assets = $query->result();
                                $query_liability = "SELECT SUM(table1.Opening) AS Opening,
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

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (  SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))-SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))) AS Balance

 
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
WHERE AC_TCOA.PARENT_ID = 2 AND AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' AND AC_TAVDtl.IsActive = 1

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

                                //echo $query_liability;exit;
                                $query_liability = $this->db->query($query_liability);
                                $result_liability = $query_liability->result();
                                $secondLebelArray = array();
                                $total_assets = 0;
                                foreach ($result_assets as $key => $value) {
                                    if($value->PN1!=''){
                                        $array[$value->PN1_Code.'~#@~'.$value->PN1][] = $value;
                                    }

                                }

                               /* echo "<pre>";
                                print_r($array);exit;*/
                                foreach ($array as $key => $valueManin) {
                                    $name_and_code = explode("~#@~", $key)
                                    ?>
                                    <tr>
                                        <td>
                                           <b><?php echo $name_and_code[0]; ?></b>
                                        </td>
                                        <td colspan="2">
                                            <b><?php echo $name_and_code[1]; ?></b>
                                        </td>
                                    </tr>
                                    <?php
                                    foreach ($valueManin as $key => $value) {
                                        $total_assets = $total_assets + $value->Balance;
                                        ?>
                                        <tr>

                                            <td><?php echo $value->PN2_Code ?></td>
                                            <td><?php echo $value->PN2 ?></td>
                                            <td align="right">
                                                <?php


                                                if($value->Balance <0 ){
                                                    echo " ( ".numberFromatfloat((-1*$value->Balance))." )" ;
                                                }else{
                                                    echo $value->Balance;
                                                }

                                                ?>
                                            </td>
                                            <td align="right" style="display: none">
                                                <?php

                                                if($value->Balance <0 ){
                                                   echo " ( ".numberFromatfloat((-1*$value->Balance))." )" ;
                                                }else{
                                                    echo $value->Balance;
                                                }



                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                } ?>
                                <tr>
                                    <td colspan="2" style="text-align: right">
                                        Total Assets (In BDT)
                                    </td>

                                    <td style="text-align: right">
                                        <?php

                                        /*if ($total_assets< 0) {
                                            echo '( '.(numberFromatfloat((-1*$total_assets))).' )';
                                        }else{*/
                                        echo numberFromatfloat($total_assets);
                                        // }


                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" align="center">
                                        <strong>
                                            Capital & Liabilities
                                        </strong>
                                    </td>
                                </tr>
                                <?php
                                $secondLebelLiabilityArray = array();
                                $arraylLiabilityArray = array();
                                $total_liability = 0;
                                foreach ($result_liability as $key => $value) {
                                    if($value->PN1 !=''){
                                        $LiabilityArrayKey=$value->PN1;
                                    }else{
                                        $LiabilityArrayKey=$value->CN;
                                    }
                                    $arraylLiabilityArray[$LiabilityArrayKey][] = $value;
                                }

                                /* echo "<pre>";
                                 print_r($arraylLiabilityArray);exit;*/
                                foreach ($arraylLiabilityArray as $key => $valueManin) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $key; ?>
                                        </td>
                                        <td colspan="2">
                                            <?php echo $key; ?>
                                        </td>
                                    </tr>
                                    <?php
                                    foreach ($valueManin as $key => $value) {
                                        $total_liability = $total_liability + ($value->Balance);
                                        ?>
                                        <tr>

                                            <td><?php echo $value->PN2_Code ?></td>
                                            <td><?php echo $value->PN2 ?></td>
                                            <td align="right">
                                                <?php


                                                if($value->Balance <0 ){
                                                    echo " ( ".numberFromatfloat((-1*$value->Balance))." )" ;
                                                }else{
                                                    echo $value->Balance;
                                                }

                                                ?>
                                            </td>
                                            <td align="right" style="display: none">
                                                <?php

                                                if($value->Balance <0 ){
                                                    echo " ( ".numberFromatfloat((-1*$value->Balance))." )" ;
                                                }else{
                                                    echo $value->Balance;
                                                }


                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" style="text-align: right">
                                        Capital & Liabilities Up To This Period
                                    </td>
                                    <td align="right">
                                        <?php
                                        if($total_liability <0 ){
                                            echo " ( ".numberFromatfloat((-1*$total_liability))." )" ;
                                        }else{
                                            echo numberFromatfloat($total_liability);
                                        }

                                        ?>
                                    </td>

                                </tr>

                                <tr>
                                    <td colspan="2" style="text-align: right">
                                        <?php
                                        $expance = $this->Accounts_model->get_sum_of_a_accounting_group(4, $end_date);
                                        $income = $this->Accounts_model->get_sum_of_a_accounting_group(3, $end_date);
                                        //print_r($income);
                                        ?>
                                        Profit/Loss Of This Period
                                    </td>
                                    <td align="right">
                                        <?php
                                        $profit_or_loss = ((-1 * $income->amount) - $expance->amount);
                                        if($profit_or_loss <0 ){
                                            echo " ( ".numberFromatfloat((-1*$profit_or_loss))." )" ;
                                        }else{
                                            echo numberFromatfloat($profit_or_loss);
                                        }

                                        ?>
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right">
                                        Total Liabilities & Equity (In BDT)

                                    </td>
                                    <td align="right">
                                        <?php
                                         $TotalLiabilities=$total_liability + $profit_or_loss ;


                                        if($TotalLiabilities <0 ){
                                            echo " ( ".numberFromatfloat((-1*$TotalLiabilities))." )" ;
                                        }else{
                                            echo numberFromatfloat($TotalLiabilities);
                                        }
                                        ?>
                                    </td>
                                    <td style="display: none">

                                    </td>
                                </tr>


                                </tbody>


                            </table>


                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<style>
    .show_hide {
        display: none;
    }

    .a {
        font: 2em Arial;
        text-decoration: none
    }


</style>

<script>

    $(document).ready(function () {
        $('.show_hide').toggle(function () {
            alert("hello");
            $("#plus51").text("-");


        }, function () {
            $("#plus51").text("+");

        });
    });

</script>
<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>


