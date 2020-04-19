<!DOCTYPE html>

<html lang="en">

<head>

	<title>User Login</title>

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">

<!--===============================================================================================-->	

	<link rel="icon" type="image/png" href="<?php echo base_url();?>assets/vendor/images/icons/favicon.ico"/>

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/bootstrap/css/bootstrap.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/animate/animate.css">

<!--===============================================================================================-->	

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/css-hamburgers/hamburgers.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/animsition/css/animsition.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/select2/select2.min.css">

<!--===============================================================================================-->	

      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/daterangepicker/daterangepicker.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/css/util.css">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/vendor/css/main.css">

<!--===============================================================================================-->

</head>

<body>

	<div class="limiter">

		<div class="container-login100">

			<div class="wrap-login100">

				<div class="login100-form-title" style="background-image: url(<?php echo base_url()?>assets/vendor/images/bg-01.jpg)">

					<span class="login100-form-title-1">

						Sign In

					</span>

				</div>



                            <form class="login100-form validate-form" method="POST" action="<?php echo site_url('AuthController/checkLogin');?>">

                                     <p class="text-center" style="color:red">

                                        <?php if ($this->session->flashdata('msg')) { ?>

                                            <?php echo $this->session->flashdata('msg'); ?>

                                        <?php }

                                        ?>

                                    </p>

                                    

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">

						<span class="label-input100">Username</span>

						<input class="input100" type="text" name="dist_email" placeholder="Enter Username">

						<span class="focus-input100"></span>

					</div>



					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">

						<span class="label-input100">Password</span>

						<input class="input100" type="password" name="dist_password" placeholder="Enter password">

						<span class="focus-input100"></span>

					</div>



					<div class="flex-sb-m w-full p-b-30">

						<div class="contact100-form-checkbox">

							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">

							<label class="label-checkbox100" for="ckb1">

								Remember me

							</label>

						</div>



						<div>

							<a href="<?php echo base_url('AuthController/forgotpasswor');?>" class="txt1">

								Forgot Password?

							</a>

						</div>

					</div>



					<div class="container-login100-form-btn">

						<button class="login100-form-btn">

							Login

						</button>

					</div>

				</form>

			</div>

		</div>

	</div>

	

<!--===============================================================================================-->

	<script src="<?php echo base_url();?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>

<!--===============================================================================================-->

	<script src="<?php echo base_url();?>assets/vendor/animsition/js/animsition.min.js"></script>

<!--===============================================================================================-->

	<script src="<?php echo base_url();?>assets/vendor/bootstrap/js/popper.js"></script>

	<script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>

<!--===============================================================================================-->

	<script src="<?php echo base_url();?>assets/vendor/select2/select2.min.js"></script>

<!--===============================================================================================-->

	<script src="<?php echo base_url();?>assets/vendor/daterangepicker/moment.min.js"></script>

	<script src="<?php echo base_url();?>assets/vendor/daterangepicker/daterangepicker.js"></script>

<!--===============================================================================================-->

	<script src="<?php echo base_url();?>assets/vendor/countdowntime/countdowntime.js"></script>

<!--===============================================================================================-->

	<script src="<?php echo base_url();?>assets/vendor/js/main.js"></script>



</body>

</html>



































































<!--<!DOCTYPE html>

<html lang="en">

    <head>

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <meta charset="utf-8" />

        <title>Distributor | Login</title>



        <meta name="description" content="User login page" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />



         bootstrap & fontawesome 

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

        <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />



         text fonts 

        <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

         ace styles 

        <link rel="stylesheet" href="assets/css/ace.min.css" />



        [if lte IE 9]>

                <link rel="stylesheet" href="assets/css/ace-part2.min.css" />

        <![endif]

        <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />



        [if lte IE 9]>

          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />

        <![endif]



         HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries 



        [if lte IE 8]>

        <script src="assets/js/html5shiv.min.js"></script>

        <script src="assets/js/respond.min.js"></script>

        <![endif]

    </head>



    <body class="login-layout light-login">

        <div class="main-container">

            <div class="main-content">

                <div class="row">

                    <div class="col-sm-10 col-sm-offset-1">

                        <div class="login-container">

                            <div class="center">

                                <h1>

                                    <i class="ace-icon fa fa-leaf green"></i>

                                    <span class="red">Welcome to AEL</span>

                                    <span class="white" id="id-text2">ERP</span>

                                </h1>



                            </div>



                            <div class="space-8"></div>



                            <div class="position-relative">

                                <div id="login-box" class="login-box visible widget-box no-border">





                                    <h3 class="text-center" style="color:green">

                                        <?php if ($this->session->flashdata('msg')) { ?>

                                            <?php echo $this->session->flashdata('msg'); ?>

                                        <?php }

                                        ?>

                                    </h3>









                                    <div class="widget-body">

                                        <div class="widget-main">

                                            <h4 class="header blue lighter bigger">

                                                <i class="ace-icon fa fa-coffee green"></i>

                                                Please Enter Your Information

                                            </h4>



                                            <div class="space-8"></div>



                                            <form method="post" action="<?php echo base_url('AuthController/checkLogin'); ?>">

                                                <fieldset>

                                                    <label class="block clearfix">

                                                        <span class="block input-icon input-icon-right">

                                                            <input type="text" class="form-control" name="dist_email" placeholder="Email" />

                                                            <i class="ace-icon fa fa-user"></i>

                                                        </span>

                                                    </label>



                                                    <label class="block clearfix">

                                                        <span class="block input-icon input-icon-right">

                                                            <input type="password" class="form-control" name = "dist_password" placeholder="Password" />

                                                            <i class="ace-icon fa fa-lock"></i>

                                                        </span>

                                                    </label>



                                                    <div class="space"></div>



                                                    <div class="clearfix">





                                                        <button  class="btn-block pull-right btn btn-sm btn-primary" type="submit" >

                                                            <i class="ace-icon fa fa-key"></i>

                                                            <span class="bigger-110">Login</span>

                                                        </button>

                                                    </div>





                                                </fieldset>

                                            </form>

                                             

                                            <div class="social-or-login center">

                                            <span class="bigger-110">Or Login Using</span>

                                            </div>

                                            

                                            <div class="space-6"></div>

                                            

                                            <div class="center">

                                            

                                            <a class="btn btn-primary btn-lg">

                                                    User Login

                                            </a>

                                            

                                            </div> 







                                        </div> /.widget-main 



                                        <div class="toolbar clearfix">

                                            <div class="">

                                                <span href="#" class="btn-block user-signup-link" data-toggle="modal" data-target="#myModal">



                                                    &nbsp;&nbsp; Forgot Password?

                                                </span>

                                            </div>



                                            <div class="">

                                                <a href="http://www.ael-bd.net"  class="user-signup-link">

                                                    AEL



                                                </a>

                                            </div>

                                        </div>

                                    </div> /.widget-body 

                                </div> /.login-box 







                            </div> /.position-relative 



                        </div>

                    </div> /.col 

                </div> /.row 

            </div> /.main-content 

        </div> /.main-container 

        <script src="assets/js/jquery-2.1.4.min.js"></script>

        <script type="text/javascript">

            if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");

        </script>





       



         Modal 

