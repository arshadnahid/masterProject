
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Purchases View</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="inventoryAddPermission" href="<?php echo site_url('purchases_add'); ?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Add
                    </a>
                </li>
                <li>
                    <a href="<?php echo site_url('purchases_list'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
                <li>
                    <a class="inventoryEditPermission" href="<?php echo site_url('purchases_edit/' . $purchasesList->generals_id); ?>">
                        <i class="ace-icon fa fa-pencil bigger-130"></i> Edit
                    </a>
                </li>
                <li>
                    <a class="inventoryEditPermission" href="<?php echo site_url('viewPurchases/' . $purchasesList->generals_id); ?>">
                        <i class="ace-icon fa fa-search-plus bigger-130"></i> View Invoice
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
                                <i class="ace-icon fa fa-leaf green"></i>
                                Purchases Voucher
                            </h3>
                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Payment Type:</span>
                                <span class="red"><?php
if ($purchasesList->payType == 1) {
    echo "Full Cash";
} elseif ($purchasesList->payType == 4) {
    echo "Cash";
} elseif ($purchasesList->payType == 3) {
    echo "Cheque";
} else {
    echo "Credit";
}
?></span>
                                <br />
                                <?php if ($purchasesList->payType == 2) { ?>
                                    <span class="invoice-info-label"> Due Date:</span>
                                    <span class="red"><?php echo date('d-m-Y', strtotime($purchasesList->dueDate)); ?></span>
                                <?php } ?>
                            </div>
                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Voucher ID:</span>
                                <span class="red"><?php echo $purchasesList->voucher_no; ?> / <?php echo $purchasesList->mainInvoiceId; ?></span>
                                <br />
                                <span class="invoice-info-label"> Date:</span>
                                <span class="red"><?php echo date('d-m-Y', strtotime($purchasesList->date)); ?></span>
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
                                    <div class="col-xs-4">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-default arrowed-in arrowed-right">
                                                Company Info
                                            </div>
                                        </div>
                                        <div>
                                            <ul class="list-unstyled spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->companyName; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->email; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->phone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->address; ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div><!-- /.col -->
                                    <div class="col-xs-4">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-default arrowed-in arrowed-right">
                                                Supplier Info
                                            </div>
                                        </div>
                                        <div>
                                            <ul class="list-unstyled  spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supID . '[' . $supplierInfo->supName . ']' ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supEmail; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supPhone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $supplierInfo->supAddress; ?>
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
                                                    <i class="ace-icon fa fa-caret-right green"></i>Current Due: <span id="customerCurrentDue"></span>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i> Transportation: <?php
                                if (!empty($purchasesList->transportation)):
                                    $transporation = $this->Common_model->tableRow('vehicle', 'id', $purchasesList->transportation);
                                    echo $transporation->vehicleName . ' [ ' . $transporation->vehicleModel . ' ] ';
                                else:
                                    echo "N/A";
                                endif;
                                ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i> Loader:
                                                    <?php
                                                    if (!empty($purchasesList->loader)):
                                                        $loaderInfo = $this->Common_model->tableRow('employee', 'id', $purchasesList->loader);
                                                        echo $loaderInfo->personalMobile . ' [ ' . $loaderInfo->name . ' ] ';
                                                    else:
                                                        echo "N/A";
                                                    endif;
                                                    ?>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                                <div class="space"></div>
                                <div style="min-height:400px;" >
                                    <div class="table-header">
                                        Purchases Item
                                    </div>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <td class="center">#</td>
                                                <td><strong>Product Category</strong></td>
                                                <td><strong>Product</strong></td>
                                                <td><strong>Unit</strong></td>
                                                <td><strong>Quantity</strong></td>
                                                <td><strong>Unit Price</strong></td>
                                                <td><strong>Total Price</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tqty = 0;
                                            $trate = 0;
                                            $tprice = 0;
                                            $returnAble = 0;
                                            $returned = 0;
                                            foreach ($stockList as $key => $each_info):
                                                if ($each_info->type == 'Cin') {
                                                    $returnAble+=1;
                                                }
                                                if ($each_info->type == 'Cout') {
                                                    $returned+=1;
                                                }
                                                if ($each_info->type == 'In') {
                                                    $tqty += $each_info->quantity;
                                                    $trate += $each_info->rate;
                                                    $tprice += $each_info->rate * $each_info->quantity;
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
                                                            $productInfo = $this->Common_model->tableRow('product', 'product_id', $each_info->product_id);
                                                            echo $productInfo->productName;
                                                            echo ' [ ' . $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id)->brandName . ' ] ';
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if (!empty($each_info->unit)):
                                                                echo $this->Common_model->tableRow('unit', 'unit_id', $each_info->unit)->unitTtile;
                                                            endif;
                                                            ?>
                                                        </td>
                                                        <td align='right'><?php echo $each_info->quantity; ?> </td>
                                                        <td align='right'><?php echo number_format((float) $each_info->rate, 2, '.', ','); ?> </td>
                                                        <td align='right'><?php echo number_format((float) $each_info->rate * $each_info->quantity, 2, '.', ','); ?> </td>
                                                    </tr>
                                                <?php }endforeach; ?>
                                        </tbody>
                                          <tfoot>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Sub-Total</strong></td>
                                                <td align='right'><?php echo number_format((float) $tprice, 2, '.', ','); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Discount ( - )</strong></td>
                                                <td align='right'><?php echo number_format((float) $purchasesList->discount, 2, '.', ','); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Loader ( + )</strong></td>
                                                <td align='right'><?php echo number_format((float) $purchasesList->loaderAmount, 2, '.', ','); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Transportation ( + )</strong></td>
                                                <td align='right'><?php echo number_format((float) $purchasesList->transportationAmount, 2, '.', ','); ?></td>
                                            </tr>
                                            <?php
                                            $netAmount = ($tprice + $purchasesList->transportationAmount + $purchasesList->loaderAmount) - $purchasesList->discount;
                                            ?>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Net Total</strong></td>
                                                <td align='right'><?php echo number_format((float) $netAmount, 2, '.', ','); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Payment ( - )</strong></td>
                                                <td align='right'><?php echo number_format((float) $creditAmount->credit, 2, '.', ','); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Due Amount</strong></td>
                                                <td align='right'><?php echo number_format((float) $netAmount - $creditAmount->credit, 2, '.', ','); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" >
                                                    <strong>  <span>In Words : &nbsp;</span> <?php echo $this->Common_model->get_bd_amount_in_text($netAmount); ?></strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" >
                                                    <span>Narration : &nbsp;</span> <?php echo $purchasesList->narration; ?>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>


                                    <?php if (!empty($returnAble)): ?>



                                        <div class="table-header">
                                            Returnable Cylinder
                                        </div>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td class="center">#</td>
                                                    <td><strong>Product Category</strong></td>
                                                    <td><strong>Product</strong></td>
                                                    <td><strong>Quantity</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tqty = 0;
                                                $trate = 0;
                                                $tprice = 0;
                                                $j = 1;
                                                foreach ($stockList as $key1 => $each_info):
                                                    if ($each_info->type == 'Cin') {
                                                        $tqty += $each_info->quantity;
                                                        $trate += $each_info->rate;
                                                        $tprice += $each_info->rate * $each_info->quantity;
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $j++; ?></td>
                                                            <td>
                                                                <?php
                                                                echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $productInfo = $this->Common_model->tableRow('product', 'product_id', $each_info->product_id);
                                                                echo $productInfo->productName;
                                                                echo ' [ ' . $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id)->brandName . ' ] ';
                                                                ?>
                                                            </td>
                                                            <td align='right'><?php echo $each_info->quantity; ?> </td>
                                                        </tr>
                                                    <?php }endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>

                                    <?php if (!empty($returned)): ?>

                                        <div class="table-header">
                                            Returned Cylinder
                                        </div>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td class="center">#</td>
                                                    <td><strong>Product Category</strong></td>
                                                    <td><strong>Product</strong></td>
                                                    <td><strong>Quantity</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tqty = 0;
                                                $trate = 0;
                                                $tprice = 0;
                                                $j = 1;
                                                foreach ($stockList as $key2 => $each_info):
                                                    if ($each_info->type == 'Cout') {
                                                        $tqty += $each_info->quantity;
                                                        $trate += $each_info->rate;
                                                        $tprice += $each_info->rate * $each_info->quantity;
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $j++; ?></td>
                                                            <td>
                                                                <?php
                                                                echo $this->Common_model->tableRow('productcategory', 'category_id', $each_info->category_id)->title;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $productInfo = $this->Common_model->tableRow('product', 'product_id', $each_info->product_id);
                                                                echo $productInfo->productName;
                                                                echo ' [ ' . $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id)->brandName . ' ] ';
                                                                ?>
                                                            </td>
                                                            <td align='right'><?php echo $each_info->quantity; ?> </td>
                                                        </tr>
                                                    <?php }endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
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
                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                                     ?></p>
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
<script>
    var url = baseUrl + "SalesController/getSupplierClosingBalance";

    $.ajax({
        type: 'POST',
        url:baseUrl + "InventoryController/getSupplierClosingBalance",
        data:{
            supplierid: '<?php echo $purchasesList->supplier_id; ?>'
        },
        success: function (data)
        {
            data=parseFloat(data);
            if(isNaN(data)){
                data=0;
            }
            $('#customerCurrentDue').text(parseFloat(data).toFixed(2));
        }
    });


</script>
