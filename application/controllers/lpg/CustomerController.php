<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/10/2019
 * Time: 9:41 AM
 */

class CustomerController extends CI_Controller
{

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $folder;
    public $folderSub;
    public $page_type;
    public $link_icon_add;
    public $link_icon_list;
    public $project;


    public $business_type;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'sales';

        $this->link_icon_add = "<i class='ace-icon fa fa-plus'></i>";
        $this->link_icon_list = "<i class='ace-icon fa fa-list'></i>";


        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->business_type = $this->session->userdata('business_type');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);


        //$this->folder = 'distributor/masterTemplate';


        if ($this->business_type == "MOBILE") {
            $this->folder = 'distributor/masterTemplateSmeMobile';
            $this->folderSub = 'distributor/sales/customer_mobile/';

            //$this->folderSub = 'distributor/inventory/product_mobile/';
        } else {
            $this->folder = 'distributor/masterTemplate';
            $this->folderSub = 'distributor/sales/customer/';
        }

    }

    function customerList()
    {


        /*page navbar details*/
        $data['title'] = get_phrase('Customer List');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add_Customer');
        $data['link_page_url'] = $this->project . '/addCustomer';
        $data['link_icon'] = $this->link_icon_add;
        /*page navbar details*/

        $data['mainContent'] = $this->load->view($this->folderSub . 'customerList', $data, true);
        $this->load->view($this->folder, $data);
    }

    function addCustomer()
    {
        if (isPostBack()) {
            //dumpVar($_POST);
            $this->form_validation->set_rules('customerID', 'Customer Id Code', 'required');
            $this->form_validation->set_rules('customerName', 'Customer Name', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/addCustomer/'));
            } else {

                $this->db->trans_start();
                $a = array();
                $b = array();
                unset($data);
                $data = array();
                $data['customerID'] = $this->input->post('customerID');
                $data['customerType'] = $this->input->post('customerType');
                $data['root_id'] = $this->input->post('root_id');
                $data['customerName'] = trim($this->input->post('customerName'));
                $data['customerPhone'] = $this->input->post('customerPhone');
                $data['customerEmail'] = $this->input->post('customerEmail');
                $data['customerAddress'] = $this->input->post('customerAddress');
                $data['credit_limit'] = isset($_POST['credit_limit'])?$this->input->post('credit_limit'):0;
                $data['credit_days'] = isset($_POST['credit_days'])?$this->input->post('credit_days'):0;

                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertID = $this->Common_model->insert_data('customer', $data);


                $condtion = array(
                    'id' => $this->config->item("Customer_Receivable"),//33,
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $condition2 = array(
                    'parent_id' => $this->config->item("Customer_Receivable"),//33,
                );
                $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);

                if (!empty($totalAccount)):
                    $totalAccount = count($totalAccount);
                    $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
                else:
                    $totalAdded = 0;
                    $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
                endif;


                $level_no = $ac_account_ledger_coa_info->level_no;
                $parent_id = $ac_account_ledger_coa_info->id;
                unset($dataCoa);
                $dataCoa = array();
                $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
                $dataCoa['code'] = $newCode;
                $dataCoa['parent_name'] = $this->input->post('customerName') . ' [ ' . $data['customerID'] . ' ]';
                $dataCoa['status'] = 1;
                $dataCoa['posted'] = 1;
                $dataCoa['level_no'] = $level_no + 1;
                $dataCoa['related_id'] = $insertID;
                $dataCoa['related_id_for'] = 3;
                $dataCoa['insert_by'] = $this->admin_id;
                $dataCoa['insert_date'] = date('Y-m-d H:i:s');
                $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
                unset($a);

                for ($x = 0; $x <= 7; $x++) {
                    unset($b);
                    if ($parent_id != 0) {
                        $condtion = array(
                            'id' => $parent_id,
                        );
                        $parentDetails = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                        $parent_id = $parentDetails->parent_id;
                        $b['id'] = $parentDetails->id;
                        $b['parent_name'] = $parentDetails->parent_name;
                        $a[] = $b;
                    }
                }

                $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_ledger_id);
                if ($PARENT_ID_DATA->posted != 0) {
                    $dataac_tb_coa['CHILD_ID'] = $PARENT_ID_DATA->id;
                } else {
                    $dataac_tb_coa['CHILD_ID'] = 0;
                }
                $a = array_reverse($a);
                $dataac_tb_coa['PARENT_ID'] = isset($a[0]) ? $a[0]['id'] : 0;
                $dataac_tb_coa['PARENT_ID1'] = isset($a[1]) ? $a[1]['id'] : 0;
                $dataac_tb_coa['PARENT_ID2'] = isset($a[2]) ? $a[2]['id'] : 0;
                $dataac_tb_coa['PARENT_ID3'] = isset($a[3]) ? $a[3]['id'] : 0;
                $dataac_tb_coa['PARENT_ID4'] = isset($a[4]) ? $a[4]['id'] : 0;
                $dataac_tb_coa['PARENT_ID5'] = isset($a[5]) ? $a[5]['id'] : 0;
                $dataac_tb_coa['PARENT_ID6'] = isset($a[6]) ? $a[6]['id'] : 0;
                $dataac_tb_coa['PARENT_ID7'] = isset($a[7]) ? $a[7]['id'] : 0;
                $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_ledger_id;
                $Condition = array(
                    'TB_AccountsLedgerCOA_id' => $inserted_ledger_id
                );
                $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);


                if($this->business_type=="LPG"){
                    $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);

                    if (!empty($totalAccount)):
                        $totalAccount = count($totalAccount);
                        $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
                    else:
                        $totalAdded = 0;
                        $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
                    endif;


                    $level_no = $ac_account_ledger_coa_info->level_no;
                    $parent_id = $ac_account_ledger_coa_info->id;
                    $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
                    $dataCoa['code'] = $newCode;
                    $dataCoa['parent_name'] = $this->input->post('customerName') . ' [ ' . $data['customerID'] . ' ]  Empty Cylinder Due';
                    $dataCoa['status'] = 1;
                    $dataCoa['posted'] = 1;
                    $dataCoa['level_no'] = $level_no + 1;
                    $dataCoa['related_id'] = $insertID;
                    $dataCoa['related_id_for'] = 4;
                    $dataCoa['insert_by'] = $this->admin_id;
                    $dataCoa['insert_date'] = date('Y-m-d H:i:s');
                    $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
                    unset($a);
                    for ($x = 0; $x <= 7; $x++) {
                        unset($b);
                        if ($parent_id != 0) {
                            $condtion = array(
                                'id' => $parent_id,
                            );
                            $parentDetails = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                            $parent_id = $parentDetails->parent_id;
                            $b['id'] = $parentDetails->id;
                            $b['parent_name'] = $parentDetails->parent_name;
                            $a[] = $b;
                        }
                    }

                    $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_ledger_id);
                    if ($PARENT_ID_DATA->posted != 0) {
                        $dataac_tb_coa['CHILD_ID'] = $PARENT_ID_DATA->id;
                    } else {
                        $dataac_tb_coa['CHILD_ID'] = 0;
                    }
                    $a = array_reverse($a);
                    $dataac_tb_coa['PARENT_ID'] = isset($a[0]) ? $a[0]['id'] : 0;
                    $dataac_tb_coa['PARENT_ID1'] = isset($a[1]) ? $a[1]['id'] : 0;
                    $dataac_tb_coa['PARENT_ID2'] = isset($a[2]) ? $a[2]['id'] : 0;
                    $dataac_tb_coa['PARENT_ID3'] = isset($a[3]) ? $a[3]['id'] : 0;
                    $dataac_tb_coa['PARENT_ID4'] = isset($a[4]) ? $a[4]['id'] : 0;
                    $dataac_tb_coa['PARENT_ID5'] = isset($a[5]) ? $a[5]['id'] : 0;
                    $dataac_tb_coa['PARENT_ID6'] = isset($a[6]) ? $a[6]['id'] : 0;
                    $dataac_tb_coa['PARENT_ID7'] = isset($a[7]) ? $a[7]['id'] : 0;
                    $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_ledger_id;
                    $Condition = array(
                        'TB_AccountsLedgerCOA_id' => $inserted_ledger_id
                    );
                    $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);
                }


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg = 'Customer ' . ' ' . $this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/addCustomer/'));
                else:
                    $msg = 'Customer ' . ' ' . $this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/addCustomer'));
                endif;
            }
        }
        /*page navbar details*/
        $data['title'] = get_phrase('Add Customer');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Customer_List');
        $data['link_page_url'] = $this->project . '/customerList';
        $data['link_icon'] = $this->link_icon_list;
        /*page navbar details*/

        $data['root_info'] = $this->db->where('is_active', 1)->get('root_info')->result();
        $customerID = $this->Sales_Model->getCustomerID($this->dist_id);
        $data['customerType'] = $this->Common_model->get_data_list('customertype');
        $data['customerID'] = $this->Sales_Model->checkDuplicateCusID($customerID, $this->dist_id);


        $data['mainContent'] = $this->load->view($this->folderSub . 'addCustomer', $data, true);


        $this->load->view($this->folder, $data);
    }

    function saveNewCustomer()
    {
        $data = array();
        if ($this->input->post('customerType') == "") {
            $customerType = 1;
        } else {
            $customerType = $this->input->post('customerType');
        }
        $data['customerID'] = $this->input->post('customerID');
        $data['customerName'] = $this->input->post('customerName');
        $data['customerPhone'] = $this->input->post('customerPhone');
        $data['customerEmail'] = $this->input->post('customerEmail');
        $data['customerAddress'] = $this->input->post('customerAddress');
        $data['customerType'] = $customerType;
        $data['dist_id'] = $this->dist_id;
        $data['updated_by'] = $this->admin_id;
        $data['credit_limit'] = isset($_POST['credit_limit'])?$this->input->post('credit_limit'):0;
        $data['credit_days'] = isset($_POST['credit_days'])?$this->input->post('credit_days'):0;
        $insertID = $this->Common_model->insert_data('customer', $data);


        $condtion = array(
            'id' => $this->config->item("Customer_Receivable"),//33,
        );
        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
        $condition2 = array(
            'parent_id' => $this->config->item("Customer_Receivable"),//33,
        );
        $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);

        if (!empty($totalAccount)):
            $totalAccount = count($totalAccount);
            $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
        else:
            $totalAdded = 0;
            $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
        endif;


        $level_no = $ac_account_ledger_coa_info->level_no;
        $parent_id = $ac_account_ledger_coa_info->id;
        unset($dataCoa);
        $dataCoa = array();
        $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
        $dataCoa['code'] = $newCode;
        $dataCoa['parent_name'] = $this->input->post('customerName') . ' [ ' . $data['customerID'] . ' ]';
        $dataCoa['status'] = 1;
        $dataCoa['posted'] = 1;
        $dataCoa['level_no'] = $level_no + 1;
        $dataCoa['related_id'] = $insertID;
        $dataCoa['related_id_for'] = 3;
        $dataCoa['insert_by'] = $this->admin_id;
        $dataCoa['insert_date'] = date('Y-m-d H:i:s');
        $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
        unset($a);

        for ($x = 0; $x <= 7; $x++) {
            unset($b);
            if ($parent_id != 0) {
                $condtion = array(
                    'id' => $parent_id,
                );
                $parentDetails = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $parent_id = $parentDetails->parent_id;
                $b['id'] = $parentDetails->id;
                $b['parent_name'] = $parentDetails->parent_name;
                $a[] = $b;
            }
        }

        $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_ledger_id);
        if ($PARENT_ID_DATA->posted != 0) {
            $dataac_tb_coa['CHILD_ID'] = $PARENT_ID_DATA->id;
        } else {
            $dataac_tb_coa['CHILD_ID'] = 0;
        }
        $a = array_reverse($a);
        $dataac_tb_coa['PARENT_ID'] = isset($a[0]) ? $a[0]['id'] : 0;
        $dataac_tb_coa['PARENT_ID1'] = isset($a[1]) ? $a[1]['id'] : 0;
        $dataac_tb_coa['PARENT_ID2'] = isset($a[2]) ? $a[2]['id'] : 0;
        $dataac_tb_coa['PARENT_ID3'] = isset($a[3]) ? $a[3]['id'] : 0;
        $dataac_tb_coa['PARENT_ID4'] = isset($a[4]) ? $a[4]['id'] : 0;
        $dataac_tb_coa['PARENT_ID5'] = isset($a[5]) ? $a[5]['id'] : 0;
        $dataac_tb_coa['PARENT_ID6'] = isset($a[6]) ? $a[6]['id'] : 0;
        $dataac_tb_coa['PARENT_ID7'] = isset($a[7]) ? $a[7]['id'] : 0;
        $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_ledger_id;
        $Condition = array(
            'TB_AccountsLedgerCOA_id' => $inserted_ledger_id
        );
        $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);


        $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);


        if ($this->business_type == "LPG") {

            if (!empty($totalAccount)):
                $totalAccount = count($totalAccount);
                $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
            else:
                $totalAdded = 0;
                $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
            endif;


            $level_no = $ac_account_ledger_coa_info->level_no;
            $parent_id = $ac_account_ledger_coa_info->id;
            $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
            $dataCoa['code'] = $newCode;
            $dataCoa['parent_name'] = $this->input->post('customerName') . ' [ ' . $data['customerID'] . ' ]  Empty Cylinder Due';
            $dataCoa['status'] = 1;
            $dataCoa['posted'] = 1;
            $dataCoa['level_no'] = $level_no + 1;
            $dataCoa['related_id'] = $insertID;
            $dataCoa['related_id_for'] = 4;
            $dataCoa['insert_by'] = $this->admin_id;
            $dataCoa['insert_date'] = date('Y-m-d H:i:s');
            $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
            unset($a);
            for ($x = 0; $x <= 7; $x++) {
                unset($b);
                if ($parent_id != 0) {
                    $condtion = array(
                        'id' => $parent_id,
                    );
                    $parentDetails = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                    $parent_id = $parentDetails->parent_id;
                    $b['id'] = $parentDetails->id;
                    $b['parent_name'] = $parentDetails->parent_name;
                    $a[] = $b;
                }
            }

            $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_ledger_id);
            if ($PARENT_ID_DATA->posted != 0) {
                $dataac_tb_coa['CHILD_ID'] = $PARENT_ID_DATA->id;
            } else {
                $dataac_tb_coa['CHILD_ID'] = 0;
            }
            $a = array_reverse($a);
            $dataac_tb_coa['PARENT_ID'] = isset($a[0]) ? $a[0]['id'] : 0;
            $dataac_tb_coa['PARENT_ID1'] = isset($a[1]) ? $a[1]['id'] : 0;
            $dataac_tb_coa['PARENT_ID2'] = isset($a[2]) ? $a[2]['id'] : 0;
            $dataac_tb_coa['PARENT_ID3'] = isset($a[3]) ? $a[3]['id'] : 0;
            $dataac_tb_coa['PARENT_ID4'] = isset($a[4]) ? $a[4]['id'] : 0;
            $dataac_tb_coa['PARENT_ID5'] = isset($a[5]) ? $a[5]['id'] : 0;
            $dataac_tb_coa['PARENT_ID6'] = isset($a[6]) ? $a[6]['id'] : 0;
            $dataac_tb_coa['PARENT_ID7'] = isset($a[7]) ? $a[7]['id'] : 0;
            $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_ledger_id;
            $Condition = array(
                'TB_AccountsLedgerCOA_id' => $inserted_ledger_id
            );
            $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);

        }
        $customerType = $this->Common_model->tableRow('customertype', 'type_id', $data['customerType'])->typeTitle;

        if (!empty($insertID)):
            echo '<option value="' . $insertID . '" selected="selected">' . $customerType . ' - ' . $data['customerID'] . ' [ ' . $data['customerName'] . ' ] ' . '</option>';
        endif;
    }

    function editCustomer($customerid)
    {
        if (isPostBack()) {
            $this->form_validation->set_rules('customerID', 'Product Code', 'required');
            $this->form_validation->set_rules('customerName', 'Customer Name', 'required');
            //$this->form_validation->set_rules('customerPhone', 'Product Branch', 'required');
            //$this->form_validation->set_rules('customerAddress', 'Customer Address', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg = $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/addCustomer/'));
            } else {
                $this->db->trans_start();
                $data = array();
                $data['customerID'] = $this->input->post('customerID');
                $data['customerName'] = $this->input->post('customerName');
                $data['customerType'] = $this->input->post('customerType');
                $data['root_id'] = $this->input->post('root_id');
                $data['customerPhone'] = $this->input->post('customerPhone');
                $data['customerEmail'] = $this->input->post('customerEmail');
                $data['customerAddress'] = $this->input->post('customerAddress');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $data['credit_limit'] = isset($_POST['credit_limit'])?$this->input->post('credit_limit'):0;
                $data['credit_days'] = isset($_POST['credit_days'])?$this->input->post('credit_days'):0;
                $this->Common_model->update_data('customer', $data, 'customer_id', $customerid);

                $condtion = array(
                    'related_id' => $customerid,
                    'related_id_for' => 3,
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $dataCOA['parent_name'] = $this->input->post('customerName') . ' [ ' . $this->input->post('customerID') . ' ]';
                $this->Common_model->update_data('ac_account_ledger_coa', $dataCOA, 'id', $ac_account_ledger_coa_info->id);


                $condtion = array(
                    'related_id' => $customerid,
                    'related_id_for' => 4,
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $dataCOA['parent_name'] = $this->input->post('customerName') . ' [ ' . $this->input->post('customerID') . ' ]  Empty Cylinder Due';
                $this->Common_model->update_data('ac_account_ledger_coa', $dataCOA, 'id', $ac_account_ledger_coa_info->id);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg = 'Customer ' . ' ' . $this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project . '/addCustomer/'));
                else:
                    $msg = 'Customer ' . ' ' . $this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project . '/addCustomer/'));
                endif;
            }
        }

        /*page navbar details*/
        $data['title'] = get_phrase('Edit Customer');
        $data['page_type'] = get_phrase($this->page_type);
        $data['link_page_name'] = get_phrase('Add Customer');
        $data['link_page_url'] = $this->project . '/addCustomer';
        $data['link_icon'] = $this->link_icon_add;
        $data['second_link_page_name'] = get_phrase('Customer List');
        $data['second_link_page_url'] = $this->project . '/customerList';
        $data['second_link_icon'] = $this->link_icon_list;

        /*page navbar details*/
        $data['root_info'] = $this->db->where('is_active', 1)->get('root_info')->result();
        $data['root_infoEdit'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $customerid);
        $data['customerType'] = $this->Common_model->get_data_list('customertype');
        $data['customerEdit'] = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $customerid);
        $data['mainContent'] = $this->load->view($this->folderSub . 'editCustomer', $data, true);
        $this->load->view($this->folder, $data);
    }

    function customerDelete()
    {
        $id = $this->input->post('id');
        $condition = array(
            'dist_id' => $this->dist_id,
            'ledger_type' => 1,
            'client_vendor_id' => $id,
        );
        $transactionExit = $this->Common_model->get_single_data_by_many_columns('client_vendor_ledger', $condition);
        if (empty($transactionExit)) {
            $Delete['is_active'] = "N";
            $Delete['is_delete'] = "Y";
            $Delete['status'] = "2";
            $this->db->where('related_id', $id);
            $this->db->where('related_id_for', 3);
            $this->db->update('ac_account_ledger_coa', $Delete);
            $this->db->where('related_id', $id);
            $this->db->where('related_id_for', 4);
            $this->db->update('ac_account_ledger_coa', $Delete);
            $this->Common_model->delete_data('customer', 'customer_id', $id);

            $msg = 'Your data successfully deleted from database.';
            $this->session->set_flashdata('error', $msg);
            echo 1;
        } else {
            $msg = "This customer can't be deleted.already have a transaction  by this customer!";
            $this->session->set_flashdata('error', $msg);

            echo 1;
        }
    }

    function checkDuplicatePhone()
    {
        $phone = $this->input->post('phone');
        if (!empty($phone)):
            $condition = array(
                'dist_id' => $this->dist_id,
                'customerPhone' => $phone
            );
            $exits = $this->Common_model->get_single_data_by_many_columns('customer', $condition);
            if (!empty($exits)):
                echo 1;
            else:
                echo 2;
            endif;
        endif;
    }
    function checkDuplicateCustomer()
    {
        $customer_name = trim($this->input->post('customer_name'));
        $phone = $this->input->post('phone');
        if (!empty($customer_name)):
            $condition = array(
                //'dist_id' => $this->dist_id,
                'customerName' => $customer_name,
                //'customerPhone' => $phone
            );
            $exits = $this->Common_model->get_single_data_by_many_columns('customer', $condition);
            if (!empty($exits)):
                echo 1;
            else:
                echo 2;
            endif;
        endif;
    }


    function checkDuplicateCustomerForUpdate() {
        $customer_name = trim($this->input->post('customer_name'));
        $customer_id = $this->input->post('customer_id');
        $condition = array(
            //'dist_id' => $this->dist_id,
            'customerName' => $customer_name,
            'customer_id !=' => $customer_id,
        );
        $exits = $this->Common_model->get_single_data_by_many_columns('customer', $condition);
        if (!empty($exits)):
            echo 1;
        else:
            echo 2;
        endif;
    }

    function customerStatusChange()
    {
        $this->db->trans_start();
        $supid = $this->input->post('supID');
        $data['status'] = $this->input->post('status');
        $this->Common_model->update_data('customer', $data, 'customer_id', $supid);
        $this->db->trans_complete();


        if ($this->db->trans_status() === FALSE):
            $msg = 'Customer Status ' . ' ' . $this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
            return 0;
        else:
            $msg = 'Customer Status ' . ' ' . $this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
            return 1;
        endif;


    }


}