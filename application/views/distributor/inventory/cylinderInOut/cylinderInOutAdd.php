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
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Cylinder Exchange Add
                </div>
            </div>
            <div class="portlet-body">

                <div class="row">

                    <div class="col-md-12">


                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right" for="form-field-1">

                                        <?php echo get_phrase('Branch') ?></label>
                                    <div class="col-sm-6">
                                        <select name="branchId" class="chosen-select form-control"
                                                id="BranchAutoId" data-placeholder="Select Branch">
                                            <option value=""></option>
                                            <?php
                                            // come from branch_dropdown_helper
                                            echo branch_dropdown(null, null);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                        Date</label>

                                    <div class="col-sm-6">

                                        <div class="input-group">

                                            <input class="form-control date-picker" name="date" id="id-date-picker-1"
                                                   type="text" value="<?php echo date('d-m-Y'); ?>"
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

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher
                                        ID</label>

                                    <div class="col-sm-6">

                                        <input type="text" id="form-field-1" name="voucherid" readonly
                                               value="<?php echo $voucherID; ?>" class="form-control"
                                               placeholder="Product Code"/>

                                    </div>

                                </div>

                            </div>

                            <div class="clearfix"></div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span
                                                style="color:red;">*</span> Payee </label>

                                    <div class="col-sm-6">

                                        <select name="payType" onchange="selectPayType(this.value)"
                                                class="chosen-select form-control" id="form-field-select-3"
                                                data-placeholder="Search by payee">

                                            <option value=""></option>


                                            <option value="2">Customer</option>

                                            <option value="3">Supplier</option>

                                        </select>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div id="searchValue"></div>

                                <div id="oldValue">

                                    <div class="form-group">

                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span
                                                    style="color:red;">*</span> Payee To </label>

                                        <div class="col-sm-6">

                                            <select id="productID" onchange="getProductPrice(this.value)"
                                                    class="chosen-select form-control" id="form-field-select-3"
                                                    data-placeholder="Search by Name">

                                                <option value=""></option>

                                            </select>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="clearfix"></div>


                            <div class="col-md-12">

                                <br>

                                <div class="table-header">

                                    Select Product Item

                                </div>

                                <table class="table table-bordered table-hover" id="show_item">

                                    <thead>

                                    <tr>

                                        <td style="width:30%" align="center"><strong>Product</strong></td>
                                        <td style="width:20%" align="center"><strong>Price</strong></td>

                                        <td style="width:10%" align="center"><strong>In</strong></td>

                                        <td style="width:10%" align="center"><strong>Out</strong></td>
                                        <td style="width:10%" align="center"><strong>Total Price</strong></td>

                                        <td style="width:10%" align="center"><strong>Action</strong></td>

                                    </tr>
                                    <tr>

                                        <td>

                                            <select class="chosen-select form-control paytoAccount"
                                                    onchange="getProductPrice(this.value)" id=""
                                                    data-placeholder="Search by Product Name">
                                                <option value=""></option>

                                                <?php

                                                foreach ($Cylinder as $key => $eahcProduct) {

                                                    $brandName = $this->Common_model->tableRow('brand', 'brandId', $eahcProduct->brand_id)->brandName;

                                                    ?>

                                                    <option paytoAccountName="<?php echo $eahcProduct->productName . " ( " . $brandName . " ) "; ?>"
                                                            value="<?php echo $eahcProduct->product_id; ?>"><?php echo $eahcProduct->productName . " ( " . $brandName . " ) "; ?></option>

                                                    <?php

                                                }

                                                ?>

                                            </select>

                                        </td>

                                        <td><input type="text" class="form-control text-right productPrice"
                                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                   placeholder="0"></td>
                                        <td><input type="text" class="form-control text-right quantityIn"
                                                   onblur="checkInputValue(this.value)"
                                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                   placeholder="0"></td>

                                        <td><input type="text" class="form-control text-right quantityOut"
                                                   onblur="checkInputValue(this.value)"
                                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                   placeholder="0"></td>
                                        <td><input type="text" class="form-control text-right totalPrice"
                                                   readonly="true"
                                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                   placeholder="0"></td>

                                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;"
                                               title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;</a></td>

                                    </tr>
                                    </thead>

                                    <tbody></tbody>

                                    <tfoot>



                                    <tr>

                                        <td align="right"><strong>Total(BDT)</strong></td>
                                        <td align="right"></td>
                                        <td align="right"><strong class="totalQtyIn"></strong></td>

                                        <td align="right"><strong class="totalQtyOut"></strong></td>

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
                                                      type="text"></textarea>


                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="clearfix"></div>

                            <div class="clearfix form-actions">

                                <div class="col-md-offset-3 col-md-9">

                                    <button onclick="return confirmSwat()" id="subBtn" class="btn btn-info"
                                            type="button">

                                        <i class="ace-icon fa fa-check bigger-110"></i>

                                        Save

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

            </div>
        </div>
    </div>
</div><!-- /.row -->


<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>


