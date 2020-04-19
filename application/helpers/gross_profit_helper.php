

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('gross_profit_by_invoice_id'))
{

    function gross_profit_by_invoice_id($sales_invoice_id) {
        $CI	=&	get_instance();
        $CI->load->database();
        $sql = "SELECT u.unitTtile FROM product p LEFT JOIN unit u ON p.unit_id = u.unit_id WHERE p.product_id = $productId LIMIT 1";
        //$result = row_array($sql);
        $query=$CI->db->query($sql);
        $result=$query->result();
        if (!empty($result['unitTtile'])):
            return $result['unitTtile'];
        else:
            return 'Pcs';
        endif;
    }}