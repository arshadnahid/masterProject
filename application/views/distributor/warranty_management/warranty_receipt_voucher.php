<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 5/12/2020
 * Time: 9:46 AM
 */?>
<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 3/28/2020
 * Time: 3:48 PM
 */
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
<?php
$property_1=get_property_list_for_show_hide(1);
$property_2=get_property_list_for_show_hide(2);
$property_3=get_property_list_for_show_hide(3);
$property_4=get_property_list_for_show_hide(4);
$property_5=get_property_list_for_show_hide(5);

?>
<div class="row">
    <form id="publicForm" action="" method="post" class="form-horizontal">
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label class="col-sm-3 control-label text-right"
                       for="form-field-1"><span style="color:red;"> *</span> <?php echo get_phrase('Date') ?>
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input class="form-control date-picker-purchase" name="purchasesDate"
                               id="purchasesDate" type="text"
                               value="<?php echo date('d-m-Y'); ?>"
                               data-date-format="dd-mm-yyyy" required/>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <span style="color:red;"> *</span> <?php echo get_phrase('Supplier Id') ?> </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <select id="supplierid" name="supplierID"
                                onchange="getSupplierClosingBalance(this.value)"
                                class="chosen-select form-control" id="form-field-select-3"
                                data-placeholder="Search by Supplier ID or Name" required>
                            <option></option>
                            <?php foreach ($supplierList as $key => $each_info): ?>
                                <option value="<?php echo $each_info->sup_id; ?>"><?php echo $each_info->supName . ' [ ' . $each_info->supID . ' ] '; ?></option>
                            <?php endforeach; ?>

                        </select>
                        <span class="input-group-btn" id="hideNewSup">
                            <a data-toggle="modal" data-target="#myModal" class="btn blue btn-xs btn-success"
                               style="height:34px;"><i class="fa fa-plus"
                                                       style="margin-top: 9px;"></i>&nbsp;<?php echo get_phrase('New') ?></a>
                        </span>
                    </div>
                </div>

            </div>
            <div class="col-md-6" id="hideAccount" style="display: none;">
                <div class="form-group  ">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                        <span style="color:red;">*</span> <?php echo get_phrase('Pay_From_Cr') ?> </label>
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
            <div class="form-group" id="dueDateDiv">
                <label class="col-sm-3 control-label no-padding-right"
                       for="form-field-1"><span style="color:red;"> *</span> <?php echo get_phrase('Due Date') ?>
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input class="form-control date-picker" name="dueDate" id="dueDate"
                               type="text" value="<?php echo date('d-m-Y'); ?>"
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
                    <span style="color:red;"> *</span> <?php echo get_phrase('Payment Type') ?> </label>
                <div class="col-sm-7">
                    <select onchange="showBankinfo(this.value)" name="paymentType"
                            class="chosen-select form-control" id="paymentType"
                            data-placeholder="Select Payment Type" required>
                        <option></option>
                        <!--<option value="1" selected >Full Cash</option>-->
                        <option selected value="4"><?php echo get_phrase('Cash') ?></option>
                        <option value="2"><?php echo get_phrase('Credit') ?></option>
                        <option value="3"><?php echo get_phrase('Cheque_DD_PO') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="showAccount">
                <label class="col-sm-3 control-label text-right" for="form-field-1"><span
                        style="color:red;"> *</span><?php echo get_phrase('Account') ?></label>
                <div class="col-sm-7">
                    <select style="width:100%!important"
                            name="accountCrPartial"
                            class="chosen-select  checkAccountBalance"
                            id="partialHead2"
                            data-placeholder="Search by Account Head"
                            onchange="check_pretty_cash(this.value)">
                        <option value=""></option>
                        <option value=""></option>
                        <?php
                        foreach ($accountHeadList as $key => $head) {

                            ?>
                            <optgroup
                                label="<?php echo get_phrase($head['parentName']); ?>">
                                <?php
                                foreach ($head['Accountledger'] as $key2 => $eachLedger) :
                                    /*log_message('error','this is the account hade list'.print_r($eachLedger["parent_name"],true));*/
                                    ?>
                                    <option <?php
                                    if ($head['parent_id'] == '28') {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $eachLedger["id"]; ?>"><?php echo get_phrase($eachLedger["parent_name"]) . " ( " . $eachLedger["code"] . " ) "; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php

                        }
                        ?>
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
                    <?php echo get_phrase('Voucher Id') ?></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" id="form-field-1" name="userInvoiceId" value="" class="form-control"
                               placeholder="Voucher ID "/>
                        <span class="input-group-addon" style="background-color:#fff">
                            <?php echo $voucherID; ?>
                            <input type="hidden" id="" name="voucherid" readonly value="<?php echo $voucherID; ?>"/>
                        </span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <?php echo get_phrase('Reference') ?></label>
                <div class="col-sm-7">
                    <input type="text" id="form-field-1" name="reference" value=""
                           class="form-control" placeholder="Reference"/>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <?php echo get_phrase('Loader') ?></label>
                <div class="col-sm-7">
                    <select name="loader" class="chosen-select form-control"
                            id="form-field-select-3" data-placeholder="Search by Loader">
                        <option></option>
                        <?php foreach ($employeeList as $key => $eachEmp): ?>
                            <option value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">
                    <?php echo get_phrase('Transportation') ?></label>
                <div class="col-sm-7">
                    <select name="transportation" class="chosen-select form-control"
                            id="form-field-select-3"
                            data-placeholder="Search by Transportation">
                        <option></option>
                        <?php foreach ($vehicleList as $key => $eachVehicle): ?>
                            <option value="<?php echo $eachVehicle->id; ?>"><?php echo $eachVehicle->vehicleName . ' [ ' . $eachVehicle->vehicleModel . ' ]'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label text-right" for="form-field-1">

                    <?php echo get_phrase('Branch') ?></label>
                <div class="col-sm-7">
                    <select name="branch_id" class="chosen-select form-control"
                            id="BranchAutoId" data-placeholder="Select Branch">
                        <option value=""></option>
                        <?php
                        // come from branch_dropdown_helper
                        echo branch_dropdown(null,null);
                        ?>
                    </select>
                </div>
            </div>

        </div>


        <div class="col-md-12" id="showBankInfo" style="display:none;margin-top: 3px;">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4 formfonterp" for="form-field-1">
                        <strong><?php echo get_phrase('Bank_A/C') ?></strong></label>
                    <div class="col-sm-8">
                        <!--onchange="getBankBranch(this.value)"-->
                        <select name="bankName" class=" form-control" id="bankName"
                                data-placeholder="Bank Name">
                            <?php

                            echo bank_account_info_dropdown();
                            ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4 formfonterp"
                           style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check No') ?></strong></label>
                    <div class="col-sm-8">
                        <input type="text" value="" class="form-control" id="checkNo" name="checkNo" autocomplete="off"
                               placeholder="Check NO"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-sm-4  formfonterp"
                           style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check Date') ?></strong></label>
                    <div class="col-sm-8">
                        <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate"
                               type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"/>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-10" style="margin-top:10px">
            <div class="table-header">
                <?php echo get_phrase('Purchases Item') ?>
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
                    <th nowrap style="width:12%;border-radius:10px;" id="product_th">
                        <strong><?php echo get_phrase('Category') ?><span
                                style="color:red;"> *</span></strong>
                    </th>
                    <th nowrap style="width:172px" align="center" id="product_th">
                        <strong><?php echo get_phrase('Product') ?><span style="color:red;"> *</span></strong></th>
                    <th nowrap style="white-space:nowrap; width:100px; vertical-align:top;">
                        <strong><?php echo get_phrase('Quantity') ?> <span
                                style="color:red;"> *</span></strong></th>
                    <th nowrap style="display: none"><strong><?php echo get_phrase('Returnable_Qty') ?></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Unit_Price_Bdt') ?> <span
                                style="color:red;"> *</span></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Total_Price_Bdt') ?> <span
                                style="color:red;"> *</span></strong></th>
                    <th nowrap  style="text-align: center;width:17%;border-radius:10px;<?php echo $property_1 =='dont_have_this_property'?'display: none':''?>">
                        <strong><?php echo $property_1; ?> </strong>
                    </th>
                    <th nowrap  style="text-align: center;width:10%;border-radius:10px;<?php echo $property_2=='dont_have_this_property'?'display: none':''?> ">
                        <strong><?php echo $property_2; ?> </strong>

                    </th>
                    <th nowrap  style="text-align: center;width:10%;border-radius:10px; <?php echo $property_3=='dont_have_this_property'?'display: none':''?>">
                        <strong><?php echo $property_3; ?> </strong>
                    </th>
                    <th nowrap  style="text-align: center;width:10%;border-radius:10px; <?php echo $property_4=='dont_have_this_property'?'display: none':''?>">
                        <strong><?php echo $property_4; ?> </strong>
                    </th>
                    <th nowrap  style="text-align: center;width:10%;border-radius:10px;<?php echo $property_5=='dont_have_this_property'?'display: none':''?>">
                        <strong><?php echo $property_5; ?> </strong>
                    </th>
                    <th nowrap  style="text-align: center;width:30%;border-radius:10px;">
                        <strong><?php echo "Narration"; ?> </strong>
                    </th>
                    <th align="center"><strong><?php echo get_phrase('Action') ?></strong></th>
                </tr>
                </thead>
                <tbody id="show_item_tbody">
                <tr>


                    <td>
                        <select class="chosen-select form-control" data-placeholder="Select Category" id="CategorySelect2"
                                onchange="getProductList(this.value)">
                            <option></option>


                            <?php
                            $categoryArray = array('1', '2');
                            foreach ($productCat as $eachInfo) {

                                if (!in_array($eachInfo->category_id, $categoryArray)) {


                                    ?>
                                    <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                <?php }
                            } ?>

                        </select>
                    </td>
                    <td id="product_td">
                        <select id="productID"
                                onchange="getProductPrice(this.value)"
                                class="chosen-select form-control"
                                id=""
                                data-placeholder="Search by Product">
                            <option value=""></option>




                        </select>
                    </td>
                    <td><input type="hidden"
                               class="form-control text-right is_same decimal"
                               value="0"><input type="text"
                                                class="form-control text-right quantity decimal" onclick="this.select();" autocomplete="off"
                                                placeholder="0"></td>
                    <td style="display: none"><input type="text"
                                                     class="form-control text-right returnAble decimal" onclick="this.select();" autocomplete="off"
                                                     placeholder="0"></td>
                    <td><input type="text"
                               class="form-control text-right rate decimal" onclick="this.select();" autocomplete="off"
                               placeholder="0.00"></td>
                    <td><input type="text"
                               class="form-control text-right price decimal"
                               placeholder="0.00" readonly="readonly"></td>
                    <!--onchange="getProductPrice2(this.value)"-->
                    <td style="<?php echo $property_1 =='dont_have_this_property'?'display: none':''?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_1 "
                               placeholder="<?php echo $property_1;?>"/>
                    </td>
                    <td style="<?php echo $property_2 =='dont_have_this_property'?'display: none':''?>">

                        <input type="text" onclick="this.select();" class="form-control text-right property_2 "
                               placeholder="<?php echo $property_2;?>"/>
                    </td>
                    <td style="<?php echo $property_3 =='dont_have_this_property'?'display: none':''?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_3 "
                               placeholder="<?php echo $property_3;?>"/>
                    </td>
                    <td style="<?php echo $property_4=='dont_have_this_property'?'display: none':''?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_4 "
                               placeholder="<?php echo $property_4;?>"/>
                    </td>
                    <td style="<?php echo $property_5 =='dont_have_this_property'?'display: none':''?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_5 "
                               placeholder="<?php echo $property_5;?>"/>
                    </td>
                    <td style="">
                        <input type="text" onclick="" class="form-control text-right narration_of_product "
                               placeholder="Narration"/>
                    </td>

                    <td><a id="add_item" class="btn btn-info form-control"
                           href="javascript:;" title="Add Item"><i
                                class="fa fa-plus"
                                style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a>
                    </td>
                </tr>
                </tbody>
                <tfoot>
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
                        <?php echo get_phrase('Payment Calculation') ?>
                    </div>

                </div>
                <div class="portlet-body" style="padding:1px">
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="font-size:11px"><strong><?php echo get_phrase('Total') ?> :</strong></label>
                        <div class="col-md-7">

                            <input type="text" value="" class="form-control total_price text-right" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap;font-size:11px"><strong><?php echo get_phrase('Discount') ?>
                                (-)
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="discount" onclick="this.select();" autocomplete="off"
                                   onkeyup="calcutateFinal()"
                                   style="text-align: right" name="discount"
                                   value="" class="form-control"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Loader') ?>
                                ( + ):</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="loader" onclick="this.select();" autocomplete="off"
                                   onkeyup="calcutateFinal()"
                                   style="text-align: right" name="loaderAmount"
                                   value="" class="form-control"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px"
                               data-toggle="tooltip"
                               title="<?php echo get_phrase('Transportation') ?> (+) :"><strong><?php echo get_phrase('Trans') ?>
                                ..( + ):</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="transportation"
                                   onkeyup="calcutateFinal()" onclick="this.select();" autocomplete="off"
                                   style="text-align: right"
                                   name="transportationAmount" value=""
                                   class="form-control" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Net Total') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="netAmount"
                                   style="text-align: right" name="netTotal"
                                   value="" readonly class="form-control"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group" id="paymentDiv">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Payment') ?>
                                ( - )
                                :</strong></label>
                        <div class="col-md-7">
                            <input name="thisAllotment" onclick="this.select();" autocomplete="off"
                                   id="thisAllotment" type="text"
                                   onkeyup="calcutateFinal(this.value)"
                                   class="form-control text-right payment"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                   placeholder="0.00">

                        </div>
                    </div>

                    <div class="form-group creditDate" style="display:none;">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Due Amount') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" readonly
                                   class="form-control text-right"
                                   value="" id="currentDue"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Previous Due') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" readonly
                                   class="form-control text-right"
                                   value="" id="customerPreviousDue"
                                   placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Total Due') ?>
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
                    <?php echo get_phrase('Save') ?>
                </button>
                &nbsp; &nbsp; &nbsp;

            </div>
        </div>

    </form>

</div><!-- /.col -->
<!-- /.row -->

</body>
<script>
    var cylinderProduct;
    var purchaseRateLock;
    window.purchaseRateLock= '<?php echo  check_parmission_by_user_role( 3001)?>';

    window.cylinderProduct = '<?php echo json_encode($cylinderProduct); ?>';
    var option = "";
    var slNo = 1;
    option += "<option value='" + '' + "'>---Select Name---</option>";
    $.each(JSON.parse(cylinderProduct), function (key, value) {
        if (value.category_id == 1) {
            option += "<option categoryName2='" + value.productCat + "' brand_id='" + value.brand_id + "' productName2='" + value.productName + ' [' + value.brandName + ']' + "' value='" + value.product_id + "'  >" + value.productName + ' [' + value.brandName + ' ]' + "</option>";
        }
    });


    $(document).ready(function () {

        checkPurchaseRateLockPermission();

        //getBankBranch
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
    function checkPurchaseRateLockPermission(){

        if(purchaseRateLock!=0){

            $(".rate").attr("readonly", true);
            $(".add_rate").attr("readonly", true);
        }else{
            $(".rate").attr("readonly", false);
            $(".add_rate").attr("readonly", false);
        }
    }
    function getProductList(cat_id) {
        if (cat_id == 2) {
            $(".returnQuantity").attr("readonly", false);
        } else {
            $(".returnQuantity").attr("readonly", true);
        }
        $.ajax({
            type: "POST",
            url: baseUrl + "lpg/InvProductController/getProductList",
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
                url: baseUrl + "lpg/SalesController/getbankbranchList",
                //url: baseUrl + "getbankbranchList",
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
            url: baseUrl + "lpg/InvProductController/getProductPriceForSale",
            //url: baseUrl + "getProductPriceForSale",
            data: 'product_id=' + productID,
            success: function (data) {

                if (data != '') {

                }
            }, complete: function () {


                var givenQuantity = 1;
                var previousProductQuantity = parseInt($('#Reciveqty_' + productID).val());

                if (previousProductID == productID) {
                    givenQuantity = givenQuantity + previousProductQuantity;
                    $('#Reciveqty_' + productID).val(givenQuantity);

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
                    '<a del_id2="' + productCatID + productID + '" class="delete_item2 btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 10px;margin-left: 8px;"></i>&nbsp;</a>' +
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
            url: baseUrl + "lpg/InvProductController/getProductStock",
            //url: baseUrl + "getProductStock",
            data: 'product_id=' + productID,
            success: function (data) {
                // quantity = 2;
            }, complete: function () {

                var previousProductID = parseFloat($('#productID_' + productID).val());
                $.ajax({
                    type: "POST",
                    url: baseUrl + "lpg/InvProductController/getProductPriceForPurchase",
                    //url: baseUrl + "getProductPriceForSale",
                    data: 'product_id=' + productID,
                    success: function (data) {
                        // quantity = 2;
                        if (data != '') {
                            rate=data;
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
                                    '<td style="width:37%">' +
                                    '<div class="input-group"><input type="text" class="form-control returnedProductQty_' + slNo + '" style="" /><a href="javascript:void(0)" id="' + slNo + '" class="AddreturnedProduct   input-group-addon"><i class="fa fa-plus"></i> </a> </div>' +
                                    '</td>' +
                                    '</tr>' +
                                    '</table>' +
                                    '</td>' +
                                    '<td>' +
                                    '<a del_id="' + productID + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 3px;margin-left: -2px;"></i>&nbsp;</a>' +
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
                                    '<td align="right" style="display: none">' +
                                    '<input type="text" class="add_ReturnQuantity  text-right form-control decimal" name="returnQuantity[]" value="" readonly>' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input type="text" id="rate_' + productID + '" class="form-control add_rate text-right decimal" name="rate[]" value="' + '' + '" placeholder="0.00">' +
                                    '</td>' +
                                    '<td align="right">' +
                                    '<input readonly type="text" class="add_price text-right form-control" id="tprice_' + productID + '" name="price[]" value="' + price + '" autocomplete="off">' +
                                    '</td>' +
                                    '<td style="">' +
                                    '<input  type="text" class="add_ime_no text-right form-control" id="ime_no_' + productID + '" name="ime_no_[]" value="' + '' + '" autocomplete="off">' +
                                    '</td>' +
                                    '<td style="display: none">' +
                                    '</td>' +
                                    '<td>' +
                                    '<a del_id="' + productID + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 10px;margin-left: 8px;"></i>&nbsp;</a>' +
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
        }
        /* else if (paymentType == 3 && branchName == '') {
             swal("Type Branch Name!", "Validation Error!", "error");
         } */
        else if (paymentType == 3 && checkNo == '') {
            swal("Type Check No!", "Validation Error!", "error");
        } else if (paymentType == 3 && checkDate == '') {
            swal("Select Check Date!", "Validation Error!", "error");
        } else if (totalPrice == '' || totalPrice < 0) {
            swal("Add Purcahses Item!", "Validation Error!", "error");
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



    $(document).on('change', '.returnedProducted', function () {
        //$('.').on('change', function() {
        var product_id = $(this).val();
        var id = $(this).attr('id');
        //alert(product_id);
        //alert(id);
        received_cylilder_price(product_id, id);
    })

    function received_cylilder_price(product_id, id=0) {

        var productCatID = 1;
        var branchId = $('#BranchAutoId').val();


        if (branchId == '' || branchId == null) {
            swal("Select Branch", "Validation Error!", "error");
            //$(".quantity").attr('readonly', true);
            return false;
        }


        var ispackage = 0;


        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductPriceForPurchase',
            //url: baseUrl + 'lpg/InvProductController/getProductPriceForSale',
            data: 'product_id=' + product_id,
            success: function (data) {
                if (id == 0) {
                    $('.received_cylilder_price').val(parseFloat(data));
                    $('.received_cylilder_price').attr("placeholder", parseFloat(data));
                } else {
                    $('.returnedProductPrice_' + id).val(parseFloat(data));
                    $('.returnedProductPrice_' + id).attr("placeholder", parseFloat(data));
                }
            }
        });
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductStock',
            data: {product_id: product_id, category_id: productCatID, ispackage: ispackage, branchId: branchId},
            success: function (data) {


            }
        });
    }
    $(document).ready(function () {


        var j = 0;

        $("#add_item").click(function () {

            var productID = $('#productID').val();
            var package_id = $('#package_id').val();
            var package_id2 = $('#productID2').val();

            var property_1 = $('.property_1').val();
            var property_2 = $('.property_2').val();
            var property_3 = $('.property_3').val();
            var property_4 = $('.property_4').val();
            var property_5 = $('.property_5').val();
            var narration_of_product=$('.narration_of_product').val();

            var productCatID = $('#productID').find('option:selected').attr('categoryId');
            var productCatName = $('#productID').find('option:selected').attr('categoryName');
            var productCatName2 = $('#productID2').find('option:selected').attr('categoryName2');
            var productName = $('#productID').find('option:selected').attr('productName');
            var productName2 = $('#productID2').find('option:selected').attr('productName2');
            var ispackage = $('#productID').find('option:selected').attr('ispackage');
            var quantity = $('.quantity').val();
            var returnAble = $('.returnAble').val();
            var rate = $('.rate').val();
            var price = $('.price').val();
            var returnQuentity = $('.returnQuentity').val();


            if (quantity == '') {
                swal("Qty can't be empty.!", "Validation Error!", "error");
                return false;
            } else if (price == '' || price == '0.00') {
                swal("Price can't be empty.!", "Validation Error!", "error");
                return false;
            } else if (productID == '') {
                swal("Product id can't be empty.!", "Validation Error!", "error");
                return false;
            } else if (ispackage == 0) {
                //var productCatID = $('#productID').find('option:selected').attr('categoryId');
                // var productCatName = $('#productID').find('option:selected').attr('categoryName');
                //var productName = $('#productID').find('option:selected').attr('productName');
                var brand_id = $('#productID').find('option:selected').attr('brand_id');
                //var quantity = $('.quantity').val();
                //var returnAble = $('.returnAble').val();
                //var rate = $('.rate').val();
                //var price = $('.price').val();

                var received_cylilder_price = parseFloat($('.received_cylilder_price').val());
                if (quantity == '') {
                    swal("Qty can't be empty.!", "Validation Error!", "error");
                    return false;
                } else if (price == '' || price == '0.00') {
                    swal("Price can't be empty.!", "Validation Error!", "error");
                    return false;
                } else if (productID == '') {
                    swal("Product id can't be empty.!", "Validation Error!", "error");
                    return false;
                }else if (productCatID == 2 && received_cylilder_price <= 0 && received_cylilder_id != "") {
                    swal(" Received Cylilder Price ! ", "Validation Error!", "error");
                    return false;
                } else {
                    var tab;
                    if ($('.is_same').val() == 0) {
                        slNo++;
                    } else {
                        slNo;
                    }
                    if (productCatID == 2) {

                        /*if ($('.is_same').val() == 0) {


                            var returnedCylender = '';
                            if (returnQuentity > 0) {
                                returnedCylender = '<tr>' +
                                    '<td>' +
                                    '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                                    productName2 +
                                    '</td>' +
                                    '<td>' +
                                    '<div class="input-group"><input type="hidden" class="text-right form-control" id="" readonly name="returnQuentity_Price_' + slNo + '[]" value="' + received_cylilder_price + '"><input type="text" class="text-right form-control" id="" readonly name="returnQuentity_' + slNo + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +
                                    '</td>' +
                                    '</tr>' +
                                    '</tr>';
                            }

                            tab = '<tr class="new_item' + j + '">' +
                                '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                                '<input type="hidden" name="package_id_' + slNo + '" value="0"/>'+
                                '<input type="hidden" name="brand_id[]" value="' + brand_id + '"/>' +
                                '<input type="hidden" name="is_package_' + slNo + '" value="0">' +
                                '<input type="hidden" name="category_id_' + slNo + '" value="' + productCatID + '">' +
                                '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName + ' ' +
                                '<input type="hidden"  name="product_id_' + slNo + '" value="' + productID + '">' +
                                '</td>' +
                                '<td align="right">' +
                                '<input type="text" class="add_quantity decimal form-control text-right" id="qty_' + j + '" name="quantity_' + slNo + '" value="' + quantity + '">' +
                                '</td>' +
                                '<td align="right">' +
                                '<input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + j + '"   name="add_returnAble[' + slNo + ']" value="' + returnAble + '"  >' +
                                '</td>' +
                                '<td align="right">' +
                                '<input type="text" id="rate_' + j + '" class="add_rate form-control decimal text-right" name="rate_' + slNo + '" value="' + rate + '">' +
                                '</td>' +
                                '<td align="right">' +
                                '<input type="text" class="add_price  text-right form-control" id="tprice_' + j + '" readonly name="price[]" value="' + price + '">' +
                                '</td>' +
                                '<td colspan="2">' +
                                '<table class="table table-bordered table-hover" style="margin-bottom: 0px;" id="return_product_' + slNo + '">' +
                                '<tr>' +
                                '<td>' +
                                '<select   class=" form-control returnedProducted  returnedProduct_' + slNo + '" id="' + slNo + '" data-placeholder="Search by product name">' +
                                option +
                                '</select>' +
                                '</td>' +
                                '<td style="width:37%">' +
                                '<div class="input-group"><input type="hidden" class="form-control text-right returnedProductPrice_' + slNo + '" /><input type="text" class="form-control returnedProductQty_' + slNo + '" style="" /><a href="javascript:void(0)" id="' + slNo + '" class="AddreturnedProduct   input-group-addon"><i class="fa fa-plus"></i> </a> </div>' +
                                '</td>' +

                                '</tr>' + returnedCylender
                                +
                                '</table>' +
                                '</td>' +
                                '<td>' +
                                '<a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:void(0);" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
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
                                '<div class="input-group"><input type="hidden" class="text-right form-control" id="" readonly name="returnQuentity_Price_' + slNo + '[]" value="' + received_cylilder_price + '"><input type="text" class="text-right form-control" id="" readonly name="returnQuentity_' + slNo + '[]" value="' + returnQuentity + '"><a href="javascript:void(0)" id="2" class="remove_returnable  input-group-addon"><i class="fa fa-minus-circle "></i> </a> </div>' +

                                "</td>" +
                                "</tr>";

                            $("#return_product_" + slNo).append(tab2);

                        }*/
                    } else {


                        tab = '<tr class="new_item' + j + '">' +
                            '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                            '<input type="hidden" name="package_id_' + slNo + '" value="0"/>'+
                            '<input type="hidden" name="is_package_' + slNo + '" value="0">' +
                            '<input type="hidden" name="category_id_' + slNo + '" value="' + productCatID + '">' +
                            '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName + ' <input type="hidden"  name="product_id_' + slNo + '" value="' + productID + '">' +
                            '</td>' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" onclick="this.select();" class="add_quantity decimal form-control text-right" id="qty_' + j + '" name="quantity_' + slNo + '" value="' + quantity + '" autocomplete="off">' +
                            '</td>' +
                            '<td align="right" style="display: none"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + j + '"   name="add_returnAble[' + slNo + ']" value=""  readonly>' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" id="rate_' + j + '" class="add_rate form-control decimal text-right" name="rate_' + slNo + '" value="' + rate + '">' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" onclick="this.select();" class="add_price  text-right form-control" id="tprice_' + j + '" readonly name="price[]" value="' + price + '" autocomplete="off">' +
                            '</td>' +
                            '<td align="right" style="<?php echo $property_1 == 'dont_have_this_property'?'display: none':''?>" >' +
                            '<input  type="text" class="add_property_1 text-right form-control" id="property_1' + j + '" name="property_1_' + slNo + '" value="' + property_1 + '">' +
                            '</td>' +
                            '<td align="right" style="<?php echo $property_2 == 'dont_have_this_property'?'display: none':''?>" >' +
                            '<input  type="text" class="add_property_2 text-right form-control" id="property_2' + j + '" name="property_2_' + slNo + '" value="' + property_2 + '">' +
                            '</td>' +
                            '<td align="right" style="<?php echo $property_3 == 'dont_have_this_property'?'display: none':''?>" >' +
                            '<input  type="text" class="add_property_3 text-right form-control" id="property_3' + j + '" name="property_3_' + slNo + '" value="' + property_3 + '">' +
                            '</td>' +
                            '<td align="right" style="<?php echo $property_4 == 'dont_have_this_property'?'display: none':''?>" >' +
                            '<input  type="text" class="add_property_4 text-right form-control" id="property_4' + j + '" name="property_4_' + slNo + '" value="' + property_4 + '">' +
                            '</td>' +
                            '<td align="right" style="<?php echo $property_5 == 'dont_have_this_property'?'display: none':''?> ">' +
                            '<input  type="text" class="add_property_5 text-right form-control" id="property_5' + j + '" name="property_5_' + slNo + '" value="' + property_5 + '">' +
                            '</td>' +
                            '<td align="right" style="">' +
                            '<input  type="text" class="narration_of_product_input text-right form-control" id="property_5' + j + '" name="narration_of_product_' + slNo + '" value="' + narration_of_product + '">' +
                            '</td>' +
                            '<td>' +
                            '<a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
                            '</td>' +
                            '</tr>';
                        $("#show_item tfoot").append(tab);
                    }
                    j++;
                }

                if (productCatID == 2) {
                    $('.is_same').val('1');
                } else {
                    $('#productID').val('').trigger('chosen:updated');
                    $('.quantity').val('');
                    $('.is_same').val('0');
                    $('.rate').val('');
                    $('.price').val('');
                    $('.returnAble').val('');
                }

                findTotalCal();
                setTimeout(function () {
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
                            $("#show_item tfoot").append('<tr class="new_item' + slNo + '    packageDeleteRow_' + value['package_id'] + '"  ><input type="hidden" name="package_id_' + slNo + '" value="' +  value['package_id']  + '"/><input type="hidden" name="is_package_' + slNo + '" value="1"><input type="hidden" name="category_id_' + slNo + '" value="' + value['category_id'] + '">' +
                                '<td style="padding-left:15px;" colspan="2"> <input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>[ ' + value['title'] + '] - ' + value['productName'] + '&nbsp;' + value['unitTtile'] + '&nbsp;[ ' + value['brandName'] + " ]" +
                                ' <input type="hidden"  name="product_id_' + slNo + '" value="' + value['product_id'] + '"></td>' +
                                '</td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" id="qty_' + j + '" name="quantity_' + slNo + '" value="' + quantity + '"></td><td align="right"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + j + '"   name="add_returnAble[]" value=""  readonly></td><td align="right"><input type="text" id="rate_' + j + '" class="add_rate form-control decimal text-right" name="rate_' + slNo + '" value="' + rate + '" onclick="this.select();"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tprice_' + j + '" readonly name="price[]" value="' + price + '"></td><td></td><td></td><td style="' + style + '"   rowspan="' + rowspan + '"><a del_id="' + slNo + '"package_id_delete="' + value['package_id'] + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');
                            j++;
                            style = 'display:none';
                            //rate='';

                        });

                    }, complete: function () {
                        findTotalCal();
                        setTimeout(function () {
                            calcutateFinal();
                        }, 100);
                    }
                });


                $('.quantity').val('');
                $('.rate').val('');
                $('.price').val('');
                $('.returnAble').val('');


            }
            checkPurchaseRateLockPermission();
            $('#CategorySelect2').val('').trigger('chosen:updated');
            $('#productID').val('').trigger('chosen:updated');
            $('#productID2').val('').trigger('chosen:updated');
            $('.quantity').val('');
            $('.is_same').val('0');
            $('.rate').val('');
            $('.price').val('');
            $('.returnAble').val('');
            $('.returnQuentity').val('');


            $('.property_1').val('');
            $('.property_3').val('');
            $('.property_3').val('');
            $('.property_4').val('');
            $('.property_5').val('');
            $('.narration_of_product').val('');
            //$('#category_product').val('').trigger('chosen:updated');
            //$('#productUnit').val('').trigger('chosen:updated');

        });

    });

</script>
<script type="text/javascript" src="<?php echo base_url('assets/purchases_mobile/purchasesAdd.js'); ?>"></script>
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



