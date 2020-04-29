<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 10:04 AM
 */
?>
<style type="text/css">
    legend {
        background: #f5f5f5;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase($link_page_name) ?></div>

            </div>
            <div class="portlet-body">
                <form id="publicForm" action="" method="post" class="form-horizontal">

                    <div class="row">
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
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date
                                    <span style="color:red;"> *</span></label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="date" id="date"
                                               type="text"
                                               value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"
                                               autocomplete="off"/>
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <fieldset style="">
                        <legend style=" " class="">Inventory Recipt : <input type="checkbox" value="InventoryReciptAdd"
                                                                             name="" id="InventoryReciptAdd"
                                                                             onclick="InventoryReciptAddShow(this)"
                                                                             checked></legend>

                        <div class="row" id="InventoryReciptAddDivShow">

                            <div class="col-md-12">
                                <table class="table table-bordered table-hover tableAddItem" id="show_item_in">
                                    <thead>
                                    <tr>
                                        <th class="text-center">
                                            <?php echo get_phrase("Inventory Category") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Inventory Item") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Rate") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Qty") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Price") ?>
                                        </th>
                                        <td>
                                            <?php echo get_phrase("Add") ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%">
                                            <select class="chosen-select form-control"
                                                    data-placeholder="Select Category" id="CategorySelect"
                                                    onchange="getProductList(this.value)">
                                                <option></option>
                                                <?php
                                                $categoryArray = array('1', '2');
                                                foreach ($productCat as $eachInfo) {
                                                    if (in_array($eachInfo->category_id, $categoryArray)) {
                                                        ?>
                                                        <option categoryName="<?php echo $eachInfo->title ?>"
                                                                value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                                    <?php }
                                                } ?>

                                                <?php
                                                $categoryArray = array('1', '2');
                                                foreach ($productCat as $eachInfo) {
                                                    if (!in_array($eachInfo->category_id, $categoryArray)) {
                                                        ?>
                                                        <option categoryName="<?php echo $eachInfo->title ?>"
                                                                value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                                    <?php }
                                                } ?>

                                            </select>
                                        </td>
                                        <td style="width: 35%">
                                            <select id="productID"
                                                    onchange="getProductPrice(this.value)"
                                                    class="chosen-select form-control"
                                                    data-placeholder="Search by Product">
                                                <option value=""></option>


                                            </select>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id="" value="" class="form-control rate"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id="" value="" class="form-control quantity"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id="" value="" class="form-control price"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 5%">
                                            <a id="add_item" class="btn btn-info form-control"
                                               href="javascript:;" title="Add Item"><i
                                                        class="fa fa-plus"
                                                        style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a>
                                        </td>
                                    </tr>
                                    </thead>


                                    <tbody>


                                    </tbody>
                                </table>


                                <div class="col-md-8 col-md-offset-2">

                                    <br>

                                    <div class="table-header">

                                        <?php echo get_phrase('Select Account Head') ?>

                                    </div>

                                    <table class="table table-bordered table-hover" id="show_item_account_in">

                                        <thead>

                                        <tr>

                                            <td style="width:35%" align="center">
                                                <strong> <?php echo get_phrase('Account Head') ?><span
                                                            style="color:red;"> *</span></strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> <?php echo get_phrase('Debit') ?><span
                                                            style="color:red;"> *</span></strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> <?php echo get_phrase('Credit') ?><span
                                                            style="color:red;"> *</span></strong></td>

                                            <td style="width:20%" align="center">
                                                <strong> <?php echo get_phrase('Memo') ?></strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> <?php echo get_phrase('Action') ?></strong></td>

                                        </tr>

                                        </thead>

                                        <tbody></tbody>

                                        <tfoot>

                                        <tr>

                                            <td>

                                                <select class="chosen-select form-control accountId"
                                                        id="form-field-select-3"
                                                        data-placeholder="Search by account head">

                                                    <option value="" disabled selected></option>
                                                    <?php
                                                    foreach ($accountHeadList as $key => $head) {
                                                        ?>
                                                        <optgroup
                                                                label="<?php echo get_phrase($head['parentName']); ?>">
                                                            <?php
                                                            foreach ($head['Accountledger'] as $eachLedger) :
                                                                ?>
                                                                <option
                                                                        paytoAccountCode="<?php echo $eachLedger->code; ?>"
                                                                        paytoAccountName="<?php echo $eachLedger->parent_name; ?>"
                                                                    <?php
                                                                    if ($eachLedger->id == '23') {
                                                                        echo "selected";
                                                                    }
                                                                    ?>
                                                                        value="<?php echo $eachLedger->id; ?>"><?php echo get_phrase($eachLedger->parent_name) . " ( " . $eachLedger->code . " ) "; ?></option>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                        <?php
                                                    }
                                                    ?>


                                                </select>

                                            </td>

                                            <td><input type="text" class="form-control text-right amountDrINValue"
                                                       onblur="checkInputValue(this.value)"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       placeholder="0.00"></td>

                                            <td><input type="text" class="form-control text-right amountCrValue"
                                                       onblur="checkInputValue(this.value)"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       placeholder="0.00"></td>

                                            <td><input type="text" class="form-control text-right memo"
                                                       placeholder="Memo"></td>

                                            <td><a id="add_item_inv_in_account" class="btn btn-info form-control"
                                                   href="javascript:;" title="Add Item"><i
                                                            class="fa fa-plus"></i>&nbsp;&nbsp;</a></td>

                                        </tr>

                                        <tr>

                                            <td align="right"><strong><?php echo get_phrase('Sub_Total') ?>
                                                    (In.BDT)</strong></td>

                                            <td align="right"><strong class="total_dr"></strong></td>

                                            <td align="right"><strong class="total_cr"></strong></td>
                                            <td></td>
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

                            <textarea cols="50" rows="2" name="narration" placeholder="Narration"
                                      type="text"></textarea>


                                            </div>

                                        </div>


                                    </div>

                                </div>

                                <div class="clearfix"></div>


                            </div>
                        </div>


                    </fieldset>


                    <fieldset style="">
                        <legend style="">Inventory Out :<input type="checkbox" value="InventoryOutAdd" name=""
                                                               id="InventoryOutAdd" onclick="InventoryOutShow(this)"
                                                               checked></legend>

                        <div class="row" id="InventoryOutAddDivShow">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="show_item_out">
                                    <thead>
                                    <tr>
                                        <th class="text-center">
                                            <?php echo get_phrase("Inventory Category") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Inventory Item") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Rate") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Qty") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Price") ?>
                                        </th>
                                        <td>
                                            <?php echo get_phrase("Add") ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%">
                                            <select class="chosen-select form-control"
                                                    data-placeholder="Select Category" id="CategorySelect2"
                                                    onchange="getProductList2(this.value)">
                                                <option></option>
                                                <?php
                                                $categoryArray = array('1', '2');
                                                foreach ($productCat as $eachInfo) {
                                                    if (in_array($eachInfo->category_id, $categoryArray)) {
                                                        ?>
                                                        <option categoryName="<?php echo $eachInfo->title ?>"
                                                                value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                                    <?php }
                                                } ?>

                                                <?php
                                                $categoryArray = array('1', '2');
                                                foreach ($productCat as $eachInfo) {
                                                    if (!in_array($eachInfo->category_id, $categoryArray)) {
                                                        ?>
                                                        <option categoryName="<?php echo $eachInfo->title ?>"
                                                                value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                                    <?php }
                                                } ?>

                                            </select>
                                        </td>
                                        <td style="width: 35%">
                                            <select id="productIDOut"
                                                    onchange="getProductPrice2(this.value)"
                                                    class="chosen-select form-control"
                                                    data-placeholder="Search by Product">
                                                <option value=""></option>


                                            </select>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id="" value="" class="form-control rateOut"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id="" value="" class="form-control quantityOut"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id="" value="" class="form-control priceOut"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 5%">
                                            <a id="add_item_out" class="btn btn-info form-control"
                                               href="javascript:;" title="Add Item"><i
                                                        class="fa fa-plus"
                                                        style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a>
                                        </td>
                                    </tr>
                                    </thead>


                                    <tbody>


                                    </tbody>
                                </table>
                                <div class="col-md-8 col-md-offset-2">

                                    <br>

                                    <div class="table-header">

                                        <?php echo get_phrase('Select Account Head') ?>

                                    </div>

                                    <table class="table table-bordered table-hover" id="show_item_account_out">

                                        <thead>

                                        <tr>

                                            <td style="width:35%" align="center">
                                                <strong> <?php echo get_phrase('Account Head') ?><span
                                                            style="color:red;"> *</span></strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> <?php echo get_phrase('Debit') ?><span
                                                            style="color:red;"> *</span></strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> <?php echo get_phrase('Credit') ?><span
                                                            style="color:red;"> *</span></strong></td>

                                            <td style="width:20%" align="center">
                                                <strong> <?php echo get_phrase('Memo') ?></strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> <?php echo get_phrase('Action') ?></strong></td>

                                        </tr>

                                        </thead>

                                        <tbody></tbody>

                                        <tfoot>

                                        <tr>

                                            <td>

                                                <select class="chosen-select form-control accountIdOut"
                                                        id=""
                                                        data-placeholder="Search by account head">

                                                    <option value="" disabled selected></option>
                                                    <?php
                                                    foreach ($accountHeadList as $key => $head) {
                                                        ?>
                                                        <optgroup
                                                                label="<?php echo get_phrase($head['parentName']); ?>">
                                                            <?php
                                                            foreach ($head['Accountledger'] as $eachLedger) :
                                                                ?>
                                                                <option
                                                                        paytoAccountCode="<?php echo $eachLedger->code; ?>"
                                                                        paytoAccountName="<?php echo $eachLedger->parent_name; ?>"
                                                                    <?php
                                                                    if ($eachLedger->id == '23') {
                                                                        echo "selected";
                                                                    }
                                                                    ?>
                                                                        value="<?php echo $eachLedger->id; ?>"><?php echo get_phrase($eachLedger->parent_name) . " ( " . $eachLedger->code . " ) "; ?></option>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                        <?php
                                                    }
                                                    ?>


                                                </select>

                                            </td>

                                            <td><input type="text" class="form-control text-right amountDrValueOut"
                                                       onblur="checkInputValueOut(this.value)"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       placeholder="0.00"></td>

                                            <td><input type="text" class="form-control text-right amountCrValueOut"
                                                       onblur="checkInputValueOut(this.value)"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       placeholder="0.00"></td>

                                            <td><input type="text" class="form-control text-right memo"
                                                       placeholder="Memo"></td>

                                            <td><a id="add_item_inv_out_account" class="btn btn-info form-control"
                                                   href="javascript:;" title="Add Item"><i
                                                            class="fa fa-plus"></i>&nbsp;&nbsp;</a></td>

                                        </tr>

                                        <tr>

                                            <td align="right"><strong><?php echo get_phrase('Sub_Total') ?>
                                                    (In.BDT)</strong></td>

                                            <td align="right"><strong class="total_dr_out"></strong></td>

                                            <td align="right"><strong class="total_cr_out"></strong></td>
                                            <td></td>
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

                            <textarea cols="50" rows="2" name="narration" placeholder="Narration"
                                      type="text"></textarea>


                                            </div>

                                        </div>


                                    </div>

                                </div>

                                <div class="clearfix"></div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-2">

                                <button onclick="return isconfirmSave()" id="subBtn" class="btn btn-info" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>

                            </div>
                            <div class="col-md-5"></div>

                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /*$('.datepicker').datepicker({
        format: 'yyyy/dd/mm'

    });*/

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });


    });

    function InventoryReciptAddShow(ele) {
        if ($(ele).prop("checked") == true) {
            $('#InventoryReciptAddDivShow').show(1000);
        }
        else if ($(ele).prop("checked") == false) {
            $("#show_item_in tbody").html('');
            $("#show_item_account_in tbody").html('');
            $('.total_dr').html('');
            $('.total_cr').html('');
            $('#InventoryReciptAddDivShow').hide(1000);
        }
    }

    function InventoryOutShow(ele) {
        if ($(ele).prop("checked") == true) {
            $('#InventoryOutAddDivShow').show(1000);
        }
        else if ($(ele).prop("checked") == false) {
            $("#show_item_out tbody").html('');
            $("#show_item_account_out tbody").html('');
            $('.total_dr_out').html('');
            $('.total_cr_out').html('');
            $('#InventoryOutAddDivShow').hide(1000);
        }
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
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
            }
        });
    }

    //get product purchases price
    function getProductPrice(product_id) {


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
            }
        });
    }


    $(document).ready(function () {


        $('.rate').blur(function () {
            var rate = parseFloat($(this).val());
            if (isNaN(rate)) {
                rate = 0;
            }
            $(this).val(parseFloat(rate.toFixed(2)));
        });

        $('.quantity').keyup(function () {
            priceCalIn();
        });
        $('.rate').keyup(function () {
            priceCalIn();
        });
    });

    function priceCalIn() {
        var quantity = $('.quantity').val();
        var rate = $('.rate').val();
        var qtyyIn = parseFloat((rate * quantity).toFixed(2));
        $('.price').val(qtyyIn);
    }


    var j = 0;

    $("#add_item").click(function () {


        var productID = $('#productID').val();
        var package_id = $('#package_id').val();
        var package_id2 = $('#productID2').val();

        var productCatID = $('#CategorySelect').find('option:selected').attr('categoryId');
        var productCatName = $('#CategorySelect').find('option:selected').attr('categoryName');

        var productName = $('#productID').find('option:selected').attr('productName');
        var productName2 = $('#productID2').find('option:selected').attr('productName2');
        var ispackage = $('#productID').find('option:selected').attr('ispackage');
        var quantity = $('.quantity').val();
        var returnAble = $('.returnAble').val();
        var rate = $('.rate').val();
        var price = $('.price').val();
        var returnQuentity = $('.returnQuentity').val();


        if (quantity == '') {
            swal("Qty can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (price == '' || price == '0.00') {
            swal("Price can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (productID == '') {
            swal("Product id can't be empty.!", "Validation Error!", "error");
            return false;
        } else {
            var tab;

            tab = '<tr class="new_item_inv_in_' + j + '">' +
                '<td style="text-align: center"><input type="hidden" name="productID[]" value="' + productID + '">' + productCatName +
                '</td>' +
                '<td style="text-align: center"><input type="hidden" name="catproductID[]" value="' + productCatID + '">' + productName +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="rate[]" value="' + rate + '">' + rate +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="quantity[]" value="' + quantity + '">' + quantity +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" class="inventory_in_price" name="price[]" value="' + price + '">' + price +
                '</td>' +
                '<td><a del_id="' + j + '" class="delete_item_in_inventory btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i></a></td>' +

                '</tr>';
            $("#show_item_in tbody").append(tab);

            $('#CategorySelect').val('').trigger('chosen:updated');
            $('#productID').val('').trigger('chosen:updated');
            $('#productID2').val('').trigger('chosen:updated');
            $('.quantity').val('');
            $('.is_same').val('0');
            $('.rate').val('');
            $('.price').val('');
            $('.returnAble').val('');
            $('.returnQuentity').val('');
        }


    });

    function getProductList2(cat_id) {
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


                $('#productIDOut').chosen();
                $('#productIDOut option').remove();
                $("#productIDOut").trigger("chosen:open");
                $('#productIDOut').append($(data));
                $("#productIDOut").trigger("chosen:updated");
            }
        });
    }

    function getProductPrice2(product_id) {


        $.ajax({
            type: "POST",
            url: baseUrl + "FinaneController/getProductPrice",
            data: 'product_id=' + product_id,
            success: function (data) {
                if (data != '0.00' && data >= 1) {
                    $('.rateOut').val(data);
                } else {
                    $('.rateOut').val('');
                }
            }
        });
    }

    var k = 0;

    $("#add_item_out").click(function () {


        var productIDOut = $('#productIDOut').val();
        var package_idOut = $('#package_idOut').val();
        var package_idOut = $('#productIDOut').val();
        var productCatIDOut = $('#CategorySelect2').find('option:selected').attr('categoryId');
        var productCatNameOut = $('#CategorySelect2').find('option:selected').attr('categoryName');
        var productCatNameOut2 = $('#CategorySelect2').find('option:selected').attr('categoryName2');
        var productNameOut = $('#productIDOut').find('option:selected').attr('productName');
        var productNameOut2 = $('#productIDOut').find('option:selected').attr('productName2');
        var ispackageOut = $('#productIDOut').find('option:selected').attr('ispackage');
        var quantityOut = $('.quantityOut').val();
        var returnAbleOut = $('.returnAbleOut').val();
        var rateOut = $('.rateOut').val();
        var priceOut = $('.priceOut').val();
        var returnQuentityOut = $('.returnQuentityOut').val();


        if (quantityOut == '') {
            swal("Qty can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (priceOut == '' || priceOut == '0.00') {
            swal("Price can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (productIDOut == '') {
            swal("Product id can't be empty.!", "Validation Error!", "error");
            return false;
        } else {
            var tab;

            tab = '<tr class="new_item_inv_out_' + k + '">' +
                '<td style="text-align: center"><input type="text" name="productIdOut[]" value="' + productIDOut + '">' + productCatNameOut +
                '</td>' +
                '<td style="text-align: center"><input type="hidden" name="categoryOut[]" value="' + productCatIDOut + '">' + productNameOut +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="rateOut[]" value="' + rateOut + '">' + rateOut +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="quantityOut[]" value="' + quantityOut + '">' + quantityOut +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" class="inventory_out_price" name="priceOut[]" value="' + priceOut + '">' + priceOut +
                '</td>' +
                '<td><a del_id="' + k + '" class="delete_item_out_inventory btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i></a></td>' +
                '</tr>';
            $("#show_item_out tbody").append(tab);

            $('#CategorySelect2').val('').trigger('chosen:updated');
            $('#productIDOut').val('').trigger('chosen:updated');
            $('#productIDOut').val('').trigger('chosen:updated');
            $('.quantityOut').val('');
            $('.is_same').val('0');
            $('.rateOut').val('');
            $('.priceOut').val('');
            $('.returnAbleOut').val('');
            $('.returnQuentityOut').val('');
        }


    });

    $(document).ready(function () {


        $('.rateOut').blur(function () {
            var rateOut = parseFloat($(this).val());
            if (isNaN(rateOut)) {
                rateOut = 0;
            }
            $(this).val(parseFloat(rateOut).toFixed(2));
        });

        $('.quantityOut').keyup(function () {
            priceCal();
        });
        $('.rateOut').keyup(function () {
            priceCal();
        });
    });

    function priceCal() {
        var quantityOut = $('.quantityOut').val();
        var rateOut = $('.rateOut').val();
        var tt_out_rate = parseFloat(rateOut * quantityOut).toFixed(2);
        $('.priceOut').val(tt_out_rate);
    }


    $(".btn_remove_ou").on('click', function () {
        $(this).parent().parent().remove();
    });

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
</script>


<script>


    $(document).on("keyup", ".amountDrIN", function () {


        findAmountDr();

        findAmountCr();

        checkValidation();

    });

    $(document).on("keyup", ".amountCrIN", function () {


        findAmountDr();

        findAmountCr();

        checkValidation();

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


    $(document).on("keyup", ".amountDrIN", function () {

        var id_arr = $(this).attr('id');

        var id = id_arr.split("_");

        var element_id = id[id.length - 1];


        var debit = parseFloat($("#debitINId_" + element_id).val());

        if (isNaN(debit)) {

            debit = 0;

        }

        var credit = parseFloat($("#creditINId_" + element_id).val());

        if (isNaN(credit)) {

            credit = 0;

        }


        if ((debit == '' || debit == 0) && (credit == '' || credit == 0)) {

            $("#debitINId_" + element_id).attr('readonly', false);

            $("#creditINId_" + element_id).attr('readonly', false);


            $("#debitINId_" + element_id).val('');

            $("#creditINId_" + element_id).val('');


        } else if ((debit == '' || debit == 0) && credit > 0) {

            $("#debitINId_" + element_id).attr('readonly', true);

            $("#creditINId_" + element_id).attr('readonly', false);


            $("#debitINId_" + element_id).val('');

            //$("#creditId_"+element_id).val('');


        } else {

            $("#debitINId_" + element_id).attr('readonly', false);

            $("#creditINId_" + element_id).attr('readonly', true);

            //   $("#debitINId_"+element_id).val('');

            $("#creditINId_" + element_id).val('');

        }


    });

    $(document).on("keyup", ".amountCrIN", function () {

        var id_arr = $(this).attr('id');

        var id = id_arr.split("_");

        var element_id = id[id.length - 1];


        var debit = parseFloat($("#debitINId_" + element_id).val());

        if (isNaN(debit)) {

            debit = 0;

        }

        var credit = parseFloat($("#creditINId_" + element_id).val());

        if (isNaN(credit)) {

            credit = 0;

        }


        if ((debit == '' || debit == 0) && (credit == '' || credit == 0)) {

            $("#debitINId_" + element_id).attr('readonly', false);

            $("#creditINId_" + element_id).attr('readonly', false);


            $("#debitINId_" + element_id).val('');

            $("#creditINId_" + element_id).val('');


        } else if ((debit == '' || debit == 0) && credit > 0) {

            $("#debitINId_" + element_id).attr('readonly', true);

            $("#creditINId_" + element_id).attr('readonly', false);


            $("#debitINId_" + element_id).val('');

            //$("#creditId_"+element_id).val('');


        } else {

            $("#debitINId_" + element_id).attr('readonly', false);

            $("#creditINId_" + element_id).attr('readonly', true);

            //   $("#debitINId_"+element_id).val('');

            $("#creditINId_" + element_id).val('');

        }


    });


    function checkInputValue(value) {

        var debit = parseFloat($(".amountDrINValue").val());

        if (isNaN(debit)) {

            debit = 0;

        }

        var credit = parseFloat($(".amountCrValue").val());

        if (isNaN(credit)) {

            credit = 0;

        }


        if (debit != '' || credit != '') {

            if (debit == '') {

                $(".amountDrINValue").attr('disabled', true);

            } else {

                $(".amountCrValue").attr('disabled', true);

            }


        } else {

            $(".amountDrINValue").attr('disabled', false);

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


    $('.amountDrINValue').change(function () {

        var drValue = parseFloat($(this).val());

        if (isNaN(drValue)) {

            drValue = 0;

        }

        if (drValue != '') {

            $(this).val(drValue.toFixed(2));

        }

    });


    $('.amountCrValue').change(function () {


        var crValue = parseFloat($(this).val());

        if (isNaN(crValue)) {

            crValue = 0;

        }

        if (crValue != '') {

            $(this).val(crValue.toFixed(2));

        }


    });

    var findAmountDr = function () {

        var ttotal_amountDrIN = 0;

        $.each($('.amountDrIN'), function () {

            amount = $(this).val();

            amount = Number(amount);

            ttotal_amountDrIN += amount;

        });

        $('.total_dr').html(parseFloat(ttotal_amountDrIN.toFixed(2)));

    };


    var findAmountCr = function () {

        var ttotal_amountCr = 0;

        $.each($('.amountCrIN'), function () {

            amount = $(this).val();

            amount = Number(amount);

            ttotal_amountCr += amount;

        });

        $('.total_cr').html(parseFloat(ttotal_amountCr.toFixed(2)));

    };


    $(document).ready(function () {


        var j = 0;

        $("#add_item_inv_in_account").click(function () {

            var accountId = $('.accountId').val();

            var accountName = $(".accountId").find('option:selected').attr('paytoAccountName');

            var accountCode = $(".accountId").find('option:selected').attr('paytoAccountCode');

            var amountDrIN = parseFloat($('.amountDrINValue').val());

            if (isNaN(amountDrIN)) {

                amountDrIN = 0;

            }

            var amountCr = parseFloat($('.amountCrValue').val());


            if (isNaN(amountCr)) {

                amountCr = 0;

            }


            var memo = $('.memo').val();

            if (accountId == '' || accountId == null) {

                productItemValidation("Account Head can't be empty.");

                return false;

            } else if (amountDrIN == '' && amountCr == '') {

                productItemValidation("Debit or Credit amount can't be empty.");

                return false;

            } else {


                if (amountDrIN == '' || amountDrIN == 0) {

                    $("#show_item_account_in tbody").append('<tr class="new_item' + accountId + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="accountIN[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amountDrIN form-control text-right decimal" type="text"  name="amountDrIN[]" onblur="checkInputValue(this.value)" id="debitINId_' + j + '" value="' + amountDrIN + '" readonly></td><td style="padding-left:15px;"  align="right"><input class="amountCrIN  form-control text-right decimal" type="text"  onblur="checkInputValue(this.value)"  name="amountCrIN[]"  id="creditINId_' + j + '"  value="' + amountCr + '"></td><td align="right"><input type="text" class="add_quantity  form-control text-right" name="memoIN[]" value="' + memo + '"></td><td><a del_id="' + accountId + j + '" class="delete_item_in_amount btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i></a></td></tr>');


                } else {

                    $("#show_item_account_in tbody").append('<tr class="new_item' + accountId + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="accountIN[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amountDrIN form-control text-right decimal" type="text"  name="amountDrIN[]" onblur="checkInputValue(this.value)" id="debitINId_' + j + '" value="' + amountDrIN + '"  ></td><td style="padding-left:15px;"  align="right"><input class="amountCrIN  form-control text-right decimal" type="text"  onblur="checkInputValue(this.value)"  name="amountCrIN[]"  id="creditINId_' + j + '" readonly value="' + amountCr + '"></td><td align="right"><input type="text" class="add_quantity  form-control text-right" name="memoIN[]" value="' + memo + '"></td><td><a del_id="' + accountId + j + '" class="delete_item_in_amount btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i></a></td></tr>');

                }


            }


            $(".amountDrINValue").attr('disabled', false);

            $(".amountCrValue").attr('disabled', false);


            $('.amountDrINValue').val('');

            $('.amountCrValue').val('');

            $('.memo').val('');

            $('.accountId').val('').trigger('chosen:updated');

            j++;

            findAmountDr();

            findAmountCr();

            // checkValidation();


        });

        $(document).on('click', '.delete_item_in_amount', function () {

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

                        findAmountDr();

                        findAmountCr();

                        checkValidation();

                    } else {

                        return false;

                    }

                });


        });

        $(document).on('click', '.delete_item_in_inventory', function () {

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

                        $('.new_item_inv_in_' + id).remove();

                        findAmountDr();

                        findAmountCr();

                        checkValidation();

                    } else {

                        return false;

                    }

                });


        });
        $(document).on('click', '.delete_item_out_inventory', function () {

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

                        $('.new_item_inv_out_' + id).remove();

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


<script type="text/javascript">

    $(document).on("keyup", ".amountDrOut", function () {


        findAmountDrOut();

        findAmountCrOut();

        //checkValidation();

    });

    $(document).on("keyup", ".amountCrOut", function () {


        findAmountDr();

        findAmountCr();

        checkValidation();

    });

    function checkInputValueOut(value) {

        var debit = parseFloat($(".amountDrValueOut").val());

        if (isNaN(debit)) {

            debit = 0;

        }

        var credit = parseFloat($(".amountCrValueOut").val());

        if (isNaN(credit)) {

            credit = 0;

        }

        if (debit != '' || credit != '') {
            if (debit == '') {
                $(".amountDrValueOut").attr('disabled', true);
            } else {
                $(".amountCrValueOut").attr('disabled', true);
            }
        } else {
            $(".amountDrValueOut").attr('disabled', false);
            $(".amountCrValueOut").attr('disabled', false);
        }


    }

    $('.amountDrValueOut').change(function () {

        var drValue = parseFloat($(this).val());

        if (isNaN(drValue)) {

            drValue = 0;

        }

        if (drValue != '') {

            $(this).val(drValue.toFixed(2));

        }

    });


    $('.amountCrValueOut').change(function () {


        var crValue = parseFloat($(this).val());

        if (isNaN(crValue)) {

            crValue = 0;

        }

        if (crValue != '') {

            $(this).val(crValue.toFixed(2));

        }


    });

    var findAmountDrOut = function () {

        var ttotal_amountDrIN = 0;

        $.each($('.amountDrOut'), function () {

            amount = $(this).val();

            amount = Number(amount);

            ttotal_amountDrIN += amount;

        });

        $('.total_dr_out').html(parseFloat(ttotal_amountDrIN.toFixed(2)));

    };


    var findAmountCrOut = function () {

        var ttotal_amountCr = 0;

        $.each($('.amountCrOut'), function () {

            amount = $(this).val();

            amount = Number(amount);

            ttotal_amountCr += amount;

        });

        $('.total_cr_out').html(parseFloat(ttotal_amountCr.toFixed(2)));

    };


    $(document).ready(function () {


        var j = 0;

        $("#add_item_inv_out_account").click(function () {

            var accountId = $('.accountIdOut').val();

            var accountName = $(".accountIdOut").find('option:selected').attr('paytoAccountName');

            var accountCode = $(".accountIdOut").find('option:selected').attr('paytoAccountCode');

            var amountDrIN = parseFloat($('.amountDrValueOut').val());

            if (isNaN(amountDrIN)) {

                amountDrIN = 0;

            }

            var amountCr = parseFloat($('.amountCrValueOut').val());


            if (isNaN(amountCr)) {

                amountCr = 0;

            }


            var memo = $('.memo').val();

            if (accountId == '' || accountId == null) {

                productItemValidation("Account Head can't be empty.");

                return false;

            } else if (amountDrIN == '' && amountCr == '') {

                productItemValidation("Debit or Credit amount can't be empty.");

                return false;

            } else {
                if (amountDrIN == '' || amountDrIN == 0) {
                    $("#show_item_account_out tbody").append('<tr class="new_item_out' + accountId + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="accountOut[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amountDrOut form-control text-right decimal" type="text"  name="amountDrOut[]" onblur="checkInputValue(this.value)" id="debitIdOut_' + j + '" value="' + amountDrIN + '" readonly></td><td style="padding-left:15px;"  align="right"><input class="amountCrOut  form-control text-right decimal" type="text"  onblur="checkInputValue(this.value)"  name="amountCrOut[]"  id="creditINIdOut_' + j + '"  value="' + amountCr + '"></td><td align="right"><input type="text" class="add_quantity  form-control text-right" name="memoOut[]" value="' + memo + '"></td><td><a del_id="' + accountId + j + '" class="delete_item_Out_amount btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i></a></td></tr>');
                } else {
                    $("#show_item_account_out tbody").append('<tr class="new_item_out' + accountId + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' + accountCode + ' ] ' + '<input type="hidden" name="accountOut[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amountDrOut form-control text-right decimal" type="text"  name="amountDrOut[]" onblur="checkInputValue(this.value)" id="debitIdOut_' + j + '" value="' + amountDrIN + '"  ></td><td style="padding-left:15px;"  align="right"><input class="amountCrOut  form-control text-right decimal" type="text"  onblur="checkInputValue(this.value)"  name="amountCrOut[]"  id="creditIdOut_' + j + '" readonly value="' + amountCr + '"></td><td align="right"><input type="text" class="add_quantity  form-control text-right" name="memoOut[]" value="' + memo + '"></td><td><a del_id="' + accountId + j + '" class="delete_item_Out_amount btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i></a></td></tr>');

                }


            }

            $(".amountDrValueOut").attr('disabled', false);
            $(".amountCrValueOut").attr('disabled', false);
            $('.amountDrValueOut').val('');
            $('.amountCrValueOut').val('');
            $('.memoOut').val('');
            $('.accountIdOut').val('').trigger('chosen:updated');
            j++;
            findAmountDrOut();
            findAmountCrOut();
            //checkValidation();


        });

        $(document).on('click', '.delete_item_Out_amount', function () {
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
                        $('.new_item_out' + id).remove();
                        findAmountDrOut();
                        findAmountCrOut();
                        checkValidation();
                    } else {
                        return false;
                    }
                });
        });

    });


    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });

    //isconfirmSave

    function isconfirmSave() {

        var total_inventory_in_price = 0;
        var total_inventory_in_amount = 0;


        $.each($('.inventory_in_price'), function () {
            var inventory_in_price = 0;
            inventory_in_price = $(this).val();
            inventory_in_price = Number(inventory_in_price);
            total_inventory_in_price += inventory_in_price;

        });
        var total_dr = parseFloat($(".total_dr").text());
        if (isNaN(total_dr)) {
            total_dr = 0;
        }
        var total_cr = parseFloat($(".total_cr").text());
        if (isNaN(total_cr)) {
            total_cr = 0;
        }
        total_inventory_in_amount = total_inventory_in_price + (total_dr - total_cr);
        console.log('total_inventory_in_price');
        console.log(total_inventory_in_price);
        console.log('total_dr');
        console.log(total_dr);
        console.log('total_cr');
        console.log(total_cr);


        var total_inventory_out_price = 0;
        var total_inventory_out_amount = 0;


        $.each($('.inventory_out_price'), function () {
            var inventory_out_price = 0;
            inventory_out_price = $(this).val();
            inventory_out_price = Number(inventory_out_price);
            total_inventory_out_price += inventory_out_price;

        });
        var total_dr_out = parseFloat($(".total_dr_out").text());
        if (isNaN(total_dr_out)) {
            total_dr_out = 0;
        }
        var total_cr_out = parseFloat($(".total_cr_out").text());
        if (isNaN(total_cr_out)) {
            total_cr_out = 0;
        }
        total_inventory_out_amount = total_inventory_out_price + (total_cr_out - total_dr_out);

        console.log('total_inventory_out_price');
        console.log(total_inventory_out_price);
        console.log('total_dr_out');
        console.log(total_dr_out);
        console.log('total_cr_out');
        console.log(total_cr_out);


        console.log('total_inventory_in_amount');
        console.log(total_inventory_in_amount);
        console.log('total_inventory_out_amount');
        console.log(total_inventory_out_amount);


        var IsInventoryReciptAdd=0;
        var IsInventoryOutAdd=0;
        if ($('#InventoryReciptAdd').prop("checked") == true) {
            IsInventoryReciptAdd=1;
        }
        if ($('#InventoryOutAdd').prop("checked") == true) {
            IsInventoryOutAdd=1;
        }


        if (IsInventoryOutAdd==1 && IsInventoryReciptAdd==1 && total_inventory_in_amount != total_inventory_out_amount) {
            swal("Your in amount And out amount not equal !. ", "Validation Error!", "error");
            // return false;
        } else if (total_inventory_in_price == 0 && total_inventory_out_price == 0) {
            swal("Select at least One In or Out Product !. ", "Validation Error!", "error");
            //return false;
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
