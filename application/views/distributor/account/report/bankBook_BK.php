<?php
if (isset($_POST['start_date'])):
    $bankAccount = $this->input->post('bankAccount');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
             <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
               Bank Book </div>
            </div>
            <div class="portlet-body">

                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action=""  method="post" class="form-horizontal">
                            <div class="col-sm-10 col-md-offset-1">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label text-right" for="BranchAutoId">

                                                <?php echo get_phrase('Branch') ?></label>
                                            <div class="col-sm-7">
                                                <select name="branch_id" class="chosen-select form-control"
                                                        id="BranchAutoId" data-placeholder="Select Branch">
                                                    <option value=""></option>
                                                    <?php
                                                    if (!empty($branch_id)) {
                                                        $selected = $branch_id;
                                                    } else {
                                                        $selected = 'all';
                                                    }
                                                    // come from branch_dropdown_helper
                                                    echo branch_dropdown('all', $selected);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $condition = array(
                                        'parentId' => 55,
                                        'dist_id' => $this->dist_id,
                                    );
                                    $allBankAccount = $this->Common_model->get_data_list_by_many_columns('generaldata', $condition);
                                    ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Bank</label>
                                            <div class="col-sm-9">
                                                <select  name="bankAccount" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Account">
                                                    <!--<option value="" disabled selected>---Select Account Head---</option>-->
                                                    <option value="all">All</option>
                                                    <?php
                                                    foreach ($allBankAccount as $eachLedger) :
                                                        ?>
                                                        <option <?php
                                                        if (!empty($bankAccount) && $bankAccount == $eachLedger->chartId) {
                                                            echo "selected";
                                                        }
                                                        ?>  value="<?php echo $eachLedger->chartId; ?>"><?php echo $eachLedger->title . " ( " . $eachLedger->code . " ) "; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From</label>
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
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To </label>
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

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-md-6">
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
                    $bankAccount = $this->input->post('bankAccount');
                    $from_date = $this->input->post('start_date');
                    $to_date = $this->input->post('end_date');
                    unset($_SESSION["bankAccount"]);
                    unset($_SESSION["start_date"]);
                    unset($_SESSION["end_date"]);
                    $_SESSION["bankAccount"] = $bankAccount;
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
                        <div class="col-xs-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        <strong>Bank Book statement : </strong><span>From <?php echo $from_date; ?> To <?php echo $to_date; ?></span>
                                    </td>
                                </tr>
                            </table>
                            <?php if ($bankAccount == 'all'): ?>
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
                                        $condition = array(
                                            'parentId' => 55,
                                            'dist_id' => $this->dist_id,
                                        );
                                        $allBankAccount = $this->Common_model->get_data_list_by_many_columns('generaldata', $condition);
                                        $allAccountId = array();
                                        foreach ($allBankAccount as $eachInfo):
                                            $allAccountId[] = $eachInfo->chartId;
                                        endforeach;
                                        // Opening Balance
                                        $total_opendebit = $this->Finane_Model->opening_balance_dr_array($dist_id, $allAccountId);
                                        $total_opencredit = $this->Finane_Model->opening_balance_cr_array($dist_id, $allAccountId);
                                        $this->db->select("sum(generalledger.debit) as totalDebit,sum(generalledger.credit) as totalCredit");
                                        $this->db->from("generalledger");
                                        $this->db->where('dist_id', $dist_id);
                                        if (!empty($allAccountId)) {
                                            $this->db->where_in('account', $allAccountId);
                                        }
                                        $this->db->where('date <=', $from_date);
                                        $query_pvs = $this->db->get()->row();
                                        $total_pvsdebit += $query_pvs->totalDebit;
                                        $total_pvscredit += $query_pvs->totalCredit;
                                        $dr_pvsbal = $total_pvsdebit + $total_opendebit;
                                        $cr_pvsbal = $total_pvscredit + $total_opencredit;
                                        $total_pvsbalance = $dr_pvsbal - $cr_pvsbal;
                                        ?>
                                        <tr>
                                            <td colspan="5" align="right"><strong>Opening Balance (In BDT.)</strong></td>
                                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvsdebit), 2, '.', ',');                                                                                                           ?></strong></td>
                                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvscredit), 2, '.', ',');                                                                                                         ?></strong></td>
                                            <td align="right">
                                                <strong><?php echo number_format((float) abs($total_pvsbalance), 2, '.', ','); ?>&nbsp;
                                                    <?php
                                                    if ($dr_pvsbal >= $cr_pvsbal): echo 'Dr.';
                                                    elseif ($dr_pvsbal < $cr_pvsbal): echo 'Cr.';
                                                    endif;
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <!-- /PVS Balance -->
                                        <!-- Search Balance -->
                                        <?php
                                        $this->db->where('dist_id', $dist_id);
                                        if (!empty($allAccountId)) {
                                            $this->db->where_in('account', $allAccountId);
                                        }
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('date', 'ASC');
                                        $query = $this->db->get('generalledger')->result_array();
                                        //dumpVar($query);
                                        foreach ($query as $row):
                                            //  dumpVar($row);
                                            ?>
                                            <tr>
                                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                                <td><?php
                                                    $generalInfo = $this->Common_model->tableRow('generals', 'generals_id', $row['generals_id']);
                                                    // echo $generalInfo->generals_id;
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
                                                        <a href="#"><?php echo $generalInfo->voucher_no; ?></a>
                                                    <?php } elseif ($generalInfo->form_id == 1) {
                                                        ?>
                                                        <a href="<?php echo site_url('journalVoucherView/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                        <?php
                                                    } elseif ($generalInfo->form_id == 11) {
                                                        ?>
                                                        <a href="<?php echo site_url('viewPurchases/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $this->Common_model->tableRow('form', 'form_id', $row['form_id'])->name;
                                                    ?></td>
                                                <td nowrap>
                                                    <?php
                                                    if (!empty($generalInfo->customer_id)) {
                                                        // echo 1;
                                                        $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $generalInfo->customer_id);
                                                        echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                                                    } elseif (!empty($generalInfo->supplier_id)) {
                                                        $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $generalInfo->supplier_id);
                                                        echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                    } else {
                                                        echo $generalInfo->miscellaneous;
                                                    }
                                                    ?>
                                                </td>
                                                <td><table class="table table-bordered"><?php
                                                        if ($row['form_id'] == 5) {
                                                            $allAccount = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $row['generals_id'], 'generalledger_id', 'DESC', '0', 1);
                                                            //  dumpVar($allAccount[0]);
                                                        } else {
                                                            $allAccount = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $row['generals_id']);
                                                        }
                                                        foreach ($allAccount as $eachInfo):
                                                            if (!in_array($eachInfo->account, $allAccountId)):
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
                                                            endif;
                                                        endforeach;
                                                        ?></table></td>
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
                                            <td colspan="5" align="right"><strong>Ending Balance (In BDT.)</strong></td>
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
                            <?php else: ?>
                                <table class="table table-bordered">
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
                                        $total_opendebit = $this->Finane_Model->opening_balance_dr($dist_id, $bankAccount);
                                        $total_opencredit = $this->Finane_Model->opening_balance_cr($dist_id, $bankAccount);
                                        $this->db->where('dist_id', $dist_id);
                                        $this->db->where('account', $bankAccount);
                                        $this->db->where('date <=', $from_date);
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
                                            <td colspan="5" align="right"><strong>Opening Balance (In BDT.)</strong></td>
                                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvsdebit), 2, '.', ',');                                                                            ?></strong></td>
                                            <td align="right"><strong> <?php //echo number_format((float) abs($total_pvscredit), 2, '.', ',');                                                                          ?></strong></td>
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
                                        $this->db->where('account', $bankAccount);
                                        $this->db->where('date >=', $from_date);
                                        $this->db->where('date <=', $to_date);
                                        $this->db->order_by('date', 'ASC');
                                        $query = $this->db->get('generalledger')->result_array();
                                        //dumpVar($query);
                                        foreach ($query as $row):
                                            //  dumpVar($row);
                                            ?>
                                            <tr>
                                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                                <td><?php
                                                    $generalInfo = $this->Common_model->tableRow('generals', 'generals_id', $row['generals_id']);
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
                                                        <a href="#"><?php echo $generalInfo->voucher_no; ?></a>
                                                    <?php } elseif ($generalInfo->form_id == 1) {
                                                        ?>
                                                        <a href="<?php echo site_url('journalVoucherView/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                        <?php
                                                    } elseif ($generalInfo->form_id == 11) {
                                                        ?>
                                                        <a href="<?php echo site_url('viewPurchases/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $this->Common_model->tableRow('form', 'form_id', $row['form_id'])->name;
                                                    ?></td>
                                                <td nowrap>
                                                    <?php
                                                    if (!empty($generalInfo->customer_id)) {
                                                        $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $generalInfo->customer_id);
                                                        echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                                                    } elseif (!empty($generalInfo->supplier_id)) {
                                                        $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $generalInfo->supplier_id);
                                                        echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                    } else {
                                                        echo $generalInfo->miscellaneous;
                                                    }
                                                    ?>
                                                </td>
                                                <td><table class="table table-bordered"><?php
                                                        if ($row['form_id'] == 5) {
                                                            $allAccount = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $row['generals_id'], 'date', 'ASC', '0', 1);
                                                        } else {
                                                            $allAccount = $this->Common_model->get_data_list_by_single_column('generalledger', 'generals_id', $row['generals_id']);
                                                        }
                                                        foreach ($allAccount as $eachInfo):
                                                            if ($eachInfo->account != $bankAccount):
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
                                                        ?></table></td>
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
                                            <td colspan="5" align="right"><strong>Ending Balance (In BDT.)</strong></td>
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
                            <?php endif; ?>
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