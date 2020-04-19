<?php
//echo '<pre>';
//print_r($stockList);
//exit;
?>

<!-- END PAGE BAR -->
<!-- END PAGE HEADER-->
<style>
@media print {
      .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
      .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
           float: left;               
      }
	  .container {
    width: auto;
  }

      .col-sm-12 {
           width: 100%;
      }

      .col-sm-11 {
           width: 91.66666666666666%;
      }

      .col-sm-10 {
           width: 83.33333333333334%;
      }

      .col-sm-9 {
            width: 75%;
      }

      .col-sm-8 {
            width: 66.66666666666666%;
      }

       .col-sm-7 {
            width: 58.333333333333336%;
       }

       .col-sm-6 {
            width: 50%;
       }

       .col-sm-5 {
            width: 41.66666666666667%;
       }

       .col-sm-4 {
            width: 33.33333333333333%;
       }

       .col-sm-3 {
            width: 25%;
       }

       .col-sm-2 {
              width: 16.666666666666664%;
       }

       .col-sm-1 {
              width: 8.333333333333332%;
        }            
}
.table > tfoot > tr > td, .table > tfoot > tr > th {
    border-bottom: 0;
    padding: 2px 18px;
    border-top: 1px solid 
    #e7ecf1;
    font-weight: 600;
}
.table > thead > tr > td, .table > thead > tr > td {
    border-bottom: 0;
    padding: 2px 18px;
    border-top: 1px solid 
    #e7ecf1;
    
}
.table > tbody > tr > td, .table > tbody > tr > td {
    border-bottom: 0;
    padding: 0px;
    border-top: 1px solid 
    #e7ecf1;
    
}
</style>
<div class="page-content">
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="row">
            <div style="width:5%!important;" class="noPrint"></div>
            <div style="width: 70%!important;">

                <!-- PAGE CONTENT BEGINS -->
                <div class="space-6"></div>

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="widget-box transparent" style="margin-left: 5px;">
                            <div class="widget-body">
                               <div class="row">
                            <div class="col-md-12 col-sm-12">
							<div class="col-xs-3 col-md-3">
							<img src="<?php echo base_url('assets/images/farabi.png'); ?>" style="height:70px ;width:80px" data-holder-rendered="true">
							 </div>
							 <div class="col-xs-6 col-md-6 text-center">
							<p style="font-size:13px;margin:3px 0;"><strong>ক্যাশ মেমো </strong></p>
                            <p style="font-size:22px;margin:3px 0;"><strong> মেসার্স ফারাবি ট্রেডার্স</strong></p>
						    <p style="font-size:17px;margin:3px 0;"><strong> প্রো সুজন মাহমুদ </strong></p>
							<p style="font-size:13px;margin:3px 0;"><strong>  রড, সিমেন্ট, গ্যাস, ইট, বালি ও সিলেকশন বালি </strong></p>
							<p style="font-size:13px;margin:3px 0;"><strong>  তার, পলিথিন ইত্যাদি সুলভ মূল্যে বিক্রয় করা হয়  ।</strong></p>
							<p style="font-size:13px;margin:3px 0;"><strong>মোবাইলঃ ০১৭১১-৭০১৬২০, ০১৭১১-৫৭৯৫৫৬, ০১৯২৫-৩১৩১৩১</strong></p>
							 </div>
							 <div class="col-xs-3 col-md-3">
							
							 </div>
						     </div>
							  
							  </div>

                                <div>
                                    <table class="table" style="border:none;margin-top: -50px;">
                                        <tbody style="border:none">
                                            <tr style="border:none">
                                                <td style="border:none;line-height: 10px;"> 
                                                    <div class="btn-group btn-group-devided" data-toggle="buttons">
													<label><?php echo get_phrase('Name') ?> :<span
                                    style="color:red; padding-right:5px"><?php echo $customerInfo->customerID . '[' . $customerInfo->customerName . ']' ?></span></label>
                        
						<label><?php echo get_phrase('Email') ?>: <span
                                    style="color:red; padding-right:5px"><?php
                                                        if (!empty($customerInfo->customerEmail)) {
                                                            echo $customerInfo->customerEmail;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?></span></label>
														<label><?php echo get_phrase('Phone') ?>: <span
                                    style="color:red; padding-right:5px"><?php
                                                        if (!empty($customerInfo->customerPhone)) {
                                                            echo $customerInfo->customerPhone;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?></span></label>
						<label><?php echo get_phrase('Invoice_ID') ?>: <span
                                    style="color:red; padding-right:5px"><?php echo $saleslist->invoice_no; ?></span></label>
                        <label><span><?php echo get_phrase('Payment Type') ?>:<span style="color:red;padding-right:5px">

                                    <span class="red"><?php
                                        if ($saleslist->payment_type == 1) {
                                            echo get_phrase("Cash");
                                        } elseif ($saleslist->payment_type == 2) {
                                            echo get_phrase("Credit");
                                        } elseif ($saleslist->payment_type == 3) {
                                            echo get_phrase("Cheque_DD_PO");
                                        } else {
                                            echo get_phrase("Cash");
                                        }
                                        ?></span>


                                </span></label>
                        <label>
                            <span><?php echo get_phrase('Date') ?>:
                                <?php echo date('d-m-Y', strtotime($saleslist->invoice_date)) ?>
                            </span>
                        </label>

                        <label> </label>
                    </div>
                                                    

                                                </td>

                                                
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>                 
                             <div class="row" style="margin-top: -15px;">
                            <div class="col-md-12 col-sm-12">
                                
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <td class="center">#</td>
                                            <td><?php echo get_phrase('Product') . ' ' . get_phrase('Category') ?> </td>
                                            <td><?php echo get_phrase('Product') ?></td>
                                            <td><?php echo get_phrase('Recive Product') ?></td>
                                            <td><?php echo get_phrase('Quantity Recived') ?></td>
                                            <td style="text-align: right"><?php echo get_phrase('Quantity') ?></td>
                                            <td style="text-align: right"><?php echo get_phrase('Unit Price') ?> </td>
                                            <td style="text-align: right"><?php echo get_phrase('Total Price') ?></td>
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
                                                    <table class="table table-bordered">
                                                        <?php
                                                        foreach ($each_info['return'] as $key1 => $value1) {
                                                            foreach ($value1 as $key2 => $value2) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $value2['return_product_cat'] . ' ' . $value2['return_product_name'] . ' ' . $value2['return_product_unit'] . '[ ' . $value2['return_product_brand'] . ' ]' ?></td>
                                                                    <td><?php echo $value2['returnable_quantity'] ?></td>
                                                                </tr>


                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </table>
                                                </td>

                                                <td align="right"><?php echo $each_info['quantity']; ?> </td>
                                                <td align="right"><?php echo $each_info['unit_price']; ?> </td>
                                                <td align="right"><?php echo number_format($each_info['unit_price'] * $each_info['quantity'], 2); ?> </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="7" align="right">
                                                <strong><?php echo get_phrase('Sub_Total') ?></strong></td>

                                            <td align="right"><?php echo number_format($tprice, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right"><strong><?php echo get_phrase('Discount') ?> (
                                                    - ) </strong></td>

                                            <td align="right"><?php echo number_format($saleslist->discount_amount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right">
                                                <strong><?php echo get_phrase('Vat') ?> <?php //echo round($saleslist->vat, 2) . ' ';       ?>
                                                    %( +
                                                    )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->vat_amount, 2); ?></td>

                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right"><strong><?php echo get_phrase('Loader') ?> ( +
                                                    )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->loader_charge, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right">
                                                <strong><?php echo get_phrase('Transportation') ?> ( + )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->transport_charge, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right">
                                                <strong><?php echo get_phrase('Net Total') ?></strong></td>
                                            <td align="right"><?php
                                                $NetTotal = $tprice + $saleslist->transport_charge + $saleslist->loader_charge + $saleslist->vat_amount;
                                                echo number_format($NetTotal, 2);
                                                ?></td>
                                        </tr>

                                        <tr>
                                            <td colspan="7" align="right"><strong><?php echo get_phrase('Payment') ?> (
                                                    - )</strong></td>
                                            <td align="right"><?php echo number_format($saleslist->paid_amount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" align="right">
                                                <strong><?php echo get_phrase('Due Amount') ?></strong></td>
                                            <td align="right"><?php echo number_format($NetTotal - $saleslist->paid_amount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <strong><span><?php echo get_phrase('In_Words') ?>
                                                        : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($NetTotal); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <span><?php echo get_phrase('Narration') ?>
                                                    : &nbsp;</span> <?php echo $saleslist->narration; ?>
                                                <div class="invoice-block pull-right">
                                                    <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                                       onclick="javascript:window.print();"> Print
                                                        <i class="fa fa-print"></i>
                                                    </a>

                                                </div>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
    
                                
                            </div>
                        </div>
                                </div>
								  <div class="hr hr8 hr-double hr-dotted"></div>

								<div class="col-xs-12 col-md-12">
                                <table class="table" style="border:none; margin-top: -20px;">
                                    <tbody style="border:none">
                                        <tr style="border:none">
                                            <td style="border:none;"> 
                                                <p style="left:0px"><strong> ক্রেতার স্বাক্ষর :_____________</strong></p>
                                                

                                            </td>
                                             <td style="border:none;"> 
                                                <p style="left:0px"><strong>farabitraders3@gmail.com</strong></p>
                                                
                                            </td>
                                            <td style="border:none; text-align:right;">
                                                <p style="left:0px"><strong> বিক্রেতার স্বাক্ষর:________________</strong></p>
                                                

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                              </div>
                                                                               
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width:25%!important;" class="noPrint"></div>
        </div>
    </div>
</div>
 


<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script>
    var ajaxUrl = baseUrl + "lpg/SalesController/getCustomerCurrentBalance2";
    //alert(ajaxUrl);
    $.ajax({
        type: 'POST',
        url: ajaxUrl,
        data: {
            customerId: '<?php echo $saleslist->customer_id; ?>'
        },
        success: function (data) {
            alert('OK');
            $('.currentBalance').text(parseFloat(data).toFixed(2));


        }
    });

</script>
