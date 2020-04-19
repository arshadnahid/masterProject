<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">Supplier List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a href="<?php echo site_url('Supplier'); ?>" class="btn btn-success pull-right">
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
                    Supplier List
                </div>


                <!-- div.table-responsive -->

                <!-- div.dataTables_borderWrap -->
                <div>
                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Supplier ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Image</th>
                                <th class="hidden-480">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($supplierList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->supID; ?></td>
                                    <td><?php echo $value->supName; ?></td>
                                    <td><?php echo $value->supEmail; ?></td>
                                    <td><?php echo $value->supPhone; ?></td>
                                    <td><?php echo $value->supAddress; ?></td>
                                    <td><img height="100px" width="100px" src="<?php echo base_url('uploads/thumb/' . $value->supLogo); ?>" class="img-thumbnail"/></td>
                                    <td><?php
                            if ($value->status == 1):
                                    ?>
                                            <a href="javascript:void(0)" onclick="supplierStatusChange('<?php echo $value->sup_id; ?>','2')" class="label label-danger arrowed">
                                                <i class="ace-icon fa fa-fire bigger-110"></i>
                                                Inactive</a>
                                        <?php else: ?>
                                            <a href="javascript:void(0)" onclick="supplierStatusChange('<?php echo $value->sup_id; ?>','1')" class="label label-success arrowed">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Active
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <!--                                            <a class="blue" href="#">
                                                                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                                                        </a>-->

                                            <a class="green" href="<?php echo site_url('supplierUpdate/' . $value->sup_id); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>

                                            <a class="red" href="javascript:void(0)" onclick="deleteData('supplier','sup_id','<?php echo $value->sup_id; ?>')">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>


                                            <!--                                            <a onclick="return isconfirm()" class="red" href="#">
                                                                                            
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




