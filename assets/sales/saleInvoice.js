
function checkDuplicateCategory(catName){
    var url = baseUrl+ "SetupController/checkDuplicateCategory";
    $.ajax({
        type: 'POST',
        url: url,
        data:{
            'catName': catName
        },
        success: function (data)
        {
            if(data == 1){
                $("#subBtn").attr('disabled',true);
                $("#errorMsg").show(10);
            }else{
                $("#subBtn").attr('disabled',false);
                $("#errorMsg").hide(10);
            }
        }
    });
}
setTimeout(function() {
    //$('.partisals').fadeOut('fast');
    $('#culinderReceive').fadeOut();
}, 3000);
/*function showCylinder(){
    $('#culinderReceive table tbody tr').remove();
    $("#culinderReceive").toggle(10);
}*/
$(document).ready(function () {
    var j = 0;
    $("#add_item2").click(function () {
        var productCatID = 2;
        var productID = $('#productID2').val();
        var productName = $('#productID2').find('option:selected').attr('productName');
        var quantity = $('.quantity2').val();
        var rate = $('.rate2').val();
        var price = $('.price2').val();
        var returnQuantity = $('.returnQuantity2').val();
        if(productCatID  == ''){
            swal("Product Category can't be empty!", "Validation Error!", "error");

            return false;
        }else if(productID == ''){
            swal("Product Name can't be empty!", "Validation Error!", "error");
            return false;
        }else if(quantity == ''){
            swal("Quantity Can't be empty!", "Validation Error!", "error");
            return false;
        }else if(rate == ''){
            swal("Unit Price Can't be empty!", "Validation Error!", "error");
            return false;
        }else{
            $("#show_item2 tfoot").append('<tr class="new_item2' + productCatID + productID + '"><input type="hidden" name="category_id2[]" value="' + productCatID + '"><td style="padding-left:15px;">' + productName + '<input type="hidden"  name="product_id2[]" value="' + productID + '"></td><td align="right"><input type="text" class="add_quantity2 text-right form-control decimal" name="quantity2[]" value="' + quantity + '"></td><td><a del_id2="' + productCatID + productID  + '" class="delete_item2 btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
        }
        $("#subBtn").attr('disabled',false);
        $('.quantity2').val('');
        $('.rate2').val('');
        $('.price2').val('');
        $('.returnQuantity2').val('');
        $(".quantity2").attr("placeholder", "0");
        $('#productID2').val('').trigger('chosen:updated');
        $('#category_product2').val('').trigger('chosen:updated');
        findTotalCal2();
    });
    $(document).on('click','.delete_item2', function () {
        var id = $(this).attr("del_id2");
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
                $('.new_item2' + id).remove();
                findTotalCal2();
            }else{
                return false;
            }
        });

    });
});

function calcutateFinal(){

    console.log('from calcutateFinal');

    findVatAmount();
    var loader = parseFloat($("#loader").val());
    if(isNaN(loader)){
        loader=0;
    }
    var transportation = parseFloat($("#transportation").val());
    if(isNaN(transportation)){
        transportation=0;
    }
    var total_price = parseFloat($(".total_price").val());
    if(isNaN(total_price)){
        total_price=0;
    }
    var discount = parseFloat($("#disCount").val());
    if(isNaN(discount)){
        discount=0;
    }
    var total_price = (total_price + transportation +  loader) - discount;

    var payment = parseFloat($("#payment").val());
    if(isNaN(payment)){
        payment=0;
    }
    //var previousDue = parseFloat($("#previousDue").val());
    var previousDue = 0;
    if(isNaN(previousDue)){
        previousDue=0;
    }
    if(payment > total_price ){
        $("#currentDue").val(parseFloat(0.00).toFixed(2));
    }else{
        $("#currentDue").val(parseFloat(total_price - payment).toFixed(2));
    }
    $("#totalDue").val(parseFloat((total_price+previousDue) - payment).toFixed(2));
}

