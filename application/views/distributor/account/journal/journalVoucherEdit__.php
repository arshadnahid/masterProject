<style>

    table tbody tr td {

        margin: 1px !important;

        padding: 1px !important;

    }

    table tfoot tr td {

        margin: 1px !important;

        padding: 1px !important;

    }

    table tbody tr td {

        margin: 1px !important;

        padding: 1px !important;

    }

</style>

<div class="main-content">

    <div class="main-content-inner">


        <div class="page-content">

            <div class="row">

                <div class="col-md-12">


                    <form id="publicForm" action="" method="post" class="form-horizontal">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date<span
                                            style="color:red;"> *</span></label>

                                <div class="col-sm-6">

                                    <div class="input-group">

                                        <input class="form-control date-picker" name="date" id="id-date-picker-1"
                                               type="text"
                                               value="<?php echo date('d-m-Y', strtotime($journalVoucher->Accounts_Voucher_Date)); ?>"
                                               data-date-format="dd-mm-yyyy"/>

                                        <span class="input-group-addon">

                                            <i class="fa fa-calendar bigger-110"></i>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"
                                       for="form-field-1">Brance </label>
                                <div class="col-sm-6">


                                    <select name="BranchAutoId" class="chosen-select form-control"
                                            id="BranchAutoId" data-placeholder="Search by payee">
                                        <option value=""></option>

                                        <?php
                                        echo branch_dropdown(null, $journalVoucher->BranchAutoId);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher ID
                                    <span style="color:red;"> *</span></label>

                                <div class="col-sm-6">

                                    <input type="text" id="form-field-1" name="voucherid" readonly
                                           value="<?php echo $journalVoucher->Accounts_Voucher_No; ?>"
                                           class="form-control" placeholder="Voucher Id Code"/>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-10 col-md-offset-1">

                            <br>

                            <div class="table-header">

                                Select Account Head

                            </div>

                            <table class="table table-bordered table-hover" id="show_item">

                                <thead>

                                <tr>

                                    <td style="width:35%" align="center"><strong>Account Head<span
                                                    style="color:red;"> *</span></strong></td>

                                    <td style="width:15%" align="center"><strong>Debit<span style="color:red;"> *</span></strong>
                                    </td>

                                    <td style="width:15%" align="center"><strong>Credit<span
                                                    style="color:red;"> *</span></strong></td>

                                    <td style="width:20%" align="center"><strong>Memo</strong></td>

                                    <td style="width:1%" align="center"><strong>Action</strong></td>

                                </tr>
                                <tr>

                                    <td>

                                        <select class="chosen-select form-control accountId" id="form-field-select-3"
                                                data-placeholder="Search by account head"
                                               >

                                            <option value="" disabled selected></option>

                                            <?php

                                            foreach ($accountHeadList as $key => $head) {

                                                ?>

                                                <optgroup label="<?php echo $head['parentName']; ?>">

                                                    <?php

                                                    foreach ($head['Accountledger'] as $eachLedger) :

                                                        ?>

                                                        <option paytoAccountCode="<?php echo $eachLedger->code; ?>"
                                                                paytoAccountName="<?php echo $eachLedger->parent_name; ?>"
                                                                value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->parent_name . " ( " . $eachLedger->code . " ) "; ?></option>

                                                    <?php endforeach; ?>

                                                </optgroup>

                                                <?php

                                            }

                                            ?>

                                        </select>

                                    </td>

                                    <td><input type="text" class="form-control text-right amountDrValue"
                                               onblur="checkInputValue(this.value)"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                               placeholder="0.00"></td>

                                    <td><input type="text" class="form-control text-right amountCrValue"
                                               onblur="checkInputValue(this.value)"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                               placeholder="0.00"></td>

                                    <td><input type="text" class="form-control text-right memo" placeholder="Memo"></td>

                                    <td><a id="add_item" class="btn btn-info form-control" href="javascript:;"
                                           title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;</a></td>

                                </tr>
                                </thead>

                                <tbody>

                                <?php

                                $totalDebit = 0;

                                $totalCredit = 0;


                                foreach ($generalledger as $key => $eachAccount):

                                    $totalDebit += $eachAccount->GR_DEBIT;

                                    $totalCredit += $eachAccount->GR_CREDIT;

                                    $chartInfo = $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $eachAccount->CHILD_ID);


                                    ?>


                                    <tr class="new_item<?php echo $key + 678 ?>">

                                        <td style="padding-left:15px;"><?php echo $chartInfo->parent_name . ' [ ' . $chartInfo->code . ' ]'; ?>
                                            <input type="hidden" name="account[]"
                                                   value="<?php echo $eachAccount->CHILD_ID; ?>"></td>

                                        <td style="padding-left:15px;" align="right"><input
                                                    id="debitId_<?php echo $key + 678; ?>"
                                                    onblur="checkInputValue(this.value)"
                                                    class="amountDr  text-right form-control" type="text"
                                                    name="amountDr[]" <?php

                                            if (empty($eachAccount->GR_DEBIT) || $eachAccount->GR_DEBIT <= 0) {

                                                echo "readonly";

                                            }

                                            ?> value="<?php echo $eachAccount->GR_DEBIT; ?>"></td>

                                        <td style="padding-left:15px;" align="right">

                                            <input class="amountCr  text-right form-control"
                                                   id="creditId_<?php echo $key + 678; ?>"
                                                   onblur="checkInputValue(this.value)" type="text"
                                                   name="amountCr[]" <?php

                                            if (empty($eachAccount->GR_DEBIT) || $eachAccount->GR_CREDIT <= 0) {

                                                echo "readonly";

                                            }

                                            ?> value="<?php echo $eachAccount->GR_CREDIT; ?>"></td>

                                        <td align="right"><input type="text"
                                                                 class="add_quantity text-right form-control"
                                                                 name="memo[]"
                                                                 value="<?php echo $eachAccount->Reference; ?>"></td>

                                        <td><a del_id="<?php echo $key + 678 ?>"
                                               class="delete_item btn form-control btn-danger" href="javascript:;"
                                               title=""><i class="fa fa-times"></i>&nbsp;</a></td>

                                    </tr>


                                <?php endforeach; ?>


                                </tbody>

                                <tfoot>



                                <tr>

                                    <td align="right"><strong>Sub-Total (In.BDT)</strong></td>

                                    <td align="right"><strong
                                                class="total_dr"><?php echo $totalDebit . '.00'; ?></strong></td>

                                    <td align="right"><strong
                                                class="total_cr"><?php echo $totalCredit . '.00'; ?></strong></td>


                                </tr>

                                </tfoot>

                            </table>

                        </div>

                        <div class="col-md-10">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                    Narration</label>

                                <div class="col-sm-9">

                                    <div class="input-group">

                                        <textarea cols="100" rows="2" name="narration" placeholder="Narration"
                                                  type="text"><?php echo $journalVoucher->Narration; ?></textarea>


                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="clearfix"></div>

                        <div class="clearfix form-actions">

                            <div class="col-md-offset-3 col-md-9">

                                <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">

                                    <i class="ace-icon fa fa-check bigger-110"></i>

                                    Update

                                </button>

                                &nbsp; &nbsp; &nbsp;

                                <button class="btn" type="reset">

                                    <i class="ace-icon fa fa-undo bigger-110"></i>

                                    Reset

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.page-content -->

