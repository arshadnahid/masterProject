<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Import Setup
                </div>
            </div>
            <div class="portlet-body">
                <!--<div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php /*echo site_url('DistributorDashboard/3'); */ ?>">Inventory</a>
                </li>
                <li class="active">Import Setup</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php /*echo site_url('DistributorDashboard/3'); */ ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>-->

                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier
                                        CSV File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="supplierImport" value=""
                                               class="form-control"/>
                                    </div>
                                    <button onclick=" return isconfirm()" id="subBtn" class="btn btn-xs btn-info"
                                            type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                    <a type="button" class="btn btn-xs btn-success"
                                       href="<?php echo base_url() ?>excelfiles/supplierFileFormat.csv"><i
                                                class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer
                                        CSV File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="customerImport" value=""
                                               class="form-control"/>
                                    </div>
                                    <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info"
                                            type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                    <a type="button" class="btn btn-xs btn-success"
                                       href="<?php echo base_url() ?>excelfiles/customer.csv"> <i
                                                class="fa fa-cloud-download"></i> &nbsp;Download CSV Format</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>

                <div class="col-md-12">
                    <form id="publicForm" action="<?php echo base_url().$this->project?>/setupImportExcelCustomer"  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Xl File</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="customerImport"  value="" class="form-control" />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/customerImportNew.xls"> <i class="fa fa-cloud-download"></i> &nbsp;Download XLS Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div><!-- /.col -->
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product
                                        Category CSV File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="proCatImport" value=""
                                               class="form-control"/>
                                    </div>
                                    <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info"
                                            type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                    <a type="button" class="btn btn-xs btn-success"
                                       href="<?php echo base_url() ?>excelfiles/productcategory.csv"><i
                                                class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->

                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Brand CSV
                                        File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="BrandImport" value=""
                                               class="form-control"/>
                                    </div>
                                    <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info"
                                            type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                    <a type="button" class="btn btn-xs btn-success"
                                       href="<?php echo base_url() ?>excelfiles/brand.csv"><i
                                                class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Unit CSV
                                        File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="UnitImport" value=""
                                               class="form-control"/>
                                    </div>
                                    <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info"
                                            type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                    <a type="button" class="btn btn-xs btn-success"
                                       href="<?php echo base_url() ?>excelfiles/unit.csv"><i
                                                class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Reference
                                        CSV File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="referenceImport" value=""
                                               class="form-control"/>
                                    </div>
                                    <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info"
                                            type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                    <a type="button" class="btn btn-xs btn-success"
                                       href="<?php echo base_url() ?>excelfiles/reference.csv"> <i
                                                class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>