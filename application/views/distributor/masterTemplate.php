<!DOCTYPE html>

<html lang="en">

    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
        <meta charset="utf-8"/>
        <title><?php echo isset($title) ? $title : 'Distributor Dashboard'; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/font-awesome-master/css/font-awesome.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>"
              rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/layouts/layout/css/themes/Jquery_ui.css'); ?>" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <link href="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/morris/morris.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/global/plugins/fullcalendar/fullcalendar.min.css'); ?>" rel="stylesheet"
              type="text/css"/>
        <link href="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>" rel="stylesheet"
              type="text/css"/>

        <link href="<?php echo base_url('assets/global/css/components.min.css'); ?>" rel="stylesheet" id="style_components"
              type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME GLOBAL STYLES -->
        <!--        scroll start-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/mScrollbar/q_jquery.mCustomScrollbar.min.css">
        <!--scroll end-->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- Data table -->
        <link href="<?php echo base_url(); ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
              rel="stylesheet" type="text/css"/>
        <!-- Data table -->
        <link href="<?php echo base_url('assets/layouts/layout/css/layout.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/layouts/layout/css/themes/darkblue.min.css'); ?>" rel="stylesheet"
              type="text/css" id="style_color"/>
        <!-- smoothness start -->
        <link href="<?php echo base_url('assets/layouts/layout/css/themes/smoothness_jquery-ui.css'); ?>" rel="stylesheet" type="text/css"/>
        <!-- smoothness end -->
        <link href="<?php echo base_url('assets/layouts/layout/css/custom.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <!-- Choose start -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/chosen.min.css"/>
        <!-- Choose end -->
        <script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>

        <link rel="stylesheet" media="all" href="<?php echo base_url() ?>assets/sweet_alert/swal.css"/>
        <script src="<?php echo base_url() ?>assets/sweet_alert/swal.js"></script>
        <!--    jquery_ui start-->
        <script src="<?php echo base_url('assets/layouts/layout/scripts/jquery-1.12.4.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/layouts/layout/scripts/jquery-ui.js'); ?>" type="text/javascript"></script>
        <!--    jquery_ui end-->
        <!--        scroll start-->
        <script src="<?php echo base_url(); ?>assets/mScrollbar/q_jquery.mCustomScrollbar.concat.min.js"></script>
        <!--        scroll end-->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url('assets/global/scripts/app.min.js'); ?>" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?php echo base_url(); ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/datatables/datatables.min.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
        type="text/javascript"></script>
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
        <!--     datepicker start-->
        <script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>" type="text/javascript"></script>
        <!--         datepicker end     -->
        <style type="text/css">

            .toast-top-full-width {
                top: 80px;

            }
            .toast-top-right {
                top: 80px;

            }
            @media all and (max-width: 240px) {
                .toast-top-full-width {

                }
            }
            @media all and (min-width: 241px) and (max-width: 320px) {
                .toast-top-full-width {

                }
            }
            @media all and (min-width: 321px) and (max-width: 480px) {
                .toast-top-full-width {

                }
            }


            @media print {
                @page {
                    margin-top: 0;
                    margin-bottom: 0;
                }

                body {
                    padding-top: 20px;
                    padding-bottom: 20px;
                }

                .noPrint {
                    display: none;
                }


                strong {
                    font-weight: normal !important;
                }
            }

            @media print {
                .noPrint {display:none;}
                strong{
                    font-weight: normal!important;
                }
            }
            @media print {
                a[href]:after {
                    content: none !important;
                }
            }
        </style>
        <script>
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }


            function confirmSwat() {
                swal({
                    title: "Are you sure ?",
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#73AE28',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    type: 'success'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $("#publicForm").submit();
                    } else {
                        return false;
                    }
                });

            }

        </script>
    </head>
    <!-- END HEAD -->
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-full-width">
        <div class="page-wrapper">

            <!-- BEGIN HEADER -->
            <?php


            if ($this->session->userdata('business_type') != "LPG") {
                include 'include/moduleHeaderSmeMobile.php';

                //$this->folderSub = 'distributor/inventory/product_mobile/';
            }else{
                include 'include/moduleHeader.php';
            }


            include 'include/common.php';

            ?>
            <!-- END HEADER -->

            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"></div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <!-- END SIDEBAR MENU -->
                        <div class="page-sidebar-wrapper">
                            <!-- BEGIN RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
                            <?php include 'include/mobileManue.php'; ?>

                            <!-- END RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
                        </div>
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <?php include 'include/message.php';




                        ?>

                        <input type="hidden" value="<?php echo site_url(); ?>" id="baseUrl">
                        <?php include 'topPageBar.php' ?>
                        <?php

                        if (!empty($mainContent)) {
                            echo $mainContent;
                        }

                        ?>


                    </div>
                    <!-- <div id="wait" style="display:none;width:auto;height:auto;position:absolute;top:50%;left:50%;padding:2px;"><img src="<?php /* echo base_url('assets/images/demo_wait.gif'); */ ?>" width="100" height="100" /><br><span style="text-align: center;">Loading..</span></div>

                    --> <!-- END CONTAINER -->
                    <!-- BEGIN FOOTER -->
                    <?php include 'include/footer.php'; ?>
                    <!-- END FOOTER -->
                </div>
            </div>
            <!-- BEGIN QUICK NAV -->

            <div class="quick-nav-overlay"></div>
        </div>
        <!-- BEGIN QUICK SIDEBAR -->
        <a href="javascript:;" class="page-quick-sidebar-toggler">
            <i class="icon-login"></i>
        </a>
        <div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
            <div class="page-quick-sidebar">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> <?php echo get_phrase('Entry') ?>

                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> <?php echo get_phrase('Report') ?>

                        </a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                        <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                            <ul class="media-list list-items">

                                <li>
                                    <a href="<?php echo site_url('salesInvoice_add'); ?>"  class="active">
                                        <span><?php echo get_phrase('Sales_Invoice') ?></span>

                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('purchases_add'); ?>"  class="active">
                                        <span><?php echo get_phrase('Purchases_Invoice') ?></span>

                                    </a>
                                </li>


                            </ul>

                        </div>

                    </div>
                    <div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
                        <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                            <ul class="media-list list-items">

                                <li>
                                    <a href="<?php echo site_url('purchasesReport'); ?>" target="_blank" class="active">
                                        <span> <?php echo get_phrase('Purchases_Report') ?></span>

                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('salesReport'); ?>" target="_blank" class="active">
                                        <span> <?php echo get_phrase('Sales_Report') ?></span>

                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('current_stock'); ?>" target="_blank" class="active">
                                        <span> <?php echo get_phrase('Current_Stock') ?></span>

                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END QUICK SIDEBAR -->
    </div>
    <!-- BEGIN CORE PLUGINS -->

    <script src="<?php echo base_url('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/js.cookie.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>"
    type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <script src="<?php echo base_url('assets/global/plugins/moment.min.js'); ?>" type="text/javascript"></script>

    <script src="<?php echo base_url('assets/global/plugins/morris/morris.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/morris/raphael-min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/counterup/jquery.waypoints.min.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/counterup/jquery.counterup.min.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/amcharts.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/serial.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/pie.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/radar.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/themes/light.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/themes/patterns.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/themes/chalk.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/ammap/ammap.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/ammap/maps/js/worldLow.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/amcharts/amstockcharts/amstock.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/fullcalendar/fullcalendar.min.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/horizontal-timeline/horizontal-timeline.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/flot/jquery.flot.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/flot/jquery.flot.resize.min.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/flot/jquery.flot.categories.min.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jquery.sparkline.min.js" type="text/javascript'); ?>"></script>
    <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js'); ?>"
    type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js'); ?>"  type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="<?php echo base_url('assets/layouts/layout/scripts/layout.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/layouts/layout/scripts/demo.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/layouts/global/scripts/quick-sidebar.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/layouts/global/scripts/quick-nav.min.js'); ?>" type="text/javascript"></script>
    <!--        select2-->
    <!-- chosen start -->
        <script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>
    <?php if ($this->session->flashdata('message') != "") { ?>
        <script>
                var msg = '<?php echo $this->session->flashdata('message'); ?>';
                toastr.info(msg);
        </script>>
    <?php } ?>

    <!--    Masud -->
    <?php if ($this->session->flashdata('error') != "") { ?>
        <script>
            var msg = '<?php echo $this->session->flashdata('error'); ?>';

            toastr.error(msg);
        </script>
    <?php }if ($this->session->flashdata('success') != "") { ?>
        <script>
            var msg = '<?php echo $this->session->flashdata('success'); ?>';
            toastr.success(msg);
        </script>
    <?php } ?>
    <script src="<?php echo base_url(); ?>assets/js/chosen.jquery.min.js"></script>

    <script>
        var baseUrl = '<?php echo base_url(); ?>';

    </script>

    <script>

        $(document).ready(function () {
            $(".chosen-select").chosen({allow_single_deselect: true});

            $('#clickmewow').click(function () {
                $('#radio1003').attr('checked', 'checked');
            });







        });
        $(document).on("keypress", ".decimal", function () {
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    </script>


    <!-- End -->
</body>

</html>