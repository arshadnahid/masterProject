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
                    <a href="#">Journal</a>
                </li>
                <li class="active">Journal Voucher Posting</li>
            </ul>

            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('journalVoucher'); ?>" class="btn btn-danger pull-right">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="date" id="id-date-picker-1" type="text" value="<?php
if (!empty($journalPosting->purchasesDate)) {
    echo $journalPosting->purchasesDate;
} else {
    echo date('d-m-Y');
}
?>" data-date-format="dd-mm-yyyy" />
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
                                               if (!empty($journalPosting->voucherid)) {
                                                   echo $journalPosting->voucherid;
                                               } else {
                                                   echo $voucherID;
                                               }
?>" class="form-control" placeholder="Product Code" />
                                </div>
                            </div>
                        </div>



                        <div class="col-md-10 col-md-offset-1">
                            <br>
                            <div class="table-header">
                                Select Account Head
                            </div>
                            <?php
                            // dumpVar($journalPosting);
                            $accountDr = explode(",", $journalPosting->accountDr);
                            $accountCr = explode(",", $journalPosting->accountCr);
                            $drAmount = explode(",", $journalPosting->drAmount);
                            $crAmount = explode(",", $journalPosting->crAmount);
                            ?>

                            <table class="table table-bordered table-hover" id="show_item">
                                <thead>
                                    <tr>
                                        <td style="width:35%"  align="center"><strong>Account Head</strong></td>
                                        <td style="width:15%" align="center"><strong>Debit</strong></td>
                                        <td style="width:15%" align="center"><strong>Credit</strong></td>
                                        <td style="width:20%" align="center"><strong>Memo</strong></td>
                                        <td style="width:15%" align="center"><strong>Action</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tdr = 0;

                                    foreach ($accountDr as $key => $value):
                                        $tdr+=$drAmount[$key];
                                        ?>
                                        <tr class="new_item<?php echo $key + 1000; ?>">
                                            <td style="padding-left:15px;">

                                                <?php
                                                $accountId = $this->Common_model->tableRow('chartofaccount', 'chart_id', $value);
                                                echo $accountId->title . ' [ ' . $accountId->accountCode . ' ] ';
                                                ?><input type="hidden" name="account[]" value="<?php echo $value; ?>">
                                            </td>
                                            <td style="padding-left:15px;" align="right"><?php echo $drAmount[$key]; ?><input class="amountDr" type="hidden" name="amountDr[]" value="<?php echo $drAmount[$key]; ?>"></td>
                                            <td style="padding-left:15px;" align="right"><input class="amountCr" type="hidden" name="amountCr[]" value=""></td>
                                            <td align="right"><input type="text" class="add_quantity form-control" name="memo[]" value=""></td>
                                            <td><a del_id="<?php echo $key + 1000; ?>" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>
                                        </tr>

                                    <?php endforeach; ?>

                                    <?php
                                    $tcr = 0;
                                    foreach ($accountCr as $key => $value):
                                        $tcr+=$crAmount[$key];
                                        ?>
                                        <tr class="new_item<?php echo $key + 2000; ?>">
                                            <td style="padding-left:15px;">

                                                <?php
                                                $accountId = $this->Common_model->tableRow('chartofaccount', 'chart_id', $value);
                                                echo $accountId->title . ' [ ' . $accountId->accountCode . ' ] ';
                                                ?><input type="hidden" name="account[]" value="<?php echo $value; ?>">
                                            </td>
                                            <td style="padding-left:15px;" align="right"><input class="amountDr" type="hidden" name="amountDr[]" value=""></td>
                                            <td style="padding-left:15px;" align="right"><?php echo $crAmount[$key]; ?><input class="amountCr" type="hidden" name="amountCr[]" value="<?php echo $crAmount[$key]; ?>"></td>
                                            <td align="right"><input type="text" class="add_quantity  form-control" name="memo[]" value=""></td>
                                            <td><a del_id="<?php echo $key + 2000; ?>" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>
                                        </tr>

                                    <?php endforeach; ?>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>

                                            <select   class="chosen-select form-control accountId" id="form-field-select-3" data-placeholder="Search by payee"  onchange="check_pretty_cash(this.value)">
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
                                        <td><input type="text" class="form-control text-right amountDrValue" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0.00"></td>
                                        <td><input type="text" class="form-control text-right amountCrValue" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0.00"></td>
                                        <td><input type="text" class="form-control text-right memo" placeholder="Memo"  ></td>
                                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                    </tr> 
                                    <tr>
                                        <td align="right"><strong>Sub-Total (In.BDT)</strong></td>
                                        <td align="right"><strong class="total_dr"><?php echo $tdr; ?></strong></td>
                                        <td align="right"><strong class="total_cr"><?php echo $tcr; ?></strong></td>
                                    </tr> 
                                </tfoot> 
                            </table> 
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Narration</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <textarea cols="100" rows="2" name="narration" placeholder="Narration" type="text"><?php echo $journalPosting->narration;?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button 
                                    <?php if($tdr !=$tcr){
                                        echo "disabled";
                                    }?>
                                    
                                    
                                    onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
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
    
   
    
    
    var checkValidation = function () {
        var totalDr = parseFloat($(".total_dr").text());
        var totalCr = parseFloat($(".total_cr").text());
      
        if(isNaN(totalDr)){
            totalDr=0;
        }
        if(isNaN(totalCr)){
            totalCr=0;
        }
    
        
        if(totalDr == totalCr){
            $("#subBtn").attr('disabled',false);
           
        }else{
            $("#subBtn").attr('disabled',true); 
            
        }
        
    };
    
    
    
    
    
    
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
   
    $('.amountDrValue').change(function(){ 
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });  
    $('.amountCrValue').change(function(){ 
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });  
    var findAmountDr = function () {
        var ttotal_amountDr = 0;
        $.each($('.amountDr'), function () {
            amount = $(this).val();
            amount = Number(amount);
            ttotal_amountDr += amount;
        });
        $('.total_dr').html(parseFloat(ttotal_amountDr).toFixed(2));
    };
    
    var findAmountCr = function () {
        var ttotal_amountCr = 0;
        $.each($('.amountCr'), function () {
            amount = $(this).val();
            amount = Number(amount);
            ttotal_amountCr += amount;
        });
        $('.total_cr').html(parseFloat(ttotal_amountCr).toFixed(2));
    };
    
    $(document).ready(function () {
    
        $("#add_item").click(function () {
            var accountId = $('.accountId').val();
            var accountName = $(".accountId").find('option:selected').attr('paytoAccountName');
            var accountCode = $(".accountId").find('option:selected').attr('paytoAccountCode');
            var amountDr = $('.amountDrValue').val();
            var amountCr = $('.amountCrValue').val();
            var memo = $('.memo').val();
            if(accountId  == '' || accountId == null){
                productItemValidation("Account Head can't be empty.");
                return false;
            }else if(amountDr == '' && amountCr == ''){
                productItemValidation("Debit or Credit amount can't be empty.");
                return false;
            }else{
                $("#show_item tbody").append('<tr class="new_item' + accountId + '"><td style="padding-left:15px;">' + accountName + ' [ ' +  accountCode    + ' ] ' + '<input type="hidden" name="account[]" value="' + accountId + '"></td><td style="padding-left:15px;"  align="right">' + amountDr + '<input class="amountDr" type="hidden"  name="amountDr[]" value="' + amountDr + '"></td><td style="padding-left:15px;"  align="right">' + amountCr + '<input class="amountCr" type="hidden"  name="amountCr[]" value="' + amountCr + '"></td><td align="right">' + memo + '<input type="hidden" class="add_quantity" name="memo[]" value="' + memo + '"></td><td><a del_id="' + accountId  + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
            }
            $('.amountDrValue').val('');
            $('.amountCrValue').val('');
            $('.memo').val('');
            $('.accountId').val('').trigger('chosen:updated');
           
            findAmountDr();
            findAmountCr();
            checkValidation();
                                          
        });
        $(document).on('click','.delete_item', function () {
            if(confirm("Are you sure?")){
                var id = $(this).attr("del_id");
                $('.new_item' + id).remove();
                findAmountDr();
                findAmountCr();
            }
        });
    });
</script>  



