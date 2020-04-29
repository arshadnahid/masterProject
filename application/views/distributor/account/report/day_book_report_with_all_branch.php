<style>

    .chosen-container-single {
        width: 633px !important;;
    }

    .funkyradio label {
        width: 100%;
        border-radius: 3px;
        border: 1px solid #D1D3D4;
        font-weight: normal;
    }

    .funkyradio input[type="radio"]:empty, .funkyradio input[type="checkbox"]:empty {
        display: none;
    }

    .funkyradio input[type="radio"]:empty ~ label, .funkyradio input[type="checkbox"]:empty ~ label {
        position: relative;
        line-height: 2.5em;
        text-indent: 3.25em;
        margin-top: 2em;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .funkyradio input[type="radio"]:empty ~ label:before, .funkyradio input[type="checkbox"]:empty ~ label:before {
        position: absolute;
        display: block;
        top: 0;
        bottom: 0;
        left: 0;
        content: '';
        width: 2.5em;
        background: #D1D3D4;
        border-radius: 3px 0 0 3px;
    }

    .funkyradio input[type="radio"]:hover:not(:checked) ~ label, .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
        color: #888;
    }

    .funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
    .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
        content: '\2714';
        text-indent: .9em;
        color: #C2C2C2;
    }

    .funkyradio input[type="radio"]:checked ~ label,
    .funkyradio input[type="checkbox"]:checked ~ label {
        color: #777;
    }

    .funkyradio input[type="radio"]:checked ~ label:before,
    .funkyradio input[type="checkbox"]:checked ~ label:before {
        content: '\2714';
        text-indent: .9em;
        color: #333;
        background-color: #ccc;
    }

    .funkyradio input[type="radio"]:focus ~ label:before,
    .funkyradio input[type="checkbox"]:focus ~ label:before {
        box-shadow: 0 0 0 3px #999;
    }

    .funkyradio-default input[type="radio"]:checked ~ label:before,
    .funkyradio-default input[type="checkbox"]:checked ~ label:before {
        color: #333;
        background-color: #ccc;
    }

    .panel-heading h3 {
        padding-left: 10px;
    }

    .col-centered {
        display: inline-block;
        float: none;
        /* reset the text-align */
        text-align: left;
        /* inline-block space fix */
        margin-right: -4px;
    }

    /* centered columns styles */
    .row-centered {
        text-align: center;
    }


</style>
<?php
if (isset($_GET['to_date'])):

    $to_date = $_GET['to_date'];

endif;


if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];

} else {
    $branch_id = 'all';
}


?>

<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 2/11/2020
 * Time: 11:55 AM
 */
?>


