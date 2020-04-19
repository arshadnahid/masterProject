<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Purchases List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('purchases_add'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a>
            </span>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Purchases List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Date</th>
                                <th>PV.No</th>
                                <th>Type</th>
                                <th>Supplier</th>
                                <th>Amount</th>
                                <th>Memo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($purchasesList as $key => $value):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                    <td><?php echo $value->voucher_no; ?></td>
                                    <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                    <td><?php
                            $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $value->supplier_id);

                            echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                ?></td>
                                    <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                    <td><?php echo $value->narration; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="blue" href="<?php echo site_url('viewPurchases/' . $value->generals_id); ?>">
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
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>





