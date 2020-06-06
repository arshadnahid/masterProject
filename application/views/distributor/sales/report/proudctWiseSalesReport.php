<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $productId = $this->input->post('productId');
    $branch_id = $this->input->post('branch_id');
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Product Unit
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm" action="" method="post" class="form-horizontal">

                            <div class="col-sm-12">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                                <?php echo get_phrase('Branch') ?></label>

                                            <div class="col-sm-9">

                                                <select name="branch_id" class="chosen-select form-control"
                                                        id="BranchAutoId" data-placeholder="Select Branch">
                                                    <option value=""></option>
                                                    <?php
                                                    if (!empty($branch_id)) {
                                                        $selected = $branch_id;
                                                    } else {
                                                        $selected = 'all';
                                                    }
                                                    // come from branch_dropdown_helper
                                                    echo branch_dropdown('all', $selected);
                                                    ?>
                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                                Product</label>

                                            <div class="col-sm-9">

                                                <select name="productId" class="chosen-select form-control supplierid"
                                                        id="form-field-select-3" data-placeholder="Search by Product">


                                                    <option <?php
                                                    if ($productId == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
                                                    </option>


                                                    <?php
                                                    foreach ($productList as $eachInfo):
                                                        $brandInfo = $this->Common_model->tableRow('brand', 'brandId', $eachInfo->brand_id)->brandName;
                                                        $category = $this->Common_model->tableRow('productcategory', 'category_id', $eachInfo->category_id)->title;
                                                        ?>

                                                        <option <?php
                                                        if (!empty($productId) && $productId == $eachInfo->product_id) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $eachInfo->product_id; ?>"><?php echo $category . ' ' . $eachInfo->productName . ' [ ' . $brandInfo . ' ] '; ?></option>

                                                    <?php endforeach; ?>

                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-group">

                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                From </label>

                                            <div class="col-sm-8">

                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="start_date"
                                                       value="<?php
                                                       if (!empty($start_date)) {
                                                           echo $start_date;
                                                       } else {
                                                           echo date('d-m-Y');
                                                       }
                                                       ?>" data-date-format='dd-mm-yyyy'
                                                       placeholder="Start Date: dd-mm-yyyy"/>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-group">

                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                To </label>

                                            <div class="col-sm-8">

                                                <input type="text" class="date-picker form-control" id="end_date"
                                                       name="end_date"
                                                       value="<?php
                                                       if (!empty($end_date)) {
                                                           echo $end_date;
                                                       } else {
                                                           echo date('d-m-Y');
                                                       }
                                                       ?>" data-date-format='dd-mm-yyyy'
                                                       placeholder="End Date:dd-mm-yyyy"/>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-group">

                                            <!--<label class="col-sm-2 control-label no-padding-right" for="form-field-1"></label>-->

                                            <div class="col-sm-6">

                                                <button type="submit" class="btn btn-success btn-sm">

                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                                                    Search

                                                </button>

                                            </div>

                                            <div class="col-sm-6">

                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="window.print();" style="cursor:pointer;">

                                                    <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>

                                                    Print

                                                </button>

                                            </div>

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
                    $productId = $this->input->post('productId');
                    if ($productId != 'all'):
                        ?>

                        <div class="row">

                            <div class="col-xs-10 col-xs-offset-1">


                                <div class="table-header">

                                    Product Wise Purchases Report <span
                                            style="color:greenyellow;">From <?php echo $start_date; ?>
                                        To <?php echo $end_date; ?></span>

                                </div>


                                <table class="table table-responsive">

                                    <tr>

                                        <td style="text-align:center;">
                                            <h3><?php echo $companyInfo->companyName ?>.</h3>
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

                                        <td align="center"><strong>Branch</strong></td>

                                        <td align="center"><strong>Date</strong></td>

                                        <td align="center"><strong>Voucher</strong></td>

                                        <td align="center"><strong>Customer</strong></td>

                                        <td align="center"><strong>Qty</strong></td>

                                        <td align="center"><strong>Unit Price(BDT)</strong></td>

                                        <td align="center"><strong>Total Price(BDT)</strong></td>


                                    </tr>

                                    </thead>

                                    <tbody>

                                    <?php
                                    $qty = 0;
                                    $priice = 0;
                                    $brandNameArray = array();
                                    $productWiseSales = $this->Sales_Model->getProductWiseSalesReport2($this->dist_id, $productId, $start_date, $end_date, $branch_id);


                                    foreach ($productWiseSales as $key => $each_product):
                                        if (!in_array($each_product->branch_name, $brandNameArray)) {
                                            array_push($brandNameArray, $each_product->branch_name); ?>
                                            <tr>
                                                <td colspan="6">
                                                    <?php echo '<b>' . $each_product->branch_name . '</b>' ?>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td><?php echo $key + 1; ?></td>

                                                <td><?php
                                                    //$voucherId = $this->Inventory_Model->getVoucherIdByGeneralId($each_product->generals_id);
                                                    echo date('M d, Y', strtotime($each_product->invoice_date));
                                                    ?></td>


                                                <td><a class="blue"
                                                       href="<?php echo site_url($this->project.'/viewLpgCylinder/' . $each_product->sales_invoice_id); ?>"><?php echo $each_product->invoice_no; ?></a>
                                                </td>

                                                <td><?php echo $each_product->customerName . ' [ ' . $each_product->customerID . ' ]'; ?></td>

                                                <td align="right"><?php
                                                    echo $each_product->quantity;
                                                    $qty += $each_product->quantity;
                                                    ?></td>

                                                <td align="right"><?php echo number_format($each_product->unit_price); ?></td>

                                                <td align="right"><?php
                                                    echo number_format($each_product->quantity * $each_product->unit_price);
                                                    $priice += $each_product->quantity * $each_product->unit_price;
                                                    ?></td>

                                            </tr>
                                            <?php
                                        } else {
                                            ?>

                                            <tr>

                                                <td><?php echo $key + 1; ?></td>

                                                <td><?php
                                                    //$voucherId = $this->Inventory_Model->getVoucherIdByGeneralId($each_product->generals_id);
                                                    echo date('M d, Y', strtotime($each_product->invoice_date));
                                                    ?></td>


                                                <td><a class="blue"
                                                       href="<?php echo site_url($this->project.'/salesInvoice_view/' . $each_product->sales_invoice_id); ?>"><?php echo $each_product->invoice_no; ?></a>
                                                </td>

                                                <td><?php echo $each_product->customerName . ' [ ' . $each_product->customerID . ' ]'; ?></td>

                                                <td align="right"><?php
                                                    echo $each_product->quantity;
                                                    $qty += $each_product->quantity;
                                                    ?></td>

                                                <td align="right"><?php echo number_format($each_product->unit_price); ?></td>

                                                <td align="right"><?php
                                                    echo number_format($each_product->quantity * $each_product->unit_price);
                                                    $priice += $each_product->quantity * $each_product->unit_price;
                                                    ?></td>

                                            </tr>

                                            <?php
                                        }
                                    endforeach;
                                    ?>

                                    </tbody>

                                    <tfoot>

                                    <tr>

                                        <td align="right" colspan="4"><strong>Total</strong></td>

                                        <td align='right'><strong><?php echo $qty; ?></strong></td>

                                        <td></td>

                                        <td align='right'><strong><?php echo number_format($priice); ?></strong></td>

                                    </tr>

                                    </tfoot>


                                </table>

                            </div>

                        </div>


                    <?php else: ?>

                        <div class="row">

                            <div class="col-xs-12">


                                <table class="table table-responsive">

                                    <tr>

                                        <td style="text-align:center;">
                                            <h3><?php echo $companyInfo->companyName ?>.</h3>
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

                                        <td align="center"><strong>SL</strong></td>

                                        <td align="center"><strong>Product</strong></td>

                                        <td align="center"><strong>Qty</strong></td>

                                        <td align="center"><strong>Unit Price(BDT)</strong></td>

                                        <td align="center"><strong>Total Price(BDT)</strong></td>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <?php
                                    $qty = 0;
                                    $priice = 0;
                                    $sl = 1;
                                    $brandNameArray = array();
                                    $productList = $this->Sales_Model->getProductSummationSalesReport
                                    ($this->dist_id, '', $start_date, $end_date, $branch_id);
                                    //echo '<pre>';
                                    //echo  $this->db->last_query();
                                    //print_r($productList);exit;
                                    foreach ($productList as $productSerial => $eachInfo):
                                        //echo $this->db->last_query();die;
                                        if (!empty($eachInfo->QTY)):
                                            if (!in_array($eachInfo->branch_name, $brandNameArray)) {
                                                array_push($brandNameArray, $eachInfo->branch_name); ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <?php echo '<b>' . $eachInfo->branch_name . '</b>' ?>
                                                    </td>
                                                </tr>

                                                <tr>

                                                    <td><?php echo $sl++; ?></td>

                                                    <td><?php echo $eachInfo->title . ' ' . $eachInfo->productName . ' [ ' . $eachInfo->brandName . ' ] '; ?></td>

                                                    <td align="right"><?php
                                                        echo $eachInfo->QTY;
                                                        $qty += $eachInfo->QTY;
                                                        ?></td>

                                                    <td align="right"><?php echo number_format($eachInfo->unitPrice); ?></td>

                                                    <td align="right"><?php
                                                        echo number_format($eachInfo->QTY * $eachInfo->unitPrice, 2);
                                                        $priice += $eachInfo->QTY * $eachInfo->unitPrice;
                                                        ?></td>

                                                </tr>
                                                <?php
                                            } else {
                                                ?>

                                                <tr>

                                                    <td><?php echo $sl++; ?></td>

                                                    <td><?php echo $eachInfo->title . ' ' . $eachInfo->productName . ' [ ' . $eachInfo->brandName . ' ] '; ?></td>

                                                    <td align="right"><?php
                                                        echo $eachInfo->QTY;
                                                        $qty += $eachInfo->QTY;
                                                        ?></td>

                                                    <td align="right"><?php echo number_format($eachInfo->unitPrice); ?></td>

                                                    <td align="right"><?php
                                                        echo number_format($eachInfo->QTY * $eachInfo->unitPrice, 2);
                                                        $priice += $eachInfo->QTY * $eachInfo->unitPrice;
                                                        ?></td>

                                                </tr>

                                                <?php
                                            }
                                        endif;
                                    endforeach;
                                    ?>

                                    </tbody>

                                    <tfoot>

                                    <tr>

                                        <td align="right" colspan="2"><strong>Total</strong></td>

                                        <td align='right'><strong><?php echo $qty; ?></strong></td>

                                        <td></td>

                                        <td align='right'><strong><?php echo number_format($priice); ?></strong></td>

                                    </tr>

                                    </tfoot>


                                </table>

                            </div>

                        </div>


                    <?php endif; ?>


                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>

<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>