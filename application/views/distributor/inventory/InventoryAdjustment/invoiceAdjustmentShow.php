<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 10:04 AM
 */
//echo '<pre>';
//print_r($adjustment);
//exit;
?>
<script>
    function printContent(el) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(el).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
    }
</script>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase($link_page_name) ?></div>

            </div>
            <div class="portlet-body" id="pdf">
                <form id="publicForm" action="" method="post" class="form-horizontal">
                    <fieldset style="">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-right" for="form-field-1">

                                        Branch:&nbsp&nbsp&nbsp <?php echo $adjustment[0]->branch_name; ?> </label>

                                    <div class="col-sm-6">


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                        <?php echo get_phrase("Date") ?> :
                                        &nbsp&nbsp <?php echo $adjustment[0]->date; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <div class="input-group">


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <legend style="margin-left: 20px; margin-top: 10px;">Inventory Recipt :</legend>
                            <div class="col-md-12">
                                <table class="table table-bordered" id="show_item_in">
                                    <thead>
                                    <tr>
                                        <th class="text-center"><strong><?php echo get_phrase("SL") ?></strong></th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Category") ?> </strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Item") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Rate") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Qty") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Price") ?></strong>
                                        </th>

                                    </tr>

                                    </thead>


                                    <tbody>
                                    <?php foreach ($adjustment as $key => $value) {
                                        if ($value->out_qty == 0) { ?>

                                            <tr>
                                                <td class="text-center"><?php echo $key + 1; ?>)</td>
                                                <td class="text-center">
                                                    <?php echo $value->title; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->productName; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->unit_price; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->in_qty; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo ($value->in_qty) * ($value->unit_price); ?>
                                                </td>

                                            </tr>
                                        <?php }
                                    } ?>


                                    </tbody>
                                </table>

                                <div class="col-md-8 col-md-offset-2">

                                    <br>

                                    <div class="table-header">

                                        Select Account Head
                                    </div>

                                    <table class="table table-bordered table-hover" id="show_item_account_in">

                                        <thead>

                                        <tr>
                                            <td style="width:5%" align="center"><strong>SL</strong></td>
                                            <td style="width:30%" align="center">
                                                <strong> Account Head</strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> Debit</strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> Credit</strong></td>

                                            <td style="width:20%" align="center">
                                                <strong> Memo</strong></td>


                                        </tr>

                                        </thead>

                                        <tbody>

                                        <?php
                                        $tDrIn = 0;
                                        $tCrIn = 0;
                                        foreach ($invoiceAdjustmentVoucherIndetails as $key => $value) {

                                            ?>
                                            <tr>
                                                <td style="width:5%" align="center"><?php echo $key + 1; ?>)</td>

                                                <td style="width:30%" align="center">
                                                    <?php
                                                    $Condition = array(

                                                        'id' => $id,

                                                    );
                                                    $ledger_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $Condition);
                                                    echo $ledger_info->parent_name . " [" . $ledger_info->code . " ]";


                                                    ?></td>

                                                <td style="width:15%" align="center">
                                                    <?php
                                                    $tDrIn = $tDrIn + $value->GR_DEBIT;
                                                    echo numberFromatfloat($value->GR_DEBIT, 2);
                                                    ?>
                                                </td>

                                                <td style="width:15%" align="center">
                                                    <?php
                                                    $tCrIn = $tCrIn + $value->GR_CREDIT;
                                                    echo numberFromatfloat($value->GR_CREDIT, 2);
                                                    ?>
                                                </td>

                                                <td style="width:20%" align="center">
                                                    <?php
                                                    echo $value->Reference;
                                                    ?>
                                                </td>


                                            </tr>
                                        <?php } ?>

                                        <tr>

                                            <td colspan="2" align="right"><strong>Sub-Total In.BDT)</strong></td>

                                            <td align="center"><strong class="total_dr">
                                                    <?php
                                                    echo numberFromatfloat($tDrIn, 2);
                                                    ?>
                                                </strong></td>

                                            <td align="center"><strong class="total_cr">
                                                    <?php
                                                    echo numberFromatfloat($tCrIn, 2);
                                                    ?></strong></td>
                                            <td></td>


                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>


                    </fieldset>


                    <fieldset style="">
                        <legend style="">Inventory Out :</legend>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="show_item_out">
                                    <thead>
                                    <tr>
                                        <th class="text-center"><strong><?php echo get_phrase("SL") ?></strong></th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Category") ?> </strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Inventory Item") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Rate") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Qty") ?></strong>
                                        </th>
                                        <th class="text-center"><strong>
                                                <?php echo get_phrase("Price") ?></strong>
                                        </th>

                                    </tr>

                                    </thead>
                                    <?php foreach ($adjustment as $key => $value) {
                                        if ($value->in_qty == 0) { ?>

                                            <tr>
                                                <td class="text-center"><?php echo $key + 1; ?>)</td>
                                                <td class="text-center">
                                                    <?php echo $value->title; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->productName; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->unit_price; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $value->out_qty; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo ($value->out_qty) * ($value->unit_price); ?>
                                                </td>

                                            </tr>
                                        <?php }
                                    } ?>


                                    <tbody>


                                    </tbody>
                                </table>


                                <div class="col-md-8 col-md-offset-2">

                                    <br>

                                    <div class="table-header">

                                        Select Account Head
                                    </div>

                                    <table class="table table-bordered table-hover" id="show_item_account_in">

                                        <thead>

                                        <tr>
                                            <td style="width:5%" align="center"><strong>SL</strong></td>
                                            <td style="width:30%" align="center">
                                                <strong> Account Head</strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> Debit</strong></td>

                                            <td style="width:15%" align="center">
                                                <strong> Credit</strong></td>

                                            <td style="width:20%" align="center">
                                                <strong> Memo</strong></td>


                                        </tr>

                                        </thead>

                                        <tbody>

                                        <?php
                                        $tDrIn = 0;
                                        $tCrIn = 0;
                                        foreach ($invoiceAdjustmentVoucherOutdetails as $key => $value) {

                                            ?>
                                            <tr>
                                                <td style="width:5%" align="center"><?php echo $key + 1; ?>)</td>

                                                <td style="width:30%" align="center">
                                                    <?php
                                                    $Condition = array(

                                                        'id' => $id,

                                                    );
                                                    $ledger_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $Condition);
                                                    echo $ledger_info->parent_name . " [" . $ledger_info->code . " ]";


                                                    ?></td>

                                                <td style="width:15%" align="center">
                                                    <?php
                                                    $tDrIn = $tDrIn + $value->GR_DEBIT;
                                                    echo numberFromatfloat($value->GR_DEBIT, 2);
                                                    ?>
                                                </td>

                                                <td style="width:15%" align="center">
                                                    <?php
                                                    $tCrIn = $tCrIn + $value->GR_CREDIT;
                                                    echo numberFromatfloat($value->GR_CREDIT, 2);
                                                    ?>
                                                </td>

                                                <td style="width:20%" align="center">
                                                    <?php
                                                    echo $value->Reference;
                                                    ?>
                                                </td>


                                            </tr>
                                        <?php } ?>

                                        <tr>

                                            <td colspan="2" align="right"><strong>Sub-Total In.BDT)</strong></td>

                                            <td align="center"><strong class="total_dr">
                                                    <?php
                                                    echo numberFromatfloat($tDrIn, 2);
                                                    ?>
                                                </strong></td>

                                            <td align="center"><strong class="total_cr">
                                                    <?php
                                                    echo numberFromatfloat($tCrIn, 2);
                                                    ?></strong></td>
                                            <td></td>


                                        </tr>
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>


                    </fieldset>
                </form>
                <button type="button" class="btn btn-default" onclick="printContent('pdf')">Print Content</button>
                <button type="button" class="btn btn-default">Default</button>
            </div>
        </div>
    </div>
</div>


