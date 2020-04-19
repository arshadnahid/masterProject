<?php
if (isset($_POST['start_date'])):
    $account = $this->input->post('accountHead');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
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
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action=""  method="post" class="form-horizontal">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div style="background-color: grey!important;">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From Date</label>
                                            <div class="col-sm-8">
                                                <input type="text"class="date-picker form-control" id="start_date" name="start_date" value="<?php
                                                if (!empty($from_date)) {
                                                    echo $from_date;
                                                } else {


                                                  echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="end_date" name="end_date" value="<?php
                                                if (!empty($to_date)):
                                                    echo $to_date;
                                                else:
                                                    echo date('d-m-Y');
                                                endif;
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
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
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td align="center"><strong>Particulars</strong></td>
                                        <td align="center"><strong>Total Balance (In BDT.)</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Code: 9000 (chart_type_id: 37) -->
                                    <tr>
                                        <td colspan="2" align="left"><strong>Income ( A )</strong></td>
                                    </tr>
                                    <!-- chart_master -->
                                    <?php
                                    $totalsale = 0;
                                    //get total sales vat
                                    $totalBat = $this->Finane_Model->accountBalance(60, $from_date, $to_date);
                                    foreach ($income as $key => $eachIncome):
                                        foreach ($eachIncome['Accountledger'] as $key => $eachSubIncome):
                                            $allIncome = 0;
                                            $allIncome = $this->Finane_Model->accountBalance($eachSubIncome->chartId, $from_date, $to_date);
                                            if ($eachSubIncome->chartId == '49'):
                                                $allIncome = abs($allIncome) + abs($totalBat);
                                            else:
                                                $allIncome = abs($allIncome);
                                            endif;


                                            if (!empty($allIncome)):
                                                ?>
                                                <tr class="item-row">
                                                    <td style="padding-left:30px;"><a href="<?php echo site_url('generalLedger/' . $eachSubIncome->chartId); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $eachSubIncome->title; ?></a></td>
                                                    <!-- /chart_master -->
                                                    <td align="right"><?php echo number_format((float) abs($allIncome), 2, '.', ','); ?></td>
                                                </tr>
                                                <?php
                                            endif;
                                            $totalsale+=abs($allIncome);
                                        endforeach;
                                        ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td align="left"><strong>Sub-Total</strong></td>
                                        <td align="right"><strong><?php echo number_format((float) $totalsale, 2, '.', ','); ?></strong></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" align="left"><strong>Less Vat ( B )</strong></td>
                                    </tr>
                                    <tr>
                                        <td><a href="<?php echo site_url('generalLedger/60'); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Vat</a></td>
                                        <td align="right"><?php echo number_format((float) abs($totalBat), 2, '.', ','); ?></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" align="left"><strong>Less Cost of goods sold ( C )</strong></td>
                                    </tr>

                                    <?php
                                    $twoa = 1;
                                    $costofGoodsProduct = 0;
                                    foreach ($expense as $row_cta):
                                        ?>
                                        <!-- chart_master -->
                                        <?php
                                        foreach ($row_cta['Accountledger'] as $row_cma):
                                            //deduct only product purchases price
                                            if ($row_cma->parentId == 61):
                                                ?>
                                                <tr>
                                                    <?php
                                                    $total_pvsdebit = '';
                                                    $total_pvscredit = '';

                                                    $total_debit = '';
                                                    $total_credit = '';


                                                    //current transaction
                                                    $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                                    $this->db->from("generalledger");
                                                    $this->db->where('dist_id', $this->dist_id);
                                                    $this->db->where('account', $row_cma->chartId);
                                                    $this->db->where('date >=', $from_date);
                                                    $this->db->where('date <=', $to_date);
                                                    $result = $this->db->get()->row();
                                                    //current transaction
                                                    $total_debit += $result->debit;
                                                    $total_credit += $result->credit;
                                                    ?>

                                                    <?php
                                                    $total_pvsdebit += $total_debit;
                                                    $total_pvscredit += $total_credit;
                                                    $amount = $total_pvsdebit - $total_pvscredit;
                                                    if (!empty($amount)) {
                                                        ?>
                                                        <td><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                        <td align="right"><?php
                                                            echo number_format((float) $total_pvsdebit - $total_pvscredit, 2, '.', ',');

                                                            $costofGoodsProduct+=$total_pvsdebit - $total_pvscredit;
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <!-- /This Period -->
                                                </tr>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                        <!-- /chart_master -->
                                        <?php
                                    endforeach;
                                    ?>
                                    <tr>
                                        <td align="left"><strong>Sub-Total</strong></td>
                                        <td align="right"><strong><?php echo number_format((float) $costofGoodsProduct, 2, '.', ','); ?></strong></td>
                                    </tr>
                                    <?php
                                    $grossProfit = $totalsale - ($costofGoodsProduct + abs($totalBat));
                                    ?>
                                    <tr>
                                        <td  align="right"><strong> Gross Profit / (Loss) ( D = A - ( B + C ) )</strong></td>
                                        <td  align="right"><strong><?php echo number_format((float) $grossProfit, 2, '.', ','); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td  align="left"  colspan="2"><strong> Administrative Cost ( E )</strong></td>
                                    </tr>

                                    <?php
                                    $twoa = 1;
                                    $grossExpense = 0;


                                    foreach ($expense as $row_cta):
                                        $total_pvsdebit = 0;
                                        $total_pvscredit = 0;
                                        foreach ($row_cta['Accountledger'] as $row_cma):
                                            ?>
                                            <tr>
                                                <?php
                                                if ($row_cma->parentId != 61):

                                                    $total_debit = '';
                                                    $total_credit = '';
                                                    //current transaction
                                                    $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                                    $this->db->from("generalledger");
                                                    $this->db->where('dist_id', $this->dist_id);
                                                    $this->db->where('account', $row_cma->chartId);
                                                    $this->db->where('date >=', $from_date);
                                                    $this->db->where('date <=', $to_date);
                                                    $result = $this->db->get()->row();
                                                    //current transaction
                                                    $total_debit += $result->debit;
                                                    $total_credit += $result->credit;
                                                    $grossExpense+=$total_debit - $total_credit;
                                                    $amount = $total_debit - $total_credit;
                                                    if (!empty($amount)):
                                                        ?>
                                                        <td><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                        <td align="right">
                                                            <?php echo number_format((float) $total_debit - $total_credit, 2, '.', ','); ?>
                                                        </td>
                                                        <!-- /This Period -->
                                                    </tr>
                                                    <?php
                                                endif;

                                            endif;
                                        endforeach;
                                        ?>
                                        <!-- /chart_master -->
                                        <?php
                                    endforeach;
                                    ?>
                                    <tr class = "item-row">
                                        <td align="left">
                                            <strong> Sub-Total</strong>
                                        </td>
                                        <!--chart_master-->
                                        <!-- /chart_master -->
                                        <td align="right"><?php echo number_format((float) $grossExpense, 2, '.', ','); ?></td>
                                    </tr>
                                    <tr class="item-row">
                                        <td align="right">
                                            <strong>
                                                Operating Profit / (Loss) ( D - E )
                                            </strong>
                                        </td>
                                        <td align="right"><strong><?php echo number_format((float) $grossProfit - $grossExpense, 2, '.', ','); ?></strong></td>
                                    </tr>
                                    <tr class="item-row">
                                        <td align="right">
                                            <strong>
                                                Total forwarded to balance sheet
                                            </strong>
                                        </td>
                                        <td align="right"><strong><?php echo number_format((float) $grossProfit - $grossExpense, 2, '.', ','); ?></strong></td>

                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else:
                    ?>
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