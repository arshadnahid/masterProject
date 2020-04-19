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
                    <?php echo get_phrase('Current Stock Report') ?>
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

            </div>
        </div>
    </div>


    <div class="col-md-12">
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

                            <td align="center"><strong><?php echo get_phrase('Branch') ?></strong></td>
                            <td align="center"><strong><?php echo get_phrase('Sl') ?></strong></td>
                            <td align="center"><strong><?php echo get_phrase('Product Name') ?></strong></td>
                            <!--<td align="center"><strong><?php /*echo get_phrase('Opening Qty') */?></strong></td>-->
                            <td align="center"><strong><?php echo get_phrase('Purchase Qty') ?></strong></td>
                            <td align="center"><strong><?php echo get_phrase('Sales Qty') ?></strong></td>
                            <td align="center"><strong>Balance</strong></td>


                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($productId != 'all') {
                            //product.product_id
                            $query2 .= " and product.product_id= " . $productId;
                        }
                        $query = "SELECT
branch.branch_name,
	purchase_details.product_id,
	/*sales_details.product_id,*/
	purchase_details.branch_id,
	/*sales_details.branch_id,*/
	purchase_details.productName,
	purchase_details.brandName,
	purchase_details.title,
	purchase_details.unitTtile,
	IFNULL(purchase_details.quantity_P,0)quantity_P,
	IFNULL(purchase_details.quantity_PRe,0)quantity_PRe,
	IFNULL(sales_details.quantity_S,0)quantity_S,
	IFNULL(sales_details.quantity_SRe,0)quantity_SRe
FROM
	(
		SELECT
			purchase_invoice_info.branch_id,
			purchase_details.product_id,
			SUM(IFNULL(purchase_details.quantity,	0	))AS quantity_P,
			SUM(IFNULL(purchase_return_details.return_quantity,0))AS quantity_PRe,
			product.productName,
			brand.brandName,
			productcategory.title,
			unit.unitTtile
		FROM
			purchase_invoice_info
		LEFT OUTER JOIN purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id
		LEFT OUTER JOIN purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id
		AND purchase_details.product_id=purchase_return_details.product_id
		LEFT OUTER JOIN product ON purchase_details.product_id = product.product_id
		LEFT OUTER JOIN productcategory ON product.category_id = productcategory.category_id
		LEFT OUTER JOIN unit ON product.unit_id = unit.unit_id
		LEFT OUTER JOIN brand ON product.brand_id = brand.brandId
		WHERE
			purchase_details.is_active = 'Y'
		AND purchase_details.is_delete = 'N'
		" . $query2 . "
		AND 
		IFNULL(purchase_return_details.is_active,'Y')= 'Y'
		AND IFNULL(purchase_return_details.is_delete,'N')= 'N'
		AND purchase_invoice_info.invoice_date >= '" . $from_date . "'
		AND purchase_invoice_info.invoice_date <= '" . $to_date . "'
		GROUP BY
			purchase_details.product_id,
			purchase_invoice_info.branch_id,
			product.productName,
			brand.brandName,
			productcategory.title,
			unit.unitTtile
	)purchase_details
