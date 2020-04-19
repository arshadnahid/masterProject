<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">Supplier Edit</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('adminSupList'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a></span>
        </div>
        <br>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier ID </label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="supplierId" readonly value="<?php echo $supplierEdit->supID; ?>" class="form-control" placeholder="SupplierID" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier Name </label>

                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="supName" value="<?php echo $supplierEdit->supName;?>" class="form-control" placeholder="Name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1"  value="<?php echo $supplierEdit->supPhone;?>" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onblur="checkDuplicatePhone(this.value)" name="supPhone" placeholder="Phone" class="form-control" />
                                <span id="errorMsg" style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="supEmail"   value="<?php echo $supplierEdit->supEmail;?>" placeholder="Email" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address</label>
                            <div class="col-sm-6">
                                <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                                <textarea  cols="6" rows="3" placeholder="Type Address.." class="form-control" name="supAddress"><?php echo $supplierEdit->supAddress;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Choose Color</label>
                            <div class="col-sm-6">
                                <div class="bootstrap-colorpicker">
                                    <input id="colorpicker1" name="colorCode" type="color" value="<?php echo $supplierEdit->colorCode;?>" class="input-small" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <script> var fileindex = 1;
                                var inc = 0;
                                var maxImg = 10;
                            </script>

                            <?php require_once 'application/views/upload-api.php'; ?>
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier Logo</label>
                            <div class="col-sm-6">
                                <div class="img_uBtn">
                                    <div  id="upload"  class="upload2"><span> <i class='glyphicon glyphicon-file'></i> Browse</span></div><span id="status" style="display:none"> <img src="<?php echo base_url() ?>scripts/upload_js/loading.gif" /> </span> 
                                </div>
                                <div class="" style="clear: both;"></div><br>
                                <div class="image_content_area">
                                    <?php if(!empty($supplierEdit->supLogo)):?>
                                    <img class="img-thumbnail" width="90" height="64" src="<?php if(!empty($supplierEdit->supLogo)){ echo site_url('uploads/thumb/'.$supplierEdit->supLogo);}?>" border="0">
                                    <?php else: ?>
                                      <img class="img-thumbnail" width="90" height="64" src="<?php echo base_url('assets/images/default.png');?>" border="0">
                                 
                                    
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>







                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    update
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
<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script>
    function checkDuplicatePhone(phone){
        var url = '<?php echo site_url("SetupController/checkDuplicateEmail") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'phone': phone},
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
    
    $('#colorpicker1').colorpicker();
</script>