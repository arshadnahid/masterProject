<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 4/12/2020
 * Time: 10:21 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class UserController extends CI_Controller {


    private $timestamp;
    public $admin_id;
    private $dist_id;
    public $project;


    public $business_type;
    public $folder;
    public $folderSub;
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->business_type = $this->session->userdata('business_type');
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);




        if ($this->business_type == "MOBILE") {
            $this->folder = 'distributor/masterTemplateSmeMobile';

            $this->folderSub = 'distributor/setup_mobile/';
        }else{
            $this->folder = 'distributor/setup';
            $this->folderSub = 'distributor/setup/';
        }

    }



    function userList() {
        /*page navbar details*/
        $data['title'] = 'User List';
        $data['page_type']=$this->page_type;
        $data['link_page_name']='User Add';
        $data['link_page_url']=$this->project.'/addUser';
        $data['link_icon']="<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['userList'] = $this->Common_model->get_data_list_by_single_column('admin', '1', 1);

        $data['mainContent'] = $this->load->view($this->folderSub.'user/userList', $data, true);

        $this->load->view('distributor/masterTemplate', $data);
    }

    function addUser()
    {

        if (isPostBack()) {


            $this->form_validation->set_rules('name', 'User Name', 'required');
            $this->form_validation->set_rules('email', 'User Email', 'required');
            $this->form_validation->set_rules('user_role', 'User Role', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = 'Required field can not be empty';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/addUser'));
            } else {
                $email=$this->input->post('email');


                $query = $this->db->field_exists('m_distributorid', 'admin');
                if ($query != TRUE) {
                    $this->load->dbforge();
                    $fields = array(
                        'm_distributorid' => array(
                            'type' => 'INT',
                            'constraint' => 11,
                            //'unsigned' => TRUE,
                            'after' => 'distributor_id')
                    );
                    $this->dbforge->add_column('admin', $fields);
                }
                $this->db->close();

                $Maindb_username=$this->config->item("Maindb_username");
                $Maindb_password=$this->config->item("Maindb_password");
                $Maindb_name=$this->config->item("Maindb_name");
                $config_app = switch_db_dinamico($Maindb_username, $Maindb_password, $Maindb_name);
                $this->db = $this->load->database($config_app, TRUE);

                $condition = array(
                    'email' => $email,
                    'm_distributorid' => $this->session->userdata('m_distributorid'),
                );
                $exitsSup = $this->Common_model->get_single_data_by_many_columns('master_admin', $condition);
                if (empty($exitsSup)) {
                    $dataUser['distributor_id']=$this->dist_id;;
                    $dataUser['m_distributorid']=$this->session->userdata('m_distributorid');
                    $dataUser['type']="Master";
                    $dataUser['status']="1";
                    $dataUser['accessType']="1";
                    $dataUser['name']= $this->input->post('name');
                    $dataUser['phone']= $this->input->post('phone');
                    $dataUser['email']= $this->input->post('email');
                    $dataUser['password'] = md5($this->input->post('password'));
                    $dataUser['created_at'] = $this->timestamp;
                    $m_admin_id=$this->Common_model->insert_data('master_admin', $dataUser);


                    $this->db->close();
                    $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
                    $this->db = $this->load->database($config_app, TRUE);

                    $data['name'] = $this->input->post('name');
                    $data['phone'] = $this->input->post('phone');
                    $data['user_role'] = $this->input->post('user_role');
                    $data['email'] = $this->input->post('email');
                    $data['password'] = md5($this->input->post('password'));
                    $data['distributor_id'] = $this->dist_id;
                    $data['accessType'] = 2;
                    $data['type'] = 'Master';
                    $data['status'] = '1';
                    $data['updated_by'] = $this->admin_id;
                    $data['m_distributorid'] = $m_admin_id;
                    $insertId = $this->Common_model->insert_data('admin', $data);

                    if (!empty($insertId)):
                        $this->db->close();
                        $config_app = switch_db_dinamico($Maindb_username, $Maindb_password, $Maindb_name);
                        $this->db = $this->load->database($config_app, TRUE);

                        $data['d_admin_id'] = $insertId;
                        $this->Common_model->update_data('master_admin', $data, 'admin_id', $m_admin_id);
                        $this->db->close();
                        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
                        $this->db = $this->load->database($config_app, TRUE);
                        $msg = "New User Created successfully.";
                        $this->session->set_flashdata('success', $msg);
                    else:
                        $msg = "Can not  Save New User ";
                        $this->session->set_flashdata('error', $msg);
                    endif;

                } else {
                    $msg = "User Already Exist In The System.Please Contact With Call Canter ";
                    $this->session->set_flashdata('error', $msg);

                }
                redirect(site_url($this->project . '/userList'));

            }


        }

        /*page navbar details*/
        $data['title'] = get_phrase('User Add');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = get_phrase('User List');
        $data['link_page_url'] = $this->project . '/userList';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['userList'] = $this->Common_model->get_data_list_by_single_column('admin', 'distributor_id', $this->dist_id);
        $data['mainContent'] = $this->load->view($this->folderSub.'user/addUser', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

    function editUser($editId) {
        if (isPostBack()) {
            $data['name'] = $this->input->post('name');
            $data['phone'] = $this->input->post('phone');
            $data['email'] = $this->input->post('email');
            $data['distributor_id'] = $this->session->userdata('m_distributorid');
            $data['updated_by'] = $this->admin_id;
            $this->Common_model->update_data('admin', $data, 'admin_id', $editId);
            $msg="User update successfully";
            $this->session->set_flashdata('success', $msg);
            //message("User update successfully.");
            redirect(site_url($this->project.'/userList'));
        }
        /*page navbar details*/
        $data['title'] = 'Update User';
        $data['page_type']=$this->page_type;
        $data['link_page_name']='User List';
        $data['link_page_url']=$this->project.'/userList';
        $data['link_icon']="<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['editInfo'] = $this->Common_model->get_single_data_by_single_column('admin', 'admin_id', $editId);
        $data['userList'] = $this->Common_model->get_data_list_by_single_column('admin', 'distributor_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/setup/user/editUser', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


}