function getCustomerCurrentBalace(customerId){
    calculateCustomerDue();
    setTimeout(function() {
        //$('.partisals').fadeOut('fast');
        calcutateFinal();
    }, 100);
}
function calculatePartialPayment(){
    setTimeout(function() {
        calcutateFinal();
    }, 100);
}
function showDuePayment(paymentValue){
    var netAmount = parseFloat($("#netAmount").val());
    var allocateAmount=parseFloat(paymentValue);
    var dueAmount =netAmount - allocateAmount;
}


function saveNewCustomer(){
    var customerName=$("#customerName").val();
    var customerId=$("#customerId").val();
    if(customerName == ''){
        alert("Customer Name Can't be empty!!");
        return false;
    }else if(customerId == ''){
        alert("Customer Id Can't be empty!!");
        return false;
    }else{
        var url = baseUrl + "SalesController/saveNewCustomer";
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
}
function checkDuplicatePhone(phone){
    var url = baseUrl+ "SalesController/checkDuplicatePhone";
    $.ajax({
        type: 'POST',
        url: url,
        data:{
            'phone': phone
        },
        success: function (data)
        {
            if(data == 1){
                $("#subBtn2").attr('disabled',true);
                $("#errorMsg").show(10);
            }else{
                $("#subBtn2").attr('disabled',false);
                $("#errorMsg").hide(10);
            }
        }
    });
}
$(document).ready(function () {

     $('.rate').blur(function () {
        var rate = parseFloat($(this).val());
        if(isNaN(rate)){
            rate=0;
        }
        //$(this).val(parseFloat(rate).toFixed(2));
    });


    $('.quantity').keyup(function () {
        priceCal();
    });

    $('.rate').keyup(function () {
        priceCal();
    });
});
function priceCal() {
    var quantity = parseFloat($('.quantity').val());
    if(isNaN(quantity)){
        quantity=0;
    }
    var rate = parseFloat($('.rate').val());
    if(isNaN(rate)){
        rate=0;
    }
    $('.price').val(parseFloat(rate * quantity).toFixed(2));
}
function calDiscount() {
    findDiscount();
    findVatAmount();
    calculatePartialPayment();

}
var findDiscount = function () {
    var totalPrice = parseFloat($(".total_price").val());
    if(isNaN(totalPrice)){
        totalPrice=0;
    }
    if(totalPrice == ''){
        $("#disCount").val('');
    }
    var disCount = parseFloat($("#disCount").val());
    if(isNaN(disCount)){
        disCount=0;
    }
    //var deductDiscount = (disCount / 100) * totalPrice;
    $("#grandTotal").val(parseFloat(Math.round(totalPrice - disCount)).toFixed(2));
    calcutateFinal();


};
var findVatAmount = function () {
    var loader = parseFloat($("#loader").val());
    if(isNaN(loader)){
        loader=0;
    }
    var transportation = parseFloat($("#transportation").val());
    if(isNaN(transportation)){
        transportation=0;
    }
    var vatValue = $("#vatAmount").val();
    if(isNaN(vatValue)){
        vatValue=0;
    }
    var grandTotal = parseFloat($("#grandTotal").val());
    if(isNaN(grandTotal)){
        grandTotal=0;
    }
    var vatForwardAmount = parseFloat((vatValue / 100) * grandTotal);
    if(isNaN(vatForwardAmount)){
        vatForwardAmount=0;
    }
    $(".totalVatAmount").html(parseFloat(Math.round(vatForwardAmount)).toFixed(2));
    $("#netAmount").val(parseFloat(Math.round(vatForwardAmount) + grandTotal + loader + transportation).toFixed(2));
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
var findTotalQty2 = function () {
    var total_quantity2 = 0;
    $.each($('.add_quantity2'), function () {
        quantity = $(this).val();
        quantity = Number(quantity);
        total_quantity2 += quantity;
    });
    $('.total_quantity2').html(parseFloat(total_quantity2));
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
    $('.total_price').val(parseFloat(total_price).toFixed(2));
    $('#currentDue').val(parseFloat(total_price).toFixed(2));

};

var calculateCustomerDueDeduct = function () {
    var customerid=parseFloat($('#customerid').val());
    var netAmount=parseFloat($('#netAmount').val());
    var payment = parseFloat($("#payment").val());
    if(isNaN(payment)){
        payment=0;
    }
    if(isNaN(netAmount)){
        netAmount=0;
    }
    var url = baseUrl + "SalesController/getCustomerCurrentBalance";
    $.ajax({
        type: 'POST',
        url: url,
        data:{
            customerId: customerid
        },
        success: function (data)
        {
            data=parseFloat(data);
            if(isNaN(data)){
                data=0;
            }
            $('.currentBalance').val((netAmount+data) - payment);
        }
    });
};
var findTotalCal2 = function () {
    findTotalQty2();

};
var findTotalCal = function () {
    findTotalQty();
    findTotalReturnQty();
    findTotalRate();
    findTotalPrice();
    calDiscount();
    calculatePartialPayment();
};
function checkStockOverQty(givenStock){

    var orgiStock=parseFloat($("#stockQty").val());
    var givenStock= parseFloat(givenStock);
    if(isNaN(givenStock)){
        givenStock=0;
    }
    if(isNaN(orgiStock)){
        orgiStock=0;
    }
    //  alert(orgiStock);
    if(orgiStock < givenStock){
        $(".quantity").val('');
        $(".quantity").val(parseFloat(orgiStock));
        swal("Stock Quantity Not Available!", "Validation Error!", "error");
        //productItemValidation("Stock Quantity Not Available.");
    }

}



function checkStockOverQty2(productID){
    var givenStock=parseFloat($('#qty_'+productID).val());
    var Pid=parseInt(productID);
    var mainStock=0;

    $.ajax({
        type: "POST",
        url: baseUrl + "lpg/InvProductController/getProductStock",
        data: 'product_id=' + Pid,
        success: function (data) {
            if (data != '') {
                mainStock = parseFloat(data);
            }
        }, complete: function () {
            if(mainStock < givenStock){
                $('#qty_'+Pid).val(mainStock);

                swal("Stock Quantity Not Available!", "Validation Error!", "error");
                //productItemValidation("Stock Quantity Not Available.");
            }
        }
    })



}

$(document).on("keyup", ".add_quantity", function () {
    var id_arr = $(this).attr('id');
    var id = id_arr.split("_");
    var element_id = id[id.length - 1];
    var quantity = parseFloat($("#qty_" + element_id).val());
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
    calculateCustomerDue();
    findTotalCal();

});
$(document).on("keyup", ".add_rate", function () {
    var id_arr = $(this).attr('id');
    var id = id_arr.split("_");
    var element_id = id[id.length - 1];
    var quantity = parseFloat($("#qty_" + element_id).val());
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
    calculateCustomerDue();
    findTotalCal();

});

$(document).ready(function () {
    var j = 0;
    var slNo = 1;
    $("#add_item22").click(function () {
        var productCatID = $('#productID').find('option:selected').attr('categoryId');
        var productCatName = $('#productID').find('option:selected').attr('categoryName');
        var productID = $('#productID').val();
        var productName = $('#productID').find('option:selected').attr('productName');

        var ispackage = $('#productID').find('option:selected').attr('ispackage');
        var package_id2 = $('#productID2').val();
        var productCatName2 = $('#productID2').find('option:selected').attr('categoryName2');
        var productName2 = $('#productID2').find('option:selected').attr('productName2');
        var brand_id = $('#productID').find('option:selected').attr('brand_id');


        var quantity = $('.quantity').val();
        var rate = $('.rate').val();
        var price = $('.price').val();
        var returnQuantity = $('.returnQuantity').val();

        var returnQuantity2 = $('.returnQuantity2').val();
        if(ispackage==0){


        if(productCatID  == ''){
            swal("Product Category can't be empty!", "Validation Error!", "error");
            return false;
        }else if(productID == ''){
            swal("Product Name can't be empty!", "Validation Error!", "error");
            return false;
        }else if(quantity == ''){
            swal("Quantity Can't be empty!", "Validation Error!", "error");
            return false;
        }else if(rate == ''){
            swal("Unit Price Can't be empty!", "Validation Error!", "error");
            return false;
        }else{
            var tab;
            if($('.is_same').val()==0){
                slNo++;
            }else{
                slNo;
            }


            if(productCatID == 2){
                console.log('OOO');
                tab ='<tr class="new_item' + j + '">' +
                    '<input type="hidden" name="slNo['+slNo+']" value="'+slNo+'"/>' +
                    '<input type="hidden" name="brand_id[]" value="'+brand_id+'"/>' +
                    '<input type="hidden" name="is_package_'+ slNo +'" value="0">' +
                    '<input type="hidden" name="category_id[]" value="'  + productCatID + '">' +
                    '<td style="padding-left:15px;"> [ ' + productCatName + '] - ' + productName +
                    '<input type="hidden"  name="product_id_'+ slNo +'" value="' + productID + '">' +
                    '</td>' +
                    '<td align="right">' +
                    '<input type="text" id="qty_'+ j +'" class="form-control text-right add_quantity decimal" onkeyup="checkStockOverQty(this.value)" name="quantity_'+ slNo +'" value="' + quantity + '">' +
                    '</td>' +
                    '<td align="right"><input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[]" value="' + returnQuantity + '">' +
                    '</td>' +
                    '<td align="right"><input type="text" id="rate_'+ j +'" class="form-control add_rate text-right decimal" name="rate_'+ slNo +'" value="' + rate + '">' +
                    '</td>' +
                    '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_'+ j +'" name="price[]" value="' + price + '">' +
                    '</td>' +
                    '<td align="right">' + productCatName2 +'&nbsp;'+productName2+
                    '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_'+slNo+'[]" value="' + package_id2 + '">' +
                    '</td>' +
                    '<td align="right">' +
                    '<input type="text" class="text-right form-control" id="" readonly name="returnQuantity2_'+slNo+'[]" value="'+returnQuantity2+'">' +
                    '</td>' +
                    '<td align="right">' +
                    '<a del_id="'+j+'" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a>' +
                    '</td>' +
                    '</tr>';
                $("#show_item tfoot").append(tab);
            }else{
                tab='<tr class="new_item' + j + '">' +
                    '<input type="hidden" name="slNo['+slNo+']" value="'+slNo+'"/>' +
                    '<input type="hidden" name="is_package_'+ slNo +'" value="0">' +
                    '<input type="hidden" name="category_id_'+ slNo +'" value="' + productCatID + '">' +
                    '<td style="padding-left:15px;"> [ ' + productCatName + '] - ' + productName +
                    '<input type="hidden"  name="product_id[]" value="' + productID + '">' +
                    '</td>' +
                    '<td align="right">' +
                    '<input type="text" id="qty_'+ j +'" class="form-control text-right add_quantity decimal" onkeyup="checkStockOverQty(this.value)" name="quantity[]" value="' + quantity + '">' +
                    '</td>' +
                    '<td align="right">' +
                    '<input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[]" value="' + returnQuantity + '" readonly>' +
                    '</td>' +
                    '<td align="right">' +
                    '<input type="text" id="rate_'+ j +'" class="form-control add_rate text-right decimal" name="rate[]" value="' + rate + '">' +
                    '</td>' +
                    '<td align="right">' +
                    '<input readonly type="text" class="add_price text-right form-control" id="tprice_'+ j +'" name="price[]" value="' + price + '">' +
                    '</td>' +
                    '<td align="right">' + productCatName2 +'&nbsp;'+productName2+
                    '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_'+slNo+'[]" value="' + package_id2 + '">' +
                    '</td>' +
                    '<td align="right">' +
                    '<input type="text" class="text-right form-control" id="" readonly name="returnQuantity2_'+slNo+'[]" value="'+returnQuantity2+'">' +
                    '</td>' +
                    '<td><a del_id="'+j+'" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>';
                $("#show_item tfoot").append(tab);
            }
        }
        }else{
            var quantity = $('.quantity').val();
            var returnAble = $('.returnAble').val();
            var rate = $('.rate').val();
            var price = $('.price').val();
            $.ajax({
                type: "POST",
                dataType:'json',
                url:baseUrl + "lpg/PurchaseController/package_product_list",
                data: 'package_id=' + productID,
                success: function (data) {
                    console.log(data);
                    $.each(data, function (key, value) {
                        slNo++;
                        $("#show_item tfoot").append('<tr class="new_item' + j + '"><input type="hidden" name="slNo['+slNo+']" value="'+slNo+'"/><input type="hidden" name="is_package_'+ slNo +'" value="1"><input type="hidden" name="category_id_'+ slNo +'" value="' + value['category_id'] + '">' +
                            '<td style="padding-left:15px;"> [ ' + value['title'] + '] - ' + value['productName'] +'&nbsp;'+ value['unitTtile'] +'&nbsp;[ '+ value['brandName']+" ]"+
                            ' <input type="hidden"  name="product_id_'+ slNo +'" value="' + value['product_id'] + '"></td>' +
                            '</td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" id="qty_'+ j +'" name="quantity_'+ slNo +'" value="' + quantity + '"></td><td align="right"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_'+ j +'"   name="add_returnAble[]" value=""  readonly></td><td align="right"><input type="text" id="rate_'+ j +'" class="add_rate form-control decimal text-right" name="rate_'+ slNo +'" value="' + rate + '"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tprice_'+ j +'" readonly name="price[]" value="' + price + '"></td><td></td><td></td><td><a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
                        j++;

                    });
                    console.log(slNo);
                }
            });


            $('.quantity').val('');
            $('.rate').val('');
            $('.price').val('');
            $('.returnAble').val('');



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
        setTimeout(function() {
            ///calculateCustomerDue();
            calcutateFinal();
        }, 100);
        j++;
    });
    $(document).on('click','.delete_item', function () {
        var id = $(this).attr("del_id");
        var package_id_delete = $(this).attr("package_id_delete");
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
                if( typeof package_id_delete!== 'undefined'){
                    $('.packageDeleteRow_'+package_id_delete).remove();
                }else{
                    $('.new_item' + id).remove();
                }

                findTotalCal();
                setTimeout(function() {
                    calcutateFinal();
                }, 100);
            }else{
                return false;
            }
        });
    });
});

function getProductPrice2(product_id) {
    $("#stockQty2").val('');
    $(".quantity2").val('');
    var total_quantity2 = 0;
    $.each($('.add_quantity2'), function () {
        quantity = $(this).val();
        quantity = Number(quantity);
        total_quantity2 += quantity;
    });
    if(isNaN(total_quantity2)){
        total_quantity2=0;
    }

    $.ajax({
        type: "POST",
        url: baseUrl+ "FinaneController/getProductPriceForSale",
        data: 'product_id=' + product_id,
        success: function (data) {
            if (data != '0.00') {
                $('.rate2').val(data);
            } else {
                $('.rate2').val('');
            }

        }
    });
    $.ajax({
        type: "POST",
        url: baseUrl+ "FinaneController/getProductStock",
        data: 'product_id=' + product_id,
        success: function (data) {
            var mainStock = parseFloat(data) - parseFloat(total_quantity);
            if(data !=''){
                $("#stockQty2").val(data);
                $(".quantity2").attr("disabled",false);
                if(mainStock <= 0){
                    $(".quantity2").attr("disabled",true);
                    $(".quantity2").attr("placeholder", "0 ");
                }else{
                    $(".quantity2").attr("disabled",false);
                    $(".quantity2").attr("placeholder", "= "+mainStock);
                }
            }else{
                $("#stockQty2").val('');
                $(".quantity2").attr("disabled",true);
                $(".quantity2").attr("placeholder", " 0");
            }

        }
    });


}

function getProductList2(cat_id) {
    $.ajax({
        type: "POST",
        url: baseUrl+ "InventoryController/getProductList",
        data: 'cat_id=' + cat_id,
        success: function (data) {
            $('#productID2').chosen();
            $('#productID2 option').remove();
            $('#productID2').append($(data));
            $("#productID2").trigger("chosen:updated");
        }
    });
}