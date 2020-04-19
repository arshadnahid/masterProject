<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Cylinder Combine Report') ?> </div>
            </div>
            <div class="portlet-body">
                <div class="row  noPrint">
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
                                            <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
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

                            <table class="table table-responsive">
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
                                    <td align="center"><strong><?php echo get_phrase('Product') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Refil Cylinder') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Empty Cylender') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Total') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Customer Due') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Customer Excess') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Supplier Due') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Supplier Excess') ?></strong></td>
                                    <!--<td align="center"><strong><?php /*echo get_phrase('Closing Stock')*/
                                    ?></strong></td>-->
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
                                    $purchase_supplier_due = $eachResult->purchase_supplier_due;
                                    $purchase_supplier_advance = $eachResult->purchase_supplier_advance;
                                    $ttCyl = ($refill_qty) + ($empty_qty) + $eachResult->sales_customer_due + $eachResult->sales_customer_advance + $eachResult->purchase_supplier_due + $eachResult->purchase_supplier_advance;
                                    if ($ttCylender > 0 && $ttCyl > 0) {

                                        ?>
                                        <tr>
                                            <td><?php echo $eachResult->brandName; ?></td>
                                            <td><?php echo $eachResult->productName; ?></td>
                                            <td align="right"> <?php echo $refill_qty; ?> </td>
                                            <td align="right"><?php echo $empty_qty; ?></td>
                                            <td align="right"><?php echo $ttCylender; ?></td>
                                            <td align="right"><?php echo $sales_customer_due; ?></td>
                                            <td align="right"><?php echo $sales_customer_advance; ?></td>
                                            <td align="right"><?php echo $purchase_supplier_due; ?></td>
                                            <td align="right"><?php echo $purchase_supplier_advance; ?></td>
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
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
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


</script>

<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>