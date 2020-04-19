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
                <li class="active">Bill Invoice Payment</li>
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
                                        <input class="form-control date-picker" disabled name="billDate" id="id-date-picker-1" type="text" value="<?php echo $billInfo->date; ?>" data-date-format="dd-mm-yyyy" />
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
                                    <input type="text" id="form-field-1" name="billVoucher" readonly value="<?php echo $billInfo->voucher_no; ?>" class="form-control" placeholder="Vouher Id" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Payee <span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <select name="billUser" disabled class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by payee">
                                        <option value=""></option>
                                        <option  <?php
if (empty($billInfo->customer_id) && empty($billInfo->supplier_id)) {
    echo "selected";
}
?> value="1">Accounts</option>
                                        <option <?php
                                            if (!empty($billInfo->customer_id)) {
                                                echo "selected";
                                            }
?> value="2">Customer</option>
                                        <option <?php
                                            if (!empty($billInfo->supplier_id)) {
                                                echo "selected";
                                            }
?>  value="3">Supplier</option>
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
                                        <td style="width:60%"  align="center"><strong>Account Head<span style="color:red;"> *</span></strong></td>
                                        <td style="width:20%" align="center"><strong>Amount<span style="color:red;"> *</span></strong></td>
                                        <td style="width:20%" align="center"><strong>Memo</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($ledgerInfo)):
                                        $allAmount = 0;
                                        foreach ($ledgerInfo as $key => $eachLedger) :
                                            if ($eachLedger->account != 50):
                                                $allAmount+=$eachLedger->debit;
                                                $accountName = $this->Common_model->tableRow('chartofaccount', 'chart_id', $eachLedger->account);
                                                ?>
                                                <tr class="new_item<?php $key + 100; ?>">
                                                    <td style="padding-left:15px;"><?php echo $accountName->title; ?>  [ <?php echo $accountName->accountCode; ?> ] <input type="hidden" name="accountDr[]" value="<?php echo $eachLedger->account; ?>"></td>
                                                    <td style="padding-left:15px;" align="right"><?php echo $eachLedger->debit; ?><input class="amount amount3" type="hidden" name="amountDr[]" value="<?php echo $eachLedger->debit; ?>"></td>
                                                    <td align="right"><?php echo $eachLedger->memo; ?><input type="hidden" class="add_quantity" name="memoDr[]" value="<?php echo $eachLedger->memo; ?>"></td>
                                                </tr>
                                                <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="right"><strong>Total(BDT)</strong></td>
                                        <td align="right"><strong class="tttotal_amount"><?php echo number_format((float) $allAmount, 2, '.', ','); ?></strong></td></tr> 
                                </tfoot> 
                            </table> 
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-md-10 col-md-offset-1">
                            <table class="table table-bordered table-hover" id="show_item2">
                                <thead>
                                    <tr>
                                        <th style="width:20%"  align="center"><strong>Date</strong></th>
                                        <th style="width:40%" align="center"><strong>Account Head</strong></th>
                                        <th style="width:20%" align="center"><strong>Amount</strong></th>
                                        <th style="width:20%" align="center"><strong>Memo</strong></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <input class="form-control date-picker" name="date" id="paymentDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" required/>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="accountCr"  class="chosen-select form-control paytoAccount" id="payAccount" data-placeholder="Search by Account Head"  onchange="check_pretty_cash(this.value)" required>
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
                                        <td><input type="text" name="paymentCr" id="payAmount" onkeyup="checkStockOverQty2(this.value)" class="form-control text-right quantity2" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0.00" required></td>
                                        <td><input type="text"   class="form-control"   placeholder="Memo"></td>

                                    </tr> 
                                    <tr>
                                        <td align="right" colspan="3"></td>
                                        <td><button  onclick="return isconfirm2()" class="btn btn-success form-control" type="button"><i class="ace-icon fa fa-check bigger-110"></i>Approve</button></td>
                                    </tr>
                                </tfoot> 
                            </table> 
                        </div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>

    function isconfirm2(){
        
        var paymentDate=$("#paymentDate").val();
        var payAccount=$("#payAccount").val();
       
        var payAmount=parseFloat($("#payAmount").val());
        if(isNaN(payAmount)){
            payAmount=0;
        }
        
        if(payAccount == ''){
            swal("Please Select Payment Head", "Validation Error!", "error");
            
        }else if(paymentDate == ''){
            swal("Select Payment Date!", "Validation Error!", "error");
        }else if(payAmount == ''){
            swal("Please Type Payment Amount", "Validation Error!", "error");
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
                $("#show_item tbody").append('<tr class="new_item' + accountId + '"><td style="padding-left:15px;">' + accountName + ' [ ' +  accountCode    + ' ] ' + '<input type="hidden" name="accountDr[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right">' + amount + '<input class="amount amount3 decimal" type="hidden"  name="amountDr[]" value="' + amount + '"></td><td align="right">' + memo + '<input type="hidden" class="add_quantity" name="memoDr[]" value="' + memo + '"></td><td><a del_id="' + accountId  + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
            }
            $('.amountt').val('');
            $('.memo').val('');
            $('.paytoAccount').val('').trigger('chosen:updated');
            // $(".paytoAccount").trigger("chosen:updated");
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


<script>
   
    
    var url ='<?php echo site_url("FinaneController/getPayUserListPosting") ?>';
    $.ajax({
        type: 'POST',
        url: url,
        data:{'payid':'<?php
                                                if (!empty($billInfo->customer_id)):
                                                    echo 2;
                                                elseif (!empty($billInfo->supplier_id)):
                                                    echo 3;
                                                else:
                                                    echo 1;
                                                endif;
                                                ?>','payUserId':'<?php
                                                if (!empty($billInfo->customer_id)):echo $billInfo->customer_id;
                                                elseif (!empty($billInfo->supplier_id)): echo $billInfo->supplier_id;
                                                endif;
                                                ?>'},
        
                                                            success: function (data)
                                                            {
                                                                $("#searchValue").html(data);
                                                                $("#oldValue").hide(1000);
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
                                                                    $("#oldValue").hide(1000);
                                                                    $('.chosenRefesh').chosen();
                                                                    $(".chosenRefesh").trigger("chosen:updated");
                                                                }
                                                            });
        
                                                        }



</script>