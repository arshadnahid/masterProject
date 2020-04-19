<style>

    table tr td {

        margin: 2px !important;

        padding: 2px !important;

    }

</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Inventory Adjustment 
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm" action="" method="post" class="form-horizontal">

                            <div class="col-md-5">

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Adjustment
                                        ID</label>

                                    <div class="col-sm-6">

                                        <input type="text" id="form-field-1" name="voucherid" readonly
                                               value="<?php echo $voucherID; ?>" class="form-control"
                                               placeholder="Product Code"/>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-5">

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Adjustment
                                        Date</label>

                                    <div class="col-sm-6">

                                        <div class="input-group">

                                            <input class="form-control date-picker" name="purchasesDate"
                                                   id="id-date-picker-1" type="text" value="<?php echo date('d-m-Y'); ?>"
                                                   data-date-format="dd-mm-yyyy"/>

                                            <span class="input-group-addon">

                                                <i class="fa fa-calendar bigger-110"></i>

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="clearfix"></div>


                            <div class="col-md-10 col-md-offset-1">

                                <div class="table-header">

                                    Product Item

                                </div>

                                <table class="table table-bordered table-hover" id="show_item">

                                    <thead>

                                        <tr>

                                            <td style="width:20%" align="center"><strong>Product Category</strong></td>

                                            <td style="width:20%" align="center"><strong>Product</strong></td>

                                            <td style="width:15%" align="center"><strong>Quantity</strong></td>

                                            <td style="width:15%" align="center"><strong>Unit Price(BDT) </strong></td>

                                            <td style="width:15%" align="center"><strong>Total Price(BDT)</strong></td>

                                            <td style="width:15%" align="center"><strong>Action</strong></td>

                                        </tr>

                                    </thead>

                                    <tbody></tbody>


                                    <tfoot>

                                        <tr>

                                            <td>

                                        <!--<select data-placeholder="(:-- Select Category --:)"  class="category_product select-search1">-->

                                                <select id="category_product" onchange="getProductList(this.value)"
                                                        class="chosen-select form-control" id="form-field-select-3"
                                                        data-placeholder="Search by product Cat">

                                                    <option value=""></option>

                                                    <?php
                                                    foreach ($productCat as $eachCat):
                                                        ?>

                                                        <option catName="<?php echo $eachCat->title; ?>"
                                                                value="<?php echo $eachCat->category_id; ?>">

                                                            <?php echo $eachCat->title; ?>

                                                        </option>


                                                    <?php endforeach; ?>


                                                </select>

                                            </td>

                                            <td>

                                                <select id="productID" onchange="getProductPrice(this.value)"
                                                        class="chosen-select form-control" id="form-field-select-3"
                                                        data-placeholder="Search by product name">

                                                    <option value=""></option>

                                                </select>

                                            </td>

                                            <td><input type="text" class="form-control text-right quantity"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       placeholder="0"></td>

                                            <td><input type="text" class="form-control text-right rate" placeholder="0.00"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                            </td>

                                            <td><input type="text" class="form-control text-right price" placeholder="0.00"
                                                       readonly="readonly"></td>

                                            <td><a id="add_item" class="btn btn-info form-control" href="javascript:;"
                                                   title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;</a></td>

                                        </tr>

                                        <tr>

                                            <td align="right" colspan="2"><strong>Sub-Total(BDT)</strong></td>

                                            <td align="right"><strong class="total_quantity"></strong></td>

                                            <td align="right"><strong class=""></strong></td>

                                            <td align="right"><strong class="total_price"></strong></td>

                                            <td></td>

                                        </tr>

                                    </tfoot>

                                </table>


                            </div>

                            <div class="col-md-10">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Narration</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <textarea cols="60" rows="2" name="narration" placeholder="Narration" type="text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button onclick="return confirmSwat()" id="subBtn" class="btn btn-info" type="button">
                                            <i class="fa fa-check"></i>Save </button>
                                        &nbsp; &nbsp; &nbsp;
                                        <button class="btn" type="reset">
                                            <i class="ace-icon fa fa-undo bigger-110"></i> Reset</button>
                                    </div>

                                </div>
                            </div>



                        </form>

                    </div>

                </div><!-- /.col -->

            </div><!-- /.row -->

        </div><!-- /.page-content -->

    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>


