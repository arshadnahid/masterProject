<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/23/2020
 * Time: 10:01 AM
 */?>
<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number" style="font-size:25px;">


                    <?php if ($totalSalesAmount < 0) {
    $totalSalesAmount = ($totalSalesAmount * -1);
    echo $totalSalesAmount = "(<span >" . number_format($totalSalesAmount, 2) . "</span>)";


} else {
    echo $totalSalesAmount = "<span  >" . number_format($totalSalesAmount, 2) . "</span>";
} ?> &#2547;

</div>
<div class="desc"> <?php echo get_phrase('Product_sales') ?> </div>
</div>
</a>
</div>
<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 red" href="#">
        <div class="visual">
            <i class="fa fa-sitemap"></i>
        </div>
        <div class="details">
            <div class="number" style="font-size:25px;">

                <?php if ($inventoryAmount < 0) {
                    $inventoryAmount = ($inventoryAmount * -1);
                    echo $inventoryAmount = "(<span  >" . number_format($inventoryAmount, 2) . "</span>)";


                } else {
                    echo $inventoryAmount = "<span >" . number_format($inventoryAmount, 2) . "</span>";
                } ?> &#2547;
            </div>
            <div class="desc"> <?php echo get_phrase('Inventory') ?> </div>
        </div>
    </a>
</div>
<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 green" href="#">
        <div class="visual">
            <i class="fa fa-credit-card-alt"></i>
        </div>
        <div class="details">
            <div class="number" style="font-size:25px;">

                <?php if ($totalCashAtBank < 0) {
                    $totalCashAtBank = ($totalCashAtBank * -1);
                    echo $totalCashAtBank = "(<span >" . number_format($totalCashAtBank, 2) . "</span>)";


                } else {
                    echo $totalCashAtBank = "<span >" . number_format($totalCashAtBank, 2) . "</span>";
                } ?> &#2547;


            </div>
            <div class="desc"> <?php echo get_phrase('Cash_At_Bank') ?> </div>
        </div>
    </a>
</div>
<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
        <div class="visual">
            <i class="fa fa-credit-card"></i>
        </div>
        <div class="details">
            <div class="number" style="font-size:25px;">
                <?php if ($cashInHand < 0) {
                    $cashInHand = ($cashInHand * -1);
                    echo $cashInHand = "(<span >" . number_format($cashInHand, 2) . "</span>)";


                } else {
                    echo $cashInHand = "<span >" . number_format($cashInHand, 2) . "</span>";
                } ?> &#2547;

            </div>
            <div class="desc"> <?php echo get_phrase('Cash_In_Hand') ?> </div>
        </div>
    </a>
</div>
<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 greenjungle" href="#">
        <div class="visual">
            <i class="fa fa-area-chart"></i>
        </div>
        <div class="details">
            <div class="number" style="font-size:25px;">
                <?php if ($accountPayable <= 0) {
                    $accountPayable = ($accountPayable * -1);
                    echo $accountPayable = "<span >" . number_format($accountPayable, 2) . "</span>";


                } else {
                    echo $accountPayable = "(<span >" . number_format($accountPayable, 2) . "</span>)";
                } ?> &#2547;
            </div>
            <div class="desc"> <?php echo get_phrase('Account_Payable') ?> </div>
        </div>
    </a>
</div>
<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 bluesteel" href="#">
        <div class="visual">
            <i class="fa fa-bar-chart-o"></i>
        </div>
        <div class="details">
            <div class="number" style="font-size:25px;">
                <?php if ($accountReceiable < 0) {
                    $accountReceiable = ($accountReceiable * -1);
                    echo $accountReceiable = "(<span >" . number_format($accountReceiable, 2) . "</span>)";


                } else {
                    echo $accountReceiable = "<span >" . number_format($accountReceiable, 2) . "</span>";
                } ?> &#2547;
            </div>
            <div class="desc"> <?php echo get_phrase('Account_Receivable') ?> </div>
        </div>
    </a>
</div>


