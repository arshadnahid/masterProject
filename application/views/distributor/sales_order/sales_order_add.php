<style type="text/css">
    table tr td {
        margin: 0px !important;
        padding: 1px !important;
    }

    table tr td tfoot .form-control {
        width: 100%;
        height: 34px;
    }

    .table-fixed thead {
        width: 90%;

    }

    .table-fixed tbody {
        height: 120px;
        overflow-y: auto;
        width: 100%;
    }

    .table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {
        display: block;
    }

    .table-fixed tbody td, .table-fixed thead > tr > th {
        float: left;
        border-bottom-width: 0;
    }
</style>

<?php
$property_1 = get_property_list_for_show_hide(1);
$property_2 = get_property_list_for_show_hide(2);
$property_3 = get_property_list_for_show_hide(3);
$property_4 = get_property_list_for_show_hide(4);
$property_5 = get_property_list_for_show_hide(5);
$salesInvoiceCreditLimitBlock = 4000;

$number_of_property = 0;

if ($property_1 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_2 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_3 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_4 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}
if ($property_5 != 'dont_have_this_property') {
    $number_of_property = $number_of_property + 1;
}

?>
<div class="row">

    <form id="publicForm" action="" method="post" class="form-horizontal">

        <div class="col-md-12">


            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Customer ID</label>

                    <div class="col-sm-6">

                        <select id="customerid" name="customer_id" class="chosen-select form-control"
                                data-placeholder="Search by Customer ID or Name">

                            <option></option>

                            <?php foreach ($customerList as $key => $each_info): ?>
                                <option value="<?php echo $each_info->customer_id; ?>"><?php echo $each_info->customerName . '&nbsp&nbsp[ ' . $each_info->typeTitle . ' ] '; ?></option>
                            <?php endforeach; ?>

                        </select>

                    </div>


                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="so_id"> Order ID</label>

                    <div class="col-sm-7">

                        <input type="text" id="so_id" name="so_id" readonly value="<?php echo $voucherID; ?>"
                               class="form-control" placeholder="SO ID"/>

                    </div>

                </div>

            </div>

            <div class="clearfix"></div>


            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-4 control-label no-padding-right" for="so_date"> Order Date</label>

                    <div class="col-sm-6">

                        <div class="input-group">

                            <input class="form-control date-picker-sales" name="so_date" id="so_date" type="text"
                                   value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"
                                   autocomplete="off"/>

                            <span class="input-group-addon">

                                            <i class="fa fa-calendar bigger-110"></i>

                                        </span>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="so_delivery_date"> Delivery Date</label>

                    <div class="col-sm-7">

                        <div class="input-group">

                            <input class="form-control date-picker-sales" name="so_delivery_date" id="so_delivery_date"
                                   type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"
                                   autocomplete="off"/>

                            <span class="input-group-addon">

                                            <i class="fa fa-calendar bigger-110"></i>

                                        </span>

                        </div>

                    </div>

                </div>

            </div>

            <div class="clearfix"></div>


            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Reference</label>

                    <div class="col-sm-6">

                        <select name="reference" class="chosen-select form-control" id="form-field-select-3"
                                data-placeholder="Search by Customer ID or Name">

                            <option></option>

                            <?php foreach ($referenceList as $key => $each_ref): ?>

                                <option value="<?php echo $each_ref->reference_id; ?>"><?php echo $each_ref->referenceName; ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Branch</label>

                    <div class="col-sm-7">

                        <select name="branch_id" class="chosen-select form-control"
                                id="BranchAutoId" data-placeholder="Select Branch">
                            <option value=""></option>
                            <?php
                            echo branch_dropdown(null, null);
                            ?>


                        </select>

                    </div>

                </div>

            </div>
            <div class="col-md-12">

                <div class="form-group">

                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1">Shipping Address</label>

                    <div class="col-sm-9">

                        <textarea placeholder="Shipping Address" class=" form-control" name="shippingAddress" cols="47"
                                  rows="1"></textarea>

                    </div>

                </div>

            </div>


            <div class="clearfix"></div>
            <div class="col-md-12 ">

                <div class="table-header">

                    Order Item

                </div>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                    <input id="productNameAutocomplete"
                           class="form-control  ui-autocomplete-input"
                           placeholder="Scan/Search Product by Name/Code" autocomplete="off">
                </div>
                <table class="table table-bordered table-hover tableAddItem" id="show_item">
                    <thead>
                    <tr>
                        <th nowrap style="width:12%;border-radius:10px;"><strong><?php echo get_phrase('Category') ?>
                                <span
                                        style="color:red;"> *</span></strong>
                        </th>
                        <th nowrap style="width:172px" id="product_th"><strong> <?php echo get_phrase('Product') ?>
                                <span
                                        style="color:red;"> *</span></strong></th>
                        <th nowrap
                            style="text-align: center;width:17%;border-radius:10px;<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_1; ?> </strong>
                        </th>
                        <th nowrap
                            style="text-align: center;width:10%;border-radius:10px;<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?> ">
                            <strong><?php echo $property_2; ?> </strong>

                        </th>
                        <th nowrap
                            style="text-align: center;width:10%;border-radius:10px; <?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_3; ?> </strong>
                        </th>
                        <th nowrap
                            style="text-align: center;width:10%;border-radius:10px; <?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_4; ?> </strong>
                        </th>
                        <th nowrap
                            style="text-align: center;width:10%;border-radius:10px;<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <strong><?php echo $property_5; ?> </strong>
                        </th>
                        <th style="white-space:nowrap; width:100px; vertical-align:top;">

                            <strong><?php echo get_phrase('Quantity') ?> <span
                                        style="color:red;"> *</span></strong></th>
                        <th nowrap style="display: none"><strong><?php echo get_phrase('Receivable_Qty') ?></strong>
                        </th>
                        <th nowrap><strong><?php echo get_phrase('Unit_Price') ?>(<?php echo get_phrase('BDT') ?>) <span
                                        style="color:red;"> *</span></strong></th>
                        <th nowrap><strong><?php echo get_phrase('Total Price') ?>(<?php echo get_phrase('BDT') ?>)
                                <span
                                        style="color:red;"> *</span></strong></th>

                        <th><strong><?php echo get_phrase('Action') ?></strong></th>
                    </tr>
                    <tr>
                        <td>
                            <select class="chosen-select form-control" data-placeholder="Select Category"
                                    id="CategorySelect2"
                                    onchange="getProductList(this.value)">
                                <option></option>
                                <option value="all">ALL</option>
                                <?php
                                $categoryArray = array('1', '2');
                                foreach ($productCat as $eachInfo) {
                                    if (in_array($eachInfo->category_id, $categoryArray)) {
                                        ?>
                                        <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                        <?php
                                    }
                                }
                                ?>

                                <?php
                                $categoryArray = array('1', '2');
                                foreach ($productCat as $eachInfo) {
                                    if (!in_array($eachInfo->category_id, $categoryArray)) {
                                        ?>
                                        <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                        <?php
                                    }
                                }
                                ?>

                            </select>
                        </td>
                        <td id="product_td">
                            <select id="productID" onchange="getProductPrice(this.value)"
                                    class="chosen-select form-control"
                                    data-placeholder="Select  Product">
                                <option value=""></option>

                            </select>
                        </td>

                        <td style="<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_1 "
                                   placeholder="<?php echo $property_1; ?>"/>
                        </td>
                        <td style="<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?>">

                            <input type="text" onclick="this.select();" class="form-control text-right property_2 "
                                   placeholder="<?php echo $property_2; ?>"/>
                        </td>
                        <td style="<?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_3 "
                                   placeholder="<?php echo $property_3; ?>"/>
                        </td>
                        <td style="<?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_4 "
                                   placeholder="<?php echo $property_4; ?>"/>
                        </td>
                        <td style="<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_5 "
                                   placeholder="<?php echo $property_5; ?>"/>
                        </td>
                        <td><input type="hidden" class="form-control text-right is_same decimal" value="0"><input
                                    type="hidden" value="" id="stockQty"/><input type="text" onclick="this.select();"
                                                                                 onkeyup="checkStockOverQty_for_select(this.value)"
                                                                                 class="form-control text-right quantity decimal"
                                                                                 placeholder="0"></td>
                        <td style="display: none"><input type="hidden" value="" id="returnStockQty"/><input type="text"
                                                                                                            readonly
                                                                                                            onclick="this.select();"
                                                                                                            class="form-control text-right returnQuantity decimal"
                                                                                                            placeholder="0">
                        </td>
                        <td><input type="text" onclick="this.select();" class="form-control text-right rate decimal"
                                   placeholder="0.00"></td>
                        <td>
                            <input type="text" onclick="this.select();" class="form-control text-right price decimal"
                                   placeholder="0.00"
                                   readonly="readonly">
                        </td>
                        <td>
                            <a id="add_item" class="btn blue form-control" href="javascript:void(0);" title="Add Item"
                               data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing ">
                                <i class="fa fa-plus" style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;
                            </a>
                        </td>
                    </tr>
                    </thead>
                    <tbody>


                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="<?php echo $number_of_property + 1 ?>">
                             <textarea style="border:none;" cols="120"
                                       class="form-control" name="narration"
                                       placeholder="Narration......"
                                       type="text"></textarea>
                        </td>
                        <td align="right">
                            <strong>Total Qty</strong>
                        </td>
                        <td align="right"><strong id="total_qty"> </strong></td>
                        <td align="right"><strong id="">Grand Total </strong></td>
                        <td align="right"><strong id="total_price"></strong></td>
                        <td></td>

                    </tr>

                    </tfoot>

                </table>

            </div>
            <div class="clearfix"></div>
            <div class="clearfix form-actions">
                <div class="col-md-6 col-md-offset-5">
                    <button onclick="return isconfirm2()" id="subBtn" class="btn blue" type="button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing ">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        <?php echo get_phrase('Save') ?>
                    </button>
                    <span id="errorMsg" style="color:red;display: none;"><i
                                class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Customer Credit Limit over!!</span>

                </div>
            </div>

        </div>


    </form>
