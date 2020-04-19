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


       function getDebitAccountIdReceiveVoucherNew($distId, $invoiceiD) {
        $this->db->select('CHILD_ID');
        $this->db->from('ac_tb_accounts_voucherdtl');
        // $this->db->where('dist_id', $distId);
        $this->db->where('Accounts_VoucherMst_AutoID', $invoiceiD);
        $this->db->where('GR_DEBIT >', 0);
        $result = $this->db->get()->row();
        if (!empty($result)) {
            return $result->CHILD_ID;
        }
    }


       function getCreditAccountIdRecieveVoucherNew($distId, $invoiceiD)
    {
        $this->db->select('*');
        $this->db->from('ac_tb_accounts_voucherdtl');
        // $this->db->where('dist_id', $distId);
        $this->db->where('Accounts_VoucherMst_AutoID', $invoiceiD);
        $this->db->where('GR_CREDIT >', 0);
        $this->db->where('IsActive', 1);
        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result;
        }
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
        //$voucher = array(1, 2, 3, 5, 11, 14, 19, 29, 30, 7);
        $this->db->select('count(*) as totalVoucher');
        $this->db->from('opening_balance');
        //$this->db->where('dist_id', $distId);
        //$this->db->where_in('form_id', $voucher);
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
    function getSupplierPayable2($client_vendor_id) {
        $this->db->select('dr as totalPrice');
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_id', $client_vendor_id);
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
    function getCustomerReceiable2($client_vendor_id) {
        $this->db->select('dr as totalPrice');
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_id', $client_vendor_id);
        $this->db->where('ledger_type', 1);
        $this->db->where('paymentType', 'Opening');
        $resutl = $this->db->get()->row();
        return $resutl;
    }

       function getCreditAccountIdNew($distId, $invoiceiD)
    {
        $this->db->select('CHILD_ID');
        $this->db->from('ac_tb_accounts_voucherdtl');
        //$this->db->where('dist_id', $distId);
        $this->db->where('Accounts_VoucherMst_AutoID', $invoiceiD);
        $this->db->where('GR_CREDIT >', 0);
        $result = $this->db->get()->row();
        if (!empty($result)) {
            return $result->CHILD_ID;
        }
    }

    function getDebitAccountIdNew($distId, $invoiceiD)
    {
        $this->db->select('*');
        $this->db->from('ac_tb_accounts_voucherdtl');
        // $this->db->where('dist_id', $distId);
        $this->db->where('Accounts_VoucherMst_AutoID', $invoiceiD);
        $this->db->where('GR_DEBIT >', 0);
        $this->db->where('IsActive', 1);
        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result;
        }
    }


    function getAccountHeadNew()
    {
        $this->db->select('parent_id');
        $this->db->from("ac_account_ledger_coa");
        $this->db->where('posted', 1);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result();
        foreach ($result as $key => $eachID) {
            $condition = array(
                //'dist_id' => $this->dist_id,
                'parent_id' => $eachID->parent_id,
            );
            $data[$eachID->parent_id]['parentName'] = $this->get_single_data_by_single_column('ac_account_ledger_coa', 'id', $eachID->parent_id)->parent_name;
            $data[$eachID->parent_id]['Accountledger'] = $this->get_data_list_by_many_columns('ac_account_ledger_coa', $condition);
        }
        return $data;
    }

    function getInventoroyCylinderStock($distId) {
        $query="SELECT
SUM(purchase_details.quantity*purchase_details.unit_price) AS totalPrice
FROM
	purchase_details
LEFT JOIN product 
ON product.product_id=purchase_details.product_id
WHERE
	is_active = 'Y'
AND is_delete = 'N'
AND product.category_id  IN(1)";

        $query = $this->db->query($query);
        $result = $query->row();
        return $result;
        /*$this->db->select('sum(price) as totalPrice');
        $this->db->from('stock');
        $this->db->where('dist_id', $distId);
        $this->db->where('category_id', 1);
        $this->db->where('openingStatus', 1);
        $resutl = $this->db->get()->row();
        return $resutl;*/
    }
    function getInventoroyCylinderStock2($product_id) {
        $query="SELECT
SUM(purchase_details.quantity*purchase_details.unit_price) AS totalPrice
FROM
	purchase_details
LEFT JOIN product 
ON product.product_id=purchase_details.product_id
WHERE
	purchase_details.is_active = 'Y'
AND purchase_details.is_delete = 'N'
AND purchase_details.is_opening = 1
AND purchase_details.product_id = ".$product_id."
";

        $query = $this->db->query($query);
        $result = $query->row();
        return $result;

    }
    function getInventoroyCylinderStockOnlyCylinder2($product_id) {
        $query="SELECT
SUM(purchase_details.quantity*purchase_details.unit_price) AS totalPrice
FROM
	purchase_details
LEFT JOIN product 
ON product.product_id=purchase_details.product_id
WHERE
	purchase_details.is_active = 'Y'
AND purchase_details.is_delete = 'N'
AND purchase_details.is_opening = 1
AND purchase_details.is_opening = 1
AND purchase_details.product_id = ".$product_id."
";

        $query = $this->db->query($query);
        $result = $query->row();
        return $result;

    }
    function getInventoroyRefillStock($distId) {
        $query="SELECT
SUM(purchase_details.quantity*purchase_details.unit_price) AS totalPrice
FROM
	purchase_details
LEFT JOIN product 
ON product.product_id=purchase_details.product_id
WHERE
	is_active = 'Y'
AND is_delete = 'N'
AND product.category_id  IN(2)";

        $query = $this->db->query($query);
        $result = $query->row();
        return $result;
        /*$this->db->select('sum(price) as totalPrice');
        $this->db->from('stock');
        $this->db->where('dist_id', $distId);
        $this->db->where('category_id', 1);
        $this->db->where('openingStatus', 1);
        $resutl = $this->db->get()->row();
        return $resutl;*/
    }
    function getInventoroyRefillStock2($product_id) {
        $query="SELECT
SUM(purchase_details.quantity*purchase_details.unit_price) AS totalPrice
FROM
	purchase_details
LEFT JOIN product 
ON product.product_id=purchase_details.product_id
WHERE
	is_active = 'Y'
AND is_delete = 'N'
AND purchase_details.product_id = ".$product_id."
";

        $query = $this->db->query($query);
        $result = $query->row();
        return $result;
        /*$this->db->select('sum(price) as totalPrice');
        $this->db->from('stock');
        $this->db->where('dist_id', $distId);
        $this->db->where('category_id', 1);
        $this->db->where('openingStatus', 1);
        $resutl = $this->db->get()->row();
        return $resutl;*/
    }

    function getInventoroyProductStock($distId) {

$query="SELECT
SUM(purchase_details.quantity*purchase_details.unit_price) AS totalPrice
FROM
	purchase_details
LEFT JOIN product 
ON product.product_id=purchase_details.product_id
WHERE
	is_active = 'Y'
AND is_delete = 'N'
AND product.category_id NOT IN(1)";

        $query = $this->db->query($query);
        $result = $query->row();
        return $result;

       /* $this->db->select('sum(price) as totalPrice');
        $this->db->from('stock');
        $this->db->where('dist_id', $distId);
        $this->db->where('category_id !=', 1);
        $this->db->where('openingStatus', 1);
        $resutl = $this->db->get()->row();
        return $resutl;*/
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

    public function getCustomerSummation($customerId, $from, $to, $distId,$branch_id='all') {
        $this->db->select('branch.branch_name,sum(client_vendor_ledger.amount) as charge,sum(client_vendor_ledger.dr) as debit,sum(client_vendor_ledger.cr) as credit,sum(client_vendor_ledger.dr - client_vendor_ledger.cr) as totalBal,');
        $this->db->from('client_vendor_ledger');
        $this->db->join('customer', 'customer.customer_id=client_vendor_ledger.client_vendor_id', 'left');
        $this->db->join('branch', 'branch.branch_id=client_vendor_ledger.BranchAutoId', 'left');
        $this->db->where('client_vendor_ledger.date >=', $from);
        $this->db->where('client_vendor_ledger.date <=', $to);
        $this->db->where('client_vendor_ledger.ledger_type', 1);

        $this->db->where('client_vendor_ledger.client_vendor_id', $customerId);
        if($branch_id!='all'){
            $this->db->where('client_vendor_ledger.BranchAutoId', $branch_id);
        }
        $this->db->group_by('client_vendor_ledger.client_vendor_id');
        $this->db->group_by('client_vendor_ledger.BranchAutoId');
        //$this->db->having('sum(client_vendor_ledger.dr - client_vendor_ledger.cr) <= 0 or sum(client_vendor_ledger.dr - client_vendor_ledger.cr) >= 0 ');
        $query = $this->db->get()->row();
        log_message('error','thi is a query'.print_r($this->db->last_query(),TRUE));
        $result['debit'] = $query->debit;
        $result['credit'] = $query->credit;
        $result['charge'] = $query->charge;
        $result['opening'] = $this->getCustomerOpening($customerId, $from, $distId);
        return $result;
    }

    function getCustomerOpening($customer, $from,$branch_id='all') {
        $this->db->select('sum(client_vendor_ledger.dr - client_vendor_ledger.cr) as totalBalance');
        $this->db->from('client_vendor_ledger');
        $this->db->where('client_vendor_ledger.date <', $from);
        $this->db->where('client_vendor_ledger.ledger_type', 1);
        if($branch_id!='all'){
            $this->db->where('client_vendor_ledger.BranchAutoId', $branch_id);
        }
        //$this->db->where('client_vendor_ledger.dist_id', $distId);
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
        $this->db->select("sum(GR_DEBIT) as debit,sum(GR_CREDIT) as credit");
        $this->db->from("ac_tb_accounts_voucherdtl");
        $this->db->where('IsActive', 1);
        $this->db->where('CHILD_ID', $code);
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

    function opening_balance_dr($dist_id, $account,$branchId=null, $from_date, $to_date) {

        $this->db->select('sum(ac_tb_accounts_voucherdtl.GR_DEBIT) as GR_DEBIT');
        $this->db->from('ac_tb_accounts_voucherdtl');
        $this->db->where('ac_tb_accounts_voucherdtl.date <', $from_date);
        $this->db->where('ac_tb_accounts_voucherdtl.IsActive', 1);
        $query = $this->db->get_where()->row();
        //$balance = $query['totalDr'] - $query['totalcr'];


        //$query = $this->db->get_where('opening_balance', array('dist_id' => $dist_id, 'account' => $account));
        if (!empty($query->GR_DEBIT)) {
            return $query->GR_DEBIT;
        } else {
            return FALSE;
        }



        //$query = $this->db->get_where('opening_balance', array('dist_id' => $dist_id, 'account' => $account));
       /* $query = $this->db->get_where('opening_balance', array('account' => $account));
        if (!empty($query->row()->debit)) {
            return $query->row()->debit;
        } else {
            return FALSE;
        }*/
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

    function opening_balance_cr($dist_id, $account,$branchId=null, $from_date, $to_date) {

        $this->db->select('sum(ac_tb_accounts_voucherdtl.GR_CREDIT) as GR_CREDIT');
        $this->db->from('ac_tb_accounts_voucherdtl');
        $this->db->where('ac_tb_accounts_voucherdtl.date <', $from_date);
        $this->db->where('ac_tb_accounts_voucherdtl.IsActive', 1);
        $query = $this->db->get_where()->row();
        //$balance = $query['totalDr'] - $query['totalcr'];


        //$query = $this->db->get_where('opening_balance', array('dist_id' => $dist_id, 'account' => $account));
        if (!empty($query->GR_CREDIT)) {
            return $query->GR_CREDIT;
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
    function get_general_ledger_data($branch_id,$account,$from_date,$to_date){


       /* SELECT
	branch.branch_id,
	branch.branch_code,
	branch.branch_name,
	tran.sup_id,
	tran.customer_id,
	tran.miscellaneous,
	tran.Narration,
	tran.customerName,
	tran.customerID,
	tran.supName,
	tran.FOR,
	tran.BackReferenceInvoiceID,
	tran.BackReferenceInvoiceNo,
	tran.supID,
	tran.Accounts_Voucher_No,
	tran.GR_DEBIT,
	tran.GR_CREDIT,
	tran.date,
	tran.CHILD_ID,
	tran.Reference
FROM
	branch
LEFT JOIN(
            SELECT
		branch.branch_id,
		`supplier`.`sup_id`,
		`customer`.`customer_id`,
		`ac_accounts_vouchermst`.`miscellaneous`,
		`ac_accounts_vouchermst`.`Narration`,
		`customer`.`customerName`,
		`customer`.`customerID`,
		`supplier`.`supName`,
		`ac_accounts_vouchermst`.`for`,
		`ac_accounts_vouchermst`.`BackReferenceInvoiceID`,
		`ac_accounts_vouchermst`.`BackReferenceInvoiceNo`,
		`supplier`.`supID`,
		`ac_accounts_vouchermst`.`Accounts_Voucher_No`,
		`ac_tb_accounts_voucherdtl`.`GR_DEBIT`,
		`ac_tb_accounts_voucherdtl`.`GR_CREDIT`,
		`ac_tb_accounts_voucherdtl`.`date`,
		`ac_tb_accounts_voucherdtl`.`CHILD_ID`,
		`ac_accounts_vouchermst`.`Reference`
	FROM
		`ac_tb_accounts_voucherdtl`
	LEFT JOIN `ac_accounts_vouchermst` ON `ac_accounts_vouchermst`.`Accounts_VoucherMst_AutoID` = `ac_tb_accounts_voucherdtl`.`Accounts_VoucherMst_AutoID`
	LEFT JOIN `customer` ON `customer`.`customer_id` = `ac_accounts_vouchermst`.`customer_id`
	LEFT JOIN `supplier` ON `supplier`.`sup_id` = `ac_accounts_vouchermst`.`supplier_id`
	LEFT JOIN branch ON branch.branch_id = ac_tb_accounts_voucherdtl.BranchAutoId
	WHERE
		`ac_tb_accounts_voucherdtl`.`BranchAutoId` = '1'
        AND `ac_tb_accounts_voucherdtl`.`CHILD_ID` = '107'
        AND `ac_tb_accounts_voucherdtl`.`date` >= '2020-03-01'
        AND `ac_tb_accounts_voucherdtl`.`date` <= '2020-03-11'
        AND `ac_tb_accounts_voucherdtl`.`IsActive` = 1 -- GROUP BY ac_tb_accounts_voucherdtl.BranchAutoId,`ac_tb_accounts_voucherdtl`.`CHILD_ID`
	ORDER BY
		`ac_tb_accounts_voucherdtl`.`date` ASC
)tran ON tran.branch_id = branch.branch_id
WHERE
	branch.is_active = "1"
ORDER BY
	branch.branch_name*/



        $this->db->select("supplier.sup_id,
                                customer.customer_id,
                                ac_accounts_vouchermst.miscellaneous,
                                ac_accounts_vouchermst.Narration,
                                customer.customerName,customer.customerID,
                                supplier.supName,
                              ac_accounts_vouchermst.for,
                              ac_accounts_vouchermst.BackReferenceInvoiceID,
                              ac_accounts_vouchermst.BackReferenceInvoiceNo,
                                supplier.supID,
                                ac_accounts_vouchermst.Accounts_Voucher_No,
                                ac_tb_accounts_voucherdtl.GR_DEBIT,
                                ac_tb_accounts_voucherdtl.GR_CREDIT,
                                ac_tb_accounts_voucherdtl.date,
                                ac_tb_accounts_voucherdtl.CHILD_ID,
                                ac_accounts_vouchermst.Reference
                                ");
        $this->db->from('ac_tb_accounts_voucherdtl');
        $this->db->join('ac_accounts_vouchermst', 'ac_accounts_vouchermst.Accounts_VoucherMst_AutoID=ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID', 'left');
        // $this->db->join('form', 'form.form_id=generals.form_id', 'left');
        $this->db->join('customer', 'customer.customer_id=ac_accounts_vouchermst.customer_id', 'left');
        $this->db->join('supplier', 'supplier.sup_id=ac_accounts_vouchermst.supplier_id', 'left');
        $this->db->where('ac_tb_accounts_voucherdtl.BranchAutoId', $branch_id);
        $this->db->where('ac_tb_accounts_voucherdtl.CHILD_ID', $account);
        $this->db->where('ac_tb_accounts_voucherdtl.date >=', $from_date);
        $this->db->where('ac_tb_accounts_voucherdtl.date <=', $to_date);
        $this->db->where('ac_tb_accounts_voucherdtl.IsActive', 1);
        $this->db->order_by('ac_tb_accounts_voucherdtl.date', 'ASC');
        $query = $this->db->get()->result_array();
        log_message('error',"this is general ledget query".print_r($this->db->last_query(),true));
        return $query;
    }
    public function getPaymentReceiveVoucherReport($branch_id,$type, $fromDate, $toDate, $disId) {
        $this->db->select("accounts_vouchertype_autoid.Accounts_VoucherType,
        ac_accounts_vouchermst.Accounts_Voucher_No,ac_accounts_vouchermst.Accounts_VoucherMst_AutoID,
        ac_accounts_vouchermst.Accounts_Voucher_Date,
        customer.customerID,customer.customerName,
        supplier.supName,supplier.supID,
        ac_accounts_vouchermst.miscellaneous,
        ac_accounts_vouchermst.BackReferenceInvoiceNo,
        ac_accounts_vouchermst.BackReferenceInvoiceID,
        SUM(ac_tb_accounts_voucherdtl.GR_DEBIT) as debit,
        branch.branch_name");
        $this->db->from('ac_accounts_vouchermst');
        $this->db->join('branch', 'branch.branch_id = ac_accounts_vouchermst.BranchAutoId', 'left');
        $this->db->join('accounts_vouchertype_autoid', 'accounts_vouchertype_autoid.Accounts_VoucherType_AutoID = ac_accounts_vouchermst.AccouVoucherType_AutoID', 'left');
        $this->db->join('customer', 'customer.customer_id = ac_accounts_vouchermst.customer_id', 'left');
        $this->db->join('supplier', 'supplier.sup_id = ac_accounts_vouchermst.supplier_id', 'left');
        $this->db->join('ac_tb_accounts_voucherdtl', 'ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID = ac_accounts_vouchermst.Accounts_VoucherMst_AutoID', 'left');
        if($branch_id!='all'){
            $this->db->where('ac_accounts_vouchermst.BranchAutoId', $branch_id);
        }

        /*$this->db->where('ac_accounts_vouchermst.BackReferenceInvoiceID ', 0);*/
        $this->db->where('ac_accounts_vouchermst.AccouVoucherType_AutoID', $type);
        $this->db->where('ac_accounts_vouchermst.Accounts_Voucher_Date >=', $fromDate);
        $this->db->where('ac_accounts_vouchermst.Accounts_Voucher_Date <=', $toDate);
        $this->db->where('ac_tb_accounts_voucherdtl.IsActive !=', 0);
        $this->db->group_by('ac_tb_accounts_voucherdtl.Accounts_VoucherMst_AutoID');
        $this->db->order_by('ac_accounts_vouchermst.Accounts_Voucher_Date', 'ASC');
        $this->db->order_by(' branch.branch_name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_recive_payment_voucher($branch,$voucherType, $startDate, $endDate)
    {


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
    cc.customerName,
    cu.supName,
    branch.branch_code,
    SUM(aav.GR_DEBIT) as debit,
    branch.branch_name
FROM
    ac_accounts_vouchermst acm
LEFT JOIN accounts_vouchertype_autoid act ON act.Accounts_VoucherType_AutoID = acm.AccouVoucherType_AutoID
LEFT JOIN customer cc ON cc.customer_id = acm.customer_id
LEFT JOIN supplier cu ON cu.sup_id = acm.supplier_id
LEFT JOIN ac_tb_accounts_voucherdtl aav ON aav.Accounts_VoucherMst_AutoID = acm.Accounts_VoucherMst_AutoID
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
            )SEPARATOR '#*%'
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
AND acm.Accounts_Voucher_Date >= '$startDate'
AND acm.Accounts_Voucher_Date <= '$endDate' AND acm.BackReferenceInvoiceID = 0
";
        $query.=" AND acm.AccouVoucherType_AutoID=".$voucherType;

        $query.=" GROUP BY acm.Accounts_VoucherMst_AutoID";
        $query = $this->db->query($query);

        $result = $query->result();
              log_message('error',"this is voucher query".print_r($this->db->last_query(),true));
        foreach ($result as $key => $value) {
            $array[$value->branch_name . '#@' . $value->BranchAutoId][] = $value;
            // $array[$value->branch_name][$value->BranchAutoId][] = $value;
        }
        return $array;

    }
}

?>