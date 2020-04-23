






<?php
//echo '<pre>';
//print_r($getAllsalaryByDateListView);
//exit;
?>
<style>
    body {
    color: #333;
    padding: 0!important;
    margin: 0!important;
    direction: "ltr";
    font-size: 11px;
}
tfoot tr, thead tr {
	background: lightblue;
}
tfoot td {
	font-weight:bold;
}
div.dataTables_wrapper  div.dataTables_filter {
  width: 100%;
  float: none;
  text-align: left;
}

.page {
    width:100%;

    min-height: 297mm;
    padding: 20mm;
    margin: 10mm auto;
    border: 1px #D3D3D3 solid;
    border-radius: 5px;
    background: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
.subpage {
    padding: 0cm;
    border: 5px black solid;
    height: 257mm;
    outline: 2cm #FFEAEA solid;
}

@page {
    size: A4;
    size: landscape;
    margin: 0;



}

@media print {
    html, body {
        margin:0 !important;
        padding:0 !important;
        height:100% !important;
        zoom: 66%;


    }

    .page .subpage .col-md-12,.col-lg-12, .col-xl-12{
        float:left;
        width:100%;

    }
    .page .subpage {
        padding: 1cm;
        border: 5px black solid;
        height: 257mm;
        outline: 2cm #FFEAEA solid;
        position:absolute;
    }
    .page {
        visibility: visible;


    }
}
</style>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-sm-12 col-md-12  col-lg-12 col-xl-12 ">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">

            <div class="portlet-body">


                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">
                                <div class="portlet green-meadow box" style="border: 1px solid #3598dc;">
                                    <div class="portlet-title" style="background-color: #3598dc;">
                                        <div class="caption">
                                            <?php echo get_phrase('Salaray Details') ?>  </div>

                                    </div>

                                </div>
                            </div>
<!--                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                        </div>

                                    </div>

                                </div>
                            </div>-->

                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">

                                    <table id="example" class="table-bordered  "  >
                                        <caption><label><strong>Date:</strong><span><?php echo $getAllsalaryByDateListView[0]->date; ?></span></label>&nbsp <label><strong>Month:</strong><span><?php echo $getAllsalaryByDateListView[0]->month; ?></span>
                                            </label>&nbsp<label><strong>Year:</strong><span><?php echo $getAllsalaryByDateListView[0]->year; ?></span></label>
<!--                                             <a class="btn btn-icon-only green" href="<?php echo site_url($this->project.'/salaryViewByCash/' . $getAllsalaryByDateListView[0]->date.'/'.$getAllsalaryByDateListView[0]->month.'/'.$getAllsalaryByDateListView[0]->year.'/'.Cash ); ?>">

                                            Cash</a>
                                        <a class="btn btn-icon-only green" href="<?php echo site_url($this->project.'/salaryViewByBank/' . $getAllsalaryByDateListView[0]->date.'/'.$getAllsalaryByDateListView[0]->month.'/'.$getAllsalaryByDateListView[0]->year.'/'.Bank ); ?>">

                                            Bank</a>-->
                                        </caption>
                                        <thead>
                                        <tr>
                                            <th width="2%"> # </th>
                                            <th width="10%"> Name of Employee </th>
                                            <th width="8%">Designation</th>
                                            <th width="8%">Department/ Section</th>
                                            <th width="5%">Payment Mode</th>
                                            <th width="5%">Basic Salary</th>
                                            <th width="5%">House Rant Allowance</th>
                                            <th width="5%">Conveyance Allowance</th>
                                            <th width="5%">Medical Allowance</th>
                                            <th width="5%">Others</th>
                                            <th width="5%">Gross Salary</th>
                                            <th width="5%">Arrear Salary</th>
                                            <th width="5%">WPF Deduction</th>
                                            <th width="5%">Absent Deduction</th>
                                            <th width="5%">Loan Deduction</th>
                                            <th width="5%">AIT Deduction</th>
                                            <th width="5%">Net Pay Amount</th>
                                            <th width="18%"> Signature</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $graunatTotal = 0;
                                            foreach ($getAllsalaryByDateListView as $key => $value):
                                            $graunatTotal += $value->netPayAmount;
                                            $graunatTotalbasicSalary += $value->basicSalary;
                                            $graunatTotalhouseRant += $value->houseRant;
                                            $graunatTotalconveyanceAllowance += $value->conveyanceAllowance;
                                            $graunatTotalmedicalAllowance += $value->medicalAllowance;
                                            $graunatTotalothers += $value->others;
                                            $graunatTotalgrossSalary += $value->grossSalary;
                                            $graunatTotalarrearSalary += $value->arrearSalary;
                                            $graunatTotalwPFDeduction += $value->wPFDeduction;
                                            $graunatTotalabsentDeduction += $value->absentDeduction;
                                            $graunatTotalloanDeduction += $value->loanDeduction;
                                            $graunatTotalaITDeduction += $value->aITDeduction;
                                            $graunatTotalnetPayAmount += $value->netPayAmount;
                                             ?>
                                            <tr>
                                                <td ><?php echo $key + 1; ?></td>
                                                <td ><?php echo $value->name; ?></td>
                                                <td ><?php echo $value->DesignationName; ?></td>
                                                <td ><?php echo $value->DepartmentName; ?></td>
                                                <td > <?php echo $value->paymentMode; ?></td>
                                                <td align="right"><?php echo $value->basicSalary; ?></td>
                                                <td align="right"><?php echo $value->houseRant; ?></td>
                                                <td align="right"><?php echo $value->conveyanceAllowance; ?></td>
                                                <td align="right"><?php echo $value->medicalAllowance; ?></td>
                                                <td align="right"><?php echo $value->others; ?></td>
                                                <td align="right"><?php echo $value->grossSalary; ?></td>
                                                <td align="right"><?php echo $value->arrearSalary; ?></td>
                                                <td align="right"><?php echo $value->wPFDeduction; ?></td>
                                                <td align="right"><?php echo $value->absentDeduction; ?></td>
                                                <td align="right"><?php echo $value->loanDeduction; ?></td>
                                                <td align="right"><?php echo $value->aITDeduction; ?></td>
                                                <td align="right"><?php echo $value->netPayAmount; ?></td>
                                                 <td ></td>


                                            </tr>

                                            <?php

                                        endforeach; ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" >
                                                    Total
                                                </td>
                                                <td align="right"><?php  echo number_format($graunatTotalbasicSalary, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalhouseRant, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalconveyanceAllowance, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalmedicalAllowance, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalothers, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalgrossSalary, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalarrearSalary, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalwPFDeduction, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalabsentDeduction, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalloanDeduction, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalaITDeduction, 2);?></td>
                                                <td align="right"><?php  echo number_format($graunatTotalnetPayAmount, 2);?></td>
                                                <td></td>
                                            </tr>

                                        </tfoot>
                                    </table>




                                 <div class="invoice-block pull-right">

                                      <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                       onclick="javascript:window.print();"> <?php echo get_phrase('Print') ?>
                                        <i class="fa fa-print"></i>
                                    </a>
                                 </div>
                            </div>
                        </div>





            <!-- End: life time stats -->
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
	// DataTable initialisation
	$('#example').DataTable(
		{
			"paging": true,
                        "ordering": false,
			"autoWidth": false,
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api();
				nb_cols = api.columns().nodes().length;
				var j = 3;
				while(j < nb_cols){
					var pageTotal = api
                .column( j, { page: 'current'} )
                .data()
                .reduce( function (a, b) {

                    return parseFloat(a,2) + parseFloat(b,2);
                }, 0 );
          // Update footer
          $( api.column( j ).footer() ).html(pageTotal);
					j++;
				}
			}
		}
	);
});
</script>

<script>
        function printDiv() {
            var divContents = document.getElementById("GFG").innerHTML;
            var a = window.open('', '', 'height=500, width=500');
            a.document.write('<html>');
            a.document.write('<body > <h1>Div contents are <br>');
            a.document.write(divContents);
            a.document.write('</body></html>');
            a.document.close();
            a.print();
        }
    </script>

<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>








