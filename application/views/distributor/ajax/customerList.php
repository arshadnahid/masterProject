<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Customer</label>
    <div class="col-sm-9">
        <select name="customer_id"  class="chosen-select form-control  chosenRefesh" id="customerId" data-placeholder="Search Customer Name" required>
           
            <option <?php
if (!empty($customerId) && $customerId == 'all'): echo "selected";
endif;
?> value="all" >All</option>
                <?php foreach ($customerList as $key => $value): ?>
                <option <?php
                if (!empty($customerId) && $customerId == $value->customer_id) {
                    echo "selected";
                }
                    ?> value="<?php echo $value->customer_id; ?>"><?php echo $value->customerID . ' [ ' . $value->customerName . ' ] ' ?></option>
                <?php endforeach; ?>
        </select>
    </div>
</div>