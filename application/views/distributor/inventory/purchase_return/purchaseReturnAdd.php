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

<?php
$property_1 = get_property_list_for_show_hide(1);
$property_2 = get_property_list_for_show_hide(2);
$property_3 = get_property_list_for_show_hide(3);
$property_4 = get_property_list_for_show_hide(4);
$property_5 = get_property_list_for_show_hide(5);

?>
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="search" action="" method="post" class="form-horizontal">
                        <table class="mytable table-responsive table table-bordered">
                            <tr>
                                <td style="padding: 10px!important;">
                                    <div class="col-md-12">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right"
                                                       for="form-field-1"> Start Date <span style="color:red;"> *</span></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input class="form-control date-picker" name="saleDate"
                                                               id="startDate" type="text"
                                                               value="<?php echo date('d-m-Y'); ?>"
                                                               data-date-format="dd-mm-yyyy"/>
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right"
                                                       for="form-field-1"> End Date <span
                                                            style="color:red;"> *</span></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input class="form-control date-picker" name="endDate"
                                                               id="saleDate" type="text"
                                                               value="<?php echo date('d-m-Y'); ?>"
                                                               data-date-format="dd-mm-yyyy"/>
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                                    Supplier Name</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <select id="supplierid" name="supplier_id"
                                                                class="chosen-select form-control"

                                                                data-placeholder="Select Supplier Name">
                                                            <option></option>
                                                            <?php foreach ($supplierList as $key => $each_info): ?>
                                                                <option value="<?php echo $each_info->sup_id; ?>"><?php echo $each_info->supName . '&nbsp&nbsp[ ' . $each_info->supID . ' ] '; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                                    Branch Name</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-sm-7">

                                                    <button onclick="filter_purchase_invoice_for_return()" id="subBtn2"
                                                            class="btn btn-info" type="button">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Search
                                                    </button>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                            </tr>


                        </table>
                        <!--</form>

                        <form id="publicForm" action="" method="post" class="form-horizontal">-->
                        <div class="col-md-12">
                            <div class="portlet box blue">
                                <div class="portlet-title" style="min-height:21px">
                                    <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                                        Purchase Invoice List
                                    </div>

                                </div>

                                <div class="portlet-body">


                                    <table class="mytable table-responsive table table-bordered"
                                           id="purchase_invoice_for_return">
                                        <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                Invoice Id
                                            </th>
                                            <th>
                                                invoice Date
                                            </th>
                                            <td>
                                                Sales Product
                                            </td>
                                            <td>
                                                Product Qty
                                            </td>
                                            <td>
                                                Price
                                            </td>
                                            <td>
                                                Total Price
                                            </td>
                                            <?php
                                            if ($property_1 != "dont_have_this_property") {
                                                ?>

                                                <th nowrap
                                                    style="width:17%;border-radius:10px;<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_1; ?> </strong>
                                                </th>
                                                <?php

                                            }
                                            ?>
                                            <?php
                                            if ($property_2 != "dont_have_this_property") {
                                                ?>
                                                <th nowrap
                                                    style="width:10%;border-radius:10px;<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?> ">
                                                    <strong><?php echo $property_2; ?> </strong>

                                                </th>
                                                <?php

                                            }
                                            ?>
                                            <?php
                                            if ($property_3 != "dont_have_this_property") {
                                                ?>
                                                <th nowrap
                                                    style="width:10%;border-radius:10px; <?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_3; ?> </strong>
                                                </th>
                                                <?php

                                            }
                                            ?>
                                            <?php
                                            if ($property_4 != "dont_have_this_property") {
                                                ?>
                                                <th nowrap
                                                    style="width:10%;border-radius:10px; <?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_4; ?> </strong>
                                                </th>
                                                <?php

                                            }
                                            ?>
                                            <?php
                                            if ($property_5 != "dont_have_this_property") {
                                                ?>
                                                <th nowrap
                                                    style="width:10%;border-radius:10px;<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                                    <strong><?php echo $property_5; ?> </strong>
                                                </th>
                                                <?php

                                            }
                                            ?>
                                            <td>
                                                Action
                                            </td>
                                        </tr>
                                        </thead>
                                    </table>


                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label no-padding-right"
                                                       for="form-field-1"> Return Date <span
                                                            style="color:red;"> *</span></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input class="form-control date-picker"
                                                               name="return_date"
                                                               id="return_date" type="text"
                                                               value="<?php echo date('d-m-Y'); ?>"
                                                               data-date-format="dd-mm-yyyy"/>
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label no-padding-right"
                                                       for="credit_limit"> <?php echo get_phrase('Naration') ?> </label>
                                                <div class="col-sm-10">


                                                                <textarea style="" cols="120"
                                                                          class="form-control" name="narration"
                                                                          placeholder="Narration......" type="text"
                                                                          spellcheck="false"></textarea>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-1 col-md-10">

                                                <button onclick="return isconfirm2()" id="subBtn" class="btn blue" type="button">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    <?php echo get_phrase('Save') ?>
                                                </button>
                                                <!--<button onclick="return isconfirm2()" id="subBtn"
                                                        class="btn btn-info"
                                                        type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    Save
                                                </button>-->
                                                &nbsp; &nbsp; &nbsp;

                                            </div>
                                        </div>
                                    </div>


                                </div>


                            </div>

                        </div>


                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>


