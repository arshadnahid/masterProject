<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Password Reset Link</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--===============================================================================================-->	
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/vendor/images/icons/favicon.ico"/>
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/animate/animate.css">
        <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/css-hamburgers/hamburgers.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/animsition/css/animsition.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/select2/select2.min.css">
        <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/daterangepicker/daterangepicker.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/css/util.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/css/main.css">
        <!--===============================================================================================-->
    </head>
    <body>

        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100">
                    <div class="login100-form-title" style="background-image: url(<?php echo base_url() ?>assets/vendor/images/bg-01.jpg)">
                        <span class="login100-form-title-1">
                            Reset your password
                        </span>
                    </div>
                    <form class="login100-form validate-form" method="POST" action="<?php echo site_url('AuthController/resetPassword'); ?>">
                        <p class="text-center" style="color:red">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg'); ?>
                            <?php }
                            ?>
                        </p>
                        <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
                            <span class="label-input100">E-mail</span>
                            <input class="input100" type="text" name="email" placeholder="Enter Email">
                            <span class="focus-input100"></span>
                        </div>

                        
                        <div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
<!--							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>-->
						</div>

						<div>
                                <a href="<?php echo base_url(); ?>" class="txt1">
                                    Back Login 
                                </a>
                            </div>
					</div>
                        
                        
                        



                        <div class="container-login100-form-btn">
                            <button class="login100-form-btn">
                                Send Reset Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
        <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>assets/vendor/animsition/js/animsition.min.js"></script>
        <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/popper.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>assets/vendor/select2/select2.min.js"></script>
        <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>assets/vendor/daterangepicker/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/daterangepicker/daterangepicker.js"></script>
        <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>assets/vendor/countdowntime/countdowntime.js"></script>
        <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>assets/vendor/js/main.js"></script>

    </body>
</html>