<?php
if (isset($_POST['start_date'])):
    $customerid = $this->input->post('customerid');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
    $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
    $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
    $branch_id = $this->input->post('branch_id');
endif;


?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Customer Ledger') ?> </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal noPrint">
                            <div class="col-sm-12 ">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="BranchAutoId"> <?php echo get_phrase('Branch') ?></label>
                                            <div class="col-sm-8">
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="form-field-1"> <?php echo get_phrase('Customer') ?></label>
                                            <div class="col-sm-8">

                                                <?php


                                                ?>

                                                <select name="customerid" class="chosen-select form-control"
                                                        id="form-field-select-3" data-placeholder="Search by customer"
                                                        onchange="check_pretty_cash(this.value)">
                                                    <option <?php
                                                    if (!empty($customerid) && $customerid == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
                                                    </option>
                                                    <?php foreach ($customerList as $eachInfo): ?>
                                                        <option <?php
                                                        if (!empty($customerid) && $customerid == $eachInfo->customer_id) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $eachInfo->customer_id; ?>"><?php echo $eachInfo->customerName; ?>
                                                            [<?php echo $eachInfo->customerID; ?>]
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label no-padding-right"
                                                   for="form-field-1"> <?php echo get_phrase('From Date') ?></label>
                                            <div class="col-sm-7">
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
                                            <label class="col-sm-5 control-label no-padding-right"
                                                   for="form-field-1"> <?php echo get_phrase('To Date') ?></label>
                                            <div class="col-sm-7">
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

                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-4">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            <?php echo get_phrase('Search') ?>
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-info btn-sm"
                                                onclick="window.print();" style="cursor:pointer;">
                                            <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                            <?php echo get_phrase('Print') ?>
                                        </button>
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
                    $customer_id = $this->input->post('customerid');
                    $from = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to = date('Y-m-d', strtotime($this->input->post('end_date')));
                    unset($_SESSION["customerid"]);
                    unset($_SESSION["start_date"]);
                    unset($_SESSION["end_date"]);
                    $_SESSION["customerid"] = $customer_id;
                    $_SESSION["start_date"] = $from;
                    $_SESSION["end_date"] = $to;
                    $dist_id = $this->dist_id;


//echo "<pre>";
//echo $this->db->last_query();
//print_r($result);


                    if ($customer_id != 'all'):
                        ?>
                        <div class="row">
                            <div class="col-sm-10 col-md-offset-1">

                                <!--                            <div class="noPrint">
                                            <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('FinaneController/customerLedger_export_excel/') ?>" class="btn btn-success pull-right">
                                                <i class="ace-icon fa fa-download"></i>
                                                Excel
                                            </a></div>-->
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
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td align="center"><strong><?php echo get_phrase('Si') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Date') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Voucher No') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Type') ?></strong></td>
                                        <!--<td align="center"><strong>Charge</strong></td>-->
                                        <td align="center"><strong><?php echo get_phrase('Receivable') ?> (+)</strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Received') ?> (-)</strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Due') ?></strong></td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $dr = '';
                                    $cr = '';
                                    $tdr = '';
                                    $tcr = '';
                                    //$query = $this->Finane_Model->customer_ledger($customer_id, $from, $to, $dist_id);
                                    $query = "SELECT
	branch.branch_name,
	al.id,
	al.`code`,
	al.parent_name,
	acd.Accounts_VoucherMst_AutoID,
	acd.GR_DEBIT,
	acd.GR_CREDIT,
	acd.CHILD_ID,
	acd.Reference,
	acm.Accounts_VoucherMst_AutoID,
	acm.AccouVoucherType_AutoID,
	acm.`for`,
	acm.Accounts_Voucher_No,
	acm.Accounts_Voucher_Date,
	acm.BackReferenceInvoiceNo,
	acm.BackReferenceInvoiceID,
	acm.Narration,
	acm.customer_id,
	acm.BranchAutoId
FROM
	ac_account_ledger_coa al
