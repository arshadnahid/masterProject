
<script src="//code.jquery.com/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jQueryUI/jquery-new-ui.js"></script>


<style>
    table tr td{
        margin: 2px!important;
        padding: 2px!important;
    }

    .autocomplete{
        /*        margin-top: 15px;
                margin-bottom: 20px;
                padding-left: 0px;*/
    }

</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/2'); ?>">Sales</a>
                </li>
                <li class="active">Sales POS</li>
                <li class="active"><span style="color:red;"> *</span> <span style="color: red">Mark field must be fill up</span></li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li class="active">
                    <i class="ace-icon fa fa-list"></i>
                    <a href="<?php echo site_url('salesPosList'); ?>">List</a>
                </li>
            </ul>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <input type="hidden" name="forPrint" id="forPrint" value="WithOutPrint"/>

                        <table class="mytable table-responsive table table-bordered">
                            <tr>
                                <td  style="padding: 10px!important;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Customer ID <span style="color:red;"> *</span></label>
                                            <div class="col-sm-6">
                                                <select  id="customerid" name="customer_id"  class="chosen-select form-control" onchange="getCustomerCurrentBalace(this.value)" id="form-field-select-3" data-placeholder="Search by Customer ID OR Name">
                                                    <option></option>
                                                    <?php foreach ($customerList as $key => $each_info): ?>
                                                        <option value="<?php echo $each_info->customer_id; ?>"><?php echo $each_info->customerID . ' [ ' . $each_info->customerName . ' ] '; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-2" id="newCustomerHide">
                                                <a  data-toggle="modal" data-target="#myModal" class="saleAddPermission btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;New</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Invoice No</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="form-field-1" name="userInvoiceId" value="" class="form-control" placeholder="Invoice No"/>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" id="form-field-1" name="voucherid" readonly value="<?php echo $voucherID; ?>" class="form-control" placeholder="Invoice ID" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Reference</label>
                                            <div class="col-sm-6">
                                                <select  name="reference"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Reference ID OR Name">
                                                    <option></option>
                                                    <?php foreach ($referenceList as $key => $each_ref): ?>
                                                        <option value="<?php echo $each_ref->reference_id; ?>"><?php echo $each_ref->referenceName; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
            <!--                                    <input type="text" id="form-field-1" name="reference"  value="" class="form-control" placeholder="Reference" />
                                                -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sales Date  <span style="color:red;"> *</span></label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control date-picker" name="saleDate" id="saleDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
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
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Payment Type  <span style="color:red;"> *</span></label>
                                            <div class="col-sm-6">
                                                <select onchange="showBankinfo(this.value)"  name="paymentType"  class="chosen-select form-control" id="paymentType" data-placeholder="Select Payment Type">
                                                    <option></option>
                                                    <!--<option value="1">Full Cash</option>-->
                                                    <option selected value="4">Cash</option>
                                                    <option value="2">Credit</option>
                                                    <option value="3">Cheque / DD/ PO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Shipping Address</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" placeholder="Shipping Address" name="shippingAddress" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div id="showBankInfo" style="display:none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-2">
                                                    <input type="text" value="" name="bankName" id="bankName" class="form-control" placeholder="Bank Name"/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" value="" name="branchName" id="branchName" class="form-control" placeholder="Branch Name"/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" value="" class="form-control" id="checkNo" name="checkNo" placeholder="Check NO"/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">


                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px!important;">

                                    <div class="col-md-12">
                                        <div class="table-header">
                                            Sales Item
                                        </div>

                                        <div class="col-md-12 autocomplete"  style="">
                                            <div class="row">


                                                <div class="input-group">

                                                    <span class=" input-group-addon glyphicon glyphicon-search"></span>
                                                    <input id="productNameAutocomplete" class="form-control "
                                                           placeholder="Scan/Search Product by Name/Code" autocomplete="off">


                                                </div>



                                            </div>

                                        </div>

                                        <table class="table table-bordered table-hover" id="show_item">
                                            <thead>
                                                <tr>
                                                    <th nowrap style="width:27%;border-radius:10px;" align="center"><strong>Product <span style="color:red;"> *</span></strong></th>
                                                    <th nowrap style="width:10%;border-radius:10px;" align="center"><strong>Quantity <span style="color:red;"> *</span></strong></th>
                                                    <th nowrap style="width:7%;border-radius:10px;" align="center"><strong>Returnable(Qty)</strong></th>
                                                    <th nowrap style="width:10%;border-radius:10px;" align="center"><strong>Unit Price(BDT)  <span style="color:red;"> *</span></strong></th>
                                                    <th nowrap style="width:13%;border-radius:10px;" align="center"><strong>Total Price(BDT) <span style="color:red;"> *</span></strong></th>
                                                    <th style="width:8%;border-radius:10px;" align="center"><strong>Action</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        <select id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product">
                                                            <option value=""></option>
                                                            <?php foreach ($productListWithCat as $key => $eachProduct):
                                                                ?>
                                                                <optgroup label="<?php echo $eachProduct['categoryName']; ?>">
                                                                    <?php
                                                                    foreach ($eachProduct['productInfo'] as $eachInfo) :
                                                                        ?>
                                                                        <option categoryName="<?php echo $eachProduct['categoryName']; ?>" categoryId="<?php echo $eachProduct['categoryId']; ?>" productName="<?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?>" value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?></option>
                                                                    <?php endforeach; ?>
                                                                </optgroup>
                                                                <?php
                                                            endforeach;
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="hidden" value="" id="stockQty"/><input type="text"  onkeyup="checkStockOverQty(this.value)" class="form-control text-right quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>
                                                    <td><input type="hidden" value="" id="returnStockQty"/><input type="text" readonly onkeyup="checkReturnStockOverQty(this.value)" class="form-control text-right returnQuantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>
                                                    <td><input type="text" class="form-control text-right rate" placeholder="0.00" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" ></td>
                                                    <td><input type="text" class="form-control text-right price" placeholder="0.00" readonly="readonly"></td>
                                                    <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><strong>Total(BDT)</strong></td>
                                                    <td align="right"><strong class="total_quantity1"></strong></td>
                                                    <td align="right"><strong class="total_return_quantity"></strong></td>
                                                    <td align="right"><strong class="total_rate2"></strong></td>
                                                    <td align="right"><strong class="total_price"></strong></td>
                                                </tr>
                                                <tr>
                                                    <td  rowspan="4">
                                                        <textarea style="border:none;" rows="6" class="form-control" name="narration" placeholder="Narration......" type="text"></textarea>
                                                    </td>
                                                    <td colspan="3"  align="right"><strong>Discount ( - ) </strong></td>
                                                    <td><input type="text"  onkeyup="calDiscount()" id="disCount" style="text-align: right" name="discount" value="" class="form-control" placeholder="0.00"   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>
                                                </tr>
                                                <tr>
                                                    <td  colspan="3"   align="right"><strong>Grand Total</strong></td>
                                                    <td><input readonly id="grandTotal" type="text" style="text-align: right" name="grandtotal" value="" class="form-control"  placeholder="0.00"/></td>
                                                </tr>
                                                <tr>
                                                    <td  colspan="3"   align="right"><strong>VAT(%) ( + )</strong></td>
                                                    <td><input type="text" id="vatAmount"  style="text-align: right" name="vat" readonly value="<?php
                                                            if (!empty($configInfo->VAT)): echo $configInfo->VAT;
                                                            endif;
                                                            ?>" class="form-control totalVatAmount"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>
                                                </tr>
                                                <tr>
                                                    <td  colspan="3"  align="right"><strong>Net Total</strong></td>
                                                    <td><input type="text" id="netAmount"  style="text-align: right" name="netTotal" value="" readonly class="form-control"  placeholder="0.00"/></td>
                                                    <td>
                                                        <input type="text" class="form-control currentBalance" title="Customer Current Balance" value="" readonly/>
                                                    </td>
                                                </tr>
                                                <tr class="creditDate" style="display:none;">
                                                    <td  colspan="4"  align="right"><strong>Due Date</strong></td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input class="form-control date-picker" name="creditDueDate" id="dueDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="partisals">
                                                    <td   align="right"><strong>Add Account <span style="color:red;"> * </span></strong></td>
                                                    <td width="100%" colspan="2">
                                                        <select   name="accountCrPartial" class="chosen-select   checkAccountBalance" id="partialHead" data-placeholder="Search by Account Head">
                                                            <option value=""></option>
                                                            <?php
                                                            foreach ($accountHeadList as $key => $head) {
                                                                if ($key != 51 || $key != 112) {
                                                                    ?>
                                                                    <optgroup label="<?php echo $head['parentName']; ?>">
                                                                        <?php
                                                                        foreach ($head['Accountledger'] as $eachLedger) :
                                                                            ?>
                                                                            <option value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                                                        <?php endforeach; ?>
                                                                    </optgroup>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td  align="right"><strong> Payment<span style="color:red;"> * </span></strong></td>
                                                    <td><input type="text" id="payment" onkeyup="calculatePartialPayment()" style="text-align: right" name="partialPayment" value=""  class="form-control"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>
                                            <input type="hidden" id="duePayment"  style="text-align: right" name="duePayment" value="" readonly  class="form-control"  placeholder="0.00"/>

                                            </tr>
                                            </tfoot>
                                        </table>

                                        <div id="culinderReceive">
                                            <div class="table-header">
                                                Receive Cylinder Item
                                            </div>
                                            <div class="col-md-12 autocomplete" style="">
                                                <div class="row">
                                                    <!--                                                    <div class="col-md-6">-->
                                                    <div class="input-group">

                                                        <span class=" input-group-addon glyphicon glyphicon-search"></span>
                                                        <input id="ReciveproductNameAutocomplete" class="form-control "
                                                               placeholder="Scan/Search Product by Name/Code" autocomplete="off">


                                                    </div>
                                                    <!--                                                    </div>-->

                                                </div>

                                            </div>
                                            <table class="table table-bordered table-hover" id="show_item2">
                                                <thead>
                                                    <tr>
                                                        <th style="width:18%" align="center"><strong>Product <span style="color:red;"> *</span></strong></th>
                                                        <th style="width:17%" align="center"><strong><span style="color:red;"> *</span>Received(Qty)</strong></th>
                                                        <th style="width:15%" align="center"><strong>Action</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td>
                                                            <select  id="productID2" onchange="getProductPrice2(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product name">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($productList as $eachProduct):
                                                                    ?>
                                                                    <option productName="<?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>" value="<?php echo $eachProduct->product_id; ?>">
                                                                        <?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="hidden" value="" id="stockQty2"/><input type="text"  onkeyup="checkStockOverQty2(this.value)" class="form-control text-right quantity2" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>
                                                        <!--<td><input type="hidden" value="" id="returnStockQty"/><input type="text"  onkeyup="checkReturnStockOverQty(this.value)" class="form-control text-right returnQuantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>-->
                                                        <td><a id="add_item2" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><strong>Total</strong></td>
                                                        <td align="right"><strong class="total_quantity2"></strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="clearfix"></div>
                                    <div class="clearfix form-actions" >
                                        <div class="col-md-offset-1 col-md-10">
                                    <!--        <button onclick="return isconfirm2(1)" id="subBtn" class="btn btn-info" type="button">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save
                                            </button>--->
                                            <button  onclick="return isconfirm2(2)" id="subBtnWithPrint" class="btn btn-info" type="button">
                                                <i class="ace-icon fa fa-print bigger-110"></i>
                                                Save 
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn" onclick="showCylinder()" type="button">
                                                <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                                                Receive Cylinder
                                            </button>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>


<script>

    function isconfirm2(action) {

        var customerid = $("#customerid").val();
        var saleDate = $("#saleDate").val();
        var paymentType = $("#paymentType").val();
        var paymentType = $("#paymentType").val();
        var dueDate = $("#dueDate").val();
        var partialHead = $("#partialHead").val();
        var thisAllotment = $("#payment").val();
        var bankName = $("#bankName").val();
        var branchName = $("#branchName").val();
        var checkNo = $("#checkNo").val();
        var checkDate = $("#checkDate").val();
        var cylinder = 0;
        var cylinderItem ='';
        // alert($(".add_quantityPos2").val());
        if ($("#culinderReceive").css('display') == 'none') {
            cylinder = 0;
        } else {
             //cylinderItem = parseFloat($(".add_quantityPos2").val());
             cylinderItem = parseFloat($(".add_quantityPos2").val());
             
            if (isNaN(cylinderItem)) {
                cylinderItem = 0;
            }
            cylinder = 1;
        }
        var totalPrice = parseFloat($(".total_price").text());
        if (isNaN(totalPrice)) {
            totalPrice = 0;
        }
        if (customerid == '') {
            swal("Select Customer Name!", "Validation Error!", "error");

        } else if (saleDate == '') {
            swal("Select Sale Date!", "Validation Error!", "error");
        } else if (paymentType == '') {
            swal("Select Payment Type", "Validation Error!", "error");
        } else if (paymentType == 2 && dueDate == '') {
            swal("Select Due Date!", "Validation Error!", "error");
        } else if (paymentType == 3 && bankName == '') {
            swal("Type Bank Name!", "Validation Error!", "error");
        } else if (paymentType == 3 && branchName == '') {
            swal("Type Branch Name!", "Validation Error!", "error");
        } else if (paymentType == 3 && checkNo == '') {
            swal("Type Check No!", "Validation Error!", "error");
        } else if (paymentType == 3 && checkDate == '') {
            swal("Select Check Date!", "Validation Error!", "error");
        } else if (totalPrice == '' || totalPrice < 0) {
            swal("Add Purcahses Item Price !", "Validation Error!", "error");
        } else if (paymentType == 4 && partialHead == '') {
            swal("Select Account Head!", "Validation Error!", "error");
        } else if (paymentType == 4 && thisAllotment == '') {
            swal("Given Cash Amount!", "Validation Error!", "error");
        } else if (cylinder == 1 && cylinderItem == '') {
            
            
          
            
            swal("Add Receive Cylinder Item!", "Validation Error!", "error");
        } else {

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
            // exit;
            function (isConfirm) {

                if (isConfirm) {
                    if (action == 1) {

                        exit;
                        $('#forPrint').val('WithOutPrint');
                        $("#publicForm").submit();

                    } else if (action == 2) {

                        //e.preventDefault(); // prevent default form submit
                        $('#forPrint').val('WithPrint');
                        $.ajax({
                            type: "post",
                            url: baseUrl + "PosController/salesPosAdd",

                            data: $('#publicForm').serialize(),
                            success: function (response)
                            {
                                console.log(response);


                                document.body.innerHTML = response;



                                window.print();
                                location.reload();


                            }, complete: function () {
                            }
                        });
                        //form.submit();

                    }





                } else {
                    return false;
                }
            });

        }

    }



    var form = $('#publicForm'); // contact form
    var submit = $('#subBtnWithPrint');  // submit button
    submit.on('click', function (e) {});




</script>



<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Customer</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm2" action=""  method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer ID </label>
                                <div class="col-sm-8">
                                    <input type="text" id="customerId" name="customerID" readonly value="<?php echo isset($customerID) ? $customerID : ''; ?>" class="form-control" placeholder="Customer ID" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Name </label>

                                <div class="col-sm-8">
                                    <input type="text" id="customerName" name="customerName" class="form-control" placeholder="Customer Name" required/>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>
                                <div class="col-sm-8">
                                    <input type="text"  maxlength="11" id="form-field-1 cstPhone" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onblur="checkDuplicatePhone(this.value)" name="customerPhone" placeholder="Customer Phone" class="form-control" />
                                    <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                                <div class="col-sm-8">
                                    <input type="email" id="form-field-1" name="customerEmail" placeholder="Email" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address</label>
                                <div class="col-sm-8">
                                    <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                                    <textarea  cols="6" rows="3" placeholder="Type Address.." class="form-control" name="customerAddress"></textarea>
                                </div>
                            </div>
                            <div class="clearfix form-actions" >
                                <div class="col-md-offset-3 col-md-9">
                                    <button onclick="saveNewCustomer()" id="subBtn2" class="btn btn-info" type="button">
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


<script type="text/javascript">

<?php
$opt = "";
foreach ($unitList as $eachUnit) {
    $opt .= '<option value="' . $eachUnit->unit_id . '" >' . $eachUnit->unitTtile . '</option>';
}
?>
    var unitList = '<?php echo $opt ?>';

    $("#productNameAutocomplete").autocomplete({

        source: function (request, response) {
            $.getJSON(baseUrl + "SalesController/get_product_list_by_dist_id", {term: request.term},
            response);
        },
        minLength: 1,
        delay: 100,
        response: function (event, ui) {
            if (ui.content) {
                if (ui.content.length == 1) {
                    addRowProduct(ui.content[0].id, ui.content[0].productName, ui.content[0].category_id, ui.content[0].productCatName, ui.content[0].brand_id, ui.content[0].brandName, ui.content[0].unit_id, ui.content[0].unitTtile)
                    var dataIns = $(this).val();
                    $(this).val('');
                    $(this).focus();
                    $(this).autocomplete('close');
                    return false;

                } else if (ui.content.length == 0) {
                    $(this).val('');
                    $(this).focus();
                    $(this).autocomplete('close');
                    return false;
                } else {
                    // alert("This Character and code have no item!!");
                }
            }
        },
        select: function (event, ui) {
            addRowProduct(ui.item.id, ui.item.productName, ui.item.category_id, ui.item.productCatName, ui.item.brand_id, ui.item.brandName, ui.item.unit_id, ui.item.unitTtile)
            $(this).val('');
            return false;
        }

    });
    //        $.ui.autocomplete.prototype._renderItem = function (ul, item) {
    //        var term = this.term.split(' ').join('|');
    //        var re = new RegExp("(" + term + ")", "gi");
    //        var t = item.label.replace(re, "<b style='color:red'>$1</b>");
    //        return $("<li></li>")
    //                .data("item.autocomplete", item)
    //                .append("<a>" + t + "</a>")
    //                .appendTo(ul);
    //    };




    function addRowProduct(productID, productName, productCatID, productCatName, productBrandID, productBrandName, productUnit, unitName) {// quantity,returnQuantity, rate, price
        var quantity;

        var productCatID = productCatID;
        var productCatName = productCatName;

        var productID = productID;
        var productName = productName;

        var productUnit = productUnit;
        var unitName = unitName;

        var rate = 0;
        var price = 0;
        var returnQuantity = $('.returnQuantity').val();
        $.ajax({
            type: "POST",
            url: baseUrl + "FinaneController/getProductStock",
            data: 'product_id=' + productID,
            success: function (data) {
                var mainStock = parseFloat(data);
                if (data != '') {
                    quantity = mainStock;
                }

            }, complete: function () {
                var previousProductID = parseFloat($('#productID_' + productID).val());
                $.ajax({
                    type: "POST",
                    url: baseUrl + "FinaneController/getProductPriceForSale",
                    data: 'product_id=' + productID,
                    success: function (data) {

                        if (data != '') {
                            //$('#rate_' + productID).val(data);
                        }
                    }, complete: function () {

                        if (quantity > 0) {
                            var givenQuantity = 1;
                            var previousProductQuantity = parseInt($('#qty_' + productID).val());

                            if (previousProductID == productID) {
                                givenQuantity = givenQuantity + previousProductQuantity;
                                $('#qty_' + productID).val(givenQuantity);
                                productTotal('_' + productID)
                                return true;
                            }

                            var tab = "";
                            if (productCatID == 2) {
                                tab = '<tr class="new_item' + productID + '">' +
                                    '<td style="padding-left:15px;">' +
                                    '['+ productCatName + ' ] - '+ productName + '&nbsp;[&nbsp;' + productBrandName + '&nbsp;]&nbsp;' +
                                    '<input id="productID_' + productID + '" name="productID[]" value="' + productID + '" type="hidden">' +
                                    '<input type="hidden" name="category_id[]" value="' + productCatID + '">' +
                                    '</td>' +
                                    '<input type="hidden"  name="product_id[]" value="' + productID + '">' +
                                    '<td align="right">' +
                                    '   <input type="text" id="qty_' + productID + '" class="form-control text-right add_quantity decimal" value="' + givenQuantity + '" placeholder="' + quantity + '"onkeyup="checkStockOverQty(this.value)" name="quantity[]" value="">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[]" value="' + givenQuantity + '">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input type="text" id="rate_' + productID + '" class="form-control add_rate text-right decimal" name="rate[]" value="' + '' + '" placeholder="0.00">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input readonly type="text" class="add_price text-right form-control" id="tprice_' + productID + '" name="price[]" value="' + price + '">' +
                                    '</td>' +
                                    '<td>' +
                                    '<a del_id="' + productID + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a>' +
                                    '</td>' +
                                    '</tr>';
                                $("#show_item tbody").append(tab);

                            } else {

                                tab = '<tr class="new_item' + productID + '">' +
                                    '<td style="padding-left:15px;">' +
                                    '['+ productCatName + ' ] - '+ productName + '&nbsp;[&nbsp;' + productBrandName + '&nbsp;]&nbsp;' +
                                    '<input id="productID_' + productID + '" name="productID[]" value="' + productID + '" type="hidden">' +
                                    '<input type="hidden" name="category_id[]" value="' + productCatID + '">' +
                                    '</td>' +
                                    '<input type="hidden"  name="product_id[]" value="' + productID + '">' +
                                    '<td align="right">' +
                                    '<input type="text" id="qty_' + productID + '" class="form-control text-right add_quantity decimal" value="' + givenQuantity + '" placeholder="' + quantity + '" onkeyup="checkStockOverQty(this.value)" name="quantity[]" value="">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[]" value="' + returnQuantity + '" readonly>' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input type="text" id="rate_' + productID + '" class="form-control add_rate text-right decimal" name="rate[]" value="' + '' + '" placeholder="0.00">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input readonly type="text" class="add_price text-right form-control" id="tprice_' + productID + '" name="price[]" value="' + price + '">' +
                                    '</td>' +
                                    '<td>' +
                                    '<a del_id="' + productID + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a>' +
                                    '</td>' +
                                    '</tr>';
                                $("#show_item tbody").append(tab);

                            }
                            $("#subBtn").attr('disabled', false);

                            findTotalCal();
                            setTimeout(function () {
                                calculateCustomerDue();
                            }, 100);
                        } else {
                            swal("Quantity Can't be empty!", "Validation Error!", "error");
                            return false;
                        }
                    }
                });
            }
        });
    }


    $(document).on("keyup", ".add_quantityPos", function () {
        var id_arr = $(this).attr('id');
        productTotal(id_arr)

    });







    $("#ReciveproductNameAutocomplete").autocomplete({

        source: function (request, response) {
            $.getJSON(baseUrl + "SalesController/get_product_list_by_dist_id", {term: request.term,receiveStatus:1},
            response);
        },
        minLength: 1,
        delay: 100,
        response: function (event, ui) {
            if (ui.content) {
                if (ui.content.length == 1) {
                    addReciveProduct(ui.content[0].id, ui.content[0].productName, ui.content[0].category_id, ui.content[0].productCatName, ui.content[0].brand_id, ui.content[0].brandName, ui.content[0].unit_id, ui.content[0].unitTtile)
                    var dataIns = $(this).val();
                    $(this).val('');
                    $(this).focus();
                    $(this).autocomplete('close');
                    return false;
                } else if (ui.content.length == 0) {
                    $(this).val('');
                    $(this).focus();
                    $(this).autocomplete('close');
                    return false;
                } else {
                    //alert("This Character and code have no item!!");
                }
            }
        },
        select: function (event, ui) {
            addReciveProduct(ui.item.id, ui.item.productName, ui.item.category_id, ui.item.productCatName, ui.item.brand_id, ui.item.brandName, ui.item.unit_id, ui.item.unitTtile)
            $(this).val('');
            return false;
        }

    });




    function addReciveProduct(productID, productName, productCatID, productCatName, productBrandID, productBrandName, productUnit, unitName) {// quantity,returnQuantity, rate, price
        var quantity;

        var productCatID = productCatID;
        var productCatName = productCatName;

        var productID = productID;
        var productName = productName;

        var productUnit = productUnit;
        var productBrandName = productBrandName;
        var unitName = unitName;

        var rate = 0;
        var price = 0;
        var returnQuantity = $('.returnQuantity').val();



        var previousProductID = parseFloat($('#ReciveproductID_' + productID).val());
        $.ajax({
            type: "POST",
            url: baseUrl + "FinaneController/getProductPriceForSale",
            data: 'product_id=' + productID,
            success: function (data) {

                if (data != '') {
                    //$('#rate_' + productID).val(data);
                }
            }, complete: function () {

                // if (quantity > 0) {
                var givenQuantity = 1;
                var previousProductQuantity = parseInt($('#Reciveqty_' + productID).val());

                if (previousProductID == productID) {
                    givenQuantity = givenQuantity + previousProductQuantity;
                    $('#Reciveqty_' + productID).val(givenQuantity);
                    //productTotal('_' + productID)
                    return true;
                }
<?php
$opt = "";
foreach ($unitList as $eachUnit) {
    $opt .= '<option value="' . $eachUnit->unit_id . '" >' . $eachUnit->unitTtile . '</option>';
}
?>


                var tab = "";
                tab = '<tr class="new_item2' + productCatID + productID + '"><input type="hidden" name="category_id2[]" value="' + productCatID + '">' +
                    '<td style="padding-left:15px;">' +
                    productName + ' [ ' + productBrandName + ' ] '+
                    '<input id="ReciveproductID_' + productID + '" name="productID[]" value="' + productID + '" type="hidden">' +
                    '<input type="hidden"  name="product_id2[]" value="' + productID + '">' +
                    '</td>' +

                    '<td align="right">' +
                    '<input type="text" id="Reciveqty_' + productID + '" class="form-control text-right add_quantityPos2 decimal" value="' + givenQuantity + '" placeholder="' + quantity + '"  name="quantity2[]" value="">' +
                    '</td>' +
                    '<td>' +
                    '<a del_id2="' + productCatID + productID + '" class="delete_item2 btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a>' +
                    '</td>' +
                    '</tr>';

                
                $("#show_item2 tbody").append(tab);
                $("#subBtn").attr('disabled', false);

                $('.quantity2').val('');
                $('.rate2').val('');
                $('.price2').val('');
                $('.returnQuantity2').val('');

                $(".quantity2").attr("placeholder", "0");
                $('#productID2').val('').trigger('chosen:updated');
                $('#category_product2').val('').trigger('chosen:updated');
                findTotalCal2();

                //                        } else {
                //                            swal("Quantity Can't be empty!", "Validation Error!", "error");
                //
                //                            return false;
                //                        }
            }
        });





        //        $.ajax({
        //            type: "POST",
        //            url: baseUrl + "FinaneController/getProductStock",
        //            data: 'product_id=' + productID,
        //            success: function (data) {
        //                var mainStock = parseFloat(data);
        //                if (data != '') {
        //                    quantity = mainStock;
        //                }
        //
        //            }, complete: function () {}
        //        });
    }


    $(document).on("keyup", ".add_quantityPos2", function () {

        findTotalQty2()

    });





</script>



<script type="text/javascript" src="<?php echo base_url('assets/sales/salePos.js'); ?>"></script>