<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 9/19/2019
 * Time: 12:39 PM
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_chart_list($head, $root, $parent, $child, $dis_id)
    {
        if (!empty($root) && empty($parent) && empty($child)):
            $this->db->select("*");
            $this->db->from("ac_account_ledger_coa");
            $this->db->where('parent_id', $root);
            //$this->db->group_start();
            ///$this->db->where('dist_id', $dis_id);
            $this->db->where('common', 1);
            //$this->db->group_end();
            $result = $this->db->get()->result();
            return $result;
        elseif (!empty($root) && !empty($parent) && empty($child)):
            $this->db->select("*");
            $this->db->from("ac_account_ledger_coa");
            $this->db->where('parent_id', $parent);
            //$this->db->group_start();
            //$this->db->where('dist_id', $dis_id);
            $this->db->where('common', 1);
            //$this->db->group_end();
            $result = $this->db->get()->result();
            return $result;
        else:
            $this->db->select("*");
            $this->db->from("ac_account_ledger_coa");
            $this->db->where('parent_id', $child);
            //$this->db->group_start();
            //$this->db->where('dist_id', $dis_id);
            $this->db->where('common', 1);
            //$this->db->group_end();
            $result = $this->db->get()->result();
            return $result;
        endif;
    }

    function getChartListTree($rootId, $parentId, $chaildId, $distId)
    {
        if (!empty($rootId) && empty($parentId)) {
            $this->db->select("*");
            $this->db->from("ac_account_ledger_coa");
            $this->db->where('parent_id', $rootId);
            $this->db->where('common', 1);
            return $this->db->get()->result();
        } elseif (!empty($parentId)) {
            $this->db->select("*");
            $this->db->from("ac_account_ledger_coa");
            $this->db->where('parent_id', $parentId);
            $this->db->where('common', 1);
            return $this->db->get()->result();
        }
    }

    function checkDuplicateHead($headTitle, $rootid = NULL, $parentid = NULL, $childid = NULL, $dist_id, $EDITID = NULL)
    {
        $exitsData = 0;
        /*if (!empty($rootid) && empty($parentid) && empty($childid)):
            $this->db->select("*");
            $this->db->from('ac_account_ledger_coa');
            $this->db->where('parent_id', $rootid);
            $this->db->where('parent_name', $headTitle);
            //$this->db->group_start();
            //$this->db->where('dist_id', $dist_id);
            $this->db->where('common', 1);
            //$this->db->group_end();
            $exitsData = $this->db->get()->row();
            return $exitsData;
        elseif (!empty($rootid) && !empty($parentid) && empty($childid)):
            $this->db->select("*");
            $this->db->from('ac_account_ledger_coa');
            $this->db->where('parent_id', $parentid);
            $this->db->where('parent_name', $headTitle);
            //$this->db->group_start();
            //$this->db->where('dist_id', $dist_id);
            $this->db->where('common', 1);
            //$this->db->group_end();
            $exitsData = $this->db->get()->row();
            return $exitsData;
        else:*/
        $this->db->select("*");
        $this->db->from('ac_account_ledger_coa');
        //$this->db->where('parent_id', $childid);
        $this->db->where('parent_name', $headTitle);
        if ($EDITID != '') {
            $this->db->where('id !=', $EDITID);
        }
        $this->db->where('common', 1);
        $exitsData = $this->db->get()->row();
        return $exitsData;
        //endif;
    }

    function getChartListbyId($parentID)
    {
        $this->db->select("*");
        $this->db->from("ac_account_ledger_coa");
        if (!empty($parentID)) {
            $this->db->where('parent_id', $parentID);
        }
        //$this->db->group_start();
        //$this->db->where('dist_id', $this->dist_id);
        //$this->db->or_where('common', 1);
        //$this->db->group_end();
        return $this->db->get()->result_array();
    }

    public function getMaxidByCoaID($id = null)
    {
        $dist_id = $this->session->userdata('dist_id');
        $this->db->select('*')
            ->from('ac_account_ledger_coa')
            ->where('parent_id', $id)
            ->order_by('id', 'DESC');
        //->where('dist_id', $dist_id);
        $chartmaxid = $this->db->get()->row();
        if (!empty($chartmaxid)):
            return $chartmaxid->acc_code;
        else:
            return 0;
        endif;
    }

    public function balanceshit($parentId, $date)
    {
        $query = "SELECT 
tab.PARENT_ID,
tab.PN,
tab.PARENT_ID1,
tab.PN1,
tab.PN1_Code,
tab.PARENT_ID2,
tab.PN2,
tab.PN2_Code,
SUM(tab.GR_DEBIT) as dr_amount,
SUM(tab.GR_CREDIT) as cr_amount,
tab.opening_debit,
tab.opening_credit
FROM (SELECT
	SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))GR_DEBIT,
	SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))GR_CREDIT,
	AC_TCOA.PARENT_ID,
	IFNULL(AC_TALCOA.parent_name,'')PN,
	IFNULL(AC_TALCOA. CODE,'')PN_Code,
	AC_TCOA.PARENT_ID1,
	IFNULL(AC_TALCOA1.parent_name,'')PN1,
	IFNULL(AC_TALCOA1. CODE,'')PN1_Code,
	
	CASE WHEN AC_TCOA.PARENT_ID2=0 
	 THEN 	AC_TCOA.CHILD_ID  
	 ELSE AC_TCOA.PARENT_ID2
		END PARENT_ID2,
CASE WHEN AC_TCOA.PARENT_ID2=0 
 THEN 	AC_TALCOA8.parent_name  
 ELSE AC_TALCOA2.parent_name
  END PN2,
CASE WHEN AC_TCOA.PARENT_ID2=0 
 THEN 	AC_TALCOA8. CODE  
 ELSE AC_TALCOA2. CODE
  END PN2_Code,
	
	
	AC_TCOA.PARENT_ID3,
	IFNULL(AC_TALCOA3.parent_name,'')PN3,
	IFNULL(AC_TALCOA3. CODE,'')PN3_Code,
	AC_TCOA.PARENT_ID4,
	IFNULL(AC_TALCOA4.parent_name,'')PN4,
	IFNULL(AC_TALCOA4. CODE,'')PN4_Code,
	AC_TCOA.PARENT_ID5,
	IFNULL(AC_TALCOA5.parent_name,'')PN5,
	IFNULL(AC_TALCOA5. CODE,'')PN5_Code,
	AC_TCOA.PARENT_ID6,
	IFNULL(AC_TALCOA6.parent_name,'')PN6,
	IFNULL(AC_TALCOA6. CODE,'')PN6_Code,
	AC_TCOA.PARENT_ID7,
	IFNULL(AC_TALCOA7.parent_name,'')PN7,
	IFNULL(AC_TALCOA7. CODE,'')PN7_Code,
	AC_TCOA.CHILD_ID,
	IFNULL(AC_TALCOA8.parent_name,'')CN,
	IFNULL(AC_TALCOA8. CODE,'')CN_Code,
	IFNULL(opb.debit,0)opening_debit,
	IFNULL(opb.credit,0)opening_credit
FROM
	ac_tb_accounts_voucherdtl AC_TAVDtl
LEFT OUTER JOIN ac_accounts_vouchermst AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID = AC_TAVMst.Accounts_VoucherMst_AutoID
LEFT OUTER JOIN ac_tb_coa AC_TCOA ON AC_TAVDtl.CHILD_ID = AC_TCOA.CHILD_ID
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TCOA.PARENT_ID = AC_TALCOA.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id
LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID = AC_TALCOA8.id
LEFT OUTER JOIN opening_balance opb ON opb.account=AC_TCOA.CHILD_ID
WHERE AC_TCOA.PARENT_ID=" . $parentId . " AND AC_TAVDtl.date <='" . $date . "' and AC_TAVDtl.IsActive=1
GROUP BY
	AC_TCOA.PARENT_ID,
	IFNULL(AC_TALCOA.parent_name,''),
	IFNULL(	AC_TALCOA. CODE,''),
	AC_TCOA.PARENT_ID1,
	IFNULL(AC_TALCOA1.parent_name,''),
	IFNULL(AC_TALCOA1. CODE,''),
	AC_TCOA.PARENT_ID2,
	IFNULL(AC_TALCOA2.parent_name,''),
	IFNULL(AC_TALCOA2. CODE,''),
	AC_TCOA.PARENT_ID3,
	IFNULL(AC_TALCOA3.parent_name,''),
	IFNULL(AC_TALCOA3. CODE,''),
	AC_TCOA.PARENT_ID4,
	IFNULL(AC_TALCOA4.parent_name,''),
	IFNULL(AC_TALCOA4. CODE,''),
	AC_TCOA.PARENT_ID5,
  IFNULL(	AC_TALCOA5.parent_name,''),
	IFNULL(AC_TALCOA5. CODE,''),
	AC_TCOA.PARENT_ID6,
	IFNULL(AC_TALCOA6.parent_name,''),
	IFNULL(	AC_TALCOA6. CODE,''),
	AC_TCOA.PARENT_ID7,
	IFNULL(AC_TALCOA7.parent_name,''),
	IFNULL(AC_TALCOA7. CODE,''),
	AC_TCOA.CHILD_ID,
	IFNULL(AC_TALCOA8.parent_name,''),
	IFNULL(AC_TALCOA8. CODE,'')
ORDER BY
	IFNULL(AC_TALCOA. CODE,'')ASC,
	IFNULL(AC_TALCOA1. CODE,'')ASC,
	IFNULL(AC_TALCOA2. CODE,'')ASC,
	IFNULL(AC_TALCOA3. CODE,'')ASC,
	IFNULL(AC_TALCOA4. CODE,'')ASC,
	IFNULL(AC_TALCOA5. CODE,'')ASC,
	IFNULL(AC_TALCOA6. CODE,'')ASC,
	IFNULL(AC_TALCOA7. CODE,'')ASC,
	IFNULL(AC_TALCOA8. CODE,'')ASC) tab 
GROUP BY
tab.PARENT_ID,
tab.PARENT_ID1,
tab.PARENT_ID2";
        $query2 = "SELECT 
