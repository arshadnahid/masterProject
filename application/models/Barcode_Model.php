<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Barcode_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
    }
    public function getPublicProductBrand($distId)
    {
        $this->db->select("brandId,dist_id,brandName");
        $this->db->from("brand");
        /*$this->db->group_start();
        $this->db->where('dist_id', $distId);
        $this->db->or_where('dist_id', 1);
        $this->db->group_end();*/

        $this->db->order_by('brandName', 'ASE');
        $getProductList = $this->db->get()->result();
        return $getProductList;
    }

    public function getPublicProductCat($distId)
    {
        $this->db->select("category_id,dist_id,title");
        $this->db->from("productcategory");
        /*$this->db->group_start();
        $this->db->where('dist_id', $distId);
        $this->db->or_where('dist_id', 1);
        $this->db->group_end();*/
        $this->db->where('is_active', 'Y');
        $this->db->where('is_delete', 'N');
        $this->db->order_by('title', 'ASE');
        $getProductList = $this->db->get()->result();
        return $getProductList;
    }

     public function getPublicProduct($distId, $catid)
    {
        $this->db->select('product.product_id,productcategory.title as productCat,product.brand_id,product.category_id,product.productName,product.dist_id,product.status,brand.brandName,unit.unitTtile');
        $this->db->from('product');
        $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
        $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
        $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
        $this->db->group_start();
        $this->db->where('product.dist_id', $distId);
        $this->db->or_where('product.dist_id', 1);
        $this->db->group_end();
        $this->db->where('product.status', 1);
        if ($catid == 'all') {
        } else {
            $this->db->where('product.category_id', $catid);
        }
        $this->db->order_by('product.productName', 'ASE');
        $getProductList = $this->db->get()->result();
        return $getProductList;
    }



}
?>