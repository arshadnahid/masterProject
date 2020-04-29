<style type="text/css">
    .table td, .table th {
        font-size: 12px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        padding: 3px;
        line-height: 1.42857;
        vertical-align: top;
        border-top: 1px solid #e7ecf1;
    }
</style>
<?php
if (isset($_POST['to_date'])):
    $to_date = $this->input->post('to_date');
    $end_date = date('Y-m-d', strtotime($this->input->post('to_date')));
    $branch_id = isset($_POST['branch_id']) ? $this->input->post('branch_id') : 'all';
else:
    $branch_id = 'all';
    $group = '';
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Balance Sheet
                </div>
            </div>
            <div class="portlet-body">
                <div class="row noPrint">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="">
                                                Branch </label>
                                            <div class="col-sm-8">
                                                <select name="branch_id" class="chosen-select form-control"
                                                        id=""
                                                        data-placeholder="Search Branch"
                                                        >

                                                    <?php
                                                    echo branch_dropdown('all', $branch_id);
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="form-field-1"><span style="color:red;"> *</span> As At </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="to_date" value="<?php
                                                if (!empty($to_date)) {
                                                    echo $to_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-sm-2">

                                            </div>
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
                if (isset($_POST['to_date'])):
                    //  dumpVar($_POST);
                    $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
                    unset($_SESSION["to_date"]);
                    $_SESSION["to_date"] = $to_date;
                    $dist_id = $this->dist_id;
                    $total_income = '';
                    $total_costs = '';
                    $total_assets = '';
                    $total_liabilityies = '';
                    $total_equity_govt = '';
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <?php
                                        if ($companyInfo->address != "") {
                                            ?>
                                            <strong>Website : </strong><?php echo $companyInfo->address; ?><br>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        if ($companyInfo->phone != "") {
                                            ?>
                                            <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        if ($companyInfo->email != "") {
                                            ?>
                                            <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                            <?php
                                        }
                                        ?>



                                        <?php
                                        if ($companyInfo->website != "") {
                                            ?>
                                            <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <?php
                                        }
                                        ?>

                                        <strong><?php echo $pageTitle; ?></strong>
                                        <?php echo $to_date; ?>
                                    </td>
                                </tr>
                            </table>
                            <!--all branch sullary-->
                            <?php
                            foreach ($balance_sheet_with_all_branch as $key => $array) {
                                $branch_name_and_code = explode("~#@~", $key)
                                ?>
                                <table class="table  table-bordered " id="BalanceSheet">
                                    <thead>
                                    <tr style="background-color: #f5f5f5;">
                                        <td align="center" colspan="5"><b><?php echo $branch_name_and_code[0] ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="width: 12%;"><strong>Code</strong></td>
                                        <td align="center" style=" width: 40%;"><strong>Description</strong></td>

                                        <td align="center"><strong>Amount</strong></td>
                                        <td align="center" style="display: none"><strong>Amount</strong></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($array as $key2 => $valueManin) {
                                        $main_group_name_and_code = explode("~#@~", $key2)
                                        ?>
                                        <tr style="background-color: #e1e2e2;">
                                            <td colspan="3" align="center">
                                                <strong><b><?php echo $main_group_name_and_code[0] ?></b>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php
                                        foreach ($valueManin as $key3 => $valueManin2) {
                                            $group_name_and_code = explode("~#@~", $key3)
                                            ?>
                                            <tr style="background-color: #f5f5f5;">
                                                <td>
                                                    <b><?php echo $group_name_and_code[0]; ?></b>
                                                </td>
                                                <td colspan="2">
                                                    <b><?php echo $group_name_and_code[1]; ?></b>
                                                </td>
                                            </tr>
                                            <?php
                                            foreach ($valueManin2 as $key => $value) {
                                                $total_assets = $total_assets + $value->Balance;
                                                ?>
                                                <tr>

                                                    <td><?php echo $value->PN2_Code ?></td>
                                                    <td><?php echo $value->PN2 ?></td>
                                                    <td align="right">
                                                        <?php


                                                        if ($value->Balance < 0) {
                                                            echo " ( " . numberFromatfloat((-1 * $value->Balance)) . " )";
                                                        } else {
                                                            echo numberFromatfloat($value->Balance);
                                                        }

                                                        ?>
                                                    </td>
                                                    <td align="right" style="display: none">
                                                        <?php

                                                        if ($value->Balance < 0) {
                                                            echo " ( " . numberFromatfloat((-1 * $value->Balance)) . " )";
                                                        } else {
                                                            echo $value->Balance;
                                                        }


                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php
                                        }
                                        ?>

                                        <tr>
                                            <td colspan="2" style="text-align: right">
                                                <i> Total <?php echo $main_group_name_and_code[0] ?> (In BDT)</i>
                                            </td>

                                            <td style="text-align: right">
                                                <?php


                                                echo numberFromatfloat($total_assets);

                                                if ($main_group_name_and_code[1] == 1) {
                                                    $total_assets_of_a_branch = $total_assets;
                                                } else {
                                                    $total_Capital_Liabilities_of_a_branch = $total_assets;
                                                }
                                                $total_assets = 0;

                                                ?>
                                            </td>
                                        </tr>

                                        <?php

                                    } ?>


                                    <tr>
                                        <td colspan="2" style="text-align: right">
                                            <?php
                                            $expance = $this->Accounts_model->get_sum_of_a_accounting_group_all_branch(4, $end_date, $branch_name_and_code[1]);
                                            $income = $this->Accounts_model->get_sum_of_a_accounting_group_all_branch(3, $end_date, $branch_name_and_code[1]);

                                            ?>
                                            <i>Profit/Loss Of This Period</i>
                                        </td>
                                        <td align="right">
                                            <?php
                                            $profit_or_loss = ((-1 * $income->amount) - $expance->amount);
                                            if ($profit_or_loss < 0) {
                                                echo " ( " . numberFromatfloat((-1 * $profit_or_loss)) . " )";
                                            } else {
                                                echo numberFromatfloat($profit_or_loss);
                                            }

                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: right">
                                            <i> Total Liabilities & Equity (In BDT)</i>

                                        </td>
                                        <td align="right">
                                            <?php
                                            $TotalLiabilities = $total_Capital_Liabilities_of_a_branch + $profit_or_loss;


                                            if ($TotalLiabilities < 0) {
                                                echo " ( " . numberFromatfloat((-1 * $TotalLiabilities)) . " )";
                                            } else {
                                                echo numberFromatfloat($TotalLiabilities);
                                            }
                                            $TotalLiabilities = 0;
                                            $total_Capital_Liabilities_of_a_branch = 0;
                                            $total_assets_of_a_branch = 0;
                                            $profit_or_loss = 0;
                                            ?>
                                        </td>

                                    </tr>


                                    </tbody>


                                </table>

                            <?php } ?>


                            <!--one branch-->
                            <?php
                            foreach ($balance_sheet as $key => $array) {
                                $branch_name_and_code = explode("~#@~", $key)
                                ?>
                                <table class="table  table-bordered " id="BalanceSheet">
                                    <thead>
                                    <tr style="background-color: #f5f5f5;">
                                        <td align="center" colspan="5"><b><?php echo $branch_name_and_code[0] ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="width: 12%;"><strong>Code</strong></td>
                                        <td align="center" style=" width: 40%;"><strong>Description</strong></td>

                                        <td align="center"><strong>Amount</strong></td>
                                        <td align="center" style="display: none"><strong>Amount</strong></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($array as $key2 => $valueManin) {
                                        $main_group_name_and_code = explode("~#@~", $key2)
                                        ?>
                                        <tr style="background-color: #e1e2e2;">
                                            <td colspan="3" align="center">
                                                <strong><b><?php echo $main_group_name_and_code[0] ?></b>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php
                                        foreach ($valueManin as $key3 => $valueManin2) {
                                            $group_name_and_code = explode("~#@~", $key3)
                                            ?>
                                            <tr style="background-color: #f5f5f5;">
                                                <td>
                                                    <b><?php echo $group_name_and_code[0]; ?></b>
                                                </td>
                                                <td colspan="2">
                                                    <b><?php echo $group_name_and_code[1]; ?></b>
                                                </td>
                                            </tr>
                                            <?php
                                            foreach ($valueManin2 as $key => $value) {
                                                $total_assets = $total_assets + $value->Balance;
                                                ?>
                                                <tr>

                                                    <td><?php echo $value->PN2_Code ?></td>
                                                    <td><?php echo $value->PN2 ?></td>
                                                    <td align="right">
                                                        <?php


                                                        if ($value->Balance < 0) {
                                                            echo " ( " . numberFromatfloat((-1 * $value->Balance)) . " )";
                                                        } else {
                                                            echo numberFromatfloat($value->Balance);
                                                        }

                                                        ?>
                                                    </td>
                                                    <td align="right" style="display: none">
                                                        <?php

                                                        if ($value->Balance < 0) {
                                                            echo " ( " . numberFromatfloat((-1 * $value->Balance)) . " )";
                                                        } else {
                                                            echo $value->Balance;
                                                        }


                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php
                                        }
                                        ?>

                                        <tr>
                                            <td colspan="2" style="text-align: right">
                                                <i> Total <?php echo $main_group_name_and_code[0] ?> (In BDT)</i>
                                            </td>

                                            <td style="text-align: right">
                                                <?php


                                                echo numberFromatfloat($total_assets);

                                                if ($main_group_name_and_code[1] == 1) {
                                                    $total_assets_of_a_branch = $total_assets;
                                                } else {
                                                    $total_Capital_Liabilities_of_a_branch = $total_assets;
                                                }
                                                $total_assets = 0;

                                                ?>
                                            </td>
                                        </tr>

                                        <?php

                                    } ?>


                                    <tr>
                                        <td colspan="2" style="text-align: right">
                                            <?php
                                            $expance = $this->Accounts_model->get_sum_of_a_accounting_group(4, $end_date, $branch_name_and_code[1]);
                                            $income = $this->Accounts_model->get_sum_of_a_accounting_group(3, $end_date, $branch_name_and_code[1]);

                                            ?>
                                            <i>Profit/Loss Of This Period</i>
                                        </td>
                                        <td align="right">
                                            <?php
                                            $profit_or_loss = ((-1 * $income->amount) - $expance->amount);
                                            if ($profit_or_loss < 0) {
                                                echo " ( " . numberFromatfloat((-1 * $profit_or_loss)) . " )";
                                            } else {
                                                echo numberFromatfloat($profit_or_loss);
                                            }

                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: right">
                                            <i> Total Liabilities & Equity (In BDT)</i>

                                        </td>
                                        <td align="right">
                                            <?php
                                            $TotalLiabilities = $total_Capital_Liabilities_of_a_branch + $profit_or_loss;


                                            if ($TotalLiabilities < 0) {
                                                echo " ( " . numberFromatfloat((-1 * $TotalLiabilities)) . " )";
                                            } else {
                                                echo numberFromatfloat($TotalLiabilities);
                                            }
                                            $TotalLiabilities = 0;
                                            $total_Capital_Liabilities_of_a_branch = 0;
                                            $total_assets_of_a_branch = 0;
                                            $profit_or_loss = 0;
                                            ?>
                                        </td>

                                    </tr>


                                    </tbody>


                                </table>

                            <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<style>
    .show_hide {
        display: none;
    }

    .a {
        font: 2em Arial;
        text-decoration: none
    }


</style>

<script>

    $(document).ready(function () {
        $('.show_hide').toggle(function () {
            alert("hello");
            $("#plus51").text("-");


        }, function () {
            $("#plus51").text("+");

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


