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
$customer_id = $_SESSION["customerid"];
$from = $_SESSION["start_date"];
$to = $_SESSION["end_date"];

if (isset($customerid)):
    $customer_id = $_SESSION["customerid"];
    $from = $_SESSION["start_date"];
    $to = $_SESSION["end_date"];
endif;
?>

<div class="page-content">
    
    <?php
    if (isset($from) && isset($to)) :


        unset($_SESSION["customerid"]);
        unset($_SESSION["start_date"]);
        unset($_SESSION["end_date"]);


        $_SESSION["customerid"] = $customer_id;
        $_SESSION["start_date"] = $from;
        $_SESSION["end_date"] = $to;
        $dist_id = $this->dist_id;



        $dr = 0;
        $cr = 0;
        if ($customer_id != 'all'):
            ?>
            <div class="row">
                <div class="col-sm-10 col-md-offset-1">	
                   
                    <table class="table table-responsive">

                        <tr>
                            <td style="text-align:center;" colspan="8">
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
                                <td align="center"><strong>SL</strong></td>
                                <td align="center"><strong>Date</strong></td>

                                <td align="center"><strong>Voucher No</strong></td>
                                <td align="center"><strong>Type</strong></td>

                                <td align="center"><strong>Charge</strong></td>
                                <td align="center"><strong>Dr</strong></td>
                                <td align="center"><strong>Cr</strong></td>
                                <td align="center"><strong>Balance</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $this->Finane_Model->customer_ledger($customer_id, $from, $to, $dist_id);
                            foreach ($query as $key => $row):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>

                                    <td align="left"><?php echo $row['trans_type']; ?></td>
                                    <!--<td align="left"><?php echo $this->Common_model->tableRow('generals', 'generals_id', $row['history_id'])->voucher_no; ?></td>-->
                                    <td align="left"><?php echo $row['paymentType']; ?></td>
                                    <td align="right"><?php echo number_format((float) $row['amount'], 2, '.', ','); ?></td>
                                    <td align="right"><?php
                    $dr += $row['dr'];
                    echo number_format((float) $row['dr'], 2, '.', ',');
                                ?></td>
                                    <td align="right"><?php
                            $cr += $row['cr'];
                            echo number_format((float) $row['cr'], 2, '.', ',');
                                ?></td>
                                    <td align="right"><?php echo $dr - $cr;
                                ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>                             
                            <tr>
                                <td colspan="5" align="right"><strong>Total (In BDT.)</strong></td>
                                <td align="right"><strong><?php echo number_format((float) $dr, 2, '.', ','); ?></strong></td>
                                <td align="right"><strong> <?php echo number_format((float) $cr, 2, '.', ','); ?></strong></td>
                                <td align="right"><strong> <?php
                    echo number_format((float) $dr - $cr, 2, '.', ',');
                    $finalAmount = $dr - $cr;
                            ?></strong></td>
                            </tr>
                        </tfoot>                           
                    </table> 
                    <br>

                </div>
            </div> 
        <?php else: ?> 

            <div class="row">
                <div class="col-sm-10 col-md-offset-1">		
                   
                    <table class="table table-responsive">

                        <tr>
                            <td style="text-align:center;" colspan="8">
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
                                <td align="center"><strong>SL</strong></td>
                                <td align="center"><strong>Name</strong></td>
                                <td align="center"><strong>Address</strong></td>
                                <td align="center"><strong>Contact Number</strong></td>
                                <td align="center"><strong>Opening Balance</strong></td>
                                <td align="center"><strong>Sales</strong></td>
                                <td align="center"><strong>Payment</strong></td>
                                <td align="center"><strong>Closing Balance</strong></td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $this->Finane_Model->customer_ledger($customer_id, $from, $to, $dist_id);
                            $ttopen = 0;
                            $ttdrebit = 0;
                            $ttcredit = 0;


                            if ($customer_id == 'all'):

                                foreach ($query as $key => $row):
                                    $openningBal = $this->Finane_Model->supplier_cus_openingAmount(1, $row['client_vendor_id'], $from, $this->dist_id);
                                    $balance = $row['debit'] - $row['credit'];
                                    $ttopen += $openningBal;
                                    $ttdrebit += $row['debit'];
                                    $ttcredit += $row['credit'];
                                    if (!empty($balance)):
                                        ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td align="center"><?php echo $row['name']; ?> [ <?php echo $row['customerID']; ?> ] </td>
                                            <td align="center"><?php echo $row['address']; ?></td>
                                            <td align="center"><?php echo $row['mobile']; ?></td>
                                            <td align="right"><?php echo $openningBal; ?></td>
                                            <td align="right"><?php echo $row['debit']; ?></td>
                                            <td align="right"><?php echo $row['credit']; ?></td>
                                            <td align="right"><?php echo $balance; ?></td>
                                        </tr>
                                        <?php
                                    endif;
                                endforeach;

                            else:
                                ?>

                                <?php
                                foreach ($query as $key => $row):
                                    $openningBal = $this->minventory->supplier_cus_openingAmount(1, $row['client_vendor_id'], $from, $to);
                                    $balance = $row['debit'] - $row['credit'];
                                    $ttopen += $openningBal;
                                    $ttdrebit += $row['debit'];
                                    $ttcredit += $row['credit'];
                                    ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td align="center"><?php echo $row['name']; ?></td>
                                        <td align="center"><?php echo $row['address']; ?></td>
                                        <td align="center"><?php echo $row['mobile']; ?></td>
                                        <td align="right"><?php echo $openningBal; ?></td>
                                        <td align="right"><?php echo $row['debit']; ?></td>
                                        <td align="right"><?php echo $row['credit']; ?></td>
                                        <td align="right"><?php echo $balance; ?></td>

                                    </tr>
                                    <?php
                                endforeach;

                            endif;
                            ?> 
                        </tbody>
                        <tfoot>                             
                            <tr>
                                <td colspan="4" align="right"><strong>Total (In BDT.)</strong></td>
                                <td align="right"><strong><?php echo number_format((float) $ttopen, 2, '.', ','); ?></strong></td>
                                <td align="right"><strong><?php echo number_format((float) $ttdrebit, 2, '.', ','); ?></strong></td>
                                <td align="right"><strong> <?php echo number_format((float) $ttcredit, 2, '.', ','); ?></strong></td>
                                <td align="right"><strong> <?php echo number_format((float) $ttdrebit - $ttcredit, 2, '.', ','); ?></strong></td>
                            </tr>
                        </tfoot>                           
                    </table> 
                </div>
            </div> 
        <?php
        endif;

    endif;
    ?>
</div><!-- /.row -->