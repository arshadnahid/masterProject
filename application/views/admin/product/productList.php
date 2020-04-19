<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">Product List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a  style="border-radius:100px 0 100px 0;" href="<?php echo site_url('adminProductAdd'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a>
            </span>
        </div>


        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Product List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Product Category</th>
                                <th>Brand</th>
                                <th>Product Code</th>
                                <th>Model Name</th>
                                <th>Purchases Price</th>
                                <th>Retail Price(MRP)</th>
                                <th>Whole Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($productList as $key => $value):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>

                                    <td><?php echo $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $value->category_id)->title; ?></td>
                                    <td><?php echo $this->Common_model->tableRow('brand','brandId',$value->brand_id)->brandName; ?></td>
                                    <td><?php echo $value->product_code; ?></td>
                                    <td><?php echo $value->productName; ?></td>
                                    <td><?php echo $value->purchases_price; ?></td>
                                    <td><?php echo $value->salesPrice; ?></td>
                                    <td><?php echo $value->retailPrice; ?></td>
                                    <td>
                                        <?php
                                        if ($value->status == 1):
                                            ?>
                                            <a href="javascript:void(0)" onclick="productStatusChange('<?php echo $value->product_id; ?>','2')" class="label label-danger arrowed">
                                                <i class="ace-icon fa fa-fire bigger-110"></i>
                                                Inactive</a>
                                        <?php else: ?>
                                            <a href="javascript:void(0)" onclick="productStatusChange('<?php echo $value->product_id; ?>','1')" class="label label-success arrowed">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Active
                                            </a>
                                        <?php endif; ?>

                                    </td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="green" href="<?php echo site_url('adminUpdateProduct/' . $value->product_id); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="red" href="javascript:void(0)" onclick="deleteData('product','product_id','productList','<?php echo $value->product_id; ?>')">
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




