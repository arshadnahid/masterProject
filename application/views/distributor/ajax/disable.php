<?php if ($payType == 1): ?>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Accounts <span style="color:red;"> *</span></label>
        <div class="col-sm-6">
            <input type="text" name="miscellaneous" value="<?php
    if (!empty($userId)) {
        echo $userId;
    }
    ?>" class="form-control" placeholder="Type Accounts  Name" required readonly/>
        </div>
    </div>
<?php elseif ($payType == 2): ?>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer <span style="color:red;"> *</span></label>
        <div class="col-sm-6">
            <select disabled name="customer_id"  class="chosen-select form-control  chosenRefesh" id="form-field-select-3" data-placeholder="Search Customer Name" required>
                <option value=""></option>
                <?php foreach ($payList as $key => $value): ?>
                    <option <?php
            if (!empty($userId) && $userId == $value->customer_id) {
                echo "selected";
            }
                    ?> value="<?php echo $value->customer_id; ?>"><?php echo $value->customerID . ' [ ' . $value->customerName . ' ] ' ?></option>
                    <?php endforeach; ?>

            </select>
        </div>
    </div>

<?php else: ?>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Supplier  <span style="color:red;"> *</span></label>
        <div class="col-sm-6">
            <select disabled name="supplier_id"  class="chosen-select form-control chosenRefesh" id="form-field-select-3" data-placeholder="Search  Supplier Name" required>
                <option value=""></option>
                <?php foreach ($payList as $key => $value): ?>
                    <option <?php
            if (!empty($userId) && $userId == $value->sup_id) {
                echo "selected";
            }
                    ?> value="<?php echo $value->sup_id; ?>"><?php echo $value->supID . ' [ ' . $value->supName . ' ] ' ?></option>
                    <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>
