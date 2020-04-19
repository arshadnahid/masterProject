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
                                            Product</label>

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
                                <?php
                                $ttClogingValue=0;
                                foreach ($stock as $key => $value) {
                                    //if($value3->Opening_Qty >0 & $value3->Purchase_Qty>0 & $value3->Closing_Qty >0){
                                    ?>
                                    <tr>
                                        <td colspan="14" class="text-left"><b><b><?php echo $key ?></b></b></td>
                                    </tr>


                                    <?php
                                    foreach ($value as $key2 => $value2) { ?>

                                        <tr>
                                            <td colspan="14"
                                                class="text-left"><?php echo "&nbsp&nbsp&nbsp&nbsp" . $key2 ?></td>
                                        </tr>

                                        <?php
                                        foreach ($value2 as $key3 => $value3) { ?>
                                            <tr>
                                                <td><?php ?></td>
                                                <td class="text-center"><?php echo $value3->productName . " " . $value3->unitTtile; ?></td>
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
                                                    $ttClogingValue+=$value3->C_Amount;
                                                    ?>
                                                </td>

                                            </tr>


                                        <?php } ?>


                                    <?php } ?>

                                <?php }
                                //}
                                ?>
                                <tr>
                                    <td colspan="13" class="text-right">Total</td>
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
                                <?php
                                $ttClogingEmptyValue=0;
                                foreach ($stockEmptyCylinder as $key => $value) {
                                    //if($value3->Opening_Qty >0 & $value3->Purchase_Qty>0 & $value3->Closing_Qty >0){
                                    ?>
                                    <tr>
                                        <td colspan="14" class="text-left"><b><b><?php echo $key ?></b></b></td>
                                    </tr>


                                    <?php
                                    foreach ($value as $key2 => $value2) { ?>

                                        <tr>
                                            <td colspan="14"
                                                class="text-left"><?php echo "&nbsp&nbsp&nbsp&nbsp" . $key2 ?></td>
                                        </tr>

                                        <?php
                                        foreach ($value2 as $key3 => $value3) { ?>
                                            <tr>
                                                <td><?php ?></td>
                                                <td class="text-center"><?php echo $value3->productName . " " . $value3->unitTtile; ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->OP_quantity); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->OP_UPrice); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->OP_Amount); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Pur_Qty); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Pur_UPrice); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Pur_Amount); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Sales_Qty); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Sales_UPrice); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Sales_Amount); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Closing_Qnty); ?></td>
                                                <td class="text-right"><?php echo numberFromatfloat($value3->Closing_UPrice ); ?></td>
                                                <td class="text-right">
                                                    <?php
                                                    $ttClogingEmptyValue+=$value3->Closing_Amount;
                                                    echo numberFromatfloat($value3->Closing_Amount );
                                                    ?>
                                                </td>

                                            </tr>


                                        <?php } ?>

                                    <?php } ?>

                                <?php }
                                //}
                                ?>

                                <tr>
                                    <td colspan="13" class="text-right">Total</td>
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
<!--SELECT  product_id,  productName,  category_id, title, unit_id,  unitTtile , brand_id, brandName,
/*Opening*/
IFNULL(IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0),0) Opening_Qty,
IFNULL(((IFNULL(quantity_Open,0) * IFNULL(U_Price_opening,0)) - (IFNULL(quantity_OpenRe,0) * IFNULL(U_Price_openingRe,0)))
/ NULLIF((IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0)),0),0 ) Opening_U_Price,

IFNULL((IFNULL(IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0),0)) *
IFNULL(((IFNULL(quantity_Open,0) * IFNULL(U_Price_opening,0)) - (IFNULL(quantity_OpenRe,0) * IFNULL(U_Price_openingRe,0)))
/ NULLIF((IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0)),0),0 ),0) Opening_Amonut,
/*END Opening*/

/*--Purchase_In*/
IFNULL(IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0),0) Purchase_Qty,
IFNULL(((IFNULL(quantity_P,0) * IFNULL(U_Price_Purchase_In,0)) - (IFNULL(quantity_PRe,0) * IFNULL(U_Price_PRe,0)))
/ NULLIF((IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0)),0),0 ) Purchase_U_Price,

