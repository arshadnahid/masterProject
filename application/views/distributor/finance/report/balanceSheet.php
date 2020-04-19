<?php
if (isset($_POST['to_date'])):
    $to_date = $this->input->post('to_date');
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Balance Sheet </div>
            </div>
            <div class="portlet-body">
                <div class="row ">
                    <div class="col-md-12">
                        <form id="publicForm" action=""  method="post" class="form-horizontal">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div style="background-color: grey!important;">

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"><span style="color:red;"> *</span> As At </label>
                                            <div class="col-sm-8">
                                                <input type="text"class="date-picker form-control" id="start_date" name="to_date" value="<?php
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
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-5">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-sm-5">
                                                <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">
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

                    $to_date = $this->input->post('to_date');
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
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                         <?php echo $to_date; ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td align="center"><strong>Account Code and Name</strong></td>
                                        <td align="center"><strong>Period Balance (In BDT.)</strong></td>
                                        <td align="center"><strong>Prior Year Balance (In BDT.)</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Assets -->
                                    <tr class="item-row">
                                        <td colspan="3" align="center">
                                            <div class="table-header">
                                                ASSETS
                                            </div>
                                        </td>
                                                                <!--<td colspan="3" align="center"><strong>Assets</strong></td>-->
                                    </tr>
                                    <!-- chart_type -->
                                    <?php
                                    foreach ($assetList as $idKey => $row_cta):
                                        $nameInfo = explode("-", $row_cta['parentName']);
                                        ?>
                                        <tr class="item-row">
                                            <td onclick="divShowHide()" data-toggle="collapse" data-target="#demo<?php echo $idKey; ?>" style="padding-left:10px;"> <span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a href="javascript:void(0)">  <?php echo $nameInfo[0]; ?> </a> </td>
                                            <!-- chart_master -->
                                            <?php
                                            $total_balance = '';
                                            foreach ($row_cta['Accountledger'] as $row_cma):
                                                ?>
                                                <?php
                                                // Opening Balance
                                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
                                                $total_debit = '';
                                                $total_credit = '';
                                                //current transaction
                                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                                $this->db->from("generalledger");
                                                $this->db->where('dist_id', $this->dist_id);
                                                $this->db->where('account', $row_cma->chartId);
                                                $this->db->where('date <=', $to_date);
                                                $result = $this->db->get()->row();
                                                echo $this->db->last_query().'<br>';
                                                $total_debit += $result->debit;
                                                $total_credit += $result->credit;

                                                $total_debit += $total_opendebit;
                                                $total_credit += $total_opencredit;
                                                $total_balance += $total_debit - $total_credit;
                                                ?>
                                            <?php endforeach; ?>
                                            <!-- /chart_master -->
                                            <td data-toggle="collapse" align="right"><?php echo number_format((float) $total_balance, 2, '.', ','); ?></td>
                                            <?php $total_assets += $total_balance; ?>
                                            <td align="right">0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div id="demo<?php echo $idKey; ?>" class="collapse">
                                                    <table class="table table-bordered">
                                                        <?php
                                                        foreach ($row_cta['Accountledger'] as $row_cma):
                                                            //opening balance
                                                            $total_opendebit1 = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                            $total_opencredit1 = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);

                                                            $total_debit1 = '';
                                                            $total_credit1 = '';

                                                            //current balance
                                                            $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                                            $this->db->from("generalledger");
                                                            $this->db->where('dist_id', $this->dist_id);
                                                            $this->db->where('account', $row_cma->chartId);
                                                            $this->db->where('date <=', $to_date);
                                                            $result = $this->db->get()->row();

                                                            $total_debit1 += $result->debit;
                                                            $total_credit1 += $result->credit;

                                                            $total_debit1 += $total_opendebit1;
                                                            $total_credit1 += $total_opencredit1;
                                                            $total_balance1 += $total_debit1 - $total_credit1;
                                                            $normalValue = $total_debit1 - $total_credit1;

                                                            if (!empty($normalValue)):
                                                                ?>
                                                                <tr>
                                                                    <td width="38%"><i class="fa fa-minus"></i><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>">&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                                    <td width="30%" align="right">

                                                                        <?php echo number_format((float) $total_debit1 - $total_credit1, 2, '.', ','); ?>

                                                                    </td>
                                                                    <td  align="right">0.00</td>
                                                                </tr>
                                                                <?php
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    ?>
                                    <!-- /chart_type -->

                                    <!-- /chart_class -->
                                    <!-- /Assets -->
                                    <tr>
                                        <td align="right"><strong>Total Assets (In BDT.)</strong></td>
                                        <td align="right"><strong> <?php echo number_format((float) $total_assets, 2, '.', ','); ?></strong></td>
                                        <td align="right"><strong> 0.00</strong></td>
                                    </tr>

                                    <!-- Liabilities -->
                                    <tr class="item-row"><td colspan="3" align="center">
                                            <div class="table-header">
                                                LIABILITIES & EQUITY
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    //dumpVar($liabilityList);


                                    foreach ($liabilityList as $idKey => $row_ctl):

                                        $total_balance = '';

                                        foreach ($row_ctl['Accountledger'] as $row_cml):

                                            // Opening Balance
                                            $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cml->chartId);
                                            $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cml->chartId);

                                            $total_debit = '';
                                            $total_credit = '';

                                            //current transaction
                                            $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                            $this->db->from("generalledger");
                                            $this->db->where('dist_id', $this->dist_id);
                                            $this->db->where('account', $row_cml->chartId);
                                            $this->db->where('date <=', $to_date);
                                            $result = $this->db->get()->row();

                                            $total_debit += $result->debit;
                                            $total_credit += $result->credit;

                                            $total_debit += $total_opendebit;
                                            $total_credit += $total_opencredit;
                                            $total_balance += $total_credit - $total_debit;
                                        endforeach;
                                        $normalAccount = $total_credit - $total_debit;
                                        // if(!empty($normalAccount)):
                                        ?>


                                        <tr  class="item-row">
                                            <td data-toggle="collapse" data-target="#demo<?php echo $idKey; ?>" style="padding-left:10px;"> <span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a  href="javascript:void(0)"> &nbsp;<?php
                                                    $mainTitle = explode("-", $row_ctl['parentName']);
                                                    $words = preg_replace('/[0-9]+/', '', $row_ctl['parentName']);
                                                    echo str_replace("-", "", $words);
                                                    ?></a> </td>
                                            <!-- chart_master -->

                                            <!-- /chart_master -->
                                            <td align="right"><?php echo number_format((float) $total_balance, 2, '.', ','); ?></td>
                                            <?php $total_liabilityies += $total_balance; ?>
                                            <td align="right">0.00</td>
                                        </tr>

                                        <tr>
                                            <td colspan="3">
                                                <div id="demo<?php echo $idKey; ?>" class="collapse">
                                                    <table class="table table-bordered">
                                                        <?php
                                                        foreach ($row_ctl['Accountledger'] as $row_cma):
                                                            //opening transaction
                                                            $total_opendebit1 = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                            $total_opencredit1 = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
                                                            $total_debit1 = '';
                                                            $total_credit1 = '';

                                                            $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                                            $this->db->from("generalledger");
                                                            $this->db->where('dist_id', $this->dist_id);
                                                            $this->db->where('account', $row_cma->chartId);
                                                            $this->db->where('date <=', $to_date);
                                                            $result = $this->db->get()->row();
                                                            //current transaction
                                                            $total_debit1 += $result->debit;
                                                            $total_credit1 += $result->credit;

                                                            $total_debit1 += $total_opendebit1;
                                                            $total_credit1 += $total_opencredit1;
                                                            $total_balance1 += $total_debit1 - $total_credit1;
                                                            $availab = $total_debit1 - $total_credit1;
                                                            if (!empty($availab)):
                                                                ?>
                                                                <tr >
                                                                    <td width="38%"><i class="fa fa-minus"></i><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>" >&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                                    <td width="30%" align="right">

                                                                        <?php echo number_format((float) abs($total_debit1 - $total_credit1), 2, '.', ','); ?>

                                                                    </td>
                                                                    <td  align="right">0.00</td>
                                                                </tr>
                                                                <?php
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    $totalsale = 0;
                                    $totalBat = $this->Finane_Model->accountBalance(60, '', $to_date);
                                    foreach ($income as $key => $eachIncome):
                                        foreach ($eachIncome['Accountledger'] as $key => $eachSubIncome):
                                            $allIncome = 0;
                                            $allIncome = $this->Finane_Model->accountBalance($eachSubIncome->chartId, '', $to_date);
                                            if ($eachSubIncome->chartId == '49'):
                                                $allIncome = $allIncome + $totalBat;
                                            else:
                                                $allIncome = $allIncome;
                                            endif;
                                            $totalsale+=abs($allIncome);
                                        endforeach;
                                    endforeach;

                                    //get total sales revenew
