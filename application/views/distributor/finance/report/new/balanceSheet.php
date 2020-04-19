<?php
if (isset($_POST['to_date'])):
    $to_date = $this->input->post('to_date');
endif;
?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state  noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Finance</a>
                </li>
                <li class="active">Balance Sheet</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('DistributorDashboard'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a>
            </span>
        </div>
        <br>
        <div class="page-content">
            <div class="row  noPrint">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="table-header">
                                Balance Sheet
                            </div>
                            <br>
                            <div style="background-color: grey!important;">

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> As At*</label>
                                        <div class="col-sm-8">
                                            <input type="text"class="date-picker form-control" id="start_date" name="to_date" value="<?php
if (!empty($to_date)) {
    echo $to_date;
} else {


    echo date('Y-m-d');
}
?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd"/>
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
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="table-header">
                            Balance Sheet As At <?php echo $to_date; ?>
                        </div>
                        <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('FinaneController/balanceSheet_export_excel/') ?>" class="btn btn-success pull-right">
                            <i class="ace-icon fa fa-download"></i>
                            Excel 
                        </a>
                        <table class="table table-responsive">
                            <tr>
                                <td style="text-align:center;">
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
                                                            <!--<td colspan="3" align="center"><strong>Assets</strong></td>-->
                                </tr> 
                                <!-- chart_type -->
                                <?php
                                foreach ($assetList as $idKey => $row_cta):
                                    $nameInfo = explode("-", $row_cta['parentName']);
                                    ?>    
                                    <tr class="item-row" style="background-color:grey;color:white;"> 
                                        <td onclick="divShowHide()" data-toggle="collapse" data-target="#demo<?php echo $idKey; ?>" style="padding-left:10px;"> <span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a href="javascript:void(0)" style="color:white;">  <?php echo $nameInfo[0]; ?> </a> </td> 
                                        <!-- chart_master --> 
                                        <?php
                                        $total_balance = '';
                                        foreach ($row_cta['Accountledger'] as $row_cma):
                                            if ($row_cma->chartId == 58) {
                                                $customerOpening = $this->Finane_Model->getCustomerOpeningBalance($this->dist_id);
                                            }
                                            ?> 
                                            <?php
                                            // Opening Balance
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
                                            if ($row_cma->chartId == 58) {
                                                $total_balance += ($total_debit - $total_credit) + $customerOpening;
                                            } else {
                                                $total_balance += ($total_debit - $total_credit);
                                            }
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
                                                        $total_opendebit1 = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma->chartId);
                                                        $total_opencredit1 = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma->chartId);

