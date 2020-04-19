$(document).on('click', '.remove_returnable', function () {

    $(this).closest("tr").remove();
});

function getSupplierClosingBalance() {
    calculateSupplierDue();
    setTimeout(function () {
        calcutateFinal();
    }, 100);


}

/*setTimeout(function () {
    $('.partialPayment').fadeOut();
    $('#culinderReceive').fadeOut();
}, 2000);*/

function getProductPrice2(product_id) {
    $("#stockQty2").val('');
    $(".quantity2").val('');

    var total_quantity2 = 0;
    $.each($('.add_quantity2'), function () {
        quantity = $(this).val();
        quantity = Number(quantity);
        total_quantity2 += quantity;
    });
    if (isNaN(total_quantity2)) {
        total_quantity2 = 0;
    }
    $.ajax({
        type: "POST",
        url: baseUrl + "lpg/InvProductController/getProductPriceForSale",
        data: 'product_id=' + product_id,
        success: function (data) {
            $('.rate2').val(data);
        }
    });
    $.ajax({
        type: "POST",
        url: baseUrl + "lpg/InvProductController/getProductStock",
        data: 'product_id=' + product_id,
        success: function (data) {
            var mainStock = parseFloat(data) - parseFloat(total_quantity);
            if (data != '') {
                $("#stockQty2").val(data);
                $(".quantity2").attr("disabled", false);
                if (mainStock <= 0) {
                    $(".quantity2").attr("disabled", true);
                    $(".quantity2").attr("placeholder", "Stock is =0 ");
                } else {
                    $(".quantity2").attr("disabled", false);
                    $(".quantity2").attr("placeholder", "Stock is = " + mainStock);
                }
            } else {
                $("#stockQty2").val('');
                $(".quantity2").attr("disabled", true);
                $(".quantity2").attr("placeholder", "Stock is = 0");
            }
        }
    });
}


function calcutateFinal() {

    var total_price = parseFloat($(".total_price").val());
    //alert(total_price);
    if (isNaN(total_price)) {
        total_price = 0;
    }

    var discount = parseFloat($("#discount").val());
    if (isNaN(discount)) {
        discount = 0;
    }
    var loader = parseFloat($("#loader").val());
    if (isNaN(loader)) {
        loader = 0;
    }
    var transportation = parseFloat($("#transportation").val());
    if (isNaN(transportation)) {
        transportation = 0;
    }

    var total_price = (total_price + transportation + loader) - discount;
    $("#netAmount").val(parseFloat(total_price).toFixed(2));
    var payment = parseFloat($("#thisAllotment").val());//payment
    if (isNaN(payment)) {
        payment = 0;
    }
    var previousDue = parseFloat($("#customerPreviousDue").val());


    if (isNaN(previousDue)) {
        previousDue = 0;
    }
    if (payment > total_price) {
        $("#currentDue").val(parseFloat(0.00).toFixed(2));
    } else {
        $("#currentDue").val(parseFloat(total_price - payment).toFixed(2));
    }
    $("#totalDue").val(parseFloat((total_price + previousDue) - payment).toFixed(2));
}


function getProductList2(cat_id) {
    $.ajax({
        type: "POST",
        url: baseUrl + "InventoryController/getProductList",
        data: 'cat_id=' + cat_id,
        success: function (data) {
            $('#productID2').chosen();
            $('#productID2 option').remove();
            $('#productID2').append($(data));
            $("#productID2").trigger("chosen:updated");
        }
    });
}