tab.PARENT_ID,
tab.PN,
tab.PARENT_ID1,
tab.PN2,
tab.PN2_Code,
tab.PARENT_ID2,
tab.PN3,
tab.PN3_Code,
SUM(tab.GR_DEBIT) as dr_amount,
SUM(tab.GR_CREDIT) as cr_amount,
SUM(tab.opening_debit) as opening_debit,
SUM(tab.opening_credit) as opening_credit
FROM (
    /*start tab table*/
        SELECT 
        ALL_LEDGER.PARENT_ID ,
        ALL_LEDGER.PN,
        ALL_LEDGER.PN_Code,
        ALL_LEDGER.PARENT_ID1,
        ALL_LEDGER.PARENT_ID2,
        ALL_LEDGER.PN2,
        ALL_LEDGER.PN2_Code,
        ALL_LEDGER.PARENT_ID3,
        ALL_LEDGER.PN3,
        ALL_LEDGER.PN3_Code,
        ALL_LEDGER.PARENT_ID4,
        ALL_LEDGER.PN4,
        ALL_LEDGER.PN4_Code,
        ALL_LEDGER.PARENT_ID5,
        ALL_LEDGER.PN5,
        ALL_LEDGER.PN5_Code,
        ALL_LEDGER.PARENT_ID6,
        ALL_LEDGER.PN6,
        ALL_LEDGER.PN6_Code,
        ALL_LEDGER.PARENT_ID7,
        ALL_LEDGER.PN7,
        ALL_LEDGER.PN7_Code,
        ALL_LEDGER.CHILD_ID,
        ALL_LEDGER.CN,
        ALL_LEDGER.CN_Code,
        
        IFNULL(opb.debit,0)opening_debit,
        IFNULL(opb.credit,0)opening_credit,
        ledger_transaction.GR_CREDIT,
        ledger_transaction.GR_DEBIT
        FROM (
                SELECT
                    AC_TCOA.COAAutoID,
                    AC_TCOA.PARENT_ID,
                    IFNULL(AC_TALCOA.parent_name,'')PN,
                    IFNULL(AC_TALCOA. CODE,'')PN_Code,
                    AC_TCOA.PARENT_ID1,
                    CASE WHEN AC_TCOA.PARENT_ID2=0 
                     THEN 	AC_TCOA.CHILD_ID  
                     ELSE AC_TCOA.PARENT_ID2
                        END PARENT_ID2,
                    CASE WHEN AC_TCOA.PARENT_ID2=0 
                 THEN 	AC_TALCOA8.parent_name  
                 ELSE AC_TALCOA2.parent_name
                  END PN2,
                    CASE WHEN AC_TCOA.PARENT_ID2=0 
                 THEN 	AC_TALCOA8. CODE  
                 ELSE AC_TALCOA2. CODE
                  END PN2_Code,
                    AC_TCOA.PARENT_ID3,
                    IFNULL(AC_TALCOA3.parent_name,'')PN3,
                    IFNULL(AC_TALCOA3. CODE,'')PN3_Code,
                    AC_TCOA.PARENT_ID4,
                    IFNULL(AC_TALCOA4.parent_name,'')PN4,
                    IFNULL(AC_TALCOA4. CODE,'')PN4_Code,
                    AC_TCOA.PARENT_ID5,
                    IFNULL(AC_TALCOA5.parent_name,'')PN5,
                    IFNULL(AC_TALCOA5. CODE,'')PN5_Code,
                    AC_TCOA.PARENT_ID6,
                    IFNULL(AC_TALCOA6.parent_name,'')PN6,
                    IFNULL(AC_TALCOA6. CODE,'')PN6_Code,
                    AC_TCOA.PARENT_ID7,
                    IFNULL(AC_TALCOA7.parent_name,'')PN7,
                    IFNULL(AC_TALCOA7. CODE,'')PN7_Code,
                    AC_TCOA.CHILD_ID,
                    IFNULL(AC_TALCOA8.parent_name,'')CN,
                    IFNULL(AC_TALCOA8. CODE,'')CN_Code
                FROM
                    ac_tb_coa AC_TCOA
                
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TCOA.PARENT_ID = AC_TALCOA.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id
                LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID = AC_TALCOA8.id
                WHERE AC_TCOA.PARENT_ID=" . $parentId . " and AC_TCOA.CHILD_ID!=0
                GROUP BY
                    AC_TCOA.PARENT_ID,
                    IFNULL(AC_TALCOA.parent_name,''),
                    IFNULL(	AC_TALCOA. CODE,''),
                    AC_TCOA.PARENT_ID1,
                    IFNULL(AC_TALCOA1.parent_name,''),
                    IFNULL(AC_TALCOA1. CODE,''),
                    AC_TCOA.PARENT_ID2,
                    IFNULL(AC_TALCOA2.parent_name,''),
                    IFNULL(AC_TALCOA2. CODE,''),
                    AC_TCOA.PARENT_ID3,
                    IFNULL(AC_TALCOA3.parent_name,''),
                    IFNULL(AC_TALCOA3. CODE,''),
                    AC_TCOA.PARENT_ID4,
                    IFNULL(AC_TALCOA4.parent_name,''),
                    IFNULL(AC_TALCOA4. CODE,''),
                    AC_TCOA.PARENT_ID5,
                  IFNULL(	AC_TALCOA5.parent_name,''),
                    IFNULL(AC_TALCOA5. CODE,''),
                    AC_TCOA.PARENT_ID6,
                    IFNULL(AC_TALCOA6.parent_name,''),
                    IFNULL(	AC_TALCOA6. CODE,''),
                    AC_TCOA.PARENT_ID7,
                    IFNULL(AC_TALCOA7.parent_name,''),
                    IFNULL(AC_TALCOA7. CODE,''),
                    AC_TCOA.CHILD_ID,
                    IFNULL(AC_TALCOA8.parent_name,''),
                    IFNULL(AC_TALCOA8. CODE,'')
                ORDER BY
                    IFNULL(AC_TALCOA. CODE,'')ASC,
                    IFNULL(AC_TALCOA1. CODE,'')ASC,
                    IFNULL(AC_TALCOA2. CODE,'')ASC,
                    IFNULL(AC_TALCOA3. CODE,'')ASC,
                    IFNULL(AC_TALCOA4. CODE,'')ASC,
                    IFNULL(AC_TALCOA5. CODE,'')ASC,
                    IFNULL(AC_TALCOA6. CODE,'')ASC,
                    IFNULL(AC_TALCOA7. CODE,'')ASC,
                    IFNULL(AC_TALCOA8. CODE,'')ASC
        ) ALL_LEDGER
        LEFT JOIN (SELECT account,debit,credit FROM opening_balance WHERE 1=1 AND date <='" . $date . "') opb on opb.account=ALL_LEDGER.CHILD_ID
        LEFT JOIN (
        SELECT
        AC_TAVDtl.CHILD_ID,
        SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))GR_DEBIT,
        SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))GR_CREDIT
        FROM
            ac_tb_accounts_voucherdtl AC_TAVDtl
        WHERE 1=1
        AND AC_TAVDtl.date <='" . $date . "'
        GROUP BY AC_TAVDtl.CHILD_ID
        ) ledger_transaction on ledger_transaction.CHILD_ID=ALL_LEDGER.CHILD_ID
/*END tab table*/
)
tab 
GROUP BY
tab.PARENT_ID,
tab.PARENT_ID1,
tab.PARENT_ID2";
        $query3 = "SELECT 
tab.PARENT_ID,
tab.PN,
tab.PARENT_ID1,
tab.PN1,
tab.PN1_Code,
tab.PN2,
tab.PN2_Code,
tab.PARENT_ID2,
tab.PN3,
tab.PN3_Code,
SUM(tab.GR_DEBIT) as dr_amount,
SUM(tab.GR_CREDIT) as cr_amount,
SUM(tab.opening_debit) as opening_debit,
SUM(tab.opening_credit) as opening_credit
FROM (
    /*start tab table*/
        SELECT 
        ALL_LEDGER.PARENT_ID ,
        ALL_LEDGER.PN,
        ALL_LEDGER.PN_Code,
        ALL_LEDGER.PARENT_ID1,
		ALL_LEDGER.PN1,
        ALL_LEDGER.PN1_Code,
        ALL_LEDGER.PARENT_ID2,
        ALL_LEDGER.PN2,
        ALL_LEDGER.PN2_Code,
        ALL_LEDGER.PARENT_ID3,
        ALL_LEDGER.PN3,
        ALL_LEDGER.PN3_Code,
        ALL_LEDGER.PARENT_ID4,
        ALL_LEDGER.PN4,
        ALL_LEDGER.PN4_Code,
        ALL_LEDGER.PARENT_ID5,
        ALL_LEDGER.PN5,
        ALL_LEDGER.PN5_Code,
        ALL_LEDGER.PARENT_ID6,
        ALL_LEDGER.PN6,
        ALL_LEDGER.PN6_Code,
        ALL_LEDGER.PARENT_ID7,
        ALL_LEDGER.PN7,
        ALL_LEDGER.PN7_Code,
        ALL_LEDGER.CHILD_ID,
        ALL_LEDGER.CN,
        ALL_LEDGER.CN_Code,
        
        IFNULL(opb.debit,0)opening_debit,
        IFNULL(opb.credit,0)opening_credit,
        ledger_transaction.GR_CREDIT,
        ledger_transaction.GR_DEBIT
        FROM (
			SELECT
            AC_TCOA.COAAutoID,
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,'')PN,
            IFNULL(AC_TALCOA. CODE,'')PN_Code,
            AC_TCOA.PARENT_ID1,
						IFNULL(AC_TALCOA1.parent_name,'')PN1,
						IFNULL(AC_TALCOA1. CODE,'')PN1_Code,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code,
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,'')PN3,
            IFNULL(AC_TALCOA3. CODE,'')PN3_Code,
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,'')PN4,
            IFNULL(AC_TALCOA4. CODE,'')PN4_Code,
            AC_TCOA.PARENT_ID5,
            IFNULL(AC_TALCOA5.parent_name,'')PN5,
            IFNULL(AC_TALCOA5. CODE,'')PN5_Code,
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,'')PN6,
            IFNULL(AC_TALCOA6. CODE,'')PN6_Code,
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,'')PN7,
            IFNULL(AC_TALCOA7. CODE,'')PN7_Code,
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,'')CN,
            IFNULL(AC_TALCOA8. CODE,'')CN_Code
        FROM
            ac_tb_coa AC_TCOA
        
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TCOA.PARENT_ID = AC_TALCOA.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID = AC_TALCOA8.id
        WHERE AC_TCOA.PARENT_ID=" . $parentId . " and AC_TCOA.CHILD_ID!=0
        GROUP BY
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,''),
            IFNULL(	AC_TALCOA. CODE,''),
            AC_TCOA.PARENT_ID1,
            IFNULL(AC_TALCOA1.parent_name,''),
            IFNULL(AC_TALCOA1. CODE,''),
            AC_TCOA.PARENT_ID2,
            IFNULL(AC_TALCOA2.parent_name,''),
            IFNULL(AC_TALCOA2. CODE,''),
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,''),
            IFNULL(AC_TALCOA3. CODE,''),
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,''),
            IFNULL(AC_TALCOA4. CODE,''),
            AC_TCOA.PARENT_ID5,
			IFNULL(AC_TALCOA5.parent_name,''),
            IFNULL(AC_TALCOA5. CODE,''),
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,''),
            IFNULL(	AC_TALCOA6. CODE,''),
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,''),
            IFNULL(AC_TALCOA7. CODE,''),
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,''),
            IFNULL(AC_TALCOA8. CODE,'')
        ORDER BY
            IFNULL(AC_TALCOA. CODE,'')ASC,
            IFNULL(AC_TALCOA1. CODE,'')ASC,
            IFNULL(AC_TALCOA2. CODE,'')ASC,
            IFNULL(AC_TALCOA3. CODE,'')ASC,
            IFNULL(AC_TALCOA4. CODE,'')ASC,
            IFNULL(AC_TALCOA5. CODE,'')ASC,
            IFNULL(AC_TALCOA6. CODE,'')ASC,
            IFNULL(AC_TALCOA7. CODE,'')ASC,
            IFNULL(AC_TALCOA8. CODE,'')ASC
        ) ALL_LEDGER
        LEFT JOIN (SELECT account,debit,credit FROM opening_balance WHERE 1=1 AND date <='" . $date . "') opb on opb.account=ALL_LEDGER.CHILD_ID
        LEFT JOIN (
        SELECT
        AC_TAVDtl.CHILD_ID,
        SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))GR_DEBIT,
        SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))GR_CREDIT
        FROM
            ac_tb_accounts_voucherdtl AC_TAVDtl
        WHERE 1=1
        AND AC_TAVDtl.date <='" . $date . "'  and AC_TAVDtl.IsActive=1
        GROUP BY AC_TAVDtl.CHILD_ID
        ) ledger_transaction on ledger_transaction.CHILD_ID=ALL_LEDGER.CHILD_ID
