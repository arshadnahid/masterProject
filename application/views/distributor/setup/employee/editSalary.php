<?php
//echo '<pre>';
//print_r($getAllsalaryByDateListView[0]->month);
//exit();
?>

<div class="main-content">
    <div class="main-content-inner">
<form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <div class="row">
                 <div class="col-md-12">
                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right"
                                           for="form-field-1"> <?php echo get_phrase('Date') ?>
                                    </label>
                                    <div class="col-sm-6">

                                        <div class="input-group">
                                            <input class="form-control date-picker" name="date"
                                                   id="Date" type="text" value="<?php echo $getAllsalaryByDateListView[0]->date; ?>"
                                                   data-date-format="dd-mm-yyyy" required/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                     <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right" for="form-field-1"> Month</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="month" name="month" >
                                            <option selected value='' disabled>--Select--</option>

                                            <option <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'January') {
                                            echo "selected";
                                        }
                                        ?>
                                                value="January">January</option>
                                            <option
                                                <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'February') {
                                            echo "selected";
                                        }
                                        ?>value="February">February</option>
                                            <option
                                                <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'March') {
                                            echo "selected";
                                        }
                                        ?>value="March">March</option>
                                            <option
                                                <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'April') {
                                            echo "selected";
                                        }
                                        ?>value="April">April</option>
                                            <option<?php
                                        if ($getAllsalaryByDateListView[0]->month == 'May') {
                                            echo "selected";
                                        }
                                        ?>
                                                value="May">May</option>
                                            <option <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'June') {
                                            echo "selected";
                                        }
                                        ?>value="June">June</option>
                                            <option<?php
                                        if ($getAllsalaryByDateListView[0]->month == 'July') {
                                            echo "selected";
                                        }
                                        ?> value="July">July</option>
                                            <option<?php
                                        if ($getAllsalaryByDateListView[0]->month == 'August') {
                                            echo "selected";
                                        }
                                        ?> value="August">August</option>
                                            <option <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'September') {
                                            echo "selected";
                                        }
                                        ?>value="September">September</option>
                                            <option <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'October') {
                                            echo "selected";
                                        }
                                        ?>value="October">October</option>
                                            <option<?php
                                        if ($getAllsalaryByDateListView[0]->month == 'November') {
                                            echo "selected";
                                        }
                                        ?> value="November">November</option>
                                            <option <?php
                                        if ($getAllsalaryByDateListView[0]->month == 'December') {
                                            echo "selected";
                                        }
                                        ?> value="December">December</option>


                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right" for="form-field-1"> year </label>
                                    <div class="col-sm-6">

                                        <select class="form-control" id="year" name="year">
                                            <option selected disabled>--Select--</option>
                                            <option <?php
                                        if ($getAllsalaryByDateListView[0]->year == '2020') {
                                            echo "selected";
                                        }
                                        ?> value="2020">2020</option>
                                            <option <?php
                                                if ($getAllsalaryByDateListView[0]->year == '2021') {
                                                    echo "selected";
                                                }
                                        ?> value="2021">2021</option>
                                        </select>

                                    </div>
                                </div>

                            </div>

                        </div>
            </div>

        </div>
        <div class="clearfix"></div>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">

                        <div class="col-md-12 table-responsive" >


                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead class="table-header">
                            <tr>
                                <th>SL No </th>

                                <th><input type="checkbox" id="select_all" /> Name of Employee </th>
                                <th>Department/Section</th>
                                <th>Payment Mode</th>
                                <th>Basic Salary</th>
                                <th>House Rant Allowance</th>
                                <th>Conveyance Allowance</th>
                                <th>Medical Allowance</th>
                                <th>WPF Deduction</th>
                                <th>Gross Salary</th>
                                <th>Arrear Salary</th>
                                <th>Absent Deduction</th>
                                <th>Loan Deduction</th>
                                <th>AIT Deduction</th>
                                <th>Net Pay Amount</th>


                            </tr>
                        </thead>
                        <tbody>

                                     <?php foreach ($employeewisedep as $key => $value): ?>

                                     <?php foreach ($getAllsalaryByDateListView as $key => $editsalary):?>
                                <tr>


                                    <td >
                                        <?php echo $key + 1; ?>

                                    </td>


                                    <?php if($editsalary->employeeID = $employeewisedep-id) ?>

                                    <td style="width: 8%">


                                         <input type="checkbox" name="employeeCheckBox[]" class="checkbox" />
                                           <?php echo $value->name; ?>
                                            <input type="hidden" id="employeeID" name="employeeID[]" value="<?php echo $value->id; ?>" />

                                    </td>
                                    <td style="width: 8%">
                                       <input type="hidden" id="departmentID" name="departmentID[]" value="<?php echo $value->department; ?>" class="form-control"placeholder="" style="text-align: right;"/>
                                           <?php echo $value->DepartmentName; ?>
                                    </td>
                                    <td style="width: 8%">

                                        <input type="hidden" id="paymentMode" name="paymentMode[]" value="<?php echo $value->salaryType; ?>" class="form-control"
                                               placeholder="" style="text-align: right;"/>
                                           <?php echo $value->salaryType; ?>

                                    </td>
                                    <td>
                                        <input type="text" id="basicSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="basicSalary[]" value="0" class="form-control basicSalary "placeholder=""
                                               onclick="this.select();" style="text-align: right;"/>
                                    </td>

                                    <td>
                                        <input type="text" id="houseRant_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>"  name="houseRant[]" value="0" class="form-control houseRant"placeholder=""
                                               onclick="this.select();" style="text-align: right;"/>
                                    </td>

                                    <td><input type="text" id="conveyanceAllowance_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="conveyanceAllowance[]" value="0" class="form-control conveyanceAllowance" placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="medicalAllowance_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="medicalAllowance[]" value="0" class="form-control medicalAllowance"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="wPFDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="wPFDeduction[]" value="0" class="form-control wPFDeduction"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="grossSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="grossSalary[]" value="0" class="form-control grossSalary"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="arrearSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="arrearSalary[]" value="0" class="form-control arrearSalary"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="absentDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="absentDeduction[]" value="0" class="form-control absentDeduction"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="loanDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="loanDeduction[]" value="0" class="form-control loanDeduction"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="aITDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="aITDeduction[]" value="0" class="form-control aITDeduction"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="netPayAmount_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="netPayAmount[]" value="0" class="form-control netPayAmount"placeholder=""
                                               onclick="this.select();" style="text-align: right;"/></td>
                                    <td></td>

                                </tr>
                                <tr>
                                    <td colspan="14" class="pul-right">total Net Pay</td>

                                </tr>
                                <?php endforeach; endforeach; ?>


                        </tbody>
                    </table>


                        </div>


                        <div class="col-sm-12">
                            <div class="clearfix form-actions" >
                                <div class="col-md-2">


                                </div>
                                <div class="col-md-2">

                                          Narration: <input type="text" id="" name="narration" value="" class="form-control"placeholder="" style="text-align: right;"/>
                                </div>
                                <div class="col-md-offset-5 col-md-8">
                                    <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </form>
    </div><!-- /.page-content -->