IFNULL((IFNULL(IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0),0)) *
IFNULL(((IFNULL(quantity_P,0) * IFNULL(U_Price_Purchase_In,0)) - (IFNULL(quantity_PRe,0) * IFNULL(U_Price_PRe,0)))
/ NULLIF((IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0)),0),0 ),0) Purchase_Amount,
/*END Purchase_In*/


/*Sales_Out*/
IFNULL(IFNULL(sales_P,0) + IFNULL(sales_SRe,0),0) Sales_Qty,

IFNULL(((IFNULL(sales_P,0) * IFNULL(U_Price_sales_out,0)) + (IFNULL(sales_SRe,0) * IFNULL(U_Price_sales_SRe,0)))
/ NULLIF((IFNULL(sales_P,0) + IFNULL(sales_SRe,0)),0),0 ) Sales_U_Price,

IFNULL((IFNULL(IFNULL(sales_P,0) + IFNULL(sales_SRe,0),0)) *
IFNULL(((IFNULL(sales_P,0) * IFNULL(U_Price_sales_out,0)) + (IFNULL(sales_SRe,0) * IFNULL(U_Price_sales_SRe,0)))
/ NULLIF((IFNULL(sales_P,0) + IFNULL(sales_SRe,0)),0),0 ),0) Sales_Amount,
/*END Sales_Out*/
/*Closing*/
IFNULL((Closing_Qty+Closing_QtyRe),0) Closing_Qty,

IFNULL(((Closing_Qty*Closing_U_price)+(Closing_QtyRe*Closing_U_price_Re))/  NULLIF((Closing_Qty+Closing_QtyRe),0),0) Closing_U_Price,
IFNULL((Closing_Qty+Closing_QtyRe)* (((Closing_Qty*Closing_U_price)+(Closing_QtyRe*Closing_U_price_Re))/  NULLIF((Closing_Qty+Closing_QtyRe),0)),0) Closing_Amount
/*END Closing*/
FROM

(

SELECT
BaseT.product_id, BaseT.productName, BaseT.category_id,productcategory.title,BaseT.unit_id, unit.unitTtile ,BaseT.brand_id,brand.brandName,
/*Opening*/
IFNULL(Opening.quantity_Open,0) quantity_Open ,
IFNULL(Opening.U_Price_opening,0) U_Price_opening,
IFNULL(IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0),0) Amount_opening,

IFNULL(Ret_Opening.quantity_OpenRe,0) quantity_OpenRe ,
IFNULL(Ret_Opening.U_Price_openingRe,0) U_Price_openingRe,
IFNULL(IFNULL(Ret_Opening.quantity_OpenRe,0)*
IFNULL(Ret_Opening.U_Price_openingRe,0),0) Amount_U_Price_openingRe,
/*END Opening*/

/*Purchase_In*/

IFNULL(Purchase_In.quantity_P,0) quantity_P,

IFNULL(Purchase_In.U_Price_Purchase_In,0) U_Price_Purchase_In,

IFNULL(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0),0) Amount_Purchase_In ,

IFNULL(Ret_Purchase.quantity_PRe ,0) quantity_PRe,
IFNULL(Ret_Purchase.U_Price_PRe,0) U_Price_PRe,

IFNULL(IFNULL(Ret_Purchase.quantity_PRe,0) *
IFNULL(Ret_Purchase.U_Price_PRe,0),0) Amount_Purchase_In_Re  ,

/*END Purchase_In*/

/*sales_out*/

IFNULL(sales_out.sales_P,0) sales_P ,
IFNULL(sales_out.U_Price_sales_out,0) U_Price_sales_out,

IFNULL(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0),0) Amount_sales_out,


IFNULL(Ret_Sales.sales_SRe ,0) sales_SRe ,
IFNULL(Ret_Sales.U_Price_sales_SRe,0) U_Price_sales_SRe,

IFNULL(IFNULL(Ret_Sales.sales_SRe ,0) *
IFNULL(Ret_Sales.U_Price_sales_SRe,0),0) Amount_sales_SRe

/*END sales_out*/

