<!-- BEGIN PAGE HEADER-->
<!-- BEGIN THEME PANEL -->

<!-- END THEME PANEL -->
<!-- BEGIN PAGE BAR -->


<!-- END PAGE BAR -->
<!-- BEGIN DASHBOARD STATS 1-->
<?php //$cashInHand=$cashInHand<0?'('.abs($cashInHand).')':$cashInHand;  echo $cashInHand;//=abs($cashInHand); echo number_format($cashInHand>0?($cashInHand):0.00,2)echo  $cashInHand?>
<div id="loading" >
    <div class="row" id="dashbordSummary">
    </div>

</div>
<div class="row" id="hideAfterLoad">
    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number" style="font-size:25px;">


                    <?php /*if ($totalSalesAmount < 0) {
                        $totalSalesAmount = ($totalSalesAmount * -1);
                        echo $totalSalesAmount = "(<span data-counter='counterup' data-value=" . number_format($totalSalesAmount, 2) . "></span>)";


                    } else {
                        echo $totalSalesAmount = "<span data-counter='counterup' data-value=" . number_format($totalSalesAmount, 2) . "></span>";
                    } */?> &#2547;

                </div>
                <div class="desc"> <?php /*echo get_phrase('Product_sales') */?> </div>
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

                    <?php /*if ($inventoryAmount < 0) {
                        $inventoryAmount = ($inventoryAmount * -1);
                        echo $inventoryAmount = "(<span data-counter='counterup' data-value=" . number_format($inventoryAmount, 2) . "></span>)";


                    } else {
                        echo $inventoryAmount = "<span data-counter='counterup' data-value=" . number_format($inventoryAmount, 2) . "></span>";
                    } */?> &#2547;
                </div>
                <div class="desc"> <?php /*echo get_phrase('Inventory') */?> </div>
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

                    <?php /*if ($totalCashAtBank < 0) {
                        $totalCashAtBank = ($totalCashAtBank * -1);
                        echo $totalCashAtBank = "(<span data-counter='counterup' data-value=" . number_format($totalCashAtBank, 2) . "></span>)";


                    } else {
                        echo $totalCashAtBank = "<span data-counter='counterup' data-value=" . number_format($totalCashAtBank, 2) . "></span>";
                    } */?> &#2547;


                </div>
                <div class="desc"> <?php /*echo get_phrase('Cash_At_Bank') */?> </div>
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
                    <?php /*if ($cashInHand < 0) {
                        $cashInHand = ($cashInHand * -1);
                        echo $cashInHand = "(<span data-counter='counterup' data-value=" . number_format($cashInHand, 2) . "></span>)";


                    } else {
                        echo $cashInHand = "<span data-counter='counterup' data-value=" . number_format($cashInHand, 2) . "></span>";
                    } */?> &#2547;

                </div>
                <div class="desc"> <?php /*echo get_phrase('Cash_In_Hand') */?> </div>
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
                    <?php /*if ($accountPayable <= 0) {
                        $accountPayable = ($accountPayable * -1);
                        echo $accountPayable = "<span data-counter='counterup' data-value=" . number_format($accountPayable, 2) . "></span>";


                    } else {
                        echo $accountPayable = "(<span data-counter='counterup' data-value=" . number_format($accountPayable, 2) . "></span>)";
                    } */?> &#2547;
                </div>
                <div class="desc"> <?php /*echo get_phrase('Account_Payable') */?> </div>
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
                    <?php /*if ($accountReceiable < 0) {
                        $accountReceiable = ($accountReceiable * -1);
                        echo $accountReceiable = "(<span data-counter='counterup' data-value=" . number_format($accountReceiable, 2) . "></span>)";


                    } else {
                        echo $accountReceiable = "<span data-counter='counterup' data-value=" . number_format($accountReceiable, 2) . "></span>";
                    } */?> &#2547;
                </div>
                <div class="desc"> <?php /*echo get_phrase('Account_Receivable') */?> </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?php echo get_phrase('Date_Wise_Purchase') ?></span>

                </div>

            </div>
            <div class="portlet-body">
                <div id="site_statistics_loading2">
                    <img src="<?php echo base_url('assets/global/img/loading.gif'); ?>" alt="loading"/></div>
                <div id="site_statistics_content2" class="display-none">
                    <div id="site_statistics2" class="chart"></div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?php echo get_phrase('Date_Wise_Sales') ?></span>

                </div>

            </div>
            <div class="portlet-body">
                <div id="site_statistics_loading">
                    <img src="<?php echo base_url('assets/global/img/loading.gif'); ?>" alt="loading"/></div>
                <div id="site_statistics_content" class="display-none">
                    <div id="site_statistics" class="chart"></div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>


