<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HrController extends CI_Controller {

    private $timestamp;
    public $admin_id;
    private $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('HR_Model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function employeeList() {
        $condition = array(
            'dist_id' => $this->dist_id,
            'isActive' => 'Y',
            'isDelete' => 'N',
        );


        /*page navbar details*/
        $data['title'] = get_phrase('Employee List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Add');
        $data['link_page_url'] = $this->project.'/employeeAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/
        $data['getAllEmployeewiseD'] = $this->HR_Model->getAllEmployeewiseD();
        $data['employeeList'] = $this->Common_model->get_data_list_by_many_columns('employee', $condition);
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeLisst', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function employeeAdd() {


        if (isPostBack()) {

            $this->form_validation->set_rules('employeeId', 'employeeId', 'trim|required|is_unique[employee.employeeId]');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Employee Already Exsist ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeAdd/'));
            } else {
                $this->db->trans_start();
                $profileName = $_FILES['profile']['name'];
                if (!empty($profileName)) {
                    $profilename1 = explode(".", $profileName);
                    $extension = end($profilename1);
                    $base_name = $profilename1[0];
                    if (file_exists("uploads/employee/" . $_FILES['profile']['name'])) {
                        $profileName = $base_name . "_" . time() . "." . $extension;
                    }
                    move_uploaded_file($_FILES['profile']['tmp_name'], "uploads/employee/" . $profileName);
                    $data['profile'] = $profileName;
                } else {
                    $data['profile'] = '';
                }
                $cvName = $_FILES['cv']['name'];
                if (!empty($cvName)) {
                    $cvname1 = explode(".", $cvName);
                    $extension = end($cvname1);
                    $base_name = $cvname1[0];
                    if (file_exists("uploads/employee/cv/" . $_FILES['cv']['name'])) {
                        $cvName = $base_name . "_" . time() . "." . $extension;
                    }
                    move_uploaded_file($_FILES['cv']['tmp_name'], "uploads/employee/cv/" . $cvName);
                    $data['cv'] = $cvName;
                } else {
                    $data['cv'] = '';
                }
                $data['name'] = $this->input->post('name');
                $data['employeeId'] = $this->input->post('employeeId');
                $data['fathersName'] = $this->input->post('fathersName');
                $data['mothersName'] = $this->input->post('mothersName');
                $data['presentAddress'] = $this->input->post('presentAddress');
                $data['permanentAddress'] = $this->input->post('permanentAddress');
                $data['dateOfBirth'] = date('d-m-Y',  strtotime($this->input->post('dateOfBirth')));
                $data['gender'] = $this->input->post('gender');
                $data['nationalId'] = $this->input->post('nationalId');
                $data['religion'] = $this->input->post('religion');
                $data['emailAddress'] = $this->input->post('emailAddress');
                $data['homeDistrict'] = $this->input->post('homeDistrict');
                $data['personalMobile'] = $this->input->post('personalMobile');
                $data['maritalStatus'] = $this->input->post('maritalStatus');
                $data['officeMobile'] = $this->input->post('officeMobile');
                $data['bloodGroup'] = $this->input->post('bloodGroup');
                $data['joiningDate'] =date('d-m-Y',  strtotime($this->input->post('joiningDate')));
                $data['employeeType'] = $this->input->post('employeeType');
                $data['salary'] = $this->input->post('salary');
                $data['empStatus'] = $this->input->post('empStatus');
                $data['spouseName'] = $this->input->post('spouseName');
                $data['spouseNumber'] = $this->input->post('spouseNumber');
                $data['res'] = $this->input->post('res');
                $data['education'] = $this->input->post('education');
                $data['department'] = $this->input->post('department');
                $data['designation'] = $this->input->post('designation');
                $data['emargencyContact'] = $this->input->post('emargencyContact');
                $data['others'] = $this->input->post('others');
                $data['AccountNo'] = $this->input->post('AccountNo');
                $data['salaryType'] = $this->input->post('salaryType');
                $data['createdBy'] = $this->admin_id;
                $data['isActive'] = 'Y';
                $data['isDelete'] = 'N';
                $data['dist_id'] = $this->dist_id;
                $this->Common_model->insert_data('employee', $data);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Employee ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeList'));
                } else {
                    $msg = 'Employee ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeList'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Employee Add';

        /*page navbar details*/
        $data['title'] = get_phrase('Employee Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee List');
        $data['link_page_url'] = $this->project.'/employeeList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/


        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['bloodGroup'] = $this->Common_model->get_data_list('blood_group', 'bloodName', 'ASC');
        $data['mainContent'] = $this->load->view('distributor/setup/employee/addNewEmployee', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function employeeEdit($editId) {

        if (isPostBack()) {

            $this->form_validation->set_rules('employeeId', 'employeeId', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Month Salary Already Make ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryAdd/'));
            } else {
                $this->db->trans_start();
                $profileName = $_FILES['profile']['name'];
                if (!empty($profileName)) {
                    $profilename1 = explode(".", $profileName);
                    $extension = end($profilename1);
                    $base_name = $profilename1[0];
                    if (file_exists("uploads/employee/" . $_FILES['profile']['name'])) {
                        $profileName = $base_name . "_" . time() . "." . $extension;
                    }
                    move_uploaded_file($_FILES['profile']['tmp_name'], "uploads/employee/" . $profileName);
                    $data['profile'] = $profileName;
                } else {
                    $data['profile'] = $this->input->post('oldImage');
                }
                $cvName = $_FILES['cv']['name'];
                if (!empty($cvName)) {
                    $cvname1 = explode(".", $cvName);
                    $extension = end($cvname1);
                    $base_name = $cvname1[0];
                    if (file_exists("uploads/employee/cv/" . $_FILES['cv']['name'])) {
                        $cvName = $base_name . "_" . time() . "." . $extension;
                    }
                    move_uploaded_file($_FILES['cv']['tmp_name'], "uploads/employee/cv/" . $cvName);
                    $data['cv'] = $cvName;
                } else {
                    $data['cv'] = $this->input->post('oldcv');
                }
                $data['name'] = $this->input->post('name');
                $data['employeeId'] = $this->input->post('employeeId');
                $data['fathersName'] = $this->input->post('fathersName');
                $data['mothersName'] = $this->input->post('mothersName');
                $data['presentAddress'] = $this->input->post('presentAddress');
                $data['permanentAddress'] = $this->input->post('permanentAddress');
                $data['dateOfBirth'] = date('d-m-Y',strtotime($this->input->post('dateOfBirth')));
                $data['gender'] = $this->input->post('gender');
                $data['nationalId'] = $this->input->post('nationalId');
                $data['religion'] = $this->input->post('religion');
                $data['emailAddress'] = $this->input->post('emailAddress');
                $data['homeDistrict'] = $this->input->post('homeDistrict');
                $data['personalMobile'] = $this->input->post('personalMobile');
                $data['maritalStatus'] = $this->input->post('maritalStatus');
                $data['officeMobile'] = $this->input->post('officeMobile');
                $data['bloodGroup'] = $this->input->post('bloodGroup');
                $data['joiningDate'] = date('d-m-Y',  strtotime($this->input->post('joiningDate')));
                $data['employeeType'] = $this->input->post('employeeType');
                $data['salary'] = $this->input->post('salary');
                $data['empStatus'] = $this->input->post('empStatus');
                $data['createdBy'] = $this->admin_id;
                $data['isActive'] = 'Y';
                $data['isDelete'] = 'N';
                $data['spouseName'] = $this->input->post('spouseName');
                $data['spouseNumber'] = $this->input->post('spouseNumber');
                $data['res'] = $this->input->post('res');
                $data['education'] = $this->input->post('education');
                $data['department'] = $this->input->post('department');
                $data['designation'] = $this->input->post('designation');
                $data['emargencyContact'] = $this->input->post('emargencyContact');
                $data['others'] = $this->input->post('others');
                $data['AccountNo'] = $this->input->post('AccountNo');
                $data['salaryType'] = $this->input->post('salaryType');
                $data['dist_id'] = $this->dist_id;
                $this->Common_model->update_data('employee', $data,'id',$editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Employee ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeList'));
                } else {
                    $msg = 'Employee ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeList'));
                }
            }

        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );

        $data['link_page_name'] = get_phrase('Employee List');
        $data['link_page_url'] = $this->project.'/employeeList';
        $data['title'] = 'Employee Edit';
        $data['title'] = get_phrase('Employee Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['editEmp'] = $this->Common_model->get_single_data_by_single_column('employee', 'id',$editId);
        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['bloodGroup'] = $this->Common_model->get_data_list('blood_group', 'bloodName', 'ASC');
        $data['mainContent'] = $this->load->view('distributor/setup/employee/editEmployee', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function employeeDelete($deleteId){
        $data['isDelete']='Y';
        $data['deletedAt']=$this->timestamp;
        $data['deletedBy']=$this->admin_id;
        $this->Common_model->update_data('employee',$data,'id',$deleteId);
        message("Your data successfully deleted from database");
        redirect(site_url($this->project.'/employeeList'));
    }


    public function employeeSalaryAdd() {

        if (isPostBack()) {


            $this->form_validation->set_rules('date', 'Date', 'required');
            $month_id=$this->input->post('month');
            $year_id=$this->input->post('year');



            $check_unique=$this->HR_Model->check_unique($month_id,$year_id);

            if($check_unique)
            {
                $this->form_validation->set_rules('month', 'Month Name', 'trim|required|xss_clean|is_unique[salary_info.month]');
            }


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Month Salary Already Make ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryAdd/'));
            } else {


                $this->db->trans_start();
                $employeeSalary_details = array();
                if (!empty($_POST['employeeCheckBox'])) {
                    foreach ($_POST['employeeCheckBox'] as $key => $value) {
                        $data = array();
                        $data['date'] = date('d-m-Y',  strtotime($this->input->post('date')));
                        $data['month'] = $_POST['month'];
                        $data['year'] = $_POST['year'];
                        $data['employeeID'] = $_POST['employeeID'][$key];
                        $data['designation'] = $_POST['designation'][$key];
                        $data['departmentID'] = $_POST['departmentID'][$key];
                        $data['paymentMode'] = $_POST['paymentMode'][$key];
                        $data['basicSalary'] = $_POST['basicSalary'][$key];
                        $data['houseRant'] = $_POST['houseRant'][$key];
                        $data['conveyanceAllowance'] = $_POST['conveyanceAllowance'][$key];
                        $data['medicalAllowance'] = $_POST['medicalAllowance'][$key];
                        $data['others'] = $_POST['others'][$key];
                        $data['wPFDeduction'] = $_POST['wPFDeduction'][$key];
                        $data['grossSalary'] = $_POST['grossSalary'][$key];
                        $data['arrearSalary'] = $_POST['arrearSalary'][$key];
                        $data['absentDeduction'] = $_POST['absentDeduction'][$key];
                        $data['loanDeduction'] = $_POST['loanDeduction'][$key];
                        $data['aITDeduction'] = $_POST['aITDeduction'][$key];
                        $data['netPayAmount'] = $_POST['netPayAmount'][$key];
                        $data['createdBy'] = $this->admin_id;
                        $data['isActive'] = 'Y';
                        $data['isDelete'] = 'N';
                        $data['narration'] = $this->input->post('narration');
                        $employeeSalary_details[] = $data;

                    }
                }
                // echo "<pre>";
                // print_r($_POST);
                // exit();


                $this->db->insert_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Salary ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                } else {
                    $msg = 'Insentive Save ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Employee Salary Add';

        /*page navbar details*/
        $data['title'] = get_phrase('Employee Salary Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary List');
        $data['link_page_url'] = $this->project.'/employeeSalaryList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/

        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();

        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->HR_Model->get_employee();

        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->HR_Model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/addEmployeeSalary', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function employeeSalaryList() {
        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );


        /*page navbar details*/
        $data['title'] = get_phrase('Employee Salary List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/

        $data['salaryList'] = $this->HR_Model->getAllsalaryList();
        $data['getAllsalaryByDateList'] = $this->HR_Model->getAllsalaryByDateList();
        $data['employeewisedep'] = $this->HR_Model->getAllEmployeewiseD();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeSalaryList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function salaryEdit($date,$month,$year) {

        if (isPostBack()) {


            $this->form_validation->set_rules('date', 'Date', 'required');
            $month_id=$this->input->post('month');
            $year_id=$this->input->post('year');


            $check_unique=$this->HR_Model->check_unique($month_id,$year_id);

            if($check_unique)
            {
                $this->form_validation->set_rules('month', 'Month Name', 'trim|required|xss_clean|is_unique[salary_info.month]');
            }


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be Empty Or This Month Salary Already Make ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryAdd/'));
            } else {


                $this->db->trans_start();
                $employeeSalary_details = array();
                if (!empty($_POST['employeeCheckBox'])) {
                    foreach ($_POST['employeeCheckBox'] as $key => $value) {
                        $data = array();
                        $data['date'] = date('d-m-Y',  strtotime($this->input->post('date')));
                        $data['month'] = $_POST['month'];
                        $data['year'] = $_POST['year'];
                        $data['employeeID'] = $_POST['employeeID'][$key];
                        $data['designation'] = $_POST['designation'][$key];
                        $data['departmentID'] = $_POST['departmentID'][$key];
                        $data['paymentMode'] = $_POST['paymentMode'][$key];
                        $data['basicSalary'] = $_POST['basicSalary'][$key];
                        $data['houseRant'] = $_POST['houseRant'][$key];
                        $data['conveyanceAllowance'] = $_POST['conveyanceAllowance'][$key];
                        $data['medicalAllowance'] = $_POST['medicalAllowance'][$key];
                        $data['others'] = $_POST['others'][$key];
                        $data['wPFDeduction'] = $_POST['wPFDeduction'][$key];
                        $data['grossSalary'] = $_POST['grossSalary'][$key];
                        $data['arrearSalary'] = $_POST['arrearSalary'][$key];
                        $data['absentDeduction'] = $_POST['absentDeduction'][$key];
                        $data['loanDeduction'] = $_POST['loanDeduction'][$key];
                        $data['aITDeduction'] = $_POST['aITDeduction'][$key];
                        $data['netPayAmount'] = $_POST['netPayAmount'][$key];
                        $data['createdBy'] = $this->admin_id;
                        $data['isActive'] = 'Y';
                        $data['isDelete'] = 'N';
                        $data['narration'] = $this->input->post('narration');
                        $employeeSalary_details[] = $data;

                    }
                }


                $this->db->insert_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Incentive ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                } else {
                    $msg = 'Insentive Save ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Employee Salary Add';

        /*page navbar details*/
        $data['title'] = get_phrase('Employee Salary Preview');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary List');
        $data['link_page_url'] = $this->project.'/employeeSalaryList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/
        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListView($date,$month,$year);
        //         echo '<pre>';
        // echo $this->db->last_query();

        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();

        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->HR_Model->get_employee();

        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('empStatus', 'Active')->where('isDelete', 'N')->get('employee')->result();
        $data['employee2'] = $this->HR_Model->get_employee();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/SalaryEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function salaryEdit_bkp($date,$month,$year) {

        if (isPostBack()) {


            $this->form_validation->set_rules('date', 'Date', 'required');


            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be ';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/employeeSalaryAdd/'));
            } else {


                $this->db->trans_start();
                $employeeSalary_details = array();
                if (!empty($_POST['employeeCheckBox'])) {
                    foreach ($_POST['employeeCheckBox'] as $key => $value) {
                        $data = array();
                        $data['date'] = date('Y-m-d',  strtotime($this->input->post('date')));
                        $data['month'] = $_POST['month'];
                        $data['year'] = $_POST['year'];
                        $data['employeeID'] = $_POST['employeeID'][$key];
                        $data['departmentID'] = $_POST['departmentID'][$key];
                        $data['paymentMode'] = $_POST['paymentMode'][$key];
                        $data['basicSalary'] = $_POST['basicSalary'][$key];
                        $data['houseRant'] = $_POST['houseRant'][$key];
                        $data['conveyanceAllowance'] = $_POST['conveyanceAllowance'][$key];
                        $data['medicalAllowance'] = $_POST['medicalAllowance'][$key];
                        $data['wPFDeduction'] = $_POST['wPFDeduction'][$key];
                        $data['grossSalary'] = $_POST['grossSalary'][$key];
                        $data['arrearSalary'] = $_POST['arrearSalary'][$key];
                        $data['absentDeduction'] = $_POST['absentDeduction'][$key];
                        $data['loanDeduction'] = $_POST['loanDeduction'][$key];
                        $data['aITDeduction'] = $_POST['aITDeduction'][$key];
                        $data['netPayAmount'] = $_POST['netPayAmount'][$key];

                        $data['createdBy'] = $this->admin_id;
                        $data['isActive'] = 'Y';
                        $data['isDelete'] = 'N';
                        $data['narration'] = $this->input->post('narration');
                        $employeeSalary_details[] = $data;

                    }
                }


                $this->db->update_batch('salary_info', $employeeSalary_details);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = 'Salary ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                } else {
                    $msg = 'Salary Save ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeSalaryAdd'));
                }
            }
        }

        $condition = array(
            'isActive' => 'Y',
            'isDelete' => 'N',
        );
        $data['title'] = 'Employee Salary Add';

        /*page navbar details*/
        $data['title'] = get_phrase('Employee Salary Add');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary List');
        $data['link_page_url'] = $this->project.'/employeeSalaryList';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*$data['second_link_page_name'] = get_phrase('Invoice_List');
        $data['second_link_page_url'] = 'salesInvoiceLpg';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['third_link_page_name'] = get_phrase('Sale Invoice View');
        $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
        $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/


        $data['religion'] = $this->Common_model->get_data_list_by_many_columns('religion', $condition);
        $data['districts'] = $this->Common_model->get_data_list('districts', 'name', 'ASC');
        $data['department'] = $this->Common_model->get_data_list('tb_department', 'DepartmentName', 'ASC');
        $data['employeewisedep'] = $this->HR_Model->getAllEmployeewiseD();
        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListView($date,$month,$year);
//                echo '<pre>';
//        echo $this->db->last_query();
        $data['designation'] = $this->Common_model->get_data_list('tb_designation', 'DesignationName', 'ASC');
        $data['employee'] = $this->db->where('isActive', 'Y')->where('isDelete', 'N')->get('employee')->result();
        $data['mainContent'] = $this->load->view('distributor/setup/employee/SalaryEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function salaryView($date,$month,$year){
        $data['title'] = get_phrase('Employee Salary View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        $data['employeefield'] = $this->db->where('isShow', '1')->get('employeefield')->result();
        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListView($date,$month,$year);
        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryDetailsView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function salaryViewPdf($date,$month,$year){

        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListView($date,$month,$year);
        $html = $this->load->view('distributor/setup/employee/salaryViewPdf', $data, true);
        // Load pdf library
        $this->load->library('pdf');

        //$html = $this->output->get_output();
        // Load HTML content


        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("salarySheet.pdf", array("Attachment" => 0));

        // echo "<pre>";
        // print_r($dataArray);
        // exit();



    }
    public function salaryViewCashPdf($date,$month,$year,$cash){

        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListViewCash($date,$month,$year,$cash);
        $html = $this->load->view('distributor/setup/employee/salaryViewPdf', $data, true);
        // Load pdf library
        $this->load->library('pdf');

        //$html = $this->output->get_output();
        // Load HTML content


        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("salarySheet.pdf", array("Attachment" => 0));

        // echo "<pre>";
        // print_r($dataArray);
        // exit();



    }
    public function salaryViewBankPdf($date,$month,$year,$bank){

        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListViewBank($date,$month,$year,$bank);
        $html = $this->load->view('distributor/setup/employee/salaryViewPdf', $data, true);
        // Load pdf library
        $this->load->library('pdf');

        //$html = $this->output->get_output();
        // Load HTML content


        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("salarySheet.pdf", array("Attachment" => 0));

        // echo "<pre>";
        // print_r($dataArray);
        // exit();



    }



    public function salaryViewByCash($date,$month,$year,$cash){
        $data['title'] = get_phrase('Employee Salary View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/


        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListViewCash($date,$month,$year,$cash);
//        echo '<pre>';
//        echo $this->db->last_query();

        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryViewByCash', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function salaryViewByBank($date,$month,$year,$bank){
        $data['title'] = get_phrase('Employee Salary View');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Employee Salary Add');
        $data['link_page_url'] = $this->project.'/employeeSalaryAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /* $data['second_link_page_name'] = get_phrase('Invoice_List');
         $data['second_link_page_url'] = 'salesInvoiceLpg';
         $data['second_link_icon'] = $this->link_icon_list;
         $data['third_link_page_name'] = get_phrase('Sale Invoice View');
         $data['third_link_page_url'] = 'salesInvoice_view/' . 1;
         $data['third_link_icon'] = $this->link_icon_edit;*/
        /*page navbar details*/


        $data['getAllsalaryByDateListView'] = $this->HR_Model->getAllsalaryByDateListViewBank($date,$month,$year,$bank);
//        echo '<pre>';
//        echo $this->db->last_query();

        $data['mainContent'] = $this->load->view('distributor/setup/employee/salaryViewByBank', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }



    public function salaryDelete($date,$month,$year){


//        $condition = array(
//          'date' => $date,
//          'month'   => $month,
//          'year'   => $year
//        );
        $result= $this->HR_Model->salaryInfoDelete($date,$month,$year);
        // $result= $this->Common_model->delete_data('salary_info',$condition);

        if ($result) {
            $msg = 'Salary  ' . ' ' . $this->config->item("delete_success_message");
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/employeeSalaryList'));

        } else {
            $msg = 'Salary  ' . ' ' . $this->config->item("delete_error_message");
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/employeeSalaryList'));
        }

    }


}
