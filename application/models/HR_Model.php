<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class HR_Model extends CI_Model {

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

    function getAllsalaryList(){
        $this->db->select("salary_info.*,employee.name,tb_department.DepartmentName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID');
        $this->db->where('salary_info.isDelete', 'N');
        $result = $this->db->get()->result();
        return $result;

    }

    function getAllsalaryByDateList(){
        $this->db->select("salary_info.*,salary_info.date,salary_info.year,,salary_info.month");
        $this->db->from("salary_info");
        $this->db->group_by("salary_info.date");
        $this->db->group_by("salary_info.year");
        $this->db->group_by("salary_info.month");
        $this->db->where('salary_info.isDelete', 'N');
        $result = $this->db->get()->result();
        return $result;

    }
    function get_employee(){
        $this->db->select("employee.*,employee.salaryType,employee.name,tb_department.DepartmentName,tb_designation.DesignationName,employee.salary");
        $this->db->from("employee");
        $this->db->join('tb_department', 'tb_department.DepartmentID=employee.department');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation');
        $this->db->where('employee.isDelete', 'N');
        $this->db->where('employee.empStatus', 'Active');
        $result = $this->db->get()->result();
        return $result;
    }
    function check_unique($month_id,$year_id)
    {
        $this->db->select('salary_info.*');
        $this->db->from('salary_info');
        $this->db->where("salary_info.month",$month_id);
        $this->db->where("salary_info.year",$year_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    function check_unique_catName($category_id,$model_id)
    {
        $this->db->select('product.*');
        $this->db->from('product');
        $this->db->where("product.category_id",$category_id);
        $this->db->where("product.modelID",$model_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function getAllsalaryByDateListView($date,$month,$year){
        $this->db->select("salary_info.*,employee.name,tb_department.DepartmentName,tb_designation.DesignationName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID');
        $this->db->where('salary_info.isDelete', 'N');
        $this->db->where('salary_info.date', $date);
        $this->db->where('salary_info.month', $month);
        $this->db->where('salary_info.year', $year );
        $result = $this->db->get()->result();
        return $result;

    }


    function getAllsalaryByDateListViewCash($date,$month,$year,$cash){
        $this->db->select("salary_info.*,employee.name,tb_department.DepartmentName,tb_designation.DesignationName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID');
        $this->db->where('salary_info.isDelete', 'N');
        $this->db->where('salary_info.date', $date);
        $this->db->where('salary_info.month', $month);
        $this->db->where('salary_info.year', $year );
        $this->db->where('salary_info.paymentMode', $cash );
        $result = $this->db->get()->result();
        return $result;

    }
    function getAllsalaryByDateListViewBank($date,$month,$year,$bank){
        $this->db->select("salary_info.*,employee.name,tb_department.DepartmentName,tb_designation.DesignationName");
        $this->db->from("salary_info");
        $this->db->join('employee', 'employee.id=salary_info.employeeID');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation');
        $this->db->join('tb_department', 'tb_department.DepartmentID=salary_info.departmentID');
        $this->db->where('salary_info.isDelete', 'N');
        $this->db->where('salary_info.date', $date);
        $this->db->where('salary_info.month', $month);
        $this->db->where('salary_info.year', $year );
        $this->db->where('salary_info.paymentMode', $bank );
        $result = $this->db->get()->result();
        return $result;

    }

    function getAllEmployeewiseD(){
        $this->db->select("employee.*,employee.salaryType,employee.name,tb_department.DepartmentName,tb_designation.DesignationName,employee.salary");
        $this->db->from("employee");
        $this->db->join('tb_department', 'tb_department.DepartmentID=employee.department');
        $this->db->join('tb_designation', 'tb_designation.DesignationID=employee.designation');
        $this->db->where('employee.isDelete', 'N');
        $this->db->order_by('empStatus','ASC');
        $result = $this->db->get()->result();
        return $result;
    }

    function salaryInfoDelete($date,$month,$year){
        $condition = array(
            'date' => $date,
            'month'   => $month,
            'year'   => $year
        );
        $this->db->where($condition);
        return $this->db->delete('salary_info');
    }
    function getBranchInfo($company)
    {
        $sql = "SELECT c.companyName,b.branch_code,b.branch_name,b.phone,b.branch_address,b.remarks,b.branch_id FROM branch as b
LEFT JOIN tbl_distributor as c ON c.dist_id = b.company_id
where b.is_active = 1 AND b.company_id = '$company'
ORDER BY b.branch_id DESC";
        $result = $this->db->query($sql)->result();
        return $result;
    }


}

?>