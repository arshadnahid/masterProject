<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EmpDepartmentController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('HR_Model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Pos_Model');
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




    public function departmentAdd(){
        if (isPostBack()) {


            $this->form_validation->set_rules('department', 'department', 'required|is_unique[tb_department.DepartmentName]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate Name';
                $this->session->set_flashdata('error', $msg);
            } else {
                $this->db->trans_start();
                $data['DepartmentName'] = $this->input->post('department');
                $data['Created_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Created_Date'] = $this->timestamp;
                $this->db->insert('tb_department', $data);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/departmentAdd'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/departmentAdd'));

                }
            }

        }

        /*page navbar details*/
        $data['title'] = get_phrase('Department Add');
        $data['page_type'] = $this->page_type;

        $data['departmentList'] = $this->HR_Model->getDepartmentList();
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/department/departmentAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }





    public function departmentEdit($editId){
        if (isPostBack()) {


            $this->form_validation->set_rules('department', 'department', 'required|is_unique[tb_department.DepartmentName]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate';
                $this->session->set_flashdata('error', $msg);
            } else {
                $this->db->trans_start();
                $data['DepartmentName'] = $this->input->post('department');
                $data['Changed_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Changed_Date'] = $this->timestamp;

                $this->Common_model->update_data('tb_department', $data, 'DepartmentID', $editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/departmentAdd'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/departmentAdd'));

                }
            }

        }
        $data['getDepartmentById'] = $this->HR_Model->getSingleData($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Department Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Department List');
        $data['link_page_url'] = $this->project.'/departmentAdd';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/department/departmentEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function departmentList(){


        $data['departmentList'] = $this->HR_Model->getDepartmentList();
        $data['title'] = get_phrase('Department List');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Department Add');
        $data['link_page_url'] = $this->project.'/departmentAdd';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/department/departmentList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function departmentDelete($deleteId){
        $checkDeparemt= $this->HR_Model->checkDepatmentId($deleteId);

//       echo '<pre>';
//       print_r($checkDeparemt);
//       exit();


        if (empty($checkDeparemt)){
            $Condition = array(
                'dist_id' => $this->dist_id,
                'DepartmentID' => $deleteId,
            );
            $result = $this->Common_model->delete_data_with_condition('tb_department', $Condition);
            if ($result) {
                $msg = "Your data successfully Deleted From database";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/departmentAdd'));
            } else {
                $msg = "Your data can't be Deleted";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/departmentAdd'));
            }
        }  else {
            $msg = "This Department Use on Another Table";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/departmentAdd'));
        }


    }


    function statusDepartment($id)
    {

        $result = $this->HR_Model->unpublishedDepartment($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/departmentAdd'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/departmentAdd'));
        }
    }
    function statusDepartment2($id)
    {

        $result = $this->HR_Model->publishedDepartment($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/departmentAdd'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/departmentAdd'));
        }
    }

    public function designationAdd(){
        if (isPostBack()) {


            $this->form_validation->set_rules('designation', 'designation', 'required|is_unique[tb_designation.DesignationName]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate';
                $this->session->set_flashdata('error', $msg);
            } else {
                $this->db->trans_start();
                $data['DesignationName'] = $this->input->post('designation');
                $data['Created_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Created_Date'] = $this->timestamp;
                $this->db->insert('tb_designation', $data);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/designationAdd'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/designationAdd'));

                }
            }

        }

        /*page navbar details*/
        $data['title'] = get_phrase('Designation Add');
        $data['page_type'] = $this->page_type;

        $data['designationList'] = $this->HR_Model->getDesinationList();
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/designation/designationAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    public function designationEdit($editId){
        if (isPostBack()) {


            $this->form_validation->set_rules('designation', 'designation', 'required|is_unique[tb_designation.DesignationName]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate';
                $this->session->set_flashdata('error', $msg);
            } else {
                $this->db->trans_start();
                $data['DesignationName'] = $this->input->post('designation');
                $data['Changed_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Changed_Date'] = $this->timestamp;

                $this->Common_model->update_data('tb_designation', $data, 'DesignationID', $editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/designationAdd'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/designationAdd'));

                }
            }

        }
        $data['getdesignationById'] = $this->HR_Model->getSingleDataDesignation($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Designation Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Designation List');
        $data['link_page_url'] = $this->project.'/designationAdd';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/designation/designationEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }


    public function designationDelete($deleteId){

        $checkDesignation= $this->HR_Model->checkDesignationId($deleteId);

        if(empty($checkDesignation)){
            $Condition = array(
                'dist_id' => $this->dist_id,
                'DesignationID' => $deleteId,
            );
            $result = $this->Common_model->delete_data_with_condition('tb_designation', $Condition);
            if ($result) {
                $msg = "Your data successfully Deleted From database";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/designationAdd'));
            } else {
                $msg = "Your data can't be Deleted";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/designationAdd'));
            }
        }
        else {
            $msg = "This Designation Use Another Table";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/designationAdd'));
        }

    }


    function statusdesignationDepartment($id)
    {

        $result = $this->HR_Model->unpublisheddesignation($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/designationAdd'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/designationAdd'));
        }
    }
    function statusdesignationDepartment2($id)
    {

        $result = $this->HR_Model->publisheddesignation($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/designationAdd'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/designationAdd'));
        }
    }





}
