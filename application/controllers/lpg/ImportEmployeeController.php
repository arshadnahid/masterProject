<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ImportEmployeeController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Common_model', 'Finane_Model', 'Inventory_Model', 'Sales_Model');
        $this->load->model('Common_model');
        $this->load->model('HR_Model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
        $this->load->library('excel');
    }


    function  employeeImport_BKP()
    {
        if (isPostBack()) {
            if (!empty($_FILES['proImport']['name']))://supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = $this->project . '_ProductImport' . date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //if ($row != 0):
                    if ($row != 0):
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $nameId = isset($readRowData[0]) ? $readRowData[0] : ''; //get name id
                        $employeeId = isset($readRowData[1]) ? $readRowData[1] : '';
                        $fathersName = isset($readRowData[2]) ? $readRowData[2] : '';
                        $mothersName = isset($readRowData[3]) ? $readRowData[3] : '';
                        $presentAddress= isset($readRowData[4]) ? $readRowData[4] : '';
                        $permanentAddress = isset($readRowData[5]) ? $readRowData[5] : '';
                        $dateOfBirth = isset($readRowData[6]) ? $readRowData[6] : '';
                        $nationalId = isset($readRowData[7]) ? $readRowData[7] : '';
                        $personalMobile = isset($readRowData[8]) ? $readRowData[8] : '';
                        $joiningDate = isset($readRowData[9]) ? $readRowData[9] : '';
                        $salary = isset($readRowData[10]) ? $readRowData[10] : '';
                        $education = isset($readRowData[11]) ? $readRowData[11] : '';
                        $department = isset($readRowData[12]) ? $readRowData[12] : ''; //get brand id
                        $designation = isset($readRowData[13]) ? $readRowData[13] : ''; //get unit id
                        /* check duplicate supplier id end */
                        //product Category
                        if ($nameId != '' || $salary != '' || $presentAddress != '') {

                            $data['name'] = $nameId;
                            $data['employeeId'] = $employeeId;
                            $data['fathersName'] = $fathersName;
                            $data['mothersName'] = $mothersName;
                            $data['presentAddress'] = $presentAddress;
                            $data['permanentAddress'] = $permanentAddress;
                            $data['dateOfBirth'] = $dateOfBirth;
                            $data['nationalId'] = $nationalId;
                            $data['joiningDate'] = $joiningDate;
                            $data['personalMobile'] = $personalMobile;
                            $data['salary'] = $salary;
                            $data['education'] = $education;
                            $deptCondition = array('DepartmentName' => $department);
                            $deptInfo = $this->Common_model->rowResult('tb_department', $deptCondition, $this->dist_id);
                            $data['department'] = $deptInfo->DepartmentID;
                            $data['deptName'] = $department;

                            $desiCondition = array('DesignationName' => $designation);
                            $desiInfo = $this->Common_model->rowResult('tb_designation', $desiCondition, $this->dist_id);
                            $data['designation'] = $desiInfo->DesignationID;
                            $data['desiName'] = $designation;
                            $data['dist_id'] = $this->dist_id;
                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                        }
                        //endif;
                    endif;
                    $row++;
                }
                if (!empty($storeData)):
                    $this->db->truncate('employeeImport');
                    $this->db->insert_batch('employeeImport', $storeData);
                endif;
                if ($this->db->affected_rows() > 0):
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                else:
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                endif;
            endif;
        }
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee List');
        $data['link_page_url'] = $this->project.'/employeeList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['emplyeeList'] = $this->Inventory_Model->getImportEmployee($this->dist_id);
        $data['emplyeeListSameId'] = $this->Inventory_Model->emplyeeListSameId($this->dist_id);
        //echo $this->db->last_query();die;
        $data['title'] = 'Employee Import';
        $data['mainContent'] = $this->load->view('distributor/inventory/import/employeeImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function updateEmployeeInfo()
    {

        $importid = $this->input->post('importid');
        $data['employeeEdit'] = $this->Common_model->get_single_data_by_single_column('employeeimport', 'importid', $importid);
        $data['depatment'] = $this->Common_model->getPublicDep($this->dist_id);
        $data['designation'] = $this->Common_model->getPublicDesi($this->dist_id);
        $data['unitList'] = $this->Common_model->getPublicUnit($this->dist_id);
        $data['catList'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        return $this->load->view('distributor/ajax/importEmployee', $data);
    }

    function updateImportEmployee()
    {
        $updateId = $this->input->post('updateId');
        $data['name'] = $this->input->post('employeeName');
        $data['employeeId'] = $this->input->post('employeeNo');
        $data['fathersName'] = $this->input->post('fathersName');
        $data['mothersName'] = $this->input->post('mothersName');
        $data['presentAddress'] = $this->input->post('presentAddress');
        $data['permanentAddress'] = $this->input->post('permanentAddress');
        $data['dateOfBirth'] = $this->input->post('dateOfBirth');
        $data['nationalId'] = $this->input->post('nationalId');
        $data['personalMobile'] = $this->input->post('personalMobile');
        $data['joiningDate'] = $this->input->post('joiningDate');
        $data['salary'] = $this->input->post('salary');
        $data['education'] = $this->input->post('education');
        $data['department'] = $this->input->post('department');
        $data['deptName'] = $this->Common_model->tableRow('tb_department', 'DepartmentID', $data['department'])->DepartmentName;
        $data['designation'] = $this->input->post('designation');
        $data['desiName'] = $this->Common_model->tableRow('tb_designation', 'DesignationID', $data['designation'])->DesignationName;
        $this->Common_model->update_data('employeeImport', $data, 'importid', $updateId);
        $msg = $this->config->item("save_success_message");
        $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/employeeImport'));
    }
    function  employeeImport()
    {
        if (isPostBack()) {
            if (!empty($_FILES['proImport']['name'])){//supplier list import operation start this block
                $config['upload_path'] = './uploads/import/setup/';
                $config['allowed_types'] = 'xl|txt|csv|mdb';
                $config['file_name'] = $this->project . '_ProductImport' . date("Y-m-d");
                $this->load->library('upload');
                $this->upload->initialize($config);
                $upload = $this->upload->do_upload('proImport');
                $data = $this->upload->data();
                $this->load->helper('file');
                $file = $_FILES['proImport']['tmp_name'];
                $importFile = fopen($file, "r");
                $row = 0;
                $storeData = array();
                while (($readRowData = fgetcsv($importFile, 1000, ",")) !== false) {
                    //if ($row != 0):
                    if ($row != 0){
                        unset($data); //empty array;
                        /* check duplicate supplier id  start */
                        $nameId = isset($readRowData[0]) ? $readRowData[0] : ''; //get name id
                        $employeeId = isset($readRowData[1]) ? $readRowData[1] : '';
                        $fathersName = isset($readRowData[2]) ? $readRowData[2] : '';
                        $mothersName = isset($readRowData[3]) ? $readRowData[3] : '';
                        $presentAddress= isset($readRowData[4]) ? $readRowData[4] : '';
                        $permanentAddress = isset($readRowData[5]) ? $readRowData[5] : '';
                        $dateOfBirth = isset($readRowData[6]) ? $readRowData[6] : '';
                        $nationalId = isset($readRowData[7]) ? $readRowData[7] : '';
                        $personalMobile = isset($readRowData[8]) ? $readRowData[8] : '';
                        $joiningDate = isset($readRowData[9]) ? $readRowData[9] : '';
                        $salary = isset($readRowData[10]) ? $readRowData[10] : '';
                        $education = isset($readRowData[11]) ? $readRowData[11] : '';
                        $department = isset($readRowData[12]) ? $readRowData[12] : ''; //get brand id
                        $designation = isset($readRowData[13]) ? $readRowData[13] : ''; //get unit id
                        /* check duplicate supplier id end */
                        //product Category
                        if ($nameId != '' || $salary != '' || $presentAddress != '') {

                            $data['name'] = $nameId;
                            $data['employeeId'] = $employeeId;
                            $data['fathersName'] = $fathersName;
                            $data['mothersName'] = $mothersName;
                            $data['presentAddress'] = $presentAddress;
                            $data['permanentAddress'] = $permanentAddress;
                            $data['dateOfBirth'] = $dateOfBirth;
                            $data['nationalId'] = $nationalId;
                            $data['joiningDate'] = $joiningDate;
                            $data['personalMobile'] = $personalMobile;
                            $data['salary'] = $salary;
                            $data['education'] = $education;
                            $deptCondition = array('DepartmentName' => $department);
                            $deptInfo = $this->Common_model->rowResult('tb_department', $deptCondition, $this->dist_id);
                            $data['department'] = $deptInfo->DepartmentID;
                            $data['deptName'] = $department;

                            $desiCondition = array('DesignationName' => $designation);
                            $desiInfo = $this->Common_model->rowResult('tb_designation', $desiCondition, $this->dist_id);
                            $data['designation'] = $desiInfo->DesignationID;
                            $data['desiName'] = $designation;
                            $data['dist_id'] = $this->dist_id;
                            //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                            $storeData[] = $data; //store each single row;
                        }
                        //endif;
                    }
                    $row++;
                }
                if (!empty($storeData)){
                    $this->db->truncate('employeeImport');
                    $this->db->insert_batch('employeeImport', $storeData);
                }
                if ($this->db->affected_rows() > 0){
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                } else{
                    $msg = 'Your csv file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                }
            }
            elseif (!empty($_FILES['employeeImportExcel']['name'])){//supplier list import operation sta


                $this->load->library('excel');
                $this->load->helper('file');
                $file = $_FILES['employeeImportExcel']['tmp_name'];
                $object = PHPExcel_IOFactory::load($file);
                $storeData = array();

                foreach($object->getWorksheetIterator() as $worksheet) {
                    //if ($row != 0):
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++){

                        $condition = array(
                            'employeeId' => trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()),
                            'dist_id' => $this->dist_id
                        );
                        $exitsID = $this->Common_model->get_single_data_by_many_columns('employeeimport', $condition);
                        if(empty($exitsID)){
                            $nameId = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()); //get name id
                            $employeeId = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                            $fathersName = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                            $mothersName = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                            $presentAddress= trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                            $permanentAddress = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                            $dateOf = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $dateOfBir = PHPExcel_Shared_Date::ExcelToPHP($dateOf);
                            $dateOfBirth=date($format = "Y-m-d",$dateOfBir);
                            $nationalId = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                            $personalMobile = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                            $joining = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $joiningDat = PHPExcel_Shared_Date::ExcelToPHP($joining);
                            $joiningDate=date($format = "Y-m-d",$joiningDat);
                            $salary = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                            $education = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                            $department = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue()); //get brand id
                            $designation = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                            // echo "<pre>";
                            // print_r($joiningDate);
                            // exit();
                            //get unit id
                            /* check duplicate supplier id end */
                            //product Category
                            if ($nameId != '' || $salary != '' || $presentAddress != '') {

                                $data['name'] = $nameId;
                                $data['employeeId'] = $employeeId;
                                $data['fathersName'] = $fathersName;
                                $data['mothersName'] = $mothersName;
                                $data['presentAddress'] = $presentAddress;
                                $data['permanentAddress'] = $permanentAddress;
                                $data['dateOfBirth'] = $dateOfBirth;
                                $data['nationalId'] = $nationalId;
                                $data['joiningDate'] = $joiningDate;
                                $data['personalMobile'] = $personalMobile;
                                $data['salary'] = $salary;
                                $data['education'] = $education;
                                $deptCondition = array('DepartmentName' => $department);
                                $deptInfo = $this->Common_model->rowResult('tb_department', $deptCondition, $this->dist_id);
                                $data['department'] = $deptInfo->DepartmentID;
                                $data['deptName'] = $department;

                                $desiCondition = array('DesignationName' => $designation);
                                $desiInfo = $this->Common_model->rowResult('tb_designation', $desiCondition, $this->dist_id);
                                $data['designation'] = $desiInfo->DesignationID;
                                $data['desiName'] = $designation;
                                $data['dist_id'] = $this->dist_id;
                                //if (!empty($data['catName']) && !empty($data['brandName']) && !empty($data['unitName'])):
                                $storeData[] = $data; //store each single row;
                            }
                        }

                    }
                }

                if (!empty($storeData)){
                    $this->db->truncate('employeeImport');
                    $this->db->insert_batch('employeeImport', $storeData);
                }
                if ($this->db->affected_rows() > 0){
                    $msg = 'Your  file ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                } else{
                    $msg = 'Your  file ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeImport'));
                }
            }

        }

        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee List');
        $data['link_page_url'] = $this->project.'/employeeList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['emplyeeList'] = $this->HR_Model->getImportEmployee($this->dist_id);
        $data['emplyeeListSameId'] = $this->HR_Model->emplyeeListSameId($this->dist_id);
        //echo $this->db->last_query();die;
        $data['title'] = 'Employee Import';
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeImport', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function saveImportEmplloyee()
    {
        $this->db->trans_start();
        $allResult = $this->Common_model->get_data_list('employeeimport');
        $allData = array();
        $productOrgId = $this->db->select('*')->count_all_results('employee');

        foreach ($allResult as $key => $value) {
            $result = $this->HR_Model->checkDuplicateEmployee($this->dist_id, $value->employeeId);
            if (empty($result)) {
                $data =array();
                $data['name'] = $value->name;
                $data['employeeId'] = $value->employeeId;
                $data['fathersName'] = $value->fathersName;
                $data['mothersName'] = $value->mothersName;
                $data['presentAddress'] = $value->presentAddress;
                $data['permanentAddress'] = $value->permanentAddress;
                $data['dateOfBirth'] = $value->dateOfBirth;
                $data['nationalId'] = $value->nationalId;
                $data['personalMobile'] = $value->personalMobile;
                $data['joiningDate'] =$value->joiningDate;
                $data['salary'] = $value->salary;
                $data['education'] = $value->education;
                $data['department'] = $value->department;
                $data['designation'] = $value->designation;
                $data['createdBy'] = $this->admin_id;
                $data['isActive'] = 'Y';
                $data['isDelete'] = 'N';
                $data['empStatus'] = 'Active';
                $data['dist_id'] = $this->dist_id;
                $allData[] = $data;



            }

        }
        if (!empty($allData)) {
            $this->Common_model->insert_data('employee', $data);
        }
        $this->db->truncate('employeeImport');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg = $this->config->item("save_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/employeeImport'));
        else:
            $msg = $this->config->item("save_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/employeeImport'));
        endif;
    }
    function deleteImportEmployee($deletedId)
    {
        $condition = array(
            'importid' => $deletedId
        );
        $this->Common_model->delete_data_with_condition('employeeImport', $condition);
        $msg = 'Delete  Successfull';
        $this->session->set_flashdata('success', $msg);
        redirect(site_url($this->project . '/employeeImport'));
    }

}
