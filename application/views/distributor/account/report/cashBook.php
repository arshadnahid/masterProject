<?php
if (isset($_POST['start_date'])):
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
    $branch_id = $this->input->post('branch_id');
    $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
    $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
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
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-sm-12">
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                From Date</label>
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
                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                To Date</label>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                    Search
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="window.print();" style="cursor:pointer;">
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
                                        <strong>Cash Book statement :</strong> From <?php echo $from_date; ?>
                                        To <?php echo $to_date; ?></span>
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

                                <!-- /PVS Balance -->
                                <!-- Search Balance -->
                                <?php
                                $query = "SELECT
avm.for,
avm.Accounts_Voucher_No,
avm.BackReferenceInvoiceNo,
avm.BackReferenceInvoiceID,
avm.customer_id,
avm.supplier_id,
avm.miscellaneous,
avm.AccouVoucherType_AutoID,
  branch.branch_name,
	avd.Accounts_VoucherDtl_AutoID,
	avd.Accounts_VoucherMst_AutoID,
	avd.TypeID,
	avd.GR_CREDIT,
	avd.GR_DEBIT,
	avd.BranchAutoId,
	avd.Reference,
	avd.date,avd2.otherLedger
FROM
	ac_tb_accounts_voucherdtl avd
LEFT JOIN (

SELECT
	avd1.Accounts_VoucherMst_AutoID,
GROUP_CONCAT(coa.parent_name , '¥',avd1.TypeID,'¥',avd1.GR_CREDIT,'¥',avd1.GR_DEBIT,'*¥¥*') AS otherLedger
/*avd1.CHILD_ID,
avd1.TypeID,
avd1.GR_CREDIT,
avd1.GR_DEBIT,
avd1.BranchAutoId*/
FROM
	ac_tb_accounts_voucherdtl avd1
LEFT JOIN ac_account_ledger_coa coa on coa.id=avd1.CHILD_ID
WHERE
	avd1.Accounts_VoucherMst_AutoID IN(
		SELECT
			avd.Accounts_VoucherMst_AutoID
		FROM
			ac_tb_accounts_voucherdtl avd
		WHERE
			avd.CHILD_ID in (
			SELECT
	
	CHILD_ID
FROM
	ac_tb_coa
WHERE
	PARENT_ID = ".$this->config->item("Cash_at_Hand")."
AND CHILD_ID <> 0
UNION ALL
	SELECT
		
		CHILD_ID
	FROM
		ac_tb_coa
	WHERE
		PARENT_ID1 = ".$this->config->item("Cash_at_Hand")."
	AND CHILD_ID <> 0
	UNION ALL
		SELECT
			
			CHILD_ID
		FROM
			ac_tb_coa
		WHERE
			PARENT_ID2 = ".$this->config->item("Cash_at_Hand")."
		AND CHILD_ID <> 0
		UNION ALL
			SELECT
				
				CHILD_ID
			FROM
				ac_tb_coa
			WHERE
				PARENT_ID3 = ".$this->config->item("Cash_at_Hand")."
			AND CHILD_ID <> 0
			UNION ALL
				SELECT
					
					CHILD_ID
				FROM
					ac_tb_coa
				WHERE
					PARENT_ID4 = ".$this->config->item("Cash_at_Hand")."
				AND CHILD_ID <> 0
				UNION ALL
					SELECT
						
						CHILD_ID
					FROM
						ac_tb_coa
					WHERE
						PARENT_ID5 = ".$this->config->item("Cash_at_Hand")."
					AND CHILD_ID <> 0
					UNION ALL
						SELECT
							
							CHILD_ID
						FROM
							ac_tb_coa
						WHERE
							PARENT_ID6 = ".$this->config->item("Cash_at_Hand")."
						AND CHILD_ID <> 0
						UNION ALL
							SELECT
								
								CHILD_ID
							FROM
								ac_tb_coa
							WHERE
								PARENT_ID7 = ".$this->config->item("Cash_at_Hand")."
							AND CHILD_ID <> 0
			/*SELECT coa.id FROM ac_account_ledger_coa coa WHERE coa.parent_id=28*/
			)
		AND avd.IsActive = 1
	)
/*AND avd1.CHILD_ID != 23*/
GROUP BY avd1.Accounts_VoucherMst_AutoID

) AS avd2 ON avd2.Accounts_VoucherMst_AutoID=avd.Accounts_VoucherMst_AutoID
LEFT JOIN branch on  branch.branch_id= avd.BranchAutoId
LEFT JOIN  ac_accounts_vouchermst avm ON avm.Accounts_VoucherMst_AutoID=avd.Accounts_VoucherMst_AutoID
WHERE
	avd.CHILD_ID in (
	SELECT
	
	CHILD_ID
FROM
	ac_tb_coa
WHERE
	PARENT_ID = ".$this->config->item("Cash_at_Hand")."
AND CHILD_ID <> 0
UNION ALL
	SELECT
		
		CHILD_ID
	FROM
		ac_tb_coa
	WHERE
		PARENT_ID1 = ".$this->config->item("Cash_at_Hand")."
	AND CHILD_ID <> 0
	UNION ALL
		SELECT
			
			CHILD_ID
		FROM
			ac_tb_coa
		WHERE
			PARENT_ID2 = ".$this->config->item("Cash_at_Hand")."
		AND CHILD_ID <> 0
		UNION ALL
			SELECT
				
				CHILD_ID
			FROM
				ac_tb_coa
			WHERE
				PARENT_ID3 = ".$this->config->item("Cash_at_Hand")."
			AND CHILD_ID <> 0
			UNION ALL
				SELECT
					
					CHILD_ID
				FROM
					ac_tb_coa
				WHERE
					PARENT_ID4 = ".$this->config->item("Cash_at_Hand")."
				AND CHILD_ID <> 0
				UNION ALL
					SELECT
						
						CHILD_ID
					FROM
						ac_tb_coa
					WHERE
						PARENT_ID5 = ".$this->config->item("Cash_at_Hand")."
					AND CHILD_ID <> 0
					UNION ALL
						SELECT
							
							CHILD_ID
						FROM
							ac_tb_coa
						WHERE
							PARENT_ID6 = ".$this->config->item("Cash_at_Hand")."
						AND CHILD_ID <> 0
						UNION ALL
							SELECT
								
								CHILD_ID
							FROM
								ac_tb_coa
							WHERE
								PARENT_ID7 = ".$this->config->item("Cash_at_Hand")."
							AND CHILD_ID <> 0
			/*SELECT coa.id FROM ac_account_ledger_coa coa WHERE coa.parent_id=28*/
			
	)
