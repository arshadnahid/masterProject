<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Inventory </a>
                </li>
                <li class="active">Import Setup</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('brand'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a>
            </span>
        </div>
        <br>

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier CSV File</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="supplierImport"  value="" class="form-control" />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/supplierFileFormat.csv"><i class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->
            


            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Category CSV File</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="proCatImport"  value="" class="form-control"  />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/productcategory.csv"><i class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->

            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product CSV File</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="proImport"  value="" class="form-control"  />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/product.csv"><i class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->


            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Brand CSV File</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="BrandImport"  value="" class="form-control"  />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/brand.csv"><i class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->
            
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>