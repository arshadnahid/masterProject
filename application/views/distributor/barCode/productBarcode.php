<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 10:04 AM
 */
?>
<style>

    /*  @media print {
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
      }*/

    .barcodea4 {
        width: 8.25in;
        height: 10.0in;
        display: block;
        border: 1px solid #CCC;
        margin: 10px auto;
        padding: 0.1in 0 0 0.1in;
        page-break-after: always;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase($title) ?></div>

            </div>
            <div class="portlet-body">
                <form id="publicForm" action="" method="post" class="form-horizontal">
                     <div class="clearfix"></div>
             
                    <fieldset style="">


                        <div class="row">
                            <legend style="margin-left: 20px; margin-top: 10px;">Product BarCode :</legend>
                            <div class="col-md-12">
                                <table class="table table-bordered" id="show_item_in">
                                    <thead>
                                    <tr>
                                        <th class="text-center">
                                            <?php echo get_phrase("Category") ?>
                                        </th>
                                        <th class="text-center">
                                            <?php echo get_phrase("Product Item") ?>
                                        </th>

                                        <th class="text-center">
                                            <?php echo get_phrase("Quantity") ?>
                                        </th>

                                        <td>
                                            <?php echo get_phrase("Add") ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%">
                                            
                                            <select class="chosen-select form-control" data-placeholder="Select Category"  id="CategorySelect"
                                onchange="getProductList(this.value)">
                            <option></option>


                            <?php
                            $categoryArray = array('1', '2');
                            foreach ($productCat as $eachInfo) {

                                if (!in_array($eachInfo->category_id, $categoryArray)) {


                                    ?>
                                    <option value="<?php echo $eachInfo->category_id ?>"><?php echo $eachInfo->title ?></option>
                                <?php }
                            } ?>

                        </select>
                                        </td>
                                        <td style="width: 35%">
                                            <select id="productID"

                                                    class="chosen-select form-control"
                                                    data-placeholder="Search by Product">
                                                <option value=""></option>


                                            </select>
                                        </td>

                                        <td style="width: 10%">
                                            <input type="text" id=""  value="" class="form-control quantity"

                                                   placeholder="" style="text-align: right;"/>
                                        </td>

                                        <td style="width: 5%">
                                            <a id="add_item" class="btn btn-info form-control"
                                               href="javascript:;" title="Add Item"><i
                                                        class="fa fa-plus"
                                                        style="margin-top: 6px;margin-left: 8px;"></i>&nbsp;&nbsp;</a>
                                        </td>
                                    </tr>
                                    </thead>


                                    <tbody>


                                    </tbody>
                                </table>


                            </div>
                        </div>

                          <div class="clearfix"></div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button onclick="return isconfirm2()" id="subBtn" class="btn btn-info pull-right"
                        type="button">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo get_phrase('BarCode Geneate') ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <!--<button class="btn" onclick="showCylinder()" type="button">
                    <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                    Returned Cylinder
                </button>-->
                </form>
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
                                        <td>Sales Price</td>
                                        <td>Barcode Quantity <br><input class="decimal" onkeyup="getval(this.value);"
                                                                        type="number" required>
                                            <button type="submit" class="btn btn-success">Generate Barcode</button>
                                        </td>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($productList as $key => $eachProduct):

                                        ?>

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
                                            <td>
                                                <?php echo $eachProduct->salesPrice?>
                                            </td>
                                            <td><input class="txt_name_value"
                                                       name="quantity[<?php echo $eachProduct->product_id ?>]"
                                                       type="number"
                                                       value="<?php echo $eachProduct->quantity?>"
                                                >
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>

                                </table>
                            </div>
                        </form>
                    <?php endif; ?>

            </div>
        </div>

                    </fieldset>




                
            </div>
        </div>
    </div>
</div>


<?php
if (count($selectList) >= 1):
    $parrowCode = 4;
    $parPageRow = 5;
    $numberOfBarcode = $numberOfBarcode;
    
    $numberOfPage = ceil($numberOfBarcode / 21);
    $pageNo=1;
    
    $c = 0;
    //for ($i = 0; $i < $numberOfPage; $i++) :

        ?>
        <div class="barcodea4">
            <?php
            foreach ($barcodes as $key => $productInfo) {
                $c = 1 + $c;
                ?>
                <div id="printableArea" style="padding:2px 10px; border:1px solid #efebeb;;min-width:100px; float:left; font-size:2px;text-align:center;margin:10px;">
                                        <b style="font-size:10px;"><span><?php echo $productInfo->productName ; ?>
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
                if ($c%21==0) {
                    echo '</div><div class="clearfix"></div>';
                    $pageNo = $pageNo+1;
                }
                 if ($pageNo>1 && $c%21==0 && ($c!= count($barcodes))) {
                    
                     
                    echo '<div class="barcodea4">';
                   // $c = 0;
                }
               
            }


            ?>
        </div>
        <?php
   // endfor;

    ?>
<?php endif; ?>

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

<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>
<script type="text/javascript">


    function getProductList(cat_id) {

        $.ajax({
            type: "POST",
            url: baseUrl + "lpg/BarcodeController/getProductList",
            data: 'cat_id=' + cat_id,
            success: function (data) {
                $('#productID').chosen();
                $('#productID option').remove();
                $("#productID").trigger("chosen:open");
                $('#productID').append($(data));
                $("#productID").trigger("chosen:updated");
            }
        });
    }

    //get product purchases price

    function isconfirm2() {

        var purchasesDate = $("#purchasesDate").val();
        var dueDate = $("#dueDate").val();

        if (purchasesDate == '') {
            swal("Select Purchases Date!", "Validation Error!", "error");
        }  else if (dueDate == '') {
            swal("Select Due Date!", "Validation Error!", "error");
        }
         else {
            swal({
                    title: "Are you sure ?",
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#73AE28',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    type: 'success'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $("#publicForm").submit();
                    } else {
                        return false;
                    }
                });
        }
    }





    var j = 0;

    $("#add_item").click(function () {


        var productID = $('#productID').val();
        var package_id = $('#package_id').val();
        var package_id2 = $('#productID2').val();

        var productCatID = $('#productID').find('option:selected').attr('categoryId');
        var productCatName = $('#productID').find('option:selected').attr('categoryName');
        var productBrandID = $('#productID').find('option:selected').attr('brandId');
        var productBrandName = $('#productID').find('option:selected').attr('brandName');
        var productCatName2 = $('#productID').find('option:selected').attr('categoryName2');
        var productName = $('#productID').find('option:selected').attr('productName');
        var productName2 = $('#productID2').find('option:selected').attr('productName2');
        var ispackage = $('#productID').find('option:selected').attr('ispackage');
        var quantity = $('.quantity').val();

        if (productBrandID == '' ) {
            swal("Brand can't be empty.!", "Validation Error!", "error");
            return false;
        } else if (productID == '') {
            swal("Product id can't be empty.!", "Validation Error!", "error");
            return false;
        }  else if (quantity == '') {
            swal("Quantity can't be empty.!", "Validation Error!", "error");
            return false;
        }else {
            var tab;

            tab ='<tr class="new_item' + productID + '">' +
                 '<td style="text-align: center"><input type="hidden" name="productCatID[]" value="'+productCatID+'">' + productCatName +
                '</td>' +
                '<td style="text-align: center"><input type="hidden" name="productID[]" value="'+productID+'">' + productName +
                '</td>' +


                '<td style="text-align: right"><input type="text" name="quantity[]" value="'+quantity+'">'+
                '</td>' +

                '<td><a class=" btn form-control btn-danger" href="javascript:void(0);" id="remCF">Remove</a></td>'+

                '</tr>';
                $("#show_item_in tbody").append(tab);

            $('#CategorySelect').val('').trigger('chosen:updated');
            $('#productID').val('').trigger('chosen:updated');
            $('#productID2').val('').trigger('chosen:updated');
            $('.quantity').val('');

        }

        $("#show_item_in").on('click','#remCF',function(){
        $(this).parent().parent().remove();
    });


    });







</script>



