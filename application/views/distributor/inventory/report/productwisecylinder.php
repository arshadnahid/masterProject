<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $productId = $this->input->post('productId');
endif;
?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Product Wise Cylinder Stock</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12 noPrint">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="table-header">
                                Product Wise Cylinder Stock Report
                            </div>
                            <br>
                            <div style="background-color: grey!important;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product</label>
                                        <div class="col-sm-9">
                                            <select  name="productId" class="chosen-select form-control supplierid" id="form-field-select-3" data-placeholder="Search by Product">
                                                <option <?php
if ($productId == 'all') {
    echo "selected";
}
?> value="all">All</option>
                                                    <?php
                                                    foreach ($productList as $eachInfo):
                                                        $brandInfo = $this->Common_model->tableRow('brand', 'brandId', $eachInfo->brand_id)->brandName;
                                                        ?>
                                                    <option <?php
                                                    if (!empty($productId) && $productId == $eachInfo->product_id) {
                                                        echo "selected";
                                                    }
                                                        ?> value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName . ' [ ' . $brandInfo . ' ] '; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From Date</label>
                                        <div class="col-sm-8">
                                            <input type="text"class="date-picker" id="start_date" name="start_date" value="<?php
                                                    if (!empty($start_date)) {
                                                        echo $start_date;
                                                    } else {
                                                        echo date('d-m-Y');
                                                    }
                                                    ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker" id="end_date" name="end_date" value="<?php
                                                   if (!empty($end_date)) {
                                                       echo $end_date;
                                                   } else {
                                                       echo date('d-m-Y');
                                                   }
                                                    ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"></label>
                                        <div class="col-sm-5">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                Search
                                            </button>
                                        </div>
                                        <div class="col-sm-5">
                                            <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">
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
            </div><!-- /.col -->
            <?php
            if (isset($_POST['start_date'])):
                $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                $productId11 = $this->input->post('productId');
                ?>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="table-header">
                            Product Wise Cylinder Stock Report <span style="color:greenyellow;">From <?php echo $start_date; ?> To <?php echo $end_date; ?></span>
                        </div>
                        <table class="table table-responsive">
                            <tr>
                                <td style="text-align:center;">
                                    <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                    <span><?php echo $companyInfo->address; ?></span><br>
                                    <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                    <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                    <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                    <strong><?php echo $pageTitle; ?></strong>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped table-bordered table-hover table-responsive">
                            <?php if ($productId11 == 'all'):
                                ?>
                                <thead>
                                    <tr>
                                        <td align="center"><strong>SL</strong></td>
                                        <td align="center"><strong>Product</strong></td>
                                        <td align="center"><strong>Opening (Pcs)</strong></td>
                                        <td align="center"><strong>Stock In (Pcs)</strong></td>
                                        <td align="center"><strong>Stock Out (Pcs)</strong></td>
                                        <td align="center"><strong>Balance (Pcs)</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $fstockIn = 0;
                                    $fstockOut = 0;
                                    $ctotalOpenig = 0;
                                    $fstockOpen = 0;
                                    $sl = 1;
                                    foreach ($productList2 as $key => $eachProduct):
