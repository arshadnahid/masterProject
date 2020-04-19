<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/1'); ?>">Finance</a>
                </li>
                <li class="active">Customer Opening Import</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo site_url('DistributorDashboard/1'); ?>">
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
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Opening File</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="customerOpening"  value="" class="form-control" />
                                </div>
                                <button onclick="return confirmSwat()" id="subBtn" class="btn btn-xs btn-info" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a  class="btn btn-xs btn-success" href="<?php echo site_url('FinaneController/getImportCustomerList') ?>"><i class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>