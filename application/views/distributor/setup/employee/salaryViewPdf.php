



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
.table-scrollable {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    border: 1px solid #e7ecf1;
    margin: 10px 0!important;
}

table {
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
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
                              <strong>Date:</strong><span><?php echo $getAllsalaryByDateListView[0]->date; ?></span> <strong> <br>Month:</strong><span><?php echo $getAllsalaryByDateListView[0]->month; ?></span>
                                            <br><strong>Year:</strong><span><?php echo $getAllsalaryByDateListView[0]->year; ?></span>
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">

                                    <table id="example" class="table-bordered"  >
                                        <caption>
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

                            </div>
                        </div>





            <!-- End: life time stats -->
        </div>
    </div>
</div>
</div>


<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>


