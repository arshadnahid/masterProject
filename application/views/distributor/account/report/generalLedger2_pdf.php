<?php
if (isset($_POST['start_date'])):
    /*    echo'<pre>';
    print_r($_POST);*/
    $account = $this->input->post('accountHead');
    $group = $this->input->post('group');
    $branch_id = $this->input->post('branch_id');
    $from_date = date('d-m-Y', strtotime($this->input->post('start_date')));
    $to_date = date('d-m-Y', strtotime($this->input->post('end_date')));
else:
    $account = $account;
    $group = '';
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;text-align: center">
                    <?php
                    $condition = array(
                        'id' => $this->input->post('Ledger_id'),
                       // 'related_id_for' => 1,
                        'is_active' => "Y",
                    );
                    $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
                        echo "<strong>Ledger :" .$ac_account_ledger_coa_info->parent_name."</strong>";

                     ?>
                </div>
            </div>
            <div class="portlet-body">

                <?php
                if (isset($_POST['start_date'])):
                    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                    $account = $this->input->post('accountHead');
                    $dist_id = $this->dist_id;
                    $total_pvsdebit = 0;
                    $total_pvscredit = 0;
                    $total_debit = 0;
                    $total_credit = 0;
                    $total_balance = 0;
                    $lastBalance = 0;
                    ?>
                    <div class="row">
                        <div class="col-xs-12">


                            <div class="row">
                                <div class="col-xs-12">


                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td nowrap align="center"><strong>Date</strong></td>
                                            <td align="center"><strong>Voucher No.</strong></td>
                                            <td align="center"><strong>Type</strong></td>
                                            <td align="center"><strong>Voucher By</strong></td>
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
                                        ?>
                                        <tr>
                                            <td colspan="5" align="right"><strong>Opening Balance (In BDT.)</strong></td>
                                            <td align="right">
                                                <strong> <?php //echo number_format((float) abs($total_pvsdebit), 2, '.', ',');
                                                    ?></strong></td>
                                            <td align="right">
                                                <strong> <?php //echo number_format((float) abs($total_pvscredit), 2, '.', ',');
                                                    ?></strong></td>
                                            <td align="right">
                                                <strong><?php echo number_format((float)abs($total_pvsbalance), 2, '.', ','); ?>
                                                    &nbsp;
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
                                        if (!empty($gl_data)):
                                            foreach ($gl_data as $row):
                                                ?>
                                                <tr>
                                                    <td nowrap><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                                    <td>

                                                        <?php
                                                        $link="javascript:void(0)";
                                                        $voucher_no="javascript:void(0)";
                                                        if ($row['for'] == 1) {
                                                            $link = "viewPurchasesCylinder/" . $row['BackReferenceInvoiceID'];
                                                            $voucher_no=$row['BackReferenceInvoiceNo']."(".$row['Accounts_Voucher_No'].")";
                                                        } elseif ($row['for'] == 2) {
                                                            $link = "viewLpgCylinder/" . $row['BackReferenceInvoiceID'];
                                                            $voucher_no=$row['BackReferenceInvoiceNo']."(".$row['Accounts_Voucher_No'].")";
                                                        }else{
                                                            $voucher_no=$row['Accounts_Voucher_No'];
                                                        } ?>

                                                        <a href="<?php echo $link?>" class="" target="_blank">
                                                            <?php
                                                            echo $voucher_no;
                                                            ?>
                                                        </a>
                                                    </td>
                                                    <td><?php //echo $row['name'];
                                                        ?></td>
                                                    <?php if (!empty($row['customerName'])): ?>
                                                        <td>
                                                            <?php echo $row['customerID'] . ' [ ' . $row['customerName'] . ' ] ' ?>
                                                        </td>
                                                    <?php elseif ($row['supName']): ?>
                                                        <td>
                                                            <?php echo $row['supID'] . ' [ ' . $row['supName'] . ' ] ' ?>
                                                        </td>
                                                    <?php else: ?>
                                                        <td><?php echo $row['miscellaneous'] ?></td>
                                                    <?php endif; ?>
                                                    <td><?php echo $row['Narration']; ?></td>
                                                    <td align="right"><?php
                                                        echo number_format((float)abs($row['GR_DEBIT']), 2, '.', ',');
                                                        $total_debit += $row['GR_DEBIT'];
                                                        ?></td>
                                                    <td align="right"><?php
                                                        echo number_format((float)abs($row['GR_CREDIT']), 2, '.', ',');
                                                        $total_credit += $row['GR_CREDIT'];
                                                        ?></td>
                                                    <td align="right">
                                                        <?php
                                                        $dr_bal = $total_debit;
                                                        $cr_bal = $total_credit;
                                                        $total_balance = $dr_bal - $cr_bal;
                                                        $lastBalance = $total_balance + $total_pvsbalance;
                                                        ?>
                                                        <?php echo number_format((float)abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>
                                                        &nbsp;
                                                        <?php
                                                        if ($lastBalance >= 1) {
                                                            echo 'Dr.';
                                                        } else {
                                                            echo 'Cr.';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach;
                                        endif;
                                        ?>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5" align="right"><strong>Ending Balance (In BDT.)</strong></td>
                                            <td align="right">
                                                <strong><?php echo number_format((float)abs($total_debit), 2, '.', ','); ?>
                                                    &nbsp;Dr.</strong></td>
                                            <td align="right">
                                                <strong> <?php echo number_format((float)abs($total_credit), 2, '.', ','); ?>
                                                    &nbsp;Cr.</strong></td>
                                            <td align="right">
                                                <strong><?php echo number_format((float)abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>
                                                    &nbsp;<?php
                                                    if ($lastBalance >= 1) {
                                                        echo 'Dr.';
                                                    } else {
                                                        echo 'Cr.';
                                                    }
                                                    ?></strong>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php
                endif; ?>
            </div>
        </div>
    </div>
</div>
<script>


</script>