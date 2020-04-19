
<style>
    table tr td{
        margin: 0px!important;
        padding: 1px!important;
    }

    table tr td  tfoot .form-control {
        width: 100%;
        height: 34px;
    }
</style>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="<?php echo site_url('DistributorDashboard/2'); ?>">Sales</a>
        <i class="fa fa-angle-right"></i>
        <li class="active">Sales Invoice Add</li>
        <i class="fa fa-angle-right"></i>
        <li class="active"><span style="color:red;"> *</span> <span style="color: red">Mark field must be fill up</span></li>

    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <button type="button" class="btn blue"><a href="<?php echo site_url('salesInvoice'); ?>" style="color:#fff;"><i class="ace-icon fa fa-list" ></i> List</a>

            </button>

        </div>
    </div>

</div>

<div class="row">

    <form id="publicForm" action=""  method="post" class="form-horizontal">
        <div class="col-sm-12 col-md-4" style="margin-top: 10px;">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <span style="color:red;">*</span>Sales Date </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input class="form-control date-picker" name="saleDate" id="saleDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Invoice No</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text"  name="userInvoiceId" value="" class="form-control" placeholder="Invoice No"/>
                        <span class="input-group-addon" style="background-color:#fff">
                            <?php echo $voucherID; ?>
                        </span>
                    </div>
                </div>


            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Loader</label>
                <div class="col-sm-7">
                    <select  name="loader"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Select  Loader">
                        <option></option>
                        <?php foreach ($employeeList as $key => $eachEmp): ?>
                            <option value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->name . '   &nbsp&nbsp[' . $eachEmp->personalMobile . ']'; ?></option>
                            <!--<option value="<?php /* echo $eachEmp->id; */ ?>"><?php /* echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; */ ?></option>-->
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Transportation</label>
                <div class="col-sm-7">
                    <select  name="transportation"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Select Transportation">
                        <option></option>
                        <?php foreach ($vehicleList as $key => $eachVehicle): ?>
                            <option value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


        </div>
        <div class="col-md-4" style="margin-top: 10px;">

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="white-space: nowrap;">Delivery Address</label>
                <div class="col-sm-7">
                    <input class="form-control" placeholder="Delivery Address" name="shippingAddress" />
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="white-space: nowrap;">Delivery Date</label>
                <div class="col-sm-7">
                    <input class="form-control" placeholder="Delivery Date" name="shippingAddress" />
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" style="white-space: nowrap;" for="form-field-1"><span style="color:red;">*</span>Payment Type</label>
                <div class="col-sm-7">
                    <select onchange="showBankinfo(this.value)"  name="paymentType"  class="chosen-select form-control" id="paymentType" data-placeholder="Select Payment Type">
                        <option></option>
                        <!--<option value="1">Full Cash</option>-->
                        <option selected value="4">Cash</option>
                        <option value="2">Credit</option>
                        <option value="3">Cheque / DD/ PO</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red;"> *</span>Account</label>
                <div class="col-sm-7">
                    <select style="width:100%!important;"  name="accountCrPartial" class="chosen-select   checkAccountBalance" id="partialHead" data-placeholder="Select Account Head">
                        <option value=""></option>
                        <?php
                        foreach ($accountHeadList as $key => $head) {
                            if ($key != 51 || $key != 112) {
                                ?>
                                <optgroup label="<?php echo $head['parentName']; ?>">
                                    <?php
                                    foreach ($head['Accountledger'] as $eachLedger) :
                                        ?>
                                        <option <?php
                                        if ($eachLedger->chartId == '54') {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                        <?php endforeach; ?>
                                </optgroup>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div> 

        </div>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span style="color:red; margin-left:-12px">*</span>
                        <select  id="customerid" name="customer_id"  class="chosen-select form-control" onchange="getCustomerCurrentBalace(this.value)" id="form-field-select-3" data-placeholder="Select Customer Name">
                            <option></option>
                            <?php foreach ($customerList as $key => $each_info): ?>
                                <option value="<?php echo $each_info->customer_id; ?>"><?php echo $each_info->customerName . '&nbsp&nbsp[ ' . $each_info->typeTitle . ' ] '; ?></option>
                                <!--   <!----><option value="<?php /* echo $each_info->customer_id; */ ?>"><?php /* echo $each_info->typeTitle . ' - ' . $each_info->customerID . ' [ ' . $each_info->customerName . ' ] '; */ ?></option>-->
                            <?php endforeach; ?>

                        </select>
                        <span class="input-group-btn" id="newCustomerHide">
                            <a  data-toggle="modal" data-target="#myModal" class="saleAddPermission btn blue btn-xs btn-success" style="height:34px;"><i class="fa fa-plus" style="margin-top:8px;"></i></a>
                        </span>
                    </div>
                </div>

            </div>
            <table class="table table-bordered table-striped table-condensed flip-content">
                <thead>
                    <tr>
                        <th class="text-center">Cylinder</th>
                        <th class="text-center">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">OMERA</td>
                        <td class="text-center">100</td>
                    </tr>
                    <tr>
                        <td class="text-center">Navana</td>
                        <td class="text-center">200</td>

                    </tr>
                    <tr>
                        <td class="text-center">Laugfs</td>
                        <td class="text-center">150</td>

                    </tr>
                    <tr>
                        <td class="text-center">Laugfs</td>
                        <td class="text-center">150</td>

                    </tr>
                    <tr>
                        <td class="text-center">Laugfs</td>
                        <td class="text-center">150</td>

                    </tr>


                </tbody>
            </table>  

        </div>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="form-group">

                <div class="col-sm-12">
                    <select  name="reference"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Select Reference Name">
                        <option></option>
                        <?php foreach ($referenceList as $key => $each_ref): ?>
                            <option value="<?php echo $each_ref->reference_id; ?>"><?php echo $each_ref->referenceName; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" id="form-field-1" name="reference"  value="" class="form-control" placeholder="Reference" />
                    -->
                </div>
            </div>
            <table class="table table-bordered table-striped table-condensed flip-content">
                <thead>
                    <tr>
                        <th class="text-center">Cylinder</th>
                        <th class="text-center">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">OMERA</td>
                        <td class="text-center">100</td>
                    </tr>
                    <tr>
                        <td class="text-center">Navana</td>
                        <td class="text-center">200</td>

                    </tr>
                    <tr>
                        <td class="text-center">Laugfs</td>
                        <td class="text-center">150</td>

                    </tr>
                    <tr>
                        <td class="text-center">Laugfs</td>
                        <td class="text-center">150</td>

                    </tr>
                    <tr>
                        <td class="text-center">Laugfs</td>
                        <td class="text-center">150</td>

                    </tr>


                </tbody>
            </table>  

        </div>
        <div class="clearfix"></div>

        <div class="col-md-12" id="showBankInfo" style="display:none; margin-left: 22px;" >

            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-3">
                    <input type="text" value="" name="bankName" id="bankName" class="form-control" placeholder="Bank Name"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-3">
                    <input type="text" value="" name="branchName" id="branchName" class="form-control" placeholder="Branch Name"/>
                </div> 
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-3">
                    <input type="text" value="" class="form-control" id="checkNo" name="checkNo" placeholder="Check NO"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-3">
                    <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                </div>
            </div>
        </div> 
        <div class="col-md-10" style="margin-top: 10px;">
            <div class="table-header">
                Sales Product
            </div>

            <table class="table table-bordered table-hover tableAddItem" id="show_item">
                <thead>
                    <tr>
                        <th nowrap style="width:12%;border-radius:10px;"><strong>Category <span style="color:red;"> *</span></strong></th>
                        <th nowrap style="width:172px"  id="product_th"><strong>Product <span style="color:red;"> *</span></strong></th>
                        <th style="white-space:nowrap; width:100px; vertical-align:top;"><strong>Quantity <span style="color:red;"> *</span></strong></th>
                        <th nowrap><strong>Receivable(Qty)</strong></th>
                        <th nowrap><strong>Unit Price(BDT)  <span style="color:red;"> *</span></strong></th>
                        <th nowrap ><strong>Total Price(BDT) <span style="color:red;"> *</span></strong></th>
                        <th nowrap style="width:17%;border-radius:10px;" ><strong>Returned Cylinder <span style="color:red;"> </th>
                                    <th nowrap style="width:10%;border-radius:10px;"><strong>Returned Qty <span style="color:red;"></th>
                                                <th><strong>Action</strong></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="chosen-select form-control">
                                                                <option>Select category</option>
                                                                <option>Empty Cylinder</option>
                                                                <option>Refill</option>
                                                                <option>Package</option>

                                                            </select>


                                                        </td>
                                                        <td id="product_td">
                                                            <select  id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product">
                                                                <option value=""></option>
                                                                <?php foreach ($productList as $key => $eachProduct):
                                                                    ?>
                                                                    <optgroup label="<?php echo $eachProduct['categoryName']; ?>">
                                                                        <?php
                                                                        foreach ($eachProduct['productInfo'] as $eachInfo) :

                                                                            $productPreFix = substr($eachInfo->productName, 0, 5);
                                                                            //  if ($productPreFix != 'Empty'):
                                                                            ?>
                                                                            <option ispackage="0" categoryName="<?php echo $eachProduct['categoryName']; ?>" categoryId="<?php echo $eachProduct['categoryId']; ?>" productName="<?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?>" value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?></option>
                                                                            <?php
                                                                            //  endif;
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
                                                                        <option ispackage="1" categoryId="<?php echo $eachInfo->category_id; ?>"  product_id="<?php echo $eachInfo->product_id; ?>"  value="<?php echo $eachInfo->package_id; ?>"><?php echo $eachInfo->package_name . " [ " . $eachInfo->package_code . " ] "; ?></option>
                                                                        <?php
                                                                        // endif;
                                                                    endforeach;
                                                                    ?>
                                                                </optgroup>
                                                            </select>
                                                        </td>
                                                        <td><input type="hidden" class="form-control text-right is_same decimal"  value="0"><input type="hidden" value="" id="stockQty"/><input type="text"  onkeyup="checkStockOverQty(this.value)" class="form-control text-right quantity decimal"  placeholder="0"></td>
                                                        <td><input type="hidden" value="" id="returnStockQty"/><input type="text" readonly  class="form-control text-right returnQuantity decimal"   placeholder="0"></td>
                                                        <td><input type="text" class="form-control text-right rate decimal" placeholder="0.00"  ></td>
                                                        <td><input type="text" class="form-control text-right price decimal" placeholder="0.00" readonly="readonly"></td>
                                                        <td>
                                                            <select  id="productID2"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by product name">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($cylinderProduct as $eachProduct):
                                                                    $productPreFix = substr($eachProduct->productName, 0, 5);
                                                                    if ($eachProduct->category_id == 1):
                                                                        ?>
                                                                        <option  categoryName2="<?php echo $eachProduct->productCat; ?>" brand_id="<?php echo $eachProduct->brand_id ?>" productName2="<?php echo $eachProduct->productName . ' ' . $eachProduct->unitTtile . ' [ ' . $eachProduct->brandName . ']'; ?>" value="<?php echo $eachProduct->product_id; ?>">
                                                                            <?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>
                                                                        </option>
                                                                        <?php
                                                                    endif;
                                                                endforeach;
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control text-right returnQuentity decimal" placeholder="0.00" ></td>
                                                        <td><a id="add_item" class="btn blue form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus" style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a></td>
                                                    </tr>

                                                </tbody>
                                                <tfoot></tfoot>
                                                </table>

                                                <table class="table table-bordered table-hover table-success">
                                                    <tr>
                                                        <td>
                                                            <textarea style="border:none;" cols="120"  class="form-control" name="narration" placeholder="Narration......" type="text"></textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div class="clearfix"></div>
                                                <div class="clearfix form-actions" >
                                                    <div class="col-md-6 col-md-offset-5">
                                                        <button onclick="return isconfirm2()" id="subBtn" class="btn blue" type="button">
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Save
                                                        </button>
                                                        &nbsp; &nbsp; &nbsp;
                                                        <!--<button class="btn" onclick="showCylinder()" type="button">
                                                            <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                                                            Receive Cylinder
                                                        </button>-->
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="col-md-2" style="margin-top: 10px;">
                                                    <div class="portlet box blue">
                                                        <div class="portlet-title" style="min-height:21px">
                                                            <div class="caption" style="font-size: 12px;padding:1px 0 1px;">
                                                                Payment Calculation </div>

                                                        </div>

                                                        <div class="portlet-body">
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap;;font-size:12px">Total </label>
                                                                <div class="col-md-6">

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap;;font-size:12px">Discount (-)</label>
                                                                <div class="col-md-6">
                                                                    <input type="text"  onkeyup="calDiscount()" id="disCount" name="discount" value="" style="text-align: right" class="form-control" placeholder="0.00"   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px">Grand Total</label>
                                                                <div class="col-md-6">
                                                                    <input readonly id="grandTotal" type="text"  name="grandtotal" value="" style="text-align: right" class="form-control"  placeholder="0.00"/>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px">VAT (%) ( + )</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="vatAmount" style="text-align: right" name="vat" readonly value="<?php
                                                                    if (!empty($configInfo->vat)): echo $configInfo->vat;
                                                                    endif;
                                                                    ?>" class="form-control totalVatAmount"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px">Loader ( + )</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="loader" onkeyup="calcutateFinal()" style="text-align: right"   name="loaderAmount" value=""  class="form-control"  placeholder="0.00"/>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Transportation( + )</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="transportation" onkeyup="calcutateFinal()" style="text-align: right" name="transportationAmount" value=""  class="form-control"  placeholder="0.00"/>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Net Total</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="netAmount"  name="netTotal" value="" readonly class="form-control" style="text-align: right"  placeholder="0.00"/>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Chaque Amount</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="chaque_amount"  name="chaque_amount" value="" style="text-align: right" class="form-control"  placeholder="0.00"/>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Payment( - )</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="payment" onkeyup="calculatePartialPayment()" style="text-align: right" name="partialPayment" value=""  class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />

                                                                </div>
                                                            </div>
                                                            <div class="form-group" style="display:none;">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Due Date</label>
                                                                <div class="col-md-6">
                                                                    <input class="form-control date-picker" name="creditDueDate" id="dueDate" style="text-align: right" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Due Amount</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="payment" onkeyup="calculatePartialPayment()" style="text-align: right" name="partialPayment" value=""  class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Previous Due</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="previousDue"  readonly  name="" value="" style="text-align: right" class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />

                                                                </div>
                                                            </div>
                                                            <div class="form-group" style="display:none;">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Due Date</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="currentDue"  readonly  name="" value="" style="text-align: right" class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-6 control-label" style="white-space: nowrap; font-size:12px;">Total Due</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" id="totalDue"  readonly name="" value="" style="text-align: right" class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />

                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>

                                                    </div><!-- /.col -->


                                                    <script>


                                                        var cylinderProduct;
                                                        window.cylinderProduct = '<?php echo json_encode($cylinderProduct); ?>';
                                                        var option = "";
                                                        option += "<option value='" + '' + "'>---Select Name---</option>";
                                                        $.each(JSON.parse(cylinderProduct), function (key, value) {
                                                            if (value.category_id == 1) {
                                                                option += "<option categoryName2='" + value.productCat + "' brand_id='" + value.brand_id + "' productName2='" + value.productName + ' [' + value.brandName + ']' + "' value='" + value.product_id + "'  >" + value.productName + ' [' + value.brandName + ' ]' + "</option>";
                                                            }
                                                        });

                                                        function isconfirm2() {

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
                                                            /* if ($("#culinderReceive").css('display') == 'none') {
                                                             cylinder = 0;
                                                             } else {
                                                             //  var cylinderItem=parseFloat($(".total_quantity2").text());
                                                             var rowCount = $('#show_item2 tfoot tr').length;
                                                             cylinder = 1;
                                                             }*/
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
                                                                swal("Add Sales Item!", "Validation Error!", "error");
                                                            } else if (paymentType == 4 && partialHead == '') {
                                                                swal("Select Account Head!", "Validation Error!", "error");
                                                            } else if (paymentType == 4 && thisAllotment == '') {
                                                                swal("Given Cash Amount!", "Validation Error!", "error");
                                                            } else if (cylinder == 1 && rowCount <= 1) {
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
                                                                function (isConfirm) {
                                                                    if (isConfirm) {
                                                                        $("#publicForm").submit();
                                                                    } else {
                                                                        return false;
                                                                    }
                                                                });
                                                            }
                                                        }

                                                        $(document).ready(function () {
                                                            console.log('hi this si nahid');
                                                            var j = 0;
                                                            var slNo = 1;
                                                            $("#add_item").click(function () {
                                                                alert('ok UUUU');
                                                                var productCatID = $('#productID').find('option:selected').attr('categoryId');

                                                                var productCatName = $('#productID').find('option:selected').attr('categoryName');
                                                                var productID = $('#productID').val();
                                                                var productName = $('#productID').find('option:selected').attr('productName');

                                                                var ispackage = $('#productID').find('option:selected').attr('ispackage');
                                                                var package_id2 = $('#productID2').val();
                                                                var productCatName2 = $('#productID2').find('option:selected').attr('categoryName2');
                                                                var productName2 = $('#productID2').find('option:selected').attr('productName2');
                                                                var brand_id = $('#productID').find('option:selected').attr('brand_id');


                                                                var quantity = $('.quantity').val();
                                                                var rate = $('.rate').val();
                                                                var price = $('.price').val();
                                                                var returnQuantity = $('.returnQuantity').val();

                                                                var returnQuentity = $('.returnQuentity').val();

                                                                if (ispackage == 0) {

                                                                    var tab;

                                                                    if (productCatID == 2) {
                                                                        //refill cylinder
                                                                        if ($('.is_same').val() == 0) {

                                                                            slNo++;
                                                                            // for return cylender
                                                                            var returnedCylender = '';
                                                                            if (returnQuentity > 0) {
                                                                                returnedCylender = '<tr>' +
                                                                                        '<td>' +
                                                                                        '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                                                                                        productName2 +
                                                                                        '</td>' +
                                                                                        '<td>' +
                                                                                        '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnQuentity_' + slNo + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +
                                                                                        '</td>' +
                                                                                        '</tr>';
                                                                            }



                                                                            tab = '<tr class="new_item' + j + '">' +
                                                                                    '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                                                                                    '<input type="hidden" name="brand_id[]" value="' + brand_id + '"/>' +
                                                                                    '<input type="hidden" name="is_package_' + slNo + '"  value="0">' +
                                                                                    '<input type="hidden" name="category_id[]"  value="' + productCatID + '">' +
                                                                                    '<td style="padding-left:15px;"> [ ' + productCatName + '] - ' + productName +
                                                                                    '<input type="hidden"  name="product_id_' + slNo + '" value="' + productID + '">' +
                                                                                    '</td>' +
                                                                                    '<td align="right">' +
                                                                                    '<input type="text" id="qty_' + j + '" class="form-control text-right add_quantity decimal" style="height: 33px;" onkeyup="checkStockOverQty(this.value)" name="quantity_' + slNo + '" value="' + quantity + '">' +
                                                                                    '</td>' +
                                                                                    '<td align="right"><input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[' + slNo + ']" value="' + returnQuantity + '">' +
                                                                                    '</td>' +
                                                                                    '<td align="right"><input type="text" id="rate_' + j + '" class="form-control add_rate text-right decimal" name="rate_' + slNo + '" value="' + rate + '">' +
                                                                                    '</td>' +
                                                                                    '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_' + j + '" name="price[]" value="' + price + '">' +
                                                                                    '</td>' +
                                                                                    '<td colspan="2">' +
                                                                                    '<table class="table table-bordered table-hover" style="margin-bottom: 0px;" id="return_product_' + slNo + '">' +
                                                                                    '<tr>' +
                                                                                    '<td>' +
                                                                                    '<select   class="chosen-select form-control returnedProduct_' + slNo + '" data-placeholder="Search by product name">' +
                                                                                    option +
                                                                                    '</select>' +
                                                                                    '</td>' +
                                                                                    '<td style="width: 40%">' +
                                                                                    '<div class="input-group"><input type="text" class="form-control returnedProductQty_' + slNo + '" /><a href="javascript:void(0)" id="' + slNo + '" class="AddreturnedProduct  input-group-addon"><i class="fa fa-plus"></i> </a> </div>' +
                                                                                    '</td>' +
                                                                                    '</tr>' +
                                                                                    returnedCylender +
                                                                                    '</table>' +
                                                                                    '</td>' +
                                                                                    '<td>' +
                                                                                    '<a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 10px;margin-left: 8px;"></i>&nbsp;</a>' +
                                                                                    '</td>' +
                                                                                    '</tr>';

                                                                            $("#show_item tfoot").append(tab);
                                                                        } else {
                                                                            slNo;
                                                                            var tab2 = "<tr>" +
                                                                                    "<td>" +
                                                                                    '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                                                                                    productName2 +
                                                                                    "</td>" +
                                                                                    "<td>" +
                                                                                    '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnQuentity_' + slNo + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +
                                                                                    "</td>" +
                                                                                    "</tr>";

                                                                            $("#return_product_" + slNo).append(tab2);

                                                                        }


                                                                        $('.is_same').val('1')
                                                                    } else {
                                                                        slNo++;
                                                                        tab = '<tr class="new_item' + j + '">' +
                                                                                '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                                                                                '<input type="hidden" name="brand_id[]" value="' + brand_id + '"/>' +
                                                                                '<input type="hidden" name="is_package_' + slNo + '" value="0">' +
                                                                                '<input type="hidden" name="category_id[]" value="' + productCatID + '">' +
                                                                                '<td style="padding-left:15px;"> [ ' + productCatName + '] - ' + productName +
                                                                                '<input type="hidden"  name="product_id_' + slNo + '" value="' + productID + '">' +
                                                                                '</td>' +
                                                                                '<td align="right">' +
                                                                                '<input type="text" id="qty_' + j + '" class="form-control text-right add_quantity decimal" onkeyup="checkStockOverQty(this.value)" name="quantity_' + slNo + '" value="' + quantity + '">' +
                                                                                '</td>' +
                                                                                '<td align="right"><input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[]" value="' + returnQuantity + '">' +
                                                                                '</td>' +
                                                                                '<td align="right"><input type="text" id="rate_' + j + '" class="form-control add_rate text-right decimal" name="rate_' + slNo + '" value="' + rate + '">' +
                                                                                '</td>' +
                                                                                '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_' + j + '" name="price[]" value="' + price + '">' +
                                                                                '</td>' +
                                                                                '<td colspan="2">' +
                                                                                '<table class="table table-bordered table-hover" style="margin-bottom: 0px;" id="return_product_' + slNo + '">' +
                                                                                '<tr>' +
                                                                                '<td>' +
                                                                                '</td>' +
                                                                                '<td>' +
                                                                                '</td>' +
                                                                                '</tr>' +
                                                                                '</table>' +
                                                                                '</td>' +
                                                                                '<td>' +
                                                                                '<a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
                                                                                '</td>' +
                                                                                '</tr>';

                                                                        $("#show_item tfoot").append(tab);





                                                                    }
                                                                } else {
                                                                    var quantity = $('.quantity').val();
                                                                    var returnAble = $('.returnAble').val();
                                                                    var rate = $('.rate').val();
                                                                    var price = $('.price').val();
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        dataType: 'json',
                                                                        url: baseUrl + "lpg/PurchaseController/package_product_list",
                                                                        data: 'package_id=' + productID,
                                                                        success: function (data) {

                                                                            $.each(data, function (key, value) {
                                                                                slNo++;
                                                                                $("#show_item tfoot").append('<tr class="new_item' + j + '"><input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/><input type="hidden" name="is_package_' + slNo + '" value="1"><input type="hidden" name="category_id_' + slNo + '" value="' + value['category_id'] + '">' +
                                                                                        '<td style="padding-left:15px;"> [ ' + value['title'] + '] - ' + value['productName'] + '&nbsp;' + value['unitTtile'] + '&nbsp;[ ' + value['brandName'] + " ]" +
                                                                                        ' <input type="hidden"  name="product_id_' + slNo + '" value="' + value['product_id'] + '"></td>' +
                                                                                        '</td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" id="qty_' + j + '" name="quantity_' + slNo + '" value="' + quantity + '"></td><td align="right"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + j + '"   name="add_returnAble[]" value=""  readonly></td><td align="right"><input type="text" id="rate_' + j + '" class="add_rate form-control decimal text-right" name="rate_' + slNo + '" value="' + rate + '"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tprice_' + j + '" readonly name="price[]" value="' + price + '"></td><td></td><td></td><td><a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');
                                                                                j++;

                                                                            });

                                                                        }
                                                                    });
                                                                    $('.quantity').val('');
                                                                    $('.rate').val('');
                                                                    $('.price').val('');
                                                                    $('.returnAble').val('');
                                                                }

                                                                findTotalCal();
                                                                setTimeout(function () {
                                                                    ///calculateCustomerDue();
                                                                    calcutateFinal();
                                                                }, 100);

                                                            })
                                                            j++;



                                                        });
                                                        $(document).on('click', '.AddreturnedProduct', function () {
                                                            //$(".AddreturnedProduct").LoadingOverlay("show");

                                                            var id = $(this).attr("id");
                                                            var productName2 = $('.returnedProduct_' + id).find('option:selected').attr('productName2');
                                                            var package_id2 = $('.returnedProduct_' + id).val();
                                                            var returnQuentity = $('.returnedProductQty_' + id).val();


                                                            if (returnQuentity == '') {
                                                                swal("Qty can't be empty.!", "Validation Error!", "error");
                                                                return false;
                                                            } else
                                                                var tab2 = "<tr>" +
                                                                        "<td>" +
                                                                        '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_' + id + '[]" value="' + package_id2 + '">' +
                                                                        productName2 +
                                                                        "</td>" +
                                                                        "<td>" +
                                                                        '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnQuentity_' + id + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +
                                                                        "</td>" +
                                                                        "</tr>";
                                                            $("#return_product_" + id).append(tab2);
                                                            $('.returnedProduct_' + id).val('');
                                                            $('.returnedProductQty_' + id).val('');

                                                        })

                                                        function getProductPrice(product_id) {
                                                            $("#stockQty").val('');
                                                            $(".quantity").val('');
                                                            $('.is_same').val('0');
                                                            var productCatID = parseFloat($('#productID').find('option:selected').attr('categoryId'));

                                                            var ispackage = $('#productID').find('option:selected').attr('ispackage');
                                                            if (ispackage == 1) {
                                                                product_id = $('#productID').find('option:selected').attr('product_id');
                                                            }




                                                            if (productCatID == 2) {

                                                                $(".returnQuantity").attr('readonly', false);
                                                            } else {
                                                                $(".returnQuantity").attr('readonly', true);
                                                            }
                                                            $.ajax({
                                                                type: "POST",
                                                                url: baseUrl + "FinaneController/getProductPriceForSale",
                                                                data: 'product_id=' + product_id,
                                                                success: function (data) {
                                                                    $('.rate').val('');
                                                                }
                                                            });
                                                            $.ajax({
                                                                type: "POST",
                                                                url: baseUrl + "FinaneController/getProductStock",
                                                                data: {product_id: product_id, category_id: productCatID, ispackage: ispackage},
                                                                success: function (data) {

                                                                    var mainStock = parseFloat(data);
                                                                    if (isNaN(mainStock)) {
                                                                        mainStock = 0;
                                                                    }


                                                                    if (data != '') {
                                                                        $("#stockQty").val(data);
                                                                        $(".quantity").attr("disabled", false);
                                                                        if (mainStock <= 0) {
                                                                            $(".quantity").attr("disabled", true);
                                                                            $(".quantity").attr("placeholder", "0 ");
                                                                        } else {
                                                                            $(".quantity").attr("disabled", false);
                                                                            $(".quantity").attr("placeholder", "" + mainStock);
                                                                        }
                                                                    } else {
                                                                        $("#stockQty").val('');
                                                                        $(".quantity").attr("disabled", true);
                                                                        $(".quantity").attr("placeholder", "0");
                                                                    }
                                                                }
                                                            });
                                                        }
                                                        $(document).on('click', '.remove_returnable', function () {
                                                            alert('OK');
                                                            $(this).closest("tr").remove();

                                                        });


                                                    </script>

                                                    <div class="modal fade" id="myModal" role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    <h4 class="modal-title">Add New Customer</h4>
                                                                </div>
                                                                <div class="modal-body">


                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <form id="publicForm2" action=""  method="post" class="form-horizontal">

                                                                                <?php
                                                                                $this->db->select("*");
                                                                                $this->db->from("customertype");
                                                                                $this->db->where_in('dist_id', array($this->dist_id, 1));
                                                                                $customerType = $this->db->get()->result();

                                                                                // echo $this->db->last_query();die;
                                                                                //$customerType = $this->Common_model->get_data_list_by_single_column('customertype', 'dist_id', $this->dist_id);
                                                                                ?>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Type</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select  name="customerType"  class=" form-control" id="form-field-select-3"  data-placeholder="Search by Customer Type">
                                                                                            <option>-Select Type-</option>
                                                                                            <?php foreach ($customerType as $key => $eachType): ?>
                                                                                                <option value="<?php echo $eachType->type_id; ?>"><?php echo $eachType->typeTitle; ?></option>
                                                                                            <?php endforeach; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer ID </label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="text" id="customerId" name="customerID" readonly value="<?php echo isset($customerID) ? $customerID : ''; ?>" class="form-control" placeholder="Customer ID" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Name </label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="text" id="customerName" name="customerName" class="form-control" placeholder="Customer Name" required/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="text"  maxlength="11" id="form-field-1 cstPhone" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onblur="checkDuplicatePhone(this.value)" name="customerPhone" placeholder="Customer Phone" class="form-control" />
                                                                                        <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="email" id="form-field-1" name="customerEmail" placeholder="Email" class="form-control" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address</label>
                                                                                    <div class="col-sm-9">
                                                                                        <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                                                                                        <textarea  cols="6" rows="3" placeholder="Type Address.." class="form-control" name="customerAddress"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="clearfix form-actions" >
                                                                                    <div class="col-md-offset-3 col-md-9">

                                                                                        <button type="button" class="btn red btn-outline sbold" data-dismiss="modal">Close</button>
                                                                                        <button onclick="saveNewCustomer()" id="subBtn2" class="btn btn-info" type="button">
                                                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                                                            Save
                                                                                        </button>
                                                                                        &nbsp; &nbsp; &nbsp;
                                                                                        <button class=" btn green btn-outline sbold" type="reset" data-dismiss="modal">
                                                                                            <i class="fa fa-undo bigger-110"></i>
                                                                                            Reset
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-dialog">

                                                                                        <!-- /.modal-content -->
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
                                                    <script type="text/javascript" src="<?php echo base_url('assets/sales/saleInvoice.js'); ?>"></script>
