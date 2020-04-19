<style>
    table tr td{
        margin: 2px!important;
        padding: 2px!important;
    }
</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Bill</a>
                </li>
                <li class="active">Bill Invoice Add</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a title="Bill voucher listt" href="<?php echo site_url('billInvoice'); ?>">
                        <i class="ace-icon fa fa-list"></i> List
                    </a>
                </li>
            </ul>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date<span style="color:red;"> *</span></label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="billDate" id="paymentDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher ID<span style="color:red;"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="billVoucher" readonly value="<?php echo $voucherID; ?>" class="form-control" placeholder="Product Code" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Payee <span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <select name="billUser" onchange="selectPayType(this.value)" class="chosen-select form-control" id="payType" data-placeholder="Search by payee">
                                        <option value=""></option>
                                        <option value="1">Accounts</option>
                                        <option value="2">Customer</option>
                                        <option value="3">Supplier</option>
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
                        <div class="col-md-10 col-md-offset-1">
                            <br>
                            <div class="table-header">
                                Select Account Head
                            </div>
                            <table class="table table-bordered table-hover" id="show_item">
                                <thead>
                                    <tr>
                                        <td style="width:40%"  align="center"><strong>Account Head<span style="color:red;"> *</span></strong></td>
                                        <td style="width:20%" align="center"><strong>Amount<span style="color:red;"> *</span></strong></td>
                                        <td style="width:20%" align="center"><strong>Memo</strong></td>
                                        <td style="width:20%" align="center"><strong>Action</strong></td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <select   class="chosen-select form-control paytoAccount" id="form-field-select-3" data-placeholder="Search by Account Head"  onchange="check_pretty_cash(this.value)">
                                                <option value=""></option>
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
                                        <td><input type="text" class="form-control text-right amountt amount3" onkeyup="checkOverAmount(this.value)" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0.00"></td>
                                        <td><input type="text" class="form-control text-right memo" placeholder="Memo"  ></td>
                                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                    </tr> 
                                    <tr>
                                        <td align="right"><strong>Total(BDT)</strong></td>
                                        <td align="right"><strong class="tttotal_amount"></strong></td>

                                        <td align="right"><strong class=""></strong></td>
                                        <td></td>
                                    </tr> 
                                </tfoot> 
                            </table> 

                            <div class="clearfix"></div>
                            <div class="clearfix form-actions" >
                                <div class="col-md-10"></div>
                                <div class="col-md-offset-2">
                                    <button  onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">
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

    $(document).on("keyup", ".amount", function () {
        findAmount();
        
    });

    function isconfirm2(){
        
        var paymentDate=$("#paymentDate").val();
        var payType=$("#payType").val();
       
        var miscellaneous=$("#miscellaneous").val();
        var customerId=$("#customerId").val();
        var supplierId=$("#supplierId").val();
        
      
       
        var totalPrice=parseFloat($(".tttotal_amount").text());
        if(isNaN(totalPrice)){
            totalPrice=0;
        }
        
        if(payType == ''){
            swal("Select Payee!", "Validation Error!", "error");
            
        }else if(paymentDate == ''){
            swal("Select Payment Date!", "Validation Error!", "error");
        }else if(payType == 1 && miscellaneous == ''){
            swal("Please Type Account Name", "Validation Error!", "error");
        }else if(payType == 2 && customerId == ''){
            swal("Select Customer Name", "Validation Error!", "error");
        }else if(payType == 3 && supplierId == ''){
            swal("Please Select Supplier!", "Validation Error!", "error");
        }else if(totalPrice == ''){
            swal("Please Add Account Head!", "Validation Error!", "error");
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


<script type="text/javascript">
   
    $('.amountt').change(function(){ 
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });  
    var findAmount = function () {
        var ttotal_amount = 0;
        $.each($('.amount'), function () {
            amount = $(this).val();
            if(isNaN(amount)){
                amount=0;
            }
            amount = Number(amount);
            ttotal_amount += amount;
        });
        
        if(ttotal_amount > 0){
            $("#subBtn").attr('disabled',false);
        }
        
        $('.tttotal_amount').html(parseFloat(ttotal_amount).toFixed(2));
    };
    
    $(document).ready(function () {
        var j=0;
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
                $("#show_item tbody").append('<tr class="new_item' + j + '"><td style="padding-left:15px;">' + accountName + ' [ ' +  accountCode    + ' ] ' + '<input type="hidden" name="accountDr[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right"><input class="amount amount3 form-control text-right decimal" type="text"  name="amountDr[]" value="' + amount + '"></td><td align="right"><input type="text" class="add_quantity form-control text-right" name="memoDr[]" value="' + memo + '"></td><td><a del_id="' + j  + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
            }
            $('.amountt').val('');
            $('.memo').val('');
            $('.paytoAccount').val('').trigger('chosen:updated');
            // $(".paytoAccount").trigger("chosen:updated");
            findAmount();
            j++;
                                          
        });
        $(document).on('click','.delete_item', function () {
        
            var id = $(this).attr("del_id");
            swal({
                title: "Are you sure ?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#73AE28',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnCancel: true,
                closeOnConfirm: true,
                type: 'success'
            },
            function (isConfirm) {
                if (isConfirm) {
                    $('.new_item' + id).remove();
                    findAmount();
                }else{
                    return false;
                }
            });  
        
        
        
          
        });
    });
</script>  


<script>
    function selectPayType(payid){
        
        var url = '<?php echo site_url("FinaneController/getPayUserList") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{'payid':payid},
            success: function (data)
            {
                $("#searchValue").html(data);
                $("#oldValue").hide(1000);
                $('.chosenRefesh').chosen();
                $(".chosenRefesh").trigger("chosen:updated");
            }
        });
        
    }



</script>