//----------------------------------------------------------------------------------
                                        //Opening stock in
                                        $this->db->select("sum(quantity) as mOpening");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cin');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 28);
                                        $this->db->where('category_id', 2);
                                        $mainOpResult = $this->db->get('stock')->row();
                                        $mainOpQuanity = $mainOpResult->mOpening;

                                        $this->db->select("sum(quantity) as tPurOpIn");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'In');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 11);
                                        $this->db->where('category_id', 2);
                                        $this->db->where('date <', $from_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $purchasesOpIn = $this->db->get('stock')->row();


                                        //exchange opening
                                        $this->db->select("sum(quantity) as tPurOpIn");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'In');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 11);
                                        $this->db->where('category_id', 2);
                                        $this->db->where('date <', $from_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $purchasesOpIn = $this->db->get('stock')->row();

                                        $this->db->select("sum(quantity) as tSalOpIn");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cin');
                                        $this->db->where('category_id', 2);
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 24);
                                        $this->db->where('customerId >=', 1);
                                        $this->db->where('date <', $from_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $saleOpIn = $this->db->get('stock')->row();


                                        //end opeing stock in
                                        //start opening stock out
                                        $this->db->select("sum(quantity) as tPurOpOut");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cout');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 23);
                                        $this->db->where('category_id', 2);
                                        $this->db->where('supplierId >=', 1);
                                        $this->db->where('date <', $from_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $purchasesOpOut = $this->db->get('stock')->row();

                                        $this->db->select("sum(quantity) as tSalOpOut");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Out');
                                        $this->db->where('category_id', 2);
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 5);
                                        $this->db->where('date <', $from_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $saleOpOut = $this->db->get('stock')->row();


                                        //get cylinder exchange report
                                        $this->db->select("sum(quantity) as exchangeStockOpIn");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cin');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 27);
                                        $this->db->where('category_id', 2);
                                        $this->db->where('date <', $from_date);
                                        $exchangeStockOpIn = $this->db->get('stock')->row();
                                        //get cylinder exchange report
                                        $this->db->select("sum(quantity) as exchangeStockOpOut");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cout');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 27);
                                        $this->db->where('category_id', 2);
                                        $this->db->where('date <', $from_date);
                                        $exchangeStockOpOut = $this->db->get('stock')->row();




                                        //enn opeing stock out
//----------------------------------------------------------------------------------
//
//
//---------------------------------------------------------------------------------
                                        //start current stock in
                                        $this->db->select("sum(quantity) as tPurIn");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'In');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 11);
                                        $this->db->where('category_id', 2);
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $purchasesIn = $this->db->get('stock')->row();

                                        $this->db->select("sum(quantity) as tSalIn");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cin');
                                        $this->db->where('category_id', 2);
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 24);
                                        $this->db->where('customerId >=', 1);
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $saleIn = $this->db->get('stock')->row();


                                        //opeing stock out
                                        $this->db->select("sum(quantity) as tPurOut");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cout');
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 23);
                                        $this->db->where('category_id', 2);
                                        $this->db->where('supplierId >=', 1);
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $purchasesOut = $this->db->get('stock')->row();

                                        $this->db->select("sum(quantity) as tSalOut");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Out');
                                        $this->db->where('category_id', 2);
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 5);
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $saleCuOut = $this->db->get('stock')->row();

                                        //cylinder exchange stop
                                        //get cylinder exchange report
                                        $this->db->select("sum(quantity) as exchangeCurreIn");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cin');
                                        $this->db->where('category_id', 2);
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 27);
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $exchangeCurreIn = $this->db->get('stock')->row();

                                        $this->db->select("sum(quantity) as exchangeCurreOut");
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('type', 'Cout');
                                        $this->db->where('category_id', 2);
                                        $this->db->where('product_id', $eachProduct->product_id);
                                        $this->db->where('form_id', 27);
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('stock_id', 'asc');
                                        $exchangeCurreOut = $this->db->get('stock')->row();







//---------------------------------------------------------------------
                                        $copBalance = ($exchangeStockOpIn->exchangeStockOpIn + $purchasesOpIn->tPurOpIn + $saleOpIn->tSalOpIn + $mainOpQuanity) - ($purchasesOpOut->tPurOpOut + $saleOpOut->tSalOpOut + $exchangeStockOpOut->exchangeStockOpOut);
                                        $currentIn = $purchasesIn->tPurIn + $saleIn->tSalIn + $exchangeCurreIn->exchangeCurreIn;
                                        $currentOut = $purchasesOut->tPurOut + $saleCuOut->tSalOut + $exchangeCurreOut->exchangeCurreOut;
                                        if (!empty($copBalance) || !empty($currentIn) || !empty($currentOut)):
