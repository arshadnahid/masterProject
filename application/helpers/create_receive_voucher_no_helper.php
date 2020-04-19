<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/23/2019
 *
 * Time: 2:15 PM
 */
if (!function_exists('create_receive_voucher_no')) {

    function create_receive_voucher_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(Accounts_VoucherMst_AutoID) AS inserted_voucher_number FROM ac_accounts_vouchermst WHERE AccouVoucherType_AutoID in(1)";
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_voucher_number'])):
            $totalPurchases = $result['inserted_voucher_number'];
        else:
            $totalPurchases = 0;
        endif;

        $purchase_invoice_no = "RV" . date('y') . date('m') . str_pad(($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        return $purchase_invoice_no;

    }
}