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

if (isset($account)):
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
//    $_SESSION["account"] = $account;
//    $_SESSION["start_date"] = $from_date;
//    $_SESSION["end_date"] = $to_date;

    if (isset($account)):

//        $from_date = $this->input->post('start_date');
//        $to_date = $this->input->post('end_date');
//        $account = $this->input->post('accountHead');
//        $dist_id = $this->dist_id;

        $account = $_SESSION["account"];
        $from_date = $_SESSION["start_date"];
        $to_date = $_SESSION["end_date"];

        $total_pvsdebit = '';
        $total_pvscredit = '';

        $total_debit = '';
        $total_credit = '';
        $total_balance = '';
        ?>
        <div class="row">
            <div class="col-xs-12">
<!--                <div class="table-header">
                    General Ledger  <span style="color:greenyellow;">From <?php echo $from_date; ?> To <?php echo $to_date; ?></span>
                </div>-->

               
                <table class="table table-responsive">

                    <tr>
                        <td style="text-align:center;" colspan="7">
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
                            <td nowrap align="center"><strong>Date</strong></td>
                            <td align="center"><strong>Voucher No.</strong></td>
                            <td align="center"><strong>Type</strong></td>
                            <td align="center"><strong>Memo</strong></td>
                            <td align="center"><strong>Debit (In BDT.)</strong></td>
                            <td align="center"><strong>Credit (In BDT.)</strong></td>
                            <td align="center"><strong>Balance (In BDT.)</strong></td>
                        </tr>
                    </thead>
                    <tbody>                             
                        <!-- PVS Balance -->
                        <?php
                        // Opening Balance
                        $total_opendebit = $this->Finane_Model->opening_balance_dr($dist_id, $account);
                        $total_opencredit = $this->Finane_Model->opening_balance_cr($dist_id, $account);

                      
                        $this->db->where('dist_id', $dist_id);
                        $this->db->where('account', $account);
                        $this->db->where('date <', $from_date);
                        $query_pvs = $this->db->get('generalledger')->result_array();




                        foreach ($query_pvs as $row_pvs):
                            $total_pvsdebit += $row_pvs['debit'];
                            $total_pvscredit += $row_pvs['credit'];
                        endforeach;
                        $dr_pvsbal = $total_pvsdebit + $total_opendebit;
                        $cr_pvsbal = $total_pvscredit + $total_opencredit;
                        $total_pvsbalance = $dr_pvsbal - $cr_pvsbal;
                        ?> 
                        <tr>
                            <td colspan="4" align="right"><strong>Opening Balance (In BDT.)</strong></td>
                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvsdebit), 2, '.', ',');                                         ?></strong></td> 
                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvscredit), 2, '.', ',');                                       ?></strong></td> 
                            <td align="right"><strong><?php echo number_format((float) abs($total_pvsbalance), 2, '.', ','); ?>&nbsp;
                                    <?php
                                    if ($dr_pvsbal >= $cr_pvsbal): echo 'Dr.';
                                    elseif ($dr_pvsbal < $cr_pvsbal): echo 'Cr.';
                                    endif;
                                    ?></strong>
                            </td> 
                        </tr> 
                        <!-- /PVS Balance -->

                        <!-- Search Balance -->
                        <?php
                        $this->db->where('dist_id', $dist_id);
                        $this->db->where('account', $account);
                        $this->db->where('date >=', $from_date);
                        $this->db->where('date <=', $to_date);
                        $query = $this->db->get('generalledger')->result_array();
                        //dumpVar($query);

                        foreach ($query as $row):
                            ?>                                
                            <tr>
                                <td nowrap><?php echo date('M d, Y', strtotime($row['date'])); ?></td> 
                                <td><?php echo $this->Common_model->tableRow('generals', 'generals_id', $row['generals_id'])->voucher_no; ?></td>                                 
                                <td><?php echo $this->Common_model->tableRow('form', 'form_id', $row['form_id'])->name; ?></td>                                 
                                <td><?php
                    // $this->Common_model->tableRow('form', 'form_id', $row['generals_id'])->voucher_no;

                    echo $row['memo'];
                            ?></td>                                
                                <td align="right"><?php
                            echo number_format((float) abs($row['debit']), 2, '.', ',');
                            $total_debit += $row['debit'];
                            ?></td>                                    
                                <td align="right"><?php
                            echo number_format((float) abs($row['credit']), 2, '.', ',');
                            $total_credit += $row['credit'];
                            ?></td>                                    
                                <td align="right">
                                    <?php
                                    $dr_bal = $total_debit;
                                    $cr_bal = $total_credit;
                                    $total_balance = $dr_bal - $cr_bal;
                                    ?>
                                    <?php echo number_format((float) abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>&nbsp;
                                    <?php
                                    if ($dr_bal >= $cr_bal): echo 'Dr.';
                                    elseif ($dr_bal < $cr_bal): echo 'Cr.';
                                    endif;
                                    ?>
                                </td> 
                            </tr>
                        <?php endforeach; ?>
                        <!-- /Search Balance -->                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" align="right"><strong>Ending Balance (In BDT.)</strong></td>                             
                            <td align="right"><strong><?php echo number_format((float) abs($total_debit), 2, '.', ','); ?>&nbsp;Dr.</strong></td>
                            <td align="right"><strong> <?php echo number_format((float) abs($total_credit), 2, '.', ','); ?>&nbsp;Cr.</strong></td>
                            <td align="right"><strong><?php echo number_format((float) abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>&nbsp;<?php
                    if ($total_balance + $total_pvsbalance > 0): echo 'Dr.';
                    elseif ($total_balance + $total_pvsbalance < 0): echo 'Cr.';
                    endif;
                        ?></strong>
                            </td> 
                        </tr>
                    </tfoot>                            
                </table> 
            </div>
        </div>
        <?php
    else:

        $account = $account;
        ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="table-header">
                    General Report Result 
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td align="center"><strong>Voucher No.</strong></td>
                            <td align="center"><strong>Type</strong></td>
                            <td align="center"><strong>Date</strong></td>
                            <td align="center"><strong>Memo</strong></td>
                            <td align="center"><strong>Debit (In BDT.)</strong></td>
                            <td align="center"><strong>Credit (In BDT.)</strong></td>
                            <td align="center"><strong>Balance (In BDT.)</strong></td>
                        </tr>
                    </thead>
                    <tbody>                             
                        <!-- PVS Balance -->
                        <?php
                        $total_opendebit = $this->Finane_Model->opening_balance_dr($dist_id, $account);
                        $total_opencredit = $this->Finane_Model->opening_balance_cr($dist_id, $account);

                        $this->db->where('dist_id', $dist_id);
                        $this->db->where('account', $account);
                        // $this->db->where('date <', $from_date);
                        $query_pvs = $this->db->get('generalledger')->result_array();

                        //dumpVar($query_pvs);


                        foreach ($query_pvs as $row_pvs):
                            $total_pvsdebit += $row_pvs['debit'];
                            $total_pvscredit += $row_pvs['credit'];
                        endforeach;
                        $dr_pvsbal = $total_pvsdebit + $total_opendebit;
                        $cr_pvsbal = $total_pvscredit + $total_opencredit;
                        $total_pvsbalance = $dr_pvsbal - $cr_pvsbal;
                        ?> 
                        <tr>
                            <td colspan="4" align="right"><strong>Opening Balance (In BDT.)</strong></td>
                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvsdebit), 2, '.', ',');                                         ?></strong></td> 
                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvscredit), 2, '.', ',');                                       ?></strong></td> 
                            <td align="right"><strong><?php echo number_format((float) abs($total_pvsbalance), 2, '.', ','); ?>&nbsp;
                                    <?php
                                    if ($dr_pvsbal >= $cr_pvsbal): echo 'Dr.';
                                    elseif ($dr_pvsbal < $cr_pvsbal): echo 'Cr.';
                                    endif;
                                    ?></strong>
                            </td> 
                        </tr> 
                        <!-- /PVS Balance -->

                        <!-- Search Balance -->
                        <?php
                        $this->db->where('dist_id', $dist_id);
                        $this->db->where('account', $account);
                        // $this->db->where('date >=', $from_date);
                        //  $this->db->where('date <=', $to_date);
                        $query = $this->db->get('generalledger')->result_array();
                        foreach ($query as $row):
                            ?>                                
                            <tr>
                                <td><?php echo $this->Common_model->tableRow('generals', 'generals_id', $row['generals_id'])->voucher_no; ?></td>                                 
                                <td><?php echo $this->Common_model->tableRow('form', 'form_id', $row['form_id'])->name; ?></td>                                 
                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td> 
                                <td><?php echo $row['memo']; ?></td>                                
                                <td align="right">
                                    <?php
                                    echo number_format((float) abs($row['debit']), 2, '.', ',');
                                    $total_debit += $row['debit'];
                                    ?>
                                </td>                                    
                                <td align="right">
                                    <?php
                                    echo number_format((float) abs($row['credit']), 2, '.', ',');
                                    $total_credit += $row['credit'];
                                    ?>
                                </td>                                    
                                <td align="right">
                                    <?php
                                    $dr_bal = $total_debit;
                                    $cr_bal = $total_credit;
                                    $total_balance = $dr_bal - $cr_bal;
                                    ?>
                                    <?php echo number_format((float) abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>&nbsp;
                                    <?php
                                    if ($dr_bal >= $cr_bal): echo 'Dr.';
                                    elseif ($dr_bal < $cr_bal): echo 'Cr.';
                                    endif;
                                    ?>
                                </td> 
                            </tr>
                        <?php endforeach; ?>
                        <!-- /Search Balance -->                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" align="right"><strong>Ending Balance (In BDT.)</strong></td>                             
                            <td align="right"><strong><?php echo number_format((float) abs($total_debit), 2, '.', ','); ?>&nbsp;Dr.</strong></td>
                            <td align="right"><strong> <?php echo number_format((float) abs($total_credit), 2, '.', ','); ?>&nbsp;Cr.</strong></td>
                            <td align="right">
                                <strong>
                                    <?php echo number_format((float) abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>&nbsp;<?php
                                if ($total_balance + $total_pvsbalance > 0): echo 'Dr.';
                                elseif ($total_balance + $total_pvsbalance < 0): echo 'Cr.';
                                endif;
                                    ?>
                                </strong>
                            </td> 
                        </tr>
                    </tfoot>                            
                </table> 
            </div>
        </div>

    <?php endif; ?>

</div><!-- /.row -->