/*function showCylinder() {
    //$("#culinderReceive").toggle();
}*/
var findTotalQty2 = function () {
    var total_quantity = 0;
    $.each($('.add_quantity2'), function () {
        quantity = $(this).val();
        quantity = Number(quantity);
        total_quantity += quantity;
    });
    $('.total_return_quantity').html(parseFloat(total_quantity));
};
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
    setTimeout(function () {
        calcutateFinal();
    }, 100);
});
$(document).on("keyup", ".add_return", function () {
    var id_arr = $(this).attr('id');
    var id = id_arr.split("_");
    var element_id = id[id.length - 1];
    var quantity = parseFloat($("#qty_" + element_id).val());
    var returnQty = parseFloat($("#qtyReturn_" + element_id).val());

    if (isNaN(quantity)) {
        quantity = 0;
    }

    if (isNaN(returnQty)) {
        returnQty = 0;
    }

    if (returnQty == '') {
        swal("Please first given qty!", "Validation Error!", "error");
    } else if (returnQty != returnQty) {
        swal("Returanable must equal Quantity!", "Validation Error!", "error");

        $(this).val('');
    }


});
$(document).on("keyup", ".returnAble", function () {
    var id_arr = $(this).attr('id');
    //  var element_id=id = id_arr.split("_");
    var element_id = id = id_arr;
    //var element_id = id[id.length - 1];
    var quantity = parseFloat($("#qty_" + element_id).val());
    var returnQty = parseFloat($("#qtyReturn_" + element_id).val());

    if (isNaN(quantity)) {
        quantity = 0;
    }

    if (isNaN(returnQty)) {
        returnQty = 0;
    }

    //if(returnQty == ''){
    //    swal("Please first given qty!", "Validation Error!", "error");
    //}else if(returnQty != returnQty){
    //    swal("Returanable must equal Quantity!", "Validation Error!", "error");

    //    $(this).val('');
    //}


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
    setTimeout(function () {
        calcutateFinal();
    }, 100);


});


$(document).ready(function () {
    var j = 0;
    $("#add_item2").click(function () {
        var productCatID = 1;
        var productID = $('#productID2').val();
        var productName = $('#productID2').find('option:selected').attr('productName2');
        var brand_id = $('#productID2').find('option:selected').attr('brand_id');
        var quantity = $('.quantity2').val();
        var rate = $('.rate2').val();
        var price = $('.price2').val();
        var returnQuantity = $('.returnQuantity2').val();
        if (productCatID == '') {
            swal("Product Category can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (productID == '') {
            swal("Product Name can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (quantity == '') {
            swal("Quantity Can't be empty!", "Validation Error!", "error");
            return false;
        } else if (rate == '') {
            swal("Unit Price Can't be empty!", "Validation Error!", "error");
            return false;
        } else {
            $("#show_item2 tbody").append('<tr class="new_item2' + productCatID + productID + '"><input type="hidden" name="brand_id2[]" value="' + brand_id + '" <input type="hidden" name="category_id2[]" value="' + productCatID + '"><td style="padding-left:15px;">' + productName + '<input type="hidden"  name="product_id2[]" value="' + productID + '"></td><td align="right"><input type="text" class="add_quantity2 text-right form-control  decimal " name="quantity2[]" value="' + quantity + '"></td><td><a del_id2="' + productCatID + productID + '" class="delete_item2 btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
        }
        $("#subBtn").attr('disabled', false);
        $('.quantity2').val('');
        $('.rate2').val('');
        $('.price2').val('');
        $('.returnQuantity2').val('');
        $(".quantity2").attr("placeholder", "0");
        $('#productID2').val('').trigger('chosen:updated');
        $('#category_product2').val('').trigger('chosen:updated');
        findTotalQty2();
        calcutateFinal();
    });
    $(document).on('click', '.delete_item2', function () {
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
                    findTotalQty2();
                } else {
                    return false;
                }
            });
    });
});
$('#accountBalance').hide();
$(document).ready(function () {
    showBankinfo($('#paymentType').val());
    $('.checkAccountBalance').change(function () {
        var accountId = $(this).val();
        $.ajax({
            type: 'POST',
            data: {
                account: accountId
            },
            url: baseUrl + "FinaneController/checkOnlyBalanceForPayment",
            success: function (result) {
                $('#accountBalance').show();
                $('#accountBalance').val('');
                $('#accountBalance').val(result);
            }
        });
    });
});

