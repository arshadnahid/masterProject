<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php if(isset($title)){ echo $title; } ?></title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles-->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-rtl.min.css" />

		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-datepicker3.min.css" />


		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->


		<script src="<?php echo base_url()?>assets/js/jquery-2.1.4.min.js"></script>
		<!-- ace settings handler -->
		<script src="<?php echo base_url()?>assets/js/ace-extra.min.js"></script>



		<link rel="stylesheet" media="all" href="<?php echo base_url()?>assets/sweet_alert/swal.css" />
		<script src="<?php echo base_url()?>assets/sweet_alert/swal.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo base_url()?>assets/js/html5shiv.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			.btnn {
				  position: relative;
				  height: 45px;
				  width: 150px;
				  margin: 10px 7px;
				  padding: 5px 5px;
				  font-weight: 700;
				  font-size: 15px;
				  letter-spacing: 2px;
				  color: #3C81D0;
				  border: 2px #3C81D0 solid;
				  border-radius: 4px;
				  text-transform: uppercase;
				  outline: 0;
				  overflow:hidden;
				  background: none;
				  z-index: 1;
				  cursor: pointer;
				  transition:         0.08s ease-in;
				  -o-transition:      0.08s ease-in;
				  -ms-transition:     0.08s ease-in;
				  -moz-transition:    0.08s ease-in;
				  -webkit-transition: 0.08s ease-in;
			}
			.fill:hover {
			  	color: whitesmoke;
			}

			.fill:before {
				  content: "";
				  position: absolute;
				  background: #3C81D0;
				  bottom: 0;
				  left: 100%;
				  right: 0;
				  top: 0;
				  z-index: -1;
				  -webkit-transition: left 0.15s ease-in;
			}

			.fill:hover:before {
			  	left: 0;
			}
		</style>

		<style type="text/css">
	
			.btntt {
			    /*margin-top: calc(50% + 25px);*/
			    position: relative;
			    display: inline-block;
			    width: 277px;
			    height: 50px;
			    font-size: 1em;
			    font-weight: bold;
			    line-height: 60px;
			    text-align: center;
			    text-transform: uppercase;
			    background-color: transparent;
			    cursor: pointer;
			    text-decoration:none;
			    font-family: 'Roboto', sans-serif;
			    font-weight:900;
			    font-size:17px;
			    letter-spacing: 0.045em;
			    text-decoration: none !important;
			}

			.btntt svg {
			    position: absolute;
			    top: 0;
			    left: 0;
			}

			.btntt svg rect {
			    //stroke: #EC0033;
			    stroke-width: 4;
			    stroke-dasharray: 353, 0;
			    stroke-dashoffset: 0;
			    -webkit-transition: all 600ms ease;
			    transition: all 600ms ease;
			}

			.btntt span{
			  background: rgb(255,130,130);
			  background: -moz-linear-gradient(left,  rgba(255,130,130,1) 0%, rgba(225,120,237,1) 100%);
			  background: -webkit-linear-gradient(left,  rgba(255,130,130,1) 0%,rgba(225,120,237,1) 100%);
			  background: linear-gradient(to right,  rgba(255,130,130,1) 0%,rgba(225,120,237,1) 100%);
			  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff8282', endColorstr='#e178ed',GradientType=1 );
			  
			  -webkit-background-clip: text;
			  -webkit-text-fill-color: transparent;
			}

			.btntt:hover svg rect {
			    stroke-width: 4;
			    stroke-dasharray: 196, 543;
			    stroke-dashoffset: 437;
			}
		</style>

		<style type="text/css">
			.input-icon{
				padding: 8px 12px 9px 12px;
				font-size: 14px;
				font-weight: 400;
				line-height: 1;
				color: #555;
				text-align: center;
				background-color: #eee;
				border: 1px solid #ccc; 
				margin-left: -5px;
			}
		</style>

		<style type="text/css">
    
		    .btn-spin{
		        -webkit-transition: -webkit-transform .8s ease-in-out;
		        -ms-transition: -ms-transform .8s ease-in-out;
		        transition: transform .8s ease-in-out;
		    }
		    .btn-spin:hover{
		        transform:rotate(360deg);
		        -ms-transform:rotate(360deg);
		        -webkit-transform:rotate(360deg);
		    }
		</style>

		<style type="text/css">
			.invoice-style{
				font-family:  'Hoefler Text', Georgia, 'Times New Roman', serif;
			 	font-weight: normal;
		        font-size: 1.0em;
			 	letter-spacing: .2em;
			 	line-height: 1.1em;
			 	margin:0px;
			 	text-align: center;
			 	text-transform: uppercase;
			 	color: #f2838b;

			}
		</style>

		<script>
		$(document).ready(function(){
		    $(".nav-tabs a").click(function(){
		        $(this).tab('show');
		    });
		});
		</script>
	</head>

	<body class="skin-1">
		<div id="navbar" class="navbar navbar-default ace-save-state" style="height: 55px;">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="<?php echo base_url()?>Ael_account" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							HR Module
						</small>
					</a>
				</div>
			</div><!-- /.navbar-container -->



			<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
				<ul class="nav ace-nav">
					<li>
						<!-- <a href="<?php echo base_url()?>">
						<i class="ace-icon fa fa-home"></i>
						Back To Home
						</a> -->
						<div style="background-color: #543d3d; border-radius: 53px 56px 58px 54px; height: 60px;">
						  	
							<?php if($this->session->userdata('admin_id') != ''){ ?>
						  		<a href="<?php echo base_url().'Ael_panel';?>" class="btntt">
				  			<?php }else{ ?>
					  			<a href="<?php echo base_url().'Dist_panel';?>" class="btntt">
					  		<?php } ?>

							  	<svg width="277" height="62">
							    	<defs>
								        <linearGradient id="grad1">
								            <stop offset="0%" stop-color="#FF8282"/>
								            <stop offset="100%" stop-color="#E178ED" />
								        </linearGradient>
								    </defs>
							     	<rect x="5" y="5" rx="25" fill="none" stroke="url(#grad1)" width="266" height="50"></rect>
							  	</svg>
							    <span><i class="ace-icon fa fa-home"></i>Back To Home</span>
							</a>
						</div>
					</li>


					<li class="light-blue dropdown-modal user-min" style="height: 55px;">
						<a data-toggle="dropdown" href="" class="dropdown-toggle">
						<!-- <img class="nav-user-photo" src="<?php //echo base_url().$res->dist_picture;?>" /> -->
							<span class="user-info">
							<!-- <small>AEL</small> -->
							
								<?php 
									if($this->session->userdata('admin_id') == ''){
										echo $this->session->userdata('dist_name');
									}
								?>
							</span>

							<i class="ace-icon fa fa-caret-down"></i>
						</a>



						<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<?php if (empty($login) ) {
						?>
						<?php if($this->session->userdata('admin_id') == ''){ ?>
							<li>
								<a href="<?php echo base_url()?>distributor_profile">
									<i class="ace-icon fa fa-user"></i>
									Profile
								</a>
							</li>

							<li class="divider">
								
							</li> <?php } } ?>

							<li>

								<?php if($this->session->userdata('admin_id') != ''){ ?>
							  		<a href="<?php echo base_url()?>logout">
							  			<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
					  			<?php }else{ ?>
						  			<a href="<?php echo base_url()?>Dist_panel/distributor_logout">
						  				<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
						  		<?php } ?>

							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<ul class="nav nav-list">

					<li class="<?php if (isset($active_menu) && ($active_menu == 'company')) { echo 'active open'; } ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-tag"></i>
							<span class="menu-text"> Company </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="<?php if (isset($active_sub_menu) && ($active_sub_menu == 'add_company')) { echo 'active'; }?>">

								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									Add Company
								</a>

								<b class="arrow"></b>
							</li>
							<li class="">
								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									View Company
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					
					<li class="">
						<a href="<?php echo base_url()?>Hr_module/">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Single </span>
						</a>

						<b class="arrow"></b>
					</li>

				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header">
							<h1>
								Home
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<?php if(isset($title)){ echo $title; } ?>
								</small>
							</h1>
						</div> <!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<?php 
								echo $admin_master;
								?>

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Developed by <a target="_blank" href="http://www.baseit.com.bd">Base IT</a>
							</span>
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		

		<!-- basic scripts -->

