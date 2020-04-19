<style>
    table tr td {
        margin: 0px !important;
        padding: 2px !important;
    }

    table tr td tfoot .form-control {
        width: 100%;
        height: 25px;
    }
</style>


<div class="main-content">
    <div class="main-content-inner">
        <!--<div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Inventory</a>
                </li>
                <li >Inventory Damage</li>
                <li class="active">Damage Product Add</li>
              
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="" href="<?php /*echo site_url($this->project . '/damageProduct'); */ ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>-->
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action="" method="post" class="form-horizontal">
                        <table class="mytable table-responsive table table-bordered">
                            <tr>
                                <td style="padding: 10px!important;"
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="form-group  ">
                                            <label class="col-sm-3 control-label no-padding-right"
                                                   for="form-field-1"><span style="color:red;"> *</span> Date</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control date-picker" name="date" id="date"
                                                           type="text" value="<?php echo date('d-m-Y'); ?>"
                                                           data-date-format="dd-mm-yyyy" required/>
                                                    <span class="input-group-addon">
                                                            <i class="fa fa-calendar bigger-110"></i>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px!important;">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="table-header">
                                                    Damage Product Item
                                                </div>
                                                <table class="table table-bordered table-hover" id="show_item">
                                                    <thead>
                                                    <tr>
                                                        <th nowrap style="width:20%;border-radius:10px;" align="center">
                                                            <strong>Product Category<span
                                                                        style="color:red;"> *</span></strong></th>
                                                        <th nowrap style="width:25%;border-radius:10px;" align="center">
                                                            <strong>Product <span style="color:red;"> *</span></strong>
                                                        </th>
                                                        <th nowrap style="width:10%;border-radius:10px;" align="center">
                                                            <strong>Quantity <span style="color:red;"> *</span></strong>
                                                        </th>
                                                        <th nowrap style="width:12%;border-radius:10px;" align="center">
                                                            <strong>Unit Price(BDT) <span
                                                                        style="color:red;"> *</span></strong></th>
                                                        <th nowrap style="width:13%;border-radius:10px;" align="center">
                                                            <strong>Total Price(BDT) <span style="color:red;"> *</span></strong>
                                                        </th>
                                                        <th align="center" style="width:5%;"><strong>Action</strong>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <select id="categoryId"
                                                                    onchange="getProductList2(this.value)"
                                                                    class="chosen-select form-control"
                                                                    id="form-field-select-3"
                                                                    data-placeholder="Search by Category">
                                                                <option value=""></option>
                                                                <?php foreach ($productCat as $key => $eachCat):
                                                                    ?>
                                                                    <option categoryName="<?php echo $eachCat->title; ?>"
                                                                            categoryId="<?php echo $eachCat->category_id; ?>"
                                                                            value="<?php echo $eachCat->category_id; ?>"><?php echo $eachCat->title; ?></option>
                                                                    <?php
                                                                endforeach;
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select id="productID2"
                                                                    onchange="getProductPrice(this.value)"
                                                                    class="chosen-select form-control"
                                                                    id="form-field-select-3"
                                                                    data-placeholder="Search by Product">
                                                                <option value=""></option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text"
                                                                   class="form-control text-right quantity decimal"
                                                                   placeholder="0"></td>
                                                        <td><input type="text"
                                                                   class="form-control text-right rate decimal"
                                                                   placeholder="0.00"></td>
                                                        <td><input type="text"
                                                                   class="form-control text-right price decimal"
                                                                   placeholder="0.00" readonly="readonly"></td>
                                                        <td><a id="add_item" class="btn btn-info form-control"
                                                               href="javascript:;" title="Add Item"><i
                                                                        class="fa fa-plus"></i>&nbsp;&nbsp;Add</a></td>
                                                    </tr>
                                                    </tbody>
                                                    <tfoot>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="clearfix"></div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info"
                                                    type="button">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save
                                            </button>
                                            &nbsp; &nbsp; &nbsp;

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>

                </div><!-- /.col -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>


    function isconfirm2() {
        var date = $("#date").val();
        if (date == '') {
            swal("Select  Date!", "Validation Error!", "error");
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


    function getProductList2(cat_id) {
        $.ajax({
            type: "POST",
            url: baseUrl + "lpg/InventoryController/getProductList",
            data: 'cat_id=' + cat_id,
            success: function (data) {
                $('#productID2').chosen();
                $('#productID2 option').remove();
                $('#productID2').append($(data));
                $("#productID2").trigger("chosen:updated");
            }
        });


    }

    $(document).on("keyup", ".add_quantity", function () {
        var id_arr = $(this).attr('id');
        var id = id_arr.split("_");
        var element_id = id[id.length - 1];
        var quantity = parseFloat($("#qty_" + element_id).val());
        if (isNaN(quantity)) {
            quantity = 0;
        }
        var rate = parseFloat($("#rate_" + element_id).val());
        if (isNaN(rate)) {
            rate = 0;
        }
        var totalAmount = quantity * rate;
        $("#tprice_" + element_id).val(parseFloat(totalAmount).toFixed(2));
        var row_total = 0;
        $.each($('.add_price'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            row_total += quantity;
        });
        $('.total_price').val(parseFloat(row_total).toFixed(2));
        findTotalCal();

    });
    $(document).on("keyup", ".add_rate", function () {
        var id_arr = $(this).attr('id');
        var id = id_arr.split("_");
        var element_id = id[id.length - 1];
        var quantity = parseFloat($("#qty_" + element_id).val());
        if (isNaN(quantity)) {
            quantity = 0;
        }
        var rate = parseFloat($("#rate_" + element_id).val());
        if (isNaN(rate)) {
            rate = 0;
        }
        var totalAmount = quantity * rate;
        $("#tprice_" + element_id).val(parseFloat(totalAmount).toFixed(2));
        var row_total = 0;
        $.each($('.add_price'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            row_total += quantity;
        });
        $('.total_price').val(parseFloat(row_total).toFixed(2));

        findTotalCal();


    });
    /*purchase query start*/
    /*purchase query start*/
    $(document).ready(function () {
        $('.rate').blur(function () {
            var rate = parseFloat($(this).val());
            if (isNaN(rate)) {
                rate = 0;
            }
            $(this).val(parseFloat(rate).toFixed(2));
        });

        $('.quantity').keyup(function () {
            priceCal();
        });
        $('.rate').keyup(function () {
            priceCal();
        });
    });

    function priceCal() {
        var quantity = $('.quantity').val();
        var rate = $('.rate').val();
        $('.price').val(parseFloat(rate * quantity).toFixed(2));
    }

    var findTotalQty = function () {
        var total_quantity = 0;
        $.each($('.add_quantity'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            total_quantity += quantity;
        });
        $('.total_qty').html(parseFloat(total_quantity).toFixed(2));
    };
    var findTotalReturnQty = function () {
        var total_quantity = 0;
        $.each($('.add_return'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            total_quantity += quantity;
        });
        $('.total_return_qty').html(parseFloat(total_quantity).toFixed(2));
    };
    var findTotalRate = function () {
        var total_rate = 0;
        $.each($('.add_rate'), function () {
            rate = $(this).val();
            rate = Number(rate);
            total_rate += rate;
        });
        $('.total_unit_price').html(parseFloat(total_rate).toFixed(2));
    };
    var findTotalPrice = function () {
        var total_price = 0;
        $.each($('.add_price'), function () {
            price = $(this).val();
            price = Number(price);
            total_price += price;
        });
        $('.total_totalPrice').html(parseFloat(total_price).toFixed(2));
        $('.total_price').val(parseFloat(total_price).toFixed(2));

    };
    var returnQty = function () {
        var returnqty = 0;
        $.each($('.add_return'), function () {
            returnq = $(this).val();
            returnq = Number(returnq);
            returnqty += returnq;
        });
        $('.total_return_qty').html(parseFloat(returnqty).toFixed(2));
    };
    var findTotalCal = function () {
        findTotalQty();
        findTotalRate();
        findTotalPrice();
        returnQty();


    };
    $(document).ready(function () {
        var j = 1;
        $("#add_item").click(function () {
            var categoryid = $('#categoryId').val();
            var categoryName = $('#categoryId').find('option:selected').attr('categoryName');
            var productID = $('#productID2').val();
            var productName = $('#productID2').find('option:selected').attr('productName');
            var bundle = $('.bundle').val();
            var quantity = $('.quantity').val();
            var stockQuantity = $('.quantity').attr('placeholder');
            var bundleValue = $('#bundleValue').val();

            var rate = $('.rate').val();
            var price = $('.price').val();

            if (quantity == '') {
                swal("Qty can't be empty.!", "Validation Error!", "error");
                return false;
            } else if (categoryid == 1 && (bundleValue == '' || bundleValue <= 0)) {
                swal("Bundle qty can't be empty.!", "Validation Error!", "error");
                return false;
            } else if (price == '' || price == '0.00') {
                swal("Price can't be empty.!", "Validation Error!", "error");
                return false;
            } else if (productID == '') {
                swal("Product id can't be empty.!", "Validation Error!", "error");
                return false;
            } else {

                var rowCount = $('#show_item tr').length;
                if (rowCount == 2) {

                    $("#show_item tfoot").prepend('<tr><td colspan="2" align="right"> Total </td><td align="right"><span class="total_qty">0.00</span></td><td align="right"><span class="total_unit_price">0.00</span></td><td align="right"><span class="total_totalPrice">0.00</span></td></tr>');
                }

                if (categoryid == 1) {
                    $("#show_item tfoot").prepend('<tr class="new_item' + j + '"><td style="padding-left:15px;">' + categoryName + '<input type="hidden" name="category_id[]" value="' + categoryid + '"> </td><td style="padding-left:15px;">' + productName + ' <input type="hidden"  name="product_id[]" value="' + productID + '"></td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" placeholder="'+ stockQuantity +'" id="qty_' + j + '" name="quantity[]" value="' + quantity + '"></td><td align="right"><input type="text" id="rate_' + j + '" class="add_rate form-control decimal text-right" name="rate[]" value="' + rate + '"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tprice_' + j + '" readonly name="price[]" value="' + price + '"></td><td><a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
                } else {
                    $("#show_item tfoot").prepend('<tr class="new_item' + j + '"><td style="padding-left:15px;">' + categoryName + '<input type="hidden" name="category_id[]" value="' + categoryid + '"> </td><td style="padding-left:15px;">' + productName + ' <input type="hidden"  name="product_id[]" value="' + productID + '"></td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" placeholder="'+ stockQuantity +'" id="qty_' + j + '" name="quantity[]" value="' + quantity + '"></td><td align="right"><input type="text" id="rate_' + j + '" class="add_rate form-control decimal text-right" name="rate[]" value="' + rate + '"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tprice_' + j + '" readonly name="price[]" value="' + price + '"></td><td><a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
                }

                j++;
            }
            $('.quantity').val('');
            $('.quantity').attr('placeholder','0');
            $('.rate').val('');
            $('.price').val('');

            $('#productID2').val('').trigger('chosen:updated');
            $('#categoryId').val('').trigger('chosen:updated');
            //$('#productUnit').val('').trigger('chosen:updated');
            findTotalCal();

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
                        findTotalCal();

                    } else {
                        return false;
                    }
                });
        });
    });

    //get product purchases price
    function getProductPrice(product_id) {

        var productCatID = $('#productID').find('option:selected').attr('categoryId');
        if (productCatID == 2) {
            $(".returnAble").attr('readonly', false);
        } else {
            $(".returnAble").attr('readonly', true);
        }

        $.ajax({
            type: "POST",
            url: baseUrl + "FinaneController/getProductPrice",
            data: 'product_id=' + product_id,
            success: function (data) {
                if (data != '0.00' && data >= 1) {
                    $('.rate').val(data);
                } else {
                    $('.rate').val('');
                }
            }, complete: function () {
                $.ajax({
                    type: "POST",
                    url: baseUrl + "lpg/InvProductController/getProductStock",
                    data: {product_id: product_id, category_id: productCatID, ispackage: 0},
                    success: function (data) {

                        var mainStock = parseFloat(data);
                        if (isNaN(mainStock)) {
                            mainStock = 0;
                        }


                        if (data != '') {

                            $(".quantity").attr("disabled", false);
                            if (mainStock <= 0) {
                                $(".quantity").attr("disabled", true);
                                $(".quantity").attr("placeholder", "0 ");
                            } else {
                                $(".quantity").attr("disabled", false);
                                $(".quantity").attr("placeholder", "" + mainStock);
                            }
                        } else {
                            $("#stockQty").val('');
                            $(".quantity").attr("disabled", true);
                            $(".quantity").attr("placeholder", "0");
                        }
                    }
                });
            }
        });
    }

    /*purchase query start*/


</script>