function showBankinfo(id) {
    $('#accountBalance').hide();

    setTimeout(function () {
        calcutateFinal();
    }, 100);








    if (id == 4) {
        $('#dueDateDiv').hide();
        $('#dueDateDiv').hide();
        $("#hideAccount").hide();
        $("#showAccount").show();
        $("#paymentDiv").show();
        $("#showBankInfo").hide();
        $(".partialPayment").show();
        //$("#partialHead").css({"width":"30%!important"});
        $(".checkAccountBalance").chosen();
        $("#partialHead2_chosen").css({
            width: "100%!important"
        });
        $('#partialHead').val('').trigger('chosen:updated');
    } else {
        $('.payment').val('');
        $("#hideAccount").hide();

        setTimeout(function () {
            $("#showAccount").hide();
        }, 1000);
        $("#showBankInfo").hide();
        $(".partialPayment").hide();
    }
    if (id == 3) {
        $("#paymentDiv").hide();
        $("#hideAccount").hide();
        $("#dueDateDiv").hide();
        $("#showBankInfo").show();
    } else {
        $("#hideAccount").hide();

        $("#showBankInfo").hide();
        // $(".partialPayment").hide(1000);
    }
    if (id == 1) {
        $("#hideAccount").show();

    } else if (id == 2) {
        $("#paymentDiv").hide();
        setTimeout(function () {
            $("#showAccount").hide();
        }, 1000);
        $('#dueDateDiv').show();
        $("#hideAccount").hide();
        //$("#showAccount").show();
    }
}

function saveNewSupplier() {
    var supplierId = $(".supplierId").val();
    var supName = $(".supName").val();
    if (supplierId != '' && supName != '') {
        $.ajax({
            type: 'POST',
            url: baseUrl + "SetupController/saveNewSupplier",
            data: $("#publicForm2").serializeArray(),
            success: function (data) {
                $('#myModal').modal('toggle');
                $('#hideNewSup').hide();
                $('#supplierid').chosen();
                $('#supplierid').append($(data));
                $("#supplierid").trigger("chosen:updated");
            }
        });
    } else {
        alert("Supplier ID AND supplier name can't be empty.");
        return false;
        //$('#myModal').modal('hide');
    }
}

var calculateSupplierDue = function () {
    var supplierid = parseFloat($('#supplierid').val());


    $.ajax({
        type: 'POST',
        url: baseUrl + "InventoryController/getSupplierClosingBalance",
        data: {
            supplierid: supplierid
        },
        success: function (data) {
            data = parseFloat(data);
            if (isNaN(data)) {
                data = 0;
            }

            $('#customerPreviousDue').val(parseFloat(data).toFixed(2));
        }
    });
};

