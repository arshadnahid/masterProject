<?php
if (isset($_POST['start_date'])):
    $type_id = $this->input->post('type_id');
    $supplier_id = $this->input->post('supplier_id');
    $searchId = $this->input->post('searchId');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>
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
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right" for="form-field-1">Type</label>
                                        <div class="col-md-9">
                                            <select style="width:150%"  onchange="selectPayType(this.value)" class="form-control"  id="type_id" name="type_id"   data-placeholder="Select Type">

                                                <option value="">-Select-</option>

                                                <option <?php
                                                if ($type_id == 2) {
                                                    echo "selected";
                                                }
                                                ?> value="2">Customer</option>
                                                <option <?php
                                                if ($type_id == 3) {
                                                    echo "selected";
                                                }
                                                ?>  value="3">Supplier </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div id="searchValue"></div>
                                    <div id="oldValue">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label no-padding-right" for="form-field-1"> ID <span style="color:red;">*</span></label>
                                            <div class="col-md-10">
                                                <select  id="productID" onchange="getProductPrice(this.value)" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Name">
                                                    <option value="all">All</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label no-padding-right" for="form-field-1"> Product </label>
                                        <div class="col-md-9">
                                            <select name="productId" id="productID"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product" style="width: 100%">
                                                <option value="all">All</option>
                                                <?php foreach ($productList as $key => $eachProduct): ?>
                                                    <option <?php
                                                    if (!empty($productId) && $productId == $eachProduct->product_id): echo "selected";
                                                    endif;
                                                    ?> value="<?php echo $eachProduct->product_id; ?>"><?php echo $eachProduct->productName ?>[<?php echo $eachProduct->brandName ?>]</option>
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
                                        <button type="button" onclick="checkValidation()" class="btn btn-success btn-sm">
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
                            if (!empty($dataresult) && $productId == 'all'):
                                $topening = 0;
                                $tstockIn = 0;
                                $tstockOut = 0;
                                ?>
                                <thead>
                                    <tr>
                                        <td align="center"><strong>SL</strong></td>
                                        <td align="center"><strong>Product</strong></td>
                                        <td align="center"><strong>Opening ( Pcs )</strong></td>
                                        <td align="center"><strong>Stock In ( Pcs )</strong></td>
                                        <td align="center"><strong>Stock Out ( Pcs )</strong></td>
                                        <td align="center"><strong>Balance ( Pcs )</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $j = 1;
                                    foreach ($dataresult as $key => $eachResult):
                                        $topening+= $opening = $eachResult->opStockIn - $eachResult->opStockOut;
                                        $tstockIn+= $stockIn = $eachResult->currentStockIn;
                                        $tstockOut+= $stockOut = $eachResult->currentStockOut;
                                        if (($opening >= 1) || ($stockIn > 0) || ($stockOut > 0)):
                                            ?>
                                            <tr>
                                                <td><?php echo $j++; ?></td>
                                                <td><?php echo $eachResult->productName . ' [ ' . $eachResult->brandName . ' ]'; ?></td>
                                                <td align="right"> <?php echo $opening; ?> </td>
                                                <td align="right"><?php echo $stockIn; ?></td>
                                                <td align="right"><?php echo $stockOut; ?></td>
                                                <td align="right"><?php echo ($opening + $stockIn) - $stockOut; ?></td>
                                            </tr>
                                            <?php
                                        // die;
                                        endif;
                                    endforeach;
                                    ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Total</strong></td>
                                        <td align="right"><?php echo $topening ?></td>
                                        <td align="right"><?php echo $tstockIn ?></td>
                                        <td align="right"><?php echo $tstockOut ?></td>
                                        <td align="right"><?php echo ($topening + $tstockIn) - $tstockOut ?></td>
                                    </tr>
                                </tfoot>

                                <?php
                            else:
                                ?>
                                <thead>
                                    <tr>
                                        <td align="center"><strong>SL</strong></td>
                                        <td align="center"><strong>Product</strong></td>
                                        <td align="center"><strong>Opening ( Pcs )</strong></td>
                                        <td align="center"><strong>Stock In ( Pcs )</strong></td>
                                        <td align="center"><strong>Stock Out ( Pcs )</strong></td>
                                        <td align="center"><strong>Balance ( Pcs )</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $j = 1;
                                    foreach ($dataresult as $key => $eachResult):
                                        $topening+= $opening = $eachResult->opStockIn - $eachResult->opStockOut;
                                        $tstockIn+= $stockIn = $eachResult->currentStockIn;
                                        $tstockOut+= $stockOut = $eachResult->currentStockOut;
                                        if (($opening >= 1) || ($stockIn > 0) || ($stockOut > 0)):
                                            ?>
                                            <tr>
                                                <td><?php echo $j++; ?></td>
                                                <td><?php echo $eachResult->productName . ' [ ' . $eachResult->brandName . ' ]'; ?></td>
                                                <td align="right"> <?php echo $opening; ?> </td>
                                                <td align="right"><?php echo $stockIn; ?></td>
                                                <td align="right"><?php echo $stockOut; ?></td>
                                                <td align="right"><?php echo ($opening + $stockIn) - $stockOut; ?></td>
                                            </tr>
                                            <?php
                                        endif;
                                    endforeach;
                                    ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Total</strong></td>
                                        <td align="right"><?php echo $topening ?></td>
                                        <td align="right"><?php echo $tstockIn ?></td>
                                        <td align="right"><?php echo $tstockOut ?></td>
                                        <td align="right"><?php echo ($topening + $tstockIn) - $tstockOut ?></td>
                                    </tr>
                                </tfoot>
                            <?php
                            endif;
                            ?>

                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>
    function checkValidation() {
        var type_id = $("#type_id").val();
        var userId = $("#userId").val();
        if (type_id == '') {
            swal("Select Customer or Supplier Type!", "Validation Error!", "error");
        } else if (userId == '' || typeof userId == 'undefined') {
            swal("Select Customer or Supplier ID!", "Validation Error!", "error");
        } else {
            $("#publicForm").submit();
        }
    }
</script>
<script>
<?php if (!empty($type_id)):
    ?>
        var url = '<?php echo site_url("FinaneController/cylinderCombine") ?>';
    <?php if ($type_id != 'all'): ?>
            $.ajax({
                type: 'POST',
                url: url,
                data: {'payid': '<?php echo $type_id; ?>', 'searchId': '<?php echo $searchId; ?>', 'supplierId': '<?php echo $supplier_id; ?>'},
                success: function (data)
                {
                    $(".chosenRefesh").trigger("chosen:updated");
                    $("#searchValue").show(1000);
                    $("#searchValue").html(data);
                    $("#oldValue").hide(1000);
                    $('.chosenRefesh').chosen();
                    $(".chosenRefesh").trigger("chosen:updated");
                }
            });
    <?php else: ?>
            $("#searchValue").hide(1000);
            $("#oldValue").show(1000);

    <?php
    endif;
endif;
?>
    function selectPayType(payid) {
        var url = '<?php echo site_url("FinaneController/cylinderCombine") ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {'payid': payid},
            success: function (data)
            {
                $(".chosenRefesh").trigger("chosen:updated");
                $("#searchValue").show(1000);
                $("#searchValue").html(data);
                $("#oldValue").hide(1000);
                $('.chosenRefesh').chosen();
                $(".chosenRefesh").trigger("chosen:updated");
            }
        });
    }
</script>