<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 4/21/2019
 * Time: 9:10 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchases_Model extends CI_Model
{


    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;


    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }


    function checkInvoiceIdAndDistributor($distId, $invoiceId) {
        $this->db->select("*");
        $this->db->from("purchase_invoice_info");
        $this->db->where("dist_id", $distId);
        $this->db->where("purchase_invoice_id", $invoiceId);
        $dataExits = $this->db->get()->row();
        if (!empty($dataExits)) {
            return true;
        }
    }
}