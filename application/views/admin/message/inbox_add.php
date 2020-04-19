<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup </a>
                </li>
                <li class="active">Offer Add</li>
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
                            <div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-recipient">Recipient:</label>

                                    <div class="col-sm-6">


                                        <select multiple="" class="chosen-select form-control" id="form-field-select-4" name="recipient[]" data-placeholder="Search Distributor">
                                            <?php foreach ($distributor as $eachInfo): ?>
                                                <option value="<?php echo $eachInfo->dist_id; ?>"><?php echo $eachInfo->companyName; ?></option>
                                            <?php endforeach; ?>
                                        </select>



                                    </div>
                                </div>

                                <div class="hr hr-18 dotted"></div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-subject">Subject:</label>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="input-icon block col-xs-12 no-padding">
                                            <input maxlength="100" type="text" class="col-xs-12" name="subject" id="form-field-subject" placeholder="Subject" />
                                            <i class="ace-icon fa fa-comment-o"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="hr hr-18 dotted"></div>

                                <div class="form-group">

                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-subject">Message:</label>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="input-icon block col-xs-12 no-padding">
                                            <textarea name="message" placeholder="Message....." cols="86" rows="6"></textarea>
                                        </div>
                                    </div>

                                </div>

<!--                                <div class="hr hr-18 dotted"></div>

                                <div class="form-group no-margin-bottom">
                                    <label class="col-sm-3 control-label no-padding-right">Attachments:</label>

                                    <div class="col-sm-9">
                                        <div id="form-attachments">
                                            <input type="file" name="attachment[]" />
                                        </div>
                                    </div>
                                </div>-->
<!--
                                <div class="align-right">
                                    <button id="id-add-attachment" type="button" class="btn btn-sm btn-danger">
                                        <i class="ace-icon fa fa-paperclip bigger-140"></i>
                                        Add Attachment
                                    </button>
                                </div>-->

                                <div class="space"></div>
                            </div>


                        </div>

                        <div class="clearfix"></div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return isconfirm()" id="subBtn" class="btn btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
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






