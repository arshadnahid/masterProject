<?php
if (isset($_POST['start_date'])):
    $supplierId = $this->input->post('supplierId');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Supplier Ledger') ?> </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal noPrint">
                            <div class="col-sm-12">


                                <div style="background-color: grey!important;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="form-field-1"> <?php echo get_phrase('Supplier') ?></label>
                                            <div class="col-sm-8">
                                                <select name="supplierId" class="chosen-select form-control"
                                                        id="form-field-select-3" data-placeholder="Search by Supplier"
                                                        onchange="check_pretty_cash(this.value)">
                                                    <option <?php
                                                    if (!empty($supplierId) && $supplierId == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
                                                    </option>
                                                    <?php foreach ($supplierList as $eachInfo): ?>
                                                        <option <?php
                                                        if (!empty($supplierId) && $supplierId == $eachInfo->sup_id) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $eachInfo->sup_id; ?>"><?php echo $eachInfo->supName; ?>
                                                            [<?php echo $eachInfo->supID; ?>]
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="form-field-1"> <?php echo get_phrase('From Date') ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="start_date" value="<?php
                                                if (!empty($from_date)) {
                                                    echo $from_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="form-field-1"> <?php echo get_phrase('To Date') ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="end_date"
                                                       name="end_date" value="<?php
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
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    <?php echo get_phrase('Search') ?>
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="window.print();" style="cursor:pointer;">
                                                    <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                                    <?php echo get_phrase('Print') ?>
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
                if (isset($_POST['start_date']) && isset($_POST['end_date'])) :
                    // dumpVar($_POST);
                    $supplierId = $this->input->post('supplierId');
                    $from = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to = date('Y-m-d', strtotime($this->input->post('end_date')));
                    unset($_SESSION["supplierId"]);
                    unset($_SESSION["start_date"]);
                    unset($_SESSION["end_date"]);
                    $_SESSION["supplierId"] = $supplierId;
                    $_SESSION["start_date"] = $from;
                    $_SESSION["end_date"] = $to;
                    $dist_id = $this->dist_id;
                    $dr = 0;
                    $cr = 0;
                    if ($supplierId != 'all'):
                        ?>
                        <div class="row">
                            <div class="col-sm-10 col-md-offset-1">

                                <!--                            <div class="noPrint">
                                                        <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('FinaneController/supplierLedger_export_excel/') ?>" class="btn btn-success pull-right">
                                                            <i class="ace-icon fa fa-download"></i>
                                                            Excel
                                                        </a>
                                                        </div>-->
                                <table class="table table-responsive">
                                    <tr>
                                        <td style="text-align:center;">
                                            <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                            <span><?php echo $companyInfo->address; ?></span><br>
                                            <strong><?php echo get_phrase('Phone') ?>
                                                : </strong><?php echo $companyInfo->phone; ?><br>
                                            <strong><?php echo get_phrase('Email') ?>
                                                : </strong><?php echo $companyInfo->email; ?><br>
                                            <strong><?php echo get_phrase('Website') ?>
                                                : </strong><?php echo $companyInfo->website; ?><br>
                                            <strong><?php echo get_phrase($pageTitle); ?></strong>
                                            : <?php echo get_phrase('Form') ?> <?php echo $from_date; ?> <?php echo get_phrase('To') ?> <?php echo $to_date; ?>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td align="center"><strong><?php echo get_phrase('SL') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Date') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Voucher No') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Type') ?></strong></td>
                                        <!--<td align="center"><strong>Charge')?>'</strong></td>-->
                                        <td align="center"><strong><?php echo get_phrase('Payable') ?> (+)</strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Paid') ?> (-)</strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Due') ?></strong></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="6" align="right"><strong>Opening Balance (In BDT.)</strong></td>
                                        <td align="right"><?php
                                            $supOpe = $this->Finane_Model->getSupOpe($supplierId, $from, $dist_id);
                                            echo number_format((float)$supOpe, 2, '.', ',');
                                            ?></td>
                                    </tr>
                                    <?php
                                    $dr = '';
                                    $cr = '';
                                    $tdr = '';
                                    $tcr = '';
                                    $query = $this->Finane_Model->supplier_ledger($supplierId, $from, $to, $dist_id);

                                    foreach ($query as $key => $row):
                                        $generalinfo = $this->Common_model->tableRow('generals', 'generals_id', $row['history_id']);
                                        $formId = $generalinfo->form_id;
                                        ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                            <td align="left">
                                                <?php
                                                //echo $formId;
                                                if ($formId == 14) {
                                                    ?>
                                                    <a target="_blank"  href="<?php echo site_url('viewMoneryReceiptSup/' . $row['history_id']); ?>"><?php echo $row['trans_type']; ?></a>
                                                <?php } elseif ($formId == 2) {
                                                    ?>
                                                    <a target="_blank"  href="<?php echo site_url('paymentVoucherView/' . $row['history_id']); ?>"><?php echo $row['trans_type']; ?></a>
                                                <?php } elseif ($formId == 3) {
                                                    ?>
                                                    <a target="_blank"  href="<?php echo site_url('receiveVoucherView/' . $row['history_id']); ?>"><?php echo $row['trans_type']; ?></a>
                                                <?php } elseif ($formId == 7) {
                                                    ?>
                                                    <a target="_blank"   title="View Sales Voucher"  href="<?php echo site_url('viewMoneryReceiptForPayment/' . $row['history_id']); ?>"> <?php echo $row['trans_type']; ?></a>
                                                <?php } elseif ($formId == 1) {
                                                    ?>
                                                    <a target="_blank"  href="<?php echo site_url('journalVoucherView/' . $row['history_id']); ?>"><?php echo $row['trans_type']; ?></a>
                                                <?php } elseif ($formId == 5) {
                                                    ?>
                                                    <a target="_blank"   title="View Sales Voucher"  href="<?php echo site_url('salesInvoice_view/' . $row['history_id']); ?>"><?php echo $row['trans_type']; ?></a>
                                                    <?php
                                                } elseif ($formId == 11) {

                                                    $purchase_invoice_info = $this->Common_model->tableRow('purchase_invoice_info', 'invoice_no', $row['trans_type']);
                                                    $purchase_invoice_info->purchase_invoice_id;
                                                    ?>
                                                    <a target="_blank"  title="View Purchases Voucher" href="<?php echo site_url('viewPurchases/' . $purchase_invoice_info->purchase_invoice_id); ?>"><?php echo $row['trans_type']; ?></a>
                                                    <?php
                                                } else {
                                                    echo $row['trans_type'];
                                                }
                                                ?>
                                            </td>
                                            <td align="left"><?php echo $row['paymentType']; ?></td>
                                            <!--<td align="right"><?php //echo number_format((float) $row['amount'], 2, '.', ',');
                                            ?></td>-->
                                            <td align="right"><?php
                                                $dr += $row['dr'];
                                                $tdr += $row['dr'];
                                                echo number_format((float)$row['dr'], 2, '.', ',');
                                                ?></td>
                                            <td align="right"><?php
                                                $cr += $row['cr'];
                                                $tcr += $row['cr'];
                                                echo number_format((float)$row['cr'], 2, '.', ',');
                                                ?></td>
                                            <td align="right"><?php
                                                echo number_format((float)($dr - $cr) + $supOpe, 2, '.', ',');
                                                ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4" align="right">
                                            <strong><?php echo get_phrase('Total_In_Bdt') ?></strong></td>
                                        <td align="right">
                                            <strong><?php echo number_format((float)$tdr, 2, '.', ','); ?></strong></td>
                                        <td align="right">
                                            <strong> <?php echo number_format((float)$tcr, 2, '.', ','); ?></strong>
                                        </td>
                                        <td align="right"><strong> <?php
                                                echo number_format((float)($dr - $cr) + $supOpe, 2, '.', ',');
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

                                <!--                            <div class="noPrint">
                                                        <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('FinaneController/supplierLedger_export_excel/') ?>" class="btn btn-success pull-right">
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
                                            <strong><?php echo $pageTitle; ?> :</strong>
                                            <span>From <?php echo $from_date; ?> To <?php echo $to_date; ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td align="center"><strong><?php echo get_phrase('SL') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Name') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Address') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Contact Number') ?></strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Opening Balance') ?></strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Payable') ?> (+)</strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Paid') ?> (-)</strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Due') ?></strong></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $ttopen = 0;
                                    $ttdrebit = 0;
                                    $ttcredit = 0;
                                    $sl = 1;

                                    foreach ($supplierList as $key => $eachSup):
                                        $supplierBal = $this->Finane_Model->getSupplierSummation($eachSup->sup_id, $from, $to, $dist_id);

                                        if (!empty($supplierBal['opening']) || !empty($supplierBal['debit']) || !empty($supplierBal['credit'])):
                                            ?>
                                            <tr>
                                                <td><?php echo $sl++; ?></td>
                                                <td align="left">
                                                    <a title="View Supplier Dashboard"
                                                       href="<?php echo site_url('supplierDashboard/' . $eachSup->sup_id); ?>"><?php echo $eachSup->supID . ' [ ' . $eachSup->supName . ' ] '; ?></a>
                                                </td>
                                                <td align="center"><?php echo $eachSup->address; ?></td>
                                                <td align="center"><?php echo $eachSup->mobile; ?></td>
                                                <td align="right"><?php
                                                    echo number_format((float)$supplierBal['opening'], 2, '.', ',');

                                                    $ttopen += $supplierBal['opening'];
                                                    ?></td>
                                                <td align="right"><?php
                                                    echo number_format((float)$supplierBal['debit'], 2, '.', ',');

                                                    $ttdrebit += $supplierBal['debit'];
                                                    ?></td>
                                                <td align="right"><?php
                                                    echo number_format((float)$supplierBal['credit'], 2, '.', ',');

                                                    $ttcredit += $supplierBal['credit'];
                                                    ?></td>
                                                <td align="right"><?php
                                                    echo number_format((float)($supplierBal['opening'] + $supplierBal['debit']) - $supplierBal['credit'], 2, '.', ',');
                                                    ?></td>
                                            </tr>
                                            <?php
                                        endif;
                                    endforeach;
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4" align="right">
                                            <strong><?php echo get_phrase('Total_In_Bdt') ?></strong></td>
                                        <td align="right">
                                            <strong><?php echo number_format((float)$ttopen, 2, '.', ','); ?></strong>
                                        </td>
                                        <td align="right">
                                            <strong><?php echo number_format((float)$ttdrebit, 2, '.', ','); ?></strong>
                                        </td>
                                        <td align="right">
                                            <strong> <?php echo number_format((float)$ttcredit, 2, '.', ','); ?></strong>
                                        </td>
                                        <td align="right">
                                            <strong> <?php echo number_format((float)($ttdrebit - $ttcredit) + $ttopen, 2, '.', ','); ?></strong>
                                        </td>
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