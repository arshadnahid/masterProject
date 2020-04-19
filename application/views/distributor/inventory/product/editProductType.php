
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Add Product Type </div>
            </div>
            <div class="portlet-body">

                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm" action=""  method="post" class="form-horizontal">

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="product_type_name"><span style="color:red!important"> *</span>Product Type</label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="product_type_id" id="product_type_id" value="<?php echo $product_type->product_type_id ?>"/>
                                    <input type="text" value="<?php echo $product_type->product_type_name ?>" id="product_type_name"  name="product_type_name"  class="form-control" placeholder="Product Type Name" required="" autocomplete="off" />
                                    <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;This product name already exits!!</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="description">Description</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <textarea name="description" id="description" rows="" cols="129"><?php echo $product_type->description ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="is_active"><span style="color:red!important"> *</span>Status</label>
                                <div class="col-sm-6">
                                    <select  name="is_active" id="" >
                                        <option value="Y" <?php echo $product_type->is_active == 'Y' ? 'selected' : '' ?>>Active</option>
                                        <option value="N" <?php echo $product_type->is_active == 'N' ? 'selected' : '' ?>>Inactive</option>
                                    </select>


                                </div>
                            </div>

                            <div class="clearfix form-actions" >
                                <div class="col-md-offset-3 col-md-9">
                                    <button onclick="return checkDuplicateProduct()"   id="subBtn" class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>









<script>

    function checkDuplicateProduct() {

        if ($('#product_type_name').val() == '') {
            swal("Give Product Type !", "Validation Error!", "error");
        } else {
            var product_type_name = $("#product_type_name").val();
            var product_type_id = $("#product_type_id").val();



            var url = '<?php echo site_url("lpg/InvProductController/checkDuplicateProductType") ?>';

            $.ajax({
                type: 'POST',
                url: url,
                data: {'product_type_name': product_type_name, 'product_type_id': product_type_id},
                success: function (data)

                {

                    if (data == 1) {
                        swal("Product Type Already Exist !", "Validation Error!", "error");
                        return false;

                    } else {

                        $("#publicForm").submit();

                    }

                }

            });
        }
    }

</script>

