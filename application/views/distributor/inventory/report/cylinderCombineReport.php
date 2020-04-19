<?php
if (isset($_POST['start_date'])):
    $type_id = $this->input->post('type_id');
    $supplier_id = $this->input->post('supplier_id');
    $searchId = $this->input->post('searchId');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Cylinder Summary Report
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="form-field-1">Type</label>
                                        <div class="col-md-8">
                                            <select onchange="selectPayType(this.value)" class="form-control"
                                                    id="type_id" name="type_id" data-placeholder="Select Type">

                                                <option value="">-Select-</option>

                                                <option <?php
                                                if ($type_id == 2) {
                                                    echo "selected";
                                                }
                                                ?> value="2">Customer
                                                </option>
                                                <!--<option <?php
                                                /*                                                if ($type_id == 3) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                */ ?> value="3">Supplier
                                                </option>-->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="searchValue"></div>
                                    <div id="oldValue">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="form-field-1"> <span
                                                        style="color:red;">*</span>ID </label>
                                            <div class="col-md-8">
                                                <select id="productID" onchange="getProductPrice(this.value)"
                                                        class="chosen-select form-control" id="form-field-select-3"
                                                        data-placeholder="Search by Name">
                                                    <option value="all">All</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1">
                                            Product </label>
                                        <div class="col-md-8">
                                            <select name="productId" id="productID" class="chosen-select form-control"
                                                    id="form-field-select-3" data-placeholder="Search by Product"
                                                    style="width: 100%">
                                                <option value="all">All</option>
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

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1">
                                            From </label>
                                        <div class="col-md-8">
                                            <input type="text" class="date-picker form-control" id="start_date"
                                                   name="start_date" value="<?php
                                            if (!empty($from_date)) {
                                                echo date('d-m-Y', strtotime($from_date));
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format='dd-mm-yyyy' placeholder=" dd-mm-yyyy"
                                                   style="width:100%"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;To</label>
                                        <div class="col-md-8">
                                            <input type="text" class="date-picker form-control" id="end_date"
                                                   name="end_date" value="<?php
                                            if (!empty($to_date)):
                                                echo $to_date;
                                            else:
                                                echo date('d-m-Y');
                                            endif;
                                            ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"
                                                   style="width:100%"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label no-padding-right"
                                               for="form-field-1"></label>
                                        <div class="col-md-8">
                                            <button type="button" onclick="checkValidation()"
                                                    class="btn btn-success btn-sm">
                                                <span class="fa fa-search"></span> Search
                                            </button>


                                            <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                                    style="cursor:pointer;">

                                                <i class="fa fa-print"></i> Print
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

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        <strong>From <?php echo $from_date; ?> To <?php echo $to_date; ?></strong>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-bordered">
                                <?php

                                $topening = 0;
                                $tstockIn = 0;
                                $tstockOut = 0;
                                $ttAdvance = 0;
                                $ttDue = 0;
                                ?>
                                <thead>
                                <tr>
                                    <td align="center"><strong>SL</strong></td>
                                    <td align="center"><strong>Product</strong></td>
                                    <td align="center"><strong>Opening ( Pcs )</strong></td>
                                    <td align="center"><strong>Stock In ( Pcs )</strong></td>
                                    <td align="center"><strong>Stock Out ( Pcs )</strong></td>
                                    <td align="center"><strong>Advance ( Pcs )</strong></td>
                                    <td align="center"><strong>Due ( Pcs )</strong></td>
                                    <td align="center"><strong>Balance ( Pcs )</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $j = 1;
                                foreach ($dataresult as $key => $eachResult):
                                    ?>
                                    <tr>
                                        <td colspan="8"><b><?php echo $key ?></b></td>
                                    </tr>
                                    <?php
                                    foreach ($eachResult as $key2 => $eachResult2) {
                                        ?>

                                        <tr>
                                            <td></td>
                                            <td colspan="7"><b><?php echo $key2 ?></b></td>
                                        </tr>
                                        <?php
                                        foreach ($eachResult2 as $key3 => $eachResult3) {
                                            $advanceQty = 0;
                                            $dueQty = 0;

                                            $opening = ($eachResult3->sales_qty_op - $eachResult3->sales_return_qty_op) - ($eachResult3->in_qty_op - $eachResult3->out_qty_op) + ($eachResult3->cylinder_exchange_qty_op_in - $eachResult3->cylinder_exchange_qty_op_out);
                                            $topening = $topening + $opening;
                                            $in_qty = $eachResult3->sales_return_qty + $eachResult3->in_qty + $eachResult3->cylinder_exchange_qty_in;
                                            $tstockIn = $tstockIn + $in_qty;
                                            $out_qty = $eachResult3->sales_qty + $eachResult3->out_qty + $eachResult3->cylinder_exchange_qty_out;
                                            $tstockOut = $tstockOut + $out_qty;
                                            $ttQty = ($opening*1) + $in_qty - $out_qty;

                                            ?>
                                            <tr>
                                                <td><?php ''?></td>
                                                <td><?php echo '&nbsp;&nbsp' . $eachResult3->product_category . " " . $eachResult3->productName ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($opening) ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($in_qty) ?></td>
                                                <td class="text-right">
                                                    <?php echo numberFromatfloat($out_qty) ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                    $advanceQty = $ttQty;
                                                    if ($ttQty > 0) {
                                                        echo numberFromatfloat($ttQty);
                                                        $ttAdvance = $ttAdvance + $ttQty;
                                                    } else {
                                                        echo 0;
                                                    }


                                                    ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                    $dueQty = $ttQty;
                                                    if ($ttQty < 0) {
                                                        $ttDue = $ttQty + $ttDue;
                                                        echo numberFromatfloat((-1 * $ttQty));


                                                    } else {
                                                        echo 0;
                                                    }

                                                    ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                    if ($ttQty < 0) {
                                                        echo numberFromatfloat((-1 * $ttQty)) . ' Due';
                                                    } else {
                                                        echo numberFromatfloat($ttQty) . ' Advance';
                                                    }


                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                endforeach;
                                ?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2" align="right"><strong>Total</strong></td>
                                    <td align="right"><?php echo numberFromatfloat($topening) ?></td>
                                    <td align="right"><?php echo numberFromatfloat($tstockIn) ?></td>
                                    <td align="right"><?php echo numberFromatfloat($tstockOut) ?></td>
                                    <td align="right"><?php

                                        echo $ttAdvance

                                        ?></td>
                                    <td align="right"><?php echo '( ' . numberFromatfloat(($ttDue * -1)) . ' )' ?></td>
                                    <td align="right">
                                        <?php
                                        echo '( ' . numberFromatfloat(($ttDue * -1)) . ' )'
                                        ?>
                                    </td>
                                </tr>
                                </tfoot>


                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
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
        data: {
            'payid': '<?php echo $type_id; ?>',
            'searchId': '<?php echo $searchId; ?>',
            'supplierId': '<?php echo $supplier_id; ?>'
        },
        success: function (data) {
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
            success: function (data) {
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
<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>