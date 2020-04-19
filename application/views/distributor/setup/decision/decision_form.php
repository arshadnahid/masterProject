
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('moduleDashboard');?>">Decision Tools</a>
                </li>
                <li class="active">Compare</li>
            </ul>

            
            <ul class="breadcrumb pull-right">
               
                <li class="active"><a href="<?php echo site_url('compare_decision'); ?>" >
                        <i class="ace-icon  fa fa-search-plus"></i>  Compare
                    </a>
                </li>
            </ul>
            
            
        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form" method="post" action="<?php echo base_url() ?>save_decision" enctype="multipart/form-data">


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="dec_title"> Title </label>

                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5" type="text" id="dec_title" name="dec_title" placeholder="Title"  autofocus="autofocus"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="asset_amount"> Asset Amount </label>

                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5 numeric" type="text" id="asset_amount" name="asset_amount" placeholder="Asset Amount"  />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="invest_type"> Invest Type </label>

                            <div class="col-sm-9">
                                <input type="radio" name="invest_type" value="1" checked="checked"> Bank Saving &emsp;
                                <input type="radio" name="invest_type" value="2"> Invest &emsp;
                                <input type="radio" name="invest_type" value="3"> Both
                            </div>
                        </div>
                        <div id="bank_part" style="display: block;">
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="dec_title"> Bank Saving Type </label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5" type="text" id="bank_saving_type" name="bank_saving_type" placeholder=" type"  />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="amount_of_saving"> Amount of Saving  </label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5 numeric" type="text" id="amount_of_saving" name="amount_of_saving" placeholder="  Amount"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="period_time"> Period Time <br><span class="red">[ Number of month ]</span></label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5 numeric" type="text" id="period_time" name="period_time" placeholder=" number of month (Ex: 5)"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="interest_per_month"> Interest percantage/ per month (%)</label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5 numeric" type="text" id="interest_per_month" name="interest_per_month" placeholder=" Interest percantage (Ex: 2)" oninput="get_bank_saving_amount()" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="per_month_interest_amount">  Per month Interest Amount</label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5 numeric" type="text" id="per_month_interest_amount" name="per_month_interest_amount" placeholder="Amount"  value="" />
                                </div>
                            </div>
                        </div>
                        <div id="inventory_part" style="display: none;">
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="company_name"> Company Name  </label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5" type="text" id="company_name" name="company_name" placeholder="  company name"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="invest_amount"> Invest Amount  </label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5 numeric" type="text" id="invest_amount" name="invest_amount" placeholder="  Amount"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="per_month_profit_amount"> Per month profit Amount  </label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5 numeric" type="text" id="per_month_profit_amount" name="per_month_profit_amount" placeholder="  Amount"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="return_of_invest_time"> Return of investment Time <br><span class="red">[ Number of month ]</span></label>

                                <div class="col-sm-9">
                                    <input class="col-xs-10 col-sm-5 numeric" type="text" id="return_of_invest_time" name="return_of_invest_time" placeholder=" number of month (Ex: 5)"  />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="note"> Note </label>
                            <div class="col-sm-9">
                                <textarea rows="3" name="note" style="width: 420px;"></textarea>
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Compare
                                </button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script type="text/javascript">

    $('body').on('keydown', 'input, select, textarea', function(e) {
        var self = $(this)
        , form = self.parents('form:eq(0)')
        , focusable
        , next
        ;
        if (e.keyCode == 13) {
            focusable = form.find('input,a,select,button,textarea').filter(':visible');
            next = focusable.eq(focusable.index(this)+1);
            if (next.length) {
                next.focus();
            } else {
                form.submit();
            }
            return false;
        }
    });

    $(function(){
        $('input[type="radio"]').click(function(){
            if ($(this).is(':checked'))
            {
                if($(this).val() == '1'){
                    $('#bank_part').show();
                    $('#inventory_part').hide();
                }
                if($(this).val() == '2'){
                    $('#bank_part').hide();
                    $('#inventory_part').show();
                }
                if($(this).val() == '3'){
                    $('#bank_part').show();
                    $('#inventory_part').show();
                }
            }
        });
    });


	


</script>
<script src="<?php echo base_url() ?>assets/ael_inventory.js"></script>




















<!--


<style type="text/css">
    /*body{margin:40px;}*/
    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }
    .btn-circle.btn-lg {
        width: 50px;
        height: 50px;
        padding: 8px 10px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 25px;
    }

    .cls-in {
        margin-left: 2px;
    }

    .cls-ic {
        margin-left: -20px;
        background-color: black;
        border-radius: 4px;
    }

</style>


<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">




            <div class="row">
                <div class="col-xs-12">



                    <div class="page-header">
                        <h1>
                            Decision Tools
                            <small>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                Compare. 
                            </small>
                        </h1>



                          <h3 class="text-center" style="color:green">
                        <?php
                        $message = $this->session->userdata('message');
                        if ($message) {
                            echo $message;
                            $this->session->unset_userdata('message');
                        }
                        $exception = $this->session->userdata('exception');
                        if ($exception) {
                            echo $exception;
                            $this->session->unset_userdata('exception');
                        }
                        ?>
</h3> 

                        <div style="float: right;">
                            <a href="<?php echo base_url() ?>compare_decision">
                                <button class="btn btn-success">Compare View</button>
                            </a>
                        </div>


                    </div> /.page-header 

                    <div class="row">
                        <div class="col-xs-12">
                            <h4 class="red center" id="check_ledger"></h4>
                             PAGE CONTENT BEGINS 

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
-->
