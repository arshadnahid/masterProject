<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $productId = $this->input->post('productId');
    $branch_id = $this->input->post('branch_id');
endif;
?>
<div class="row">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Product Ledger Report
                </div>

            </div>

            <div class="portlet-body">

                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal noPrint">

                            <div style="background-color: grey!important;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right"
                                               for="branch_id">  <?php echo get_phrase('Branch') ?> </label>
                                        <div class="col-md-9">
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
                                        <label class="col-md-3 control-label no-padding-right" for="form-field-1">
                                            Product </label>
                                        <div class="col-md-9">
                                            <select name="productId" id="productID" class="chosen-select form-control"
                                                    id="form-field-select-3" data-placeholder="Search by Product"
                                                    style="width: 100%">
                                                <option <?php
                                                if ($productId == 'all') {
                                                    echo "selected";
                                                }
                                                ?> value="all">All
                                                </option>
                                                <?php foreach ($productList as $key => $eachProduct): ?>
                                                    <option <?php
                                                    if (!empty($productId) && $productId == $eachProduct->product_id): echo "selected";
                                                    endif;
                                                    ?> value="<?php echo $eachProduct->product_id; ?>"><?php echo $eachProduct->productName ?>
                                                        [<?php echo $eachProduct->brandName ?>]
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From
                                            </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker form-control" id="start_date"
                                                   name="start_date" value="<?php
                                            if (!empty($start_date)) {
                                                echo $start_date;
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To
                                            </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker form-control" id="end_date"
                                                   name="end_date" value="<?php
                                            if (!empty($end_date)) {
                                                echo $end_date;
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label no-padding-right"
                                               for="form-field-1"></label>
                                        <div class="col-sm-5">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                Search
                                            </button>
                                        </div>
                                        <div class="col-sm-5">
                                            <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                                    style="cursor:pointer;">
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
                    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                    $productId11 = $this->input->post('productId');
                    ?>
                    <div class="row">
                        <div class="col-xs-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        <strong>Product Ledger Report: </strong>From <?php echo $start_date; ?>
                                        To <?php echo $end_date; ?>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <td align="center"><strong>Branch</strong></td>
                                    <td align="center"><strong>Date</strong></td>
                                    <td align="center"><strong>Voucher</strong></td>
                                    <td align="center"><strong>Customer/Supplier</strong></td>
                                    <td align="center"><strong>Product</strong></td>
                                    <td align="center"><strong>Stock In</strong></td>
                                    <td align="center"><strong>Stock Out</strong></td>
                                    <td align="center"><strong>Balance</strong></td>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $i = 0;
                                $totalIn = 0;
                                $totalOut = 0;
                                $totalOpenig = 0;
                                $brandNameArray = array();
                                foreach ($productLedger['purchase'] as $key => $eachInfo):
                                    $ttotalIn = 0;
                                    $ttotalOut = 0;
                                    if (!in_array($eachInfo->branch_name, $brandNameArray)) {
                                        array_push($brandNameArray, $eachInfo->branch_name); ?>
                                        <tr>
                                            <td colspan="8">
                                                <?php echo '<b>' . $eachInfo->branch_name . '</b>' ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php //echo $i = $i + 1; ?></td>
                                            <td><?php echo $eachInfo->invoice_date; ?></td>
                                            <td><?php echo $eachInfo->invoice_no; ?></td>
                                            <td>
                                                <?php
                                                if (!empty($eachInfo->customerName)):
                                                    echo $eachInfo->customerName;
                                                else:
                                                    echo $eachInfo->supName;
                                                endif;
                                                ?>
                                            </td>
                                            <td><?php echo $eachInfo->title . ' ' . $eachInfo->productName . ' [ ' . $eachInfo->brandName . ']'; ?></td>
                                            <td align="right">
                                                <?php
                                                echo $eachInfo->quantity;
                                                $ttotalIn = $eachInfo->quantity;
                                                $totalIn += $eachInfo->quantity;
                                                ?>
                                            </td>
                                            <td align="right">
                                                0
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo $ttotalIn - $ttotalOut;
                                                ?>
                                            </td>
                                        </tr>


                                    <?php } else {
                                        ?>
                                        <tr>
                                            <td><?php //echo $i = $i + 1; ?></td>
                                            <td><?php echo $eachInfo->invoice_date; ?></td>
                                            <td><?php echo $eachInfo->invoice_no; ?></td>
                                            <td>
                                                <?php
                                                if (!empty($eachInfo->customerName)):
                                                    echo $eachInfo->customerName;
                                                else:
                                                    echo $eachInfo->supName;
                                                endif;
                                                ?>
                                            </td>
                                            <td><?php echo $eachInfo->title . ' ' . $eachInfo->productName . ' [ ' . $eachInfo->brandName . ']'; ?></td>
                                            <td align="right">
                                                <?php
                                                echo $eachInfo->quantity;
                                                $ttotalIn = $eachInfo->quantity;
                                                $totalIn += $eachInfo->quantity;
                                                ?>
                                            </td>
                                            <td align="right">
                                                0
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo $ttotalIn - $ttotalOut;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } endforeach;
                                $brandNameArray = array();
                                foreach ($productLedger['sales'] as $key => $eachInfo):
                                    $ttotalIn = 0;
                                    $ttotalOut = 0;

                                    if (!in_array($eachInfo->branch_name, $brandNameArray)) {
                                        array_push($brandNameArray, $eachInfo->branch_name); ?>
                                        <tr>
                                            <td colspan="8">
                                                <?php echo '<b>' . $eachInfo->branch_name . '</b>' ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php //echo $i = $i + 1; ?></td>
                                            <td><?php echo $eachInfo->invoice_date; ?></td>
                                            <td><?php echo $eachInfo->invoice_no; ?></td>
                                            <td>
                                                <?php
                                                if (!empty($eachInfo->customerName)):
                                                    echo $eachInfo->customerName;
                                                else:
                                                    echo $eachInfo->customerName;
                                                endif;
                                                ?>
                                            </td>
                                            <td><?php echo $eachInfo->title . ' ' . $eachInfo->productName . ' [ ' . $eachInfo->brandName . ']'; ?></td>
                                            <td align="right">
                                                0
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo $eachInfo->quantity;
                                                $ttotalOut = $eachInfo->quantity;
                                                $totalOut += $eachInfo->quantity;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo $ttotalIn - $ttotalOut;
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td><?php //echo $i = $i + 1; ?></td>
                                            <td><?php echo $eachInfo->invoice_date; ?></td>
                                            <td><?php echo $eachInfo->invoice_no; ?></td>
                                            <td>
                                                <?php
                                                if (!empty($eachInfo->customerName)):
                                                    echo $eachInfo->customerName;
                                                else:
                                                    echo $eachInfo->customerName;
                                                endif;
                                                ?>
                                            </td>
                                            <td><?php echo $eachInfo->title . ' ' . $eachInfo->productName . ' [ ' . $eachInfo->brandName . ']'; ?></td>
                                            <td align="right">
                                                0
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo $eachInfo->quantity;
                                                $ttotalOut = $eachInfo->quantity;
                                                $totalOut += $eachInfo->quantity;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo $ttotalIn - $ttotalOut;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" align="right"><strong>Total Closing Stock (PCS)</strong></td>
                                    <td align="right"><strong><?php echo $totalIn; ?>&nbsp;</strong></td>
                                    <td align="right"><strong><?php echo $totalOut; ?>&nbsp;</strong></td>
                                    <td align="right"><strong><?php
                                            //
                                            echo ($totalIn) - $totalOut;
                                            ?>&nbsp;</strong></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
<!-- /.page-content -->
<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>