<!--[if !IE]> -->


<!-- <![endif]-->

<!--[if IE]>
<script src="<?php echo base_url()?>assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->

<!-- ace scripts -->
<script src="<?php echo base_url()?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo base_url()?>assets/js/ace.min.js"></script>
<!-- CK Editor -->
<script src="//cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>

<!-- Bootstrap WYSIHTML5 -->


<!-- inline scripts related to this page -->


<!--Table Page specific plugin scripts -->
<script src="<?php echo base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/buttons.colVis.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/dataTables.select.min.js"></script>

<!-- Select dropdown search -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/select/select.css" />

<script src="<?php echo base_url() ?>assets/select/select.js"></script>

<!-- End Select dropdown search -->




<script type="text/javascript">
jQuery(function($) {
$('#sidebar2').insertBefore('.page-content');
$('#navbar').addClass('h-navbar');
$('.footer').insertAfter('.page-content');

$('.page-content').addClass('main-content');

$('.menu-toggler[data-target="#sidebar2"]').insertBefore('.navbar-brand');


$(document).on('settings.ace.two_menu', function(e, event_name, event_val) {
if(event_name == 'sidebar_fixed') {
if( $('#sidebar').hasClass('sidebar-fixed') ) $('#sidebar2').addClass('sidebar-fixed')
else $('#sidebar2').removeClass('sidebar-fixed')
}
}).triggerHandler('settings.ace.two_menu', ['sidebar_fixed' ,$('#sidebar').hasClass('sidebar-fixed')]);

$('#sidebar2[data-sidebar-hover=true]').ace_sidebar_hover('reset');
$('#sidebar2[data-sidebar-scroll=true]').ace_sidebar_scroll('reset', true);
})
</script>



