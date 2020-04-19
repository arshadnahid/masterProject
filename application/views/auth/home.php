<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cashboi | Free Cloudebased invoicing software</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/landing-page/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/landing-page/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/landing-page/css/form-elements.css">
         <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/landing-page/bootstrap-select/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/landing-page/css/style.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
        <!-- Javascript -->
        <script src="<?php echo base_url(); ?>assets/landing-page/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/landing-page/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/landing-page/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/landing-page/js/retina-1.1.0.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="<?php echo base_url(); ?>assets/landing-page/bootstrap-select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/landing-page/js/scripts.js"></script>

        <style type="text/css">
            #userRegSubmit{
                height: 50px;
                margin: 0;
                padding: 0 20px;
                vertical-align: middle;
                background: #19b9e7;
                border: 0;
                font-family: 'Roboto', sans-serif;
                font-size: 16px;
                font-weight: 300;
                line-height: 50px;
                color: #fff;
            }
            .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){
                width: 100% !important;
            }
        </style>
        
        <!--[if lt IE 10]>
            <script src="<?php echo base_url(); ?>assets/landing-page/js/placeholder.js"></script>
        <![endif]-->

    </head>

    <body>

		<!-- Top menu -->
		<nav class="navbar navbar-inverse navbar-no-bg" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="">Store Registration Form at Cashboi</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="top-navbar-1">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<span class="li-text">
								Registered already?
							</span> 
							<a href="<?php echo base_url('userLogin'); ?>"><strong>Login</strong></a> 
							<span class="li-text">
								Here.  
							</span> 
							<span class="li-social">
								<a href="https://www.facebook.com/Cashboi-665453913641550/"><i class="fa fa-facebook"></i></a> 
								<a href="#"><i class="fa fa-twitter"></i></a> 
								<a href="#"><i class="fa fa-envelope"></i></a> 
								<a href="#"><i class="fa fa-skype"></i></a>
							</span>
						</li>
					</ul>
				</div>
			</div>
		</nav>

        <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong> Registration Form</strong></h1>
                            <div class="description">
                            	<p style="color:#ffffff">
	                            	Create free bill for your Store. 
	                            	Not only Invoice also your expenses of store.
                            	</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-sm-6 book">
                    		<img src="<?php echo base_url(); ?>assets/landing-page/img/ebook.png" alt="">
                    	</div>
                        <div class="col-sm-5 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Fill in the form below to get instant access:</h3>
                            	
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-pencil"></i>
                        		</div>
                                <?php if($this->session->flashdata('msg')){?>
                                <?php if(array_key_exists('success',$this->session->flashdata('msg'))){?>
                                    <p style="color: #fff;text-align: center;"><?php echo $this->session->flashdata('msg')['success']; ?></p>
                                <?php }?>
                                <?php }?>
                            </div>
                            
                            <div class="form-bottom">
			                    <?php echo form_open(base_url('userRegistrationAttempt'),array('class' => 'registration-form2'));?>
                                <?php if($this->session->flashdata('msg')){?>
                                <?php if(array_key_exists('error',$this->session->flashdata('msg'))){?>
                                <?php echo $this->session->flashdata('msg')['error']; ?>
                                <?php }?>
                                <?php }?>
                                    
			                    	<div class="form-group">
			                    		<label class="sr-only" for="form-first-name">Your Name</label>
                                        <?php echo form_input(array('type'=>'text','name'=>'userName','id'=>'form-first-name','class'=>'form-first-name form-control required','placeholder'=>'Your Name...'));?>
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="form-last-name">Mobile No:</label>
                                        <?php echo form_input(array('type'=>'text','name'=>'mobileNo','id'=>'form-mobile-no','class'=>'form-last-name form-control required','placeholder'=>'Mobile No...'));?>
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="form-email">Email</label>
                                        <?php echo form_input(array('type'=>'text','name'=>'email','id'=>'form-email','class'=>'form-email form-control required','placeholder'=>'Email...'));?>
			                        </div>
									<div class="form-group">
			                        	<label class="sr-only" for="form-last-name">Store Name:</label>
                                        <?php echo form_input(array('type'=>'text','name'=>'storeName','id'=>'form-store','class'=>'form-store form-control required','placeholder'=>'Store Name...'));?>
			                        </div>
									<div class="form-group">
			                        	<label class="sr-only" for="form-last-name">Website:</label>
                                         <?php echo form_input(array('type'=>'text','name'=>'websiteURL','class'=>'form-control','placeholder'=>'Website URL...'));?>
			                        </div>
									<div class="form-group">
			                        	<label class="sr-only" for="form-last-name">Faecbook page Link:</label>
                                        <?php echo form_input(array('type'=>'text','name'=>'facebookURL','class'=>'form-control','placeholder'=>'Faecbook page Link...'));?>
			                        </div>
<!--                                    <div class="form-group">
                                        <select name="typeOf" class="selectpicker show-menu-arrow">
                                          <option selected="YES">Select Store Type</option>
                                          <?php // foreach($types as $type){?>
                                          <option value="<?php // echo $type->typeID; ?>"><?php // echo $type->typeName; ?></option>
                                          <?php // } ?>
                                        </select>

                                        
                                    </div>-->
                                    <?php echo form_submit('', 'Give it to me!',array('class'=>'btn','id'=>'userRegSubmit'));?>
			                    <?php echo form_close();?>
		                    </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>


 <!-- GoStats JavaScript Based Code -->
<script type="text/javascript" src="http://gostats.com/js/counter.js"></script>
<script type="text/javascript">_gos='monster.gostats.com';_goa=492444;
_got=5;_goi=1;_gol='website traffic analysis';_GoStatsRun();</script>
<noscript><a target="_blank" title="website traffic analysis" 
href="http://gostats.com"><img alt="website traffic analysis" 
src="http://monster.gostats.com/bin/count/a_492444/t_5/i_1/counter.png" 
style="border-width:0" /></a></noscript>
<!-- End GoStats JavaScript Based Code -->

    </body>

</html>