function checkDuplicatePhone(phone) {
    $.ajax({
        type: 'POST',
        url: baseUrl + "SetupController/checkDuplicateEmail",
        data: {
            'phone': phone
        },
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

/*purchase query start*/
/*purchase query start*/
$(document).ready(function () {


    $('.rate').blur(function () {
        var rate = parseFloat($(this).val());
        if (isNaN(rate)) {
            rate = 0;
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

};
var returnQty = function () {
    var returnqty = 0;
    $.each($('.add_return'), function () {
        returnq = $(this).val();
        returnq = Number(returnq);
        returnqty += returnq;
    });
    $('.totalReturnQty').html(parseFloat(returnqty).toFixed(2));
};
var findTotalCal = function () {
    findTotalQty();
    findTotalRate();
    findTotalPrice();
    returnQty();

};
$(document).ready(function () {
    var j = 0;
    var slNo = 101000;
    findTotalCal();
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
        } else if (ispackage == 0) {
            //var productCatID = $('#productID').find('option:selected').attr('categoryId');
            // var productCatName = $('#productID').find('option:selected').attr('categoryName');
            //var productName = $('#productID').find('option:selected').attr('productName');
            var brand_id = $('#productID').find('option:selected').attr('brand_id');
            //var quantity = $('.quantity').val();
            //var returnAble = $('.returnAble').val();
            //var rate = $('.rate').val();
            //var price = $('.price').val();


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
                if ($('.is_same').val() == 0) {
                    slNo++;
                } else {
                    slNo;
                }
                if (productCatID == 2) {
                    if ($('.is_same').val() == 0) {


                        var returnedCylender = '';
                        if (returnQuentity > 0) {
                            returnedCylender = '<tr>' +
                                '<td>' +
                                '<input type="hidden" class="text-right form-control" id="" readonly name="Addreturnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                                productName2 +
                                '</td>' +
                                '<td>' +
                                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="AddreturnQuentity_' + slNo + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +
                                '</td>' +
                                '</tr>' +
                                '</tr>';
                        }

                        tab = '<tr class="new_item' + slNo + '">' +

                            '<input type="hidden" name="Addbrand_id[]" value="' + brand_id + '"/>' +
                            '<input type="hidden" name="Addis_package_' + slNo + '" value="0">' +
                            '<input type="hidden" name="Addcategory_id_' + slNo + '" value="' + productCatID + '">' +
                            '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName + ' ' +
                            '<input type="text" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                            '<input type="hidden"  name="Addproduct_id_' + slNo + '" value="' + productID + '">' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" class="add_quantity decimal form-control text-right" id="qty_' + slNo + '" name="Addquantity_' + slNo + '" value="' + quantity + '">' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + slNo + '"   name="add_returnAble[' + slNo + ']" value="' + returnAble + '"  >' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" id="rate_' + slNo + '" class="add_rate form-control decimal text-right" name="Addrate_' + slNo + '" value="' + rate + '">' +
                            '</td>' +
                            '<td align="right" style="display: none">' +
                            '<input type="text" class="add_price  text-right form-control" id="tprice_' + slNo + '" readonly name="Addprice[]" value="' + price + '">' +
                            '</td>' +
                            '<td colspan="2">' +
                            '<table class="table table-bordered table-hover" style="margin-bottom: 0px;" id="return_product_' + slNo + '">' +
                            '<tr>' +
                            '<td>' +
                            '<select   class=" form-control returnedProduct_' + slNo + '" data-placeholder="Search by product name">' +
                            option +
                            '</select>' +
                            '</td>' +
                            '<td style="width: 40%">' +
                            '<div class="input-group"><input type="text" class="form-control returnedProductQty_' + slNo + '" style="width: 80px;" /><a href="javascript:void(0)" id="' + slNo + '" class="AddreturnedProduct   input-group-addon"><i class="fa fa-plus"></i> </a> </div>' +
                            '</td>' +

                            '</tr>' + returnedCylender
                            +
                            '</table>' +
                            '</td>' +
                            '<td>' +
                            '<a del_id="' + slNo + '" class="delete_item btn form-control btn-danger" href="javascript:void(0);" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
                            '</td>' +
                            '</tr>';
                        $("#show_item tfoot").append(tab);
                    } else {
                        slNo;
                        var tab2 = "<tr>" +
                            "<td>" +
                            '<input type="hidden" class="text-right form-control" id="" readonly name="Addreturnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                            productName2 +
                            "</td>" +
                            "<td>" +
                            '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="AddreturnQuentity_' + slNo + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +

                            "</td>" +
                            "</tr>";

                        $("#return_product_" + slNo).append(tab2);

                    }
                } else {

                    tab = '<tr class="new_item' + slNo + '">' +
                        '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                        '<input type="hidden" name="Addis_package_' + slNo + '" value="0">' +
                        '<input type="hidden" name="Addcategory_id_' + slNo + '" value="' + productCatID + '">' +
                        '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName + ' <input type="hidden"  name="Addproduct_id_' + slNo + '" value="' + productID + '">' +
                        '</td>' +
                        '</td>' +
                        '<td align="right">' +
                        '<input type="text" class="add_quantity decimal form-control text-right" id="qty_' + slNo + '" name="Addquantity_' + slNo + '" value="' + quantity + '">' +
                        '</td>' +
                        '<td align="right" style="display: none"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + slNo + '"   name="add_returnAble[' + slNo + ']" value=""  readonly>' +
                        '</td>' +
                        '<td align="right">' +
                        '<input type="text" id="rate_' + slNo + '" class="add_rate form-control decimal text-right" name="Addrate_' + slNo + '" value="' + rate + '">' +
                        '</td>' +
                        '<td align="right" ><input type="text" class="add_price  text-right form-control" id="tprice_' + slNo + '" readonly name="Addprice[]" value="' + price + '">' +
                        '</td>' +
                        '<td align="right" style="display: none">' +
                        //'<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_'+slNo+'[]" value="' + package_id2 + '">' +
                        '</td>' +
                        '<td align="right" style="display: none">' +
                        //'<input type="text" class="text-right form-control" id="" readonly name="returnQuentity_'+slNo+'[]" value="'+returnQuentity+'">' +
                        '</td>' +
                        '<td>' +
                        '<a del_id="' + slNo + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
                        '</td>' +
                        '</tr>';
                    $("#show_item tfoot").append(tab);
                }
                j++;
            }

            if (productCatID == 2) {
                $('.is_same').val('1');
            } else {
                $('#productID').val('').trigger('chosen:updated');
                $('#productCategoryId').val('').trigger('chosen:updated');
                $('.quantity').attr("placeholder","0");

                $('.quantity').val('');
                $('.is_same').val('0');
                $('.rate').val('');
                $('.price').val('');
                $('.returnAble').val('');
            }

            findTotalCal();
            setTimeout(function () {
                calcutateFinal();
            }, 100);

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

                    $.each(data, function (key, value) {
                        slNo++;
                        $("#show_item tfoot").append('<tr class="new_item' + slNo + '"><input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/><input type="hidden" name="Addis_package_' + slNo + '" value="1"><input type="hidden" name="Addcategory_id_' + slNo + '" value="' + value['category_id'] + '">' +
                            '<td style="padding-left:15px;"> [ ' + value['title'] + '] - ' + value['productName'] + '&nbsp;' + value['unitTtile'] + '&nbsp;[ ' + value['brandName'] + " ]" +
                            ' <input type="hidden"  name="Addproduct_id_' + slNo + '" value="' + value['product_id'] + '"></td>' +
                            '</td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" id="qty_' + slNo + '" name="Addquantity_' + slNo + '" value="' + quantity + '"></td><td align="right" style="display: none"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + slNo + '"   name="add_returnAble[]" value=""  readonly></td><td align="right"><input type="text" id="rate_' + slNo + '" class="add_rate form-control decimal text-right" name="Addrate_' + slNo + '" value="' + rate + '"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tprice_' + slNo + '" readonly name="Addprice[]" value="' + price + '"></td><td style="display: none"></td><td style="display: none"></td><td><a del_id="' + slNo + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');
                        j++;

                    });

                }, complete: function () {
                    findTotalCal();
                    setTimeout(function () {
                        calcutateFinal();
                    }, 100);
                }
            });


            $('.quantity').val('');
            $('.rate').val('');
            $('.price').val('');
            $('.returnAble').val('');


        }


        //$('#category_product').val('').trigger('chosen:updated');
        //$('#productUnit').val('').trigger('chosen:updated');

    });
    $(document).on('click', '.AddreturnedProduct', function () {
        //$(".AddreturnedProduct").LoadingOverlay("show");

        var id = $(this).attr("id");
        var productName2 = $('.returnedProduct_' + id).find('option:selected').attr('productName2');
        var package_id2 = $('.returnedProduct_' + id).val();
        var returnQuentity = $('.returnedProductQty_' + id).val();


        if (returnQuentity == '') {
            swal("Qty can't be empty.!", "Validation Error!", "error");
            return false;
        } else
            var tab2 = "<tr>" +
                "<td>" +
                '<input type="hidden" class="text-right form-control" id="" readonly name="Addreturnproduct_' + id + '[]" value="' + package_id2 + '">' +
                productName2 +
                "</td>" +
                "<td>" +
                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="AddreturnQuentity_' + id + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +

                "</td>" +
                "</tr>";
        $("#return_product_" + id).append(tab2);
        $('.returnedProduct_' + id).val('');
        $('.returnedProductQty_' + id).val('');

    })
    //AddreturnedProduct
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
                    setTimeout(function () {
                        calcutateFinal();
                    }, 100);
                } else {
                    return false;
                }
            });
    });
});

//get product purchases price
function getProductPrice(product_id) {
    $('.is_same').val('0');
    $('#productID2').removeAttr("disabled");
    $('.quantity').val('');
    $('.returnAble ').val('');
    $('.rate ').val('');
    $('.price').val('');
    $('.returnQuentity').val('');
    var productCatID = $('#productID').find('option:selected').attr('categoryId');
    if (productCatID == 2) {
        $(".returnAble").attr('readonly', false);
        //$("#productID2").trigger("chosen:updated");
        //$("#productID2").html("<option disabled selected></option>");
        $('#productID2').val('').prop('disabled', false).trigger("chosen:updated");
    } else {
        $('#productID2').val('').prop('disabled', true).trigger("chosen:updated");
        $(".returnAble").attr('readonly', true);
    }

    $.ajax({
        type: "POST",
        url: baseUrl + "lpg/InvProductController/getProductPriceForPurchase",
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



function checkDuplicateCategory(catName) {
    $.ajax({
        type: 'POST',
        url: baseUrl + "SetupController/checkDuplicateCategory",
        data: {
            'catName': catName
        },
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

/*purchase query start*/