<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
        <base href="http://localhost/istiaq/pos/"/>
        <meta http-equiv="cache-control" content="max-age=0"/>
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>

        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
<!--        <link rel= "stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">-->
        <!-- jQuery 2.2.3 -->
<!--        <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>-->
        <script src="<?php echo base_url(); ?>assets/js/jquery-2.1.4.min.js"></script>
        <style type="text/css" media="all">
            body { color: #000; }
            #wrapper { max-width: 480px; margin: 0 auto; padding-top: 20px; }
            .btn { border-radius: 0; margin-bottom: 5px; }
            .bootbox .modal-footer { border-top: 0; text-align: center; }
            h3 { margin: 5px 0; }
            .order_barcodes img { float: none !important; margin-top: 5px; }
            @media print {
                .no-print { display: none; }
                #wrapper { max-width: 480px; width: 100%; min-width: 250px; margin: 0 auto; }
                .no-border { border: none !important; }
                .border-bottom { border-bottom: 1px solid #ddd !important; }
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <div id="receiptData">
                <div class="no-print"></div>
                <div id="receipt-data">
                    <div class="text-center">
                        <h3 style="text-transform:uppercase;"><?php echo $appConfig->companyName ?></h3>
                        <div class="col-sm-12" style="font-size:12px;"><?php echo $appConfig->address ?></div>
                        <div  class="col-sm-12" style="font-size:12px;"><?php echo $appConfig->phone ?></div>
                    </div>
                    <?php
                    //$user = $this->session->userdata('user');
                    //$admin_data = $this->COMMON_MODEL->get_single_data_by_single_column('tbl_pos_users', 'userID', $user);
                    //echo '<pre>';
                    //print_r($saleslist);
                    ?>
                    <p>Date: <?php echo date("d-m-Y",strtotime($saleslist[0]->date));    ?><br>Sale No/Ref: <?php echo $saleslist[0]->voucher_no;    ?><br>Sales Person: <?php echo  $saleslist[0]->salesPerson   ?> </p>
                    <div style="clear:both;"></div>
                    <table class="table table-striped table-condensed">
                        <tbody>
                            <?php $sl = 1;$productPrice=0; ?>
                            <?php
                            foreach ($saleslist as $product) {
                                if ($product->type == 'Out') {
                                    ?>
                                    <tr>
                                        <td colspan="2" class="no-border">
                                            #<?php echo $sl; ?>: &nbsp;&nbsp;<?php echo $product->productName . '[' . $product->title . ']'; ?><span class="pull-right">*NT</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="no-border border-bottom">
                                            <?php echo $product->quantity; ?> x <?php echo $product->price; ?>
                                        </td>
                                        <td class="no-border border-bottom text-right">
                                            <?php $productPrice=$productPrice+($product->quantity * $product->price);echo $product->quantity * $product->price; ?>
                                        </td>
                                    </tr>
                                    <?php $sl++; ?>
                                    <?php
                                }
                            }
                            ?>    
                        </tbody>
                        <tfoot>
                            <?php 
                            $vat=0;
                            if ($saleslist[0]->vat > 0) { ?>
                                <tr>
                                    <th>Vat:</th>
                                    <th class="text-right"><?php $vat=$saleslist[0]->vat;echo $saleslist[0]->vat; ?></th>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>Grand Total:</th>
                                <th class="text-right"><?php echo $productPrice+$vat;     ?></th>
                            </tr>
                            <?php $discount=0;if ($saleslist[0]->discount > 0) { ?>
                                <tr>
                                    <th>Discount:</th>
                                    <th class="text-right"><?php $discount=$saleslist[0]->discount;echo $saleslist[0]->discount; ?></th>
                                </tr>   
                            <?php } ?>

                            <?php if (!empty($sales[0]->CardNum)) { ?>

                                <?php
                                $cardCharge = $sales[0]->netTotal - $sales[0]->grandTotal;
                                if ($cardCharge > 0) {
                                    ?>
                                    <tr>
                                        <th>Card Charge( <?php echo $sales[0]->percentage . '%' ?> ):</th>
                                        <th class="text-right"><?php echo $sales[0]->netTotal - $sales[0]->grandTotal; ?></th>
                                    </tr>  
                                <?php } ?>

                            <?php } ?>
                            <tr>
                                <th>Net Total:</th>
                                <th class="text-right"><?php echo ($productPrice+$vat)-$discount;     ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <table class="table table-striped">
                        <tr>
                            <td style="text-align: left;">Paid by: <?php
                                    if ($saleslist[0]->payType == 1) {
                                        echo "Cash";
                                    } elseif ($saleslist[0]->payType == 2) {
                                        echo "Credit";
                                    } elseif ($saleslist[0]->payType == 3) {
                                        echo "Check";
                                    } else {
                                        echo "Cash";
                                    }
                                    ?></td>
                            <td style="text-align: center;">Pay Amount: <?php echo $saleslist[0]->debit; ?></td>
<!--                            <td style="text-align: right;">Change: <?php //echo $sales[0]->posChange; ?></td>-->
                        </tr>
                        <?php //if ($sales[0]->returnValue > 0) { ?>
<!--                            <tr>
                                <td colspan="3" style="text-align: center;">Return Amount: <?php echo $sales[0]->returnValue; ?></td>
                            </tr>-->
                        <?php //} ?>
                    </table>
                    <p class="text-center" style="font-size: 10px "> Thank you for shopping with us. Please come again</p>  
                </div>

                <div class="order_barcodes text-center">
                    <span style="font-size:8px;"> Software Developed By AEL</span>
                </div>
                <div style="clear:both;"></div>
            </div>

            <div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
                <hr>
                <span class="pull-right col-xs-12">
                    <button onclick="window.print();" class="btn btn-block btn-primary">Print</button>                
                </span>

                <span class="col-xs-12">
                    <a class="btn btn-block btn-success" href="<?php echo base_url('pos'); ?>">Back to POS</a>
                </span>
                <div style="clear:both;"></div>
                <div class="col-xs-12" style="background:#F5F5F5; padding:10px;">
                    <p style="font-weight:bold;">
                        Please don't forget to disble the header and footer in browser print settings.
                    </p>
                    <p style="text-transform: capitalize;">
                        <strong>FF:</strong> File &gt; Print Setup &gt; Margin &amp; Header/Footer Make all --blank--
                    </p>
                    <p style="text-transform: capitalize;">
                        <strong>chrome:</strong> Menu &gt; Print &gt; Disable Header/Footer in Option &amp; Set Margins to None
                    </p>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>

        <script type="text/javascript">

            $(window).load(function () {

                  //window.print();
                // return false;
            });


        </script>
    </body>
</html>
