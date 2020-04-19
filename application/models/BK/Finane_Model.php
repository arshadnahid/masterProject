<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Finane_Model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function checkCustomerExits($cusId, $distId) {
        $this->db->select("*");
        $this->db->from("customer");
        $this->db->where('customer_id', $cusId);
        $this->db->where('dist_id', $distId);
        $exits = $this->db->get()->row();
        if (!empty($exits)):
            return true;
        else:
            return false;
        endif;
    }

    function checkSupplierExits($supplier, $distId) {
        $this->db->select("*");
        $this->db->from("supplier");
        $this->db->where('sup_id', $supplier);
        $this->db->where('dist_id', $distId);
        $exits = $this->db->get()->row();
        if (!empty($exits)):
            return true;
        else:
            return false;
        endif;
    }

    function getImportSupplier($distId) {
        $this->db->select('sup_id,supID,supName');
        $this->db->from('supplier');
        $this->db->where('dist_id', $distId);
        $resutl = $this->db->get()->result();
        $allSup = array();
        foreach ($resutl as $key => $eachSupplier):
            $data = array();
            $data['sl'] = $key + 1;
            $data['sup_id'] = $eachSupplier->sup_id;
            $data['supID'] = $eachSupplier->supID;
            $data['supName'] = $eachSupplier->supName;
            $allSup[] = $data;
        endforeach;
        return $allSup;
    }

    function getImportCustomer($distId) {
        $this->db->select('customer_id,customerID,customerName');
        $this->db->from('customer');
        $this->db->where('dist_id', $distId);
        $resutl = $this->db->get()->result();
        $allCust = array();
        foreach ($resutl as $key => $eachCustomer):
            $data = array();
            $data['sl'] = $key + 1;
            $data['customer_id'] = $eachCustomer->customer_id;
            $data['customerID'] = $eachCustomer->customerID;
            $data['customerName'] = $eachCustomer->customerName;
            $allCust[] = $data;
        endforeach;
        return $allCust;
    }

    function checkOpeningDeleteValid($distId) {
        $voucher = array(1, 2, 3, 5, 11, 14, 19, 29, 30, 7);
        $this->db->select('count(form_id) as totalVoucher');
        $this->db->from('generals');
        $this->db->where('dist_id', $distId);
        $this->db->where_in('form_id', $voucher);
        $resutl = $this->db->get()->row();
        if (!empty($resutl->totalVoucher)):
            return $resutl->totalVoucher;
        else:
            return 0;
        endif;
    }

    function getSupplierPayable($distId) {
        $this->db->select('sum(dr) as totalPrice');
        $this->db->from('client_vendor_ledger');
        $this->db->where('dist_id', $distId);
        $this->db->where('ledger_type', 2);
        $this->db->where('paymentType', 'Opening');
        $resutl = $this->db->get()->row();
        return $resutl;
    }

    function getCustomerReceiable($distId) {
        $this->db->select('sum(dr) as totalPrice');
        $this->db->from('client_vendor_ledger');
        $this->db->where('dist_id', $distId);
        $this->db->where('ledger_type', 1);
        $this->db->where('paymentType', 'Opening');
        $resutl = $this->db->get()->row();
        return $resutl;
    }

    function getInventoroyCylinderStock($distId) {
        $this->db->select('sum(price) as totalPrice');
        $this->db->from('stock');
        $this->db->where('dist_id', $distId);
        $this->db->where('category_id', 1);
        $this->db->where('openingStatus', 1);
        $resutl = $this->db->get()->row();
        return $resutl;
    }

    function getInventoroyProductStock($distId) {
        $this->db->select('sum(price) as totalPrice');
        $this->db->from('stock');
        $this->db->where('dist_id', $distId);
        $this->db->where('category_id !=', 1);
        $this->db->where('openingStatus', 1);
        $resutl = $this->db->get()->row();
        return $resutl;
    }

    function getCusOpe($customer_id, $from, $dist_id) {
        $this->db->select("sum(dr - cr) as totalBalance");
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date <', $from);
        $this->db->where('client_vendor_ledger.ledger_type', 1);
        $this->db->where('client_vendor_ledger.dist_id', $dist_id);
        $this->db->where('client_vendor_ledger.client_vendor_id', $customer_id);
        $query = $this->db->get()->row();
        if (!empty($query->totalBalance)) {
            return $query->totalBalance;
        } else {
            return 0;
        }
    }

    function getSupOpe($supllier_id, $from, $dist_id) {
        $this->db->select("sum(dr - cr) as totalBalance");
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date <', $from);
        $this->db->where('client_vendor_ledger.ledger_type', 2);
        $this->db->where('client_vendor_ledger.dist_id', $dist_id);
        $this->db->where('client_vendor_ledger.client_vendor_id', $supllier_id);
        $this->db->order_by('client_vendor_ledger.date', 'ASC');
        $query = $this->db->get()->row();
        if (!empty($query->totalBalance)) {
            return $query->totalBalance;
        } else {
            return 0;
        }
    }

    function customer_ledger($customer_id, $from, $to, $dist_id) {
        if ($customer_id == 'all') {
            $this->db->select('client_vendor_ledger.client_vendor_id,customer.customerAddress as address,customer.customerPhone as mobile,customer.customerID as customerID,customer.customerName as name,sum(client_vendor_ledger.amount) as charge,sum(client_vendor_ledger.dr) as debit,sum(client_vendor_ledger.cr) as credit,');
            $this->db->join('customer', 'customer.customer_id=client_vendor_ledger.client_vendor_id', 'left');
            $this->db->group_by('client_vendor_id');
        }
        if ($customer_id != 'all') {
            $this->db->where('client_vendor_ledger.client_vendor_id', $customer_id);
        }
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date >=', $from);
        $this->db->where('client_vendor_ledger.date <=', $to);
        $this->db->where('client_vendor_ledger.ledger_type', 1);
        $this->db->where('client_vendor_ledger.dist_id', $dist_id);
        $this->db->order_by('client_vendor_ledger.date', 'ASC');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function getCustomerSummation($customerId, $from, $to, $distId) {
        $this->db->select('sum(client_vendor_ledger.amount) as charge,sum(client_vendor_ledger.dr) as debit,sum(client_vendor_ledger.cr) as credit,sum(client_vendor_ledger.dr - client_vendor_ledger.cr) as totalBal,');
        $this->db->from('client_vendor_ledger');
        $this->db->join('customer', 'customer.customer_id=client_vendor_ledger.client_vendor_id', 'left');
        $this->db->where('client_vendor_ledger.date >=', $from);
        $this->db->where('client_vendor_ledger.date <=', $to);
        $this->db->where('client_vendor_ledger.ledger_type', 1);
        $this->db->where('client_vendor_ledger.dist_id', $distId);
        $this->db->where('client_vendor_ledger.client_vendor_id', $customerId);
        //$this->db->having('sum(client_vendor_ledger.dr - client_vendor_ledger.cr) <= 0 or sum(client_vendor_ledger.dr - client_vendor_ledger.cr) >= 0 ');
        $query = $this->db->get()->row();
        $result['debit'] = $query->debit;
        $result['credit'] = $query->credit;
        $result['charge'] = $query->charge;
        $result['opening'] = $this->getCustomerOpening($customerId, $from, $distId);
        return $result;
    }

    function getCustomerOpening($customer, $from, $distId) {
        $this->db->select('sum(client_vendor_ledger.dr - client_vendor_ledger.cr) as totalBalance');
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date <', $from);
        $this->db->where('client_vendor_ledger.ledger_type', 1);
        $this->db->where('client_vendor_ledger.dist_id', $distId);
        $this->db->where('client_vendor_ledger.client_vendor_id', $customer);
        $query = $this->db->get()->row();
        if (!empty($query->totalBalance)) {
            return $query->totalBalance;
        } else {
            return 0;
        }
    }

    public function getSupplierSummation($supplier, $from, $to, $distId) {
        $this->db->select('sum(client_vendor_ledger.amount) as charge,sum(client_vendor_ledger.dr) as debit,sum(client_vendor_ledger.cr) as credit,sum(client_vendor_ledger.dr - client_vendor_ledger.cr) as totalBal,');
        $this->db->from('client_vendor_ledger');
        $this->db->join('customer', 'customer.customer_id=client_vendor_ledger.client_vendor_id', 'left');
        $this->db->where('client_vendor_ledger.date >=', $from);
        $this->db->where('client_vendor_ledger.date <=', $to);
        $this->db->where('client_vendor_ledger.ledger_type', 2);
        $this->db->where('client_vendor_ledger.dist_id', $distId);
        $this->db->where('client_vendor_ledger.client_vendor_id', $supplier);
        //$this->db->having('sum(client_vendor_ledger.dr - client_vendor_ledger.cr) <= 0 or sum(client_vendor_ledger.dr - client_vendor_ledger.cr) >= 0 ');
        $query = $this->db->get()->row();
        $result['debit'] = $query->debit;
        $result['credit'] = $query->credit;
        $result['charge'] = $query->charge;
        $result['opening'] = $this->getSupplierOpening($supplier, $from, $distId);
        return $result;
    }

    function getSupplierOpening($supplier, $from, $distId) {
        $this->db->select('sum(client_vendor_ledger.dr - client_vendor_ledger.cr) as totalBalance');
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date <', $from);
        $this->db->where('client_vendor_ledger.ledger_type', 2);
        $this->db->where('client_vendor_ledger.dist_id', $distId);
        $this->db->where('client_vendor_ledger.client_vendor_id', $supplier);
        $query = $this->db->get()->row();
        if (!empty($query->totalBalance)) {
            return $query->totalBalance;
        } else {
            return 0;
        }
    }

    function getPaymentId($invoiceId) {
        $this->db->select('generals_id');
        $this->db->from('generals');
        $this->db->where('mainInvoiceId', $invoiceId);
        $result = $this->db->get()->row();
        if (!empty($result->generals_id)) {
            return $result->generals_id;
        }
    }

    function getPaymentLedger($invoiceiD) {
        $this->db->select('*');
        $this->db->from('generalledger');
        $this->db->where('generals_id', $invoiceiD);
        $this->db->where('credit >', 0);
        $result = $this->db->get()->row();
        if (!empty($result)) {
            return $result;
        }
    }

    function getCreditAccountIdRecieveVoucher($distId, $invoiceiD) {
        $this->db->select('*');
        $this->db->from('generalledger');
        $this->db->where('dist_id', $distId);
        $this->db->where('generals_id', $invoiceiD);
        $this->db->where('credit >', 0);
        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result;
        }
    }

    function getCreditAccountId($distId, $invoiceiD) {
        $this->db->select('account');
        $this->db->from('generalledger');
        $this->db->where('dist_id', $distId);
        $this->db->where('generals_id', $invoiceiD);
        $this->db->where('credit >', 0);
        $result = $this->db->get()->row();
        if (!empty($result)) {
            return $result->account;
        }
    }

    function getDebitAccountId($distId, $invoiceiD) {
        $this->db->select('*');
        $this->db->from('generalledger');
        $this->db->where('dist_id', $distId);
        $this->db->where('generals_id', $invoiceiD);
        $this->db->where('debit >', 0);
        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result;
        }
    }

    function getDebitAccountIdReceiveVoucher($distId, $invoiceiD) {
        $this->db->select('account');
        $this->db->from('generalledger');
        $this->db->where('dist_id', $distId);
        $this->db->where('generals_id', $invoiceiD);
        $this->db->where('debit >', 0);
        $result = $this->db->get()->row();
        if (!empty($result->account)) {
            return $result->account;
        }
    }

    function getCustomerOpeningBalance($dist_id) {
        $this->db->select("sum(amount) as totalSum");
        $this->db->from("client_vendor_ledger");
        $this->db->where("ledger_type", 1);
        $this->db->where('dist_id', $dist_id);
        $this->db->where("paymentType", 'Opening');
        $totalOpening = $this->db->get()->row();
        return $totalOpening->totalSum;
    }

    function getSupplierOpeningBalance($dist_id) {
        $this->db->select("sum(amount) as totalSum");
        $this->db->from("client_vendor_ledger");
        $this->db->where("ledger_type", 2);
        $this->db->where('dist_id', $dist_id);
        $this->db->where("paymentType", 'Opening');
        $totalOpening = $this->db->get()->row();
        return $totalOpening->totalSum;
    }

    function accountBalance($code, $fromDate = NULL, $toDate = NULL) {


        $debit = 0;
        $credit = 0;
        $result = 0;
        $open_debit = $this->db->select_sum('debit')->get_where('opening_balance', array('account' => $code, 'dist_id' => $this->dist_id))->row()->debit;
        $open_credit = $this->db->select_sum('credit')->get_where('opening_balance', array('account' => $code, 'dist_id' => $this->dist_id))->row()->credit;
        // Previous Balance - This Period
        $this->db->select("sum(debit) as debit,sum(credit) as credit");
        $this->db->from("generalledger");
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('account', $code);
        if (!empty($fromDate)) {
            $this->db->where('date >=', $fromDate);
        }
        if (!empty($toDate)) {
            $this->db->where('date <=', $toDate);
        }
        $result = $this->db->get()->row();
        $debit += $result->debit;
        $credit += $result->credit;
        $debit += $open_debit;
        $credit += $open_credit;
        $result = $debit - $credit;
        return $result;
    }

    function accountBalanceDebitOrCredit($code) {
        $debit = 0;
        $credit = 0;
        $result = 0;
        $open_debit = $this->db->select_sum('debit')->get_where('opening_balance', array('account' => $code, 'dist_id' => $this->dist_id))->row()->debit;
        $open_credit = $this->db->select_sum('credit')->get_where('opening_balance', array('account' => $code, 'dist_id' => $this->dist_id))->row()->credit;
        // Previous Balance - This Period
        $this->db->select("sum(debit) as debit,sum(credit) as credit");
        $this->db->from("generalledger");
        $this->db->where('dist_id', $this->dist_id);
        $this->db->where('account', $code);
        $result = $this->db->get()->row();
        $debit += $result->debit;
        $credit += $result->credit;
        $debit += $open_debit;
        $credit += $open_credit;
        if (!empty($debit) || !empty($credit)) {
            return true;
        } else {
            return false;
        }
    }

    function generals_supplier($supplier_id) {
        $query = $this->db->get_where('generals', array('form_id' => 11, 'supplier_id' => $supplier_id));
        return $query->result_array();
    }

    function supplier_ledger($suplier_id, $from, $to, $dist_id) {
        if ($suplier_id == 'all') {
            $this->db->select('client_vendor_ledger.client_vendor_id,supplier.supAddress as address,supplier.supPhone as mobile,supplier.supID as customerID,supplier.supName as name,sum(client_vendor_ledger.amount) as charge,sum(client_vendor_ledger.dr) as debit,sum(client_vendor_ledger.cr) as credit,');
            $this->db->join('supplier', 'supplier.sup_id=client_vendor_ledger.client_vendor_id', 'left');
            $this->db->group_by('client_vendor_id');
        }
        if ($suplier_id != 'all') {
            $this->db->where('client_vendor_ledger.client_vendor_id', $suplier_id);
        }
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date >=', $from);
        $this->db->where('client_vendor_ledger.date <=', $to);
        $this->db->where('client_vendor_ledger.ledger_type', 2);
        $this->db->where('client_vendor_ledger.dist_id', $dist_id);
        $this->db->order_by('client_vendor_ledger.date', 'ASC');
        $query = $this->db->get()->result_array();
        return $query;
    }

    function supplier_cus_openingAmount($type, $supID, $fromdate, $distId) {
        $this->db->select('sum(client_vendor_ledger.dr) as totalDr,sum(client_vendor_ledger.cr) as totalcr');
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date <', $fromdate);
        $this->db->where('client_vendor_ledger.ledger_type', $type);
        $this->db->where('client_vendor_ledger.client_vendor_id', $supID);
        $this->db->where('client_vendor_ledger.dist_id', $distId);
        $query = $this->db->get_where()->row_array();
        $balance = $query['totalDr'] - $query['totalcr'];
        return $balance;
    }

    public function getExpenseHead() {
        $condition = array(
            'dist_id' => $this->dist_id,
            'status' => 1,
        );
        $this->db->where_in('parentId', array('4'));
        $getParentList = $this->Common_model->get_data_list_by_many_columns('chartofaccount', $condition);
        //step num 2// get parent menu lsit
        $assetsList = array();
        foreach ($getParentList as $key => $eachValue):
            $getChildList = $this->Common_model->get_data_list_by_single_column('chartofaccount', 'parentId', $eachValue->chart_id);
            //setp num 3 get child menu list
            if (!empty($getChildList)):
                foreach ($getChildList as $key => $childValue):
                    $headList = $this->Common_model->get_data_list_by_single_column('chartofaccount', 'parentId', $childValue->chart_id);
                    if (!empty($headList)):
                        $data['account_info'][$childValue->chart_id] = $headList;
                        foreach ($headList as $key => $value):
                            $assetsList[] = $value;
                        endforeach;
                    else:
                        $data['account_info'][$eachValue->chart_id] = $childValue;
                        $assetsList[] = $childValue;
                    endif;
                endforeach;
            endif;
        //
        //$child $this->Finane_Model->checkParentIdEmpaty($eachValue->chart_id);
        endforeach;
        return $assetsList;
    }

    function opening_balance_dr($dist_id, $account) {
        $query = $this->db->get_where('opening_balance', array('dist_id' => $dist_id, 'account' => $account));
        if (!empty($query->row()->debit)) {
            return $query->row()->debit;
        } else {
            return FALSE;
        }
    }

    function opening_balance_dr_array($dist_id, $account = array()) {
        $allDebit = 0;
        foreach ($account as $eachInfo):
            $query = $this->db->get_where('opening_balance', array('dist_id' => $dist_id, 'account' => $eachInfo))->row()->debit;
            $allDebit+=$query;
        endforeach;
        if (!empty($allDebit)) {
            return $allDebit;
        } else {
            return FALSE;
        }
    }

    function opening_balance_cr_array($dist_id, $account = array()) {
        $allDebit = 0;
        foreach ($account as $eachInfo):
            $query = $this->db->get_where('opening_balance', array('dist_id' => $dist_id, 'account' => $eachInfo))->row()->credit;
            $allDebit+=$query;
        endforeach;
        if (!empty($allDebit)) {
            return $allDebit;
        } else {
            return FALSE;
        }
    }

    function opening_balance_cr($dist_id, $account) {
        $query = $this->db->get_where('opening_balance', array('dist_id' => $dist_id, 'account' => $account));
        if (!empty($query->row()->credit)) {
            return $query->row()->credit;
        } else {
            return FALSE;
        }
    }

    public function getMaxidByCoaID($id = null) {
        $dist_id = $this->session->userdata('dist_id');
        $this->db->select('*')
                ->from('chartofaccount')
                ->where('parentId', $id)
                ->order_by('chart_id', 'DESC')
                ->where('dist_id', $dist_id);
        $chartmaxid = $this->db->get()->row();
        if (!empty($chartmaxid)):
            return $chartmaxid->acc_code;
        else:
            return 0;
        endif;
    }

    public function getTotalheadAccount($id = null) {
        $dist_id = $this->session->userdata('dist_id');
        $this->db->select('*')
                ->from('chartofaccount')
                ->where('parentId', $id)
                ->where('dist_id', $dist_id);
        $chartmaxid = $this->db->get();
        $generals_plus = $chartmaxid->num_rows();
        return $generals_plus;
    }

    function get_chart_list($head, $root, $parent, $child, $dis_id) {
        if (!empty($root) && empty($parent) && empty($child)):
            $this->db->select("*");
            $this->db->from("chartofaccount");
            $this->db->where('parentId', $root);
            $this->db->group_start();
            $this->db->where('dist_id', $dis_id);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            $result = $this->db->get()->result();
            return $result;
        elseif (!empty($root) && !empty($parent) && empty($child)):
            $this->db->select("*");
            $this->db->from("chartofaccount");
            $this->db->where('parentId', $parent);
            $this->db->group_start();
            $this->db->where('dist_id', $dis_id);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            $result = $this->db->get()->result();
            return $result;
        else:
            $this->db->select("*");
            $this->db->from("chartofaccount");
            $this->db->where('parentId', $child);
            $this->db->group_start();
            $this->db->where('dist_id', $dis_id);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            $result = $this->db->get()->result();
            return $result;
        endif;
    }

    function checkDuplicateHead($headTitle, $rootid = NULL, $parentid = NULL, $childid = NULL, $dist_id) {
        $exitsData = 0;
        if (!empty($rootid) && empty($parentid) && empty($childid)):
            $this->db->select("*");
            $this->db->from('chartofaccount');
            $this->db->where('parentId', $rootid);
            $this->db->where('title', $headTitle);
            $this->db->group_start();
            $this->db->where('dist_id', $dist_id);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            $exitsData = $this->db->get()->row();
            return $exitsData;
        elseif (!empty($rootid) && !empty($parentid) && empty($childid)):
            $this->db->select("*");
            $this->db->from('chartofaccount');
            $this->db->where('parentId', $parentid);
            $this->db->where('title', $headTitle);
            $this->db->group_start();
            $this->db->where('dist_id', $dist_id);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            $exitsData = $this->db->get()->row();
            return $exitsData;
        else:
            $this->db->select("*");
            $this->db->from('chartofaccount');
            $this->db->where('parentId', $childid);
            $this->db->where('title', $headTitle);
            $this->db->group_start();
            $this->db->where('dist_id', $dist_id);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            $exitsData = $this->db->get()->row();
            return $exitsData;
        endif;
    }

    function getChartList() {
        $this->db->select("*");
        $this->db->from("chartofaccount");
        $this->db->where('rootId !=', 0);
        $this->db->group_start();
        $this->db->where('dist_id', $this->dist_id);
        $this->db->or_where('common', 1);
        $this->db->group_end();
        return $this->db->get()->result();
    }

    function getChartListTree($rootId, $parentId, $chaildId, $distId) {


        if (!empty($rootId) && empty($parentId) && empty($chaildId)) {
            $this->db->select("*");
            $this->db->from("chartofaccount");
            $this->db->where('parentId', $rootId);
            $this->db->group_start();
            $this->db->where('dist_id', $distId);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            return $this->db->get()->result();
        } elseif (!empty($rootId) && !empty($parentId) && empty($chaildId)) {
            $this->db->select("*");
            $this->db->from("chartofaccount");
            $this->db->where('parentId', $parentId);
            $this->db->group_start();
            $this->db->where('dist_id', $distId);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            return $this->db->get()->result();
        } elseif (!empty($rootId) && !empty($parentId) && !empty($chaildId)) {
            $this->db->select("*");
            $this->db->from("chartofaccount");
            $this->db->where('parentId', $chaildId);
            $this->db->group_start();
            $this->db->where('dist_id', $distId);
            $this->db->or_where('common', 1);
            $this->db->group_end();
            return $this->db->get()->result();
        }
    }

    function getChartListbyId($parentID) {
        $this->db->select("*");
        $this->db->from("chartofaccount");
        if (!empty($parentID)) {
            $this->db->where('parentId', $parentID);
        }
        $this->db->group_start();
        $this->db->where('dist_id', $this->dist_id);
        $this->db->or_where('common', 1);
        $this->db->group_end();
        return $this->db->get()->result_array();
    }

    function getCustomerID($distributorid) {
        $cusOrgId = $this->db->where('dist_id', $distributorid)->count_all_results('customer') + 1;
        $CustomerGeneratedID = "CID" . date("y") . date("m") . str_pad($cusOrgId, 4, "0", STR_PAD_LEFT);
        return $CustomerGeneratedID;
    }

    public function checkDuplicateCusID($customerGeneratedID, $distributorid) {
        $checkExits = $this->db->get_where('customer', array('dist_id' => $distributorid, 'customerID' => $customerGeneratedID))->row();
        if (!empty($checkExits)) {
            $supOriId = $this->db->where('dist_id', $distributorid)->count_all_results('customer') + 2;
            return $newID = "CID" . date("y") . date("m") . str_pad($supOriId, 4, "0", STR_PAD_LEFT);
        } else {
            if (!empty($customerGeneratedID)) {
                return $customerGeneratedID;
            }
        }
    }

}

?>