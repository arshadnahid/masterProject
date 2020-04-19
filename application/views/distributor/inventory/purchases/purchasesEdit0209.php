<?php


?>


<style>
    table tr td {
        margin: 0px !important;
        padding: 1px !important;
    }

    table tr td tfoot .form-control {
        width: 100%;
        height: 34px;
    }

    .custome_read_only {
        pointer-events: none;
        cursor: not-allowed;
        background-color: #eeeeee;
    }

    .ui-autocomplete {
        /*  background-color: #FFF;
          box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
          width: 50%;height: 25px;display: inline-block;background-color: #ffffff;border: 1px solid #d0d0d0;border-radius: 0;margin-left: 15px;
          margin-bottom: 20px;*/
        max-height: 250px !important;
        max-width: 300px !important;
        overflow: auto !important;
        height: auto !important;
        margin-left: -38px !important;
    }

    /*.ui-menu .ui-menu-item {
        padding: 5px 10px 6px;
        color: #444;
        cursor: pointer;
        display: block;
        -webkit-box-sizing: inherit;
        -moz-box-sizing: inherit;
        box-sizing: inherit;
    }*/
    .ui-autocomplete .ui-menu-item {
        font-size: 14px !important;
        background: #fff;
        border-bottom: 1px solid rgba(128, 128, 128, 0.20);
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        height: 30px !important;
        line-height: 30px !important;
        color: gray;
        padding-bottom: 15px !important;
        margin: 0px !important;
        font-weight: normal !important;
    }
</style>