/*END tab table*/
)
tab 
GROUP BY
tab.PARENT_ID,
tab.PARENT_ID1,
tab.PARENT_ID2";
        log_message('error', 'balance sheet query' . print_r($query3, true));
        $query = $this->db->query($query3);
        $result = $query->result();
        return $result;
    }

    public function incomeStatement($parentId, $parentId2, $start_date, $end_date, $branch)
    {
        $query3 = "SELECT 
tab.PARENT_ID,
tab.PN,
tab.PARENT_ID1,
tab.PN1,
tab.PN1_Code,
tab.PN2,
tab.PN2_Code,
tab.PARENT_ID2,
tab.PN3,
tab.PN3_Code,
SUM(tab.GR_DEBIT) as dr_amount,
SUM(tab.GR_CREDIT) as cr_amount,
SUM(tab.opening_debit) as opening_debit,
SUM(tab.opening_credit) as opening_credit
FROM (
    /*start tab table*/
        SELECT 
        ALL_LEDGER.PARENT_ID ,
        ALL_LEDGER.PN,
        ALL_LEDGER.PN_Code,
        ALL_LEDGER.PARENT_ID1,
		ALL_LEDGER.PN1,
        ALL_LEDGER.PN1_Code,
        ALL_LEDGER.PARENT_ID2,
        ALL_LEDGER.PN2,
        ALL_LEDGER.PN2_Code,
        ALL_LEDGER.PARENT_ID3,
        ALL_LEDGER.PN3,
        ALL_LEDGER.PN3_Code,
        ALL_LEDGER.PARENT_ID4,
        ALL_LEDGER.PN4,
        ALL_LEDGER.PN4_Code,
        ALL_LEDGER.PARENT_ID5,
        ALL_LEDGER.PN5,
        ALL_LEDGER.PN5_Code,
        ALL_LEDGER.PARENT_ID6,
        ALL_LEDGER.PN6,
        ALL_LEDGER.PN6_Code,
        ALL_LEDGER.PARENT_ID7,
        ALL_LEDGER.PN7,
        ALL_LEDGER.PN7_Code,
        ALL_LEDGER.CHILD_ID,
        ALL_LEDGER.CN,
        ALL_LEDGER.CN_Code,
        
        IFNULL(opb.debit,0)opening_debit,
        IFNULL(opb.credit,0)opening_credit,
        ledger_transaction.GR_CREDIT,
        ledger_transaction.GR_DEBIT
        FROM (
			SELECT
            AC_TCOA.COAAutoID,
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,'')PN,
            IFNULL(AC_TALCOA. CODE,'')PN_Code,
            AC_TCOA.PARENT_ID1,
						IFNULL(AC_TALCOA1.parent_name,'')PN1,
						IFNULL(AC_TALCOA1. CODE,'')PN1_Code,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code,
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,'')PN3,
            IFNULL(AC_TALCOA3. CODE,'')PN3_Code,
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,'')PN4,
            IFNULL(AC_TALCOA4. CODE,'')PN4_Code,
            AC_TCOA.PARENT_ID5,
            IFNULL(AC_TALCOA5.parent_name,'')PN5,
            IFNULL(AC_TALCOA5. CODE,'')PN5_Code,
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,'')PN6,
            IFNULL(AC_TALCOA6. CODE,'')PN6_Code,
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,'')PN7,
            IFNULL(AC_TALCOA7. CODE,'')PN7_Code,
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,'')CN,
            IFNULL(AC_TALCOA8. CODE,'')CN_Code
        FROM
            ac_tb_coa AC_TCOA
        
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TCOA.PARENT_ID = AC_TALCOA.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID = AC_TALCOA8.id
        WHERE AC_TCOA.PARENT_ID=" . $parentId . " and AC_TCOA.CHILD_ID!=0
        GROUP BY
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,''),
            IFNULL(	AC_TALCOA. CODE,''),
            AC_TCOA.PARENT_ID1,
            IFNULL(AC_TALCOA1.parent_name,''),
            IFNULL(AC_TALCOA1. CODE,''),
            AC_TCOA.PARENT_ID2,
            IFNULL(AC_TALCOA2.parent_name,''),
            IFNULL(AC_TALCOA2. CODE,''),
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,''),
            IFNULL(AC_TALCOA3. CODE,''),
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,''),
            IFNULL(AC_TALCOA4. CODE,''),
            AC_TCOA.PARENT_ID5,
			IFNULL(AC_TALCOA5.parent_name,''),
            IFNULL(AC_TALCOA5. CODE,''),
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,''),
            IFNULL(	AC_TALCOA6. CODE,''),
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,''),
            IFNULL(AC_TALCOA7. CODE,''),
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,''),
            IFNULL(AC_TALCOA8. CODE,'')
        ORDER BY
            IFNULL(AC_TALCOA. CODE,'')ASC,
            IFNULL(AC_TALCOA1. CODE,'')ASC,
            IFNULL(AC_TALCOA2. CODE,'')ASC,
            IFNULL(AC_TALCOA3. CODE,'')ASC,
            IFNULL(AC_TALCOA4. CODE,'')ASC,
            IFNULL(AC_TALCOA5. CODE,'')ASC,
            IFNULL(AC_TALCOA6. CODE,'')ASC,
            IFNULL(AC_TALCOA7. CODE,'')ASC,
            IFNULL(AC_TALCOA8. CODE,'')ASC
        ) ALL_LEDGER
        LEFT JOIN (SELECT account,debit,credit FROM opening_balance WHERE 1=1 AND date <='" . $end_date . "') opb on opb.account=ALL_LEDGER.CHILD_ID
        LEFT JOIN (
        SELECT
        AC_TAVDtl.CHILD_ID,
        SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))GR_DEBIT,
        SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))GR_CREDIT
        FROM
            ac_tb_accounts_voucherdtl AC_TAVDtl
        WHERE 1=1 and AC_TAVDtl.IsActive=1
        AND AC_TAVDtl.date <='" . $end_date . "'
        GROUP BY AC_TAVDtl.CHILD_ID
        ) ledger_transaction on ledger_transaction.CHILD_ID=ALL_LEDGER.CHILD_ID