<a data-toggle="modal" data-target="#myModal"
   class="saleAddPermission btn btn-xs green" class="saleAddPermission ">
    <i class="fa fa-list"></i>
    Add Configuration For Day Book Report&nbsp;&nbsp;&nbsp; </a>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Day Book Report
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="row noPrint">
                        <div class="col-md-12">
                            <form id="publicForm" action="" method="get" class="form-horizontal">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span
                                                    style="color:red;">*</span>Brance </label>
                                        <div class="col-sm-6">
                                            <select name="branch_id" class="chosen-select form-control"
                                                    id="BranchAutoId" data-placeholder="Search" required="true">
                                                <?php
                                                echo branch_dropdown('all', $branch_id);
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span
                                                    style="color:red;"> *</span> <?php echo get_phrase('As At') ?>
                                        </label>

                                        <div class="col-sm-6">

                                            <div class="input-group">

                                                <input class="form-control date-picker" type="text"
                                                       name="to_date" value="<?php
                                                if (!empty($to_date)) {
                                                    echo $to_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy'
                                                       placeholder="Start Date: dd-mm-yyyy"/>

                                                <span class="input-group-addon">

                                            <i class="fa fa-calendar bigger-110"></i>

                                        </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-6 col-md-offset-3">


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-sm-2"></div>
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


                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <label class="form-control" style="color: #7d7878;background-color: #D1D3D4">
                                Group Summary
                            </label>
                        </div>
                        <?php

                        if (!empty($getalldayBookSummery)) {
                            $balance_upto_that_day = array(1, 2);
                            foreach ($getalldayBookSummery as $key => $value) {
                                ?>
                                <div class="col-sm-4" style="overflow: hidden;padding-right: 0;">
                                    <div class="row">
                                        <div class="col-sm-12" style="display: block;" id="property_section">


                                            <div class="funkyradio select_color"
                                                 id="item_<?php echo $value->acc_group_id ?>">
                                                <div class="funkyradio-default">
                                                    <input class="property" type="checkbox"
                                                           name="chk_<?php echo $value->acc_group_id ?>"
                                                           id="GROUP_<?php echo $value->acc_group_id ?>"
                                                           value="<?php echo $value->acc_group_id ?>"
                                                           checked>
                                                    <label for="GROUP_<?php echo $value->acc_group_id ?>"
                                                           class="property_2"
                                                           style="margin-top: 10px;"><?php echo $value->parent_name ?>
                                                        <span style="float: right;margin-right: 5px;width: 45%;text-align: right;border-left: 1px solid #d1d3d4;">
                                                            <?php
                                                            $start_date = date('Y-m-d', strtotime($to_date));
                                                            if ($value->acc_group_id == 44) {


                                                                $query = "SELECT 
SUM(IFNULL(baseTable.income,0))-SUM(IFNULL(baseTable.expance,0)) as profit_lose 

FROM (

SELECT
-- 	SUM(IFNULL(AC_TAVDtl.GR_DEBIT, 0))GR_DEBIT,
-- 	SUM(IFNULL(AC_TAVDtl.GR_CREDIT, 0))GR_CREDIT,
-- 	(SUM(IFNULL(AC_TAVDtl.GR_DEBIT, 0))- SUM(IFNULL(AC_TAVDtl.GR_CREDIT, 0)))AS Balance,
	AC_TCOA.PARENT_ID,
	IFNULL(AC_TALCOA.parent_name, '')PN,
-- 	AC_TCOA.PARENT_ID1,
-- 	IFNULL(AC_TALCOA1.parent_name, '')PN1,
CASE WHEN AC_TCOA.PARENT_ID=3 
	 THEN 	(SUM(IFNULL(AC_TAVDtl.GR_CREDIT, 0))- SUM(IFNULL(AC_TAVDtl.GR_DEBIT, 0)))
	 
		END income,
CASE WHEN AC_TCOA.PARENT_ID=4 
	 THEN 	(SUM(IFNULL(AC_TAVDtl.GR_DEBIT, 0))- SUM(IFNULL(AC_TAVDtl.GR_CREDIT, 0)))
	 
		END expance



FROM
	ac_tb_accounts_voucherdtl AC_TAVDtl
LEFT OUTER JOIN ac_accounts_vouchermst AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID = AC_TAVMst.Accounts_VoucherMst_AutoID
LEFT OUTER JOIN ac_tb_coa AC_TCOA ON AC_TAVDtl.CHILD_ID = AC_TCOA.CHILD_ID
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TCOA.PARENT_ID = AC_TALCOA.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID = AC_TALCOA8.id /*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE
	AC_TCOA.PARENT_ID IN(3, 4)
AND AC_TCOA.CHILD_ID NOT IN(0, 105, 106)
/*AND AC_TAVMst.Accounts_Voucher_Date >= '2020-03-07'*/
AND AC_TAVMst.Accounts_Voucher_Date = '" . $start_date . "'
AND AC_TAVDtl.IsActive = 1";
                                                                if ($branch_id != 'all') {
                                                                    $query .= " AND AC_TAVDtl.BranchAutoId = '" . $branch_id . "'";
                                                                }

                                                                $query .= " GROUP BY
	AC_TCOA.PARENT_ID ";
                                                                if ($branch_id != 'all') {
                                                                    $query .= " ,AC_TAVDtl.BranchAutoId";
                                                                }

                                                                $query .= " ) baseTable";
                                                                $query = $this->db->query($query);
                                                                $result = $query->row();

                                                                if ($result->profit_lose < 0) {
                                                                    $number = $result->profit_lose * -1;
                                                                    $balance = '(' . $number . ')';
                                                                } else {
                                                                    $balance = $result->profit_lose * 1;
                                                                }

                                                                echo  $result->profit_lose;

                                                                //exit;

                                                            }
                                                            else if ($value->acc_group_id == 75) {
                                                                $query = "SELECT
	SUM(pd.quantity*pd.unit_price)  as purchase_amount
	

FROM
	purchase_details pd
LEFT JOIN purchase_invoice_info pii on pii.purchase_invoice_id=pd.purchase_invoice_id
WHERE
	pd.is_active = 'Y'
AND pd.branch_id = '" . $branch_id . "'
AND pii.invoice_date='" . $start_date . "'";

                                                                $query = $this->db->query($query);
                                                                $result = $query->row();
                                                                $balance = (number_format($result->purchase_amount * 1, 2));
                                                                echo $text . " " . $balance;


                                                            } else {
                                                                $text = "";
                                                                if (in_array($value->PARENT_ID, $balance_upto_that_day)) {
                                                                    //$text=" Up to ".$to_date;
                                                                } else {
                                                                    // $text=" No ".$to_date;
                                                                }
                                                                if ($value->dayBookBalance < 0) {
                                                                    $balance = '(' . (number_format($value->dayBookBalance * -1, 2)) . ')';
                                                                } else {
                                                                    $balance = (number_format($value->dayBookBalance * 1, 2));
                                                                }
                                                                echo $text . " " . $balance;
                                                            }


                                                            ?>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                </div>
                            <?php }
                        } ?>


                    </div>
                </div><!-- /.col -->

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Transaction of the Day
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-bordered table-hover tableAddItem">
                    <thead>
                    <tr>
                        <td>Date</td>
                        <td>Particulars</td>
                        <td>Voucher Type</td>
                        <td>Voucher No</td>
                        <td>Debit Amount</td>
                        <td>Credit Amount</td>
                    </tr>
                    </thead>
                    <tbody>


                    <?php
                    if (!empty($getalldayBookDetails)) {


                        foreach ($getalldayBookDetails as $key => $value) {
                            if ($value->GR_DEBIT > 0 || $value->GR_CREDIT > 0) {
                                ?>
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($value->Accounts_Voucher_Date)); ?></td>
                                    <td><?php echo $value->ledger_name ?></td>
                                    <td><?php
                                        if ($value->for != 0) {
                                            if ($value->for == 2) {
                                                echo "Sales Voucher";
                                            } else if ($value->for == 1) {
                                                echo "Purchase Voucher";
                                            } else if ($value->for == 3) {
                                                echo "supplier Payment";
                                            } else if ($value->for == 4) {
                                                echo "supplier Pending Caque";
                                            }
                                        } else
                                            echo $value->Accounts_VoucherType
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($value->for != 0) {
                                            echo $value->BackReferenceInvoiceNo;
                                        } else
                                            echo $value->Accounts_Voucher_No
                                        ?>
                                    </td>
                                    <td><?php echo $value->GR_DEBIT ?></td>
                                    <td><?php echo $value->GR_CREDIT ?>
                                </tr>

                            <?php }
                        }
                    } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add Configuration For Day Book Report</h4>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-md-12" id="addForm">
                        <form id="publicForm2" action="" method="post" class="form-horizontal ">

                            <?php
                            $this->db->select(' *');
                            $this->db->from('ac_account_ledger_coa');
                            $this->db->where('ac_account_ledger_coa.level_no ', 3);
                            $this->db->where('ac_account_ledger_coa.posted ', 0);
                            $accountGroup = $this->db->get()->result();
                            ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Account
                                    Group</label>
                                <div class="col-sm-9">
                                    <select class="chosen-select form-control" name="daybook"
                                            id="daybookId"
                                            data-placeholder="Search Account Group">
                                        <option value=""></option>
                                        <?php
                                        foreach ($accountGroup as $key => $head) {
                                            ?>
                                            <option value="<?php echo $head->id; ?>"><?php echo get_phrase($head->parent_name) . " ( " . $head->code . " ) "; ?></option>
                                            <?php
                                        }
                                        ?>
                                        <option value="<?php echo 58; ?>"><?php echo get_phrase('Sales') . " ( " . '3 - 001 - 001' . " ) "; ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">


                                    <button onclick="saveconfigaration('add')" id="subBtn" class="btn btn-info"
                                            type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        <?php echo get_phrase('Save') ?>
                                    </button>
                                    &nbsp; &nbsp; &nbsp;

                                </div>
                                <div class="modal-dialog">

                                    <!-- /.modal-content -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Serial No</th>

                                <th>Day Book</th>
                                <th style="" align="center"><strong><?php echo get_phrase('Action') ?></strong></th>
                            </tr>
                            </thead>
                            <tbody id="userData">

                            <?php
                            foreach ($dayBookCofig as $key => $value):
                                ?>

                                <tr>
                                    <td><?php echo $key + 1; ?></td>

                                    <td><?php echo $value->parent_name . ' [ ' . $value->code . ' ]'; ?></td>
                                    <?php
                                    echo '<td><a href="javascript:void(0);" class="btn btn-danger pull-left" onclick="return confirm(\'Are you sure to delete data?\')?saveconfigaration(\'delete\',\'' . $value->id . '\'):false;"><i class="fa fa-remove"></i></a></td>';

                                    ?>


                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>


                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">

                                <button type="button" class="btn red btn-outline sbold" data-dismiss="modal">Close
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class=" btn green btn-outline sbold" type="reset" data-dismiss="modal">
                                    <i class="fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                            <div class="modal-dialog">

                                <!-- /.modal-content -->
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">

                    function saveconfigaration(type, id) {


                        id = (typeof id == "undefined") ? '' : id;
                        var statusArr = {add: "added", edit: "updated", delete: "deleted"};
                        var userData = '';
                        if (type == 'add') {
                            userData = $("#addForm").find('.form-horizontal').serialize() + '&action_type=' + type + '&id=' + id;
                        } else if (type == 'edit') {
                            userData = $("#editForm").find('.form').serialize() + '&action_type=' + type;
                        } else {
                            userData = 'action_type=' + type + '&id=' + id;
                        }
                        var url = baseUrl + "lpg/AccountReportController/saveBankBookConfig";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: userData,
                            dataType: 'JSON',

                            success: function (msg) {
                                if (msg == '1') {
                                    swal('Group has been ' + statusArr[type] + ' successfully.', "!", "success");
                                    getconfig();
                                } else {
                                    swal("Some problem occurred !", "please try again!", "error");
                                }
                                $('#daybookId').val('').trigger('chosen:updated');
                            }
                        });


                    }


                    function getconfig() {

                        $.ajax({
                            type: 'POST',
                            url: baseUrl + "lpg/AccountReportController/getconfig",
                            data: 'action_type=view&' + $("#userForm").serialize(),
                            success: function (html) {
                                $('#userData').html(html);
                            }
                        });
                    }

                </script>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });


    });


</script>