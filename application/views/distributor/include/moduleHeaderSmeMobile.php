<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 3/28/2020
 * Time: 12:33 PM
 */
?>
<?php
$admin_status = strtolower($this->session->userdata('status'));
$admin_id = strtolower($this->session->userdata('admin_id'));
$admin_id = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $admin_id)->user_role;
$distributor_id = strtolower($this->session->userdata('dis_id'));


?>


<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo site_url($this->project . '/moduleDashboard'); ?>">
                <img src="<?php echo base_url('assets/layouts/layout/img/logo.png'); ?>" alt="logo"
                     class="logo-default"/> </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN MEGA MENU dashboard -->
        <!-- DOC: Remove "hor-menu-light" class to have a horizontal menu with theme background instead of white background -->
        <!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) in the responsive menu below along with sidebar menu. So the horizontal menu has 2 seperate versions -->
        <div class="hor-menu   hidden-sm hidden-xs">
            <ul class="nav navbar-nav">
                <!-- DOC: Remove data-hover="megamenu-dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
                <li class="classic-menu-dropdown <?php if (!empty($page_type) && $page_type == 'dashboard') echo 'active'; ?>"
                    aria-haspopup="true">
                    <a href="<?php echo site_url($this->project . '/moduleDashboard'); ?>"><?php echo get_phrase('Dashboard') ?>
                        <?php if (!empty($page_type) && $page_type == 'dashboard') echo "<span class='selected'> </span>"; ?>
                    </a>
                </li>

                <li class="mega-menu-dropdown mega-menu-full <?php if (!empty($page_type) && $page_type == 'inventory') echo 'active'; ?>"
                    aria-haspopup="true" data-hover="megamenu-dropdown"
                    data-close-others="true">
                    <a href="javascript:;" class="dropdown-toggle" data-hover="megamenu-dropdown"
                       data-close-others="true">  <?php echo get_phrase('Inventory'); ?>
                        <?php if (!empty($page_type) && $page_type == 'inventory') echo "<span class='selected'> </span>"; ?>
                        <i class="fa fa-angle-down" style="color:#fff;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- Content container to add padding -->
                            <div class="mega-menu-content ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"> <?php echo get_phrase('Operation'); ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-12">
                                                        <ul class="mega-menu-submenu">
                                                            <?php
                                                            $limit = 6;

                                                            //sales report
                                                            $sub_menu = get_menu_list_by_user_role(8, $admin_id, $limit, $statr = 0);


                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>

                                                        </ul>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Setup') ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 8;

                                                            //sales report
                                                            $sub_menu = get_menu_list_by_user_role(7, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>

                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //inventory
                                                            $sub_menu = get_menu_list_by_user_role(7, $admin_id, $limit, $statr = 6);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>

                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Inventory Report'); ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">
                                                            <?php
                                                            $limit = 6;

                                                            //inventory
                                                            $sub_menu = get_menu_list_by_user_role(11, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>

                                                        </ul>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //inventory
                                                            $sub_menu = get_menu_list_by_user_role(11, $admin_id, $limit, $statr = 6);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                            <li>
                                                                <a href="<?php echo site_url($this->project . '/' . 'stock_group_report'); ?>">   <?php echo get_phrase('Cylinder Group Report'); ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo site_url($this->project . '/' . 'cylinderInOutJournal'); ?>">   <?php echo get_phrase('Cylinder Exchange'); ?></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--<div class="col-md-4">
                                                <div class="col-md-12">
                                                    <h5 class="text-center" style="color:#fff"> <?php /*echo get_phrase('Inventory Report'); */ ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">
                                                            <?php
                                            /*                                                            $limit = 6;

                                                                                                        //inventory
                                                                                                        $sub_menu = get_menu_list_by_user_role(72, $admin_id, $limit, $statr = 0);
                                                                                                        foreach ($sub_menu as $each_menu):
                                                                                                            $url = $each_menu->url;

                                                                                                            $link = $url;

                                                                                                            $label = $each_menu->label;
                                                                                                            */ ?>

                                                                <li>
                                                                    <a href="<?php /*echo $link; */ ?>">   <?php /*echo get_phrase($label); */ ?></a>
                                                                </li>
                                                            <?php /*endforeach; */ ?>

                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                            /*                                                            $limit = 6;

                                                                                                        //inventory
                                                                                                        $sub_menu = get_menu_list_by_user_role(72, $admin_id, $limit, $statr = 6);
                                                                                                        foreach ($sub_menu as $each_menu):
                                                                                                            $url = $each_menu->url;

                                                                                                            $link = $url;

                                                                                                            $label = $each_menu->label;
                                                                                                            */ ?>

                                                                <li>
                                                                    <a href="<?php /*echo $link; */ ?>">   <?php /*echo get_phrase($label); */ ?></a>
                                                                </li>
                                                            <?php /*endforeach; */ ?>

                                                        </ul>
                                                    </div>


                                                </div>

                                            </div>-->

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="mega-menu-dropdown mega-menu-full <?php if (!empty($page_type) && $page_type == 'sales') echo 'active'; ?>"
                    aria-haspopup="true" data-hover="megamenu-dropdown"
                    data-close-others="true">
                    <a href="javascript:void(0)" class="dropdown-toggle " data-hover="megamenu-dropdown"
                       data-close-others="true"> <?php echo get_phrase('sales') ?>
                        <?php if (!empty($page_type) && $page_type == 'Sales') echo "<span class='selected'> </span>"; ?>
                        <i class="fa fa-angle-down" style="color:#fff;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- Content container to add padding -->
                            <div class="mega-menu-content ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">

                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('operation'); ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-12">
                                                        <ul class="mega-menu-submenu">
                                                            <?php
                                                            $limit = 5;

                                                            //sales operation
                                                            $sub_menu = get_menu_list_by_user_role(10, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>

                                                        </ul>
                                                    </div>
                                                    <!--<div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                    /*                                                            $limit = 5;
                                                                                                                //sales operation
                                                                                                                $sub_menu = get_menu_list_by_user_role(10, $admin_id, $limit, $statr = 5);
                                                                                                                foreach ($sub_menu as $each_menu):
                                                                                                                    $url = $each_menu->url;

                                                                                                                    $link = $url;

                                                                                                                    $label = $each_menu->label;
                                                                                                                    */ ?>

                                                                <li>
                                                                    <a href="<?php /*echo $link; */ ?>">   <?php /*echo $label; */ ?></a>
                                                                </li>
                                                            <?php /*endforeach; */ ?>
                                                        </ul>
                                                    </div>-->
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('setup'); ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">


                                                            <?php
                                                            $limit = 5;

                                                            //sales setup
                                                            $sub_menu = get_menu_list_by_user_role(9, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">
                                                            <?php
                                                            $limit = 5;

                                                            //sales setup
                                                            $sub_menu = get_menu_list_by_user_role(9, $admin_id, $limit, $statr = 5);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Report'); ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //sales report
                                                            $sub_menu = get_menu_list_by_user_role(12, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //sales report
                                                            $sub_menu = get_menu_list_by_user_role(12, $admin_id, $limit, $statr = 6);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="mega-menu-dropdown mega-menu-full" aria-haspopup="true" data-hover="megamenu-dropdown"
                    data-close-others="true">
                    <a href="javascript:;" class="dropdown-toggle" data-hover="megamenu-dropdown"
                       data-close-others="true"> <?php echo get_phrase('Accounts'); ?>
                        <i class="fa fa-angle-down" style="color:#fff;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- Content container to add padding -->
                            <div class="mega-menu-content ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <ul class="mega-menu-submenu">
                                                    <li>
                                                        <h3><?php echo get_phrase('Operation') ?></h3>
                                                        <hr style="color:#fff;margin: 8px 0;">
                                                    </li>
                                                    <?php
                                                    $limit = 6;

                                                    //account transaction
                                                    $sub_menu = get_menu_list_by_user_role(14, $admin_id, $limit, $statr = 0);
                                                    foreach ($sub_menu as $each_menu):
                                                        $url = $each_menu->url;

                                                        $link = $url;

                                                        $label = $each_menu->label;
                                                        ?>

                                                        <li>
                                                            <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Setup') ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-12">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //sales report
                                                            $sub_menu = get_menu_list_by_user_role(13, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <!--                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                    $limit = 6;

                                                    //sales report
                                                    $sub_menu = get_menu_list_by_user_role(13, $admin_id, $limit, $statr = 6);
                                                    foreach ($sub_menu as $each_menu):
                                                        $url = $each_menu->url;

                                                        $link = $url;

                                                        $label = $each_menu->label;
                                                        ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>-->
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Report') ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //account report
                                                            $sub_menu = get_menu_list_by_user_role(15, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //sales report
                                                            $sub_menu = get_menu_list_by_user_role(15, $admin_id, $limit, $statr = 6);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="mega-menu-dropdown mega-menu-full" aria-haspopup="true" data-hover="megamenu-dropdown"
                    data-close-others="true">
                    <a href="javascript:;" class="dropdown-toggle" data-hover="megamenu-dropdown"
                       data-close-others="true"> <?php echo get_phrase('Configuration') ?>
                        <i class="fa fa-angle-down" style="color:#fff;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- Content container to add padding -->
                            <div class="mega-menu-content ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Opening') ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-12">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //opening
                                                            $sub_menu = get_menu_list_by_user_role(81, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <!--                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                    $limit = 6;

                                                    //sales report
                                                    $sub_menu = get_menu_list_by_user_role(81, $admin_id, $limit, $statr = 6);
                                                    foreach ($sub_menu as $each_menu):
                                                        $url = $each_menu->url;

                                                        $link = $url;

                                                        $label = $each_menu->label;
                                                        ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo $label; ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>-->
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Setup') ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 5;

                                                            //setup
                                                            $sub_menu = get_menu_list_by_user_role(2, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 5;

                                                            //setup
                                                            $sub_menu = get_menu_list_by_user_role(2, $admin_id, $limit, $statr = 5);
                                                            //echo  $this->db->last_query();exit;
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <h5 class="text-center"
                                                        style="color:#fff"><?php echo get_phrase('Import') ?></h5>
                                                    <hr style="color:#fff;margin: 8px 0;">
                                                    <div class="col-md-12">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                            $limit = 6;

                                                            //setup
                                                            $sub_menu = get_menu_list_by_user_role(80, $admin_id, $limit, $statr = 0);
                                                            foreach ($sub_menu as $each_menu):
                                                                $url = $each_menu->url;

                                                                $link = $url;

                                                                $label = $each_menu->label;
                                                                ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <!--                                                    <div class="col-md-6">
                                                        <ul class="mega-menu-submenu">

                                                            <?php
                                                    $limit = 6;

                                                    //setup
                                                    $sub_menu = get_menu_list_by_user_role(81, $admin_id, $limit, $statr = 6);
                                                    foreach ($sub_menu as $each_menu):
                                                        $url = $each_menu->url;

                                                        $link = $url;

                                                        $label = $each_menu->label;
                                                        ?>

                                                                <li>
                                                                    <a href="<?php echo site_url($this->project . '/' . $link); ?>">   <?php echo get_phrase($label); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>-->
                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- END MEGA MENU -->

        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <?php
        $dist_id = $this->session->userdata('dis_id');
        $admin_id = $this->session->userdata('admin_id');
        $headOffice = strtolower($this->session->userdata('headOffice'));
        $adminInfo = $this->db->get_where('admin', array('1' => 1, 'admin_id' => $admin_id))->row();
        ?>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                        <img alt="" class="img-circle"
                             src="<?php echo base_url('assets/layouts/layout/img/avatar3_small.jpg'); ?>"/>
                        <span class="username username-hide-on-mobile"> <?php echo $adminInfo->name; ?> </span>
                        <i class="fa fa-angle-down" style="color:#fff;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?php echo site_url('distributor_profile'); ?>">
                                <i class="icon-user"></i> My Profile </a>
                        </li>


                        <li>
                            <?php if (!empty($headOffice)): ?>
                            <a href="<?php echo site_url('AuthController/signoutHeadoffice'); ?>">
                                <?php else: ?>
                                <a href="<?php echo site_url($this->project . '/' . 'signout'); ?>">
                                    <?php endif; ?>
                                    <i class="icon-key"></i> Log Out </a>

                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown dropdown-quick-sidebar-toggler">
                    <a href="javascript:;" class="dropdown-toggle">
                        <i class="icon-logout"></i>
                    </a>
                </li>
                <!-- END USER LOGIN DROPDOWN -->

            </ul>
        </div>
        <div class="top-menu">
            <div class="nav navbar-nav pull-right" style="margin-top: 12px;">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <form>
                    <select class="form-control" style="height: 29px;padding:2px 6px;"
                            onchange="javascript:window.location.href = '<?php echo base_url(); ?>lpg/LanguageSwitcher/switchLang/' + this.value;"
                            class="sobujformcontrol">
                        <option value="english" <?php if ($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>
                            English
                        </option>
                        <option value="bangla" <?php if ($this->session->userdata('site_lang') == 'bangla') echo 'selected="selected"'; ?>>
                            বাংলা
                        </option>

                    </select>
                </form>


                <!-- END USER LOGIN DROPDOWN -->

            </div>
        </div>
        <!-- END TOP NAVIGATION MENU -->

    </div>
    <!-- END HEADER INNER -->
</div>