</div>


<script>


    $(document).on("keyup", ".amountDr", function () {

        var id_arr = $(this).attr('id');

        var id = id_arr.split("_");

        var element_id = id[id.length - 1];


        var debit = parseFloat($("#debitId_" + element_id).val());

        if (isNaN(debit)) {

            debit = 0;

        }

        var credit = parseFloat($("#creditId_" + element_id).val());

        if (isNaN(credit)) {

            credit = 0;

        }


        if ((debit == '' || debit == 0) && (credit == '' || credit == 0)) {

            $("#debitId_" + element_id).attr('readonly', false);

            $("#creditId_" + element_id).attr('readonly', false);


            $("#debitId_" + element_id).val('');

            $("#creditId_" + element_id).val('');


        } else if ((debit == '' || debit == 0) && credit > 0) {

            $("#debitId_" + element_id).attr('readonly', true);

            $("#creditId_" + element_id).attr('readonly', false);


            $("#debitId_" + element_id).val('');

            //$("#creditId_"+element_id).val('');


        } else {

            $("#debitId_" + element_id).attr('readonly', false);

            $("#creditId_" + element_id).attr('readonly', true);

            //   $("#debitId_"+element_id).val('');

            $("#creditId_" + element_id).val('');

        }


    });

    $(document).on("keyup", ".amountCr", function () {

        var id_arr = $(this).attr('id');

        var id = id_arr.split("_");

        var element_id = id[id.length - 1];


        var debit = parseFloat($("#debitId_" + element_id).val());

        if (isNaN(debit)) {

            debit = 0;

        }

        var credit = parseFloat($("#creditId_" + element_id).val());

        if (isNaN(credit)) {

            credit = 0;

        }


        if ((debit == '' || debit == 0) && (credit == '' || credit == 0)) {

            $("#debitId_" + element_id).attr('readonly', false);

            $("#creditId_" + element_id).attr('readonly', false);


            $("#debitId_" + element_id).val('');

            $("#creditId_" + element_id).val('');


        } else if ((debit == '' || debit == 0) && credit > 0) {

            $("#debitId_" + element_id).attr('readonly', true);

            $("#creditId_" + element_id).attr('readonly', false);


            $("#debitId_" + element_id).val('');

            //$("#creditId_"+element_id).val('');


        } else {

            $("#debitId_" + element_id).attr('readonly', false);

            $("#creditId_" + element_id).attr('readonly', true);

            //   $("#debitId_"+element_id).val('');

            $("#creditId_" + element_id).val('');

        }


    });


    function isconfirm2() {


        var paymentDate = $("#journalDate").val();

        var payType = $("#payType").val();

        var miscellaneous = $("#miscellaneous").val();

        var customerId = $("#customerId").val();

        var supplierId = $("#supplierId").val();

        var payForm = $("#payForm").val();

        var total_dr = parseFloat($(".total_dr").text());

        if (isNaN(total_dr)) {

            total_dr = 0;

        }

        var total_cr = parseFloat($(".total_cr").text());

        if (isNaN(total_cr)) {

            total_cr = 0;

        }

        if (payType == '') {

            swal("Select Receive From!", "Validation Error!", "error");

        } else if (paymentDate == '') {

            swal("Select Journal Date!", "Validation Error!", "error");

        } else if (total_dr == '') {

            swal("Please Add Debit Amount!", "Validation Error!", "error");

        } else if (total_cr == '') {

            swal("Please Add Credit Amount", "Validation Error!", "error");

        } else if (total_cr != total_dr) {

            swal("Debit Amount and Credit Amount Must Be Same!!", "Validation Error!", "error");

        } else {

            swal({

                    title: "Are you sure ?",

                    text: "You won't be able to revert this!",

                    showCancelButton: true,

                    confirmButtonColor: '#73AE28',

                    cancelButtonColor: '#d33',

                    confirmButtonText: 'Yes',

                    cancelButtonText: "No",

                    closeOnCancel: true,

                    closeOnConfirm: true,

                    type: 'success'

                },

                function (isConfirm) {

                    if (isConfirm) {

                        $("#publicForm").submit();

                    } else {

                        return false;

                    }

                });

        }

    }

