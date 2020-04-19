<!-- /section:basics/sidebar -->
<div class="main-content">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->


        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->



                <h3 class="text-center" style="color:Red">
                    <?php
                    $message = $this->session->userdata('message');
                    if ($message) {
                        echo $message;
                        $this->session->unset_userdata('message');
                    }
                    $exception = $this->session->userdata('exception');
                    if ($exception) {
                        echo $exception;
                        $this->session->unset_userdata('exception');
                    }
                    ?>
                </h3>





                <form class="form-horizontal" role="form" action="<?php echo base_url() ?>update_password" method="post" enctype="multipart/form-data">


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">New Password </label>

                        <div class="col-sm-9">
                            <input type="hidden" id="form-field-1" name="admin_id" value="<?php echo $admin_info->admin_id ?>" class="col-xs-10 col-sm-5"  />
                            <input type="password" id="form-field-1" name="admin_password1" value="<?php echo $admin_info->admin_password ?>" class="col-xs-10 col-sm-5"  />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Confirm Password </label>

                        <div class="col-sm-9">

                            <input type="password" id="form-field-1" name="admin_password2" value="<?php echo $admin_info->admin_password ?>" class="col-xs-10 col-sm-5"  />
                        </div>
                    </div>







                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" >
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Update
                            </button>

                            &nbsp; &nbsp; &nbsp;

                        </div>
                    </div>

                </form>
                <div class="hr hr-24"></div>


            </div><!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->

    </div>
</div>


