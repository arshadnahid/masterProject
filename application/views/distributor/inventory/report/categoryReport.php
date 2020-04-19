<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
endif;
?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Category Stock Report</li>
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
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="table-header">
                                Stock Report
                            </div><br>
                            <div style="background-color: grey!important;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Category</label>
                                        <div class="col-sm-8">
                                            <input type="text"class="date-picker" id="start_date" name="start_date" value="<?php
if (!empty($start_date)) {
    echo $start_date;
} else {
    echo date('Y-m-d');
}
?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From Date</label>
                                        <div class="col-sm-8">
                                            <input type="text"class="date-picker" id="start_date" name="start_date" value="<?php
                                if (!empty($start_date)) {
                                    echo $start_date;
                                } else {
                                    echo date('Y-m-d');
                                }
?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker" id="end_date" name="end_date" value="<?php
                                                   if (!empty($end_date)) {
                                                       echo $end_date;
                                                   } else {
                                                       echo date('Y-m-d');
                                                   }
?>" data-date-format='yyyy-mm-dd' placeholder="End Date: yyyy-mm-dd"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');
                $end_date = $this->input->post('end_date');
                $dr = 0;
                $cr = 0;
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-header">
                            Category  Stock Report
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2"  style="text-align:center;">SL</th>
                                    <th rowspan="2" style="text-align:center;">Category</th>
                                    <th rowspan="2"  style="text-align:center;">Products</th>
                                    <th rowspan="2"  style="text-align:center;">Products Code</th>
                                    <th colspan="3"  style="text-align:center;">Opening Stock as on <br><?php //echo $from;                                                            ?></th>
                                    <th colspan="3"  style="text-align:center;">Purchase</th>
                                    <th colspan="3"  style="text-align:center;">Sales</th>
                                    <th colspan="3"  style="text-align:center;">Closing stock as on <br>
                                        <?php //echo $to;     ?>
                                    </th>

                                </tr>
                                <tr>   
                                    <th align="center">Qty</th>
                                    <th align="center"> Rate</th>
                                    <th align="center">TK</th>
                                    <th align="center">Qty</th>
                                    <th align="center"> Rate</th>
                                    <th align="center">TK</th>
                                    <th align="center">Qty</th>
                                    <th align="center"> Rate</th>
                                    <th align="center">TK</th>
                                    <th align="center">Qty</th>
                                    <th align="center">Rate</th>
                                    <th align="center">TK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $open_qty = 0;
                                $purchases_qty = 0;
                                $sale_qty = 0;
                                $closing_qty = 0;
                                $closing_value = 0;
                                $productList = $this->db->get_where('product',array('category_id' => $categoryId))->result_array();
                                foreach ($productList as $key => $each_product):
                                    $reportStock = $this->Inventory_Model->getStockReport($start_date, $end_date, $each_product['product_id']);
                                    // dumpVar($reportStock);
                                    $open_qty += $openingStock = $reportStock['openingStock']['totalOpeQty'] - $reportStock['openingOut']['totalOpeQty'];
                                    $openingAvgPrice = $reportStock['openingStock']['totalAvgOpePusPrice'];
                                    $purchases_qty += $purchasesStock = $reportStock['purchasesStock']['totalPurcQty'];
                                    $purchasesAvgPrice = $reportStock['purchasesStock']['totalAvgPusPrice'];
                                    $sale_qty += $saleStock = $reportStock['saleStock']['totalSaleQty'];
                                    $saleAvgPrice = $reportStock['saleStock']['totalAvgSalePusPrice'];
                                    $closing = (($openingStock + $purchasesStock) - $saleStock);
                                    $closing_qty += $closing;
                                    $closing_value += $closing * $purchasesAvgPrice;
//                                    
                                    $commonAvg = 0;
                                    if (!empty($openingAvgPrice)):
                                        $commonAvg = $openingAvgPrice;
                                    elseif (!empty($purchasesAvgPrice)):
                                        $commonAvg = $purchasesAvgPrice;

                                    elseif (!empty($saleAvgPrice)):
                                        $commonAvg = $saleAvgPrice;
                                    endif;

                                    if (!empty($reportStock['openingStock']['totalOpeQty']) || !empty($reportStock['purchasesStock']['totalPurcQty']) || !empty($reportStock['saleStock']['totalSaleQty'])):
                                        $productCat = $this->Common_model->tableRow('product', 'product_id', $each_product['product_id'])->category_id;
                                        ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $this->Common_model->tableRow('productcategory', 'category_id', $productCat)->title; ?></td>
                                            <td><?php echo $this->Common_model->tableRow('product', 'product_id', $each_product['product_id'])->productName; ?></td>
                                            <td><?php echo $this->Common_model->tableRow('product', 'product_id', $each_product['product_id'])->product_code; ?></td>
                                            <td><?php echo $openingStock; ?></td>
                                            <td><?php echo number_format((float) abs($openingAvgPrice), 2, '.', ','); ?></td>
                                            <td><?php echo number_format((float) abs($openingStock * $openingAvgPrice), 2, '.', ','); ?></td>
                                            <td><?php echo $purchasesStock; ?></td>
                                            <td><?php echo number_format((float) abs($purchasesAvgPrice), 2, '.', ','); ?></td>
                                            <td><?php echo number_format((float) abs($purchasesStock * $purchasesAvgPrice), 2, '.', ','); ?></td>
                                            <td><?php echo $saleStock; ?></td>
                                            <td><?php echo number_format((float) abs($saleAvgPrice), 2, '.', ','); ?></td>
                                            <td><?php echo number_format((float) abs($saleStock * $saleAvgPrice), 2, '.', ','); ?></td>
                                            <td><?php echo $closing; ?></td>
                                            <td><?php echo number_format((float) abs($commonAvg), 2, '.', ','); ?></td>
                                            <td><?php echo number_format((float) abs($closing * $commonAvg), 2, '.', ','); ?></td>
                                        </tr>
                                        <?php
                                    endif;
                                endforeach;
                                ?>
                            </tbody>
                            <tfoot>                             
                                <tr>
                                    <td colspan="4" align="right"><strong>Total Opening </strong></td>
                                    <td align="right" ><strong><?php echo number_format((float) $open_qty, 2, '.', ','); ?></strong></td>
                                    <td align="right" colspan="2"><strong>Total Purchases</strong></td>
                                    <td align="right" ><strong><?php echo number_format((float) $purchases_qty, 2, '.', ','); ?></strong></td>
                                    <td align="right" colspan="2"><strong> Total Issu</strong></td>
                                    <td align="right"><strong> <?php echo number_format((float) $sale_qty, 2, '.', ','); ?></strong></td>
                                    <td align="right" colspan="2"><strong> Total Closing</strong></td>
                                    <td align="right" ><strong> <?php echo number_format((float) $closing_qty, 2, '.', ','); ?></strong></td>

                                    <td align="right"><strong> Closing Value</strong></td>
                                    <td align="right" ><strong> <?php echo number_format((float) $closing_value, 2, '.', ','); ?></strong></td>
                                </tr>
                            </tfoot>    

                        </table> 
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
