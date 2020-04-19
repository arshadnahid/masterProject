<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">Add New Employee</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li class="active">
                    <i class="ace-icon fa fa-list"></i>
                    <a href="<?php echo site_url('employeeList'); ?>">List</a>
                </li>
            </ul>
        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Name <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" id="name" name="name" class="form-control required" placeholder="Name" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee ID </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="form-field-1" name="employeeId" class="form-control required" placeholder="Employee ID" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Father's Name </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="form-field-1" name="fathersName" class="form-control required" placeholder="Father's Name" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Mother's Name </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="form-field-1" name="mothersName" class="form-control required" placeholder="Mother's Name"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Profile</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1"   name="profile" placeholder="Vehicle Model" class="form-control" />
                                        <span><i class="fa fa-user" style="font-size:100px"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Present Address <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <textarea type="text" id="presentAddress" name="presentAddress" class="form-control required" placeholder="Present adddress" /></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Permanent Address </label>
                                    <div class="col-sm-6">
                                        <textarea type="text" id="form-field-1" name="permanentAddress" class="form-control required" placeholder="Permanent adddress" /></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date of Birth</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input class="form-control date-picker" name="dateOfBirth"  type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" required/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Gender </label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="gender">
                                            <option selected disabled>--Select--</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">FeMale</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> National ID <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="nationalId"   name="nationalId" placeholder="National ID" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Religion</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="religion">
                                            <option selected disabled>--Select--</option>
                                            <?php foreach ($religion as $key => $value): ?>
                                                <option value="<?php echo $value->id; ?>"><?php echo $value->religionName; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                                    <div class="col-sm-6">
                                        <input type="email" id="form-field-1"   name="emailAddress" placeholder="Email" class="form-control" />
                                    </div>
                                </div>
                            </div>



                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Home District</label>
                                    <div class="col-sm-6">
                                        <select  name="homeDistrict" class="chosen-select form-control"  data-placeholder="Search District" required>
                                            <option></option>
                                            <?php foreach ($districts as $key => $value): ?>
                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Personal Mobile <span style="color:red;"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="personalMobile"   name="personalMobile" placeholder="Personal Mobile" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Marital Status</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="maritalStatus">
                                            <option selected disabled>--Select--</option>
                                            <option value="Married">Married</option>
                                            <option value="Unmarried">Unmarried</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Work Mobile</label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="form-field-1"   name="officeMobile" placeholder="work Mobile" class="form-control" />
                                    </div>
                                </div>
                            </div>




                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Blood Group</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="bloodGroup">
                                            <option selected disabled>--Select--</option>
                                            <?php foreach ($bloodGroup as $key => $value): ?>
                                                <option value="<?php echo $value->id; ?>"><?php echo $value->bloodName; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Joining Date</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input class="form-control date-picker" name="joiningDate"  type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" required/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Type</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="employeeType">
                                            <option selected disabled>--Select--</option>
                                            <option value="Staff">Staff</option>
                                            <option value="Loader">Loader</option>
                                            <option value="Driver">Driver</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Salary</label>
                                    <div class="col-sm-6">
                                        <input type="text" maxlength="11" id="form-field-1"   name="salary" placeholder="Salary" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Status</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="empStatus">
                                            <option selected disabled>--Select--</option>
                                            <option value="Active">Active</option>
                                            <option value="Inavtive">Inavtive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="clearfix form-actions" >
                                <div class="col-md-offset-3 col-md-9">
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
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>
 function isconfirm2(){
        var personalMobile=$("#personalMobile").val();
        var nationalId=$("#nationalId").val();
        var presentAddress=$("#presentAddress").val();
        var name=$("#name").val();

        if(name == ''){
            swal("Name Can't be empty!", "Validation Error!", "error");
        }else if(presentAddress == ''){
            swal("Present Address can't be empty!", "Validation Error!", "error");
        }else if(nationalId == ''){
            swal("National ID Can't be empty", "Validation Error!", "error");
        }else if(personalMobile == ''){
            swal("Personal Mobile number can't be empty", "Validation Error!", "error");
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



<script>
    function checkDuplicateEmail(email){
        var url = '<?php echo site_url("SetupController/checkDuplicateEmailForUser") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'email': email},
            success: function (data)
            {
                if(data == 1){
                    $("#subBtn").attr('disabled',true);
                    $("#errorMsg").show();
                }else{
                    $("#subBtn").attr('disabled',false);
                    $("#errorMsg").hide();
                }
            }
        });

    }
</script>
