
<?php

?>
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">


            <div class="row" id="" >
                <div class="col-md-12">

                    <form action=""  method="post" class="form-horizontal">


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name </label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="name"  value="" class="form-control" placeholder="Name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description  <span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1"  name="description"  value="" class="form-control" placeholder="Description" />

                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Is Active </label>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input name="is_active"  type="checkbox"  value="1" class="ace">
                                        <span class="lbl"> </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">



                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Save</button>






                                &nbsp; &nbsp; &nbsp;
                                <a class="btn" href="" >
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

                                <th>is_active</th>
                                <th>Description</th>


                                <th>Action</th>


                            </tr>
                            </thead>


                            <tbody>
                            <?php foreach ($routes as $key=>$value):?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->route_name; ?></td>

                                    <td><?php echo $value->is_active; ?></td>
                                    <td><?php echo $value->description; ?></td>


                                    <td>
                                        <a class="blue" href="<?php echo site_url($this->project.'/route_info_edit/' . $value->id) ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="red inventoryDeletePermission" href="<?php echo site_url($this->project.'/route_info_edit/' . $value->id) ?>" >
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                        </a>
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

    .chosen-container{
        width: 100% !important;

    }


</style>




