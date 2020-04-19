<?php
$headlogin = $this->session->userdata('headOffice');
//echo"<pre>";
//print_r($accountHeadList);
//exit;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-parent_name" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Add_Account_Head_Opening_Balance') ?> </div>
            </div>
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">

            </div>
            <div class="portlet-body">
                <div>
                    <ul class="">


                        <li>Opening !!You can entry opening balance only one time.<span style="color:red!important;">Be Careful!!</span>
                        </li>
                    </ul>
                    <ul class="breadcrumb pull-right">
                        <li>
                            <a href="<?php echo site_url('DistributorDashboard/settings'); ?>">
                                <i class="ace-icon fa fa-list"></i>
                                List
                            </a>
                        </li>
                        <?php if (!empty($headlogin)): ?>
                            <?php if ($openingBalanceDeleteCheck < 1): ?>
                                <li>
                                    <a class="red" href="javascript:void(0)" onclick="deleteOpeningBalance()">
                                        <i class="ace-icon fa fa-trash-o"></i>
                                        Remove?
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a class="red" href="<?php echo site_url('openingBalance/1'); ?>">
                                    <i class="ace-icon fa fa-backward"></i>
                                    Reverse?
                                </a>
                            </li>
                            <?php
                        else:
                            if ($openingBalanceDeleteCheck < 1):
                                ?>
                                <li>
                                    <a class="red" href="javascript:void(0)" onclick="deleteOpeningBalance()">
                                        <i class="ace-icon fa fa-trash-o"></i>
                                        Remove?
                                    </a>
                                </li>
                                <?php
                            endif;
                        endif;
                        $opeDate = $this->Common_model->get_single_data_by_single_column('opening_balance', 'dist_id', $this->dist_id)->date;
                        ?>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td align="center"><strong><?php echo get_phrase('Opening Date') ?></strong></td>
                                <td colspan="2">
                                    <div class="input-group">
                                        <input autocomplete="off" class="form-control date-picker" readonly
                                               name="date"
                                               id="id-date-picker-1" type="text" value="<?php
                                        if (!empty($opeDate)) {
                                            echo date('d-m-Y', strtotime($opeDate));
                                        } else {
                                            echo date('d-m-Y');
                                        }
                                        ?>" data-date-format="dd-mm-yyyy"/>
                                        <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><strong><?php echo get_phrase('Account Name') ?></strong></td>
                                <td align="center"><strong><?php echo get_phrase('Debit_In_Bdt') ?></strong></td>
                                <td align="center"><strong><?php echo get_phrase('Credit_In_Bdt') ?></strong></td>
                                <td align="center"><strong><?php echo get_phrase('Action') ?></strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total_debit = 0;
                            $totalOpening = 0;
                            $total_credit = 0;
                            $ids=array();
                            foreach ($accountHeadList as $key => $eachValue):
                                ?>
                                <tr>
                                    <td style="text-align: center;font-weight: bold;font-size: 18px;"
                                        colspan="4"><?php echo get_phrase($eachValue['parentName']); ?></td>
                                </tr>
                                <?php
                                foreach ($eachValue['Accountledger'] as $eachParent) {
                                    //Customer Receivable
                                    if ($eachParent->parent_id == $this->config->item("Customer_Receivable") && $eachParent->related_id_for == 3) {
                                        if (isset($exitsopening->credit) && empty($reloadData)) {
                                            //come from opening table
                                            $total_debit += $exitsopening->debit;
                                        } else {
                                            //for reverse come from inventory stock table
                                            $receiableBalance = $this->Finane_Model->getCustomerReceiable2($eachParent->related_id);
                                            $total_debit += $receiableBalance->totalPrice;
                                        }
                                        if ($receiableBalance->totalPrice > 0) {
                                            $text = "Edit-Nahid";
                                            $color = "red";
                                        } else {
                                            $text = "Add";
                                            $color = "green";
                                        }
                                        ?>
                                        <tr class="customer_receivable">
                                            <td><?php echo get_phrase($eachParent->parent_name); ?></td>
                                            <td>
                                                <input autocomplete="off" type="hidden" name="accountid[]"
                                                       value="<?php echo $eachParent->id; ?>"/>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control debit"
                                                       id="debit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $receiableBalance->totalPrice; ?>"
                                                       name="headDebit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control credit"
                                                       id="credit_amount_<?php echo $eachParent->id; ?>"
                                                       value="0"
                                                       name="headCredit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00" readonly/>
                                            </td>
                                            <td>
                                                <a class="btn btn-icon-only <?php echo $color ?>"
                                                   href="javascript:void(0)"
                                                   onclick="updateAndSaveopening_balance('<?php echo $eachParent->id; ?>')">
                                                    <i class="fa "></i><?php echo $text ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    } else if ($eachParent->parent_id == $this->config->item("Supplier_Payables")) {
                                        //Supplier Payables
                                        if ($eachParent->parent_id == $this->config->item("Supplier_Payables")):
                                            $payableBalance = $this->Finane_Model->getSupplierPayable2($eachParent->related_id);
                                            //if account head supplier payable
                                            if (isset($exitsopening->credit) && empty($reloadData)) {
                                                //come from opening table
                                                $total_credit += $exitsopening->credit;
                                            } else {
                                                //for reverse come from inventory stock table
                                                $total_credit += $payableBalance->totalPrice;
                                            }
                                            if ($payableBalance->totalPrice > 0) {
                                                $text = "Edit-Nahid";
                                                $color = "red";
                                            } else {
                                                $text = "Add";
                                                $color = "green";
                                            }
                                        else:
                                            $total_credit += isset($exitsopening->credit) ? $exitsopening->credit : '0';
                                        endif;
                                        ?>

                                        <tr class="supplier_payable">
                                            <td><?php echo get_phrase($eachParent->parent_name); ?></td>
                                            <td>
                                                <input autocomplete="off" type="hidden" name="accountid[]"
                                                       value="<?php echo $eachParent->id; ?>"/>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control debit"
                                                       id="debit_amount_<?php echo $eachParent->id; ?>"
                                                       value=""
                                                       name="headDebit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00" readonly/>
                                            </td>
                                            <td>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control credit"
                                                       id="credit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $payableBalance->totalPrice; ?>"
                                                       name="headCredit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <a class="btn btn-icon-only <?php echo $color ?>"
                                                   href="javascript:void(0)"
                                                   onclick="updateAndSaveopening_balance('<?php echo $eachParent->id; ?>')">
                                                    <i class="fa "></i><?php echo $text ?>
                                                </a>
                                            </td>

                                        </tr>

                                        <?php
                                    } else if ($eachParent->parent_id == 32) {
                                        $drOrcrBalance = $this->Common_model->get_single_data_by_single_column('opening_balance', 'account', $eachParent->id);
                                        $exitsopeningDr = $drOrcrBalance->debit;
                                        $exitsopeningCr = $drOrcrBalance->credit;
                                        if ($drOrcrBalance->debit > 0 ) {
                                            $text = "Edit-Nahid";
                                            $color = "red";
                                        } else {
                                            $text = "Add";
                                            $color = "green";
                                        }

                                        ?>

                                        <tr class="cash_at_bank">
                                            <td><?php echo get_phrase($eachParent->parent_name); ?></td>
                                            <td>
                                                <input autocomplete="off" type="hidden" name="accountid[]"
                                                       value="<?php echo $eachParent->id; ?>"/>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control debit"
                                                       id="debit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $drOrcrBalance->debit; ?>"
                                                       name="headDebit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control credit"
                                                       id="credit_amount_<?php echo $eachParent->id; ?>"
                                                       value=""
                                                       name="headCredit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00" readonly/>
                                            </td>
                                            <td>
                                                <a class="btn btn-icon-only red" href="javascript:void(0)"
                                                   onclick="updateAndSaveopening_balance('<?php echo $eachParent->id; ?>')">
                                                    <i class="fa "></i>Edit
                                                </a>
                                            </td>

                                        </tr>

                                        <?php
                                    } else if ($eachParent->parent_id == $this->config->item("Inventory_Finished_Goods_Stock")) {
                                        //Inventory & Finished Goods Stock
                                        $cylinderPrice = $this->Finane_Model->getInventoroyCylinderStock2($eachParent->related_id);
                                        //if account head supplier payable
                                        $total_debit += $cylinderPrice->totalPrice;
                                        if ($cylinderPrice->totalPrice > 0) {
                                            $text = "Edit-Nahid";
                                            $color = "red";
                                        } else {
                                            $text = "Add";
                                            $color = "green";
                                        }
                                        ?>

                                        <tr class="Inventory-Finished-Goods-Stock">
                                            <td><?php echo get_phrase($eachParent->parent_name); ?></td>
                                            <td>
                                                <input autocomplete="off" type="hidden" name="accountid[]"
                                                       value="<?php echo $eachParent->id; ?>"/>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control debit"
                                                       id="debit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $cylinderPrice->totalPrice; ?>"
                                                       name="headDebit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control credit"
                                                       id="credit_amount_<?php echo $eachParent->id; ?>"
                                                       value=""
                                                       name="headCredit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00" readonly/>
                                            </td>
                                            <td>
                                                <a class="btn btn-icon-only <?php echo $color ?>"
                                                   href="javascript:void(0)"
                                                   onclick="updateAndSaveopening_balance('<?php echo $eachParent->id; ?>')">
                                                    <i class="fa "></i><?php echo $text ?>
                                                </a>
                                            </td>

                                        </tr>

                                        <?php
                                    } else if ($eachParent->parent_id == $this->config->item("New_Cylinder_Stock") ) {
                                        //New Cylinder Stock
                                        $cylinderPrice = $this->Finane_Model->getInventoroyCylinderStockOnlyCylinder2($eachParent->related_id);
                                        //if account head supplier payable
                                        $total_debit += $cylinderPrice->totalPrice;
                                        if ($cylinderPrice->totalPrice > 0) {
                                            $text = "Edit";

                                            $color = "red";
                                        } else {
                                            $text = "Add";
                                            $color = "green";
                                        }
                                        array_push($ids,$eachParent->id);

                                        $drOrcrBalance = $this->Common_model->get_single_data_by_single_column('opening_balance', 'account', $eachParent->id);
                                        $exitsopeningDr = $drOrcrBalance->debit;
                                        $exitsopeningCr = $drOrcrBalance->credit;
                                        if ($drOrcrBalance->debit > 0 ) {
                                            $text = "Edit";
                                            $color = "red";
                                        } else {
                                            $text = "Add";
                                            $color = "green";
                                        }

                                        ?>

                                        <tr class="new-cylinder-stock">
                                            <td><?php echo get_phrase($eachParent->parent_name); ?></td>
                                            <td>
                                                <input autocomplete="off" type="hidden" name="accountid[]"
                                                       value="<?php echo $eachParent->id; ?>"/>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control debit"
                                                       id="debit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $drOrcrBalance->debit; ?>"
                                                       name="headDebit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control credit"
                                                       id="credit_amount_<?php echo $eachParent->id; ?>"
                                                       value=""
                                                       name="headCredit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00" readonly/>
                                            </td>
                                            <td>
                                                <a class="btn btn-icon-only <?php echo $color ?>"
                                                   href="javascript:void(0)"
                                                   onclick="updateAndSaveopening_balance('<?php echo $eachParent->id; ?>')">
                                                    <i class="fa "></i><?php echo $text ?>
                                                </a>
                                            </td>

                                        </tr>

                                        <?php
                                    } else if ($eachParent->parent_id == $this->config->item("Refill_Stock")) {
                                        //Refill Stock
                                        $refillPrice = $this->Finane_Model->getInventoroyCylinderStock2($eachParent->related_id);
                                        //if account head supplier payable
                                        $total_debit += $refillPrice->totalPrice;
                                        if ($refillPrice->totalPrice > 0) {
                                            $text = "Edit-Nahid";
                                            $color = "red";
                                        } else {
                                            $text = "Add";
                                            $color = "green";
                                        }
                                        ?>

                                        <tr class="Refill-Stock">
                                            <td><?php echo get_phrase($eachParent->parent_name); ?></td>
                                            <td>
                                                <input autocomplete="off" type="hidden" name="accountid[]"
                                                       value="<?php echo $eachParent->id; ?>"/>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control debit"
                                                       id="debit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $refillPrice->totalPrice; ?>"
                                                       name="headDebit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control credit"
                                                       id="credit_amount_<?php echo $eachParent->id; ?>"
                                                       value=""
                                                       name="headCredit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00" readonly/>
                                            </td>
                                            <td>
                                                <a class="btn btn-icon-only <?php echo $color ?>"
                                                   href="javascript:void(0)"
                                                   onclick="updateAndSaveopening_balance('<?php echo $eachParent->id; ?>')">
                                                    <i class="fa "></i><?php echo $text ?>
                                                </a>
                                            </td>

                                        </tr>

                                        <?php
                                    } else if (!in_array($eachParent->related_id_for, array(4, 5))) {
                                        $drOrcrBalance = $this->Common_model->get_single_data_by_single_column('opening_balance', 'account', $eachParent->id);
                                        $exitsopeningDr = $drOrcrBalance->debit;
                                        $exitsopeningCr = $drOrcrBalance->credit;
                                        if ($drOrcrBalance->debit > 0 || $drOrcrBalance->credit > 0) {
                                            $text = "Edit-Nahid";
                                            $color = "red";
                                        } else {
                                            $text = "Add";
                                            $color = "green";
                                        }
                                        ?>


                                        <tr class="pppp<?php echo $eachParent->related_id_for ?>">
                                            <td><?php echo get_phrase($eachParent->parent_name); ?></td>
                                            <td>
                                                <input autocomplete="off" type="hidden" name="accountid[]"
                                                       value="<?php echo $eachParent->id; ?>"/>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control debit"
                                                       id="debit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo $exitsopeningDr ?>"
                                                       name="headDebit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <input style="text-align: right" type="text"
                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       class="form-control credit"
                                                       id="credit_amount_<?php echo $eachParent->id; ?>"
                                                       value="<?php echo $exitsopeningCr ?>"
                                                       name="headCredit_<?php echo $eachParent->id; ?>"
                                                       placeholder="0.00"/>
                                            </td>
                                            <td>
                                                <a class="btn btn-icon-only <?php echo $color ?>"
                                                   href="javascript:void(0)"
                                                   onclick="updateAndSaveopening_balance('<?php echo $eachParent->id; ?>')">
                                                    <i class="fa "></i><?php echo $text ?>
                                                </a>
                                            </td>

                                        </tr>

                                        <?php
                                    }
                                }
                            endforeach;
                            ?>

                            <!--End parent query--->
                            </tbody>
                            <tfoot>
                            <tr>
                                <td align="right"><strong><?php echo get_phrase('Sub_Total_Bdt') ?></strong></td>
                                <td align="right"><strong> <span
                                                class="ttl_dr_del"><?php ;
                                            echo number_format((float)$total_debit, 2, '.', ''); ?></span><span
                                                class="ttl_dr"></span></strong></td>
                                <td align="right"><strong> <span
                                                class="ttl_cr_del"><?php echo number_format((float)$total_credit, 2, '.', ''); ?></span><span
                                                class="ttl_cr"></span></strong></td>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-5 col-md-7">

                                <a style=""
                                   href="<?php echo site_url('lpg/OpeningController/save_openning_balance_to_main_table'); ?>"
                                   id="showSaveBtn"
                                   class="btn btn-xs btn-success"><i class="fa fa-check"></i> Save</a>


                            </div>
                        </div>

                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<script type="text/javascript">

    function deleteOpeningBalance() {
        swal({
                parent_name: "Are you sure ?",
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
                    window.location.href = "deleteOpneningBalance";
                } else {
                    return false;
                }
            });
    }


    $(document).ready(function () {

        $('.ttl_dr_del').remove();
        ttl_dr = 0;
        $.each($('.debit'), function () {
            dr = $(this).val();
            dr = Number(dr);
            ttl_dr += dr;
        });
        $(this).val(parseFloat($(this).val()).toFixed(2));
        $('.ttl_dr').html(parseFloat(ttl_dr).toFixed(2));


        $('.ttl_cr_del').remove();
        ttl_cr = 0;
        $.each($('.credit'), function () {
            cr = $(this).val();
            cr = Number(cr);
            ttl_cr += cr;
        });
        $(this).val(parseFloat($(this).val()).toFixed(2));
        $('.ttl_cr').html(parseFloat(ttl_cr).toFixed(2));


        $('.debit').change(function () {

            $('.ttl_dr_del').remove();
            ttl_dr = 0;
            $.each($('.debit'), function () {
                dr = $(this).val();
                dr = Number(dr);
                ttl_dr += dr;
            });
            $(this).val(parseFloat($(this).val()).toFixed(2));
            $('.ttl_dr').html(parseFloat(ttl_dr).toFixed(2));
        });
        $('.credit').change(function () {

            $('.ttl_cr_del').remove();
            ttl_cr = 0;
            $.each($('.credit'), function () {
                cr = $(this).val();
                cr = Number(cr);
                ttl_cr += cr;
            });
            $(this).val(parseFloat($(this).val()).toFixed(2));
            $('.ttl_cr').html(parseFloat(ttl_cr).toFixed(2));
        });
    });
</script>
<script>

    $(document).ready(function () {
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });


    function updateAndSaveopening_balance(id) {
        var id = id;
        var drAmount = parseFloat($('#debit_amount_' + id).val());
        var crAmount = parseFloat($('#credit_amount_' + id).val());
        updateAndSave(id, drAmount, crAmount)
    }


    function updateAndSave(id, drAmount, crAmount) {
        $.ajax({
            type: "POST",
            url: baseUrl + 'lpg/OpeningController/updateAndSaveopening_balance',
            data: {
                id: id,
                drAmount: drAmount,
                crAmount: crAmount
            },
            dataType: "json",
            success: function (data) {
                if (JSON.parse(data) == 1) {
                    toastr.success('Save Successfully');
                } else {
                    toastr.error("Can Not Save");
                }
            }
        });
    }
</script>






