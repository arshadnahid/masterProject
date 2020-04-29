<?php
$property_1=get_property_list_for_show_hide(1);
$property_2=get_property_list_for_show_hide(2);
$property_3=get_property_list_for_show_hide(3);
$property_4=get_property_list_for_show_hide(4);
$property_5=get_property_list_for_show_hide(5);

?>

<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">

                    <!--<span class="caption-subject font-dark sbold uppercase"><?php /*echo get_phrase('Voucher_Id') */?></span>-->
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                        <label><?php echo get_phrase('Purchases_Voucher') ?> : <span
                                    style="color:red; padding-right:5px"><?php echo $purchasesList->invoice_no; ?></span></label>
                        <label><span><?php echo get_phrase(' Payment_Type') ?> :</span>
                            <span style="color:red;padding-right:5px"><?php
                                if ($purchasesList->payment_type == 1) {
                                    echo get_phrase("Cash");
                                } elseif ($purchasesList->payment_type == 4) {
                                    echo get_phrase("Cash");
                                } elseif ($purchasesList->payment_type == 3) {
                                    echo get_phrase("Cheque");
                                } else {
                                    echo get_phrase("Credit");
                                }
                                ?></span></label>
                        <label><span><?php echo get_phrase(' Date') ?> :</span><span
                                    class="red"><?php echo date('d-m-Y', strtotime($purchasesList->invoice_date)); ?></span></label>

                    </div>

                </div>
            </div>
            <div class="portlet-body">

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet green-meadow box" style="border: 1px solid #3598dc;">
                                    <div class="portlet-title" style="background-color: #3598dc;">
                                        <div class="caption">
                                            <?php echo get_phrase(' Invoice_Info') ?>  </div>

                                    </div>

                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-xs-4">
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
                                            <div class="col-xs-4">
                                                <h3><?php echo get_phrase('Supplier_Info') ?></h3>
                                                <ul class="list-unstyled  spaced">
                                                    <li>
                                                        <?php echo get_phrase('Name') ?>
                                                        : <?php echo $supplierInfo->supID . '[' . $supplierInfo->supName . ']' ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Email') ?>
                                                        : <?php echo $supplierInfo->supEmail; ?>
                                                    </li>


                                                    <li>
                                                        <i></i><?php echo get_phrase('Phone') ?>
                                                        : <?php echo $supplierInfo->supPhone; ?>: <?php
                                                        if (!empty($supplierInfo->supPhone)) {
                                                            echo $supplierInfo->supPhone;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>

                                                    <li>
                                                        <i></i><?php echo get_phrase('Address') ?>
                                                        :<?php echo $supplierInfo->supAddress; ?> <?php
                                                        if (!empty($supplierInfo->supAddress)) {
                                                            echo $$supplierInfo->supAddress;
                                                        } else {
                                                            echo get_phrase("N_A");
                                                        }
                                                        ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-4 invoice-payment">
                                                <h3><?php echo get_phrase('Invoice_Info') ?></h3>
                                                <ul class="list-unstyled ">
                                                    <li>
                                                        <?php echo get_phrase('Current_Due') ?> : <span
                                                                id="customerCurrentDue"><?php echo numberFromatfloat($supplier_due)?></span>
                                                    </li>
                                                    <li>

                                                        <?php echo get_phrase('Transportation') ?>: <?php
                                                        // if (!empty($purchasesList->transportation)):
                                                        if ($purchasesList->transport_charge > 0):
                                                            $transporation = $this->Common_model->tableRow('vehicle', 'id', $purchasesList->tran_vehicle_id);

                                                            echo $transporation->vehicleName . ' [ ' . $transporation->vehicleModel . ' ] ';
                                                        // echo $transporation->transport_charge ;
                                                        else:
                                                            echo get_phrase("N_A");
                                                        endif;
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Loader') ?>:

                                                        <?php
                                                        if (($purchasesList->loader_charge) > 0):
                                                            //if (!empty($purchasesList->loader)):
                                                            $loaderInfo = $this->Common_model->tableRow('employee', 'id', $purchasesList->loader_emp_id);
                                                            echo $loaderInfo->personalMobile . ' [ ' . $loaderInfo->name . ' ] ';
                                                        //echo $loaderInfo->loader_charge;
                                                        else:
                                                            echo get_phrase("N_A");
                                                        endif;
                                                        ?>
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
                                            <td><strong><?php echo get_phrase('Product') ?></strong></td>


                                            <th nowrap  style="text-align: center;width:17%;border-radius:10px;<?php echo $property_1 =='dont_have_this_property'?'display: none':''?>">
                                                <strong><?php echo $property_1; ?> </strong>
                                            </th>
                                            <th nowrap  style="text-align: center;width:10%;border-radius:10px;<?php echo $property_2=='dont_have_this_property'?'display: none':''?> ">
                                                <strong><?php echo $property_2; ?> </strong>

                                            </th>
                                            <th nowrap  style="text-align: center;width:10%;border-radius:10px; <?php echo $property_3=='dont_have_this_property'?'display: none':''?>">
                                                <strong><?php echo $property_3; ?> </strong>
                                            </th>
                                            <th nowrap  style="text-align: center;width:10%;border-radius:10px; <?php echo $property_4=='dont_have_this_property'?'display: none':''?>">
                                                <strong><?php echo $property_4; ?> </strong>
                                            </th>
                                            <th nowrap  style="text-align: center;width:10%;border-radius:10px;<?php echo $property_5=='dont_have_this_property'?'display: none':''?>">
                                                <strong><?php echo $property_5; ?> </strong>
                                            </th>
                                            <td class="text-right"><strong><?php echo get_phrase('Quantity') ?></strong>
                                            </td>
                                            <td class="text-right">
                                                <strong><?php echo get_phrase('Unit_Price') ?></strong></td>
                                            <td class="text-right">
                                                <strong><?php echo get_phrase('Total_Price') ?></strong></td>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 0;
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
                                                <td class="center"><?php echo $i = $i + 1; ?></td>
                                                <td>
                                                    <?php
                                                    echo $each_info['title'] . ' ' . $each_info['productName'] . ' ' . $each_info['unitTtile'] . ' [ ' . $each_info['brandName'] . ' ] ';
                                                    //echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                    ?>
                                                </td>
                                                <th nowrap  style="text-align: center;width:17%;border-radius:10px;<?php echo $property_1 =='dont_have_this_property'?'display: none':''?>">
                                                    <strong><?php echo  $each_info['property_1']; ?> </strong>
                                                </th>
                                                <th nowrap  style="text-align: center;width:10%;border-radius:10px;<?php echo $property_2=='dont_have_this_property'?'display: none':''?> ">
                                                    <strong><?php echo  $each_info['property_2']; ?> </strong>

                                                </th>
                                                <th nowrap  style="text-align: center;width:10%;border-radius:10px; <?php echo $property_3=='dont_have_this_property'?'display: none':''?>">
                                                    <strong><?php echo  $each_info['property_3']; ?> </strong>
                                                </th>
                                                <th nowrap  style="text-align: center;width:10%;border-radius:10px; <?php echo $property_4=='dont_have_this_property'?'display: none':''?>">
                                                    <strong><?php echo  $each_info['property_4']; ?> </strong>
                                                </th>
                                                <th nowrap  style="text-align: center;width:10%;border-radius:10px;<?php echo $property_5=='dont_have_this_property'?'display: none':''?>">
                                                    <strong><?php echo $each_info['property_5'];; ?> </strong>
                                                </th>

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
                                            <td colspan="6" align="right">
                                                <strong><?php echo get_phrase('Sub_Total') ?></strong></td>
                                            <td align='right'><?php echo number_format((float)$tprice, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><strong><?php echo get_phrase('Discount') ?> (
                                                    - )</strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->discount_amount, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><strong><?php echo get_phrase('Loader') ?> ( +
                                                    )</strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->loader_charge, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right">
                                                <strong><?php echo get_phrase('Transportation') ?> ( + )</strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->transport_charge, 2, '.', ','); ?></td>
                                        </tr>

                                        <?php
                                        $netAmount = ($tprice + $purchasesList->transport_charge + $purchasesList->loader_charge) - $purchasesList->discount_amount;
                                        ?>
                                        <tr>
                                            <td colspan="6" align="right">
                                                <strong><?php echo get_phrase('Net_Total') ?></strong></td>
                                            <td align='right'><?php echo number_format((float)$netAmount, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right">
                                                <strong><?php echo get_phrase('Payment') ?></strong></td>
                                            <td align='right'><?php echo number_format((float)$purchasesList->paid_amount, 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right">
                                                <strong><?php echo get_phrase('Due_Amount') ?></strong></td>
                                            <td align='right'><?php echo  number_format((float)$netAmount - $purchasesList->paid_amount, 2, '.', ',');// echo  banglaNumber(number_format((float)$netAmount - $purchasesList->paid_amount, 2, '.', ',')); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <strong>
                                                    <span>In Words : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($netAmount); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <span><?php echo get_phrase('Narration') ?>
                                                    : &nbsp;</span> <?php echo $purchasesList->narration; ?>
                                                <div class="invoice-block pull-right">
                                                    <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                                       onclick="javascript:window.print();"> <?php echo get_phrase('Print') ?>
                                                        <i class="fa fa-print"></i>
                                                    </a>

                                                </div>
                                            </td>

                                        </tr>
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
