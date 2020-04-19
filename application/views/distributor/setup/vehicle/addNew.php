<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                     Add New Vehicle </div>
                      </div>
                      <div class="portlet-body">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Vehicle Name </label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="vehicleName" class="form-control required" placeholder="Vehicle Name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Vehicle Model</label>
                            <div class="col-sm-6">
                                <input type="text" maxlength="11" id="form-field-1"   name="vehicleModel" placeholder="Vehicle Model" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Chassis Number</label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="chassisNumber" placeholder="Chassis Number" class="form-control" />

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Number Plate</label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="numberPlate" placeholder="Number Plate" class="form-control" />
                            </div>
                        </div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return confirmSwat()" id="subBtn" class="btn btn-info" type="button">
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
</div>

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
