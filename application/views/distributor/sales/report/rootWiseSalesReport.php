<?php
$from_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$to_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
$root_id = isset($_GET['root_id']) ? $_GET['root_id'] : '';
$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
?>

<style type="text/css">

    .ui-autocomplete {
        /*  background-color: #FFF;
          box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
          width: 50%;height: 25px;display: inline-block;background-color: #ffffff;border: 1px solid #d0d0d0;border-radius: 0;margin-left: 15px;
          margin-bottom: 20px;*/
        max-height: 250px !important;
        max-width: 300px !important;
        overflow: auto !important;
        height: auto !important;
        margin-left: -38px !important;
    }

    /*.ui-menu .ui-menu-item {
        padding: 5px 10px 6px;
        color: #444;
        cursor: pointer;
        display: block;
        -webkit-box-sizing: inherit;
        -moz-box-sizing: inherit;
        box-sizing: inherit;
    }*/
    .ui-autocomplete .ui-menu-item {
        font-size: 14px !important;
        background: #fff;
        border-bottom: 1px solid rgba(128, 128, 128, 0.20);
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        height: 30px !important;
        line-height: 30px !important;
        color: gray;
        padding-bottom: 15px !important;
        margin: 0px !important;
        font-weight: normal !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Daily Sales statement Root wise
                </div>

            </div>

            <div class="portlet-body ">
                <div class="row">
                    <div class="col-md-12 noPrint">

                        <form id="publicForm" action="" method="get" class="form-horizontal">


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label no-padding-right" for="BranchAutoId">
                                        <?php echo get_phrase('Branch') ?></label>
                                    <div class="col-sm-8">
                                        <select name="branch_id" class="chosen-select form-control"
                                                id="BranchAutoId" data-placeholder="Select Branch">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($branch_id)) {
                                                $selected = $branch_id;
                                            } else {
                                                $selected = 'all';
                                            }
                                            // come from branch_dropdown_helper
                                            echo branch_dropdown('all', $selected);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label no-padding-right" for="root_id">
                                        Root </label>
                                    <div class="col-sm-8">
                                        <select onchange="showRootWiseCusList(this.value)" name="root_id"
                                                class="chosen-select form-control" id="root_id"
                                                data-placeholder="Select Root">

                                            <option value="all">All</option>
                                            <?php foreach ($rootInfo as $key => $root_info): ?>
                                                <option <?php
                                                if (!empty($root_id) && $root_id == $root_info->root_id): echo "selected";
                                                endif;
                                                ?> value="<?php echo $root_info->root_id; ?>"><?php echo $root_info->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div id="newValue"></div>
                                <div id="oldValue">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right"
                                               for="form-field-1">Customer</label>
                                        <div class="col-sm-9">
                                            <!--<select  id="customerTypeList" name="customer_id"  class="chosen-select form-control" data-placeholder="Search by Customer">
                                                <option value="all">All</option>

                                            </select>-->
                                            <input type="text" class="form-control" id="customer_id_autocomplete"
                                            />
                                            <input type="hidden" class="form-control" id="customer_id"
                                                   name="customer_id" value="all"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> From
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="date-picker form-control" id="start_date"
                                               name="start_date"
                                               value="<?php
                                               if (!empty($from_date)) {
                                                   echo $from_date;
                                               } else {
                                                   echo date('d-m-Y');
                                               }
                                               ?>" data-date-format='dd-mm-yyyy'
                                               placeholder="Start Date: dd-mm-yyyy" style="width:100%"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> To
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="date-picker form-control" id="end_date"
                                               name="end_date"
                                               value="<?php
                                               if (!empty($to_date)):
                                                   echo $to_date;
                                               else:
                                                   echo date('d-m-Y');
                                               endif;
                                               ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"
                                               style="width:100%"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                                style="cursor:pointer;">
                                            <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                            Print
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>


                <!-- /.col -->

                <?php
                if ($from_date != ''):
                    //$customer_id = $this->input->post('customer_id');
                    if (!empty($salesList)):
                        if ($customer_id != 'all'):
                            ?>
                            <div class="row">

                                <div class="col-xs-12">

                                    <table class="table table-responsive">
                                        <tr>
                                            <td style="text-align:center;">
                                                <h3><?php echo $companyInfo->companyName; ?></h3>
                                                <p><?php echo $companyInfo->address; ?></p>
                                                <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                                <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                                <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                                <strong><?php echo $pageTitle; ?></strong>
                                                <strong> <strong>From <?php echo $from_date; ?>
                                                        To <?php echo $to_date; ?></span></strong></strong>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td align="center"><strong>Customer Name</strong></td>
                                            <td align="center"><strong>Root</strong></td>
                                            <td align="center"><strong>Date</strong></td>
                                            <td align="center"><strong>Product Name</strong></td>
                                            <td align="center"><strong>Amount</strong></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $totalAmount = 0;
                                        $totalOpening = 0;
                                        $brandNameArray = array();
                                        foreach ($salesList as $key => $row):
                                            if (!in_array($row->branch_name, $brandNameArray)) {
                                                array_push($brandNameArray, $row->branch_name);
                                                ?>
                                                <tr>
                                                    <td colspan="6">
                                                        <?php echo '<b>' . $row->branch_name . '</b>' ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $row->customerName; ?></td>
                                                    <td><?php echo $row->name; ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($row->invoice_date)); ?></td>
                                                    <td><?php echo $row->Product; ?></td>
                                                    <td align="right"><?php
                                                        echo number_format((float)abs($row->invoice_amount), 2, '.', ',');
                                                        $total_debit += $row->invoice_amount;
                                                        ?></td>
                                                </tr>

                                                <?php
                                            } else {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row->customerName; ?></td>
                                                    <td><?php echo $row->name; ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($row->invoice_date)); ?></td>
                                                    <td><?php echo $row->Product; ?></td>
                                                    <td align="right"><?php
                                                        echo number_format((float)abs($row->invoice_amount), 2, '.', ',');
                                                        $total_debit += $row->invoice_amount;
                                                        ?></td>
                                                </tr>
                                            <?php } endforeach; ?>
                                        <!-- /Search Balance -->
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="4" align="right"><strong>Total Sales Amount</strong></td>
                                            <td align="right">
                                                <strong><?php echo number_format((float)abs($total_debit + $totalOpening), 2, '.', ','); ?>
                                                    &nbsp;</strong></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-xs-12">
                                        <div class="table-header">
                                            Root Wise Sales Report <span
                                                    style="color:greenyellow;">From <?php echo $from_date; ?>
                                                To <?php echo $to_date; ?></span>
                                        </div>

                                        <table class="table table-responsive">
                                            <tr>
                                                <td style="text-align:center;">
                                                    <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                                    <p><?php echo $companyInfo->address; ?>
                                                    </p>
                                                    <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                                    <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                                    <strong>Website : </strong><?php echo $companyInfo->website; ?>
                                                    <br>
                                                    <strong><?php echo $pageTitle; ?></strong>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>

                                                <td align="center"><strong>Branch</strong></td>
                                                <td align="center"><strong>Root</strong></td>
                                                <td align="center"><strong>Customer Name</strong></td>
                                                <td align="center"><strong>Invoice No</strong></td>
                                                <td align="center"><strong>Date</strong></td>
                                                <td align="center"><strong>Product</strong></td>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($salesList as $key => $value_expance) {
                                                ?>
                                                <tr class="expance_without_cost_of_goods_sold">
                                                    <td>
                                                        <?php echo $key; ?>
                                                    </td>
                                                    <td colspan="5" class="text-left">
                                                        <?php ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                foreach ($value_expance as $key => $value) { ?>
                                                    <tr class="expance_without_cost_of_goods_sold">
                                                        <td>
                                                            <?php ?>
                                                        </td>
                                                        <td colspan="5" class="text-left">
                                                            <?php echo $key; ?>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                    foreach ($value as $key => $value2) { ?>

                                                        <tr class="expance_without_cost_of_goods_sold">
                                                            <td colspan="2" class="text-left">
                                                            </td>
                                                            <td colspan="" class="text-left">
                                                                <?php echo $value2->customerName; ?>
                                                            </td>
                                                            <td colspan="" class="text-left">
                                                                <?php echo $value2->invoice_no; ?>
                                                            </td>
                                                            <td colspan="" class="text-left">
                                                                <?php echo $value2->invoice_date; ?>
                                                            </td>
                                                            <td colspan="" class="text-left" style="padding: 0px;">
                                                                <table class="table table-bordered" style="margin-bottom: 0px;">
                                                                    <thead>
                                                                    <tr>
                                                                        <td>Product</td>
                                                                        <td>Qty</td>
                                                                        <td>Price</td>
                                                                        <td>Total Price</td>
                                                                    </tr>
                                                                    </thead>
                                                                <?php
                                                                $allAccount = explode('*¥¥*', $value2->otherLedger);
                                                                foreach ($allAccount as $eachInfo):
                                                                    if (!empty($eachInfo)):
                                                                        $eachInfo = trim($eachInfo, ",");
                                                                        $ledger = explode('¥', $eachInfo);
                                                                        $amount = 0;
                                                                        if (!empty($ledger[2]) && $ledger[2] > 0):
                                                                            $amount = $ledger[2];
                                                                        else:
                                                                            $amount = $ledger[3];
                                                                        endif;
                                                                        if ($amount > 0) {
                                                                            ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php
                                                                                    echo $ledger[0];
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    echo number_format((float)abs($ledger[2]), 2, '.', ',');
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    echo number_format((float)abs($ledger[1]), 2, '.', ',');
                                                                                    ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php
                                                                                    $a=$ledger[1]*$ledger[2];
                                                                                    echo number_format((float)abs($a), 2, '.', ',');
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                    endif;
                                                                endforeach;
                                                                ?>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <!-- /Search Balance -->
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3" align="right"><strong>Total Sales Amount</strong></td>
                                                <td align="right">
                                                    <strong><?php echo number_format((float)abs($total_debit), 2, '.', ','); ?>
                                                        &nbsp;</strong></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>


<!-- /.row -->


<script>

    var table = $('#example').DataTable({})
    // Add event listener for opening and closing details
    $('#sample_3 tbody').on('click', 'td.sorting_1', function () {
        alert('dar');
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        console.log('iiii');
        console.log(row);

        if (row.child.isShown()) {
            console.log('ppp');
            // This row is already open - close it
            row.child.hide();
            //tr.removeClass('shown');
        }
        else {
            console.log('000');
            // Open this row
            // row.child().hide();
            //tr.addClass('shown');
        }
    });

    function showRootWiseCusList(root_id) {

        $("#customer_id_autocomplete").prop("disabled", true);
        $("#customer_id_autocomplete").val('');
        $("#customer_id").val('');

        if (typeof(customerList_jason[root_id]) != 'undefined') {


            if ((customerList_jason[root_id].length > 0)) {

                $("#customer_id_autocomplete").prop("disabled", false);
                $("#customer_id_autocomplete").autocomplete({
                    //source: customerList_jason[type]

                    source: function (request, response) {
                        var root_id = $('#root_id').val();
                        var source = customerList_jason[root_id];
                        console.log(customerList_jason[root_id]);
                        var term = $.ui.autocomplete.escapeRegex(request.term)
                            , startsWithMatcher = new RegExp("^" + term, "i")
                            , startsWith = $.grep(customerList_jason[root_id], function (value) {
                            return startsWithMatcher.test(value.label || value.value || value);
                        })
                            , containsMatcher = new RegExp(term, "i")
                            , contains = $.grep(customerList_jason[root_id], function (value) {
                            return $.inArray(value, startsWith) < 0 &&
                                containsMatcher.test(value.label || value.value || value);
                        });

                        response(startsWith.concat(contains));
                    },
                    minLength: 0,
                    select: function (event, ui) {
                        var item_id = ui.item.value;
                        $("#customer_id").val(item_id);
                        $('#customer_id_autocomplete').val(ui.item.label);

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
                    response: function (e, ui) {
                        /* destroy the scrollbar after each search completes, before the menu is shown */
                        $(".ui-autocomplete").mCustomScrollbar("destroy");
                        if (ui.content.length < 1) {
                            var item = new Object();
                            item.label = 'No match found!';
                            item.value = '';
                            ui.content.push(item);
                        }

                    },

                    focus: function (e, ui) {
                        //* scroll via keyboard
                        if (!ui.item) {
                            var first = $(".ui-autocomplete li:first");
                            first.trigger("mouseenter");
                            $(this).val(first.data("uiAutocompleteItem").label);
                        }
                        var el = $(".ui-state-focus").parent();
                        if (!el.is(":mcsInView") && !el.is(":hover")) {
                            $(".ui-autocomplete").mCustomScrollbar("scrollTo", el, {scrollInertia: 0, timeout: 0});
                        }

                        //this is to prevent showing an ID in the textbox instead of name
                        //when the user tries to select using the up/down arrow of his keyboard
                        //$("#customer_id_autocomplete").val('');

                        return false;
                    },
                    close: function (e, ui) {
                        // $(this).autocomplete('search', '');
                        /* destroy the scrollbar each time autocomplete menu closes */
                        //$(".ui-autocomplete").mCustomScrollbar("destroy");
                    }
                }).click(function () {
                    $(this).autocomplete('search', '');
                });


                //$("#customer_id_autocomplete").trigger("click");
            } else {
                if (jQuery($("#customer_id_autocomplete")).data('ui-autocomplete') != undefined) {
                    jQuery($("#customer_id_autocomplete")).autocomplete("destroy");
                    jQuery($("#customer_id_autocomplete")).removeData('autocomplete');

                }
            }
        } else if (root == 'all') {
            if (jQuery($("#customer_id_autocomplete")).data('ui-autocomplete') != undefined) {
                jQuery($("#customer_id_autocomplete")).autocomplete("destroy");
                jQuery($("#customer_id_autocomplete")).removeData('autocomplete');
            }
            $("#customer_id_autocomplete").val('ALL');
            $("#customer_id").val('all');

        } else {


            if (jQuery($("#customer_id_autocomplete")).data('ui-autocomplete') != undefined) {
                jQuery($("#customer_id_autocomplete")).autocomplete("destroy");
                jQuery($("#customer_id_autocomplete")).removeData('autocomplete');
            }
            $("#customer_id_autocomplete").val('No customer');
            $("#customer_id").val('');
        }
    }


    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
        var term = this.term.split(' ').join('|');
        var re = new RegExp("(" + term + ")", "gi");
        var t = item.label.replace(re, "<b style='color:#29B4B6'>$1</b>");
        return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + t + "</a>")
            .appendTo(ul);
    };


    //customerList_jason

    var customerList_jason =<?php echo $customerList_jason; ?>;



</script>

<script>

    $(document).ready(function () {
        //$('#cusType').val('all').trigger("chosen:updated");
        var root_id = '<?php echo isset($_GET['root_id']) ? $_GET['root_id'] : 'all'?>';
        var customer_id = '<?php echo isset($_GET['customer_id']) ? $_GET['customer_id'] : ''?>';

        if (root_id != '') {
            showRootWiseCusList(root_id);
        } else {
            showRootWiseCusList('all');
        }
        if (customer_id != '') {

            var a = customerList_jason[1].find(x = > x.value === customer_id
        ).
            label;
            var b = customerList_jason[1].find(x = > x.value === customer_id
        ).
            value;

            $("#customer_id_autocomplete").val(a);
            $("#customer_id").val(b);
        }

        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>
