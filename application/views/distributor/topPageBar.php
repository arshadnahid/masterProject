<?php /**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/10/2019
 * Time: 1:28 PM
 */ ?>



<div class="breadcrumbs noPrint" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-truck"></i>
            <a href="javascript:void(0)"><?php echo isset($page_type)?$page_type :''?>

        <li class="fa fa-angle-right"></li>
            </a>
        </li>
        <li class="active"><?php echo $title ?></li>
        <li class="pull-right">
            <?php
            if (isset($link_page_name) && $link_page_name !='') {
                ?>
                <a class="saleAddPermission btn btn-xs red" href="<?php echo site_url($link_page_url); ?>">
                    <?php echo isset($link_icon) ? $link_icon : '' ?>
                  <!--  <i class="ace-icon fa fa-plus"></i>-->
                    <?php echo isset($link_page_name) ? $link_page_name : '' ?>
                </a>
                <?php
            }
            ?>
        </li>
        <li class="pull-right" style="margin-right: 5px;">
            <?php
            if (isset($second_link_page_url)) {
                ?>
                <a class="saleAddPermission btn btn-xs green" href="<?php echo site_url($second_link_page_url); ?>">
                    <?php echo isset($second_link_icon) ? $second_link_icon : '' ?>
                    <!--  <i class="ace-icon fa fa-plus"></i>-->
                    <?php echo isset($second_link_page_name) ? $second_link_page_name.'&nbsp&nbsp&nbsp' : '' ?>
                </a>
                <?php
            }
            ?>
        </li>

        <li class="pull-right" style="margin-right: 5px;">
            <?php
            if (isset($third_link_page_url)) {
                ?>
                <a class="saleAddPermission btn btn-xs blue" href="<?php echo site_url($third_link_page_url); ?>">
                    <?php echo isset($third_link_icon) ? $third_link_icon : '' ?>
                    <!--  <i class="ace-icon fa fa-plus"></i>-->
                    <?php echo isset($third_link_page_name) ? $third_link_page_name.'&nbsp&nbsp&nbsp' : '' ?>
                </a>
                <?php
            }
            ?>
        </li>
    </ul>

</div>

<?php

$this_is_test_data_for_nahid='NAHID';

?>