//die;
                                            $fstockOpen += $copBalance;
                                            $fstockIn += $currentIn;
                                            $fstockOut += $currentOut;
                                            ?>
                                            <tr>
                                                <td><?php
                            echo $sl;
                            $sl++;
                                            ?></td>
                                                <td>
                                                    <?php
                                                    $brandName = $this->Common_model->tableRow('brand', 'brandId', $eachProduct->brand_id)->brandName;
                                                    echo $eachProduct->productName . ' [ ' . $brandName . ' ] ';
                                                    ?>
                                                </td>
                                                <td align="right"><?php echo $copBalance; ?></td>
                                                <td align="right"><?php echo $currentIn; ?></td>
                                                <td align="right"><?php echo $currentOut; ?></td>
                                                <td align="right"><?php
                                    $balanceCurrent = ($copBalance + $currentIn) - $currentOut;
                                    echo $balanceCurrent;
                                                    ?></td>
                                            </tr>
                                            <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Total Closing Cylinder Stock ( Pcs )</strong></td>
                                        <td align="right"><strong><?php echo $fstockOpen; ?>&nbsp;</strong></td>
                                        <td align="right"><strong><?php echo $fstockIn; ?>&nbsp;</strong></td>
                                        <td align="right"><strong><?php echo $fstockOut; ?>&nbsp;</strong></td>
                                        <td align="right"><strong><?php echo ($fstockIn + $fstockOpen) - $fstockOut; ?>&nbsp;</strong></td>
                                    </tr>
                                </tfoot>
                                <?php
                            else:
                                $productId = $this->input->post('productId');
                                $this->db->select("sum(quantity) as mOpening");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'Cin');
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 28);
                                $this->db->where('category_id', 2);
                                $mainOpResult = $this->db->get('stock')->row();
                                $mainOpQuanity = $mainOpResult->mOpening;

                                $this->db->select("sum(quantity) as tPurOpIn");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'In');
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 11);
                                $this->db->where('category_id', 2);
                                $this->db->where('date <', $from_date);
                                $this->db->order_by('stock_id', 'asc');
                                $purchasesOpIn = $this->db->get('stock')->row();






                                $this->db->select("sum(quantity) as tSalOpIn");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'Cin');
                                $this->db->where('category_id', 2);
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 24);
                                $this->db->where('customerId >=', 1);
                                $this->db->where('date <', $from_date);
                                $this->db->order_by('stock_id', 'asc');
                                $saleOpIn = $this->db->get('stock')->row();
                                //end opeing stock in
                                //start opening stock out
                                $this->db->select("sum(quantity) as tPurOpOut");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'Cout');
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 23);
                                $this->db->where('category_id', 2);
                                $this->db->where('supplierId >=', 1);
                                $this->db->where('date <', $from_date);
                                $this->db->order_by('stock_id', 'asc');
                                $purchasesOpOut = $this->db->get('stock')->row();

                                $this->db->select("sum(quantity) as tSalOpOut");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'Out');
                                $this->db->where('category_id', 2);
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 5);
                                $this->db->where('date <', $from_date);
                                $this->db->order_by('stock_id', 'asc');
                                $saleOpOut = $this->db->get('stock')->row();
                                //enn opeing stock out
