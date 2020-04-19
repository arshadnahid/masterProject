<?php


$condition = array(
    'AccouVoucherType_AutoID' => 0,

    'Narration' => "Opening Balance  Voucher",
);
$ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_accounts_vouchermst', $condition);

if(!empty($ac_account_ledger_coa_info)){
    $opeDate=date('d-m-Y', strtotime($ac_account_ledger_coa_info->Accounts_Voucher_Date));
}


?>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    View Chart of Account
                </div>
            </div>
            <div class="portlet-body">

                <div class="row">
                    <form action="<?php echo $this->project?>/save_openning_balance_to_main_table" method="post">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td align="center"><strong><?php echo get_phrase('Opening Date') ?></strong></td>
                                <td colspan="2">
                                    <div class="input-group">
                                        <input autocomplete="off" class="form-control date-picker" readonly
                                               name="date"
                                               id="id-date-picker-1" type="text" value="<?php
                                        if (!empty($opeDate)) {
                                            echo date('d-m-Y', strtotime($opeDate));
                                        } else {
                                            echo date('d-m-Y');
                                        }
                                        ?>" data-date-format="dd-mm-yyyy"/>
                                        <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                    </div>
                                </td>
                            </tr>
                            <!--<tr>
                            <td align="right"><strong><?php /*echo get_phrase('Sub_Total_Bdt') */ ?></strong></td>
                            <td align="right">
                                <strong>
                                    <span class="ttl_dr"><?php
                            /*                                        echo number_format((float)$total_debit, 2, '.', ''); */ ?>
                                    </span>
                                </strong>

                            </td>
                            <td align="right">
                                <strong>
                                    <span class="ttl_cr"><?php
                            /*                                        echo number_format((float)$total_credit, 2, '.', ''); */ ?>
                                    </span>
                                </strong>
                            </td>

                        </tr>-->
                            <tr>
                                <td colspan="3">
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-5 col-md-7">
                                            <input type="submit"  value="Save"  class="btn btn-xs btn-success">



                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                    <table class="table table-bordered" id="FlagsExport">

                        <thead>

                        <tr>

                            <td align="center"><strong>Account Group [ Root - Parent - Child ]</strong></td>

                            <td align="center"><strong><?php echo get_phrase('Debit_In_Bdt') ?></strong></td>
                            <td align="center"><strong><?php echo get_phrase('Credit_In_Bdt') ?></strong></td>
                            <th align="center"><?php echo get_phrase('Action') ?></th>


                        </tr>

                        </thead>
                        <tbody>
                        <?php
                        foreach ($chartList as $key => $value) {
                            $drOrcrBalance = $this->Common_model->get_single_data_by_single_column2('opening_balance', 'account', $value['id']);
                            $exitsopeningDr = $drOrcrBalance->debit != "" ? $drOrcrBalance->debit : 0;
                            $exitsopeningCr = $drOrcrBalance->credit != "" ? $drOrcrBalance->credit : 0;
                            $total_debit = $total_debit + $exitsopeningDr;
                            $total_credit = $total_credit + $exitsopeningCr;
                            if ($exitsopeningDr > 0 || $exitsopeningCr > 0) {
                                $text = "Edit";
                                $color = "red";
                            } else {
                                $text = "Add";
                                $color = "green";
                            }
                            $style = 0;
                            $textColor = "";
                            $textSize = "";
                            $fontWeight = "";
                            if ($value['label'] == 0) {
                                $style = "10";
                                $textColor = 'red';
                                $fontWeight = '800';
                                $textSize = '22px;';
                            } elseif ($value['label'] == 1) {
                                $style = (20 * ($value['label']));
                                $textColor = '#000';
                                //$fontWeight='500';
                                $textSize = '19px';
                            } elseif ($value['label'] == 2) {
                                $style = (20 * ($value['label']));
                                $textColor = '#000';
                                //$fontWeight='500';
                                $textSize = '16px';
                            } elseif ($value['label'] == 3) {
                                $style = (20 * ($value['label']));
                                $textColor = '#000';
                                //$fontWeight='500';
                                $textSize = '14px';
                            } else {
                                $style = (20 * ($value['label']));
                                $textColor = '#000';
                                //$fontWeight='500';
                                $textSize = '12px';
                            }
                            ?>

                            <tr>
                                <td align="left"
                                    style="
                                            padding-left:<?php echo $style . 'px' ?>!important;
                                            color: <?php echo $textColor; ?>;
                                            font-weight: <?php echo $fontWeight; ?>;
                                            font-size: <?php echo $textSize ?>;">

                                    <?php echo $value['parent_name'] ?>
                                </td>

                                <td class="text-left">
                                    <?php if ($value['posted'] == 1) { ?>
                                        <input autocomplete="off" type="hidden" name="accountid[]"
                                               value="<?php echo $value['id']; ?>"/>
                                        <input style="text-align: right" type="text"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                               class="form-control debit" id="debit_amount_<?php echo $value['id'] ?>"
                                               value="<?php echo $exitsopeningDr ?>"
                                               name="headDebit_<?php echo $value['id'] ?>"
                                               placeholder="<?php echo $exitsopeningDr ?>">
                                    <?php } ?>
                                </td>
                                <td class="text-left">
                                    <?php if ($value['posted'] == 1) { ?>
                                        <input style="text-align: right" type="text"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                               class="form-control credit" id="credit_amount_<?php echo $value['id'] ?>"
                                               value="<?php echo $exitsopeningCr ?>"
                                               name="headCredit_<?php echo $value['id'] ?>"
                                               placeholder="<?php echo $exitsopeningCr ?>">
                                    <?php } ?>
                                </td>
                                <td align="center">
                                    <?php if ($value['posted'] == 1) { ?>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="btn btn-icon-only <?php echo $color ?>" href="javascript:void(0)"
                                               onclick="updateAndSaveopening_balance('<?php echo $value['id']; ?>')">
                                                <!--  <i class="fa fa-pencil" style="color:#fff"></i>-->
                                                <?php echo $text ?>
                                            </a>

                                        </div>
                                    <?php } ?>

                                </td>


                            </tr>

                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td align="right"><strong><?php echo get_phrase('Sub_Total_Bdt') ?></strong></td>
                            <td align="right">
                                <strong>
                                    <span class="ttl_dr"><?php
                                        echo number_format((float)$total_debit, 2, '.', ''); ?>
                                    </span>
                                </strong>

                            </td>
                            <td align="right">
                                <strong>
                                    <span class="ttl_cr"><?php
                                        echo number_format((float)$total_credit, 2, '.', ''); ?>
                                    </span>
                                </strong>
                            </td>
                            <td></td>
                        </tr>
                        </tfoot>


                    </table>


                </div>
            </div>
        </div>
    </div>
</div>


</div>
<script src="<?php echo base_url('assets/setup.js'); ?>"></script>

<script type="text/javascript">

    function deleteOpeningBalance() {
        swal({
                parent_name: "Are you sure ?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#73AE28',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true,
                type: 'success'
            },
            function (isConfirm) {
                if (isConfirm) {
                    window.location.href = "deleteOpneningBalance";
                } else {
                    return false;
                }
            });
    }


    $(document).ready(function () {
        $(document).ready(function () {
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            })

        });

        var ttl_dr = 0;
        $.each($('.debit'), function () {
            dr = $(this).val();
            dr = Number(dr);
            ttl_dr += dr;
        });
        $(this).val(parseFloat($(this).val()).toFixed(2));
        $('.ttl_dr').html();
        $('.ttl_dr').html(parseFloat(ttl_dr).toFixed(2));


        var ttl_cr = 0;
        $.each($('.credit'), function () {
            cr = $(this).val();
            cr = Number(cr);
            ttl_cr += cr;
        });
        $(this).val(parseFloat($(this).val()).toFixed(2));
        $('.ttl_cr').html();
        $('.ttl_cr').html(parseFloat(ttl_cr).toFixed(2));


        $('.debit').keyup(function () {


            ttl_dr = 0;
            $.each($('.debit'), function () {
                dr = $(this).val();
                dr = Number(dr);
                ttl_dr += dr;
            });
            //$(this).val(parseFloat($(this).val()).toFixed(2));
            $('.ttl_dr').html();
            $('.ttl_dr').html(parseFloat(ttl_dr).toFixed(2));
        });
        $('.credit').keyup(function () {


            ttl_cr = 0;
            $.each($('.credit'), function () {
                cr = $(this).val();
                cr = Number(cr);
                ttl_cr += cr;
            });
            //$(this).val(parseFloat($(this).val()).toFixed(2));
            $('.ttl_cr').html();
            $('.ttl_cr').html(parseFloat(ttl_cr).toFixed(2));
        });
    });


    function updateAndSaveopening_balance(id) {
        var id = id;
        var drAmount = parseFloat($('#debit_amount_' + id).val());
        var crAmount = parseFloat($('#credit_amount_' + id).val());
        updateAndSave(id, drAmount, crAmount)
    }


    function updateAndSave(id, drAmount, crAmount) {
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/OpeningController/updateAndSaveopening_balance',
            data: {
                id: id,
                drAmount: drAmount,
                crAmount: crAmount
            },
            dataType: "json",
            success: function (data) {
                if (JSON.parse(data) == 1) {
                    toastr.success('Save Successfully');
                } else {
                    toastr.error("Can Not Save");
                }
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#FlagsExport').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            /*"pageLength": 50,
            dom: 'Bfrtip',
            buttons: ['copy','csv','excel','pdf','print']*/
            // "dom": '<"top"i>rt<"bottom"flp><"clear">'
        });
    });
</script>







