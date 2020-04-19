<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                     Chart of Account </div>
                      </div>
                      <div class="portlet-body">

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form id="publicForm"  action=""  method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red;">*</span> Root Account</label>
                            <div class="col-sm-8">
                                <select class="chosen-select form-control" id="rootAccount" onchange="getParentAccountList(this.value)" name="rootAccount" id="form-field-select-3" data-placeholder="Search Root Account">
                                    <option value="">  </option>
                                    <?php foreach ($rootAccount as $key => $value): ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->parent_name; ?></option>
                                    <?php endforeach; ?>
                                </select>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Parent Account</label>
                            <div class="col-sm-8">
                                <select  onchange="getChildeAccountList(this.value)" class="chosen-select form-control" id="parentAccount" name="parentAccount" id="form-field-select-3" data-placeholder="Search Parent Account">
                                    <option value="0">  </option>
                                    <?php foreach ($AccountList as $key => $value): ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->parent_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Child Account</label>
                            <div class="col-sm-8">
                                <select  onchange="getHeadAccountList(this.value)" class="chosen-select form-control" id="childAccount" name="childAccount" id="form-field-select-3" data-placeholder="Search Child Account">
                                    <option value="">  </option>
                                </select>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <span style="color:red;">*</span> Account Code</label>
                            <div class="col-sm-8">
                                <input type="text" value="" id="accountCode"  class="form-control" readonly placeholder="Account Code" name="accountCode"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <span style="color:red;">*</span> Account Head</label>
                            <div class="col-sm-8">
                                <input type="text" onblur="checkDuplicateHead(this.value)" onkeyup="disabledbutton()" id="acc_head" class="form-control" placeholder="Account Head" name="accountHead"/>
                                <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Account Head already Exits!!Please Type Different Name.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Posted</label>
                            <div class="col-sm-8">
                                <input type="checkbox"   id="" class="" placeholder="" name="posted" value="1"/>

                            </div>
                        </div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-6">
                                <button onclick="return confirmSwat()"   id="btnDisabled" class="btn btn-info" type="button">
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
                <div class="col-md-6 col-md-offset-3" style="margin-top:10px">
                    <div id="showTreeList"></div>
                </div>
            </div><!-- /.col -->
            </div><!-- /.col -->
            </div><!-- /.col -->
            </div><!-- /.col -->
            </div><!-- /.col -->


<script>

    function getChildeAccountList(parentId){
        showTreeList();
        $("#acc_head").val('');
        var url = '<?php echo site_url("lpg/AccountController/getChildCode") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'parentId': parentId},
            success: function (data)
            {
                $("#accountCode").val(data);
            }
        });
        //$("#childAccount").html('<option disabled selected>Search Account Head</option>');
        var headTitle = $('#acc_head').val();
        var rootAccount = $('#rootAccount').val();
        var parentAccount = $('#parentAccount').val();
        //var childAccount = $('#childAccount').val();
        var url = '<?php echo site_url("lpg/AccountController/getChartList") ?>';
       /* $.ajax({
            url: url,
            type: 'post',
            data: {
                headTitle: headTitle,
                rootAccount:rootAccount,
                parentAccount:parentAccount,
                childAccount:childAccount
            },
            success: function(data) {

                $('#childAccount').chosen();
                $('#childAccount option').remove();
                $('#childAccount').append($(data));
                $("#childAccount").trigger("chosen:updated");
            }
        });*/
    }
    function showTreeList(){
        var url ='<?php echo site_url('lpg/AccountController/getTreeList'); ?>';
        var rootId=$("#rootAccount").val();
        var parentId=$("#parentAccount").val();
        var chaildId=$("#childAccount").val();
        $.ajax({
            url: url,
            type: 'post',
            data: {
                rootId: rootId,
                parentId:parentId,
                chaildId:chaildId
            },
            success: function(data) {
                $('#showTreeList').html(data);
            }
        });
    }
    function getParentAccountList(rootID){
        showTreeList();
        $("#acc_head").val('');
        var url = '<?php echo site_url("lpg/AccountController/getParentCode") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'rootID': rootID},
            success: function (data)
            {
                $("#accountCode").val(data);
            }
        });
        //$('#childAccount').chosen();
        //$('#childAccount option').remove();
        //$('#childAccount').append($('<option disabled selected>Search Account Head</option>'));
        //$("#childAccount").trigger("chosen:updated");
        //$("#parentAccount").html('<option disabled selected>Search Account Head</option>');
        var headTitle = $('#acc_head').val();
        var rootAccount = $('#rootAccount').val();
        var parentAccount = $('#parentAccount').val();
        var childAccount = $('#childAccount').val();
        var url = '<?php echo site_url("lpg/AccountController/getChartList") ?>';
        /*$.ajax({
            url: url,
            type: 'post',
            data: {
                headTitle: headTitle,
                rootAccount:rootAccount,
                parentAccount:parentAccount,
                childAccount:childAccount
            },
            success: function(data) {
                //$("#parentAccount").html(data);
                $('#parentAccount').chosen();
                $('#parentAccount option').remove();
                $('#parentAccount').append($(data));
                $("#parentAccount").trigger("chosen:updated");
            }
        });*/
    }
    function disabledbutton(){
        // alert("ddd");
        $("#btnDisabled").attr('disabled',true);
    }
    function checkDuplicateHead(){
        var headTitle = $('#acc_head').val();
        var rootAccount = $('#rootAccount').val();
        var parentAccount = $('#parentAccount').val();
        var childAccount = $('#childAccount').val();
        var url = '<?php echo site_url("lpg/AccountController/checkDuplicateHead") ?>';
        $.ajax({
            url: url,
            type: 'post',
            data: {
                headTitle: headTitle,
                rootAccount:rootAccount,
                parentAccount:parentAccount,
                childAccount:childAccount
            },
            success: function(data) {
                if(data == 1){
                    $("#btnDisabled").attr('disabled',true);
                    $("#errorMsg").show();
                }else{
                    $("#btnDisabled").attr('disabled',false);
                    $("#errorMsg").hide();
                }
            }
        });
    }
</script>