AND avd.IsActive=1
AND avd.date >='" . $startDate . "'
AND avd.date <='" . $endDate . "'
";

                                if ($branch_id != 'all') {
                                    //product.product_id
                                    $query .= " and avd.BranchAutoId= " . $branch_id;
                                }
                                $query .= " ORDER BY avd.BranchAutoId ,avd.date";
                                $query = $this->db->query($query);
                                $result = $query->result_array();
                                /*echo"<pre>";
                                echo $this->db->last_query();
                                exit;*/
//                                echo"<pre>";
//                                print_r($result);exit;
                                $brandNameArray = array();
                                foreach ($result as $row):
                                    if (!in_array($row['branch_name'], $brandNameArray)) {
                                        array_push($brandNameArray, $row['branch_name']); ?>
                                        <tr>
                                            <td colspan="8">
                                                <?php echo $row['branch_name']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                        // Opening Balance

                                        $queryOpening="SELECT
	
	SUM(avd.GR_CREDIT) GR_CREDIT,
	SUM(avd.GR_DEBIT) GR_DEBIT
FROM
	ac_tb_accounts_voucherdtl avd

LEFT JOIN branch ON branch.branch_id = avd.BranchAutoId
LEFT JOIN ac_accounts_vouchermst avm ON avm.Accounts_VoucherMst_AutoID = avd.Accounts_VoucherMst_AutoID
WHERE
	avd.CHILD_ID IN(
		SELECT
			CHILD_ID
		FROM
			ac_tb_coa
		WHERE
			PARENT_ID = ".$this->config->item("Cash_at_Hand")."
		AND CHILD_ID <> 0
		UNION ALL
			SELECT
				CHILD_ID
			FROM
				ac_tb_coa
			WHERE
				PARENT_ID1 = ".$this->config->item("Cash_at_Hand")."
			AND CHILD_ID <> 0
			UNION ALL
				SELECT
					CHILD_ID
				FROM
					ac_tb_coa
				WHERE
					PARENT_ID2 = ".$this->config->item("Cash_at_Hand")."
				AND CHILD_ID <> 0
				UNION ALL
					SELECT
						CHILD_ID
					FROM
						ac_tb_coa
					WHERE
						PARENT_ID3 = ".$this->config->item("Cash_at_Hand")."
					AND CHILD_ID <> 0
					UNION ALL
						SELECT
							CHILD_ID
						FROM
							ac_tb_coa
						WHERE
							PARENT_ID4 = ".$this->config->item("Cash_at_Hand")."
						AND CHILD_ID <> 0
						UNION ALL
							SELECT
								CHILD_ID
							FROM
								ac_tb_coa
							WHERE
								PARENT_ID5 = ".$this->config->item("Cash_at_Hand")."
							AND CHILD_ID <> 0
							UNION ALL
								SELECT
									CHILD_ID
								FROM
									ac_tb_coa
								WHERE
									PARENT_ID6 = ".$this->config->item("Cash_at_Hand")."
								AND CHILD_ID <> 0
								UNION ALL
									SELECT
										CHILD_ID
									FROM
										ac_tb_coa
									WHERE
										PARENT_ID7 = ".$this->config->item("Cash_at_Hand")."
									AND CHILD_ID <> 0 /*SELECT coa.id FROM ac_account_ledger_coa coa WHERE coa.parent_id=28*/
	)
AND avd.IsActive = 1

AND avd.date AND avd.date <'" . $startDate . "'";
                                       // if ($branch_id != 'all') {

                                            $queryOpening .= " and avd.BranchAutoId= " . $row['BranchAutoId'];
                                        //}

                                        $query = $this->db->query($queryOpening);
                                        $result = $query->row();


                                        $total_opendebit =$result->GR_DEBIT;
                                        $total_opencredit=$result->GR_CREDIT;

                                        $dr_pvsbal =  $total_opendebit;
                                        $cr_pvsbal = $total_opencredit;
                                        $total_pvsbalance = $dr_pvsbal - $cr_pvsbal;
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

                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                            <td>

                                                <?php
                                                if ($row['for'] == 1) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/viewPurchasesCylinder/' . $row['BackReferenceInvoiceID']); ?>"><?php echo $row['BackReferenceInvoiceNo']; ?></a>
                                                <?php } elseif ($row['for'] == 2) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/viewLpgCylinder/' . $row['BackReferenceInvoiceID']); ?>"><?php echo $row['BackReferenceInvoiceNo']; ?></a>
                                                <?php } elseif ($row['for'] == 0 && $row['AccouVoucherType_AutoID'] == 1) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/receiveVoucherView/' . $row['Accounts_VoucherMst_AutoID']); ?>"><?php echo $row['Accounts_Voucher_No']; ?></a>
                                                <?php } elseif ($row['for'] == 0 && $row['AccouVoucherType_AutoID'] == 2) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/paymentVoucherView/' . $row['Accounts_VoucherMst_AutoID']); ?>"><?php echo $row['Accounts_Voucher_No']; ?></a>
                                                <?php } elseif ($row['for'] == 0 && $row['AccouVoucherType_AutoID'] == 3) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/journalVoucherView/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($row['AccouVoucherType_AutoID'] == 11) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/viewPurchases/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($row['AccouVoucherType_AutoID'] == 5) {
                                                    ?>
                                                    <a href="<?php echo site_url('salesInvoice_view/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['for'] == 1) {
                                                    echo 'Purchase Invoice';
                                                } else if ($row['for'] == 2) {
                                                    echo 'Sales Invoice';
                                                } else {
                                                    echo $this->Common_model->tableRow('accounts_vouchertype_autoid', 'Accounts_VoucherType_AutoID', $row['AccouVoucherType_AutoID'])->Accounts_VoucherType;
                                                }
                                                ?>
                                            </td>
                                            <td><?php
                                                if (!empty($row['customer_id'])) {
                                                    $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $row['customer_id']);
                                                    echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                                                } elseif (!empty(($row['supplier_id']))) {
                                                    $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $row['supplier_id']);
                                                    echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                } else {
                                                    echo($row['miscellaneous']);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <td>Ledger</td>
                                                        <td>Dr</td>
                                                        <td>Cr</td>
                                                    </tr>
                                                    </thead>
                                                    <?php
                                                    //echo $row['otherLedger'];
                                                    $allAccount = explode('*¥¥*', $row['otherLedger']);

                                                    foreach ($allAccount as $eachInfo):
                                                        if (!empty($eachInfo)):
                                                            $eachInfo = trim($eachInfo, ",");
                                                            $ledger = explode('¥', $eachInfo);
                                                            $amount = 0;
                                                            if (!empty($ledger[2]) && $ledger[2] > 0):
                                                                $amount = $ledger[2];
                                                            else:
                                                                $amount = $ledger[3];
                                                            endif;
                                                            if ($amount > 0) {
                                                                ?>
                                                                <tr>
                                                                    <td width="70%"><?php
                                                                        echo $ledger[0];
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        echo number_format((float)abs($ledger[2]), 2, '.', ',');
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        echo number_format((float)abs($ledger[3]), 2, '.', ',');
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </table>
                                            </td>
                                            <td align="right"><?php
                                                echo number_format((float)abs($row['GR_DEBIT']), 2, '.', ',');
                                                $total_debit += $row['GR_DEBIT'];
                                                $currentDebit += $row['GR_DEBIT'];
                                                ?>
                                            </td>
                                            <td align="right"><?php
                                                echo number_format((float)abs($row['GR_CREDIT']), 2, '.', ',');
                                                $total_credit += $row['GR_CREDIT'];
                                                $currentCredit += $row['GR_CREDIT'];
                                                ?>
                                            </td>
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


                                    <?php } else {
                                        ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                            <td>

                                                <?php
                                                if ($row['for'] == 1) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/viewPurchasesCylinder/' . $row['BackReferenceInvoiceID']); ?>"><?php echo $row['BackReferenceInvoiceNo']; ?></a>
                                                <?php } elseif ($row['for'] == 2) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/viewLpgCylinder/' . $row['BackReferenceInvoiceID']); ?>"><?php echo $row['BackReferenceInvoiceNo']; ?></a>
                                                <?php } elseif ($row['for'] == 0 && $row['AccouVoucherType_AutoID'] == 1) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/receiveVoucherView/' . $row['Accounts_VoucherMst_AutoID']); ?>"><?php echo $row['Accounts_Voucher_No']; ?></a>
                                                <?php } elseif ($row['for'] == 0 && $row['AccouVoucherType_AutoID'] == 2) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/paymentVoucherView/' . $row['Accounts_VoucherMst_AutoID']); ?>"><?php echo $row['Accounts_Voucher_No']; ?></a>
                                                <?php } elseif ($row['for'] == 0 && $row['AccouVoucherType_AutoID'] == 3) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/journalVoucherView/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($row['AccouVoucherType_AutoID'] == 11) {
                                                    ?>
                                                    <a href="<?php echo site_url($this->project . '/viewPurchases/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php } elseif ($row['AccouVoucherType_AutoID'] == 5) {
                                                    ?>
                                                    <a href="<?php echo site_url('salesInvoice_view/' . $generalInfo->generals_id); ?>"><?php echo $generalInfo->voucher_no; ?></a>
                                                <?php }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['for'] == 1) {
                                                    echo 'Purchase Invoice';
                                                } else if ($row['for'] == 2) {
                                                    echo 'Sales Invoice';
                                                } else {
                                                    echo $this->Common_model->tableRow('accounts_vouchertype_autoid', 'Accounts_VoucherType_AutoID', $row['AccouVoucherType_AutoID'])->Accounts_VoucherType;
                                                }
                                                ?>
                                            </td>
                                            <td><?php
                                                if (!empty($row['customer_id'])) {
                                                    $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $row['customer_id']);
                                                    echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                                                } elseif (!empty(($row['supplier_id']))) {
                                                    $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $row['supplier_id']);
                                                    echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                } else {
                                                    echo($row['miscellaneous']);
                                                }
                                                ?></td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <td>Ledger</td>
                                                        <td>Dr</td>
                                                        <td>Cr</td>
                                                    </tr>
                                                    </thead>
                                                    <?php
                                                    $allAccount = explode('*¥¥*', $row['otherLedger']);
                                                    foreach ($allAccount as $eachInfo):
                                                        if (!empty($eachInfo)):
                                                            $eachInfo = trim($eachInfo, ",");
                                                            $ledger = explode('¥', $eachInfo);
                                                            $amount = 0;
                                                            if (!empty($ledger[2]) && $ledger[2] > 0):
                                                                $amount = $ledger[2];
                                                            else:
                                                                $amount = $ledger[3];
                                                            endif;
                                                            if ($amount > 0) {
                                                                ?>
                                                                <tr>
                                                                    <td width="70%"><?php
                                                                        echo $ledger[0];
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        echo number_format((float)abs($ledger[2]), 2, '.', ',');
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        echo number_format((float)abs($ledger[3]), 2, '.', ',');
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </table>
                                            </td>
                                            <td align="right"><?php
                                                echo number_format((float)abs($row['GR_DEBIT']), 2, '.', ',');
                                                $total_debit += $row['GR_DEBIT'];
                                                $currentDebit += $row['GR_DEBIT'];
                                                ?>
                                            </td>
                                            <td align="right"><?php
                                                echo number_format((float)abs($row['GR_CREDIT']), 2, '.', ',');
                                                $total_credit += $row['GR_CREDIT'];
                                                $currentCredit += $row['GR_CREDIT'];
                                                ?>
                                            </td>
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
                                        <?php
                                    }
                                endforeach; ?>

                                <?php
                                if ($total_pvsbalance >= 1) {
                                    $total_debit += $total_pvsbalance;
                                } else {
                                    $total_credit += $total_pvsbalance;
                                }
                                ?>
                                <!-- /Search Balance -->
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" align="right"><strong>Ending Balance (In BDT.)</strong></td>
                                    <td align="right">
                                        <strong><?php echo number_format((float)abs($currentDebit), 2, '.', ','); ?>
                                            &nbsp;Dr.</strong></td>
                                    <td align="right">
                                        <strong> <?php echo number_format((float)abs($currentCredit), 2, '.', ','); ?>
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