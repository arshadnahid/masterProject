<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Current Stock Report') ?> </div>

            </div>

            <div class="portlet-body">

                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm" action="" method="post" class="form-horizontal">

                            <div class="col-sm-12">
                                <div style="background-color: grey!important;">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                                Product</label>

                                            <div class="col-sm-9">

                                                <select name="productId" class="chosen-select form-control supplierid"
                                                        id="form-field-select-3" data-placeholder="Search by Product">


                                                    <option <?php

                                                    if ($productId == 'all') {

                                                        echo "selected";

                                                    }

                                                    ?> value="all">All
                                                    </option>


                                                    <?php

                                                    foreach ($productList as $eachInfo):


                                                        $brandInfo = $this->Common_model->tableRow('brand', 'brandId', $eachInfo->brand_id)->brandName;
                                                        $category = $this->Common_model->tableRow('productcategory', 'category_id', $eachInfo->category_id)->title;

                                                        ?>

                                                        <option <?php

                                                        if (!empty($productId) && $productId == $eachInfo->product_id) {

                                                            echo "selected";

                                                        }

                                                        ?> value="<?php echo $eachInfo->product_id; ?>"><?php echo $category . ' ' . $eachInfo->productName . ' [ ' . $brandInfo . ' ] '; ?></option>

                                                    <?php endforeach; ?>

                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                From Date</label>

                                            <div class="col-sm-8">

                                                <input type="text" class="date-picker form-control" id="start_date" name="start_date"
                                                       value="<?php

                                                       if (!empty($start_date)) {

                                                           echo $start_date;

                                                       } else {

                                                           echo date('d-m-Y');

                                                       }

                                                       ?>" data-date-format='dd-mm-yyyy'
                                                       placeholder="Start Date: dd-mm-yyyy"/>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                To Date</label>

                                            <div class="col-sm-8">

                                                <input type="text" class="date-picker form-control" id="end_date" name="end_date"
                                                       value="<?php

                                                       if (!empty($end_date)) {

                                                           echo $end_date;

                                                       } else {

                                                           echo date('d-m-Y');

                                                       }

                                                       ?>" data-date-format='dd-mm-yyyy'
                                                       placeholder="End Date:dd-mm-yyyy"/>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-group">

                                            <!--<label class="col-sm-2 control-label no-padding-right" for="form-field-1"></label>-->

                                            <div class="col-sm-6">

                                                <button type="submit" class="btn btn-success btn-sm">

                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                                                    Search

                                                </button>

                                            </div>

                                            <div class="col-sm-6">

                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="window.print();" style="cursor:pointer;">

                                                    <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>

                                                    Print

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="clearfix"></div>

                        </form>

                    </div>

                </div>
                <?php
                if (!empty($allStock)):

                    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to_date = date('Y-m-d', strtotime(date()));

                    ?>
                    <div class="row">
                        <div class="col-xs-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong> <?php echo get_phrase('Phone') ?>
                                            : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong> <?php echo get_phrase('Email') ?>
                                            : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong> <?php echo get_phrase('Website') ?>
                                            : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
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

                                    <td align="center"><strong><?php echo get_phrase('Sl') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Product Name') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Received Qty') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Sales Qty') ?></strong></td>


                                    <td align="center"><strong>Balance</strong></td>
                                    <td align="center"><strong>Price</strong></td>
                                    <td align="center"><strong>Total Price</strong></td>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $j = 1;
                                $i = 0;
                                $total = 0;
                                $tpurchase_qty = 0;
                                $tsales_qty = 0;
                                $tbalance = 0;
                                $purchase_price = 0;
                                foreach ($allStock as $key => $eachResult):
                                    $purchase_qty = $eachResult->tt_quantity;// - $eachResult->product_pur_return_quantity;
                                    $sales_qty = $eachResult->sales_qty;// - $eachResult->product_sales_return_quantity;
                                    $balance=$purchase_qty - $sales_qty;
                                    ?>

                                    <?php
                                    if ($sales_qty > 0 || $purchase_qty > 0) {

                                        $i=$i+1;

                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td ><?php echo $eachResult->productName; ?></td>
                                            <td align="right"> <?php echo $purchase_qty;$tpurchase_qty=$tpurchase_qty+ $purchase_qty?></td>
                                            <td align="right"> <?php echo $sales_qty;$tsales_qty=$tsales_qty+ $sales_qty?> </td>

                                            <td align="right"><?php echo $balance;$tbalance=$tbalance+$balance ?></td>
                                            <td align="right"><?php echo number_format($eachResult->avg_purchase_price, 2);$purchase_price=$purchase_price+$eachResult->avg_purchase_price ?></td>
                                            <td align="right"><?php

                                                echo number_format($eachResult->tt_price, 2);
                                                $total = $total + $eachResult->tt_price;
                                                ?></td>


                                        </tr>
                                        <?php
                                    }
                                endforeach;
                                ?>


                                <tr>
                                    <td colspan="2" style="text-align: right;">
                                        <strong><?php echo get_phrase('Total')?></strong></strong>
                                    </td>
                                    <td style="text-align: right;"><strong><?php echo number_format($tpurchase_qty, 2);?></strong></td>
                                    <td style="text-align: right;"><strong><?php echo number_format($tsales_qty, 2);?></strong></td>
                                    <td style="text-align: right;"><strong><?php echo number_format($tbalance, 2);?></strong></td>
                                    <td style="text-align: right;"><strong><?php //echo number_format($purchase_price, 2);?></strong></td>
                                    <td style="text-align: right;" colspan="">
                                        <strong><?php
                                        echo number_format($total, 2);
                                        ?></strong>
                                    </td>
                                </tr>


                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>