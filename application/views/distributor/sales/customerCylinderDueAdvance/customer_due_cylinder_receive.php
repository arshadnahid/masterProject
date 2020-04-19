<div class="col-sm-12">
    <div class="portlet box blue">
        <div class="portlet-title" style="min-height:21px">
            <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                <?php echo get_phrase($title) ?></div>

        </div>
        <div class="portlet-body">
            <form id="cylinder_due_recive" action="" method="post" class="form-horizontal">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <span
                                        style="color:red;">*</span><strong><?php echo get_phrase('Customer') ?></strong></label>
                            <div class="col-sm-7">
                                <select name="customerid" class="chosen-select form-control customerId" id="customerId"
                                        data-placeholder="Search by Customer">

                                    <option value=""></option>

                                    <?php foreach ($customerList as $eachInfo): ?>

                                        <option <?php
                                        if (!empty($customerid) && $customerid == $eachInfo->customer_id) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $eachInfo->customer_id; ?>"><?php echo $eachInfo->customerName; ?>
                                            [<?php echo $eachInfo->customerID; ?>]
                                        </option>

                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label formfonterp" for="form-field-1"
                                   style="white-space: nowrap;"><span
                                        style="color:red;"> * </span><strong> <?php echo get_phrase('Payment Date') ?></strong></label>
                            <div class="col-sm-7">
                                <input type="text" class="date-picker form-control" id="start_date" name="paymentDate"
                                       value="<?php
                                       if (!empty($from_date)) {
                                           echo $from_date;
                                       } else {
                                           echo date('d-m-Y');
                                       }
                                       ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-right formfonterp"
                                   for="form-field-1"><strong><?php echo get_phrase('Branch') ?></strong></label>
                            <div class="col-sm-7">
                                <select name="branch_id" class="chosen-select form-control"
                                        id="BranchAutoId" data-placeholder="Select Branch">

                                    <?php
                                    // come from branch_dropdown_helper
                                    echo branch_dropdown(null, null);
                                    ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-right formfonterp" for="form-field-1"><span
                                        style="color:red;"> * </span><strong><?php echo get_phrase('Receipt Id') ?></strong></label>
                            <div class="col-sm-7">
                                <input readonly type="text" class="form-control" name="receiptId"
                                       value="<?php echo $moneyReceitVoucher; ?>" placeholder=""/>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">

                    <div class="col-sm-12">

                        <div id="voucherheader" class="table-header" style="display: none;">

                            <?php echo get_phrase('Due Voucher List') ?>

                        </div>

                        <span id="customer_result"></span>

                    </div>

                    <div class="col-sm-10 col-md-offset-1" id="narration" style="display: none;">

                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right"
                                   for="form-field-1"> <?php echo get_phrase('Narration') ?></label>

                            <div class="col-sm-9">

                                <div class="input-group">

                                        <textarea cols="100" rows="2" name="narration" placeholder="Narration"
                                                  type="text"></textarea>


                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-sm-10 col-md-offset-1">

                        <div class="clearfix form-actions" id="submitBtn" style="display: none;">

                            <div class="col-md-offset-3 col-md-9">

                                <button onclick="return confirmWithValidat()" id="subBtn" class="btn btn-info"
                                        type="button">

                                    <i class="fa fa-check bigger-110"></i>

                                    <?php echo get_phrase('Save') ?>

                                </button>

                                &nbsp; &nbsp; &nbsp;

                                <button class="btn" type="reset">

                                    <i class="ace-icon fa fa-undo bigger-110"></i>

                                    Reset

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $('.received_cylilder_id').trigger("chosen:updated");

        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });
        $('.customerId').change(function () {
            get_invoice_list();
        });
        $('#BranchAutoId').change(function () {
            get_invoice_list();
        });

        function get_invoice_list() {

            var customer = $('.customerId').val();
            var BranchAutoId = $('#BranchAutoId').val();
            $('#paid_amount').val('');
            if (BranchAutoId == '' || BranchAutoId == 0 || BranchAutoId == null) {

            } else if (customer == '' || customer == 0 || customer == null) {


            } else {

                $("#paid_amount").val('');
                $.ajax({
                    type: 'POST',
                    data: {customer: customer, BranchAutoId: BranchAutoId},
                    url: '<?php echo site_url("lpg/CylinderDueAdvanceController/customer_due_cylinder_ajax"); ?>',
                    success: function (result) {

                        $('#customer_result').html(result);

                        $('#voucherheader').show(1000);

                        $('#narration').show(1000);

                        $('#submitBtn').show(1000);

                    }

                });
            }


        }

    });


    function add_cylinder(id) {
        var productID = $('#productID2_' + id).val();
        var ReturnedQty = $('#ReturnedQty_' + id).val();
        var returnedProductPrice = $('#returnedProductPrice_' + id).val();
        var sales_invoice_id = $('#invoice_id_' + id).val();
        var categoryname2 = $('#productID2_' + id).find('option:selected').attr('categoryname2');
        var productname2 = $('#productID2_' + id).find('option:selected').attr('productname2');


        if (productID == '') {
            swal("Product Name can't be empty!", "Validation Error!", "error");
            $("#productID").trigger("chosen:open");
            return false;
        } else if (ReturnedQty < 0 || ReturnedQty == "") {
            swal("Product Quentity can't be empty!", "Validation Error!", "error");
            $("#productID").trigger("chosen:open");
            return false;
        } else if (returnedProductPrice < 0 || returnedProductPrice == "") {
            swal("Product Price can't be empty!", "Validation Error!", "error");
            $("#productID").trigger("chosen:open");
            return false;
        }

        var tab;
        tab = '<tr class="new_item' + id + '">' +
            '<td style="padding-left:15px;text-align: right" colspan="5" >' + categoryname2 + ' ' + productname2 +
            '</td>' +
            '<td style="padding-left:15px;text-align: right" >' + ReturnedQty +
            '<input type="hidden" name="sales_invoice_id[]" value="' + sales_invoice_id + '" />' +
            '<input type="hidden" name="sales_details_id_main[]" value="' + id + '" />' +
            '<input type="hidden" name="sales_details_id[]" value="' + id + '" />' +
            '<input type="hidden" name="returned_cylinder_id[]" value="' + productID + '" />' +
            '<input type="hidden" class="ReturnedQtyClass" name="ReturnedQty[]" value="' + ReturnedQty + '" />' +
            '<input type="hidden" name="returnedProductPrice[]" value="' + returnedProductPrice + '" />' +
            '</td>' +
            '<td style="padding-left:15px;text-align: right" >' + returnedProductPrice +
            '</td>' +
            '<td style="padding-left:15px;text-align: right" >' +
            '</td>' +
            '<td style="padding-left:15px;text-align: right" >' +
            '</td>' +
            '</tr>';
        $('#trID_' + id).after(tab);


        $('#productID2_' + id).val('').trigger('chosen:updated');
        $('#ReturnedQty_' + id).val('');
        $('#returnedProductPrice_' + id).val('');
        $('#total_price_' + id).val('');
        $('#returnedProductPrice_' + id).attr("placeholder", "0.00");

    }


    function received_cylilder_price(id) {
        alert(id);
        var product_id = $('#productID2_' + id).val();
        var productCatID = 1;
        var branchId = $('#BranchAutoId').val();
        if (branchId == '' || branchId == null) {
            swal("Select Branch", "Validation Error!", "error");

            return false;
        }
        var ispackage = 0;
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductPriceForPurchase',
            data: 'product_id=' + product_id,
            success: function (data) {
                if (id == 0) {
                    $('.received_cylilder_price').val(parseFloat(data));
                    $('.received_cylilder_price').attr("placeholder", parseFloat(data));
                } else {
                    $('#returnedProductPrice_' + id).val(parseFloat(data));
                    $('#returnedProductPrice_' + id).attr("placeholder", parseFloat(data));
                }
            }
        });
    }


    function confirmWithValidat() {

        var customerid = $("#customerId").val();
        var paymentDate = $("#start_date").val();
        var ReturnedQty = 0;
        $.each($('.ReturnedQtyClass'), function (i, elem) {
            if ($(this).val() != '') {
                ReturnedQty = ReturnedQty + parseFloat($(this).val());
            }
        });

        if (customerid == '') {
            swal("Customer Can't be empty !", 'Validatin Error', 'error');
        } else if (paymentDate == '') {
            swal("Payment Date Cnn't be empty !", 'Validatin Error', 'error');
        }
        /*else if (ReturnedQty == '' || ReturnedQty < 0) {
            swal("Add  Returned Cylinder Qty !", 'Validatin Error', 'error');
        }*/
        else {
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
                        $("#cylinder_due_recive").submit();
                    } else {
                        return false;
                    }
                });
        }
    }
</script>