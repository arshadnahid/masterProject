<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 10:04 AM
 */
?>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase($link_page_name) ?></div>

            </div>
            <div class="portlet-body">
                <form id="publicForm" action="" method="post" class="form-horizontal">
                    <fieldset style="">
                        <legend style="">Inventory Recipt :</legend>
                         <div class="row">
                             <div class="col-md-12">
                                 <select class="chosen-select form-control" name="branchId" 
                                                    data-placeholder="Select Branch" id="branchId">
                                                <option></option>
                                                <?php
                                               
                                                foreach ($branch as $branchs) {
                                                    
                                                        ?>
                                                        <option value="<?php echo $branchs->branch_id ?>"><?php echo $branchs->branch_name ?></option>
                                                    <?php 
                                                } ?>

                                            </select>

                             </div>
                         </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="show_item_in">
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
                                                        <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                                    <?php }
                                                } ?>

                                                <?php
                                                $categoryArray = array('1', '2');
                                                foreach ($productCat as $eachInfo) {
                                                    if (!in_array($eachInfo->category_id, $categoryArray)) {
                                                        ?>
                                                        <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
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
                                            <input type="text" id=""  value="" class="form-control rate"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id=""  value="" class="form-control quantity"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id=""  value="" class="form-control price"
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


                            </div>
                        </div>


                    </fieldset>



                    <fieldset style="">
                        <legend style="">Inventory Out :</legend>

                        <div class="row">
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
                                                        <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                                    <?php }
                                                } ?>

                                                <?php
                                                $categoryArray = array('1', '2');
                                                foreach ($productCat as $eachInfo) {
                                                    if (!in_array($eachInfo->category_id, $categoryArray)) {
                                                        ?>
                                                        <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
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
                                            <input type="text" id=""  value="" class="form-control rateOut"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id=""  value="" class="form-control quantityOut"
                                                   onclick="this.select();"
                                                   placeholder="" style="text-align: right;"/>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" id=""  value="" class="form-control priceOut"
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


                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info form-control" value="Save"></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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
            $(this).val(parseFloat(rate).toFixed(2));
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
        var qtyyIn = parseFloat(rate * quantity).toFixed(2);
        $('.price').val(qtyyIn);
    }

    var j = 0;

    $("#add_item").click(function () {


        var productID = $('#productID').val();
        var package_id = $('#package_id').val();
        var package_id2 = $('#productID2').val();
        var productCatID = $('#productID').find('option:selected').attr('categoryId');
        var productCatName = $('#productID').find('option:selected').attr('categoryName');
        var productCatName2 = $('#productID2').find('option:selected').attr('categoryName2');
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

            tab ='<tr class="new_item' + j + '">' +
                '<td style="text-align: center"><input type="hidden" name="productIdIn[]" value="'+productID+'">' + productCatName +
                '</td>' +
                '<td style="text-align: center"><input type="hidden" name="categoryIdIn[]" value="'+productID+'">' + productName +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="rateIn[]" value="'+rate+'">' + rate +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="quantityIn[]" value="'+quantity+'">' + quantity +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="priceIn[]" value="'+price+'">' + price +
                '</td>' +
                '<td><button type="button" name="delete_btn" id="" class="btn btn-xs btn-danger btn_delete_out"><span class="glyphicon glyphicon-remove"></span></button></td>'+
                
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

     function getSingleAdjustment(id){
            $.ajax({
                type  : 'ajax',
                url   : '<?php echo site_url('lpg/InventoryAdjustmentController/singleInvoiceAdjustment')?>',
                async : true,
                data: 'id=' + id,
                success : function(data){
                    var m=0;
                    var html = '';
                    var i;
                    for(i=0; i<data.length; i++){
                       
                        var tab;

            tab ='<tr class="new_item' + m + '">' +
                '<td style="text-align: center"><input type="hidden" name="productIdIn[]" value="'+productID+'">' + '+data[i].productID+' +
                '</td>' +
                '<td style="text-align: center"><input type="hidden" name="categoryIdIn[]" value="'+productID+'">' + '+data[i].productID+' +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="rateIn[]" value="'+rate+'">' + '+data[i].rate+' +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="quantityIn[]" value="'+quantity+'">' + '+data[i].quantity+' +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="priceIn[]" value="'+price+'">' + '+data[i].price+' +
                '</td>' +
                '<td><button type="button" name="delete_btn" id="" class="btn btn-xs btn-danger btn_delete_out"><span class="glyphicon glyphicon-remove"></span></button></td>'+
                
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
        }
  

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

        var productCatIDOut = $('#productIDOut').find('option:selected').attr('categoryId');
        var productCatNameOut = $('#productIDOut').find('option:selected').attr('categoryName');
        var productCatNameOut2 = $('#productIDOut').find('option:selected').attr('categoryName2');
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

            tab ='<tr class="new_item' + k + '">' +
                '<td style="text-align: center"><input type="hidden" name="productIdOut[]" value="'+productIDOut+'">' + productCatNameOut +
                '</td>' +
                '<td style="text-align: center"><input type="hidden" name="categoryOut[]" value="'+productIDOut+'">' + productIDOut +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="rateOut[]" value="'+rateOut+'">' + rateOut +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="quantityOut[]" value="'+quantityOut+'">' + quantityOut +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="priceOut[]" value="'+priceOut+'">' + priceOut +
                '</td>' +
                '<td><button type="button" name="delete_btn" id="" class="btn btn-xs btn-danger btn_remove_out"><span class="glyphicon glyphicon-remove"></span></button></td>'+
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
        var tt_out_rate= parseFloat(rateOut * quantityOut).toFixed(2);
        $('.priceOut').val(tt_out_rate);
    }




$(".btn_remove_ou").on('click',function(){
            $(this).parent().parent().remove();
        });

</script>



