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
                            <label class="col-sm-3 control-label no-padding-right" for="product_type_name">Product Type<span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" id="product_type_name"  name="product_type_name"  value="" class="form-control" placeholder="Product Type Name" required="" autocomplete="off" />
                                <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;This product name already exits!!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="description">Description</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <textarea name="description" class="form-control"id="description" rows="4" cols="129"></textarea>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="is_active">Status<span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <select  name="is_active" id="" class="form-control" >
                                    <option value="Y" selected="">Active</option>
                                    <option value="N" >Inactive</option>
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
            </div>
            </div>
            </div>
            </div>
            </div>
        <!-- /.row -->
    <!-- /.page-content -->
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





        //$("#publicForm").submit();



    }

</script>