-->        <div class="modal fade" id="myModal" role="dialog">

            <div class="modal-dialog">



                

                <div class="modal-content">

                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                        <h4 class="modal-title">Reset your password</h4>

                    </div>

                    <div class="modal-body">

                       

                        

                        

                        

                        <form class="login100-form validate-form" method="POST" action="">

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">

						<span class="label-input100">Email</span>

						<input class="input100" type="text" name="dist_email" placeholder="Enter Email">

						<span class="focus-input100"></span>

					</div>



					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">

						<span class="label-input100">Password</span>

						<input class="input100" type="password" name="dist_password" placeholder="Enter password">

						<span class="focus-input100"></span>

					</div>



					<div class="flex-sb-m w-full p-b-30">

						<div class="contact100-form-checkbox">

							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">

							<label class="label-checkbox100" for="ckb1">

								Remember me

							</label>

						</div>



						<div>

							<a  data-toggle="modal" data-target="#myModal" href="#" class="txt1">

								Forgot Password?

							</a>

						</div>

					</div>



					<div class="container-login100-form-btn">

						<button class="login100-form-btn">

							Login

						</button>

					</div>

				</form>

                        

                        

<!--                        <form id="publicForm" action="<?php echo site_url('AuthController/resetPassword');?>"  method="post" class="form-horizontal">

                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> E-mail<span style="color:red!important"> *</span></label>

                            <div class="col-sm-6">

                                <input type="email" id="form-field-1" name="email"  value="" class="form-control" placeholder="to@gmail.com" required/>

                            </div>

                        </div>

                        <div class="form-group">

                            <div class="col-sm-6 col-sm-offset-3">

                               <button onclick="return isconfirm()"  id="subBtn" class="btn btn-info btn-block" type="submit">

                                    <i class="ace-icon fa fa-check bigger-110"></i>

                                    Send Reset Email

                                </button>



                            </div>

                        </div>

                        





                        <div class="clearfix form-actions" >

                            <div class="col-md-offset-3 col-md-9">

                                

                                

                            </div>

                        </div>

                    </form>-->

                        

                        

                        

                        

                    </div>

                    

                </div>



            </div>

        </div><!--









         inline scripts related to this page 

        <script type="text/javascript">

            jQuery(function($) {

                $(document).on('click', '.toolbar a[data-target]', function(e) {

                    e.preventDefault();

                    var target = $(this).data('target');

                    $('.widget-box.visible').removeClass('visible');//hide others

                    $(target).addClass('visible');//show target

                });

            });

			

			

			

            //you don't need this, just used for changing background

            jQuery(function($) {

                $('#btn-login-dark').on('click', function(e) {

                    $('body').attr('class', 'login-layout');

                    $('#id-text2').attr('class', 'white');

                    $('#id-company-text').attr('class', 'blue');

				

                    e.preventDefault();

                });

                $('#btn-login-light').on('click', function(e) {

                    $('body').attr('class', 'login-layout light-login');

                    $('#id-text2').attr('class', 'grey');

                    $('#id-company-text').attr('class', 'blue');

				

                    e.preventDefault();

                });

                $('#btn-login-blur').on('click', function(e) {

                    $('body').attr('class', 'login-layout blur-login');

                    $('#id-text2').attr('class', 'white');

                    $('#id-company-text').attr('class', 'light-blue');

				

                    e.preventDefault();

                });

			 

            });

        </script>

    </body>

</html>-->

