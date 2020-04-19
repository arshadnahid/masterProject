<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 3/29/2020
 * Time: 9:57 AM
 */
?>
<?php
$added_menu = $this->Common_model->get_data_list_by_single_column('admin_role', 'user_role', $user_id);
$addPermitionSetupModule = 2101;
$editPermitionSetupModule = 2102;
$deletePermitionSetupModule = 2103;
$addPermitionSaleModule = 2110;
$editPermitionSaleModule = 2111;
$deletePermitionSaleModule = 2112;
$addPermitionAccountModule = 2113;
$editPermitionAccountModule = 2114;
$deletePermitionAccountModule = 2115;
$priviousDateEnrtyBlockSales = 2200;
$priviousDateEnrtyBlockPurchase = 2201;
$priviousDateEnrtyBlockAccount = 2202;

$purchasePriceBlock = 3001;
$salesPriceBlock = 3002;

?>
<div class="row">
    <div class="col-md-12">
        <br><br>
        <div class="col-sm-12">
            <table border="1" class="table table-bordered datatable" id="table-1">
                <tr>
                    <td style="text-align:right;">All Check &nbsp;</td>
                    <td style="color:green;">&nbsp; <input type="checkbox" id="checkAll"> All Check</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-3">
            <table border="1" class="table table-bordered datatable" id="table-1">
                <tr>
                    <td colspan="2" style="text-align: center;font-size: 20px !important;color:red;"><i
                                class="fa fa-cog"></i>&nbsp;&nbsp;Setup Module
                    </td>
                </tr>
                <tr>
                    <th>
                        <center style="text-align: center;">Make Permission</center>
                    </th>
                    <td>
                        <table class="table table-bordered">
                            <?php
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $addPermitionSetupModule,
                            );
                            $addPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $editPermitionSetupModule,//'2102',
                            );
                            $editPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $deletePermitionSetupModule,//'2103',
                            );
                            $deletePermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            ?>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($addPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $addPermitionSetupModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Add
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($editPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $editPermitionSetupModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Edit
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($deletePermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $deletePermitionSetupModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Delete
                                </th>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="display: none">
                    <th>
                        <center style="text-align: center;">Dashboard Permission</center>
                    </th>
                    <td>
                        <table class="table table-bordered">
                            <?php
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2104',
                            );
                            $companySummary = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2105',
                            );
                            $todaySummary = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2106',
                            );
                            $dailySummary = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2107',
                            );
                            $companySummaryGrape = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2108',
                            );
                            $inventoryProductStock = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2109',
                            );
                            $topSaleProductStock = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2119',
                            );
                            $incentive = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            ?>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($companySummary)) {
                                        echo 'checked';
                                    }
                                    ?> value="2104" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Company Summary
                                </th>
                            </tr>
                            <tr>
                                <th nowrap><input type="checkbox" <?php
                                    if (!empty($todaySummary)) {
                                        echo 'checked';
                                    }
                                    ?> value="2105" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Todays Summary
                                </th>
                            </tr>
                            <tr>
                                <th nowrap><input type="checkbox" <?php
                                    if (!empty($dailySummary)) {
                                        echo 'checked';
                                    }
                                    ?> value="2106" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Daily Summary
                                </th>
                            </tr>
                            <tr>
                                <th nowrap><input type="checkbox" <?php
                                    if (!empty($companySummaryGrape)) {
                                        echo 'checked';
                                    }
                                    ?> value="2107" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Summary Grape
                                </th>
                            </tr>
                            <tr>
                                <th nowrap><input type="checkbox" <?php
                                    if (!empty($inventoryProductStock)) {
                                        echo 'checked';
                                    }
                                    ?> value="2108" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Inventory Product Stock
                                </th>
                            </tr>
                            <tr>
                                <th nowrap><input type="checkbox" <?php
                                    if (!empty($topSaleProductStock)) {
                                        echo 'checked';
                                    }
                                    ?> value="2109" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Top Sales Product Stock
                                </th>
                            </tr>
                            <tr>
                                <th nowrap><input type="checkbox" <?php
                                    if (!empty($incentive)) {
                                        echo 'checked';
                                    }
                                    ?> value="2119" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Incentive
                                </th>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                foreach ($systemMenu as $key => $each_value):
                    $condition = array(
                        'parent_id' => $each_value->navigation_id,
                        'active' => 1,);
                    $sub_menu = $this->Common_model->get_data_list_by_many_columns('navigation', $condition);
                    ?>
                    <tr>
                        <th>
                            <center style="text-align: center;"><?php echo $each_value->label; ?></center>
                        </th>
                        <td>
                            <table class="table table-bordered">
                                <?php foreach ($sub_menu as $key => $sub_value): ?>
                                    <tr>
                                        <td nowrap><input <?php
                                            foreach ($added_menu as $each_added_menu) {
                                                if ($each_added_menu->navigation_id == $sub_value->navigation_id) {
                                                    echo "checked";
                                                }
                                            }
                                            ?> type="checkbox" value="<?php echo $sub_value->navigation_id; ?>"
                                               name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_value->label; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-sm-3">
            <table border="1" class="table table-bordered datatable" id="table-1">
                <tr>
                    <td colspan="2" style="text-align: center;font-size: 20px !important;color:red;"><i
                                class="fa fa-truck"></i>&nbsp;&nbsp;Sale Module
                    </td>
                </tr>
                <tr>
                    <th>
                        <center style="text-align: center;">Make Permission</center>
                    </th>
                    <td>
                        <table class="table table-bordered">
                            <?php
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $addPermitionSaleModule//'2110',
                            );
                            $addPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $editPermitionSaleModule,//'2111',
                            );
                            $editPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $deletePermitionSaleModule//'2112',
                            );
                            $deletePermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $priviousDateEnrtyBlockSales//'2112',
                            );
                            $priviousDateEnrtyBlockPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $conditionSalesPriceBlock = array(
                                'user_role' => $user_id,
                                'navigation_id' => $salesPriceBlock//'2112',
                            );
                            $salesPriceBlockPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $conditionSalesPriceBlock);
                            ?>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($addPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $addPermitionSaleModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Add
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($editPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $editPermitionSaleModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Edit
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($deletePermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $deletePermitionSaleModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Delete
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($priviousDateEnrtyBlockPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $priviousDateEnrtyBlockSales ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Privious
                                    Date Enrty Block
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($salesPriceBlockPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $salesPriceBlock ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Sales Price Block
                                </th>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                foreach ($salesMenu as $key => $each_value):
                    $condition = array(
                        'parent_id' => $each_value->navigation_id,
                        'active' => 1,);
                    $sub_menu = $this->Common_model->get_data_list_by_many_columns('navigation', $condition);
                    ?>
                    <tr>
                        <th>
                            <center style="text-align: center;"><?php echo $each_value->label; ?></center>
                        </th>
                        <td>
                            <table class="table table-bordered">
                                <?php foreach ($sub_menu as $key => $sub_value): ?>
                                    <tr>
                                        <td nowrap><input <?php
                                            foreach ($added_menu as $each_added_menu) {
                                                if ($each_added_menu->navigation_id == $sub_value->navigation_id) {
                                                    echo "checked";
                                                }
                                            }
                                            ?> type="checkbox" value="<?php echo $sub_value->navigation_id; ?>"
                                               name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_value->label; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-sm-3">
            <table border="1" class="table table-bordered datatable" id="table-1">
                <tr>
                    <td colspan="2" style="text-align: center;font-size: 20px !important;color:red;"><i
                                class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;Account Module
                    </td>
                </tr>
                <tr>
                    <th>
                        <center style="text-align: center;">Make Permission</center>
                    </th>
                    <td>
                        <table class="table table-bordered">
                            <?php
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $addPermitionAccountModule//'2113',
                            );
                            $addPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $editPermitionAccountModule//'2114',
                            );
                            $editPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $deletePermitionAccountModule//'2115',
                            );
                            $deletePermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);


                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $priviousDateEnrtyBlockAccount//'2112',
                            );
                            $priviousDateEnrtyBlockPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);

                            ?>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($addPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $addPermitionAccountModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Add
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($editPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $editPermitionAccountModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Edit
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($deletePermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $deletePermitionAccountModule ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Delete
                                </th>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($priviousDateEnrtyBlockPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $priviousDateEnrtyBlockAccount ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Privious
                                    Date Enrty Block
                                </th>
                            </tr>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                foreach ($accountMenu as $key => $each_value):
                    $condition = array(
                        'parent_id' => $each_value->navigation_id,
                        'active' => 1,);
                    $sub_menu = $this->Common_model->get_data_list_by_many_columns('navigation', $condition);
                    ?>
                    <tr>
                        <th>
                            <center style="text-align: center;"><?php echo $each_value->label; ?></center>
                        </th>
                        <td>
                            <table class="table table-bordered">
                                <?php foreach ($sub_menu as $key => $sub_value): ?>
                                    <tr>
                                        <td nowrap><input <?php
                                            foreach ($added_menu as $each_added_menu) {
                                                if ($each_added_menu->navigation_id == $sub_value->navigation_id) {
                                                    echo "checked";
                                                }
                                            }
                                            ?> type="checkbox" value="<?php echo $sub_value->navigation_id; ?>"
                                               name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_value->label; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-sm-3">
            <table border="1" class="table table-bordered datatable" id="table-1">
                <tr>
                    <td colspan="2" style="text-align: center;font-size: 20px !important;color:red;"><i
                                class="fa fa-sitemap"></i>&nbsp;&nbsp;Inventory Module
                    </td>
                </tr>
                <tr>
                    <th>
                        <center style="text-align: center;">Make Permission</center>
                    </th>
                    <td>
                        <table class="table table-bordered">
                            <?php
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2116',
                            );
                            $addPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2117',
                            );
                            $editPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => '2118',
                            );
                            $deletePermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);

                            $condition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $priviousDateEnrtyBlockPurchase//'2112',
                            );
                            $priviousDateEnrtyBlockPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $condition);
                            $purchasePriceBlockCondition = array(
                                'user_role' => $user_id,
                                'navigation_id' => $purchasePriceBlock//'2112',
                            );
                            $purchasePriceBlockPermition = $this->Common_model->get_single_data_by_many_columns('admin_role', $purchasePriceBlockCondition);

                            ?>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($addPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="2116" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Add
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($editPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="2117" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Edit
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($deletePermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="2118" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Delete
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($priviousDateEnrtyBlockPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $priviousDateEnrtyBlockPurchase ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Privious
                                    Date Enrty Block
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" <?php
                                    if (!empty($purchasePriceBlockPermition)) {
                                        echo 'checked';
                                    }
                                    ?> value="<?php echo $purchasePriceBlock ?>" name="navigation[]"/>&nbsp;&nbsp;&nbsp;&nbsp;Purchase Price Block
                                </th>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                foreach ($inventoryMenu as $key => $each_value):
                    $condition = array(
                        'parent_id' => $each_value->navigation_id,
                        'active' => 1,);
                    $sub_menu = $this->Common_model->get_data_list_by_many_columns('navigation', $condition);
                    ?>
                    <tr>
                        <th>
                            <center style="text-align: center;"><?php echo $each_value->label; ?></center>
                        </th>
                        <td>
                            <table class="table table-bordered">
                                <?php foreach ($sub_menu as $key => $sub_value): ?>
                                    <tr>
                                        <td nowrap><input <?php
                                            foreach ($added_menu as $each_added_menu) {
                                                if ($each_added_menu->navigation_id == $sub_value->navigation_id) {
                                                    echo "checked";
                                                }
                                            }
                                            ?> type="checkbox" value="<?php echo $sub_value->navigation_id; ?>"
                                               name="navigation[]"/>&nbsp;&nbsp;&nbsp;<?php echo $sub_value->label; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <table border="1" class="table table-bordered datatable" id="table-1">
        </table>
    </div>
</div>
<script>
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>
