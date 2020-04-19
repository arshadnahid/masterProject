<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar ace-save-state">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="<?php echo site_url('adminDashboard') ?>" class="navbar-brand">
                <small>
                    <img class="nav-user-photo" src="<?php echo base_url() ?>assets/inc/ael.png"  style="
                         height: 50px;
                         width: 120px;
                         "/>
                    ADMIN PANEL
                </small>
            </a>

            <button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
                <span class="sr-only">Toggle user menu</span>

                <img src="<?php echo base_url() ?>assets/images/avatars/user.jpg"  />
            </button>

            <button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
                 <span class="sr-only">Toggle sidebar</span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>
            </button>


        </div>

        <div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
            <ul class="nav ace-nav">


                <li class="light-blue dropdown-modal user-min">
                    <a data-toggle="dropdown" href="" class="dropdown-toggle">
                        <img class="nav-user-photo" src="<?php echo base_url() ?>assets/inc/ael.png" />
                        <span class="user-info">
                            <small>AEL</small>
                            Admin
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                                    <a href="<?php echo base_url() ?>assets/userMenual/headOfficeUserMenual.pdf" class="clearfix" download>
                                       
                                       <i class="fa fa-cloud-download"></i> &nbsp; Download  User Manual
                                    </a>
                                </li>
                        <li>
                            <a href="<?php echo base_url() ?>change_Admin_password/<?php echo $admin_id ?>">
                                <i class="ace-icon fa fa-cog"></i>
                              Change Password 
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo base_url() ?>profile">
                                <i class="ace-icon fa fa-user"></i>
                                Profile
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="<?php echo base_url() ?>admin/AdminAuthController/adminSignout">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>


    </div><!-- /.navbar-container -->
</div>

<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>

    <div id="sidebar" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
        <script type="text/javascript">
            try{ace.settings.loadState('sidebar')}catch(e){}
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
<a href="<?php echo site_url('adminDashboard');?>">

            <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
             
                 <span class="btn btn-success"></span>

                <span class="btn btn-info"></span>

                <span class="btn btn-warning"></span>

                <span class="btn btn-danger"></span>
            </div></a>
        </div><!-- /.sidebar-shortcuts -->