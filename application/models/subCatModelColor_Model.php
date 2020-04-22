<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class subCatModelColor_Model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->tableName = 'files';
    }


    public function getDepartmentList(){
        $this->db->select('*');
        $this->db->from('tb_department');

        $this->db->order_by('tb_department.DepartmentName', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }


    public function getSubCatList(){
        $this->db->select('*');
        $this->db->from('tb_subCategory');

        $this->db->order_by('tb_subCategory.SubCatName', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }
    public function getSingleSubCat($editId){
        $this->db->select('*');
        $this->db->from('tb_subCategory');
        $this->db->where('SubCatID',$editId);
        $result = $this->db->get()->result();
        return $result;

    }

    public function publishedSubCat($id) {
        $this->db->set('IsActive', 1);
        $this->db->where('SubCatID', $id);
        return $this->db->update('tb_subCategory');
    }

    public function unpublishedSubCat($id) {

        $this->db->set('IsActive', 0);
        $this->db->where('SubCatID', $id);
        return $this->db->update('tb_subCategory');
    }

    public function getSingleModel($editId){
        $this->db->select('*');
        $this->db->from('tb_model');
        $this->db->where('ModelID',$editId);
        $this->db->order_by('tb_model.Model', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }
    public function publishedModel($id) {
        $this->db->set('IsActive', 1);
        $this->db->where('ModelID', $id);
        return $this->db->update('tb_model');
    }

    public function unpublishedModel($id) {

        $this->db->set('IsActive', 0);
        $this->db->where('ModelID', $id);
        return $this->db->update('tb_model');
    }
    public function getColorList(){
        $this->db->select('*');
        $this->db->from('tb_color');
        $this->db->order_by('tb_color.Color', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }

    public function getSingleColor($editId){
        $this->db->select('*');
        $this->db->from('tb_color');
        $this->db->where('ColorID',$editId);
        $this->db->order_by('tb_color.Color', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }
    public function publishedColor($id) {
        $this->db->set('IsActive', 1);
        $this->db->where('ColorID', $id);
        return $this->db->update('tb_color');
    }

    public function unpublishedColor($id) {

        $this->db->set('IsActive', 0);
        $this->db->where('ColorID', $id);
        return $this->db->update('tb_color');
    }
    public function getSizeList(){
        $this->db->select('*');
        $this->db->from('tb_size');
        $this->db->order_by('tb_size.Size', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }
    public function getSingleSize($editId){
        $this->db->select('*');
        $this->db->from('tb_size');
        $this->db->where('SizeID',$editId);
        $this->db->order_by('tb_size.Size', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }
    public function publishedSize($id) {
        $this->db->set('IsActive', 1);
        $this->db->where('SizeID', $id);
        return $this->db->update('tb_size');
    }

    public function unpublishedSize($id) {

        $this->db->set('IsActive', 0);
        $this->db->where('SizeID', $id);
        return $this->db->update('tb_size');
    }


    public function getModelList(){
        $this->db->select('*');
        $this->db->from('tb_Model');
        $this->db->order_by('tb_Model.Model', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }

    public function checkDepatmentId($deleteId){
        $this->db->select('*');
        $this->db->from('employee');
        $this->db->where('department', $deleteId);
        $result = $this->db->get()->result();
        return $result;
    }

    public function checkDesignationId($deleteId){
        $this->db->select('*');
        $this->db->from('employee');
        $this->db->where('designation', $deleteId);
        $result = $this->db->get()->result();
        return $result;
    }

    public function getDesinationList(){
        $this->db->select('*');
        $this->db->from('tb_designation');

        $this->db->order_by('tb_designation.DesignationName', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }

    public function getSingleData($editId){
        $this->db->select('*');
        $this->db->from('tb_department');
        $this->db->where('DepartmentID',$editId);
        $result = $this->db->get()->result();
        return $result;

    }


    public function publishedDepartment($id) {
        $this->db->set('IsActive', 1);
        $this->db->where('DepartmentID', $id);
        return $this->db->update('tb_department');
    }
    public function getSingleDataDesignation($editId){
        $this->db->select('*');
        $this->db->from('tb_designation');
        $this->db->where('DesignationID',$editId);
        $result = $this->db->get()->result();
        return $result;

    }

    public function unpublishedDepartment($id) {

        $this->db->set('IsActive', 0);
        $this->db->where('DepartmentID', $id);
        return $this->db->update('tb_department');
    }


    public function publisheddesignation($id) {
        $this->db->set('IsActive', 1);
        $this->db->where('DesignationID', $id);
        return $this->db->update('tb_designation');
    }

    public function unpublisheddesignation($id) {

        $this->db->set('IsActive', 0);
        $this->db->where('DesignationID', $id);
        return $this->db->update('tb_designation');
    }




    public function getRows($id = ''){
        $this->db->select('id,file_name,uploaded_on');
        $this->db->from('files');
        if($id){
            $this->db->where('id',$id);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            $this->db->order_by('uploaded_on','desc');
            $query = $this->db->get();
            $result = $query->result_array();
        }
        return !empty($result)?$result:false;
    }

    /*
     * Insert file data into the database
     * @param array the data for inserting into the table
     */
    public function insert($data = array()){
        $insert = $this->db->insert_batch('files',$data);
        return $insert?true:false;
    }

    function getSearch($name)
    {
        $select = $this->db->query("SELECT products.title,product_picture.picture FROM products
            LEFT JOIN product_picture
        ON products.id=product_picture.pid
        WHERE title LIKE '%".$name."%' LIMIT 5 ");
        return $select->result_array();
    }
    function limitSearch(){
        $this->db->select('p.id,p.title, pic.id pic_id, pic.picture');
        $this->db->from('products p');
        $this->db->join('product_picture pic', 'p.id=pic.pid', 'left');
        $this->db->like('title', $query);
        $this->db->limit(10);
        $this->db->group_by("p.id");
        $this->db->order_by("p.id", "DESC");
        return $this->db->get()->result_array();
    }


}

?>