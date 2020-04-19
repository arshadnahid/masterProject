<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $categoryId = $this->input->post('categoryId');
    $brandId = $this->input->post('brandId');
endif;
?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/2'); ?>">Sales</a>
                </li>
                <li class="active">Top Sales Product</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo site_url('DistributorDashboard/2'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>
        <br>
        <div class="page-content">
            <div class="row noPrint">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-sm-12">
                            <div class="table-header">
                                Top Sales Product
                            </div>
                            <br>
                            <div style="background-color: grey!important;">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From Date</label>
                                        <div class="col-sm-8">
                                            <input type="text"class="date-picker" id="start_date" name="start_date" value="<?php
if (!empty($start_date)) {
    echo $start_date;
} else {
    echo date('d-m-Y');
}
?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker" id="end_date" name="end_date" value="<?php
                                                   if (!empty($end_date)) {
                                                       echo $end_date;
                                                   } else {
                                                       echo date('d-m-Y');
                                                   }
?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">
                                            <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                            Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->
            <?php
            if (isset($_POST['start_date'])):
                $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));

                $dr = 0;
                $cr = 0;
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-header">
                            Top Sales Product Report <span style="color:greenyellow;">From <?php echo $start_date; ?> To <?php echo $end_date; ?></span>
                        </div>
                        <div class="noPrint">
    <!--                        <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('InventoryController/stockReport_export_excel/') ?>" class="btn btn-success pull-right">
                            <i class="ace-icon fa fa-download"></i>
                            Excel
                        </a>-->
                        </div>
                        <table class="table table-responsive">
                            <tr>
                                <td style="text-align:center;">
                                    <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                    <span><?php echo $companyInfo->address; ?></span><br>
                                    <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                    <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                    <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                    <strong><?php echo $pageTitle; ?></strong>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <td  style="text-align:center;"><strong>Sl</strong></td>
                                    <td  style="text-align:center;"><strong>Category</strong></td>
                                    <td   style="text-align:center;"><strong>Products</strong></td>
                                    <td  style="text-align:center;"><strong>Products Code</strong></td>
                                    <td  style="text-align:center;"><strong>Quantity</strong></td>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $allQty = 0;
                                foreach ($topSaleProduct as $key => $eachSale):
                                    echo $this->db->last_query();die;

                                    $allQty+=$eachSale->totalSale;
                                    ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td><?php echo $eachSale->title; ?></td>
                                        <td>[<?php echo $eachSale->productName; ?>[ <?php echo $eachSale->brandName; ?> ]</td>
                                        <td><?php echo $eachSale->product_code; ?></td>
                                        <td><?php echo $eachSale->totalSale; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" align="right"><strong>Total </strong></td>
                                    <td align="right" align="right"><strong> <?php echo $allQty; ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
