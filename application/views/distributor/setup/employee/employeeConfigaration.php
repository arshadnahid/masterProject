<style>

    .chosen-container-single {
        width: 633px !important;;
    }

    .funkyradio label {
        width: 100%;
        border-radius: 3px;
        border: 1px solid #D1D3D4;
        font-weight: normal;
    }

    .funkyradio input[type="radio"]:empty, .funkyradio input[type="checkbox"]:empty {
        display: none;
    }

    .funkyradio input[type="radio"]:empty ~ label, .funkyradio input[type="checkbox"]:empty ~ label {
        position: relative;
        line-height: 2.5em;
        text-indent: 3.25em;
        margin-top: 2em;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .funkyradio input[type="radio"]:empty ~ label:before, .funkyradio input[type="checkbox"]:empty ~ label:before {
        position: absolute;
        display: block;
        top: 0;
        bottom: 0;
        left: 0;
        content: '';
        width: 2.5em;
        background: #D1D3D4;
        border-radius: 3px 0 0 3px;
    }

    .funkyradio input[type="radio"]:hover:not(:checked) ~ label, .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
        color: #888;
    }

    .funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
    .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
        content: '\2714';
        text-indent: .9em;
        color: #C2C2C2;
    }

    .funkyradio input[type="radio"]:checked ~ label,
    .funkyradio input[type="checkbox"]:checked ~ label {
        color: #777;
    }

    .funkyradio input[type="radio"]:checked ~ label:before,
    .funkyradio input[type="checkbox"]:checked ~ label:before {
        content: '\2714';
        text-indent: .9em;
        color: #333;
        background-color: #ccc;
    }

    .funkyradio input[type="radio"]:focus ~ label:before,
    .funkyradio input[type="checkbox"]:focus ~ label:before {
        box-shadow: 0 0 0 3px #999;
    }

    .funkyradio-default input[type="radio"]:checked ~ label:before,
    .funkyradio-default input[type="checkbox"]:checked ~ label:before {
        color: #333;
        background-color: #ccc;
    }

    .panel-heading h3 {
        padding-left: 10px;
    }

    .col-centered {
        display: inline-block;
        float: none;
        /* reset the text-align */
        text-align: left;
        /* inline-block space fix */
        margin-right: -4px;
    }

    /* centered columns styles */
    .row-centered {
        text-align: center;
    }


</style>



