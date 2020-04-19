
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Decision Tools</a>
                </li>
                <li class="active">Compare</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('newDecision'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Add New
                </a>
            </span>
        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-xs-4 col-sm-4 pricing-span-header">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h4 class="widget-title bigger lighter">Company/Bank Name</h4>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <ul class="list-unstyled list-striped pricing-table-header">
                                 
                                    <li style="">Title/Product</li>
                                    <li>Invest Amount</li>
                                    <li>Percentage</li>
                                    <li>Duration</li>
                                    <li>Per month Interest/Profit Amount</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-8 col-sm-8 pricing-span-body">

                    <?php foreach ($newDecisionList as $key => $compare) { ?>


                        <div class="pricing-span">
                            <div class="widget-box pricing-box-small <?php
                    if ($key % 2 == 0) {
                        echo 'widget-color-red';
                    } else {
                        echo 'widget-color-purple';
                    }
                        ?>">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter"><?php echo $compare->company_bank; ?></h5>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main no-padding">
                                        <ul class="list-unstyled list-striped pricing-table">
                                            <li style=""><?php echo $compare->title_Product; ?></li>
                                            <li style=""><?php echo $compare->investAmount; ?>৳</li>
                                        <li> <?php echo $compare->percentage; ?>%</li>
                                        <li> 
                                            <div class="price">
                                                <span class="label label-lg label-inverse arrowed-in arrowed-in-right">
                                                    <?php echo $compare->duration; ?> 
                                                    <small>month</small>
                                                </span>
                                            </div>
                                        </li>
                                        <li> 
                                            <div class="price">
                                                <span class="label label-lg label-pink arrowed-in arrowed-in-right">

                                                    <?php
                                                    $monthlyAmount = $compare->investAmount / $compare->duration;
                                                    $monthlyProfit = ($monthlyAmount / 100) * $compare->percentage;
                                                    echo number_format($monthlyProfit,2);
                                                    ?> ৳ 
                                                    <small>/month</small>
                                                </span>
                                            </div>
                                        </li>
                                        </ul>
                                    </div>

                                 
                                       
                                        <?php if ($key == 0): ?>
                                            <div style="background:green!important;">
                                                <a href="javascript:void(0)" class="btn btn-block btn-sm btn-danger">

                                                    <span class="fa fa-check"></span>&nbsp;Best Decision

                                                </a>
                                            </div>
                                        <?php else: ?>
<!--                                            <div >
                                                <a href="javascript:void(0)" class="btn btn-block btn-sm btn-danger">
                                                    <span></span>
                                                </a>
                                            </div>-->
                                        <?php endif; ?>
                                    
                                </div>
                            </div>
                        </div>























<!--                        <div class="widget-box pricing-box-small widget-color-purple">
                            <div class="widget-header">
                                <h5 class="widget-title bigger lighter"><?php echo $compare->company_bank; ?></h5>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <ul class="list-unstyled list-striped pricing-table">
                                        <li style=""><?php echo $compare->title_Product; ?></li>
                                        <li style=""><?php echo $compare->investAmount; ?>৳</li>
                                        <li> <?php echo $compare->percentage; ?>%</li>
                                        <li> 
                                            <div class="price">
                                                <span class="label label-lg label-inverse arrowed-in arrowed-in-right">
                                                    <?php echo $compare->duration; ?> 
                                                    <small>month</small>
                                                </span>
                                            </div>
                                        </li>
                                        <li> 
                                            <div class="price">
                                                <span class="label label-lg label-pink arrowed-in arrowed-in-right">

                                                    <?php
                                                    $monthlyAmount = $compare->investAmount / $compare->duration;
                                                    $monthlyProfit = ($monthlyAmount / 100) * $compare->percentage;
                                                    echo $monthlyProfit;
                                                    ?> ৳ 
                                                    <small>/month</small>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>


                            </div>
                        </div>-->




                    <?php } ?>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>

