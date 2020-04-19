
<div class="main-content" >
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Purchases Edit</li>
                <li class="active"> <span style="color:red;"> * </span> <span style="color:red">Mark field must be fill up</span></li>
            </ul>
            <ul class="breadcrumb pull-right">

                <li>
                    <a class="inventoryAddPermission" href="<?php echo site_url('purchases_add'); ?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Add
                    </a>
                </li>
                <li>
                    <a href="<?php echo site_url('purchases_list'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
                <li>
                    <a class="inventoryEditPermission" href="<?php echo site_url('viewPurchases/' . $editPurchases->generals_id); ?>">
                        <i class="ace-icon fa fa-search-plus bigger-130"></i> View
                    </a>
                </li>
                <li>
                    <a class="inventoryEditPermission" href="<?php echo site_url('viewPurchasesWithCylinder/' . $editPurchases->generals_id); ?>">
                        <i class="ace-icon fa fa-search-plus bigger-130"></i> With Cylinder
                    </a>
                </li>

            </ul>
        </div>
        <div class="page-content">

            <div class="row">
                <form id="publicForm" action=""  method="post" class="form-horizontal">
                    <table class="mytable  table table-bordered">
                        <tr>
                            <td  style="padding: 10px!important;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Supplier ID <span style="color:red;">*</span></label>
                                        <div class="col-sm-6">
                                            <select  id="supplierid" name="supplierID" onchange="getSupplierClosingBalance(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Supplier ID or Name" required>
                                                <option></option>
                                                <?php foreach ($supplierList as $key => $each_info): ?>
                                                    <option <?php
                                                if ($editPurchases->supplier_id == $each_info->sup_id) {
                                                    echo "selected";
                                                }
                                                    ?> value="<?php echo $each_info->sup_id; ?>"><?php echo $each_info->supID . ' [ ' . $each_info->supName . ' ] '; ?></option>
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
                                            <input type="text" id="form-field-1" name="userInvoiceId"  value="<?php echo $editPurchases->mainInvoiceId; ?>" class="form-control" placeholder="Voucher ID "/>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" id="form-field-1" name="voucherid" readonly value="<?php echo $editPurchases->voucher_no; ?>" class="form-control" placeholder="Product Code" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Reference</label>
                                        <div class="col-sm-6">
                                            <input type="text" id="form-field-1" name="reference"  value="<?php echo $editPurchases->reference; ?>" class="form-control" placeholder="Reference" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Purchases Date<span style="color:red;"> *</span></label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input class="form-control date-picker" name="purchasesDate" id="purchasesDate" type="text" value="<?php echo date('d-m-Y', strtotime($editPurchases->date)); ?>" data-date-format="dd-mm-yyyy" required/>
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
                                                    <option <?php
                                                if ($editPurchases->loader == $eachEmp->id): echo "selected";
                                                endif;
                                                    ?> value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; ?></option>
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
                                                    <option <?php
                                                if ($editPurchases->transportation == $eachVehicle->id) {
                                                    echo "selected";
                                                }
                                                    ?> value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Payment Type<span style="color:red;"> *</span></label>
                                        <div class="col-sm-6">
                                            <select onchange="showBankinfo(this.value)"  name="paymentType"  class="chosen-select form-control" id="paymentType" data-placeholder="Select Payment Type" required>
                                                <option></option>
                                                <!--<option value="1" selected >Full Cash</option>-->
                                                <option <?php
                                                    if ($editPurchases->payType == 4) {
                                                        echo "selected";
                                                    }
                                                    ?> value="4">Cash</option>
                                                <option <?php
                                                    if ($editPurchases->payType == 2) {
                                                        echo "selected";
                                                    }
                                                    ?>  value="2" >Credit</option>
                                                <option <?php
                                                    if ($editPurchases->payType == 3) {
                                                        echo "selected";
                                                    }
                                                    ?>  value="3">Cheque/ DD / PO </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="hideAccount"  style="display: none;">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Pay. From( CR ) <span style="color:red;">*</span></label>
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
                                                            <option value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
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
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Due Date <span style="color:red;">*</span> </label>
                                        <div class="col-sm-7">

                                            <div class="input-group">
                                                <input class="form-control date-picker" name="dueDate" id="dueDate" type="text" value="<?php echo date('d-m-Y', strtotime($editPurchases->dueDate)); ?>" data-date-format="dd-mm-yyyy" />
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6" id="showBankInfo" style="display:none;">
                                    <div class="form-group">
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
                                                <input class="form-control date-picker" id="checkDate" name="checkDate" name="purchasesDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </td>
                        </tr>
                        <tr>
                            <td  style="padding: 10px!important;">
                                <div class="col-md-12">

                                    <div class="col-md-8">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="table-header">
                                                    Purchases Item
                                                </div>
                                                <table class="table table-bordered table-hover" id="show_item">
                                                    <thead>
                                                        <tr>
                                                            <td style="width:40%" align="center"><strong>Product<span style="color:red;"> *</span></strong></td>
                                                            <td style="width:20%" align="center"><strong>Quantity<span style="color:red;"> *</span></strong></td>
                                                            <td nowrap style="width:10%;" align="center"><strong>Returnable Cylinder(Qty)</strong></td>
                                                            <td nowrap style="width:10%;" align="center"><strong>Unit Price(BDT)<span style="color:red;"> *</span> </strong></td>
                                                            <td nowrap style="width:15%;" align="center"><strong>Total Price(BDT)</strong></td>
                                                            <td style="width:10%" align="center"><strong>Action</strong></td>
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
                                                                                if ($productPreFix != 'Empty'):
                                                                                    ?>
                                                                                    <option categoryName="<?php echo $eachProduct['categoryName']; ?>" categoryId="<?php echo $eachProduct['categoryId']; ?>" productName="<?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?>" value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?></option>
                                                                                    <?php
                                                                                endif;
                                                                            endforeach;
                                                                            ?>
                                                                        </optgroup>
                                                                        <?php
                                                                    endforeach;
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control text-right quantity decimal"  placeholder="0"></td>
                                                            <td><input type="text" class="form-control text-right returnAble decimal"   placeholder="0"></td>
                                                            <td><input type="text" class="form-control text-right rate decimal" placeholder="0.00"  ></td>
                                                            <td><input type="text" class="form-control text-right price decimal" placeholder="0.00" readonly="readonly"></td>
                                                            <td><a id="add_item" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                                        </tr>
                                                    </tbody>

                                                    <tfoot>

                                                        <?php
                                                        $totalEditPrice = 0;
                                                        foreach ($stockListEdit[2] as $key => $eachStock) :

                                                             ?>
                                                            <tr class="new_item<?php echo $key + 767; ?>">
                                                                <td>
                                                                    <input type="hidden" name="category_id[]" value="<?php echo $eachStock['category_id']; ?>">
                                                                    <input type="hidden" name="product_id[]" value="<?php echo $eachStock['product_id']; ?>">

                                                                    <?php echo '  [ ' . $eachStock['category'] . ' ] - ' . $eachStock['productName'] . ' [ ' . $eachStock['brandName'] . ' ] ';?>
                                                                </td>
                                                                <td>

                                                                    <input type="text" class="add_quantity form-control text-right decimal" id="qty_<?php echo $key + 767 ?>" name="quantity[]" value="<?php echo $eachStock['quantity'
                                                                    ]; ?>">

                                                                </td>


                                                            </tr>



                                                            <?php






                                                            if ($eachStock->type == 'In') {
                                                                $returnQtyedit = $this->Inventory_Model->getReturnAbleCylinder2($this->dist_id, $eachStock->generals_id, $eachStock->product_id);
                                                                ?>

                                                                <tr >

                                                            <td style="padding-left:15px;">
                                                                <?php
                                                                $cateogry = $this->Common_model->tableRow('productcategory', 'category_id', $eachStock->category_id)->title;
                                                                $productInfo = $this->Common_model->tableRow('product', 'product_id', $eachStock->product_id);
                                                                $brandInfo = $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id);

                                                                ?>

                                                            </td>

                                                            <td align="right"></td>
                                                            <td align="right"><input type="text" <?php
                                                        if ($eachStock->category_id == 2) {
                                                            echo "readonly";
                                                        }
                                                                ?> class="add_return form-control text-right decimal" id="qtyReturn_<?php echo $key + 767 ?>" name="add_returnAble[]" value="<?php echo $returnQtyedit->quantity; ?>"></td>
                                                            <td align="right"><input type="text" class="add_rate decimal text-right form-control" id="rate_<?php echo $key + 767 ?>" name="rate[]" value="<?php echo $eachStock->rate; ?>"></td>
                                                            <td align="right"><?php
                                                                             $totalEditPrice+=$eachStock->price;
                                                                ?><input type="text" readonly class="add_price text-right form-control" id="tprice_<?php echo $key + 767 ?>" name="price[]" value="<?php echo $eachStock->price; ?>"></td>
                                                            <td><a del_id="<?php echo $key + 767; ?>" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    endforeach;
                                                    ?>
