
<style type="text/css">
    #container {
        min-width: 210px;
        max-width: 500px;
        height: 300px;
        margin: 0 auto
    }
    #container2 {
        min-width: 210px;
        max-width: 500px;
        height: 300px;
        margin: 0 auto
    }
    .mystyle{
        height: 78px!important;
    }
</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">
            <div class="page-header">
                <input type="hidden" id="com_per" value="<?php echo isset($com_per) ? $com_per : 0 ?>">
                <input type="hidden" id="user_per" value="<?php echo isset($user_per) ? $user_per : 0 ?>">
                <input type="hidden" id="emp_per" value="<?php echo isset($emp_per) ? $emp_per : 0 ?>">
                <input type="hidden" id="pro_per" value="<?php echo isset($pro_per) ? $pro_per : 0 ?>">
                <input type="hidden" id="sal_per" value="<?php echo isset($sal_per) ? $sal_per : 0 ?>">
            </div><!-- /.page-header -->
            <style>
                .cusTable tbody tr td{
                    margin: 0px;
                    padding: 2px;
                }
            </style>

            <div class="row" style="margin-top:-5px!important;">
                <?php if (!empty($companySummaryPrmission)): ?>

                    <div class="col-md-3">
                        <div class="table-header">
                            Company Summary As <?php echo date('d-m-Y'); ?>
                        </div>
                        <div class="newChosen2">
                            <select  style="width:100%!important" onchange="showCompanySummary(this.value)" id="searchDay" class="chosen-select search-query container2" data-placeholder="Search by previous day">
                                <option></option>
                                <?php
                                $m = date("m");
                                $de = date("d");
                                $y = date("Y");
                                for ($i = 0; $i <= 30; $i++) {
                                    ?>
                                    <option value="<?php echo date('Y-m-d', mktime(0, 0, 0, $m, ($de - $i), $y)); ?>"><?php echo date('Y - m - d : l', mktime(0, 0, 0, $m, ($de - $i), $y)); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div id="showCompanySummary"></div>
                    </div>
                    <?php
                endif;
                if (!empty($todaySummaryPermission)):
                    ?>
                    <div class="col-md-5">
                        <div class="table-header">
                            Todays Summary  | <?php echo date('F, D'); ?>
                        </div>
                        <div class="newChosen">
                            <select style="width:100%!important" onchange="showTodaySummary(this.value)" id="searchDay" class="chosen-select search-query container2" data-placeholder="Search by previous day">
                                <option></option>
                                <?php
                                $m = date("m");
                                $de = date("d");
                                $y = date("Y");
                                for ($i = 0; $i <= 30; $i++) {
                                    ?>
                                    <option value="<?php echo date('Y-m-d', mktime(0, 0, 0, $m, ($de - $i), $y)); ?>"><?php echo date('Y - m - d : l', mktime(0, 0, 0, $m, ($de - $i), $y)); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div id="showTodaysSummary"></div>
                    </div>
                <?php endif; ?>


                <?php if (!empty($incentivePermition)): ?>

                    <div class="col-md-4">
                        <div class="table-header">
                            Incentive Summary
                        </div>
                        <div class="input-group input-group-sm">
                            <select  style="width:100%!important" onchange="showIncentiveSummary()" id="monthYear" class="chosen-select search-query " data-placeholder="Search by previous month">
                                <option></option>
                                <?php
                                for ($i = 0; $i <= 12; $i++) {
                                    $time = strtotime("first day of -" . $i . " months");
                                    ?>
                                    <option value="<?php echo date('Y-m', $time) ?>"><?php echo date("Y-F", $time) ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div id="loadIncentive"></div>
                    </div>
                <?php endif; ?>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($dailySummaryPermission)): ?>

                        <div class="col-md-6">

                            <div id="container2" style="min-width: 100%; height: 400px; margin: 0 auto"></div>
                        </div>
                        <?php
                    endif;
                    if (!empty($companySummaryGrapePermission)):
                        ?>
                        <div class="col-md-6">
                            <div id="companySummary" style="min-width: 100%; height: 400px; margin: 0 auto"></div>
                        </div>
                        <?php
                    endif;
                    if (!empty($inventoryProductStockPermission)):
                        ?>
                        <div class="col-md-6">
                            <div id="inventoryStock" style="min-width: 100%; height: 400px; margin: 0 auto"></div>
                        </div>
                        <?php
                    endif;
                    if (!empty($topSaleProductStockPermission)):
                        ?>
                        <div class="col-md-6">
                            <div id="container" style="min-width: 100%; height: 400px; margin: 0 auto"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div>
<style>
    .chosen-container{
        width: 420px !important;

    }
    .newChosen .chosen-container{
        width: 530px !important;

    }
    .newChosen2 .chosen-container{
        width: 310px !important;

    }
</style>
<script type="text/javascript">

    searchDay='<?php echo date('Y-m-d'); ?>';
    var url = '<?php echo site_url("HomeController/getCompanySummary") ?>';
    $.ajax({
        type: 'POST',
        url: url,
        data: {'searchDay': searchDay},
        success: function (data)
        {
            $("#showCompanySummary").html(data);
        }
    });


    function showCompanySummary(searchDay){
        var url = '<?php echo site_url("HomeController/getCompanySummary") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'searchDay': searchDay},
            success: function (data)
            {
                $("#showCompanySummary").html(data);
            }
        });

    }
    var searchDay='<?php echo date('Y-m-d'); ?>';
    var url = '<?php echo site_url("HomeController/getTodaySummary") ?>';
    $.ajax({
        type: 'POST',
        url: url,
        data: {'searchDay': searchDay},
        success: function (data)
        {
            $("#showTodaysSummary").html(data);
        }
    });

    function showTodaySummary(searchDay){

        var url = '<?php echo site_url("HomeController/getTodaySummary") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'searchDay': searchDay},
            success: function (data)
            {
                $("#showTodaysSummary").html(data);
            }
        });

    }



    var monthId='<?php echo date('Y-m'); ?>';
    var url = '<?php echo site_url("HomeController/getIncentiveMonthWise") ?>';
    $.ajax({
        type: 'POST',
        url: url,
        data: {'monthId': monthId},
        success: function (data)
        {
            $("#loadIncentive").html(data);
        }
    });

    function showIncentiveSummary(monthId){
        var monthId=$("#monthYear").val();
        var url = '<?php echo site_url("HomeController/getIncentiveMonthWise") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'monthId': monthId},
            success: function (data)
            {
                $("#loadIncentive").html(data);
            }
        });

    }




    Highcharts.chart('container2', {
        title: {
            text: 'Daily Sales,Purchases Summary The month of <?php echo date('F Y', strtotime(date('Y-m-d'))); ?>'
        },
        credits: {
            enabled: false
        },
        subtitle: {
            text: ''
        },
        yAxis: {
            title: {
                text: 'Amount'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 1
            }
        },
        series: [{
                name: 'Sales',
                data: [

<?php
foreach ($fullMonthSalesList as $key => $value) {
    if (!empty($value)) {
        echo $value . ',';
    }
}
?>
                ]

            },{
                name: 'Purchases',
                data: [
<?php
foreach ($fullMonthPurchasesList as $key => $value) {
    if (!empty($value)) {
        echo $value . ',';
    }
}
?>]}],
                    responsive: {
                        rules: [{
                                condition: {
                                    maxWidth: 1500
                                },
                                chartOptions: {
                                    legend: {
                                        layout: 'horizontal',
                                        align: 'center',
                                        verticalAlign: 'bottom'
                                    }
                                }
                            }]
                    }
                });
