<style>

    @media print {
        @page {
            margin-top: 0;
            margin-bottom: 0;
        }

        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .noPrint {
            display: none;
        }

        strong {
            font-weight: normal !important;
        }
    }
</style>

<div class="row">
    <?php
    $numberOfQuantity = $quantity;
    //print_r($numberOfQuantity);
    ?>
    <!-- BEGIN EXAMPLE TABLE PORTLET-->


    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title noPrint" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Product Barcode
                </div>

            </div>

            <div class="portlet-body">
                <?php
                if (isset($_POST['productid'])) {
                    $categoryID = $_POST['category'];
                    $productid = $_POST['productid'];
                    $productInfo = $this->Common_model->get_single_data_by_single_column('product', 'product_id', $productId);
                }else{
                    $categoryID ='all';
                }
                ?>

                <div class="row">
                    <div class="col-md-12  noPrint">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                        Category</label>
                                    <div class="col-sm-8">
                                        <select id="productId" name="category" onchange="getProductList(this.value)"
                                                class="chosen-select form-control" id="form-field-select-3"
                                                data-placeholder="Search Category">
                                            <option></option>
                                            <option <?php
                                            if (!empty($categoryID) && $categoryID == 'all') {
                                                echo "selected";
                                            }
                                            ?> value="all">All
                                            </option>
                                            <?php foreach ($categoryList as $key => $each_info): ?>
                                                <option <?php
                                                if (!empty($categoryID) && $categoryID == $each_info->category_id): echo "selected";
                                                endif;
                                                ?> value="<?php echo $each_info->category_id; ?>"><?php echo $each_info->title; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                        Product</label>
                                    <div class="col-sm-9">
                                        <select id="productID" name="productid" onchange="getProductPrice(this.value)"
                                                class="chosen-select form-control" id="form-field-select-3"
                                                data-placeholder="Search by Product">
                                            <option <?php
                                            if (!empty($productid) && $productid == 'all') {
                                                echo "selected";
                                            }
                                            ?> value="all">All
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button id="subBtn" class="btn btn-success btn-sm" name="barcode" type="submit">
                                            <i class="fa fa-list"></i>
                                            Get List
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button onclick="window.print();" style="cursor:pointer;" id="subBtn"
                                                class="btn btn-info btn-sm">
                                            <i class="fa fa-print bigger-110"></i>
                                            Print
                                        </button>
                                        <input class="btn btn-info btn-sm"
                                                                                        type="button"
                                                                                        onclick="printDiv('printableArea')"
                                                                                        value="Barcodev Print"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    if (!empty($productList) && empty($selectList)):
                        ?>
                        <form action="" method="POST">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td>All Check</td>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input id="checkAll" class="ace ace-checkbox-2" type="checkbox"
                                                           value="<?php echo $eachProduct->product_id; ?>">
                                                    <span class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;All Product</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>Barcode Quantity <br><input class="decimal" onkeyup="getval(this.value);"
                                                                    type="number" required>
                                                           <button type="submit" class="btn btn-success">Generate Barcode</button>         
                                                                    </td>
                                    
                                   
                                       
                                      
                                       
                                   
                                   
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($productList as $key => $eachProduct): ?>
                                        <tr>
                                            <td>
                                                <?php echo $key + 1; ?>
                                            </td>
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="productId[]" class="ace ace-checkbox-2"
                                                               type="checkbox"
                                                               value="<?php echo $eachProduct->product_id; ?>">
                                                        <?php if (empty($eachProduct->productCat)): ?>
                                                            <span class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $eachProduct->productName . '[' . $this->Common_model->tableRow('brand', 'brandId', $eachProduct->brand_id)->brandName . ']'; ?></span>
                                                        <?php else: ?>
                                                            <span class="lbl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $eachProduct->productCat . ' - ' . $eachProduct->productName . '[' . $eachProduct->brandName . ']'; ?></span>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </td>
                                            <td><input class="txt_name_value" name="quantity[<?php echo $eachProduct->product_id?>]" type="number"
                                                       >
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </form>
                    <?php endif; ?>
                    <?php
                    if (count($selectList) >= 1):
                        ?>
                        <div class="col-md-12" id="printableArea">
                            
                            <?php
                                foreach ($selectList as $key => $eachProduct):
                                    $productInfo = $this->Common_model->getProductInfo($eachProduct);

                                    ?>
                                   


                                            <?php 
                                            $y=1;
                                            
                                            for ($i = 0; $i < $quantity[$productInfo->product_id]; $i++) : 
                                            
                                            ?>

                                                <div id="printableArea" style="padding:2px 10px; border:1px solid #efebeb;;min-width:100px; float:left; font-size:2px;text-align:center;margin:10px;">
                                                    <b style="font-size:12px;"><span><?php echo $productInfo->productName ; ?>
                                                    </b></br>
                                                    <span style="font-size:12px;margin-top:-10px">
                                                        <?php echo $productInfo->brandName;?>
                                                    </span>
                                                    </br>
                                                    <img src="<?php echo base_url(); ?>/barcode/html/image.php?filetype=PNG&dpi=92&scale=1&rotation=0&font_family=Arial.ttf&font_size=10&text=<?php echo $productInfo->product_code; ?>&thickness=45&checksum=&code=BCGcode39"/>
                                                    <br>
                                                   <strong><span style="font-size:12px; margin-right:10px"><?php if($productInfo->salesPrice!='') echo 'MRP : '. $productInfo->salesPrice ?>/-</span></strong> 
                                                </div>


                                            <?php 
                                            if($y==20){ ?>
                                                <div class="col-md-12" style="min-height: 100px;width:820px;padding:2px 10px; border:1px solid #efebeb;; float:left; font-size:2px;text-align:center;margin:10px;">
                                                    
                                                </div>
                                            <?php $y=0;}
                                            
                                            $y=$y+1;
                                            
                                            
                                            endfor; 
                                            
                                            ?>


                                      
                                <?php endforeach; ?>
                            
                            
                            
                            <table class="table ">
                                <thead class="noPrint">
                                <tr>
                                    <td>Product Name</td>
                                    <td>Product Barcode <span class="pull-right"> </span>
                                    </td>
                                </tr>
                                </thead>
                                
                            </table>
                        </div>
                    <?php endif; ?>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.row -->
    </div><!-- /.row -->
</div><!-- /.row -->
<!-- /.page-content -->
<script>
    function getval(sel) {
        $('.txt_name_value').val(sel);
    }


    /////////////////
    function getProductList(cat_id) {

        $.ajax({
            type: "POST",
            url: baseUrl + "getProductListForBarcode",
            data: 'cat_id=' + cat_id,
            //data: {cat_id:cat_id, quantity:quantity}
            success: function (data) {

                $('#productID').chosen();
                $('#productID option').remove();
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
            }
        });
    }
</script>
<script>
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    ///////

    $("#amount").change(function () {
        alert($("#amount").val());

    });

    //////
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
