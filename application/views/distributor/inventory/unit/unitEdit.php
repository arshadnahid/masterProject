<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Inventory </a>
                </li>
                <li class="active">Unit Edit</li>
            </ul>
            
            <ul class="breadcrumb pull-right">
                
                <li>
                    <a href="<?php echo site_url('unit'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
                

            </ul>
            
           
        </div>
        <br>

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit Name<span style="color:red"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="unitTtile"  value="<?php echo $unitList->unitTtile; ?>" class="form-control" placeholder="Unit Name" required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit Code</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="code"  value="<?php echo $unitList->code; ?>" class="form-control" placeholder="Unit code" />
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return confirmSwat()"   id="subBtn" class="btn btn-info" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Update
                                </button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>