/*END tab table*/
)
tab 
WHERE
tab.PARENT_ID1='" . $parentId2 . "'
GROUP BY
tab.PARENT_ID,
tab.PARENT_ID1,
tab.PARENT_ID2";
        $query = $this->db->query($query3);
        $result = $query->result();
        return $result;
    }

    public function incomeStatement_with_out_sales_revenue($parentId, $parentId2, $start_date, $end_date, $branch)
    {
        $query3 = "SELECT 
tab.PARENT_ID,
tab.PN,
tab.PARENT_ID1,
tab.PN1,
tab.PN1_Code,
tab.PN2,
tab.PN2_Code,
tab.PARENT_ID2,
tab.PN3,
tab.PN3_Code,
SUM(tab.GR_DEBIT) as dr_amount,
SUM(tab.GR_CREDIT) as cr_amount,
SUM(tab.opening_debit) as opening_debit,
SUM(tab.opening_credit) as opening_credit
FROM (
    /*start tab table*/
        SELECT 
        ALL_LEDGER.PARENT_ID ,
        ALL_LEDGER.PN,
        ALL_LEDGER.PN_Code,
        ALL_LEDGER.PARENT_ID1,
		ALL_LEDGER.PN1,
        ALL_LEDGER.PN1_Code,
        ALL_LEDGER.PARENT_ID2,
        ALL_LEDGER.PN2,
        ALL_LEDGER.PN2_Code,
        ALL_LEDGER.PARENT_ID3,
        ALL_LEDGER.PN3,
        ALL_LEDGER.PN3_Code,
        ALL_LEDGER.PARENT_ID4,
        ALL_LEDGER.PN4,
        ALL_LEDGER.PN4_Code,
        ALL_LEDGER.PARENT_ID5,
        ALL_LEDGER.PN5,
        ALL_LEDGER.PN5_Code,
        ALL_LEDGER.PARENT_ID6,
        ALL_LEDGER.PN6,
        ALL_LEDGER.PN6_Code,
        ALL_LEDGER.PARENT_ID7,
        ALL_LEDGER.PN7,
        ALL_LEDGER.PN7_Code,
        ALL_LEDGER.CHILD_ID,
        ALL_LEDGER.CN,
        ALL_LEDGER.CN_Code,
        
        IFNULL(opb.debit,0)opening_debit,
        IFNULL(opb.credit,0)opening_credit,
        ledger_transaction.GR_CREDIT,
        ledger_transaction.GR_DEBIT
        FROM (
			SELECT
            AC_TCOA.COAAutoID,
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,'')PN,
            IFNULL(AC_TALCOA. CODE,'')PN_Code,
            AC_TCOA.PARENT_ID1,
						IFNULL(AC_TALCOA1.parent_name,'')PN1,
						IFNULL(AC_TALCOA1. CODE,'')PN1_Code,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code,
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,'')PN3,
            IFNULL(AC_TALCOA3. CODE,'')PN3_Code,
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,'')PN4,
            IFNULL(AC_TALCOA4. CODE,'')PN4_Code,
            AC_TCOA.PARENT_ID5,
            IFNULL(AC_TALCOA5.parent_name,'')PN5,
            IFNULL(AC_TALCOA5. CODE,'')PN5_Code,
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,'')PN6,
            IFNULL(AC_TALCOA6. CODE,'')PN6_Code,
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,'')PN7,
            IFNULL(AC_TALCOA7. CODE,'')PN7_Code,
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,'')CN,
            IFNULL(AC_TALCOA8. CODE,'')CN_Code
        FROM
            ac_tb_coa AC_TCOA
        
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TCOA.PARENT_ID = AC_TALCOA.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID = AC_TALCOA8.id
        WHERE AC_TCOA.PARENT_ID=" . $parentId . " and AC_TCOA.CHILD_ID!=0
        GROUP BY
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,''),
            IFNULL(	AC_TALCOA. CODE,''),
            AC_TCOA.PARENT_ID1,
            IFNULL(AC_TALCOA1.parent_name,''),
            IFNULL(AC_TALCOA1. CODE,''),
            AC_TCOA.PARENT_ID2,
            IFNULL(AC_TALCOA2.parent_name,''),
            IFNULL(AC_TALCOA2. CODE,''),
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,''),
            IFNULL(AC_TALCOA3. CODE,''),
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,''),
            IFNULL(AC_TALCOA4. CODE,''),
            AC_TCOA.PARENT_ID5,
			IFNULL(AC_TALCOA5.parent_name,''),
            IFNULL(AC_TALCOA5. CODE,''),
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,''),
            IFNULL(	AC_TALCOA6. CODE,''),
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,''),
            IFNULL(AC_TALCOA7. CODE,''),
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,''),
            IFNULL(AC_TALCOA8. CODE,'')
        ORDER BY
            IFNULL(AC_TALCOA. CODE,'')ASC,
            IFNULL(AC_TALCOA1. CODE,'')ASC,
            IFNULL(AC_TALCOA2. CODE,'')ASC,
            IFNULL(AC_TALCOA3. CODE,'')ASC,
            IFNULL(AC_TALCOA4. CODE,'')ASC,
            IFNULL(AC_TALCOA5. CODE,'')ASC,
            IFNULL(AC_TALCOA6. CODE,'')ASC,
            IFNULL(AC_TALCOA7. CODE,'')ASC,
            IFNULL(AC_TALCOA8. CODE,'')ASC
        ) ALL_LEDGER
        LEFT JOIN (SELECT account,debit,credit FROM opening_balance WHERE 1=1 AND date <='" . $end_date . "') opb on opb.account=ALL_LEDGER.CHILD_ID
        LEFT JOIN (
        SELECT
        AC_TAVDtl.CHILD_ID,
        SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))GR_DEBIT,
        SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))GR_CREDIT
        FROM
            ac_tb_accounts_voucherdtl AC_TAVDtl
        WHERE 1=1 and AC_TAVDtl.IsActive=1
        AND AC_TAVDtl.date <='" . $end_date . "'
        GROUP BY AC_TAVDtl.CHILD_ID
        ) ledger_transaction on ledger_transaction.CHILD_ID=ALL_LEDGER.CHILD_ID
/*END tab table*/
)
tab 
WHERE
tab.PARENT_ID1 !='" . $parentId2 . "'
GROUP BY
tab.PARENT_ID,
tab.PARENT_ID1,
tab.PARENT_ID2";
        $query = $this->db->query($query3);
        $result = $query->result();
        return $result;
    }

    public function incomeStatement2($parentId, $parentId2, $parentId3, $start_date, $end_date, $branch)
    {
        $query3 = "SELECT 
tab.PARENT_ID,
tab.PN,
tab.PARENT_ID1,
tab.PN1,
tab.PN1_Code,
tab.PN2,
tab.PN2_Code,
tab.PARENT_ID2,
tab.PN3,
tab.PN3_Code,
SUM(tab.GR_DEBIT) as dr_amount,
SUM(tab.GR_CREDIT) as cr_amount,
SUM(tab.opening_debit) as opening_debit,
SUM(tab.opening_credit) as opening_credit
FROM (
    /*start tab table*/
        SELECT 
        ALL_LEDGER.PARENT_ID ,
        ALL_LEDGER.PN,
        ALL_LEDGER.PN_Code,
        ALL_LEDGER.PARENT_ID1,
		ALL_LEDGER.PN1,
        ALL_LEDGER.PN1_Code,
        ALL_LEDGER.PARENT_ID2,
        ALL_LEDGER.PN2,
        ALL_LEDGER.PN2_Code,
        ALL_LEDGER.PARENT_ID3,
        ALL_LEDGER.PN3,
        ALL_LEDGER.PN3_Code,
        ALL_LEDGER.PARENT_ID4,
        ALL_LEDGER.PN4,
        ALL_LEDGER.PN4_Code,
        ALL_LEDGER.PARENT_ID5,
        ALL_LEDGER.PN5,
        ALL_LEDGER.PN5_Code,
        ALL_LEDGER.PARENT_ID6,
        ALL_LEDGER.PN6,
        ALL_LEDGER.PN6_Code,
        ALL_LEDGER.PARENT_ID7,
        ALL_LEDGER.PN7,
        ALL_LEDGER.PN7_Code,
        ALL_LEDGER.CHILD_ID,
        ALL_LEDGER.CN,
        ALL_LEDGER.CN_Code,
        
        IFNULL(opb.debit,0)opening_debit,
        IFNULL(opb.credit,0)opening_credit,
        ledger_transaction.GR_CREDIT,
        ledger_transaction.GR_DEBIT
        FROM (
			SELECT
            AC_TCOA.COAAutoID,
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,'')PN,
            IFNULL(AC_TALCOA. CODE,'')PN_Code,
            AC_TCOA.PARENT_ID1,
						IFNULL(AC_TALCOA1.parent_name,'')PN1,
						IFNULL(AC_TALCOA1. CODE,'')PN1_Code,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code,
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,'')PN3,
            IFNULL(AC_TALCOA3. CODE,'')PN3_Code,
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,'')PN4,
            IFNULL(AC_TALCOA4. CODE,'')PN4_Code,
            AC_TCOA.PARENT_ID5,
            IFNULL(AC_TALCOA5.parent_name,'')PN5,
            IFNULL(AC_TALCOA5. CODE,'')PN5_Code,
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,'')PN6,
            IFNULL(AC_TALCOA6. CODE,'')PN6_Code,
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,'')PN7,
            IFNULL(AC_TALCOA7. CODE,'')PN7_Code,
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,'')CN,
            IFNULL(AC_TALCOA8. CODE,'')CN_Code
        FROM
            ac_tb_coa AC_TCOA
        
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA ON AC_TCOA.PARENT_ID = AC_TALCOA.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id
        LEFT OUTER JOIN ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID = AC_TALCOA8.id
        WHERE AC_TCOA.PARENT_ID=" . $parentId . " and AC_TCOA.CHILD_ID!=0
        GROUP BY
            AC_TCOA.PARENT_ID,
            IFNULL(AC_TALCOA.parent_name,''),
            IFNULL(	AC_TALCOA. CODE,''),
            AC_TCOA.PARENT_ID1,
            IFNULL(AC_TALCOA1.parent_name,''),
            IFNULL(AC_TALCOA1. CODE,''),
            AC_TCOA.PARENT_ID2,
            IFNULL(AC_TALCOA2.parent_name,''),
            IFNULL(AC_TALCOA2. CODE,''),
            AC_TCOA.PARENT_ID3,
            IFNULL(AC_TALCOA3.parent_name,''),
            IFNULL(AC_TALCOA3. CODE,''),
            AC_TCOA.PARENT_ID4,
            IFNULL(AC_TALCOA4.parent_name,''),
            IFNULL(AC_TALCOA4. CODE,''),
            AC_TCOA.PARENT_ID5,
			IFNULL(AC_TALCOA5.parent_name,''),
            IFNULL(AC_TALCOA5. CODE,''),
            AC_TCOA.PARENT_ID6,
            IFNULL(AC_TALCOA6.parent_name,''),
            IFNULL(	AC_TALCOA6. CODE,''),
            AC_TCOA.PARENT_ID7,
            IFNULL(AC_TALCOA7.parent_name,''),
            IFNULL(AC_TALCOA7. CODE,''),
            AC_TCOA.CHILD_ID,
            IFNULL(AC_TALCOA8.parent_name,''),
            IFNULL(AC_TALCOA8. CODE,'')
        ORDER BY
            IFNULL(AC_TALCOA. CODE,'')ASC,
            IFNULL(AC_TALCOA1. CODE,'')ASC,
            IFNULL(AC_TALCOA2. CODE,'')ASC,
            IFNULL(AC_TALCOA3. CODE,'')ASC,
            IFNULL(AC_TALCOA4. CODE,'')ASC,
            IFNULL(AC_TALCOA5. CODE,'')ASC,
            IFNULL(AC_TALCOA6. CODE,'')ASC,
            IFNULL(AC_TALCOA7. CODE,'')ASC,
            IFNULL(AC_TALCOA8. CODE,'')ASC
        ) ALL_LEDGER
        LEFT JOIN (SELECT account,debit,credit FROM opening_balance WHERE 1=1 AND date <='" . $end_date . "') opb on opb.account=ALL_LEDGER.CHILD_ID
        LEFT JOIN (
        SELECT
        AC_TAVDtl.CHILD_ID,
        SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))GR_DEBIT,
        SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))GR_CREDIT
        FROM
            ac_tb_accounts_voucherdtl AC_TAVDtl
        WHERE 1=1 and AC_TAVDtl.IsActive=1
        AND AC_TAVDtl.date <='" . $end_date . "'
        GROUP BY AC_TAVDtl.CHILD_ID
        ) ledger_transaction on ledger_transaction.CHILD_ID=ALL_LEDGER.CHILD_ID
