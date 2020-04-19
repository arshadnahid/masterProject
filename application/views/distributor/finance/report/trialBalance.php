<?php
if (isset($_POST['start_date'])):
    $account = $this->input->post('accountHead');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>
<div class="row">
    <div class="col-md-12">
        <form id="publicForm" action=""  method="post" class="form-horizontal">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="table-header">
                    Trial Balance
                </div>

                <br>
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
    $sub_total_pvsdebit = '';
    $sub_total_pvscredit = '';
    $sub_total_debit = '';
    $sub_total_credit = '';
    $sub_total_debit_balance = '';
    $sub_total_credit_balance = '';
    unset($_SESSION["start_date"]);
    unset($_SESSION["end_date"]);
    $_SESSION["start_date"] = $from_date;
    $_SESSION["end_date"] = $to_date;
    $dist_id = $this->dist_id;
    $finalTrialBalancedr = '';
    $finalTrialBalancecr = '';
    ?>
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="table-header">
                Trial Balance <span style="color:greenyellow;">From <?php echo $from_date; ?> To <?php echo $to_date; ?></span>

            </div>
            <!--                        <div class="noPrint">
                                    <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('FinaneController/trialBalance_export_excel/') ?>" class="btn btn-success pull-right">
                                        <i class="ace-icon fa fa-download"></i>
                                        Excel 
                                    </a></div>-->
            <table class="table table-responsive">
                <tr>
                    <td style="text-align:center;">
                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                        <span><?php echo $companyInfo->address; ?></span><br>
                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                        <strong><?php echo $pageTitle; ?></strong>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td rowspan="2" align="center" style="padding-top:15px;"><strong>Account Name</strong></td>
                        <td colspan="2" align="center"><strong>Brought Forward</strong></td>
                        <td colspan="2" align="center"><strong>This Period</strong></td>
                        <td colspan="2" align="center"><strong>Balance (In BDT.)</strong></td>
                    </tr>
                    <tr>
                        <td align="center"><strong>Debit</strong></td>
                        <td align="center"><strong>Credit</strong></td>
                        <td align="center"><strong>Debit</strong></td>
                        <td align="center"><strong>Credit</strong></td>
                        <td align="center"><strong>Debit</strong></td>
                        <td align="center"><strong>Credit</strong></td>
                    </tr>
                </thead>
                <tbody> 
                    <!-- Assets -->
                    <tr class="item-row">
                        <td colspan="7"><strong>A. Assets</strong></td> 
                    </tr>                    

                    <?php
                    $twoa = 1;
                    foreach ($assetList as $row_cta):
                        ?>                         
                        <tr class="item-row">
                            <td colspan="7"><strong>&nbsp;&nbsp;A.<?php
                                    if ($row_cta['parentName']): echo $twoa;
                                    endif;
                                    ?> <?php echo $row_cta['parentName']; ?></strong>
                            </td> 
                        </tr> 
                        <!-- chart_master --> 
                        <?php
                        foreach ($row_cta['Accountledger'] as $row_cma):
                            ?>                         
                            <tr>
                                <td><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_cma->title; ?>[<?php echo $row_cma->code; ?>]</a></td>                                 
                                <!-- PVS Balance -->
                                <?php
                                // Opening Balance
                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
                                $total_pvsdebit = '';
                                $total_pvscredit = '';


                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date <', $from_date);
                                $result = $this->db->get()->row();

                                $total_pvsdebit += $result->debit;
                                $total_pvscredit += $result->credit;


                                $total_pvsdebit += $total_opendebit;
                                $total_pvscredit += $total_opencredit;
                                ?>   
                                <!--Opening trial balance start -->
                                <td align="right">
                                    <?php
                                    if ($total_pvsdebit >= $total_pvscredit): echo number_format((float) $total_pvsdebit - $total_pvscredit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>
                                </td> 
                                <td align="right">
                                    <?php
                                    if ($total_pvsdebit < $total_pvscredit): echo number_format((float) $total_pvscredit - $total_pvsdebit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>
                                </td> 


                                <?php
                                $sub_total_pvsdebit += $total_pvsdebit;
                                $sub_total_pvscredit += $total_pvscredit;
                                ?>
                                <!-- This Period -->
                                <?php
                                $total_debit = '';
                                $total_credit = '';


                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $result = $this->db->get()->row();

                                $total_debit += $result->debit;
                                $total_credit += $result->credit;
                                ?> 
                                <td align="right">
                                    <?php
                                    if ($total_debit >= $total_credit): echo number_format((float) $total_debit - $total_credit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>
                                </td> 

                                <td align="right">
                                    <?php
                                    if ($total_debit < $total_credit): echo number_format((float) $total_credit - $total_debit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>

                                </td> 

                                <!--present trial balance end -->
                                <!--paresent trial balance start -->
                                <!-- /This Period -->  
                                <?php
                                $sub_total_debit += $total_debit;
                                $sub_total_credit += $total_credit;
                                ?>                                
                                <!-- Balance --> 
                                <?php
                                $debit_balance = $total_pvsdebit + $total_debit;
                                $credit_balance = $total_pvscredit + $total_credit;
                                ?> 
                                <!--Present trial balance End -->
                                <!--closing trial balance start -->

                                <td align="right">
                                    <?php
                                    if ($debit_balance >= $credit_balance): echo number_format((float) $debit_balance - $credit_balance, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>

                                </td> 
                                <td align="right">

                                    <?php
                                    if ($debit_balance < $credit_balance): echo number_format((float) $credit_balance - $debit_balance, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>
                                </td> 
                                <!-- /Balance --> 
                                <?php
                                $sub_total_debit_balance += $debit_balance;
                                $sub_total_credit_balance += $credit_balance;
                                $finalTrialBalancedr +=abs($debit_balance - $credit_balance);
                                $finalTrialBalancecr +=abs($credit_balance - $debit_balance);
                                ?>                                 
                            </tr>




                        <?php endforeach; ?>                     
                        <!-- /chart_master --> 
                        <?php $twoa++; ?>
                    <?php endforeach; ?>                     
                    <!-- /chart_type -->                         


                    <tr class="item-row">
                        <td colspan="7"><strong>B. Liability</strong></td> 
                    </tr>                    
                    <!-- chart_class -->


                    <!-- chart_type -->
                    <?php
                    $twoa = 1;

                    foreach ($liabilityList as $row_cta):
                        ?>                         
                        <tr class="item-row">
                            <td colspan="7"><strong>&nbsp;&nbsp;B.<?php
                                    if ($row_cta['parentName']): echo $twoa;
                                    endif;
                                    ?>. <?php echo $row_cta['parentName']; ?></strong>
                            </td> 
                        </tr> 
                        <!-- chart_master --> 
                        <?php
                        foreach ($row_cta['Accountledger'] as $row_cma):
                            ?>                         
                            <tr>
                                <td><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_cma->title; ?> [ <?php echo $row_cma->code; ?>]</a></td>                                 
                                <!-- PVS Balance -->
                                <?php
                                // Opening Balance
                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);

                                $total_pvsdebit = '';
                                $total_pvscredit = '';



                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date <', $from_date);
                                $result = $this->db->get()->row();

                                $total_pvsdebit += $result->debit;
                                $total_pvscredit += $result->credit;

                                $total_pvsdebit += $total_opendebit;
                                $total_pvscredit += $total_opencredit;
                                ?>     
                                <td align="right"><?php
                                    if ($total_pvsdebit >= $total_pvscredit): echo number_format((float) $total_pvsdebit - $total_pvscredit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($total_pvsdebit < $total_pvscredit): echo number_format((float) $total_pvscredit - $total_pvsdebit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- PVS Balance -->
                                <?php
                                $sub_total_pvsdebit += $total_pvsdebit;
                                $sub_total_pvscredit += $total_pvscredit;
                                ?>
                                <!-- This Period -->
                                <?php
                                $total_debit = '';
                                $total_credit = '';


                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $result = $this->db->get()->row();

                                $total_debit += $result->debit;
                                $total_credit += $result->credit;
                                ?> 
                                <td align="right"><?php
                                    if ($total_debit >= $total_credit): echo number_format((float) $total_debit - $total_credit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($total_debit < $total_credit): echo number_format((float) $total_credit - $total_debit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- /This Period -->  
                                <?php
                                $sub_total_debit += $total_debit;
                                $sub_total_credit += $total_credit;
                                ?>                                
                                <!-- Balance --> 
                                <?php
                                $debit_balance = $total_pvsdebit + $total_debit;
                                $credit_balance = $total_pvscredit + $total_credit;
                                ?> 
                                <td align="right"><?php
                                    if ($debit_balance >= $credit_balance): echo number_format((float) $debit_balance - $credit_balance, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($debit_balance < $credit_balance): echo number_format((float) $credit_balance - $debit_balance, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- /Balance --> 
                                <?php
                                $sub_total_debit_balance += $debit_balance;
                                $sub_total_credit_balance += $credit_balance;
                                ?>                                 
                            </tr>
                        <?php endforeach; ?>                     
                        <!-- /chart_master --> 
                        <?php $twoa++; ?>
                    <?php endforeach; ?>     


                    <tr class="item-row">
                        <td colspan="7"><strong>C. Income</strong></td> 
                    </tr>                    
                    <!-- chart_class -->


                    <!-- chart_type -->
                    <?php
                    $twoa = 1;

                    foreach ($income as $row_cta):
                        ?>                         
                        <tr class="item-row">
                            <td colspan="7"><strong>&nbsp;&nbsp;C.<?php
                                    if ($row_cta['parentName']): echo $twoa;
                                    endif;
                                    ?>. <?php echo $row_cta['parentName']; ?></strong>
                            </td> 
                        </tr> 
                        <!-- chart_master --> 
                        <?php
                        foreach ($row_cta['Accountledger'] as $row_cma):
                            ?>                         
                            <tr>
                                <td><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_cma->title; ?> [ <?php echo $row_cma->code; ?> ] </a></td>                                 
                                <!-- PVS Balance -->
                                <?php
                                // Opening Balance
                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);

                                $total_pvsdebit = '';
                                $total_pvscredit = '';


                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date <', $from_date);
                                $result = $this->db->get()->row();

                                $total_pvsdebit += $result->debit;
                                $total_pvscredit += $result->credit;

                                $total_pvsdebit += $total_opendebit;
                                $total_pvscredit += $total_opencredit;
                                ?>     
                                <td align="right"><?php
                                    if ($total_pvsdebit >= $total_pvscredit): echo number_format((float) $total_pvsdebit - $total_pvscredit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($total_pvsdebit < $total_pvscredit): echo number_format((float) $total_pvscredit - $total_pvsdebit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- PVS Balance -->
                                <?php
                                $sub_total_pvsdebit += $total_pvsdebit;
                                $sub_total_pvscredit += $total_pvscredit;
                                ?>
                                <!-- This Period -->
                                <?php
                                $total_debit = '';
                                $total_credit = '';
                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $result = $this->db->get()->row();
                                $total_debit += $result->debit;
                                $total_credit += $result->credit;
                                ?> 
                                <td align="right"><?php
                                    if ($total_debit >= $total_credit): echo number_format((float) $total_debit - $total_credit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($total_debit < $total_credit): echo number_format((float) $total_credit - $total_debit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- /This Period -->  
                                <?php
                                $sub_total_debit += $total_debit;
                                $sub_total_credit += $total_credit;
                                ?>                                
                                <!-- Balance --> 
                                <?php
                                $debit_balance = $total_pvsdebit + $total_debit;
                                $credit_balance = $total_pvscredit + $total_credit;
                                ?> 
                                <td align="right"><?php
                                    if ($debit_balance >= $credit_balance): echo number_format((float) $debit_balance - $credit_balance, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($debit_balance < $credit_balance): echo number_format((float) $credit_balance - $debit_balance, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- /Balance --> 
                                <?php
                                $sub_total_debit_balance += $debit_balance;
                                $sub_total_credit_balance += $credit_balance;
                                ?>                                 
                            </tr>
                        <?php endforeach; ?>                     
                        <!-- /chart_master --> 
                        <?php $twoa++; ?>
                    <?php endforeach; ?>

                    <tr class="item-row">
                        <td colspan="7"><strong>D. Expense</strong></td> 
                    </tr>                    
                    <!-- chart_class -->


                    <!-- chart_type -->
                    <?php
                    $twoa = 1;
                    $expenseDebetBalance = 0;
                    foreach ($expense as $row_cta):
                        ?>                         
                        <tr class="item-row">
                            <td colspan="7"><strong>&nbsp;&nbsp;D.<?php
                                    if ($row_cta['parentName']): echo $twoa;
                                    endif;
                                    ?>. <?php echo $row_cta['parentName']; ?></strong>
                            </td> 
                        </tr> 
                        <!-- chart_master --> 
                        <?php
                        foreach ($row_cta['Accountledger'] as $row_cma):
                            ?>                         
                            <tr>
                                <td><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_cma->title; ?> [ <?php echo $row_cma->code; ?> ]</a></td>                                 
                                <!-- PVS Balance -->
                                <?php
                                // Opening Balance
                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);

                                $total_pvsdebit = '';
                                $total_pvscredit = '';

                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date <', $from_date);
                                $result = $this->db->get()->row();
                                $total_pvsdebit += $result->debit;
                                $total_pvscredit += $result->credit;

                                $total_pvsdebit += $total_opendebit;
                                $total_pvscredit += $total_opencredit;
                                ?>     
                                <td align="right">
                                    <?php
                                    if ($total_pvsdebit >= $total_pvscredit): echo number_format((float) $total_pvsdebit - $total_pvscredit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>
                                </td> 
                                <td align="right">
                                    <?php
                                    if ($total_pvsdebit < $total_pvscredit): echo number_format((float) $total_pvscredit - $total_pvsdebit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?>
                                </td> 
                                <!-- PVS Balance -->
                                <?php
                                $sub_total_pvsdebit += $total_pvsdebit;
                                $sub_total_pvscredit += $total_pvscredit;
                                ?>
                                <!-- This Period -->
                                <?php
                                $total_debit = '';
                                $total_credit = '';
                                $this->db->select("sum(debit) as debit,sum(credit) as credit");
                                $this->db->from("generalledger");
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cma->chartId);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $result = $this->db->get()->row();
                                $total_debit += $result->debit;
                                $total_credit += $result->credit;
                                ?> 
                                <td align="right"><?php
                                    if ($total_debit >= $total_credit): echo number_format((float) $total_debit - $total_credit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($total_debit < $total_credit): echo number_format((float) $total_credit - $total_debit, 2, '.', ',');
                                    else: echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- /This Period -->  
                                <?php
                                $sub_total_debit += $total_debit;
                                $sub_total_credit += $total_credit;
                                ?>                                
                                <!-- Balance --> 
                                <?php
                                $debit_balance = $total_pvsdebit + $total_debit;
                                $credit_balance = $total_pvscredit + $total_credit;
                                ?> 
                                <td align="right"><?php
                                    if ($debit_balance >= $credit_balance):
                                        echo number_format((float) $debit_balance - $credit_balance, 2, '.', ',');

                                    else:
                                        echo '0.00';
                                    endif;
                                    ?></td> 
                                <td align="right"><?php
                                    if ($debit_balance < $credit_balance):
                                        echo number_format((float) $credit_balance - $debit_balance, 2, '.', ',');

                                    else:
                                        echo '0.00';
                                    endif;
                                    ?></td> 
                                <!-- /Balance --> 
                                <?php
                                $sub_total_debit_balance += $debit_balance;
                                //$expenseDebetBalance += $debit_balance;
                                $sub_total_credit_balance += $credit_balance;
                                ?>                                 
                            </tr>
                        <?php endforeach; ?>                     
                        <!-- /chart_master --> 
                        <?php $twoa++; ?>
                        <?php
                    endforeach;


                    $opnignReturn = $this->db->get_where('retainearning', array('dist_id' => $this->dist_id))->row()->cr;
                    ?>











                </tbody>    
                <tfoot>
                    <tr>
                        <td align="right"><strong>Total Ending Balance (In BDT.)</strong></td>
                        <td align="right">
                            <strong><?php
                                echo number_format((float) $sub_total_pvsdebit, 2, '.', ',');
                                ?></strong>
                        </td>
                        <td align="right"><strong><?php
                                echo number_format((float) $sub_total_pvscredit + $opnignReturn, 2, '.', ',');
                                ?></strong>
                        </td>
                        <td align="right"><strong><?php echo number_format((float) $sub_total_debit, 2, '.', ','); ?></strong></td>
                        <td align="right"><strong><?php echo number_format((float) $sub_total_credit, 2, '.', ','); ?></strong></td>

                        <td align="right"><strong><?php echo number_format((float) $sub_total_debit_balance, 2, '.', ','); ?></strong></td> 
                        <td align="right"><strong><?php echo number_format((float) $sub_total_credit_balance + $opnignReturn, 2, '.', ','); ?></strong></td> 



                    </tr>
                </tfoot>                
            </table>  
        </div>
    </div>
    <?php
else:
endif;
?>



