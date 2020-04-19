
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup </a>
                </li>
                <li class="active">Distributor Edit</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('distributor'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a>
            </span>
        </div>
        <br>



        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Company Name</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="companyName"  value="<?php echo $distributorList->companyName; ?>" class="form-control" placeholder="Name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Owner Name</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="dist_name"  value="<?php echo $distributorList->dist_name; ?>" class="form-control" placeholder="Owner Name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact NO</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="contactNo"  value="<?php echo $distributorList->contactNo; ?>" class="form-control" placeholder="Owner Name" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact Person Name</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="contactPerson"  value="<?php echo $distributorList->contactPerson; ?>" class="form-control" placeholder="Contact Person Name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact NO</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="contactPersonCont"  value="<?php echo $distributorList->contactPersonCont; ?>" class="form-control" placeholder="Contact Person Contact NO" />
                                </div>
                            </div>

                            <!--                            <div class="form-group">
                                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Name</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" id="form-field-1" name=""  value="" class="form-control" placeholder="Name" />
                                                            </div>
                                                        </div>-->
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"  for="form-field-1">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" onblur="checkDuplicateEmail(this.value)" name="dist_email"  value="<?php echo $distributorList->dist_email; ?>" class="form-control" placeholder="Email" />
                                    <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Email already Exits!!</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Password</label>
                                <div class="col-sm-6">
                                    <input type="password" id="form-field-1" name="dist_password"  value="" class="form-control" placeholder="********" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="dist_phone"  value="<?php echo $distributorList->dist_phone; ?>" class="form-control" placeholder="Phone" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Website</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="dis_website"  value="<?php echo $distributorList->dis_website; ?>" class="form-control" placeholder="Website" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Zone</label>
                                <div class="col-sm-6">
                                    <select  name="zone" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Zone Add">
                                        <option selected disabled>Select Zone</option>
                                        <?php foreach ($zoneList as $key => $value): ?>
                                            <option <?php
                                            if ($distributorList->zone == $value->zone_id) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $value->zone_id; ?>"><?php echo $value->zone_title; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                    <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Division</label>
                                <div class="col-sm-6">
                                    <select  name="division" onchange="getDivisionId(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search Division">
                                        <option selected disabled>Select Division</option>
                                        <?php foreach ($divisionList as $key => $value): ?>
                                            <option <?php
                                            if ($distributorList->division == $value->id) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                    <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">District</label>
                                <div class="col-sm-6">
                                    <select  onchange="getDistrictid(this.value)" name="district" class="chosen-select form-control" id="districtList" data-placeholder="Search District">
                                        <option selected disabled>Select District</option>

                                    </select>
                                    <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Thana</label>
                                <div class="col-sm-6">
                                    <select  onchange="getThanaId(this.value)"  name="thanna" class="chosen-select form-control" id="thanaList" data-placeholder="Search Thanna">
                                        <option selected disabled>Select Thana</option>

                                    </select>
                                    <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Post Office</label>
                                <div class="col-sm-6">
                                    <select  name="thanna" class="chosen-select form-control" id="unitList" data-placeholder="Search Post Office">
                                        <option selected disabled>Select Post Office</option>

                                    </select>
                                    <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Address</label>
                                <div class="col-sm-6">
                                    <textarea id="form-field-1" name="dist_address"  value="" class="form-control" placeholder="Address"><?php echo $distributorList->dist_address; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <script>
                                    var fileindex = 1;
                                    var inc = 0;
                                    var maxImg = 10;
                                </script>

                                <?php require_once 'application/views/upload-api.php'; ?>
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Images</label>
                                <div class="col-sm-6">
                                    <div class="img_uBtn">
                                        <div  id="upload"  class="upload2"><span> <i class='glyphicon glyphicon-file'></i> Browse</span></div><span id="status" style="display:none"> <img src="<?php echo base_url() ?>scripts/upload_js/loading.gif" /> </span> 
                                    </div>
                                    <div class="" style="clear: both;"></div><br>






                                    <!--                                <div class="image_content_area">
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                                                    </div>-->

                                    <div class="image_content_area">
                                        <span id="delete_avatar_1031" style="margin-top:30px; ">
                                            <div class="upload_image1" style="width:100px; margin-right:10px; float:left;">
                                                <img src="<?php echo base_url('uploads/thumb/' . $distributorList->dist_picture); ?>" class="img-thumbnail" />
                                            </div>

                                        </span>
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Status</label>
                                <div class="col-sm-6">
                                    <select name="status" class="chosen-select form-control">
                                        <option <?php if($distributorList->status == '1') echo 'selected'  ?>  value="1">Active</option>
                                        <option <?php if($distributorList->status == '2') echo 'selected'  ?>  value="2">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Update
                                </button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>

<script>


    var url = '<?php echo site_url("admin/AdminSettings/getDistrictList") ?>';
    $.ajax({
        type: 'POST',
        url: url,
        data: {'divisionId': '<?php echo $distributorList->division; ?>', 'districtId': '<?php echo $distributorList->district; ?>'},
        success: function (data)
        {
            $('#districtList').chosen();
            $('#districtList option').remove();
            $('#districtList').append($(data));
            $("#districtList").trigger("chosen:updated");
            // $("#districtList").html(data);
        }
    });



    var url = '<?php echo site_url("admin/AdminSettings/getThanaList") ?>';
    $.ajax({
        type: 'POST',
        url: url,
        data: {'districtid': '<?php echo $distributorList->district; ?>', 'thanaId': '<?php echo $distributorList->thanna; ?>'},
        success: function (data)
        {
            $('#thanaList').chosen();
            $('#thanaList option').remove();
            $('#thanaList').append($(data));
            $("#thanaList").trigger("chosen:updated");
        }
    });


    var url = '<?php echo site_url("admin/AdminSettings/getUnionList") ?>';
    $.ajax({
        type: 'POST',
        url: url,
        data: {'thanaId': '<?php echo $distributorList->thanna; ?>', 'unionId': '<?php echo $distributorList->postOffice; ?>'},
        success: function (data)
        {
            $('#unitList').chosen();
            $('#unitList option').remove();
            $('#unitList').append($(data));
            $("#unitList").trigger("chosen:updated");

        }
    });



    function getDivisionId(divisionId) {
        $("#districtList").html('<option disabled selected>Select District</option>');
        $("#thanaList").html('<option disabled selected>Select Thana</option>');
        $("#unitList").html('<option disabled selected>Select Post Office</option>');

        var url = '<?php echo site_url("admin/AdminSettings/getDistrictList") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'divisionId': divisionId},
            success: function (data)
            {
                $('#districtList').chosen();
                $('#districtList option').remove();
                $('#districtList').append($(data));
                $("#districtList").trigger("chosen:updated");
                // $("#districtList").html(data);
            }
        });

    }
    function getDistrictid(districtid) {
        $("#thanaList").html('<option disabled selected>Select Thana</option>');
        $("#unitList").html('<option disabled selected>Select Post Office</option>');
        var url = '<?php echo site_url("admin/AdminSettings/getThanaList") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'districtid': districtid},
            success: function (data)
            {
                $('#thanaList').chosen();
                $('#thanaList option').remove();
                $('#thanaList').append($(data));
                $("#thanaList").trigger("chosen:updated");
            }
        });

    }
    function getThanaId(thanaId) {
        $("#unitList").html('<option disabled selected>Select Post Office</option>');
        var url = '<?php echo site_url("admin/AdminSettings/getUnionList") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'thanaId': thanaId},
            success: function (data)
            {
                $('#unitList').chosen();
                $('#unitList option').remove();
                $('#unitList').append($(data));
                $("#unitList").trigger("chosen:updated");

            }
        });

    }

    function checkDuplicateEmail(email) {
        var url = '<?php echo site_url("admin/AdminSettings/checkDuplicateEmailForUser") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'email': email},
            success: function (data)
            {
                if (data == 1) {
                    $("#subBtn").attr('disabled', true);
                    $("#errorMsg").show();
                } else {
                    $("#subBtn").attr('disabled', false);
                    $("#errorMsg").hide();
                }
            }
        });

    }

</script>