<!-- Form Page Script -->



<script>
CKEDITOR.replace( 'editor1' );
</script>

<!-- End Form Page Script -->





<!-- Table page inline scripts related to this page -->
<script type="text/javascript">
jQuery(function($) {
//initiate dataTables plugin
var myTable = 
$('#dynamic-table')
//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
.DataTable( {
bAutoWidth: false,
"aoColumns": [
{ "bSortable": false },
null, null,null, null, null,
{ "bSortable": false }
],
"aaSorting": [],


//"bProcessing": true,
//"bServerSide": true,
//"sAjaxSource": "http://127.0.0.1/table.php"   ,

//,
//"sScrollY": "200px",
//"bPaginate": false,

//"sScrollX": "100%",
//"sScrollXInner": "120%",
//"bScrollCollapse": true,
//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
//you may want to wrap the table inside a "div.dataTables_borderWrap" element

//"iDisplayLength": 50


select: {
style: 'multi'
}
} );



$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

new $.fn.dataTable.Buttons( myTable, {
buttons: [
{
"extend": "colvis",
"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
"className": "btn btn-white btn-primary btn-bold",
columns: ':not(:first):not(:last)'
},
{
"extend": "copy",
"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
"className": "btn btn-white btn-primary btn-bold"
},
{
"extend": "csv",
"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
"className": "btn btn-white btn-primary btn-bold"
},
{
"extend": "excel",
"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
"className": "btn btn-white btn-primary btn-bold"
},
{
"extend": "pdf",
"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
"className": "btn btn-white btn-primary btn-bold"
},
{
"extend": "print",
"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
"className": "btn btn-white btn-primary btn-bold",
autoPrint: true,
message: 'This print was produced using the Print button for DataTables'
}         
]
} );
myTable.buttons().container().appendTo( $('.tableTools-container') );

//style the message box
var defaultCopyAction = myTable.button(1).action();
myTable.button(1).action(function (e, dt, button, config) {
defaultCopyAction(e, dt, button, config);
$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
});


var defaultColvisAction = myTable.button(0).action();
myTable.button(0).action(function (e, dt, button, config) {

defaultColvisAction(e, dt, button, config);


if($('.dt-button-collection > .dropdown-menu').length == 0) {
$('.dt-button-collection')
.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
.find('a').attr('href', '#').wrap("<li />")
}
$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
});

////

setTimeout(function() {
$($('.tableTools-container')).find('a.dt-button').each(function() {
var div = $(this).find(' > div').first();
if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
else $(this).tooltip({container: 'body', title: $(this).text()});
});
}, 500);





myTable.on( 'select', function ( e, dt, type, index ) {
if ( type === 'row' ) {
$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
}
} );
myTable.on( 'deselect', function ( e, dt, type, index ) {
if ( type === 'row' ) {
$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
}
} );




/////////////////////////////////
//table checkboxes
$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

//select/deselect all rows according to table header checkbox
$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
var th_checked = this.checked;//checkbox inside "TH" table header

$('#dynamic-table').find('tbody > tr').each(function(){
var row = this;
if(th_checked) myTable.row(row).select();
else  myTable.row(row).deselect();
});
});

