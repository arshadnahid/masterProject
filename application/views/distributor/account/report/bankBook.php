<?php
if (isset($_POST['start_date'])):
    $bankAccount = $this->input->post('bankAccount');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
    $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
    $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
    $branch_id = $this->input->post('branch_id');
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Bank Book
                </div>
            </div>
            <div class="portlet-body">

                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal noPrint">
                            <div class="col-sm-12 ">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label text-right" for="BranchAutoId">

                                                <?php echo get_phrase('Branch') ?></label>
                                            <div class="col-sm-7">
                                                <select name="branch_id" class="chosen-select form-control"
                                                        id="BranchAutoId" data-placeholder="Select Branch">
                                                    <option value=""></option>
                                                    <?php
                                                    if (!empty($branch_id)) {
                                                        $selected = $branch_id;
                                                    } else {
                                                        $selected = 'all';
                                                    }
                                                    // come from branch_dropdown_helper
                                                    echo branch_dropdown('all', $selected);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $condition = array(
                                        'parentId' => 55,
                                        'dist_id' => $this->dist_id,
                                    );
                                    $allBankAccount = $this->Common_model->get_data_list_by_many_columns('generaldata', $condition);
                                    ?>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                From</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="start_date" value="<?php
                                                if (!empty($from_date)) {
                                                    echo $from_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                To </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="end_date"
                                                       name="end_date" value="<?php
                                                if (!empty($to_date)):
                                                    echo $to_date;
                                                else:
                                                    echo date('d-m-Y');
                                                endif;
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-md-6">
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
                if (isset($_POST['start_date']) && $_POST['end_date']):
                    //  dumpVar($_POST);
                    $bankAccount = $this->input->post('bankAccount');
                    $from_date = $this->input->post('start_date');
                    $to_date = $this->input->post('end_date');
                    unset($_SESSION["bankAccount"]);
                    unset($_SESSION["start_date"]);
                    unset($_SESSION["end_date"]);
                    $_SESSION["bankAccount"] = $bankAccount;
                    $_SESSION["start_date"] = $from_date;
                    $_SESSION["end_date"] = $to_date;
                    $dist_id = $this->dist_id;
                    $total_pvsdebit = '';
                    $total_pvscredit = '';
                    $total_debit = '';
                    $total_credit = '';
                    $total_balance = '';
                    ?>
                    <div class="row">
                        <div class="col-xs-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        <strong>Bank Book statement : </strong><span>From <?php echo $from_date; ?>
                                            To <?php echo $to_date; ?></span>
                                    </td>
                                </tr>
                            </table>
                            <?php ?>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td align="center" rowspan="2"><strong>Name Of Bank A/C</strong></td>
                                    <td align="center" colspan="2"><strong>Opening Balance</strong></td>
                                    <td align="center" colspan="2"><strong>Transaction </strong></td>
                                    <td align="center" colspan="2"><strong>Closing Balance</strong></td>

                                </tr>
                                <tr>

                                    <td align="center"><strong>Dr</strong></td>
                                    <td align="center"><strong>Cr</strong></td>
                                    <td align="center"><strong>Deposit </strong></td>
                                    <td align="center"><strong>Withdraw </strong></td>
                                    <td align="center"><strong>Dr</strong></td>
                                    <td align="center"><strong>Cr</strong></td>

                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $query = "SELECT
branch.branch_name,
branch.branch_id,
	SUM(IFNULL(Opening.GR_DEBIT,0))GR_DEBIT_OP,
	SUM(IFNULL(Opening.GR_CREDIT * - 1,0))GR_CREDIT_OP,
	SUM(IFNULL(Trans.GR_DEBIT,0))GR_DEBIT_Trans,
	SUM(IFNULL(Trans.GR_CREDIT * - 1,0))GR_CREDIT_Trans,
  IFNULL(software_opening.tt_credit,0) sof_opening_credit,
  IFNULL(software_opening.tt_debit,0) sof_opening_debit,
	CASE
WHEN(SUM(IFNULL(Opening.GR_DEBIT,0))- SUM(IFNULL(Opening.GR_CREDIT,0))+ SUM(IFNULL(Trans.GR_DEBIT,0))- SUM(IFNULL(Trans.GR_CREDIT,0)))> 0 
THEN
	(SUM(IFNULL(Opening.GR_DEBIT,0))- SUM(IFNULL(Opening.GR_CREDIT,0))+ SUM(IFNULL(Trans.GR_DEBIT,0))- SUM(IFNULL(Trans.GR_CREDIT,0)))
ELSE
	0
END Closing_DR,
 CASE
WHEN(SUM(IFNULL(Opening.GR_DEBIT,0))- SUM(IFNULL(Opening.GR_CREDIT,0))+ SUM(IFNULL(Trans.GR_DEBIT,0))- SUM(IFNULL(Trans.GR_CREDIT,0)))< 0 
THEN
	(SUM(IFNULL(Opening.GR_DEBIT,0))- SUM(IFNULL(Opening.GR_CREDIT,0))+ SUM(IFNULL(Trans.GR_DEBIT,0))- SUM(IFNULL(Trans.GR_CREDIT,0)))
ELSE
	0
END Closing_CR,
 ac_tb_coa.CHILD_ID,
 AC_TALCOA.parent_name,
 IFNULL(AC_TALCOA. CODE,'')AS CODE,
 AC_TALCOA.parent_name + ' - ( ' + IFNULL(AC_TALCOA. CODE,'')+ ' )' AS PNcode,
Trans.BranchAutoId
FROM
	ac_tb_coa
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TALCOA.id = ac_tb_coa.CHILD_ID

LEFT OUTER JOIN(
	SELECT
		AC_TAVDtl.CHILD_ID,
		AC_TAVDtl.GR_DEBIT,
		AC_TAVDtl.GR_CREDIT,
AC_TAVDtl.BranchAutoId
	FROM
		ac_tb_accounts_voucherdtl AC_TAVDtl
	LEFT OUTER JOIN ac_accounts_vouchermst AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID = AC_TAVMst.Accounts_VoucherMst_AutoID /*WHERE   CONVERT(DATETIME, AC_TAVMst.Accounts_Voucher_Date,103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103)*/
WHERE 1=1
AND AC_TAVDtl.date >='" . $startDate . "'
AND AC_TAVDtl.date <='" . $endDate . "'
)Trans ON ac_tb_coa.CHILD_ID = Trans.CHILD_ID
LEFT OUTER JOIN(
	SELECT
		AC_TAVDtl.CHILD_ID,
		AC_TAVDtl.GR_DEBIT,
		AC_TAVDtl.GR_CREDIT,
AC_TAVDtl.BranchAutoId
	FROM
		ac_tb_accounts_voucherdtl AC_TAVDtl
	LEFT OUTER JOIN ac_accounts_vouchermst AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID = AC_TAVMst.Accounts_VoucherMst_AutoID 
WHERE 1=1

AND AC_TAVDtl.date <'" . $startDate . "'

)Opening ON ac_tb_coa.CHILD_ID = Opening.CHILD_ID /*AND Opening.BranchAutoId=Trans.BranchAutoId*/
/* opening_balance */
LEFT JOIN (
SELECT  account,SUM(debit) tt_debit,SUM(credit) tt_credit FROM  opening_balance  
GROUP BY account
) software_opening on software_opening.account=Trans.CHILD_ID
LEFT JOIN branch on branch.branch_id=Trans.BranchAutoId
WHERE
	ac_tb_coa.PARENT_ID2 =  ".$this->config->item("Cash_at_Bank")."
AND ac_tb_coa.CHILD_ID <> 0";
                                if ($branch_id != 'all') {
                                    //product.product_id
                                   // $query .= " and Trans.BranchAutoId= " . $branch_id;
                                }
                                $query .= " GROUP BY
                                                ac_tb_coa.CHILD_ID,
                                                AC_TALCOA.parent_name,
                                                AC_TALCOA. CODE,
                                              Trans.BranchAutoId   ORDER BY branch.branch_name";
                                log_message('error','this is bank book query . '.print_r($query,true));
                                $query = $this->db->query($query);



                                $result = $query->result_array();
                              /* echo "<pre>";
                                print_r($this->db->last_query());
                                print_r($result);*/
                                $total_op_dr = 0;
                                $total_op_cr = 0;
                                $total_dr_tran = 0;
                                $total_cr_tran = 0;
                                $total_closing_dr = 0;
                                $total_closing_cr = 0;
                                $brandNameArray = array();
                                foreach ($result as $row) {
                                    $GR_DEBIT_OP = $row['GR_DEBIT_OP'];
                                    $GR_CREDIT_OP = $row['GR_CREDIT_OP'];
                                    $TT_GR_DEBIT_OP = $row['GR_DEBIT_OP'] + $row['sof_opening_credit'];
                                    $total_op_dr = $total_op_dr + $TT_GR_DEBIT_OP;
                                    $TT_GR_CREDIT_OP = $row['GR_CREDIT_OP'] + $row['sof_opening_credit'];
                                    $total_op_cr = $total_op_cr + $TT_GR_CREDIT_OP;
                                    $GR_DEBIT_Trans = $row['GR_DEBIT_Trans'];
                                    $total_dr_tran = $total_dr_tran + $GR_DEBIT_Trans;
                                    $GR_CREDIT_Trans = $row['GR_CREDIT_Trans'];
                                    $total_cr_tran = $GR_CREDIT_Trans + $total_cr_tran;
                                    $Closing_DR = $row['Closing_DR'];
                                    $Closing_CR = $row['Closing_CR'];

                                    $Closing_DR2 = $row['Closing_DR'] < 0 ? ($row['Closing_DR'] * -1) : $row['Closing_DR'];
                                    $Closing_CR2 = $row['Closing_CR'] < 0 ? ($row['Closing_CR'] * -1) : $row['Closing_CR'];

                                    $total_closing_dr = $Closing_DR + $total_closing_dr;
                                    $total_closing_cr = $Closing_CR + $total_closing_cr;
                                    if ($Closing_DR2 > 0 || $Closing_CR2 > 0) {
                                        if (!in_array($row['branch_name'], $brandNameArray)) {
                                            array_push($brandNameArray, $row['branch_name']); ?>
                                            <tr>
                                                <td colspan="7">
                                                    <?php echo $row['branch_name']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="">
                                                    <form action="generalLedger" method="post" class="general-form"
                                                          target="_blank">

                                                        <input type="hidden" name="start_date"
                                                               value="<?php echo $from_date ?>"/>
                                                        <input type="hidden" name="end_date"
                                                               value="<?php echo $to_date ?>"/>
                                                        <input type="hidden" name="branch_id"
                                                               value="<?php echo $row['branch_id'] ?>"/>
                                                        <input type="hidden" name="accountHead"
                                                               value="<?php echo $row['CHILD_ID'] ?>"/>
                                                        <input type="hidden" name="group"
                                                               value="<?php echo $row['CHILD_ID'] ?>"/>

                                                        <button type="submit" class="btn btn-xs btn-link"
                                                                style="    text-decoration: none;"><?php echo $row['parent_name'] . ' [' . $row['CODE'] . ']' ?></button>
                                                        <!-- <a href="#" onclick="document.getElementsByClassName('general-form').submit();"></a>-->

                                                    </form>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_DEBIT_OP) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_CREDIT_OP) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_DEBIT_Trans) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_CREDIT_Trans) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($Closing_DR) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($Closing_CR) ?>
                                                </td>

                                            </tr>
                                        <?php } else {
                                            ?>
                                            <tr>
                                                <td align="">
                                                    <form action="generalLedger" method="post" class="general-form"
                                                          target="_blank">

                                                        <input type="hidden" name="start_date"
                                                               value="<?php echo $from_date ?>"/>
                                                        <input type="hidden" name="end_date"
                                                               value="<?php echo $to_date ?>"/>
                                                        <input type="hidden" name="branch_id"
                                                               value="<?php echo $row['branch_id'] ?>"/>
                                                        <input type="hidden" name="accountHead"
                                                               value="<?php echo $row['CHILD_ID'] ?>"/>

                                                        <button type="submit" class="btn btn-xs btn-link"
                                                                style="    text-decoration: none;"><?php echo $row['parent_name'] . ' [' . $row['CODE'] . ']' ?></button>
                                                        <!-- <a href="#" onclick="document.getElementsByClassName('general-form').submit();"></a>-->

                                                    </form>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_DEBIT_OP) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_CREDIT_OP) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_DEBIT_Trans) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($GR_CREDIT_Trans) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($Closing_DR) ?>
                                                </td>
                                                <td align="right">
                                                    <?php echo numberFromatfloat($Closing_CR) ?>
                                                </td>

                                            </tr>

                                        <?php }
                                    }
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td align="right"><strong>Ending Balance (In BDT.)</strong></td>
                                    <td align="right">
                                        <strong>
                                            <?php echo numberFromatfloat($total_op_dr); ?>
                                            &nbsp;Dr.
                                        </strong>
                                    </td>
                                    <td align="right">
                                        <strong>
                                            <?php echo numberFromatfloat($total_op_cr); ?>
                                            &nbsp;Cr.
                                        </strong>
                                    </td>
                                    <td align="right">
                                        <strong>
                                            <?php echo numberFromatfloat($total_dr_tran); ?>
                                            &nbsp;Dr.
                                        </strong>
                                    </td>
                                    <td align="right">
                                        <strong>
                                            <?php echo numberFromatfloat($total_cr_tran); ?>
                                            &nbsp;Cr.
                                        </strong>
                                    </td>
                                    <td align="right">
                                        <strong>
                                            <?php echo numberFromatfloat($total_closing_dr); ?>
                                            &nbsp;Dr.
                                        </strong>
                                    </td>
                                    <td align="right">
                                        <strong>
                                            <?php echo numberFromatfloat($total_closing_cr); ?>
                                            &nbsp;Cr.
                                        </strong>
                                    </td>

                                </tr>
                                </tfoot>
                            </table>
                            <?php
                            ?>


                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>