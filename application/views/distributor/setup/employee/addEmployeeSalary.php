<?php
// echo "<pre>";
//          print_r($employeefield);
//          exit();
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
                                                   id="Date" type="text" value="<?php echo date('d-m-Y'); ?>"
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
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>


                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right" for="form-field-1"> year </label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="year" name="year">
                                            <option selected disabled>--Select--</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
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

                       <div class="col-md-12 " >
 <style type="text/css">
table{
    font-size: 11px;
}
    
 </style>
<link href="<?php echo base_url('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>

                    <table id="" width="100%"  class="table table-responsive table-striped table-bordered table-hover">
                        <thead class="table-header">
                            <tr>
                                <th >SL No </th>
                                <th > All<input class="float:left" type="checkbox" id="select_all" /> </th>

                                <?php 
                                     foreach ($employeefield as $key => $value) {
                                         
                                     
                                 if($value->fieldName =='Employee Name') { ?>
                                <th> Name of Employee </th>
                                <?php }  
                                if($value->fieldName =='Designation') { ?>
                                <th> Designation </th>
                                <?php }  
                                if($value->fieldName =='Department/ Section') { ?>
                                <th> Department/ Section </th>
                                <?php }  
                                if($value->fieldName =='Payment Mode') { ?>
                                <th>Payment Mode</th>
                                <?php }  
                                if($value->fieldName =='Basic Salary') { ?>
                                <th> Basic Salary </th>
                                <?php }  
                                if($value->fieldName =='House Rant Allowance') { ?>
                                <th> House Rant Allowance </th>
                                <?php } 
                                if($value->fieldName =='Conveyance Allowance') { ?>
                                <th> Conveyance Allowance</th>
                                <?php }  
                                if($value->fieldName =='Medical Allowance') { ?>
                                <th> Medical Allowance </th>
                                <?php }  
                                if($value->fieldName =='Others') { ?>
                                <th> Others </th>
                                <?php } 
                                if($value->fieldName =='Gross Salary') { ?>
                                <th> Gross Salary </th>
                                <?php } 
                                if($value->fieldName =='Arrear Salary') { ?>
                                <th> Arrear Salary </th>
                                <?php }  
                                if($value->fieldName =='WPF Deduction') { ?>
                                <th> WPF Deduction </th>
                                <?php }  
                                if($value->fieldName =='Absent Deduction') { ?>
                                <th> Absent Deduction </th>
                                <?php }  
                                if($value->fieldName =='Loan Deduction') { ?>
                                <th> Loan Deduction </th>
                                <?php }  
                                if($value->fieldName =='AIT Deduction') { ?>
                                <th> AIT Deduction </th>
                               
                                <?php }  
                                if($value->fieldName =='Net Pay Amount') { ?>
                                <th> Net Pay Amount </th>
                                
                                 <?php }  }?>


                            </tr>
                        </thead>
                        <tbody>
                                     <?php $basic=0;
                                           $house =0;
                                     foreach ($employeewisedep as $key => $value): ?>
                                     <?php
                                          $salary = $value->salary;
                                          $basic =  $value->salary*0.6;
                                          $house = $value->salary*0.6*0.5;
                                          $convenyence = $value->salary*0.6*0.5*0.1;
                                          $medical = 500;
                                          $totalper = ($basic+$house+$convenyence+ $medical);
                                          $others = ($salary - $totalper );

                                     ?>

                                <tr>
                                    <td style="width: 2%">
                                        <?php echo $key + 1; ?>

                                    </td>
                                    <td style="width: 2%">
                                       <input type="checkbox" name="employeeCheckBox[]" id="checkbox" class="checkbox float:left" />

                                    </td>
                                     <?php 
                                     foreach ($employeefield as $key => $row) {
                                        if($row->fieldName =='Employee Name') { ?>
                                    <td style="width: 8%">
                                           <?php echo $value->name; ?>
                                            <input type="hidden" id="employeeID" name="employeeID[]" value="<?php echo $value->id; ?>" />

                                    </td>
                                <?php }  
                                if($row->fieldName =='Designation') { ?>
                                    <td style="width: 8%">
                                       <input type="hidden" id="designation" name="designation[]" value="<?php echo $value->designation; ?>" class="form-control"placeholder="" style="text-align: right;"/>
                                           <?php echo $value->DesignationName; ?>
                                    </td>
                                    <?php }  
                                if($row->fieldName =='Department/ Section') { ?>
                                    <td style="width: 5%">
                                       <input type="hidden" id="departmentID" name="departmentID[]" value="<?php echo $value->department; ?>" class="form-control"placeholder="" style="text-align: right;"/>
                                           <?php echo $value->DepartmentName; ?>
                                    </td>
                                     <?php }  
                                if($row->fieldName =='Payment Mode') { ?>

                                    <td style="width: 5%">

                                        <input type="hidden" id="paymentMode" name="paymentMode[]" value="<?php echo $value->salaryType; ?>" class="form-control"
                                               placeholder="" style="text-align: right;"/>
                                           <?php echo $value->salaryType; ?>

                                    </td>
                                    <?php }  
                                if($row->fieldName =='Basic Salary') { ?>
                                    <td style="width: 8%">
                                        <input type="text" readonly id="basicSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="basicSalary[]" value="<?php echo $value->salary*0.6; ?>" class="form-control basicSalary "placeholder=""
                                               onclick="this.select();" style="text-align: right;"/>
                                    </td>
                                    <?php }  
                                if($row->fieldName =='House Rant Allowance') { ?>
                                    <td style="width: 5%">
                                        <input type="text" readonly id="houseRant_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>"  name="houseRant[]" value="<?php echo $value->salary*0.6*0.5; ?>" class="form-control houseRant"placeholder=""
                                               onclick="this.select();" style="text-align: right;"/>
                                    </td>
                                     <?php } 
                                if($row->fieldName =='Conveyance Allowance') { ?>
                                    <td style="width: 5%">
                                        <input type="text" readonly id="conveyanceAllowance_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="conveyanceAllowance[]" value="<?php echo $value->salary*0.6*0.5*0.1; ?>" class="form-control conveyanceAllowance" placeholder="" style="text-align: right;"/>
                                    </td>
                                     <?php }  
                                if($row->fieldName =='Medical Allowance') { ?>
                                    <td style="width: 5%"> <input readonly type="text" id="medicalAllowance_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="medicalAllowance[]" value="500" class="form-control medicalAllowance"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->fieldName =='Others') { ?>
                                    <td style="width: 8%"><input readonly type="text" id="others_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="others[]" value="<?php echo  $others;  ?>" class="form-control others"placeholder="" style="text-align: right;"/></td>
                                    <?php } 
                                if($row->fieldName =='Gross Salary') { ?>
                                    <td style="width: 8%"> <input type="text" id="grossSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="grossSalary[]" value="<?php echo  $value->salary;  ?>" class="form-control grossSalary"placeholder="" style="text-align: right;"/></td>
                                    <?php } 
                                if($row->fieldName =='Arrear Salary') { ?>
                                    <td style="width: 5%"><input type="text" id="arrearSalary_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="arrearSalary[]" value="0" class="form-control arrearSalary"placeholder="" style="text-align: right;"/></td>
                                     <?php }  
                                if($row->fieldName =='WPF Deduction') { ?>
                                    <td style="width: 5%"><input type="text" id="wPFDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="wPFDeduction[]" value="0" class="form-control wPFDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->fieldName =='Absent Deduction') { ?>
                                    <td style="width: 5%"><input type="text" id="absentDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="absentDeduction[]" value="0" class="form-control absentDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->fieldName =='Loan Deduction') { ?>

                                    <td style="width: 5%"><input type="text" id="loanDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="loanDeduction[]" value="0" class="form-control loanDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->fieldName =='AIT Deduction') { ?>
                                    <td style="width: 5%"><input type="text" id="aITDeduction_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="aITDeduction[]" value="0" class="form-control aITDeduction"placeholder="" style="text-align: right;"/></td>
                                    <?php }  
                                if($row->fieldName =='Net Pay Amount') { ?>
                                    <td style="width: 8%"><input type="text" id="netPayAmount_<?php echo $value->id; ?>" attr-id="<?php echo $value->id; ?>" name="netPayAmount[]" value="<?php echo  $value->salary;  ?>" class="form-control netPayAmount"placeholder=""
                                               onclick="this.select();" style="text-align: right;"/></td>
                                    <?php }  }?>

                                </tr>

                                <?php endforeach; ?>
                                


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

    var othersTotal = parseFloat(( (basicSalary*1)+ (houseRant*1) + (conveyanceAllowance*1) + (medicalAllowance*1) )).toFixed(2);
    var total = parseFloat(( (grossSalary*1)+ (arrearSalary*1)- (absentDeduction*1) - (loanDeduction*1) - (aITDeduction*1) -(wPFDeduction*1) )).toFixed(2);
    $('#others_'+id).val(othersTotal);

    $('#netPayAmount_'+id).val(total);

//     var basicSalaryr = 0;
//    for(i=0;i<basicSalary.length; i++){
//        basicSalaryr += basicSalary[i];
//    }
//    $('#salid').val(basicSalaryr);

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
        }else if(employeeCheckBox == null){
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
    $('#example').DataTable();
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




