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

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date <span
                                            style="color:red;"> *</span></label>

                                <div class="col-sm-6">

                                    <div class="input-group">

                                        <input class="form-control date-picker" name="date" id="paymentDate" type="text"
                                               value="<?php echo date('d-m-Y', strtotime($receiveVoucher->Accounts_Voucher_Date)); ?>"
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

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher ID
                                    <span style="color:red;"> *</span></label>

                                <div class="col-sm-6">

                                    <input type="text" id="form-field-1" name="voucherid" readonly
                                           value="<?php echo $receiveVoucher->Accounts_Voucher_No; ?>"
                                           class="form-control" placeholder="Voucher Id"/>

                                </div>

                            </div>

                        </div>

                        <div class="clearfix"></div>


            <div class="col-md-6 form-inline" >

                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span
                                style="color:red;">*</span> <?php echo get_phrase('Payee A/C (Cash)') ?> </label>

                    <div class="col-sm-6">

                        <select name="cash"  class="chosen-select form-control" value="0"
                                id="payType" data-placeholder="Search by Payee A/C (Cash)">

                            <option value="" disabled selected></option>
                            <?php
                            foreach ($accountHeadListByCash as $key => $head) {
                                ?>

                                <option
                                    <?php
                                                    if ($getCreditAccountId[0]->CHILD_ID == $head->id  ) {
                                                        echo "selected";
                                                    }
                                                    ?>

                                        value="<?php echo $head->id; ?>"><?php echo $head->parent_name . " ( " . $head->code . " ) "; ?></option>

                                <?php
                            }
                            ?>

                        </select>

                    </div>
                    <div class="form-group">

                       <input type="text" class="form-control text-right cashCr" value="<?php echo $getCreditAccountId[0]->GR_CREDIT;?>" name="cashCr"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   placeholder="0.00">

                    </div>

                </div>

            </div>

                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"
                                       for="form-field-1">Branch </label>
                                <div class="col-sm-6">


                                    <select name="BranchAutoId" class="chosen-select form-control"
                                            id="" data-placeholder="Search by payee">
                                        <option value=""></option>

                                        <?php
                                        foreach ($branch as $key => $value) {
                                            if ($receiveVoucher->BranchAutoId == $value->branch_id) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                            echo '<option value="' . $value->branch_id . '"' . $selected . '>' . ' ' . $value->branch_name . '</option>';
                                        }
                                        ?>


                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>



                        <div class="col-md-6 form-inline">
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span
                                style="color:red;">*</span> <?php echo get_phrase('Payee A/C (Bank)') ?> </label>
                    <div class="col-sm-6">
                        <select name="accountDr" class="chosen-select form-control checkAccountBalance" id="payForm"
                                data-placeholder="Search by Account Head" >
                            <option value="" disabled selected></option>
                            <?php
                            foreach ($accountHeadListByBank as $key => $head) {
                                ?>

                                <option   <?php
                                                    if ($getCreditAccountId[1]->CHILD_ID == $head->id  ) {
                                                        echo "selected";
                                                    }
                                                    ?>
                                        value="<?php echo $head->id; ?>"><?php echo $head->parent_name . " ( " . $head->code . " ) "; ?></option>

                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control text-right bankCr" name="bankCr" value="<?php echo $getCreditAccountId[1]->GR_CREDIT;?>"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   placeholder="0.00">
                    </div>
                </div>

            </div>

              <div class="clearfix"></div>
            <div class="col-md-3"></div>
            <div class="col-md-6 form-inline">

                <div class="form-group">
                    <?php
                           $totalAmount = 0;

                                foreach ($getCreditAccountId as $key => $eachAccount):

                                    $totalAmount += $eachAccount->GR_CREDIT;
                                endforeach;


                    ?>

                                <div class="col-sm-6">
                                    <label class="col-sm-6 control-label no-padding-right" for="form-field-1"><span
                                style="color:red;"></span> <?php echo get_phrase('Total') ?> </label>

                    </div>
                </div>
                <div class="form-group">


                    <strong class="tttotal_amount22  pul-right"><?php echo $totalAmount . '.00'; ?></strong>
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

                                    <td style="width:40%" align="center"><strong>Account Head<span
                                                    style="color:red;"> *</span></strong></td>

                                    <td style="width:20%" align="center"><strong>Amount<span
                                                    style="color:red;"> *</span></strong></td>

                                    <td style="width:20%" align="center"><strong>Memo</strong></td>

                                    <td style="width:20%" align="center"><strong>Action</strong></td>

                                </tr>

                                </thead>

                                <tbody>
                                     <tr>

                                    <td>

                                        <select class="chosen-select form-control paytoAccount" id="form-field-select-3"
                                                data-placeholder="Search by Account Head"
                                                onchange="check_pretty_cash(this.value)">

                                            <option value="" disabled selected></option>

                                            <?php

                                            foreach ($accountHeadList as $key => $head) {

                                                ?>

                                                <optgroup label="<?php echo $head['parentName']; ?>">

                                                    <?php

                                                    foreach ($head['Accountledger'] as $eachLedger) :

                                                        // if (($eachLedger->chartId == '54' && $eachLedger->parentId == '42') || $eachLedger->parentId == '55'):

                                                        ?>


                                                        <option paytoAccountCode="<?php echo $eachLedger->code; ?>"
                                                                paytoAccountName="<?php echo $eachLedger->parent_name; ?>"
                                                                value="<?php echo $eachLedger->id; ?>"><?php echo $eachLedger->parent_name . " ( " . $eachLedger->code . " ) "; ?></option>

                                                        <?php

                                                        // endif;

                                                    endforeach;

                                                    ?>

                                                </optgroup>

                                                <?php

                                            }

                                            ?>

                                        </select>

                                    </td>

                                    <td><input type="text" class="form-control text-right amountt"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                               placeholder="0.00"></td>

                                    <td><input type="text" class="form-control text-right memo" placeholder="Memo"></td>

                                    <td><a id="add_item" class="btn btn-info form-control" href="javascript:;"
                                           title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>

                                </tr>

                                <?php

                                $totalAmount2 = 0;

                                foreach ($getDebitAccountId as $key => $eachAccount):

                                    $totalAmount2 += $eachAccount->GR_DEBIT;

                                    // $chartInfo = $this->Common_model->tableRow('chartofaccount', 'chart_id', $eachAccount->account);

                                    $chartInfo = $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $eachAccount->CHILD_ID);

                                    ?>

                                    <tr class="new_item<?php echo $key + 565; ?>">

                                        <td style="padding-left:15px;"><?php echo $chartInfo->parent_name . ' [ ' . $chartInfo->code . ' ]'; ?>
                                            <input type="hidden" name="accountCr[]"
                                                   value="<?php echo $eachAccount->CHILD_ID; ?>"></td>

                                        <td style="padding-left:15px;" align="right"><input
                                                    class="amount form-control text-right" type="text" name="amountCr[]"
                                                    value="<?php echo $eachAccount->GR_DEBIT; ?>"></td>

                                        <td align="right"><input type="text"
                                                                 class="add_quantity form-control text-right"
                                                                 name="memoCr[]"
                                                                 value="<?php echo $eachAccount->Reference; ?>"></td>

                                        <td><a del_id="<?php echo $key + 565; ?>"
                                               class="delete_item btn form-control btn-danger" href="javascript:;"
                                               title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>

                                <tfoot>



                                <tr>

                                    <td align="right"><strong>Sub-Total(BDT)</strong></td>

                                    <td align="right"><strong
                                                class="tttotal_amount"><?php echo $totalAmount2 . '.00'; ?></strong></td>

                                    <td align="right"><strong class=""></strong></td>

                                    <td></td>

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
                                                  type="text"><?php echo $receiveVoucher->Narration; ?></textarea>

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


    function isconfirm2() {


        var paymentDate = $("#paymentDate").val();

        var payType = $("#payType").val();

        var BranchAutoId = $("#BranchAutoId").val();

        var payForm = $("#payForm").val();
        var payForm = $("#payForm").val();


        var totalPrice = parseFloat($(".tttotal_amount").text());

        if (isNaN(totalPrice)) {

            totalPrice = 0;

        }


        if (payType == '') {

            swal("Select Cash From!", "Validation Error!", "error");


        } else if (paymentDate == '') {

            swal("Select Payment Date!", "Validation Error!", "error");

        } else if (payType=='') {

            swal("Please Type Account Name", "Validation Error!", "error");

        } else if (payForm == '' || payForm == null) {

            swal("Please Select Bank Head", "Validation Error!", "error");

        } else if (totalPrice == '') {

            swal("Please Select Account Head!", "Validation Error!", "error");

        }else if (totalPrice == '') {

            swal("Please Select Account Head!", "Validation Error!", "error");

        }else if(BranchAutoId ==''){
            swal("Please Select A Branch!", "Validation Error!", "error");
        } else {

            swal({

                    title: "Are you sure ?",

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

                        $("#publicForm").submit();

                    } else {

                        return false;

                    }

                });


        }


    }


