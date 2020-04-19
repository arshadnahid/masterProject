<style>
    table { border: 1px solid black !important}
    tr { border: 1px solid black}
    table tr td{
        margin:0 !important ; padding: 0 !important ;
    }
    table tr th{
        margin:0 !important ; padding: 0 !important ;
    }
</style>  

<?php
$from_date = $_SESSION["start_date"];
$to_date = $_SESSION["end_date"];

if (isset($from_date)):
    $from_date = $_SESSION["start_date"];
    $to_date = $_SESSION["end_date"];
    $dist_id = $this->dist_id;
else:
    $account = $account;
endif;
?>

<div class="page-content">

    <?php
    if (isset($from_date) && isset($to_date)):
        $sub_total_pvsdebit = '';
        $sub_total_pvscredit = '';
        $sub_total_debit = '';
        $sub_total_credit = '';
        $sub_total_debit_balance = '';
        $sub_total_credit_balance = '';
        ?>
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                
                <table class="table table-responsive">

                    <tr>
                        <td style="text-align:center;" colspan="7">
                            <h3><?php echo $companyInfo->companyName; ?>.</h3>
                            <p><?php echo $companyInfo->dist_address; ?></p>

                            <strong>Phone : </strong><?php echo $companyInfo->dist_phone; ?><br>
                            <strong>Email : </strong><?php echo $companyInfo->dist_email; ?><br>
                            <strong>Website : </strong><?php echo $companyInfo->dis_website; ?><br>
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
                        <!-- chart_class -->


                        <!-- chart_type -->
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date <', $from_date);
                                    $query_pvs = $this->db->get('generalledger')->result_array();



                                    foreach ($query_pvs as $row_pvs):
                                        $total_pvsdebit += $row_pvs['debit'];
                                        $total_pvscredit += $row_pvs['credit'];
                                    endforeach;
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date >=', $from_date);
                                    $this->db->where('date <=', $to_date);
                                    $query = $this->db->get('generalledger')->result_array();
                                    foreach ($query as $row):
                                        $total_debit += $row['debit'];
                                        $total_credit += $row['credit'];
                                    endforeach;
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date <', $from_date);
                                    $query_pvs = $this->db->get('generalledger')->result_array();
                                    foreach ($query_pvs as $row_pvs):
                                        $total_pvsdebit += $row_pvs['debit'];
                                        $total_pvscredit += $row_pvs['credit'];
                                    endforeach;
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date >=', $from_date);
                                    $this->db->where('date <=', $to_date);
                                    $query = $this->db->get('generalledger')->result_array();
                                    foreach ($query as $row):
                                        $total_debit += $row['debit'];
                                        $total_credit += $row['credit'];
                                    endforeach;
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date <', $from_date);
                                    $query_pvs = $this->db->get('generalledger')->result_array();
                                    foreach ($query_pvs as $row_pvs):
                                        $total_pvsdebit += $row_pvs['debit'];
                                        $total_pvscredit += $row_pvs['credit'];
                                    endforeach;
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date >=', $from_date);
                                    $this->db->where('date <=', $to_date);
                                    $query = $this->db->get('generalledger')->result_array();
                                    foreach ($query as $row):
                                        $total_debit += $row['debit'];
                                        $total_credit += $row['credit'];
                                    endforeach;
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

                        foreach ($income as $row_cta):
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date <', $from_date);
                                    $query_pvs = $this->db->get('generalledger')->result_array();
                                    foreach ($query_pvs as $row_pvs):
                                        $total_pvsdebit += $row_pvs['debit'];
                                        $total_pvscredit += $row_pvs['credit'];
                                    endforeach;
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
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date >=', $from_date);
                                    $this->db->where('date <=', $to_date);
                                    $query = $this->db->get('generalledger')->result_array();
                                    foreach ($query as $row):
                                        $total_debit += $row['debit'];
                                        $total_credit += $row['credit'];
                                    endforeach;
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

                        <!-- /chart_class -->
                        <!-- /Assets --> 

                        <!-- /Cost of Goods Sold -->                     
                        <?php
                        $reEarning = 0;
                        $returnEarnig = $this->Common_model->get_single_data_by_single_column('retainearning', 'dist_id', $this->dist_id);
                        if (!empty($returnEarnig->dr)):
                            $reEarning = $returnEarnig->dr;
                        else:
                            $reEarning = $returnEarnig->cr;
                        endif;


                        $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, 49);
                        $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, 49);
                        $openingBalance11 = $total_opencredit - $total_opendebit;

                        $totalVat = 0;
                        $totalsale = 0;
                        $this->db->select('sum(generals.debit) as totalProductSales,sum(generals.vatAmount) as totalVat');
                        $this->db->from('generals');
                        $this->db->where('generals.form_id', '5');
                        $this->db->where('generals.dist_id', $this->dist_id);
                        $ttProductSale = $this->db->get()->row_array();
                        $saleWithoutVat = $ttProductSale['totalProductSales'] - $ttProductSale['totalVat'];
                        $totalVat += $ttProductSale['totalVat'];
                        $totalsale += $saleWithoutVat + $openingBalance11;
                        ?>                   

                        <?php
                        $productCost = '';
                        $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, 62);
                        $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, 62);
                        $openingBalance11 = $total_opendebit - $total_opencredit;

                        $debit_7500 = '';
                        $credit_7500 = '';
                        $this->db->where('account', 62);
                        //$this->db->where('date >=', $from_date);
                        $this->db->where('date <=', $to_date);
                        $this->db->where('dist_id', $this->dist_id);
                        $query = $this->db->get('generalledger')->result_array();
                        foreach ($query as $row):
                            $debit_7500 += $row['debit'];
                            $credit_7500 += $row['credit'];
                        endforeach;
                        $productCost += $debit_7500 - $credit_7500;
                        ?> 

                        <?php
                        $grossProfit = $totalsale - ($productCost + $openingBalance11);

                        $total_balance = '';
                        $operatingExpense = 0;
                        $query_cmx = $this->Finane_Model->getExpenseHead();
                        $totalOpeningBaance = 0;
                        foreach ($query_cmx as $row_cmx):
                            if ($row_cmx->parentId != '61'):
                                ?>                         
                                <?php
                                $total_opendebitExpense = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cmx->chart_id);
                                $total_opencreditExpense = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cmx->chart_id);
                                $openingBalanceExpense = $total_opendebitExpense - $total_opencreditExpense;
                                $totalOpeningBaance+=$openingBalanceExpense;

                                $total_debit = '';
                                $total_credit = '';
                                $this->db->where('dist_id', $this->dist_id);
                                $this->db->where('account', $row_cmx->chart_id);
                                //$this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $query = $this->db->get('generalledger')->result_array();
                                foreach ($query as $row):
                                    $total_debit += $row['debit'];
                                    $total_credit += $row['credit'];
                                endforeach;
                                $operatingExpense += $total_debit - $total_credit;
                                ?> 
                                <?php
                            endif;
                        endforeach;

                        $totalExpense = $operatingExpense + $totalOpeningBaance;


                        $totalRetainEarnig = ($grossProfit - $totalExpense) + $reEarning;
                        ?>  



                        <tr>
                            <td><a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retain earnings</a></td>                                 
                            <!-- PVS Balance -->

                            <td align="right">0.00</td> 
                            <td align="right"><?php echo number_format((float) $totalRetainEarnig, 2, '.', ','); ?></td> 
                            <!-- PVS Balance -->
                            <!-- This Period -->

                            <td align="right">0.00</td> 
                            <td align="right"><?php //echo $totalRetainEarnig;                         ?></td> 
                            <!-- /This Period -->  

                            <!-- Balance --> 

                            <td align="right">0.00</td> 
                            <td align="right"><?php echo number_format((float) $totalRetainEarnig, 2, '.', ','); ?></td> 
                            <!-- /Balance --> 

                        </tr>


                    </tbody>    
                    <tfoot><tr>
                            <td align="right"><strong>Total Ending Balance (In BDT.)</strong></td>
                            <td align="right"><strong><?php echo number_format((float) $sub_total_pvsdebit, 2, '.', ','); ?></strong></td>
                            <td align="right"><strong><?php echo number_format((float) $sub_total_pvscredit + $totalRetainEarnig, 2, '.', ','); ?></strong></td>
                            <td align="right"><strong><?php echo number_format((float) $sub_total_debit, 2, '.', ','); ?></strong></td>
                            <td align="right"><strong><?php echo number_format((float) $sub_total_credit, 2, '.', ','); ?></strong></td>
                            <td align="right"><strong><?php echo number_format((float) $sub_total_debit_balance, 2, '.', ','); ?></strong></td> 
                            <td align="right"><strong><?php echo number_format((float) $sub_total_credit_balance + $totalRetainEarnig, 2, '.', ','); ?></strong></td> 
                        </tr></tfoot>                
                </table>  
            </div>
        </div>
        <?php
    else:
    endif;
    ?>
</div>
