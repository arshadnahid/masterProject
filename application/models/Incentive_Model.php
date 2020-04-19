<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Incentive_Model extends CI_Model
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


    function getAllNewProduct(){
        $this->db->select("*");
        $this->db->from("incentive_info");
        $result = $this->db->get()->result();

        return $result;

  }

 function saleDetailsProduct(){
        $this->db->select("sales_details.product_id,sum(sales_details.quantity),product.productName,brand.brandName,productcategory.title");
        $this->db->from("sales_details");
        $this->db->join('product', 'product.product_id=sales_details.product_id');
        $this->db->join('productcategory', 'productcategory.category_id=product.category_id');
        $this->db->join('brand', 'brand.brandId=product.brand_id');
        $this->db->group_by('sales_details.product_id');
        $this->db->group_by('product.productName');
        $this->db->group_by('brand.brandName');
        $this->db->group_by('productcategory.title');
        $this->db->where('productcategory.category_id !=', 1);



        $result = $this->db->get()->result();
        return $result;

 }

 function getAllIncentiveList(){
   $sql=  "SELECT `sales_details`.`product_id`, `productcategory`.`title` AS 'CategoryName',`brand`.`brandName` AS 'BrandName', `product`.`productName` AS 'ProductName',
`incentive_details`.`quantity` AS 'TargetQty',sum(`sales_details`.`quantity`) AS 'Lifting',(`incentive_details`.`quantity`) - (sum(`sales_details`.`quantity`)) AS 'BalanceQty'
FROM
 `incentive_details` LEFT JOIN

`sales_details` ON `incentive_details`.`product_id`=`sales_details`.`product_id`
LEFT JOIN  `product` ON `product`.`product_id`=`sales_details`.`product_id`
LEFT JOIN  `productcategory` ON `productcategory`.`category_id`=`product`.`category_id`
LEFT JOIN  `brand` ON `brand`.`brandId`=`product`.`brand_id`
where `productcategory`.`category_id`!=1
group by `sales_details`.`product_id`,`product`.`productName`, `brand`.`brandName`, `productcategory`.`title`,`incentive_details`.`quantity` ";

 $query = $this->db->query($sql);
        $result = $query->result();
        return $result;

 }


    function getAllProductById($id)
    {

      $this->db->select("incentive_details.*,product.productName,brand.brandName,incentive_info.invoice_no,incentive_info.from_date,incentive_info.to_date");
         //$this->db->select("*");
        $this->db->from("incentive_details");

        $this->db->join('product', 'product.product_id=incentive_details.product_id');

        $this->db->join('brand', 'brand.brandId=product.brand_id');
        $this->db->join('incentive_info', 'incentive_info.incentive_info_id=incentive_details.incentive_info_id');

        $this->db->where('incentive_details.incentive_info_id', $id);

        $result = $this->db->get()->result();

        return $result;


    }


    public function geteditNewP($id){
        $this->db->select("incentive_details.*,product.productName,productcategory.title");
        $this->db->from("incentive_details");
        $this->db->join('product', 'product.product_id=new_product_add.product_id');
        $this->db->join('productcategory', 'productcategory.category_id=product.category_id');
        $this->db->where('incentive_details.incentive_info_id', $id);
        $result = $this->db->get()->result();

        return $result;
    }


}
?>