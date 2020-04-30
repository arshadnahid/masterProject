<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 10:04 AM
 */
?>


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
                                            
                                            <select class="chosen-select form-control" data-placeholder="Select Category" id="CategorySelect"
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
            </div>
        </div>

                    </fieldset>




                </form>
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



