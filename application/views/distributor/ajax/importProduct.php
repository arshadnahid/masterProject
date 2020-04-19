 <form id="publicForm" action="<?php echo site_url('lpg/ImportController/updateImportProduct');?>"  method="post" class="form-horizontal">
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product Category<span style="color:red!important"> *</span></label>
    <div class="col-sm-6">
        <select  id="productCategory"  name="category_id"  class="chosen-select form-control" data-placeholder="Search product Category">
            <option disabled selected>-Select-</option>
            <?php foreach ($catList as $each_info): ?>
                <option <?php
            if (!empty($productInfo->catId) && $productInfo->catId == $each_info->category_id) {
                echo "selected";
            }
                ?> value="<?php echo $each_info->category_id; ?>"><?php echo $each_info->title; ?></option>
<?php endforeach; ?>
        </select>
    </div>
</div>
     <input type="hidden" name="updateId" value="<?php echo $productInfo->importId;?>"/>

<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Brand<span style="color:red!important"> *</span></label>
    <div class="col-sm-6">
        <select  id="brandId"  name="brand"  class="chosen-select form-control"  data-placeholder="Search Brand">
            <option disabled selected>-Select-</option>
            <?php foreach ($brandList as $each_info): ?>
                <option  <?php
            if (!empty($productInfo->brandId) && $productInfo->brandId == $each_info->brandId) {
                echo "selected";
            }
            ?>  value="<?php echo $each_info->brandId; ?>"><?php echo $each_info->brandName; ?></option>
<?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Unit<span style="color:red!important"> *</span></label>
    <div class="col-sm-6">
        <select  id="productUnit" name="unit"  class="chosen-select form-control"  data-placeholder="Search Brand">
            <option disabled selected>-Select-</option>
                <?php foreach ($unitList as $each_info): ?>
                <option  <?php
                if (!empty($productInfo->unitId) && $productInfo->unitId == $each_info->unit_id) {
                    echo "selected";
                }
                ?>  value="<?php echo $each_info->unit_id; ?>"><?php echo $each_info->unitTtile; ?></option>
<?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product Name<span style="color:red!important"> *</span></label>
    <div class="col-sm-6">
        <input type="text" id="productName" name="productName"  value="<?php echo $productInfo->productName; ?>" class="form-control" placeholder="Product Name" />
        <span id="errorMsg"  style="color:red;display: none;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-120"></i> &nbsp;&nbsp;This product name already exits!!</span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Purchases Price</label>
    <div class="col-sm-6">
        <input type="text" id="form-field-1" name="purchases_price" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php echo $productInfo->purchasesPrice; ?>" class="form-control" placeholder="Purchases Price" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Retail Price (MRP)</label>
    <div class="col-sm-6">
        <input type="text" id="form-field-1" name="salesPrice"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php echo $productInfo->retailprice; ?>" class="form-control" placeholder="Sales Price" />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Wholesale Price</label>
    <div class="col-sm-6">
        <input type="text" id="form-field-1" name="retailPrice"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  value="<?php echo $productInfo->salesPrice; ?>" class="form-control" placeholder="Wholesale Price" />
    </div>
</div>

<div class="clearfix form-actions" >
    <div class="col-md-offset-3 col-md-9">
        <button   id="subBtn" class="btn btn-info" type="submit">
            <i class="ace-icon fa fa-check bigger-110"></i>
            Update
        </button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn" class="close" data-dismiss="modal">
            <i class="ace-icon fa fa-undo bigger-110"></i>
            Reset
        </button>
    </div>
</div>
 </form>