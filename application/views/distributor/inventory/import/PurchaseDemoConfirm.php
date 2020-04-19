
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
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Purchase Voucher Posting</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('demolist'); ?>" class="btn btn-info pull-right">
                    <i class="ace-icon fa fa-backward "></i>
                    Purchase Posting List
                </a>
            </span>
        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action="<?php echo site_url('purchases_add/'.$configInfo->purchase_demo_id);?>"  method="post" class="form-horizontal">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Supplier ID</label>
                                <div class="col-sm-6">
                                    <select  id="supplierid" name="supplierID" onchange="get_menu_list()" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by supplier id or name">
                                        <option></option>
                                        <?php foreach ($supplierList as $key => $each_info): ?>
                                            <option <?php
                                        if ($configInfo->supplierID == $each_info->sup_id) {
                                            echo "selected";
                                        }
                                            ?> value="<?php echo $each_info->sup_id; ?>"><?php echo $each_info->supID . ' [ ' . $each_info->supName . ' ] '; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-3" id="hideNewSup">
                                    <a  data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;New Supplier</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher ID</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="voucherid" value="<?php echo $configInfo->voucherid; ?>" class="form-control" placeholder="Product Code" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Reference</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="reference"  value="<?php echo $configInfo->reference; ?>" class="form-control" placeholder="Reference" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Purchases Date</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="purchasesDate" id="id-date-picker-1" type="text" value="<?php echo date("d-m-Y", strtotime($configInfo->purchasesDate)) ?>" data-date-format="dd-mm-yyyy" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Payment Type</label>
                                <div class="col-sm-6">
                                    <select onchange="showBankinfo(this.value)"  name="paymentType"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Select Payment Type">
                                        <option <?php
                                            if ($configInfo->paymentType == 1) {
                                                echo "selected";
                                            }
                                            ?> value="1">Cash</option>
                                        <option <?php
                                            if ($configInfo->paymentType == 2) {
                                                echo "selected";
                                            }
                                            ?> value="2">Credit</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="hideAccount">                    
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Pay. From( CR ) <span style="color:red;">*</span></label>
                                <div class="col-sm-6">
                                    <select  name="accountCr" class="chosen-select form-control  checkAccountBalance" id="form-field-select-3" data-placeholder="Search by payee"  onchange="check_pretty_cash(this.value)">
                                        <option value="" disabled selected>---Select Account Head---</option>
                                        <?php
                                        foreach ($accountHeadList as $key => $head) {
                                            ?>
                                            <optgroup label="<?php echo $head['parentName']; ?>">
                                                <?php
                                                foreach ($head['Accountledger'] as $eachLedger) :
                                                    ?>
                                                    <option <?php
                                            if ($configInfo->accountCr == $eachLedger->chartId) {
                                                echo 'selected="selected"';
                                            }
                                                    ?> value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                                    <?php endforeach; ?>
                                            </optgroup>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="accountBalance" readonly name="balance"  value="" class="form-control" placeholder="Balance" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div id="showBankInfo" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-4">
                                        <input type="text" value="" name="bankName" class="form-control" placeholder="Bank Name"/>
                                    </div> 
                                    <div class="col-sm-3">
                                        <input type="text" value="" name="branchName" class="form-control" placeholder="Branch Name"/>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-4">
                                        <input type="text" value="" class="form-control" name="checkNo" placeholder="Check NO"/>
                                    </div> 
                                    <div class="col-sm-3">
                                        <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="id-date-picker-1" type="text" value="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd" />
                                    </div> 
                                </div>
                            </div>
                        </div>



                        <?php
                                      
                        $productCat = explode(",", $configInfo->category_product);
                        $product = explode(",", $configInfo->productID);
                        $productUnit = explode(",", $configInfo->productUnit);
                        $productQty = explode(",", $configInfo->quantity);
                        $productRate = explode(",", $configInfo->rate);
                        $productPrice = explode(",", $configInfo->price);

                       
                        ?>
                        <div class="col-md-10 col-md-offset-1">
                            <div class="table-header">
                                Purchases Item
                            </div>
                            <table class="table table-bordered table-hover" id="show_item">
                                <thead>
                                    <tr>
                                        <td style="width:20%"  align="center"><strong>Product Category</strong></td>
                                        <td style="width:20%" align="center"><strong>Product</strong></td>
                                        <td style="width:20%" align="center"><strong>Unit</strong></td>
                                        <td style="width:15%" align="center"><strong>Quantity</strong></td>
                                        <td style="width:15%" align="center"><strong>Unit Price(BDT) </strong></td>
                                        <td nowrap style="width:15%" align="center"><strong>Total Price(BDT)</strong></td>
                                        <td style="width:15%" align="center"><strong>Action</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productCat as $key => $value): ?>

                                        <tr class="new_item<?php echo $key+1212;?>">
                                            <td style="padding-left:15px;"><?php echo $this->Common_model->tableRow('productcategory', 'category_id', $value)->title; ?><input type="hidden" name="category_id[]" value="<?php echo $value; ?>"></td>
                                            <td style="padding-left:15px;"><?php echo $this->Common_model->tableRow('product', 'product_id', $product[$key])->productName; ?><input type="hidden" name="product_id[]" value="<?php echo $product[$key]; ?>"></td>
                                            <td style="padding-left:15px;"><?php echo $this->Common_model->tableRow('unit', 'unit_id', $productUnit[$key])->unitTtile; ?><input type="hidden" name="unit_id[]" value="<?php echo $productUnit[$key]; ?>"></td>
                                            <td align="right"><?php echo $productQty[$key]; ?><input type="hidden" class="add_quantity" name="quantity[]" value="<?php echo $productQty[$key]; ?>"></td>
                                            <td align="right"><?php echo $productRate[$key]; ?><input type="hidden" class="add_rate" name="rate[]" value="<?php echo $productRate[$key]; ?>"></td>
                                            <td align="right"><?php echo $productPrice[$key]; ?><input type="hidden" class="add_price" name="price[]" value="<?php echo $productPrice[$key]; ?>"></td>
                                            <td><a del_id="<?php echo $key+1212;?>" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>
                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>
                                <?php
                                $productCat = $this->db->get("productcategory")->result();
                                ?>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <!--<select data-placeholder="(:-- Select Category --:)"  class="category_product select-search1">-->
                                            <select id="category_product"  onchange="getProductList(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Category">
                                                <option value=""></option>
                                                <?php
                                                foreach ($productCat as $eachCat):
                                                    ?>
                                                    <option catName="<?php echo $eachCat->title; ?>" value="<?php echo $eachCat->category_id; ?>">
                                                        <?php echo $eachCat->title; ?>
                                                    </option>													
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select  id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by product">
                                                <option value=""></option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="productUnit" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Unit ">
                                                <option value=""></option>
                                                <?php
                                                foreach ($unitList as $eachUnit):
                                                    ?>
                                                    <option unitName="<?php echo $eachUnit->unitTtile; ?>" value="<?php echo $eachUnit->unit_id; ?>">
                                                        <?php echo $eachUnit->unitTtile; ?>
                                                    </option>													
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control text-right quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>
                                        <td><input type="text" class="form-control text-right rate" placeholder="0.00" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" ></td>
                                        <td><input type="text" class="form-control text-right price" placeholder="0.00" readonly="readonly"></td>
                                        <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                    </tr> 
                                    <tr>
                                        <td align="right" colspan="3"><strong>Sub-Total(BDT)</strong></td>
                                        <td align="right"><strong class="total_quantity"></strong></td>
                                        <td align="right"><strong class="total_rate"></strong></td>
                                        <td align="right"><strong class="total_price"></strong></td>
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
                                        <textarea cols="100" rows="2" name="narration" placeholder="Narration" type="text"></textarea>

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

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Supplier</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm2" action=""  method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier ID </label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="supplierId" readonly value="<?php echo isset($supplierID) ? $supplierID : ''; ?>" class="form-control" placeholder="SupplierID" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier Name </label>

                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="supName" class="form-control required" placeholder="Name" />
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" maxlength="11" id="form-field-1" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onblur="checkDuplicatePhone(this.value)" name="supPhone" placeholder="Phone" class="form-control" />
                                    <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                                <div class="col-sm-6">
                                    <input type="email" id="form-field-1" name="supEmail" placeholder="Email" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address</label>
                                <div class="col-sm-6">
                                    <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                                    <textarea  cols="6" rows="3" placeholder="Type Address.." class="form-control" name="supAddress"></textarea>
                                </div>
                            </div>



                            <div class="clearfix form-actions" >
                                <div class="col-md-offset-3 col-md-9">
                                    <button onclick="saveNewSupplier()" id="subBtn2" class="btn btn-info" type="button" >
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset" data-dismiss="modal">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>

    </div>