<script>


    //get product purchases price
    function getProductPrice(product_id) {


        $.ajax({
            type: "POST",
            url: baseUrl + "FinaneController/getProductPrice",
            data: 'product_id=' + product_id,
            success: function (data) {
                if (data != '0.00' && data >= 1) {
                    $('.productPrice').val(data);
                } else {
                    $('.productPrice').val('');
                }
            }
        });
    }

    function checkOverAmount(amount) {

        //        var closeAmount=  Number($("#closeAmount").val());

        //        if(isNaN(closeAmount) || closeAmount == ''){

        //             productItemValidation("Please Select Pay.from(CR) Account!");

        //        }


        var total_amount = 0;

        $.each($('.amount3'), function () {

            quantity = $(this).val();

            quantity = Number(quantity);

            total_amount += quantity;

        });

        if (closeAmount < total_amount) {

            $("#subBtn").attr('disabled', true);

            productItemValidation("Account Balance Not Available!");

            this.value = '';

            $(this).val(0);

        } else {

            $("#subBtn").attr('disabled', false);

        }

    }


    function selectPayType(payid) {


        var url = '<?php echo site_url("FinaneController/getPayUserList") ?>';

        $.ajax({

            type: 'POST',

            url: url,

            data: {'payid': payid},

            success: function (data) {

                $("#searchValue").html(data);

                $("#oldValue").hide(1000);

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

                $('#hideNewSup').hide(1000);

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

                    $("#errorMsg").show(1000);

                } else {

                    $("#subBtn2").attr('disabled', false);

                    $("#errorMsg").hide(1000);

                }

            }

        });


    }


</script>

<script type="text/javascript">


    $('.amountt').change(function () {

        $(this).val(parseFloat($(this).val()).toFixed(2));

    });
    $('.quantityIn').keyup(function () {
        priceCal();
    });
    $('.quantityOut').keyup(function () {
        priceCal();
    });

    function priceCal() {
        var quantityIn = parseFloat($(".quantityIn").val());

        if (isNaN(quantityIn)) {

            quantityIn = 0;

        }

        var quantityOut = parseFloat($(".quantityOut").val());
        var productPrice = parseFloat($(".productPrice").val());

        if (isNaN(quantityOut)) {

            quantityOut = 0;

        }


        if (quantityIn != '' || quantityOut != '') {

            if (quantityIn == '') {

                $(".totalPrice").val((quantityOut*productPrice).toFixed(2));

            } else {

                $(".totalPrice").val((quantityIn*productPrice).toFixed(2));

            }


        } else {

            $(".quantityIn").attr('disabled', false);

            $(".quantityOut").attr('disabled', false);

        }
    }

    function qtySum() {

        ttqtyIn();

        ttqtyOut();

    }

    var ttqtyIn = function () {

        var ttotalInQTY = 0;

        $.each($('.qtyIn'), function () {

            qty = $(this).val();

            qty = Number(qty);

            ttotalInQTY += qty;

        });

        $('.totalQtyIn').html(parseFloat(ttotalInQTY));

    };

    var ttqtyOut = function () {

        var ttotalInQTY = 0;

        $.each($('.qtyOut'), function () {

            qty = $(this).val();

            qty = Number(qty);

            ttotalInQTY += qty;

        });

        $('.totalQtyOut').html(parseFloat(ttotalInQTY));

    };

    function checkInputValue(value) {

        var quantityIn = parseFloat($(".quantityIn").val());

        if (isNaN(quantityIn)) {

            quantityIn = 0;

        }

        var quantityOut = parseFloat($(".quantityOut").val());

        if (isNaN(quantityOut)) {

            quantityOut = 0;

        }


        if (quantityIn != '' || quantityOut != '') {

            if (quantityIn == '') {

                $(".quantityIn").attr('disabled', true);

            } else {

                $(".quantityOut").attr('disabled', true);

            }


        } else {

            $(".quantityIn").attr('disabled', false);

            $(".quantityOut").attr('disabled', false);

        }


    }

    $(document).ready(function () {


        $("#add_item").click(function () {


            var accountId = $('.paytoAccount').val();

            var accountName = $(".paytoAccount").find('option:selected').attr('paytoAccountName');


            var totalPrice = 0;
            var qtyIn = parseFloat($('.quantityIn').val());

            var qtyOut = parseFloat($('.quantityOut').val());
            var productPrice = parseFloat($('.productPrice').val());

            if (isNaN(qtyIn)) {

                qtyIn = 0;

            }

            if (isNaN(qtyOut)) {

                qtyOut = 0;

            }
            if (qtyIn > 0) {
                totalPrice = qtyIn * productPrice;
            }
            if (qtyOut > 0) {
                totalPrice = qtyOut * productPrice;
            }

            if (accountId == '' || accountId == null) {

                productItemValidation("Product Name can't be empty.");

                return false;

            } else {

                var tr = '<tr class="new_item' + accountId + '">' +
                    '<td style="padding-left:15px;">' + accountName + '<input type="hidden" name="productId[]" value="' + accountId + '"></td>' +
                    '<td style="padding-left:15px;"  align="right">' + productPrice + '<input class="" type="hidden"  name="productPrice[]" value="' + productPrice + '"></td>' +
                    '<td style="padding-left:15px;"  align="right">' + qtyIn + '<input class="qtyIn" type="hidden"  name="qtyIn[]" value="' + qtyIn + '"></td>' +
                    '<td style="padding-left:15px;"  align="right">' + qtyOut + '<input class="qtyOut" type="hidden"  name="qtyOut[]" value="' + qtyOut + '"></td>' +
                    '<td style="padding-left:15px;"  align="right">' + totalPrice + '<input class="totalPrice" type="hidden"  name="totalPrice[]" value="' + totalPrice + '"></td>' +
                    '<td><a del_id="' + accountId + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>';

                $("#show_item tbody").prepend(tr);
                tr = "";

            }

            $('.quantityIn').val('');

            $('.quantityOut').val('');
            $('.productPrice').val('');

            $('.paytoAccount').val('').trigger('chosen:updated');

            $(".quantityIn").attr('disabled', false);

            $(".quantityOut").attr('disabled', false);
            qtySum();


        });

        $(document).on('click', '.delete_item', function () {

            if (confirm("Are you sure?")) {

                var id = $(this).attr("del_id");

                $('.new_item' + id).remove();

                qtySum();

            }

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