</div>
<div class="row">
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-share font-red-sunglo hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?php echo get_phrase('Month_Wise_Purchase') ?></span>

                </div>

            </div>
            <div class="portlet-body">
                <div id="month_wise_purchase_loading">
                    <img src="<?php echo base_url('assets/global/img/loading.gif'); ?>" alt="loading"/></div>
                <div id="month_wise_purchase_content" class="display-none">
                    <div id="month_wise_purchase" style="height: 228px;"></div>
                </div>
                <div style="margin: 20px 0 10px 30px">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                            <span class="label label-sm label-success"> <?php echo get_phrase('Purchase') ?></span>
                            <!--<h3>৳13,23400</h3>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-share font-red-sunglo hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?php echo get_phrase('Month_Wise_Sales') ?></span>

                </div>

            </div>
            <div class="portlet-body">
                <div id="site_activities_loading">
                    <img src="<?php echo base_url('assets/global/img/loading.gif'); ?>" alt="loading"/></div>
                <div id="site_activities_content" class="display-none">
                    <div id="site_activities" style="height: 228px;"></div>
                </div>
                <div style="margin: 20px 0 10px 30px">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                            <span class="label label-sm label-success"> <?php echo get_phrase('Sales'); ?></span>
                            <!-- <h3>৳13,23400</h3>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>


</div>


<div class="row">
    <div class="col-md-6">
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <div id=""></div>

    </div>
    <div class="col-md-6"></div>
