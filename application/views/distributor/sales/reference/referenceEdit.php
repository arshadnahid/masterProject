<div class="row">    <div class="col-md-12">        <div class="portlet box blue">            <div class="portlet-title" style="min-height:21px">                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">                   <?php echo  get_phrase('Reference_Edit')?>                </div>            </div>            <div class="portlet-body">        <form id="publicForm" action=""  method="post" class="form-horizontal">            <div class="form-group">                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Referencen_Id')?><span style="color:red;"> *</span></label>                <div class="col-sm-6">                    <input type="text" id="form-field-1"  value="<?php echo $referenceList->refCode; ?>" name="refCode" class="form-control required" placeholder="Reference Code" readonly  required/>                </div>            </div>            <div class="form-group">                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Referencen_Name')?><span style="color:red;"> *</span></label>                <div class="col-sm-6">                    <input type="text" id="form-field-1" value="<?php echo $referenceList->referenceName; ?>" name="referenceName" class="form-control required" placeholder="Reference Name"  required/>                </div>            </div>            <div class="form-group">                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Phone')?></label>                <div class="col-sm-6">                    <input type="text" maxlength="11" id="form-field-1" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" name="referencePhone"  value="<?php echo $referenceList->referencePhone; ?>" placeholder="Reference Phone" class="form-control" />                    <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>                </div>            </div>            <div class="form-group">                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Email')?></label>                <div class="col-sm-6">                    <input type="email" id="form-field-1"  value="<?php echo $referenceList->referenceEmail; ?>" name="referenceEmail" placeholder="Email" class="form-control" />                </div>            </div>            <div class="form-group">                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Address')?></label>                <div class="col-sm-6">                                <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->                    <textarea  cols="6" rows="3" placeholder="Type Address.." class="form-control" name="referenceAddress"><?php echo $referenceList->referenceAddress; ?></textarea>                </div>            </div>            <div class="clearfix form-actions" >                <div class="col-md-offset-3 col-md-9">                    <button onclick="return confirmSwat()"   id="subBtn" class="btn btn-info" type="button">                        <i class="ace-icon fa fa-check bigger-110"></i>                        <?php echo get_phrase('Update')?>                    </button>                    &nbsp; &nbsp; &nbsp;                    <button class="btn" type="reset">                        <i class="ace-icon fa fa-undo bigger-110"></i>                        <?php echo get_phrase('Reset')?>                    </button>                </div>            </div>        </form>             </div>                     </div>                     </div>                     </div>         <!-- /.col --><!-- /.row --><script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script><script>                                    function checkDuplicatePhone(phone) {                                        var url = '<?php echo site_url("SalesController/checkDuplicatePhone") ?>';                                        $.ajax({                                            type: 'POST',                                            url: url,                                            data: {'phone': phone},                                            success: function (data)                                            {                                                if (data == 1) {                                                    $("#subBtn").attr('disabled', true);                                                    $("#errorMsg").show();                                                } else {                                                    $("#subBtn").attr('disabled', false);                                                    $("#errorMsg").hide();                                                }                                            }                                        });                                    }</script>