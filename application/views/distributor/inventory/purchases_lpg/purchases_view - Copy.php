<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Purchases View</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="inventoryAddPermission" href="<?php echo site_url('purchases_add'); ?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Add
                    </a>
                </li>
                <li>
                    <a href="<?php echo site_url('purchases_list'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
                <li>
                    <a class="inventoryEditPermission"
                       href="<?php echo site_url('purchases_edit/' . $purchasesList->purchase_invoice_id); ?>">
                        <i class="ace-icon fa fa-pencil bigger-130"></i> Edit
                    </a>
                </li>
                <li>
                    <a class="inventoryEditPermission"
                       href="<?php echo site_url('viewPurchasesWithCylinder/' . $purchasesList->purchase_invoice_id); ?>">
                        <i class="ace-icon fa fa-search-plus bigger-130"></i> With Cylinder
                    </a>
                </li>
            </ul>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-large">
                            <h3 class="widget-title grey lighter">
                                <i class="ace-icon fa fa-leaf green"></i>
                                Purchases Voucher
                            </h3>
                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Payment Type:</span>
                                <span class="red"><?php
                                    if ($purchasesList->payment_type == 1) {
                                        echo " Cash";
                                    } elseif ($purchasesList->payment_type == 4) {
                                        echo "Cash";
                                    } elseif ($purchasesList->payment_type == 3) {
                                        echo "Cheque";
                                    } else {
                                        echo "Credit";
                                    }
                                    ?></span>
                                <br/>
                                <?php if ($purchasesList->payment_type == 2) { ?>
                                    <span class="invoice-info-label"> Due Date:</span>
                                    <span class="red"><?php echo date('d-m-Y', strtotime($purchasesList->due_date)); ?></span>
                                <?php } ?>
                            </div>
                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Voucher ID:</span>
                                <span class="red"><?php echo $purchasesList->invoice_no; ?>
                                    / <?php echo $purchasesList->supplier_invoice_no; ?></span>
                                <br/>
                                <span class="invoice-info-label"> Date:</span>
                                <span class="red"><?php echo date('d-m-Y', strtotime($purchasesList->invoice_date)); ?></span>
                            </div>
                            <div class="widget-toolbar hidden-480" class="hidden-xs">
                                <a onclick="window.print();" style="cursor:pointer;">
                                    <i class="ace-icon fa fa-print"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main padding-24">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-default arrowed-in arrowed-right">
                                                Company Info
                                            </div>
                                        </div>
                                        <div>
                                            <ul class="list-unstyled spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->companyName; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->email; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->phone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->address; ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-xs-4">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-default arrowed-in arrowed-right">
                                                Supplier Info
                                            </div>
                                        </div>
                                        <div>
                                            <ul class="list-unstyled  spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supID . '[' . $supplierInfo->supName . ']' ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supEmail; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supPhone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supAddress; ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-xs-4">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-default arrowed-in arrowed-right">
                                                Invoice Info
                                            </div>
                                        </div>
                                        <div>
                                            <ul class="list-unstyled  spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Current Due: <span
                                                            id="customerCurrentDue"></span>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>
                                                    Transportation: <?php
                                                    // if (!empty($purchasesList->transportation)):
                                                    if ($purchasesList->transport_charge > 0):
                                                        $transporation = $this->Common_model->tableRow('vehicle', 'id', $purchasesList->tran_vehicle_id);

                                                        echo $transporation->vehicleName . ' [ ' . $transporation->vehicleModel . ' ] ';
                                                    // echo $transporation->transport_charge ;
                                                    else:
                                                        echo "N/A";
                                                    endif;
                                                    ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i> Loader:

                                                    <?php
                                                    if (($purchasesList->loader_charge) > 0):
                                                        //if (!empty($purchasesList->loader)):
                                                        $loaderInfo = $this->Common_model->tableRow('employee', 'id', $purchasesList->loader_emp_id);
                                                        echo $loaderInfo->personalMobile . ' [ ' . $loaderInfo->name . ' ] ';
                                                    //echo $loaderInfo->loader_charge;
                                                    else:
                                                        echo "N/A";
                                                    endif;
                                                    ?>
                                                </li>

                                            </ul>
                                        </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                                <div class="space"></div>
                                <div style="min-height:400px;">
                                    <table class="table table-striped table-bordered" id="ALLPRODUCT">
                                        <thead>
                                        <tr>
                                            <td class="center">#</td>
                                            <td><strong>Product</strong></td>


                                            <td>Return Product</td>
                                            <td>Quantity Return</td>
                                            <td><strong>Quantity</strong></td>
                                            <td><strong>Unit Price</strong></td>
                                            <td><strong>Total Price</strong></td>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $tqty = 0;
                                        $trate = 0;
                                        $tprice = 0;
                                        foreach ($stockList[$purchasesList->purchase_invoice_id] as $key => $each_info):
                                            //if ($each_info->type == 'In') {
                                            $tqty += $each_info['quantity'];
                                            $trate += $each_info['unit_price'];
                                            $tprice += $each_info['unit_price'] * $each_info['quantity'];
                                            ?>
                                            <tr>
                                                <td class="center"><?php echo $key + 1; ?></td>
                                                <td>
                                                    <?php
                                                    echo $each_info['title'] . ' ' . $each_info['productName'] . ' ' . $each_info['unitTtile'] . ' [ ' . $each_info['brandName'] . ' ] ';
                                                    //echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                    ?>
                                                </td>
                                                <td colspan="2">
                                                    <table class="table table-border">
                                                        <?php
                                                        foreach ($each_info['return'] as $key1 => $value1) {
                                                            if(!empty($value1)){


                                                            foreach ($value1 as $key2 => $value2) {

                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $value2['return_product_cat'] . ' ' . $value2['return_product_name'] . ' ' . $value2['return_product_unit'] . '[ ' . $value2['return_product_brand'] . ' ]' ?></td>
                                                                    <td><?php echo $value2['returnable_quantity'] ?></td>
                                                                </tr>


                                                            <?php }
                                                            }?>
                                                            <tr>
                                                                <td>--------</td>
                                                                <td>--------</td>
                                                            </tr>

                                                            <?php
                                                        } ?>
                                                    </table>
                                                </td>

                                                <td align="right"><?php echo $each_info['quantity']; ?> </td>
                                                <td align="right"><?php echo $each_info['unit_price']; ?> </td>
                                                <td align="right"><?php echo number_format($each_info['unit_price'] * $each_info['quantity'], 2); ?> </td>
                                            </tr>
                                            <?php
                                            //}
                                        endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Sub-Total</strong></td>
                                            <td align='right'><?php echo number_format((float)$tprice, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Discount ( - )</strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->discount_amount, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Loader ( + )</strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->loader_charge, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Transportation ( + )</strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->transport_charge, 2, '.', ','); ?></td>
                                        </tr>

                                        <?php
                                        $netAmount = ($tprice + $purchasesList->transport_charge + $purchasesList->loader_charge) - $purchasesList->discount_amount;
                                        ?>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Net Total</strong></td>
                                            <td align='right'><?php echo number_format((float)$netAmount, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Payment</strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->paid_amount, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Due Amount</strong></td>
                                            <td align='right'><?php echo number_format((float)$netAmount - $purchasesList->paid_amount, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <strong>
                                                    <span>In Words : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($netAmount); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <span>Narration : &nbsp;</span> <?php //echo $purchasesList->narration; ?>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="hr hr8 hr-double hr-dotted"></div>
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <p>Prepared By:_____________<br/>
                                            Date:____________________
                                        </p>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p>Approved By:________________<br/>
                                            Date:_________________</p>
                                    </div>
                                </div>
                                <hr/>
                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                                     ?></p>
                                <!--                                <div class="space-6"></div>
                                                                <div class="well">
                                                                    Thank you for choosing Ace Company products.
                                                                    We believe you will be satisfied by our services.
                                                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>

<script>
    var url = baseUrl + "SalesController/getSupplierClosingBalance";

    $.ajax({
        type: 'POST',
        url: baseUrl + "InventoryController/getSupplierClosingBalance",
        data: {
            supplierid: '<?php echo $purchasesList->supplier_id; ?>'
        },
        success: function (data) {
            data = parseFloat(data);
            if (isNaN(data)) {
                data = 0;
            }
            $('#customerCurrentDue').text(parseFloat(data).toFixed(2));
        }
    });

    MergeCommonRowsForSearchResult($('#ALLPRODUCT'), 1, 3);


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

