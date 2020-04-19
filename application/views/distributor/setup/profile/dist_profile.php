<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Profile</a>
                </li>
                <li class="active">Distributor Profile</li>
            </ul>
<!--            <span style="padding-top: 5px!important;">
                <a  style="border-radius:100px 0 100px 0;" href="<?php echo site_url('addProduct'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a>
            </span>-->
        </div>


        <div class="page-content">

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="hr dotted"></div>

                    <div>
                        <div id="user-profile-1" class="user-profile row">
                            <div class="col-xs-12 col-sm-3 center">
                                <div>
                                    <span class="profile-picture">
                                        <img id="avatar" class="editable img-responsive" alt="<?php echo $dist_info->dist_name ?>" src="<?php echo base_url('assets/images/distributor.png'); ?>" />
                                    </span>

                                    <div class="space-4"></div>

                                    <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                                        <div class="inline position-relative">
                                            <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                                                <i class="ace-icon fa fa-circle light-green"></i>
                                                &nbsp;
                                                <span class="white"><?php echo $dist_info->dist_name ?></span>
                                            </a>

                                            <ul class="align-left dropdown-menu dropdown-caret dropdown-lighter">
                                                <li class="dropdown-header"> Change Status </li>

                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-circle green"></i>
                                                        &nbsp;
                                                        <span class="green">Available</span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-circle red"></i>
                                                        &nbsp;
                                                        <span class="red">Busy</span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-circle grey"></i>
                                                        &nbsp;
                                                        <span class="grey">Invisible</span>
                                                    </a>
                                                </li>
                                            </ul>


                                        </div>
                                    </div>
                                </div>

                                <div class="space-6"></div>

                                <div class="profile-contact-info">
                                    <div class="profile-contact-links align-left">
                                        <!-- <a href="#" class="btn btn-link">
                                        <i class="ace-icon fa fa-plus-circle bigger-120 green"></i>
                                        Add as a friend
                                        </a> -->

                                        <a href="#" class="btn btn-link">
                                            <i class="ace-icon fa fa-envelope bigger-120 pink"></i>
                                            Send a message
                                        </a>

                                        <a href="#" class="btn btn-link">
                                            <i class="ace-icon fa fa-globe bigger-125 blue"></i>
                                            www.distributor.com
                                        </a>
                                    </div>

                                    <div class="space-6"></div>

                                    <div class="profile-social-links align-center">
                                        <a href="<?php echo $dist_info->dist_id ?>" class="tooltip-info" title="" data-original-title="Visit my Facebook">
                                            <i class="middle ace-icon fa fa-facebook-square fa-2x blue"></i>
                                        </a>


                                        <a href="mailto:<?php echo $dist_info->dist_email ?>" class="tooltip-error" title="" data-original-title="Visit my Pinterest">
                                            <i class="middle ace-icon fa  fa-envelope-square fa-2x red"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="hr hr12 dotted"></div>



                                <div class="hr hr16 dotted"></div>
                            </div>

                            <div class="col-xs-12 col-sm-9">






                                <div class="space-12"></div>

                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Name </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="username"><?php echo $adminInfo->name ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Address </div>

                                        <div class="profile-info-value">
                                            <i class="fa fa-map-marker light-orange bigger-110"></i>
                                            <span class="editable" id="country"><?php echo $adminInfo->address ?></span>
                                            <span class="editable" id="city">Bangladesh</span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Email </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="age"><?php echo $adminInfo->email ?></span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Phone </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="signup"><?php echo $adminInfo->phone ?></span>
                                        </div>
                                    </div>
                                    <!-- 
                                    <div class="profile-info-row">
                                    <div class="profile-info-name"> Last Online </div>
                                    
                                    <div class="profile-info-value">
                                    <span class="editable" id="login">3 hours ago</span>
                                    </div>
                                    </div>
                                    
                                    <div class="profile-info-row">
                                    <div class="profile-info-name"> About Me </div>
                                    
                                    <div class="profile-info-value">
                                    <span class="editable" id="about">Editable as WYSIWYG</span>
                                    </div>
                                    </div> -->



                                </div>
                                <div class="space-6"></div>
                                <div class="center">
                                    <a href="<?php echo base_url() ?>updatePassword/<?php echo $this->admin_id ?>">
                                        <button class="btn btn-danger">Change Password</button>
                                    </a>
                                </div>


                            </div>
                        </div>
                    </div>



                </div>


            </div>
        </div>
    </div>
</div>