//                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, 49);
//                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, 49);
//                                $openingBalance11 = $total_opencredit - $total_opendebit;
//                                $totalVat = 0;
//                                $totalsale = 0;
//                                $this->db->select('sum(generals.debit) as totalProductSales,sum(generals.vatAmount) as totalVat');
//                                $this->db->from('generals');
//                                $this->db->where('generals.form_id', '5');
//                                $this->db->where('generals.date <=', $to_date);
//                                $this->db->where('generals.dist_id', $this->dist_id);
//                                $ttProductSale = $this->db->get()->row_array();
//                                $saleWithoutVat = $ttProductSale['totalProductSales'] - $ttProductSale['totalVat'];
//                                $totalVat += $ttProductSale['totalVat'];
//                                $totalsale += $saleWithoutVat + $openingBalance11;
//less total cost of produt sold
                                    $twoa = 1;
                                    $costofGoodsProduct = 0;
                                    foreach ($expense as $row_cta):

                                        foreach ($row_cta['Accountledger'] as $row_cma):
                                            //deduct only product purchases price
                                            if ($row_cma->parentId == 61):
                                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
                                                $total_pvsdebit = '';
                                                $total_pvscredit = '';
                                                $total_debit = '';
                                                $total_credit = '';


                                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                                $this->db->from("generalledger");
                                                $this->db->where('dist_id', $this->dist_id);
                                                $this->db->where('account', $row_cma->chartId);
                                                $this->db->where('date <=', $to_date);
                                                $result = $this->db->get()->row();
                                                //current transaction
                                                $total_debit += $result->debit;
                                                $total_credit += $result->credit;

                                                $total_pvsdebit += $total_debit;
                                                $total_pvscredit += $total_credit;
                                                $costofGoodsProduct+=$total_pvsdebit - $total_pvscredit;

                                            endif;
                                        endforeach;
                                    endforeach;
                                    //get gross profit
                                    $grossProfit = $totalsale - $costofGoodsProduct;
                                    //get administrative expense
                                    $twoa = 1;
                                    $grossExpense = 0;
                                    foreach ($expense as $row_cta):
                                        foreach ($row_cta['Accountledger'] as $row_cma):
                                            if ($row_cma->parentId != 61):
                                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
                                                $total_pvsdebit = '';
                                                $total_pvscredit = '';
                                                $total_debit = '';
                                                $total_credit = '';
                                                //current transaction
                                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                                $this->db->from("generalledger");
                                                $this->db->where('dist_id', $this->dist_id);
                                                $this->db->where('account', $row_cma->chartId);
                                                $this->db->where('date <=', $to_date);
                                                $result = $this->db->get()->row();
                                                //current transaction
                                                $total_debit += $result->debit;
                                                $total_credit += $result->credit;

                                                $total_pvsdebit += $total_debit;
                                                $total_pvscredit += $total_credit;
                                                $grossExpense+=$total_pvsdebit - $total_pvscredit;
                                            endif;
                                        endforeach;
                                    endforeach;
//get opening return earning
                                    $reEarning = 0;
                                    $returnEarnig = $this->Common_model->get_single_data_by_single_column('retainearning', 'dist_id', $this->dist_id);
                                    if (!empty($returnEarnig->dr)):
                                        $reEarning = $returnEarnig->dr;
                                    else:
                                        $reEarning = $returnEarnig->cr;
                                    endif;
                                    ?>
                                    <tr>
                                        <td data-toggle="collapse" data-target="#demoReturn" style="padding-left:10px;"><span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a href="javascript:void(0)" >&nbsp;Profit / ( Loss )</a></td>
                                        <td align="right"><?php echo number_format((float) ($grossProfit - $grossExpense) + $reEarning, 2, '.', ','); ?></td>
                                        <td align="right"><strong> 0.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><strong>Total Liabilities & Equity (In BDT.)</strong></td>
                                        <td align="right"><strong>  <?php echo number_format((float) $total_liabilityies + (($grossProfit - $grossExpense) + $reEarning), 2, '.', ','); ?></strong></td>
                                        <td align="right"><strong> 0.00</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
    </div>
    <style>
        .show_hide {
            display:none;
        }
        .a{
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
