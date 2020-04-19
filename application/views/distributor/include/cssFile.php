<style>
            @media print {



                @page {
                    margin-top: 0;
                    margin-bottom: 0;
                }
                body  {
                    padding-top: 20px;
                    padding-bottom: 20px ;
                }


                .noPrint {display:none;}
                strong{
                    font-weight: normal!important;
                }
            }

            a[href]:after {
                content: none !important;
            }

            a {
                text-decoration: none !important;
            }
            @import url(https://fonts.googleapis.com/css?family=Droid+Sans+Mono|Pacifico|Oxygen);
            p {
                margin: 0;
                font-family: Pacifico;
                position: relative;
                left: 5%;
            }

            .calculator {
                position: relative;
                margin: 1em auto;
                padding: 1em 0;
                display: block-inline;
                width: 350px;
                background-color: #444;
                border-radius: 25px;
                box-shadow: 5px 5px 15px 3px #111;
                font-family: 'Oxygen';
            }

            .calc-row {
                text-align: center;
            }

            .calc-row div.screen {
                font-family: Droid Sans Mono;
                display: table;
                width: 85%;
                background-color: #aaa;
                text-align: right;
                font-size: 2em;
                min-height: 1.2em;
                margin-left: 0.5em;
                padding-right: 0.5em;
                border: 1px solid #888;
                color: #333;
            }

            .calc-row div {
                text-align: center;
                display: inline-block;
                font-weight: bold;
                border: 1px solid #555;
                background-color: #eee;
                padding: 10px 0;
                margin: 7px 5px;
                border-radius: 15px;
                box-shadow: 2px 2px 1px 1px #222;
                width: 50px;
            }

            .calc-row div.zero {
                width: 112px;
            }

            .calc-row div.zero {
                margin-right: 5px;
            }

        </style>





        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
        <!--<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" />-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />

        <!-- page specific plugin styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.custom.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/chosen.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-timepicker.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/daterangepicker.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-colorpicker.min.css" />

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fonts.googleapis.com.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

        <!--[if lte IE 9]>
                <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
        <![endif]-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-skins.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-rtl.min.css" />

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->


<!--        <link href="<?php echo base_url(); ?>assets/calculate/CalcSS3.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/calculate/index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/calculate/CalcSS3.js"></script>-->
        <script src="<?php echo base_url(); ?>assets/code/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>assets/code/modules/exporting.js"></script>
        <script src="<?php echo base_url(); ?>assets/code/modules/export-data.js"></script>
        <script src="<?php echo base_url(); ?>assets/code/modules/series-label.js"></script>
        <script>
            var baseUrl='<?php echo base_url(); ?>';
    
        </script>



        <!-- ace settings handler -->
        <script src="<?php echo base_url(); ?>assets/js/ace-extra.min.js"></script>
        <link rel="stylesheet" media="all" href="<?php echo base_url() ?>assets/sweet_alert/swal.css" />
        <script src="<?php echo base_url() ?>assets/sweet_alert/swal.js"></script>
        <link href="<?php echo base_url() ?>assets/message.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>assets/jquery.js"></script>
        <script src="<?php echo base_url() ?>assets/datatable.js"></script>