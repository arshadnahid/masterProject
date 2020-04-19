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


                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td nowrap align="center"><strong>Group/Ledger</strong></td>
                                    <td align="center"><strong>Opening</strong></td>
                                    <td align="center"><strong>Receipt (Dr)</strong></td>
                                    <td align="center"><strong>Payment (Cr)</strong></td>
                                    <td align="center"><strong>Balance</strong></td>

                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                if (!empty($gl_data)):
                                    $tt_Opening=0;
                                    $tt_GR_DEBIT=0;
                                    $tt_GR_CREDIT=0;
                                    foreach ($gl_data as $key => $value) {
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-left"><b><?php echo $key ?></b></td>
                                        </tr>
                                        <?php
                                        foreach ($value as $key => $value2) {
                                            $tt_Opening+=$value2->Opening;
                                            $tt_GR_DEBIT+=$value2->GR_DEBIT;
                                            $tt_GR_CREDIT+=$value2->GR_CREDIT;
                                            ?>
                                            <tr class="ledger_tr_<?php echo $value2->CHILD_ID?>">

                                                <td class="text-left" style="white-space: nowrap;"><a href="javascript:void(0)" class="fordetailsshow" onclick="details_report('<?php echo $value2->CHILD_ID?>')"><?php echo $value2->CN ?></a></td>
                                                <td class="text-right" style="white-space: nowrap;"><?php echo numberFromatfloat($value2->Opening) ?></td>
                                                <td  class="text-right" style="white-space: nowrap;"><?php echo numberFromatfloat($value2->GR_DEBIT) ?></td>
                                                <td class="text-right" style="white-space: nowrap;"><?php echo numberFromatfloat($value2->GR_CREDIT) ?></td>
                                                <td class="text-right" style="white-space: nowrap;">
                                                    <?php
                                                    $Balance=(($value2->Opening+$value2->GR_DEBIT)-$value2->GR_CREDIT);
                                                    $tt_Balance+=$Balance;
                                                    if($Balance>0){
                                                        echo numberFromatfloat($Balance) .' Dr';
                                                    }else{
                                                        echo numberFromatfloat((-1*$Balance)) .' Cr';
                                                    }

                                                    ?>
                                                </td>
                                            </tr>


                                        <?php }
                                    }
                                endif;
                                ?>

                                <!-- /Search Balance -->

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td class="text-right"><?php echo "Total" ?></td>
                                    <td class="text-right"><?php echo numberFromatfloat($tt_Opening); ?></td>
                                    <td class="text-right"><?php echo numberFromatfloat($tt_GR_DEBIT); ?></td>
                                    <td class="text-right"><?php echo numberFromatfloat($tt_GR_CREDIT) ?></td>
                                    <td class="text-right" style="white-space: nowrap;">
                                        <?php
                                        if($tt_Balance>0){
                                            echo numberFromatfloat($tt_Balance) .' Dr';
                                        }else{
                                            echo numberFromatfloat((-1*$tt_Balance)) .' Cr';
                                        }

                                        ?>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
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