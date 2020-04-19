<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/6/2019
 * Time: 12:37 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class SupplierController extends CI_Controller
{

    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $page_type;
    public $folder;
    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }
    function supplierList() {
         /*page navbar details*/
        $data['title'] = get_phrase('Supplier List');
        $data['page_type']=get_phrase($this->page_type);
        $data['link_page_name']=get_phrase('Supplier Add');
        $data['link_page_url']=$this->project.'/Supplier';
        $data['link_icon']="<i class='fa fa-plus'></i>";
         /*page navbar details*/
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierList', $data, true);
        $this->load->view($this->folder, $data);
    }

    function Supplier() {
        $supID = $this->Common_model->getSupplierID($this->dist_id);
        $data['supplierID'] = $this->Common_model->checkDuplicateSupID($supID, $this->dist_id);

        if (isPostBack()) {

            $this->form_validation->set_rules('supName', 'Supplier Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', "Product Package Can't Save.");
                redirect(site_url($this->project.'/Supplier'));
            } else {
                $this->db->trans_start();
                $dataInsert['supID'] = $data['supplierID'];
                //$data['supID'] = $this->input->post('supplierId');
                $dataInsert['supName'] = $this->input->post('supName');
                $dataInsert['supEmail'] = $this->input->post('supEmail');
                $dataInsert['supPhone'] = $this->input->post('supPhone');
                $dataInsert['supAddress'] = $this->input->post('supAddress');
                $dataInsert['dist_id'] = $this->dist_id;
                $dataInsert['status'] = 1;
                $dataInsert['updated_by'] = $this->admin_id;


                $insertID = $this->Common_model->insert_data('supplier', $dataInsert);

                $condtion = array(
                    'id' => $this->config->item("Supplier_Payables"),//53,
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $condition2 = array(
                    'parent_id' => $this->config->item("Supplier_Payables"),//53,
                );
                $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);

                if (!empty($totalAccount)):
                    $totalAccount = count($totalAccount);
                $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
                else:
                    $totalAdded = 0;
                     $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
                endif;


                $level_no =$ac_account_ledger_coa_info->level_no;
                $parent_id =$ac_account_ledger_coa_info->id;
                $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
                $dataCoa['code'] = $newCode;
                $dataCoa['parent_name'] = $this->input->post('supName').' [ '.$data['supplierID'].' ]';
                $dataCoa['status'] = 1;
                $dataCoa['posted'] =1;
                $dataCoa['level_no'] =$level_no+1;
                $dataCoa['related_id'] =$insertID;
                $dataCoa['related_id_for'] =2;
                $dataCoa['insert_by'] =$this->admin_id;
                $dataCoa['insert_date'] =date('Y-m-d H:i:s');
                $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
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


/*echo "<pre>";
print_r($ac_account_ledger_coa_info);
print_r($newCode);
exit;*/







                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $this->session->set_flashdata('error', "Product Package Can't Save.");

                    redirect(site_url($this->project.'/Supplier/' ));
                else:
                    $this->session->set_flashdata('success', "Product Package Save successfully.");
                    //message("Product Package Save successfully.");
                    redirect(site_url($this->project.'/supplierUpdate/' . $insertID));
                endif;
            }
        }

        /*page navbar details*/
        $data['title'] = get_phrase('Supplier_Add');
        $data['page_type']=get_phrase($this->page_type);
        $data['link_page_name']=get_phrase('Supplier List');
        $data['link_page_url']=$this->project.'/supplierList';
        $data['link_icon']="<i class='fa fa-list'></i>";
        /*page navbar details*/

        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierAdd', $data, true);
        $this->load->view($this->folder, $data);
    }


    function saveNewSupplier() {
        $data['supID'] = $this->input->post('supplierId');
        $data['supName'] = $this->input->post('supName');
        $data['supEmail'] = $this->input->post('supEmail');
        $data['supPhone'] = $this->input->post('supPhone');
        $data['supAddress'] = $this->input->post('supAddress');
        $data['status'] = 1;
        $data['dist_id'] = $this->dist_id;
        $data['updated_by'] = $this->admin_id;
        $insertID = $this->Common_model->insert_data('supplier', $data);

        $condtion = array(
            'id' => $this->config->item("Supplier_Payables"),//53,
        );
        $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
        $condition2 = array(
            'parent_id' => $this->config->item("Supplier_Payables"),
        );
        $totalAccount = $this->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);

        if (!empty($totalAccount)):
            $totalAccount = count($totalAccount);
            $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
        else:
            $totalAdded = 0;
            $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
        endif;


        $level_no =$ac_account_ledger_coa_info->level_no;
        $parent_id =$ac_account_ledger_coa_info->id;
        $dataCoa['parent_id'] = $ac_account_ledger_coa_info->id;
        $dataCoa['code'] = $newCode;
        $dataCoa['parent_name'] = $this->input->post('supName').' [ '.$data['supplierID'].' ]';
        $dataCoa['status'] = 1;
        $dataCoa['posted'] =1;
        $dataCoa['level_no'] =$level_no+1;
        $dataCoa['related_id'] =$insertID;
        $dataCoa['related_id_for'] =2;
        $dataCoa['insert_by'] =$this->admin_id;
        $dataCoa['insert_date'] =date('Y-m-d H:i:s');
        $inserted_ledger_id = $this->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
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



        if (!empty($insertID)):
            echo '<option value="' . $insertID . '" selected="selected">' . $data['supID'] . ' [ ' . $data['supName'] . ' ] ' . '</option>';
        endif;
    }

    function supplierUpdate($updateid = null) {
        if (isPostBack()) {

            $this->form_validation->set_rules('supName', 'Supplier Name', 'required');

            if ($this->form_validation->run() == FALSE) {
                $m=validation_errors();

                $msg= $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/supplierUpdate/' . $updateid));
            } else {
                $this->db->trans_start();
                $data['supID'] = $this->input->post('supplierId');
                $data['supName'] = $this->input->post('supName');
                $data['supEmail'] = $this->input->post('supEmail');
                $data['supPhone'] = $this->input->post('supPhone');
                $data['supAddress'] = $this->input->post('supAddress');

                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $this->Common_model->update_data('supplier', $data, 'sup_id', $updateid);


                $condtion = array(
                    'related_id' => $updateid,
                    'related_id_for' => 2,
                );
                $ac_account_ledger_coa_info = $this->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $dataCOA['parent_name'] = $this->input->post('supName').' [ '.$this->input->post('supplierId').' ]';
                $this->Common_model->update_data('ac_account_ledger_coa', $dataCOA, 'id', $ac_account_ledger_coa_info->id);


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE):
                    $msg= 'Supplier '.' '.$this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project.'/Supplier/' ));
                else:
                    $msg= 'Supplier '.' '.$this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project.'/supplierUpdate/' . $updateid));
                endif;
            }
        }



        /*page navbar details*/
        $data['title'] = get_phrase('Supplier Edit');
        $data['page_type']=get_phrase($this->page_type);
        $data['link_page_name']=get_phrase('Supplier List');
        $data['link_page_url']=$this->project.'/supplierList';
        $data['link_icon']="<i class='fa fa-list'></i>";
        $data['second_link_page_name']=get_phrase('Supplier Add');
        $data['second_link_page_url']=$this->project.'/Supplier';
        $data['second_link_icon']=$this->link_icon_add;
        /*page navbar details*/
        $data['supplierEdit'] = $this->Common_model->get_single_data_by_single_column('supplier', 'sup_id', $updateid);
        $data['mainContent'] = $this->load->view('distributor/inventory/supplier/supplierEdit', $data, true);
        $this->load->view($this->folder, $data);
    }

    function checkDuplicateEmail() {
        $phone = $this->input->post('phone');
        if (!empty($phone)):
            $array = array(
                'supPhone' => $phone,
                'dist_id' => $this->dist_id,
            );
            $exitsSup = $this->Common_model->get_single_data_by_many_columns('supplier', $array);
            if (!empty($exitsSup)) {
                echo "1";
            } else {
                echo 2;
            }
        else:
            echo 2;
        endif;
    }
    function suplierStatusChange() {
        $this->db->trans_start();
        $supid = $this->input->post('supID');
        $data['status'] = $this->input->post('status');
        $this->Common_model->update_data('supplier', $data, 'sup_id', $supid);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            $msg= 'Supplier Status'.' '.$this->config->item("update_error_message");
            $this->session->set_flashdata('error', $msg);
        else:
            $msg= 'Supplier Status'.' '.$this->config->item("update_success_message");
            $this->session->set_flashdata('success', $msg);
        endif;
        return 1;
    }
}
?>
