<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 11/24/2019
 * Time: 12:05 PM
 */


if (!function_exists('transportation_dropdown')) {
    function transportation_dropdown($selected_branch_id=null,$option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $branchCondition = array(
            'is_active' => 1
        );
        $all_branch = $CI->Common_model->get_data_list_by_many_columns('branch', $branchCondition);

        $option = '';

        $option .= '<option value="0" disabled selected>Select Branch</option>';

        foreach ($all_branch as $branch) {
            if($selected_branch_id == $branch->branch_id){
                $selected='selected';
            }else{
                $selected='';
            }
            $option .= "<option value='$branch->branch_id' $selected>". $branch->branch_name. "</option>";

        }
        return $option;

    }
}