</div>
<!-- END CONTENT -->
<!-- BEGIN QUICK SIDEBAR -->
<!-- END QUICK SIDEBAR -->
<script type="text/javascript">
    /*$(document).ready(function(){
        google.charts.load('current', {'packages':['corechart']});

        google.charts.setOnLoadCallback(barDisassembly);

        function barDisassembly() {
            var data2 = google.visualization.arrayToDataTable([
                ['01', '100'],
                ['02', 1500000],
                ['03', 100],
                ['04', 1111],
                ['05', 2222],
                ['06', 3333],
                ['07', 444],
                ['08', 777],
                ['09', 1],
                ['10', 777],
                ['11', 899],
                ['12', 123],
                ['13', 567],
                ['14', 222],
                ['15', 1000],
                ['16', 2000],
                ['17', 5656],
                ['19', 900],
                ['20', 333],
                ['21', 1231],
                ['22', 1111],
                ['23', 567],
                ['24', 111],
            ]);

            var data_jason=;
            console.log(data_jason);
            var data = google.visualization.arrayToDataTable(data_jason);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1, {
                calc: 'stringify',
                role: 'annotation',
                sourceColumn: 1,
                type: 'string'
            }]);

            var options = {
                chartArea: { 'left': 100, 'top': 20, 'right': 5, 'bottom': 100 },
                //width: 400,
                //height: 400,
               /!* legend: {
                    position: 'top',
                    maxLines: 3
                },*!/
                bar: { groupWidth: "25%" },
                isStacked: true,



                pointSize: 5,
                series: [{ 'color': 'blue'}],

                vAxis: {
                    gridlines: { count: 10 },
                    logScale: false,
                    stripLines: true

                },
                hAxis: {
                    slantedText: true,
                    slantedTextAngle: 40, // here you can even use 180
                    gridlines: { count: 12 },
                    logScale: false,
                    stripLines: true
                },

                curveType: 'function',
                lineWidth: 4,
                intervals: { 'style': 'bar' },
                legend: 'none'


            };


            var bar = new google.visualization.ColumnChart(document.getElementById('bar_disassembly'));

            bar.draw(view, options);
        }
    });*/
    var Dashboard = function () {
        return {


            initCharts: function () {
                function e(e, t, a, i) {
                    $('<div id="tooltip" class="chart-tooltip">' + i + "</div>").css({
                        position: "absolute",
                        display: "none",
                        top: t - 40,
                        left: e - 40,
                        border: "0px solid #ccc",
                        padding: "2px 6px",
                        "background-color": "#fff"
                    }).appendTo("body").fadeIn(200)
                }

                if (jQuery.plot) {
                    var date_wise_sales_string = '<?php echo $date_wise_sales_array?>';
                    var t = JSON.parse(date_wise_sales_string);

                    var date_wise_purchase_string = '<?php echo $date_wise_purchase_array?>';
                    var t2 = JSON.parse(date_wise_purchase_string);

                    var tt2 = [
                        ["2", 1500],
                        ["05", 800],
                        ["06", 1500],
                        ["07", 2350],
                        ["08", 1500],
                        ["09", 1300],
                        ["10", 2300],
                        ["11", 7300],
                        ["12", 9300],
                        ["13", 2300],
                        ["14", 6300],
                        ["15", 8300],
                        ["16", 4300],
                        ["17", 5300],
                        ["18", 8300],
                        ["19", 5300],
                        ["20", 2300],
                        ["21", 3300],
                        ["22", 1300],
                        ["23", 4300],
                        ["24", 6300],
                        ["25", 7300],
                        ["26", 300],
                        ["27", 1300],
                        ["28", 2300],
                        ["29", 4300],
                        ["30", 3300],
                        ["31", 4600]
                    ];

                    if (0 != $("#site_statistics").size()) {
                        $("#site_statistics_loading").hide(), $("#site_statistics_content").show();
                        var a = ($.plot($("#site_statistics"), [{
                            data: t,
                            lines: {
                                fill: .6,
                                lineWidth: 0
                            },
                            color: ["#f89f9f"]
                        }, {
                            data: t,
                            points: {
                                show: !0,
                                fill: !0,
                                radius: 5,
                                fillColor: "#f89f9f",
                                lineWidth: 3
                            },
                            color: "#fff",
                            shadowSize: 0
                        }], {
                            xaxis: {
                                tickLength: 0,
                                tickDecimals: 0,
                                mode: "categories",
                                min: 0,
                                font: {
                                    lineHeight: 14,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#6F7B8A"
                                }
                            },
                            yaxis: {
                                ticks: 5,
                                tickDecimals: 0,
                                tickColor: "#eee",
                                font: {
                                    lineHeight: 14,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#6F7B8A"
                                }
                            },
                            grid: {
                                hoverable: !0,
                                clickable: !0,
                                tickColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1
                            }
                        }), null);
                        $("#site_statistics").bind("plothover", function (t22, i, l) {
                            if ($("#x").text(i.x.toFixed(2)), $("#y").text(i.y.toFixed(2)), l) {
                                if (a != l.dataIndex) {
                                    a = l.dataIndex, $("#tooltip").remove();
                                    l.datapoint[0].toFixed(2), l.datapoint[1].toFixed(2);
                                    e(l.pageX, l.pageY, l.datapoint[0], l.datapoint[1] + " Taka")
                                }
                            } else $("#tooltip").remove(), a = null
                        })
                    }
                    if (0 != $("#site_statistics2").size()) {
                        console.log(t2);
                        $("#site_statistics_loading2").hide(), $("#site_statistics_content2").show();
                        var a = ($.plot($("#site_statistics2"), [{
                            data: t2,
                            lines: {
                                fill: .6,
                                lineWidth: 0
                            },
                            color: ["#4d87e3"]
                        }, {
                            data: t2,
                            points: {
                                show: !0,
                                fill: !0,
                                radius: 5,
                                fillColor: "#f89f9f",
                                lineWidth: 3
                            },
                            color: "#fff",
                            shadowSize: 0
                        }], {
                            xaxis: {
                                tickLength: 0,
                                tickDecimals: 0,
                                mode: "categories",
                                min: 0,
                                font: {
                                    lineHeight: 14,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#6F7B8A"
                                }
                            },
                            yaxis: {
                                ticks: 5,
                                tickDecimals: 0,
                                tickColor: "#eee",
                                font: {
                                    lineHeight: 14,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#4d87e3"
                                }
                            },
                            grid: {
                                hoverable: !0,
                                clickable: !0,
                                tickColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1
                            }
                        }), null);
                        $("#site_statistics2").bind("plothover", function (t2, i, l) {
                            if ($("#x").text(i.x.toFixed(2)), $("#y").text(i.y.toFixed(2)), l) {
                                if (a != l.dataIndex) {
                                    a = l.dataIndex, $("#tooltip").remove();
                                    l.datapoint[0].toFixed(2), l.datapoint[1].toFixed(2);
                                    e(l.pageX, l.pageY, l.datapoint[0], l.datapoint[1] + " Taka")
                                }
                            } else $("#tooltip").remove(), a = null
                        })
                    }
                    if (0 != $("#site_activities").size()) {
                        var i = null;
                        $("#site_activities_loading").hide(), $("#site_activities_content").show();


                        var l =<?php echo $month_wise_sales_array?>;
                        /* var l = [
                              ["DEyyC", 300],
                              ["JAN", 600],
                              ["FEB", 1100],
                              ["MAR", 1200],
                              ["APR", 860],
                              ["MAY", 1200],
                              ["JUN", 1450],
                              ["JUL", 1800],
                              ["AUG", 1200],
                              ["SEP", 600]
                          ];*/
                        $.plot($("#site_activities"), [{
                            data: l,
                            lines: {
                                fill: .2,
                                lineWidth: 0
                            },
                            color: ["#BAD9F5"]
                        }, {
                            data: l,
                            points: {
                                show: !0,
                                fill: !0,
                                radius: 4,
                                fillColor: "#9ACAE6",
                                lineWidth: 2
                            },
                            color: "#9ACAE6",
                            shadowSize: 1
                        }, {
                            data: l,
                            lines: {
                                show: !0,
                                fill: !1,
                                lineWidth: 3
                            },
                            color: "#9ACAE6",
                            shadowSize: 0
                        }], {
                            xaxis: {
                                tickLength: 0,
                                tickDecimals: 0,
                                mode: "categories",
                                min: 0,
                                font: {
                                    lineHeight: 18,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#6F7B8A"
                                },

                            },
                            yaxis: {
                                ticks: 5,
                                tickDecimals: 0,
                                tickColor: "#eee",
                                font: {
                                    lineHeight: 14,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#6F7B8A"
                                }
                            },
                            grid: {
                                hoverable: !0,
                                clickable: !0,
                                tickColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1
                            }
                        });
                        $("#site_activities").bind("plothover", function (t, a, l) {
                            if ($("#x").text(a.x.toFixed(2)), $("#y").text(a.y.toFixed(2)), l && i != l.dataIndex) {
                                i = l.dataIndex, $("#tooltip").remove();
                                l.datapoint[0].toFixed(2), l.datapoint[1].toFixed(2);
                                e(l.pageX, l.pageY, l.datapoint[0], l.datapoint[1] + " ৳")
                            }
                        }), $("#site_activities").bind("mouseleave", function () {
                            $("#tooltip").remove()
                        })
                    }
                    if (0 != $("#month_wise_purchase").size()) {
                        var i = null;
                        $("#month_wise_purchase_loading").hide(), $("#month_wise_purchase_content").show();


                        var l =<?php echo $month_wise_purchase_array?>;
                        /* var l = [
                              ["DEyyC", 300],
                              ["JAN", 600],
                              ["FEB", 1100],
                              ["MAR", 1200],
                              ["APR", 860],
                              ["MAY", 1200],
                              ["JUN", 1450],
                              ["JUL", 1800],
                              ["AUG", 1200],
                              ["SEP", 600]
                          ];*/
                        $.plot($("#month_wise_purchase"), [{
                            data: l,
                            lines: {
                                fill: .2,
                                lineWidth: 0
                            },
                            color: ["#32c5d2"]
                        }, {
                            data: l,
                            points: {
                                show: !0,
                                fill: !0,
                                radius: 4,
                                fillColor: "#9ACAE6",
                                lineWidth: 2
                            },
                            color: "#9ACAE6",
                            shadowSize: 1
                        }, {
                            data: l,
                            lines: {
                                show: !0,
                                fill: !1,
                                lineWidth: 3
                            },
                            color: "#9ACAE6",
                            shadowSize: 0
                        }], {
                            xaxis: {
                                tickLength: 0,
                                tickDecimals: 0,
                                mode: "categories",
                                min: 0,
                                font: {
                                    lineHeight: 18,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#6F7B8A"
                                }
                            },
                            yaxis: {
                                ticks: 5,
                                tickDecimals: 0,
                                tickColor: "#eee",
                                font: {
                                    lineHeight: 14,
                                    style: "normal",
                                    variant: "small-caps",
                                    color: "#6F7B8A"
                                }
                            },
                            grid: {
                                hoverable: !0,
                                clickable: !0,
                                tickColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1
                            }
                        });
                        $("#month_wise_purchase").bind("plothover", function (t, a, l) {
                            if ($("#x").text(a.x.toFixed(2)), $("#y").text(a.y.toFixed(2)), l && i != l.dataIndex) {
                                i = l.dataIndex, $("#tooltip").remove();
                                l.datapoint[0].toFixed(2), l.datapoint[1].toFixed(2);
                                e(l.pageX, l.pageY, l.datapoint[0], l.datapoint[1] + " ৳")
                            }
                        }), $("#month_wise_purchase").bind("mouseleave", function () {
                            $("#tooltip").remove()
                        })
                    }
                }
            },


            init: function () {
                this.initCharts()
            }
        }
    }();
    App.isAngularJsApp() === !1 && jQuery(document).ready(function () {
        Dashboard.init();

    });


</script>

<script type='text/javascript'>
    $(document).ready(function () {
        $("#hideAfterLoad").LoadingOverlay("show");
        setTimeout(function () {

            $.ajax({
                url: "<?php echo site_url();?>lpg/HomeController/dashbordTodaySummary",
                type: 'POST',
                //dataType: 'JSON',

                success: function (data) {


                    $('#dashbordSummary').html(data);

                }, complete: function () {
                    $("#hideAfterLoad").LoadingOverlay("hide", true);
                    $("#hideAfterLoad").hide();
                }

            });
        },100)


        // event.preventDefault();
    });



</script>