<div class="tabbable"   >
    <ul class="nav nav-tabs" id="myTab" >
        <li class="active">
            <a data-toggle="tab" href="#supplierInfo">
                <i class="green ace-icon fa fa-home bigger-120"></i>
                Sales List
                <span class="badge badge-danger"><?php echo count($todaysSalesList); ?></span>
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#purchasesList">
                Purchases List
                <span class="badge badge-danger"><?php echo count($todaysPurchases); ?></span>
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#paymentList">
                Payment List
                <span class="badge badge-danger"><?php echo count($todaysPayment); ?></span>
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#voucherList">
                Receive List
                <span class="badge badge-danger"><?php echo count($todaysReceive); ?></span>
            </a>
        </li>
    </ul>
    <div class="tab-content" style="height:165px;overflow-x: hidden">
        <div id="supplierInfo" class="tab-pane fade in active">
            <div>
                <table  class="table table-striped table-bordered table-hover" style=" overflow-y: hidden;">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th>SV.No</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($todaysSalesList as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td nowrap><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                <td nowrap><a title="view invoice" href="<?php echo site_url('salesInvoice_view/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>
                                <td nowrap><a href="<?php echo site_url('customerDashboard/' . $value->customer_id); ?>"><?php echo $value->customerID . ' [ ' . $value->customerName . ' ] '; ?></a></td>
                                <td nowrap><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                <td>
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a class="blue" href="<?php echo site_url('salesInvoice_view/' . $value->generals_id); ?>">
                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="purchasesList" class="tab-pane fade">
            <div>
                <table  id=""  class="table table-striped table-bordered table-hover" style=" overflow-y: hidden;">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th>SV.No</th>

                            <th>Supplier</th>

                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($todaysPurchases as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td nowrap><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                <td nowrap><a title="view Voucher" href="<?php echo site_url('viewPurchases/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>
                                <td nowrap><a href="<?php echo site_url('supplierDashboard/' . $value->supplier_id); ?>"><?php echo $value->supID . ' [ ' . $value->supName . ' ] '; ?></a></td>
                                <td nowrap align="right"><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                <td>
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a class="blue" href="<?php echo site_url('viewPurchases/' . $value->generals_id); ?>">
                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="paymentList" class="tab-pane fade">
            <div>
                <table   class="table table-striped table-bordered table-hover" style=" overflow-y: hidden;">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th nowrap>PV.No</th>

                            <th nowrap>Payment.Type</th>
                            <th nowrap>Payment.By</th>

                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($todaysPayment as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td nowrap><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                <td nowrap><a href="<?php echo site_url('paymentVoucherView/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>

                                <td nowrap>Cash</td>
                                <td nowrap><?php
                        if (!empty($value->customerName)) {
                            echo $value->customerID . ' [' . $value->customerName . ' ] ';
                        } elseif ($value->supID) {
                            echo $value->supID . ' [' . $value->supName . ' ] ';
                        } else {
                            echo $value->miscellaneous;
                        }
                            ?></td>
                                <td nowrap><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                <td>
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a class="blue" href="<?php echo site_url('paymentVoucherView/' . $value->generals_id); ?>">
                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                        </a>
                                                                                                                                                                                    <!--                                            <a class="green" href="<?php echo site_url('editPurchases/' . $value->generals_id); ?>">
                                                                                                                                                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                                                                                                                                                    </a>-->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="voucherList" class="tab-pane fade">
            <div>
                <table   class="table table-striped table-bordered table-hover" style=" overflow-y: hidden;">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th nowrap>PV.No</th>

                            <th nowrap>Payment.Type</th>
                            <th nowrap>Payment By</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($todaysReceive as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td nowrap><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                <td nowrap><a href="<?php echo site_url('paymentVoucherView/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>

                                <td nowrap>Cash</td>
                                <td nowrap><?php
                        if (!empty($value->customerName)) {
                            echo $value->customerID . ' [' . $value->customerName . ' ] ';
                        } elseif ($value->supID) {
                            echo $value->supID . ' [' . $value->supName . ' ] ';
                        } else {
                            echo $value->miscellaneous;
                        }
                            ?></td>
                                <td nowrap><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                <td>
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a class="blue" href="<?php echo site_url('paymentVoucherView/' . $value->generals_id); ?>">
                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                        </a>
                                                                                                                                                                                    <!--                                            <a class="green" href="<?php echo site_url('editPurchases/' . $value->generals_id); ?>">
                                                                                                                                                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                                                                                                                                                    </a>-->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="salesOrder" class="tab-pane fade">
            <div>
                <table  class="table table-striped table-bordered table-hover" style=" overflow-y: hidden;">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th nowrap>SV.No</th>

                            <th>Customer</th>
                            <th>Amount</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($salesOrder as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td nowrap><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                <td nowrap><a title="view invoice" href="<?php echo site_url('salesInvoice_view/' . $row['generals_id']); ?>"><?php echo $value->voucher_no; ?></a></td>
                                <td nowrap><?php
                        $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $value->customer_id);
                        echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                            ?></td>
                                <td nowrap><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                <td nowrap>
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a class="blue" href="<?php echo site_url('salesInvoice_view/' . $value->generals_id); ?>">
                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>