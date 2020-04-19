<div class="main-content">
<div class="main-content-inner">
<div class="page-content">

<div class="row">
<div class="col-xs-12 col-md-offset-3">


<div class="row">
<div class="col-xs-3 col-sm-3 pricing-span-header">
<div class="widget-box transparent">
	<div class="widget-header">
		<h5 class="widget-title bigger lighter">Compare Product Offer</h5>
	</div>

	<div class="widget-body">
		<div class="widget-main no-padding">
			<ul class="list-unstyled list-striped pricing-table-header">
				<li style="height: 80px;">Product Name </li>
				<li style="height: 80px;">Offers</li>
				<li>Priceing Per Unit</li>
				<li>Date</li>
				
			</ul>
		</div>
	</div>
</div>
</div>

<div class="col-xs-6 col-sm-9 pricing-span-body">

<?php 
$counter = 0;
foreach ($offer_info as $compare) {
?>
<div class="pricing-span">
	<div class="widget-box pricing-box-small widget-color-purple">
		<div class="widget-header">
			<h5 class="widget-title bigger lighter"><?php echo "Comapre -".++$counter?></h5>
		</div>

		<div class="widget-body">
			<div class="widget-main no-padding">
				<ul class="list-unstyled list-striped pricing-table">
					<li style="height: 80px;"><?php echo $compare->product_name ?> </li>
					<li style="height: 80px; overflow: scroll; font-size: 14px;"><?php echo $compare->offers ?></li>
					<li> <?php echo $compare->price ?> </li>
					<li> <?php 
                     
					$date= $compare->date;
					$dt = new DateTime($date);
					echo $dt->format('Y-m-d');

					  ?> 
					</li>
					

				</ul>

			
			</div>

			<?php if ($compare->take == 0) {
			?>

			<div>
				<a href="#" class="btn btn-block btn-sm btn-danger">
					<i class="fa fa-check-square" aria-hidden="true"></i><span> Already Took</span>
				</a>
			</div>

			<?php } else {
			?>
           <div>
				<a href="<?php echo base_url()?>take_offers/<?php echo $compare->compare_id ?>" class="btn btn-block btn-sm btn-danger">
					<span> Take It</span>
				</a>
			</div>


             <?php } ?>


		</div>
	</div>
</div>

<?php } ?>



</div>
</div><!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->