<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Purchase Demo List</li>
            </ul>
            
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="inventoryAddPermission" href="<?php echo site_url('demoImport'); ?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Add 
                    </a>
                </li>
                
            </ul>
            
            
            
        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Purchase Demo List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Voucher ID</th>
                                <th>Supplier</th>
                                <th>Reference</th>
                                <th>Purchases Date</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total Price</th>
                              
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($PurchaseDemoList as $key => $value):
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

                                    <td><?php echo $value->voucherid; ?></td>
                                    <td nowrap><?php
                            $supInfo = $this->Common_model->tableRow('supplier', 'sup_id', $value->supplierID);
                            echo $supInfo->supName . ' [ ' . $supInfo->supID . ' ] '
                                ?></td>
                                    <td><?php echo $value->reference; ?></td>
                                    <td><?php echo $value->purchasesDate; ?></td>
                                    <td width="20%">
                                        <?php
                                        $product = explode(',', $value->productID);
                                        foreach ($product as $key => $val) {
                                            $productResult = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $val);
                                            echo $productResult->productName . ', ';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $ttQty; ?></td>
                                    <td nowrap><?php echo $ttPrice; ?></td>
                                    <td nowrap>
                                        <?php if ($value->ConfirmStatus == 0): ?>
                                            <a href="<?php echo site_url('PurchaseDemoConfirm/' . $value->purchase_demo_id); ?>"> <span class="label label-xlg label-danger arrowed arrowed-right"><i class="fa fa-refresh fa-spin fa-fw"></i>&nbsp;Pendig </span></a>
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
