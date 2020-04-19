<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title><?php echo isset($title) ? $title : 'Distributor Dashboard'; ?></title>
        <meta name="description" content="top menu &amp; navigation" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <?php include 'include/cssFile.php';?>
    </head>
    <body class="no-skin">
        <style type="text/css" media="print">
            @page {
                size: auto;   /* auto is the initial value */
                margin: 0;  /* this affects the margin in the printer settings */
            }
            a[href^="http"]::after {
                content: " (" attr(href) ")";
            }
            @media print {
                .header, .hide,title { visibility: hidden }
            }
        </style>
        <style type="text/css">
            .nonValid{
                border: 2px solid red;
            }
            .valid{
                border: 2px solid green;
            }
            @media screen {
                div.divFooter {
                    display: none;
                }
            }
            @media print {
                div.divFooter {
                    position: fixed;
                    bottom: 0;
                }
            }
            .goog-te-banner-frame.skiptranslate {
                display: none !important;
            }
            body {
                top: 0px !important;
            }
            <?php if (empty($designCssHide)): ?>
                table tr td{
                    margin:1px!important;
                    padding:1px!important;
                    font-size: 12px!important;
                }
                table tr th{
                    margin:2px!important;
                    padding:2px!important;
                    font-size: 13px!important;
                }
            <?php else: ?>
                table tr td{
                    margin:1px!important;
                   padding-top:1px!important;
                    padding-button:1px!important;
                    font-size: 10px!important;
                }
                table tr th{
                    margin:2px!important;
                    padding-top:1px!important;
                    padding-button:1px!important;
                    font-size: 10px!important;
                }
            <?php endif; ?>
        </style>
        <div class="modal fade" id="calculator1" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body" >
                        <div class="calculator">
                            <p style="color:white;">Calculator</p>
                            <div class="calc-row">
                                <div class="screen"  style="height:70px!important;">0123456789</div>
                            </div>
                            <div class="calc-row">
                                <div class="button">C</div><div class="button">CE</div><div class="button backspace">back</div><div class="button plus-minus">+/-</div><div class="button">%</div>
                            </div>
                            <div class="calc-row">
                                <div class="button">7</div><div class="button">8</div><div class="button">9</div><div class="button divice">/</div><div class="button root">sqrt</div>
                            </div>
                            <div class="calc-row">
                                <div class="button">4</div><div class="button">5</div><div class="button">6</div><div class="button multiply">*</div><div class="button inverse">1/x</div>
                            </div>
                            <div class="calc-row">
                                <div class="button">1</div><div class="button">2</div><div class="button">3</div><div class="button">-</div><div class="button pi">pi</div>
                            </div>
                            <div class="calc-row">
                                <div class="button zero">0</div><div class="button decimal">.</div><div class="button">+</div><div class="button equal">=</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="<?php echo site_url(); ?>" id="baseUrl">
        <?php
        include 'include/header.php';
        include 'include/common.php';
        ?>
        <div class = "main-container ace-save-state" id = "main-container">
            <?php
            if (!empty($_SESSION['moduleList']) && $_SESSION['moduleList'] == 1) {
                include 'include/accountModule.php';
            } elseif (!empty($_SESSION['moduleList']) && $_SESSION['moduleList'] == 2) {
                include 'include/salesModule.php';
            } elseif (!empty($_SESSION['moduleList']) && $_SESSION['moduleList'] == 3) {
                include 'include/inventoryModule.php';
            } elseif (!empty($_SESSION['moduleList']) && $_SESSION['moduleList'] == 4) {
                include 'include/setupModule.php';
            } else {
                include 'include/leftMenu.php';
            }
            include 'include/message.php';
            if (!empty($mainContent)) {
                echo $mainContent;
            }
            ?>
            <div class="divFooter">Print Time: <?php echo date('Y-m-d H:i:s'); ?> || PC Name: <?php echo php_uname('n'); ?></div>
            <?php include 'include/footer.php'; ?>
        </div>
       <?php
        if(!empty($pageName) && $pageName=='salePosAdd'){
            //include 'include/jsFileForPos.php';
        }else{
            include 'include/jsFile.php';
        }
        ?>
    </body>
</html>
