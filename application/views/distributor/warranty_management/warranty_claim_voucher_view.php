<?php
$property_1=get_property_list_for_show_hide(1);
$property_2=get_property_list_for_show_hide(2);
$property_3=get_property_list_for_show_hide(3);
$property_4=get_property_list_for_show_hide(4);
$property_5=get_property_list_for_show_hide(5);

?>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">

                    <span class="caption-subject font-dark sbold uppercase"> <?php echo get_phrase('Sales Invoice') ?>

                    </span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided" data-toggle="buttons">
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
                                            <?php echo get_phrase('Invoice Info') ?>
                                        </div>

                                    </div>

                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <h3><?php echo get_phrase('Company Info') ?></h3>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <?php echo get_phrase('Name') ?>
                                                        :<?php echo $companyInfo->companyName; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Branch') ?>
                                                        : <?php echo $this->Common_model->tableRow('branch', 'branch_id', $saleslist->branch_id)->branch_name;; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Email') ?>
                                                        : <?php echo $companyInfo->email; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Phone') ?>
                                                        : <?php echo $companyInfo->phone; ?>
                                                    </li>
                                                    <!--<li>
                                                        Account Name: FoodMaster Ltd
                                                    </li>-->
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
                                                <h3><?php echo get_phrase('Invoice Info') ?></h3>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <?php
                                                        if($customerDue->totalbalance<0){
                                                        ?>
                                                        <?php echo get_phrase('Advance') ?>: <span
                                                                class="currentBalance">
                                                            <?php
                                                            echo numberFromatfloat((-1*$customerDue->totalbalance));
                                                            ?>
                                                        </span>
                                                        <?php }else{ ?>
                                                            <?php echo get_phrase('Current Due') ?>: <span
                                                                    class="currentBalance">
                                                            <?php
                                                            echo numberFromatfloat($customerDue->totalbalance);
                                                            ?>
                                                        </span>
                                                        <?php }?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Transportation') ?>: <?php
                                                        if (!empty($saleslist->transportation)):
                                                            $transporation = $this->Common_model->tableRow('vehicle', 'id', $saleslist->transportation);
                                                            echo $transporation->vehicleName . ' [ ' . $transporation->vehicleModel . ' ] ';
                                                        else:
                                                            echo get_phrase("N_A");
                                                        endif;
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php echo get_phrase('Loader') ?>:

                                                        <?php
                                                        if (!empty($saleslist->loader)):
                                                            $loaderInfo = $this->Common_model->tableRow('employee', 'id', $saleslist->loader);
                                                            echo $loaderInfo->personalMobile . ' [ ' . $loaderInfo->name . ' ] ';
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
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <td class="center">#</td>
                                            <td><?php echo get_phrase('Product') . ' ' . get_phrase('Category') ?> </td>
                                            <td><?php echo get_phrase('Product') ?></td>
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
                                            <th nowrap  style="text-align: center;width:10%;border-radius:10px;">
                                                <strong><?php echo 'Narration'; ?> </strong>
                                            </th>
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
                                                <th nowrap  style="text-align: center;width:10%;border-radius:25px;">
                                                    <strong><?php echo $each_info['product_details'];; ?> </strong>
                                                </th>
                                                <td align="right"><?php echo $each_info['quantity']; ?> </td>
                                                <td align="right"><?php echo $each_info['unit_price']; ?> </td>
                                                <td align="right"><?php echo number_format($each_info['unit_price'] * $each_info['quantity'], 2); ?> </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="8" align="right">
                                                <strong><?php echo get_phrase('Sub_Total') ?></strong></td>

                                            <td align="right"><?php echo number_format($tprice, 2); ?></td>
                                        </tr>

                                        <?php
                                        if ($saleslist->discount_amount > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="8" align="right">
                                                    <strong><?php echo get_phrase('Discount') ?> (
                                                        - ) </strong></td>

                                                <td align="right"><?php echo number_format($saleslist->discount_amount, 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($saleslist->vat_amount > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="8" align="right">
                                                    <strong><?php echo get_phrase('Vat') ?> <?php //echo round($saleslist->vat, 2) . ' ';       ?>
                                                        %( +
                                                        )</strong></td>
                                                <td align="right"><?php echo number_format($saleslist->vat_amount, 2); ?></td>

                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($saleslist->loader_charge > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="8" align="right"><strong><?php echo get_phrase('Loader') ?>
                                                        ( +
                                                        )</strong></td>
                                                <td align="right"><?php echo number_format($saleslist->loader_charge, 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($saleslist->transport_charge > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="8" align="right">
                                                    <strong><?php echo get_phrase('Transportation') ?> ( + )</strong>
                                                </td>
                                                <td align="right"><?php echo number_format($saleslist->transport_charge, 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="8" align="right">
                                                <strong><?php echo get_phrase('Net Total') ?></strong></td>
                                            <td align="right"><?php
                                                $NetTotal = $tprice + $saleslist->transport_charge + $saleslist->loader_charge + $saleslist->vat_amount;
                                                echo number_format($NetTotal, 2);
                                                ?></td>
                                        </tr>
                                        <?php
                                        if ($saleslist->paid_amount > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="8" align="right">
                                                    <strong><?php echo get_phrase('Payment') ?> (
                                                        - )</strong></td>
                                                <td align="right"><?php echo number_format($saleslist->paid_amount, 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($NetTotal - $saleslist->paid_amount > 0) {
                                            ?>
                                            <tr>
                                                <td colspan="8" align="right">
                                                    <strong><?php echo get_phrase('Due Amount') ?></strong></td>
                                                <td align="right"><?php echo number_format($NetTotal - $saleslist->paid_amount, 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="9">
                                                <strong><span><?php echo get_phrase('In_Words') ?>
                                                        : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($NetTotal); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9">
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
                                <div class="hr hr8 hr-double hr-dotted"></div>
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <p><?php echo get_phrase('Prepared By') ?>:_____________<br/>
                                            <?php echo get_phrase('Date') ?>:____________________
                                        </p>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p><?php echo get_phrase('Approved By') ?>:________________<br/>
                                            <?php echo get_phrase('Date') ?>:_________________</p>
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
<script>
    var ajaxUrl = baseUrl+"lpg/SalesController/getCustomerCurrentBalance2";
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
