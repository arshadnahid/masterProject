<?php //echo '<pre>';
//print_r($stockList);
//exit;
?>


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
                <li class="active saleAddPermission"><a href="<?php echo site_url('salesInvoice_add'); ?>">
                        <i class="ace-icon 	fa fa-plus"></i> Add
                    </a>
                </li>
                <li>
                    <a class="saleEditPermission"
                       href="<?php echo site_url('salesInvoice_edit/' . $saleslist->sales_invoice_id); ?>">
                        <i class="ace-icon 	fa fa-pencil bigger-130"></i> Edit
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
                                <!-- <i class="ace-icon fa fa-leaf green"></i>-->
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
                                    if ($saleslist->payment_type == 1) {
                                        echo "Cash";
                                    } elseif ($saleslist->payment_type == 2) {
                                        echo "Credit";
                                    } elseif ($saleslist->payment_type == 3) {
                                        echo "Check";
                                    } else {
                                        echo "Cash";
                                    }
                                    ?></span>
                                <br/>
                                <?php if (!empty($saleslist->reference)): ?>
                                    <span class="invoice-info-label">Reference:</span>
                                    <span class="blue"><?php echo $this->Common_model->tableRow('reference', 'reference_id', $saleslist->reference)->referenceName; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Invoice ID:</span>
                                <span class="red"><?php echo $saleslist->invoice_no; ?>
                                    / <?php echo $saleslist->sales_invoice_id ?></span>
                                <br/>
                                <span class="invoice-info-label">Date:</span>
                                <span class="blue"><?php echo date('d-m-Y', strtotime($saleslist->invoice_date)) ?></span>
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
                                                    <i class="ace-icon fa fa-caret-right blue"></i>Name:<?php echo $companyInfo->companyName; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>Email: <?php echo $companyInfo->email; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>Phone: <?php echo $companyInfo->phone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>Address: <?php echo $companyInfo->address; ?>
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
                                            <ul class="list-unstyled  spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Name: <?php echo $customerInfo->customerID . '[' . $customerInfo->customerName . ']' ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Email: <?php
                                                    if (!empty($customerInfo->customerEmail)) {
                                                        echo $customerInfo->customerEmail;
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Phone: <?php
                                                    if (!empty($customerInfo->customerPhone)) {
                                                        echo $customerInfo->customerPhone;
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>Address: <?php
                                                    if (!empty($customerInfo->customerAddress)) {
                                                        echo $customerInfo->customerAddress;
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
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
                                                    <i class="ace-icon fa fa-caret-right green"></i> Current Due: <span
                                                            class="currentBalance"></span>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i>
                                                    Transportation: <?php
                                                    if ($saleslist->transport_charge > 0):
                                                        $transporation = $this->Common_model->tableRow('vehicle', 'id', $saleslist->tran_vehicle_id);
                                                        echo $transporation->vehicleName . ' [ ' . $transporation->vehicleModel . ' ] ';
                                                    else:
                                                        echo "N/A";
                                                    endif;
                                                    ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i> Loader:

                                                    <?php
                                                    if (($saleslist->loader_charge) > 0):

                                                        $loaderInfo = $this->Common_model->tableRow('employee', 'id', $saleslist->loader_emp_id);
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
                                <div style="min-height:400px;">
                                    <table class="table table-striped table-responsive table-bordered">
                                        <thead>
                                        <tr>
                                            <td class="center">#</td>
                                            <td>Product Category</td>
                                            <td>Product</td>
                                            <td>Recive Product</td>
                                            <td>Quantity Recived</td>
                                            <td style="text-align: right">Quantity</td>
                                            <td style="text-align: right">Unit Price g</td>
                                            <td style="text-align: right">Total Price</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $tqty = 0;
                                        $trate = 0;
                                        $tprice = 0;
                                        $j = 1;
                                        foreach ($stockList[$saleslist->sales_invoice_id] as $key => $each_info):

                                            $tqty += $each_info['quantity'];
                                            $trate += $each_info['unit_price'];
                                            $tprice += $each_info['unit_price'] * $each_info['quantity'];
                                            ?>
                                            <tr>
                                                <td class="center"><?php echo $j++; ?></td>
                                                <td>
                                                    <?php
                                                    echo $each_info['title'];
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo $each_info['productName'] . '' . $each_info['unitTtile'] . '[' . $each_info['brandName'] . ']';


                                                    ?>
                                                </td>
                                                <td colspan="2">
                                                    <table class="table table-border">
                                                        <?php
                                                        foreach ($each_info['return'] as $key1 => $value1) {
                                                            foreach ($value1 as $key2 => $value2) {

                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $value2['return_product_cat'] . ' ' . $value2['return_product_name'] . ' ' . $value2['return_product_unit'] . '[ ' . $value2['return_product_brand'] . ' ]' ?></td>
                                                                    <td><?php echo $value2['returnable_quantity'] ?></td>
                                                                </tr>


                                                            <?php }
                                                        } ?>
                                                    </table>
                                                </td>

                                                <td align="right"><?php echo $each_info['quantity']; ?> </td>
                                                <td align="right"><?php echo $each_info['unit_price']; ?> </td>
                                                <td align="right"><?php echo number_format($each_info['unit_price'] * $each_info['quantity'], 2); ?> </td>
                                            </tr>
                                            <?php


                                        endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="7" align="right"><strong>Sub - Total</strong></td>

                                            <td align="right"><?php echo number_format($tprice, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right"><strong>Discount ( - ) </strong></td>

                                            <td align="right"><?php echo number_format($saleslist->discount_amount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right">
                                                <strong>VAT <?php //echo round($saleslist->vat, 2) . ' '; ?>%( +
                                                    )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->vat_amount, 2); ?></td>

                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right"><strong>Loader ( + )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->loader_charge, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right"><strong>Transportation ( + )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->transport_charge, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right"><strong>Net Total</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->paid_amount, 2); ?></td>
                                        </tr>

                                        <tr>
                                            <td colspan="7" align="right"><strong>Payment ( - )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->paid_amount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right"><strong>Due Amount</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->invoice_amount - $saleslist->paid_amount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <strong><span>In Words : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($saleslist->invoice_amount); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <span>Narration : &nbsp;</span> <?php echo $saleslist->narration; ?>
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
                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                                                                         ?></p>
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
        success: function (data) {
            $('.currentBalance').text(parseFloat(data).toFixed(2));


        }
    });

</script>
