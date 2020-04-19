<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 4/7/2019
 * Time: 4:51 PM
 */?>


<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state  noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Cylinder Combine Report</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>
        <br>
        <div class="page-content">
            <div class="row  noPrint">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-md-12">
                            <div class="table-header">
                                Cylinder Combine Report
                            </div>
                            <br>
                            <div class="col-md-7">

                                <div class="col-md-2">

                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Brand</label>
                                        <div class="col-sm-8">
                                            <select  name="brandId" class="chosen-select form-control " id="form-field-select-3" data-placeholder="Search by Category">
                                                <option <?php
                                                if (!empty($brandId) && $brandId == '0') {
                                                    echo "selected";
                                                }
                                                ?> value="0">All</option>
                                                <?php foreach ($brandList as $eachInfo): ?>
                                                    <option <?php
                                                    if (!empty($brandId) && $brandId == $eachInfo->brandId) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $eachInfo->brandId; ?>"><?php echo $eachInfo->brandName; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1"> From </label>
                                        <div class="col-md-8">
                                            <input type="text"class="date-picker" id="start_date" name="start_date" value="<?php
                                            if (!empty($from_date)) {
                                                echo $from_date;
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format='dd-mm-yyyy' placeholder=" dd-mm-yyyy" style="width:100%"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;To</label>
                                        <div class="col-md-8">
                                            <input type="text" class="date-picker" id="end_date" name="end_date" value="<?php
                                            if (!empty($to_date)):
                                                echo $to_date;
                                            else:
                                                echo date('d-m-Y');
                                            endif;
                                            ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy" style="width:100%"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <button type="submit"  class="btn btn-success btn-sm">
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">

                                            Print
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
            </div><!-- /.col -->
            <?php
            if (isset($_POST['start_date'])):
                $type_id = $this->input->post('type_id');
                $searchId = $this->input->post('searchId');
                $productId = $this->input->post('productId');
                $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                $total_pvsdebit = '';
                $total_pvscredit = '';
                $total_debit = '';
                $total_credit = '';
                $total_balance = '';
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-header">
                            Cylinder Combine Report <span style="color:greenyellow;">From <?php echo $from_date; ?> To <?php echo $to_date; ?></span>
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
                        <table class="table table-bordered">
                            <?php
                            //all supplier all customer

                                $topening = 0;
                                $tstockIn = 0;
                                $tstockOut = 0;
                                ?>
                                <thead>
                                <tr>
                                    <td align="center"><strong>SL</strong></td>
                                    <td align="center"><strong>Brand</strong></td>
                                    <td align="center"><strong>Refil Cylinder</strong></td>
                                    <td align="center"><strong>Empty Cylender</strong></td>
                                    <td align="center"><strong>Total</strong></td>
                                    <td align="center"><strong>Customer Due</strong></td>
                                    <td align="center"><strong>Customer Excess</strong></td>
                                    <td align="center"><strong>Supplier Due</strong></td>
                                    <td align="center"><strong>Supplier Excess</strong></td>
                                    <td align="center"><strong>Closing Stock</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $j = 1;
                                foreach ($allStock as $key => $eachResult):
                                    $refill_qty=$eachResult->purchase_refill_qty-$eachResult->sales_refill_qty+$eachResult->exchange_qty_refil;
                                    $empty_qty=$eachResult->purchase_empty_qty-$eachResult->sales_empty_qty+$eachResult->exchange_qty_empty;
                                    $ttCylender=$refill_qty+$empty_qty;
                                    //$ttCylender=($eachResult->purchase_refill_qty-$eachResult->sales_refill_qty)+($eachResult->purchase_empty_qty-$eachResult->sales_empty_qty);
                                    $sales_customer_due=$eachResult->sales_customer_due;
                                    $sales_customer_advance=$eachResult->sales_customer_advance;
                                    $purchase_supplier_due=$eachResult->purchase_supplier_due;
                                    $purchase_supplier_advance=$eachResult->purchase_supplier_advance;
                                    $ttCyl=$refill_qty+$empty_qty+$eachResult->sales_customer_due+$eachResult->sales_customer_advance+$eachResult->purchase_supplier_due+$eachResult->purchase_supplier_advance;
                                    //$ttCyl=($eachResult->purchase_refill_qty-$eachResult->sales_refill_qty)+($eachResult->purchase_empty_qty-$eachResult->sales_empty_qty)+$eachResult->sales_customer_due+$eachResult->sales_customer_advance+$eachResult->purchase_supplier_due+$eachResult->purchase_supplier_advance;
                                        if($ttCylender>0 && $ttCyl>0){
                                        ?>
                                        <tr>
                                            <td><?php echo $j++; ?></td>
                                            <td><?php echo  $eachResult->brandName ; ?></td>
                                            <td align="right"> <?php echo $refill_qty; ?> </td>
                                            <td align="right"><?php echo $empty_qty;?></td>
                                            <td align="right"><?php echo $ttCylender; ?></td>
                                            <td align="right"><?php echo $sales_customer_due; ?></td>
                                            <td align="right"><?php echo $sales_customer_advance; ?></td>
                                            <td align="right"><?php echo $purchase_supplier_due; ?></td>
                                            <td align="right"><?php echo $purchase_supplier_advance; ?></td>
                                            <td align="right">
                                                <?php echo $ttCyl; ?>
                                            </td>

                                        </tr>
                                        <?php
                                        }
                                endforeach;
                                    ?>







                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