/*END tab table*/
)
tab 
WHERE
tab.PARENT_ID1='" . $parentId2 . "'
 AND tab.PARENT_ID2='" . $parentId3 . "'
GROUP BY
tab.PARENT_ID,
tab.PARENT_ID1,
tab.PARENT_ID2";
        $query = $this->db->query($query3);
        $result = $query->result();
        return $result;
    }

    public function get_sales_revenue($start_date, $end_date, $branch)
    {
        $query = "SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   
   
 
  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE   AC_TCOA.PARENT_ID = 3  AND   AC_TCOA.PARENT_ID1=" . $this->config->item("SalesRevenue") . " AND
  AC_TAVMst.Accounts_Voucher_Date < '" . $start_date . "' AND 1=1 
        GROUP BY 
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 
LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID


/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 3  AND   AC_TCOA.PARENT_ID1=" . $this->config->item("SalesRevenue") . "  AND  AC_TAVMst.Accounts_Voucher_Date >= '" . $start_date . "' AND  AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1 ";
        if ($branch != 'all') {
            $query .= " AND AC_TAVDtl.BranchAutoId=" . $branch;
        }
        $query .= " GROUP BY   AC_TAVDtl.BranchAutoId,
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC";
        // log_message('error', 'this is get_sales_revenue ' . print_r($query, true));
        $query = $this->db->query($query);
        $result = $query->result();
        foreach ($result as $key => $value) {
            $array[$value->PN1_Code . '#@' . $value->PN1][] = $value;
        }
        return $array;
    }

    public function get_other_income_without_sales_revenue($start_date, $end_date, $branch)
    {
        $query = "SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance
  ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   
   
 
  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE   AC_TCOA.PARENT_ID = 3  AND   AC_TCOA.PARENT_ID1 !=" . $this->config->item("SalesRevenue") . " AND  AC_TCOA.CHILD_ID!=0 AND
  AC_TAVMst.Accounts_Voucher_Date < '" . $start_date . "' and AC_TAVDtl.IsActive=1
 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 3  AND   AC_TCOA.PARENT_ID1 !=" . $this->config->item("SalesRevenue") . "  AND  AC_TCOA.CHILD_ID!=0 AND AC_TAVMst.Accounts_Voucher_Date >= '" . $start_date . "' AND  AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' and AC_TAVDtl.IsActive=1
 AND 1=1 ";
        if ($branch != 'all') {
            $query .= " AND AC_TAVDtl.BranchAutoId =" . $branch;
        }
        $query .= " GROUP BY  AC_TAVDtl.BranchAutoId,
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC";

        $query = $this->db->query($query);
        $result = $query->result();
        foreach ($result as $key => $value) {
            foreach ($result as $key => $value) {
                if ($value->CN != $value->PN2) {
                    $array[$value->PN1_Code . '#@' . $value->PN1][$value->PN2_Code . '#@' . $value->PN2][] = $value;
                } else {
                    $array[$value->PN1_Code . '#@' . $value->PN1]['single'][] = $value;
                }
            }
        }
        return $array;
    }

    public function get_expance_without_cost_of_goods_sold($start_date, $end_date, $branch)
    {
        $query = "SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,CASE WHEN AC_TCOA.PARENT_ID2=0 
             THEN 	AC_TCOA.CHILD_ID  
             ELSE AC_TCOA.PARENT_ID2
                END PARENT_ID2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8.parent_name  
         ELSE AC_TALCOA2.parent_name
          END PN2,
            CASE WHEN AC_TCOA.PARENT_ID2=0 
         THEN 	AC_TALCOA8. CODE  
         ELSE AC_TALCOA2. CODE
          END PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   
   
 
  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1 !=" . $this->config->item("Cost_of_Goods_Sold") . " AND
  AC_TAVMst.Accounts_Voucher_Date < '" . $start_date . "' and AC_TAVDtl.IsActive=1
 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4 AND AC_TCOA.CHILD_ID!=0 AND   AC_TCOA.PARENT_ID1 !=" . $this->config->item("Cost_of_Goods_Sold") . " AND  AC_TAVMst.Accounts_Voucher_Date >= '" . $start_date . "' AND  AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' and AC_TAVDtl.IsActive=1

 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC";
        log_message('error', 'this is get_sales_revenue ' . print_r($query, true));
        $query = $this->db->query($query);
        $result = $query->result();
        foreach ($result as $key => $value) {
            if ($value->CN != $value->PN2) {
                $array[$value->PN1_Code . '#@' . $value->PN1][$value->PN2_Code . '#@' . $value->PN2][] = $value;
            } else {
                $array[$value->PN1_Code . '#@' . $value->PN1]['single'][] = $value;
            }
        }


        return $array;
    }

    function get_cost_of_goods_group_sum($start_date, $end_date, $branch)
    {
        $query = "SELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
SUM(tab1.Balance) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

 FROM (
 SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   
   
 
  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE   AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=" . $this->config->item("Cost_of_Goods_Sold") . " AND
  AC_TAVMst.Accounts_Voucher_Date < '" . $start_date . "' and AC_TAVDtl.IsActive=1
 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 
  LEFT JOIN ( SELECT show_in_income_stetement,id FROM ac_account_ledger_coa  ) aalc ON aalc.id=AC_TCOA.CHILD_ID

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = 4  AND   AC_TCOA.PARENT_ID1=" . $this->config->item("Cost_of_Goods_Sold") . "  AND  AC_TAVMst.Accounts_Voucher_Date >= '" . $start_date . "' AND  AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' and AC_TAVDtl.IsActive=1
AND aalc.show_in_income_stetement=1 AND 1=1 ";
        $query .= " AND AC_TAVDtl.BranchAutoId=" . $branch;
        $query .= " GROUP BY  AC_TAVDtl.BranchAutoId,
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code";
        log_message('error', 'This is cost of good sold' . print_r($query, true));
        $query = $this->db->query($query);
        $array = $query->row();
        return $array;
    }

    function get_sum_of_a_accounting_group($groupId, $date, $BranchAutoId = '1')
    {
        $queryBK = "
SELECT (SUM(tab1.GR_DEBIT)-SUM(tab1.GR_CREDIT)) as amount FROM(

SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   
   
 
  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE   AC_TCOA.PARENT_ID = " . $groupId . "   AND
  AC_TAVMst.Accounts_Voucher_Date < '" . $date . "' and AC_TAVDtl.IsActive=1
 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = " . $groupId . "  AND    AC_TAVMst.Accounts_Voucher_Date <= '" . $date . "' and AC_TAVDtl.IsActive=1

 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC
) tab1
GROUP BY tab1.PARENT_ID";


        $query = "SELECT
--  baseTable.CHILD_ID,
(SUM(acd.GR_DEBIT)-SUM(GR_CREDIT)) AS amount FROM (SELECT
	CHILD_ID
FROM
	ac_tb_coa
WHERE
	PARENT_ID = " . $groupId . "
AND CHILD_ID <> 0
UNION ALL
	SELECT
		
		CHILD_ID
	FROM
		ac_tb_coa
	WHERE
		PARENT_ID1 = " . $groupId . "
	AND CHILD_ID <> 0
	UNION ALL
		SELECT
			CHILD_ID
		FROM
			ac_tb_coa
		WHERE
			PARENT_ID2 = " . $groupId . "
		AND CHILD_ID <> 0
		UNION ALL
			SELECT
				CHILD_ID
			FROM
				ac_tb_coa
			WHERE
				PARENT_ID3 = " . $groupId . "
			AND CHILD_ID <> 0
			UNION ALL
				SELECT
					CHILD_ID
				FROM
					ac_tb_coa
				WHERE
					PARENT_ID4 = " . $groupId . "
				AND CHILD_ID <> 0
				UNION ALL
					SELECT
						CHILD_ID
					FROM
						ac_tb_coa
					WHERE
						PARENT_ID5 = " . $groupId . "
					AND CHILD_ID <> 0
					UNION ALL
						SELECT
							CHILD_ID
						FROM
							ac_tb_coa
						WHERE
							PARENT_ID6 = " . $groupId . "
						AND CHILD_ID <> 0
						UNION ALL
							SELECT
								CHILD_ID
							FROM
								ac_tb_coa 
							WHERE
								PARENT_ID7 = " . $groupId . "
							AND CHILD_ID <> 0) baseTable
LEFT JOIN ac_tb_accounts_voucherdtl acd ON acd.CHILD_ID=baseTable.CHILD_ID
LEFT JOIN ac_accounts_vouchermst acm ON acm.Accounts_VoucherMst_AutoID=acd.Accounts_VoucherMst_AutoID
WHERE acm.Accounts_Voucher_Date <= '" . $date . "' and acd.IsActive=1 and acd.BranchAutoId='" . $BranchAutoId . "'
GROUP BY acd.BranchAutoId";


        $query = $this->db->query($query);
        $array = $query->row();

        return $array;
    }


    function get_third_level_group_sum($first_group, $thired_level_group)
    {
        $query = "SELECT 
SUM(tab1.Opening) AS Opening,
SUM(tab1.GR_DEBIT) AS GR_DEBIT,
SUM(tab1.GR_CREDIT) AS GR_CREDIT,
(SUM(tab1.GR_DEBIT)-SUM(tab1.GR_CREDIT)) AS Balance,
tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code

 FROM (
 SELECT    

IFNULL(OT.Opening,0) Opening ,  
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   
   
 
  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE   AC_TCOA.PARENT_ID = " . $first_group . "  AND   AC_TCOA.PARENT_ID2=" . $thired_level_group . " 

 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID = " . $first_group . "  AND   AC_TCOA.PARENT_ID2=" . $thired_level_group . " 


 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC
) tab1


GROUP BY

tab1.PARENT_ID,
tab1.PN,
tab1.PN_Code,
tab1.PARENT_ID1,
tab1.PN1,
tab1.PN1_Code,
tab1.PARENT_ID2,
tab1.PN2,
tab1.PN2_Code";


        $query = $this->db->query($query);
        $array = $query->row();

        return $array;
    }

    function get_general_ledger_summery($groupId, $ledgerId, $start_date, $end_date, $branch_id)
    {
        $query2 = "";
        if ($ledgerId != 'all') {
            $query2 = " AND CHILD_ID=" . $ledgerId;

        }
        $query = " 
SELECT    

IFNULL(OT.Opening,0) Opening ,  SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance

 
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code,
    (SELECT parent_name FROM  ac_account_ledger_coa WHERE id=(SELECT 
      parent_id
       
  FROM  ac_account_ledger_coa WHERE id=AC_TCOA.CHILD_ID))
  
   as MainParentName


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst 
ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
   

  
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code


FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE      AC_TAVMst.Accounts_Voucher_Date < '" . $start_date . "' AND AC_TAVMst.IsActive=1 

 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON AC_TCOA.CHILD_ID =OT.CHILD_ID 

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) --AND AC_TAVDtl.GR_DEBIT<>0 AND AC_TAVDtl.GR_CREDIT<>0
*/
WHERE  AC_TAVDtl.CHILD_ID in(SELECT
	CHILD_ID
FROM
	ac_tb_coa
WHERE
	PARENT_ID = " . $groupId . "  " . $query2 . "
AND CHILD_ID <> 0
UNION ALL
	SELECT
		
		CHILD_ID
	FROM
		ac_tb_coa
	WHERE
		PARENT_ID1 = " . $groupId . "  " . $query2 . "
	AND CHILD_ID <> 0
	UNION ALL
		SELECT
			CHILD_ID
		FROM
			ac_tb_coa
		WHERE
			PARENT_ID2 = " . $groupId . " " . $query2 . "
		AND CHILD_ID <> 0
		UNION ALL
			SELECT
				CHILD_ID
			FROM
				ac_tb_coa
			WHERE
				PARENT_ID3 = " . $groupId . " " . $query2 . "
			AND CHILD_ID <> 0
			UNION ALL
				SELECT
					CHILD_ID
				FROM
					ac_tb_coa
				WHERE
					PARENT_ID4 = " . $groupId . " " . $query2 . "
				AND CHILD_ID <> 0
				UNION ALL
					SELECT
						CHILD_ID
					FROM
						ac_tb_coa
					WHERE
						PARENT_ID5 = " . $groupId . " " . $query2 . "
					AND CHILD_ID <> 0
					UNION ALL
						SELECT
							CHILD_ID
						FROM
							ac_tb_coa
						WHERE
							PARENT_ID6 = " . $groupId . " " . $query2 . "
						AND CHILD_ID <> 0
						UNION ALL
							SELECT
								CHILD_ID
							FROM
								ac_tb_coa
							WHERE
								PARENT_ID7 = " . $groupId . " " . $query2 . "
							AND CHILD_ID <> 0)
							
							
							
						   AND  AC_TAVMst.Accounts_Voucher_Date >= '" . $start_date . "' AND  AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "'
 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
 ,OT.Opening 
 ORDER BY MainParentName,IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC";
        //log_message('error','this is gl query '.print_r($query,true));


        $queryNew = "SELECT IFNULL(OT.Opening,0) Opening,base_table.CHILD_ID  ,
SUM(IFNULL(acd_tran.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(acd_tran.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(acd_tran.GR_DEBIT,0)) - SUM(IFNULL(acd_tran.GR_CREDIT,0))) AS Balance,
AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code,
    (SELECT parent_name FROM  ac_account_ledger_coa WHERE id=(SELECT 
      parent_id
  FROM  ac_account_ledger_coa WHERE id=AC_TCOA.CHILD_ID))
from  (SELECT DISTINCT clild_ids.CHILD_ID FROM (SELECT
	CHILD_ID
FROM
	ac_tb_coa
WHERE
	PARENT_ID = " . $groupId . "  " . $query2 . "
AND CHILD_ID <> 0
UNION ALL
	SELECT
		CHILD_ID
	FROM
		ac_tb_coa
	WHERE
		PARENT_ID1 = " . $groupId . "  " . $query2 . "
	AND CHILD_ID <> 0
	UNION ALL
		SELECT
			CHILD_ID
		FROM
			ac_tb_coa
		WHERE
			PARENT_ID2 = " . $groupId . " " . $query2 . "
		AND CHILD_ID <> 0
		UNION ALL
			SELECT
				CHILD_ID
			FROM
				ac_tb_coa
			WHERE
				PARENT_ID3 = " . $groupId . " " . $query2 . "
			AND CHILD_ID <> 0
			UNION ALL
				SELECT
					CHILD_ID
				FROM
					ac_tb_coa
				WHERE
					PARENT_ID4 = " . $groupId . " " . $query2 . "
				AND CHILD_ID <> 0
				UNION ALL
					SELECT
						CHILD_ID
					FROM
						ac_tb_coa
					WHERE
						PARENT_ID5 = " . $groupId . " " . $query2 . "
					AND CHILD_ID <> 0
					UNION ALL
						SELECT
							CHILD_ID
						FROM
							ac_tb_coa
						WHERE
							PARENT_ID6 = " . $groupId . " " . $query2 . "
						AND CHILD_ID <> 0
						UNION ALL
							SELECT
								CHILD_ID
							FROM
								ac_tb_coa
							WHERE
								PARENT_ID7 = " . $groupId . " " . $query2 . "
							AND CHILD_ID <> 0) clild_ids) base_table
LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON base_table.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id LEFT OUTER JOIN 
(
SELECT    

   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code
FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE      AC_TAVMst.Accounts_Voucher_Date < '" . $start_date . "' AND AC_TAVMst.IsActive=1 

 GROUP BY  
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON base_table.CHILD_ID =OT.CHILD_ID 

LEFT OUTER JOIN
(
SELECT
	acd.CHILD_ID,SUM(acd.GR_CREDIT) GR_CREDIT,SUM(acd.GR_DEBIT) GR_DEBIT
FROM
	ac_tb_accounts_voucherdtl acd
LEFT JOIN ac_accounts_vouchermst acm ON acm.Accounts_VoucherMst_AutoID = acd.Accounts_VoucherMst_AutoID
WHERE  acm.Accounts_Voucher_Date  <= '" . $end_date . "' AND  acm.Accounts_Voucher_Date >= '" . $start_date . "' AND acd.IsActive=1 
GROUP BY acd.CHILD_ID

)  acd_tran on acd_tran.CHILD_ID=base_table.CHILD_ID


GROUP BY base_table.CHILD_ID";


        $queryNew2 = "SELECT branch.branch_id,branch.branch_name,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code,IFNULL(OT.Opening,0) Opening,base_table.CHILD_ID  ,
SUM(IFNULL(acd_tran.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(acd_tran.GR_CREDIT,0)) GR_CREDIT ,
 (SUM(IFNULL(acd_tran.GR_DEBIT,0)) - SUM(IFNULL(acd_tran.GR_CREDIT,0))) AS Balance,
AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,
    (SELECT parent_name FROM  ac_account_ledger_coa WHERE id=(SELECT 
      parent_id
  FROM  ac_account_ledger_coa WHERE id=AC_TCOA.CHILD_ID))
from  (SELECT DISTINCT clild_ids.CHILD_ID,branch.branch_id FROM (SELECT
	CHILD_ID
FROM
	ac_tb_coa
WHERE
	PARENT_ID = " . $groupId . "  " . $query2 . "
AND CHILD_ID <> 0
UNION ALL
	SELECT
		CHILD_ID
	FROM
		ac_tb_coa
	WHERE
		PARENT_ID1 = " . $groupId . "  " . $query2 . "
	AND CHILD_ID <> 0
	UNION ALL
		SELECT
			CHILD_ID
		FROM
			ac_tb_coa
		WHERE
			PARENT_ID2 = " . $groupId . " " . $query2 . "
		AND CHILD_ID <> 0
		UNION ALL
			SELECT
				CHILD_ID
			FROM
				ac_tb_coa
			WHERE
				PARENT_ID3 = " . $groupId . " " . $query2 . "
			AND CHILD_ID <> 0
			UNION ALL
				SELECT
					CHILD_ID
				FROM
					ac_tb_coa
				WHERE
					PARENT_ID4 = " . $groupId . " " . $query2 . "
				AND CHILD_ID <> 0
				UNION ALL
					SELECT
						CHILD_ID
					FROM
						ac_tb_coa
					WHERE
						PARENT_ID5 = " . $groupId . " " . $query2 . "
					AND CHILD_ID <> 0
					UNION ALL
						SELECT
							CHILD_ID
						FROM
							ac_tb_coa
						WHERE
							PARENT_ID6 = " . $groupId . " " . $query2 . "
						AND CHILD_ID <> 0
						UNION ALL
							SELECT
								CHILD_ID
							FROM
								ac_tb_coa
							WHERE
								PARENT_ID7 = " . $groupId . " " . $query2 . "
							AND CHILD_ID <> 0) clild_ids cross join branch
GROUP BY clild_ids.CHILD_ID,branch.branch_name ) base_table
LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON base_table.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id LEFT OUTER JOIN 
(
SELECT    
AC_TAVDtl.BranchAutoId,
   SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
   (SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) - SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) ) AS Opening
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code
FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  
 
WHERE      AC_TAVMst.Accounts_Voucher_Date < '" . $start_date . "' AND AC_TAVMst.IsActive=1 

 GROUP BY  AC_TAVDtl.BranchAutoId,
 AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  ) OT ON base_table.CHILD_ID =OT.CHILD_ID  AND base_table.branch_id=OT.BranchAutoId

LEFT OUTER JOIN
(
SELECT
	acd.BranchAutoId,acd.CHILD_ID,SUM(acd.GR_CREDIT) GR_CREDIT,SUM(acd.GR_DEBIT) GR_DEBIT
FROM
	ac_tb_accounts_voucherdtl acd
LEFT JOIN ac_accounts_vouchermst acm ON acm.Accounts_VoucherMst_AutoID = acd.Accounts_VoucherMst_AutoID
WHERE  acm.Accounts_Voucher_Date  <= '" . $end_date . "' AND  acm.Accounts_Voucher_Date >= '" . $start_date . "' AND acd.IsActive=1 
GROUP BY acd.BranchAutoId,acd.CHILD_ID

)  acd_tran on acd_tran.CHILD_ID=base_table.CHILD_ID AND acd_tran.BranchAutoId=base_table.branch_id


LEFT JOIN branch ON branch.branch_id=base_table.branch_id
WHERE 1=1";

        if ($branch_id != 'all' && $branch_id != '0') {
            $queryNew2 .= " AND base_table.branch_id=" . $branch_id;
        }
        $queryNew2 .= "  GROUP BY base_table.branch_id,base_table.CHILD_ID
ORDER BY base_table.branch_id,base_table.CHILD_ID";
        log_message('error', "get_general_ledger_summery " . print_r($queryNew2, true));
        $query = $this->db->query($queryNew2);
        $result = $query->result();
        $array = array();
        foreach ($result as $key => $value) {
            $array[$value->branch_name][] = $value;
        }
        return $array;
    }

    public function getalldayBook()
    {
        $this->db->select('day_book_report_config.id,ac_account_ledger_coa.id as groupId,ac_account_ledger_coa.parent_name,ac_account_ledger_coa.code');
        $this->db->from("day_book_report_config");
        $this->db->join('ac_account_ledger_coa', 'ac_account_ledger_coa.id=day_book_report_config.acc_group_id');
        $this->db->where('ac_account_ledger_coa.level_no ', 3);
        $accountGroup = $this->db->get()->result();
        return $accountGroup;
    }


    public function getalldayBookSummery($start_date, $branch_id)
    {
        $query = "SELECT
 acl.code, acl.parent_name,acl.level_no,	base_table.acc_group_id,
  CASE WHEN IFNULL(aci.CHILD_ID,0)=0 
	 THEN 	base_table.acc_group_id  
	 ELSE aci.CHILD_ID
		END CHILD_ID,
SUM(IFNULL(tran_amount.GR_DEBIT,0)) as GR_DEBIT,
SUM(IFNULL(tran_amount.GR_CREDIT,0)) as GR_CREDIT
FROM
	day_book_report_config AS base_table 
LEFT JOIN 
ac_tb_coa aci ON aci.PARENT_ID2=base_table.acc_group_id AND aci.CHILD_ID !=0 AND aci.PARENT_ID2 !=0 
LEFT JOIN ac_account_ledger_coa acl ON acl.id=base_table.acc_group_id 
LEFT JOIN ( SELECT
	SUM(IFNULL(acd.GR_CREDIT,0)) GR_CREDIT,SUM(IFNULL(acd.GR_DEBIT,0)) GR_DEBIT ,acd.CHILD_ID
FROM
	ac_tb_accounts_voucherdtl acd
LEFT JOIN ac_accounts_vouchermst acm on acm.Accounts_VoucherMst_AutoID=acd.Accounts_VoucherMst_AutoID
WHERE
	1 = 1
AND acd.date='" . $start_date . "'
GROUP BY acd.CHILD_ID ) AS tran_amount on tran_amount.CHILD_ID=aci.CHILD_ID
WHERE 1=1 
GROUP BY base_table.acc_group_id";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function getalldayBookSummeryNew($start_date, $branch_id)
    {
        $query = "
SELECT baseTable.branch_id,
baseTable.branch_name,
baseTable.acc_group_id,
baseTable.`code`,
baseTable.parent_name,
baseTable.posted,
baseTable.CHILD_ID,
baseTable.PARENT_ID,
SUM(IFNULL(balance_on_that_day_table.balance_on_that_day,0)) balance_on_that_day,
SUM(IFNULL(balance_upto_that_day_table.balance_upto_that_day,0)) balance_upto_that_day,
CASE WHEN baseTable.PARENT_ID=3 OR baseTable.PARENT_ID=4
	 THEN 	SUM(IFNULL(balance_on_that_day_table.balance_on_that_day,0))  
	 ELSE SUM(IFNULL(balance_upto_that_day_table.balance_upto_that_day,0))
		END dayBookBalance


FROM(
SELECT
branch.branch_id,
branch.branch_name,
	dbc.acc_group_id,
	acl.`code`,
	acl.parent_name,
	acl.posted,
CASE WHEN acl.posted=1 
	 THEN 	dbc.acc_group_id  
	 ELSE childTable.CHILD_ID
		END CHILD_ID,

	CASE WHEN acl.posted=1 
	 THEN 	(SELECT ac_tb_coa.PARENT_ID FROM ac_tb_coa WHERE ac_tb_coa.TB_AccountsLedgerCOA_id= dbc.acc_group_id)
	 ELSE childTable.PARENT_ID
		END PARENT_ID

FROM
	day_book_report_config dbc
LEFT JOIN ac_account_ledger_coa acl ON acl.id = dbc.acc_group_id
CROSS JOIN branch
LEFT JOIN(
	SELECT
		ac_tb_coa.CHILD_ID,
		ac_tb_coa.PARENT_ID,
		ac_tb_coa.PARENT_ID1,
		ac_tb_coa.PARENT_ID2
	FROM
		ac_tb_coa
	WHERE
		1 = 1
	AND ac_tb_coa.CHILD_ID != 0
	AND ac_tb_coa.CHILD_ID NOT IN(106,105)
-- 105=Sales Empty Cylinder With Refill,106=Cost of Empty Cylinder With Refill
)AS childTable ON childTable.PARENT_ID2 = dbc.acc_group_id
) baseTable

LEFT JOIN (
SELECT  acd.CHILD_ID,(SUM(acd.GR_CREDIT)-SUM(acd.GR_DEBIT)) as balance_on_that_day 
FROM ac_tb_accounts_voucherdtl acd 
WHERE 1=1 AND  acd.date ='" . $start_date . "' AND acd.BranchAutoId='" . $branch_id . "'
GROUP BY acd.CHILD_ID
) balance_on_that_day_table ON balance_on_that_day_table.CHILD_ID=baseTable.CHILD_ID


LEFT JOIN (
SELECT  acd.CHILD_ID,(SUM(acd.GR_DEBIT)-SUM(acd.GR_CREDIT)) as balance_upto_that_day 
FROM ac_tb_accounts_voucherdtl acd 
WHERE 1=1 AND  acd.date <='" . $start_date . "' AND acd.BranchAutoId='" . $branch_id . "'
GROUP BY acd.CHILD_ID
) balance_upto_that_day_table ON balance_upto_that_day_table.CHILD_ID=baseTable.CHILD_ID
WHERE baseTable.branch_id ='" . $branch_id . "'
GROUP BY baseTable.acc_group_id,baseTable.branch_id
ORDER BY  baseTable.PARENT_ID,baseTable.branch_id



";

        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function getalldayBookDetails($start_date, $branch_id)
    {
        $query = "SELECT
tran_amount.Accounts_Voucher_Date,
tran_amount.Accounts_Voucher_No,
tran_amount.AccouVoucherType_AutoID,
tran_amount.BackReferenceInvoiceID,
tran_amount.BackReferenceInvoiceNo,
tran_amount.Accounts_VoucherType,
tran_amount.for,
 acl.code, acl.parent_name,acl.level_no,	base_table.acc_group_id,
  CASE WHEN IFNULL(aci.CHILD_ID,0)=0 
	 THEN 	base_table.acc_group_id  
	 ELSE aci.CHILD_ID
		END CHILD_ID,
CASE WHEN IFNULL(aci.CHILD_ID,0)=0 
	 THEN 	acl.parent_name  
	 ELSE tran_amount.ledger_name
		END ledger_name,
(IFNULL(tran_amount.GR_DEBIT,0)) as GR_DEBIT,
(IFNULL(tran_amount.GR_CREDIT,0)) as GR_CREDIT
FROM
	day_book_report_config AS base_table 
LEFT JOIN 
ac_tb_coa aci ON aci.PARENT_ID2=base_table.acc_group_id AND aci.CHILD_ID !=0 AND aci.PARENT_ID2 !=0 
LEFT JOIN ac_account_ledger_coa acl ON acl.id=base_table.acc_group_id 
LEFT JOIN ( SELECT
	(IFNULL(acd.GR_CREDIT,0)) GR_CREDIT,(IFNULL(acd.GR_DEBIT,0)) GR_DEBIT ,acd.CHILD_ID,
acm.Accounts_Voucher_No,acm.Accounts_Voucher_Date,
acm.BackReferenceInvoiceID,acm.BackReferenceInvoiceNo,acm.`for`,acm.AccouVoucherType_AutoID,acl.parent_name as ledger_name,voucher_name.Accounts_VoucherType
FROM
	ac_tb_accounts_voucherdtl acd
LEFT JOIN ac_accounts_vouchermst acm on acm.Accounts_VoucherMst_AutoID=acd.Accounts_VoucherMst_AutoID
LEFT JOIN ac_account_ledger_coa acl ON acl.id=acd.CHILD_ID
LEFT JOIN accounts_vouchertype_autoid voucher_name on voucher_name.Accounts_VoucherType_AutoID=acm.AccouVoucherType_AutoID
WHERE
	1 = 1
AND acd.date='" . $start_date . "'
AND acd.BranchAutoId='" . $branch_id . "'
 ) AS tran_amount on tran_amount.CHILD_ID=aci.CHILD_ID
WHERE 1=1";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    function balance_sheet_query_with_branch($end_date, $branch, $optional = "")
    {
        $query = "SELECT 
branch.branch_name,
table1.BranchAutoId,
SUM(table1.GR_DEBIT) AS GR_DEBIT,
SUM(table1.GR_CREDIT) AS GR_CREDIT,
CASE
    WHEN table1.PARENT_ID = 1 THEN (SUM(table1.GR_DEBIT)-SUM(table1.GR_CREDIT))
    ELSE (SUM(table1.GR_CREDIT)-SUM(table1.GR_DEBIT))
END AS Balance,
table1.PARENT_ID,
table1.PN,
table1.PN_Code,
table1.PARENT_ID1,
table1.PN1,
table1.PN1_Code,
table1.PARENT_ID2,
CASE
    WHEN table1.PN2 !='' THEN table1.PN2
    ELSE table1.CN
END AS PN2,
CASE
    WHEN table1.PN2_Code !='' THEN table1.PN2_Code
    ELSE table1.PN2_Code
END AS PN2_Code,
table1.PARENT_ID3,
table1.PN3,
table1.PN3_Code,
table1.PARENT_ID4,
table1.PN4,
table1.PN4_Code,table1.CN   ,table1.CN_Code
 FROM ( 
SELECT    

 AC_TAVDtl.BranchAutoId,
SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0)) GR_DEBIT ,
SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0)) GR_CREDIT ,
 (  SUM(IFNULL(AC_TAVDtl.GR_DEBIT,0))-SUM(IFNULL(AC_TAVDtl.GR_CREDIT,0))) AS Balance
    ,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'') PN    , IFNULL(AC_TALCOA.code,'')  PN_Code
    ,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'') PN1  , IFNULL(AC_TALCOA1.code,'') PN1_Code
    ,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'') PN2  , IFNULL(AC_TALCOA2.code,'') PN2_Code
    ,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'') PN3  , IFNULL(AC_TALCOA3.code,'') PN3_Code
    ,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'') PN4  , IFNULL(AC_TALCOA4.code,'') PN4_Code
    ,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'') PN5  , IFNULL(AC_TALCOA5.code,'') PN5_Code
    ,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'') PN6  , IFNULL(AC_TALCOA6.code,'') PN6_Code
    ,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'') PN7  , IFNULL(AC_TALCOA7.code,'') PN7_Code
    ,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'') CN   , IFNULL(AC_TALCOA8.code,'') CN_Code

