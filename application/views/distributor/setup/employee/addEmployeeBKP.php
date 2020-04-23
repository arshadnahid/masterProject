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
                                        <select class="form-control" id="month" name="month">
                                            <option selected disabled>--Select--</option>
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

                        <div class="col-md-12">


                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead class="table-header">
                            <tr>

                                <th>Name of Employee</th>
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
                                <th>Signature</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                                <tr>

                                    <td style="width: 8%">
                                          <select id="employeeID" name="employeeID"

                                                    class="chosen-select form-control">
                                                <option selected value="" disabled >--Select--</option>
                                            <?php foreach ($employee as $key => $value): ?>
                                                <option  employeeName="<?php echo $value->name; ?>"
                                                    value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                            <?php endforeach; ?>


                                            </select>
                                    </td>
                                    <td style="width: 8%">
                                        <select class="form-control" id="departmentID" name="departmentID">
                                            <option selected value="" disabled>--Select--</option>
                                            <?php foreach ($department as $key => $value): ?>
                                                <option  departmentName="<?php echo $value->DepartmentName; ?>"
                                                    value="<?php echo $value->DepartmentID; ?>"><?php echo $value->DepartmentName; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="width: 8%">
                                         <select class="form-control" id="paymentMode" name="paymentMode">
                                            <option selected disabled>--Select--</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="basicSalary" name="basicSalary" value="" class="form-control"placeholder="" style="text-align: right;"/>
                                    </td>

                                    <td>
                                        <input type="text" id="houseRant" name="houseRant" value="" class="form-control"placeholder="" style="text-align: right;"/>
                                    </td>

                                    <td><input type="text" id="conveyanceAllowance" name="conveyanceAllowance" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="medicalAllowance" name="medicalAllowance" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="wPFDeduction" name="wPFDeduction" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="grossSalary" name="grossSalary" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="arrearSalary" name="arrearSalary" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="absentDeduction" name="absentDeduction" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="loanDeduction" name="loanDeduction" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="aITDeduction" name="aITDeduction" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="netPayAmount" name="netPayAmount" value="" class="form-control"placeholder="" style="text-align: right;"/></td>
                                    <td><input type="text" id="signature" name="signature" value="" class="form-control"placeholder="" style="text-align: right;"/></td>

                                </tr>
                                <tr>
                                    <td colspan="15"></td>
                                    <td style="width: 5%">
                                            <a id="add_item" class="btn btn-info form-control"
                                               href="javascript:;" title="Add Item"> Add Item<i
                                                        class="fa fa-plus"
                                                        style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a>
                                        </td>
                                </tr>

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

<script>
 function isconfirm2(){
        var month=$("#month").val();
        var month=$("#year").val();
        if(month == ''){
            swal("Month Can't be empty!", "Validation Error!", "error");
        }else if(year == ''){
            swal("employeeID  can't be empty!", "Validation Error!", "error");
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

     var j = 0;

    $("#add_item").click(function () {


        var employeeID = $('#employeeID').val();
        var employeeName = $('#employeeID').find('option:selected').attr('employeeName');
        var departmentID = $('#departmentID').val();
        var departmentName = $('#departmentID').find('option:selected').attr('departmentName');
        var paymentMode = $('#paymentMode').val();
        var basicSalary = $('#basicSalary').val();
        var houseRant = $('#houseRant').val();
        var conveyanceAllowance = $('#conveyanceAllowance').val();
        var medicalAllowance = $('#medicalAllowance').val();
        var wPFDeduction = $('#wPFDeduction').val();
        var grossSalary = $('#grossSalary').val();
        var arrearSalary = $('#arrearSalary').val();
        var absentDeduction = $('#absentDeduction').val();
        var loanDeduction = $('#loanDeduction').val();
        var aITDeduction = $('#aITDeduction').val();
        var netPayAmount = $('#netPayAmount').val();
        var signature = $('#signature').val();


        if (employeeID == '' ) {
            swal("Employee can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (departmentID == '') {
            swal("Department Name can't be empty.!", "Validation Error!", "error");
            return false;
        }else if (basicSalary == '') {
            swal("Basic Salary can't be empty.!", "Validation Error!", "error");
            return false;
        } else {
            var tab;

            tab ='<tr class="new_item' + employeeID + '">' +

                 '<td style="text-align: center"><input type="hidden" name="employeeID[]" value="'+employeeID+'">' + employeeName +
                '</td>' +
                '<td style="text-align: center"><input type="hidden" name="departmentID[]" value="'+departmentID+'">' + departmentName +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="paymentMode[]" value="'+paymentMode+'">' + paymentMode +
                '</td>' +
                 '<td style="text-align: right"><input type="hidden" name="basicSalary[]" value="'+basicSalary+'">' + basicSalary +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="houseRant[]" value="'+houseRant+'">' + houseRant +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="conveyanceAllowance[]" value="'+conveyanceAllowance+'">' + conveyanceAllowance +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="medicalAllowance[]" value="'+medicalAllowance+'">' + medicalAllowance +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="wPFDeduction[]" value="'+wPFDeduction+'">' + wPFDeduction +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="grossSalary[]" value="'+grossSalary+'">' + grossSalary +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="arrearSalary[]" value="'+arrearSalary+'">' + arrearSalary +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="absentDeduction[]" value="'+absentDeduction+'">' + absentDeduction +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="loanDeduction[]" value="'+loanDeduction+'">' + loanDeduction +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="aITDeduction[]" value="'+aITDeduction+'">' + aITDeduction +
                '</td>' +
                '<td style="text-align: right"><input type="hidden" name="netPayAmount[]" value="'+netPayAmount+'">' + netPayAmount +
                '</td>' +
                 '<td style="text-align: right"><input type="hidden" name="signature[]" value="'+signature+'">' + signature +
                '</td>' +

                '<td><a class=" btn form-control btn-danger" href="javascript:void(0);" id="remCF">Remove</a></td>'+

                '</tr>';
                $("#example tbody").append(tab);

            $('#employeeID').val('').trigger('chosen:updated');
            $('#departmentID').val('').trigger('chosen:updated');
            $('#paymentMode').val('').trigger('chosen:updated');
            $('#basicSalary').val('').trigger('chosen:updated');
            $('#houseRant').val('');
            $('#conveyanceAllowance').val('');
            $('#medicalAllowance').val('');
            $('#wPFDeduction').val('');
            $('#grossSalary').val('');
            $('#arrearSalary').val('');
            $('#absentDeduction').val('');
            $('#loanDeduction').val('');
            $('#aITDeduction').val('');
            $('#netPayAmount').val('');
            $('#signature').val('');


        }

        $("#example").on('click','#remCF',function(){
        $(this).parent().parent().remove();
    });


    });


</script>




