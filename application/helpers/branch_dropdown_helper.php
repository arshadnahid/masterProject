<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/23/2019
 *
 * Time: 2:15 PM
 */
if (!function_exists('branch_dropdown')) {
    function branch_dropdown($all_branch_option = null, $selected_branch_id = null, $option = '')
    {
        $CI =& get_instance();
        $CI->load->database();

        if ($selected_branch_id == null) {
            $selected_branch_id = 1;
        } else {
            $selected_branch_id = $selected_branch_id;
        }

        $branchCondition = array(
            'is_active' => 1
        );
        $all_branch = $CI->Common_model->get_data_list_by_many_columns('branch', $branchCondition);
        $option = '';
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
        }
        return $option;
    }
}