<a data-toggle="modal" data-target="#myModal"
   class="saleAddPermission btn btn-xs green" class="saleAddPermission ">
    <i class="fa fa-list"></i>
    Add Employee Configuration &nbsp;&nbsp;&nbsp; </a>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Employee Configuration
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    
                   <div class="col-sm-6">
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
            <table border="1" class="table table-bordered datatable" id="table-1">
                <tr>
                    <td colspan="2" style="text-align: center;font-size: 20px !important;color:red;"><i class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;HR Module</td>
                </tr>
                
                    <tr>
                        <th><center style="text-align: center;">Employe salary Field Permisson</center>
                           <center style="text-align: center;"><input type="checkbox" id="checkAll">All Check </center>  </th>

                        <td>
                            <table  class="table table-bordered">
                                <?php foreach ($EmployeeField as $key => $value): ?>
                                    <tr>
                                        <td nowrap><input <?php
                                            
                                                if ($value->isShow == 1) {
                                                    echo "checked";
                                                
                                            }
                                            ?> type="checkbox" value="<?php echo $value->id; ?>" id="fieldName" name="fieldName[]"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value->fieldName; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                  </table>
                  </div>
                <!-- /.col -->
                 <div class="clearfix" ></div>
                <div class="col-sm-6">
                            <div class="clearfix form-actions" >
                                <div class="col-md-offset-5 col-md-10">
                                    <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                                                        
                                </div>
                            </div>
                        </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add Employee Configuration</h4>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-md-12" id="addForm">
                        <form id="publicForm2" action="" method="post" class="form-horizontal ">

                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Field</label>
                                <div class="col-sm-9">
                                    <select class="chosen-select form-control" name="employeField"
                                            id="employeField"
                                            data-placeholder="Search Employee Field">
                                        <option value=""></option>
                                            <option value="Eployee Name">Eployee Name</option>
                                            <option value="Designation">Designation</option>
                                            <option value="Department/ Section">Department/ Section</option>
                                            <option value="Payment Mode">Payment Mode</option>
                                            <option value="Basic Salary">Basic Salary</option>
                                            <option value="House Rant Allowance">House Rant Allowance</option>
                                            <option value="Conveyance Allowance">Conveyance Allowance</option>
                                            <option value="Medical Allowance">Medical Allowance</option>
                                            <option value="Others">Others</option>
                                            <option value="Gross Salary">Gross Salary</option>
                                            <option value="Arrear Salary">Arrear Salary  </option>
                                            <option value="WPF Deduction">WPF Deduction  </option>
                                           <option value="Absent Deduction">Absent Deduction</option>
                                           <option value="Loan Deduction">Loan Deduction</option>
                                           <option value="AIT Deduction">AIT Deduction </option>
                                           <option value="Net Pay Amount">Net Pay Amount</option>

                                    </select>
                                </div>
                            </div>


                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">


                                    <button onclick="saveconfigaration('add')" id="subBtn" class="btn btn-info"
                                            type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        <?php echo get_phrase('Save') ?>
                                    </button>
                                    &nbsp; &nbsp; &nbsp;

                                </div>
                                <div class="modal-dialog">

                                    <!-- /.modal-content -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Serial No</th>

                                <th>Employee Tools</th>
                                <th style="" align="center"><strong><?php echo get_phrase('Action') ?></strong></th>
                            </tr>
                            </thead>
                            <tbody id="userData">

                            <?php
                            foreach ($EmployeeField as $key => $value):
                                ?>

                                <tr>
                                    <td><?php echo $key + 1; ?></td>

                                    <td><?php echo $value->fieldName; ?></td>
                                    <?php
                                    echo '<td><a href="javascript:void(0);" class="btn btn-danger pull-left" onclick="return confirm(\'Are you sure to delete data?\')?saveconfigaration(\'delete\',\'' . $value->id . '\'):false;"><i class="fa fa-remove"></i></a></td>';

                                    ?>


                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>


                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">

                                <button type="button" class="btn red btn-outline sbold" data-dismiss="modal">Close
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class=" btn green btn-outline sbold" type="reset" data-dismiss="modal">
                                    <i class="fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                            <div class="modal-dialog">

                                <!-- /.modal-content -->
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">

                    function saveconfigaration(type, id) {


                        id = (typeof id == "undefined") ? '' : id;
                        var statusArr = {add: "added", edit: "updated", delete: "deleted"};
                        var userData = '';
                        if (type == 'add') {
                            userData = $("#addForm").find('.form-horizontal').serialize() + '&action_type=' + type + '&id=' + id;
                        } else if (type == 'edit') {
                            userData = $("#editForm").find('.form').serialize() + '&action_type=' + type;
                        } else {
                            userData = 'action_type=' + type + '&id=' + id;
                        }
                        var url = baseUrl + "lpg/EmployeeConfiqureController/saveBankBookConfig";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: userData,
                            dataType: 'JSON',

                            success: function (msg) {
                                if (msg == '1') {
                                    swal('Group has been ' + statusArr[type] + ' successfully.', "!", "success");
                                    getconfig();
                                } else {
                                    swal("Some problem occurred !", "please try again!", "error");
                                }
                                $('#employeField').val('').trigger('chosen:updated');
                            }
                        });


                    }


                    function getconfig() {

                        $.ajax({
                            type: 'POST',
                            url: baseUrl + "lpg/EmployeeConfiqureController/getconfig",
                            data: 'action_type=view&' + $("#userForm").serialize(),
                            success: function (html) {
                                $('#userData').html(html);
                            }
                        });
                    }

                </script>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });


    });


</script>

<script>
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>

<script>
 function isconfirm2(){
        var fieldName=$("#fieldName").val();
        

        if(fieldName == ''){
            swal("fieldName Can't be empty!", "Validation Error!", "error");
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