<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $categoryId = $this->input->post('category_id');

    $brandId = $this->input->post('brandId');
    $productId = $this->input->post('productId');
endif;
?>
<div class="row">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Stock Report
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12 noPrint">
                        <form id="publicForm" action="" method="post" class="form-horizontal">

                            <div class="col-md-12">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right"
                                               for="form-field-1"><?php echo get_phrase('Category') ?></label>
                                        <div class="col-sm-6">
                                            <select id="productCategory" onchange="checkDuplicateProduct()"
                                                    name="category_id" class="chosen-select form-control"
                                                    data-placeholder="Search product Category">
                                                <option <?php
                                                if (!empty($categoryId) && $categoryId == 'All') {
                                                    echo "selected";
                                                }
                                                ?> value="All">All
                                                </option>
                                                <?php foreach ($productCat as $each_info): ?>
                                                    <option <?php
                                                    if (!empty($categoryId) && $categoryId == $each_info->category_id) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $each_info->category_id; ?>"><?php echo $each_info->title; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            Brand</label>
                                        <div class="col-sm-8">
                                            <select name="brandId" class="chosen-select form-control supplierid"
                                                    id="form-field-select-3" data-placeholder="Search by Category">
                                                <option <?php
                                                if (!empty($brandId) && $brandId == 'All') {
                                                    echo "selected";
                                                }
                                                ?> value="All">All
                                                </option>
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
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Product
                                        </label>

                                        <div class="col-sm-9">

                                            <select name="productId" class="chosen-select form-control supplierid"
                                                    id="form-field-select-3" data-placeholder="Search by Product">


                                                <option <?php
                                                if ($productId == 'All') {
                                                    echo "selected";
                                                }
                                                ?> value="All">All
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

                            </div>
                            <div class="col-md-12">


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            From :
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
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                            To</label>
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
                                <div class="col-md-3">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                                style="cursor:pointer;">
                                            <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                            Print
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <?php
                if (isset($_POST['start_date'])):
                    $dr = 0;
                    $cr = 0;
                    ?>
                    <div class="row">
                        <div class="col-xs-12">


                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo rtrim($companyInfo->address); ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        : From <?php echo $start_date; ?> To <?php echo $end_date; ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>

                                <tr>


                                    <td rowspan="2" style="text-align:center;"><strong>Category</strong></td>

                                    <td rowspan="2" style="text-align:center;"><strong>Products</strong></td>


                                    <td colspan="1" style="text-align:center;"><strong>Opening Stock as on </strong>
                                    </td>

                                    <td colspan="1" style="text-align:center;"><strong>Purchase</strong></td>

                                    <td colspan="1" style="text-align:center;"><strong>Sales</strong></td>

                                    <td colspan="1" style="text-align:center;"><strong>Closing stock as on </strong>

                                    </td>


                                </tr>

                                <tr>

                                    <td align="center">Qty</td>



                                    <td align="center">Qty</td>


                                    <td align="center">Qty</td>



                                    <td align="center">Qty</td>




                                </tr>

                                </thead>
                                <tbody>
                                <?php
                                $ttClogingValue=0;
                                foreach ($stock as $key => $value) {
                                    //if($value3->Opening_Qty >0 & $value3->Purchase_Qty>0 & $value3->Closing_Qty >0){
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-left"><b><b><?php echo $key ?></b></b></td>
                                    </tr>


                                    <?php
                                    foreach ($value as $key2 => $value2) { ?>

                                        <tr>
                                            <td colspan="6"
                                                class="text-left"><?php echo "&nbsp&nbsp&nbsp&nbsp" . $key2 ?></td>
                                        </tr>

                                        <?php
                                        foreach ($value2 as $key3 => $value3) { ?>
                                            <tr>
                                                <td><?php ?></td>
                                                <td class="text-center"><?php echo $value3->productName . " " . $value3->unitTtile; ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->OP_quantity); ?></td>




                                                <td class="text-right"><?php echo numberFromatfloat($value3->Pur_quantity); ?></td>



                                                <td class="text-right"><?php echo numberFromatfloat($value3->S_quantity); ?></td>



                                                <td class="text-right"><?php echo numberFromatfloat($value3->C_quantity); ?></td>






                                            </tr>


                                        <?php } ?>


                                    <?php } ?>

                                <?php }
                                //}
                                ?>
                                <tr>
                                    <td colspan="5" class="text-right">Total</td>
                                    <td colspan="" class="text-right"><?php echo numberFromatfloat($ttClogingValue)?></td>
                                </tr>

                                </tbody>
                                <tfoot>

                                </tfoot>

                            </table>
                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <td colspan="14">
                                        Empty Cylinder With Refill Stock
                                    </td>
                                </tr>
                                <tr>


                                    <td rowspan="2" style="text-align:center;"><strong>Category</strong></td>

                                    <td rowspan="2" style="text-align:center;"><strong>Products</strong></td>


                                    <td colspan="1" style="text-align:center;"><strong>Opening Stock as on </strong>
                                    </td>

                                    <td colspan="1" style="text-align:center;"><strong>Purchase</strong></td>

                                    <td colspan="1" style="text-align:center;"><strong>Sales</strong></td>

                                    <td colspan="1" style="text-align:center;"><strong>Closing stock as on </strong>

                                    </td>


                                </tr>

                         <tr>

                                    <td align="center">Qty</td>



                                    <td align="center">Qty</td>



                                    <td align="center">Qty</td>


                                    <td align="center">Qty</td>



                                </tr>

                                </thead>
                                <tbody>
                                <?php
                                $ttClogingEmptyValue=0;
                                foreach ($stockEmptyCylinder as $key => $value) {
                                    //if($value3->Opening_Qty >0 & $value3->Purchase_Qty>0 & $value3->Closing_Qty >0){
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-left"><b><b><?php echo $key ?></b></b></td>
                                    </tr>


                                    <?php
                                    foreach ($value as $key2 => $value2) { ?>

                                        <tr>
                                            <td colspan="6"
                                                class="text-left"><?php echo "&nbsp&nbsp&nbsp&nbsp" . $key2 ?></td>
                                        </tr>

                                        <?php
                                        foreach ($value2 as $key3 => $value3) { ?>
                                            <tr>
                                                <td><?php ?></td>
                                                <td class="text-center"><?php echo $value3->productName . " " . $value3->unitTtile; ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->OP_quantity_refill); ?></td>


                                                <td class="text-right"><?php echo numberFromatfloat($value3->Pur_quantity_refill); ?></td>


                                                <td class="text-right"><?php echo numberFromatfloat($value3->S_quantity_refill); ?></td>


                                                <td class="text-right"><?php echo numberFromatfloat($value3->C_quantity_refill); ?></td>




                                            </tr>


                                        <?php } ?>

                                    <?php } ?>

                                <?php }
                                //}
                                ?>

                                <tr>
                                    <td colspan="5" class="text-right">Total</td>
                                    <td colspan="" class="text-right"><?php echo numberFromatfloat($ttClogingEmptyValue)?></td>
                                </tr>
                                </tbody>
                                <tfoot>

                                </tfoot>

                            </table>



                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <td colspan="14"  style="text-align:center;">
                                        <strong>Empty Cylinder Stock</strong>
                                    </td>
                                </tr>
                                <tr>


                                    <td rowspan="2" style="text-align:center;"><strong>Category</strong></td>

                                    <td rowspan="2" style="text-align:center;"><strong>Products</strong></td>


                                    <td colspan="1" style="text-align:center;"><strong>Opening Stock as on </strong>
                                    </td>

                                    <td colspan="1" style="text-align:center;"><strong>Purchase</strong></td>

                                    <td colspan="1" style="text-align:center;"><strong>Sales</strong></td>

                                    <td colspan="1" style="text-align:center;"><strong>Closing stock as on </strong>

                                    </td>


                                </tr>

                                <tr>

                                    <td align="center">Qty</td>



                                    <td align="center">Qty</td>



                                    <td align="center">Qty</td>



                                    <td align="center">Qty</td>



                                </tr>

                                </thead>
                                <tbody>
                                <?php
                                $ttClogingEmptyValue=0;
                                foreach ($stockEmptyCylinder as $key => $value) {
                                    //if($value3->Opening_Qty >0 & $value3->Purchase_Qty>0 & $value3->Closing_Qty >0){
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-left"><b><b><?php echo $key ?></b></b></td>
                                    </tr>


                                    <?php
                                    foreach ($value as $key2 => $value2) { ?>

                                        <tr>
                                            <td colspan="6"
                                                class="text-left"><?php echo "&nbsp&nbsp&nbsp&nbsp" . $key2 ?></td>
                                        </tr>

                                        <?php
                                        foreach ($value2 as $key3 => $value3) {
                                           $qtyOP= $value3->OP_quantity-$value3->OP_quantity_refill;
                                           $qtyPur= $value3->Pur_Qty-$value3->Pur_quantity_refill;
                                           $qtySales= $value3->Sales_Qty-$value3->S_quantity_refill;
                                           $qtyClosing= $value3->Closing_Qnty-$value3->C_quantity_refill;
                                            ?>
                                            <tr>
                                                <td><?php ?></td>
                                                <td class="text-center"><?php echo $value3->productName . " " . $value3->unitTtile; ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($qtyOP); ?></td>

                                                 <td class="text-right"><?php echo numberFromatfloat($qtyPur); ?></td>


                                                <td class="text-right"><?php echo numberFromatfloat($qtySales); ?></td>


                                                <td class="text-right"><?php echo numberFromatfloat($qtyClosing); ?></td>




                                            </tr>


                                        <?php } ?>

                                    <?php } ?>

                                <?php }
                                //}
                                ?>

                                <tr>
                                    <td colspan="5" class="text-right">Total</td>
                                    <td colspan="" class="text-right"><?php echo numberFromatfloat($ttClogingEmptyValue)?></td>
                                </tr>
                                </tbody>
                                <tfoot>

                                </tfoot>

                            </table>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>
