<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">Product Category List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('adminProductCatAdd'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a></span>
        </div>


        <div class="page-content">
            <div class="row">
                <!--                <h3 class="header smaller lighter blue"></h3>
                
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>-->
                <div class="table-header">
                    Product Category List
                </div>


                <!-- div.table-responsive -->

                <!-- div.dataTables_borderWrap -->
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Product Cat Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($productCatList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->title; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="green" href="<?php echo site_url('adminUpdateProductCat/' . $value->category_id); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="red" href="javascript:void(0)" onclick="deleteData('productcategory','category_id','productCatList','<?php echo $value->category_id; ?>')">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
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
</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>