</div>


<script>
    $('#accountBalance').hide();
    $(document).ready(function () {
        $('.checkAccountBalance').change(function(){		
            var accountId = $(this).val();		
            $.ajax({
                type: 'POST', 
                data: {account: accountId}, 
                url: '<?php echo site_url('FinaneController/checkOnlyBalanceForPayment'); ?>', 
                success: function(result){ 
                    $('#accountBalance').show();
                    $('#accountBalance').val(''); 
                    $('#accountBalance').val(result); 
                } 
            });
        });
    });
    function showBankinfo(id){
    
        $('#accountBalance').hide();
        if(id == 3){
            $("#showBankInfo").show(1000); 
        }else{
            $("#showBankInfo").hide(1000);   
        }
       
        if(id == 1){
            $("#hideAccount").show(1000);
        }else{
            $("#hideAccount").hide(1000); 
        }
        
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
    
    $(document).ready(function () {
        $('.quantity').keyup(function () {
            $(this).val(parseFloat($(this).val()));
            priceCal();
        });
        $('.rate').keyup(function () {
            $(this).val(parseFloat($(this).val()).toFixed(2));
            priceCal();
        });
    });
    function priceCal() {
        var quantity = $('.quantity').val();
        
        if(isNaN(quantity)){
            quantity=0;
        }
        
        var rate = $('.rate').val();
        if(isNaN(rate)){
            rate=0;
        }
        
        $('.price').val(parseFloat(rate * quantity).toFixed(2));
    }


    var findTotalQty = function () {
        var total_quantity = 0;
        $.each($('.add_quantity'), function () {
            quantity = $(this).val();
            quantity = Number(quantity);
            total_quantity += quantity;
        });
        $('.total_quantity').html(parseFloat(total_quantity));
    };


    var findTotalRate = function () {
        var total_rate = 0;
        $.each($('.add_rate'), function () {
            rate = $(this).val();
            rate = Number(rate);
            total_rate += rate;
        });
        $('.total_rate').html(parseFloat(total_rate).toFixed(2));
    };
    var findTotalPrice = function () {
        var total_price = 0;
        $.each($('.add_price'), function () {
            price = $(this).val();
            price = Number(price);
            total_price += price;
        });
        $('.total_price').html(parseFloat(total_price).toFixed(2));

    };
    var findTotalCal = function () {
        findTotalQty();
        findTotalRate();
        findTotalPrice();
    };

    
    
    $(document).ready(function () {

        var j = 0;
        $("#add_item").click(function () {
            var productCatID = $('#category_product').val();
            var productCatName = $("#category_product").find('option:selected').attr('catName');
       
            var productID = $('#productID').val();
            var productName = $('#productID').find('option:selected').attr('productName');
            var quantity = $('.quantity').val();
            var rate = $('.rate').val();
            var price = $('.price').val();
            var productUnit = $('#productUnit').val();
            var unitName = $('#productUnit').find('option:selected').attr('unitName');
            
            
            if(quantity  == ''){
                productItemValidation("Qty can't be empty.");
                return false;
            }else if(price == ''){
                productItemValidation("Price can't be empty.");
                return false;
            
            }else if(productID == ''){
                productItemValidation("Product id can't be empty.");
                return false;
            }else {
            
                $("#show_item tbody").append('<tr class="new_item' + productCatID + productID + '"><td style="padding-left:15px;">' + productCatName + '<input type="hidden" name="category_id[]" value="' + productCatID + '"></td><td style="padding-left:15px;">' + productName + '<input type="hidden"  name="product_id[]" value="' + productID + '"></td><td style="padding-left:15px;">' +  unitName + '<input type="hidden" name="unit_id[]" value="' + productUnit + '"><td align="right">' + quantity + '<input type="hidden" class="add_quantity" name="quantity[]" value="' + quantity + '"></td><td align="right">' + rate + '<input type="hidden" class="add_rate" name="rate[]" value="' + rate + '"></td><td align="right">' + price + '<input type="hidden" class="add_price" name="price[]" value="' + price + '"></td><td><a del_id="' + productCatID + productID  + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td></tr>');
            }
            $('.quantity').val('');
            $('.rate').val('');
            $('.price').val('');

            $('#productID').val('').trigger('chosen:updated');
            $('#category_product').val('').trigger('chosen:updated');
            $('#productUnit').val('').trigger('chosen:updated');
            findTotalCal();
                                          
        });
        $(document).on('click','.delete_item', function () {
            if(confirm("Are you sure?")){
                var id = $(this).attr("del_id");
                $('.new_item' + id).remove();
                findTotalCal();
            }
        });
    });
               
    function getProductPrice(product_id) {
       
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('FinaneController/getProductPrice'); ?>",
            data: 'product_id=' + product_id,
            success: function (data) {
                $('.rate').val(data);
            }
        });
    }
    function getProductList(cat_id) {
       
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('FinaneController/getProductList'); ?>",
            data: 'cat_id=' + cat_id,
            success: function (data) {
                
                $('#productID').chosen();
                $('#productID option').remove();
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
                
            }
        });
    }

</script>  



<script>
    function checkDuplicateCategory(catName){
        var url = '<?php echo site_url("SetupController/checkDuplicateCategory") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:{ 'catName': catName},
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