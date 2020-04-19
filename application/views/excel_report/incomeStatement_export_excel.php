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
$account = $_SESSION["account"];
$from_date = $_SESSION["start_date"];
$to_date = $_SESSION["end_date"];

if (isset($from_date)):
    $account = $_SESSION["account"];
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
        //  dumpVar($_POST);

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
            
                <table class="table table-responsive" >

                    <tr>
                        <td style="text-align:center;" colspan="2">
                            <h3><?php echo $companyInfo->companyName; ?>.</h3>
                            <p><?php echo $companyInfo->dist_address; ?>
                            </p>

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
                            <td align="center"><strong>Particulars</strong></td>
                            <td align="center"><strong>Total Balance (In BDT.)</strong></td>
                        </tr>
                    </thead>
                    <tbody>                   

                        <!-- Code: 9000 (chart_type_id: 37) --> 
                        <tr class="item-row">
                            <td style="padding-left:30px;"><a href="<?php echo site_url('generalLedger/' . 49); ?>">Sales Revenue ( A )</a></td> 
                            <!-- chart_master --> 
                            <?php
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
                            <!-- /chart_master --> 
                            <td align="right"><?php echo number_format((float) $saleWithoutVat + $openingBalance11, 2, '.', ','); ?></td>                                  
                        </tr>  
                        <!-- Code: 2700 (chart_type_id: 14) --> 
                        <tr class="item-row">
                            <td  style="padding-left:30px;"><a href="<?php echo site_url('generalLedger/' . 62); ?>">Less Cost Of Goods Sold ( B )</a></td> 
                            <!-- chart_master --> 
                            <?php
                            $productCost = '';
                            ?> 
                            <?php
                            $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, 62);
                            $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, 62);
                            $openingBalance11 = $total_opendebit - $total_opencredit;
                            $debit_7500 = '';
                            $credit_7500 = '';
                            $this->db->where('account', 62);
                            $this->db->where('date >=', $from_date);
                            $this->db->where('date <=', $to_date);
                            $this->db->where('dist_id', $this->dist_id);
                            $query = $this->db->get('generalledger')->result_array();
                            foreach ($query as $row):
                                $debit_7500 += $row['debit'];
                                $credit_7500 += $row['credit'];
                            endforeach;
                            $productCost += $debit_7500 - $credit_7500;
                            ?> 
                            <!-- /chart_master --> 
                            <td align="right"><?php echo number_format((float) $productCost + $openingBalance11, 2, '.', ','); ?></td>  

                        </tr> 

                        <?php
                        $grossProfit = $totalsale - ($productCost + $openingBalance11);
                        ?>

                        <tr>
                            <td  align="right"><strong> Gross profit ( C = A - B )</strong></td>
                            <td  align="right"><strong><?php echo number_format((float) $grossProfit, 2, '.', ','); ?></strong></td>
                        </tr>


                        <tr class="item-row"> 
                            <td style="padding-left:30px;">

                                Administrative  Cost( D )

                            </td>
                            <!-- chart_master --> 
                            <?php
                            $total_balance = '';
                            $operatingExpense = 0;
                            $query_cmx = $this->Finane_Model->getExpenseHead();


                            $totalOpeningBaance = 0;

                            foreach ($query_cmx as $row_cmx):



                                if ($row_cmx->parentId != '61'):
                                    //echo "<br>" . $row_cmx->chart_id;
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
                                    $this->db->where('date >=', $from_date);
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

                            // echo $openingBalanceExpense;


                            $totalExpense = $operatingExpense + $totalOpeningBaance;
                            ?>                     
                            <!-- /chart_master -->                        
                            <td align="right"><?php echo number_format((float) $totalExpense, 2, '.', ','); ?></td> 


                        </tr>

                        <tr class="item-row"> 
                            <td align="right">
                                <strong>
                                    Net Profit for this period ( C - D )
                                </strong>
                            </td>
                            <td align="right"><strong><?php echo number_format((float) $grossProfit - $totalExpense, 2, '.', ','); ?></strong></td> 

                        </tr>
                        <tr class="item-row"> 
                            <td align="right">
                                <strong>
                                    Total forwarded to balance sheet
                                </strong>
                            </td>
                            <td align="right"><strong><?php echo number_format((float) $grossProfit - $totalExpense, 2, '.', ','); ?></strong></td> 

                        </tr>


                    </tbody>    
                </table> 
            </div>
        </div>
    <?php else:
        ?>
    <?php endif; ?>
</div><!-- /.row -->