//----------------------------------------------------------------------------------
//
//
//---------------------------------------------------------------------------------
                                //start current stock in
                                $this->db->select("*");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'In');
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 11);
                                $this->db->where('category_id', 2);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $this->db->order_by('stock_id', 'asc');
                                $purchasesIn = $this->db->get('stock')->result_array();

                                $this->db->select("*");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'Cin');
                                $this->db->where('category_id', 2);
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 24);
                                $this->db->where('customerId >=', 1);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $this->db->order_by('stock_id', 'asc');
                                $saleIn = $this->db->get('stock')->result_array();

                                //opeing stock out
                                $this->db->select("*");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'Cout');
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 23);
                                $this->db->where('category_id', 2);
                                $this->db->where('supplierId >=', 1);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $this->db->order_by('stock_id', 'asc');
                                $purchasesOut = $this->db->get('stock')->result_array();

                                $this->db->select("*");
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('type', 'Out');
                                $this->db->where('category_id', 2);
                                $this->db->where('product_id', $productId);
                                $this->db->where('form_id', 5);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $this->db->order_by('stock_id', 'asc');
                                $saleCuOut = $this->db->get('stock')->result_array();
                                $totalInout = array_merge($purchasesIn, $saleIn, $purchasesOut, $saleCuOut);
                                $copBalance = ($purchasesOpIn->tPurOpIn + $saleOpIn->tSalOpIn + $mainOpQuanity) - ($purchasesOpOut->tPurOpOut + $saleOpOut->tSalOpOut);
                                ?>
                                <thead>
                                    <tr>
                                        <td align="center"><strong>SL</strong></td>
                                        <td align="center"><strong>Date</strong></td>
                                        <td align="center"><strong>Voucher</strong></td>
                                        <td align="center"><strong>Customer/Supplier</strong></td>
                                        <td align="center"><strong>Product</strong></td>
                                        <td align="center"><strong>Stock In</strong></td>
                                        <td align="center"><strong>Stock Out</strong></td>
                                        <td align="center"><strong>Balance</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="right" colspan="7">Opening Qty</td>
                                        <td align="right"><?php echo $copBalance; ?></td>
                                    </tr>
                                    <?php
                                    foreach ($totalInout as $key => $eachInfo):
                                        $ttotalIn = 0;
                                        $ttotalOut = 0;
                                        ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $eachInfo['date']; ?></td>
                                            <td><?php echo $eachInfo['voucher_no']; ?></td>
                                            <td>
                                                <?php
                                                $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $eachInfo['customerId']);
                                                if (!empty($customerInfo)) {
                                                    echo $customerInfo->customerID . '[ ' . $customerInfo->customerName . ']';
                                                }
                                                $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $eachInfo['supplierId']);
                                                if (!empty($suplierInfo)) {
                                                    echo $suplierInfo->supID . '[ ' . $suplierInfo->supName . ']';
                                                }
                                                if (empty($customerInfo) && empty($suplierInfo)) {
                                                    echo "Opening Clinder";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $productinfo = $this->Common_model->tableRow('product', 'product_id', $eachInfo['product_id']);
                                                $brandInfo = $this->Common_model->tableRow('brand', 'brandId', $productinfo->brand_id)->brandName;
                                                echo $productinfo->productName . ' [ ' . $brandInfo . ' ]';
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                if ($eachInfo['type'] == 'Cin' || $eachInfo['type'] == 'In') {
                                                    echo $eachInfo['quantity'];
                                                    $ttotalIn = $eachInfo['quantity'];
                                                    $totalIn += $eachInfo['quantity'];
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                if ($eachInfo['type'] == 'Cout' || $eachInfo['type'] == 'Out') {
                                                    echo $eachInfo['quantity'];
                                                    $ttotalOut = $eachInfo['quantity'];
                                                    $totalOut += $eachInfo['quantity'];
                                                }
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
//                                                $bal = $totalIn - $totalOut;
//                                                if ($bal > $bal) {
//                                                    echo "Payable = ";
//                                                } else {
//                                                    echo "Receiable = ";
//                                                }
                                                echo $ttotalIn - $ttotalOut;
                                                // echo $totalIn - $totalOut;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Total Closing Cylinder Stock (PCS)</strong></td>
                                        <td align="right"><strong><?php echo $totalIn; ?>&nbsp;</strong></td>
                                        <td align="right"><strong><?php echo $totalOut; ?>&nbsp;</strong></td>
                                        <td align="right"><strong><?php
//                            $bal = $totalIn - $totalOut;
//                            if ($bal > $bal) {
//                                echo "Payable = ";
//                            } else {
//                                echo "Receiable = ";
//                            }
                            echo ($totalIn + $copBalance) - $totalOut;
                                    ?>&nbsp;</strong></td>
                                    </tr>
                                </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
