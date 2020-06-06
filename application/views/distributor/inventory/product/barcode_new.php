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


            <div class="col-md-10 col-md-offset-1">

                <br>
                <div class="table-header">

                    <?php echo get_phrase('Select Product') ?>

                </div>

                <table class="table table-bordered table-hover" >

                    <thead>

                    <tr>

                        <td style="width:20%" align="center">
                            <strong><?php echo get_phrase('Category') ?></strong>
                        </td>

                        <td style="width:40%" align="center">
                            <strong><?php echo get_phrase('Product') ?><span
                                        style="color:red;"> *</span></strong></td>

                        <td style="width:20%" align="center">
                            <strong><?php echo get_phrase('Qty') ?></strong></td>

                        <td style="width:20%" align="center">
                            <strong><?php echo get_phrase('Action') ?></strong></td>

                    </tr>
                    <tr>

                        <td>

                            <select id="category_id" name="category" onchange="getProductList(this.value)"
                                    class="chosen-select form-control category_id"
                                    data-placeholder="Search Category">
                                <option></option>
                                <option <?php
                                if (!empty($categoryID) && $categoryID == 'all') {
                                    echo "selected";
                                }
                                ?> value="all">All
                                </option>
                                <?php foreach ($categoryList as $key => $each_info): ?>
                                    <option <?php
                                    if (!empty($categoryID) && $categoryID == $each_info->category_id): echo "selected";
                                    endif;
                                    ?> value="<?php echo $each_info->category_id; ?>"><?php echo $each_info->title; ?></option>
                                <?php endforeach; ?>
                            </select>

                        </td>

                        <td>
                            <select id="productID" name="productid"
                                    class="chosen-select form-control productID"
                                    data-placeholder="Search by Product">
                                <option <?php
                                if (!empty($productid) && $productid == 'all') {
                                    echo "selected";
                                }
                                ?> value="all">All
                                </option>
                            </select>

                        </td>

                        <td><input type="text" class="form-control text-right qty" placeholder="Memo"></td>

                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i
                                        class="fa fa-plus"></i>&nbsp;</a></td>

                    </tr>
                    </thead>

                    <tbody id="show_item"></tbody>

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


        var miscellaneous = $("#miscellaneous").val();

        var customerId = $("#customerId").val();
        var BranchAutoId = $("#BranchAutoId").val();

        var supplierId = $("#supplierId").val();

        var payForm = $("#payForm").val();
        var payForm = $("#payForm").val();


        var totalPrice = parseFloat($(".tttotal_amount").text());

        if (isNaN(totalPrice)) {

            totalPrice = 0;

        }




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


</script>


<script>


    $(document).ready(function () {
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });

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

            amount = Number(amount);

            ttotal_amount += amount;

        });

        $('.tttotal_amount').html(parseFloat(ttotal_amount).toFixed(2));

    };


    $(document).ready(function () {


        $("#add_item").click(function () {

            var category_id = $('.category_id').val();
            var productID = $('.productID').val();
            var qty = $('.qty').val();

            $.ajax({
                type: "POST",
                url: baseUrl + "lpg/ProductBarcodeController/createProductListForBarcode",
                //data: 'cat_id=' + cat_id,
                data: {category_id: category_id, productID: productID, qty: qty},
                success: function (data) {
                    $('#show_item').html(data);
                    console.log(data);
                }
            });


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
    $(document).ready(function () {
        getProductList('all');
    });

    function getProductList(cat_id) {

        $.ajax({
            type: "POST",
            url: baseUrl + "lpg/InvProductController/getProductListForBarcode",
            data: 'cat_id=' + cat_id,
            //data: {cat_id:cat_id, quantity:quantity}
            success: function (data) {

                $('#productID').chosen();
                $('#productID option').remove();
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
            }
        });
    }
</script>






