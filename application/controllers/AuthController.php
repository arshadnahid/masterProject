<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class AuthController extends CI_Controller {
    private $timestamp;
    public function __construct() {
        parent::__construct();
        //$this->load->model('Common_model', 'Finane_Model', 'Inventory_Model', 'Sales_Model');
        $this->load->model('Common_model');

        $this->timestamp = date('Y-m-d H:i:s');
    }
    public function index() {





        $thisdis = $this->session->userdata('dis_id');
        $admin_id = $this->session->userdata('admin_id');
        $this->project = $this->session->userdata('project');
        if (!empty($thisdis) && !empty($admin_id) && $this->project !='') {
            redirect(site_url($this->project . '/moduleDashboard'));
        }
        if (isPostBack()) {
            $project = $this->uri->segment(1);
            //  echo $project;die;
            $this->session->set_userdata('project', $project);
            $data['email'] = $this->input->post('dist_email');
            $data['password'] = md5($this->input->post('dist_password'));
            $data['accessType'] = 2;
            $data['project'] = $project;
            // $userInfo = $this->Common_model->get_single_data_by_many_columns('admin', $data);
            $userInfo = $this->Common_model->check_loginrequest($data);



            if (empty($userInfo)) {
                $this->session->set_flashdata('msg', 'Undefined Username OR Password!');
                redirect(site_url($project . '/login'));
            } elseif (!empty($userInfo) && $userInfo->status == 2) {
                $this->session->set_flashdata('msg', 'Your Are inactive Distributor.Please Contact with admin to active your account.');
                redirect(site_url($project . '/login'));
            } else {
                $ip_addr = $this->input->ip_address();
                $data1['ipAddress'] = $ip_addr;
                $data1['adminId'] = $userInfo->admin_id;
                $data1['logIn'] = $this->timestamp;
                $data1['date'] = date('Y-m-d');
                $data1['distId'] = $userInfo->distributor_id;
                //$lastId = $this->Common_model->insert_data('adminloghistory', $data1);
                $this->session->set_userdata('dis_id', $userInfo->distributor_id);
                $this->session->set_userdata('admin_id', $userInfo->d_admin_id);
                $this->session->set_userdata('master_db_admin_id', $userInfo->admin_id);
                $this->session->set_userdata('username', $userInfo->name);
                $this->session->set_userdata('type', $userInfo->type);
                $this->session->set_userdata('loginTime', time());
                // $this->session->set_userdata('loginId', $lastId);
                $this->session->set_userdata('db_hostname', $userInfo->hostname);
                $this->session->set_userdata('db_username', $userInfo->username);
                $this->session->set_userdata('db_password', $userInfo->password);
                $this->session->set_userdata('db_name', $userInfo->db_name);
                $this->session->set_userdata('m_distributorid', $userInfo->m_distributorid);
                $this->session->set_userdata('business_type', $userInfo->business_type);
                unset($userInfo);
                redirect(site_url($project . '/moduleDashboard'));
            }
        }
        $data['message'] = 'Your software renew data is expire.please renew your software.';
        $this->load->view('auth/login', $data);




        /*$thisdis = $this->session->userdata('dis_id');
        $admin_id = $this->session->userdata('admin_id');
        if (!empty($thisdis) || !empty($admin_id)) {
            redirect(site_url('moduleDashboard'));
        }
        $this->load->view('auth/login');*/
    }

    public function forgotpasswor() {
        $this->load->view('auth/forgotpassword');
    }

    public function resetPasswordUpdate($userId = null, $activationCode = null) {
        if (isPostBack()) {
            $password = $this->input->post('password');
            $confirmPassword = $this->input->post('confirmPassword');
            if (empty($password) || empty($confirmPassword)) {
                $this->session->set_flashdata('msg', 'Password or Confirm password not empty.');
                redirect(site_url('AuthController/resetPasswordUpdate/' . $userId . '/' . $activationCode));
            } else if ($password != $confirmPassword) {
                $this->session->set_flashdata('msg', 'Password should be same with Confirm password.');
                redirect(site_url('AuthController/resetPasswordUpdate/' . $userId . '/' . $activationCode));
            } else {
                $data_info['password'] = md5($this->input->post('password'));
                $data_info['activationCode'] = '';
                $this->Common_model->update_data('admin', $data_info, 'admin_id', $userId);
                $this->session->set_flashdata('msg', 'Your password successfully updated');
                redirect(site_url());
            }
        }
        $condition = array(
            'admin_id' => $userId,
            'activationCode' => $activationCode,
        );
        $valid_user = $this->Common_model->get_single_data_by_many_columns('admin', $condition);
        if (empty($valid_user)) {
            $this->session->set_flashdata('msg', 'Your given Information not correct');
            redirect(site_url());
        }
        $this->load->view('auth/resetPassword');
    }

    public function checkLogin() {

        $data['email'] = $this->input->post('dist_email');
        $data['password'] = md5($this->input->post('dist_password'));
        $data['accessType'] = 2;
        $userInfo = $this->Common_model->get_single_data_by_many_columns('admin', $data);
        if (empty($userInfo)) {
            $this->session->set_flashdata('msg', 'Undefined Username OR Password!');
            redirect(site_url());
        } elseif (!empty($userInfo) && $userInfo->status == 2) {
            $this->session->set_flashdata('msg', 'Your Are inactive Distributor.Please Contact with admin to active your account.');
            redirect(site_url());
        } else {
            $ip_addr = $this->input->ip_address();
            $data1['ipAddress'] = $ip_addr;
            $data1['adminId'] = $userInfo->admin_id;
            $data1['logIn'] = $this->timestamp;
            $data1['date'] = date('Y-m-d');
            $data1['distId'] = $userInfo->distributor_id;
            $lastId = $this->Common_model->insert_data('adminloghistory', $data1);
            $this->session->set_userdata('dis_id', $userInfo->distributor_id);
            $this->session->set_userdata('admin_id', $userInfo->admin_id);
            $this->session->set_userdata('username', $userInfo->name);
            $this->session->set_userdata('type', $userInfo->type);
            $this->session->set_userdata('loginTime', time());
            $this->session->set_userdata('loginId', $lastId);
            unset($userInfo);
            redirect(site_url('moduleDashboard'));
        }
    }
    public function signout() {
        $project = $this->session->userdata('project');
        //echo $project;exit;

        $loginId = $this->session->userdata('loginId');

        $data['logOut'] = date('Y-m-d H:i:s');

        $this->session->unset_userdata('dis_id');
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('type');
        $this->session->unset_userdata('loginTime');
        $this->session->unset_userdata('db_hostname');
        $this->session->unset_userdata('db_username');
        $this->session->unset_userdata('db_password');
        $this->session->unset_userdata('db_name');
        $this->session->unset_userdata('project');
        $this->session->unset_userdata('m_distributorid');
        $this->session->unset_userdata('business_type');
        $this->session->sess_destroy();
        session_destroy();
        //redirect(site_url('AuthController'));
        redirect(site_url($project . '/login'));
    }

    function resetPassword() {
        $email = $this->input->post('email');
        if (!empty($email)) {
            $emailExits = $this->Common_model->get_single_data_by_single_column('admin', 'email', $email);
            if (empty($emailExits)) {
                $this->session->set_flashdata('msg', 'Invalid username or email');
                redirect(site_url('AuthController/forgotpasswor'));
            }
            $email_data['activationCode'] = md5(rand());
            $this->Common_model->update_data('admin', $email_data, 'admin_id', $emailExits->admin_id);
            $email_data['userId'] = $emailExits->admin_id;
            $msg = $this->load->view('auth/emailReset', $email_data, TRUE);
            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'mail.ael-bd.net';
            $config['smtp_port'] = '26';
            $config['smtp_user'] = 'to@ael-bd.net';
            $config['smtp_pass'] = '123456@2018';
            $config['charset'] = 'iso-8859-1';
            $config['type'] = 'html';
            $config['wordwrap'] = TRUE;
            $this->email->initialize($config);
            $this->email->from('to@ael-bd.net', 'ERP Notification');
            $this->email->to($email);
            $this->email->subject('We received a password reset request.');
            $this->email->message($msg);
            $this->email->set_mailtype('html');
            $this->email->send();
            $this->email->clear(TRUE);
            $this->session->set_flashdata('msg', 'Check your email with a link to update your password');
            redirect(site_url());
        } else {
            $this->session->set_flashdata('msg', 'Invalid username or email');
            redirect(site_url('AuthController/forgotpasswor'));
        }
    }

    function signoutHeadoffice() {
        $this->session->unset_userdata('dis_id');
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('type');
        $this->session->unset_userdata('loginTime');
        $this->session->unset_userdata('headOffice');
        redirect(site_url('adminDashboard'));
    }

}
