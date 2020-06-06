<style type="text/css">
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


<?php
$property_1 = get_property_list_for_show_hide(1);
$property_2 = get_property_list_for_show_hide(2);
$property_3 = get_property_list_for_show_hide(3);
$property_4 = get_property_list_for_show_hide(4);
$property_5 = get_property_list_for_show_hide(5);
$salesInvoiceCreditLimitBlock = 4000;
$conditionsalesInvoiceCreditLimitBlock = array(
    'user_role' => $this->admin_id,
    'navigation_id' => $salesInvoiceCreditLimitBlock//'2112',
);
$salesInvoiceCreditLimitBlockPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $conditionsalesInvoiceCreditLimitBlock);

?>
<div class="row">

    <div class="col-sm-7">
        <?php
        foreach ($customer_money_revcive as $key => $value) {
            $customer_money_revcive_info = array();
            $customer_money_revcive_info = explode("&^&", $key);

            ?>
            <form id="billTobillForm_<?php echo $customer_money_revcive_info[0] ?>"
                  action="<?php echo base_url() ?>lpg/SalesInvoiceEditController/delete_bill_to_bill_collection"
                  method="post" class="form-horizontal" name="billTobillForm_<?php echo $customer_money_revcive_info[0] ?>">

                <table class="table table-bordered table-striped table-condensed flip-content">

                    <thead>

                    <tr>
                        <td class="text-center">
                            <strong>
                                <?php echo get_phrase('Voucher No') ?>:
                                <?php echo get_phrase($customer_money_revcive_info[1]) ?>

                            </strong>
                        </td>

                        <td class="text-center">
                            <strong>
                                <?php echo get_phrase('Date') ?>:
                                <?php echo get_phrase($customer_money_revcive_info[2]) ?>

                            </strong>
                        </td>

                        <td class="text-right">
                            <input type="text" name="due_collection_info_id" id="due_collection_info_id"
                                   value="<?php echo $customer_money_revcive_info[0] ?> "/>
                            <input type="text" name="invoice_id" id="invoice_id"
                                   value="<?php echo $editInvoice->sales_invoice_id ?> "/>

                            <button onclick="return isconfirm3()" id="" class="btn btn-xs red" type="button">
                                <i class="fa fa-trash-o bigger-130"></i>
                                <?php echo get_phrase('Save') ?>
                            </button>

                        </td>

                    </tr>
                    <tr>
                        <td class="text-center"><?php echo get_phrase('Sales_invoice_no') ?>
                        </td>
                        <td class="text-center"><?php echo get_phrase('Sales_invoice_amount') ?>
                        </td>
                        <td class="text-center"><?php echo get_phrase('paid_amount') ?>
                        </td>
                    </tr>


                    </thead>
                    <tbody>

                    <?php foreach ($value as $key1 => $value1) { ?>
                        <tr>
                            <td class="text-center">
                                <input type="text" name="due_collection_details_id[]"
                                       value="<?php echo $value1->cus_due_collection_details_id ?> "/>
                                <input type="text" name="sales_invoice_id[]"
                                       value="<?php echo $value1->sales_invoice_id ?> "/>
                                <input type="text" name="invoice_no[]" value="<?php echo $value1->invoice_no ?> "/>

                                <?php echo $value1->invoice_no ?>
                            </td>
                            <td class="text-center"><?php echo $value1->invoice_amount ?>
                            </td>
                            <td class="text-center"><?php echo $value1->paid_amount ?>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>


                </table>
            </form>
        <?php } ?>

    </div>

</div>
<div class="row">


    <form id="publicForm" action="" method="post" class="form-horizontal" name="publicForm">
        <div class="col-sm-12 col-md-4" style="margin-top: 10px;">
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <span
                            style="color:red;">*</span><strong><?php echo get_phrase('Sales_Date') ?></strong></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input class="form-control date-picker" name="saleDate" id="saleDate" type="text"
                               value="<?php echo date('d-m-Y', strtotime($editInvoice->invoice_date)); ?>"
                               data-date-format="dd-mm-yyyy" autocomplete="off"/>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <span
                            style="color:red;">*</span><strong>Customer Name</strong></label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <select id="customerid" name="customer_id" class="chosen-select form-control"
                                onchange="getCustomerCurrentBalace(this.value)"
                                data-placeholder="Select Customer Name">
                            <option></option>
                            <?php foreach ($customerList as $key => $each_info): ?>
                                <option

                                    <?php
                                    if ($editInvoice->customer_id == $each_info->customer_id) {
                                        echo "selected";
                                    }
                                    ?>
                                        value="<?php echo $each_info->customer_id; ?>"><?php echo $each_info->customerName . '&nbsp&nbsp[ ' . $each_info->typeTitle . ' ] '; ?></option>

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
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <strong>Reference</strong></label>
                <div class="col-sm-7">

                    <select name="reference" class="chosen-select form-control" id="form-field-select-3"
                            data-placeholder="Select Reference Name">
                        <option></option>
                        <?php foreach ($referenceList as $key => $each_ref): ?>
                            <option
                                <?php
                                if ($editInvoice->refference_person_id == $each_ref->reference_id) {
                                    echo "selected";
                                }
                                ?>
                                    value="<?php echo $each_ref->reference_id; ?>"><?php echo $each_ref->referenceName; ?></option>
                        <?php endforeach; ?>
                    </select>


                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <strong>Customer
                        Info</strong></label>
                <div class="col-sm-7">
                    <table class="table table-bordered table-striped table-condensed flip-content">
                        <!--<thead>
                        <tr>
                            <th class="text-center">Cylinder</th>
                            <th class="text-center">Quantity</th>
                        </tr>
                        </thead>-->
                        <tbody>
                        <tr>
                            <td class="text-right" style="width: 42%;"><strong><?php echo get_phrase('Address') ?>
                                </strong></td>
                            <td>:</td>
                            <td class="text-left"><span id="customer_address_span"> </span></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="width: 42%;"><strong><?php echo get_phrase('Phone') ?>
                                </strong></td>
                            <td>:</td>
                            <td class="text-left"><span id="customer_phone_span"> </span></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="width: 42%;"><strong
                                        id="previousDueText"><?php echo get_phrase('Due') ?></strong>
                            </td>
                            <td>:</td>
                            <td class="text-left">
                                <span style="display: none" id="previousDue">0.00</span>
                                <span style="" id="previousDueSpan">0.00</span>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-right" style="width: 42%;"><strong
                                        id=""><?php echo get_phrase('Credit Limit') ?></strong>
                            </td>
                            <td>:</td>
                            <td class="text-left">

                                <input type="text" value="0" id="CreditLimit" readonly style="width: 77%;border: 0px;"/>
                            </td>

                        </tr>
                        </tbody>
                    </table>




                </div>
            </div>

        </div>
        <div class="col-md-4" style="margin-top: 10px;">

            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"
                       style="white-space: nowrap;"><strong><?php echo get_phrase('Delivery_Address') ?></strong></label>
                <div class="col-sm-7">
                    <input class="form-control" placeholder="Delivery Address" name="shippingAddress"
                           value="<?php echo $editInvoice->delivery_address ?>"
                           autocomplete="off"/>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" for="form-field-1"
                       style="white-space: nowrap;"><strong><?php echo get_phrase('Delivery_Date') ?></strong></label>
                <div class="col-sm-7">

                    <?php
                    $delivery_date = "";

                    if ($editInvoice->delivery_date != "0000-00-00" && $editInvoice->delivery_date != 'NULL') {
                        $delivery_date = date('d-m-Y', strtotime($editInvoice->delivery_date));
                    }


                    ?>
                    <input class="form-control date-picker"
                           value="<?php echo $delivery_date ?>"
                           data-date-format="dd-mm-yyyy" autocomplete="off" placeholder="Delivery Date"
                           name="delivery_date"/>
                </div>
            </div>
            <div class="clearfix"></div>
            <!--creditDate-->
            <div class="form-group ">
                <label class="col-sm-3 control-label formfonterp"
                       style="white-space: nowrap; "><strong><?php echo get_phrase('Due_Date') ?>
                        :</strong></label>
                <div class="col-md-7">
                    <!--id="dueDate"-->

                    <?php
                    $due_date = "";

                    if ($editInvoice->due_date != "0000-00-00" && $editInvoice->due_date != 'NULL') {
                        $due_date = date('d-m-Y', strtotime($editInvoice->due_date));
                    }


                    ?>
                    <input class="form-control date-picker" name="creditDueDate"
                           style=""
                           type="text" value="<?php echo $due_date ?>" data-date-format="dd-mm-yyyy"
                           autocomplete="off"/>

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label formfonterp" style="white-space: nowrap;" for="form-field-1"><span
                            style="color:red;">*</span><strong><?php echo get_phrase('Payment_Type') ?></strong></label>
                <div class="col-sm-7">
                    <select onchange="showBankinfo(this.value)" name="paymentType" class="chosen-select form-control"
                            id="paymentType" data-placeholder="Select Payment Type">
                        <option></option>

                        <option
                            <?php
                            if ($editInvoice->payment_type == 4) {
                                echo "selected";
                            }
                            ?> value="4"
                        ><?php echo get_phrase('Cash') ?></option>
                        <option <?php
                        if ($editInvoice->payment_type == 2) {
                            echo "selected";
                        }
                        ?> value="2"><?php echo get_phrase('Credit') ?></option>
                        <option <?php
                        if ($editInvoice->payment_type == 3) {
                            echo "selected";
                        }
                        ?> value="3"><?php echo get_phrase('Cheque_DD_PO') ?></option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group partisals">
                <label class="col-sm-3 control-label no-padding-right formfonterp" for="form-field-1"><span
                            style="color:red;"> *</span><strong><?php echo get_phrase('Account') ?></strong></label>
                <div class="col-sm-7">
                    <select style="width:100%!important;" name="accountCrPartial"
                            class="chosen-select   checkAccountBalance" id="partialHead"
                            data-placeholder="Select Account Head">
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
                                    if ($eachLedger["id"] == $sale_invoice_cash_ledger_id) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $eachLedger["id"]; ?>"><?php echo get_phrase($eachLedger["parent_name"]) . " ( " . $eachLedger["code"] . " ) "; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php

                        }
                        ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="col-md-4" style="margin-top: 10px;">
            <div class="form-group">
                <label class="col-sm-4 control-label text-right formfonterp"
                       for="form-field-1"><strong> <?php echo get_phrase('Invoice_No') ?>.</strong></label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="text" name="userInvoiceId" value="<?php echo $editInvoice->customer_invoice_no; ?>"
                               class="form-control" placeholder="Invoice No"
                               autocomplete="off"/>
                        <span class="input-group-addon" style="background-color:#fff">
                            <?php echo $editInvoice->invoice_no; ?>
                            <input type="hidden" id="" name="voucherid" readonly
                                   value="<?php echo $editInvoice->invoice_no; ?>"/>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label formfonterp" for="form-field-1">
                    <strong><?php echo get_phrase('Loader') ?></strong></label>
                <div class="col-sm-8">
                    <select name="loader" class="chosen-select form-control" id="form-field-select-3"
                            data-placeholder="Select  Loader">
                        <option></option>
                        <?php foreach ($employeeList as $key => $eachEmp): ?>
                            <option <?php
                            if ($editInvoice->loader_emp_id == $eachEmp->id) {
                                echo "selected";
                            }
                            ?> value="<?php echo $eachEmp->id; ?>"><?php echo $eachEmp->name . '   &nbsp&nbsp[' . $eachEmp->personalMobile . ']'; ?></option>
                            <!--<option value="<?php /* echo $eachEmp->id; */ ?>"><?php /* echo $eachEmp->personalMobile . ' [ ' . $eachEmp->name . ']'; */ ?></option>-->
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label formfonterp" for="form-field-1"><strong>
                        <?php echo get_phrase('Transportation') ?></strong></label>
                <div class="col-sm-8">
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

            <div class="form-group">
                <label class="col-sm-4 control-label formfonterp" for="form-field-1"><strong>
                        Branch</strong></label>
                <div class="col-sm-8">
                    <select name="branch_id" class="chosen-select form-control"
                            id="BranchAutoId" data-placeholder="Select Branch">
                        <option value=""></option>
                        <?php
                        echo branch_dropdown(null, $editInvoice->branch_id);
                        ?>


                    </select>
                </div>
            </div>

        </div>

        <div class="col-md-12" id="showBankInfo" style="display:none;margin-top: -26px;">

            <div class="col-md-4">
                <div class="form-group" style="margin-left: -29px;">
                    <label class="col-sm-3 control-label formfonterp" for="bankName">
                        <strong><?php echo get_phrase('Bank_A/C') ?></strong></label>
                    <div class="col-sm-7">
                        <!--onchange="getBankBranch(this.value)"-->
                        <select name="bankName" class=" form-control" id="bankName"
                                data-placeholder="Bank Account  Name">
                            <?php
                            echo bank_account_info_dropdown($editInvoice->bank_branch_id);
                            ?>
                        </select>
                    </div>
                </div>

            </div>
            <!--<div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-3 control-label formfonterp"
                           style="white-space: nowrap;"><strong><?php /*echo get_phrase('Branch_Name') */ ?></strong></label>
                    <div class="col-sm-7">
                        <select name="branchName" class=" form-control" id="branchName"
                                data-placeholder="Bank Name">
                            <option value=""></option>
                            <?php /*foreach ($bankList as $key => $value): */ ?>
                                <option value="<?php /*echo $value->bank_info_id; */ ?>"><?php /*echo $value->bank_name; */ ?></option>
                            <?php /*endforeach; */ ?>
                        </select>

                    </div>
                </div>
            </div>-->
            <div class="col-md-2">
                <div class="form-group">
                    <label class="col-sm-4 formfonterp"
                           style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check_No') ?></strong></label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo $editInvoice->check_no ?>" class="form-control"
                               id="checkNo" name="checkNo"
                               placeholder="Check NO" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="col-sm-4  formfonterp"
                           style="white-space: nowrap;padding-top: 7px;"><strong><?php echo get_phrase('Check_Date') ?></strong></label>
                    <div class="col-sm-8">
                        <input class="form-control date-picker" name="checkDate" name="purchasesDate" id="checkDate"
                               type="text"
                               value="<?php echo $editInvoice->check_date != '' ? date('d-m-Y', strtotime($editInvoice->check_date)) : ''; ?>"
                               data-date-format="dd-mm-yyyy"/>
                    </div>
                </div>
            </div>

        </div>

        <div class="clearfix"></div>
        <div class="col-md-10" style="margin-top: 10px;">
            <div class="table-header">
                <?php echo get_phrase('Sales') . ' ' . get_phrase('Product') ?>
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
                    <th nowrap style="width:12%;border-radius:10px;"><strong><?php echo get_phrase('Category') ?> <span
                                    style="color:red;"> *</span></strong>
                    </th>
                    <th nowrap style="width:172px" id="product_th"><strong> <?php echo get_phrase('Product') ?> <span
                                    style="color:red;"> *</span></strong></th>
                    <th style="white-space:nowrap; width:100px; vertical-align:top;">
                        <strong><?php echo get_phrase('Quantity') ?> <span
                                    style="color:red;"> *</span></strong></th>

                    <th nowrap><strong><?php echo get_phrase('Unit_Price') ?>(<?php echo get_phrase('BDT') ?>) <span
                                    style="color:red;"> *</span></strong></th>
                    <th nowrap><strong><?php echo get_phrase('Total Price') ?>(<?php echo get_phrase('BDT') ?>) <span
                                    style="color:red;"> *</span></strong></th>


                    <th nowrap
                        style="text-align: center;width:17%;border-radius:10px;<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <strong><?php echo $property_1; ?> </strong>
                    </th>
                    <th nowrap
                        style="text-align: center;width:10%;border-radius:10px;<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?> ">
                        <strong><?php echo $property_2; ?> </strong>

                    </th>
                    <th nowrap
                        style="text-align: center;width:10%;border-radius:10px; <?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <strong><?php echo $property_3; ?> </strong>
                    </th>
                    <th nowrap
                        style="text-align: center;width:10%;border-radius:10px; <?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <strong><?php echo $property_4; ?> </strong>
                    </th>
                    <th nowrap
                        style="text-align: center;width:10%;border-radius:10px;<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <strong><?php echo $property_5; ?> </strong>
                    </th>
                    <th><strong><?php echo get_phrase('Action') ?></strong></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <select class="chosen-select form-control" data-placeholder="Select Category"
                                id="CategorySelect2"
                                onchange="getProductList(this.value)">
                            <option></option>
                            <?php
                            $categoryArray = array('1', '2');
                            foreach ($productCat as $eachInfo) {
                                if (in_array($eachInfo->category_id, $categoryArray)) {
                                    ?>
                                    <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                    <?php
                                }
                            }
                            ?>
                            <option value="package">Package</option>
                            <?php
                            $categoryArray = array('1', '2');
                            foreach ($productCat as $eachInfo) {
                                if (!in_array($eachInfo->category_id, $categoryArray)) {
                                    ?>
                                    <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>
                    </td>
                    <td id="product_td">
                        <select id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control"
                                data-placeholder="Select  Product">
                            <option value=""></option>

                        </select>
                    </td>
                    <td><input type="hidden" class="form-control text-right is_same decimal" value="0"><input
                                type="hidden" value="" id="stockQty"/><input type="text" onclick="this.select();"
                                                                             onkeyup="checkStockOverQty(this.value)"
                                                                             class="form-control text-right quantity decimal"
                                                                             placeholder="0"></td>
                     <td><input type="text" onclick="this.select();" class="form-control text-right rate decimal"
                               placeholder="0.00"></td>
                    <td><input type="text" onclick="this.select();" class="form-control text-right price decimal"
                               placeholder="0.00"
                               readonly="readonly"></td>

                    <td style="<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_1 "
                               placeholder="<?php echo $property_1; ?>"/>
                    </td>
                    <td style="<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?>">

                        <input type="text" onclick="this.select();" class="form-control text-right property_2 "
                               placeholder="<?php echo $property_2; ?>"/>
                    </td>
                    <td style="<?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_3 "
                               placeholder="<?php echo $property_3; ?>"/>
                    </td>
                    <td style="<?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_4 "
                               placeholder="<?php echo $property_4; ?>"/>
                    </td>
                    <td style="<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                        <input type="text" onclick="this.select();" class="form-control text-right property_5 "
                               placeholder="<?php echo $property_5; ?>"/>
                    </td>

                    <td>
                        <a id="add_item" class="btn blue form-control" href="javascript:void(0);" title="Add Item">
                            <i class="fa fa-plus" style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;
                        </a>
                    </td>
                </tr>

                </tbody>
                <tfoot>
                <?php
                $tqty = 0;
                $trate = 0;
                $tprice = 0;
                $j = 10001;
                $slNo = 20001;

                $option .= '<option value=""></option>';
                foreach ($cylinderProduct as $eachProduct):
                    $productPreFix = substr($eachProduct->productName, 0, 5);
                    if ($eachProduct->category_id == 1):

                        $option .= '<option categoryName2="' . $eachProduct->productCat . '"brand_id="' . $eachProduct->brand_id . '"productName2="' . $eachProduct->productName . ' ' . $eachProduct->unitTtile . ' [ ' . $eachProduct->brandName . ']"' . 'value=' . $eachProduct->product_id . ' >' . $eachProduct->productName . ' [ ' . $eachProduct->brandName . ' ] ' . '</option>';


                    endif;
                endforeach;


                foreach ($editStock[$editInvoice->sales_invoice_id] as $key => $each_info):
                    $slNo++;
                    $refillEmptyCylinder = "";
                    $tqty += $each_info['quantity'];
                    $trate += $each_info['unit_price'];
                    $tprice += $each_info['unit_price'] * $each_info['quantity'];
                    $package = '';
                    if ($each_info['is_package'] == 1) {
                        $package = 'Package ';
                    }
                    if ($each_info['category_id'] == 2 && $each_info['is_package'] == 0) {
                        $refillEmptyCylinder = '<tr>';
                        $refillEmptyCylinder .= '<td>' . '<select class="chosen-select form-control returnedProducted returnedProduct_' . $slNo . '" id="' . $slNo . '" data-placeholder="Search by product name">' . $option . '</select>';
                        $refillEmptyCylinder .= '</td>';

                        $refillEmptyCylinder .= '<td style="width: 27%">';
                        $refillEmptyCylinder .= '<div class="input-group"><input type="text" class="form-control text-right returnedProductQty_' . $slNo . '" /> </div>';
                        $refillEmptyCylinder .= '</td>';
                        $refillEmptyCylinder .= '<td style="width: 27%">';
                        $refillEmptyCylinder .= '<div class="input-group"><input type="text" class="form-control text-right returnedProductPrice_' . $slNo . '" /><a href="javascript:void(0)" id="' . $slNo . '" class="btn blue AddreturnedProduct  input-group-addon"><i class="fa fa-plus" style=" color:#fff"></i> </a> </div>';
                        $refillEmptyCylinder .= '</td>';
                        $refillEmptyCylinder .= '</tr>';
                    }

                    ?>


                    <tr class="new_item<?php echo $j ?>">
                        <td colspan="2">
                            <input type="hidden" name="slNo[<?php echo $slNo ?>]" value="<?php echo $slNo ?>"/>
                            <input type="hidden" name="brand_id[]" value="<?php echo $each_info['brand_id'] ?>"/>
                            <input type="hidden" id="is_package_<?php echo $slNo ?>"
                                   name="is_package_<?php echo $slNo ?>"
                                   value="<?php echo $each_info['is_package'] ?>"/>
                            <input type="hidden" name="category_id[]" value="<?php echo $each_info['category_id'] ?>"/>
                            <input type="hidden" id="product_id_<?php echo $slNo ?>"
                                   name="product_id_<?php echo $slNo ?>"
                                   value="<?php echo $each_info['product_id'] ?>"/>
                            <?php
                            echo $package . ' ' . $each_info['productName'] . '' . $each_info['unitTtile'] . '[' . $each_info['brandName'] . ']';
                            ?>

                        </td>

                        <td>
                            <input type="text" attr-main-qty="<?php echo $each_info['quantity'] ?>"
                                   id="qty_<?php echo $j ?>" class="form-control text-right add_quantity "
                                   style="height: 33px;"
                                   name="quantity_<?php echo $slNo ?>" value="<?php echo $each_info['quantity'] ?>"/>
                        </td>

                        <td>
                            <input type="text" id="rate_<?php echo $j ?>"
                                   class="form-control add_rate text-right decimal"
                                   name="rate_<?php echo $slNo ?>" value="<?php echo $each_info['unit_price'] ?>"/>
                        </td>
                        <td>
                            <input readonly type="text" class="add_price text-right form-control"
                                   id="tprice_<?php echo $j ?>"
                                   name="price[]" value="<?php echo $each_info['unit_price'] ?>"/>

                        </td>
                        <td style="<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_1 "
                                   placeholder="<?php echo $property_1; ?>" name="property_1_<?php echo $slNo ?>" value="<?php echo $each_info['property_1'] ?>"/>
                        </td>
                        <td style="<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : '' ?>">

                            <input type="text" onclick="this.select();" class="form-control text-right property_2 "
                                   placeholder="<?php echo $property_2; ?>" name="property_2_<?php echo $slNo ?>" value="<?php echo $each_info['property_2'] ?>"/>
                        </td>
                        <td style="<?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_3 "
                                   placeholder="<?php echo $property_3; ?>" name="property_3_<?php echo $slNo ?>" value="<?php echo $each_info['property_3'] ?>"/>
                        </td>
                        <td style="<?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_4 "
                                   placeholder="<?php echo $property_4; ?>" name="property_4_<?php echo $slNo ?>" value="<?php echo $each_info['property_4'] ?>"/>
                        </td>
                        <td style="<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : '' ?>">
                            <input type="text" onclick="this.select();" class="form-control text-right property_5 "
                                   placeholder="<?php echo $property_5; ?>" name="property_5_<?php echo $slNo ?>" value="<?php echo $each_info['property_5'] ?>"/>
                        </td>


                        <td><a del_id="<?php echo $j ?>" class="delete_item btn form-control btn-danger"
                               href="javascript:;" title=""><i class="fa fa-times"
                                                               style="margin-top: 4px;margin-left: 6px;"></i>&nbsp;</a>
                        </td>
                    </tr>
                    <?php


                    $j++;
                endforeach; ?>
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
            <div class="clearfix"></div>
            <div class="clearfix form-actions">
                <div class="col-md-6 col-md-offset-5">
                    <button onclick="return isconfirm2()" id="subBtn" class="btn blue" type="button">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        <?php echo get_phrase('Save') ?>
                    </button>

                </div>
            </div>
        </div>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="portlet box blue">

                <div class="portlet-title" style="min-height:21px">
                    <div class="caption" style="font-size: 12px;padding:1px 0 1px;">
                        <?php echo get_phrase('Payment_Calculation') ?> </div>

                </div>
                <div class="portlet-body" style="padding:1px">
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="font-size:11px"><strong><?php echo get_phrase('Total') ?> :</strong></label>
                        <div class="col-md-7">

                            <input type="text" id="total_price" class="form-control total_price" readonly="true"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap;font-size:11px"><strong><?php echo get_phrase('Discount') ?>
                                (-)
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" onkeyup="calDiscount()" id="disCount" name="discount"
                                   value="<?php echo $editInvoice->discount_amount ?>"
                                   onclick="this.select();"
                                   autocomplete="off"
                                   style="text-align: right" class="form-control" placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Grand_Total') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input readonly id="grandTotal" type="text" name="grandtotal" value=""
                                   style="text-align: right"
                                   class="form-control" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Vat') ?> (%) (
                                + )
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="vatAmount" style="text-align: right" name="vat" readonly value="<?php
                            if (!empty($configInfo->vat)): echo $configInfo->vat;
                            endif;
                            ?>" class="form-control totalVatAmount" placeholder="0.00" onclick="this.select();"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px"><strong><?php echo get_phrase('Loader') ?> (
                                + )
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="loader" onkeyup="calcutateFinal()" style="text-align: right"
                                   onclick="this.select();"
                                   name="loaderAmount"
                                   value="<?php echo $editInvoice->loader_charge//;discount_amount//transport_charge?>"
                                   class="form-control" placeholder="0.00"
                                   autocomplete="off"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label" style="white-space: nowrap; font-size:11px;"
                               data-toggle="tooltip" title="<?php echo get_phrase('Transportation') ?> (+) :">
                            <strong><?php echo get_phrase('Trans') ?>..(+) :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="transportation" onkeyup="calcutateFinal()" style="text-align: right"
                                   onclick="this.select();"
                                   name="transportationAmount"
                                   value="<?php echo $editInvoice->transport_charge//;discount_amount//transport_charge?>"
                                   class="form-control" placeholder="0.00"
                                   autocomplete="off"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Net_Total') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="netAmount" name="netTotal" value="" readonly class="form-control"
                                   onclick="this.select();"
                                   style="text-align: right" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group" style="display: none">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Chq_Amount') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="chaque_amount" name="chaque_amount" value=""
                                   style="text-align: right"
                                   class="form-control" placeholder="0.00"/>

                        </div>
                    </div>
                    <div class="form-group" id="paymentTr">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Payment') ?>(
                                - )
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="payment" onkeyup="calculatePartialPayment()" onclick="this.select();"
                                   style="text-align: right"
                                   autocomplete="off"
                                   name="partialPayment" value="<?php echo $editInvoice->paid_amount ?>"
                                   class="form-control" autocomplete="off"
                                   placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>
                    <div class="form-group creditDate" style="display:none;">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Due_Date') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input class="form-control date-picker" name="creditDueDate" id="dueDate"
                                   style="text-align: right"
                                   type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy"
                                   autocomplete="off"/>

                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-5 control-label"
                               style="white-space: nowrap; font-size:11px;"><strong><?php echo get_phrase('Due_Amount') ?>
                                :</strong></label>
                        <div class="col-md-7">
                            <input type="text" id="currentDue" readonly="true" name="" value=""
                                   style="text-align: right"
                                   class="form-control" autocomplete="off" placeholder="0.00"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"/>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </form>
