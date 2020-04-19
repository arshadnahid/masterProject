<div class="row">
    <div class="col-sm-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Add Customer') ?> </div>

            </div>
            <div class="portlet-body">
                <form id="publicForm" action="" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="form-field-1"> <?php echo get_phrase('Customer Type') ?><span
                                    style="color:red;"> *</span></label>
                        <div class="col-sm-6">
                            <select name="customerType" class="chosen-select form-control" id="form-field-select-3"
                                    data-placeholder="Search Customer Type">

                                <?php
                                foreach ($customerType as $eachType):
                                    ?>
                                    <option value="<?php echo $eachType->type_id; ?>">
                                        <?php echo $eachType->typeTitle; ?>
                                    </option>
                                    <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="form-field-1"> <?php echo get_phrase('Root Name') ?><span
                                    style="color:red;"> </span></label>
                        <div class="col-sm-6">
                            <select name="root_id" class="chosen-select form-control" id="form-field-select-3"
                                    data-placeholder="Search Customer Type">
                                <option>Select Root</option>
                                <?php
                                foreach ($root_info as $rootid):
                                    ?>
                                    <option value="<?php echo $rootid->root_id; ?>">
                                        <?php echo $rootid->name; ?>
                                    </option>
                                    <?php
                                endforeach;root_info
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="form-field-1"> <?php echo get_phrase('Customer Id') ?> <span
                                    style="color:red;"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" id="form-field-1" name="customerID" readonly
                                   value="<?php echo isset($customerID) ? $customerID : ''; ?>" class="form-control"
                                   placeholder="Customer ID"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="customerName"> <?php echo get_phrase('Customer Name') ?> <span
                                    style="color:red;"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" id="customerName" name="customerName" class="form-control required" onblur="checkDuplicateCustomer(this.value)"
                                   placeholder="Customer Name" required autocomplete="off"/>
                            <span id="errorMsg" style="color:red;display: none;"><i
                                        class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Customer already Exits!!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="form-field-1"> <?php echo get_phrase('Phone') ?></label>
                        <div class="col-sm-6">
                            <input type="text" maxlength="11" id="customer_phone"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   onblur="checkDuplicatePhone(this.value)" name="customerPhone"
                                   placeholder="Customer Phone" class="form-control"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="form-field-1"> <?php echo get_phrase('Email') ?></label>
                        <div class="col-sm-6">
                            <input type="email" id="customerEmail" name="customerEmail" placeholder="Email"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="form-field-1"> <?php echo get_phrase('Address') ?> </label>
                        <div class="col-sm-6">
                            <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                            <textarea cols="6" rows="3" placeholder="Type Address.." class="form-control"
                                      name="customerAddress"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="credit_limit"> <?php echo get_phrase('Credit Limit') ?> </label>
                        <div class="col-sm-6">
                            <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                            <input type="text" id="credit_limit" name="credit_limit" placeholder="Credit Limit" autocomplete="off"
                                   class="form-control"/>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"
                               for="credit_days"> <?php echo get_phrase('Credit Days') ?> </label>
                        <div class="col-sm-6">
                            <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                            <input type="text" id="credit_days" name="credit_days" placeholder="Credit Days" autocomplete="off"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button onclick="return confirmSwat()" id="subBtn" class="btn btn-info" type="button">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                <?php echo get_phrase('Save') ?>
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                <?php echo get_phrase('Reset') ?>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- /.col -->
            </div>
        </div>
    </div>
</div>
<script>
    function checkDuplicatePhone(phone) {

    }

    function checkDuplicateCustomer(customer_name) {
        var phone=$('#customer_phone').val();
        var url = '<?php echo site_url("lpg/CustomerController/checkDuplicateCustomer") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'customer_name':customer_name,'phone': phone},
            success: function (data) {
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