
<?php

?>
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">


            <div class="row" id="" >
                <div class="col-md-12">

                    <form id="" action=""  method="post" class="form-horizontal">

                        <!--id	name	description	is_active	created_by	created_at	updated_by	updated_at-->

                        <input name="route_id" type="hidden" value="<?php echo $route_info->id ;?>">

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name </label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="name"  value="<?php echo $route_info->route_name?>" class="form-control" placeholder="Name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description  <span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1"  name="description"  value="<?php echo $route_info->description?>" class="form-control" placeholder="Description" />

                            </div>
                        </div>







                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">



                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Update</button>






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

        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<style>

    .chosen-container{
        width: 100% !important;

    }


</style>




