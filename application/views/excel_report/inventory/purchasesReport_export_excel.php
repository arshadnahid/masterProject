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
endif;
?>
<div class="page-content">

    <?php
    if (isset($from_date)):
        $from_date = $_SESSION["start_date"];
        $to_date = $_SESSION["end_date"];
        $dist_id = $this->dist_id;
        
        $total_pvsdebit = '';
        $total_pvscredit = '';

        $total_debit = '';
        $total_credit = '';
        $total_balance = '';
        ?>
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
               
                <table class="table table-responsive">

                    <tr>
                        <td style="text-align:center;" colspan="6">
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
                            <td align="center"><strong>Date</strong></td>
                            <td align="center"><strong>Voucher No.</strong></td>
                            <td align="center"><strong>Payment Type</strong></td>
                            <td align="center"><strong>Supplier</strong></td>
                            <td align="center"><strong>Memo</strong></td>
                            <td align="center"><strong>Amount</strong></td>
                        </tr>
                    </thead>
                    <tbody>                             

                        <?php
                        $this->db->where('dist_id', $dist_id);
                        $this->db->where('form_id', 11);
                        $this->db->where('date >=', $from_date);
                        $this->db->where('date <=', $to_date);
                        $query = $this->db->get('generals')->result_array();

                        $total_debit = 0;
                        foreach ($query as $row):
                            ?>                                
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td> 
                                <td><a href="<?php echo site_url('viewPurchases/' . $row['generals_id']); ?>"><?php echo $row['voucher_no']; ?></a></td> 
                                <td><?php
                    if ($row['payType'] == 1) {
                        echo "Cash";
                    } elseif ($row['payType'] == 2) {
                        echo "Credit";
                    } else {
                        echo "Bank";
                    }
                            ?></td> 
                                <td>
                                    <a href="<?php echo site_url('supplierDashboard/' . $row['supplier_id']); ?>">

                                        <?php
                                        $suppInfo = $this->Common_model->tableRow('supplier', 'sup_id', $row['supplier_id']);
                                        echo $suppInfo->supID . '[' . $suppInfo->supName . ']';
                                        ?></a></td>
                                <td><?php echo $row['memo']; ?></td>                                
                                <td align="right"><?php
                                echo number_format((float) abs($row['debit']), 2, '.', ',');
                                $total_debit += $row['debit'];
                                        ?></td>                                    

                            </tr>
                        <?php endforeach; ?>
                        <!-- /Search Balance -->                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" align="right"><strong>Total Purchases Amount</strong></td>                             
                            <td align="right"><strong><?php echo number_format((float) abs($total_debit), 2, '.', ','); ?>&nbsp;</strong></td>
                        </tr>
                    </tfoot>                            
                </table> 
            </div>
        </div>
    <?php endif; ?>
</div><!-- /.row -->