<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/15/2019
 * Time: 10:07 AM
 */

if (!function_exists('create_sales_invoice_no')) {

    function create_sales_invoice_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(sales_invoice_id) AS inserted_sales_invoice_number FROM sales_invoice_info WHERE invoice_for!='3'";//WHERE is_active='Y' AND is_delete='N'
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_sales_invoice_number'])):
            $totalSale = $result['inserted_sales_invoice_number'];
        else:
            $totalSale = 0;
        endif;

        $sales_invoice_no = "SID" . date("y") . date("m") . str_pad(($totalSale) + 1, 4, "0", STR_PAD_LEFT);
        return $sales_invoice_no;

    }
}
if (!function_exists('create_warranty_claim_no')) {

    function create_warranty_claim_no($option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $sql = "SELECT COUNT(sales_invoice_id) AS inserted_sales_invoice_number FROM sales_invoice_info WHERE invoice_for='3'";//WHERE is_active='Y' AND is_delete='N'
        //$result = row_array($sql);

        $query = $CI->db->query($sql);
        $result = $query->row_array();

        if (!empty($result['inserted_sales_invoice_number'])):
            $totalSale = $result['inserted_sales_invoice_number'];
        else:
            $totalSale = 0;
        endif;

        $sales_invoice_no = "WCID" . date("y") . date("m") . str_pad(($totalSale) + 1, 4, "0", STR_PAD_LEFT);
        return $sales_invoice_no;

    }
}