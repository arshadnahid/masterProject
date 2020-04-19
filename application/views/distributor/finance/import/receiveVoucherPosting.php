<style>
    table tbody tr td{
        margin: 1px!important;
        padding: 1px!important;
    }
    table tfoot tr td{
        margin: 1px!important;
        padding: 1px!important;
    }
    table tbody tr td{
        margin: 1px!important;
        padding: 1px!important;
    }
</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Receive</a>
                </li>
                <li class="active">Receive Voucher Posting</li>
            </ul>

            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('financeImport'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a>
            </span>
        </div>
        <br>

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">

                    <form id="publicForm" action="<?php echo site_url('receiveVoucherAdd/' . $receivePosting->purchase_demo_id); ?>"  method="post" class="form-horizontal">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="date" id="id-date-picker-1" type="text" value="<?php
if (!empty($receivePosting->purchasesDate)) {
    echo $receivePosting->purchasesDate;
} else {
    echo date('Y-m-d');
}
?>" data-date-format="yyyy-mm-dd" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher ID</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="voucherid" readonly value="<?php
                                               if (!empty($receivePosting->voucherid)) {
                                                   echo $receivePosting->voucherid;
                                               } else {
                                                   echo $voucherID;
                                               }
?>" class="form-control" placeholder="Product Code" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Receive <span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <select name="payType" onchange="selectPayType(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Receive">
                                        <option value=""></option>
                                        <option <?php
                                           if ($receivePosting->payee == 1) {
                                               echo "selected";
                                           }
?> value="1">Miscellaneous</option>
                                        <option <?php
                                            if ($receivePosting->payee == 2) {
                                                echo "selected";
                                            }
?> value="2">Customer</option>
                                        <option <?php
                                            if ($receivePosting->payee == 3) {
                                                echo "selected";
                                            }
?> value="3">Supplier</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="searchValue"></div>
                            <div id="oldValue">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Payee To <span style="color:red;">*</span></label>
                                    <div class="col-sm-6">
                                        <select  id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Name">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Deposit ( DR ) <span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <select  name="accountDr" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by payee"  onchange="check_pretty_cash(this.value)">
                                        <option value="" disabled selected>---Select Account Head---</option>

                                        <?php
                                        foreach ($accountHeadList as $key => $head) {
                                            ?>
                                            <optgroup label="<?php echo $head['parentName']; ?>">
                                                <?php
                                                foreach ($head['Accountledger'] as $eachLedger) :
                                                    ?>
                                                    <option <?php
                                            if ($receivePosting->accountCr == $eachLedger->chartId) {
                                                echo "selected";
                                            }
                                                    ?> value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                                    <?php endforeach; ?>
                                            </optgroup>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-10 col-md-offset-1">
                            <br><div class="table-header">
                                Select Account Head
                            </div>
                            <?php
                            $accountCr = explode(",", $receivePosting->accountDr);
                            $accountDrPrice = explode(",", $receivePosting->price);
                            $accountDrMemo = explode(",", $receivePosting->memo);
                            ?>



                            <table class="table table-bordered table-hover" id="show_item">
                                <thead>
                                    <tr>
                                        <td style="width:40%"  align="center"><strong>Account Head</strong></td>
                                        <td style="width:20%" align="center"><strong>Amount</strong></td>
                                        <td style="width:20%" align="center"><strong>Memo</strong></td>
                                        <td style="width:20%" align="center"><strong>Action</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($accountCr as $key => $value): ?>

                                        <tr class="new_item<?php echo $key + 1000; ?>">
                                            <td style="padding-left:15px;">
                                                <?php
                                                $accountInfo = $this->Common_model->tableRow('chartofaccount', 'chart_id', $value);
                                                echo $accountInfo->title . ' [ ' . $accountInfo->accountCode . ' ] ';
                                                ?>

                                                <input type="hidden" name="accountCr[]" value="<?php echo $value; ?>"></td>
                                            <td style="padding-left:15px;" align="right"><?php echo $accountDrPrice[$key]; ?><input class="amount" type="hidden" name="amountCr[]" value="<?php echo $accountDrPrice[$key]; ?>"></td>
                                            <td align="right">dfdf<input type="hidden" class="add_quantity" name="memoCr[]" value="<?php echo $accountDrPrice[$key]; ?>"></td>
                                            <td><a del_id="<?php echo $key + 1000; ?>" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>

                                            <select   class="chosen-select form-control paytoAccount" id="form-field-select-3" data-placeholder="Search by payee"  onchange="check_pretty_cash(this.value)">
                                                <option value="" disabled selected>---Select Account Head---</option>
                                                <?php
                                                foreach ($accountHeadList as $key => $head) {
                                                    ?>
                                                    <optgroup label="<?php echo $head['parentName']; ?>">
                                                        <?php
                                                        foreach ($head['Accountledger'] as $eachLedger) :
                                                            ?>
                                                            <option paytoAccountCode="<?php echo $eachLedger->code; ?>" paytoAccountName="<?php echo $eachLedger->title; ?>" value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                                        <?php endforeach; ?> 
                                                    </optgroup>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control text-right amountt" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0.00"></td>
                                        <td><input type="text" class="form-control text-right memo" placeholder="Memo"  ></td>
                                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                    </tr> 
                                    <tr>
                                        <td align="right"><strong>Sub-Total(BDT)</strong></td>
                                        <td align="right"><strong class="tttotal_amount"></strong></td>
                                        <td align="right"><strong class=""></strong></td>
                                        <td></td>
                                    </tr> 
                                </tfoot> 
                            </table> 
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Narration</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <textarea cols="100" rows="2" name="narration" placeholder="Narration" type="text"><?php echo $receivePosting->narration;?></textarea>

                                    </div>
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
<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>


<script>
    var url = '<?php echo site_url("FinaneController/getPayUserListPosting") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{'payid':'<?php echo $receivePosting->payee;?>','payUserId':'<?php echo $receivePosting->supplierID;?>'},
            success: function (data)
            {
                $("#searchValue").html(data);
                $("#oldValue").hide();
                $('.chosenRefesh').chosen();
                $(".chosenRefesh").trigger("chosen:updated");
            }
        });
    
    
    function selectPayType(payid){
        
        var url = '<?php echo site_url("FinaneController/getPayUserList") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{'payid':payid},
            success: function (data)
            {
                $("#searchValue").html(data);
                $("#oldValue").hide();
                $('.chosenRefesh').chosen();
                $(".chosenRefesh").trigger("chosen:updated");
            }
        });
        
    }
    
	
	
	
	
    //1531563473 
	
    //1531564223 
    //product sell account id
    //1531566765 
    //1531569512  
    //1531571161 
    
    
    function saveNewSupplier(){
        var url = '<?php echo site_url("SetupController/saveNewSupplier") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:$("#publicForm2").serializeArray(),
            success: function (data)
            {
                $('#myModal').modal('toggle');
                $('#hideNewSup').hide();
                $('#supplierid').chosen();
                //$('#customerid option').remove();
                $('#supplierid').append($(data));
                $("#supplierid").trigger("chosen:updated");
            }
        });
    }
    
    
    function checkDuplicatePhone(phone){
        var url = '<?php echo site_url("SetupController/checkDuplicateEmail") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'phone': phone},
            success: function (data)
            {
                if(data == 1){
                    $("#subBtn2").attr('disabled',true);
                    $("#errorMsg").show();
                }else{
                    $("#subBtn2").attr('disabled',false);
                    $("#errorMsg").hide();
                }
            }
        });
        
    }
    
   
