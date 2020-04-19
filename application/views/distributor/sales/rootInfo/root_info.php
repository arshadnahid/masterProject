<?php

?>
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">


            <div class="row" id="">
                <div class="col-md-12">

                    <form id="" action="<?php echo base_url('lpg/RootController/root_info_insert'); ?>" method="post"
                          class="form-horizontal">

                        <!--id	name	description	is_active	created_by	created_at	updated_by	updated_at-->


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name <span
                                        style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="name" value="" class="form-control"
                                       placeholder="Name"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                Description </label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="description" value="" class="form-control"
                                       placeholder="Description"/>

                            </div>
                        </div>


                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">


                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Save</button>


                                &nbsp; &nbsp; &nbsp;
                                <a class="btn" href="">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Refresh
                                </a>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="table-header">
                        Branch List
                    </div>
                    <div>
                        <table id="example" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>


                                <th>Sl</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Is_active</th>


                                <th>Action</th>


                            </tr>
                            </thead>


                            <tbody>
                            <?php foreach ($root_info as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->name; ?></td>
                                    <td><?php echo $value->description; ?></td>
                                    <td><?php
                                        if ($value->root_id != 1) {

                                            if ($value->is_active == 1) { ?>
                                                <a href="<?php echo site_url($this->project . '/status/' . $value->root_id) ?>">
                                                    <span class="label label-success">Inactive</span></a>
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo site_url($this->project . '/status2/' . $value->root_id) ?>"><span
                                                            class="label label-danger"
                                                            style="background:red">Active</span></a>
                                            <?php }
                                        }
                                        ?></td>


                                    <td>
                                        <a class="blue"
                                           href="<?php echo site_url($this->project . '/root_info_edit/' . $value->root_id) ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <?php

                                        if ($value->root_id != 1) {
                                            ?>
                                            <a class="red inventoryDeletePermission"
                                               href="<?php echo site_url($this->project . '/root_info_delete/' . $value->root_id) ?>">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<style>

    .chosen-container {
        width: 100% !important;

    }


</style>




