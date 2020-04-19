<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup </a>
                </li>
                <li class="active">Get Distributor Access</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('adminDashboard'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a>
            </span>
        </div>
        <br>

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-md-3"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label no-padding-right" for="form-field-1">Select Distributor</label>
                                <div class="col-sm-8">
                                    <select name="distributor" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Distributor">
                                        <?php foreach ($allDistributor as $key => $eachDis): ?>
                                            <option value="<?php echo $eachDis->dist_id; ?>"><?php echo $eachDis->companyName; //. ' [ ' . $eachDis->dist_name . ' ] ';       ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success">Get Access</button>
                                </div>
                            </div>
                        </div>

<!--                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                               
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>-->
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