<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog">


        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Add New Supplier</h4>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm2" action="" method="post" class="form-horizontal">

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier
                                    ID </label>

                                <div class="col-sm-6">

                                    <input type="text" id="form-field-1" name="supplierId" readonly
                                           value="<?php echo isset($supplierID) ? $supplierID : ''; ?>"
                                           class="form-control" placeholder="SupplierID"/>

                                </div>

                            </div>

                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier
                                    Name </label>


                                <div class="col-sm-6">

                                    <input type="text" id="form-field-1" name="supName" class="form-control required"
                                           placeholder="Name"/>

                                </div>

                            </div>


                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>

                                <div class="col-sm-6">

                                    <input type="text" maxlength="11" id="form-field-1"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           onblur="checkDuplicatePhone(this.value)" name="supPhone" placeholder="Phone"
                                           class="form-control"/>

                                    <span id="errorMsg" style="color:red;display: none;"><i
                                            class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>

                                </div>

                            </div>


                            <div class="form-group">

                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>

                                <div class="col-sm-6">

                                    <input type="email" id="form-field-1" name="supEmail" placeholder="Email"
                                           class="form-control"/>

                                </div>

                            </div>
                            <div class="col-md-12">

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Narration</label>

                                    <div class="col-sm-5">

                                        <div class="input-group">

                                            <textarea cols="55" rows="2" name="narration" placeholder="Narration" type="text"></textarea>

                                        </div>

                                    </div>
                                    <div class="col-md-4">

                                        <button onclick="saveNewSupplier()" id="subBtn2" class="btn btn-info" type="button">

                                            <i class="ace-icon fa fa-check bigger-110"></i>

                                            Save

                                        </button>

                                        &nbsp; &nbsp; &nbsp;

                                        <button class="btn" type="reset" data-dismiss="modal">

                                            <i class="ace-icon fa fa-undo bigger-110"></i>

                                            Reset

                                        </button>

                                    </div>  

                                </div>

                            </div>


                        </form>

                    </div>

                </div>

            </div>

            <div class="modal-footer">


            </div>

        </div>


    </div>

</div>