</script>



<script>


    $("#subBtn").attr('disabled', false);

    var url = '<?php echo site_url("FinaneController/getPayUserListForUpdate") ?>';

    $.ajax({

        type: 'POST',

        url: url,
        data: {
            'payid': $('#payType').val(), payUserId: '<?php

                if (!empty($receiveVoucher->miscellaneous)) {

                    echo $receiveVoucher->miscellaneous;

                } elseif (!empty($receiveVoucher->customer_id)) {

                    echo $receiveVoucher->customer_id;

                } else {

                    echo $receiveVoucher->supplier_id;

                }

                ?>'
        },

        success: function (data) {

            $("#searchValue").html(data);

            $("#oldValue").hide(1000);

            $('.chosenRefesh').chosen();

            $(".chosenRefesh").trigger("chosen:updated");

        }

    });


    $(document).ready(function () {

        $('.checkAccountBalance').change(function () {

            var accountId = $(this).val();

            // alert(accountId);

            $.ajax({

                type: 'POST',

                data: {account: accountId},

                url: '<?php echo site_url('FinaneController/checkBalanceForPayment'); ?>',

                success: function (result) {


                    $('#accountBalance').html('');

                    $('#accountBalance').html(result);

                }

            });

        });

    });


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


    //1531563473


    //1531564223

    //product sell account id

    //1531566765

    //1531569512

    //1531571161


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


     $(document).on("keyup", ".cashCr", function () {

        findAmountcr();
        checkValidation();

    });
     $(document).on("keyup", ".bankCr", function () {

        findAmountcr();
        checkValidation();

    });

      var findAmountcr = function () {



        var a = parseFloat($('.cashCr').val());
        var b = parseFloat($('.bankCr').val());


        $('.tttotal_amount22').html(parseFloat( a + b ).toFixed(2));

    };





    $(document).on("keyup", ".amount", function () {

        findAmount();

    });


    $('.amountt').change(function () {

        $(this).val(parseFloat($(this).val()).toFixed(2));

    });

    var findAmount = function () {

        var ttotal_amount = 0;

        $.each($('.amount'), function () {

            amount = $(this).val();


            ttotal_amount += amount*1;

        });

        $('.tttotal_amount').html(parseFloat(ttotal_amount).toFixed(2));
        checkValidation();

    };

     var checkValidation = function () {

        var totalDr = $(".tttotal_amount").text();

        var totalCr = $(".tttotal_amount22").text();



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

    $(document).ready(function () {


        $("#add_item").click(function () {

            var accountId = $('.paytoAccount').val();

            var accountName = $(".paytoAccount").find('option:selected').attr('paytoAccountName');

            var accountCode = $(".paytoAccount").find('option:selected').attr('paytoAccountCode');

            var amount = Number($('.amountt').val());

            var memo = $('.memo').val();


            if (accountId == '' || accountId == null) {

                productItemValidation("Account Head can't be empty.");

                return false;

            } else if (amount == 0) {

                productItemValidation("Amount can't be empty.");

                return false;

            } else {

                $("#show_item tbody").append('<tr class="new_item' + accountId + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="accountCr[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amount form-control text-right decimal" type="text"  name="amountCr[]" value="' + amount + '"></td><td align="right"><input type="text" class="add_quantity form-control text-right" name="memoCr[]" value="' + memo + '"></td><td><a del_id="' + accountId + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');

            }

            $('.amountt').val('');

            $('.memo').val('');


            $('.paytoAccount').val('').trigger('chosen:updated');

            findAmount();


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

                    closeOnConfirm: true,

                    closeOnCancel: true,

                    type: 'success'

                },

                function (isConfirm) {

                    if (isConfirm) {

                        $('.new_item' + id).remove();

                        findAmount();

                    } else {

                        return false;

                    }

                });


        });

    });

</script>

