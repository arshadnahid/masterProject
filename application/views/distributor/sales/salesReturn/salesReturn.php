
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Sales Return List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Invoice ID</th>
                            <th>Return Amount</th>
                            <th>Action</th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($salesReturnList as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $value->return_date; ?></td>
                                <td><?php echo $this->Common_model->tableRow('customer', 'customer_id', $value->customer_id)->customerName; ?></td>
                                <td><?php echo $value->invoice_no; ?></td>
                                <td align="right"><?php echo $value->amount; ?></td>
                                <td>
                                    <a class="green" href="<?php echo site_url($this->project .'/viewSalesReturn/'. $value->sales_return_id); ?>">
                                        <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                    </a>

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