FROM         ac_tb_accounts_voucherdtl  AC_TAVDtl LEFT OUTER JOIN 
 ac_accounts_vouchermst  AC_TAVMst ON AC_TAVDtl.Accounts_VoucherMst_AutoID= AC_TAVMst.Accounts_VoucherMst_AutoID  LEFT OUTER JOIN 
ac_tb_coa  AC_TCOA ON AC_TAVDtl.CHILD_ID=AC_TCOA.CHILD_ID  LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA  ON AC_TCOA.PARENT_ID  = AC_TALCOA.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA1 ON AC_TCOA.PARENT_ID1 = AC_TALCOA1.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA2 ON AC_TCOA.PARENT_ID2 = AC_TALCOA2.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA3 ON AC_TCOA.PARENT_ID3 = AC_TALCOA3.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA4 ON AC_TCOA.PARENT_ID4 = AC_TALCOA4.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA5 ON AC_TCOA.PARENT_ID5 = AC_TALCOA5.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA6 ON AC_TCOA.PARENT_ID6 = AC_TALCOA6.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA7 ON AC_TCOA.PARENT_ID7 = AC_TALCOA7.id LEFT OUTER JOIN 
ac_account_ledger_coa AC_TALCOA8 ON AC_TCOA.CHILD_ID   = AC_TALCOA8.id  