</script>


<script>

    $(document).on("keyup", ".amountDr", function () {


        findAmountDr();

        findAmountCr();

        checkValidation();

    });

    $(document).on("keyup", ".amountCr", function () {


        findAmountDr();

        findAmountCr();

        checkValidation();

    });


    function checkInputValue(value) {

        var debit = parseFloat($(".amountDrValue").val());

        if (isNaN(debit)) {

            debit = 0;

        }

        var credit = parseFloat($(".amountCrValue").val());

        if (isNaN(credit)) {

            credit = 0;

        }


        if (debit != '' || credit != '') {

            if (debit == '') {

                $(".amountDrValue").attr('disabled', true);

            } else {

                $(".amountCrValue").attr('disabled', true);

            }


        } else {

            $(".amountDrValue").attr('disabled', false);

            $(".amountCrValue").attr('disabled', false);

        }


    }


    var checkValidation = function () {

        var totalDr = parseFloat($(".total_dr").text());

        var totalCr = parseFloat($(".total_cr").text());


        if (isNaN(totalDr)) {

            totalDr = 0;

        }

        if (isNaN(totalCr)) {

            totalCr = 0;

        }


        if (totalDr == totalCr) {

            $("#subBtn").attr('disabled', false);


        } else {

            $("#subBtn").attr('disabled', true);


        }


    };


    function selectPayType(payid) {


        var url = '<?php echo site_url("FinaneController/getPayUserList") ?>';

        $.ajax({

            type: 'POST',

            url: url,

            data: {'payid': payid},

            success: function (data) {

                $("#searchValue").html(data);

                $("#oldValue").hide();

                $('.chosenRefesh').chosen();

                $(".chosenRefesh").trigger("chosen:updated");

            }

        });


    }


    function saveNewSupplier() {

        var url = '<?php echo site_url("SetupController/saveNewSupplier") ?>';

        $.ajax({

            type: 'POST',

            url: url,

            data: $("#publicForm2").serializeArray(),

            success: function (data) {

                $('#myModal').modal('toggle');

                $('#hideNewSup').hide();

                $('#supplierid').chosen();

                //$('#customerid option').remove();

                $('#supplierid').append($(data));

                $("#supplierid").trigger("chosen:updated");

            }

        });

    }


    function checkDuplicatePhone(phone) {

        var url = '<?php echo site_url("SetupController/checkDuplicateEmail") ?>';

        $.ajax({

            type: 'POST',

            url: url,

            data: {'phone': phone},

            success: function (data) {

                if (data == 1) {

                    $("#subBtn2").attr('disabled', true);

                    $("#errorMsg").show();

                } else {

                    $("#subBtn2").attr('disabled', false);

                    $("#errorMsg").hide();

                }

            }

        });


    }


</script>


