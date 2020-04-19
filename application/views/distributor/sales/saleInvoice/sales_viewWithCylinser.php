
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/2'); ?>">Sales</a>
                </li>
                <li class="active">Sales Invoice</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo site_url('salesInvoice'); ?>">
                        <i class="ace-icon 	fa fa-list"></i> List
                    </a>
                </li>
                <li class="active saleAddPermission"><a href="<?php echo site_url('salesInvoice_add'); ?>" >
                        <i class="ace-icon 	fa fa-plus"></i>  Add
                    </a>
                </li>

                <li>
                    <a class="saleEditPermission"  href="<?php echo site_url('salesInvoice_edit/' . $saleslist->generals_id); ?>">
                        <i class="ace-icon 	fa fa-pencil bigger-130"></i> Edit
                    </a>
                </li>
                <li>
                    <a class="saleEditPermission"  href="<?php echo site_url('salesInvoice_view/' . $saleslist->generals_id); ?>">
                        <i class="ace-icon fa fa-search-plus "></i> View Invoice
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
                                Sales Invoice
                            </h3>
                            <!--                            <div class="widget-toolbar no-border invoice-info">
                                                            <span class="invoice-info-label"></span>
                                                           <a  onclick="window.print();" style="cursor:pointer;">
                                                                <i class="ace-icon fa fa-print"></i>Money Receipt
                                                            </a>
                                                        </div>-->
                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Payment Type:</span>
                                <span class="red"><?php
if ($saleslist->payType == 1) {
    echo "Cash";
} elseif ($saleslist->payType == 2) {
    echo "Credit";
} elseif ($saleslist->payType == 3) {
    echo "Check";
} else {
    echo "Cash";
}
?></span>
                                <br />
                                <span class="invoice-info-label">Reference:</span>
                                <span class="blue"><?php echo $this->Common_model->tableRow('reference', 'reference_id', $saleslist->reference)->referenceName; ?></span>
                            </div>
                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Invoice ID:</span>
                                <span class="red"><?php echo $saleslist->voucher_no; ?> / <?php echo $saleslist->mainInvoiceId ?></span>
                                <br />
                                <span class="invoice-info-label">Date:</span>
                                <span class="blue"><?php echo date('d-m-Y', strtotime($saleslist->date)) ?></span>
                            </div>
                            <div class="widget-toolbar hidden-480"  class="hidden-xs">
                                <a  onclick="window.print();" style="cursor:pointer;">
                                    <i class="ace-icon fa fa-print"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main padding-24">
                                <div class="row"  >
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
                                                Customer Info
                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-xs-7">

                                                <ul class="list-unstyled  spaced">
                                                    <li>
                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerID . '[' . $customerInfo->customerName . ']' ?>
                                                    </li>
                                                    <li>
                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerEmail; ?>
                                                    </li>
                                                    <li>
                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerPhone; ?>
                                                    </li>
                                                    <li>
                                                        <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerAddress; ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-5">
                                                <ul class="list-unstyled  spaced">
                                                    <li>
                                                        <strong> Current Due: <span class="currentBalance"></span></strong>
                                                    </li>
                                                </ul>
                                            </div>
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
                                                    <i class="ace-icon fa fa-caret-right green"></i> Current Due: <span class="currentBalance"></span>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i> Transportation: <?php
                                    if (!empty($saleslist->transportation)):
                                        $transporation = $this->Common_model->tableRow('vehicle', 'id', $saleslist->transportation);
                                        echo $transporation->vehicleName . ' [ ' . $transporation->vehicleModel . ' ] ';
                                    else:
                                        echo "N/A";
                                    endif;