/*WHERE   CONVERT(DATETIME, AC_TAVMst.[Accounts_Voucher_Date],103)
BETWEEN CONVERT(DATETIME, @FDate,103) AND CONVERT(DATETIME, @TDate,103) */
WHERE AC_TCOA.PARENT_ID IN (1,2) AND AC_TAVMst.Accounts_Voucher_Date <= '" . $end_date . "' AND AC_TAVDtl.IsActive = 1  ";
        if ($branch != 'all') {
            $query .= " AND AC_TAVDtl.BranchAutoId = " . $branch;
        }
        $query .= "  GROUP BY  
 AC_TAVDtl.BranchAutoId,AC_TCOA.PARENT_ID  , IFNULL(AC_TALCOA.parent_name,'')     , IFNULL(AC_TALCOA.code,'')   
,AC_TCOA.PARENT_ID1 , IFNULL(AC_TALCOA1.parent_name,'')    , IFNULL(AC_TALCOA1.code,'')  
,AC_TCOA.PARENT_ID2 , IFNULL(AC_TALCOA2.parent_name,'')    , IFNULL(AC_TALCOA2.code,'')  
,AC_TCOA.PARENT_ID3 , IFNULL(AC_TALCOA3.parent_name,'')    , IFNULL(AC_TALCOA3.code,'')  
,AC_TCOA.PARENT_ID4 , IFNULL(AC_TALCOA4.parent_name,'')    , IFNULL(AC_TALCOA4.code,'')  
,AC_TCOA.PARENT_ID5 , IFNULL(AC_TALCOA5.parent_name,'')    , IFNULL(AC_TALCOA5.code,'')  
,AC_TCOA.PARENT_ID6 , IFNULL(AC_TALCOA6.parent_name,'')    , IFNULL(AC_TALCOA6.code,'')  
,AC_TCOA.PARENT_ID7 , IFNULL(AC_TALCOA7.parent_name,'')    , IFNULL(AC_TALCOA7.code,'')  
,AC_TCOA.CHILD_ID   , IFNULL(AC_TALCOA8.parent_name,'')    , IFNULL(AC_TALCOA8.code,'')  
  
 ORDER BY  AC_TAVDtl.BranchAutoId ASC,IFNULL(AC_TALCOA.code,'')      ASC
