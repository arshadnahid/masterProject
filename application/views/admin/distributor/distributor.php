<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">Distributor List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('distributor_add'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a></span>
        </div>


        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Distributor List
                </div>
                <div>

                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Company Name</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($distributorList as $key => $value):
                                ?>
                            <tr <?php if($value->status == '2'):?>style="background-color: #ececec;color: #b9b9b9;"<?php endif;?>>
                                    <td><?php echo $key + 1; ?></td>
                                    
                                     <td>
                                        <?php if (!empty($value->supLogo)): ?>
                                            <img height="50px" width="50px" src="<?php echo base_url('uploads/thumb/' . $value->supLogo); ?>" class="img-thumbnail"/>
                                        <?php else: ?>
                                            <img height="50px" width="50px" src="<?php echo base_url('assets/images/default.png'); ?>" class="img-thumbnail"/>
                                        <?php endif; ?>

                                    </td>
                                    
                                    
                                    <td><?php echo $value->companyName; ?></td>
                                    <td><?php echo $value->dist_name; ?></td>
                                    <td><?php echo $value->dist_email; ?></td>
                                    <td><?php echo $value->dist_phone; ?></td>
                                    
                                    <td><?php echo $value->dist_address; ?></td>
                                   
                                    
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <!--                                                <a class="blue" href="#">
                                                                                                <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                                                            </a>-->

                                            <a class="green" href="<?php echo site_url('distributor_edit/' . $value->dist_id); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="blue" href="<?php echo site_url('viewDistributor/' . $value->dist_id); ?>">
                                                <i class="ace-icon fa fa-desktop bigger-130"></i>
                                            </a>

<!--                                            <a class="red" href="javascript:void(0)" onclick="deleteData('supplier','sup_id','supplierList','<?php echo $value->sup_id; ?>')">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>-->


                                            <a onclick="return isconfirm()" class="red" href="#">

                                            </a>
                                        </div>


                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>







                    <!--                    -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>
