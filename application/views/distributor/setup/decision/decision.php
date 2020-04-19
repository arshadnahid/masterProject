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
                <li class="active">
                    <i class="ace-icon fa fa-list"></i>
                    <a href="<?php echo site_url('productList'); ?>">List</a>
                </li>
                
            </ul>
            
            
           
        </div>
        <br>

        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form" method="post" action="<?php echo base_url() ?>compare_offers" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-select-3"> Product</label>

                            <div class="col-sm-4">
                                <select name="product_id"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Choose Users...">
                                    <option >----------Select Product-----------</option>
                                    <?php foreach ($offer_info as $offer_info) { ?>
                                        <option value="<?php echo $offer_info->product_id ?>"><?php echo $offer_info->product_name ?></option>
                                    <?php } ?>
                                </select>
                                        <!-- <input type="text" id="form-field-1" name="user_label" placeholder="label" class="col-xs-10 col-sm-5" /> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Offers </label>

                            <div class="col-sm-9">
                                <textarea rows="6" name="offers" style="width: 420px;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="id-date-picker-1"> Price </label>
                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-5" type="text" id="" name="price" placeholder="Offer Price"  />
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" >
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









<!--
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




                    </div> /.page-header 

                    <div class="row">
                        <div class="col-xs-12">
                             PAGE CONTENT BEGINS 

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>-->
