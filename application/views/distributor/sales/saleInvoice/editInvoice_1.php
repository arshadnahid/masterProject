<style>
    table tr td{
        margin: 0px!important;
        padding: 2px!important;
    }

    table tr td  tfoot .form-control {
        width: 100%;
        height: 25px;
    }
</style>

            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">

                        <table class="mytable table-responsive table table-bordered">
                            <tr>
                                <td style="padding: 10px!important;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Customer ID <span style="color:red;"> *</span></label>
                                            <div class="col-sm-6">
                                                <select title="Select product category" onchange="getCustomerCurrentBalace(this.value)"  id="customerid" name="customer_id"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Customer ID OR Name">
                                                    <option></option>
                                                    <?php foreach ($customerList as $key => $each_info): ?>
                                                        <option <?php
                                                    if ($editInvoice->customer_id == $each_info->customer_id) {
                                                        echo "selected";
                                                    }
                                                        ?> value="<?php echo $each_info->customer_id; ?>"><?php echo $each_info->typeTitle . ' - ' . $each_info->customerID . ' [ ' . $each_info->customerName . ' ] '; ?></option>
                                                        <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!--                                            <div class="col-sm-2" id="newCustomerHide">
                                                                                            <a  data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;New</a>
                                                                                        </div>-->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Invoice No</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="form-field-1" name="userInvoiceId" value="<?php echo $editInvoice->mainInvoiceId; ?>" class="form-control" placeholder="Invoice No"/>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" id="form-field-1" name="voucherid" readonly value="<?php echo $editInvoice->invoice_no; ?>" class="form-control" placeholder="Voucher ID" />
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
                                                        <option <?php
                                                    if ($editInvoice->reference == $each_ref->reference_id) {
                                                        echo "selected";
                                                    }
                                                        ?>  value="<?php echo $each_ref->reference_id; ?>"><?php echo $each_ref->referenceName; ?></option>
                                                        <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sales Date  <span style="color:red;"> *</span></label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control date-picker" name="saleDate" id="id-date-picker-1" type="text" value="<?php echo date('d-m-Y', strtotime($editInvoice->invoice_date)); ?>" data-date-format="dd-mm-yyyy" />
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
                                                        <option <?php if($editInvoice->loader_emp_id == $eachEmp->id){ echo "selected";}?> value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; ?></option>
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
                                                        <option <?php if($editInvoice->tran_vehicle_id == $eachVehicle->id){ echo "selected";}?> value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Payment Type  <span style="color:red;"> *</span></label>
                                            <div class="col-sm-6">
                                                <?php
                                                $pType = array('1' => 'Full Cash', '2' => 'Credit', '3' => 'Cheque', '4' => 'Partial');
                                                ?>
                                                <select onchange="showBankinfo(this.value)"  name="paymentType"  class="chosen-select form-control" id="paymentType" data-placeholder="Select Payment Type">
                                                    <option></option>
                                                    <!--                                                    <option <?php
                                                if ($editInvoice->payment_type == 1) {
                                                    echo "selected";
                                                }
                                                ?> value="1"  >Full Cash</option>-->
                                                    <option <?php
                                                    if ($editInvoice->payment_type == 4) {
                                                        echo "selected";
                                                    }
                                                ?>  value="4">Cash</option>
                                                    <option <?php
                                                        if ($editInvoice->payment_type == 2) {
                                                            echo "selected";
                                                        }
                                                ?>  value="2">Credit</option>
                                                    <option <?php
                                                        if ($editInvoice->payment_type == 3) {
                                                            echo "selected";
                                                        }
                                                ?>  value="3">Cheque / DD/ PO</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Shipping Address</label>
                                            <div class="col-sm-7">
                                                <input type="text" placeholder="Shipping Address" name="shippingAddress" class="form-control" value="<?php echo $editInvoice->shipAddress; ?>" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div id="showBankInfo" style="display:none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-2">
                                                    <input type="text" value="<?php echo isset($bank_check_details->bankName) ? $bank_check_details->bankName : '' ?>" name="bankName" id="bankName" class="form-control" placeholder="Bank Name"/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" value="<?php echo isset($bank_check_details->branchName) ? $bank_check_details->branchName : '' ?>" name="branchName" id="branchName" class="form-control" placeholder="Branch Name"/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" value="<?php echo isset($bank_check_details->checkNo) ? $bank_check_details->checkNo : '' ?>" class="form-control" id="checkNo" name="checkNo" placeholder="Check NO"/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input class="form-control date-picker"  name="checkDate"  id="checkDate" type="text" value="<?php echo isset($bank_check_details->checkDate) ? $bank_check_details->checkDate : '' ?>" data-date-format="dd-mm-yyyy" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <input type="hidden" id="invoiceAmount" value="<?php echo $creditAmount->credit; ?>">
                            <input type="hidden" id="editCustomer" value="<?php echo $editInvoice->customer_id; ?>">
                            <input type="hidden" id="paymentType" value="<?php echo $editInvoice->payType; ?>">
                            <input type="hidden" id="sales_invoice_id" name="sales_invoice_id" value="<?php echo $editInvoice->sales_invoice_id; ?>">
                            <tr>
                                <td style="padding: 10px!important;">
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="table-header">
                                                        Sales Item
                                                    </div>
                                                    <table class="table table-bordered table-hover" id="show_item">
                                                        <thead>
                                                            <tr>
                                                                <th nowrap style="width:27%" align="center"><strong>Product <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:10%" align="center"><strong>Quantity <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:7%" align="center"><strong>Receivable Cylinder(Qty)</strong></th>
                                                                <th nowrap style="width:10%" align="center"><strong>Unit Price(BDT)  <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:13%" align="center"><strong>Total Price(BDT) <span style="color:red;"> *</span></strong></th>
                                                                <th nowrap style="width:20%;border-radius:10px;" align="center"><strong>Returned Cylinder <span style="color:red;"> </th>
                                                                <th nowrap style="width:10%;border-radius:10px;" align="center"><strong>Returned Qty <span style="color:red;"></th>
                                                                <th style="width:8%" align="center"><strong>Action</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <select id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product">
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
                                                                                <option ispackage="1" categoryId="<?php echo $eachInfo->category_id; ?>"  product_id="<?php echo $eachInfo->product_id;?>"  value="<?php echo $eachInfo->package_id; ?>"><?php echo $eachInfo->package_name . " [ " . $eachInfo->package_code . " ] "; ?></option>
                                                                                <?php
                                                                                // endif;
                                                                            endforeach;
                                                                            ?>
                                                                        </optgroup>
                                                                    </select>
                                                                </td>
                                                                <td><input type="hidden" value="" id="stockQty"/><input type="text"  onkeyup="checkStockOverQty(this.value)" class="form-control text-right quantity decimal" placeholder="0"></td>
                                                                <td><input type="hidden" value="" id="returnStockQty"/><input type="text"  onkeyup="checkReturnStockOverQty(this.value)" class="form-control text-right returnQuantity decimal"   placeholder="0"></td>
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
                                                                <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>

                                                            <?php
                                                            $totalEditPrice = 0;
                                                            foreach ($editStock[$editInvoice->sales_invoice_id] as $key => $eachStock):
                                                                //echo '<pre>';
                                                                //print_r($eachStock);
                                                              //  if ($eachStock->type == 'Out') {
                                                                  //  $returnQtyedit = $this->Sales_Model->getReturnAbleCylinder2($this->dist_id, $eachStock->generals_id, $eachStock->product_id,$eachStock->quantity);
                                                                    // echo $this->db->last_query();
                                                                    ?>
                                                                    <tr class="new_item<?php echo $key + 100; ?>">
                                                                        <td style="padding-left:15px;">
                                                                            <input type="hidden" name="category_id[]" value="<?php echo $eachStock['category_id']; ?>"/>
                                                                            <input type="hidden" name="sales_details_id[]" value="<?php echo $eachStock['sales_details_id']; ?>"/>
                                                                            <input type="hidden" name="is_package_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $eachStock['is_package']; ?>"/>
                                                                            <?php

                                                                            echo ' [ ' . $eachStock['title'] . ' ] ' . $eachStock['productName'] . ' [ ' . $eachStock['brandName'] . ' ] ';
                                                                            ?>
                                                                            <input type="hidden" name="product_id_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $eachStock['product_id']; ?>">
                                                                        </td>
                                                                        <td align="right">
                                                                            <input type="text" id="qty_<?php echo $key + 100; ?>" autocomplete="off" class="add_quantity text-right form-control decimal" name="quantity_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $eachStock['quantity']; ?>">
                                                                        </td>
                                                                        <?php if ($eachStock['category_id'] == 2): ?>
                                                                            <td align="right">
                                                                                <input type="text" autocomplete="off" class="add_ReturnQuantity text-right form-control decimal"  name="returnAbleQuantity_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $returnQtyedit->quantity; ?>">
                                                                            </td>
                                                                        <?php else: ?>
                                                                            <td align="right">
                                                                                <input type="text" autocomplete="off" class="add_ReturnQuantity text-right form-control decimal "  readonly="readonly" name="returnAbleQuantity_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $returnQtyedit->quantity; ?>">
                                                                            </td>
                                                                        <?php endif; ?>
                                                                        <td align="right">
                                                                            <input type="text" id="rate_<?php echo $key + 100; ?>" autocomplete="off" class="add_rate  text-right form-control decimal" name="unit_price_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $eachStock['unit_price']; ?>">
                                                                        </td>
                                                                        <td align="right">
                                                                            <?php
                                                                            $totalEditPrice += ($eachStock['quantity']*$eachStock['unit_price']);
                                                                            ?>
                                                                            <input type="text" autocomplete="off" class="add_price form-control text-right" id="tprice_<?php echo $key + 100; ?>" readonly name="price_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo ($eachStock['quantity']*$eachStock['unit_price']); ?>">
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <?php if($eachStock['category_id']==2){?>
                                                                            <table class="table table-border" id="return_product_edit_<?php echo $eachStock['sales_details_id']; ?>">
                                                                                <tr>
                                                                                    <td>
                                                                                        <select    class="chosen-select form-control   Add returnedProductEdit_<?php echo $eachStock['sales_details_id']?> "  data-placeholder="Search by product name">
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
                                                                                    <td>
                                                                                        <input type="text" class="form-control returnedProductQtyEdit_<?php echo $eachStock['sales_details_id']; ?>" /><a href="javascript:void(0)" id="<?php echo $eachStock['sales_details_id']; ?>" class="AddreturnedProductEdit"><i class="fa fa-plus"></i> </a>
                                                                                    </td>


                                                                                </tr>
                                                                                <?php
                                                                                foreach ($eachStock['return'] as $key1 =>$value1){
                                                                                    foreach ($value1 as $key2 =>$value2){
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <input type="hidden" class="text-right form-control" id="" readonly name="returnproductEdit_<?php echo $eachStock['sales_details_id']?>[]" value="<?php echo $value2['sales_return_id']?>">
                                                                                                <?php echo $value2['return_product_cat'].' '. $value2['return_product_name'] .' '. $value2['return_product_unit'] .'[ '. $value2['return_product_brand'] .' ]'?></td>
                                                                                            <td><input type="text" class="text-right form-control" id="" readonly name="returnQuentityEdit_<?php echo $eachStock['sales_details_id']?>[]" value="<?php echo $value2['returnable_quantity']?>"></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                            </table>
                                                                            <?php }?>
                                                                        </td>
                                                                        <td><a del_id="<?php echo $key + 100; ?>" autocomplete="off" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>
                                                                    </tr>
                                                                    <?php
                                                               // }
                                                            endforeach;
                                                            ?>
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
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td nowrap align="right"><strong>Total(BDT)</strong></td>

                                                            <td align="right"><strong class="total_price"><?php echo $totalEditPrice . '.00'; ?></strong></td>
                                                        </tr>

                                                        <tr>
                                                            <td  nowrap align="right"><strong>Discount ( - ) </strong></td>
                                                            <td>
                                                                <input type="text" autocomplete="off"  onkeyup="calDiscount()" id="disCount" style="text-align: right" name="discount" value="<?php
                                                            if (!empty($editInvoice->discount_amount) && $editInvoice->discount_amount >= 1): echo $editInvoice->discount_amount;
                                                            endif;
                                                            ?>" class="form-control decimal" placeholder="0.00"    />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Grand Total</strong></td>
                                                            <td>
                                                                <input readonly id="grandTotal" type="text" style="text-align: right" name="grandtotal" value="<?php echo $totalEditPrice - $editInvoice->discount_amount; ?>" class="form-control"  placeholder="0.00"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap align="right"><strong>VAT(%) ( + )</strong></td>
                                                            <td><input type="text" id="vatAmount"  style="text-align: right" name="vat" readonly value="<?php
                                                                       if (!empty($configInfo->VAT)): echo $configInfo->VAT;
                                                                       endif;
                                                            ?>" class="form-control totalVatAmount"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Loader ( + )</strong></td>
                                                            <td>
                                                                <input type="text" id="loader" class="form-control" autocomplete="off" onkeyup="calcutateFinal()"   style="text-align: right" name="loaderAmount" value="<?php echo $editInvoice->loader_charge>0?$editInvoice->loader_charge:'';?>"    placeholder="0.00"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Transportation ( + )</strong></td>
                                                            <td>
                                                                <input type="text" name="transportationAmount"   class="form-control" id="transportation" autocomplete="off" onkeyup="calcutateFinal()" value="<?php echo $editInvoice->transport_charge>0?$editInvoice->transport_charge:'';?>"   style="text-align: right"   placeholder="0.00"/>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        //vat deduction
                                                        $totalAmount = $totalEditPrice - $editInvoice->discount_amount;
                                                        if (!empty($configInfo->VAT)):
                                                            $newAmount = ($configInfo->VAT / 100) * $totalAmount;
                                                        else:
                                                            $newAmount = $totalEditPrice - $editInvoice->discount_amount;
                                                        endif;
                                                        ?>
                                                        <tr>
                                                            <td  nowrap  align="right"><strong>Net Total</strong></td>
                                                            <td>
                                                                <input type="text" id="netAmount"  style="text-align: right" name="netTotal" value="<?php echo $newAmount; ?>" readonly class="form-control"  placeholder="0.00"/>
                                                            </td>

                                                        </tr>
                                                        <tr class="chaque_amount_class" style="display:none">
                                                            <td nowrap  align="right"><strong>Chaque Amount</strong></td>
                                                            <td><input type="text" id="chaque_amount"  style="text-align: right" name="chaque_amount" value="<?php echo isset($bank_check_details->totalPayment) ? $bank_check_details->totalPayment : '' ?>"  class="form-control"  placeholder="0.00"/></td>
                                                        </tr>
                                                        <tr class="creditDate" style="display:none;">
                                                            <td  nowrap align="right"><strong>Due Date</strong></td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input class="form-control date-picker" name="creditDueDate" id="id-date-picker-1" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="partisals">
                                                            <td  nowrap  align="right"><strong>Add Account <span style="color:red;"> * </span></strong></td>
                                                            <td width="100%" colspan="2">
                                                                <select   name="accountCrPartial" class="chosen-select   checkAccountBalance" id="partialHead" data-placeholder="Search by Account Head"  onchange="check_pretty_cash(this.value)">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    foreach ($accountHeadList as $key => $head) {
                                                                        if ($key == 42 || $key == 45 || $key == 55) {
                                                                            ?>
                                                                            <optgroup label="<?php echo $head['parentName']; ?>">
                                                                                <?php
                                                                                foreach ($head['Accountledger'] as $eachLedger) :
                                                                                    ?>
                                                                                    <option <?php
                                                                        if ($accountId == $eachLedger->chartId) {
                                                                            echo "selected";
                                                                        } elseif ($eachLedger->chartId == '54') {
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
                                                            </td>
                                                        </tr>
                                                        <tr  class="partisals">
                                                            <td  nowrap align="right"><strong> Payment ( - )<span style="color:red;"> * </span></strong></td>
                                                            <td><input type="text" id="payment" onkeyup="calculatePartialPayment()" style="text-align: right" name="partialPayment" value="<?php echo $creditAmount->credit; ?>"  class="form-control"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>
                                                        <input type="hidden" id="duePayment"  style="text-align: right" name="duePayment" value="<?php echo $newAmount - $creditAmount->credit; ?>" readonly  class="form-control"  placeholder="0.00"/>
                                                        </tr>

                                                        <tr>
                                                            <td nowrap align="right"><strong>Due Amount</strong></td>
                                                            <td><input type="text" id="currentDue"  readonly style="text-align: right" name="" value=""  class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>

                                                        </tr>
                                                        <tr>
                                                            <td  nowrap align="right"><strong>Previous Due</strong></td>
                                                            <td><input type="text" id="previousDue"  readonly style="text-align: right" name="" value=""  class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>

                                                        </tr>
                                                        <tr>
                                                            <td  nowrap align="right"><strong>Total Due</strong></td>
                                                            <td><input type="text" id="totalDue"  readonly style="text-align: right" name="" value=""  class="form-control" autocomplete="off"  placeholder="0.00"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" /></td>
                                                        </tr>

                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px!important;">
                                    <div class="col-md-12">
                                        <?php if (!empty($cylinderReceive)): ?>
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                <?php endif; ?>
                                                <div id="culinderReceive">
                                                    <div class="table-header">
                                                        Received Cylinder Item
                                                    </div>
                                                    <table class="table table-bordered table-hover" id="show_item2">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:18%" align="center"><strong>Product <span style="color:red;"> *</span></strong></th>
                                                                <th style="width:17%" align="center"><strong><span style="color:red;"> *</span>Received Cylinder(Qty)</strong></th>
                                                                <th style="width:15%" align="center"><strong>Action</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td>
                                                                    <select  id="productID2" onchange="getProductPrice2(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search By Product Name">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        foreach ($cylinderProduct as $eachProduct):

                                                                            $productPreFix = substr($eachProduct->productName, 0, 5);
                                                                            if ($productPreFix == 'Empty'):
                                                                                ?>
                                                                                <option productName="<?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>" value="<?php echo $eachProduct->product_id; ?>">
                                                                                    <?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>
                                                                                </option>
                                                                                <?php
                                                                            endif;
                                                                        endforeach;
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td><input type="hidden" value="" id="stockQty2"/><input type="text"  onkeyup="checkStockOverQty2(this.value)" class="form-control text-right quantity2" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>
                                                                <!--<td><input type="hidden" value="" id="returnStockQty"/><input type="text"  onkeyup="checkReturnStockOverQty(this.value)" class="form-control text-right returnQuantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>-->
                                                                <td><a id="add_item2" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                                            </tr>

                                                            <?php
                                                            if (!empty($cylinderReceive)):
                                                                $totalCylinder = 0;
                                                                foreach ($cylinderReceive as $key1 => $eachItem):
                                                                    $totalCylinder += $eachItem->quantity;
                                                                    ?>

                                                                    <tr class="new_item2<?php echo $key1 + 77; ?>">
                                                                <input type="hidden" name="category_id2[]" value="<?php echo $eachItem->category_id; ?>">
                                                                <td style="padding-left:15px;"><?php echo $eachItem->productName; ?> [ <?php echo $eachItem->brandName; ?> ] <input type="hidden" name="product_id2[]" value="<?php echo $eachItem->product_id; ?>"></td>
                                                                <td align="right"><?php echo $eachItem->quantity; ?><input type="hidden" class="add_quantity2" name="quantity2[]" value="<?php echo $eachItem->quantity; ?>"></td>
                                                                <td><a del_id2="<?php echo $key1 + 77; ?>" class="delete_item2 btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a>
                                                                </td>
                                                                </tr>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <?php if (!empty($cylinderReceive)): ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="clearfix"></div>
                                    <div class="clearfix form-actions" >
                                        <div class="col-md-offset-1 col-md-10">
                                            <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Update
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn" onclick="showCylinder()" type="button">
                                                <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                                                Receive Cylinder
                                            </button>
                                        </div>
                                    </div>
                                </td></tr></table>
                    </form>
                </div>
            </div><!-- /.col -->
        <!-- /.page-content -->

<script>


    $(document).on('click', '.AddreturnedProductEdit', function () {
        //alert('ok');
        //$(".AddreturnedProduct").LoadingOverlay("show");

        var id = $(this).attr("id");
        console.log(id);
        var productName2=$('.returnedProductEdit_'+id).find('option:selected').attr('productName2');
        var package_id2 = $('.returnedProductEdit_'+id).val();
        var returnQuentity = $('.returnedProductQtyEdit_'+id).val();


        if(returnQuentity==''){
            swal("Qty can't be empty.!", "Validation Error!", "error");
            return false;
        }else
            var tab2="<tr>" +
                "<td>" +
                '<input type="hidden" class="text-right form-control" id="" readonly name="returnproductAdd_'+id+'[]" value="' + package_id2 + '">' +
                productName2+
                "</td>" +
                "<td>" +
                '<input type="text" class="text-right form-control" id="" readonly name="returnQuentityAdd_'+id+'[]" value="'+returnQuentity+'">' +

                "</td>" +
                "</tr>";
        $("#return_product_edit_"+id).append(tab2);
        $('.returnedProductEdit_'+id).val('');
        $('.returnedProductQtyEdit_'+id).val('');

    })




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
        if ($("#culinderReceive").css('display') == 'none') {
            cylinder = 0;
        } else {
            //var cylinderItem = parseFloat($(".total_quantity2").text());
            var rowCount = $('#show_item2 tfoot tr').length;

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
            swal("Add Purcahses Item!", "Validation Error!", "error");
        } else if (paymentType == 4 && partialHead == '') {
            swal("Select Account Head!", "Validation Error!", "error");
        } else if (paymentType == 4 && thisAllotment == '') {
            swal("Given Cash Amount!", "Validation Error!", "error");
        } else if (cylinder == 1 && rowCount <=1) {
            swal("Add Receive Cylinder Item or close receive cylinder form!", "Validation Error!", "error");
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

<script>


    $(document).ready(function () {
        console.log("ready!");

        var payType = $('#paymentType').val();
        showBankinfo(payType);
    });




<?php if (!empty($cylinderReceive)):
    ?>

            setTimeout(function () {
                $('#culinderReceive').show();
            }, 10);
<?php else: ?>
        setTimeout(function () {
            $('#culinderReceive').hide();
        }, 2000);
<?php endif; ?>
    var url = baseUrl + "SalesController/getCustomerCurrentBalance";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            customerId: '<?php echo $editInvoice->customer_id; ?>'
        },
        success: function (data)
        {
            $('.currentBalance').val(data);
            getCustomerCurrentBala();

        }
    });
<?php if ($editInvoice->payType == 4) { ?>
        setTimeout(function () {
            $(".partisals").show(10);
        }, 2000);
        $(".partisals").show(10);
<?php } elseif ($editInvoice->payType == 3) { ?>
        $("#showBankInfo").show(10);
<?php } elseif ($editInvoice->payType == 2) { ?>
        setTimeout(function () {
            $(".partisals").hide(10);
        }, 2000);
        $(".creditDate").show(10);
<?php } ?>
</script>
<script type="text/javascript" src="<?php echo base_url('assets/sales/salesEdit.js'); ?>"></script>
