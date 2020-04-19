<?php
if (isset($_POST['start_date'])):
    $branch_id = $this->input->post('branch_id');
    $cusType = $this->input->post('cusType');
    $productId = $this->input->post('productId');
    $start_date = $this->input->post('start_date');
    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
    $end_date = $this->input->post('end_date');
    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Current Stock Report') ?> </div>

            </div>

            <div class="portlet-body">

                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm" action="" method="post" class="form-horizontal">

                            <div class="col-sm-12">
                                <div style="background-color: grey!important;">
                                    <div class="col-md-2">

                                        <div class="form-group">

                                            <label class="col-sm-3 control-label no-padding-right" for="BranchAutoId">
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
                                    <div class="col-md-2">

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
                                                From Date</label>

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
                                                To Date</label>

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

                </div>
                <?php
                if (!empty($allStock)):
                    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to_date = date('Y-m-d', strtotime(date($end_date)));
                    ?>
                    <div class="row">
                        <div class="col-xs-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong> <?php echo get_phrase('Phone') ?>
                                            : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong> <?php echo get_phrase('Email') ?>
                                            : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong> <?php echo get_phrase('Website') ?>
                                            : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-bordered" id="ALLPRODUCT">
                                <?php
                                //all supplier all customer
                                $topening = 0;
                                $tstockIn = 0;
                                $tstockOut = 0;
                                ?>
                                <thead>
                                <tr>

                                    <td align="center"><strong><?php echo get_phrase('Sl') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Product Name') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Opening Qty') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Purchase Qty') ?></strong></td>
                                    <td align="center"><strong><?php echo get_phrase('Sales Qty') ?></strong></td>
                                    <td align="center"><strong>Balance</strong></td>
                                    <td align="center"><strong>Price</strong></td>
                                    <td align="center"><strong>Total Price</strong></td>

                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                $this->db->select("*,productcategory.title,brand.brandName");
                                $this->db->from("product");
                                $this->db->join('productcategory', 'productcategory.category_id=product.category_id', 'left');
                                $this->db->join('brand', 'brand.brandId=product.brand_id', 'left');
                                $this->db->where('status', 1);
                                if ($productId != 'all') {
                                    $this->db->where('product_id', $productId);
                                }
                                $result = $this->db->get()->result();
                                $this->db->select("*");
                                $this->db->from("branch");
                                $this->db->where('is_active', 1);
                                if ($branch_id != 'all') {
                                    $this->db->where('branch_id', $branch_id);
                                }
                                $result_branch = $this->db->get()->result();
                                foreach ($result_branch as $key => $eachResult) {


                                    $query = "SELECT
	product.product_id,
	CONCAT(productcategory.title,' ',	product.productName,' [ ',brand.brandName,' ]')AS productName,
	IFNULL(purchase_qty.pur_qty,0) as purchase_qty,
purchase_qty.branch_name,
IFNULL(sales_qty.sales_qty,0) as sales_qty,
sales_qty.branch_name as branch_name,
IFNULL(purchase_return_details.return_empty_qty,0) as return_empty_qty,
IFNULL(sales_return_details.return_empty_qty,0) AS sales_return_empty_qty,
(IFNULL(purchase_qty.pur_qty,0)-IFNULL(sales_qty.sales_qty,0)-IFNULL(purchase_return_details.return_empty_qty,0)+IFNULL(sales_return_details.return_empty_qty,0)) as balance

FROM
	product
LEFT JOIN productcategory ON productcategory.category_id = product.category_id
LEFT JOIN unit ON unit.unit_id = product.unit_id
LEFT JOIN brand ON brand.brandId = product.brand_id
LEFT JOIN(
	SELECT
		IFNULL(SUM(purchase_details.quantity),0)AS pur_qty,
		purchase_details.product_id,
    branch.branch_name
	FROM
		purchase_details
left JOIN purchase_invoice_info pii ON pii.purchase_invoice_id=purchase_details.purchase_invoice_id
LEFT JOIN branch on branch.branch_id=pii.branch_id
	WHERE
		purchase_details.is_active = 'Y'
  AND branch.is_active='1'
	AND purchase_details.is_delete = 'N'
  AND pii.invoice_date >= '" . $from_date . "'
  AND pii.invoice_date <= '" . $to_date . "'
  AND pii.branch_id=" . $eachResult->branch_id . "
	GROUP BY
		purchase_details.product_id,pii.branch_id
)AS purchase_qty ON purchase_qty.product_id = product.product_id

LEFT JOIN (
SELECT
		IFNULL(SUM(sales_details.quantity),0)AS sales_qty,
		sales_details.product_id,
    branch.branch_name
	FROM
		sales_details
left JOIN sales_invoice_info sii ON sii.sales_invoice_id=sales_details.sales_invoice_id
LEFT JOIN branch on branch.branch_id=sii.branch_id
	WHERE
		sales_details.is_active = 'Y'
	AND sales_details.is_delete = 'N'
 AND sii.invoice_date >= '" . $from_date . "'
  AND sii.invoice_date <= '" . $to_date . "'
  AND sii.branch_id=" . $eachResult->branch_id . "
	GROUP BY
		sales_details.product_id,sii.branch_id
) sales_qty ON sales_qty.product_id=product.product_id
LEFT JOIN(
	SELECT
		SUM(sales_return_details.return_quantity * sales_return_details.unit_price)/ SUM(return_quantity)AS avg_purchase_return_price_sales,
		sum(sales_return_details.return_quantity)AS return_empty_qty,
		sales_return_details.product_id
	FROM
		sales_return_details
	WHERE 
	sales_return_details.branch_id=" . $eachResult->branch_id . "
	GROUP BY
		sales_return_details.product_id
)AS sales_return_details ON sales_return_details.product_id = product.product_id
LEFT JOIN(
	SELECT
		SUM(purchase_return_details.return_quantity * purchase_return_details.unit_price)/ SUM(return_quantity)AS avg_purchase_return_price,
		IFNULL(sum(purchase_return_details.return_quantity),0)AS return_empty_qty,
		purchase_return_details.product_id
	FROM
		purchase_return_details
		WHERE 
	purchase_return_details.branch_id=" . $eachResult->branch_id . "
	GROUP BY
		purchase_return_details.product_id
)AS purchase_return_details ON purchase_return_details.product_id = product.product_id WHERE 1=1 ";
                                    if ($productId != 'all') {
                                        //product.product_id
                                        $query.=" and product.product_id= ".$productId;
                                    }
                                    $query.="  HAVING balance>0  ORDER BY brand.brandName,productcategory.title,product.productName";
echo  $query;echo '<br><br>';

                                    $query = $this->db->query($query);
                                    $resultStock = $query->result();
                                    /*$queryAvarage_price = "SELECT
                        (SUM(purchase_details.unit_price * purchase_details.quantity)/ sum(purchase_details.quantity))AS price,
                        purchase_details.branch_id,
                        purchase_details.product_id
                    FROM
                        purchase_details
                    LEFT JOIN purchase_invoice_info sii ON sii.purchase_invoice_id=purchase_details.purchase_invoice_id
                    WHERE
                        1=1  and purchase_details.branch_id=" . $eachResult->branch_id . " AND purchase_details.is_active = 'Y'
                    AND purchase_details.is_delete = 'N'
                    AND sii.is_active = 'Y'
                    AND sii.is_delete = 'N' AND sii.invoice_date >= '" . $from_date . "'
                    AND sii.invoice_date <= '" . $to_date . "'
                    GROUP BY purchase_details.product_id,purchase_details.branch_id ";*/

                                    if (!empty($resultStock)) {
                                        ?>
                                        <tr>
                                            <td colspan="7">
                                                <?php echo '<b>' . $eachResult->branch_name . '</b>' ?>
                                            </td>
                                        </tr>
                                    <?php }
                                    $i=0;
                                    foreach ($resultStock as $key => $eachProduct) {







                                        $queryAvarage_price = "SELECT
                        (SUM(purchase_details.unit_price * purchase_details.quantity)/ sum(purchase_details.quantity))AS price,
                        purchase_details.branch_id,
                        purchase_details.product_id
                    FROM
                        purchase_details
                    LEFT JOIN purchase_invoice_info sii ON sii.purchase_invoice_id=purchase_details.purchase_invoice_id
                    WHERE
                        1=1  and purchase_details.branch_id=" . $eachResult->branch_id . " AND purchase_details.is_active = 'Y'
                    AND purchase_details.is_delete = 'N'
                    AND sii.is_active = 'Y'
                    AND sii.is_delete = 'N' AND sii.invoice_date >= '" . $from_date . "'
                    AND sii.invoice_date <= '" . $to_date . "'
                    AND purchase_details.product_id = '" . $eachProduct->product_id . "'
                    GROUP BY purchase_details.branch_id ";
                                        //echo "<pre>";
                                        //print_r($queryAvarage_price);

                                        $queryAvarage_price = $this->db->query($queryAvarage_price);
                                        $queryAvarage_price = $queryAvarage_price->row();


                                        ?>

                                        <tr>
                                            <td class="text-center"><?php echo $i=$i+1;?></td>
                                            <td><?php echo $eachProduct->productName ?></td>
                                            <td>

                                            </td>
                                            <td align="right"> <?php echo $eachProduct->purchase_qty;
                                                $ttPurchaseQty = $ttPurchaseQty + $eachProduct->purchase_qty; ?>
                                            </td>

                                            <td align="right">
                                                <?php echo $eachProduct->sales_qty;
                                                $ttSalesQty = $ttSalesQty + $eachProduct->sales_qty
                                                ?>
                                            </td>

                                            <td align="right"><?php echo $eachProduct->purchase_qty - $eachProduct->sales_qty;
                                                $ttbalanceQty = $ttbalanceQty + ($eachProduct->purchase_qty - $eachProduct->sales_qty) ?></td>
                                            <td align="right"><?php echo numberFromatfloat($queryAvarage_price->price) ?></td>
                                            <td align="right"><?php
                                                $price = $queryAvarage_price->price * ($eachProduct->purchase_qty - $eachProduct->sales_qty);
                                                $ttPrice = $ttPrice +$price;
                                                echo numberFromatfloat($price);
                                                ?>
                                            </td>


                                        </tr>

                                        <?php
                                    }
                                }
                                ?>
                                <tfoot>
                                <tr>

                                    <td colspan="3"><?php echo get_phrase('Total')?></td>
                                    <td align="right"><?php echo numberFromatfloat($ttPurchaseQty)?></td>
                                    <td align="right"><?php echo numberFromatfloat($ttSalesQty)?></td>
                                    <td align="right"><?php echo numberFromatfloat($ttbalanceQty)?></td>
                                    <td align="right"><?php //echo numberFromatfloat()?></td>
                                    <td align="right"><?php echo numberFromatfloat($ttPrice)?></td>
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

<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>