</script>

<script type="text/javascript">
                Highcharts.chart('container', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Top Sales Product List'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: [
                            '1',

                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'TOP Sales Product (Qty)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} Qty</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [

<?php foreach ($topSalesProduct as $each): ?>

                            {showInLegend: false,
                                name: '<?php echo $each->productName; ?>',
                                data: [<?php echo $each->totalQty; ?>]
                            },
<?php endforeach; ?>

                    ]
                });
</script>
<script type="text/javascript">
                Highcharts.chart('inventoryStock', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Inventory Product Stock'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: [
                            '1',

                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Inventory Product Stock (Qty)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} Qty</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [

<?php
foreach ($inventoryAllStock as $key => $each):
    $productStockInfo = explode("-", $each);
// dumpVar($productStockInfo);
    ?>

                                {showInLegend: false,
                                    name: '<?php echo $productStockInfo[1]; ?>',
                                    data: [<?php echo $productStockInfo[0]; ?>]
                                },
<?php endforeach; ?>

                    ]
                });
</script>
<script type="text/javascript">
                Highcharts.chart('companySummary', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Company Summary'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: [
                            '1',

                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Company Summary by 1k(1000)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} BDT</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [


                        {
                            name: 'Account Receiable',
                            data: [<?php echo $accountReceiable; ?>]
                        },
                        {
                            name: 'Account Payable',
                            data: [<?php echo abs($accountPayable); ?>]
                        },
                        {
                            name: 'Cash in Hand',
                            data: [<?php echo $cashInHand; ?>]
                        },
                        {
                            name: 'Cash At Bank',
                            data: [<?php echo $totalCashAtBank; ?>]
                        },
                        {
                            name: 'Total Sales',
                            data: [<?php echo $totalSalesAmount; ?>]
                        },

                    ]
                });
</script>