<script>


    function showBankinfo(id) {

        if (id == 3) {

            $("#showBankInfo").show(1000);

        } else {

            $("#showBankInfo").hide(1000);

        }


        if (id == 1) {

            $("#hideAccount").show(1000);

        } else {

            $("#hideAccount").hide(1000);

        }


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


    $(document).ready(function () {

        $('.quantity').keyup(function () {

            $(this).val(parseFloat($(this).val()));

            priceCal();

        });

        $('.rate').keyup(function () {

            //$(this).val(parseFloat($(this).val()).toFixed(2));

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

        $('.total_quantity').html(parseFloat(total_quantity));

    };


    /*  var findTotalRate = function () {
     
     var total_rate = 0;
     
     $.each($('.add_rate'), function () {
     
     rate = $(this).val();
     
     rate = Number(rate);
     
     total_rate += rate;
     
     });
     
     $('.total_rate').html(parseFloat(total_rate).toFixed(2));
     
     };*/

    var findTotalPrice = function () {

        var total_price = 0;

        $.each($('.add_price'), function () {

            price = $(this).val();

            price = Number(price);

            total_price += price;

        });

        $('.total_price').html(parseFloat(total_price).toFixed(2));


    };

    var findTotalCal = function () {

        findTotalQty();

        //findTotalRate();

        findTotalPrice();

    };


    $(document).ready(function () {


        var j = 0;

        $("#add_item").click(function () {

            var productCatID = $('#category_product').val();

            var productCatName = $("#category_product").find('option:selected').attr('catName');


            var productID = $('#productID').val();
            var ispackage = $('#productID').find('option:selected').attr('ispackage');
            var productName = $('#productID').find('option:selected').attr('productName');

            var quantity = $('.quantity').val();

            var rate = $('.rate').val();

            var price = $('.price').val();


            if (productCatID == '') {

                swal("Product Category can't be empty!", "Validation Error!", "error");

                return false;

            } else if (productID == '') {

                swal("Product Name can't be empty!", "Validation Error!", "error");

                return false;

            } else if (quantity == '') {

                swal("Quantity Can't be empty!", "Validation Error!", "error");

                return false;

            } else if (rate == '') {

                swal("Unit Price Can't be empty!", "Validation Error!", "error");

                return false;

            } else {


                if (ispackage == 0) {
                    $("#show_item tbody").append('<tr class="new_item' + productCatID + productID + '"><td style="padding-left:15px;">' + productCatName + '<input type="hidden" name="category_id[]" value="' + productCatID + '"></td><td style="padding-left:15px;">' + productName + '<input type="hidden"  name="product_id[]" value="' + productID + '"></td><td align="right">' + '<input type="hidden" name="is_package[]" value="0"/><input type="text" id="QTY_' + productID + '" class="add_quantity  form-control text-right decimal" name="quantity[]" value="' + quantity + '" autocomplete="off"></td><td align="right">' + '<input type="text" id="RATE_' + productID + '" class="add_rate form-control text-right decimal" name="rate[]" value="' + rate + '" autocomplete="off"></td><td align="right">' + '<input type="text" id="ITEMPRICE_' + productID + '" class="add_price form-control text-right decimal" name="price[]" value="' + price + '" readonly="true"></td><td><a del_id="' + productCatID + productID + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');
                } else {
                    var quantity = $('.quantity').val();
                    var returnAble = $('.returnAble').val();
                    var rate = $('.rate').val();
                    var price = $('.price').val();
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: baseUrl + "lpg/PurchaseController/package_product_list",
                        data: 'package_id=' + productID,
                        success: function (data) {
                            var style = '';
                            var rowspan = '';
                            $.each(data, function (key, value) {
                                rowspan = '2';
                                $("#show_item tbody").append('<tr class="new_item' + value['package_id'] + 'package' + '"><td style="padding-left:15px;">' + value['title'] + '<input type="hidden" name="category_id[]" value="' + value['category_id'] + '"></td><td style="padding-left:15px;">' + value['package_name'] + '<input type="hidden"  name="product_id[]" value="' + value['product_id'] + '"></td><td align="right">' + '<input type="hidden" name="is_package[]" value="1"/><input type="text" id="QTY_' + value['product_id'] + 'P' + '" class="add_quantity  form-control text-right decimal" name="quantity[]" value="' + quantity + '" autocomplete="off"></td><td align="right">' + '<input type="text" id="RATE_' + value['product_id'] + 'P' + '" class="add_rate form-control text-right decimal" name="rate[]" value="' + rate + '" autocomplete="off"></td><td align="right">' + '<input type="text" id="ITEMPRICE_' + value['product_id'] + 'P' + '" class="add_price form-control text-right decimal" name="price[]" value="' + price + '" readonly="true"></td><td  style="' + style + '" rowspan="' + rowspan + '"><a del_id="' + value['package_id'] + 'package' + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');
                                style = 'display:none';
                            });

                        }, complete: function () {
                            findTotalCal();
                            setTimeout(function () {
                                ///calculateCustomerDue();
                                // calcutateFinal();
                            }, 100);
                        }
                    });
                }
            }

            $('.quantity').val('');

            $('.rate').val('');

            $('.price').val('');

            $('#productID').val('').trigger('chosen:updated');

            $('#category_product').val('').trigger('chosen:updated');

            $('#productUnit').val('').trigger('chosen:updated');

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


    function getProductPrice(product_id) {


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('FinaneController/getProductPrice'); ?>",
            data: 'product_id=' + product_id,
            success: function (data) {

                $('.rate').val(data);

            }

        });

    }

    function getProductList(cat_id) {


        $.ajax({
            type: "POST",
            url: "<?php echo site_url('lpg/InvProductController/getProductList'); ?>",
            data: 'cat_id=' + cat_id,
            success: function (data) {


                $('#productID').chosen();

                $('#productID option').remove();

                $('#productID').append($(data));

                $("#productID").trigger("chosen:updated");


            }

        });

    }


</script>


<script>

    function checkDuplicateCategory(catName) {

        var url = '<?php echo site_url("SetupController/checkDuplicateCategory") ?>';

        $.ajax({
            type: 'POST',
            url: url,
            data: {'catName': catName},
            success: function (data) {

                if (data == 1) {

                    $("#subBtn").attr('disabled', true);

                    $("#errorMsg").show();

                } else {

                    $("#subBtn").attr('disabled', false);

                    $("#errorMsg").hide();

                }

            }

        });


    }

    $(document).on("keyup", ".add_rate", function () {
        var id_arr = $(this).attr('id');
        var id = id_arr.split("_");
        var element_id = id[id.length - 1];

        /*var quantity = parseFloat($("#qty_" + element_id).val());
         if(isNaN(quantity)){
         quantity=0;
         }
         var rate= parseFloat($("#rate_" + element_id).val());
         if(isNaN(rate)){
         rate=0;
         }
         var totalAmount = quantity * rate;
         $("#tprice_"+ element_id).val(parseFloat(totalAmount).toFixed(2));
         var row_total = 0;
         $.each($('.add_price'), function () {
         quantity = $(this).val();
         quantity = Number(quantity);
         row_total += quantity;
         });
         $('.total_price').val(parseFloat(row_total).toFixed(2));
         calculateCustomerDue();*/
        calculate_rate(element_id);

    });
    $(document).on("keyup", ".add_quantity", function () {
        var id_arr = $(this).attr('id');
        var id = id_arr.split("_");
        var element_id = id[id.length - 1];



        calculate_rate(element_id);

    });
    function  calculate_rate(id) {


        var quantity = parseFloat($("#QTY_" + id).val());
        if (isNaN(quantity)) {
            quantity = 0;
        }
        var rate = parseFloat($("#RATE_" + id).val());
        if (isNaN(rate)) {
            rate = 0;
        }

        var totalAmount = quantity * rate;
        $("#ITEMPRICE_" + id).val(parseFloat(totalAmount).toFixed(2));

        setTimeout(function () {
            ///calculateCustomerDue();
            findTotalPrice();
            findTotalQty();
        }, 100);
        // ITEMPRICE_


    }

</script>
<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>