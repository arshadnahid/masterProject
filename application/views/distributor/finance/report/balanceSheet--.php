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
                    <a href="<?php echo site_url('DistributorDashboard/1'); ?>">Finance</a>
                </li>
                <li class="active">Balance Sheet</li>
            </ul>
            
            <ul class="breadcrumb pull-right">
                <li class="active">
                    <i class="ace-icon fa fa-list"></i>
                    <a href="<?php echo site_url('DistributorDashboard/1'); ?>">List</a>
                </li>
            </ul>
            
            
            
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
<!--                        <div class="noPrint">
                        <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('FinaneController/balanceSheet_export_excel/') ?>" class="btn btn-success pull-right">
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
                                                      $normalValue=  $total_debit1 - $total_credit1;
                                                        
                                                        if(!empty($normalValue)):
                                                        ?>
                                                        <tr  style="background-color:green;color:white;">
                                                            <td width="38%"><i class="fa fa-minus"></i><a href="<?php echo site_url('generalLedger/' . $row_cma->chartId); ?>" style="color:white;">&nbsp;&nbsp;<?php echo $row_cma->title; ?></a></td>
                                                            <td width="30%" align="right">

                                                                <?php echo number_format((float) $total_debit1 - $total_credit1, 2, '.', ','); ?>

                                                            </td>
                                                            <td  align="right">0.00</td>
                                                        </tr>
                                                    <?php
                                                    endif;
                                                    
                                                    endforeach; ?>

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
                                             endforeach; 
                                        
                                        
                                      $normalAccount= $total_credit - $total_debit;
                                       // if(!empty($normalAccount)):
                                        
                                        ?>   
                                
                                
                                    <tr style="background-color:grey;color:white;" class="item-row"> 
                                        <td data-toggle="collapse" data-target="#demo<?php echo $idKey; ?>" style="padding-left:10px;"> <span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a style="color:white;" href="javascript:void(0)"> &nbsp;<?php
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
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                 // endif;
                                endforeach; 
                                
                              
                              
                                //get total sales revenew
                                $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, 49);
                                $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, 49);
                                $openingBalance11 = $total_opencredit - $total_opendebit;
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
                                $totalsale += $saleWithoutVat + $openingBalance11;
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
                                            $this->db->where('dist_id', $this->dist_id);
                                            $this->db->where('account', $row_cma->chartId);
                                            $this->db->where('date <=', $to_date);
                                            $query = $this->db->get('generalledger')->result_array();
                                            foreach ($query as $row):
                                                $total_debit += $row['debit'];
                                                $total_credit += $row['credit'];
                                            endforeach;
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
                                            $this->db->where('dist_id', $this->dist_id);
                                            $this->db->where('account', $row_cma->chartId);
                                            $this->db->where('date <=', $to_date);
                                            $query = $this->db->get('generalledger')->result_array();
                                            foreach ($query as $row):
                                                $total_debit += $row['debit'];
                                                $total_credit += $row['credit'];
                                            endforeach;
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
                                <tr style="background-color:grey;color:white;">
                                    <td data-toggle="collapse" data-target="#demoReturn" style="padding-left:10px;"><span class="show_hide" id="plus<?php echo $idKey; ?>">+</span><a href="javascript:void(0)" style="color:white;">&nbsp;Profit / ( Loss )</a></td>
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
