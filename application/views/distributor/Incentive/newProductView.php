



<?php
//echo '<pre>';
//print_r($stockList);
//exit;
?>

<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">

            <div class="portlet-body">

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet green-meadow box" style="border: 1px solid #3598dc;">
                                    <div class="portlet-title" style="background-color: #3598dc;">
                                        <div class="caption">
                                            <?php echo get_phrase('Incentive Details') ?>  </div>

                                    </div>

                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <h3><?php echo get_phrase(' Company_Info') ?> </h3>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <?php echo get_phrase('Company_Name') ?>
                                                        : <?php echo $companyInfo->companyName; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Branch') ?>
                                                        : <?php echo $this->Common_model->tableRow('branch', 'branch_id', $purchasesList->branch_id)->branch_name; ; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Email') ?>
                                                        : <?php echo $companyInfo->email; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Phone') ?>
                                                        : <?php echo $companyInfo->phone; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Address') ?>
                                                        : <?php echo wordwrap($companyInfo->address, 40, "<br>\n"); ?>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-xs-6 invoice-payment">
                                                <h3><?php echo get_phrase('Invoice_Info') ?></h3>
                                                <ul class="list-unstyled ">
                                                    <li>
                                                        <?php echo get_phrase('Invoice') ?> : <span
                                                               ><?php  echo $invoice[0]->invoice_no?></span>
                                                    </li>
                                                     <li>
                                                        <?php echo get_phrase('From Date') ?> : <span
                                                               ><?php  echo $invoice[0]->from_date?></span>
                                                    </li>
                                                     <li>
                                                        <?php echo get_phrase('To Date') ?> : <span
                                                               ><?php  echo $invoice[0]->to_date?></span>
                                                    </li>



                                                </ul>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-striped" id="ALLPRODUCT">
                                        <thead>
                                        <tr>
                                            <td class="center">#</td>
                                            <td class="center"><strong ><?php echo get_phrase('Product') ?></strong></td>


                                            <td class="center"><strong><?php echo get_phrase('Brand') ?></strong></td>
                                            <td class="center"><strong><?php echo get_phrase('Quantity') ?></strong></td>



                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach ($invoice as $key => $value):
                                             ?>
                                            <tr>
                                                <td class="center"><?php echo $i = $i + 1; ?></td>
                                                 <td align="center"><?php echo $value->productName; ?> </td>
                                                 <td align="center"><?php echo $value->brandName; ?> </td>
                                                <td align="center"><?php echo $value->quantity; ?> </td>


                                            </tr>
                                            <?php
                                            //}
                                        endforeach; ?>
                                        </tbody>
                                        <tfoot>

                                        </tfoot>
                                    </table>

                                </div>
                                <div class="hr hr8 hr-double hr-dotted"></div>
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <p><?php echo get_phrase('Prepared_By') ?> :_____________<br/>
                                            <?php echo get_phrase('Date') ?> :____________________
                                        </p>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p><?php echo get_phrase('Approved By') ?> :________________<br/>
                                            <?php echo get_phrase('Date') ?> :_________________</p>
                                    </div>
                                </div>

                                 <div class="invoice-block pull-right">
                                                    <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                                       onclick="javascript:window.print();"> <?php echo get_phrase('Print') ?>
                                                        <i class="fa fa-print"></i>
                                                    </a>

                                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script type="text/javascript">
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
