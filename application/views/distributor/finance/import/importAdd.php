<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Finance </a>
                </li>
                <li class="active">Finance Import Voucher</li>
            </ul>
            
            
            <ul class="breadcrumb pull-right">
                <li class="active">
                    <i class="ace-icon fa fa-list"></i>
                    <a href="<?php echo site_url('financeImport'); ?>">List</a>
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
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Payment Voucher CSV</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="paymentVoucher"  value="" class="form-control" />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/finance/paymentVoucher.csv"><i class="fa fa-download"></i> &nbsp; Download CSV Format</a>
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
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Receive Voucher CSV</label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="receiveVoucher"  value="" class="form-control" />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/finance/receiveVoucher.csv"> <i class="fa fa-download"></i> &nbsp;Download CSV Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->


            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action="<?php echo site_url('VoucherController/jounalVoucherAdd');?>"  method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Journal Voucher CSV </label>
                                <div class="col-sm-6">
                                    <input type="file" id="form-field-1" name="journalVoucher"  value="" class="form-control"  />
                                </div>
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-xs btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                <button class="btn btn-xs" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                                <a type="button" class="btn btn-xs btn-success" href="<?php echo base_url() ?>excelfiles/finance/journalVoucher.csv"><i class="fa fa-download"></i> &nbsp; Download CSV Format</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>