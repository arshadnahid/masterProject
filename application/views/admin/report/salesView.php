
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Sales</a>
                </li>
                <li class="active">Sales Invoice</li>
            </ul>

            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('disSalesReport'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a>
            </span>
        </div>
        <br>

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
} else {
    echo "Check";
}
?></span>

                                <br />
                                <span class="invoice-info-label">Reference:</span>
                                <span class="blue"><?php echo $this->Common_model->tableRow('reference', 'reference_id', $saleslist->reference)->referenceName; ?></span>
                            </div>




                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Invoice ID:</span>
                                <span class="red"><?php echo $saleslist->voucher_no; ?></span>

                                <br />
                                <span class="invoice-info-label">Date:</span>
                                <span class="blue"><?php echo $saleslist->date ?></span>
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
<!--                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                                Company Info
                                            </div>
                                        </div>

                                        <div>
                                            <ul class="list-unstyled spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->dist_name; ?>
                                                </li>

                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->dist_email; ?>
                                                </li>

                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->dist_phone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->dist_address; ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> /.col -->

                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                                Customer Info
                                            </div>
                                        </div>

                                        <div>
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
                                    </div><!-- /.col -->
                                </div><!-- /.row -->

                                <div class="space"></div>

                                <div style="min-height:400px;" >
                                    <table class="table table-striped table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <td class="center">#</td>
                                                <td>Product Cat</td>
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

                                            foreach ($stockList as $key => $each_info):
                                                $tqty+=$each_info->quantity;
                                                $trate+=$each_info->rate;
                                                $tprice+=$each_info->rate * $each_info->quantity;
                                                ?>

                                                <tr>
                                                    <td class="center"><?php echo $key + 1; ?></td>
                                                    <td>
                                                        <?php
                                                        echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $this->Common_model->tableRow('product', 'product_id', $each_info->product_id)->productName;
                                                        ?>
                                                    </td>
                                                    <td><?php echo $each_info->quantity; ?> </td>
                                                    <td><?php echo $each_info->rate; ?> </td>
                                                    <td><?php echo number_format($each_info->rate * $each_info->quantity, 2); ?> </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" align="right">Sub-Total</td>
                                                <td><?php echo $tqty; ?></td>
                                                <td><?php echo number_format($trate, 2); ?></td>
                                                <td><?php echo number_format($tprice, 2); ?>/=</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="right">Discount ( - ) </td>
                                                <td><?php echo number_format($saleslist->discount, 2); ?></td>
                                                <td><?php echo number_format($tprice - $saleslist->discount, 2); ?>/=</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="right">VAT ( + )</td>
                                                <td><?php echo round($saleslist->vat, 2) . ' '; ?>%</td>
                                                <td><?php echo number_format($saleslist->vatAmount, 2); ?>/=</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" align="right">Net Total</td>

                                                <td><?php echo number_format($saleslist->debit, 2); ?>/=</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" >
                                                    <span>In Words : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($saleslist->debit); ?>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="6" >
                                                    <span>Narration : &nbsp;</span> <?php echo $saleslist->narration; ?>
                                                </td>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="hr hr8 hr-double hr-dotted"></div>


                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <p>Prepared By:_____________<br />
                                            Date:____________________
                                        </p>                        
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p>Checked By:_______________<br />
                                            Date:__________________</p>                       
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p>Approved By:________________<br />
                                            Date:_________________</p>  
                                    </div>
                                </div>

                                <hr />
                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                  ?></p>

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

