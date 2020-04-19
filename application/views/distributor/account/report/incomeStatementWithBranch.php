<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/16/2020
 * Time: 10:46 AM
 */
?>
<?php
if (isset($_POST['start_date'])):
    $account = $this->input->post('accountHead');
    $from_date = $this->input->post('start_date');
    $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
    $to_date = $this->input->post('end_date');
    $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
    $branch_id = isset($_POST['branch_id']) ? $this->input->post('branch_id') : 'all';
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Income Statement
                </div>
            </div>
            <div class="portlet-body">
                <div class="row noPrint">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="">
                                                Branch </label>
                                            <div class="col-sm-8">
                                                <select name="branch_id" class="chosen-select form-control"
                                                        id=""
                                                        data-placeholder="Search Branch"
                                                        onchange="check_pretty_cash(this.value)">

                                                    <?php
                                                    echo branch_dropdown('all', $branch_id);
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                From Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="start_date" value="<?php
                                                if (!empty($from_date)) {
                                                    echo $from_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                To Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="end_date"
                                                       name="end_date" value="<?php
                                                if (!empty($to_date)):
                                                    echo $to_date;
                                                else:
                                                    echo date('d-m-Y');
                                                endif;
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-5">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-sm-5">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="window.print();" style="cursor:pointer;">
                                                    <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                                    Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <?php
                if (isset($_POST['start_date']) && isset($_POST['end_date'])):
                    //  dumpVar($_POST);
                    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                    $account = $this->input->post('accountHead');
                    $dist_id = $this->dist_id;
                    unset($_SESSION["account"]);
                    unset($_SESSION["start_date"]);
                    unset($_SESSION["end_date"]);
                    $_SESSION["account"] = $account;
                    $_SESSION["start_date"] = $from_date;
                    $_SESSION["end_date"] = $to_date;
                    $dist_id = $this->dist_id;
                    $total_pvsdebit = '';
                    $total_pvscredit = '';
                    $total_debit = '';
                    $total_credit = '';
                    $total_balance = '';
                    $space = "&nbsp;&nbsp;&nbsp";
                    $space2 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
                    ?>
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?> : </strong>
                                        From <?php echo $from_date; ?> To <?php echo $to_date; ?>
                                    </td>
                                </tr>
                            </table>

                            <?php

                            if ($branch_id == "all") {
                                $condition = array(
                                    'is_active' => '1',
                                );


                            } else {
                                $condition = array(
                                    'is_active' => '1',
                                    'branch_id' => $branch_id,
                                );
                            }

                            $branchs = $this->Common_model->get_data_list_by_many_columns('branch', $condition);

                            foreach ($branchs as $key => $branch) {
                                ?>

                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td colspan="3" align="center"><strong><?php echo $branch->branch_name; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td align="center"><strong>Code</strong></td>
                                        <td align="center"><strong>Description</strong></td>
                                        <td align="center"><strong>Total Balance (In BDT.)</strong></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $productSales = 0;
                                    $productCost = 0;
                                    $totalIncome = 0;
                                    $sales_revenue = $this->Accounts_model->get_sales_revenue($start_date, $end_date, $branch->branch_id);
                                    $cost_of_goods = $this->Accounts_model->get_cost_of_goods_group_sum($start_date, $end_date, $branch->branch_id);
                                    foreach ($sales_revenue as $key => $value_sales_revenue) {
                                        $name_and_code = explode("#@", $key)
                                        ?>
                                        <tr class="sales-revenue">
                                            <td>
                                                <?php echo $name_and_code[0]; ?>
                                            </td>
                                            <td colspan="2" class="text-left">
                                                <?php echo $name_and_code[1]; ?>
                                            </td>
                                        </tr>

                                        <?php
                                        foreach ($value_sales_revenue as $key => $value) {
                                            ?>
                                            <tr class="sales-revenue-ledger">

                                                <td>
                                                    <?php echo $space . '' . $value->CN_Code ?>
                                                </td>
                                                <td>
                                                    <?php echo $space . '' . $value->CN ?>
                                                </td>
                                                <td align="right">
                                                    <?php

                                                    //$sales=(-1 * ($value->Balance + $value->Opening));
                                                    $sales = (-1 * ($value->Balance));
                                                    if ($sales < 0) {
                                                        echo '( ' . (numberFromatfloat((-1 * $sales),2)) . ' )';
                                                    } else {
                                                        echo numberFromatfloat($sales,2);
                                                    }


                                                    $productSales = $productSales + $sales;
                                                    ?>
                                                </td>

                                            </tr>
                                        <?php }
                                    } ?>
                                    <tr class="cost-of-goods-sold">
                                        <td class="cost-of-goods-sold-td" colspan="2" style="text-align: right">
                                            <i> Less,Cost of Goods Sold</i>
                                        </td>
                                        <td class="cost-of-goods-sold-td text-right" colspan="2">
                                            <?php
                                            //$cost_of_goods_amount=$cost_of_goods->Balance + $cost_of_goods->Opening;
                                            $cost_of_goods_amount = $cost_of_goods->Balance;
                                            if ($sales < 0) {
                                                echo '( ' . (numberFromatfloat((-1 * $cost_of_goods_amount),2)) . ' )';
                                            } else {
                                                echo numberFromatfloat($cost_of_goods_amount,2);
                                            }

                                            //$productCost = $productCost + ($cost_of_goods->Balance + $cost_of_goods->Opening);
                                            $productCost = $productCost + ($cost_of_goods->Balance);
                                            ?>
                                        </td>

                                    </tr>
                                    <tr class="gross_profit_loss">
                                        <td class="gross_profit_loss-td" colspan="2" style="text-align: right">
                                            <i>Gross Profit</i>
                                        </td>
                                        <td class="cost-of-goods-sold-td text-right">
                                            <?php
                                            echo number_format($productSales - $productCost,2);
                                            $totalIncome = $totalIncome + ($productSales - $productCost);
                                            ?>
                                        </td>

                                    </tr>
                                    <?php
                                    $other_income = $this->Accounts_model->get_other_income_without_sales_revenue($start_date, $end_date, $branch->branch_id);


                                    if (!empty($other_income)) {
                                        foreach ($other_income as $key => $value_other_income) {
                                            $name_and_code = explode("#@", $key)
                                            ?>
                                            <tr class="other-income">
                                                <td>
                                                    <?php echo $name_and_code[0]; ?>
                                                </td>
                                                <td colspan="2" class="text-left">
                                                    <?php echo $name_and_code[1]; ?>
                                                </td>
                                            </tr>

                                            <?php
                                            foreach ($value_other_income as $key => $value) {
                                                $name_and_code2 = explode("#@", $key);
                                                if ($name_and_code2[0] != 'single') {
                                                    ?>
                                                    <tr class="other-income-group">

                                                        <td>
                                                            <?php echo $space . '' . $name_and_code2[0]; ?>
                                                        </td>
                                                        <td colspan="2" class="text-left">
                                                            <?php echo $space . '' . $name_and_code2[1]; ?>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                }
                                                foreach ($value as $key => $value2) { ?>
                                                    <tr class="sales-revenue-ledger">

                                                        <td><?php echo $space2 . $value2->CN_Code ?></td>
                                                        <td><?php echo $space2 . $value2->CN ?></td>
                                                        <td align="right">
                                                            <?php
                                                            // echo $other_income=(-1 *($value2->Balance + $value2->Opening));
                                                             $other_income = (-1 * ($value2->Balance));
                                                            echo number_format($other_income,2);
                                                            $totalIncome = $totalIncome + $other_income;
                                                            //echo "<br>".$totalIncome;
                                                            ?>
                                                        </td>

                                                    </tr>

                                                    <?php
                                                } ?>


                                            <?php }
                                        }
                                    } ?>

                                    <tr class="total-income" style="text-align: right">
                                        <td colspan="2" class="text-right">
                                            <i>Total Income :</i>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            echo number_format($totalIncome,2);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="expance">
                                        <td colspan="3">
                                            <?php echo get_phrase("Less,Expance") ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $expance_without_cost_of_goods_sold = $this->Accounts_model->get_expance_without_cost_of_goods_sold($start_date, $end_date, $branch);
                                    $totalExpance = 0;
                                    foreach ($expance_without_cost_of_goods_sold as $key => $value_expance) {
                                        $name_and_code_expance = explode("#@", $key);

                                        ?>
                                        <tr class="expance_without_cost_of_goods_sold">
                                            <td>
                                                <?php echo $name_and_code_expance[0]; ?>
                                            </td>
                                            <td colspan="2" class="text-left">
                                                <?php echo $name_and_code_expance[1]; ?>
                                            </td>
                                        </tr>

                                        <?php

                                        foreach ($value_expance as $key => $value) {
                                            $name_and_code_expance2 = explode("#@", $key);
                                            if ($name_and_code_expance2[0] != 'single') {
                                                ?>
                                                <tr class="expance_without_cost_of_goods_sold">

                                                    <td>
                                                        <?php echo $space . '' . $name_and_code_expance2[0]; ?>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <?php echo $space . '' . $name_and_code_expance2[1]; ?>
                                                    </td>

                                                </tr>
                                                <?php
                                            }
                                            foreach ($value as $key => $value2) { ?>
                                                <tr class="sales-revenue-ledger">

                                                    <td><?php echo $space2 . $value2->CN_Code ?></td>
                                                    <td><?php echo $space2 . $value2->CN ?></td>
                                                    <td align="right">
                                                        <?php

                                                        echo number_format($value2->Balance,2);
                                                        $totalExpance = $totalExpance + ($value2->Balance);
                                                        ?>
                                                    </td>

                                                </tr>

                                                <?php
                                            } ?>


                                        <?php }
                                    } ?>
                                    <tr>
                                        <td colspan="2" style="text-align: right">
                                            Total Expance
                                        </td>
                                        <td align="right">
                                            <?php echo numberFromatfloat($totalExpance,2) ?>
                                        </td>
                                    </tr>


                                    </tbody>
                                    <tfoot>
                                    <td colspan="2" style="text-align: right">
                                        Net Profit
                                    </td>
                                    <td align="right">
                                        <?php
                                        echo number_format($totalIncome - $totalExpance,2);
                                        ?>
                                    </td>
                                    </tfoot>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>
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

    });
</script>
