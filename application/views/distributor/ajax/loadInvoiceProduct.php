<div class="col-md-10 col-md-offset-1">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-header">
                Sales Invoice Product
            </div>
            <table class="table table-bordered table-hover tableAddItem" id="show_item">
                <thead>
                    <tr>
                        <th style="width:5%">SL</th>
                        <th nowrap ><strong>Product </strong></th>
                        <th nowrap ><strong>Unit </strong></th>
                        <th nowrap style="width:10%;text-align: right;"><strong>Quantity </strong></th>
                        <th nowrap  style="width:12%;text-align: right;"><strong>Unit Price </strong></th>
                        <th nowrap  style="width:13%;text-align: right;"  ><strong>Total Price </strong></th>
                        <th align="center" style="width:5%"><strong>Action</strong></th>
                    </tr>
                </thead>
                <tbody>
                <input type="hidden" value="<?php echo $productList[0]->sales_invoice_id ; ?>" name="sales_invoice_id"/>
                <?php
                $j = 1;
                foreach ($productList as $key => $each_info):
                    ?>
                    <tr>
                        <td class="center"><?php echo $j++; ?></td>
                        <td>
                            <?php
                            $productInfo = $this->Common_model->tableRow('product', 'product_id', $each_info->product_id);
                            echo $this->Common_model->tableRow('productcategory', 'category_id', $productInfo->category_id)->title;
                            ?>
                            <?php

                            echo $productInfo->productName;
                            echo ' [ ' . $this->Common_model->tableRow('brand', 'brandId', $productInfo->brand_id)->brandName . ' ] ';
                            ?>
                        </td>

                        <td>
                            <?php
                            if (!empty($productInfo->unit_id)):
                                echo $this->Common_model->tableRow('unit', 'unit_id', $productInfo->unit_id)->unitTtile;
                            else:
                                echo "KG";
                            endif;
                            ?>
                        </td>
                        <td class="text-right"><input type="text" name="sales_details_qty[]" value="<?php  echo $each_info->quantity; ?>" placeholder="<?php  echo $each_info->quantity; ?>" /></td>
                        <td align="right"><input type="hidden" name="unit_price[]" value="<?php echo $each_info->unit_price; ?>" /><?php echo $each_info->unit_price; ?> </td>
                        <td align="right"><?php echo number_format($each_info->unit_price * $each_info->quantity, 2); ?> </td>
                        <td><input type="checkbox" name="sales_details_id[]" value="<?php echo $each_info->sales_details_id; ?>" /></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!empty($returnProductList)): ?>
                <div class="table-header">
                    Sales Invoice Return Product
                </div>
                <table class="table table-bordered table-hover tableAddItem" id="show_item">
                    <thead>

                        <tr>
                            <th style="width:4%">SL</th>
                            <th nowrap style="width:25%" align="center" id=""><strong>Product </strong></th>
                            <th nowrap style="width:10%;text-align: right;" ><strong>Quantity </strong></th>
                            <th nowrap  style="width:12%;text-align: right;" ><strong>Unit Price </strong></th>
                            <th nowrap  style="width:13%;text-align: right;"   ><strong>Total Price </strong></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 1;
                        foreach ($returnProductList as $key => $each_info):
                            ?>
                            <tr>
                                <td class="center"><?php echo $j++; ?></td>
                                <td>
                                    <?php
                                    echo $each_info->productName;
                                    ?>
                                </td>
                                <td class="text-right"><?php echo $each_info->return_quantity; ?> </td>
                                <td class="text-right"><?php echo $each_info->unit_price; ?> </td>
                                <td class="text-right"><?php echo $each_info->return_quantity * $each_info->unit_price; ?> </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>


