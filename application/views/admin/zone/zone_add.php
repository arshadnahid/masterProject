<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup </a>
                </li>
                <li class="active">Zone Add</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('zone'); ?>" class="btn btn-danger pull-right">
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

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Division</label>
                            <div class="col-sm-6">
                                <select  name="divisionId" onchange="getDivisionId(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search Division">
                                    <option selected disabled>Select Division</option>
                                    <?php foreach ($divisionList as $key => $value): ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">District</label>
                            <div class="col-sm-6">
                                <select  onchange="getDistrictid(this.value)" name="districtId" class="chosen-select form-control" id="districtList" data-placeholder="Search District">
                                    <option selected disabled>Select District</option>

                                </select>
                                <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Thana</label>
                            <div class="col-sm-6">
                                <select  onchange="getThanaId(this.value)"  name="thanaId" class="chosen-select form-control" id="thanaList" data-placeholder="Search Thanna">
                                    <option selected disabled>Select Thana</option>

                                </select>
                                <!--<input type="text" id="form-field-1" name="dis_website"  value="" class="form-control" placeholder="Website" />-->
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Zone Name</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="zone_title"  value="" class="form-control" placeholder="Zone Name" />
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
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
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>

<script>
    
    
    
    function getDivisionId(divisionId){
        $("#districtList").html('<option disabled selected>Select District</option>');
        $("#thanaList").html('<option disabled selected>Select Thana</option>');
        $("#unitList").html('<option disabled selected>Select Post Office</option>');
        
        var url = '<?php echo site_url("admin/AdminSettings/getDistrictList") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'divisionId': divisionId},
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
    function getDistrictid(districtid){
        $("#thanaList").html('<option disabled selected>Select Thana</option>');
        $("#unitList").html('<option disabled selected>Select Post Office</option>');
        var url = '<?php echo site_url("admin/AdminSettings/getThanaList") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'districtid': districtid},
            success: function (data)
            {
                $('#thanaList').chosen();
                $('#thanaList option').remove();
                $('#thanaList').append($(data));
                $("#thanaList").trigger("chosen:updated");
            }
        });
        
    }


</script>

