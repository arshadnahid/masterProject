<style>
    table tr td{
        margin: 2px!important;
        padding: 2px!important;
    }
</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Sales</a>
                </li>
                <li class="active">Sales Invoice Add</li>
            </ul>
            
            
            <ul class="breadcrumb pull-right">
                
                <li>
                    <a href="<?php echo site_url('salesInvoice'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
                

            </ul>
            
        </div>


        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label no-padding-right" for="form-field-1">Customer ID</label>
                                <div class="col-sm-7">
                                    <select  id="customerid" name="customer_id"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Customer ID or Name">
                                        <option></option>
                                        <?php foreach ($customerList as $key => $each_info): ?>
                                            <option value="<?php echo $each_info->customer_id; ?>"><?php echo $each_info->customerID . ' [ ' . $each_info->customerName . ' ] '; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Invoice ID</label>
                                <div class="col-sm-7">
                                    <input type="text" id="form-field-1" name="voucherid" readonly value="<?php echo $voucherID; ?>" class="form-control" placeholder="Invoice ID" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 col-sm-offset-1 control-label no-padding-right" for="form-field-1"> Receive Date</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="saleDate" id="id-date-picker-1" type="text" value="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd" />
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
                                Sales Item
                            </div>
                            <table class="table table-bordered table-hover" id="show_item">
                                <thead>
                                    <tr>
                                        <th style="width:18%"  align="center"><strong>Product Category</strong></th>
                                        <th style="width:25%" align="center"><strong>Product</strong></th>
                                        <th style="width:18%" align="center"><strong>Unit</strong></th>
                                        <th style="width:17%" align="center"><strong>Return(Qty)</strong></th>
                                        <th style="width:17%" align="center"><strong>Unit Price(BDT) </strong></th>
                                        <th style="width:17%" align="center"><strong>Total Price(BDT)</strong></th>
                                        <th style="width:15%" align="center"><strong>Action</strong></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <!--<select data-placeholder="(:-- Select Category --:)"  class="category_product select-search1">-->
                                            <select id="category_product"  onchange="getProductList(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by category">
                                                <option value=""></option>
                                                <?php
                                                foreach ($productCat as $eachCat):
                                                    ?>
                                                    <option catName="<?php echo $eachCat->title; ?>" value="<?php echo $eachCat->category_id; ?>">
                                                        <?php echo $eachCat->title; ?>
                                                    </option>													
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select  id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by product name">
                                                <option value=""></option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="productUnit" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Unit Name">
                                                <option value=""></option>
                                                <?php
                                                foreach ($unitList as $eachUnit):
                                                    ?>
                                                    <option unitName="<?php echo $eachUnit->unitTtile; ?>" value="<?php echo $eachUnit->unit_id; ?>">
                                                        <?php echo $eachUnit->unitTtile; ?>
                                                    </option>													
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="hidden" value="" id="stockQty"/><input type="text"  onkeyup="checkStockOverQty(this.value)" class="form-control text-right quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>
                                        <!--<td><input type="hidden" value="" id="returnStockQty"/><input type="text"  onkeyup="checkReturnStockOverQty(this.value)" class="form-control text-right returnQuantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>-->
                                        <td><input type="text" class="form-control text-right rate" placeholder="0.00" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" ></td>
                                        <td><input type="text" class="form-control text-right price" placeholder="0.00" readonly="readonly"></td>
                                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                    </tr> 
                                    <tr>
                                        <td align="right" colspan="3"><strong>Sub-Total(BDT)</strong></td>
                                        <td align="right"><strong class="total_quantity"></strong></td>
                                        <!--<td align="right"><strong class="total_return_quantity"></strong></td>-->
                                        <td align="right"><strong class="total_rate"></strong></td>
                                        <td align="right"><strong class="total_price"></strong></td>
                                    </tr> 
                                    <tr>
                                        <td colspan="3" rowspan="1">
                                            <textarea style="border:none;" rows="2" class="form-control" name="narration" placeholder="Narration......" type="text"></textarea>
                                        </td>