<div class="row">
    <form id="publicForm" action="" method="post" class="form-horizontal">
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label class="col-sm-3 control-label text-right"
                       for="form-field-1"><span style="color:red;"> *</span> <?php echo get_phrase('Purchases Date')?></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input class="form-control date-picker" name="purchasesDate"
                               id="purchasesDate" type="text"
                               value="<?php echo date('d-m-Y', strtotime($purchasesList->invoice_date)); ?>"
                               data-date-format="dd-mm-yyyy" required/>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <span style="color:red;"> *</span> <?php echo get_phrase('Supplier Id')?> </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <select id="supplierid" name="supplierID"
                                onchange="getSupplierClosingBalance(this.value)"
                                class="chosen-select form-control" id="form-field-select-3"
                                data-placeholder="Search by Supplier ID or Name" required>
                            <option></option>
                            <?php foreach ($supplierList as $key => $each_info): ?>
                                <option <?php
                                if ($editPurchases->supplier_id == $each_info->sup_id) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $each_info->sup_id; ?>"><?php echo $each_info->supID . ' [ ' . $each_info->supName . ' ] '; ?></option>
                            <?php endforeach; ?>

                        </select>
                        <span class="input-group-btn" id="hideNewSup">
                            <a data-toggle="modal" data-target="#myModal" class="btn blue btn-xs btn-success"
                               style="height:34px;"><i class="fa fa-plus" style="margin-top: 9px;"></i>&nbsp;New</a>
                        </span>
                    </div>
                </div>

            </div>
            <div class="col-md-6" id="hideAccount" style="display: none;">
                <div class="form-group  ">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                        <span style="color:red;">*</span> Pay. From( CR ) </label>
                    <div class="col-sm-7">
                        <select name="accountCr"
                                class="chosen-select form-control  checkAccountBalance"
                                id="form-field-select-3"
                                data-placeholder="Search by Account Head"
                                onchange="check_pretty_cash(this.value)">
                            <option value=""></option>
                            <?php
                            foreach ($accountHeadList as $key => $head) {
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
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="accountBalance" readonly name="balance" value=""
                               class="form-control" placeholder="Balance"/>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <?php
            $style = '';
            if ($purchasesList->payment_type != 2) {
                $style = 'display:none';
            }

            ?>
            <div class="form-group" style="<?php echo $style ?>" id="dueDateDiv">
                <label class="col-sm-3 control-label no-padding-right"
                       for="form-field-1"><span style="color:red;"> *</span> <?php echo get_phrase('Due Date')?></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input class="form-control date-picker" name="dueDate" id="dueDate"
                               type="text" value="<?php echo date('d-m-Y', strtotime($purchasesList->due_date)); ?>"
                               data-date-format="dd-mm-yyyy"/>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group  ">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                    <span style="color:red;"> *</span> <?php echo get_phrase('Payment Type')?> </label>
                <div class="col-sm-7">
                    <select onchange="showBankinfo(this.value)" name="paymentType"
                            class="chosen-select form-control" id="paymentType"
                            data-placeholder="Select Payment Type" required>
                        <option></option>
                        <option <?php
                        if ($purchasesList->payment_type == 4) {
                            echo "selected";
                        }
                        ?> value="4">Cash
                        </option>
                        <option <?php
                        if ($purchasesList->payment_type == 2) {
                            echo "selected";
                        }
                        ?> value="2">Credit
                        </option>
                        <option <?php
                        if ($purchasesList->payment_type == 3) {
                            echo "selected";
                        }
                        ?> value="3">Cheque / DD/ PO
                        </option>
                    </select>
                </div>
            </div>
            <?php
            $style = '';
            if ($purchasesList->payment_type != 4) {
                //$style='display:none';
            }

            ?>
            <div class="form-group" id="showAccount" style="<?php echo $style ?>">
                <label class="col-sm-3 control-label text-right" for="form-field-1"><span style="color:red;"> *</span><?php echo get_phrase('Add Account')?></label>
                <div class="col-sm-7">
                    <select style="width:100%!important"
                            name="accountCrPartial"
                            class="chosen-select  checkAccountBalance"
                            id="partialHead2"
                            data-placeholder="Search by Account Head"
                            onchange="check_pretty_cash(this.value)">
                        <option value=""></option>
                        <?php
                        foreach ($accountHeadList as $key => $head) {
                            //if ($key == 42 || $key == 45 || $key == 55) {
                            ?>
                            <optgroup
                                    label="<?php echo $head['parentName']; ?>">
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
                            //}
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
        <div class="col-sm-12 col-md-6">

            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <?php echo get_phrase('Voucher Id')?> </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" id="form-field-1" name="userInvoiceId"
                               value="<?php echo $purchasesList->supplier_invoice_no; ?>" class="form-control"
                               placeholder="Voucher ID "/>
                        <span class="input-group-addon" style="background-color:#fff">
                            <?php echo $purchasesList->invoice_no; ?>
                            <input type="hidden" id="" name="voucherid" readonly
                                   value="<?php echo $purchasesList->invoice_no; ?>"/>
                            <input type="hidden" id="" name="purchase_invoice_id" readonly
                                   value="<?php echo $purchasesList->purchase_invoice_id; ?>"/>
                        </span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <?php echo get_phrase('Reference')?></label>
                <div class="col-sm-7">
                    <input type="text" id="form-field-1" name="reference"
                           value="<?php echo $purchasesList->refference_person; ?>"
                           class="form-control" placeholder="Reference"/>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <?php echo get_phrase('Loader')?></label>
                <div class="col-sm-7">
                    <select name="loader" class="chosen-select form-control"
                            id="form-field-select-3" data-placeholder="Search by Loader">
                        <option></option>
                        <option></option>
                        <?php foreach ($employeeList as $key => $eachEmp): ?>
                            <option <?php
                            if ($purchasesList->loader_emp_id == $eachEmp->id): echo "selected";
                            endif;
                            ?> value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <?php echo get_phrase('Transportation')?></label>
                <div class="col-sm-7">
                    <select name="transportation" class="chosen-select form-control"
                            id="form-field-select-3"
                            data-placeholder="Search by Transportation">
                        <option></option>
                        <?php foreach ($vehicleList as $key => $eachVehicle): ?>
                            <option <?php
                            if ($purchasesList->tran_vehicle_id == $eachVehicle->id) {
                                echo "selected";
                            }
                            ?> value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

        </div>
        <?php
        $style = '';
        if ($purchasesList->payment_type != 3) {
            $style = 'display:none';
        }

        ?>

        <div class="col-md-12" id="showBankInfo" style="margin-top: 3px;<?php echo $style ?>">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4 formfonterp" for="form-field-1"> <strong><?php echo get_phrase('Bank Name')?></strong></label>
                    <div class="col-sm-8">
                        <select name="bankName" class=" form-control" id="bankName" onchange="getBankBranch(this.value)"
                                data-placeholder="Bank Name">
                            <option value=""></option>
                            <?php foreach ($bankList as $key => $value): ?>
                                <option <?php
                                if ($purchasesList->bank_id == $value->bank_info_id) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $value->bank_info_id; ?>"><?php echo $value->bank_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4  formfonterp" style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Branch Name')?></strong></label>
                    <div class="col-sm-8">
                        <select name="branchName" class=" form-control" id="branchName"
                                data-placeholder="Bank Name">
                            <option value=""></option>
                            <?php foreach ($bankList as $key => $value): ?>
                                <option <?php
                                if ($purchasesList->bank_branch_id == $value->bank_info_id) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $value->bank_info_id; ?>"><?php echo $value->bank_name; ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4 formfonterp" style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check No')?></strong></label>
                    <div class="col-sm-8">
                        <input type="text" value="" class="form-control" id="checkNo" name="checkNo"
                               placeholder="Check NO"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4  formfonterp" style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check Date')?></strong></label>
                    <div class="col-sm-8">
                        <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate"
                               type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"/>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-10" style="margin-top:10px">
            <div class="table-header">
                <?php echo get_phrase('Purchases Item')?>
            </div>

            <!-- <button type="button" class="btn btn-primary btn-rounded" onclick="packageOrProduct(1)">Product</button>
             <button type="button" class="btn btn-default btn-rounded" onclick="packageOrProduct(0)">Package</button>!-->

            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                <input id="productNameAutocomplete"
                       class="form-control  ui-autocomplete-input"
                       placeholder="Scan/Search Product by Name/Code" autocomplete="off">
            </div>
            <table class="table table-bordered table-hover" id="show_item">
                <thead>
                <tr>
                    <!--   <th id="package_th" nowrap style="width:25%;border-radius:10px;" align="center"><strong>package <span style="color:red;"> *</span></strong></th> !-->
                    <th nowrap style="width:12%;border-radius:10px;" id="product_th"><strong>Category<span
                                    style="color:red;"> *</span></strong>
                    </th>
                    <th nowrap style="width:172px" align="center" id="product_th"><strong><?php echo get_phrase('Product')?>
                            <span style="color:red;"> *</span></strong></th>
                    <th nowrap style="white-space:nowrap; width:100px; vertical-align:top;"><strong><?php echo get_phrase('Quantity')?> <span
                                    style="color:red;"> *</span></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Returnable_Qty')?></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Unit Price')?>() <span
                                    style="color:red;"> *</span></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Total Price')?>(<?php echo get_phrase('BDT')?>) <span
                                    style="color:red;"> *</span></strong></th>
                    <th nowrap style="width:17%;border-radius:10px;"><strong><?php echo get_phrase('Returned Cylinder')?> <span style="color:red;">
                    </th>
                    <th nowrap style="width:10%;border-radius:10px;"><strong><?php echo get_phrase('Returned Qty')?>
                            <span style="color:red;"></th>
                    <th align="center"><strong><?php echo get_phrase('Action')?></strong></th>
                </tr>
                </thead>
                <tbody id="show_item_tbody">
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
                        <select id="productID"
                                onchange="getProductPrice(this.value)"
                                class="chosen-select form-control"
                                id="form-field-select-3"
                                data-placeholder="Search by Product">
                            <option value=""></option>
                            <?php foreach ($productList as $key => $eachProduct):
                                ?>
                                <optgroup
                                        label="<?php echo $eachProduct['categoryName']; ?>">
                                    <?php
                                    foreach ($eachProduct['productInfo'] as $eachInfo) :

                                        $productPreFix = substr($eachInfo->productName, 0, 5);
                                        //if ($productPreFix != 'Empty'):
                                        ?>
                                        <option ispackage="0"
                                                brand_id="<?php echo $eachInfo->brand_id ?>"
                                                categoryName="<?php echo $eachProduct['categoryName']; ?>"
                                                unit="<?php echo $eachInfo->unitTtile ?>"
                                                categoryId="<?php echo $eachProduct['categoryId']; ?>"
                                                productName="<?php echo $eachInfo->productName . ' ' . $eachInfo->unitTtile . " [ " . $eachInfo->brandName . " ] "; ?>"
                                                value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName . ' ' . $eachInfo->unitTtile . " [ " . $eachInfo->brandName . " ] "; ?></option>
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
                                    <option ispackage="1"
                                            value="<?php echo $eachInfo->package_id; ?>"><?php echo $eachInfo->package_name . " [ " . $eachInfo->package_code . " ] "; ?></option>
                                    <?php
                                    // endif;
                                endforeach;
                                ?>
                            </optgroup>
                            -->


                        </select>
                    </td>
                    <td><input type="hidden"
                               class="form-control text-right is_same decimal"
                               value="0"><input type="text"
                                                class="form-control text-right quantity decimal"
                                                placeholder="0"></td>
                    <td><input type="text"
                               class="form-control text-right returnAble decimal"
                               placeholder="0"></td>
                    <td><input type="text"
                               class="form-control text-right rate decimal"
                               placeholder="0.00"></td>
                    <td><input type="text"
                               class="form-control text-right price decimal"
                               placeholder="0.00" readonly="readonly"></td>
                    <td>
                        <select id="productID2"
                                onchange="getProductPrice2(this.value)"
                                class="chosen-select form-control"
                                id="form-field-select-3"
                                data-placeholder="Search by product name">
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
                    <td><input type="text"
                               class="form-control text-right returnQuentity decimal"
                               placeholder="0.00"></td>
                    <td><a id="add_item" class="btn btn-info form-control"
                           href="javascript:;" title="Add Item"><i
                                    class="fa fa-plus"
                                    style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a>
                    </td>
                </tr>
                </tbody>
                <tfoot>


                <?php
                $totalEditPrice = 0;
                foreach ($stockListEdit[$purchasesList->purchase_invoice_id] as $key => $eachStock ){
                    ?>

                    <tr class="new_item<?php echo $key + 100; ?>">
                        <td style="padding-left:15px;" colspan="2">
                            <input type="hidden" name="category_id[]" value="<?php echo $eachStock['category_id']; ?>"/>
                            <input type="hidden" name="purchase_details_id[]" value="<?php echo $eachStock['purchase_details_id']; ?>"/>
                            <input type="hidden" name="is_package_<?php echo $eachStock['purchase_details_id']; ?>" value="<?php echo $eachStock['is_package']; ?>"/>
                            <?php

                            echo ' [ ' . $eachStock['title'] . ' ] ' . $eachStock['productName'] . ' [ ' . $eachStock['brandName'] . ' ] ';
                            ?>
                            <input type="hidden" name="product_id_<?php echo $eachStock['purchase_details_id']; ?>" value="<?php echo $eachStock['product_id']; ?>">
                        </td>
                        <td align="right">
                            <input type="text" id="qty_<?php echo $key + 100; ?>" autocomplete="off" class="add_quantity text-right form-control decimal" name="quantity_<?php echo $eachStock['purchase_details_id']; ?>" value="<?php echo $eachStock['quantity']; ?>">
                        </td>
                        <?php if ($eachStock['category_id'] == 2): ?>
                            <td align="right">
                                <input type="text" autocomplete="off" class="add_ReturnQuantity text-right form-control decimal"  name="returnAbleQuantity_<?php echo $eachStock['purchase_details_id']; ?>" value="<?php echo $eachStock['tt_returnable_quantity']; ?>">
                            </td>
                        <?php else: ?>
                            <td align="right">
                                <input type="text" autocomplete="off" class="add_ReturnQuantity text-right form-control decimal "  readonly="readonly" name="returnAbleQuantity_<?php echo $eachStock['purchase_details_id']; ?>" value="<?php $eachStock['tt_returnable_quantity']; ?>">
                            </td>
                        <?php endif; ?>
                        <td align="right">
                            <input type="text" id="rate_<?php echo $key + 100; ?>" autocomplete="off" class="add_rate  text-right form-control decimal" name="unit_price_<?php echo $eachStock['purchase_details_id']; ?>" value="<?php echo $eachStock['unit_price']; ?>">
                        </td>
                        <td align="right">
                            <?php
                            $totalEditPrice += ($eachStock['quantity']*$eachStock['unit_price']);
                            ?>
                            <input type="text" autocomplete="off" class="add_price form-control text-right" id="tprice_<?php echo $key + 100; ?>" readonly name="price_[]" value="<?php echo ($eachStock['quantity']*$eachStock['unit_price']); ?>">
                        </td>
                        <td colspan="2">
                            <?php if($eachStock['category_id']==2){?>
                                <table class="table table-border" id="return_product_<?php echo $eachStock['purchase_details_id']; ?>">
                                    <tr>
                                        <td>
                                            <select    class="chosen-select form-control   Add returnedProduct_<?php echo $eachStock['purchase_details_id']?> "  data-placeholder="Search by product name">
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
                                                <input type="text" class="form-control returnedProductQty_<?php echo $eachStock['purchase_details_id']; ?>" />
                                                <a href="javascript:void(0)" id="<?php echo $eachStock['purchase_details_id']; ?>" class=" AddreturnedProduct   btn blue   input-group-addon"><i class="fa fa-plus" style=" color:#fff"></i> </a>
                                            </div>
                                        </td>


                                    </tr>
                                    <?php
                                    foreach ($eachStock['return'] as $key1 =>$value1){
                                        foreach ($value1 as $key2 =>$value2){
                                            ?>
                                            <tr>
                                                <td>

                                                    <input type="hidden" class="text-right form-control" id="" readonly name="returnproductEdit_<?php echo $eachStock['purchase_details_id']?>[]" value="<?php echo $value2['purchase_return_id']?>">
                                                    <?php echo $value2['return_product_cat'].' '. $value2['return_product_name'] .' '. $value2['return_product_unit'] .'[ '. $value2['return_product_brand'] .' ]'?></td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="text-right form-control" id="" readonly name="returnQuentityEdit_<?php echo $eachStock['purchase_details_id']?>[]" value="<?php echo $value2['returnable_quantity']?>">
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

                }
                ?>


                </tfoot>
            </table>
            <table class="table table-bordered table-hover table-success">
                <tr>
                    <td>
                                                            <textarea style="border:none;" cols="120"
                                                                      class="form-control" name="narration"
                                                                      placeholder="Narration......"
                                                                      type="text"></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="portlet box blue">

                <div class="portlet-title" style="min-height:21px">
                    <div class="caption" style="font-size: 12px;padding:1px 0 1px;">
                        <?php echo get_phrase('Payment Calculation')?>
                    </div>

                </div>
                <div class="portlet-body" style="padding:1px">
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="font-size:11px"><strong><?php echo get_phrase('Total')?> :</strong></label>
                        <div class="col-md-7">

                            <input type="text" value="" class="form-control total_price text-right" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap;font-size:11px"><strong><?php echo get_phrase('Discount')?>
                                (-)
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="discount"
                                   onkeyup="calcutateFinal()"
                                   style="text-align: right" name="discount"
                                   value="<?php echo  $purchasesList->discount_amount?>" class="form-control"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Loader')?>
                                ( + ):</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="loader"
                                   onkeyup="calcutateFinal()"
                                   style="text-align: right" name="loaderAmount"
                                   value="<?php echo  $purchasesList->loader_charge?>" class="form-control"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px"
                               data-toggle="tooltip" title="Transportation (+) :"><strong><?php echo get_phrase('Trans')?>( + ):</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="transportation"
                                   onkeyup="calcutateFinal()"
                                   style="text-align: right"
                                   name="transportationAmount" value="<?php echo  $purchasesList->transport_charge?>"
                                   class="form-control" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Net Total')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="netAmount"
                                   style="text-align: right" name="netTotal"
                                   value="" readonly class="form-control"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group" id="paymentDiv">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Payment')?>
                                ( - )
                                :</strong></label>
                        <div class="col-md-7">
                            <input name="thisAllotment"
                                   id="thisAllotment" type="text"
                                   onkeyup="calcutateFinal(this.value)"
                                   class="form-control text-right payment"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   placeholder="0.00">

                        </div>
                    </div>

                    <div class="form-group creditDate" style="display:none;">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Due Amount')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" readonly
                                   class="form-control text-right"
                                   value="" id="currentDue"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Previous Due')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" readonly
                                   class="form-control text-right"
                                   value="" id="customerPreviousDue"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Total Due')?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" readonly
                                   class="form-control text-right"
                                   value="" id="totalDue"
                                   placeholder="0.00"/>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-md-12" style="">

        </div>
        <div class="clearfix"></div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info"
                        type="button">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo get_phrase('Update')?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <!--<button class="btn" onclick="showCylinder()" type="button">
                    <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                    Returned Cylinder
                </button>-->
            </div>
        </div>

    </form>

</div><!-- /.col -->
<!-- /.row -->

</body>
<script>
    var cylinderProduct;
    window.cylinderProduct = '<?php echo json_encode($cylinderProduct); ?>';
    var option = "";
    var slNo = 1;
    option += "<option value='" + '' + "'>---Select Name---</option>";
    $.each(JSON.parse(cylinderProduct), function (key, value) {
        if (value.category_id == 1) {
            option += "<option categoryName2='" + value.productCat + "' brand_id='" + value.brand_id + "' productName2='" + value.productName + ' [' + value.brandName + ']' + "' value='" + value.product_id + "'  >" + value.productName + ' [' + value.brandName + ' ]' + "</option>";
        }
    });

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
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
            }
        });
    }

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

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


    function getBankBranch(id) {
        $('#branchName').html('');
        if (id != '') {
            $.ajax({
                type: "POST",
                url: baseUrl + "getbankbranchList",
                data: 'bank_id=' + id,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    var option = "";
                    option += "<option value='" + '' + "'>---Select Name---</option>";
                    $.each(data, function (key, value) {
                        option += "<option   value='" + value.bank_branch_id + "'  >" + value.bank_branch_name + "</option>";
                    });
                    $('#branchName').html(option);
                }
            });
        }
    }


    $("#ReciveproductNameAutocomplete").autocomplete({
        source: function (request, response) {
            $.getJSON(baseUrl + "SalesController/get_product_list_by_dist_id", {term: request.term, receiveStatus: 1},
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
            } else {
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
            url: baseUrl + "getProductPriceForSale",
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
                    productName + ' [ ' + productBrandName + ' ] ' +
                    '<input id="ReciveproductID_' + productID + '" name="productID[]" value="' + productID + '" type="hidden">' +
                    '<input type="hidden"  name="product_id2[]" value="' + productID + '">' +
                    '</td>' +
                    '<td align="right">' +
                    '<input type="text" id="Reciveqty_' + productID + '" class="form-control text-right add_quantity2 decimal" value="' + givenQuantity + '" placeholder="' + quantity + '"  name="quantity2[]" value="">' +
                    '</td>' +
                    '<td>' +
                    '<a del_id2="' + productCatID + productID + '" class="delete_item2 btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 10px;margin-left: 8px;"></i>&nbsp;Remove</a>' +
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
        },
        open: function (e, ui) {
            // create the scrollbar each time autocomplete menu opens/updates

            $(".ui-autocomplete").mCustomScrollbar({
                setHeight: 182,
                theme: "minimal-dark",
                autoExpandScrollbar: true
                //scrollbarPosition:"outside"
            });
        },
        focus: function (e, ui) {
            //* scroll via keyboard
            if (!ui.item) {
                var first = $(".ui-autocomplete li:first");
                first.trigger("mouseenter");
                $(this).val(first.data("uiAutocompleteItem").label);
            }
            /*var el = $(".ui-state-focus").parent();
             if (!el.is(":mcsInView") && !el.is(":hover")) {
             $(".ui-autocomplete").mCustomScrollbar("scrollTo", el, {scrollInertia: 0, timeout: 0});
             }*/
        },
        close: function (e, ui) {
            // destroy the scrollbar each time autocomplete menu closes
            $(".ui-autocomplete").mCustomScrollbar("destroy");
        }

    });


    function addRowProduct(productID, productName, productCatID, productCatName, productBrandID, productBrandName, productUnit, unitName) {// quantity,returnQuantity, rate, price


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
            url: baseUrl + "getProductStock",
            data: 'product_id=' + productID,
            success: function (data) {
                // quantity = 2;
            }, complete: function () {

                var previousProductID = parseFloat($('#productID_' + productID).val());
                $.ajax({
                    type: "POST",
                    url: baseUrl + "getProductPriceForSale",
                    data: 'product_id=' + productID,
                    success: function (data) {
                        // quantity = 2;
                        if (data != '') {
                            //$('#rate_' + productID).val(data);
                        }
                    }, complete: function () {
                        var quantity = 2;

                        if (quantity > 0) {
                            var givenQuantity = 1;
                            var previousProductQuantity = parseInt($('#qty_' + productID).val());

                            if (previousProductID == productID) {
                                givenQuantity = givenQuantity + previousProductQuantity;
                                $('#qty_' + productID).val(givenQuantity);
                                //productTotal('_' + productID)
                                return true;
                            }

                            var tab = "";
                            if (productCatID == 2) {
                                //alert('OOOO');
                                tab = '<tr class="new_item' + productID + '">' +
                                    '<td style="padding-left:15px;" colspan="2">' +
                                    '[' + productCatName + ' ] - ' + productName + '&nbsp;' + unitName + '&nbsp;[&nbsp;' + productBrandName + '&nbsp;]&nbsp;' +
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
                                    '<td colspan="2">' +
                                    '<table class="table table-bordered table-hover" style="margin-bottom: 0px;" id="return_product_' + slNo + '">' +
                                    '<tr>' +
                                    '<td>' +
                                    '<select   class=" form-control returnedProduct_' + slNo + '" data-placeholder="Search by product name">' +
                                    option +
                                    '</select>' +
                                    '</td>' +
                                    '<td style="width: 40%">' +
                                    '<div class="input-group"><input type="text" class="form-control returnedProductQty_' + slNo + '" style="width: 80px;" /><a href="javascript:void(0)" id="' + slNo + '" class="AddreturnedProduct   input-group-addon"><i class="fa fa-plus"></i> </a> </div>' +
                                    '</td>' +
                                    '</tr>' +
                                    '</table>' +
                                    '</td>' +
                                    '<td>' +
                                    '<a del_id="' + productID + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 3px;margin-left: -2px;"></i>&nbsp;Remove</a>' +
                                    '</td>' +
                                    '</tr>';
                                $("#show_item_tbody").append(tab);

                            } else {

                                tab = '<tr class="new_item' + productID + '">' +
                                    '<td style="padding-left:15px;" colspan="2">' +
                                    '[' + productCatName + ' ] - ' + productName + '&nbsp;' + unitName + '&nbsp;[&nbsp;' + productBrandName + '&nbsp;]&nbsp;' +
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
                                    '</td>' +
                                    '<td>' +
                                    '</td>' +
                                    '<td>' +
                                    '<a del_id="' + productID + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 10px;margin-left: 8px;"></i>&nbsp;Remove</a>' +
                                    '</td>' +
                                    '</tr>';
                                $("#show_item_tbody").append(tab);

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
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });


    function isconfirm2() {
        var supplierid = $("#supplierid").val();
        var purchasesDate = $("#purchasesDate").val();
        var paymentType = $("#paymentType").val();
        var paymentType = $("#paymentType").val();
        var dueDate = $("#dueDate").val();
        var partialHead = $("#partialHead2").val();
        var thisAllotment = $("#thisAllotment").val();
        var bankName = $("#bankName").val();
        var branchName = $("#branchName").val();
        var checkNo = $("#checkNo").val();
        var checkDate = $("#checkDate").val();
        var cylinder = 0;

        var totalPrice = parseFloat($(".total_price").val());
        if (isNaN(totalPrice)) {
            totalPrice = 0;
        }
        if (supplierid == '') {
            swal("Select Supplier Name!", "Validation Error!", "error");
        } else if (purchasesDate == '') {
            swal("Select Purchases Date!", "Validation Error!", "error");
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
            swal("Add Purcahses Item !", "Validation Error!", "error");
        } else if (paymentType == 4 && partialHead == '') {
            swal("Select Account Head!", "Validation Error!", "error");
        } else if (paymentType == 4 && thisAllotment == '') {
            swal("Given Cash Amount!", "Validation Error!", "error");
        } else if (cylinder == 1 && cylinderItem <= 0) {


            swal("Add Return Cylinder Item or Close Return Cylinder!", "Validation Error!", "error");
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
<script type="text/javascript" src="<?php echo base_url('assets/purchases/purchasesEdit.js'); ?>"></script>
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
                        <form id="publicForm2" action="" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier
                                    ID </label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="supplierId" readonly
                                           value="<?php echo isset($supplierID) ? $supplierID : ''; ?>"
                                           class="form-control supplierId" placeholder="SupplierID"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier
                                    Name </label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="supName"
                                           class="form-control required supName" placeholder="Name" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" maxlength="11" id="form-field-1"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           onblur="checkDuplicatePhone(this.value)" name="supPhone" placeholder="Phone"
                                           class="form-control"/>
                                    <span id="errorMsg" style="color:red;display: none;"><i
                                                class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;Phone Number already Exits!!</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>
                                <div class="col-sm-6">
                                    <input type="email" id="form-field-1" name="supEmail" placeholder="Email"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                    Address</label>
                                <div class="col-sm-6">
                                    <!--<textarea id="editor1" cols="10" rows="5" name="comp_add"></textarea>-->
                                    <textarea cols="6" rows="3" placeholder="Type Address.." class="form-control"
                                              name="supAddress"></textarea>
                                </div>
                            </div>
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button onclick="saveNewSupplier()" id="subBtn2" class="btn btn-info" type="button">
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

