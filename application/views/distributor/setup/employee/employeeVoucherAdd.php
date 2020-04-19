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

<div class="row">

    <div class="col-md-12">

        <form id="publicForm" action="" method="post" class="form-horizontal">

            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red;"> *</span> <?php echo get_phrase('Date') ?>
                    </label>

                    <div class="col-sm-6">

                        <div class="input-group">

                            <input class="form-control date-picker" name="date" id="paymentDate" type="text"
                                   value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"/>

                            <span class="input-group-addon">

                                            <i class="fa fa-calendar bigger-110"></i>

                                        </span>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red;"> *</span> <?php echo get_phrase('Voucher Id') ?>
                    </label>

                    <div class="col-sm-6">

                        <input type="text" id="form-field-1" name="voucherid" readonly value="<?php echo $voucherID; ?>"
                               class="form-control" placeholder="Product Code"/>

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

                                <option paytoAccountCode="<?php echo $head->code; ?>"
                                        paytoAccountName="<?php echo $head->parent_name; ?>"
                                        value="<?php echo $head->id; ?>"><?php echo $head->parent_name . " ( " . $head->code . " ) "; ?></option>

                                <?php
                            }
                            ?>

                        </select>

                    </div>
                    <div class="form-group">

                       <input type="text" class="form-control text-right cashCr" value="0" name="cashCr"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   placeholder="0.00">

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div id="searchValue"></div>

                <div id="oldValue">

                    <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Branch </label>
                    <div class="col-sm-6">
                        <select name="BranchAutoId" class="chosen-select form-control"
                                id="BranchAutoId" data-placeholder="Search by payee">
                            <?php
                            echo branch_dropdown(null, null);
                            ?>

                        </select>
                    </div>
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

                                <option paytoAccountCode="<?php echo $head->code; ?>"
                                        paytoAccountName="<?php echo $head->parent_name; ?>"
                                        value="<?php echo $head->id; ?>"><?php echo $head->parent_name . " ( " . $head->code . " ) "; ?></option>

                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control text-right bankCr" name="bankCr" value="0"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   placeholder="0.00">
                    </div>
                </div>

            </div>


            <div class="clearfix"></div>
            <div class="col-md-3"></div>
            <div class="col-md-6 form-inline">

                <div class="form-group">

                                <div class="col-sm-6">
                                    <label class="col-sm-6 control-label no-padding-right" for="form-field-1"><span
                                style="color:red;"></span> <?php echo get_phrase('Total') ?> </label>

                    </div>
                </div>
                <div class="form-group">


                    <strong class="tttotal_amount22 pul-right"></strong>
                </div>

            </div>


            <div class="col-md-10 col-md-offset-1">

                <br>
                <div class="table-header">

                    <?php echo get_phrase('Payee To') ?><?php echo get_phrase('Select Account Head') ?>

                </div>

                <table class="table table-bordered table-hover" id="show_item">

                    <thead>

                    <tr>

                        <td style="width:40%" align="center">
                            <strong><?php echo get_phrase('Account Head') ?></strong>
                        </td>

                        <td style="width:20%" align="center">
                            <strong><?php echo get_phrase('Amount') ?><span
                                        style="color:red;"> *</span></strong></td>

                        <td style="width:20%" align="center">
                            <strong><?php echo get_phrase('Memo') ?></strong></td>

                        <td style="width:20%" align="center">
                            <strong><?php echo get_phrase('Action') ?></strong></td>

                    </tr>

                    </thead>

                    <tbody>
                         <tr>

                        <td>

                            <select class="chosen-select form-control paytoAccount" id="form-field-select-3"
                                    data-placeholder="Search by Account Head" onchange="check_pretty_cash(this.value)">

                                <option value="" disabled selected></option>

                                <?php
                                foreach ($accountHeadList as $key => $head) {
                                    ?>

                                    <option paytoAccountCode="<?php echo $head->code; ?>"
                                            paytoAccountName="<?php echo $head->parent_name; ?>"
                                            value="<?php echo $head->id; ?>"><?php echo $head->parent_name . " ( " . $head->code . " ) "; ?></option>

                                    <?php
                                }
                                ?>

                            </select>

                        </td>

                        <td><input type="text" class="form-control text-right amountt"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   placeholder="0.00"></td>

                        <td><input type="text" class="form-control text-right memo" placeholder="Memo"></td>

                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i
                                        class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo get_phrase('Add Item') ?></a></td>

                    </tr>
                    </tbody>

                    <tfoot>



                    <tr>

                        <td align="right"><strong><?php echo get_phrase('Sub_Total') ?>(BDT)</strong></td>

                        <td align="right"><strong class="tttotal_amount"></strong></td>

                        <td align="right"><strong class=""></strong></td>

                        <td></td>

                    </tr>

                    </tfoot>

                </table>

            </div>

            <div class="col-md-10">

                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right"
                           for="form-field-1"> <?php echo get_phrase('Narration') ?></label>

                    <div class="col-sm-5">

                        <div class="input-group">

                            <textarea cols="55" rows="2" name="narration" placeholder="Narration"
                                      type="text"></textarea>

                        </div>

                    </div>
                    <div class="col-md-4">

                        <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">

                            <i class="ace-icon fa fa-check bigger-110"></i>

                            <?php echo get_phrase('Save') ?>

                        </button>

                        &nbsp; &nbsp; &nbsp;

                        <button class="btn" type="reset">

                            <i class="ace-icon fa fa-undo bigger-110"></i>

                            <?php echo get_phrase('Reset') ?>

                        </button>

                    </div>

                </div>

            </div>

            <div class="clearfix"></div>


        </form>

    </div>

</div><!-- /.col -->


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

            } else if (amount ==  0) {

                productItemValidation("Amount can't be empty.");

                return false;

            } else {

                $("#show_item tbody").append('<tr class="new_item' + accountId + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="accountCr[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amount text-right form-control decimal" type="text"  name="amountCr[]" value="' + amount + '"></td><td align="right"><input type="text" class="add_quantity form-control text-right" name="memoCr[]" value="' + memo + '"></td><td><a del_id="' + accountId + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');

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
<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>






