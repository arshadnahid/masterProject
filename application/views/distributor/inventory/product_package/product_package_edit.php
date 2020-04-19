<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/25/2019
 * Time: 3:23 PM
 */
?>
<?php /**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/25/2019
 * Time: 9:44 AM
 */ ?>




<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">

            <div class="row">

                <div class="col-md-12">



                    <form id="publicForm" action=""  method="post" class="form-horizontal">



                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Package Code')?> <span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">

                                <input type="text" id="form-field-1" name="package_code" readonly value="<?php echo $package_details[0]->package_code; ?>" class="form-control" placeholder="Product Code" />
                            </div>
                        </div>
                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('Package Name')?> <span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <input type="hidden" name="package_id" id="package_id" value="<?php echo $package_details[0]->package_id ?>">
                                <input type="text" id="package_name" name="package_name"   class="form-control" placeholder="Package Name" value="<?php echo $package_details[0]->package_name ?>" />
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="productID"><?php echo get_phrase('Package ')?>Product <span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">

                                <select id="productID"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product">
                                    <option value=""></option>
                                    <?php foreach ($productList as $key => $eachProduct):
                                        ?>
                                        <optgroup label="<?php echo $eachProduct['categoryName']; ?>">
                                            <?php
                                            foreach ($eachProduct['productInfo'] as $eachInfo) :
                                                ?>
                                                <option categoryName="<?php echo $eachProduct['categoryName']; ?>" categoryId="<?php echo $eachProduct['categoryId']; ?>" productName="<?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?>" value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <?php
                                    endforeach;
                                    ?>
                                </select>

                            </div>
                            <div class="col-sm-3">
                                <a  hidden="javascrict:void(0)" id="add_package_product" class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;Add</a>
                            </div>
                        </div>




                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">

                                <table border="1"  id="package_details"style="width:100%;background: white">
                                    <thead>
                                        <tr style="background:#F9FBFC">

                                            <th id="thd" style="height:25px;text-align: center;font-weight: bold;">
                                                <?php echo get_phrase('Product Name')?>
                                            </th>
                                            <th id="thd" style="height:25px;text-align: center;font-weight: bold;">
                                                <?php echo get_phrase('Product Type')?>
                                            </th>
                                            <th id="thd" style="height:25px;width: 5%;text-align: center">
                                                <i style="color:red" class="fa fa-trash-o"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="package_table">
                                        <?php
                                        foreach ($package_details as $key => $value):
                                            ?>
                                            <tr class='trClass'>
                                                <td>
                                                    <input type='hidden' name='product_id[]' id='productID_<?php echo $value->product_id ?>' value='<?php echo $value->package_products_id ?>'/>
                                                    <input type='hidden' name='package_products_id_<?php echo $value->package_products_id ?>' id='productID_<?php echo $value->product_id ?>' value='<?php echo $value->package_products_id ?>'/>
                                                    <?php echo $value->productName . ' [ ' . $value->brandName . ' ]' ?>
                                                </td>
                                                <td>
                                                    <?php echo $value->title ?>
                                                </td>
                                                <td id='thd' style='height: 27px;text-align:center'>
                                                    <span><input type='hidden' class='tag' value='<?php echo $value->product_id ?>'>
                                                        <a style='padding-left:0px' href='javascript:void(0)' attr-pid='<?php echo $value->product_id ?>' onClick='return false;' class='removetag'>
                                                            <i style='color:red;' class='fa fa-times'></i>
                                                        </a>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                        ?>




                                    </tbody>
                                </table>


                            </div>
                            <div class="col-sm-3"></div>





                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="description">Description</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <textarea name="description" class="form-control"id="description" rows="4" cols="129"></textarea>
                                </div>

                            </div>
                        </div>





                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return checkDuplicateProduct()"   id="subBtn" class="btn btn-info" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    <?php echo get_phrase('Update')?>
                                </button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    <?php echo get_phrase('Reset')?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>









<script>

    function checkDuplicateProduct() {

        if ($('.trClass').length == 0) {
            swal("Add  Product To Make A Package !", "Validation Error!", "error");
        } else {
            var package_name = $("#package_name").val();
            var package_id = $("#package_id").val();

            var url = '<?php echo site_url("ProductPackageController/checkDuplicateProductPackage") ?>';

            $.ajax({
                type: 'POST',
                url: url,
                data: {'package_name': package_name, 'package_id': package_id},
                success: function (data)
                {
                    if (data == 1) {
                        swal("Product Package Already Exist !", "Validation Error!", "error");
                        return false;
                    } else {
                        $("#publicForm").submit();
                    }
                }
            });
        }
    }

    $('#add_package_product').click(function () {
        var productCatID = $('#productID').find('option:selected').attr('categoryId');
        var productCatName = $('#productID').find('option:selected').attr('categoryName');
        var productID = $('#productID').val();
        var productName = $('#productID').find('option:selected').attr('productName');
        var checkStatus = checkProductAddedOrNot(productID);
        if (productID != '') {
            if (checkStatus == true) {
                var tab = "<tr class='trClass'>";

                tab += '<td>' +
                        "<input type='hidden' name='product_id[]' id='productID_" + productID + "' value='" + productID + "'>" +
                        "<input type='hidden' name='' id='' value='" + productID + "'>" +
                        "<input type='hidden' name='' id='' value='" + productID + "'>" +
                        "<input type='hidden' name='' id='' value='" + productID + "'>" +
                        "<input type='hidden' name='' id='' value='" + productID + "'>"

                        + productName + '</td>';
                tab += '<td>' + productCatName + '</td>';
                tab += "<td id='thd' style='height: 27px;text-align:center'>" +
                        "<span><input type='hidden' class='tag' value='" + productID + "'>" +
                        "<a style='padding-left:0px' href='javascript:void(0)' attr-pid='" + productID + "' onClick='return false;' class='removetag'>" +
                        "<i style='color:red;' class='fa fa-times'></i>" +
                        "</span>" +
                        "</td>";
                tab += '</tr>';
                $('#package_table').prepend(tab);
                $('#productID').val('').trigger('chosen:updated');
                $('.removetag').click(function (e) {
                    $(this).parent().parent().parent().remove();
                });
            } else {
                return false;
            }
        } else {
            return false;
        }


    });

    $('.removetag').click(function (e) {
        $(this).parent().parent().parent().remove();
    });
    function checkProductAddedOrNot(pid) {
        var previousProductID = parseFloat($('#productID_' + pid).val());
        if (previousProductID == pid) {

            return false;
        } else {
            return true;
        }
    }


</script>