//select/deselect a row when the checkbox is checked/unchecked
$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
var row = $(this).closest('tr').get(0);
if(this.checked) myTable.row(row).deselect();
else myTable.row(row).select();
});



$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
e.stopImmediatePropagation();
e.stopPropagation();
e.preventDefault();
});



//And for the first simple table, which doesn't have TableTools or dataTables
//select/deselect all rows according to table header checkbox
var active_class = 'active';
$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
var th_checked = this.checked;//checkbox inside "TH" table header

$(this).closest('table').find('tbody > tr').each(function(){
var row = this;
if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
});
});

//select/deselect a row when the checkbox is checked/unchecked
$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
var $row = $(this).closest('tr');
if($row.is('.detail-row ')) return;
if(this.checked) $row.addClass(active_class);
else $row.removeClass(active_class);
});






/********************************/
//add tooltip for small view action buttons in dropdown menu
$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

//tooltip placement on right or left
function tooltip_placement(context, source) {
var $source = $(source);
var $parent = $source.closest('table')
var off1 = $parent.offset();
var w1 = $parent.width();

var off2 = $source.offset();
//var w2 = $source.width();

if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
return 'left';
}




/***************/
$('.show-details-btn').on('click', function(e) {
e.preventDefault();
$(this).closest('tr').next().toggleClass('open');
$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
});
/***************/





/**
//add horizontal scrollbars to a simple table
$('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
{
horizontal: true,
styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
size: 2000,
mouseWheelLock: true
}
).css('padding-top', '12px');
*/


})
</script>


<!-- <script type="text/javascript">
    $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
</script>  -->

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
  <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<!--  <script>
  $( function() {
    $( "#datepicker" ).datepicker({format: 'yyyy-mm-dd'});
  } );
  </script> -->


<script type="text/javascript">
function printDiv(printableArea) {
    var printContents = document.getElementById(printableArea).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<script type="text/javascript">
<?php
$session_data = $this->session->userdata('admin_id');
$branch_id = $this->session->userdata('branch_id');
?>



$(document).keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13') {
        //addtocart2();    
    }
});


    var box = [''];