//                                                        $this->Finane_Model->

                                                        if ($row_cma->chartId == 58) {
                                                            $customerOpening = $this->Finane_Model->getCustomerOpeningBalance($this->dist_id);
                                                        }
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
                                                        if ($row_cma->chartId == 58) {
                                                           
                                                            $total_balance1 += ($total_debit1 - $total_credit1) + $customerOpening;
                                                        } else {
                                                            $total_balance1 += ($total_debit1 - $total_credit1);
                                                        }
                                                        ?>
                                                        <tr  style="background-color:green;color:white;">
                                                            <td width="38%"><i class="fa fa-minus"></i><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>" style="color:white;">&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                            <td width="30%" align="right">

                                                                <?php
                                                                if ($row_cma->chartId == 58) {
                                                                    
                                                                    //$total_balance1 += ($total_debit1 - $total_credit1) + $customerOpening;
                                                                    echo number_format((float) ($total_debit1 - $total_credit1) + $customerOpening, 2, '.', ',');
                                                                } else {
                                                                    echo number_format((float) ($total_debit1 - $total_credit1), 2, '.', ',');
                                                                }
                                                                ?>

                                                            </td>
                                                            <td  align="right">0.00</td>
                                                        </tr>
                                                    <?php endforeach; ?>

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
                                foreach ($liabilityList as $idKey => $row_ctl):
                                    //dumpVar($row_ctl);
                                    ?>  
                                    <tr style="background-color:grey;color:white;" class="item-row"> 
                                        <td data-toggle="collapse" data-target="#demo<?php echo $idKey; ?>" style="padding-left:10px;"> <span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a style="color:white;" href="javascript:void(0)"> &nbsp;<?php
                            $mainTitle = explode("-", $row_ctl['parentName']);

                            echo $mainTitle[0];
                                    ?></a> </td>  
                                        <!-- chart_master --> 
                                        <?php
                                        $total_balance = '';

                                        foreach ($row_ctl['Accountledger'] as $row_cml):
                                            ?>                         
                                            <?php
                                            // Opening Balance
                                            $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cml->chartId);
                                            $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cml->chartId);


                                            if ($row_cml->chartId == 50) {
                                                $supplierOpening = $this->Finane_Model->getSupplierOpeningBalance($this->dist_id);
                                            }


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

                                            if ($row_cml->chartId == 50) {
                                                $total_balance += ($total_credit - $total_debit) + $supplierOpening;
                                            } else {
                                                $total_balance += $total_credit - $total_debit;
                                            }
                                            ?> 
                                        <?php endforeach; ?>                     
                                        <!-- /chart_master -->                        
                                        <td align="right"><?php 
                                        
                                        
                                        echo number_format((float) $total_balance, 2, '.', ','); 
                                        
                                        
                                        ?></td> 
                                        <?php $total_liabilityies += $total_balance; ?> 
                                        <td align="right">0.00</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">
                                            <div id="demo<?php echo $idKey; ?>" class="collapse">

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


                                                        if ($row_cma->chartId == 50) {
                                                            $supplierOpening = $this->Finane_Model->getSupplierOpeningBalance($this->dist_id);
                                                        }

                                                        $total_debit1 += $total_opendebit1;
                                                        $total_credit1 += $total_opencredit1;
                                                        $total_balance1 += $total_debit1 - $total_credit1;

                                                        if ($row_cma->chartId == 50) {
                                                            $availab = ($total_debit1 - $total_credit1) + $supplierOpening;
                                                        } else {
                                                            $availab = $total_debit1 - $total_credit1;
                                                        }


                                                        if (!empty($availab)):
                                                            ?>
                                                            <tr  style="background-color:green;color:white;">
                                                                <td width="38%"><i class="fa fa-minus"></i><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>" style="color:white;">&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                                <td width="30%" align="right">

                                                                    <?php
                                                                    if ($row_cma->chartId == 50) {
                                                                        echo number_format((float) abs(($total_debit1 - $total_credit1) + $supplierOpening), 2, '.', ',');
                                                                    } else {
                                                                        echo number_format((float) abs($total_debit1 - $total_credit1), 2, '.', ',');
                                                                    }
                                                                    ?>

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


                                <?php endforeach; ?>                     
                                <!-- /chart_type -->








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
















                                <!--calculate profit and loss-->



                                <!-- chart_master --> 
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
                                        <div id="demoReturn" class="collapse">

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
                                        </div>
                                    </td>
                                </tr>

                                <!-- /chart_class -->
                                <tr>
                                    <td align="right"><strong>Total Liabilities & Equity (In BDT.)</strong></td>
                                    <td align="right"><strong>  <?php echo number_format((float) $total_liabilityies + (($grossProfit - $operatingExpense) + $reEarning), 2, '.', ','); ?></strong></td>
                                    <td align="right"><strong> 0.00</strong></td>
                                </tr>                    
                                <!-- /Liabilities -->  


                                <!-- Equity --> 

                                                                                                                                                                                                                                                                                                                                                                    <!--                                <tr>
                                                                                                                                                                                                                                                                                                                                                                         <td align="right"><strong>Total Equites and Government Support (In BDT.)</strong></td>
                                                                                                                                                                                                                                                                                                                                                                         <td align="right"><strong>  <?php echo number_format((float) $total_equity_govt + $profit['profit'], 2, '.', ','); ?></strong></td>
                                                                                                                                                                                                                                                                                                                                                                         <td align="right"><strong> 0.00</strong></td>
                                                                                                                                                                                                                                                                                                                                                                     </tr>

                                                                                                                                                                                                                                                                                                                                                                     <tr>
                                                                                                                                                                                                                                                                                                                                                                         <td align="right"><strong>Total Liabilities, Equites and Government Support (In BDT.)</strong></td>
                                                                                                                                                                                                                                                                                                                                                                         <td align="right"><strong>  <?php echo number_format((float) $total_liabilityies + $total_equity_govt + $total_return + $profit['profit'], 2, '.', ','); ?></strong></td>
                                                                                                                                                                                                                                                                                                                                                                         <td align="right"><strong> 0.00</strong></td>
                                                                                                                                                                                                                                                                                                                                                                     </tr>-->
                            </tbody>    
                        </table>    
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
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
    
    
    
    $(document).ready(function(){
        $('.show_hide').toggle(function(){
            alert("hello");
            $("#plus51").text("-");
           

        },function(){
            $("#plus51").text("+");
           
        });
    });

</script>
