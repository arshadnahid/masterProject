
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
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('compare_decision'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Compare ?
                </a>
            </span>
        </div>
        <br>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="dec_title"> Company/Bank Name </label>

                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5" type="text" id="dec_title" name="company_bank" placeholder="Comany Name"  autofocus="autofocus"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="asset_amount"> Title/Product</label>

                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5 numeric" type="text" id="asset_amount" name="title_Product" placeholder="Title/Product"  />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="asset_amount"> Invest Amount</label>
                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5 numeric" type="text" id="asset_amount" name="investAmount" placeholder="Invest Amount"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="asset_amount"> Percentage</label>
                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5 numeric" type="text" id="asset_amount" name="percentage" placeholder="0%"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="asset_amount"> Duration</label>
                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5 numeric" type="text" id="asset_amount" name="duration" placeholder="total month say 12,24 month"  />
                            </div>
                        </div>

                        
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