</div>

<script>

    $(document).ready(function () {
        showBankinfo('<?php echo $editInvoice->payment_type;?>');
        $('.add_quantity').blur(function () {
            var branchId = $('#BranchAutoId').val();
            var id_arr = $(this).attr('name');
            var add_quantity_id = $(this).attr('id');
            var givenStock = $(this).val();
            var mainStockBeforeEdit = $(this).attr('attr-main-qty');
            var id = id_arr.split("_");
            //alert(id);
            var product_id = $('#product_id_' + id[1]).val();
            var ispackage = $('#is_package_' + id[1]).val();
            $.ajax({
                type: "POST",
                url: baseUrl + 'lpg/InvProductController/getProductStock',
                data: {product_id: product_id, category_id: '', ispackage: ispackage, branchId: branchId},
                success: function (data) {

                    var mainStock = parseFloat(data);
                    if (isNaN(mainStock)) {
                        mainStock = 0;
                    }


                    if (mainStock < givenStock) {

                        $('#' + add_quantity_id).val(1112323);
                        $('#' + add_quantity_id).attr("placeholder", mainStock);
                        $('#' + add_quantity_id).val(mainStockBeforeEdit);

                    }
                }
            });


            //alert('LLL');
            var rate = parseFloat($(this).val());
            if (isNaN(rate)) {
                rate = 0;
            }
            $(this).val(parseFloat(rate).toFixed(2));
        });

        $('.rate').blur(function () {
            var rate = parseFloat($(this).val());
            if (isNaN(rate)) {
                rate = 0;
            }
            $(this).val(parseFloat(rate).toFixed(2));
        });


        $('.quantity').keyup(function () {
            priceCal();
        });

        $('.rate').keyup(function () {
            priceCal();
        });
    });

    function showBankinfo(id) {
        //$("#payment").val('');
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
            $(".partisals").show(10);
        } else {
            $("#paymentTr").hide(10);
            $(".partisals").hide(10);
        }
        if (id == 2) {
            $(".creditDate").show(10);
        } else {
            $(".creditDate").hide(10);
        }
    }

    var calculateCustomerDue = function () {
        $('#searchIdcylinderTypeReport').val('');
        $('#previousDue').html('');
        $('#customer_address_span').html('');
        $('#customer_phone_span').html('');


        var customerid = parseFloat($('#customerid').val());


        $('#cmdSaveValue').attr('href', 'javascript:void(0)');
        var netAmount = parseFloat($('#netAmount').val());
        var payment = parseFloat($("#payment").val());
        if (isNaN(payment)) {
            payment = 0;
        }
        if (isNaN(netAmount)) {
            netAmount = 0;
        }
        $('#cmdSaveValueSpan').html('');
        var url = baseUrl + "lpg/SalesController/getCustomerCurrentBalance";
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                customerId: customerid
            },
            success: function (data) {  var data = JSON.parse(data);
                console.log(data);
                var due;
                due = parseFloat(data.customer_due);
                if (isNaN(due)) {
                    due = 0;
                }
                $('#previousDue').html(parseFloat(due).toFixed(2));
                if (due < 0) {
                    due = due * -1;
                    previousDueText = "Advance &nbsp;:&nbsp;"
                }
                $('#previousDueSpan').html(parseFloat(due).toFixed(2));

                $('#previousDueText').html(previousDueText);

                $('#customer_address_span').html(data.customer_details.customerAddress);
                $('#customer_phone_span').html(data.customer_details.customerPhone);
                var CreditLimit;
                CreditLimit = parseFloat(data.customer_details.credit_limit);
                if (isNaN(CreditLimit)) {
                    CreditLimit = 0;
                }

                $('#CreditLimit').val(parseFloat(CreditLimit).toFixed(2));

                var dueCylinder = data.CylinderDue;
                console.log(dueCylinder);

                var i = 1;
                if (dueCylinder == null) {

                    $('#CylinderDueTable').hide();
                } else {

                    if (dueCylinder < 0) {
                        var text = 'Due &nbsp;:&nbsp;' + (-1 * dueCylinder);
                    } else {
                        var text = 'Advance &nbsp;:&nbsp;' + (1 * dueCylinder);
                    }

                    var link = baseUrl + 'lpg/InventoryController/cylinderTypeReport2/' + customerid;
                    $('#cmdSaveValue').attr('href', link);
                    $('#CylinderDueTable').show();
                    $('#cmdSaveValueSpan').html(text);


                }


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
            url: baseUrl + "lpg/InvProductController/getProductList",
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

    var cylinderProduct;
    window.cylinderProduct = '<?php echo json_encode($cylinderProduct); ?>';
    var option = "";
    option += "<option value='" + '' + "'>---Select Name---</option>";
    console.log('cylinderProduct');
    console.log(cylinderProduct);
    $.each(JSON.parse(cylinderProduct), function (key, value) {
        if (value.category_id == 1) {
            option += "<option returnProductUnit='" + value.unitTtile + "' categoryName2='" + value.productCat + "' brand_id='" + value.brand_id + "' productName2='" + value.productName + ' ' + value.unitTtile + ' [ ' + value.brandName + ' ]' + "' value='" + value.product_id + "'  >" + value.productName + " " + value.unitTtile + ' [' + value.brandName + ' ]' + "</option>";
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

    function isconfirm3() {
        var due_collection_info_id = $('#due_collection_info_id').val();

        if (due_collection_info_id == "") {
            swal("Select Due Collection Info Id !", "Validation Error!", "error");
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
                        $("#billTobillForm_"+due_collection_info_id).submit();
                    } else {
                        return false;
                    }
                });
        }
    }

    $(function () {

    });
    $(document).ready(function () {
        $('.add_quantity').trigger('keyup');
        var j = 0;
        var slNo = 1;
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
            var received_cylilder_price = parseFloat($('.received_cylilder_price').val());
            if (isNaN(received_cylilder_price)) {
                received_cylilder_price = 0;
            }

            var property_1 = $('.property_1').val();
            var property_2 = $('.property_2').val();
            var property_3 = $('.property_3').val();
            var property_4 = $('.property_4').val();
            var property_5 = $('.property_5').val();

            var quantity = $('.quantity').val();
            var rate = $('.rate').val();
            var price = $('.price').val();
            var returnQuantity = $('.returnQuantity').val();
            var returnQuantity2 = $('.returnQuantity2').val();
            var received_cylilder_id = $('.received_cylilder_id').val();
            var received_cylilder_price = $('.received_cylilder_price').val();


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
            } else if (productCatID == 2 && received_cylilder_price <= 0 && received_cylilder_id != "") {
                swal(" Received Cylilder Price ! ", "Validation Error!", "error");
                return false;
            }

            if (productCatName2 != '') {
                calculate_profit_lose_for_empty_cylinder();
            }


            if (ispackage == 0) {

                var tab;

                if (productCatID == 2) {
                    //refill cylinder
                    if ($('.is_same').val() == 0) {
                        alert('Ok');

                        slNo++;
                        // for return cylender
                        var returnedCylender = '';
                        if (returnQuantity2 > 0) {
                            returnedCylender = '<tr>' +
                                '<td style="width: 46%;">' +
                                '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_' + slNo + '[]" value="' + package_id2 + '">' +
                                productName2 +
                                '</td>' +
                                '<td style="width: 27%;">' +
                                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnedQuantity_' + slNo + '[]" value="' + returnQuantity2 + '"></div>' +
                                '</td>' +
                                '<td>' +
                                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnedQuantityPrice_' + slNo + '[]" value="' + received_cylilder_price + '"><a href="javascript:void(0)" id="2" class="btn red remove_returnable  input-group-addon"><i class="fa fa-minus-circle " style="color:#fff"></i> </a> </div>' +
                                '</td>' +
                                '</tr>';
                        }


                        tab = '<tr class="new_item' + j + '">' +
                            '<input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/>' +
                            '<input type="hidden" name="brand_id[]" value="' + brand_id + '"/>' +
                            '<input type="hidden" id="is_package_' + slNo + '" name="is_package_' + slNo + '"  value="0">' +
                            '<input type="hidden" name="category_id[]"  value="' + productCatID + '">' +
                            '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName +
                            '<input type="hidden" id="product_id_' + slNo + '"  name="product_id_' + slNo + '" value="' + productID + '">' +
                            '</td>' +
                            '<td align="right">' +
                            '<input type="text" id="qty_' + j + '" class="form-control text-right add_quantity decimal" style="height: 33px;"  name="quantity_' + slNo + '" value="' + quantity + '">' +
                            '</td>' +

                            '<td align="right"><input type="text" id="rate_' + j + '" class="form-control add_rate text-right decimal" name="rate_' + slNo + '" value="' + rate + '">' +
                            '</td>' +
                            '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_' + j + '" name="price[]" value="' + price + '">' +
                            '</td>' +
                            '<td colspan="3">' +
                            '<table class="table table-bordered table-hover" style="margin-bottom: 0px;" id="return_product_' + slNo + '">' +
                            '<tr>' +
                            '<td>' +
                            '<select   class="chosen-select form-control returnedProducted returnedProduct_' + slNo + '"  id="' + slNo + '" data-placeholder="Search by product name"> ' +
                            option +
                            '</select>' +
                            '</td>' +
                            '<td style="width: 27%">' +
                            '<div class="input-group"><input type="text" class="form-control text-right returnedProductQty_' + slNo + '" /> </div>' +
                            '</td>' +
                            '<td style="width: 40%">' +
                            '<div class="input-group"><input type="text" class="form-control text-right returnedProductPrice_' + slNo + '" /><a href="javascript:void(0)" id="' + slNo + '" class="btn blue AddreturnedProduct  input-group-addon"><i class="fa fa-plus" style=" color:#fff"></i> </a> </div>' +
                            '</td>' +
                            '</tr>' +
                            returnedCylender +
                            '</table>' +
                            '</td>' +
                            '<td>' +
                            '<a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times" style="margin-top: 4px;margin-left: 6px;"></i>&nbsp;</a>' +
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
                            '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnedQuantity_' + slNo + '[]" value="' + returnQuantity2 + '"><a href="javascript:void(0)" id="2" class="btn red remove_returnable  input-group-addon"><i class="fa fa-minus-circle " style=" color:#fff"></i> </a> </div>' +
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
                        '<input type="hidden" id="is_package_' + slNo + '" name="is_package_' + slNo + '" value="0">' +
                        '<input type="hidden" name="category_id[]" value="' + productCatID + '">' +
                        '<td style="padding-left:15px;" colspan="2"> [ ' + productCatName + '] - ' + productName +
                        '<input type="hidden" id="product_id_' + slNo + '"  name="product_id_' + slNo + '" value="' + productID + '">' +
                        '</td>' +
                        '<td align="right">' +
                        '<input type="text" id="qty_' + j + '" class="form-control text-right add_quantity decimal"  name="quantity_' + slNo + '" value="' + quantity + '">' +
                        '</td>' +

                        '<td align="right"><input type="text" id="rate_' + j + '" class="form-control add_rate text-right decimal" name="rate_' + slNo + '" value="' + rate + '">' +
                        '</td>' +
                        '<td align="right"><input readonly type="text" class="add_price text-right form-control" id="tprice_' + j + '" name="price[]" value="' + price + '">' +
                        '</td>' +
                        '<td align="right" style="<?php echo $property_1 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                        '<input  type="text" class="add_property_1 text-right form-control" id="property_1' + j + '" name="property_1_' + slNo + '" value="' + property_1 + '">' +
                        '</td>' +
                        '<td align="right" style="<?php echo $property_2 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                        '<input  type="text" class="add_property_2 text-right form-control" id="property_2' + j + '" name="property_2_' + slNo + '" value="' + property_2 + '">' +
                        '</td>' +
                        '<td align="right" style="<?php echo $property_3 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                        '<input  type="text" class="add_property_3 text-right form-control" id="property_3' + j + '" name="property_3_' + slNo + '" value="' + property_3 + '">' +
                        '</td>' +
                        '<td align="right" style="<?php echo $property_4 == 'dont_have_this_property' ? 'display: none' : ''?>" >' +
                        '<input  type="text" class="add_property_4 text-right form-control" id="property_4' + j + '" name="property_4_' + slNo + '" value="' + property_4 + '">' +
                        '</td>' +
                        '<td align="right" style="<?php echo $property_5 == 'dont_have_this_property' ? 'display: none' : ''?> ">' +
                        '<input  type="text" class="add_property_5 text-right form-control" id="property_5' + j + '" name="property_5_' + slNo + '" value="' + property_5 + '">' +
                        '</td>' +
                        '<td>' +
                        '<a del_id="' + j + '" class="delete_item btn form-control btn-danger" href="javascript:;" title=""><i class="fa fa-times"></i>&nbsp;</a>' +
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
                            $("#show_item tfoot").append('<tr class="new_item' + j + '    packageDeleteRow_' + value['package_id'] + '"><input type="hidden" name="slNo[' + slNo + ']" value="' + slNo + '"/><input type="hidden" id="is_package_' + slNo + '" name="is_package_' + slNo + '" value="1"><input type="hidden" name="category_id_' + slNo + '" value="' + value['category_id'] + '">' +
                                '<td style="padding-left:15px;" colspan="2"> [ ' + value['title'] + '] - ' + value['productName'] + '&nbsp;' + value['unitTtile'] + '&nbsp;[ ' + value['brandName'] + " ]" +
                                ' <input type="hidden" id="product_id_' + slNo + '"  name="product_id_' + slNo + '" value="' + value['product_id'] + '"></td>' +
                                '</td><td align="right"><input type="text" class="add_quantity decimal form-control text-right" id="qtyPackage_' + j + '" name="quantity_' + slNo + '" value="' + quantity + '"></td><td align="right"><input type="text" class="add_return form-control text-right decimal "  id="qtyReturn_' + j + '"   name="add_returnAble[]" value=""  readonly></td><td align="right"><input type="text" id="ratePackage_' + j + '" class="add_rate_package form-control decimal text-right" name="rate_' + slNo + '" value="' + rate + '"></td><td align="right"><input type="text" class="add_price  text-right form-control" id="tpricePackage_' + j + '" readonly name="price[]" value="' + price + '"></td><td></td><td></td><td style="' + style + '"   rowspan="' + rowspan + '"><a del_id="' + j + '" package_id_delete="' + value['package_id'] + '"class="delete_item btn form-control btn-danger" href="javascript:void(0);" title=""><i class="fa fa-times"></i>&nbsp;</a></td></tr>');
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
            $('#CategorySelect2').val('').trigger('chosen:updated');
            $('#productID').val('').trigger('chosen:updated');
            $('#productID2').val('').trigger('chosen:updated');
            $('.quantity').val('');
            $('.is_same').val('0');
            $('.rate').val('');
            $('.price').val('');
            $('.returnAble').val('');
            $('.returnQuentity').val('');
            $('.returnQuantity2').val('');
            $('.returnQuantity').val('');
            $('.quantity').attr("placeholder", "0");
            $('.received_cylilder_price').attr("placeholder", "0");
            $('.received_cylilder_price').val('');


            $('.property_1').val('');
            $('.property_3').val('');
            $('.property_3').val('');
            $('.property_4').val('');
            $('.property_5').val('');
        })


        j++;


    });


    function calculate_profit_lose_for_empty_cylinder() {


    }

    $(document).on('click', '.AddreturnedProduct', function () {
        //$(".AddreturnedProduct").LoadingOverlay("show");

        var id = $(this).attr("id");
        var productName2 = $('.returnedProduct_' + id).find('option:selected').attr('productName2');
        var package_id2 = $('.returnedProduct_' + id).val();
        var returnQuantity2 = $('.returnedProductQty_' + id).val();
        var returnedProductPrice = $('.returnedProductPrice_' + id).val();


        if (returnQuantity2 == '') {
            swal("Qty can't be empty.!", "Validation Error!", "error");
            return false;
        } else
            var tab2 = "<tr>" +
                "<td style='width: 46%;'>" +
                '<input type="hidden" class="text-right form-control" id="" readonly name="returnproduct_' + id + '[]" value="' + package_id2 + '">' +
                productName2 +
                "</td>" +
                "<td style='width: 27%;'>" +
                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnedQuantity_' + id + '[]" value="' + returnQuantity2 + '"> </div>' +
                "</td>" +
                '<td>' +
                '<div class="input-group"><input type="text" class="text-right form-control" id="" readonly name="returnedQuantityPrice_' + id + '[]" value="' + returnedProductPrice + '"><a href="javascript:void(0)" id="2" class=" btn red remove_returnable  input-group-addon"><i class="fa fa-minus-circle " style="color:#fff"></i> </a> </div>' +
                '</td>' +
                "</tr>";
        $("#return_product_" + id).append(tab2);
        $('.returnedProduct_' + id).val('');
        $('.returnedProductQty_' + id).val('');
        $('.returnedProductPrice_' + id).val('');
        $('.returnedProductPrice_' + id).attr('placeholder', '0');

    })

    function getProductPrice(product_id) {
        var productName = $('#productID').find('option:selected').attr('productName');
        $("#stockQty").val('');
        $(".quantity").val('');
        $('.is_same').val('0');
        var productCatID = parseFloat($('#productID').find('option:selected').attr('categoryId'));
        var branchId = $('#BranchAutoId').val();


        if (branchId == '' || branchId == null) {
            swal("Select Branch", "Validation Error!", "error");
            $(".quantity").attr('readonly', true);
            return false;
        }


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

            $.ajax({
                type: "POST",
                url: baseUrl + 'lpg/SalesLpgController/checkHaveEmptyCylinderOrPrice',
                data: 'product_id=' + product_id,
                success: function (data) {
                    console.log(data);
                    if (data <= 0) {
                        swal("There is no price or package  Against This Refill Product !", "Please Set a Price or Package !", "error");
                        $('#productID').val('').trigger('chosen:updated');
                        $('.quantity ').val('');
                        return false;
                    }
                }
            });


        } else {
            $(".returnQuantity").attr('readonly', true);
            $(".returnQuantity2").attr('readonly', true);
            $('#productID2').val('').prop('disabled', true).trigger("chosen:updated");
        }
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductPriceForSale',
            data: 'product_id=' + product_id,
            success: function (data) {
                $('.rate').val('');
            }
        });
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/InvProductController/getProductStock',
            data: {product_id: product_id, category_id: productCatID, ispackage: ispackage, branchId: branchId},
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

    function received_cylilder_price2(product_id) {
        alert(product_id);
        exit;

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

                $('.received_cylilder_price').val(parseFloat(data));
                $('.received_cylilder_price').attr("placeholder", parseFloat(data));
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

    $(document).on('click', '.remove_returnable', function () {
        alert('OK');
        $(this).closest("tr").remove();

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
                                    <select name="customerType" class=" form-control" id="customerType"
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
<script type="text/javascript" src="<?php echo base_url('assets/sales_lpg/saleInvoiceEdit.js'); ?>"></script>