LEFT JOIN ac_tb_accounts_voucherdtl acd ON acd.CHILD_ID = al.id
LEFT JOIN ac_accounts_vouchermst acm ON acm.Accounts_VoucherMst_AutoID = acd.Accounts_VoucherMst_AutoID
LEFT JOIN branch on branch.branch_id=acm.BranchAutoId
WHERE
	al.related_id_for = 3
-- AND acm.Accounts_Voucher_Date
	 AND acm.Accounts_Voucher_Date >='" . $startDate . "'
    AND  acm.Accounts_Voucher_Date <='" . $endDate . "'
    AND  al.related_id ='" . $customerid . "'
	";
                                    if ($branch_id != 'all') {
                                        //product.product_id
                                        $query .= " and acm.BranchAutoId= " . $branch_id;
                                    }
                                    $query .= " ORDER BY acm.Accounts_Voucher_Date,acm.BranchAutoId";
                                    $query = $this->db->query($query);
                                    $result = $query->result_array();
                                    /* echo "<pre>";
                                     echo $this->db->last_query();
                                     print_r($result);
                                     exit;*/
                                    $brandNameArray = array();
                                    foreach ($result as $key => $row) {
                                        if (!in_array($row['branch_name'], $brandNameArray)) {
                                            array_push($brandNameArray, $row['branch_name']);
                                            ?>
                                            <tr>
                                                <td> <?php echo $row['branch_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" align="right">
                                                    <strong><?php echo get_phrase('Opening_Balance_In_Bdt') ?></strong>
                                                </td>
                                                <td align="right"><?php


                                                    $query = "SELECT
	branch.branch_name,
	al.id,
	al.`code`,
	al.parent_name,
	acd.Accounts_VoucherMst_AutoID,
	SUM(acd.GR_DEBIT) GR_DEBIT,
	SUM(acd.GR_CREDIT) GR_CREDIT,
	acd.CHILD_ID,
	acd.Reference,
	acm.Accounts_VoucherMst_AutoID,
	acm.AccouVoucherType_AutoID,
	acm.`for`,
	acm.Accounts_Voucher_No,
	acm.Accounts_Voucher_Date,
	acm.BackReferenceInvoiceNo,
	acm.BackReferenceInvoiceID,
	acm.Narration,
	acm.customer_id,
	acm.BranchAutoId
FROM
	ac_account_ledger_coa al
LEFT JOIN ac_tb_accounts_voucherdtl acd ON acd.CHILD_ID = al.id
LEFT JOIN ac_accounts_vouchermst acm ON acm.Accounts_VoucherMst_AutoID = acd.Accounts_VoucherMst_AutoID
LEFT JOIN branch on branch.branch_id=acm.BranchAutoId
WHERE
	al.related_id_for = 3
-- AND acm.Accounts_Voucher_Date
	 
    AND  acm.Accounts_Voucher_Date <='" . $startDate . "'
    AND  al.related_id ='" . $customerid . "'
	";

                                                    //product.product_id
                                                    $query .= " and acm.BranchAutoId= " . $row['BranchAutoId'];

                                                    $query .= "  GROUP BY al.related_id ORDER BY acm.Accounts_Voucher_Date,acm.BranchAutoId";
                                                    $query = $this->db->query($query);
                                                    $result_opening = $query->result_array();

                                                    $custoOpe= $result_opening[0]['GR_DEBIT']-$result_opening[0]['GR_CREDIT'];


                                                    $custoOpe = $this->Finane_Model->getCusOpe($customer_id, $from, $dist_id);
                                                    echo number_format((float)$custoOpe, 2, '.', ',');
                                                    ?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php echo $key + 1; ?>
                                                </td>
                                                <td>
                                                    <?php echo date('M d, Y', strtotime($row['Accounts_Voucher_Date'])); ?></td>
                                                <td align="left">
                                                    <?php
                                                    if ($row['for'] == 2) {
                                                        echo '<a class="" target="_blank" href="' . site_url($this->project . '/viewLpgCylinder/' . $row['BackReferenceInvoiceID']) . '">' . $row['BackReferenceInvoiceNo'] . '</a>';

                                                    } else {
                                                        echo '<a class="" target="_blank" href="' . site_url($this->project . '/receiveVoucherView/' . $row['Accounts_VoucherMst_AutoID']) . '">' . $row['Accounts_Voucher_No'] . '</a>';


                                                    }


                                                    ?>
                                                </td>

                                                <td align="left"><?php echo $row['Reference']; ?></td>

                                                <td align="right"><?php
                                                    $dr += $row['GR_DEBIT'];
                                                    $tdr += $row['GR_DEBIT'];
                                                    echo number_format((float)$row['GR_DEBIT'], 2, '.', ',');
                                                    ?></td>
                                                <td align="right"><?php $cr += $row['GR_CREDIT'];
                                                    $tcr += $row['cr'];
                                                    echo number_format((float)$row['GR_CREDIT'], 2, '.', ',');
                                                    ?></td>
                                                <td align="right"><?php echo number_format((float)($dr - $cr) + $custoOpe, 2, '.', ','); ?></td>
                                            </tr>
                                        <?php } else {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $key + 1; ?>
                                                </td>
                                                <td>
                                                    <?php echo date('M d, Y', strtotime($row['Accounts_Voucher_Date'])); ?></td>
                                                <td align="left">
                                                    <?php
                                                    if ($row['for'] == 2) {
                                                        echo '<a class="" target="_blank" href="' . site_url($this->project . '/viewLpgCylinder/' . $row['BackReferenceInvoiceID']) . '">' . $row['BackReferenceInvoiceNo'] . '</a>';

                                                    } else if ($row['for'] == $this->config->item("accounting_master_table_for_warranty_claim_voucher")) {
                                                        echo '<a class="" target="_blank" href="' . site_url($this->project . '/warranty_claim_voucher_view/' . $row['Accounts_VoucherMst_AutoID']) . '">' . $row['Accounts_Voucher_No'] . '</a>';

                                                    } else if ($row['for'] == $this->config->item("accounting_master_table_for_warranty_receipt_voucher")) {
                                                        echo '<a class="" target="_blank" href="' . site_url($this->project . '/warranty_receipt_voucher_view/' . $row['Accounts_VoucherMst_AutoID']) . '">' . $row['Accounts_Voucher_No'] . '</a>';

                                                    } else if ($row['for'] == $this->config->item("accounting_master_table_for_Sales_invoice_Customer_Payment_money_recive")) {
                                                        echo '<a class="" target="_blank" href="' . site_url($this->project . '/warranty_receipt_voucher_view/' . $row['Accounts_VoucherMst_AutoID']) . '">' . $row['Accounts_Voucher_No'] . '</a>';

                                                    } else if ($row['for'] == 0) {

                                                        if ($row['AccouVoucherType_AutoID'] == 1) {
                                                            $action='receiveVoucherView';
                                                        }else if($row['AccouVoucherType_AutoID'] == 2){
                                                            $action='paymentVoucherView';
                                                        }else if($row['AccouVoucherType_AutoID'] == 3){
                                                            $action='journalVoucherView';
                                                        }

                                                        echo '<a class="" target="_blank" href="' . site_url($this->project . '/'.$action.'/' . $row['Accounts_VoucherMst_AutoID']) . '">' . $row['Accounts_Voucher_No'] . '</a>';

                                                    }

                                                    ?>
                                                </td>

                                                <td align="left"><?php echo $row['Reference']; ?></td>

                                                <td align="right"><?php
                                                    $dr += $row['GR_DEBIT'];
                                                    $tdr += $row['GR_DEBIT'];
                                                    echo number_format((float)$row['GR_DEBIT'], 2, '.', ',');
                                                    ?></td>
                                                <td align="right"><?php $cr += $row['GR_CREDIT'];
                                                    $tcr += $row['cr'];
                                                    echo number_format((float)$row['GR_CREDIT'], 2, '.', ',');
                                                    ?></td>
                                                <td align="right"><?php echo number_format((float)($dr - $cr) + $custoOpe, 2, '.', ','); ?></td>
                                            </tr>
                                        <?php }
                                    } ?>
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
                                                echo number_format((float)($dr - $cr) + $custoOpe, 2, '.', ',');
                                                // $finalAmount = ($dr - $cr)+$custoOpe;
                                                ?></strong></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <br>
                            </div>
                        </div>
                    <?php else:


                        $query = "SELECT
branch.branch_name,
	cvl.client_vendor_id,
	cvl.BranchAutoId,
	cus.customerID,
	cus.customerName,
	cus.customerPhone,
	cus.customerAddress,
	cus.customerType,
	SUM(cvl.amount) AS charge,
	SUM(cvl.dr) AS debit,
	SUM(cvl.cr) AS credit
FROM
	client_vendor_ledger cvl
LEFT JOIN customer cus ON cus.customer_id = cvl.client_vendor_id
LEFT JOIN branch on branch.branch_id=cvl.BranchAutoId
WHERE
	cvl.ledger_type = 1
	AND cvl.date >='" . $startDate . "'
AND cvl.date <='" . $endDate . "'
	";
                        if ($branch_id != 'all') {
                            //product.product_id
                            $query .= " and cvl.BranchAutoId= " . $branch_id;
                        }
                        $query .= " GROUP BY cvl.client_vendor_id,cvl.BranchAutoId
ORDER BY branch.branch_name";
                        $query = $this->db->query($query);
                        $result = $query->result_array();
                        //echo $this->db->last_query();
                        ?>
                        <div class="row">
                            <div class="col-sm-10 col-md-offset-1">


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
                                        <td align="center"><strong><?php echo get_phrase('Si') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Branch') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Name') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Address') ?></strong></td>
                                        <td align="center"><strong><?php echo get_phrase('Contact Number') ?></strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Opening Balance') ?></strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Receivable') ?> (+)</strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Received') ?> (-)</strong>
                                        </td>
                                        <td align="center"><strong><?php echo get_phrase('Due') ?></strong></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $ttopen = 0;
                                    $ttdrebit = 0;
                                    $ttcredit = 0;
                                    $sl = 1;
                                    foreach ($result as $key => $eachCust):
                                        $customerBal = $this->Finane_Model->getCustomerOpening($eachCust['client_vendor_id'], $from, $branch_id);

                                        //if (!empty($customerBal['opening']) || !empty($customerBal['debit']) || !empty($customerBal['credit'])):
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $sl++; ?>
                                            </td>
                                            <td>
                                                <?php echo $eachCust['branch_name']; ?>
                                            </td>
                                            <td align="left">

                                                <?php echo $eachCust['customerName'] . ' [ ' . $eachCust['customerID'] . ' ] '; ?>
                                            </td>
                                            <td align="center">
                                                <?php echo $eachCust['customerAddress']; ?>
                                            </td>
                                            <td align="center"><?php echo $eachCust['customerPhone']; ?></td>
                                            <td align="right"><?php
                                                echo number_format((float)$customerBal['totalBalance'], 2, '.', ',');
                                                $ttopen += $customerBal['totalBalance'];
                                                ?></td>
                                            <td align="right"><?php
                                                echo number_format((float)$eachCust['debit'], 2, '.', ',');
                                                $ttdrebit += $eachCust['debit'];
                                                ?></td>
                                            <td align="right"><?php
                                                echo number_format((float)$eachCust['credit'], 2, '.', ',');
                                                $ttcredit += $eachCust['credit'];
                                                ?></td>
                                            <td align="right"><?php echo number_format(($customerBal['totalBalance'] + $eachCust['debit']) - $eachCust['credit']); ?></td>
                                        </tr>
                                        <?php
                                        // endif;
                                    endforeach;
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="5" align="right">
                                            <strong><?php echo get_phrase('Total_In_Bdt') ?></strong></td>
                                        <!--<td align="right"><strong><?php //echo number_format((float) $ttopen, 2, '.', ',');
                                        ?></strong></td>-->
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
