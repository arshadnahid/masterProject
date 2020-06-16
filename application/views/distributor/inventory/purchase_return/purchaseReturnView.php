
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="widget-box transparent">


                        <div class="widget-header widget-header-large">
                            <h3 class="widget-title grey lighter">
                                <i class="ace-icon fa fa-file-invoice green"></i>
                                Sales Return Voucher
                            </h3>





                            <div class="widget-toolbar hidden-480"  class="hidden-xs">
                                <a  onclick="window.print();" style="cursor:pointer;">
                                    <i class="ace-icon fa fa-print"></i>
                                </a>
                            </div>



                        </div>

                        <div class="widget-body">
                            <div class="widget-main padding-24">





                                <div class="row"  >
                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-default arrowed-in arrowed-right">
                                                Company Info
                                            </div>
                                        </div>
                                        <div>
                                            <ul class="list-unstyled spaced">
                                                <li>
                                                    Name:<?php echo $companyInfo->companyName; ?>
                                                </li>
                                                <li>
                                                    Email: <?php echo $companyInfo->email; ?>
                                                </li>
                                                <li>
                                                    Phone: <?php echo $companyInfo->phone; ?>
                                                </li>
                                                <li>
                                                    Address: <?php echo $companyInfo->address; ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-default arrowed-in arrowed-right">
                                                Customer Info
                                            </div>
                                        </div>
                                        <div>
                                            <ul class="list-unstyled  spaced">
                                                <li>
                                                    Customer : <?php echo $saleReturnInfo->customerName; ?>
                                                </li>
                                                <li>
                                                    Date : <?php echo $saleReturnInfo->return_date; ?>
                                                </li>
                                                <li>
                                                    Invoice : <?php echo $saleReturnInfo->voucher_no; ?>
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
                                                <td><strong>Product</strong></td>
                                                <td class="text-right"><strong>Quantity</strong></td>
                                                <td class="text-right"><strong>Unit</strong></td>
                                                <td class="text-right"><strong>Unit Price</strong></td>
                                                <td class="text-right"><strong>Total Price</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tqty = 0;
                                            $trate = 0;
                                            $tprice = 0;
                                            $j = 1;
                                            foreach ($salesDetailsInfo as $key => $each_info):




                                                $tqty += $each_info->return_quantity;

                                                $tprice += $each_info->unit_price * $each_info->return_quantity;
                                                ?>
                                                <tr>
                                                    <td class="center"><?php echo $j++; ?></td>
                                                    <td><?php echo $each_info->productName; ?></td>
                                                    <td class="text-right"><?php echo $each_info->return_quantity; ?></td>
                                                    <td class="text-right"><?php echo $each_info->unit; ?></td>
                                                    <td class="text-right"><?php echo $each_info->quantity; ?></td>
                                                    <td class="text-right"><?php echo $each_info->price * $each_info->quantity; ?></td>


                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
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
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p>Approved By:________________<br />
                                            Date:_________________</p>
                                    </div>
                                </div>
                                <hr />



                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                                                                                                                                   ?></p>
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
