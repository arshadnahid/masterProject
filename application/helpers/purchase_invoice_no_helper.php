<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/15/2019
 * Time: 11:01 AM
 */
if (!function_exists('create_purchase_invoice_no')) {

    function create_purchase_invoice_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(purchase_invoice_id) AS inserted_purchase_invoice_number FROM purchase_invoice_info WHERE `for` !=3 ";
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_purchase_invoice_number'])):
            $totalPurchases = $result['inserted_purchase_invoice_number'];
        else:
            $totalPurchases = 0;
        endif;

        $purchase_invoice_no = "PUV" . date('y') . date('m') . str_pad(($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        return $purchase_invoice_no;

    }
}

if (!function_exists('create_warranty_receipt_no')) {

    function create_warranty_receipt_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(purchase_invoice_id) AS inserted_purchase_invoice_number FROM purchase_invoice_info WHERE `for` =3 ";
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_purchase_invoice_number'])):
            $totalPurchases = $result['inserted_purchase_invoice_number'];
        else:
            $totalPurchases = 0;
        endif;

        $purchase_invoice_no = "WRID" . date('y') . date('m') . str_pad(($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);
        return $purchase_invoice_no;

    }
}