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
$to_date = $_SESSION["to_date"];
if (isset($to_date)):
   $to_date = $_SESSION["to_date"];
endif;
?>

<div class="page-content">

    <?php
    if (isset($to_date)):

        $to_date = $to_date;
        $dist_id = $this->dist_id;
        $total_income = '';
        $total_costs = '';
        $total_assets = '';
        $total_liabilityies = '';
        $total_equity_govt = '';
        ?>
        <div class="row">
          
                <table class="table table-responsive">

                    <tr>
                        <td style="text-align:center;" colspan="3">
                            <h3><?php echo $companyInfo->companyName; ?>.</h3>
                            <p><?php echo $companyInfo->dist_address; ?>
                            </p>

                            <strong>Phone : </strong><?php echo $companyInfo->dist_phone; ?><br>
                            <strong>Email : </strong><?php echo $companyInfo->dist_email; ?><br>
                            <strong>Website : </strong><?php echo $companyInfo->dis_website; ?><br>
                            <strong><?php echo $pageTitle; ?> </strong>



                        </td>
                    </tr>

                </table>

                <table class="table table-bordered">
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
                                    Assets

                                </div>
                            </td>
                            
                        </tr> 

                        <?php
                        foreach ($assetList as $idKey => $row_cta):
                            $nameInfo = explode("-", $row_cta['parentName']);
                            ?>    
                            <tr class="item-row" style="background-color:grey;color:white;"> 
                                <td onclick="divShowHide()" data-toggle="collapse" data-target="#demo<?php echo $idKey; ?>" style="padding-left:10px;"> <span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a href="javascript:void(0)" style="color:white;">  <?php echo $nameInfo[0]; ?> </a> </td> 
                             
                                <?php
                                $total_balance = '';

                                foreach ($row_cta['Accountledger'] as $row_cma):

                                    ?> 
                                    <?php
                               
                                    $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                    $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
                                    $total_debit = '';
                                    $total_credit = '';
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cma->chartId);
                                    $this->db->where('date <=', $to_date);
                                    $query = $this->db->get('generalledger')->result_array();
                                    foreach ($query as $row):
                                        $total_debit += $row['debit'];
                                        $total_credit += $row['credit'];
                                    endforeach;
                                    $total_debit += $total_opendebit;
                                    $total_credit += $total_opencredit;
                                    $total_balance += $total_debit - $total_credit;
                                    ?> 
                                <?php endforeach; ?>                     
                           
                                <td data-toggle="collapse" align="right"><?php echo number_format((float) $total_balance, 2, '.', ','); ?></td>  

                                <?php $total_assets += $total_balance; ?>    
                                <td align="right">0.00</td> 
                            </tr>
                            <tr>
                                <td colspan="3">
                                    

                                        <table class="table table-bordered">

                                            <?php
                                            foreach ($row_cta['Accountledger'] as $row_cma):
                                                $total_opendebit1 = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                $total_opencredit1 = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);

