<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup</a>
                </li>
                <li class="active">District List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('district_add'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a>
            </span>
        </div>


        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    District List
                </div>
                <div>

                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>

                                <th>District Name</th>

                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($districtList as $key => $value):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->district_title; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <!--                                                <a class="blue" href="#">
                                                                                                <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                                                            </a>-->

                                            <a class="green" href="<?php echo site_url('district_edit/' . $value->district_id); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>

                                            <a class="red" href="javascript:void(0)" onclick="deleteData('district','district_id','district_list','<?php echo $value->district_id; ?>')">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>


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

