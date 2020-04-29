<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 4/1/2020
 * Time: 11:54 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class InvProductPropertyController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $folder;
    public $folderSub;
    public $page_type;
    public $project;
    public $business_type;

    public function __construct()
    {

        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('InvProductProperty_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/product/';
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->business_type = $this->session->userdata('business_type');

        if ($this->business_type == "MOBILE") {
            $this->folder = 'distributor/masterTemplateSmeMobile';
            $this->folderSub = 'distributor/inventory/product_mobile/';
        }


        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }


    function index()
    {
        $data = array();
        // If file upload form submitted
        if ($this->input->post('fileSubmit') && !empty($_FILES['files']['name'])) {
            $filesCount = count($_FILES['files']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                $uploadPath = 'uploads/files/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file')) {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    $uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['imageName'] = $this->input->post('imageName');
                }
            }
            if (!empty($uploadData)) {
                $insert = $this->InvProductProperty_Model->insert($uploadData);
                $statusMsg = $insert ? 'Images Uploaded Successfully.' : 'Images Uploaded Fail.';
                $this->session->set_flashdata('statusMsg', $statusMsg);
            }
        }
        $data['files'] = $this->InvProductProperty_Model->getRows();

        $data['mainContent'] = $this->load->view('distributor/test/multipleImageUpload', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function searcht()
    {
        $term = $this->input->get('term');
        $this->db->like('title', $term);
        $data = $this->db->get("products")->result();
        echo json_encode($data);
    }

    public function SearchResult()
    {
        $term = $this->input->get('term');
        $getDetail = $this->InvProductProperty_Model->getSearch($term);
        $data = array();
        foreach ($getDetail as $row) {

            $temp_array = array();
            $temp_array['value'] = $row['title'];
            $temp_array['label'] = '<img src="<php site_url() ?>uploads/product/' . $row['picture'] . '" width="100" />&nbsp;&nbsp;&nbsp;' . $row['title'] . '';
            $data[] = $temp_array;
        }
        echo json_encode($data);
    }

    public function seachdetails()
    {


        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $data['getalldayBook'] = $this->DayBook_Model->getalldayBook();

        /*page navbar details*/
        $data['title'] = get_phrase('search');
        $data['page_type'] = $this->page_type;

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/test/searchJq', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function subCategory()
    {
        if (isPostBack()) {


            $this->form_validation->set_rules('SubCatName', 'SubCatName', 'required|is_unique[tb_subcategory.SubCatName]');


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
                $this->db->insert('tb_subcategory', $data);

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
        $data['getSizeList'] = $this->InvProductProperty_Model->getSizeList();
        $data['getColorList'] = $this->InvProductProperty_Model->getColorList();
        $data['getSubCatList'] = $this->InvProductProperty_Model->getSubCatList();
        $data['getModelList'] = $this->InvProductProperty_Model->getModelList();
        $data['departmentList'] = $this->InvProductProperty_Model->getDepartmentList();
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/subCategory', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function subCatEdit($editId)
    {
        if (isPostBack()) {


            $this->form_validation->set_rules('SubCatName', 'SubCatName', 'required|is_unique[tb_subcategory.SubCatName]');


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

                $this->Common_model->update_data('tb_subcategory', $data, 'SubCatID', $editId);
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
        $data['getSingleSubCat'] = $this->InvProductProperty_Model->getSingleSubCat($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Sub Category Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Sub Category List');
        $data['link_page_url'] = $this->project . '/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/subCatEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function subCatDelete($deleteId)
    {

        $result = $this->db->delete('tb_subcategory', array('SubCatID' => $deleteId));

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

        $result = $this->InvProductProperty_Model->unpublishedSubCat($id);

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

        $result = $this->InvProductProperty_Model->publishedSubCat($id);

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

    public function modelAdd()
    {

        $this->form_validation->set_rules('Model', 'Model', 'required|is_unique[tb_model.Model]');

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
            $this->db->insert('tb_model', $data);

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


    public function modelEdit($editId)
    {
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
        $data['getSingleModel'] = $this->InvProductProperty_Model->getSingleModel($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Sub Category Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Sub Category List');
        $data['link_page_url'] = $this->project . '/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/modelEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function modelDelete($deleteId)
    {

        $result = $this->db->delete('tb_model', array('ModelID' => $deleteId));

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

        $result = $this->InvProductProperty_Model->unpublishedModel($id);

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

        $result = $this->InvProductProperty_Model->publishedModel($id);

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

    public function colorAdd()
    {

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

    public function colorEdit($editId)
    {
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
        $data['getSingleColor'] = $this->InvProductProperty_Model->getSingleColor($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Color Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Color List');
        $data['link_page_url'] = $this->project . '/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/colorEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function colorDelete($deleteId)
    {

        $result = $this->db->delete('tb_color', array('ColorID' => $deleteId));

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

        $result = $this->InvProductProperty_Model->unpublishedColor($id);

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

        $result = $this->InvProductProperty_Model->publishedColor($id);

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

    public function sizeAdd()
    {

        $this->form_validation->set_rules('Size', 'Size', 'required|is_unique[tb_size.Size]');

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
            $this->db->insert('tb_size', $data);

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

    public function sizeEdit($editId)
    {
        if (isPostBack()) {


            $this->form_validation->set_rules('Size', 'Size', 'required|is_unique[tb_size.Size]');


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

                $this->Common_model->update_data('tb_size', $data, 'SizeID', $editId);
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
        $data['getSingleSize'] = $this->InvProductProperty_Model->getSingleSize($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Update Size Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Size List');
        $data['link_page_url'] = $this->project . '/subCategory';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/subCategory/sizeEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }


    public function sizeDelete($deleteId)
    {

        $result = $this->db->delete('tb_size', array('SizeID' => $deleteId));

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

        $result = $this->InvProductProperty_Model->unpublishedSize($id);

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

        $result = $this->InvProductProperty_Model->publishedSize($id);

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

    public function departmentAdd()
    {
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

        $data['departmentList'] = $this->InvProductProperty_Model->getDepartmentList();
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/department/departmentAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }


    public function departmentEdit($editId)
    {
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
        $data['getDepartmentById'] = $this->InvProductProperty_Model->getSingleData($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Department Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Department List');
        $data['link_page_url'] = $this->project . '/departmentAdd';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/department/departmentEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function departmentList()
    {


        $data['departmentList'] = $this->InvProductProperty_Model->getDepartmentList();
        $data['title'] = get_phrase('Department List');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Department Add');
        $data['link_page_url'] = $this->project . '/departmentAdd';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/department/departmentList', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function departmentDelete($deleteId)
    {
        $checkDeparemt = $this->InvProductProperty_Model->checkDepatmentId($deleteId);

//       echo '<pre>';
//       print_r($checkDeparemt);
//       exit();


        if (empty($checkDeparemt)) {
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
        } else {
            $msg = "This Department Use on Another Table";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/departmentAdd'));
        }


    }


    function statusDepartment($id)
    {

        $result = $this->InvProductProperty_Model->unpublishedDepartment($id);

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

        $result = $this->InvProductProperty_Model->publishedDepartment($id);

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

    public function employeeVoucher()
    {
        $condition = array(
            'dist_id' => $this->dist_id,
            'form_id' => 3,
        );
        //  $data['receiveVoucher'] = $this->Common_model->get_data_list_by_many_columns('generals', $condition, 'date', 'DESC');

        /*page navbar details*/
        $data['title'] = 'Employee Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Employee Voucher Add';
        $data['link_page_url'] = $this->project . '/employeeVoucherAdd';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucher', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function employeeVoucherAdd($postingId = null)
    {
        $this->load->helper('create_receive_voucher_no_helper');
        if (isPostBack()) {
//validation rules set here.
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('accountCr[]', 'Account Debit', 'required');

            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/employeeVoucherAdd'));
            } else {

                $this->db->trans_start();


                $voucherCondition = array(
                    'AccouVoucherType_AutoID' => 5,
                    //'BranchAutoId' => $this->dist_id,
                );
                $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
                $voucherID = "EPS" . date('y') . date('m') . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);

                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $voucherID;
                $data['Narration'] = $this->input->post('narration');
                $data['CompanyId'] = $this->dist_id;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Reference'] = 0;
                $data['AccouVoucherType_AutoID'] = 8;
                $data['IsActive'] = 1;
                $data['Created_By'] = $this->admin_id;
                $data['Created_Date'] = $this->timestamp;

                $general_id = $this->Common_model->insert_data('ac_accounts_vouchermst', $data);

                $acountDr = $this->input->post('accountCr'); // account Head bellow

                $cashCrId = $this->input->post('cash');
                $bankId = $this->input->post('accountDr');
                $cashCr = $this->input->post('cashCr');
                $bankCr = $this->input->post('bankCr');

                /* Pay account DR */
                if (!empty($cashCrId)) {
                    $dr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $cashCrId;
                    $dr['GR_CREDIT'] = $cashCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Created_By'] = $this->dist_id;
                    $dr['Created_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $dr);

                }
                if (!empty($bankId)) {
                    $dr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $bankId;
                    $dr['GR_CREDIT'] = $bankCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Created_By'] = $this->dist_id;
                    $dr['Created_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->insert_data('ac_tb_accounts_voucherdtl', $dr);

                }


                $allCr = array();
                foreach ($acountDr as $key => $value) {
                    unset($cr);
                    $cr['Accounts_VoucherMst_AutoID'] = $general_id;
                    $cr['TypeID'] = 2;
                    $cr['CHILD_ID'] = $this->input->post('accountCr')[$key];
                    $cr['GR_CREDIT'] = 0;
                    $cr['Reference'] = $this->input->post('memoCr')[$key];
                    $cr['GR_DEBIT'] = $this->input->post('amountCr')[$key];
                    $cr['IsActive'] = 1;
                    $cr['Created_By'] = $this->dist_id;
                    $cr['Created_Date'] = $this->timestamp;
                    $cr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $cr['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $allCr[] = $cr;
                }

                /* echo '<pre>';
                 print_r($allCr);
                 exit();*/
                $this->db->insert_batch('ac_tb_accounts_voucherdtl', $allCr);


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeVoucherAdd/'));
                } else {
                    $msg = $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeVoucherAdd/' . $general_id));

                }


                /* Pay account Credit */
            }
        }
        //$data['accountHeadList'] = $this->Common_model->getAccountHead();


        $conditionaccountHeadList = array(
            'status' => '1',
            'posted' => '1'
        );

        $data['accountHeadList'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadList);

        $conditionaccountHeadListByCash = array(
            'status' => '1',
            'posted' => '1',
            'parent_id' => '28'
        );

        $data['accountHeadListByCash'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByCash);

        $conditionaccountHeadListByBank = array(
            'status' => '1',
            'posted' => '1',
            'parent_id' => '32'
        );

        $data['accountHeadListByBank'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByBank);

        $voucherCondition = array(
            'AccouVoucherType_AutoID' => 8,
            'BranchAutoId' => $this->dist_id,
        );

        $data['voucherID'] = create_receive_voucher_no();
        $totalPurchases = $this->Common_model->get_data_list_by_many_columns('ac_accounts_vouchermst', $voucherCondition);
        $data['voucherID'] = "EPS" . date("y") . date("m") . str_pad(count($totalPurchases) + 1, 4, "0", STR_PAD_LEFT);


        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);


        $data['title'] = 'Add Employee Voucher';
        /*page navbar details*/
        $data['title'] = 'Add Employee Voucher';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Employee Voucher List';
        $data['link_page_url'] = $this->project . '/employeeVoucher';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucherAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    public function employeeVoucherEdit($invoiceId)
    {


        if (isPostBack()) {
            $this->form_validation->set_rules('date', 'Payment Date', 'required');
            $this->form_validation->set_rules('voucherid', 'Voucher Id', 'required');
            $this->form_validation->set_rules('accountCr[]', 'Account Debit', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project . '/receiveVoucherAdd'));
            } else {
                $this->db->trans_start();

                $data['AccouVoucherType_AutoID'] = 8;
                $data['BranchAutoId'] = $this->input->post('BranchAutoId');
                $data['Accounts_Voucher_Date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['Accounts_Voucher_No'] = $this->input->post('voucherid');
                // $data['AccouVoucherType_AutoID'] = $this->input->post('payType');
                $data['Narration'] = $this->input->post('narration');
                $data['IsActive'] = 1;
                $data['CompanyId'] = $this->dist_id;
                $data['Changed_By'] = $this->admin_id;
                $data['Changed_Date'] = $this->timestamp;


                $this->Common_model->update_data('ac_accounts_vouchermst', $data, 'Accounts_VoucherMst_AutoID', $invoiceId);


                $acountDr = $this->input->post('accountCr'); // account Head bellow

                $cashCrId = $this->input->post('cash');
                $bankId = $this->input->post('accountDr');
                $cashCr = $this->input->post('cashCr');
                $bankCr = $this->input->post('bankCr');


                $allDataUpdate = array();
                $allDatainsert = array();


                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $del = $this->db->delete('ac_tb_accounts_voucherdtl');


                /*   $Delete['IsActive'] = 0;
                   $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                   $this->db->update('ac_tb_accounts_voucherdtl', $Delete);*/

                $cashCrCondition = array(
                    'TypeID' => 1,
                    'Accounts_VoucherMst_AutoID' => $invoiceId,
                    'CHILD_ID' => $this->input->post('cash')
                );

                if (!empty($cashCrId)) {
                    $dr['Accounts_VoucherMst_AutoID'] = $invoiceId;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $cashCrId;
                    $dr['GR_CREDIT'] = $cashCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Changed_By'] = $this->admin_id;
                    $dr['Changed_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $dr, $cashCrCondition);

                }
                $bankCrCondition = array(
                    'TypeID' => 1,
                    'Accounts_VoucherMst_AutoID' => $invoiceId,
                    'CHILD_ID' => $this->input->post('accountDr')
                );

                if (!empty($bankId)) {
                    $dr['Accounts_VoucherMst_AutoID'] = $invoiceId;
                    $dr['TypeID'] = 1;
                    $dr['CHILD_ID'] = $bankId;
                    $dr['GR_CREDIT'] = $bankCr;
                    $dr['GR_DEBIT'] = 0;
                    $dr['IsActive'] = 1;
                    $dr['Changed_By'] = $this->admin_id;
                    $dr['Changed_Date'] = $this->timestamp;
                    $dr['BranchAutoId'] = $this->input->post('BranchAutoId');
                    $dr['date'] = date('Y-m-d', strtotime($this->input->post('date')));

                    $this->Common_model->save_and_check('ac_tb_accounts_voucherdtl', $dr, $bankCrCondition);

                }


                foreach ($acountDr as $key => $value) {
                    $costCondition = array(
                        'Accounts_VoucherMst_AutoID' => $invoiceId,
                        'CHILD_ID' => $value,
                    );
                    $checkArray = $this->Common_model->get_single_data_by_many_columns('ac_tb_accounts_voucherdtl', $costCondition);

                    if (!empty($checkArray)) {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = $this->input->post('amountCr')[$key];
                        $jv['GR_CREDIT'] = 0;
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memo')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['TypeID'] = 2;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $allDataUpdate[] = $jv;
                        unset($jv);
                    } else {
                        $jv['Accounts_VoucherMst_AutoID'] = $invoiceId;
                        $jv['CHILD_ID'] = $value;
                        $jv['GR_DEBIT'] = $this->input->post('amountCr')[$key];
                        $jv['GR_CREDIT'] = 0;
                        $jv['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                        $jv['Reference'] = $this->input->post('memoCr')[$key];
                        $jv['IsActive'] = 1;
                        $jv['Created_By'] = $this->dist_id;
                        $jv['Changed_By'] = $this->admin_id;
                        $jv['Changed_Date'] = $this->timestamp;
                        $jv['BranchAutoId'] = $this->input->post('BranchAutoId');
                        $jv['TypeID'] = 2;
                        $allDatainsert[] = $jv;
                        unset($jv);
                    }
                }
                $this->db->where('Accounts_VoucherMst_AutoID', $invoiceId);
                $this->db->update_batch('ac_tb_accounts_voucherdtl', $allDataUpdate, 'CHILD_ID');

                if (!empty($allDatainsert)) {
                    $this->db->insert_batch('ac_tb_accounts_voucherdtl', $allDatainsert);
                }


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/employeeVoucherAdd/'));
                } else {

                    $msg = $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/employeeVoucherView/' . $invoiceId));

                }
            }
        }

        $data['receiveVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $invoiceId);
        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();

        $conditionaccountHeadListByCash = array(
            'status' => '1',
            'posted' => '1',
            'parent_id' => '28'
        );

        $data['accountHeadListByCash'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByCash);

        $conditionaccountHeadListByBank = array(
            'status' => '1',
            'posted' => '1',
            'parent_id' => '32'
        );

        $data['accountHeadListByBank'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $conditionaccountHeadListByBank);

//payment voucher Credit Account
        $data['getDebitAccountId'] = $this->Finane_Model->getDebitAccountIdEmployeeVoucherNew($this->dist_id, $invoiceId);
//        echo '<pre>';
//        echo print_r($data['getDebitAccountId']);
//        exit();

//payment voucher debit account
        $data['getCreditAccountId'] = $this->Finane_Model->getCreditAccountIdEmployeeVoucherNew($this->dist_id, $invoiceId);

        $branchCondition = array(
            'is_active' => 1
        );
        $data['branch'] = $this->Common_model->get_data_list_by_many_columns('branch', $branchCondition);


        /*page navbar details*/
        $data['title'] = get_phrase('Edit Employee Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Employee Voucher');
        $data['link_page_url'] = $this->project . '/employeeVoucherAdd';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase(' Employee Voucher');
        $data['second_link_page_url'] = $this->project . '/employeeVoucher';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';
        $data['third_link_page_name'] = get_phrase('View Employee Voucher');
        $data['third_link_page_url'] = $this->project . '/employeeVoucherView/' . $invoiceId;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/

        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucherEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function employeeVoucherView($voucherID)
    {


        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $costCondition = array(
            'Accounts_VoucherMst_AutoID' => $voucherID,
            'IsActive' => 1,
        );
        $data['receiveVoucher'] = $this->Common_model->get_single_data_by_single_column('ac_accounts_vouchermst', 'Accounts_VoucherMst_AutoID', $voucherID);
        $data['receiveJournal'] = $this->Common_model->get_data_list_by_many_columns('ac_tb_accounts_voucherdtl', $costCondition);
        if ($data['receiveVoucher']->customer_id):
            $data['customerInfo'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $data['receiveVoucher']->customer_id);
        elseif ($data['receiveVoucher']->supplier_id):
            $data['supplierInfo'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $data['receiveVoucher']->supplier_id);
        else:
        endif;
        $data['title'] = 'Receive Voucher';


        /*page navbar details*/
        $data['title'] = get_phrase('Receive Voucher');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Employee Voucher');
        $data['link_page_url'] = $this->project . '/employeeVoucherAdd';
        $data['link_icon'] = $this->link_icon_add;

        $data['second_link_page_name'] = get_phrase('Employee Voucher');
        $data['second_link_page_url'] = $this->project . '/employeeVoucher';
        $data['second_link_icon'] = $this->link_icon_list;
        $data['therd_link_icon'] = '<i class="fa fa-list"></i>';

        //$data['third_link_page_url'] = $this->project . '/receiveVoucherEdit/' . $voucherID;
        $data['third_link_icon'] = '<i class="fa fa-edit"></i>';
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/employeeVoucherView', $data, true);
        $this->load->view('distributor/masterTemplate', $data);


    }

    public function employeeList()
    {

        $this->Filter_Model->filterData('ac_accounts_vouchermst',
            array('ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            array('ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            array('ac_accounts_vouchermst.Accounts_Voucher_Date', 'ac_accounts_vouchermst.Accounts_Voucher_No', 'accounts_vouchertype_autoid.Accounts_VoucherType', 'ac_accounts_vouchermst.narration'),
            $this->dist_id);


        $list = $this->Filter_Model->get_receive_datatables();

        // log_message('error', 'Hi mamun receiveList ' . print_r($list, true));


        $data = array();
        $no = $_POST['start'];
        foreach ($list as $receive) {
            $no++;
            $row = array();
            $row[] = $no;

            $row[] = '<a title="View Payment Voucher" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->AccouVoucherType_AutoID) . '">' . $receive->Accounts_Voucher_No . '</a></td>';
            $row[] = date('M d, Y', strtotime($receive->Accounts_Voucher_Date));
            $row[] = $receive->Accounts_VoucherType;//$payment->name;
            $row[] = $receive->branch_name;
            $row[] = $receive->name;
            $row[] = $receive->Narration;

            $row[] = number_format((float)$receive->amount, 2, '.', ',');


            $row[] = '
              <a class="btn btn-icon-only red financeEditPermission" href="' . site_url($this->project . '/receiveVoucherEdit/' . $receive->Accounts_VoucherMst_AutoID) . '">
            <i class="ace-icon fa fa-pencil bigger-130"></i></a>
            <a class="btn btn-icon-only blue" href="' . site_url($this->project . '/receiveVoucherView/' . $receive->Accounts_VoucherMst_AutoID) . '">
    <i class="ace-icon fa fa-search-plus bigger-130"></i></a>

    ';
            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Filter_Model->count_all_receive(),
            "recordsFiltered" => $this->Filter_Model->count_filtered_receive(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function designationAdd()
    {
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

        $data['designationList'] = $this->InvProductProperty_Model->getDesinationList();
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/designation/designationAdd', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }

    public function designationEdit($editId)
    {
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
        $data['getdesignationById'] = $this->InvProductProperty_Model->getSingleDataDesignation($editId);
        /*page navbar details*/
        $data['title'] = get_phrase('Designation Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('Designation List');
        $data['link_page_url'] = $this->project . '/designationAdd';

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/setup/employee/designation/designationEdit', $data, true);
        $this->load->view('distributor/masterTemplate', $data);

    }


    public function designationDelete($deleteId)
    {

        $checkDesignation = $this->InvProductProperty_Model->checkDesignationId($deleteId);

        if (empty($checkDesignation)) {
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
        } else {
            $msg = "This Designation Use Another Table";
            $this->session->set_flashdata('error', $msg);
            redirect(site_url($this->project . '/designationAdd'));
        }

    }


    function statusdesignationDepartment($id)
    {

        $result = $this->InvProductProperty_Model->unpublisheddesignation($id);

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

        $result = $this->InvProductProperty_Model->publisheddesignation($id);

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

    public function dayBookAdd($postingId = null)
    {

        if (isPostBack()) {


            //set some validation for input fields

            $this->form_validation->set_rules('daybook', 'daybook', 'required|is_unique[day_book_report_config.acc_group_id]');


            if ($this->form_validation->run() === FALSE) {
                $msg = "Required field can't be empty Or Not Same Value";
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/dayBookAdd'));
            } else {
                $this->db->trans_start();
                $data['acc_group_id'] = $this->input->post('daybook');


                $this->db->insert('day_book_report_config', $data);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $msg = "Your data can't be inserted";
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/dayBookAdd'));
                } else {
                    $msg = "Your data successfully inserted into database";
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/dayBookAdd'));

                }
            }

        }

        $data['accountHeadList'] = $this->Common_model->getAccountHeadNew();
        $data['getalldayBook'] = $this->DayBook_Model->getalldayBook();

        /*page navbar details*/
        $data['title'] = get_phrase('Day Book Add');
        $data['page_type'] = $this->page_type;

        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/daybook/dayBookConfig', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    public function checkdayBooks($id)
    {
        $this->DayBook_Model->checkdayBook($id);
//        echo '<pre>';
//        print_r($data);
//        exit();
    }

    public function checkdayBook()
    {
        $this->DayBook_Model->getalldayBook();
    }

    public function daybookDelete($id)
    {

        $result = $this->DayBook_Model->delete_daybook($id);
        if ($result) {
            $this->session->set_flashdata('message', 'Deleted Sucessfully');
            redirect(site_url($this->project . '/dayBookAdd'));
        } else {
            $this->session->set_flashdata('message', 'Deleted Failed');
            redirect(site_url($this->project . '/dayBookAdd'));
        }
    }


    function product_property_set()
    {
        if (isPostBack()) {


            //set some validation for input fields
            $this->db->trans_start();
            $active = array();

            $Delete['is_show'] = 0;
            $this->db->update('product_property', $Delete);

            for ($i = 1; $i <= 8; $i++) {

                if (isset($_POST['txt' . $i])) {
                    $Condition = array(
                        'property_id' => $i
                    );
                    $active['property_id'] = $i;
                    $active['is_show'] =1;
                    $active['property_name_show'] = $_POST['txt' . $i];

                    $this->Common_model->save_and_check('product_property', $active, $Condition);
                    $active = array();
                    $Condition = array();
                }
            }




            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $msg = $this->config->item("update_error_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/product_property_set'));
            } else {

                $msg = $this->config->item("update_success_message");
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/product_property_set' ));

            }


        }
        $data=array();

        $data['mainContent'] = $this->load->view('distributor/inventory/product/product_property_set', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

}