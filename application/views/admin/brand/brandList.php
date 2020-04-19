<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Inventory</a>
                </li>
                <li class="active">Brand List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('adminBrandAdd'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a>
            </span>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Brand  List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Brand Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($brandList as $key => $value):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->brandName; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="blue" href="<?php echo site_url('adminBrandUpdate/' . $value->brandId); ?>">
                                               <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>

                <!--                                            <a class="green" href="<?php echo site_url('editPurchases/' . $value->brandId); ?>">
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
</div>





