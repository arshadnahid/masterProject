



<?php
//echo '<pre>';
//print_r($stockList);
//exit;
?>

<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">

            <div class="portlet-body">

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="portlet green-meadow box" style="border: 1px solid #3598dc;">
                                    <div class="portlet-title" style="background-color: #3598dc;">
                                        <div class="caption">
                                            <?php echo get_phrase(' Incentive List') ?>  </div>

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
            	 <th width="3%">#</th>
                 <th width="7%">Category Name</th>
                 <th width="8%">Brand Name </th>
                 <th width="8%">Product Name</th>
                 <th width="8%">Target Qty</th>
                 <th width="8%">Lifting </th>
                 <th width="8%">Balance Qty </th>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($incentivelist as $key => $value):
             ?>
            <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo  $value->CategoryName; ?></td>
                <td><?php echo  $value->BrandName; ?></td>
                <td><?php echo  $value->ProductName; ?></td>
                <td><?php echo  $value->TargetQty; ?></td>
                <td><?php echo  $value->Lifting; ?></td>
                <td ><?php echo  $value->BalanceQty; ?></td>


            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>

        </tfoot>
                                    </table>

                                </div>
                                <div class="hr hr8 hr-double hr-dotted"></div>


                                 <div class="invoice-block pull-right">
                                                    <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                                       onclick="javascript:window.print();"> <?php echo get_phrase('Print') ?>
                                                        <i class="fa fa-print"></i>
                                                    </a>

                                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <!-- End: life time stats -->

            <div class="clearfix">
    <div id="chart_div" style="width: 500px; "></div>
    </div>
        </div>
    </div>
</div>

 <script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js">
      </script>
      <script type = "text/javascript">
         google.charts.load('current', {packages: ['corechart','bar']});
      </script>


     <script language = "JavaScript">
         function drawChart() {
            // Define the chart to be drawn.
            var data = google.visualization.arrayToDataTable([
               ['Product Name', 'Lifting', 'TargetQty'],
               ['Product Name', '5', '10'],

          <?php
            foreach ($incentivelist as $key => $value)
            {
                 echo  "['$value->ProductName', '$value->Lifting','$value->TargetQty']";
            }
             ?>
            ]);

            var options = {title: 'Incentive Product', bars: 'vertical'};

            // Instantiate and draw the chart.
            var chart = new google.charts.Bar(document.getElementById('chart_div'));

            chart.draw(data, options);
         }
         google.charts.setOnLoadCallback(drawChart);
      </script>

