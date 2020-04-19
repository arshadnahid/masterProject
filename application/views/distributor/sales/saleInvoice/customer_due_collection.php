<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 4/16/2019
 * Time: 9:46 AM
 */?>


<script src="//code.jquery.com/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jQueryUI/jquery-new-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/q_jquery.mCustomScrollbar.min.css">
<script src="<?php echo base_url(); ?>assets/js/q_jquery.mCustomScrollbar.concat.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>

<style type="text/css">
    .ui-autocomplete {
        max-height: 250px!important;
        max-width: 300px!important;
        overflow: auto!important;
        height: auto!important;
        margin-left: -38px!important;
    }
    .ui-autocomplete .ui-menu-item {
        font-size: 14px!important;
        background: #fff;
        border-bottom: 1px solid rgba(128, 128, 128, 0.20);
        border-top: none!important;
        border-left: none!important;
        border-right: none!important;
        height: 30px!important;
        line-height: 30px!important;
        color: gray;
        padding-bottom: 15px!important;
        margin: 0px!important;
        font-weight: normal!important;
    }
</style>

<?php

?>
<div class="main-content">
    <form id="publicForm" action=""  method="post" class="form-horizontal">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state  noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/2'); ?>">Sales</a>
                </li>
                <li class="active">Customer Due Collection</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo site_url('cus_due_coll_list'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>
        <br>
        <div class="page-content">
            <div class="row  noPrint">
                <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="table-header">
                                Customer Due Collection
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1"> Customer </label>
                                        <div class="col-md-8">
                                            <input type="hidden" name="customer_id" id="customer_id" />
                                            <input type="text" name="customer_id_autocomplete" id="customer_id_autocomplete"  autocomplete="off" class="form-control"/>
                                        </div>
                                    </div>
                                </div><div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1"> Due </label>
                                        <div class="col-md-8">
                                            <input type="text" name="" id="due_amount" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;Advance</label>
                                        <div class="col-md-8">
                                            <input type="text" name="advance_amount" id="advance_amount" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right" for="form-field-1"> Paid Amount </label>
                                    <div class="col-md-8">
                                        <input type="text" name="paid_amount" id="paid_amount" class="form-control" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control date-picker" id="start_date" name="date" value="<?php           echo date('Y-m-d');?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd" style="width:100%"/>
                                    </div>
                                </div>
                            </div>
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control "  name="cus_due_collection_no" value="<?php           echo $moneyReceitVoucher;?>"   />
                                    </div>
                                </div>
                            </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Payment Type  <span style="color:red;"> *</span></label>
                                        <div class="col-sm-6">
                                            <select onchange="showBankinfo(this.value)"  name="paymentType"  class="chosen-select form-control" id="paymentType" data-placeholder="Select Payment Type">
                                                <option></option>
                                                <!--<option value="1">Full Cash</option>-->
                                                <option selected value="4">Cash</option>

                                                <option value="3">Cheque / DD/ PO</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div id="showBankInfo" style="display:none;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-2">
                                                <input type="text" value="" name="bankName" id="bankName" class="form-control" placeholder="Bank Name"/>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" value="" name="branchName" id="branchName" class="form-control" placeholder="Branch Name"/>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" value="" class="form-control" id="checkNo" name="checkNo" placeholder="Check NO"/>
                                            </div>
                                            <div class="col-sm-2">
                                                <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-info btn-sm" id="adjust_payment"  style="cursor:pointer;">

                                            Add
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit"  class="btn btn-success btn-sm">
                                            Save
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div><!-- /.col -->
        <div class="page-content">
            <div class="row" id="due_invoice_list_div">
                <div class="col-md-12">
                    <table class="table table-bordered" id="due_invoice_list">
                        <thead>
                        <tr>

                            <th class="text-center">Sales Invoice No</th>
                            <th class="text-center">Due Amount</th>
                            <th class="text-center">Paid Amount</th>
                            <th class="text-center">Posted</th>

                        </tr>
                        </thead>
                        <tbody id="due_invoice_list_tr">

                        </tbody>

                    </table>
                    <table class="table table-bordered" id="no_due_invoice" style="display:none">
                        <thead>
                        <tr>

                            <th class="text-center"><span id=""></span> Have No Due invoice</th>


                        </tr>
                        </thead>


                    </table>

                </div>
            </div>
        </div>


    </div><!-- /.page-content -->
    </form>
</div>
<script type="text/javascript">
    var customer_info =<?php echo $customer_info; ?>;




    $("#customer_id_autocomplete").autocomplete({

        source: function (request, response) {
            var term = $.ui.autocomplete.escapeRegex(request.term)
                , startsWithMatcher = new RegExp("^" + term, "i")
                , startsWith = $.grep(customer_info, function (value) {
                return startsWithMatcher.test(value.label || value.value || value);
            })
                , containsMatcher = new RegExp(term, "i")
                , contains = $.grep(customer_info, function (value) {
                return $.inArray(value, startsWith) < 0 &&
                    containsMatcher.test(value.label || value.value || value);
            });

            response(startsWith.concat(contains));
        },
        minLength: 0,
        select: function (event, ui) {
            var item_id = ui.item.value;
            $("#customer_id").val(item_id);
            $('#customer_id').trigger('change');
            $(this).val(ui.item.label);

            get_customer_due_invoice_list(item_id);



            return false;
        },
        focus: function (event, ui) {
            //this is to prevent showing an ID in the textbox instead of name
            //when the user tries to select using the up/down arrow of his keyboard
            //$("#customer_id_autocomplete").val(ui.item.label);
            $("#customer_id").val(ui.item.label);
            return false;
        },
        close: function (e, ui) {
            // $(this).autocomplete('search', '');
            /* destroy the scrollbar each time autocomplete menu closes */
            //$(".ui-autocomplete").mCustomScrollbar("destroy");
        }

    }).click(function () {

        //show_hide_add_button();
        $(this).autocomplete('search', '');
    });

    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
        var term = this.term.split(' ').join('|');
        var re = new RegExp("(" + term + ")", "gi");
        var t = item.label.replace(re, "<b style='color:red'>$1</b>");
        return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + t + "</a>")
            .appendTo(ul);
    };

