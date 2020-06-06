<?php
if (isset($_POST['start_date'])):
    /*    echo'<pre>';
    print_r($_POST);*/
    $account = $this->input->post('accountHead');
    $group = $this->input->post('group');
    $branch_id = $this->input->post('branch_id');
    $from_date = date('d-m-Y', strtotime($this->input->post('start_date')));
    $to_date = date('d-m-Y', strtotime($this->input->post('end_date')));
else:
    $account = $account;
    $group = '';
endif;
//echo "<pre>";
//print_r($accountHeadList);
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    General Report
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal noPrint">
                            <div class="col-sm-12">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="account_group">
                                                Account Group</label>
                                            <div class="col-sm-8">
                                                <select name="group" class="chosen-select form-control"
                                                        id="account_group"
                                                        data-placeholder="Search by Account Head"
                                                        onchange="get_account_ledger_list(this.value)">
                                                    <option value="" disabled selected></option>
                                                    <?php
                                                    foreach ($accountHeadList as $key => $head) {
                                                        ?>
                                                        <option <?php if ($head->id == $group) {
                                                            echo 'selected';
                                                        } ?> value="<?php echo $head->id; ?>"><?php echo get_phrase($head->parent_name); ?></option>

                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="">
                                                Ledger </label>
                                            <div class="col-sm-8">
                                                <select name="accountHead" class="chosen-select form-control"
                                                        id="Ledger_id"
                                                        data-placeholder="Search Ledger">
                                                    <option value="" disabled selected></option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
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

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            From Date</label>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            To Date</label>
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                Search
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="is_print" value="0" id="is_print">
                                            <button type="button" class="btn btn-info btn-sm" id="PRINT"
                                                    style="cursor:pointer;">
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
                    General Report
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (isset($_POST['start_date'])):
                    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                    $account = $this->input->post('accountHead');
                    $dist_id = $this->dist_id;
                    $total_pvsdebit = 0;
                    $total_pvscredit = 0;
                    $total_debit = 0;
                    $total_credit = 0;
                    $total_balance = 0;
                    $lastBalance = 0;
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
                                        <strong>From <?php echo $from_date; ?> To <?php echo $to_date; ?></strong>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td nowrap align="center"><strong>Group/Ledger</strong></td>
                                    <td align="center"><strong>Opening</strong></td>
                                    <td align="center"><strong>(Dr)</strong></td>
                                    <td align="center"><strong>(Cr)</strong></td>
                                    <td align="center"><strong>Balance</strong></td>

                                </tr>
                                </thead>
                                <tbody>
                                <!-- PVS Balance -->


                                <!-- /PVS Balance -->
                                <!-- Search Balance -->
                                <?php
                                if (!empty($gl_data)):
                                    $tt_Opening=0;
                                    $tt_GR_DEBIT=0;
                                    $tt_GR_CREDIT=0;
                                    foreach ($gl_data as $key => $value) {
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-left"><b><?php echo $key ?></b></td>
                                        </tr>
                                        <?php
                                        foreach ($value as $key => $value2) {
                                            $tt_Opening+=$value2->Opening;
                                            $tt_GR_DEBIT+=$value2->GR_DEBIT;
                                            $tt_GR_CREDIT+=$value2->GR_CREDIT;
                                            ?>
                                            <tr class="ledger_tr_<?php echo $value2->CHILD_ID?>">

                                                <td class="text-left" style="white-space: nowrap;">
                                                    <form  action="generalLedger2" method="post" class="form-horizontal " target="_blank">
                                                        <button type="submit" class="btn btn-info btn-sm btn-link" id=""
                                                                style="cursor:pointer;">

                                                            <?php echo $value2->CN ?>
                                                        </button>


                                                        <input type="hidden" name="branch_id" value="<?php echo $value2->branch_id;?>">
                                                        <input type="hidden" name="start_date" value="<?php echo $from_date;?>">
                                                        <input type="hidden" name="end_date" value="<?php echo $to_date ;?>">
                                                        <input type="hidden" name="accountHead" value="<?php echo $value2->CHILD_ID;?>">
                                                        <input type="hidden" name="group" value="<?php echo $this->input->post('group');?>">


                                                    </form>
                                                </td>
                                                <td class="text-right"><?php echo numberFromatfloat($value2->Opening) ?></td>
                                                <td  class="text-right"><?php echo numberFromatfloat($value2->GR_DEBIT) ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value2->GR_CREDIT) ?></td>
                                                <td class="text-right">
                                                    <?php
                                                    $Balance=(($value2->Opening+$value2->GR_DEBIT)-$value2->GR_CREDIT);
                                                    $tt_Balance+=$Balance;
                                                    if($Balance>0){
                                                        echo numberFromatfloat($Balance) .' Dr';
                                                    }else{
                                                        echo numberFromatfloat((-1*$Balance)) .' Cr';
                                                    }

                                                    ?>
                                                </td>
                                            </tr>


                                        <?php }
                                    }
                                endif;
                                ?>

                                <!-- /Search Balance -->

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td class="text-right"><?php echo "Total" ?></td>
                                    <td class="text-right"><?php echo numberFromatfloat($tt_Opening); ?></td>
                                    <td class="text-right"><?php echo numberFromatfloat($tt_GR_DEBIT); ?></td>
                                    <td class="text-right"><?php echo numberFromatfloat($tt_GR_CREDIT) ?></td>
                                    <td class="text-right">
                                        <?php
                                        if($tt_Balance>0){
                                            echo numberFromatfloat($tt_Balance) .' Dr';
                                        }else{
                                            echo numberFromatfloat((-1*$tt_Balance)) .' Cr';
                                        }

                                        ?>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>


                    <?php
                endif; ?>
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
        var groupID = $('#account_group').val();
        if (groupID != '') {
            get_account_ledger_list(groupID)
        }

    });


    function get_account_ledger_list(groupId) {

        var url = '<?php echo site_url("lpg/AccountController/account_ledger_list") ?>';

        $.ajax({

            type: 'POST',

            url: url,
            data: {'groupId': groupId, 'ledger_id': '<?php echo $account; ?>'},
            //data: {'groupId': groupId},
            success: function (data) {
                $('#Ledger_id').chosen();
                $('#Ledger_id option').remove();
                $('#Ledger_id').append($(data));
                $("#Ledger_id").trigger("chosen:updated");
                $("#account_group").trigger("liszt:updated");

            }
        });

    }

    var form = $('#publicForm'); // contact form
    var submit = $('#PRINT');  // submit button
    submit.on('click', function (e) {
        e.preventDefault(); // prevent default form submit
        $("#is_print").val(1);
        form.attr('target', '_blank');
        form.submit();
        $("#is_print").val(0);
        form.attr('target', '');
    });

    function details_report(id){

    }

</script>