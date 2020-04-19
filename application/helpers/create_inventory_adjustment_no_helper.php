<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/23/2019
 * Time: 2:15 PM
 */
if (!function_exists('create_inventory_adjustment_no')) {

    function create_inventory_adjustment_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(inv_adjustment_no) AS inserted_voucher_number FROM inventory_adjustment_info ";
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_voucher_number'])):
            $totalInventoryAdj = $result['inserted_voucher_number'];
        else:
            $totalInventoryAdj = 0;
        endif;

        $adjustment_no_no = "INVADJ" . date('y') . date('m') . str_pad(($totalInventoryAdj) + 1, 4, "0", STR_PAD_LEFT);
        return $adjustment_no_no;

    }
}