function get_customer_due_invoice_list(customer_id){

    $("#due_invoice_list_div").LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: baseUrl + 'lpg/SalesController/get_customer_due_invoice_list',
        data: {
            customer_id: $("#customer_id").val(),
        },
        dataType: "json",
        beforeSend: function(  ) {
            $("#due_invoice_list_div").LoadingOverlay("show");
            $("#due_invoice_list_tr").html("");
            $('#no_due_invoice').hide();
            $('#due_invoice_list').hide();
        },
        success: function (data) {

            if(data.invoice_list.length!=0){
                $('#due_invoice_list').show();
                var tr = "";
                for (var i = 0; i <= data.invoice_list.length - 1; i++) {

                    var amount = (parseFloat(data.invoice_list[i].invoice_amount) - ( parseFloat(data.invoice_list[i].paid_amount) + parseFloat(data.invoice_list[i].due_paid_amount))).toFixed(2);

                    tr += "<tr>";
                    tr += "<td>" +
                        '<input type="hidden" name="invoiceId[]" id="" class="sales_invoice_id" value="' + data.invoice_list[i].sales_invoice_id + '">' +
                        '<input type="hidden" name="" id="amount_' + data.invoice_list[i].sales_invoice_id + '" class="" value="' + amount + '">' +
                        data.invoice_list[i].invoice_no +
                        "</td>";
                    tr += "<td>" + amount + "</td>";
                    tr += "<td>" + '<input type="text" autocomplete="off" name="paidAmount_' + data.invoice_list[i].sales_invoice_id + '"  id="paidAmount_' + data.invoice_list[i].sales_invoice_id + '" >' + "</td>";
                    tr += "<td>" + '<input type="checkbox"  name="posted_' + data.invoice_list[i].sales_invoice_id + '"  value="' + data.invoice_list[i].sales_invoice_id + '" >' + "</td>";
                    tr += "</tr>";

                }
                $('#due_invoice_list_tr').append(tr);

            }else{

                $('#no_due_invoice').show();


            }

        }, complete: function () {
            $("#due_invoice_list_div").LoadingOverlay("hide", true);
        }
    });
}


    $("#adjust_payment").bind("click", function () {
       var paid_amount=parseFloat($('#paid_amount').val());
        var advance_amount=parseFloat($('#advance_amount').val());
        if(isNaN(advance_amount)){
            advance_amount=0;
        }
        $.each($('.sales_invoice_id'), function (i, elem) {
            var sales_invoice_id=$(this).val();
            var amount=parseFloat($('#amount_'+sales_invoice_id).val());
            if(paid_amount>amount && paid_amount>0){
                $('#paidAmount_'+sales_invoice_id).val(amount);
            }else if(paid_amount<amount && paid_amount>0){
                $('#paidAmount_'+sales_invoice_id).val(paid_amount);
            }else if(paid_amount==amount && paid_amount>0){
                $('#paidAmount_'+sales_invoice_id).val(paid_amount);
            }
            paid_amount=paid_amount-amount;
           //for advance

        });
        if(paid_amount>0){
            advance_amount+paid_amount;
            $('#advance_amount').val(advance_amount+paid_amount);
        }
    });
    function showBankinfo(id){
        $("#paid_amount").val('');


        if(id == 3){
            $("#showBankInfo").show(10);

        }else{
            $("#showBankInfo").hide(10);
        }

    }
</script>