<script>
    function isconfirm2() {

        var supplierid = $("#supplierid").val();
        var return_date = $("#return_date").val();
        var numberOfChecked = 0;
        $.each($('.checkbox_for_return'), function (i, elem) {
            if ($(this).prop("checked") == true) {
                numberOfChecked=numberOfChecked+1;
            }
        });



         if (supplierid == '') {
            swal("Select Supplier Name!", "Validation Error!", "error");
        } else if (return_date == '') {
            swal("Select Return Date", "Validation Error!", "error");
        } else if (numberOfChecked < 1) {
            swal("Select Return Product!", "Validation Error!", "error");
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
                        $("#search").submit();
                    } else {
                        return false;
                    }
                });
        }
    }

    $(document).ready(function () {
        var table;
        window.table = $('#purchase_invoice_for_return').DataTable();
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });

    function getInvoiceProduct(invoiceId) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('lpg/ReturnDagameController/getInvoiceProductList'); ?>",
            data: 'invoiceId=' + invoiceId,
            success: function (result) {
                $("#invoiceProduct").html(result);
            }
        });

    }

    function filter_purchase_invoice_for_return() {
        var supplierid = $('#supplierid').val();
        var BranchAutoId = $('#BranchAutoId').val();

        $("#purchase_invoice_for_return").dataTable().fnDestroy();

        if (supplierid == "") {
            swal("Select Customer Name!", "Validation Error!", "error");
        } else if (BranchAutoId == '') {
            swal("Select Branch Name!", "Validation Error!", "error");
        } else {
            var table = $('#purchase_invoice_for_return').DataTable({
                "paging": false,
                "processing": false,
                "serverSide": true,
                "order": [],
                "ordering": false,

                "info": false,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    url: "<?php echo site_url('lpg/ReturnDagameController/getpurchaseInvoiceList'); ?>",
                    "type": "POST",
                    "data": function (data) {
                        data.startDate = $('#startDate').val();
                        data.endDate = $('#endDate').val();
                        data.supplierid = $('#supplierid').val();
                        data.BranchAutoId = $('#BranchAutoId').val();

                    }
                },
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "columns": [
                    {
                        "bVisible": false, "aTargets": [0]
                    },
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,

                    /*  {
                          // render action button
                          mRender: function (data, type, row) {
                              var bindHtml = '';
                              bindHtml += ' <input class="form-check-input checkbox_for_return" name="sales_details_id[]" type="checkbox" value="' + row[0] + '" id="defaultCheck1' + row[0] + '">';
                              return bindHtml;
                          }
                      },*/


                ], "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData[0]);
                }

            });
        }


        //  table.clear().draw();
    }


    $('.date-picker2').datepicker({
        autoclose: true,
        todayHighlight: true,
        onSelect: function (date) {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('lpg/ReturnDagameController/showAllInvoiceListByDate'); ?>",
                data: 'date=' + date,
                success: function (result) {
                    $('#invoiceList').chosen();
                    $('#invoiceList option').remove();
                    $('#invoiceList').append($(result));
                    $("#invoiceList").trigger("chosen:updated");
                }
            });
        }
    });
    //show datepicker when clicking on the icon
    /*.next().on(ace.click_event, function(){
        $(this).prev().focus();
    });*/





    $(document).on('click', '.checkbox_for_return', function () {
        var id = $(this).val();
        if ($(this).prop("checked") == true) {
            $('#quantity_' + id).attr("readonly", false);
            $('#unit_price_' + id).attr("readonly", false);
        } else {
            $('#quantity_' + id).attr("readonly", true);
            $('#unit_price_' + id).attr("readonly", true);
        }
    });

    $(document).on('keyup', '.quantity', function () {
        var quantity = parseFloat($(this).val());
        var actual_quantity = parseFloat($(this).attr('attr-actual-quantity'));
        var id = $(this).attr('attr-purchase-details-id');


        if (actual_quantity < quantity) {
            $(this).val(actual_quantity);
        }
        calculateTotal(id);

    });
    $(document).on('keyup', '.unit_price', function () {
        var unit_price = parseFloat($(this).val());

        var id = $(this).attr('attr-purchase-details-id');


        calculateTotal(id);

    });

    function calculateTotal(id) {
        var unit_price = parseFloat($('#unit_price_' + id).val());
        var quantity = parseFloat($('#quantity_' + id).val());
        var tt_price = unit_price * quantity;

        $('#tt_price_' + id).val(tt_price);

    }

</script>