<script type="text/javascript">


    $('.amountDrValue').change(function () {

        var drValue = parseFloat($(this).val());

        if (isNaN(drValue)) {

            drValue = 0;

        }

        if (drValue != '') {

            $(this).val(drValue).toFixed(2);

        }

    });


    $('.amountCrValue').change(function () {


        var crValue = parseFloat($(this).val());

        if (isNaN(crValue)) {

            crValue = 0;

        }

        if (crValue != '') {

            $(this).val(crValue).toFixed(2);

        }


    });

    var findAmountDr = function () {

        var ttotal_amountDr = 0;

        $.each($('.amountDr'), function () {

            amount = $(this).val();

            amount = Number(amount);

            ttotal_amountDr += amount;

        });

        $('.total_dr').html(parseFloat(ttotal_amountDr).toFixed(2));

    };


    var findAmountCr = function () {

        var ttotal_amountCr = 0;

        $.each($('.amountCr'), function () {

            amount = $(this).val();

            amount = Number(amount);

            ttotal_amountCr += amount;

        });

        $('.total_cr').html(parseFloat(ttotal_amountCr).toFixed(2));

    };


    $(document).ready(function () {


        var j = 0;

        $("#add_item").click(function () {

            var accountId = $('.accountId').val();


            var accountName = $(".accountId").find('option:selected').attr('paytoAccountName');

            var accountCode = $(".accountId").find('option:selected').attr('paytoAccountCode');

            var amountDr = $('.amountDrValue').val();

            var amountCr = $('.amountCrValue').val();

            var memo = $('.memo').val();

            if (accountId == '' || accountId == null) {

                productItemValidation("Account Head can't be empty.");

                return false;

            } else if (amountDr == '' && amountCr == '') {

                productItemValidation("Debit or Credit amount can't be empty.");

                return false;

            } else {

                if (amountDr == '' || amountDr == 0) {

                    $("#show_item tbody").append('<tr class="new_item' + accountId + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="account[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amountDr form-control text-right" type="text"  name="amountDr[]" onblur="checkInputValue(this.value)" id="debitId_' + j + '" value="' + amountDr + '" readonly></td><td style="padding-left:15px;"  align="right"><input class="amountCr  form-control text-right" type="text"  onblur="checkInputValue(this.value)"  name="amountCr[]"  id="creditId_' + j + '"  value="' + amountCr + '"></td><td align="right"><input type="text" class="add_quantity  form-control text-right" name="memo[]" value="' + memo + '"></td><td><a del_id="' + accountId + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');


                } else {

                    $("#show_item tbody").append('<tr class="new_item' + accountId + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="account[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amountDr form-control text-right" type="text"  name="amountDr[]" onblur="checkInputValue(this.value)" id="debitId_' + j + '" value="' + amountDr + '"  ></td><td style="padding-left:15px;"  align="right"><input class="amountCr  form-control text-right" type="text"  onblur="checkInputValue(this.value)"  name="amountCr[]"  id="creditId_' + j + '" readonly value="' + amountCr + '"></td><td align="right"><input type="text" class="add_quantity  form-control text-right" name="memo[]" value="' + memo + '"></td><td><a del_id="' + accountId + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');

                }


                //$("#show_item tbody").append('<tr class="new_item' + accountId + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' +  accountCode    + ' ] ' + '<input type="hidden" name="account[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right">' + amountDr + '<input class="amountDr" type="hidden"  name="amountDr[]" value="' + amountDr + '"></td><td style="padding-left:15px;"  align="right">' + amountCr + '<input class="amountCr" type="hidden"  name="amountCr[]" value="' + amountCr + '"></td><td align="right">' + memo + '<input type="hidden" class="add_quantity" name="memo[]" value="' + memo + '"></td><td><a del_id="' + accountId + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');

            }

            $(".amountDrValue").attr('disabled', false);

            $(".amountCrValue").attr('disabled', false);


            $('.amountDrValue').val('');

            $('.amountCrValue').val('');

            $('.memo').val('');

            $('.accountId').val('').trigger('chosen:updated');

            j++;

            findAmountDr();

            findAmountCr();

            checkValidation();


        });

        $(document).on('click', '.delete_item', function () {


            var id = $(this).attr("del_id");

            swal({

                    title: "Are you sure ?",

                    text: "You won't be able to revert this!",

                    showCancelButton: true,

                    confirmButtonColor: '#73AE28',

                    cancelButtonColor: '#d33',

                    confirmButtonText: 'Yes',

                    cancelButtonText: "No",

                    closeOnCancel: true,

                    closeOnConfirm: true,

                    type: 'success'

                },

                function (isConfirm) {

                    if (isConfirm) {

                        $('.new_item' + id).remove();

                        findAmountDr();

                        findAmountCr();

                        checkValidation();

                    } else {

                        return false;

                    }

                });


        });

    });

</script>

