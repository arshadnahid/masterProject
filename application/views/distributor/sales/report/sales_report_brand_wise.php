
<?php
/*
SELECT
	sd.branch_id,
	sd.product_id,
product.productName,
IFNULL(sales_package.sales_package_qty,0) AS sales_package_qty,
IFNULL(sales_refill.sales_refill_qty,0) AS sales_refill_qty,
IFNULL(sales_empty.sales_empty_qty,0) AS sales_empty_qty
FROM
	sales_details sd
LEFT JOIN sales_invoice_info si ON si.sales_invoice_id = sd.sales_invoice_id
LEFT JOIN product ON product.product_id = sd.product_id

LEFT JOIN(
    -- sales package qty
SELECT
	sales_details.branch_id,sales_details.product_id,SUM(sales_details.quantity) sales_package_qty
FROM
	sales_details
LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
LEFT JOIN product ON product.product_id = sales_details.product_id
WHERE
	product.category_id = 2
    AND sales_details.is_package = 1
    AND sales_details.show_in_invoice=1
    AND sales_invoice_info.invoice_date >= '2020-01-01'
    AND sales_invoice_info.invoice_date <= '2020-02-26'
GROUP BY
sales_details.branch_id,sales_details.product_id
) AS sales_package on sales_package.branch_id=sd.branch_id AND sd.product_id=sales_package.product_id

LEFT JOIN(
    -- sales refill qty
SELECT
	sales_details.branch_id,sales_details.product_id,SUM(sales_details.quantity) sales_refill_qty
FROM
	sales_details
LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
LEFT JOIN product ON product.product_id = sales_details.product_id
WHERE
	product.category_id = 2
    AND sales_details.is_package != 1
    AND sales_details.show_in_invoice=1
    AND sales_invoice_info.invoice_date >= '2020-01-01'
    AND sales_invoice_info.invoice_date <= '2020-02-26'
GROUP BY
sales_details.branch_id,sales_details.product_id
) AS sales_refill on sales_refill.branch_id=sd.branch_id AND sd.product_id=sales_refill.product_id

LEFT JOIN(
    -- sales Empty qty
SELECT
	sales_details.branch_id,sales_details.product_id,SUM(sales_details.quantity) sales_empty_qty
FROM
	sales_details
LEFT JOIN sales_invoice_info ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id
LEFT JOIN product ON product.product_id = sales_details.product_id
WHERE
	product.category_id = 1
    AND sales_details.is_package != 1
    AND sales_details.show_in_invoice=1
    AND sales_invoice_info.invoice_date >= '2020-01-01'
    AND sales_invoice_info.invoice_date <= '2020-02-26'
GROUP BY
sales_details.branch_id,sales_details.product_id
) AS sales_empty on sales_empty.branch_id=sd.branch_id AND sd.product_id=sales_empty.product_id



WHERE
	product.category_id IN(1, 2)
AND si.invoice_date >= '2020-01-01'
AND si.invoice_date <= '2020-02-26'
AND sd.show_in_invoice = 1
GROUP BY sd.branch_id,sd.product_id*/




if (isset($_POST['start_date'])):
    $brandId = $this->input->post('brandId');
    $cusType = $this->input->post('cusType');
    $customer_id = $this->input->post('customer_id');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Sales Rreport Brand Wise  </div>

            </div>

            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action=""  method="post" class="form-horizontal noPrint">

                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right" for="form-field-1"> From </label>
                                    <div class="col-md-8">
                                        <input type="text"class="date-picker form-control" id="start_date" name="start_date" value="<?php
                                        if (!empty($from_date)) {
                                            echo $from_date;
                                        } else {
                                            echo date('d-m-Y');
                                        }
                                        ?>" data-date-format='dd-mm-yyyy' placeholder=" dd-mm-yyyy" style="width:100%"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;To</label>
                                    <div class="col-md-8">
                                        <input type="text" class="date-picker form-control" id="end_date" name="end_date" value="<?php
                                        if (!empty($to_date)):
                                            echo $to_date;
                                        else:
                                            echo date('d-m-Y');
                                        endif;
                                        ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy" style="width:100%"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">
                                            <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                            Print
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- /.col -->
                <?php
                if (isset($_POST['start_date'])):?>

                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?></h3>
                                        <p><?php echo $companyInfo->dist_address; ?></p>
                                        <strong>Phone : </strong><?php echo $companyInfo->dist_phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->dist_email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->dis_website; ?><br>
                                        <strong><?php echo 'Sales Rreport Brand Wise'; ?></strong> : From <?php echo $from_date; ?> To <?php echo $to_date; ?>

                                    </td>
                                </tr>
                            </table>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Brand</th>
                                    <?php
                                    foreach ($product  as $ind => $element) {
                                        ?>
                                        <th  class="text-center" colspan="3"><?php echo $element .' Kg'?></th>
                                    <?php }?>
                                </tr>
                                <tr>
                                    <th></th>
                                    <?php
                                    foreach ($product  as $ind => $element) {
                                        ?>
                                        <th class="<?php echo 'package_'.$element?>">
                                            Pack

                                        </th>
                                        <th class="<?php echo 'package_'.$element?>">
                                            Ref
                                        </th>
                                        <th class="<?php echo 'package_'.$element?>">
                                            Emp
                                        </th>
                                    <?php }?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($sales_list  as $ind => $element2) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $element2['brand_name']?>
                                        </td>
                                        <?php
                                        foreach ($product  as $ind => $element) {
                                            $package_th= ('package_'.$element)?'package_'.$element:'package_0';
                                            $refial_th= ('refial_'.$element)?'refial_'.$element:'refial_0';
                                            $empty_th= ('empty_'.$element)?'empty_'.$element:'empty_0';
                                            $packageValue= isset($element2[$element.'_package'])?$element2[$element.'_package']:'';
                                            $refialValue= isset($element2[$element.'_refial'])?$element2[$element.'_refial']:'';
                                            $emptyValue= isset($element2[$element.'_empty'])?$element2[$element.'_empty']:'';
                                            ?>
                                            <th class="<?php echo $package_th?>">
                                                <?php echo $packageValue!=''?$packageValue:'--';?>
                                            </th>
                                            <th class="<?php echo $refial_th?>">
                                                <?php echo $refialValue!=''?$refialValue:'--';?>
                                            </th>
                                            <th class="<?php echo $empty_th ?>">
                                                <?php echo $emptyValue!=''?$emptyValue:'--';?>
                                            </th>
                                        <?php }?>
                                    </tr>
                                <?php }?>
                                </tbody>

                            </table>

                        </div>
                    </div>


                    <!-- /.page-content -->
                <?php endif;?>
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