LEFT OUTER JOIN(
	SELECT
		sales_invoice_info.branch_id,
		sales_details.product_id,
		SUM(IFNULL(sales_details.quantity,0))AS quantity_S,
		SUM(IFNULL(sales_return_details.return_quantity,0))AS quantity_SRe,
		product.productName,
		brand.brandName,
		productcategory.title,
		unit.unitTtile
	FROM
		sales_invoice_info
	LEFT OUTER JOIN sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
	LEFT OUTER JOIN sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id
	AND sales_details.product_id=sales_return_details.product_id
	LEFT OUTER JOIN product ON sales_details.product_id = product.product_id
	LEFT OUTER JOIN productcategory ON product.category_id = productcategory.category_id
	LEFT OUTER JOIN unit ON product.unit_id = unit.unit_id
	LEFT OUTER JOIN brand ON product.brand_id = brand.brandId
	WHERE
		sales_details.is_active = 'Y'
	AND sales_details.is_delete = 'N'
	
	" . $query2 . "
	AND IFNULL(sales_return_details.is_active,'Y')= 'Y'
	AND IFNULL(sales_return_details.is_delete,'N')= 'N'
	AND sales_invoice_info.invoice_date >= '" . $from_date . "'
	AND sales_invoice_info.invoice_date <= '" . $to_date . "'
	GROUP BY
		sales_details.product_id,
		sales_invoice_info.branch_id,
		product.productName,
		brand.brandName,
		productcategory.title,
		unit.unitTtile
)sales_details ON purchase_details.product_id = sales_details.product_id
AND purchase_details.branch_id = sales_details.branch_id
LEFT JOIN branch on branch.branch_id=purchase_details.branch_id
";
                        if ($branch_id != 'all') {
                            $query .= " AND purchase_details.branch_id= " . $branch_id;
                        }
                        $query .= " ORDER BY branch.branch_name,purchase_details.brandName, purchase_details.title ,purchase_details.productName";
                        $query = $this->db->query($query);
                        $resultStock = $query->result();
                        //echo  $this->db->last_query();
                        $ttpurchaseQty = 0;
                        $ttsalesQty = 0;
                        $ttPrice = 0;
                        $brandNameArray = array();
                        $i=1;
                        foreach ($resultStock as $ind => $element2) {
                            $queryAvarage_price = "SELECT
                        (SUM(purchase_details.unit_price * purchase_details.quantity)/ sum(purchase_details.quantity))AS price,
                        purchase_details.branch_id,
                        purchase_details.product_id
                    FROM
                        purchase_details
                    LEFT JOIN purchase_invoice_info sii ON sii.purchase_invoice_id=purchase_details.purchase_invoice_id
                    WHERE
                        1=1  and purchase_details.branch_id=" . $element2->branch_id . " AND purchase_details.is_active = 'Y'
                    AND purchase_details.is_delete = 'N'
                    AND sii.is_active = 'Y'
                    AND sii.is_delete = 'N' AND sii.invoice_date >= '" . $from_date . "'
                    AND sii.invoice_date <= '" . $to_date . "'
                    AND purchase_details.product_id = '" . $element2->product_id . "'
                    GROUP BY purchase_details.branch_id ";
                            /* echo "<pre>";
                             print_r($queryAvarage_price);*/
                            $queryAvarage_price = $this->db->query($queryAvarage_price);
                            $queryAvarage_price = $queryAvarage_price->row();
                            $purchaseQty = $element2->quantity_P + $element2->quantity_SRe;
                            $salesQty = $element2->quantity_S + $element2->quantity_SRe;
                            $ttpurchaseQty += $purchaseQty;
                            $ttsalesQty += $salesQty;
                            if (!in_array($element2->branch_name, $brandNameArray)) {
                                array_push($brandNameArray, $element2->branch_name); ?>
                                <tr>
                                    <td colspan="8">
                                        <?php echo $element2->branch_name ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">
                                        <?php
                                        echo $i++ ?>
                                    </td>
                                    <td class="text-center" colspan="">
                                        <?php
                                        echo $element2->title . ' ' . $element2->productName . " " . $element2->unitTtile . ' ' . $element2->brandName
                                        ?>
                                    </td>
                                    <!--<td class="text-right">
                                        <?php
                                    /*                                        */?>
                                    </td>-->
                                    <td class="text-right">
                                        <?php
                                        echo $purchaseQty;
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                        echo $salesQty;
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                        echo $purchaseQty - $salesQty;
                                        ?>
                                    </td>



                                </tr>

                            <?php } else {
                                ?>
                                <tr>
                                    <td class="text-right" colspan="2">
                                        <?php
                                        echo $i++ ?>
                                    </td>
                                    <td class="text-center" colspan="">
                                        <?php
                                        echo $element2->title . ' ' . $element2->productName . " " . $element2->unitTtile . ' ' . $element2->brandName
                                        ?>
                                    </td>
                                    <!--<td class="text-right">
                                        <?php
                                    /*                                        */?>
                                    </td>-->
                                    <td class="text-right">
                                        <?php
                                        echo $purchaseQty;
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                        echo $salesQty;
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                        echo $purchaseQty - $salesQty;
                                        ?>
                                    </td>



                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" class="text-right"><?php echo "Total" ?></td>

                            <td>
                                <?php
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                echo numberFromatfloat($ttpurchaseQty);
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                echo numberFromatfloat($ttsalesQty);
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                echo numberFromatfloat(($ttpurchaseQty - $ttsalesQty));
                                ?>
                            </td>

                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php endif; ?>
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

