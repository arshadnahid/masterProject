<?php
if (isset($_POST['start_date'])):

    $brandId = $this->input->post('brandId');

    $branch_id = $this->input->post('branch_id');
    $from_date = date('d-m-Y', strtotime($this->input->post('start_date')));
    $to_date = date('d-m-Y', strtotime($this->input->post('end_date')));
else:

endif;
?>


<style>
    .refil {
        background: #d6d3ef;
    }

    .refilSum {
        background: #aaa1f7;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Cylinder Combine Report') ?> </div>
            </div>
            <div class="portlet-body">
                <div class="row  noPrint hideforpdf">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right"
                                               for="form-field-1"> <?php echo get_phrase('Brand') ?></label>
                                        <div class="col-sm-8">
                                            <select name="brandId" class="chosen-select form-control "
                                                    id="form-field-select-3" data-placeholder="Search by Category">
                                                <option <?php
                                                if (!empty($brandId) && $brandId == '0') {
                                                    echo "selected";
                                                }
                                                ?> value="0">All
                                                </option>
                                                <?php foreach ($brandList as $eachInfo): ?>
                                                    <option <?php
                                                    if (!empty($brandId) && $brandId == $eachInfo->brandId) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $eachInfo->brandId; ?>"><?php echo $eachInfo->brandName; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-8">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label no-padding-right"
                                                   for="form-field-1"> <?php echo get_phrase('From') ?> </label>
                                            <div class="col-md-8">
                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="start_date" value="<?php
                                                if (!empty($from_date)) {
                                                    echo $from_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder=" dd-mm-yyyy"
                                                       style="width:100%"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;<?php echo get_phrase('To') ?></label>
                                            <div class="col-md-8">
                                                <input type="text" class="date-picker form-control" id="end_date"
                                                       name="end_date" value="<?php
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
                                    <div class="col-md-4">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <?php echo get_phrase('Search') ?>
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="hidden" name="is_print" value="0" id="is_print">
                                            <button type="button" class="btn btn-info btn-sm" id="PRINT"
                                                    style="cursor:pointer;">

                                                <?php echo get_phrase('Print') ?>
                                            </button>
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
</div>
<?php
if (isset($_POST['start_date'])):
    $type_id = $this->input->post('type_id');
    $searchId = $this->input->post('searchId');
    $productId = $this->input->post('productId');
    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
    $total_pvsdebit = '';
    $total_pvscredit = '';
    $total_debit = '';
    $total_credit = '';
    $total_balance = '';
    ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="noPrint hideforpdf">
                <table class="table table-responsive noPrint hideforpdf">
                    <tr>
                        <td style="text-align:center;">
                            <h3><?php echo $companyInfo->companyName; ?>.</h3>
                            <span><?php echo $companyInfo->address; ?></span><br>
                            <strong><?php echo get_phrase('Phone') ?>
                                : </strong><?php echo $companyInfo->phone; ?><br>
                            <strong><?php echo get_phrase('Email') ?>
                                : </strong><?php echo $companyInfo->email; ?><br>
                            <strong><?php echo get_phrase('Website') ?>
                                : </strong><?php echo $companyInfo->website; ?><br>
                            <strong><?php echo $pageTitle; ?></strong>
                            <strong> <?php echo get_phrase('Cylinder_Combine_Report') ?> :</strong>
                            From <?php echo $from_date; ?> To <?php echo $to_date; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table table-bordered" id="ALLPRODUCT">
                <?php
                //all supplier all customer

                $topening = 0;
                $tstockIn = 0;
                $tstockOut = 0;
                ?>
                <thead>
                <tr>

                    <td align="center"><strong><?php echo get_phrase('Brand') ?></strong></td>
                    <td align="center" rowspan="2"><strong><?php echo get_phrase('Product') ?></strong></td>
                    <td align="center" colspan="3"><strong><?php echo get_phrase('Refil Cylender') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(C=A-B)</strong></p>
                    </td>

                    <td align="center" colspan="5"><strong><?php echo get_phrase('Empty Cylender') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(H=D-E-F+G)</strong></p>
                    </td>
                    <td align="center" colspan="2"><strong><?php echo get_phrase('Customer') ?></strong></td>
                    <td align="center" colspan="2"><strong><?php echo get_phrase('Supplier') ?></strong></td>

                    <!--<td align="center"><strong><?php /*echo get_phrase('Closing Stock')*/
                    ?></strong></td>-->
                </tr>
                <tr>
                    <td align="center"><strong><?php echo get_phrase('Brand') ?></strong></td>

                    <td align="center" class=""><strong>
                            <?php echo get_phrase('Purchase  ') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(A)</strong></p>
                    </td>
                    <td align="center" class="">
                        <strong><?php echo get_phrase('Sales  ') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(B)</strong></p>
                    </td>
                    <td align="center" class="">
                        <strong><?php echo get_phrase(' Balance') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(C)</strong></p>
                    </td>
                    <td align="center">
                        <strong><?php echo get_phrase('Purchase') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(D)</strong></p>
                    </td>
                    <td align="center">
                        <strong><?php echo get_phrase('Sales') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(E)</strong></p>
                    </td>
                    <td align="center">
                        <strong><?php echo get_phrase('Purchase Out ') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(F)</strong></p>
                    </td>
                    <td align="center">
                        <strong><?php echo get_phrase('Sales Receive ') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(G)</strong></p>
                    </td>
                    <td align="center">
                        <strong><?php echo get_phrase('  Balance') ?></strong>
                        <p Style="margin: 0px !important;"><strong>(H)</strong></p>
                    </td>
                    <!--<td align="center"><strong><?php /*echo get_phrase('Total') */
                    ?></strong></td>-->
                    <td align="center"><strong><?php echo get_phrase('Customer Due') ?></strong></td>
                    <td align="center"><strong><?php echo get_phrase('Customer Excess') ?></strong></td>
                    <td align="center"><strong><?php echo get_phrase('Supplier Due') ?></strong></td>
                    <td align="center"><strong><?php echo get_phrase('Supplier Excess') ?></strong></td>
                </tr>
                </thead>
                <tbody>
                <?php
                $j = 1;
                foreach ($allStock as $key => $eachResult):

                    ?>

                    <?php
                    $refill_qty = $eachResult->purchase_refill_qty - $eachResult->sales_refill_qty + $eachResult->exchange_qty_refill;
                    $empty_qty = $eachResult->purchase_empty_qty - $eachResult->sales_empty_qty + $eachResult->exchange_qty_empty;
                    //$ttCylender=($eachResult->purchase_refill_qty-$eachResult->sales_refill_qty)+($eachResult->purchase_empty_qty-$eachResult->sales_empty_qty);
                    $ttCylender = ($refill_qty) + ($empty_qty);
                    $sales_customer_due = $eachResult->sales_customer_due;
                    $sales_customer_advance = $eachResult->sales_customer_advance;
                    if(($eachResult->sales_customer_due-$eachResult->exchange_qty)>0){
                        $sales_customer_due = $eachResult->sales_customer_due-$eachResult->exchange_qty;

                    }else{
                        $sales_customer_advance = $eachResult->sales_customer_advance+$eachResult->exchange_qty;
                    }
                    $purchase_supplier_due = $eachResult->purchase_supplier_due;
                    $purchase_supplier_advance = $eachResult->purchase_supplier_advance;
                    $ttCyl = ($refill_qty) + ($empty_qty) + $eachResult->sales_customer_due + $eachResult->sales_customer_advance + $eachResult->purchase_supplier_due + $eachResult->purchase_supplier_advance;
                   // if ($ttCylender > 0 && $ttCyl > 0) {
                    $balance=($eachResult->purchase_empty_qty+ $eachResult->sales_empty_return_qty) - ($eachResult->sales_empty_qty + $eachResult->purchase_return_empty_qty);
                    if ($eachResult->purchase_refill_qty > 0 || $eachResult->sales_refill_qty > 0 || $balance >0) {

                        ?>
                        <tr>
                            <td><?php echo $eachResult->brandName; ?></td>
                            <td><?php echo $eachResult->productName ."   Kg"; ?></td>
                            <td align="right"> <?php echo $eachResult->purchase_refill_qty; ?> </td>
                            <td align="right"><?php echo $eachResult->sales_refill_qty; ?></td>
                            <td align="right"><?php echo($eachResult->purchase_refill_qty - $eachResult->sales_refill_qty); ?></td>
                            <td align="right"><?php echo $eachResult->purchase_empty_qty; ?></td>
                            <td align="right"><?php echo $eachResult->sales_empty_qty; ?></td>
                            <td align="right"><?php echo $eachResult->purchase_return_empty_qty; ?></td>
                            <td align="right"><?php echo $eachResult->sales_empty_return_qty ; ?></td>
                            <td align="right"><?php echo ($eachResult->purchase_empty_qty+ $eachResult->sales_empty_return_qty) - ($eachResult->sales_empty_qty + $eachResult->purchase_return_empty_qty) ; ?></td>
                            <td align="right"><?php echo $sales_customer_due; ?></td>
                            <td align="right"><?php echo $sales_customer_advance; ?></td>
                            <td align="right"><?php echo $purchase_supplier_due; ?></td>
                            <td align="right"><?php echo $purchase_supplier_advance; ?></td>

                            <!--<td align="right"><?php /*echo $purchase_supplier_advance; */ ?></td>-->
                            <!--<td align="right"><?php /*echo $ttCyl; */ ?></td>-->

                        </tr>
                        <?php
                    }
                endforeach;
                ?>


            </table>
        </div>
    </div>
<?php endif; ?>
<script>


    MergeCommonRowsForSearchResult($('#ALLPRODUCT'), 1, 0);


    function MergeCommonRowsForSearchResult(table, startCol, HowManyCol) {


        var firstColumnBrakes = [];
        // iterate through the columns instead of passing each column as function parameter:
        for (var i = startCol; i <= (startCol + HowManyCol); i++) {
            var previous = null, cellToExtend = null, rowspan = 1;
            table.find("td:nth-child(" + i + ")").each(function (index, e) {
                var jthis = $(this), content = jthis.text();

                // check if current row "break" exist in the array. If not, then extend rowspan:
                if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
                    console.log(content);
                    // hide the row instead of remove(), so the DOM index won't "move" inside loop.
                    jthis.addClass('hidden');
                    cellToExtend.attr("rowspan", (rowspan = rowspan + 1));
                    //sum


                } else {
                    // store row breaks only for the first column:
                    if (i === 1)
                        firstColumnBrakes.push(index);
                    rowspan = 1;
                    previous = content;
                    cellToExtend = jthis;
                }

            });
            //sumColValue(table,startCol);
        }

        // now remove hidden td's (or leave them hidden if you wish):
        $('td.hidden').remove();
    }


    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });

    var form = $('#publicForm'); // contact form
    var submit = $('#PRINT');  // submit button
    submit.on('click', function (e) {
        e.preventDefault(); // prevent default form submit
        $("#is_print").val(1);
        form.attr('target', '_blank');
        form.submit();
        $("#is_print").val(0);
        form.attr('target', '');
    });
</script>