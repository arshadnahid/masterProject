<style>
    table tr td {
        margin: 0px !important;
        padding: 1px !important;
    }

    table tr td tfoot .form-control {
        width: 100%;
        height: 34px;
    }

    .table-fixed thead {
        width: 90%;
    }

    .table-fixed tbody {
        height: 120px;
        overflow-y: auto;
        width: 100%;
    }

    .table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {
        display: block;
    }

    .table-fixed tbody td, .table-fixed thead > tr > th {
        float: left;
        border-bottom-width: 0;
    }
</style>
<div class="row">

    <form id="publicForm" action="" method="post" class="form-horizontal">
        <div class="col-sm-12 col-md-4" style="margin-top: 10px;">
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <span
                            style="color:red;">*</span><strong><?php echo get_phrase('Sales Date')?></strong></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input class="form-control date-picker" name="saleDate" id="saleDate" type="text"
                               value="<?php echo date('d-m-Y', strtotime($editInvoice->invoice_date)); ?>" autocomplete="off"/>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label text-right formfonterp" for="form-field-1"><strong> <?php echo get_phrase('Invoice No')?>.</strong></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" name="userInvoiceId" value="<?php echo $editInvoice->customer_invoice_no; ?>" class="form-control" placeholder="Invoice No"
                               autocomplete="off"/>
                        <span class="input-group-addon" style="background-color:#fff">
                            <?php echo  $editInvoice->invoice_no; ?>
                            <input type="hidden" id="" name="voucherid" readonly value="<?php echo $editInvoice->invoice_no; ?>"/>
                            <input type="hidden" id="" name="sales_invoice_id" readonly value="<?php echo $editInvoice->sales_invoice_id; ?>"/>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <strong><?php echo get_phrase('Loader')?></strong></label>
                <div class="col-sm-7">
                    <select name="loader" class="chosen-select form-control" id="form-field-select-3"
                            data-placeholder="Select  Loader">
                        <option></option>
                        <?php foreach ($employeeList as $key => $eachEmp): ?>
                            <option <?php
                            if ($editInvoice->loader_emp_id == $eachEmp->id) {
                                echo "selected";
                            }
                            ?> value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"><strong><?php echo  get_phrase('Transportation')?></strong></label>
                <div class="col-sm-7">
                    <select name="transportation" class="chosen-select form-control" id="form-field-select-3"
                            data-placeholder="Select Transportation">
                        <option></option>
                        <?php foreach ($vehicleList as $key => $eachVehicle): ?>
                            <option <?php
                            if ($editInvoice->tran_vehicle_id == $eachVehicle->id) {
                                echo "selected";
                            }
                            ?> value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="col-md-4" style="margin-top: 10px;">

            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"
                       style="white-space: nowrap;"><strong><?php echo get_phrase('Delivery Address')?></strong></label>
                <div class="col-sm-7">
                    <input class="form-control" placeholder="Delivery Address" name="shippingAddress" value="<?php echo $editInvoice->delivery_address?>"
                           autocomplete="off"/>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"
                       style="white-space: nowrap;"><strong><?php echo  get_phrase('Delivery Date')?></strong></label>
                <div class="col-sm-7">
                    <input class="form-control date-picker" value="<?php echo $editInvoice->delivery_date!=''?date('d-m-Y', strtotime($editInvoice->delivery_date)):''; ?>"
                           data-date-format="dd-mm-yyyy" autocomplete="off" placeholder="Delivery Date"
                           name="delivery_date"/>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" style="white-space: nowrap;" for=""><span
                            style="color:red;">*</span><strong><?php echo get_phrase('Payment Type')?></strong></label>
                <div class="col-sm-7">
                    <select onchange="showBankinfo(this.value)" name="paymentType" class="chosen-select form-control"
                            id="paymentType" data-placeholder="Select Payment Type">
                        <option></option>
                        <!--<option value="1">Full Cash</option>-->
                        <option <?php
                        if ($editInvoice->payment_type == 4) {
                            echo "selected";
                        }
                        ?> value="4"><?php echo get_phrase('Cash') ?>
                        </option>
                        <option <?php
                        if ($editInvoice->payment_type == 2) {
                            echo "selected";
                        }
                        ?> value="2"><?php echo get_phrase('Credit') ?>
                        </option>
                        <option <?php
                        if ($editInvoice->payment_type == 3) {
                            echo "selected";
                        }
                        ?> value="3"><?php echo get_phrase('Cheque_DD_PO') ?>
                        </option>

                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group partisals AccountHeadDiv">
                <label class="col-sm-3 control-label no-padding-right formfonterp" for="form-field-1"><span
                            style="color:red;"> *</span><strong><?php echo get_phrase('Account')?></strong></label>
                <div class="col-sm-7">
                    <select style="width:100%!important;" name="accountCrPartial"
                            class="chosen-select   checkAccountBalance" id="partialHead"
                            data-placeholder="Select Account Head">
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
                                        ?> value="<?php echo $eachLedger->chartId; ?>"><?php echo get_phrase($eachLedger->title) . " ( " . $eachLedger->code . " ) "; ?></option>
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
                        <span class="input-group-btn" id="newCustomerHide">
                            <a data-toggle="modal" data-target="#myModal"
                               class="saleAddPermission btn blue btn-xs btn-success" style="height:34px;"><i
                                        class="fa fa-plus" style="margin-top:8px;"></i></a>
                        </span>
                    </div>
                </div>

            </div>
            <table class="table table-bordered table-striped table-condensed flip-content">
                <!--<thead>
                <tr>
                    <th class="text-center">Cylinder</th>
                    <th class="text-center">Quantity</th>
                </tr>
                </thead>-->
                <tbody>
                <tr>
                    <td class="text-right" style="width: 30%"><strong><?php echo get_phrase('Address')?>:</strong></td>
                    <td class="text-left"><span id="customer_address_span"> </span></td>

                </tr>
                <tr>
                    <td class="text-right" style="width: 30%"><strong><?php echo get_phrase('Phone')?>:</strong></td>
                    <td class="text-left"><span id="customer_phone_span"> </span></td>

                </tr>

                <tr>
                    <td class="text-right" style="width: 30%"><strong><?php echo get_phrase('Due')?>:</strong></td>
                    <td class="text-left"><span id="previousDue">0.00</span></td>
                </tr>


                </tbody>
            </table>

        </div>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="form-group">

                <div class="col-sm-12">
                    <select name="reference" class="chosen-select form-control" id="form-field-select-3"
                            data-placeholder="Select Reference Name">
                        <option></option>
                        <?php foreach ($referenceList as $key => $each_ref): ?>
                            <option <?php
                            if ($editInvoice->reference == $each_ref->reference_id) {
                                echo "selected";
                            }
                            ?>  value="<?php echo $each_ref->reference_id; ?>"><?php echo $each_ref->referenceName; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" id="form-field-1" name="reference"  value="" class="form-control" placeholder="Reference" />
                    -->
                </div>
            </div>
            <table class="table table-fixed " id="CylinderDueTable">
                <thead>
                <tr>
                    <th class="col-md-3" style="margin-left: -8px;"><strong><?php echo get_phrase('SL')?></strong></th>
                    <th class="col-md-7 text-center"><strong><?php echo get_phrase('Product')?></strong></th>
                    <th class="col-md-2" style="margin-left: 8px;"><strong><?php echo get_phrase('Qty')?></strong></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
        <div  class="col-md-12"  id="showBankInfo" style="display:none;margin-top: -55px;">

            <div  class="col-md-4">
                <div class="form-group" style="margin-left: -29px;">
                    <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <strong><?php echo get_phrase('Bank Name')?></strong></label>
                    <div class="col-sm-7">
                        <select name="bankName" class=" form-control" id="bankName" onchange="getBankBranch(this.value)"
                                data-placeholder="Bank Name">
                            <option value=""></option>
                            <?php foreach ($bankList as $key => $value):



                                ?>
                                <option <?php

                                if ($editInvoice->bank_id == $value->bank_info_id) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $value->bank_info_id; ?>"><?php echo $value->bank_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
            <div  class="col-md-4">
                <div class="form-group">
                    <label  class="col-sm-3 control-label formfonterp" style="white-space: nowrap;"><strong><?php echo  get_phrase('Branch Name')?></strong></label>
                    <div class="col-sm-7">
                        <select name="branchName" class=" form-control" id="branchName"
                                data-placeholder="Bank Name">
                            <option value=""></option>
                            <?php foreach ($bankList as $key => $value): ?>
                                <option value="<?php echo $value->bank_info_id; ?>"><?php echo $value->bank_name; ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
            </div>
            <div  class="col-md-2">
                <div class="form-group">
                    <label class="col-sm-4 formfonterp" style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check No')?></strong></label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo  $editInvoice->check_no?>" class="form-control" id="checkNo" name="checkNo"
                               placeholder="Check NO"/>
                    </div>
                </div>
            </div>
            <div  class="col-md-2">
                <div class="form-group">
                    <label  class="col-sm-4  formfonterp" style="white-space: nowrap;padding-top: 7px;"><strong><?php echo  get_phrase('Check Date')?></strong></label>
                    <div class="col-sm-8">
                        <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate"
                               type="text" value="<?php echo $editInvoice->check_date!=''?date('d-m-Y', strtotime($editInvoice->check_date)):''; ?>" data-date-format="dd-mm-yyyy"/>
                    </div>
                </div>
            </div>

        </div>
        <div class="clearfix"></div>
        <div class="col-md-10" style="margin-top: 10px;">
            <div class="table-header">
                <?php echo get_phrase('Sales Product')?>
            </div>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                <input id="productNameAutocomplete"
                       class="form-control  ui-autocomplete-input"
                       placeholder="Scan/Search Product by Name/Code" autocomplete="off">
            </div>
            <table class="table table-bordered table-hover tableAddItem" id="show_item">
                <thead>
                <tr>
                    <th nowrap style="width:12%;border-radius:10px;"><strong><?php echo get_phrase('Category')?> <span style="color:red;"> *</span></strong>
                    </th>
                    <th nowrap style="width:172px" id="product_th"><strong><?php echo get_phrase('Product')?> <span
                                    style="color:red;"> *</span></strong></th>
                    <th style="white-space:nowrap; width:100px; vertical-align:top;"><strong><?php echo get_phrase('Quantity')?> <span
                                    style="color:red;"> *</span></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Receivable_Qty')?></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Unit_Price_Bdt')?> <span style="color:red;"> *</span></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Total_Price_Bdt')?> <span style="color:red;"> *</span></strong></th>
                    <th nowrap style="width:17%;border-radius:10px;"><strong><?php echo get_phrase('Returned Cylinder')?> <span style="color:red;">
                    </th>
                    <th nowrap style="width:10%;border-radius:10px;"><strong><?php echo get_phrase('Returned Qty')?> <span style="color:red;"></th>
                    <th><strong><?php echo get_phrase('Action')?></strong></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <select class="chosen-select form-control" data-placeholder="Select Category"
                                onchange="getProductList(this.value)">
                            <option></option>
                            <?php
                            foreach ($productCat as $eachInfo) {
                                ?>
                                <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                            <?php } ?>
                            <option value="package">Package</option>

                        </select>
                    </td>
                    <td id="product_td">
                        <select id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control"
                                data-placeholder="Select  Product">
                            <option value=""></option>

                        </select>
                    </td>
                    <td><input type="hidden" class="form-control text-right is_same decimal" value="0"><input
                                type="hidden" value="" id="stockQty"/><input type="text"
                                                                             onkeyup="checkStockOverQty(this.value)"
                                                                             class="form-control text-right quantity decimal"
                                                                             placeholder="0"></td>
                    <td><input type="hidden" value="" id="returnStockQty"/><input type="text" readonly
                                                                                  class="form-control text-right returnQuantity decimal"
                                                                                  placeholder="0"></td>
                    <td><input type="text" class="form-control text-right rate decimal" placeholder="0.00"></td>
                    <td><input type="text" class="form-control text-right price decimal" placeholder="0.00"
                               readonly="readonly"></td>
                    <td>
                        <select id="productID2" class="chosen-select form-control" id="form-field-select-3"
                                data-placeholder="Select  product ">
                            <option value=""></option>
                            <?php
                            foreach ($cylinderProduct as $eachProduct):
                                $productPreFix = substr($eachProduct->productName, 0, 5);
                                if ($eachProduct->category_id == 1):
                                    ?>
                                    <option categoryName2="<?php echo $eachProduct->productCat; ?>"
                                            brand_id="<?php echo $eachProduct->brand_id ?>"
                                            productName2="<?php echo $eachProduct->productName . ' ' . $eachProduct->unitTtile . ' [ ' . $eachProduct->brandName . ']'; ?>"
                                            value="<?php echo $eachProduct->product_id; ?>">
                                        <?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>
                                    </option>
                                    <?php
                                endif;
                            endforeach;
                            ?>
                        </select>
                    </td>
                    <td><input type="text" class="form-control text-right returnQuantity2 decimal" placeholder="0.00">
                    </td>
                    <td><a id="add_item" class="btn blue form-control" href="javascript:void(0);" title="Add Item"><i
                                    class="fa fa-plus" style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a>
                    </td>
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
                        <td style="padding-left:15px;" colspan="2">
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
                                <input type="text" autocomplete="off" class="add_ReturnQuantity text-right form-control decimal"  name="returnAbleQuantity_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $eachStock['tt_returnable_quantity']; ?>">
                            </td>
                        <?php else: ?>
                            <td align="right">
                                <input type="text" autocomplete="off" class="add_ReturnQuantity text-right form-control decimal "  readonly="readonly" name="returnAbleQuantity_<?php echo $eachStock['sales_details_id']; ?>" value="<?php $eachStock['tt_returnable_quantity']; ?>">
                            </td>
                        <?php endif; ?>
                        <td align="right">
                            <input type="text" id="rate_<?php echo $key + 100; ?>" autocomplete="off" class="add_rate  text-right form-control decimal" name="unit_price_<?php echo $eachStock['sales_details_id']; ?>" value="<?php echo $eachStock['unit_price']; ?>">
                        </td>
                        <td align="right">
                            <?php
                            $totalEditPrice += ($eachStock['quantity']*$eachStock['unit_price']);
                            ?>
                            <input type="text" autocomplete="off" class="add_price form-control text-right" id="tprice_<?php echo $key + 100; ?>" readonly name="price[]" value="<?php echo ($eachStock['quantity']*$eachStock['unit_price']); ?>">
                        </td>
                        <td colspan="2">
                            <?php if($eachStock['category_id']==2){?>
                                <table class="table table-border" id="return_product_<?php echo $eachStock['sales_details_id']; ?>">
                                    <tr>
                                        <td>
                                            <select    class="chosen-select form-control   Add returnedProduct_<?php echo $eachStock['sales_details_id']?> "  data-placeholder="Search by product name">
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
                                            <div class="input-group">
                                                <input type="text" class="form-control returnedProductQty_<?php echo $eachStock['sales_details_id']; ?>" />
                                                <a href="javascript:void(0)" id="<?php echo $eachStock['sales_details_id']; ?>" class=" AddreturnedProduct   btn blue   input-group-addon"><i class="fa fa-plus" style=" color:#fff"></i> </a>
                                            </div>
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
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="text-right form-control" id="" readonly name="returnQuentityEdit_<?php echo $eachStock['sales_details_id']?>[]" value="<?php echo $value2['returnable_quantity']?>">
                                                        <a href="javascript:void(0)" id="2" class="btn red remove_returnable  input-group-addon"><i class="fa fa-minus-circle " style=" color:#fff"></i> </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                    ?>
                                </table>
                            <?php }?>
                        </td>
                        <td>
                            <a del_id="<?php echo $key + 100; ?>" autocomplete="off" class="delete_item btn form-control btn-danger" href="javascript:void(0);" title=""><i class="fa fa-times"></i>&nbsp;</a>
                        </td>
                    </tr>
                    <?php
                    // }
                endforeach;
                ?>




                </tfoot>
            </table>

            <table class="table table-bordered table-hover table-success">
                <tr>
                    <td>
                                                            <textarea style="border:none;" cols="120"
                                                                      class="form-control" name="narration"
                                                                      placeholder="Narration......"
                                                                      type="text"><?php echo  $editInvoice->narration?></textarea>
                    </td>
                </tr>
            </table>
            <div class="clearfix"></div>
            <div class="clearfix form-actions">
                <div class="col-md-6 col-md-offset-5">
                    <button onclick="return isconfirm2()" id="subBtn" class="btn blue" type="button">
                        <i class="fa fa-check"></i>
                        <?php echo get_phrase('Update')?>
                    </button>

                </div>
            </div>
        </div>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="portlet box blue">

                <div class="portlet-title" style="min-height:21px">
                    <div class="caption" style="font-size: 12px;padding:1px 0 1px;">
                        <?php echo get_phrase('Payment Calculation')?> </div>

                </div>
                <div class="portlet-body" style="padding:1px">
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="font-size:11px"><strong><?php echo get_phrase('Total')?> :</strong></label>
                        <div class="col-md-7">

                            <input type="text" id="total_price" value="<?php echo $totalEditPrice . '.00'; ?>" class="form-control total_price" readonly="true"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap;font-size:11px"><strong><?php echo get_phrase('Discount')?> (-)
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" onkeyup="calDiscount()" id="" name="discount" value="<?php
                            if (!empty($editInvoice->discount_amount) && $editInvoice->discount_amount >= 1): echo $editInvoice->discount_amount;
                            endif;
                            ?>" autocomplete="off" style="text-align: right" class="form-control" placeholder="0.00" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Grand Total')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input readonly id="grandTotal" type="text" name="grandtotal" value="<?php echo $totalEditPrice - $editInvoice->discount_amount; ?>" style="text-align: right"
                                   class="form-control" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Vat')?> (%) ( + ): </strong></label>
                        <div class="col-md-7">
                            <input type="text" id="vatAmount" style="text-align: right" name="vat" readonly value="<?php
                            if (!empty($configInfo->VAT)): echo $configInfo->VAT;
                            endif;
                            ?>" class="form-control totalVatAmount" placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Loader')?> ( + )
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="loader" onkeyup="calcutateFinal()" style="text-align: right"
                                   name="loaderAmount" value="<?php echo $editInvoice->loader_charge > 0 ? $editInvoice->loader_charge : ''; ?>" class="form-control" placeholder="0.00" autocomplete="off"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;" data-toggle="tooltip" title="Transportation (+) :"> <strong><?php echo get_phrase('Trans')?>..(+) :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="transportation" onkeyup="calcutateFinal()" style="text-align: right"
                                   name="transportationAmount" value="<?php echo $editInvoice->transport_charge > 0 ? $editInvoice->transport_charge : ''; ?>" class="form-control" placeholder="0.00"
                                   autocomplete="off"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Net Total')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="netAmount" name="netTotal" value="<?php echo $editInvoice->invoice_amount ; ?>" readonly class="form-control"
                                   style="text-align: right" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group" style="display: none">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Chq Amount')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="chaque_amount" name="chaque_amount" value="<?php echo isset($bank_check_details->totalPayment) ? $bank_check_details->totalPayment : '' ?>" style="text-align: right"
                                   class="form-control" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group" id="paymentTr">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Payment')?>( - )
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="payment" onkeyup="calculatePartialPayment()" style="text-align: right"
                                   autocomplete="off"
                                   name="partialPayment" value="<?php echo $editInvoice->paid_amount; ?>" class="form-control" autocomplete="off" placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>
                    <div class="form-group creditDate" style="display:none;">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Due Date')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input class="form-control date-picker" name="creditDueDate" id="dueDate" style="text-align: right"
                                   type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"
                                   autocomplete="off"/>

                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong>Due Amount :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="payment" onkeyup="calculatePartialPayment()" style="text-align: right"
                                   name="partialPayment" value="" class="form-control" autocomplete="off" placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>-->

                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Due Amount')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="currentDue" readonly="true" name="" value="" style="text-align: right"
                                   class="form-control" autocomplete="off" placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong>Total Due :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="totalDue" readonly name="" value="" style="text-align: right"
                                   class="form-control" autocomplete="off" placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>-->


                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function showBankinfo(id) {
        $("#payment").val('');
        calcutateFinal();
        //calculateCustomerDue();
        $(".chaque_amount_class").hide(10);
        if (id == 3) {
            $("#showBankInfo").show(10);
            $(".chaque_amount_class").show(10);
        } else {
            $("#showBankInfo").hide(10);

        }
        if (id == 4) {
            $("#paymentTr").show(10);
            $(".AccountHeadDiv").show(10);
        } else {
            $("#paymentTr").hide(10);
            $(".AccountHeadDiv").hide(10);
        }
        if (id == 2) {
            $(".creditDate").show(10);
        } else {
            $(".creditDate").hide(10);
        }
    }

    var calculateCustomerDue = function () {
        $('#previousDue').html('');
        $('#customer_address_span').html('');
        $('#customer_phone_span').html('');


        var customerid = parseFloat($('#customerid').val());
        var netAmount = parseFloat($('#netAmount').val());
        var payment = parseFloat($("#payment").val());
        if (isNaN(payment)) {
            payment = 0;
        }
        if (isNaN(netAmount)) {
            netAmount = 0;
        }
        $('#CylinderDueTable tbody').html('');
        var url = baseUrl + "getCustomerCurrentBalance";
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                customerId: customerid
            },
            success: function (data) {

                var data = JSON.parse(data);
                var due;
                due = parseFloat(data.customer_due);
                if (isNaN(due)) {
                    due = 0;
                }
                $('#previousDue').html(parseFloat(due).toFixed(2));

                $('#customer_address_span').html(data.customer_details.customerAddress);
                $('#customer_phone_span').html(data.customer_details.customerPhone);
                /*$('#customerName').html(data.customer_details.customerName);
                 $('#customerID').html(data.customer_details.customerID);*/
                var dueCylinder = data.CylinderDue;
                console.log(dueCylinder);
                var i = 1;
                $.each(dueCylinder, function (key, value) {
                    var tr = '<tr>' +
                        '<td class="col-xs-2">' + i + '</td>' +
                        '<td class="col-xs-8">' + value.productName + ' [ ' + value.brandName + ' ]' + '</td>' +
                        '<td class="col-xs-2">' + value.customer_due + '</td>' +
                        '</tr>';

                    $('#CylinderDueTable tbody').prepend(tr);
                });


            }
        });
    };

    function getProductList(cat_id) {
        if (cat_id == 2) {
            $(".returnQuantity").attr("readonly", false);
        } else {
            $(".returnQuantity").attr("readonly", true);
        }
        $.ajax({
            type: "POST",
            url: baseUrl + "getProductList",
            data: 'cat_id=' + cat_id,
            success: function (data) {
                $('#productID').chosen();
                $('#productID option').remove();
                $("#productID").trigger("chosen:open");
                //event.stopPropagation();
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
            }
        });
    }

    function getBankBranch(id) {
        $('#branchName').html('');
        var branchId='<?php echo  $editInvoice->bank_branch_id;?>';
        if (id != '') {
            $.ajax({
                type: "POST",
                url: baseUrl + "getbankbranchList",
                data: 'bank_id=' + id,
                dataType: 'json',
                success: function (data) {

                    var option = "";
                    var selected='';
                    option += "<option value='" + '' + "'>---Select Name---</option>";
                    $.each(data, function (key, value) {
                        if(branchId==value.bank_branch_id){
                            selected='selected';
                        }
                        option += "<option "+selected+"  value='" + value.bank_branch_id + "'  >" + value.bank_branch_name + "</option>";
                        selected='';
                    });
                    $('#branchName').html(option);
                }
            });
        }
    }

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
        var totalPrice = parseFloat($(".total_price").val());
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
        getCustomerCurrentBalace();
        var j = 0;
        var slNo = 101000;
        $("#add_item").click(function () {

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

            var returnQuantity2 = $('.returnQuantity2').val();


            if (productID == '') {
                swal("Product Name can't be empty!", "Validation Error!", "error");
                $("#productID").trigger("chosen:open");
                return false;
            } else if (quantity == '') {
                swal("Quantity Can't be empty!", "Validation Error!", "error");
                return false;
            } else if (rate == '') {
                swal("Unit Price Can't be empty!", "Validation Error!", "error");
                return false;
            }


            if (ispackage == 0) {

                var tab;

                if (productCatID == 2) {
                    //refill cylinder
                    if ($('.is_same').val() == 0) {

                        slNo++;
                        // for return cylender
                        var returnedCylender = '';
                        if (returnQuantity2 > 0) {
                            returnedCylender = '<tr>' +
                                '<td>' +
                                '<input type="hidden" class="text-right form-control" id="" readonly name="Addreturnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                                productName2 +
                                '</td>' +
                                '<td>' +
                                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="AddreturnedQuantity_' + slNo + '[]" value="' + returnQuantity2 + '"><a href="javascript:void(0)" id="2" class="btn red remove_returnable  input-group-addon"><i class="fa fa-minus-circle " style="color:#fff"></i> </a> </div>' +
                                '</td>' +
                                '</tr>';
                        }


                        tab = '<tr class="new_item' + slNo + '">' +
                            '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                            '<input type="hidden" name="Addbrand_id[]" value="' + brand_id + '"/>' +
                            '<input type="hidden" name="Addis_package_' + slNo + '"  value="0">' +
                            '<input type="hidden" name="Addcategory_id[]"  value="' + productCatID + '">' +
                            '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName +
                            '<input type="hidden"  name="Addproduct_id_' + slNo + '" value="' + productID + '">' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" id="qty_' + slNo + '" class="form-control text-right add_quantity decimal" style="height: 33px;" onkeyup="checkStockOverQty(this.value)" name="Addquantity_' + slNo + '" value="' + quantity + '">' +
                            '</td>' +
                            '<td align="right"><input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="AddreturnQuantity[' + slNo + ']" value="' + returnQuantity + '">' +
                            '</td>' +
                            '<td align="right"><input type="text" id="rate_' + slNo + '" class="form-control add_rate text-right decimal" name="Addrate_' + slNo + '" value="' + rate + '">' +
                            '</td>' +
                            '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_' + slNo + '" name="Addprice[]" value="' + price + '">' +
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
                            '<div class="input-group"><input type="text" class="form-control returnedProductQty_' + slNo + '" /><a href="javascript:void(0)" id="' + slNo + '" class="btn blue AddreturnedProduct  input-group-addon"><i class="fa fa-plus" style=" color:#fff"></i> </a> </div>' +
                            '</td>' +
                            '</tr>' +
                            returnedCylender +
                            '</table>' +
                            '</td>' +
                            '<td>' +
                            '<a del_id="' + slNo + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 4px;margin-left: 6px;"></i>&nbsp;</a>' +
                            '</td>' +
                            '</tr>';

                        $("#show_item tfoot").append(tab);
                    } else {
                        slNo;
                        var tab2 = "<tr>" +
                            "<td>" +
                            '<input type="hidden" class="text-right form-control" id="" readonly name="Addreturnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                            productName2 +
                            "</td>" +
                            "<td>" +
                            '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="AddreturnedQuantity_' + slNo + '[]" value="' + returnQuantity2 + '"><a href="javascript:void(0)" id="2" class="btn red remove_returnable  input-group-addon"><i class="fa fa-minus-circle " style=" color:#fff"></i> </a> </div>' +
                            "</td>" +
                            "</tr>";

                        $("#return_product_" + slNo).append(tab2);

                    }


                    $('.is_same').val('1')
                } else {
                    slNo++;
                    tab = '<tr class="new_item' + slNo + '">' +
                        '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                        '<input type="hidden" name="Addbrand_id[]" value="' + brand_id + '"/>' +
                        '<input type="hidden" name="Addis_package_' + slNo + '" value="0">' +
                        '<input type="hidden" name="Addcategory_id[]" value="' + productCatID + '">' +
                        '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName +
                        '<input type="hidden"  name="Addproduct_id_' + slNo + '" value="' + productID + '">' +
                        '</td>' +
                        '<td align="right">' +
                        '<input type="text" id="qty_' + slNo + '" class="form-control text-right add_quantity decimal" onkeyup="checkStockOverQty(this.value)" name="Addquantity_' + slNo + '" value="' + quantity + '">' +
                        '</td>' +
                        '<td align="right"><input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="AddreturnQuantity[]" value="' + returnQuantity + '">' +
                        '</td>' +
                        '<td align="right"><input type="text" id="rate_' + slNo + '" class="form-control add_rate text-right decimal" name="Addrate_' + slNo + '" value="' + rate + '">' +
                        '</td>' +
                        '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_' + slNo + '" name="Addprice[]" value="' + price + '">' +
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
                        '<a del_id="' + slNo + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
                        '</td>' +
                        '</tr>';

                    $("#show_item tfoot").append(tab);


                }
                findTotalCal();
                setTimeout(function () {
                    ///calculateCustomerDue();
                    calcutateFinal();
                }, 100);
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
                        var style = '';
                        var rowspan = '';
                        $.each(data, function (key, value) {

                            slNo++;
                            rowspan = '2';
                            $("#show_item tfoot").append('<tr class="new_item' + slNo + '    packageDeleteRow_' + value['package_id'] + '"><input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/><input type="hidden" name="Addis_package_' + slNo + '" value="1"><input type="hidden" name="Addcategory_id_' + slNo + '" value="' + value['category_id'] + '">' +
                                '<td style="padding-left:15px;" colspan="2"> [ ' + value['title'] + '] - ' + value['productName'] + '&nbsp;' + value['unitTtile'] + '&nbsp;[ ' + value['brandName'] + " ]" +
                                ' <input type="hidden"  name="Addproduct_id_' + slNo + '" value="' + value['product_id'] + '"></td>' +
                                '</td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" id="qty_' + slNo + '" name="Addquantity_' + slNo + '" value="' + quantity + '"></td><td align="right"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + slNo + '"   name="Addadd_returnAble[]" value=""  readonly></td><td align="right"><input type="text" id="rate_' + slNo + '" class="add_rate form-control decimal text-right" name="Addrate_' + slNo + '" value="' + rate + '"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tprice_' + slNo + '" readonly name="price[]" value="' + price + '"></td><td></td><td></td><td style="' + style + '"   rowspan="' + rowspan + '"><a del_id="' + slNo + '" package_id_delete="' + value['package_id'] + '"class="delete_item btn form-control btn-danger" href="javascript:void(0);" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');
                            j++;
                            style = 'display:none';


                        });

                    }, complete: function () {
                        findTotalCal();
                        setTimeout(function () {
                            ///calculateCustomerDue();
                            calcutateFinal();
                        }, 100);
                    }
                });
                $('.quantity').val('');
                $('.rate').val('');
                $('.price').val('');
                $('.returnAble').val('');
            }


        })
        j++;


    });
    $(document).on('click', '.AddreturnedProduct', function () {
        //$(".AddreturnedProduct").LoadingOverlay("show");
        alert('OKOOOO');
        var id = $(this).attr("id");
        var productName2 = $('.returnedProduct_' + id).find('option:selected').attr('productName2');
        var package_id2 = $('.returnedProduct_' + id).val();
        var returnQuantity2 = $('.returnedProductQty_' + id).val();


        if (returnQuantity2 == '') {
            swal("Qty can't be empty.!", "Validation Error!", "error");
            return false;
        } else
            var tab2 = "<tr>" +
                "<td>" +
                '<input type="hidden" class="text-right form-control" id="" readonly name="returnproductAdd_' + id + '[]" value="' + package_id2 + '">' +
                productName2 +
                "</td>" +
                "<td>" +
                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnedQuantityAdd_' + id + '[]" value="' + returnQuantity2 + '"><a href="javascript:void(0)" id="2" class=" btn red remove_returnable  input-group-addon"><i class="fa fa-minus-circle " style="color:#fff"></i> </a> </div>' +
                "</td>" +
                "</tr>";
        $("#return_product_" + id).append(tab2);
        $('.returnedProduct_' + id).val('');
        $('.returnedProductQty_' + id).val('');

    });

    function getProductPrice(product_id) {
        $("#stockQty").val('');
        $(".quantity").val('');
        $('.is_same').val('0');
        var productCatID = parseFloat($('#productID').find('option:selected').attr('categoryId'));

        var ispackage = $('#productID').find('option:selected').attr('ispackage');
        if (ispackage == 1) {
            product_id = $('#productID').find('option:selected').attr('product_id');
            $('#productID2').val('').prop('disabled', false).trigger("chosen:updated");
            $(".returnQuantity").attr('readonly', false);
            $(".returnQuantity2").attr('readonly', false);
        }


        if (productCatID == 2 && ispackage == 0) {
            $('#productID2').val('').prop('disabled', false).trigger("chosen:updated");
            $(".returnQuantity").attr('readonly', false);
            $(".returnQuantity2").attr('readonly', false);
        } else {
            $(".returnQuantity").attr('readonly', true);
            $(".returnQuantity2").attr('readonly', true);
            $('#productID2').val('').prop('disabled', true).trigger("chosen:updated");
        }
        $.ajax({
            type: "POST",
            url: baseUrl + "getProductPriceForSale",
            data: 'product_id=' + product_id,
            success: function (data) {
                $('.rate').val('');
            }
        });
        $.ajax({
            type: "POST",
            url: baseUrl + "getProductStock",
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
    $(document).ready(function () {
        showBankinfo('<?php echo $editInvoice->payment_type ?>');

        $('#bankName').trigger('change');
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
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
                        <form id="publicForm2" action="" method="post" class="form-horizontal">

                            <?php
                            $this->db->select("*");
                            $this->db->from("customertype");
                            $this->db->where_in('dist_id', array($this->dist_id, 1));
                            $customerType = $this->db->get()->result();

                            // echo $this->db->last_query();die;
                            //$customerType = $this->Common_model->get_data_list_by_single_column('customertype', 'dist_id', $this->dist_id);
                            ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer
                                    Type</label>
                                <div class="col-sm-9">
                                    <select name="customerType" class=" form-control" id="form-field-select-3"
                                            data-placeholder="Search by Customer Type">
                                        <option>-Select Type-</option>
                                        <?php foreach ($customerType as $key => $eachType): ?>
                                            <option value="<?php echo $eachType->type_id; ?>"><?php echo $eachType->typeTitle; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer
                                    ID </label>
                                <div class="col-sm-9">
                                    <input type="text" id="customerId" name="customerID" readonly
                                           value="<?php echo isset($customerID) ? $customerID : ''; ?>"
                                           class="form-control" placeholder="Customer ID"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer
                                    Name </label>
                                <div class="col-sm-9">
                                    <input type="text" id="customerName" name="customerName" class="form-control"
                                           placeholder="Customer Name" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>
                                <div class="col-sm-9">
                                    <input type="text" maxlength="11" id="form-field-1 cstPhone"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           onblur="checkDuplicatePhone(this.value)" name="customerPhone"
                                           placeholder="Customer Phone" class="form-control"/>
                                    <span id="errorMsg" style="color:red;display: none;"><i
                                                class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                                <div class="col-sm-9">
                                    <input type="email" id="form-field-1" name="customerEmail" placeholder="Email"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                    Address</label>
                                <div class="col-sm-9">
                                    <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                                    <textarea cols="6" rows="3" placeholder="Type Address.." class="form-control"
                                              name="customerAddress"></textarea>
                                </div>
                            </div>
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">

                                    <button type="button" class="btn red btn-outline sbold" data-dismiss="modal">Close
                                    </button>
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
