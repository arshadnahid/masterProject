<?php
if (isset($_POST['start_date'])):
    $account = $this->input->post('accountHead');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>

<div class="main-content">

    <div class="main-content-inner">


        <div class="page-content">

            <div class="row  noPrint">

                <div class="col-md-12">

                    <form id="publicForm" action="" method="post" class="form-horizontal">

                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="table-header">

                                Trial Balance

                            </div>

                            <br>

                            <div style="background-color: grey!important;">


                                <div class="col-md-4">

                                    <div class="form-group">

                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From
                                            Date</label>

                                        <div class="col-sm-8">

                                            <input type="text" class="date-picker form-control" id="start_date"
                                                   name="start_date" value="<?php
                                            if (!empty($from_date)) {
                                                echo $from_date;
                                            } else {
                                                echo date('Y-m-d');
                                            }
                                            ?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd"/>

                                        </div>

                                    </div>


                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">

                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To
                                            Date</label>

                                        <div class="col-sm-8">

                                            <input type="text" class="date-picker form-control" id="end_date"
                                                   name="end_date" value="<?php
                                            if (!empty($to_date)):
                                                echo $to_date;
                                            else:
                                                echo date('Y-m-d');
                                            endif;
                                            ?>" data-date-format='yyyy-mm-dd' placeholder="End Date: yyyy-mm-dd"/>

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

                                            <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                                    style="cursor:pointer;">

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
            if (isset($_POST['start_date']) && isset($_POST['end_date'])):
                $sub_total_pvsdebit = '';
                $sub_total_pvscredit = '';
                $sub_total_debit = '';
                $sub_total_credit = '';
                $sub_total_debit_balance = '';
                $sub_total_credit_balance = '';
                $opDr = '';
                $opCr = '';
                $pDr = '';
                $pCr = '';
                $cDr = '';
                $cCr = '';
                unset($_SESSION["start_date"]);
                unset($_SESSION["end_date"]);
                $_SESSION["start_date"] = $from_date;
                $_SESSION["end_date"] = $to_date;
                $dist_id = $this->dist_id;
                $finalTrialBalancedr = '';
                $finalTrialBalancecr = '';
                ?>

                <div class="row">

                    <div class="col-sm-10 col-sm-offset-1">

                        <div class="table-header">

                            Trial Balance <span style="color:greenyellow;">From <?php echo $from_date; ?>
                                To <?php echo $to_date; ?></span>


                        </div>
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
                                <td rowspan="2" align="left" style="padding-top:15px;"><strong>Account Name</strong>
                                </td>
                                <td colspan="2" align="center"><strong>Brought Forward</strong></td>
                                <td colspan="2" align="center"><strong>This Period</strong></td>
                                <td colspan="2" align="center"><strong>Balance (In BDT.)</strong></td>
                            </tr>
                            <tr>
                                <td align="right"><strong>Debit</strong></td>
                                <td align="right"><strong>Credit</strong></td>
                                <td align="right"><strong>Debit</strong></td>
                                <td align="right"><strong>Credit</strong></td>
                                <td align="right"><strong>Debit</strong></td>
                                <td align="right"><strong>Credit</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Assets -->
                            <tr class="item-row">
                                <td colspan="7"><strong>A. Assets</strong></td>
                            </tr>

                            <?php
                            $twoa = 0;
                            ?>

                            <!-- chart_master -->
                            <?php
                            foreach ($assetList as $key => $row_cma):
                                if ($key != 0) {
                                    if ($row_cma['posted'] == 0) {
                                        ?>

                                        <tr class="item-row">
                                            <td colspan="7"><strong>&nbsp;&nbsp;<?php
                                                    for ($x = 0; $x <= $row_cma['label']; $x++) {
                                                        echo  '&nbsp;&nbsp;&nbsp;';
                                                    }
                                                       // echo 'A'.$row_cma['label_for'];
                                                    ?> <?php echo $row_cma['parent_name'] ?></strong>


                                            </td>
                                        </tr>

                                        <?php
                                    } else



                                        //echo $row_cma['parent_name'];
                                        // Opening Balance
                                        $total_opendebit = $this->Finane_Model->opening_balance_dr($this->dist_id, $row_cma['id']);
                                    $total_opencredit = $this->Finane_Model->opening_balance_cr($this->dist_id, $row_cma['id']);
                                    $total_pvsdebit = 0;
                                    $total_pvscredit = 0;
                                    $this->db->select('sum(GR_DEBIT) as totalDebit,sum(GR_CREDIT) as totalCredit');
                                    //$this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('CHILD_ID', $row_cma['id']);
                                    $this->db->where('date <', $from_date);
                                    $query_pvs = $this->db->get('ac_tb_accounts_voucherdtl')->row();
                                    $total_pvsdebit += $query_pvs->totalDebit;
                                    $total_pvscredit += $query_pvs->totalCredit;
                                    $total_pvsdebit += $total_opendebit;
                                    $total_pvscredit += $total_opencredit;
                                    $sub_total_pvsdebit += $total_pvsdebit;
                                    $sub_total_pvscredit += $total_pvscredit;
                                    $total_debit = '';
                                    $total_credit = '';
                                    $this->db->select('sum(GR_DEBIT) as totalDebit,sum(GR_CREDIT) as totalCredit');
                                    //$this->db->where('dist_id', $this->dist_id);
                                    $this->db->where('CHILD_ID', $row_cma['id']);
                                    $this->db->where('date >=', $from_date);
                                    $this->db->where('date <=', $to_date);
                                    $this->db->where('IsActive', 1);
                                    $query = $this->db->get('ac_tb_accounts_voucherdtl')->row();

                                    $total_debit += $query->totalDebit;
                                    $total_credit += $query->totalCredit;
                                    $sub_total_debit += $total_debit;
                                    $sub_total_credit += $total_credit;
                                    $debit_balance = $total_pvsdebit + $total_debit;
                                    $credit_balance = $total_pvscredit + $total_credit;
                                    $ddbitValue = $total_debit - $total_credit;
                                    $ddcreditValue = $total_credit - $total_debit;
                                    $cbbbb = $debit_balance - $credit_balance;
                                    $dbbbb = $credit_balance - $debit_balance;
                                    if (!empty($total_pvsdebit) || !empty($total_pvscredit) || !empty($ddbitValue) || !empty($ddcreditValue) || !empty($cbbbb) || !empty($dbbbb)):
                                        ?>


                                        <tr>

                                            <td>
                                                <a href="<?php echo site_url('generalLedger/' . $row_cma['id']); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo   '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;'.$row_cma['parent_name']; ?></a>
                                            </td>

                                            <!-- PVS Balance -->

                                            <?php ?>


                                            <!--Opening trial balance start -->


                                            <td align="right">

                                                <?php
                                                if ($total_pvsdebit >= $total_pvscredit): echo number_format((float)$total_pvsdebit - $total_pvscredit, 2, '.', ',');
                                                    $opDr += $total_pvsdebit - $total_pvscredit;
                                                else:
                                                    echo '0.00';
                                                endif;
                                                ?></td>

                                            <td align="right">

                                                <?php
                                                if ($total_pvsdebit < $total_pvscredit): echo number_format((float)$total_pvscredit - $total_pvsdebit, 2, '.', ',');
                                                    $opCr += $total_pvscredit - $total_pvsdebit;
                                                else:
                                                    echo '0.00';
                                                endif;
                                                ?>

                                            </td>


                                            <td align="right">

                                                <?php
                                                if ($total_debit >= $total_credit):
                                                    echo number_format((float)$total_debit - $total_credit, 2, '.', ',');
                                                    $pDr += $total_debit - $total_credit;
                                                else: echo '0.00';
                                                endif;
                                                ?>

                                            </td>


                                            <td align="right">
                                                <?php
                                                if ($total_debit < $total_credit): echo number_format((float)$total_credit - $total_debit, 2, '.', ',');
                                                    $pCr += $total_credit - $total_debit;
                                                else: echo '0.00';
                                                endif;
                                                ?>
                                            </td>
                                            <?php ?>
                                            <td align="right">

                                                <?php
                                                if ($debit_balance >= $credit_balance): echo number_format((float)$debit_balance - $credit_balance, 2, '.', ',');
                                                    $cDr += $debit_balance - $credit_balance;
                                                else: echo '0.00';
                                                endif;
                                                ?>


                                            </td>

                                            <td align="right">


                                                <?php
                                                if ($debit_balance < $credit_balance): echo number_format((float)$credit_balance - $debit_balance, 2, '.', ',');
                                                    $cCr += $credit_balance - $debit_balance;
                                                else: echo '0.00';
                                                endif;
                                                ?>

                                            </td>

                                            <!-- /Balance -->

                                            <?php
                                            $sub_total_debit_balance += $debit_balance;
                                            $sub_total_credit_balance += $credit_balance;
                                            $finalTrialBalancedr += abs($debit_balance - $credit_balance);
                                            $finalTrialBalancecr += abs($credit_balance - $debit_balance);
                                            ?>

                                        </tr>


                                        <?php
                                    endif;
                                    if($row_cma['label']==1){

                                    }else if($row_cma['label']==2){

                                    }else if($row_cma['label']==3){

                                    }else if($row_cma['label']==4){

                                    }

                                }
                            endforeach;
                            ?>

                            <!-- /chart_master -->


                            <!-- /chart_type -->


                            </tbody>

                            <tfoot>

                            <tr>

                                <td align="right"><strong>Total Ending Balance (In BDT.)</strong></td>

                                <td align="right">

                                    <strong><?php
                                        echo number_format((float)$opDr, 2, '.', ',');
                                        ?></strong>

                                </td>

                                <td align="right"><strong><?php
                                        echo number_format((float)$opCr + $opnignReturn, 2, '.', ',');
                                        ?></strong>

                                </td>

                                <td align="right">
                                    <strong><?php echo number_format((float)$pDr, 2, '.', ','); ?></strong></td>

                                <td align="right">
                                    <strong><?php echo number_format((float)$pCr, 2, '.', ','); ?></strong></td>

                                <td align="right">
                                    <strong><?php echo number_format((float)$cDr, 2, '.', ','); ?></strong></td>

                                <td align="right">
                                    <strong><?php echo number_format((float)$cCr + $opnignReturn, 2, '.', ','); ?></strong>
                                </td>

                            </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

                <?php
            else:
            endif;
            ?>

        </div><!-- /.row -->

    </div><!-- /.page-content -->

</div>


<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>

















