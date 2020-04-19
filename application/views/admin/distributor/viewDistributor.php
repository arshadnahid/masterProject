<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('adminDashboard'); ?>">Home</a>
                </li>
                <li class="active">Distributor Profile</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="hr dotted"></div>
                    <div>
                        <div id="user-profile-1" class="user-profile row">
                            <div class="col-xs-12 col-sm-3 center">
                                <div>
                                    <span class="profile-picture">
                                        <?php if (!empty($distributoList->dist_picture)): ?>
                                            <img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="<?php echo base_url('uploads/thumb/'.$distributoList->dist_picture);?>" />
                                        <?php else: ?>
                                            <img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="<?php echo base_url('assets/images/avatars/profile-pic.jpg');?>" />
                                        <?php endif; ?>
                                    </span>
                                    <div class="space-4"></div>
                                    <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                                        <div class="inline position-relative">
                                            <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                                                <i class="ace-icon fa fa-circle light-green"></i>
                                                &nbsp;
                                                <span class="white"><?php echo $distributoList->companyName; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-6"></div>
                                <div class="hr hr16 dotted"></div>
                            </div>
                            <div class="col-xs-12 col-sm-9">
                                <div class="space-12"></div>
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Company Name </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="username"><?php echo $distributoList->companyName; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Distributor Name </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="country"><?php echo $distributoList->companyName; ?></span>
                                            <!--<span class="editable" id="city">Amsterdam</span>-->
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Contact No </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="signup"><?php echo $distributoList->contactNo; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Contact Person </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="age"><?php echo $distributoList->contactPerson; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name">Contact Person Contact </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="login"><?php echo $distributoList->contactPersonCont; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Email </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $distributoList->dist_email; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Phone </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $distributoList->dist_phone; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name">Website </div>
                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $distributoList->dis_website; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Division </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $this->Common_model->tableRow('divisions', 'id', $distributoList->division)->name; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> District </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $this->Common_model->tableRow('districts', 'id', $distributoList->district)->name; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name">Thana  </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $this->Common_model->tableRow('upazilas', 'id', $distributoList->thanna)->name; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Post Office </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $this->Common_model->tableRow('unions', 'id', $distributoList->postOffice)->name; ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Address </div>

                                        <div class="profile-info-value">
                                            <span class="editable" id="about"><?php echo $distributoList->dist_address; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>