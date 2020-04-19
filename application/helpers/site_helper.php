<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/25/2019
 * Time: 1:01 PM
 */
if (!function_exists('banglaNumber')) {
    function banglaNumber($int)
    {
        $engNumber = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');
        $converted = str_replace($engNumber, $bangNumber, $int);
        return $converted;
    }
}
if (!function_exists('numberFromatfloat')) {
    function numberFromatfloat($int, $decimals = '2', $dec_point = '.', $tousand_sep = ',')
    {
        return number_format((float)abs($int), $decimals, $dec_point, $tousand_sep);
        /*$engNumber = array(1,2,3,4,5,6,7,8,9,0);
        $bangNumber = array('১','২','৩','৪','৫','৬','৭','৮','৯','০');

        $converted = str_replace($engNumber, $bangNumber, $int);
         $converted;*/
    }
}
if (!function_exists('create_ledger_cus_sup_product')) {
    function create_ledger_cus_sup_product($related_id, $parent_name, $ledger_parent_id, $for, $admin_id)
    {

        $CI =& get_instance();
        $CI->load->database();

        $dataCoa=array();
        $a=array();
        $b=array();
        $dataac_tb_coa=array();

        $condtion = array(
            'id' => $ledger_parent_id,//33,
        );
        $ac_account_ledger_coa_info = $CI->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);

        $condition2 = array(
            'parent_id' => $ledger_parent_id,//33,
        );
        $totalAccount = $CI->Common_model->get_data_list_by_many_columns('ac_account_ledger_coa', $condition2);
        if (!empty($totalAccount)):
            $totalAccount = count($totalAccount);
            $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAccount + 1, 2, "0", STR_PAD_LEFT);
        else:
            $totalAdded = 0;
            $newCode = $ac_account_ledger_coa_info->code . ' - ' . str_pad($totalAdded + 1, 2, "0", STR_PAD_LEFT);
        endif;
        $level_no = $ac_account_ledger_coa_info->level_no;
        $parent_id = $ac_account_ledger_coa_info->id;
        $dataCoa['parent_id'] = $ledger_parent_id;
        $dataCoa['code'] = $newCode;
        $dataCoa['parent_name'] = $parent_name;
        $dataCoa['status'] = 1;
        $dataCoa['posted'] = 1;
        $dataCoa['level_no'] = $level_no + 1;
        $dataCoa['related_id'] = $related_id;
        $dataCoa['related_id_for'] = $for;
        $dataCoa['insert_by'] = $admin_id;
        $dataCoa['insert_date'] = date('Y-m-d H:i:s');
        log_message("error","this is from site helper".print_r($dataCoa,true));

        $inserted_ledger_id = $CI->Common_model->insert_data('ac_account_ledger_coa', $dataCoa);
        for ($x = 0; $x <= 7; $x++) {
            if ($parent_id != 0) {
                $condtion = array(
                    'id' => $parent_id,
                );
                $parentDetails = $CI->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
                $parent_id = $parentDetails->parent_id;
                $b['id'] = $parentDetails->id;
                $b['parent_name'] = $parentDetails->parent_name;
                $a[] = $b;
            }
        }
        $PARENT_ID_DATA = $CI->Common_model->getAccountHeadNew2($inserted_ledger_id);
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
        $CI->Common_model->save_and_check('ac_tb_coa', $dataac_tb_coa, $Condition);
    }
}
if (!function_exists('update_ledger_cus_sup_product')) {
    function update_ledger_cus_sup_product($related_id, $parent_name, $ledger_parent_id, $for, $admin_id)
    {
        $CI =& get_instance();
        $CI->load->database();
        $condtion = array(
            'related_id' => $related_id,
            'related_id_for' => $for,
        );
        $ac_account_ledger_coa_info = $CI->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condtion);
        $dataCOA['parent_name'] = $parent_name;
        $CI->Common_model->update_data('ac_account_ledger_coa', $dataCOA, 'id', $ac_account_ledger_coa_info->id);
    }
}
if (!function_exists('get_product_last_purchase_price')) {
    function get_product_last_purchase_price($productId, $toDate = null, $fromDate = null)
    {
        //log_message('error', 'product id from get_last_purchase_price ' . print_r($productId, true));
        $last_purchase_price = 0;
        $CI =& get_instance();
        $CI->load->database();
        $last_purchase = $CI->db->where('product_id', $productId)
            ->order_by('purchase_details_id', "desc")
            ->limit(1)
            ->get('purchase_details')
            ->row();
        if (empty($last_purchase)) {
            $product_purchase_price = $CI->db->where('product_id', $productId)
                ->limit(1)
                ->get('product')
                ->row();
            $last_purchase_price = $product_purchase_price->purchases_price;
        } else {
            $last_purchase_price = $last_purchase->unit_price;
        }
        return $last_purchase_price;
    }
}
if (!function_exists('get_product_purchase_price')) {
    function get_product_purchase_price($productId, $toDate = null, $fromDate = null)
    {
        $CI =& get_instance();
        $CI->load->database();
        if($productId==""){
            $last_purchase_price=0;
        }else{
            $product_purchase_price = $CI->db->where('product_id', $productId)
                ->limit(1)
                ->get('product')
                ->row();
            $last_purchase_price = $product_purchase_price->purchases_price;
        }

        return $last_purchase_price;
    }
}
if (!function_exists('get_product_last_sales_price')) {
    function get_product_last_sales_price($productId, $toDate = null, $fromDate = null)
    {
        $CI =& get_instance();
        $CI->load->database();
        $last_sales_price = 0;
        $last_sales = $CI->db->where('product_id', $productId)
            ->order_by('sales_details_id', "desc")
            ->limit(1)
            ->get('sales_details')
            ->row();
        if (empty($last_sales)) {
            $product_sales_price = $CI->db->where('product_id', $productId)
                ->limit(1)
                ->get('product')
                ->row();
            $last_sales_price = $product_sales_price->salesPrice;
        } else {
            $last_sales_price = $last_sales->unit_price;
        }
        return $last_sales_price;
    }
}
if (!function_exists('get_customer_supplier_product_ledger_id')) {
    function get_customer_supplier_product_ledger_id($related_id, $for)
    {
        $CI =& get_instance();
        $CI->load->database();
        $condition = array(
            'related_id' => $related_id,
            'related_id_for' => $for,
            'is_active' => "Y",
        );
        $ac_account_ledger_coa_info = $CI->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);
        return $ac_account_ledger_coa_info;
    }
}
if (!function_exists('bank_account_info_dropdown')) {
    function bank_account_info_dropdown($selected_bank_account = null, $option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $bankAccountCondition = array(
            'is_active' => 'Y',
            'is_delete' => 'N'
        );
        $all_bank_account = $CI->Common_model->get_data_list_by_many_columns('bank_account_info', $bankAccountCondition);
        $option = '';
        $option .= '<option value="0" disabled selected>Select Bank Account</option>';
        foreach ($all_bank_account as $bankAccount) {
            if ($selected_bank_account == $bankAccount->bank_account_info_id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $option .= "<option value='$bankAccount->bank_account_info_id' $selected>" . $bankAccount->bank_name . " # A/C :  " . $bankAccount->account_no . "</option>";
        }
        return $option;
    }
}

if (!function_exists('get_ledger_colsing_balance')) {
    function get_ledger_colsing_balance($ledger_type =array('Suppiler','Customer','Other'),$ledgerId)
    {
        $CI =& get_instance();
        $CI->load->database();
        if($ledger_type=='Suppiler' || $ledger_type=='Customer'){
            if($ledger_type=='Suppiler'){
                $for=2;
            }else{
                $for=3;
            }
            $condition = array(
                'related_id' => $ledgerId,
                'related_id_for' => $for,
                'is_active' => "Y",
            );
            $ac_account_ledger_coa_info = $CI->Common_model->get_single_data_by_many_columns('ac_account_ledger_coa', $condition);

        }

        return $ledger_type;
    }
}



if (!function_exists('get_menu_list_by_user_role')) {
    function get_menu_list_by_user_role($parent_id, $admin_id, $limit, $statr = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('admin_role.admin_role_id,admin_role.admin_id,admin_role.navigation_id,admin_role.parent_id,navigation.label,navigation.parent_id,navigation.url,navigation.icon');
        $CI->db->from('admin_role');
        $CI->db->join('navigation', 'navigation.navigation_id=admin_role.navigation_id', 'left');
        $CI->db->where('admin_role.parent_id', $parent_id);
        $CI->db->where('admin_role.user_role', $admin_id);
        $CI->db->where('navigation.active', 1);
        $CI->db->order_by('orderBy');
        $CI->db->limit($limit, $statr);
        $sql = $CI->db->get();
        return $sql->result();

        return $ledger_type;
    }
}

if (!function_exists('user_role_dropdown')) {
    function user_role_dropdown($selected_user_role = null, $option = '')
    {
        $CI =& get_instance();
        $CI->load->database();
        $user_roleCondition = array(
            //'is_active' => 'Y',
            //'is_delete' => 'N'
        );
        $all_user_role = $CI->Common_model->get_data_list_by_many_columns('user_role', $user_roleCondition);
        $option = '';
        $option .= '<option value="0" disabled selected>Select Role</option>';
        foreach ($all_user_role as $user_role) {
            if ($selected_user_role == $user_role->id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $option .= "<option value='$user_role->id' $selected>" . $user_role->role_name . "</option>";
        }
        return $option;
    }
}

if (!function_exists('check_parmission_by_user_role')) {
    function check_parmission_by_user_role($navigationOrActionId )
    {
        $CI =& get_instance();
        $CI->load->database();
        $admin_id=$CI->session->userdata('admin_id');

        $user_role=$CI->Common_model->get_single_data_by_single_column('admin', 'admin_id', $admin_id)->user_role;
        $condition = array(
            'user_role' => $user_role,
            'navigation_id' => $navigationOrActionId,//'2103',
        );
        $Permition = $CI->Common_model->get_single_data_by_many_columns('admin_role', $condition);


        if (!empty($Permition)) {
            return $navigationOrActionId;
        }else{
            return 0;
        }

    }
}
