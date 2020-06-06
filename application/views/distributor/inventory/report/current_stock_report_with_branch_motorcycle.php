<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 4/22/2020
 * Time: 9:33 AM
 */
?>

<?php
if (isset($_POST['start_date'])):
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $categoryId = $this->input->post('category_id');
    $model_id = $this->input->post('model_id');

    $brandId = $this->input->post('brandId');
    $productId = $this->input->post('productId');
endif;
?>

<?php
$ProductSubCategory = get_property_list_for_show_hide(6);
$Color = get_property_list_for_show_hide(7);
$Size = get_property_list_for_show_hide(8);


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

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="branch_id">
                                            <strong><?php echo get_phrase('Branch') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="branch_id" name="branch_id"
                                                        class="chosen-select form-control"

                                                        data-placeholder="Select Branch Name">
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

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="branch_id">
                                            <strong><?php echo get_phrase('Category') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="productCategory" onchange="checkDuplicateProduct()"
                                                        name="category_id" class="chosen-select form-control"
                                                        data-placeholder="Search product Category">
                                                    <option <?php
                                                    if (!empty($categoryId) && $categoryId == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
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
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="branch_id">
                                            <strong><?php echo get_phrase('Brand') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select name="brandId" class="chosen-select form-control supplierid"
                                                        id="form-field-select-3" data-placeholder="Search by Category">
                                                    <option <?php
                                                    if (!empty($brandId) && $brandId == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
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
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4"
                                     style="<?php echo $ProductSubCategory == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="model_id">
                                            <strong><?php echo get_phrase("Model") ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="subcategory" name="model_id"
                                                        class="chosen-select form-control"
                                                        data-placeholder="Search subcategory">
                                                    <option <?php
                                                    if ($model_id == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
                                                    </option>
                                                    <?php foreach ($model as $each_info): ?>
                                                        <option value="<?php echo $each_info->ModelID; ?>"><?php echo $each_info->Model; ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4"
                                     style="<?php echo $ProductSubCategory == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="branch_id">
                                            <strong><?php echo get_phrase($ProductSubCategory) ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="subcategory" name="subcategory"
                                                        class="chosen-select form-control"
                                                        data-placeholder="Search subcategory">
                                                    <option <?php
                                                    if ($subcategoryId == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
                                                    </option>
                                                    <?php foreach ($subcategory as $each_info): ?>
                                                        <option value="<?php echo $each_info->SubCatID; ?>"><?php echo $each_info->SubCatName; ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4"
                                     style="<?php echo $Color == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="branch_id">
                                            <strong><?php echo get_phrase($Color) ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="color" name="color" class="chosen-select form-control"
                                                        data-placeholder="Search Color">
                                                    <option <?php
                                                    if ($colorId == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
                                                    </option>
                                                    <?php foreach ($color as $each_info): ?>
                                                        <option value="<?php echo $each_info->ColorID; ?>"><?php echo $each_info->Color; ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4"
                                     style="<?php echo $Size == 'dont_have_this_property' ? 'display: none' : '' ?>">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="branch_id">
                                            <strong><?php echo get_phrase($Size) ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="size" name="size" class="chosen-select form-control"
                                                        data-placeholder="Search Size">
                                                    <option <?php
                                                    if ($sizeId == 'all') {
                                                        echo "selected";
                                                    }
                                                    ?> value="all">All
                                                    </option>
                                                    <?php foreach ($size as $each_info): ?>
                                                        <option value="<?php echo $each_info->SizeID; ?>"><?php echo $each_info->Size; ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="col-md-12">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="branch_id">
                                            <strong><?php echo get_phrase('Product') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
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

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                            <strong> <?php echo get_phrase('From') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input class="form-control date-picker" name="start_date"
                                                       id="start_date"
                                                       type="text"
                                                       value="<?php
                                                       if (!empty($start_date)) {
                                                           echo $start_date;
                                                       } else {
                                                           echo date('d-m-Y');
                                                       }
                                                       ?>" data-date-format="dd-mm-yyyy"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                             </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label formfonterp" for="form-field-1">
                                            <strong> <?php echo get_phrase('To') ?></strong></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="end_date"
                                                       type="text" name="end_date"
                                                       value="<?php
                                                       if (!empty($end_date)):
                                                           echo $end_date;
                                                       else:
                                                           echo date('d-m-Y');
                                                       endif;
                                                       ?>" data-date-format="dd-mm-yyyy"
                                                       autocomplete="off"/>
                                                <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                             </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-12">


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
                            <?php
                            $ttClogingValue = 0;
                            foreach ($stockBranch as $keyB => $stock) {
                                ?>
                                <table class="table table-striped table-bordered table-hover table-responsive">
                                    <thead>
                                    <tr>

                                        <td colspan="18"
                                            class="" style="text-align: center"><?php echo($keyB) ?></td>
                                    </tr>
                                    <tr>


                                        <td rowspan="2" style="text-align:center;"><strong>Category</strong></td>
                                        <td rowspan="2" style="text-align:center;"><strong>Brand</strong></td>
                                        <td rowspan="2" style="text-align:center;"><strong>Model</strong></td>

                                        <td rowspan="2" style="text-align:center;<?php echo $ProductSubCategory=='dont_have_this_property'?'display: none':''?>"><strong><?php echo $ProductSubCategory?></strong></td>

                                        <td rowspan="2" style="text-align:center;"><strong>Products</strong></td>
                                        <td rowspan="2" style="text-align:center;<?php echo $Size=='dont_have_this_property'?'display: none':''?>"><strong><?php echo $Size?></strong></td>
                                        <td rowspan="2" style="text-align:center;<?php echo $Color=='dont_have_this_property'?'display: none':''?>"><strong><?php echo $Color?></strong></td>

                                        <td colspan="3" style="text-align:center;"><strong>Opening Stock as on </strong>
                                        </td>

                                        <td colspan="3" style="text-align:center;"><strong>Purchase</strong></td>

                                        <td colspan="3" style="text-align:center;"><strong>Sales</strong></td>

                                        <td colspan="3" style="text-align:center;"><strong>Closing stock as on </strong>

                                        </td>


                                    </tr>

                                    <tr>

                                        <td align="center">Qty</td>

                                        <td align="center"> Rate</td>

                                        <td align="center">TK</td>

                                        <td align="center">Qty</td>

                                        <td align="center"> Rate</td>

                                        <td align="center">TK</td>

                                        <td align="center">Qty</td>

                                        <td align="center"> Rate</td>

                                        <td align="center">TK</td>

                                        <td align="center">Qty</td>

                                        <td align="center">Rate</td>

                                        <td align="center">TK</td>

                                    </tr>

                                    </thead>
                                    <tbody>

                                    <?php foreach ($stock as $key => $value) {
//if($value3->Opening_Qty >0 & $value3->Purchase_Qty>0 & $value3->Closing_Qty >0){
                                        ?>
                                        <tr>
                                            <td colspan="18" class="text-left"><b><b><?php echo $key ?></b></b></td>
                                        </tr>


                                        <?php
                                        foreach ($value as $keyBrand2 => $value2) { ?>

                                            <tr>
                                                <td></td>
                                                <td colspan="17"
                                                    class="text-left"><?php echo "&nbsp&nbsp&nbsp&nbsp" . $keyBrand2 ?></td>
                                            </tr>

                                            <?php
                                            foreach ($value2 as $keyModel3 => $value4) {

                                                ?>


                                                <tr>
                                                    <td></td>
                                                    <td colspan="17"
                                                        class="text-left"><?php echo "&nbsp&nbsp&nbsp&nbsp" . $keyModel3 ?></td>
                                                </tr>

                                                <?php

                                            foreach ($value4 as $key3 => $value3) { ?>
                                                <tr>
                                                    <td><?php ?></td>
                                                    <td><?php ?></td>

                                                    <td style="<?php echo $ProductSubCategory=='dont_have_this_property'?'display: none':''?>"><?php  echo $value3->SubCatName?></td>
                                                    <td><?php ?></td>
                                                    <td class="text-center"><?php echo $value3->productName . " " . $value3->unitTtile; ?></td>
                                                    <td style="<?php echo $Size=='dont_have_this_property'?'display: none':''?>"><?php  echo $value3->SizeName?></td>
                                                    <td style="<?php echo $Color=='dont_have_this_property'?'display: none':''?>"><?php  echo $value3->ColorName?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->OP_quantity); ?></td>

                                                    <td class="text-right"><?php echo numberFromatfloat($value3->OP_UPrice); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->OP_Amount); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->Pur_quantity); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->Pur_UPrice); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->Pur_Amount); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->S_quantity); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->S_UPrice); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->S_Amount); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->C_quantity); ?></td>
                                                    <td class="text-right"><?php echo numberFromatfloat($value3->C_UPrice); ?></td>
                                                    <td class="text-right">
                                                        <?php
                                                        echo numberFromatfloat($value3->C_Amount);
                                                        $ttClogingValue += $value3->C_Amount;
                                                        ?>
                                                    </td>

                                                </tr>


                                            <?php }
                                            }
                                            ?>


                                        <?php } ?>

                                    <?php }
                                    ?>

                                    <tr>
                                        <td colspan="17" class="text-right">Total</td>
                                        <td colspan=""
                                            class="text-right"><?php echo numberFromatfloat($ttClogingValue) ?></td>
                                    </tr>

                                    </tbody>
                                    <tfoot>

                                    </tfoot>

                                </table>

                                <?php
                            }
                            //}
                            ?>






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

