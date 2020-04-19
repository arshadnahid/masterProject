
<?php
$dateInfo = explode("=", $offerList->dateRange);
?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup </a>
                </li>
                <li class="active">Offer Edit</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('offer'); ?>" class="btn btn-danger pull-right">
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

                        <div class="col-md-12">



                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">From Date</label>
                                <div class="col-sm-3">
                                    <input type="text" class="date-picker" id="start_date" name="fromDate" value="<?php
if (!empty($dateInfo[0])) {
    echo $dateInfo[0];
} else {


    echo date('Y-m-d');
}
?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd"/>

                                </div>
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1">To Date</label>
                                <div class="col-sm-3">
                                    <input type="text" class="date-picker" id="start_date" name="toDate" value="<?php
                                           if (!empty($dateInfo[1])) {
                                               echo $dateInfo[1];
                                           } else {


                                               echo date('Y-m-d');
                                           }
?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd"/>

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Offer Title</label>
                                <div class="col-sm-6">
                                    <input type="text" id="form-field-1" name="offerTitle"  value="<?php echo $offerList->offerTitle; ?>" class="form-control" placeholder="Offer Title" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Offer Description</label>
                                <div class="col-sm-6">
                                    <textarea  id="form-field-1" name="offerDescription"  value="" class="form-control" placeholder="Offer Description" ><?php echo $offerList->offerDescription; ?></textarea>
                                </div>
                            </div>


                        </div>

                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
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