,IFNULL(AC_TALCOA1.code,'') ASC
,IFNULL(AC_TALCOA2.code,'') ASC
,IFNULL(AC_TALCOA3.code,'') ASC
,IFNULL(AC_TALCOA4.code,'') ASC
,IFNULL(AC_TALCOA5.code,'') ASC
,IFNULL(AC_TALCOA6.code,'') ASC
,IFNULL(AC_TALCOA7.code,'') ASC
,IFNULL(AC_TALCOA8.code,'') ASC

) table1
LEFT JOIN branch on branch.branch_id=table1.BranchAutoId

GROUP BY table1.BranchAutoId,table1.PARENT_ID,
table1.PN,
table1.PN_Code,
table1.PARENT_ID1,
table1.PN1,
table1.PN1_Code,
table1.PARENT_ID2,
table1.PN2,
table1.PN2_Code";


        $query = $this->db->query($query);
        $result = $query->result();

        foreach ($result as $key => $value) {

            $array[$value->branch_name . '~#@~' . $value->BranchAutoId][$value->PN . '~#@~' . $value->PARENT_ID][$value->PN1_Code . '~#@~' . $value->PN1][] = $value;


        }


        return $array;
    }


    /*$this->db->select('product.product_id,productcategory.title as productCat,product.brand_id,product.category_id,product.productName,product.dist_id,product.status,brand.brandName,unit.unitTtile');
    $this->db->from('product');
    $this->db->join('brand', 'brand.brandId = product.brand_id', 'left');
    $this->db->join('unit', 'unit.unit_id = product.unit_id', 'left');
    $this->db->join('productcategory', 'productcategory.category_id = product.category_id', 'left');
    $this->db->group_start();
    $this->db->where('product.dist_id', $distId);
    $this->db->or_where('product.dist_id', 1);
    $this->db->group_end();
    $this->db->where('product.status', 1);
    if ($catid == 'all') {
    } else {
        $this->db->where('product.category_id', $catid);
    }
    $this->db->order_by('product.productName', 'ASE');
    $getProductList = $this->db->get()->result();
    return $getProductList;*/


    public function get_recive_payment_voucher($voucherType, $startDate, $endDate, $branch)
    {
        $this->db->select('	acm.Accounts_VoucherMst_AutoID,
        acm.AccouVoucherType_AutoID,
        acm.`for`,
        acm.Accounts_Voucher_No,
        acm.Accounts_Voucher_Date,
        acm.BackReferenceInvoiceNo,
        acm.BackReferenceInvoiceID,
        acm.Narration,
        acm.BranchAutoId,
        act.Accounts_VoucherType,
         branch.branch_code,
        branch.branch_name');
        $this->db->from('ac_accounts_vouchermst acm');
        $this->db->join('accounts_vouchertype_autoid act', 'act.Accounts_VoucherType_AutoID=acm.AccouVoucherType_AutoID', 'left');
        $this->db->join('branch ', 'branch.branch_id=acm.BranchAutoId', 'left');
        $this->db->where('1 =', '1');
        $this->db->where('acm.BackReferenceInvoiceID =', '0');
        $this->db->where('acm.AccouVoucherType_AutoID =', $voucherType);
        $this->db->where('acm.Accounts_Voucher_Date >=', $startDate);
        $this->db->where('acm.Accounts_Voucher_Date <=', $endDate);
        if ($branch != 'all') {
            $this->db->where('acm.BranchAutoId <=', $branch);
        }

        $query="SELECT
	acd.voucher_by,
	acm.Accounts_VoucherMst_AutoID,
	acm.AccouVoucherType_AutoID,
	acm.`for`,
	acm.Accounts_Voucher_No,
	acm.miscellaneous,
acm.customer_id,acm.supplier_id,
	acm.Accounts_Voucher_Date,
	acm.BackReferenceInvoiceNo,
	acm.BackReferenceInvoiceID,
	acm.Narration,
	acm.BranchAutoId,
	act.Accounts_VoucherType,
	branch.branch_code,
	branch.branch_name
FROM
	ac_accounts_vouchermst acm
LEFT JOIN accounts_vouchertype_autoid act ON act.Accounts_VoucherType_AutoID = acm.AccouVoucherType_AutoID
LEFT JOIN(
	SELECT
		acd.Accounts_VoucherMst_AutoID,
		GROUP_CONCAT(
			CONCAT(
				acd.CHILD_ID,
				'&^&',
				coa.parent_id,
				'&^&',
				coa.parent_name
			)SEPARATOR '~#*%~'
		)AS voucher_by
	FROM
		ac_tb_accounts_voucherdtl acd
	LEFT JOIN ac_account_ledger_coa coa ON coa.id = acd.CHILD_ID
	WHERE
		1 = 1
	AND coa.parent_id IN(33, 52)
	GROUP BY
		acd.Accounts_VoucherMst_AutoID
)acd ON acd.Accounts_VoucherMst_AutoID = acm.Accounts_VoucherMst_AutoID
LEFT JOIN branch ON branch.branch_id = acm.BranchAutoId
WHERE
	1 = 1
AND acm.Accounts_Voucher_Date >= '2020-01-01'
AND acm.Accounts_Voucher_Date <= '2020-04-02'
AND acm.BackReferenceInvoiceID = 0";
        $query.=" AND acm.AccouVoucherType_AutoID=".$voucherType;

        $query = $this->db->query($query);
        $result = $query->result();
        foreach ($result as $key => $value) {
            $array[$value->branch_name . '~#@~' . $value->BranchAutoId][] = $value;
        }
        return $array;
    }

}
