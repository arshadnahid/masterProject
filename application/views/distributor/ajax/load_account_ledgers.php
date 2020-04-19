<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/11/2020
 * Time: 12:10 PM
 */
?>


<?php
if ($ledgerFor == "paymentVoucherAdd") { ?>
    <select class="chosen-select form-control paytoAccount chosenRefesh" id="form-field-select-3"
            data-placeholder="Search by Account Head" >
        <option value=""></option>
        <?php
        foreach ($accountHeadList as $key => $head) {
            ?>
            <optgroup
                    label="<?php echo get_phrase($head['parentName']); ?>">
                <?php
                foreach ($head['Accountledger'] as $eachLedger) :
                    ?>
                    <option paytoAccountName="<?php echo $eachLedger['parent_name']; ?>"
                            paytoAccountCode="<?php echo $eachLedger['code']; ?>"
                            value="<?php echo $eachLedger["id"]; ?>"><?php echo get_phrase($eachLedger['parent_name']) . " ( " . $eachLedger['code'] . " ) "; ?></option>
                <?php endforeach; ?>
            </optgroup>
            <?php
        }
        ?>
    </select>
<?php } else {
    ?>


    <select style="width:100%!important;" name="accountCrPartial"
            class="chosen-select   checkAccountBalance chosenRefesh" id="partialHead"
            data-placeholder="Select Account Head">
        <option value=""></option>
        <?php
        foreach ($accountHeadList as $key => $head) {

            ?>
            <optgroup
                    label="<?php echo get_phrase($head['parentName']); ?>">
                <?php
                foreach ($head['Accountledger'] as $key2 => $eachLedger) :
                    /*log_message('error','this is the account hade list'.print_r($eachLedger["parent_name"],true));*/
                    ?>
                    <option <?php
                    if ($head['parent_id'] == '28') {
                        echo "selected";
                    }
                    ?> value="<?php echo $eachLedger["id"]; ?>"><?php echo get_phrase($eachLedger["parent_name"]) . " ( " . $eachLedger["code"] . " ) "; ?></option>
                <?php endforeach; ?>
            </optgroup>
            <?php

        }
        ?>
    </select>
    <?php
}
?>