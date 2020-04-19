<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Customer Opening Import </div>
                      </div>
            <div class="portlet-body">
                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm" action=""  method="post" class="form-horizontal" enctype="multipart/form-data">
                            <div class="col-md-12">
                            
                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Opening Date</label>

                                    <div class="col-sm-5">

                                        <input autocomplete="off" class="form-control date-picker" readonly name="customer_opening_date"
                                               id="id-date-picker-1" type="text" value="<?php

                                            echo date('d-m-Y')

                                        ?>" data-date-format="dd-mm-yyyy"/>


                                    </div>
                                </div>
                              
                            </div>
                            <div class="col-md-12">

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Opening File</label>

                                    <div class="col-sm-5">

                                        <input type="file" id="form-field-1" name="customerOpening"  value="" class="form-control" />

                                    </div>

                                    <button onclick="return confirmSwat()" id="subBtn" class="btn blue" type="button">

                                        <i class="ace-icon fa fa-check bigger-110"></i>

                                        Save

                                    </button>

                                    <button class="btn default" type="reset">

                                        <i class="ace-icon fa fa-undo bigger-110"></i>

                                        Reset

                                    </button>

                                    <a  class="btn green" href="<?php echo site_url('lpg/FinaneController/getImportCustomerList') ?>"><i class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>

                                </div>

                            </div>
                             <div class="col-md-12">

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer Opening Excel File</label>

                                    <div class="col-sm-5">

                                        <input type="file" id="form-field-1" name="customerOpeningExcel"  value="" class="form-control" />

                                    </div>

                                    <button onclick="return confirmSwat()" id="subBtn" class="btn blue" type="button">

                                        <i class="ace-icon fa fa-check bigger-110"></i>

                                        Save

                                    </button>

                                    <button class="btn default" type="reset">

                                        <i class="ace-icon fa fa-undo bigger-110"></i>

                                        Reset

                                    </button>

                                    <a  class="btn green" href="<?php echo site_url('lpg/FinaneController/getImportCustomerListExcel') ?>"><i class="fa fa-cloud-download"></i> &nbsp; Download Excel Format</a>

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
<script>

    $(document).ready(function () {
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>