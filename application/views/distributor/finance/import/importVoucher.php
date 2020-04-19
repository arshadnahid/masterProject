<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Import</a>
                </li>
                <li class="active">Financial Import List</li>
            </ul>
            
            <ul class="breadcrumb pull-right">
                
                <li class="active financeAddPermission"><a href="<?php echo site_url('financeImportAdd'); ?>" >
                        <i class="ace-icon 	fa fa-plus"></i>  Add
                    </a>
                </li>
               
            </ul>
            
            
        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Financial import List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th> Date</th>
                                <th>Voucher Type</th>
                                <th>Voucher ID</th>
                                <th>Payee</th>
                                <th>Payee To</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($voucherList as $key => $value):
                                //  dumpVar($value);

                                $quanitty = explode(",", $value->quantity);
                                $ttQty = 0;
                                foreach ($quanitty as $eachQty):
                                    $ttQty+=$eachQty;
                                endforeach;
                                $rate = explode(",", $value->rate);
                                $price = explode(",", $value->price);
                                $ttPrice = 0;
                                foreach ($price as $eachPrice):
                                    $ttPrice+=$eachPrice;
                                endforeach;
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->purchasesDate; ?></td>
                                    <td><?php
                            if ($value->type == 3) {
                                echo "Payment Voucher";
                            } elseif ($value->type == 4) {
                                echo "Receive Voucher";
                            } else {
                                echo "Journal Voucher";
                            }
                                ?></td>
                                    <td><?php echo $value->voucherid; ?></td>
                                    <td><?php
                                    if ($value->payee == 1) {
                                        echo "Miscellaneous";
                                    } elseif ($value->payee == 2) {
                                        echo "Customer";
                                    } elseif($value->payee == 3){
                                        echo "Supplier";
                                    }else{
                                        echo "Journal Voucher";
                                    }
                                ?></td>



                                    <td nowrap>

                                        <?php
                                        if ($value->payee == 1) {
                                            echo $value->supplierID;
                                        } elseif ($value->payee == 2) {
                                            $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $value->supplierID);
                                            echo $customerInfo->customerName . ' [ ' . $customerInfo->customerID . ' ] ';
                                        } elseif($value->payee == 3){
                                            $supInfo = $this->Common_model->tableRow('supplier', 'sup_id', $value->supplierID);
                                            echo $supInfo->supName . ' [ ' . $supInfo->supID . ' ] ';
                                        }else{
                                            echo "Journal Voucher";
                                        }
                                        ?></td>


                                    <td width="20%">
                                        <?php
                                        $totalprice = 0;
                                        $price = explode(',', $value->drAmount);
                                        foreach ($price as $key => $val) {
                                            $totalprice+=$val;
                                        }
                                        echo $totalprice;
                                        ?>
                                    </td>

                                    <td nowrap>
                                        <?php if ($value->ConfirmStatus == 0): ?>
                                            <?php
                                            if ($value->type == 3) {
                                                ?>
                                                <a title="Posting" href="<?php echo site_url('paymentVoucherPosting/' . $value->purchase_demo_id); ?>"> <span class="label label-xlg label-danger arrowed arrowed-right"><i class="fa fa-refresh fa-spin fa-fw"></i>&nbsp;Pendig </span></a>
                                                <?php
                                            } elseif ($value->type == 4) {
                                                ?>
                                                <a title="Posting" href="<?php echo site_url('receiveVoucherPosting/' . $value->purchase_demo_id); ?>"> <span class="label label-xlg label-danger arrowed arrowed-right"><i class="fa fa-refresh fa-spin fa-fw"></i>&nbsp;Pendig </span></a>
                                                <?php
                                            } else {
                                                ?>
                                                <a title="Posting" href="<?php echo site_url('journalVoucherPosting/' . $value->purchase_demo_id); ?>"> <span class="label label-xlg label-danger arrowed arrowed-right"><i class="fa fa-refresh fa-spin fa-fw"></i>&nbsp;Pendig </span></a>
                                                <?php
                                            }
                                            ?>
                                        <?php else: ?>
                                            <span class="label label-xlg label-success arrowed arrowed-right"><i class="fa fa-check"></i>&nbsp;Posted</span>
                                        <?php endif; ?>
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