</script>


<script type="text/javascript">
   
    $('.amountt').change(function(){ 
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });  
    var findAmount = function () {
        var ttotal_amount = 0;
        $.each($('.amount'), function () {
            amount = $(this).val();
            amount = Number(amount);
            ttotal_amount += amount;
        });
        $('.tttotal_amount').html(parseFloat(ttotal_amount).toFixed(2));
    };
    
    $(document).ready(function () {
    
        $("#add_item").click(function () {
            var accountId = $('.paytoAccount').val();
            var accountName = $(".paytoAccount").find('option:selected').attr('paytoAccountName');
            var accountCode = $(".paytoAccount").find('option:selected').attr('paytoAccountCode');
            var amount = $('.amountt').val();
            var memo = $('.memo').val();
        
            if(accountId  == '' || accountId == null){
                productItemValidation("Account Head can't be empty.");
                return false;
            }else if(amount == ''){
                productItemValidation("Amount can't be empty.");
                return false;
            }else{
                $("#show_item tbody").append('<tr class="new_item' + accountId + '"><td style="padding-left:15px;">' + accountName + ' [ ' +  accountCode    + ' ] ' + '<input type="hidden" name="accountCr[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right">' + amount + '<input class="amount" type="hidden"  name="amountCr[]" value="' + amount + '"></td><td align="right">' + memo + '<input type="hidden" class="add_quantity" name="memoCr[]" value="' + memo + '"></td><td><a del_id="' + accountId  + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
            }
            $('.amountt').val('');
            $('.memo').val('');
          
            $('.paytoAccount').val('').trigger('chosen:updated');
            findAmount();
                                          
        });
        $(document).on('click','.delete_item', function () {
            if(confirm("Are you sure?")){
                var id = $(this).attr("del_id");
                $('.new_item' + id).remove();
                findAmount();
            }
        });
    });
</script>  




