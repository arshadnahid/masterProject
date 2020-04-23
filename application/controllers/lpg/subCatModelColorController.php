<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class subCatModelColorController extends CI_Controller {

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $project;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('DayBook_Model');
        $this->load->model('subCatModelColor_Model');
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



    public function subCategory(){
        if (isPostBack()) {


            $this->form_validation->set_rules('SubCatName', 'SubCatName', 'required|is_unique[tb_subCategory.SubCatName]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate Name';
                $this->session->set_flashdata('error', $msg);
            } else {
                $this->db->trans_start();
                $data['SubCatName'] = $this->input->post('SubCatName');
                $data['Created_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Created_Date'] = $this->timestamp;
                $this->db->insert('tb_subCategory', $data);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/subCategory'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/subCategory'));

                }
            }

        }

        /*page navbar details*/
        $data['title'] = get_phrase('Setup Menu Add');
        $data['page_type'] = $this->page_type;
        $data['getSizeList'] = $this->subCatModelColor_Model->getSizeList();
        $data['getColorList'] = $this->subCatModelColor_Model->getColorList();
        $data['getSubCatList'] = $this->subCatModelColor_Model->getSubCatList();
        $data['getModelList'] = $this->subCatModelColor_Model->getModelList();
        $data['departmentList'] = $this->subCatModelColor_Model->getDepartmentList();
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/subCategory', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function subCatEdit($editId){
        if (isPostBack()) {


            $this->form_validation->set_rules('SubCatName', 'SubCatName', 'required|is_unique[tb_subCategory.SubCatName]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate';
                $this->session->set_flashdata('error', $msg);
            } else {
                $this->db->trans_start();
                $data['SubCatName'] = $this->input->post('SubCatName');
                $data['Changed_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Changed_Date'] = $this->timestamp;

                $this->Common_model->update_data('tb_subCategory', $data, 'SubCatID', $editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be Updated";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/subCategory'));
                } else {
                    $msg = "Your data successfully Updated into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/subCategory'));

                }
            }

        }
        $data['getSingleSubCat'] = $this->subCatModelColor_Model->getSingleSubCat($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Sub Category Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Sub Category List');
        $data['link_page_url'] = $this->project.'/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/subCatEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    public function subCatDelete($deleteId){

        $result =  $this->db->delete('tb_subCategory', array('SubCatID' => $deleteId));

        if ($result) {
            $msg = "Your data successfully Deleted From database";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory'));
        } else {
            $msg = "Your data can't be Deleted";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/subCategory'));
        }


    }
    function statusSubCat($id)
    {

        $result = $this->subCatModelColor_Model->unpublishedSubCat($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory'));
        }
    }
    function statusSubCat2($id)
    {

        $result = $this->subCatModelColor_Model->publishedSubCat($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory'));
        }
    }
    public function modelAdd(){

        $this->form_validation->set_rules('Model', 'Model', 'required|is_unique[tb_Model.Model]');

        if ($this->form_validation->run() === FALSE) {
            $msg = 'Your data cannot be Empty Or Duplicate Name';
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/subCategory#tab2default'));
        } else {
            $this->db->trans_start();
            $data['Model'] = $this->input->post('Model');
            $data['Created_By'] = $this->admin_id;
            $data['isActive'] = 1;
            $data['dist_id'] = $this->dist_id;
            $data['Created_Date'] = $this->timestamp;
            $this->db->insert('tb_Model', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = "Your data can't be inserted";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/subCategory'));
            } else {
                $msg = "Your data successfully inserted into database";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/subCategory#tab2default'));

            }
        }


    }


    public function modelEdit($editId){
        if (isPostBack()) {


            $this->form_validation->set_rules('Model', 'Model', 'required|is_unique[tb_model.Model]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/subCategory#tab2default'));
            } else {
                $this->db->trans_start();
                $data['Model'] = $this->input->post('Model');
                $data['Changed_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Changed_Date'] = $this->timestamp;

                $this->Common_model->update_data('tb_model', $data, 'ModelID', $editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be Updated";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/subCategory'));
                } else {
                    $msg = "Your data successfully Updated into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/subCategory#tab2default'));

                }
            }

        }
        $data['getSingleModel'] = $this->subCatModelColor_Model->getSingleModel($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Sub Category Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Sub Category List');
        $data['link_page_url'] = $this->project.'/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/modelEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function modelDelete($deleteId){

        $result =  $this->db->delete('tb_model', array('ModelID' => $deleteId));

        if ($result) {
            $msg = "Your data successfully Deleted From database";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab2default'));
        } else {
            $msg = "Your data can't be Deleted";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/subCategory#tab2default'));
        }


    }
    function statusModel($id)
    {

        $result = $this->subCatModelColor_Model->unpublishedModel($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab2default'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory#tab2default'));
        }
    }
    function statusModel2($id)
    {

        $result = $this->subCatModelColor_Model->publishedModel($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab2default'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory#tab2default'));
        }
    }

    public function colorAdd(){

        $this->form_validation->set_rules('Color', 'Color', 'required|is_unique[tb_color.Color]');

        if ($this->form_validation->run() === FALSE) {
            $msg = 'Your data cannot be Empty Or Duplicate Name';
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/subCategory#tab3default'));
        } else {
            $this->db->trans_start();
            $data['Color'] = $this->input->post('Color');
            $data['Created_By'] = $this->admin_id;
            $data['isActive'] = 1;
            $data['dist_id'] = $this->dist_id;
            $data['Created_Date'] = $this->timestamp;
            $this->db->insert('tb_color', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = "Your data can't be inserted";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/subCategory'));
            } else {
                $msg = "Your data successfully inserted into database";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/subCategory#tab3default'));

            }
        }


    }

    public function colorEdit($editId){
        if (isPostBack()) {


            $this->form_validation->set_rules('Color', 'Color', 'required|is_unique[tb_color.Color]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/subCategory#tab3default'));
            } else {
                $this->db->trans_start();
                $data['Color'] = $this->input->post('Color');
                $data['Changed_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Changed_Date'] = $this->timestamp;

                $this->Common_model->update_data('tb_color', $data, 'ColorID', $editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be Updated";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/subCategory'));
                } else {
                    $msg = "Your data successfully Updated into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/subCategory#tab3default'));

                }
            }

        }
        $data['getSingleColor'] = $this->subCatModelColor_Model->getSingleColor($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Color Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Color List');
        $data['link_page_url'] = $this->project.'/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/colorEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }
    public function colorDelete($deleteId){

        $result =  $this->db->delete('tb_color', array('ColorID' => $deleteId));

        if ($result) {
            $msg = "Your data successfully Deleted From database";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab3default'));
        } else {
            $msg = "Your data can't be Deleted";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/subCategory#tab3default'));
        }


    }

    function statusColor($id)
    {

        $result = $this->subCatModelColor_Model->unpublishedColor($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab3default'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory#tab3default'));
        }
    }
    function statusColor2($id)
    {

        $result = $this->subCatModelColor_Model->publishedColor($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab3default'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory#tab3default'));
        }
    }

    public function sizeAdd(){

        $this->form_validation->set_rules('Size', 'Size', 'required|is_unique[tb_Size.Size]');

        if ($this->form_validation->run() === FALSE) {
            $msg = 'Your data cannot be Empty Or Duplicate Name';
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/subCategory#tab4default'));
        } else {
            $this->db->trans_start();
            $data['Size'] = $this->input->post('Size');
            $data['Created_By'] = $this->admin_id;
            $data['isActive'] = 1;
            $data['dist_id'] = $this->dist_id;
            $data['Created_Date'] = $this->timestamp;
            $this->db->insert('tb_Size', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = "Your data can't be inserted";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/subCategory#tab4default'));
            } else {
                $msg = "Your data successfully inserted into database";
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/subCategory#tab4default'));

            }
        }


    }

    public function sizeEdit($editId){
        if (isPostBack()) {


            $this->form_validation->set_rules('Size', 'Size', 'required|is_unique[tb_Size.Size]');


            if ($this->form_validation->run() === FALSE) {
                $msg = 'Your data cannot be Empty Or Duplicate';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/subCategory#tab4default'));
            } else {
                $this->db->trans_start();
                $data['Size'] = $this->input->post('Size');
                $data['Changed_By'] = $this->admin_id;
                $data['isActive'] = 1;
                $data['dist_id'] = $this->dist_id;
                $data['Changed_Date'] = $this->timestamp;

                $this->Common_model->update_data('tb_Size', $data, 'SizeID', $editId);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be Updated";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/subCategory#tab4default'));
                } else {
                    $msg = "Your data successfully Updated into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/subCategory#tab4default'));

                }
            }

        }
        $data['getSingleSize'] = $this->subCatModelColor_Model->getSingleSize($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Size Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Size List');
        $data['link_page_url'] = $this->project.'/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/sizeEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }


    public function sizeDelete($deleteId){

        $result =  $this->db->delete('tb_Size', array('SizeID' => $deleteId));

        if ($result) {
            $msg = "Your data successfully Deleted From database";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab4default'));
        } else {
            $msg = "Your data can't be Deleted";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/subCategory#tab4default'));
        }


    }

    function statusSize($id)
    {

        $result = $this->subCatModelColor_Model->unpublishedSize($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab4default'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory#tab4default'));
        }
    }
    function statusSize2($id)
    {

        $result = $this->subCatModelColor_Model->publishedSize($id);

        if ($result) {
            $msg = "Your data successfully Change";
            $this->session->set_flashdata('success', $msg);
            redirect(site_url($this->project . '/subCategory#tab4default'));
        } else {
            $msg = "Your data Do Not Change";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project . '/subCategory#tab4default'));
        }
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

        $data['departmentList'] = $this->subCatModelColor_Model->getDepartmentList();
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
        $data['getDepartmentById'] = $this->subCatModelColor_Model->getSingleData($editId);
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


        $data['departmentList'] = $this->subCatModelColor_Model->getDepartmentList();
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
        $checkDeparemt= $this->subCatModelColor_Model->checkDepatmentId($deleteId);

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

        $result = $this->subCatModelColor_Model->unpublishedDepartment($id);

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

        $result = $this->subCatModelColor_Model->publishedDepartment($id);

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

        $data['designationList'] = $this->subCatModelColor_Model->getDesinationList();
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
        $data['getdesignationById'] = $this->subCatModelColor_Model->getSingleDataDesignation($editId);
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

        $checkDesignation= $this->subCatModelColor_Model->checkDesignationId($deleteId);

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

        $result = $this->subCatModelColor_Model->unpublisheddesignation($id);

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

        $result = $this->subCatModelColor_Model->publisheddesignation($id);

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
