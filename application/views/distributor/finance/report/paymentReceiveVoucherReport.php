<?php
if (isset($_POST['start_date'])):
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
    $vType = $this->input->post('reportType');
    $branch_id = $this->input->post('branch_id');
endif;

// echo "<pre>";
// print_r($result);
// exit();
?>


<style type="text/css">
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>

<div class="row noPrint">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Payment / Receive Voucher Report') ?> </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-sm-12 ">


                                <div style="background-color: grey!important;">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                Report Type</label>
                                            <div class="col-sm-8">
                                                <select name="reportType" id="reportType" class="form-control required">
                                                    <option selected disabled>Select Type</option>
                                                    <option <?php
                                                    if ($vType == 2) {
                                                        echo "selected";
                                                    }
                                                    ?> value="2">Payment
                                                    </option>
                                                    <option value="1" <?php
                                                    if ($vType == 1) {
                                                        echo "selected";
                                                    }
                                                    ?>>Receive
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="">
                                                Branch </label>
                                            <div class="col-sm-8">
                                                <select name="branch_id" class="chosen-select form-control"
                                                        id=""
                                                        data-placeholder="Search Branch"
                                                >
                                                    <option value="" disabled selected></option>
                                                    <option value="all">All</option>
                                                    <?php
                                                    foreach ($branch as $branchItem) {
                                                        ?>
                                                        <option <?php
                                                        if (!empty($branch_id) && $branch_id == $branchItem->branch_id) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $branchItem->branch_id ?>"><?php echo $branchItem->branch_name ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                From
                                                Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control " id="start_date"
                                                       name="start_date"
                                                       value="<?php
                                                       if (!empty($from_date)) {
                                                           echo $from_date;
                                                       } else {
                                                           echo date('Y-m-d');
                                                       }
                                                       ?>" data-date-format='yyyy-mm-dd'
                                                       placeholder="Start Date: yyyy-mm-dd"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                To
                                                Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control " id="end_date"
                                                       name="end_date"
                                                       value="<?php
                                                       if (!empty($to_date)):
                                                           echo $to_date;
                                                       else:
                                                           echo date('Y-m-d');
                                                       endif;
                                                       ?>" data-date-format='yyyy-mm-dd'
                                                       placeholder="End Date: yyyy-mm-dd"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <button type="button" onclick="return isconfirm2()"
                                                        class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-info btn-sm" id="btn-print"
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
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row noPrint">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Payment / Receive Voucher Report') ?> </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">


                        <?php
                        if (isset($_POST['start_date'])):
                            $reportType = $this->input->post('reportType');
                            $start_date = $this->input->post('start_date');
                            $to_date = $this->input->post('end_date');
                            $dist_id = $this->dist_id;
                            ?>
                            <div class="row">
                                <!--<div class="col-xs-8 col-xs-offset-2">
                                    <div class="table-header">

                                        <?php
                                /*                                        if ($reportType == 1) {
                                                                            echo "Payment Voucher Report";
                                                                        } else {
                                                                            echo "Receive Voucher Report";
                                                                        }
                                                                        */
                                ?><span style="color:greenyellow;">From <?php /*echo $from_date; */
                                ?>
                                            To <?php /*echo $to_date; */
                                ?></span>
                                    </div>
                                </div>-->
                                <div class="col-xs-8 col-xs-offset-2">
                                    <div class="noPrint">
                                        <!--                            <button style="border-radius:100px 0 100px 0;" href="<?php echo site_url('SalesController/salesReport_export_excel/') ?>" class="btn btn-success pull-right noPrint">
                            <i class="ace-icon fa fa-download"></i>
                            Excel
                        </button>-->
                                    </div>
                                    <table class="table table-responsive">
                                        <tr>
                                            <td style="text-align:center;">
                                                <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                                <span><?php echo $companyInfo->address; ?></span><br>
                                                <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                                <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                                <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                                <strong><?php echo $pageTitle; ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td align="center"><strong>Branch</strong></td>
                                            <td align="center"><strong>Date</strong></td>

                                            <td align="center"><strong>Voucher No.</strong></td>

                                            <td align="center"><strong>Voucher By</strong></td>
                                            <td align="center"><strong>Amount</strong></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        $total_debit = 0;
                                        foreach ($result as $key => $value) {

                                            ?>

                                            <tr>
                                                <td colspan="5"><b><?php echo $key ?></b></td>
                                            </tr>
                                            <?php
                                            foreach ($value as $key => $eachResult) { ?>

                                                <tr>

                                                    <td><?php echo "" ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($eachResult->Accounts_Voucher_Date)); ?></td>
                                                    <td>
                                                        <a title="view invoice" class="<?php echo $class ?>"
                                                           href="<?php echo site_url($this->project . '/receiveVoucherView/' . $eachResult->Accounts_VoucherMst_AutoID); ?>"><?php echo $eachResult->Accounts_Voucher_No; ?></a>

                                                    </td>

                                                    <td>
                                                        <?php
                                                        $eachResult->voucher_by;
                                                        $details=explode('&^&',$eachResult->voucher_by);
                                                        echo $details[2];
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        $total_debit=$total_debit+$eachResult->debit;
                                                        echo numberFromatfloat($eachResult->debit);
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } ?>
                                        <!-- /Search Balance -->
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="4" align="right"><strong>
                                                    <?php
                                                    if ($reportType == 1) :
                                                        echo "Total Receive";
                                                    else:

                                                        echo "Total Payment";
                                                    endif;
                                                    ?>
                                                    Amount</strong></td>
                                            <td align="right">
                                                <strong><?php echo number_format((float)abs($total_debit), 2, '.', ','); ?>
                                                    &nbsp;</strong></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    function isconfirm2() {
        var repotrtType = $("#reportType").val();

        if (repotrtType == '' || repotrtType == null) {
            swal("Select Report Type!", "Validation Error!", "error");
        } else {

            $("#publicForm").submit();
        }
    }


    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>