</div>

<script>


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

    var incriment = 1;
    var salesRateLock;
    window.salesRateLock = '<?php echo check_parmission_by_user_role(3002)?>';


    function checkSalesRateLockPermission() {

        if (salesRateLock != 0) {

            $(".rate").attr("readonly", true);
            $(".add_rate").attr("readonly", true);
        } else {
            $(".rate").attr("readonly", false);
            $(".add_rate").attr("readonly", false);
        }
    }

    $("#add_item").click(function () {
        $('#add_item').prop('disabled', true);
        $('#add_item').button('loading');
        console.log(incriment);

        var productCatID = $('#productID').find('option:selected').attr('categoryId');
        var productCatName = $('#productID').find('option:selected').attr('categoryName');
        var productID = $('#productID').val();
        var productName = $('#productID').find('option:selected').attr('productName');
        var ispackage = $('#productID').find('option:selected').attr('ispackage');
        var package_id2 = $('#productID2').val();
        var productCatName2 = $('#productID2').find('option:selected').attr('categoryName2');
        var productName2 = $('#productID2').find('option:selected').attr('productName2');
        var productBrandID = $('#productID').find('option:selected').attr('brand_id');
        var productBrandName = $('#productID').find('option:selected').attr('brandName');

        var quantity = $('.quantity').val();
        var rate = $('.rate').val();

        var property_1 = $('.property_1').val();
        var property_2 = $('.property_2').val();
        var property_3 = $('.property_3').val();
        var property_4 = $('.property_4').val();
        var property_5 = $('.property_5').val();

        var price = $('.price').val();

        if (productID == '') {
            swal("Product Name can't be empty!", "Validation Error!", "error");
            $('#add_item').prop('disabled', false);
            $('#add_item').button('reset');
            $("#productID").trigger("chosen:open");
            return false;
        } else if (quantity == '') {
            swal("Quantity Can't be empty!", "Validation Error!", "error");
            $('#add_item').prop('disabled', false);
            $('#add_item').button('reset');
            return false;
        } else if (rate == '') {
            swal("Unit Price Can't be empty!", "Validation Error!", "error");
            $('#add_item').prop('disabled', false);
            $('#add_item').button('reset');
            return false;
        } else {
            var productUnit = "";
            var unitName = "";
            addRowProduct(productID, productName, productCatID, productCatName, productBrandID, productBrandName, productUnit, unitName, property_1, property_2, property_3, property_4, property_5, quantity, rate)
            $('#CategorySelect2').val('').trigger('chosen:updated');
            $('#productID').val('').trigger('chosen:updated');
            $('#productID2').val('').trigger('chosen:updated');
            $('.quantity').val('');
            $('.is_same').val('0');
            $('.rate').val('');
            $('.price').val('');
            $('.returnAble').val('');
            $('.returnQuentity').val('');
            $('.returnQuantity2').val('');
            $('.returnQuantity').val('');
            $('.quantity').attr("placeholder", "0");
            $('.property_1').val('');
            $('.property_2').val('');
            $('.property_3').val('');
            $('.property_4').val('');
            $('.property_5').val('');
        }

        $('#add_item').prop('disabled', false);
        $('#add_item').button('reset');
    });
    $("#productNameAutocomplete").autocomplete({
        source: function (request, response) {
            $.getJSON(baseUrl + "lpg/SalesOrderController/get_product_list_by_dist_id", {term: request.term},
                response);
        },
        minLength: 1,
        delay: 100,
        response: function (event, ui) {
            if (ui.content) {
                if (ui.content.length == 1) {
                    addRowProduct(ui.content[0].id, ui.content[0].productName, ui.content[0].category_id, ui.content[0].productCatName, ui.content[0].brand_id, ui.content[0].brandName, ui.content[0].unit_id, ui.content[0].unitTtile, ui.content[0].property_1, ui.content[0].property_2, ui.content[0].property_3, ui.content[0].property_4, ui.content[0].property_5)
                    var dataIns = $(this).val();
                    $(this).val('');
                    $(this).focus();
                    $(this).autocomplete('close');
                    return false;

                } else if (ui.content.length == 0) {
                    $(this).val('');
                    $(this).focus();
                    $(this).autocomplete('close');
                    return false;
                } else {
                    // alert("This Character and code have no item!!");
                }
            }
        },
        select: function (event, ui) {
            addRowProduct(ui.item.id, ui.item.productName, ui.item.category_id, ui.item.productCatName, ui.item.brand_id, ui.item.brandName, ui.item.unit_id, ui.item.unitTtile, ui.item.property_1, ui.item.property_2, ui.item.property_3, ui.item.property_4, ui.item.property_5)
            $(this).val('');
            return false;
        },
        open: function (e, ui) {
            // create the scrollbar each time autocomplete menu opens/updates

            $(".ui-autocomplete").mCustomScrollbar({
                setHeight: 182,
                theme: "minimal-dark",
                autoExpandScrollbar: true
                //scrollbarPosition:"outside"
            });
        },
        focus: function (e, ui) {
            //* scroll via keyboard
            if (!ui.item) {
                var first = $(".ui-autocomplete li:first");
                first.trigger("mouseenter");
                $(this).val(first.data("uiAutocompleteItem").label);
            }
            /*var el = $(".ui-state-focus").parent();
             if (!el.is(":mcsInView") && !el.is(":hover")) {
             $(".ui-autocomplete").mCustomScrollbar("scrollTo", el, {scrollInertia: 0, timeout: 0});
             }*/
        },
        close: function (e, ui) {
            // destroy the scrollbar each time autocomplete menu closes
            $(".ui-autocomplete").mCustomScrollbar("destroy");
        }

    });


    function addRowProduct(productID, productName, productCatID, productCatName, productBrandID, productBrandName, productUnit, unitName, property_1, property_2, property_3, property_4, property_5, quantity=1, given_rate="") {// quantity,returnQuantity, rate, price
        console.log(incriment);


        var quantity = quantity;
        var MainQuantity;
        var productCatID = productCatID;
        var productCatName = productCatName;
        var productID = productID;
        var productName = productName;
        var productUnit = productUnit;
        var unitName = unitName;
        var property_1 = property_1 != null ? property_1 : "";
        var property_2 = property_2 != null ? property_2 : "";
        var property_3 = property_3 != null ? property_3 : "";
        var property_4 = property_4 != null ? property_4 : "";
        var property_5 = property_5 != null ? property_5 : "";
        var BranchAutoId = $("#BranchAutoId").val();
        var rate = 0;
        var price = 0;
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductStock',

            data: {product_id: productID, category_id: productCatID, ispackage: 0, branchId: BranchAutoId},
            success: function (data) {
                MainQuantity = parseFloat(data);
                // MainQuantity = 10000;
                //$("#stockQty").val(MainQuantity);

            }, complete: function () {
                var previousProductID = parseFloat($('#PRODUCTID_' + productID).val());
                $.ajax({
                    type: "POST",
                    url: baseUrl + "lpg/InvProductController/getProductPriceForSale",
                    data: 'product_id=' + productID,
                    success: function (data) {
                        if (given_rate != "") {
                            rate = given_rate;
                        } else {
                            rate = data;

                        }
                    }, complete: function () {

                        if ($('#PRODUCTID_' + productID).val() == productID) {
                            quantity = parseInt($('#qty_' + productID).val()) + 1;
                            setTimeout(function () {
                                calcutate_one_product_total_price(productID, quantity, rate);
                            }, 100);
                        }
                        if (quantity <= MainQuantity) {
                            var givenQuantity = 1;
                            var previousProductQuantity = parseInt($('#qty_' + productID).val());
                            if (previousProductID == productID) {
                                givenQuantity = givenQuantity + previousProductQuantity;
                                $('#qty_' + productID).val(givenQuantity);
                                return true;
                            }
                            var tab = "";


                            var row_id = incriment;
                            // var row_id=productID;
                            tab = '<tr class="new_item' + row_id + '">' +
                                '<input type="hidden" name="slNo[' + row_id + ']" value="' + row_id + '"/>' +
                                '<input type="hidden" name="brand_id[]" value="' + productBrandID + '"/>' +
                                '<input type="hidden" name="is_package_' + productID + '" value="0">' +
                                '<input type="hidden" name="category_id[]" value="' + productCatID + '">' +
                                '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName + '[ ' + productBrandName + ' ]' +
                                '<input type="hidden" id="PRODUCTID_' + row_id + '"  name="product_id_' + row_id + '" value="' + productID + '">' +
                                '</td>' +
                                '<td align="right" style="<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                                '<input  type="text" class="add_property_1 text-right form-control" id="property_1' + row_id + '" name="property_1_' + row_id + '" value="' + property_1 + '">' +
                                '</td>' +
                                '<td align="right" style="<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                                '<input  type="text" class="add_property_2 text-right form-control" id="property_2' + row_id + '" name="property_2_' + row_id + '" value="' + property_2 + '">' +
                                '</td>' +
                                '<td align="right" style="<?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                                '<input  type="text" class="add_property_3 text-right form-control" id="property_3' + row_id + '" name="property_3_' + row_id + '" value="' + property_3 + '">' +
                                '</td>' +
                                '<td align="right" style="<?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                                '<input  type="text" class="add_property_4 text-right form-control" id="property_4' + row_id + '" name="property_4_' + row_id + '" value="' + property_4 + '">' +
                                '</td>' +
                                '<td align="right" style="<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : ''?> ">' +
                                '<input  type="text" class="add_property_5 text-right form-control" id="property_5' + row_id + '" name="property_5_' + row_id + '" value="' + property_5 + '">' +
                                '</td>' +

                                '<td align="right">' +
                                '<input type="text" id="qty_' + row_id + '" attr-in-stock-qty="' + MainQuantity + '" placeholder="' + MainQuantity + '" attr-pid="' + productID + '" class="form-control text-right add_quantity decimal"  name="quantity_' + row_id + '" value="' + quantity + '" >' +
                                '</td>' +
                                '<td align="right"><input type="text" id="rate_' + row_id + '" class="form-control add_rate text-right decimal" name="rate_' + row_id + '" value="' + rate + '">' +
                                '</td>' +
                                '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_' + row_id + '" name="price[]" value="' + price + '">' +
                                '</td>' +
                                '<td>' +
                                '<a del_id="' + row_id + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
                                '</td>' +
                                '</tr>';

                            $("#show_item tbody").append(tab);
                            $("#subBtn").attr('disabled', false);
                            incriment = incriment + 1;
                            setTimeout(function () {
                                calcutate_one_product_total_price(row_id, quantity, rate);
                            }, 100);


                        } else {
                            swal("Stock Quantity Not Available!", "Validation Error!", "error");
                            return false;
                        }
                    }
                });
            }
        });


    }


    function checkStockOverQty(element_id, givenStock, stock_qty) {
        var orgiStock = parseFloat(stock_qty);
        var givenStock = parseFloat(givenStock);
        if (isNaN(givenStock)) {
            givenStock = 0;
        }
        if (isNaN(orgiStock)) {
            orgiStock = 0;
        }
        if (orgiStock < givenStock) {
            $("#qty_" + element_id).val('');
            $("#qty_" + element_id).val(parseFloat(orgiStock));
            productItemValidation("Stock Quantity Not Available.");
        }

    }

    function checkStockOverQty_for_select(givenStock) {
        var orgiStock = parseFloat($("#stockQty").val());
        var givenStock = parseFloat(givenStock);
        if (isNaN(givenStock)) {
            givenStock = 0;
        }
        if (isNaN(orgiStock)) {
            orgiStock = 0;
        }
        //  alert(orgiStock);
        if (orgiStock < givenStock) {
            $(".quantity").val('');
            $(".quantity").val(parseFloat(orgiStock));
            productItemValidation("Stock Quantity Not Available.");
        }

    }

    function productItemValidation(msg) {


        swal({

                title: msg,

                //text: "You won't be able to revert this!",

                showCancelButton: true,

                confirmButtonColor: '#73AE28',

                //cancelButtonColor: '#d33',

                confirmButtonText: 'Yes',

                //cancelButtonText: "No",

                closeOnConfirm: true,

                //closeOnCancel: false,

                type: 'warning'

            },

            function (isConfirm) {

                if (isConfirm) {

                    //$("#publicForm").submit();

                } else {

                    // return false;

                }

            });

    }


    function getProductList(cat_id) {
        if (cat_id == 2) {
            $(".returnQuantity").attr("readonly", false);
        } else {
            $(".returnQuantity").attr("readonly", true);
        }
        $.ajax({
            type: "POST",
            url: baseUrl + "lpg/InvProductController/getProductList",
            data: 'cat_id=' + cat_id,
            success: function (data) {
                $('#productID').chosen();
                $('#productID option').remove();
                $("#productID").trigger("chosen:open");
                //event.stopPropagation();
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
            }
        });
    }

    function getProductPrice(product_id) {
        var productName = $('#productID').find('option:selected').attr('productName');
        $("#stockQty").val('');
        $(".quantity").val('');
        $(".property_1").val('');
        $(".property_2").val('');
        $(".property_3").val('');
        $(".property_4").val('');
        $(".property_5").val('');
        $('.is_same').val('0');
        var productCatID = parseFloat($('#productID').find('option:selected').attr('categoryId'));
        var property_1 = $('#productID').find('option:selected').attr('property_1');
        var property_2 = $('#productID').find('option:selected').attr('property_2');
        var property_3 = $('#productID').find('option:selected').attr('property_3');
        var property_4 = $('#productID').find('option:selected').attr('property_4');
        var property_5 = $('#productID').find('option:selected').attr('property_5');

        var branchId = $('#BranchAutoId').val();


        if (branchId == '' || branchId == null) {
            swal("Select Branch", "Validation Error!", "error");
            $(".quantity").attr('readonly', true);
            return false;
        }


        var ispackage = $('#productID').find('option:selected').attr('ispackage');
        var sales_price = $('#productID').find('option:selected').attr('salesprice');
        if (ispackage == 1) {
            product_id = $('#productID').find('option:selected').attr('product_id');
            $('#productID2').val('').prop('disabled', false).trigger("chosen:updated");
            $(".returnQuantity").attr('readonly', false);
            $(".returnQuantity2").attr('readonly', false);
        }


        if (productCatID == 2 && ispackage == 0) {
            $('#productID2').val('').prop('disabled', false).trigger("chosen:updated");
            $(".returnQuantity").attr('readonly', false);
            $(".returnQuantity2").attr('readonly', false);
            $.ajax({
                type: "POST",
                url: baseUrl + 'lpg/SalesLpgController/checkHaveEmptyCylinderOrPrice',
                data: 'product_id=' + product_id,
                success: function (data) {
                    console.log(data);
                    if (data <= 0) {
                        swal("There is no price or package  Against This Refill Product !", "Please Set a Price or Package !", "error");
                        $('#productID').val('').trigger('chosen:updated');
                        $('.quantity ').val('');
                        return false;
                    }
                }
            });
        } else {
            $(".returnQuantity").attr('readonly', true);
            $(".returnQuantity2").attr('readonly', true);
            $('#productID2').val('').prop('disabled', true).trigger("chosen:updated");
        }

        var rate = parseFloat(sales_price);
        if (isNaN(rate)) {
            rate = 0;
        }
        $('.rate').val(rate);

        /*$.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductPriceForSale',
            data: 'product_id=' + product_id,
            success: function (data) {


                var rate = parseFloat(data);
                if (isNaN(rate)) {
                    rate = 0;
                }

                $('.rate').val(rate);
            }
        });*/
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductStock',
            data: {product_id: product_id, category_id: productCatID, ispackage: ispackage, branchId: branchId},
            success: function (data) {
                var mainStock = parseFloat(data);
                if (isNaN(mainStock)) {
                    mainStock = 0;
                }
                if (data != '') {
                    $("#stockQty").val(data);
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

        $(".property_1").val(property_1);
        $(".property_2").val(property_2);
        $(".property_3").val(property_3);
        $(".property_4").val(property_4);
        $(".property_5").val(property_5);
    }


    $(document).on("keyup", ".add_quantity", function () {
        var id_arr = $(this).attr('id');
        var id = id_arr.split("_");
        var element_id = id[id.length - 1];
        var quantity = parseFloat($("#qty_" + element_id).val());
        var stock_quantity = parseFloat($("#qty_" + element_id).attr("attr-in-stock-qty"));
        if (isNaN(quantity)) {
            quantity = 0;
        }
        if (isNaN(stock_quantity)) {
            stock_quantity = 0;
        }
        var rate = parseFloat($("#rate_" + element_id).val());
        if (isNaN(rate)) {
            rate = 0;
        }
        checkStockOverQty(element_id, quantity, stock_quantity);
        calcutate_one_product_total_price(element_id, quantity, rate);


    });

    $(document).on("keyup", ".add_rate", function () {
        var id_arr = $(this).attr('id');
        var id = id_arr.split("_");
        var element_id = id[id.length - 1];
        var quantity = parseFloat($("#qty_" + element_id).val());
        var rate = parseFloat($(this).val());

        calcutate_one_product_total_price(element_id, quantity, rate);
    });

    function calcutate_one_product_total_price(element_id, givenStock, given_rate) {

        alert('OKK');
        var totalAmount = given_rate * givenStock;
        $("#tprice_" + element_id).val(parseFloat(totalAmount).toFixed(2));
        setTimeout(function () {
            calcutateFinal();
        }, 100);
    }

    function calcutateFinal() {
        var total_price = 0;
        var total_quantity = 0;
        $.each($('.add_quantity'), function () {
            var quantity = $(this).val();
            quantity = Number(quantity);
            total_quantity += quantity;
        });

        $.each($('.add_price'), function () {
            var price = $(this).val();
            price = Number(price);
            total_price += price;
        });
        $('#total_price').html(parseFloat(total_price).toFixed(2));
        $('#total_qty').html(parseFloat(total_quantity).toFixed(2));
    }

    function priceCal() {
        var quantity = parseFloat($('.quantity').val());
        if (isNaN(quantity)) {
            quantity = 0;
        }
        var rate = parseFloat($('.rate').val());
        if (isNaN(rate)) {
            rate = 0;
        }
        $('.price').val(parseFloat(rate * quantity).toFixed(2));
    }


    function isconfirm2() {
        $('#subBtn').prop('disabled', true);
        $('#subBtn').button('loading');
        var customerid = $("#customerid").val();
        var so_date = $("#so_date").val();
        var so_delivery_date = $("#so_delivery_date").val();
        var branchName = $("#branchName").val();

        var rowCount = $('#show_item tbody tr').length;

        var totalPrice = parseFloat($("#total_price").html());
        if (isNaN(totalPrice)) {
            totalPrice = 0;
        }


        if (customerid == '') {

            swal("Select Customer Name!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (so_date == '') {
            swal("Select SO Date!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (totalPrice == '' || totalPrice < 0) {
            swal("Add SO Item!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
        } else if (rowCount < 1) {
            swal("Add Product!", "Validation Error!", "error");
            $('#subBtn').prop('disabled', false);
            $('#subBtn').button('reset');
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
                        $('#subBtn').prop('disabled', false);
                        $('#subBtn').button('reset');
                        return false;
                    }
                });
        }
    }
</script>


