
<div class="row">

    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo  get_phrase('Product Package Add')?> </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                <form id="publicForm" action=""  method="post" class="form-horizontal">

                    <div class="form-group">

                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><span style="color:red!important"> *</span> <?php echo get_phrase('Package Code')?> </label>
                        <div class="col-sm-6">

                            <input type="text" id="form-field-1" name="package_code" readonly value="<?php echo $packageid; ?>" class="form-control" placeholder="Product Code" />
                        </div>
                    </div>
                    <div class="form-group">

                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <span style="color:red!important"> *</span><?php echo get_phrase('Package Name')?> </label>
                        <div class="col-sm-6">
                            <input type="hidden" name="package_id" id="package_id" value="">
                            <input type="text" id="package_name" name="package_name"   class="form-control" placeholder="Package Name" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="productID"><span style="color:red!important"> *</span> <?php echo get_phrase('Product')?> </label>
                        <div class="col-sm-6">
                            <select id="productID"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Product">
                                <option value=""></option>
                                <?php
                                $a=array('1,2');
                                foreach ($productList as $key => $eachProduct):
                                    //if(in_array($eachProduct['categoryId'],$a)){
                                    ?>
                                    <optgroup label="<?php echo $eachProduct['categoryName']; ?>">
                                        <?php
                                        foreach ($eachProduct['productInfo'] as $eachInfo) :
                                            ?>
                                            <option categoryName="<?php echo $eachProduct['categoryName']; ?>" categoryId="<?php echo $eachProduct['categoryId']; ?>" productName="<?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?>" value="<?php echo $eachInfo->product_id; ?>"><?php echo $eachInfo->productName . " [ " . $eachInfo->brandName . " ] "; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <?php
                                    //}
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
                                            <?php echo get_phrase('Product Category')?>
                                        </th>
                                        <th id="thd" style="height:25px;width: 5%;text-align: center">
                                            <i style="color:red" class="fa fa-trash-o"></i>
                                        </th>



                                    </tr>
                                </thead>
                                <tbody id="package_table">


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
                                <?php echo get_phrase('Save')?>
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                <?php echo get_phrase('Reset')?>
                            </button>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->


<script>

    function checkDuplicateProduct() {

        if ($('.trClass').length == 0) {
            swal("Add  Product To Make A Package !", "Validation Error!", "error");
        } else {
            var package_name = $("#package_name").val();
            var package_id = $("#package_id").val();

            var url = '<?php echo site_url("lpg/ProductPackageController/checkDuplicateProductPackage") ?>';

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
                //tab += '<td>' + '' + '%</td>';
                //tab += '<td>' + '' + '</td>';
                tab += "<td id='thd' style='height: 27px;text-align:center'>" +
                        "<span><input type='hidden' class='tag' value='" + productID + "'>" +
                        "<a style='padding-left:0px' href='javascript:void(0)' attr-pid='" + productID + "' onClick='return false;' class='removetag" + productID + "'>" +
                        "<i style='color:red;' class='fa fa-times'></i>" +
                        "</span>" +
                        "</td>";
                tab += '</tr>';
                $('#package_table').prepend(tab);
                $('#productID').val('').trigger('chosen:updated');
            } else {
                return false;
            }
        } else {
            return false;
        }

        $('.removetag' + productID).click(function (e) {
            $(this).parent().parent().parent().remove();
        });
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