function addtocart2() {

        var barcode = $('#addtocart').val();
       
        //alert(customer);
        
        
        if(barcode==''){ alert("Enter barcode number");}
        else{
        /// barcode check------start 
        var qtyneed = prompt("Please enter Quantity","0");
      //  alert('Loading');
        
        if(qtyneed>0){
        //quantity check-----------start    
        
        var a = box.indexOf(barcode);
        // alert(box);
        if (a > -1) {
            //document.getElementById("demo").innerHTML = a;

            alert('Product Is already in List');
            $('#addtocart').val('');
            return false;
        }
        else {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>sales/addtocart",
                data: {barcode: barcode, qtyneed:qtyneed}
            })
                    .done(function(msg) {
                //alert(msg);
                
                if (msg == 1) {
                    alert("Barcode Not in List !");
                }
                if(msg == 2){
                    alert("Quantity not available");
                }
                else {
                    box.push(barcode);
                    $('#cattable').append(msg);
                    sum();
                    duechange();
                    $('#addtocart').val('');
                }

                


            });

        }
        //quentity check ----------------------end
        }
        //barcode check end
        }
        
    }


     function submitsales() {
       
        var sales = $("#sales").val();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>sales/sales",
            data: {sales: sales}
        })
                .done(function(msg) {
            //alert(msg);
                 //  var url="<?php //echo base_url(); ?>pos/welcome/invoice_print?invoice_id="+msg;
                  // window.open(url,'_blank');
          var url = "<?php echo base_url(); ?>sales/sales";
           window.open(url, "_self");

            alert("Done..");
            //location.reload();
        });

        


    }



        function remove_product(barcode) {
        //alert(barcode);
        var code = $('#' + barcode).attr('class');
        console.log(box);
        var i = box.indexOf(code);
        console.log(i);
        if (i != -1) {
            box.splice(i, 1);
        }
        console.log(box);

        $('#' + barcode).remove();
        sum();
        duechange();
    }



    </script>


    <script type="text/javascript">


     function purchases_price() {
            var txtaFirstNumberValue = document.getElementById('pprice').value;
            
            var txtaThirdNumberValue = document.getElementById('purqty').value;
           var txtaTwoNumberValue = document.getElementById('advnc').value;
            

            var result = parseInt(txtaFirstNumberValue) * parseInt(txtaThirdNumberValue);
            if (!isNaN(result)) {
                document.getElementById('totalp').value = result;
                
                  
            }

 var results = parseInt(result) - parseInt(txtaTwoNumberValue);
             if (!isNaN(results)) {
              
                
                  

                   document.getElementById('duep').value = results;
            }





          }
 function myFunction() {
    window.print();
}


    </script>
<!--  -->
<script src="<?php echo base_url()?>assets/js/daterangepicker.min.js"></script>

<script src="<?php echo base_url()?>assets/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">

    $('.date-picker').datepicker({
                    autoclose: true,
                    todayHighlight: true
                })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });

    $('.month-picker').datepicker({
                    //format: "MM yyyy",
				    minViewMode: 1,
					//startDate: "-1m",
				    //endDate: "+1y",    
				    autoclose: true
                })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });


    $('.year-picker').datepicker({
                    //format: "MM yyyy",
				    minViewMode: 2,
					//startDate: "-1m",
				    //endDate: "+1y",    
				    autoclose: true
                })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });







</script>




<script type="text/javascript">

	// function check_if_exists() {
 //   		var cat_name = $('#cat_name').val();
 //        //alert(customer);
 //        $.ajax({
 //            type: "POST",
 //            url: "<?php echo base_url();?>Product/filename_exists",
 //            data: {cat_name: cat_name}
 //        })
 //                .done(function(msg) {
 //            //alert(msg);
            
 //            if (msg == 1) {
 //                alert("Name Exists");
 //                Hidefunc();
 //            }
 //            // if(msg == 2){
 //            //     alert("Name Wrong");
 //            // }
 //            else {
 //                 $('#msg').html('<span style="color: green;">'+msg+"</span>");
 //            }
 //        });  
 //    }

 //    function Hidefunc() {
	//     document.getElementById("btn_hide").style.display = "none";
	// }

</script>




<!-- <script type="text/javascript">

$('#lis').each(function() {
    var lengths = $(this).find('ul').length;
  
    document.getElementById('output').innerHTML = lengths;
    document.getElementById('outputs').innerHTML = lengths;
});
</script> -->










	</body>
</html>