/*Closing*/
, IFNULL((IFNULL(Opening.quantity_Open,0)+IFNULL(Purchase_In.quantity_P,0)-IFNULL(sales_out.sales_P,0)),0) AS Closing_Qty

,IFNULL( (((IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0))+(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0) )-(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0))) )/
NULLIF((IFNULL((IFNULL(Opening.quantity_Open,0)+IFNULL(Purchase_In.quantity_P,0)-IFNULL(sales_out.sales_P,0)),0)),0),0) AS Closing_U_price
,
IFNULL(((IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0))+(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0) )-(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0))),0)  Amount_Closing


,IFNULL((IFNULL(Ret_Opening.quantity_OpenRe,0)+IFNULL(Ret_Purchase.quantity_PRe,0)-IFNULL(Ret_Sales.sales_SRe,0)),0) AS Closing_QtyRe


,IFNULL( IFNULL( (IFNULL(Ret_Opening.quantity_OpenRe,0) * IFNULL(Ret_Opening.U_Price_openingRe,0)) +
(IFNULL(Ret_Purchase.quantity_PRe,0) * IFNULL(Ret_Purchase.U_Price_PRe,0) )-
(IFNULL(Ret_Sales.sales_SRe,0) * IFNULL(Ret_Sales.U_Price_sales_SRe,0)),0) /
NULLIF((IFNULL(Ret_Opening.quantity_OpenRe,0)+IFNULL(Ret_Purchase.quantity_PRe,0)-IFNULL(Ret_Sales.sales_SRe,0)),0),0) AS Closing_U_price_Re

,
IFNULL(((IFNULL(Ret_Opening.quantity_OpenRe,0)*
IFNULL(Ret_Opening.U_Price_openingRe,0))+(IFNULL(Ret_Opening.quantity_OpenRe,0) *
IFNULL(Ret_Purchase.U_Price_PRe,0) )-(IFNULL(Ret_Sales.sales_SRe,0) *
IFNULL(Ret_Sales.U_Price_sales_SRe,0))),0)  Amount_Closing_Re


/*END Closing*/
FROM
(
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
FROM  product LEFT OUTER JOIN

(SELECT product_id  FROM   purchase_details
union
SELECT product_id FROM   purchase_return_details
)  T2 ON product.product_id=T2.product_id  WHERE product.category_id=1) BaseT LEFT OUTER JOIN


productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN

/*Purchase_In*/

(SELECT
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P,
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_Purchase_In

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'
AND purchase_invoice_info.invoice_date >= '2020-01-05'
AND purchase_invoice_info.invoice_date <= '2020-01-08'
AND purchase_invoice_info.is_opening=0
AND purchase_details.is_package=0


GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open,
IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening /*--,*/



FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id /*--LEFT OUTER JOIN*/

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'
AND purchase_invoice_info.invoice_date < '2020-01-05'
AND purchase_details.is_package=0
GROUP BY   purchase_details.product_id  ) Opening
ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN

/*END Opening*/

/*sales_out*/

(SELECT
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_P,

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out /* --,*/


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'
AND sales_invoice_info.invoice_date >= '2020-01-05'
AND sales_invoice_info.invoice_date <= '2020-01-08'
AND sales_details.is_package=0


GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT
purchase_return_details.product_id ,

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  ,
IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
/  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id
AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id



WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N'
AND purchase_invoice_info.invoice_date >= '2020-01-05'
AND purchase_invoice_info.invoice_date <= '2020-01-08'  AND purchase_details.is_opening=0 AND purchase_details.is_package=0

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT
purchase_return_details.product_id ,

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
/ NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe


FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id

AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N'
AND purchase_invoice_info.invoice_date < '2020-01-05'
AND purchase_details.is_package=0


GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
/NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id
AND sales_details.sales_details_id = sales_return_details.sales_details_id



WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  /*--purchase_invoice_info.branch_id= AND */
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N'
AND sales_invoice_info.invoice_date >= '2020-01-05'
AND sales_invoice_info.invoice_date <= '2020-01-08'
AND sales_details.is_package=0

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id
/*END ReturnSales*/

) AllOPSC  WHERE 1=1    AND 1=1  AND 1=1  AND 1=1  ORDER BY title,brandName,productName-->

<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>