?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i> Loader:

                                                    <?php
                                                    if (!empty($saleslist->loader)):

                                                        $loaderInfo = $this->Common_model->tableRow('employee', 'id', $saleslist->loader);
                                                        echo $loaderInfo->personalMobile . ' [ ' . $loaderInfo->name . ' ] ';
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
                                <div style="min-height:400px;" >
                                    <table class="table table-striped table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <td class="center">#</td>
                                                <td>Product Category</td>
                                                <td>Product</td>
                                                <td>Quantity</td>
                                                <td>Unit Price</td>
                                                <td>Total Price</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tqty = 0;
                                            $trate = 0;
                                            $tprice = 0;
                                            $j = 1;
                                            $receiveAble = 0;
                                            $received = 0;
                                            foreach ($stockList as $key => $each_info):
                                                if ($each_info->type == 'Cin') {
                                                    $received+=1;
                                                }
                                                if ($each_info->type == 'Cout') {
                                                    $receiveAble+=1;
                                                }


                                                if ($each_info->type == 'Out') {
                                                    $tqty += $each_info->quantity;
                                                    $trate += $each_info->rate;
                                                    $tprice += $each_info->rate * $each_info->quantity;
                                                    ?>
                                                    <tr>
                                                        <td class="center"><?php echo $j++; ?></td>
                                                        <td>
                                                            <?php
                                                            echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $productInfo = $this->Common_model->tableRow('product', 'product_id', $each_info->product_id);
                                                            echo $productInfo->productName;
                                                            echo ' [ ' . $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id)->brandName . ' ] ';
                                                            ?>
                                                        </td>
                                                        <td><?php echo $each_info->quantity; ?> </td>
                                                        <td align="right"><?php echo $each_info->rate; ?> </td>
                                                        <td align="right"><?php echo number_format($each_info->rate * $each_info->quantity, 2); ?> </td>
                                                    </tr>
                                                    <?php
                                                }
                                            endforeach;
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" align="right"><strong>Total</strong></td>
<!--                                                <td><?php echo $tqty; ?></td>
                                                <td align="right"><?php echo number_format($trate, 2); ?></td>-->
                                                <td align="right"><?php echo number_format($tprice, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="right"><strong>Discount ( - ) </strong></td>
                                                <td align="right"><?php echo number_format($saleslist->discount, 2); ?></td>
                                                <td align="right"><?php echo number_format($tprice - $saleslist->discount, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="right"><strong>VAT ( + )</strong></td>
                                                <td align="right"><?php echo round($saleslist->vat, 2) . ' '; ?>%</td>
                                                <td align="right"><?php echo number_format($saleslist->vatAmount, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" align="right"><strong>Loader </strong></td>
                                                <td align="right"><?php echo number_format($saleslist->loaderAmount, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" align="right"><strong>Transportation</strong></td>
                                                <td align="right"><?php echo number_format($saleslist->transportationAmount, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" align="right"><strong>Net Total</strong></td>
                                                <td align="right"><?php echo number_format($saleslist->debit, 2); ?></td>
                                            </tr>

                                            <tr>
                                                <td colspan="5" align="right"><strong>Payment</strong></td>
                                                <td align="right"><?php echo number_format($invoicePayment, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" align="right"><strong>Due Amount</strong></td>
                                                <td align="right"><?php echo number_format($saleslist->debit - $invoicePayment, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" >
                                                    <strong><span>In Words : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($saleslist->debit); ?></strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <span>Narration : &nbsp;</span> <?php echo $saleslist->narration; ?>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <?php
                                    if (!empty($receiveAble)):
                                        ?>
                                        <div class="table-header">
                                            Receivable Cylinder
                                        </div>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td class="center">#</td>
                                                    <td><strong>Product Category</strong></td>
                                                    <td><strong>Product</strong></td>
                                                    <td><strong>Quantity</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tqty = 0;
                                                $trate = 0;
                                                $tprice = 0;
                                                $j = 1;
                                                foreach ($stockList as $key1 => $each_info):
                                                    if ($each_info->type == 'Cout') {
                                                        $tqty += $each_info->quantity;
                                                        $trate += $each_info->rate;
                                                        $tprice += $each_info->rate * $each_info->quantity;
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $j++; ?></td>
                                                            <td>
                                                                <?php
                                                                echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $productInfo = $this->Common_model->tableRow('product', 'product_id', $each_info->product_id);
                                                                echo $productInfo->productName;
                                                                echo ' [ ' . $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id)->brandName . ' ] ';
                                                                ?>
                                                            </td>
                                                            <td align='right'><?php echo $each_info->quantity; ?> </td>
                                                        </tr>
                                                    <?php }endforeach; ?>
                                            </tbody>
                                        </table>
                                        <?php
                                    endif;

                                    if (!empty($received)):
                                        ?>
                                        <div class="table-header">
                                            Received Cylinder
                                        </div>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td class="center">#</td>
                                                    <td><strong>Product Category</strong></td>
                                                    <td><strong>Product</strong></td>
                                                    <td><strong>Quantity</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tqty = 0;
                                                $trate = 0;
                                                $tprice = 0;
                                                $j = 1;
                                                foreach ($stockList as $key2 => $each_info):
                                                    if ($each_info->type == 'Cin') {
                                                        $tqty += $each_info->quantity;
                                                        $trate += $each_info->rate;
                                                        $tprice += $each_info->rate * $each_info->quantity;
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $j++; ?></td>
                                                            <td>
                                                                <?php
                                                                echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $productInfo = $this->Common_model->tableRow('product', 'product_id', $each_info->product_id);
                                                                echo $productInfo->productName;
                                                                echo ' [ ' . $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id)->brandName . ' ] ';
                                                                ?>
                                                            </td>
                                                            <td align='right'><?php echo $each_info->quantity; ?> </td>
                                                        </tr>
                                                    <?php }endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>
                                <div class="hr hr8 hr-double hr-dotted"></div>
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <p>Prepared By:_____________<br />
                                            Date:____________________
                                        </p>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p>Approved By:________________<br />
                                            Date:_________________</p>
                                    </div>
                                </div>
                                <hr />
                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                                                ?></p>
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
    var url = baseUrl + "SalesController/getCustomerCurrentBalance";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            customerId: '<?php echo $saleslist->customer_id; ?>'
        },
        success: function (data)
        {
            $('.currentBalance').text(parseFloat(data).toFixed(2));


        }
    });

</script>
