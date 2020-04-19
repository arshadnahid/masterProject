<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Sales</a>
                </li>
                <li class="active">Sales Order</li>
            </ul>
            
            
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="saleAddPermission" href="<?php echo site_url('salesOrderAdd'); ?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Add 
                    </a>
                </li>
                

            </ul>
            
            
            
        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Sales Order List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>SV.No</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Order Date</th>
                                <th>Delivery Date</th>
                                <th>Narration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($saleslist as $key => $value):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>

                                    <td><?php echo $value->voucher_no; ?></td>
                                    <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                    <td nowrap>
                                        <a href="<?php echo site_url('customerDashboard/'.$value->customer_id);?>">
                                        <?php
                            if (!empty($value->customer_id)):
                                $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $value->customer_id);

                                echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                            endif;
                                ?></a></td>
                                    <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($value->deliveryDate)); ?></td>
                                    <td><?php echo $value->narration; ?></td>
                                    <td nowrap>
                                        <?php if ($value->orderStatus == 1): ?>
                                            <a href="<?php echo site_url('salesOrderConfirm/' . $value->generals_id); ?>"> <span class="label label-xlg label-info arrowed arrowed-right"><i class="fa fa-refresh fa-spin fa-fw"></i>&nbsp;Confirm ? </span>
                                            </a>
                                            <a href="<?php echo site_url('salesOrderCancel/' . $value->generals_id); ?>"> <span class="label label-xlg label-danger arrowed arrowed-right"><i class="fa fa-refresh fa-spin fa-fw"></i>&nbsp;Cancel ? </span>
                                            </a>

                                        <?php elseif ($value->orderStatus == 2): ?>
                                            <span class="label label-xlg label-success arrowed arrowed-right"><i class="fa fa-check"></i>&nbsp;Confirm</span>



                                        <?php else: ?>
                                            <span class="label label-xlg label-danger arrowed arrowed-right"><i class="fa fa-times"></i>&nbsp;Cancel</span>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="blue" href="<?php echo site_url('salesOrderView/' . $value->generals_id); ?>">
                                                <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                            </a>
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
</div>





