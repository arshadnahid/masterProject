<?php
if (isset($_POST['start_date'])):
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Cash Book 
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action=""  method="post" class="form-horizontal">
                            <div class="col-sm-12">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From Date</label>
                                            <div class="col-sm-8">
                                                <input type="text"class="date-picker form-control" id="start_date" name="start_date" value="<?php
                                                if (!empty($from_date)) {
                                                    echo $from_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="end_date" name="end_date" value="<?php
                                                if (!empty($to_date)):
                                                    echo $to_date;
                                                else:
                                                     echo date('d-m-Y');
                                                endif;
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
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
                if (isset($_POST['start_date']) && $_POST['end_date']):
                    //  dumpVar($_POST);
                    $from_date = $this->input->post('start_date');
                    $to_date = $this->input->post('end_date');
                    unset($_SESSION["start_date"]);
                    unset($_SESSION["end_date"]);
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
                        <div class="col-xs-10 col-xs-offset-1">

                            <!--<div class="noPrint">
                                                    <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('FinaneController/cashBook_export_excel/') ?>" class="btn btn-success pull-right">
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
                                        <strong><?php echo $pageTitle; ?> </strong>
                                        <strong>Cash Book statement :</strong> From <?php echo $from_date; ?> To <?php echo $to_date; ?></span>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td align="center"><strong>Date</strong></td>
                                        <td align="center"><strong>Voucher No.</strong></td>
                                        <td align="center"><strong>Voucher Type</strong></td>
                                        <td align="center"><strong>Voucher By</strong></td>
                                        <td align="center"><strong>Memo</strong></td>
                                        <td align="center"><strong>Received (In BDT.)</strong></td>
                                        <td align="center"><strong>Payment (In BDT.)</strong></td>
                                        <td align="center"><strong>Balance (In BDT.)</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- PVS Balance -->
                                    <?php
                                    // Opening Balance
                                    $total_opendebit = $this->Finane_Model->opening_balance_dr($dist_id, 54);
                                    $total_opencredit = $this->Finane_Model->opening_balance_cr($dist_id, 54);

                                    $this->db->select("sum(generalledger.debit) as totalDebit,sum(generalledger.credit) as totalCredit");
                                    $this->db->from("generalledger");
                                    $this->db->where('dist_id', $dist_id);
                                    $this->db->where('account', 54);
                                    $this->db->where('date <', $from_date);
                                    $query_pvs = $this->db->get()->row();

                                    $total_pvsdebit += $query_pvs->totalDebit;
                                    $total_pvscredit += $query_pvs->totalCredit;

                                    $dr_pvsbal = $total_pvsdebit + $total_opendebit;
                                    $cr_pvsbal = $total_pvscredit + $total_opencredit;
                                    $total_pvsbalance = $dr_pvsbal - $cr_pvsbal;
                                    ?>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Opening Balance (In BDT.)</strong></td>
                                        <td align="right"><strong> <?php //echo number_format((float) abs($total_pvsdebit), 2, '.', ',');                                                       ?></strong></td>
                                        <td align="right"><strong> <?php //echo number_format((float) abs($total_pvscredit), 2, '.', ',');                                                     ?></strong></td>
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
                                    $this->db->select("*");
                                    $this->db->from("generalledger");
                                    $this->db->where('dist_id', $dist_id);
                                    $this->db->where('account', 54);
                                    $this->db->where('date >=', $from_date);
                                    $this->db->where('date <=', $to_date);
                                    $this->db->order_by('date', 'ASC');
                                    $query1 = $this->db->get()->result_array();
                                    //echo $this->db->last_query();die;
                                    //dumpVar($query);
                                    foreach ($query1 as $row):
                                        $generalInfo = $this->Common_model->tableRow('generals', 'generals_id', $row['generals_id']);
                                        ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                            <td><?php
                                                if ($generalInfo->form_id == 14) {
                                                    ?>
                                                    <a href="<?php echo site_url('viewMoneryReceiptSup/' . $generalInfo->voucher_no); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($generalInfo->form_id == 2) {
                                                    ?>
                                                    <a href="<?php echo site_url('paymentVoucherView/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($generalInfo->form_id == 3) {
                                                    ?>
                                                    <a href="<?php echo site_url('receiveVoucherView/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($generalInfo->form_id == 7) {
                                                    ?>
                                                    <a href="<?php echo site_url('salesInvoice_view/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($generalInfo->form_id == 1) {
                                                    ?>
                                                    <a href="<?php echo site_url('journalVoucherView/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($generalInfo->form_id == 11) {
                                                    ?>
                                                    <a href="<?php echo site_url('viewPurchases/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($generalInfo->form_id == 5) {
                                                    ?>
                                                    <a href="<?php echo site_url('salesInvoice_view/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php }
                                                ?>
                                            </td>
                                            <td> <?php echo $this->Common_model->tableRow('form', 'form_id', $generalInfo->form_id)->name; ?></td>
                                            <td><?php
                                                if (!empty($generalInfo->customer_id)) {
                                                    $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $generalInfo->customer_id);
                                                    echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                                                } elseif (!empty($generalInfo->supplier_id)) {
                                                    $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $generalInfo->supplier_id);
                                                    echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                } else {
                                                    echo $generalInfo->miscellaneous;
                                                }
                                                ?></td>
                                            <td><table class="table table-bordered"><?php
                                                    if ($row['form_id'] == 5) {
                                                        $allAccount = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $row['generals_id'], 'generalledger_id', 'DESC', '0', 1);
                                                    } else {
                                                        $allAccount = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $row['generals_id']);
                                                    }
                                                    foreach ($allAccount as $eachInfo):
                                                        if ($eachInfo->account != '54'):
                                                            ?>
                                                            <tr>
                                                                <td width="70%"><?php
                                                                    echo $this->Common_model->tableRow('chartofaccount', 'chart_id', $eachInfo->account)->title;
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if (!empty($eachInfo->debit) && $eachInfo->debit != '0.00'):
                                                                        echo number_format((float) abs($eachInfo->debit), 2, '.', ',');
                                                                    else:
                                                                        echo number_format((float) abs($eachInfo->credit), 2, '.', ',');
                                                                    endif;
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        endif;
                                                    endforeach;
                                                    ?></table>
                                            </td>
                                            <td align="right"><?php
                                                echo number_format((float) abs($row['debit']), 2, '.', ',');
                                                $total_debit += $row['debit'];
                                                $currentDebit+=$row['debit'];
                                                ?>
                                            </td>
                                            <td align="right"><?php
                                                echo number_format((float) abs($row['credit']), 2, '.', ',');
                                                $total_credit += $row['credit'];
                                                $currentCredit+=$row['credit'];
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                $dr_bal = $total_debit;
                                                $cr_bal = $total_credit;
                                                $total_balance = $dr_bal - $cr_bal;
                                                $lastBalance = $total_balance + $total_pvsbalance;
                                                ?>
                                                <?php echo number_format((float) abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>&nbsp;

                                                <?php
                                                if ($lastBalance >= 1) {
                                                    echo 'Dr.';
                                                } else {
                                                    echo 'Cr.';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php
                                    if ($total_pvsbalance >= 1) {

                                        $total_debit+=$total_pvsbalance;
                                    } else {
                                        $total_credit+=$total_pvsbalance;
                                    }
                                    ?>
                                    <!-- /Search Balance -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Ending Balance (In BDT.)</strong></td>
                                        <td align="right"><strong><?php echo number_format((float) abs($currentDebit), 2, '.', ','); ?>&nbsp;Dr.</strong></td>
                                        <td align="right"><strong> <?php echo number_format((float) abs($currentCredit), 2, '.', ','); ?>&nbsp;Cr.</strong></td>
                                        <td align="right"><strong><?php echo number_format((float) abs($total_balance + $total_pvsbalance), 2, '.', ','); ?>&nbsp;<?php
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
                <?php endif; ?>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>