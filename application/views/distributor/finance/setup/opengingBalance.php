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
                        <form id="publicForm" action="" method="post">
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
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_debit = 0;
                                $totalOpening = 0;
                                $total_credit = 0;
                                foreach ($accountHeadList as $key => $eachValue):
                                    ?>
                                    <tr>
                                        <td style="text-align: center;font-weight: bold;font-size: 18px;"
                                            colspan="3"><?php echo get_phrase($eachValue['parentName']); ?></td>
                                    </tr>
                                    <?php
                                    foreach ($eachValue['Accountledger'] as $eachParent):
                                        /* Come from openingBalance table */
                                        $condition = array(
                                            'dist_id' => $this->dist_id,
                                            'account' => $eachParent->id,
                                        );
                                        $exitsopening = $this->Common_model->get_single_data_by_many_columns('opening_balance', $condition);
                                        /* Come from openingBalance table */
                                        if ($eachParent->id == 20) {
                                            $productPrice = $this->Finane_Model->getInventoroyProductStock($this->dist_id);
                                            $total_debit += $productPrice->totalPrice;
                                        } elseif ($eachParent->parent_id == 33 && $eachParent->related_id_for == 3) {
                                            //if account head customer receiveable
                                            if (isset($exitsopening->credit) && empty($reloadData)) {
                                                //come from opening table
                                                $total_debit += $exitsopening->debit;
                                            } else {
                                                //for reverse come from inventory stock table
                                                $receiableBalance = $this->Finane_Model->getCustomerReceiable2($eachParent->related_id);
                                                $total_debit += $receiableBalance->totalPrice;
                                            }
                                        } elseif ($eachParent->parent_id == 21) {
                                            //for reverse come from inventory stock table
                                            $cylinderPrice = $this->Finane_Model->getInventoroyCylinderStock2($eachParent->related_id);
                                           //log_message('error', 'this is inventory query' . print_r($this->db->last_query(), true));
                                            $total_debit += $cylinderPrice->totalPrice;
                                        } elseif ($eachParent->parent_id == 26) {
                                            $refillPrice2 = 0;
                                            //for reverse come from inventory stock table
                                            $refillPrice2 = $this->Finane_Model->getInventoroyRefillStock2($eachParent->related_id);
                                            $total_debit += $refillPrice2->totalPrice;
                                        } elseif ($eachParent->parent_id == 25) {
                                            $refillPrice = 0;
                                            //for reverse come from inventory stock table
                                            $refillPrice = $this->Finane_Model->getInventoroyRefillStock2($eachParent->related_id);
                                            $total_debit += $refillPrice->totalPrice;
                                        } else {
                                            //others account head
                                            $total_debit += isset($exitsopening->debit) ? $exitsopening->debit : '0';
                                            $totalOpening += isset($exitsopening->debit) ? $exitsopening->debit : '0';
                                        }
                                        //different calculation for credit balance
                                        ?>
                                        <tr>
                                            <td ="<?php echo $eachParent->id; ?>" /><b>&nbsp;<?php echo get_phrase($eachParent->parent_name); ?></b></td>
                                            <input autocomplete="off" type="hidden" name="accountid[]"
                                                   value="<?php echo $eachParent->id; ?>"/>
                                            <?php
                                            if ($eachParent->id == 20):
                                                //inventory stock
                                                ?>
                                                <td ="<?php echo $eachParent->id; ?>" /><input autocomplete="off" style="text-align: right" type="text"
                                                                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                               class="form-control debit"
                                                                                               value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $productPrice->totalPrice; ?>"
                                                                                               name="headDebit[]" readonly placeholder="0.00"/></td>
                                                <?php
                                            elseif ($eachParent->parent_id == 21):
                                                //cylinder inventory stock
                                                ?>
                                                <td ="<?php echo $eachParent->id; ?>" /><input autocomplete="off" style="text-align: right" type="text"
                                                                                               readonly
                                                                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                               class="form-control debit"
                                                                                               value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $cylinderPrice->totalPrice; ?>"
                                                                                               name="headDebit[]" readonly placeholder="0.00"/></td>

                                                <?php
                                                $cylinderPrice = array();
                                            elseif ($eachParent->parent_id == 25):
                                                //cylinder inventory stock
                                                ?>
                                                <td ="<?php echo $eachParent->id; ?>" /><input autocomplete="off" style="text-align: right" type="text"
                                                                                               readonly
                                                                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                               class="form-control debit"
                                                                                               value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $refillPrice->totalPrice; ?>"
                                                                                               name="headDebit[]" readonly placeholder="0.00"/></td>

                                                <?php
                                                $cylinderPrice = array();
                                            elseif ($eachParent->parent_id == 26):
                                                //cylinder inventory stock
                                                ?>
                                                <td ="<?php echo $eachParent->id; ?>" /><input autocomplete="off" style="text-align: right" type="text"
                                                                                               readonly
                                                                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                               class="form-control debit"
                                                                                               value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $refillPrice2->totalPrice; ?>"
                                                                                               name="headDebit[]" readonly placeholder="0.00"/></td>

                                                <?php
                                                $refillPrice = array();
                                            elseif ($eachParent->parent_id == 33 && $eachParent->related_id_for == 3):
                                                //customer receiaveable
                                                ?>
                                                <td ="<?php echo $eachParent->id; ?>" /><input autocomplete="off" style="text-align: right" type="text"
                                                                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                               class="form-control debit"
                                                                                               value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 && empty($reloadData) ? $exitsopening->debit : $receiableBalance->totalPrice; ?>"
                                                                                               name="headDebit[]" readonly placeholder="0.00"/>
                                                </td>
                                            <?php else: ?>
                                                <td ="<?php echo $eachParent->id; ?>" /><input autocomplete="off" style="text-align: right" type="text"
                                                                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                               class="form-control debit"
                                                                                               value="<?php echo isset($exitsopening) && $exitsopening->debit > 0 ? $exitsopening->debit : ''; ?>"
                                                                                               name="headDebit[]" placeholder="0.00"/>
                                                </td>
                                            <?php endif; ?>

                                            <?php
                                            if ($eachParent->parent_id == 53):
                                                if ($eachParent->parent_id == 53):
                                                    $payableBalance = $this->Finane_Model->getSupplierPayable2($eachParent->related_id);
                                                    //if account head supplier payable
                                                    if (isset($exitsopening->credit) && empty($reloadData)) {
                                                        //come from opening table
                                                        $total_credit += $exitsopening->credit;
                                                    } else {
                                                        //for reverse come from inventory stock table
                                                        $total_credit += $payableBalance->totalPrice;
                                                    }
                                                else:
                                                    $total_credit += isset($exitsopening->credit) ? $exitsopening->credit : '0';
                                                endif;
                                                ?>
                                                <td ="<?php echo $eachParent->id; ?>" /><input autocomplete="off" style="text-align: right" type="text"
                                                                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                               class="form-control credit"
                                                                                               value="<?php echo isset($exitsopening) && $exitsopening->credit > 0 && empty($reloadData) ? $exitsopening->credit : $payableBalance->totalPrice; ?>"
                                                                                               readonly name="headCredit[]" placeholder="0.00"/></td>
                                            <?php else: ?>
                                                <td id=""><input autocomplete="off" style="text-align: right" type="text"
                                                                 oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                 class="form-control credit"
                                                                 value="<?php echo isset($exitsopening) && $exitsopening->credit > 0 ? $exitsopening->credit : ''; ?>"
                                                                 name="headCredit[]" placeholder="0.00"/></td>
                                            <?php endif; ?>

                                        </tr>
                                        <?php
                                    endforeach;
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
                                    <button id="btnDisabled" onclick="return confirmSwat()" id="subBtn"
                                            class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        <?php echo get_phrase('Save') ?>
                                    </button>
                                    <?php
                                    if ($openingBalanceDeleteCheck < 1 && empty($reloadData)): ?>
                                        <button id="btnDisabled" onclick="return confirmSwat()" id="subBtn"
                                                class="btn btn-info" type="button">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            <?php echo get_phrase('Save') ?>
                                        </button>
                                    <?php elseif (!empty($reloadData)): ?>
                                        <button id="btnDisabled" onclick="return confirmSwat()" id="subBtn"
                                                class="btn btn-info" type="button">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            <?php echo get_phrase('Save') ?>
                                        </button>
                                        <?php
                                    else:
                                        ?>
                                        <button id="" disabled id="subBtn" class="btn btn-info" type="button">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            <?php echo get_phrase('Save') ?>
                                        </button>
                                    <?php endif; ?>
                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        <?php echo get_phrase('Reset') ?>
                                    </button>
                                </div>
                            </div>
                        </form>
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
</script>






