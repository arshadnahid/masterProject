

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-truck"></i>
                    <a href="<?php echo site_url('DistributorDashboard/2'); ?>">Sales</a>
                </li>
                <li class="active">Sales Pos List</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="saleAddPermission" href="<?php echo site_url('salesPos'); ?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Add 
                    </a>
                </li>
            </ul>

        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Sales List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Date</th>
                                <th>SV.No</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th>Memo</th>
                                <th>Amount</th>
                                <th>GP Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($saleslist as $key => $value):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                    <td><a title="view invoice" href="<?php echo site_url('salesInvoice_view/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>
                                    <td><?php echo $value->name; ?></td>
                                    <td><a href="<?php echo site_url('customerDashboard/' . $value->customer_id); ?>"><?php
                            echo $value->customerID . ' [ ' . $value->customerName . ' ] ';
                                ?></a></td>
                                    <td><?php echo $value->narration; ?></td>
                                    <td align="right"><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                    <td align="right"><?php echo number_format((float) $this->Sales_Model->getGpAmountByInvoiceId($this->dist_id, $value->generals_id), 2, '.', ','); ?></td>
                                    <td nowrap>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="blue" href="<?php echo site_url('salesInvoice_view/' . $value->generals_id); ?>">
                                                <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                            </a>
                                            <?php
                                            $date = strtotime('08-12-2018');
                                            $currentDate = strtotime($value->date);
                                            if ($date <= $currentDate):
                                                ?>
                                                <a class="info saleEditPermission" href="<?php echo site_url('salesInvoice_edit/' . $value->generals_id); ?>">
                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                </a>
                                                <?php
                                            endif;
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>





