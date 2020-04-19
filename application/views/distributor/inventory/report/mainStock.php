<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $categoryId = $this->input->post('categoryId');
    $brandId = $this->input->post('brandId');
endif;
?>
<div class="row">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Stock Report
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">

                            <div style="background-color: grey!important;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            Category</label>
                                        <div class="col-sm-8">
                                            <select name="categoryId" class="chosen-select form-control supplierid"
                                                    id="form-field-select-3" data-placeholder="Search by Category">
                                                <option <?php
                                                if (!empty($categoryId) && $categoryId == 'All') {
                                                    echo "selected";
                                                }
                                                ?> value="All">All
                                                </option>
                                                <?php foreach ($categoryList as $eachInfo): ?>
                                                    <option <?php
                                                    if (!empty($categoryId) && $categoryId == $eachInfo->category_id) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $eachInfo->category_id; ?>"><?php echo $eachInfo->title; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            Brand</label>
                                        <div class="col-sm-8">
                                            <select name="brandId" class="chosen-select form-control supplierid"
                                                    id="form-field-select-3" data-placeholder="Search by Category">
                                                <option <?php
                                                if (!empty($brandId) && $brandId == 'All') {
                                                    echo "selected";
                                                }
                                                ?> value="All">All
                                                </option>
                                                <?php foreach ($brandList as $eachInfo): ?>
                                                    <option <?php
                                                    if (!empty($brandId) && $brandId == $eachInfo->brandId) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $eachInfo->brandId; ?>"><?php echo $eachInfo->brandName; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            From</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker form-control" id="start_date"
                                                   name="start_date" value="<?php
                                            if (!empty($start_date)) {
                                                echo $start_date;
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            To</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker form-control" id="end_date"
                                                   name="end_date" value="<?php
                                            if (!empty($end_date)) {
                                                echo $end_date;
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                                style="cursor:pointer;">
                                            <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                            Print
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <?php
                if (isset($_POST['start_date'])):
                    $dr = 0;
                    $cr = 0;
                    ?>
                    <div class="row">
                        <div class="col-xs-12">


                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo rtrim($companyInfo->address); ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        : From <?php echo $start_date; ?> To <?php echo $end_date; ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <td rowspan="2" style="text-align:center;"><strong>Category</strong></td>
                                    <td rowspan="2" style="text-align:center;"><strong>Products</strong></td>
                                    <td rowspan="2" style="text-align:center;"><strong>Products Code</strong></td>
                                    <td colspan="3" style="text-align:center;"><strong>Opening Stock as on </strong>
                                    </td>
                                    <td colspan="3" style="text-align:center;"><strong>Purchase</strong></td>
                                    <td colspan="3" style="text-align:center;"><strong>Sales</strong></td>
                                    <td colspan="3" style="text-align:center;"><strong>Closing stock as on </strong>
                                    </td>

                                </tr>
                                <tr>
                                    <td align="right">Qty</td>
                                    <td align="right"> Rate</td>
                                    <td align="right">TK</td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;">Qty</font></font></td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;"> Rate</font></font></td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;">TK</font></font></td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;">Qty</font></font></td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;"> Rate</font></font></td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;">TK</font></font></td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;">Qty</font></font></td>
                                    <td align="right">Rate</td>
                                    <td align="right"><font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;">TK</font></font></td>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $tOpenQty = 0; //opening quantity
                                $tOpenPrice = 0; //opening total price
                                $tPurQty = 0; //purchases quantity
                                $tPurPrice = 0; //purchases total price
                                $tSalQty = 0; //sale quantity
                                $tSalPrice = 0; //total sale price
                                $tClosStock = 0; //closing quantity
                                $tClosPrice = 0; //closign total price
                                foreach ($allStock as $key => $eachStock):
                                    $opInQty = 0;
                                    $closingStockQty = 0;
                                    if ($eachStock->opStockIn > 0 || $eachStock->opStockOut > 0 || $eachStock->currentStockIn > 0 || $eachStock->currentStockOut > 0):
                                        $opInQty = $eachStock->opStockIn - $eachStock->opStockOut;
                                        $tOpenQty += $opInQty;
                                        $tOpenPrice += $opInQty * $eachStock->avgPurPrice; //opening Price
                                        $tPurQty += $eachStock->currentStockIn;
                                        $tPurPrice += $eachStock->currentStockIn * $eachStock->avgPurPrice; //purchases total price
                                        $tSalQty += $eachStock->currentStockOut;
                                        $tSalPrice += $eachStock->currentStockOut * $eachStock->avgSalePrice; //sale price
                                        $closingStockQty = ($opInQty + $eachStock->currentStockIn) - $eachStock->currentStockOut;
                                        $tClosPrice += $closingStockQty * $eachStock->avgPurPrice;
                                        $tClosStock += $closingStockQty;
                                        ?>
                                        <tr>
                                            <td><?php echo $eachStock->pCateogry; ?></td>
                                            <td><?php echo $eachStock->productName . ' [ ' . $eachStock->brandName . ' ]'; ?></td>
                                            <td><?php echo $eachStock->product_code; ?></td>
                                            <td align="right"><?php
                                                if (!empty($opInQty)):
                                                    echo $opInQty;
                                                endif;
                                                ?></td>
                                            <td align="right"><?php
                                                if (!empty($opInQty)):
                                                    echo number_format((float)abs($eachStock->avgPurPrice), 2, '.', ',');
                                                endif;
                                                ?></td>
                                            <td align="right"><?php
                                                if (!empty($opInQty)):
                                                    echo number_format((float)abs($opInQty * $eachStock->avgPurPrice), 2, '.', ',');
                                                endif;
                                                ?></td>
                                            <td align="right"><?php echo $eachStock->currentStockIn; ?></td>
                                            <td align="right"><?php
                                                if (!empty($eachStock->currentStockIn)):
                                                    echo number_format((float)abs($eachStock->avgPurPrice), 2, '.', ',');
                                                endif;
                                                ?></td>
                                            <td align="right"><?php
                                                if (!empty($eachStock->currentStockIn)):
                                                    echo number_format((float)abs($eachStock->currentStockIn * $eachStock->avgPurPrice), 2, '.', ',');
                                                endif;
                                                ?></td>
                                            <td align="right"><?php echo $eachStock->currentStockOut; ?></td>
                                            <td align="right"><?php
                                                if (!empty($eachStock->currentStockOut)):
                                                    echo number_format((float)abs($eachStock->avgSalePrice), 2, '.', ',');
                                                endif;
                                                ?></td>
                                            <td align="right"><?php
                                                if (!empty($eachStock->currentStockOut)):
                                                    echo number_format((float)abs($eachStock->currentStockOut * $eachStock->avgSalePrice), 2, '.', ',');
                                                endif;
                                                ?></td>
                                            <td align="right"><?php echo $closingStockQty; ?></td>
                                            <td align="right"><?php
                                                if (!empty($closingStockQty)):
                                                    echo number_format((float)abs($eachStock->avgPurPrice), 2, '.', ',');
                                                endif;
                                                ?></td>
                                            <td align="right"><?php echo number_format((float)abs($closingStockQty * $eachStock->avgPurPrice), 2, '.', ','); ?></td>
                                        </tr>
                                        <?php
                                    endif;
                                endforeach;
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" align="right"><strong>Total (BDT) </strong></td>
                                    <td align="right" colspan="3"><strong><?php
                                            if (!empty($tOpenQty)):
                                                echo number_format((float)$tOpenPrice, 2, '.', ',');
                                            endif;
                                            ?></strong></td>
                                    <td align="right" colspan="3"><strong><?php
                                            if (!empty($tPurQty)):
                                                echo number_format((float)$tPurPrice, 2, '.', ',');
                                            endif;
                                            ?></strong></td>
                                    <td align="right" colspan="3"><strong> <?php
                                            if (!empty($tSalQty)):
                                                echo number_format((float)$tSalPrice, 2, '.', ',');
                                            endif;
                                            ?></strong></td>
                                    <td align="right" colspan="3"><strong> <?php
                                            if (!empty($tClosStock)):
                                                echo number_format((float)$tClosPrice, 2, '.', ',');
                                            endif;
                                            ?></strong></td>
                                </tr>
                                </tfoot>

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
