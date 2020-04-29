<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/19/2019
 * Time: 12:06 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class AccountController extends CI_Controller
{
    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Accounts_model');
        $this->load->model('Inventory_Model');
        $this->load->model('AccountsBalanceSheet_Model');
        $this->load->model('Sales_Model');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'Accounts';
        $this->link_icon_add = "<i class='ace-icon fa fa-plus'></i>";
        $this->link_icon_list = "<i class='ace-icon fa fa-list'></i>";
        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }
    /**
     *
     */
    public function chartOfAccount()
    {
        if (isPostBack()) {
            $rootAccount = $this->input->post('rootAccount');
            $parentAccount = $this->input->post('parentAccount');
            $childAccount = $this->input->post('childAccount');
            $accountHead = $this->input->post('accountHead');
            $accountCode = $this->input->post('accountCode');
            $this->db->trans_start();
            if (empty($accountCode)) :
                exception("Required Field Can't be Empty!!");
                redirect(site_url($this->project . '/chartOfAccount'), 'refresh');
            endif;
            unset($data);
            if (!empty($rootAccount) && empty($parentAccount)):
//Parent Account Inserted.
                $data['parent_id'] = $rootAccount;
                $data['code'] = $accountCode;
                $data['parent_name'] = $accountHead;
                $data['status'] = 1;
                $data['posted'] = isset($_POST['posted']) ? $_POST['posted'] : 0;
                $inserted_id = $this->Common_model->insert_data('ac_account_ledger_coa', $data);
            elseif (!empty($parentAccount)) :
                //Child Account Inserted.
                $data['parent_id'] = $parentAccount;
                $data['code'] = $accountCode;
                $data['parent_name'] = $accountHead;
                $data['status'] = 1;
                $data['posted'] = isset($_POST['posted']) ? $_POST['posted'] : 0;
                $inserted_id = $this->Common_model->insert_data('ac_account_ledger_coa', $data);
                $exits = $this->Finane_Model->accountBalanceDebitOrCredit($parentAccount);
            endif;
            //$PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_id);

            $condtion = array(
                'id' => $inserted_id,
            );
            $generalId = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
            $a = array();
            $parent_id = $generalId->parent_id;
            for ($x = 0; $x <= 7; $x++) {
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
            $data_level_no['level_no']=count($a)+1;
            $this->Common_model->update_data('ac_account_ledger_coa', $data_level_no, 'id', $inserted_id);




            $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($inserted_id);
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
            $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $inserted_id;
            $Condition = array(
                'TB_AccountsLedgerCOA_id' => $inserted_id
            );
            $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);




            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE):
                $msg = 'This parent head already created transaction.Can not create child head';
                $this->session->set_flashdata('error', $msg);
            else:
                $msg = 'Your chart of head successfully created';
                $this->session->set_flashdata('success', $msg);
            endif;
            redirect(site_url($this->project . '/chartOfAccount'));
        }
        $data['rootAccount'] = $this->Common_model->get_data_list_by_single_column('ac_account_ledger_coa', 'parent_id', '0');
        $this->db->select("*");
        $this->db->from("ac_account_ledger_coa");
        //$this->db->where('parent_id !=', 0);
        $this->db->where('posted !=', 1);
        $data['AccountList'] = $this->db->get()->result();
        /*echo'<pre>';
        print_r($data['AccountList']);exit;*/
        /*page navbar details*/
        $data['title'] = 'Chart of Account';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Chart of Account List';
        $data['link_page_url'] = $this->project . '/listChartOfAccount';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/setup/chartOfAccount', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function checkDuplicateHead()
    {
        if ($this->input->is_ajax_request()) {
            $headTitle = $this->input->post('headTitle');
            $EDITID = $this->input->post('EDITID');
            $headTitle = trim($headTitle);
            $headTitle = preg_replace('/\s+/', ' ', $headTitle);
            $rootAccount = $this->input->post('rootAccount');
            $parentAccount = $this->input->post('parentAccount');
            $childAccount = $this->input->post('childAccount');
            $acc_head = $this->Accounts_model->checkDuplicateHead($headTitle, $rootAccount, $parentAccount, $childAccount, $this->dist_id, $EDITID);
//echo $this->db->last_query();die;
            if (!empty($acc_head)) {
                echo "1";
            } else {
                echo "2";
            }
        }
    }
    public function getChartList()
    {
        if ($this->input->is_ajax_request()) {
            $headTitle = $this->input->post('headTitle');
            $rootAccount = $this->input->post('rootAccount');
            $parentAccount = $this->input->post('parentAccount');
            $childAccount = $this->input->post('childAccount');
            $chartList = $this->Accounts_model->get_chart_list($headTitle, $rootAccount, $parentAccount, $childAccount, $this->dist_id);
            $disabledHead = array(72, 58, 50, 54, 60, 49, 59, 62);
            $add = '';
            if (!empty($chartList)):
                $add .= "<option selected disabled value=''>Search Account Head</option>";
                foreach ($chartList as $key => $value):
                    $disabled = 0;
                    if (in_array($value->id, $disabledHead)) {
                        $disabled = 'disabled';
                    }
                    $add .= "<option $disabled  value='" . $value->id . "'>$value->parent_name</option>";
                endforeach;
                echo $add;
                DIE;
            else:
                echo "<option value=''>No Head Available</option>";
                DIE;
            endif;
        }
    }
    public function getParentCode()
    {
        if ($this->input->is_ajax_request()) {
            $rootID = $this->input->post('rootID');
            $parent = $this->input->post('parent');
            $child = $this->input->post('child');
            $condition = array(
                'parent_id' => $rootID,
                //'dist_id' => $this->dist_id,
            );
            $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition);
            $array = array();
            if (!empty($totalAccount)):
                $totalAccount = count($totalAccount);
                $newCode = $rootID . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
                echo $newCode;
            else:
                $totalAdded = 0;
                echo $newCode = $rootID . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
            endif;
        }
    }
    public function getTreeList()
    {
        $rootId = $this->input->post('rootId');
        $parentId = $this->input->post('parentId');
        $chaildId = $this->input->post('chaildId');
        $data['rootId'] = $rootId;
        $data['parentId'] = $parentId;
        $data['childId'] = $chaildId;
        if (!empty($rootId) && empty($parentId)) {
            //if root id not empty and other empty
            $data['rootList'] = $this->Accounts_model->getChartListTree($rootId, $parentId, $chaildId, $this->dist_id);
            echo $this->load->view('distributor/ajax/showTree', $data);
        } elseif (!empty($parentId)) {
            //if root id and parent id not empty and child id not empty
            $data['parentList'] = $this->Accounts_model->getChartListTree($rootId, $parentId, $chaildId, $this->dist_id);
            echo $this->load->view('distributor/ajax/showTree', $data);
        } //else {
        //if all id not empty
        //  $data['chartList'] = $this->Accounts_model->getChartListTree($rootId, $parentId, $chaildId, $this->dist_id);
        //  echo $this->load->view('distributor/ajax/showTree', $data);
        //}
    }
    public function getChildCode()
    {
        if ($this->input->is_ajax_request()) {
            $rootID = $this->input->post('rootID');
            $parent = $this->input->post('parentId');
            $child = $this->input->post('child');
            $condition = array(
                'parentId' => $parent,
                'dist_id' => $this->dist_id,
            );
            $this->db->select("*");
            $this->db->from("ac_account_ledger_coa");
            $this->db->where("parent_id", $parent);
            $this->db->where('common', 1);
            $totalAccount = $this->db->get()->result();
            $lastHeadCode = $this->Common_model->get_single_data_by_single_column('ac_account_ledger_coa', 'id', $parent);
            $array = array();
            if (!empty($totalAccount)):
                $totalAccount = count($totalAccount);
                $newCode = $lastHeadCode->code . ' - ' . str_pad($totalAccount + 1, 3, "0", STR_PAD_LEFT);
                echo $newCode;
            else:
                $totalAdded = 0;
                echo $newCode = $lastHeadCode->code . ' - ' . str_pad($totalAdded + 1, 3, "0", STR_PAD_LEFT);
            endif;
        }
    }
    public function getHeadCode()
    {
        if ($this->input->is_ajax_request()) {
            $child = $this->input->post('childID');
            $condition = array(
                'parent_id' => $child,
                //'dist_id' => $this->dist_id,
            );
            $this->db->select("*");
            $this->db->from("ac_account_ledger_coa");
            $this->db->where("parent_id", $child);
            //$this->db->group_start();
            //$this->db->where('dist_id', $this->dist_id);
            $this->db->where('common', 1);
            //$this->db->group_end();
            $totalAccount = $this->db->get()->result();
            // $totalAccount = $this->Common_model->get_data_list_by_many_columns('generaldata', $condition);
            $oldAccountCode = $this->Common_model->get_single_data_by_single_column('ac_account_ledger_coa', 'id', $child);
            $array = array();
            if (!empty($totalAccount)):
                $totalAccount = count($totalAccount);
                $newCode = $oldAccountCode->code . ' - ' . str_pad($totalAccount + 1, 4, "0", STR_PAD_LEFT);
                echo $newCode;
            else:
                $totalAdded = 0;
                echo $newCode = $oldAccountCode->code . ' - ' . str_pad($totalAdded + 1, 4, "0", STR_PAD_LEFT);
            endif;
        }
    }
    public function listChartOfAccount()
    {
        $data['title'] = 'Chart of Account';
        $condition = array(
            'status' => 1,
            //'parent_id !=' => 0,
        );
      // al_old  $data['chartList'] = $chartList = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition);


         $data['chartList'] = $this->db->select('t1.*, t2.CHILD_ID as if_insert_data')
            ->distinct()
            ->distinct('t2.CHILD_ID')
            ->from('ac_account_ledger_coa as t1')
            ->join('ac_tb_accounts_voucherdtl as t2', 't1.id = t2.CHILD_ID', 'left')
             ->where('t1.is_delete',"N")
            ->order_by("id", "asc")
            ->get()->result();




        /*page navbar details*/
        $data['title'] = 'Chart of Account';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Add Chart Of Account';
        $data['link_page_url'] = $this->project . '/chartOfAccount';
        $data['link_icon'] = "<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/account/setup/listChartOfAccount', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function editChartOfAccount($id)
    {
        if (isPostBack()) {
            $this->db->trans_start();
            $condtion = array(
                'id' => $id,
            );
            $generalId = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
            //$data1['title'] = $this->input->post('accountHead');
            //$this->Common_model->update_data('generaldata', $data1, 'generalId', $generalId->generalId);
            $a = array();
            $parent_id = $generalId->parent_id;
            for ($x = 0; $x <= 7; $x++) {
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
            $data_level_no['level_no']=count($a)+1;
            $this->Common_model->update_data('ac_account_ledger_coa', $data_level_no, 'id', $id);
            $PARENT_ID_DATA = $this->Common_model->getAccountHeadNew2($id);
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
            $dataac_tb_coa['TB_AccountsLedgerCOA_id'] = $id;
            $Condition = array(
                'TB_AccountsLedgerCOA_id' => $id
            );
            $this->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);
            $data['id'] = $id;
            $data['parent_name'] = $this->input->post('accountHead');
            $this->Common_model->update_data('ac_account_ledger_coa', $data, 'id', $id);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE):
                $msg = 'Your data can not be updated.Somthing is wrong';
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project . '/editChartOfAccount/' . $id));
            else:
                $msg = 'Your chart of account successfully updated into database ';
                $this->session->set_flashdata('success', $msg);
                redirect(site_url($this->project . '/editChartOfAccount/' . $id));
            endif;
        }
        $data['title'] = 'Edit Chart of Account';
        $condition = array(
            'dist_id' => $this->dist_id,
            'rootId !=' => 0,
        );
        $data['editChartAccount'] = $rootAccount = $this->Common_model->get_single_data_by_single_column('ac_account_ledger_coa', 'id', $id);
        /*echo "<pre>";
        print_r($data['editChartAccount']);
        exit;*/
        $data['mainContent'] = $this->load->view('distributor/account/setup/editChartAccount', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function viewChartOfAccount()
    {
        $data['designCssHide'] = $this->uri->segment(1);
        $condition = array(
            'status' => 1,
        );
        /*page navbar details*/
        $data['title'] = 'View Chart of Account';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'View Chart of Account List';
        $data['link_page_url'] = $this->project . '/listChartOfAccount';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/
        //$data['chartList'] = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition);
        $chartList = $this->Common_model->get_data_list_by_many_columns_array('ac_account_ledger_coa', $condition);
        $chartListTree = $this->rows_to_tree($chartList, 'id', 'parent_id');
        $final_array = array();
        foreach ($chartListTree as $id => $row) {
            // $final_array[$id]=$row['node'];
            $row['node']['label'] = 0;
            array_push($final_array, $row['node']);
            if (!empty($row['children'])) {
                foreach ($row['children'] as $idC => $rowC) {
                    $rowC['node']['label'] = 1;
                    array_push($final_array, $rowC['node']);
                    if (!empty($rowC['children'])) {
                        foreach ($rowC['children'] as $idD => $rowD) {
                            $rowD['node']['label'] = 2;
                            array_push($final_array, $rowD['node']);
                            if (!empty($rowD['children'])) {
                                foreach ($rowD['children'] as $idE => $rowE) {
                                    $rowE['node']['label'] = 3;
                                    array_push($final_array, $rowE['node']);
                                    if (!empty($rowE['children'])) {
                                        foreach ($rowE['children'] as $idF => $rowF) {
                                            $rowF['node']['label'] = 4;
                                            array_push($final_array, $rowF['node']);
                                            if (!empty($rowF['children'])) {
                                                foreach ($rowF['children'] as $idG => $rowG) {
                                                    $rowG['node']['label'] = 5;
                                                    array_push($final_array, $rowG['node']);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['chartList'] = $final_array;
        //echo '<pre>';
//print_r($final_array);
//print_r($chartList);
        //print_r($data['chartList']);
        //exit;
        $data['mainContent'] = $this->load->view('distributor/account/setup/ViewChartOfAccount', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function rows_to_tree($raw, $id_key = 'id', $parent_key = 'parent_id')
    {
        // First, transform $raw to $rows so that array key == id
        $rows = array();
        foreach ($raw as $row) {
            $rows[$row[$id_key]] = $row;
        }
        $tree = array();
        $tree_index = array(); // Storing the reference to each node
        while (count($rows)) {
            foreach ($rows as $id => $row) {
                if ($row[$parent_key]) { // If it has parent
                    // Abnormal case: has parent id but no such id exists
                    if (!array_key_exists($row[$parent_key], $rows) AND !array_key_exists($row[$parent_key], $tree_index)) {
                        unset($rows[$id]);
                    } // If the parent id exists in $tree_index, insert itself
                    else if (array_key_exists($row[$parent_key], $tree_index)) {
                        $parent = &$tree_index[$row[$parent_key]];
                        $parent['children'][$id] = array('node' => $row, 'children' => array());
                        $tree_index[$id] = &$parent['children'][$id];
                        unset($rows[$id]);
                    }
                } else { // Top parent
                    $tree[$id] = array('node' => $row, 'children' => array());
                    $tree_index[$id] = &$tree[$id];
                    unset($rows[$id]);
                }
            }
        }
        return $tree;
    }
    public function account_ledger_list()
    {
        $groupId = $this->input->post('groupId');
        $ledger_id = $this->input->post('ledger_id');
        if ($groupId != 'all') {
            $conditionArray = array(
                'parent_id' => $groupId,
                'status' => 1,
            );
        } else {
            $conditionArray = array(
                'status' => 1,
            );
        }

        $districtList = $this->Common_model->get_ledger_by_group($groupId, $childId='');
        $selecAll="";
       /* if ( $ledger_id == 'all'):
            $selecAll = 'selected';
        endif;*/

        $append = '';
        /*$append .= '<option disabled selected>Select Ledger</option>';*/
        $append .= '<option value="all"'.$selecAll.'>All</option>';
        if (!empty($districtList)):
            foreach ($districtList as $eachInfo):
                $selec = '';
                if (!empty($ledger_id) && $ledger_id == $eachInfo->CHILD_ID):
                    $selec = 'selected';
                endif;
                $append .= '<option ' . $selec . ' value="' . $eachInfo->CHILD_ID . '">' . $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $eachInfo->CHILD_ID)->parent_name . '</option>';
            endforeach;
        else:
            $append .= '<option>Empty!</option>';
        endif;
        echo $append;
    }
    public function balanceSheet()
    {
        $condition = array(
            'status' => 1,
        );
        $this->load->library('Site_library', '', 'site_library');
        if ($this->input->post('to_date')) {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        } else {
            $to_date = date('Y-m-d');
        }
        $chartList = $this->Accounts_model->balanceshit(1, $to_date);
       /* echo "<pre>";
        echo $this->db->last_query();
        print_r($chartList);
        exit;*/
        $data['assetList'] = $chartList;

        //$data['liabilityList'] = $this->Common_model->getAccountListByRoodId(2);
        $data['liabilityList'] = $this->Accounts_model->balanceshit(2, $to_date);

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Balance Sheet';
        $data['expense'] = $this->Common_model->getAccountListByRoodId(4);
        $data['title'] = 'Balance Sheet';
        $data['income'] = $this->Common_model->getAccountListByRoodId(3);
        $data['mainContent'] = $this->load->view('distributor/account/report/balanceSheetNew', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function balanceSheetWithBranch()
    {
        if (isPostBack()) {

            $this->load->library('Site_library', '', 'site_library');
            if ($this->input->post('to_date')) {
                $branch_id=isset($_POST['branch_id'])?$this->input->post('branch_id'):'all';
                $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
            } else {
                $to_date = date('Y-m-d');
            }
            $data['balance_sheet'] = $this->Accounts_model->balance_sheet_query_with_branch($to_date,$branch_id);

        }



        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Balance Sheet';
        //$data['expense'] = $this->Common_model->getAccountListByRoodId(4);
        $data['title'] = 'Balance Sheet';
        //$data['income'] = $this->Common_model->getAccountListByRoodId(3);
        $data['mainContent'] = $this->load->view('distributor/account/report/balanceSheetWithBranch', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    public function balanceSheetWithAllBranch()
    {
        if (isPostBack()) {

            $this->load->library('Site_library', '', 'site_library');
            if ($this->input->post('to_date')) {
                $branch_id=isset($_POST['branch_id'])?$this->input->post('branch_id'):'all';
                $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
            } else {
                $to_date = date('Y-m-d');
            }
            if($branch_id=='all'){
                $data['balance_sheet_with_all_branch'] = $this->AccountsBalanceSheet_Model->balance_sheet_query_with_all_branch($to_date,$branch_id);

            }else{
                $data['balance_sheet'] = $this->AccountsBalanceSheet_Model->balance_sheet_query_with_branch($to_date,$branch_id);
            }


        }

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Balance Sheet';
        $data['title'] = 'Balance Sheet';
        $data['mainContent'] = $this->load->view('distributor/account/report/balanceSheetWithAllBranch', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function incomeStetement()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Income Statement';



      /*  if (isPostBack()) {
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $chartList = $this->Accounts_model->balanceshit(3, $end_date);
            echo $this->db->last_query();exit;



        }*/


        //$data['expense'] = $this->Common_model->getAccountListByRoodId(4);
        //$data['income'] = $this->Common_model->getAccountListByRoodId(3);
        /*page navbar details*/







        $data['title'] = 'Income Statement';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Income Statement List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/






        $data['mainContent'] = $this->load->view('distributor/account/report/incomeStatement', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }


    function incomeStatementWithBranch()
    {
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['pageTitle'] = 'Income Statement';

        $data['title'] = 'Income Statement';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = 'Income Statement List';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/



        $data['mainContent'] = $this->load->view('distributor/account/report/incomeStatementWithBranch', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    function incomeStatementWithAllBranch()
    {
        $this->load->model('AccountsIncomeStetement_Model');

        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', '1', 1);
        $data['pageTitle'] = 'Income Statement';

        $data['title'] = 'Income Statement';
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '#';
        $data['link_icon'] = "<i class='fa fa-list'></i>";
        /*page navbar details*/



        $data['mainContent'] = $this->load->view('distributor/account/report/incomeStatementWithAllBranch', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }

}