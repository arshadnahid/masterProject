
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
            
            <ul class="breadcrumb pull-right">
                
                <li class="active saleAddPermission"><a href="<?php echo site_url('decision'); ?>" >
                        <i class="ace-icon 	fa fa-plus"></i>  Add
                    </a>
                </li>
                
            </ul>
            
            
            
        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-xs-4 col-sm-4 pricing-span-header">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h4 class="widget-title bigger lighter">Decision Title</h4>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <ul class="list-unstyled list-striped pricing-table-header">
                                    <li style="">Asset Amount </li>
                                    <li style="">Invest Type</li>
                                    <li>Amount of Saving / Invest</li>
                                    <!-- <li>Note</li> -->
                                    <li>Decision Making Date</li>
                                    <li>Period Time / Return of investment Time</li>
                                    <li>Per month Interest/Profit Amount</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-8 col-sm-8 pricing-span-body">

                    <?php
                    $counter = 0;
                    $counter1 = 0;
                    foreach ($offer_info as $key => $compare) {
                        $counter++;
                        ?>
                        <div class="pricing-span">
                            <div class="widget-box pricing-box-small <?php
                    if ($counter % 2 == 0) {
                        echo 'widget-color-red';
                    } else {
                        echo 'widget-color-purple';
                    }
                        ?>">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter"><?php echo substr($compare->dec_title, 0, 15) . '...'; ?></h5>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main no-padding">
                                        <ul class="list-unstyled list-striped pricing-table">
                                            <li style=""><?php echo $compare->asset_amount . ' &#2547;'; ?></li>
                                            <li style=""><?php
                             if ($compare->invest_type == '1') {
                                 echo 'Bank Saving';
                             } else if ($compare->invest_type == '2') {
                                 echo 'Invest';
                             } else {
                                 echo 'Both';
                             }
                        ?></li>
                                            <li> <?php
                                            if ($compare->invest_type == '1') {
                                                echo $compare->amount_of_saving . ' &#2547;';
                                            } else if ($compare->invest_type == '2') {
                                                echo $compare->invest_amount . ' &#2547;';
                                            } else {
                                                echo ($compare->amount_of_saving + $compare->invest_amount);
                                                echo ' &#2547;';
                                            }
                        ?> </li>
                                            <!-- <li> <?php //echo $compare->note                ?> </li> -->

                                            <li> <?php
                                            //echo $compare->decision_date;
                                            echo date('Y-m-d', strtotime($compare->decision_date));
                        ?> </li>
                                            <li> 
                                                <div class="price">
                                                    <span class="label label-lg label-inverse arrowed-in arrowed-in-right">
                                                        <?php
                                                        if ($compare->invest_type == '1') {
                                                            echo $compare->period_time;
                                                        } else if ($compare->invest_type == '2') {
                                                            echo $compare->return_of_invest_time;
                                                        } else {
                                                            echo ($compare->period_time >= $compare->return_of_invest_time) ? $compare->period_time : $compare->return_of_invest_time;
                                                        }
                                                        ?> 
                                                        <small>month</small>
                                                    </span>
                                                </div>
                                            </li>
                                            <li> 
                                                <div class="price">
                                                    <span class="label label-lg label-pink arrowed-in arrowed-in-right">
                                                        <?php //echo $compare->period_time.' &#2547;';  ?> 
                                                        <?php
                                                        if ($compare->invest_type == '1') {
                                                            echo $compare->per_month_interest_amount . ' &#2547;';
                                                        } else if ($compare->invest_type == '2') {
                                                            echo $compare->per_month_profit_amount . ' &#2547;';
                                                        } else {
                                                            echo ($compare->per_month_interest_amount + $compare->per_month_profit_amount);
                                                            echo ' &#2547;';
                                                        }
                                                        ?> 
                                                        <small>/month</small>
                                                    </span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <?php if ($counter % 2 == 0) {
                                        ?>
                                        <?php if ($key == 0): ?>
                                            <div style="background:green!important;">
                                                <a href="javascript:void(0)" class="btn btn-block btn-sm btn-danger">

                                                    <span class="fa fa-check"></span>&nbsp;Best Decision

                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div >
                                                <a href="javascript:void(0)" class="btn btn-block btn-sm btn-danger">
                                                    <span></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php } else {
                                        ?>
                                        <?php if ($key == 0): ?>
                                            <div style="background:green!important;">
                                                                 <!-- <a href="<?php //echo base_url()               ?>take_offers/<?php //echo $compare->decision_id                ?>" class="btn btn-block btn-sm btn-purple"> -->
                                                <a href="javascript:void(0)" class="btn btn-block btn-sm btn-purple">
                                                    <?php if ($key == 0): ?>
                                                        <span class="fa fa-check"></span>&nbsp;Best Decision
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div>
                                                <a href="javascript:void(0)" class="btn btn-block btn-sm btn-danger">
                                                    <span class="fa fa-check"></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>


























<!--


<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">

            <div class="row">
                <div class="col-xs-12 col-xs-offset-1">


                     PAGE CONTENT ENDS 
                </div> /.col 
            </div> /.row 
        </div> /.page-content 
    </div>
</div> /.main-content -->