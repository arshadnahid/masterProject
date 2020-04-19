<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/23/2019
 *
 * Time: 2:15 PM
 */
if (!function_exists('account_ledger_dropdown')) {
    function account_ledger_dropdown($all_ledger_option = null, $selected_ledger_id = null, $option = '')
    {
        $CI =& get_instance();
        $CI->load->database();



        $branchCondition = array(
            'is_active' => "Y",
            'is_delete' => "N",
            'posted' => 1,
        );
        $accountHeadList = $CI->Common_model->Common_model->getAccountHeadNew();

       /* $option = '';
        $option .= '<option value="0" disabled selected>Select Branch</option>';
        if ($all_branch_option != null) {
            if ($selected_branch_id == 'all') {
                $selectedAll = 'selected';
            } else {
                $selectedAll = '';
            }
            $option .= "<option value='all' $selectedAll>" . 'All Branch' . "</option>";
        }
        foreach ($all_branch as $branch) {

            if ($selected_branch_id == $branch->branch_id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $option .= "<option value='$branch->branch_id' $selected>" . $branch->branch_name . "</option>";
        }*/
        return $option;
    }
}