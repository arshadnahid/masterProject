<style>
    table { border: 1px solid black !important}
    tr { border: 1px solid black}
    table tr td{
        margin:0 !important ; padding: 0 !important ;
    }
    table tr th{
        margin:0 !important ; padding: 0 !important ;
    }
</style>  
<?php
$start_date = $_SESSION["start_date"];
$end_date = $_SESSION["end_date"];
$categoryId = $_SESSION["categoryId"];


if (isset($start_date)):
    $start_date = $_SESSION["start_date"];
    $end_date = $_SESSION["end_date"];
    $categoryId = $_SESSION["categoryId"];
endif;
?>
<div class="page-content">
   
<?php
if (isset($start_date)):
     $start_date = $_SESSION["start_date"];
    $end_date = $_SESSION["end_date"];
    $categoryId = $_SESSION["categoryId"];
    $dr = 0;
    $cr = 0;
    ?>
        <div class="row">
            <div class="col-xs-12">

                <table class="table table-responsive">

                    <tr>
                        <td style="text-align:center;" colspan="16">
                            <h3><?php echo $companyInfo->companyName; ?>.</h3>
                            <p><?php echo $companyInfo->dist_address; ?>
                            </p>

                            <strong>Phone : </strong><?php echo $companyInfo->dist_phone; ?><br>
                            <strong>Email : </strong><?php echo $companyInfo->dist_email; ?><br>
                            <strong>Website : </strong><?php echo $companyInfo->dis_website; ?><br>
                            <strong><?php echo $pageTitle; ?></strong>
                        </td>
                    </tr>
                </table>
                <table class="table table-striped table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th rowspan="2"  style="text-align:center;">SL</th>
                            <th rowspan="2" style="text-align:center;">Category</th>
                            <th rowspan="2"  style="text-align:center;">Products</th>
                            <th rowspan="2"  style="text-align:center;">Products Code</th>
                            <th colspan="3"  style="text-align:center;">Opening Stock as on <br><?php //echo $from;                                                             ?></th>
                            <th colspan="3"  style="text-align:center;">Purchase</th>
                            <th colspan="3"  style="text-align:center;">Sales</th>
                            <th colspan="3"  style="text-align:center;">Closing stock as on <br>
    <?php //echo $to;      ?>
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

    if (!empty($categoryId) && $categoryId == 'All'):
        $productList = $this->db->get('product')->result_array();
    elseif (!empty($categoryId) && $categoryId != 'All'):
        $productList = $this->db->get_where('product', array('category_id' => $categoryId))->result_array();
    else:
        $productList = $this->db->get('product')->result_array();
    endif;


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