<!--                                        <td colspan="3"  align="right"><strong>Discount ( - ) </strong></td>
                                        <td><input type="text"  onkeyup="calDiscount()" id="disCount" style="text-align: right" name="discount" value="" class="form-control" placeholder="0.00"/></td>
                                    --></tr>
                                    
                                </tfoot> 
                            </table> 
                        </div>




                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-1 col-md-10">
                                <button disabled onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
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
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script>
    
    
    
    function calculatePartialPayment(){
        var netAmount = parseFloat($("#netAmount").val());
        if(isNaN(netAmount)){
            netAmount=0;
        }
        var payment = parseFloat($("#payment").val());
        if(isNaN(payment)){
            payment=0;
        }
        
        var finalDue=netAmount-payment;
        $("#duePayment").val(finalDue);
        
        
    }
    
    function showDuePayment(paymentValue){
        var netAmount = parseFloat($("#netAmount").val());
        var allocateAmount=parseFloat(paymentValue);
        var dueAmount =netAmount - allocateAmount;
        
    }
    
    
    
    
    function showBankinfo(id){
        if(id == 3){
            $("#showBankInfo").show(1000); 
        }else{
            $("#showBankInfo").hide(1000);   
        }
        if(id == 4){
            $(".partisals").show(1000); 
        }else{
            $(".partisals").hide(1000);   
        }
        
        if(id == 2){
            $(".creditDate").show(1000); 
        }else{
            $(".creditDate").hide(1000);   
        }
       
        
    }
    
    
    
    function saveNewCustomer(){
        
        // alert("hello");
        var url = '<?php echo site_url("SalesController/saveNewCustomer") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:$("#publicForm2").serializeArray(),
            success: function (data)
            {
                $('#myModal').modal('toggle');
                $('#newCustomerHide').hide();
                $('#customerid').chosen();
                //$('#customerid option').remove();
                $('#customerid').append($(data));
                $("#customerid").trigger("chosen:updated");
               
            }
        });
        
    }
    function checkDuplicatePhone(phone){
        var url = '<?php echo site_url("SalesController/checkDuplicatePhone") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'phone': phone},
            success: function (data)
            {
                if(data == 1){
                    $("#subBtn2").attr('disabled',true);
                    $("#errorMsg").show(1000);
                }else{
                    $("#subBtn2").attr('disabled',false);
                    $("#errorMsg").hide(1000);
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
            //  $(this).val(parseFloat($(this).val()).toFixed(2));
            priceCal();
        });
    });
    function priceCal() {
        var quantity = $('.quantity').val();
        var rate = $('.rate').val();
        $('.price').val(parseFloat(rate * quantity).toFixed(2));
    }


    function calDiscount() {
        findDiscount();
        findVatAmount();
    }
    var findDiscount = function () {
        var totalPrice = parseFloat($(".total_price").text());
        if(totalPrice == ''){
            $("#disCount").val('');
        }
        var disCount = parseFloat($("#disCount").val());
        if(isNaN(disCount)){
            disCount=0;
        }
        //var deductDiscount = (disCount / 100) * totalPrice;
        $("#grandTotal").val(parseFloat(Math.round(totalPrice - disCount)).toFixed(2));

    };
    var findVatAmount = function () {
        var vatValue = $("#vatAmount").val();

        var grandTotal = parseFloat($("#grandTotal").val());
        var vatForwardAmount = parseFloat((vatValue / 100) * grandTotal);
        $(".totalVatAmount").html(parseFloat(Math.round(vatForwardAmount)).toFixed(2));
        $("#netAmount").val(parseFloat(Math.round(vatForwardAmount) + grandTotal).toFixed(2));
    };
    var findTotalQty = function () {
        var total_quantity = 0;
        $.each($('.add_quantity'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            total_quantity += quantity;
        });
        $('.total_quantity').html(parseFloat(total_quantity));
    };
    var findTotalReturnQty = function () {
        var total_return_quantity = 0;
        $.each($('.add_ReturnQuantity'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            total_return_quantity += quantity;
        });
        $('.total_return_quantity').html(parseFloat(total_return_quantity));
    };
    var findTotalRate = function () {
        var total_rate = 0;
        $.each($('.add_rate'), function () {
            rate = $(this).val();
            rate = Number(rate);
            total_rate += rate;
        });
        $('.total_rate').html(parseFloat(total_rate).toFixed(2));
    };
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
        findTotalReturnQty();
        findTotalRate();
        findTotalPrice();
        calDiscount();
        calculatePartialPayment();
    };
    function checkStockOverQty(stockQty){
        var orgiStock=$("#stockQty").val();
        if(isNaN(orgiStock)){
            orgiStock=0;
        }
        var total_quantity = 0;
        $.each($('.add_quantity'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            total_quantity += quantity;
        });
        
        if(isNaN(total_quantity)){
            total_quantity=0;
        }
        var totalQty= parseFloat(orgiStock) - (parseFloat(total_quantity)+parseFloat(stockQty));
        if(parseFloat(totalQty) < 0){
            $(".quantity").val('');
            $(".quantity").val(parseFloat(orgiStock) - parseFloat(total_quantity));
            productItemValidation("Stock Quantity Not Available.");
        }
        
       
    }
    
    
    $(document).ready(function () {

        var j = 0;
        $("#add_item").click(function () {
            var productCatID = $('#category_product').val();
            var productCatName = $("#category_product").find('option:selected').attr('catName');
       
            var productID = $('#productID').val();
            var productName = $('#productID').find('option:selected').attr('productName');
            
            var productUnit = $('#productUnit').val();
            var unitName = $('#productUnit').find('option:selected').attr('unitName');
            
            
            
            var quantity = $('.quantity').val();
            var rate = $('.rate').val();
            var price = $('.price').val();
            var returnQuantity = $('.returnQuantity').val();
            
            if(productCatID  == ''){
                productItemValidation("Product Category can't be empty.");
             
                return false;
            }else if(productID == ''){
                productItemValidation("Product Name can't be empty.");
                return false;
            }else if(productUnit == ''){
                productItemValidation("Product Unit can't be empty.");
                return false;
                
            }else if(quantity == ''){
                productItemValidation("Quantity Can't be empty.");
                return false;
            }else if(rate == ''){
                productItemValidation("Unit Price Can't be empty.");
                return false;
            }else{
            
                $("#show_item tbody").append('<tr class="new_item' + productCatID + productID + '"><td style="padding-left:15px;">' + productCatName + '<input type="hidden" name="category_id[]" value="' + productCatID + '"></td><td style="padding-left:15px;">' + productName + '<input type="hidden"  name="product_id[]" value="' + productID + '"></td><td style="padding-left:15px;">' +  unitName + '<input type="hidden" name="unit_id[]" value="' + productUnit + '"></td><td align="right">' + quantity + '<input type="hidden" class="add_quantity" name="quantity[]" value="' + quantity + '"></td><td align="right">' + rate + '<input type="hidden" class="add_rate" name="rate[]" value="' + rate + '"></td><td align="right">' + price + '<input type="hidden" class="add_price" name="price[]" value="' + price + '"></td><td><a del_id="' + productCatID + productID  + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
            }
            
            $("#subBtn").attr('disabled',false);
            
            $('.quantity').val('');
            $('.rate').val('');
            $('.price').val('');
            $('.returnQuantity').val('');

            $(".quantity").attr("placeholder", "0"); 
            $('#productID').val('').trigger('chosen:updated');
            $('#category_product').val('').trigger('chosen:updated');
            findTotalCal();
                                          
        });
        $(document).on('click','.delete_item', function () {
            if(confirm("Are you sure?")){
                var id = $(this).attr("del_id");
                $('.new_item' + id).remove();
                findTotalCal();
            }
        });
    });
               
    function getProductPrice(product_id) {
       
        $("#stockQty").val(''); 
        $(".quantity").val(''); 
       
        var total_quantity = 0;
        $.each($('.add_quantity'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            total_quantity += quantity;
        });
      
        if(isNaN(total_quantity)){
            total_quantity=0;
        }
       
       
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('FinaneController/getProductPriceForSale'); ?>",
            data: 'product_id=' + product_id,
            success: function (data) {
                $('.rate').val(data);
            }
        });
