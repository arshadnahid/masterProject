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
        <link rel= "stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <!-- jQuery 2.2.3 -->
        <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <style type="text/css" media="all">
            body { color: #000; }
            #wrapper { max-width: 480px; margin: 0 auto; padding-top: 20px; }
            .btn { border-radius: 0; margin-bottom: 5px; }
            .bootbox .modal-footer { border-top: 0; text-align: center; }
            h3 { margin: 5px 0; }
            .order_barcodes img { float: none !important; margin-top: 5px; }
            @media print {
                .no-print { display: none; }
                #wrapper { max-width: 480px; width: 100%; min-width: 240px; margin: 0 auto; }
                .no-border { border: none !important; }
                .border-bottom { border-bottom: 1px solid #ddd !important; }
                table tr td{
                    font-size: 10px!important;
                }
                table tr th{
                    font-size: 10px!important;
                }
                 table tr th{
                    padding:0px;
                    margin: 0px;
                }
                table tr td{
                    padding:0px;
                    margin: 0px;
                }
                
            }
        </style>
    </head>

    <body>
        <?php
        $warehouse_list = $this->COMMON_MODEL->get_single_data_by_single_column('tbl_pos_warehouses', 'warehouseID', $this->defaults['warehouseID']);
        ?>
        <div id="wrapper">
            <div id="receiptData">
                <div class="no-print"></div>
                <div id="receipt-data">
                    <div class="text-center" >
                        <h3 style="text-transform:uppercase;">Hijabbook</h3>
                        <div class="col-sm-12"  style="font-size:10px !important;"><?php echo $warehouse_list['warehouseAddress']; ?></div><br>
                    </div>
                    <?php
                    $user = $this->session->userdata('user');
                    $admin_data = $this->COMMON_MODEL->get_single_data_by_single_column('tbl_pos_users', 'userID', $user);
                    ?>
                    <p style="font-size:10px !important;">Date: <?php echo $sales[0]->salesDate; ?><br>Sale No/Ref: <?php echo $sales[0]->invoiceNo; ?><br>Sales Person: <?php echo $admin_data['username'] ?> </p>
                    <div style="clear:both;"></div>
                    <table class="table table-striped table-condensed content_table">

                        <tr>
                            <td class="no-border border-bottom">
                                Product
                            </td>
                            <td class="no-border border-bottom text-right">
                                Amount
                            </td>
                        </tr>

                        <?php $sl = 1; ?>
                        <?php foreach ($sales[0]->products as $product) { ?>
                            <tr>
                                <td colspan="2" class="no-border">
                                    #<?php echo $sl; ?>: &nbsp;&nbsp;<?php echo $product->productName; ?><span class="pull-right">*NT</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border border-bottom">
                                    <?php echo $product->quantity; ?> x <?php echo $product->price; ?>
                                </td>
                                <td style="margin-top:-5px !important;" class="no-border border-bottom text-right">
                                    <?php echo $product->quantity * $product->price; ?>
                                </td>
                            </tr>
                            <?php $sl++; ?>
                        <?php } ?>    


                        <?php if ($sales[0]->vat > 0) { ?>
                            <tr>
                                <td class="no-border text-right"><b>Vat </b></td>
                                <td class="no-border text-right"><b><?php echo $sales[0]->vat; ?></b></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="no-border text-right"><b>Grand Total </b></td>
                            <td class="no-border text-right"><b><?php echo $sales[0]->grandTotal; ?></b></td>
                        </tr>
                        <?php if ($sales[0]->discount > 0) { ?>
                            <tr>
                                <td class="no-border text-right"><b>Discount(-) </b></td>
                                <td class="no-border text-right"><b><?php echo $sales[0]->discount; ?></b></td>
                            </tr>   
                        <?php } ?>

                        <?php if (!empty($sales[0]->CardNum)) { ?>

                            <?php
                            if (!empty($sales[0]->discount)) {
                                $cardCharge = $sales[0]->grandTotal - $sales[0]->discount;
                            } else {
                                $cardCharge = $sales[0]->grandTotal;
                            }
                            $total_perc = ($sales[0]->percentage / 100) * $cardCharge;
                            if ($sales[0]->percentage > 0) {
                                ?>
                                <tr>
                                    <td class="no-border text-right"><b>Card Charge(+)( <?php echo $sales[0]->percentage . '%' ?> ) </b></td>
                                    <td class="no-border text-right"><b><?php echo $total_perc; ?></b></td>
                                </tr>  
                            <?php } ?>

                        <?php } ?>

                        <?php if ($sales[0]->returnCharge > 0) { ?>
                            <tr>
                                <td class="no-border text-right"  style="color:red;"><b>Return Charge(+)  </b></td>
                                <td class="no-border text-right"><b><?php echo $sales[0]->returnCharge; ?></b></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="no-border text-right"><b>Net Total </b></td>
                            <td class="no-border text-right"><b><?php
                                    if (!empty($sales[0]->returnCharge)) {
                                        echo $sales[0]->netTotal + $sales[0]->returnCharge;
                                    } else {
                                        echo $sales[0]->netTotal;
                                    }
                                    ?></b></td>
                        </tr>
                    </table>
                    <table class="table table-striped">
                        <tr>
                            <td style="text-align: left;">Paid by  <?php
                                if (!empty($sales[0]->CardNum)) {
                                    echo "Card <span style='font-size:10px;'>[" . $sales[0]->CardNum . "]</span>";
                                } else {
                                    echo 'Cash';
                                }
                                ?></td>
                            <td style="text-align: center;">Pay Amount  <?php echo $sales[0]->posPaying; ?></td>
                            <td style="text-align: right;">Change  <?php echo $sales[0]->posChange; ?></td>
                        </tr>
                        <?php if ($sales[0]->returnValue > 0) { ?>
                            <tr>
                                <td colspan="3" style="text-align: center;">Return Amount  <?php echo $sales[0]->returnValue; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <p class="text-center" style="font-size: 10px "> Thank you for shopping with us. Please come again</p>  
                </div>

                <div class="order_barcodes text-center">
                    <span style="font-size:8px;"> Software Developed By ABH World</span>
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
                window.print();
                return false;
            });

        </script>
    </body>
</html>
