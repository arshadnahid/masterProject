

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */


if ( ! function_exists('getProductUnit'))
{



function getProductUnit($productId) {
    $CI	=&	get_instance();
    $CI->load->database();




    $sql = "SELECT * FROM product WHERE product_id = $productId LIMIT 1";
    $query=$CI->db->query($sql);
    $result=$query->result();
    //$result = row_array($sql);
    if (!empty($result['unit_id'])):
        return $result['unit_id'];
    else:
        return 1;
    endif;
}
}
if ( ! function_exists('getUnitName'))
{

function getUnitName($productId) {
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