//        $.ajax({
//            type: "POST",
//            url: "<?php echo site_url('FinaneController/getProductStock'); ?>",
//            data: 'product_id=' + product_id,
//            success: function (data) {
//                var mainStock = parseFloat(data) - parseFloat(total_quantity);
//                if(data !=''){
//                    $("#stockQty").val(data); 
//                    $(".quantity").attr("disabled",false); 
//                    
//                    if(mainStock <= 0){
//                        $(".quantity").attr("disabled",true); 
//                        $(".quantity").attr("placeholder", "Stock is =0 "); 
//                    }else{
//                        $(".quantity").attr("disabled",false); 
//                        $(".quantity").attr("placeholder", "Stock is = "+mainStock); 
//                    }
//                }else{
//                    $("#stockQty").val(''); 
//                    $(".quantity").attr("disabled",true); 
//                    $(".quantity").attr("placeholder", "Stock is = 0"); 
//                }
//                
//              
//            }
//        });
        
        
        
        
    }
    function getProductList(cat_id) {
       
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('InventoryController/getProductList'); ?>",
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
    function checkDuplicateCategory(catName){
        var url = '<?php echo site_url("SetupController/checkDuplicateCategory") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'catName': catName},
            success: function (data)
            {
                if(data == 1){
                    $("#subBtn").attr('disabled',true);
                    $("#errorMsg").show(1000);
                }else{
                    $("#subBtn").attr('disabled',false);
                    $("#errorMsg").hide(1000);
                }
            }
        });
        
    }
    
   
</script>