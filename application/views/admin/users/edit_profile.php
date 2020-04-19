<!-- /section:basics/sidebar -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Setup </a>
                </li>
                <li class="active">Distributor Add</li>
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

                    <!-- PAGE CONTENT BEGINS -->



                    <h3 class="text-center" style="color:green">
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





                    <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name </label>

                            <div class="col-sm-9">
                                <input type="hidden" id="form-field-1" name="admin_id" value="<?php echo $admin_info->admin_id ?>" class="col-xs-10 col-sm-5"  />
                                <input type="text" id="form-field-1" name="name" value="<?php echo $admin_info->name ?>" class="col-xs-10 col-sm-5"  />
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email</label>

                            <div class="col-sm-9">
                                <input type="email" id="form-field-1" name="email" placeholder="email" class="col-xs-10 col-sm-5" value="<?php echo $admin_info->email ?>" />
                            </div>
                        </div>


<!--                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Password</label>

                            <div class="col-sm-6">
                                <input type="password" id="form-field-1" name="password"  class="col-xs-10 col-sm-5" value="<?php echo $admin_info->password ?>" />
                            </div>
                        </div>-->

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address</label>
                            <div class="col-sm-9">
                                <textarea rows="4" cols="54" placeholder="Address..."  name="address"><?php echo $admin_info->address ?> </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone</label>
                            <div class="col-sm-9">
                                <input type="text" id="form-field-1" name="phone" placeholder="Phone" class="col-xs-10 col-sm-5" value="<?php echo $admin_info->phone ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <script> 
                                var fileindex = 1;
                                var inc = 0;
                                var maxImg = 10;
                            </script>

                            <?php require_once 'application/views/upload-api.php'; ?>
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Images</label>
                            <div class="col-sm-9">
                                <div class="img_uBtn">
                                    <div  id="upload"  class="upload2"><span> <i class='glyphicon glyphicon-file'></i> Browse</span></div><span id="status" style="display:none"> <img src="<?php echo base_url() ?>scripts/upload_js/loading.gif" /> </span> 
                                </div>
                                <div class="" style="clear: both;"></div><br>
                                <!--                                <div class="image_content_area">
                                
                                
                                
                                                                </div>-->

                                <div class="image_content_area">
                                    <span id="delete_avatar_101" style="margin-top:30px; ">
                                        <div class="upload_image1" style="width:100px; margin-right:10px; float:left;">
                                            <?php if (!empty($admin_info->image)): ?>
                                                <img src="<?php echo base_url('uploads/thumb/') . $admin_info->image ?>" class="img-responsive img-rounded"  width="304" height="236">
                                            <?php else: ?>
                                                <img height="50px" width="50px" src="<?php echo base_url('assets/images/default.png') ?>" class="img-responsive img-rounded"  width="304" height="236">
                                            <?php endif; ?>

                                        </div>

                                    </span>
                                </div>


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
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>



