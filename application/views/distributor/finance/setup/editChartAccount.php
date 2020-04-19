<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/1'); ?>">Setup</a>
                </li>
                <li class="active">Edit Chart Of Account</li>
            </ul>
            
            <ul class="breadcrumb pull-right">
                
                <li>
                    <a href="<?php echo site_url('listChartOfAccount'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
                

            </ul>
            
            
            
        </div>
        <br>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <div class="page-content">
            <div class="row">
                <div class="col-md-6">
                    <form  action=""  method="post" class="form-horizontal">
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Account Code<span style="color:red;"> ( * )</span></label>
                            <div class="col-sm-8">
                                <input type="text" value="<?php echo $editChartAccount->accountCode;?>" id="accountCode"  class="form-control" readonly placeholder="Account Code" name="accountCode"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Account Head<span style="color:red;"> ( * )</span></label>
                            <div class="col-sm-8">
                                <input type="text" onblur="checkDuplicateHead(this.value)" id="acc_head" value="<?php echo $editChartAccount->title;?>" class="form-control" placeholder="Account Head" name="accountHead"/>
                                <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Account Head already Exits!!Please Type Different Name.</span>
                            </div>
                        </div>

                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-4 col-md-8">
                                <button id="btnDisabled" onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
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
                <div class="col-md-6"></div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>



<script>
    
    function getHeadAccountList(childID){
        $("#acc_head").val('');
        var url = '<?php echo site_url("FinaneController/getHeadCode") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'childID': childID},
            success: function (data)
            {
                $("#accountCode").val(data);
            }
        });

    }
    function getChildeAccountList(parentId){
        $("#acc_head").val('');
        var url = '<?php echo site_url("FinaneController/getChildCode") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'parentId': parentId},
            success: function (data)
            {
                $("#accountCode").val(data);
            }
        });
        $("#childAccount").html('<option disabled selected>Search Account Head</option>');
        var headTitle = $('#acc_head').val();
        var rootAccount = $('#rootAccount').val();
        var parentAccount = $('#parentAccount').val();
        var childAccount = $('#childAccount').val();

        var url = '<?php echo site_url("FinaneController/getChartList") ?>';
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
                //$("#parentAccount").html(data);
                $('#childAccount').chosen();
                $('#childAccount option').remove();
                $('#childAccount').append($(data));
                $("#childAccount").trigger("chosen:updated");
    
            }
        });
    }
    
    function getParentAccountList(rootID){
        $("#acc_head").val('');
        var url = '<?php echo site_url("FinaneController/getParentCode") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'rootID': rootID},
            success: function (data)
            {
                $("#accountCode").val(data);
            }
        });
        
        
        $('#childAccount').chosen();
        $('#childAccount option').remove();
        $('#childAccount').append($('<option disabled selected>Search Account Head</option>'));
        $("#childAccount").trigger("chosen:updated");
        
       
        $("#parentAccount").html('<option disabled selected>Search Account Head</option>');
        var headTitle = $('#acc_head').val();
        var rootAccount = $('#rootAccount').val();
        var parentAccount = $('#parentAccount').val();
        var childAccount = $('#childAccount').val();

        var url = '<?php echo site_url("FinaneController/getChartList") ?>';
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

                //$("#parentAccount").html(data);
                $('#parentAccount').chosen();
                $('#parentAccount option').remove();
                $('#parentAccount').append($(data));
                $("#parentAccount").trigger("chosen:updated");
    
              

        
            }
        });
    }
    
    
    
    
    
    
    
    function checkDuplicateHead(){
    
        var headTitle = $('#acc_head').val();
        var rootAccount = $('#rootAccount').val();
        var parentAccount = $('#parentAccount').val();
        var childAccount = $('#childAccount').val();

        var url = '<?php echo site_url("FinaneController/checkDuplicateHead") ?>';
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