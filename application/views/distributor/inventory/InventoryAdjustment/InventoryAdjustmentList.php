<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Cylinder Exchange List')?> </div>
            </div>
            <div class="portlet-body">

                <div class="row">


                    <table id="example" class="table table-striped table-bordered table-hover">

                        <thead>

                        <tr>

                            <th>Serial No</th>

                            <th>Invoice Adjustment No</th>
                            <th>Action</th>

                        </tr>

                        </thead>

                        <tbody>




                        <?php
                        foreach ($adjustmentBrand as $key => $value):
                            ?>

                            <tr>
                                <td><?php echo $key + 1; ?></td>

                                <td><?php echo  $value->inv_adjustment_no; ?></td>

                                <td>
                                    <a class="btn btn-icon-only red" href="<?php echo site_url($this->project . '/invoiceAdjustmentShow/' . $value->id) ?>">

                                        <i class="fa fa-search-plus"></i>

                                    </a>

                                </td>

                            </tr>
                        <?php endforeach; ?>


                        </tbody>

                    </table>

                </div><!-- /.col -->

            </div><!-- /.row -->

        </div><!-- /.page-content -->

    </div>
</div>