//                                                        $this->Finane_Model->


                                                $total_debit1 = '';
                                                $total_credit1 = '';
                                                $this->db->where('dist_id', $this->dist_id);
                                                $this->db->where('account', $row_cma->chartId);
                                                $this->db->where('date <=', $to_date);
                                                $query = $this->db->get('generalledger')->result_array();
                                                foreach ($query as $row):
                                                    $total_debit1 += $row['debit'];
                                                    $total_credit1 += $row['credit'];
                                                endforeach;

                                                $total_debit1 += $total_opendebit1;
                                                $total_credit1 += $total_opencredit1;
                                                $total_balance1 += $total_debit1 - $total_credit1;
                                                ?>
                                                <tr  style="background-color:green;color:white;">
                                                    <td width="38%"><i class="fa fa-minus"></i><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>" style="color:white;">&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                    <td width="30%" align="right">

                                                        <?php echo number_format((float) $total_debit1 - $total_credit1, 2, '.', ','); ?>

                                                    </td>
                                                    <td  align="right">0.00</td>
                                                </tr>
                                            <?php endforeach; ?>

                                        </table>
                               
                                </td>
                            </tr>
                            <?php
                        endforeach;
                        ?>                     
                      
                        <tr>
                            <td align="right"><strong>Total Assets (In BDT.)</strong></td>
                            <td align="right"><strong> <?php echo number_format((float) $total_assets, 2, '.', ','); ?></strong></td>
                            <td align="right"><strong> 0.00</strong></td>
                        </tr>

                     
                        <tr class="item-row"><td colspan="3" align="center">
                                <div class="table-header">
                                    LIABILITIES & EQUITY
                                </div>
                            </td>
                        </tr> 

                        <?php
                        foreach ($liabilityList as $idKey => $row_ctl):
                        
                            ?>  
                            <tr style="background-color:grey;color:white;" class="item-row"> 
                                <td data-toggle="collapse" data-target="#demo<?php echo $idKey; ?>" style="padding-left:10px;"> <span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a style="color:white;" href="javascript:void(0)"> &nbsp;<?php
                    $mainTitle = explode("-", $row_ctl['parentName']);

                    echo $mainTitle[0];
                            ?></a> </td>  
                               
                                <?php
                                $total_balance = '';

                                foreach ($row_ctl['Accountledger'] as $row_cml):
                                    ?>                         
                                    <?php
                              
                                    $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cml->chartId);
                                    $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cml->chartId);

                                    $total_debit = '';
                                    $total_credit = '';
                                    $this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('account', $row_cml->chartId);
                                    $this->db->where('date <=', $to_date);
                                    $query = $this->db->get('generalledger')->result_array();
                                    foreach ($query as $row):
                                        $total_debit += $row['debit'];
                                        $total_credit += $row['credit'];
                                    endforeach;
                                    $total_debit += $total_opendebit;
                                    $total_credit += $total_opencredit;
                                    $total_balance += $total_credit - $total_debit;
                                    ?> 
                                <?php endforeach; ?>                     
                                                   
                                <td align="right"><?php echo number_format((float) $total_balance, 2, '.', ','); ?></td> 
                                <?php $total_liabilityies += $total_balance; ?> 
                                <td align="right">0.00</td>
                            </tr>

                            <tr>
                                <td colspan="3">
                                 

                                        <table class="table table-bordered">

                                            <?php
                                            foreach ($row_ctl['Accountledger'] as $row_cma):
                                                $total_opendebit1 = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                $total_opencredit1 = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);
                                                $total_debit1 = '';
                                                $total_credit1 = '';
                                                $this->db->where('dist_id', $this->dist_id);
                                                $this->db->where('account', $row_cma->chartId);
                                                $this->db->where('date <=', $to_date);
                                                $query = $this->db->get('generalledger')->result_array();
                                                foreach ($query as $row):
                                                    $total_debit1 += $row['debit'];
                                                    $total_credit1 += $row['credit'];
                                                endforeach;

                                                $total_debit1 += $total_opendebit1;
                                                $total_credit1 += $total_opencredit1;
                                                $total_balance1 += $total_debit1 - $total_credit1;
                                                $availab = $total_debit1 - $total_credit1;
                                                if (!empty($availab)):
                                                    ?>
                                                    <tr  style="background-color:green;color:white;">
                                                        <td width="38%"><i class="fa fa-minus"></i><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>" style="color:white;">&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
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
                               
                                </td>
                            </tr>


                        <?php endforeach; ?>                     
                      
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
                        ?>                     

                        <?php
                        /*
                          $total_pvsdebit = '';
                          $total_pvscredit = '';

                          $total_debit = '';
                          $total_credit = '';
                          $total_balance = '';

                          $totalVat = 0;
                          $totalsale = 0;
                          $this->db->select('sum(generals.debit) as totalProductSales,sum(generals.vatAmount) as totalVat');
                          $this->db->from('generals');
                          $this->db->where('generals.form_id', '5');
                          $this->db->where('generals.date <=', $to_date);
                          $this->db->where('generals.dist_id', $this->dist_id);
                          $ttProductSale = $this->db->get()->row_array();
                          $saleWithoutVat = $ttProductSale['totalProductSales'] - $ttProductSale['totalVat'];
                          $totalVat += $ttProductSale['totalVat'];
                          $totalsale += $saleWithoutVat;
                          $productCost = '';
                          $debit_7500 = '';
                          $credit_7500 = '';
                          $this->db->where('account', 62);
                          $this->db->where('date <=', $to_date);
                          $this->db->where('dist_id', $this->dist_id);
                          $query = $this->db->get('generalledger')->result_array();
                          foreach ($query as $row):
                          $debit_7500 += $row['debit'];
                          $credit_7500 += $row['credit'];
                          endforeach;
                          $productCost += $debit_7500 - $credit_7500;

                          $grossProfit = $totalsale - $productCost;

                          $total_balance = '';
                          $operatingExpense = 0;
                          $query_cmx = $this->Finane_Model->getExpenseHead();
                          foreach ($query_cmx as $row_cmx):
                          if ($row_cmx->chart_id != '62'):

                          $total_debit = '';
                          $total_credit = '';
                          $this->db->where('dist_id', $this->dist_id);
                          $this->db->where('account', $row_cmx->chart_id);
                          $this->db->where('date <=', $to_date);
                          $query = $this->db->get('generalledger')->result_array();
                          foreach ($query as $row):
                          $total_debit += $row['debit'];
                          $total_credit += $row['credit'];
                          endforeach;
                          $operatingExpense += $total_debit - $total_credit;

                          endif;
                          endforeach;



                         */
                        $reEarning = 0;
                        $returnEarnig = $this->Common_model->get_single_data_by_single_column('retainearning', 'dist_id', $this->dist_id);

                        if (!empty($returnEarnig->dr)):
                            $reEarning = $returnEarnig->dr;
                        else:
                            $reEarning = $returnEarnig->cr;
                        endif;
                        ?>                     
                        <tr style="background-color:grey;color:white;">
                            <td data-toggle="collapse" data-target="#demoReturn" style="padding-left:10px;"><span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a href="javascript:void(0)" style="color:white;">&nbsp;Retain earnings </a></td>
                            <td align="right"><?php echo number_format((float) ($grossProfit - $totalExpense) + $reEarning, 2, '.', ','); ?></td>
                            <td align="right"><strong> 0.00</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                               

                                    <table class="table table-bordered">
                                        <tr  style="background-color:green;color:white;">
                                            <td width="38%"><i class="fa fa-minus"></i>&nbsp;&nbsp;Total Sales</td>
                                            <td width="30%" align="right"><?php echo $totalsale; ?></td>
                                            <td  align="right">0.00</td>
                                        </tr>
                                        <tr  style="background-color:green;color:white;">
                                            <td width="38%"><i class="fa fa-minus"></i>&nbsp;&nbsp;Total Cost</td>
                                            <td width="30%" align="right"><?php echo $productCost; ?></td>
                                            <td  align="right">0.00</td>
                                        </tr>
                                    </table>
                          
                            </td>
                        </tr>

                  
                        <tr>
                            <td align="right"><strong>Total Liabilities & Equity (In BDT.)</strong></td>
                            <td align="right"><strong>  <?php echo number_format((float) $total_liabilityies + (($grossProfit - $operatingExpense) + $reEarning), 2, '.', ','); ?></strong></td>
                            <td align="right"><strong> 0.00</strong></td>
                        </tr>                    
                                                                                                                                                                                         <td align="right"><strong> 0.00</strong></td>
                                                                                                                                                                                                 </tr>
                    </tbody>    
                </table>    
           
        </div>
    <?php endif; ?>
</div>