<!--                                                    <tr>
                                                        <td align="right" ><strong>Total(BDT)</strong></td>
                                                        <td align="right"><strong class="total_quantity"></strong></td>
                                                        <td align="right"><strong class="totalReturnQty"></strong></td>
                                                        <td align="right"><strong class="total_rate"></strong></td>

                                                        <td><input type="text" readonly class="form-control" value="" id="supplierCurrentBal"/></td>
                                                    </tr>-->
                                                    </tfoot>
                                                </table>
                                                <table class="table table-bordered table-hover table-success">
                                                    <tr>
                                                        <td>
                                                            <textarea style="border:none;" cols="120"  class="form-control" name="narration" placeholder="Narration......" type="text"><?php echo $editPurchases->narration; ?></textarea>
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
                                                <table  class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td nowrap align="right"><strong>Total (BDT)</strong></td>
                                                            <td align="right"><input type="text" value="<?php echo $totalEditPrice.'.00'; ?>" class="text-right form-control total_price" readonly /></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Discount ( - )</strong></td>
                                                            <td><input type="text" id="discount" onkeyup="calcutateFinal()"   style="text-align: right" name="discount" value="<?php echo $editPurchases->discount; ?>"  class="form-control"  placeholder="0.00"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Loader ( + )</strong></td>
                                                            <td><input type="text" id="loader" onkeyup="calcutateFinal()"   style="text-align: right" name="loaderAmount" value="<?php echo $editPurchases->loaderAmount; ?>"  class="form-control"  placeholder="0.00"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Transportation ( + )</strong></td>
                                                            <td><input type="text" id="transportation" onkeyup="calcutateFinal()"   style="text-align: right" name="transportationAmount"  value="<?php echo $editPurchases->transportationAmount; ?>"  class="form-control"  placeholder="0.00"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Net Total</strong></td>
                                                            <td><input type="text" id="netAmount"  style="text-align: right" name="netTotal" value="<?php echo ($editPurchases->loaderAmount + $totalEditPrice + $editPurchases->transportationAmount); ?>" readonly class="form-control"  placeholder="0.00"/></td>
                                                        </tr>
                                                        <tr class="partialPayment" >
                                                            <td nowrap><strong>Add Account </strong><span style="color:red;"> * </span></td>
                                                            <td>
                                                                <select style="width:100%!important"  name="accountCrPartial" class="chosen-select  checkAccountBalance" id="partialHead2" data-placeholder="Search by Account Head"  onchange="check_pretty_cash(this.value)">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    foreach ($accountHeadList as $key => $head) {
                                                                        // if ($key == 42 || $key == 45 || $key == 55) {
                                                                        ?>
                                                                        <optgroup label="<?php echo $head['parentName']; ?>">
                                                                            <?php
                                                                            foreach ($head['Accountledger'] as $eachLedger) :
                                                                                ?>
                                                                                <option <?php
                                                                        if (!empty($accountIdForEdit) && $accountIdForEdit == $eachLedger->chartId) {
                                                                            echo "selected";
                                                                        } else {
                                                                            if ($eachLedger->chartId == '54') {
                                                                                echo "selected";
                                                                            }
                                                                        }
                                                                                ?> value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                                                                <?php endforeach; ?>
                                                                        </optgroup>
                                                                        <?php
                                                                        //}
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <!--<td><input type="text" name="dueAllotment" class="form-control text-right dueAmount" placeholder="0.00" readonly="readonly"></td>-->
                                                        </tr>
                                                        <tr  class="partialPayment">
                                                            <td nowrap  align="right"><strong>Payment ( - ) <span style="color:red;"> * </span></strong></td>
                                                            <td  align="right"><input name="thisAllotment" id="thisAllotment" type="text" onkeyup="calcutateFinal(this.value)" value="<?php echo $creditAmount2->credit; ?>" class="form-control text-right payment" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" placeholder="0.00"></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap align="right"><strong>Due Amount</strong></td>
                                                            <td  align="right"><input id="currentDue" type="text" onkeyup="calculateDueAmount(this.value)" readonly value="" class="form-control text-right payment" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" placeholder="0.00"></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Previous Due</strong></td>
                                                            <td align="right"><input readonly id="customerPreviousDue" readonly type="text" onkeyup="calculateDueAmount(this.value)" value="" class="form-control text-right payment" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" placeholder="0.00"></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap  align="right"><strong>Total Due</strong></td>
                                                            <td align="right"><input id="totalDue" type="text" readonly onkeyup="calculateDueAmount(this.value)" value="" class="form-control text-right payment" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" placeholder="0.00"></td>
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
                                <div id="culinderReceive">
                                    <div class="table-header">
                                        Returned Cylinder Item
                                    </div>
                                    <table class="table table-bordered table-hover" id="show_item2">
                                        <thead>
                                            <tr>
    <!--                                            <th style="width:18%"  align="center"><strong>Product Category</strong></th>-->
                                                <th style="width:18%" align="center"><strong>Product <span style="color:red;"> *</span> </strong></th>
                                                <th style="width:17%" align="center"><strong>Returned Cylinder (Qty) <span style="color:red;"> *</span></strong></th>
                                                <th style="width:15%" align="center"><strong>Action</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalReturn = 0;
                                            if (!empty($cylinserOut)):
                                                foreach ($cylinserOut as $serial => $eachStock):
                                                    $totalReturn+=$eachStock->quantity;
                                                    ?>
                                                    <tr class="new_item2<?php echo $serial + 77 ?>">
                                                <input type="hidden" name="category_id2[]" value="2">
                                                <td style="padding-left:15px;"><?php echo $eachStock->productName . ' [ ' . $eachStock->brandName . ' ] ' ?><input type="hidden" name="product_id2[]" value="<?php echo $eachStock->product_id; ?>"></td>
                                                <td align="right"><?php echo $eachStock->quantity; ?><input type="hidden" class="add_quantity2" name="quantity2[]" value="<?php echo $eachStock->quantity; ?>"></td>
                                                <td><a del_id2="<?php echo $serial + 77 ?>" class="delete_item2 btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;Remove</a></td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <select  id="productID2" onchange="getProductPrice2(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by product name">
                                                        <option value=""></option>
                                                        <?php
                                                        foreach ($cylinderProduct as $eachProduct):
                                                            $productPreFix = substr($eachProduct->productName, 0, 5);
                                                            if ($productPreFix == 'Empty'):
                                                                ?>
                                                                <option productName2="<?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ']'; ?>" value="<?php echo $eachProduct->product_id; ?>">
                                                                    <?php echo $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] '; ?>
                                                                </option>
                                                                <?php
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </td>
                                                <td><input type="hidden" value="" id="stockQty2"/><input type="text"  onkeyup="checkStockOverQty2(this.value)" class="form-control text-right quantity2" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="0"></td>
                                                <td><a id="add_item2" class="btn btn-info form-control" href="javascript:;" title="Add Item"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Item</a></td>
                                            </tr>
                                            <tr>
                                                <td align="right" ><strong>Sub-Total(BDT)</strong></td>
                                                <td align="right"><strong class="total_quantity2"><?php echo $totalReturn; ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td  style="padding: 10px!important;">
                                <div class="clearfix"></div>
                                <div class="clearfix form-actions" >
                                    <div class="col-md-offset-3 col-md-9">
                                        <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="button">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Update
                                        </button>
                                        &nbsp; &nbsp; &nbsp;
                                        <button class="btn" onclick="showCylinder()" type="button">
                                            <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                                            Returned Cylinder
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>

<script>

    $(document).ready(function () {
        calcutateFinal();

        var payType = $('#paymentType').val();
        showBankinfo(payType);
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
        if ($("#culinderReceive").css('display') == 'none') {
            cylinder=0;
        }else{
            var cylinderItem=parseFloat($(".total_quantity2").text());
            if(isNaN(cylinderItem)){
                cylinderItem=0;
            }else{
                cylinder=1;
            }


        }
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
        }else if((cylinder == 1 && cylinderItem < 0) || cylinderItem ==0){
            swal("Add Return Cylinder Item Or Close Return Cylinder!", "Validation Error!", "error");
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

<script type="text/javascript" src="<?php echo base_url('assets/purchases/purchasesEdit.js'); ?>"></script>
<script>

<?php if (!empty($cylinserOut)): ?>
        setTimeout(function() {
            $('#culinderReceive').show();
        }, 2000);
<?php else:
    ?>
            setTimeout(function() {
                $('#culinderReceive').hide();
            }, 2000);

<?php endif; ?>


<?php if ($editPurchases->payType == 4) { ?>

        setTimeout(function() {
            $(".partialPayment").show(10);

        }, 2000);
<?php } elseif ($editPurchases->payType == 3) { ?>

        $("#showBankInfo").show(10);
<?php } elseif ($editPurchases->payType == 2) { ?>

        setTimeout(function() {
            $(".partialPayment").hide(10);
        }, 2000);

        $("#showAccount").show(10);
<?php } ?>


</script>