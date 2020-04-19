<?php /**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 4/16/2019
 * Time: 9:46 AM
 */ ?>





<?php
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
?>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Daily Sales Statement') ?></div>

            </div>

            <div class="portlet-body">
                <form id="publicForm" action="" method="get" class="form-horizontal noPrint">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right"
                                           for="BranchAutoId"> <?php echo get_phrase('Branch') ?> </label>
                                    <div class="col-md-8">
                                        <select name="branch_id" class="chosen-select form-control"
                                                id="BranchAutoId" data-placeholder="Select Branch">
                                            <option value=""></option>
                                            <?php
                                            if (!empty($branch_id)) {
                                                $selected = $branch_id;
                                            } else {
                                                $selected = 'all';
                                            }
                                            // come from branch_dropdown_helper
                                            echo branch_dropdown('all', $selected);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right"
                                           for="form-field-1"> <?php echo get_phrase('From') ?> </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control date-picker" id="start_date"
                                               name="start_date"
                                               value="<?php
                                               if (!empty($start_date)) {
                                                   echo $start_date;
                                               } else {
                                                   echo date('d-m-Y');
                                               }
                                               ?>" data-date-format='dd-mm-yyyy' placeholder=" dd-mm-yyyy"
                                               style="width:100%"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right"
                                           for="form-field-1">&nbsp;&nbsp;<?php echo get_phrase('To') ?></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control date-picker" id="end_date"
                                               name="end_date" value="<?php
                                        if (!empty($end_date)):
                                            echo $end_date;
                                        else:
                                            echo date('d-m-Y');
                                        endif;
                                        ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"
                                               style="width:100%"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="ace-icon fa fa-search icon-on-right"></i> <?php echo get_phrase('Search') ?>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                            style="cursor:pointer;">

                                        <i class="fa fa-print icon-on-right"></i> <?php echo get_phrase('Print') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <table class="table table-responsive">
                        <tbody>
                        <tr>
                            <td style="text-align:center;">
                                <h3>M/S Master Trades</h3>
                                <p style="margin-bottom: 4px;"><?php echo get_phrase('Address') ?>:Navana Zohura; 28,
                                    Kazi Nazrul Islam Avenue</p>
                                <strong> <?php echo get_phrase('Phone') ?> : </strong>0188898987<br>
                                <strong> <?php echo get_phrase('Daily Sales Statement') ?></strong>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                    if (isset($daily_sales_statement) && !empty($daily_sales_statement)) {
                        $price = 0;
                        $ttPrice = 0;
                        ?>

                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center"><?php echo get_phrase('Branch') ?></th>
                                    <th class="text-center"><?php echo get_phrase('Sales Date') ?></th>
                                    <th class="text-center"><?php echo get_phrase('Product Name') ?></th>
                                    <th class="text-center"><?php echo get_phrase('Sales Qty') ?></th>
                                    <th class="text-center"><?php echo get_phrase('Sales Price') ?></th>
                                    <th class="text-center"><?php echo get_phrase('Total Sales') ?></th>


                                </tr>

                                </thead>
                                <tbody>
                                <?php
                                $brandNameArray = array();
                                foreach ($daily_sales_statement as $ind => $element2) {
                                    if (!in_array($element2->branch_name, $brandNameArray)) {
                                        array_push($brandNameArray, $element2->branch_name); ?>
                                        <tr>
                                            <td colspan="6">
                                                <?php echo $element2->branch_name; ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" align="right">
                                                <?php echo $element2->invoice_date; ?>
                                            </td>
                                            <td>
                                                <?php echo $element2->productName; ?>
                                            </td>
                                            <td align="right">
                                                <?php echo $element2->sales_qty; ?>
                                            </td>
                                            <td style="text-align: right">
                                                <?php echo numberFromatfloat($element2->sales_price); ?>
                                            </td>

                                            <td style="text-align: right">
                                                <?php
                                                $price = $element2->sales_qty * $element2->sales_price;
                                                echo numberFromatfloat($price);
                                                $ttPrice = $ttPrice + $price;
                                                ?>
                                            </td>

                                        </tr>

                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="2" align="right">
                                                <?php echo $element2->invoice_date; ?>
                                            </td>
                                            <td>
                                                <?php echo $element2->productName; ?>
                                            </td>
                                            <td align="right">
                                                <?php echo $element2->sales_qty; ?>
                                            </td>
                                            <td style="text-align: right">
                                                <?php echo numberFromatfloat($element2->sales_price); ?>
                                            </td>

                                            <td style="text-align: right">
                                                <?php
                                                $price = $element2->sales_qty * $element2->sales_price;
                                                //echo $price;
                                                echo numberFromatfloat($price);
                                                $ttPrice = $ttPrice + $price;
                                                ?>
                                            </td>

                                        </tr>
                                    <?php }
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6" style="text-align: right">
                                        <?php echo numberFromatfloat($ttPrice) ?>
                                    </td>
                                </tr>
                                </tfoot>

                            </table>

                        </div>
                        <?php
                    } else {

                    ?>

                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">No Data Found</th>


                            </tr>
                            </thead>
                        </table>


                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });</script>




