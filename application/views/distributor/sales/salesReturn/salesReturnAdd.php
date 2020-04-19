
<script src="<?php echo base_url(); ?>assets/js/jQueryUI/jquery-new-ui.js"></script>
<style>
    table tr td{
        margin: 0px!important;
        padding: 2px!important;
    }

    table tr td  tfoot .form-control {
        width: 100%;
        height: 25px;
    }
</style>
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <table class="mytable table-responsive table table-bordered">
                            <tr>
                                <td  style="padding: 10px!important;">
                                    <div class="col-md-12">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sales Date  <span style="color:red;"> *</span></label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input class="form-control date-picker2" name="saleDate" id="saleDate" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" />
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Invoice No</label>
                                                <div class="col-sm-7">

                                                    <select  id="invoiceList" onchange="getInvoiceProduct(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Name">
                                                        <option value=""></option>
                                                    </select>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label text-right" for="form-field-1">

                                                    <?php echo get_phrase('Branch') ?></label>
                                                <div class="col-sm-7">
                                                    <select name="branch_id" class="chosen-select form-control"
                                                            id="BranchAutoId" data-placeholder="Select Branch">
                                                        <option value=""></option>
                                                        <?php
                                                        // come from branch_dropdown_helper
                                                        echo branch_dropdown(null,null);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 10px!important;">

                                     <div id="invoiceProduct"></div>


                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="clearfix"></div>
                                    <div class="clearfix form-actions" >
                                        <div class="col-md-offset-1 col-md-10">
                                            <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info" type="submit">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save
                                            </button>
                                            &nbsp; &nbsp; &nbsp;

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>












<script>



    function getInvoiceProduct(invoiceId){

        $.ajax({
            type:"POST",
            url:"<?php echo site_url('lpg/ReturnDagameController/getInvoiceProductList'); ?>",
            data: 'invoiceId=' + invoiceId,
            success: function(result)
            {
                $("#invoiceProduct").html(result);
            }
        });

    }


    $('.date-picker2').datepicker({
        autoclose: true,
        todayHighlight: true,
        onSelect: function(date)
        {

            $.ajax({
                type:"POST",
                url:"<?php echo site_url('lpg/ReturnDagameController/showAllInvoiceListByDate'); ?>",
                data: 'date=' + date,
                success: function(result)
                {
                    $('#invoiceList').chosen();
                    $('#invoiceList option').remove();
                    $('#invoiceList').append($(result));
                    $("#invoiceList").trigger("chosen:updated");
                }
            });
        }
    });
    //show datepicker when clicking on the icon
    /*.next().on(ace.click_event, function(){
        $(this).prev().focus();
    });*/


















</script>