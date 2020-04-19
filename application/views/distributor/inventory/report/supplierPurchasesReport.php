<?phpif (isset($_POST['start_date'])):    $supplierId = $this->input->post('supplierId');    $from_date = $this->input->post('start_date');    $to_date = $this->input->post('end_date');    $branch_id = $this->input->post('branch_id');    // echo $branch_id;exit;endif;?><div class="row">    <!-- BEGIN EXAMPLE TABLE PORTLET-->    <div class="col-md-12">        <div class="portlet box blue">            <div class="portlet-title" style="min-height:21px">                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">                    <?php echo get_phrase('Supplier Purchases Report') ?> </div>            </div>            <div class="portlet-body">                <div class="row noPrint">                    <div class="col-md-12">                        <form id="publicForm" action="" method="post" class="form-horizontal">                            <div style="background-color: grey!important;">                                <div class="col-md-3">                                    <div class="form-group">                                        <label class="col-sm-3 control-label no-padding-right"                                               for="form-field-1"> <?php echo get_phrase('Branch') ?></label>                                        <div class="col-sm-9">                                            <select name="branch_id" class="chosen-select form-control"                                                    id="BranchAutoId" data-placeholder="Select Branch">                                                <option value=""></option>                                                <?php                                                if (!empty($branch_id)) {                                                    $selected = $branch_id;                                                } else {                                                    $selected = 'all';                                                }                                                // come from branch_dropdown_helper                                                echo branch_dropdown('all', $selected);                                                ?>                                            </select>                                        </div>                                    </div>                                </div>                                <div class="col-md-3">                                    <div class="form-group">                                        <label class="col-sm-3 control-label no-padding-right"                                               for="form-field-1"> <?php echo get_phrase('Supplier Id') ?></label>                                        <div class="col-sm-9">                                            <select id="customerid" name="supplierId" class="chosen-select form-control"                                                    id="form-field-select-3"                                                    data-placeholder="Search by Supplier ID or Name">                                                <option <?php                                                if ($supplierId == 'all') {                                                    echo "selected";                                                }                                                ?> value="all">All                                                </option>                                                <?php foreach ($supplierList as $key => $each_info): ?>                                                    <option <?php                                                    if (!empty($supplierId) && $supplierId == $each_info->sup_id) {                                                        echo "selected";                                                    }                                                    ?> value="<?php echo $each_info->sup_id; ?>"><?php echo $each_info->supName . ' [ ' . $each_info->supID . ' ] '; ?></option>                                                <?php endforeach; ?>                                            </select>                                        </div>                                    </div>                                </div>                                <div class="col-md-2">                                    <div class="form-group">                                        <label class="col-sm-4 control-label no-padding-right"                                               for="form-field-1"> <?php echo get_phrase('From Date') ?></label>                                        <div class="col-sm-8">                                            <input type="text" class="date-picker form-control" id="start_date"                                                   name="start_date" value="<?php                                            if (!empty($from_date)) {                                                echo $from_date;                                            } else {                                                echo date('d-m-Y');                                            }                                            ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>                                        </div>                                    </div>                                </div>                                <div class="col-md-2">                                    <div class="form-group">                                        <label class="col-sm-4 control-label no-padding-right"                                               for="form-field-1"> <?php echo get_phrase('To Date') ?></label>                                        <div class="col-sm-8">                                            <input type="text" class="date-picker form-control" id="end_date"                                                   name="end_date" value="<?php                                            if (!empty($to_date)):                                                echo $to_date;                                            else:                                                echo date('d-m-Y');                                            endif;                                            ?>" data-date-format='dd-mm-yyyy' placeholder="End Date:dd-mm-yyyy"/>                                        </div>                                    </div>                                </div>                                <div class="col-md-2">                                    <div class="form-group">                                        <div class="col-sm-6">                                            <button type="submit" class="btn btn-success btn-sm">                                                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>                                                <?php echo get_phrase('Search') ?>                                            </button>                                        </div>                                        <div class="col-sm-6">                                            <button type="button" class="btn btn-info btn-sm" onclick="window.print();"                                                    style="cursor:pointer;">                                                <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>                                                <?php echo get_phrase('Print') ?>                                            </button>                                        </div>                                    </div>                                </div>                            </div>                            <div class="clearfix"></div>                        </form>                    </div>                </div><!-- /.col -->            </div>        </div>    </div></div><?phpif (isset($_POST['start_date'])):    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));    $supplierId = $this->input->post('supplierId');    if ($supplierId != 'all'):        unset($_SESSION["supplierId"]);        unset($_SESSION["start_date"]);        unset($_SESSION["end_date"]);        $_SESSION["supplierId"] = $supplierId;        $_SESSION["start_date"] = $from_date;        $_SESSION["end_date"] = $to_date;        $dist_id = $this->dist_id;        $total_pvsdebit = '';        $total_pvscredit = '';        $total_debit = '';        $total_credit = '';        $total_balance = '';        ?>        <div class="row">            <div class="col-xs-12">                <div class="noPrint">                    <!--                            <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('InventoryController/supplierPurchasesReport_export_excel/') ?>" class="btn btn-success pull-right noPrint">                                <i class="ace-icon fa fa-download"></i>                                Excel                            </a>-->                </div>                <table class="table table-responsive">                    <tr>                        <td style="text-align:center;">                            <h3><?php echo $companyInfo->companyName; ?>.</h3>                            <span><?php echo $companyInfo->address; ?></span><br>                            <strong><?php echo get_phrase('Phone') ?> : </strong><?php echo $companyInfo->phone; ?><br>                            <strong><?php echo get_phrase('Email') ?> : </strong><?php echo $companyInfo->email; ?><br>                            <strong><?php echo get_phrase('Website') ?> : </strong><?php echo $companyInfo->website; ?>                            <br>                            <strong><?php echo get_phrase($pageTitle); ?></strong>                            <strong><?php echo get_phrase('Purchases Report ') ?>  <?php echo $from_date; ?>                                To <?php echo $to_date; ?></strong>                        </td>                    </tr>                </table>                <table class="table table-bordered">                    <thead>                    <tr>                        <td align="center"><strong><?php echo get_phrase('Branch') ?></strong></td>                        <td align="center"><strong><?php echo get_phrase('Date') ?></strong></td>                        <td align="center"><strong><?php echo get_phrase('Voucher No') ?>.</strong></td>                        <td align="center"><strong><?php echo get_phrase('Payment Type') ?></strong></td>                        <td align="center"><strong><?php echo get_phrase('Memo') ?></strong></td>                        <td align="center"><strong><?php echo get_phrase('Amount') ?></strong></td>                    </tr>                    </thead>                    <tbody>                    <?php                    $this->db->select("purchase_invoice_info.*,IFNULL(branch.branch_name,'NA') as branch_name");                    $this->db->join('branch', 'branch.branch_id=purchase_invoice_info.branch_id', 'left');                    //$this->db->where('dist_id', $dist_id);                    if ($branch_id != 'all') {                        $this->db->where('branch.branch_id', $branch_id);                    }                    $this->db->where('purchase_invoice_info.supplier_id', $supplierId);                    $this->db->where('purchase_invoice_info.invoice_date >=', $from_date);                    $this->db->where('purchase_invoice_info.invoice_date <=', $to_date);                    $this->db->where('purchase_invoice_info.is_opening !=', 1);                    //$this->db->group_by('purchase_invoice_info.supplier_id,purchase_invoice_info.branch_id');                    $this->db->order_by('branch.branch_name', 'ASC');                    $query = $this->db->get('purchase_invoice_info')->result_array();                    $total_debit = 0;                    $brandNameArray = array();                    foreach ($query as $row):                        if (!in_array($row['branch_name'], $brandNameArray)) {                            array_push($brandNameArray, $row['branch_name']);                            ?>                            <tr>                                <td colspan="6">                                    <?php echo '<b>' . $row['branch_name'] . '</b>' ?>                                </td>                            </tr>                            <tr>                                <td colspan="2" align="right"><?php echo date('M d, Y', strtotime($row['invoice_date'])); ?></td>                                <td><a target="_blank"                                       href="<?php echo site_url($this->project.'/viewPurchasesCylinder/' . $row['purchase_invoice_id']); ?>"><?php echo $row['invoice_no']; ?></a>                                </td>                                <td><?php                                    if ($row['payment_type'] == 1) {                                        echo "Cash";                                    } elseif ($row['payment_type'] == 2) {                                        echo "Credit";                                    } elseif ($row['payment_type'] == 3) {                                        echo "Bank";                                    } else {                                        echo "Cash";                                    }                                    ?></td>                                <td><?php echo $row['memo']; ?></td>                                <td align="right"><?php                                    echo number_format((float)abs($row['invoice_amount']), 2, '.', ',');                                    $total_debit += $row['invoice_amount'];                                    ?></td>                            </tr>                        <?php } else {                            ?>                            <tr>                                <td colspan="2" align="right"><?php echo date('M d, Y', strtotime($row['invoice_date'])); ?></td>                                <td><a target="_blank"                                       href="<?php echo site_url('viewPurchasesCylinder/' . $row['purchase_invoice_id']); ?>"><?php echo $row['invoice_no']; ?></a>                                </td>                                <td><?php                                    if ($row['payment_type'] == 1) {                                        echo "Cash";                                    } elseif ($row['payment_type'] == 2) {                                        echo "Credit";                                    } elseif ($row['payment_type'] == 3) {                                        echo "Bank";                                    } else {                                        echo "Cash";                                    }                                    ?></td>                                <td><?php echo $row['memo']; ?></td>                                <td align="right"><?php                                    echo number_format((float)abs($row['invoice_amount']), 2, '.', ',');                                    $total_debit += $row['invoice_amount'];                                    ?></td>                            </tr>                        <?php } endforeach; ?>                    <!-- /Search Balance -->                    </tbody>                    <tfoot>                    <tr>                        <td colspan="5" align="right">                            <strong><?php echo get_phrase('Total Purchases Amount') ?></strong></td>                        <td align="right"><strong><?php echo number_format((float)abs($total_debit), 2, '.', ','); ?>                                &nbsp;</strong></td>                    </tr>                    </tfoot>                </table>            </div>        </div>        <?php    else:        unset($_SESSION["supplierId"]);        unset($_SESSION["start_date"]);        unset($_SESSION["end_date"]);        $_SESSION["supplierId"] = $supplierId;        $_SESSION["start_date"] = $from_date;        $_SESSION["end_date"] = $to_date;        $dist_id = $this->dist_id;        $total_pvsdebit = '';        $total_pvscredit = '';        $total_debit = '';        $total_credit = '';        $total_balance = '';        ?>        <div class="row">            <div class="col-xs-12">                <div class="noPrint">                </div>                <table class="table table-responsive">                    <tr>                        <td style="text-align:center;">                            <h3><?php echo $companyInfo->companyName; ?>.</h3>                            <span><?php echo $companyInfo->address; ?></span><br>                            <strong><?php echo get_phrase('Phone') ?> : </strong><?php echo $companyInfo->phone; ?><br>                            <strong><?php echo get_phrase('Email') ?> : </strong><?php echo $companyInfo->email; ?><br>                            <strong><?php echo get_phrase('Website') ?> : </strong><?php echo $companyInfo->website; ?>                            <br>                            <strong><?php echo get_phrase($pageTitle); ?></strong>                            <strong><?php echo get_phrase('Purchases Report ') ?>  <?php echo $from_date; ?>                                To <?php echo $to_date; ?></strong>                        </td>                    </tr>                </table>                <table class="table table-bordered">                    <thead>                    <tr>                        <td align="center"><strong><?php echo get_phrase('Branch') ?></strong></td>                        <td align="center"><strong><?php echo get_phrase('Sl') ?></strong></td>                        <td align="center"><strong><?php echo get_phrase('Supplier') ?></strong></td>                        <td align="center"><strong><?php echo get_phrase('Amount') ?></strong></td>                    </tr>                    </thead>                    <tbody>                    <?php                    $total_debit = 0;                    $sl = 1;                    //foreach ($supplierList as $key => $value) :                    $this->db->select("sum(purchase_invoice_info.invoice_amount) as totalAmount,supplier.supID,supplier.sup_id,supplier.supName,branch.branch_id,IFNULL(branch.branch_name,'NA') as branch_name");                    $this->db->join('supplier', 'supplier.sup_id=purchase_invoice_info.supplier_id', 'left');                    $this->db->join('branch', 'branch.branch_id=purchase_invoice_info.branch_id', 'left');                    //$this->db->where('purchase_invoice_info.dist_id', $dist_id);                    if ($branch_id != 'all') {                        $this->db->where('purchase_invoice_info.branch_id', $branch_id);                    }                    //$this->db->where('supplier_id', $value->sup_id);                    $this->db->where('purchase_invoice_info.invoice_date >=', $from_date);                    $this->db->where('purchase_invoice_info.invoice_date <=', $to_date);                    $this->db->where('purchase_invoice_info.is_opening !=', 1);                    $this->db->group_by('purchase_invoice_info.supplier_id,purchase_invoice_info.branch_id');                    $this->db->order_by('branch.branch_name', 'ASC');                    //$this->db->order_by('supplier.supName', 'ASC');                    $purchasesInfo = $this->db->get('purchase_invoice_info')->result();                    $brandNameArray = array();                    foreach ($purchasesInfo as $key1 => $value1) {                        if (!empty($value1->totalAmount)):                            $total_debit += $value1->totalAmount;                            if (!in_array($value1->branch_name, $brandNameArray)) {                                array_push($brandNameArray, $value1->branch_name);                                ?>                                <tr>                                    <td colspan="4">                                        <?php echo '<b>' . $value1->branch_name . '</b>' ?>                                    </td>                                </tr>                                <tr>                                    <td colspan="2" align="right"><?php echo $sl++; ?></td>                                    <td>                                        <a href="javascript:void(0)"><?php echo $value1->supID . ' [ ' . $value1->supName . ' ] '; ?></a>                                    </td>                                    <td align='right'><?php echo number_format($value1->totalAmount); ?></td>                                </tr>                                <?php                            } else {                                ?>                                <tr>                                    <td colspan="2" align="right"><?php echo $sl++; ?></td>                                    <td>                                        <a href="<?php echo site_url('supplierDashboard/' . $value1->sup_id); ?>"><?php echo $value1->supID . ' [ ' . $value1->supName . ' ] '; ?></a>                                    </td>                                    <td align='right'><?php echo number_format($value1->totalAmount); ?></td>                                </tr>                                <?php                            }endif;                    }                    //endforeach;                    ?>                    <!-- /Search Balance -->                    </tbody>                    <tfoot>                    <tr>                        <td colspan="3" align="right">                            <strong><?php echo get_phrase('Total Purchases Amount') ?></strong></td>                        <td align="right"><strong><?php echo number_format((float)abs($total_debit), 2, '.', ','); ?>                                &nbsp;</strong></td>                    </tr>                    </tfoot>                </table>            </div>        </div>    <?php endif; ?><?php endif; ?><script>    $(document).ready(function () {        $('.date-picker').datepicker({            autoclose: true,            todayHighlight: true        })    });</script>