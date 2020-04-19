<?php
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 'all';
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Sales Report') ?> </div>

            </div>

            <div class="portlet-body">
                <form id="publicForm" action="" method="get" class="form-horizontal">
                    <div class="form-body noPrint">
                        <div class="row">

                            <div class="col-sm-12 col-md-6" style="margin-top: 10px;">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                        <strong><?php echo get_phrase('Branch') ?></strong></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <select id="branch_id" name="branch_id"
                                                    class="chosen-select form-control"

                                                    data-placeholder="Select Branch Name">
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
                                <div class="form-group">
                                    <label class="col-sm-3 control-label formfonterp" for="form-field-1"> <strong>Customer
                                            Name</strong></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <select id="customerid" name="customer_id"
                                                    class="chosen-select form-control"

                                                    data-placeholder="Select Customer Name">
                                                <option value="all"<?php echo $customer_id=='all'?"selected":""?>>All</option>
                                                <?php foreach ($customerList as $key => $each_info):



                                                    ?>
                                                    <option value="<?php echo $each_info->customer_id; ?>"   <?php

                                                    if ( $each_info->customer_id == $customer_id) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo $each_info->customerName . '&nbsp&nbsp[ ' . $each_info->typeTitle . ' ] '; ?></option>

                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="col-sm-12 col-md-6" style="margin-top: 10px;">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                            <strong> <?php echo get_phrase('From') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input class="form-control date-picker" name="start_date"
                                                       id="start_date"
                                                       type="text"
                                                       value="<?php
                                                       if (!empty($start_date)) {
                                                           echo $start_date;
                                                       } else {
                                                           echo date('d-m-Y');
                                                       }
                                                       ?>" data-date-format="dd-mm-yyyy"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                             </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                            <strong> <?php echo get_phrase('To') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="end_date"
                                                       type="text" name="end_date"
                                                       value="<?php
                                                       if (!empty($end_date)):
                                                           echo $end_date;
                                                       else:
                                                           echo date('d-m-Y');
                                                       endif;
                                                       ?>" data-date-format="dd-mm-yyyy"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                             </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12 col-md-6" style="">
                                <div class="col-sm-12 col-md-6" style="">


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                            <strong><?php echo get_phrase('Type') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="type" name="type"
                                                        class="chosen-select form-control"

                                                        data-placeholder="Select Branch Name">
                                                    <option value="all" <?php echo $type=='all'?'selected':""?> >ALL</option>
                                                    <option value="due" <?php echo $type=='due'?'selected':""?> >DUE</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6" style="">


                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                <?php echo get_phrase('Search') ?>
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button type="button" class="btn btn-info btn-sm" id="btn-print"
                                                    onclick="window.print();" style="cursor:pointer;">
                                                <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                                <?php echo get_phrase('Print') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            <div class="col-md-2">

                            </div>
                        </div>
                    </div>
                </form>

                <?php
                if (isset($sales_data) && !empty($sales_data)):
                    ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="noPrint">
                                <!--                            <button style="border-radius:100px 0 100px 0;" href="<?php echo site_url('SalesController/salesReport_export_excel/') ?>" class="btn btn-success pull-right noPrint">
                                    <i class="ace-icon fa fa-download"></i>
                                    Excel
                                </button>-->
                            </div>
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
                                        <strong><?php echo $pageTitle; ?></strong>
                                        <strong><?php echo get_phrase('From') ?> <?php echo $start_date; ?> <?php echo get_phrase('To') ?> <?php echo $end_date; ?></span></strong>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td align="center"><strong><?php echo get_phrase('Branch') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Date') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Voucher No') ?>.</strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Due Date') ?>.</strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Customer') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Payment Type') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Memo') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Amount') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Paid Amount') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Due Amount') ?></strong></td>
                                    <!--<td align="center"><strong>GP Amount</strong></td>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_debit = 0;
                                $total_paid_amount = 0;
                                $total_due_amount = 0;
                                $totalGpAmount = 0;
                                $brandNameArray = array();
                                foreach ($sales_data as $ind => $row):
                                    if (!in_array($row->branch_name, $brandNameArray)) {
                                        array_push($brandNameArray, $row->branch_name); ?>

                                        <tr>
                                            <td colspan="10">
                                                <?php echo '<b>' . $row->branch_name . '</b>' ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                align="right"><?php echo date('M d, Y', strtotime($row->invoice_date)); ?>
                                            </td>
                                            <td><a title="view invoice"
                                                   href="<?php echo site_url($this->project . '/salesInvoice_view/' . $row->sales_invoice_id); ?>"><?php echo $row->invoice_no; ?></a>
                                            </td>
                                            <td align="left"><?php echo date('M d, Y', strtotime($row->due_date)); ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)">
                                                    <?php
                                                    //$customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $row->customer_id);
                                                    echo $row->customerID . '[ ' . $row->customerName . ']';
                                                    ?>
                                                </a>
                                            </td>
                                            <td><?php
                                                if ($row->payment_type == 1) {
                                                    echo "Cash";
                                                } elseif ($row->payment_type == 2) {
                                                    echo "Credit";
                                                } elseif ($row->payment_type == 3) {
                                                    echo "Bank";
                                                } else {
                                                    echo "Cash";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row->narration; ?></td>
                                            <td align="right">
                                                <?php
                                                echo number_format((float)abs($row->invoice_amount), 2, '.', ',');
                                                $total_debit += $row->invoice_amount;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo number_format((float)abs($row->paid_amount), 2, '.', ',');
                                                $total_paid_amount += $row->paid_amount;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo number_format((float)abs($row->balance), 2, '.', ',');
                                                $total_due_amount += $row->balance;
                                                ?>
                                            </td>

                                        </tr>

                                    <?php } else {
                                        ?>
                                        <tr>
                                            <td colspan="2"
                                                align="right"><?php echo date('M d, Y', strtotime($row->invoice_date)); ?></td>
                                            <td><a title="view invoice"
                                                   href="<?php echo site_url($this->project . '/salesInvoice_view/' . $row->sales_invoice_id); ?>"><?php echo $row->invoice_no; ?></a>
                                            </td>
                                            <td align="left"><?php echo date('M d, Y', strtotime($row->due_date)); ?>
                                            <td>
                                                <a href="javascript:void(0)">
                                                    <?php
                                                    //$customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $row->customer_id);
                                                    echo $row->customerID . '[ ' . $row->customerName . ']';
                                                    ?>
                                                </a>
                                            </td>
                                            <td><?php
                                                if ($row->payment_type == 1) {
                                                    echo "Cash";
                                                } elseif ($row->payment_type == 2) {
                                                    echo "Credit";
                                                } elseif ($row->payment_type == 3) {
                                                    echo "Bank";
                                                } else {
                                                    echo "Cash";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $row->narration; ?></td>
                                            <td align="right"><?php
                                                echo number_format((float)abs($row->invoice_amount), 2, '.', ',');
                                                $total_debit += $row->invoice_amount;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo number_format((float)abs($row->paid_amount), 2, '.', ',');
                                                $total_paid_amount += $row->paid_amount;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo number_format((float)abs($row->balance), 2, '.', ',');
                                                $total_due_amount += $row->balance;
                                                ?>
                                            </td>

                                        </tr>
                                    <?php }
                                endforeach; ?>
                                <!-- /Search Balance -->
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="7" align="right"><strong>Total Sales Amount</strong></td>
                                    <td align="right">
                                        <strong>
                                            <?php echo number_format((float)abs($total_debit), 2, '.', ','); ?>
                                            &nbsp;</strong>
                                    </td>
                                    <td align="right">
                                        <strong>
                                            <?php echo number_format((float)abs($total_paid_amount), 2, '.', ','); ?>
                                            &nbsp;</strong>
                                    </td>
                                    <td align="right">
                                        <strong>
                                            <?php echo number_format((float)abs($total_due_amount), 2, '.', ','); ?>
                                            &nbsp;</strong>
                                    </td>

                                </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                <?php endif; ?>
            </div>
        </div>
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



