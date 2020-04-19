


<style>
    table tr td{
        margin: 0px!important;
        padding: 2px!important;
    }

    table tr td  tfoot .form-control {
        width: 100%;
        height: 25px;
    }
    .custome_read_only{
        pointer-events: none;
        cursor:not-allowed;
        background-color:#eeeeee;
    }
</style>
<script src="//code.jquery.com/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jQueryUI/jquery-new-ui.js"></script>


<div class="main-content" >
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Purchases Add</li>
                <li class="active"> <span style="color:red;"> * </span> <span style="color:red">Mark field must be fill up</span></li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="" href="<?php echo site_url('purchases_list'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <table class="mytable table-responsive table table-bordered">
                            <tr>
                                <td  style="padding: 10px!important;">
                                    <div class="col-md-6">
                                        <div class="form-group  ">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> <span style="color:red;"> *</span> Supplier ID </label>
                                            <div class="col-sm-6">
                                                <select  id="supplierid" name="supplierID" onchange="getSupplierClosingBalance(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Supplier ID or Name" required>
                                                    <option></option>
                                                    <?php foreach ($supplierList as $key => $each_info): ?>
                                                        <option value="<?php echo $each_info->sup_id; ?>"><?php echo $each_info->supID . ' [ ' . $each_info->supName . ' ] '; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-2" id="hideNewSup">
                                                <a  data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;New</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Voucher ID</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="form-field-1" name="userInvoiceId"  value="" class="form-control" placeholder="Voucher ID "/>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" id="form-field-1" name="voucherid" readonly value="<?php echo $voucherID; ?>" class="form-control" placeholder="Product Code" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Supplier Voucher Reference</label>
                                            <div class="col-sm-6">
                                                <input type="text" id="form-field-1" name="reference"  value="" class="form-control" placeholder="Reference" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group  ">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red;"> *</span> Purchases Date</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control date-picker" name="purchasesDate" id="purchasesDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" required/>
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
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Loader</label>
                                            <div class="col-sm-6">
                                                <select  name="loader"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Loader">
                                                    <option></option>
                                                    <?php foreach ($employeeList as $key => $eachEmp): ?>
                                                        <option value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Transportation</label>
                                            <div class="col-sm-7">
                                                <select  name="transportation"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Transportation">
                                                    <option></option>
                                                    <?php foreach ($vehicleList as $key => $eachVehicle): ?>
                                                        <option value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group  ">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> <span style="color:red;"> *</span> Payment Type </label>
                                            <div class="col-sm-6">
                                                <select onchange="showBankinfo(this.value)"  name="paymentType"  class="chosen-select form-control" id="paymentType" data-placeholder="Select Payment Type" required>
                                                    <option></option>
                                                    <!--<option value="1" selected >Full Cash</option>-->
                                                    <option value="4">Cash</option>
                                                    <option value="2" selected>Credit</option>
                                                    <option value="3">Cheque/ DD / PO </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="hideAccount"  style="display: none;">
                                        <div class="form-group  ">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <span style="color:red;">*</span> Pay. From( CR ) </label>
                                            <div class="col-sm-7">
                                                <select  name="accountCr" class="chosen-select form-control  checkAccountBalance" id="form-field-select-3" data-placeholder="Search by Account Head"  onchange="check_pretty_cash(this.value)">
                                                    <option value=""></option>
                                                    <?php
                                                    foreach ($accountHeadList as $key => $head) {
                                                        ?>
                                                        <optgroup label="<?php echo $head['parentName']; ?>">
                                                            <?php
                                                            foreach ($head['Accountledger'] as $eachLedger) :
                                                                ?>
                                                                <option   value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
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
                                    <div class="col-md-6" id="showAccount">
                                        <div class="form-group  ">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red;"> *</span>  Due Date</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control date-picker" name="dueDate" id="dueDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="showBankInfo" style="display:none;">
                                        <div class="form-group  ">
                                            <div class="col-sm-10">
                                                <div class="col-sm-3">
                                                    <input type="text" value="" name="bankName" id="bankName" class="form-control" placeholder="Bank Name"/>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="text" value="" name="branchName" id="branchName" class="form-control" placeholder="Branch Name"/>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="text" value="" class="form-control" id="checkNo" name="checkNo" placeholder="Cheque NO"/>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2"></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px!important;">
                                    <div class="col-md-12 "  style="">
                                        <div class="col-md-8">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="table-header">
                                                        Purchases Item
                                                    </div>

                                                    <!-- <button type="button" class="btn btn-primary btn-rounded" onclick="packageOrProduct(1)">Product</button>
                                                     <button type="button" class="btn btn-default btn-rounded" onclick="packageOrProduct(0)">Package</button>!-->
                                                    <div class="autocomplete">
                                                        <div class="input-group">

                                                            <span class=" input-group-addon glyphicon glyphicon-search"></span>
                                                            <input id="productNameAutocomplete" class="form-control "
                                                                   placeholder="Scan/Search Product by Name/Code" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <table class="table table-bordered table-hover" id="show_item">
                                                        <thead>
                                                            <tr>
                                                                                                                           <!--   <th id="package_th" nowrap style="width:25%;border-radius:10px;" align="center"><strong>package <span style="color:red;"> *</span></strong></th> !-->
                                                                <th id="product_th" nowrap style="width:25%;border-radius:10px;" align="center"><strong>Product <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:8%;border-radius:10px;" align="center"><strong>Quantity <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:10%;border-radius:10px;" align="center"><strong>Returnable(Qty)</strong></th>
                                                                <th nowrap style="width:10%;border-radius:10px;" align="center"><strong>Unit Price(BDT)  <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:10%;border-radius:10px;" align="center"><strong>Total Price(BDT) <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:20%;border-radius:10px;" align="center"><strong>Returned Cylinder <span style="color:red;"> </th>
                                                                <th nowrap style="width:10%;border-radius:10px;" align="center"><strong>Returned Qty <span style="color:red;"></th>
                                                                <th align="center"><strong>Action</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <!-- <td id="package_td">
                                                                    <select id="package_id" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product">
                                                                        <option value=""></option>
                                                                        <?php foreach ($packageList as $key => $eachInfo):
                                                                            ?>


                                                                                        <option categoryName="" unit="" categoryId="" productName="<?php echo $eachInfo->package_name . " [ " . $eachInfo->package_code . " ] "; ?>" value="<?php echo $eachInfo->package_id; ?>"><?php echo $eachInfo->package_name . " [ " . $eachInfo->package_code . " ] "; ?></option>


                                                                            <?php
                                                                        endforeach;
                                                                        ?>
                                                                    </select>
                                                                </td>!-->
                                                                <td id="product_td">
                                                                    <select id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product">
                                                                        <option value=""></option>
                                                                        <?php foreach ($productList as $key => $eachProduct):
                                                                            ?>
                                                                            <optgroup label="<?php echo $eachProduct['categoryName']; ?>">
                                                                                <?php
                                                                                foreach ($eachProduct['productInfo'] as $eachInfo) :

                                                                                    $productPreFix = substr($eachInfo->productName, 0, 5);
                                                                                    //if ($productPreFix != 'Empty'):
                                                                                        ?>
                                                                                        <option ispackage="0" brand_id="<?php echo $eachInfo->brand_id?>"categoryName="<?php echo $eachProduct['categoryName']; ?>" unit="<?php echo $eachInfo->unitTtile ?>" categoryId="<?php echo $eachProduct['categoryId']; ?>" productName="<?php echo $eachInfo->productName .' '.$eachInfo->unitTtile. " [ " . $eachInfo->brandName . " ] "; ?>" value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName .' '.$eachInfo->unitTtile. " [ " . $eachInfo->brandName . " ] "; ?></option>
                                                                                        <?php
                                                                                   // endif;
                                                                                endforeach;
                                                                                ?>
                                                                            </optgroup>
                                                                            <?php
                                                                        endforeach;
                                                                        ?>
                                                                        <optgroup label="Package">
                                                                            <?php
                                                                            foreach ($productList['packageList'] as $eachInfo) :


                                                                                ?>
                                                                                <option ispackage="1"   value="<?php echo $eachInfo->package_id; ?>"><?php echo $eachInfo->package_name . " [ " . $eachInfo->package_code . " ] "; ?></option>
                                                                                <?php
                                                                                // endif;
                                                                            endforeach;
                                                                            ?>
                                                                        </optgroup>


                                                                    </select>
                                                                </td>
                                                                <td><input type="hidden" class="form-control text-right is_same decimal"  value="0"><input type="text" class="form-control text-right quantity decimal"   placeholder="0"></td>
                                                                <td><input type="text" class="form-control text-right returnAble decimal"   placeholder="0"></td>
                                                                <td><input type="text" class="form-control text-right rate decimal" placeholder="0.00"  ></td>
                                                                <td><input type="text" class="form-control text-right price decimal" placeholder="0.00" readonly="readonly"></td>
                                                                <td>
                                                                    <select  id="productID2" onchange="getProductPrice2(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by product name">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        foreach ($cylinderProduct as $eachProduct):
                                                                            $productPreFix = substr($eachProduct->productName, 0, 5);
                                                                            if ($eachProduct->category_id == 1):
                                                                                ?>
                                                                                <option  categoryName2="<?php echo $eachProduct->productCat; ?>" brand_id="<?php echo $eachProduct->brand_id?>" productName2="<?php echo $eachProduct->productName .' '. $eachProduct->unitTtile .' [ ' . $eachProduct->brandName . ']'; ?>" value="<?php echo $eachProduct->product_id; ?>">
                                                                                    <?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>
                                                                                </option>
                                                                                <?php
                                                                            endif;
                                                                        endforeach;
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td><input type="text" class="form-control text-right returnQuentity decimal" placeholder="0.00" ></td>
                                                                <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;</a></td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                        </tfoot>
                                                    </table>
                                                    <table class="table table-bordered table-hover table-success">
                                                        <tr>
                                                            <td>
                                                                <textarea style="border:none;" cols="120"  class="form-control" name="narration" placeholder="Narration......" type="text"></textarea>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="table-header">
                                                        Payment Calculation
                                                    </div>
                                                    <table  class="table table-bordered ">
                                                        <tbody>
                                                            <tr>
                                                                <td nowrap align="right"><strong>Total (BDT)</strong></td>
                                                                <td align="right"><input type="text" value="" class="form-control total_price text-right" readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td nowrap  align="right"><strong>Discount ( - )</strong></td>
                                                                <td><input type="text" id="discount" onkeyup="calcutateFinal()"   style="text-align: right" name="discount" value=""  class="form-control"  placeholder="0.00"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td nowrap  align="right"><strong>Loader ( + )</strong></td>
                                                                <td><input type="text" id="loader" onkeyup="calcutateFinal()"   style="text-align: right" name="loaderAmount" value=""  class="form-control"  placeholder="0.00"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td nowrap  align="right"><strong>Transportation ( + )</strong></td>
                                                                <td><input type="text" id="transportation" onkeyup="calcutateFinal()"   style="text-align: right" name="transportationAmount" value=""  class="form-control"  placeholder="0.00"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td nowrap  align="right"><strong>Net Total</strong></td>
                                                                <td><input type="text" id="netAmount"  style="text-align: right" name="netTotal" value="" readonly class="form-control"  placeholder="0.00"/></td>
                                                            </tr>
                                                            <tr class="partialPayment">
                                                                <td nowrap align="right"><strong>Add Account</strong><span style="color:red;">*</span></td>
                                                                <td>
                                                                    <select style="width:100%!important"  name="accountCrPartial" class="chosen-select  checkAccountBalance" id="partialHead2" data-placeholder="Search by Account Head"  onchange="check_pretty_cash(this.value)">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        foreach ($accountHeadList as $key => $head) {
                                                                            //if ($key == 42 || $key == 45 || $key == 55) {
                                                                            ?>
                                                                            <optgroup label="<?php echo $head['parentName']; ?>">
                                                                                <?php
                                                                                foreach ($head['Accountledger'] as $eachLedger) :
                                                                                    ?>
                                                                                    <option <?php if($eachLedger->chartId == '54'){ echo "selected";}?>  value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                                                                <?php endforeach; ?>
                                                                            </optgroup>
                                                                            <?php
                                                                            //}
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr class="partialPayment">
                                                                <td nowrap align="right"><strong>Payment ( - ) <span style="color:red;"> * </span></strong></td>
                                                                <td align="right"><input name="thisAllotment" id="thisAllotment" type="text" onkeyup="calcutateFinal(this.value)" class="form-control text-right payment" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" placeholder="0.00"></td>
                                                            </tr>
                                                            <tr>
                                                                <td nowrap align="right"><strong>Due Amount</strong></td>
                                                                <td align="right"><input type="text" readonly class="form-control text-right" value="" id="currentDue" placeholder="0.00"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td nowrap align="right"><strong>Previous Due</strong></td>
                                                                <td align="right"><input type="text" readonly class="form-control text-right" value="" id="customerPreviousDue" placeholder="0.00"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td nowrap align="right"><strong>Total Due</strong></td>
                                                                <td align="right"><input type="text" readonly class="form-control text-right" value="" id="totalDue" placeholder="0.00"/></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="col-md-12"  style="">

                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="clearfix form-actions" >
                                        <div class="col-md-offset-3 col-md-9">
                                            <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <!--<button class="btn" onclick="showCylinder()" type="button">
                                                <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                                                Returned Cylinder
                                            </button>-->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>

                </div><!-- /.col -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>
    var cylinderProduct;
    window.cylinderProduct='<?php echo json_encode($cylinderProduct); ?>';
    var option = "";
    option += "<option value='" + '' + "'>---Select Name---</option>";
    $.each(JSON.parse( cylinderProduct), function (key, value) {
        if(value.category_id==1){
            option+="<option categoryName2='"+value.productCat+"' brand_id='"+value.brand_id +"' productName2='"+value.productName+' ['+ value.brandName+']'+"' value='"+value.product_id+"'  >"+ value.productName+' ['+ value.brandName  +' ]'+"</option>";
        }
    });



    $(document).ready(function () {



        //$(".chosen-select").chosen();
        //packageOrProduct(1);
        //$('#productID2').prop('disabled', true).trigger("liszt:updated");

    });

  /*  function  packageOrProduct(forHideshow){

        if(forHideshow==1){
            $('#package_th').hide();
            $('#package_td').hide();
            $('#product_th').show();
            $('#product_td').show();
        }else{


            $('#package_th').show();
            $('#package_td').show();
            $('#product_th').hide();
            $('#product_td').hide();
        }
    }*/





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
            }else{
                $(this).val('');
                $(this).focus();
                $(this).autocomplete('close');
                return false;
                swal("Select Supplier Name!", "Validation Error!", "error");
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

                var tab = "";
                tab = '<tr class="new_item2' + productCatID + productID + '"><input type="hidden" name="category_id2[]" value="' + productCatID + '">' +
                    '<td style="padding-left:15px;">' +
                    productName + ' [ ' + productBrandName + ' ] '+
                    '<input id="ReciveproductID_' + productID + '" name="productID[]" value="' + productID + '" type="hidden">' +
                    '<input type="hidden"  name="product_id2[]" value="' + productID + '">' +
                    '</td>' +

                    '<td align="right">' +
                    '<input type="text" id="Reciveqty_' + productID + '" class="form-control text-right add_quantity2 decimal" value="' + givenQuantity + '" placeholder="' + quantity + '"  name="quantity2[]" value="">' +
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
                findTotalQty2();


            }
        });
    }
    $("#productNameAutocomplete").autocomplete({

        source: function (request, response) {
            $.getJSON(baseUrl + "InventoryController/get_product_list_by_dist_id", {term: request.term},
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
                quantity = 2;
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
                                    '['+ productCatName + ' ] - '+ productName +'&nbsp;'+unitName+ '&nbsp;[&nbsp;' + productBrandName + '&nbsp;]&nbsp;' +
                                    '<input id="productID_' + productID + '" name="productID[]" value="' + productID + '" type="hidden">' +
                                    '<input type="hidden" name="category_id[]" value="' + productCatID + '">' +
                                    '</td>' +
                                    '<input type="hidden"  name="product_id[]" value="' + productID + '">' +
                                    '<td align="right">' +
                                    '   <input type="text" id="qty_' + productID + '" class="form-control text-right add_quantity decimal" value="' + givenQuantity + '" placeholder="' + quantity + '"onkeyup="checkStockOverQty(this.value)" name="quantity[]" value="">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="add_returnAble[]" value="' + givenQuantity + '">' +
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
                                    '['+ productCatName + ' ] - '+ productName +'&nbsp;'+unitName+  '&nbsp;[&nbsp;' + productBrandName + '&nbsp;]&nbsp;' +
                                    '<input id="productID_' + productID + '" name="productID[]" value="' + productID + '" type="hidden">' +
                                    '<input type="hidden" name="category_id[]" value="' + productCatID + '">' +
                                    '</td>' +
                                    '<input type="hidden"  name="product_id[]" value="' + productID + '">' +
                                    '<td align="right">' +
                                    '<input type="text" id="qty_' + productID + '" class="form-control text-right add_quantity decimal" value="' + givenQuantity + '" placeholder="' + quantity + '" onkeyup="checkStockOverQty(this.value)" name="quantity[]" value="">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[]" value="" readonly>' +
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
    function isconfirm2(){
        var supplierid=$("#supplierid").val();
        var purchasesDate=$("#purchasesDate").val();
        var paymentType=$("#paymentType").val();
        var paymentType=$("#paymentType").val();
        var dueDate=$("#dueDate").val();
        var partialHead=$("#partialHead2").val();
        var thisAllotment=$("#thisAllotment").val();
        var bankName=$("#bankName").val();
        var branchName=$("#branchName").val();
        var checkNo=$("#checkNo").val();
        var checkDate=$("#checkDate").val();
        var cylinder=0;

        var totalPrice=parseFloat($(".total_price").val());
        if(isNaN(totalPrice)){
            totalPrice=0;
        }
        if(supplierid == ''){
            swal("Select Supplier Name!", "Validation Error!", "error");
        }else if(purchasesDate == ''){
            swal("Select Purchases Date!", "Validation Error!", "error");
        }else if(paymentType == ''){
            swal("Select Payment Type", "Validation Error!", "error");
        }else if(paymentType == 2 && dueDate == ''){
            swal("Select Due Date!", "Validation Error!", "error");
        }else if(paymentType == 3 && bankName == ''){
            swal("Type Bank Name!", "Validation Error!", "error");
        }else if(paymentType == 3 && branchName == ''){
            swal("Type Branch Name!", "Validation Error!", "error");
        }else if(paymentType == 3 && checkNo == ''){
            swal("Type Check No!", "Validation Error!", "error");
        }else if(paymentType == 3 && checkDate == ''){
            swal("Select Check Date!", "Validation Error!", "error");
        }else if(totalPrice == '' || totalPrice < 0){
            swal("Add Purcahses Item!", "Validation Error!", "error");
        }else if(paymentType == 4 && partialHead == ''){
            swal("Select Account Head!", "Validation Error!", "error");
        }else if(paymentType == 4 && thisAllotment == ''){
            swal("Given Cash Amount!", "Validation Error!", "error");
        }else if(cylinder == 1 && cylinderItem <= 0){


            swal("Add Return Cylinder Item or Close Return Cylinder!", "Validation Error!", "error");
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
<script type="text/javascript" src="<?php echo base_url('assets/purchases/purchasesAdd.js'); ?>"></script>
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
                                    <input type="text" id="form-field-1" name="supplierId" readonly value="<?php echo isset($supplierID) ? $supplierID : ''; ?>" class="form-control supplierId" placeholder="SupplierID" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier Name </label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="supName" class="form-control required supName" placeholder="Name" required/>
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