</div>




<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });

    $("#datepicker").datepicker( {
    format: "mm-yyyy",
    viewMode: "months",
    minViewMode: "months"
});




</script>

<script type="text/javascript">
$(document).ready(function(){
    $( ".basicSalary" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});

$( ".houseRant" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".conveyanceAllowance" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".medicalAllowance" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".wPFDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".grossSalary" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".arrearSalary" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".absentDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".loanDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});
$( ".aITDeduction" ).keyup(function() {
var id=$(this).attr('attr-id');
calCUlation(id);
});




function calCUlation(id){

    var basicSalary=parseFloat($('#basicSalary_'+id).val()).toFixed(2);
    var houseRant=parseFloat($('#houseRant_'+id).val()).toFixed(2);
    var conveyanceAllowance=parseFloat($('#conveyanceAllowance_'+id).val()).toFixed(2);
    var medicalAllowance=parseFloat($('#medicalAllowance_'+id).val()).toFixed(2);
    var wPFDeduction=parseFloat($('#wPFDeduction_'+id).val()).toFixed(2);
    var grossSalary=parseFloat($('#grossSalary_'+id).val()).toFixed(2);
    var arrearSalary=parseFloat($('#arrearSalary_'+id).val()).toFixed(2);
    var absentDeduction=parseFloat($('#absentDeduction_'+id).val()).toFixed(2);
    var loanDeduction=parseFloat($('#loanDeduction_'+id).val()).toFixed(2);
    var aITDeduction=parseFloat($('#aITDeduction_'+id).val()).toFixed(2);


    var total = parseFloat(((basicSalary*1) + (houseRant*1)+ (conveyanceAllowance*1)+ (medicalAllowance*1)+ (grossSalary*1)+ (arrearSalary*1)- (absentDeduction*1) - (loanDeduction*1) - (aITDeduction*1) -(wPFDeduction*1) )).toFixed(2);


    $('#netPayAmount_'+id).val(total);




}



    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });

    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>
<script>
 function isconfirm2(){

        var month=$("#month").val();
        //alert(month);
        var year=$("#year").val();
        var employeeCheckBox=$("#checkbox").val();
         var basicSalary=parseFloat($('#basicSalary_').val()).toFixed(2);

        if(month == null){
            swal("Month Can't be empty!", "Validation Error!", "error");
        }else if(year == null){
            swal("year  can't be empty!", "Validation Error!", "error");
        }else if(employeeCheckBox == ''){
            swal("Employee CheckBox  can't be empty!", "Validation Error!", "error");
        }else if(basicSalary == ''){
            swal("basicSalary  can't be empty!", "Validation Error!", "error");
        }else{
            swal({
                title: "Are you sure ?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#73AE28',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true,
                type: 'success'
            },
            function (isConfirm) {
                if (isConfirm) {
                    $("#publicForm").submit();
                }else{
                    return false;
                }
            });
        }
    }




</script>
<script type="text/javascript">

	$(document).ready(function() {
    $('#adjustmentList').DataTable();
});

//  $(document).ready(function () {
//
//        $('#basicSalary').blur(function () {
//            var basicSalary = parseFloat($(this).val());
//
//            if (isNaN(basicSalary)) {
//                basicSalary = 0;
//            }
//            $(this).val(parseFloat(basicSalary.toFixed(2)));
//
//             $('#basicSalary').keyup(function () {
//            basicSalaryCalIn($h = 0, $bs = basicSalary);
//        });
//
//        });
//
//        $('#houseRant').keyup(function () {
//            basicSalaryCalIn($h = 4, $bs = 0);
//        });
//
//    });
//
//    function basicSalaryCalIn($h, $bs ) {
//
//
//        var basicSalary = $bs;
//        var houseRant = $h;
//
//        var total = parseFloat((basicSalary + houseRant).toFixed(2));
//        console.log(netPayAmount);
//        $('#netPayAmount').val(total);
//    }



</script>

</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>




