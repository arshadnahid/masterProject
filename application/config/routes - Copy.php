<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* import setup start */
$route['setupImport'] = 'ImportController/setupImport';
$route['productImport'] = 'ImportController/productImport';
$route['demoImport'] = 'VoucherController/demoImport';
$route['demolist'] = 'VoucherController/demolist';
$route['PurchaseDemoConfirm/(:any)'] = 'VoucherController/PurchaseDemoConfirm/$1';
$route['salesImport'] = 'VoucherController/salesImport';
$route['addSalesImport'] = 'VoucherController/addSalesImport';
$route['salesImportConfirm/(:any)'] = 'VoucherController/salesImportConfirm/$1';
$route['financeImport'] = 'VoucherController/financeImport';
$route['financeImportAdd'] = 'VoucherController/financeImportAdd';

$route['paymentVoucherPosting/(:any)'] = 'VoucherController/paymentVoucherPosting/$1';
$route['receiveVoucherPosting/(:any)'] = 'VoucherController/receiveVoucherPosting/$1';
$route['journalVoucherPosting/(:any)'] = 'VoucherController/journalVoucherPosting/$1';

/* import setup end */
/* Admin module start */


$route['adminProductAdd'] = 'admin/AdminSettings/adminProductAdd';
$route['adminUpdateProduct/(:any)'] = 'admin/AdminSettings/adminUpdateProduct/$1';
$route['adminProuductList'] = 'admin/AdminSettings/adminProuductList';
$route['adminProductImport'] = 'admin/AdminSettings/adminProductImport';
//admin product cat
$route['adminProductCatAdd'] = 'admin/AdminSettings/adminProductCatAdd';
$route['adminUpdateProductCat/(:any)'] = 'admin/AdminSettings/adminUpdateProductCat/$1';
$route['adminProuductCatList'] = 'admin/AdminSettings/adminProuductCatList';
//admin brand add
$route['adminBrandAdd'] = 'admin/AdminSettings/adminBrandAdd';
$route['adminBrandUpdate/(:any)'] = 'admin/AdminSettings/adminBrandUpdate/$1';
$route['adminBrandList'] = 'admin/AdminSettings/adminBrandList';
//admin unit add
$route['adminUnitAdd'] = 'admin/AdminSettings/adminUnitAdd';
$route['adminUnitUpdate/(:any)'] = 'admin/AdminSettings/adminUnitUpdate/$1';
$route['adminUnitList'] = 'admin/AdminSettings/adminUnitList';
//admin brand add
$route['adminSupAdd'] = 'admin/AdminSettings/adminSupAdd';
$route['adminSupEdit/(:any)'] = 'admin/AdminSettings/adminSupEdit/$1';
$route['adminSupList'] = 'admin/AdminSettings/adminSupList';


$route['adminlogin'] = 'admin/AdminAuthController';
$route['adminDashboard'] = 'admin/AdminDashboard/adminDashboard';
//distributor Report
$route['distributor_add'] = 'admin/AdminSettings/distributor_add';
$route['distributor'] = 'admin/AdminSettings/distributor';
$route['distributor_edit/(:any)'] = 'admin/AdminSettings/distributor_edit/$1';
//zone Add
$route['zone_add'] = 'admin/AdminSettings/zone_add';
$route['zone'] = 'admin/AdminSettings/zone';
$route['zone_edit/(:any)'] = 'admin/AdminSettings/zone_edit/$1';
//District Add
$route['viewDistributor/(:any)'] = 'admin/AdminSettings/viewDistributor/$1';
$route['district_add'] = 'admin/AdminSettings/district_add';
$route['district_list'] = 'admin/AdminSettings/district_list';
$route['district_edit/(:any)'] = 'admin/AdminSettings/district_edit/$1';
//offer Add
$route['offer_add'] = 'admin/AdminSettings/offer_add';
$route['offer'] = 'admin/AdminSettings/offer_';
$route['offer_edit/(:any)'] = 'admin/AdminSettings/offer_edit/$1';
//message Add
$route['inbox_add'] = 'admin/AdminSettings/inbox_add';
$route['inbox'] = 'admin/AdminSettings/inbox';
$route['inbox_edit/(:any)'] = 'admin/AdminSettings/inbox_edit/$1';

//user info

$route['profile'] = 'admin/AdminSettings/profile';
$route['updateProfile'] = 'admin/AdminSettings/updateProfile';
$route['change_Admin_password'] = 'admin/AdminSettings/change_Admin_password';

//head office Report
$route['disPurchasesReport'] = 'admin/AdminReportController/disPurchasesReport';
$route['disStockReport'] = 'admin/AdminReportController/disStockReport';
$route['disSalesReport'] = 'admin/AdminReportController/disSalesReport';
$route['disSalesReportView/(:any)'] = 'admin/AdminReportController/disSalesReportView/$1';
$route['adminViewPurchases/(:any)'] = 'admin/AdminReportController/adminViewPurchases/$1';
$route['adminViewPurchases/(:any)'] = 'admin/AdminReportController/adminViewPurchases/$1';
$route['getDistributorAccess'] = 'admin/AdminReportController/getDistributorAccess';
$route['dbBackup'] = 'admin/AdminSettings/dbBackup';

/* Admin module end */
/* Admin Login end */

$route['default_controller'] = 'AuthController';
$route['login'] = 'AuthController/login';
//home controller
/*$route['DistributorDashboard'] = 'HomeController/moduleDashboard';
$route['DistributorDashboard/(:any)'] = 'HomeController/dashboard/$1';*/
$route['userAccess'] = 'HomeController/userAccess';

$route['adminLoginHistory'] = 'HomeController/adminLoginHistory';
$route['insert_menu_accessList'] = 'HomeController/insert_menu_accessList';


/* setup controller start */

$route['userMessageList'] = 'SetupController/userMessageList';
$route['userAllOfferList'] = 'SetupController/userAllOfferList';
$route['getAllMessage'] = 'SetupController/getAllMessage';
$route['incentiveList'] = 'SetupController/incentiveList';
$route['userList'] = 'SetupController/userList';
$route['addUser'] = 'SetupController/addUser';
$route['editUser/(:any)'] = 'SetupController/editUser/$1)';
$route['decision'] = 'SetupController/decision_tools';
$route['save_decision'] = 'SetupController/save_decision_tools';
$route['compare_decision'] = 'SetupController/compare_decision';
$route['distributor_profile'] = 'SetupController/distributor_profile';
$route['updatePassword/(:any)'] = 'SetupController/updatePassword/$1';
$route['change_password'] = 'SetupController/change_password';
$route['newDecision'] = 'SetupController/newDecision';
$route['newDecisionList'] = 'SetupController/newDecisionList';
$route['SystemConfig'] = 'SetupController/SystemConfig';

//vehicle
$route['vehicleList'] = 'SetupController/vehicleList';
$route['vehicleAdd'] = 'SetupController/vehicleAdd';
$route['vehicleEdit/(:any)'] = 'SetupController/vehicleEdit/$1)';
$route['vehicleDelete/(:any)'] = 'SetupController/vehicleDelete/$1)';
//employee
$route['employeeList'] = 'SetupController/employeeList';
$route['employeeAdd'] = 'SetupController/employeeAdd';
$route['employeeEdit/(:any)'] = 'SetupController/employeeEdit/$1)';
$route['employeeDelete/(:any)'] = 'SetupController/employeeDelete/$1)';

/* setup controller end */


/* inventory  controller start */

$route['Supplier'] = 'InventoryController/Supplier';
$route['supplierList'] = 'InventoryController/supplierList';
$route['productList'] = 'InventoryController/productList';
$route['addProduct'] = 'InventoryController/addProduct';
$route['updateProduct/(:any)'] = 'InventoryController/updateProduct/$1';
$route['productCatList'] = 'InventoryController/productCatList';
$route['deleteInventorySetup/(:any)'] = 'InventoryController/deleteInventorySetup/$1';
$route['deleteProductCategory/(:any)'] = 'InventoryController/deleteProductCategory/$1';
$route['deleteProduct/(:any)'] = 'InventoryController/deleteProduct/$1';
$route['deleteBrand/(:any)'] = 'InventoryController/deleteBrand/$1';
$route['deleteUnit/(:any)'] = 'InventoryController/deleteUnit/$1';
$route['addProductCat'] = 'InventoryController/addProductCat';
$route['updateProductCat/(:any)'] = 'InventoryController/updateProductCat/$1';


$route['cylinderSummaryReport'] = 'InventoryController/cylinderSummaryReport';
$route['cylinderDetailsReport'] = 'InventoryController/cylinderDetailsReport';
$route['cylinderLedger'] = 'InventoryController/cylinderLedger';
$route['cylinderInOutJournal'] = 'InventoryController/cylinderInOutJournal';
$route['cylinderInOutJournalAdd'] = 'InventoryController/cylinderInOutJournalAdd';
$route['cylinderInOutJournalView/(:any)'] = 'InventoryController/cylinderInOutJournalView/$1';
$route['cylinderInOutJournalEdit/(:any)'] = 'InventoryController/cylinderInOutJournalEdit/$1';
$route['cylinderOpening'] = 'InventoryController/cylinderOpening';
$route['cylinderOpeningAdd'] = 'InventoryController/cylinderOpeningAdd';
$route['cylinderOpeningView/(:any)'] = 'InventoryController/cylinderOpeningView/$1';
$route['cylinderStockReport'] = 'InventoryController/cylinderStockReport';
$route['viewMoneryReceiptSup/(:any)'] = 'InventoryController/viewMoneryReceiptSup/$1';
$route['viewMoneryReceiptSup/(:any)/(:any)'] = 'InventoryController/viewMoneryReceiptSup/$1/$2';
$route['productWisePurchasesReport'] = 'InventoryController/productWisePurchasesReport';
$route['newCylinderStockReport'] = 'InventoryController/newCylinderStockReport';
$route['lowInventoryReport'] = 'InventoryController/lowInventoryReport';
$route['productWiseCylinderStock'] = 'InventoryController/productWiseCylinderStock';
$route['productLedger'] = 'InventoryController/productLedger';
$route['brandWiseProfit'] = 'InventoryController/brandWiseProfit';


$route['unit'] = 'InventoryController/unit';
$route['unitEdit/(:any)'] = 'InventoryController/unitEdit/$1';
$route['supplierUpdate/(:any)'] = 'InventoryController/supplierUpdate/$1';
$route['unitAdd'] = 'InventoryController/unitAdd';

$route['productBarcode'] = 'InventoryController/productBarcode';
$route['categoryReport'] = 'InventoryController/categoryReport';
$route['supplierDashboard/(:any)'] = 'InventoryController/supplierDashboard/$1';



$route['cylinderPurchases'] = 'InventoryController/cylinderPurchases';
$route['cylinderPurchases_add'] = 'InventoryController/cylinderPurchases_add';
$route['viewCylinder/(:any)'] = 'InventoryController/viewCylinder/$1';

$route['cylinderExchange'] = 'InventoryController/cylinderExchange';
$route['cylinderExchangeAdd'] = 'InventoryController/cylinderExchangeAdd';
$route['viewCylinderExchange/(:any)'] = 'InventoryController/viewCylinderExchange/$1';

$route['brand'] = 'InventoryController/brand';
$route['brandAdd'] = 'InventoryController/brandAdd';
$route['brandEdit/(:any)'] = 'InventoryController/brandEdit/$1';

$route['inventoryAdjustment'] = 'InventoryController/inventoryAdjustment';
$route['inventoryAdjustmentAdd'] = 'InventoryController/inventoryAdjustmentAdd';
$route['deleteInventoryOpening/(:any)'] = 'InventoryController/deleteInventoryOpening/$1';
$route['openigInventoryImport'] = 'InventoryController/openigInventoryImport';
$route['viewAdjustment/(:any)'] = 'InventoryController/viewAdjustment/$1';
$route['supplierPayment'] = 'InventoryController/supplierPayment';

$route['supplierPaymentAdd'] = 'InventoryController/supplierPaymentAdd';
$route['purchases_list'] = 'InventoryController/purchases_list';
//$route['purchases_add'] = 'InventoryController/purchases_add';

$route['purchases_add'] = 'lpg/PurchaseController/purchases_add';
//$route['purchases_edit/(:any)'] = 'InventoryController/purchases_edit/$1';
$route['purchases_edit/(:any)'] = 'lpg/PurchaseController/purchases_edit/$1';
$route['full_cylinder_stock_report'] = 'lpg/PurchaseController/cylinder_stock_report';

$route['stock_group_report'] = 'lpg/PurchaseController/cylinder_stock_group_report';

$route['current_stock'] = 'lpg/PurchaseController/current_stock';
$route['current_stock_value'] = 'lpg/PurchaseController/current_stock_value';


$route['purchases_add/(:any)'] = 'InventoryController/purchases_add/$1';

$route['viewPurchases/(:any)'] = 'InventoryController/viewPurchases/$1';
$route['viewPurchasesWithCylinder/(:any)'] = 'InventoryController/viewPurchasesWithCylinder/$1';
$route['editPurchases/(:any)'] = 'InventoryController/editPurchases/$1';

$route['stockReport'] = 'InventoryController/stockReport';
$route['purchasesReport'] = 'InventoryController/purchasesReport';
$route['supplierPurchasesReport'] = 'InventoryController/supplierPurchasesReport';
$route['supPendingCheque'] = 'InventoryController/supPendingCheque';

/* inventory  controller end */


/* Sales Module start */

$route['productWiseSalesReport'] = 'SalesController/productWiseSalesReport';
$route['salesReport'] = 'SalesController/salesReport';
$route['cylinder_sales_report'] = 'lpg/SalesController/cylinder_sales_report';
$route['sales_report_brand_wise'] = 'lpg/SalesController/sales_report_brand_wise';
$route['daily_sales_statement']='lpg/SalesController/daily_sales_statement';
$route['date_wise_product_sales']='lpg/SalesController/date_wise_product_sales';
$route['salesInvoice_edit/(:any)'] = 'lpg/SalesController/salesInvoice_edit/$1';
$route['date_wise_product_sales_by_date']='lpg/SalesController/date_wise_product_sales_by_date';
$route['customer_due']='lpg/SalesController/customer_due';

$route['referenceSalesReport'] = 'SalesController/referenceSalesReport';
$route['cancelOrder'] = 'SalesController/cancelOrder';
$route['salesOrderCancel/(:any)'] = 'SalesController/salesOrderCancel/$1';
$route['salesOrder'] = 'SalesController/salesOrder';
$route['customerSalesReport'] = 'SalesController/customerSalesReport';
$route['salesOrderAdd'] = 'SalesController/salesOrderAdd';
$route['cylinderReceive'] = 'SalesController/cylinderReceive';

$route['cylinderReceiveAdd'] = 'SalesController/cylinderReceiveAdd';
$route['cylinderReceiveView/(:any)'] = 'SalesController/cylinderReceiveView/$1';
$route['salesOrderView/(:any)'] = 'SalesController/salesOrderView/$1';
$route['reference'] = 'SalesController/reference';
$route['referenceAdd'] = 'SalesController/referenceAdd';
$route['deleteReference/(:any)'] = 'SalesController/deleteReference/$1';
$route['editReference/(:any)'] = 'SalesController/editReference/$1';
$route['customerDashboard/(:any)'] = 'SalesController/customerDashboard/$1';
$route['topSaleProduct'] = 'SalesController/topSaleProduct';

$route['dishonourCustomerChwque/(:any)'] = 'SalesController/dishonourCustomerChwque/$1';
$route['dishonourCustomerChwqueList'] = 'SalesController/dishonourCustomerChwqueList';
$route['pendingCheck'] = 'SalesController/pendingCheck';
$route['customerPaymentAdd'] = 'SalesController/customerPaymentAdd';
$route['viewMoneryReceipt/(:any)'] = 'SalesController/viewMoneryReceipt/$1';
$route['viewMoneryReceipt/(:any)/(:any)'] = 'SalesController/viewMoneryReceipt/$1/$2';

$route['customerPayment'] = 'SalesController/customerPayment';
$route['customerList'] = 'SalesController/customerList';
$route['addCustomer'] = 'SalesController/addCustomer';
$route['editCustomer/(:any)'] = 'SalesController/editCustomer/$1';

$route['salesOrderConfirm/(:any)'] = 'SalesController/salesOrderConfirm/$1';
$route['salesInvoice'] = 'SalesController/salesInvoice';
//$route['salesInvoice_add'] = 'SalesController/salesInvoice_add';
$route['salesInvoice_add'] = 'lpg/SalesController/salesInvoice_add';
$route['salesInvoice_add/(:any)'] = 'SalesController/salesInvoice_add/$1';

$route['salesInvoice_view/(:any)'] = 'lpg/SalesController/salesInvoice_view/$1';
$route['salesInvoicViewWithCylinder/(:any)'] = 'SalesController/salesInvoicViewWithCylinder/$1';



/* Sales Module end */

/* Finance Account start */
$route['editChartOfAccount/(:any)'] = 'FinaneController/editChartOfAccount/$1';
$route['chartOfAccount'] = 'FinaneController/chartOfAccount';
$route['listChartOfAccount'] = 'FinaneController/listChartOfAccount';
$route['viewChartOfAccount'] = 'FinaneController/viewChartOfAccount';
$route['editChartOfAccount/(:any)'] = 'FinaneController/editChartOfAccount/$1';
$route['openingBalance'] = 'FinaneController/openingBalance';
$route['openingBalance/(:any)'] = 'FinaneController/openingBalance/$1';
$route['deleteOpneningBalance'] = 'FinaneController/deleteOpneningBalance';
$route['customerLedger'] = 'FinaneController/customerLedger';
$route['supplierLedger'] = 'FinaneController/supplierLedger';
//payment Voucher

$route['supplierOpening'] = 'FinaneController/supplierOpening';
$route['supplierOpeningAdd'] = 'FinaneController/supplierOpeningAdd';
$route['customerOpneing'] = 'FinaneController/customerOpneing';
$route['customerOpneingAdd'] = 'FinaneController/customerOpneingAdd';
$route['customerOpneingEdit/(:any)'] = 'FinaneController/customerOpneingEdit/$1';
$route['supplierOpeningImport'] = 'FinaneController/supplierOpeningImport';
$route['customerOpeningImport'] = 'FinaneController/customerOpeningImport';



$route['paymentVoucher'] = 'FinaneController/paymentVoucher';
$route['paymentVoucherAdd'] = 'FinaneController/paymentVoucherAdd';
$route['paymentVoucherAdd/(:any)'] = 'FinaneController/paymentVoucherAdd/$1';
$route['paymentVoucherEdit/(:any)'] = 'FinaneController/paymentVoucherEdit/$1';
$route['paymentVoucherView/(:any)'] = 'FinaneController/paymentVoucherView/$1';

//Report Voucher
$route['trialBalance'] = 'FinaneController/trialBalance';
$route['generalLedger'] = 'FinaneController/generalLedger';
$route['generalLedger/(:any)'] = 'FinaneController/generalLedger/$1';

$route['incomeStetement'] = 'FinaneController/incomeStetement';
$route['balanceSheet'] = 'FinaneController/balanceSheet';
$route['cashFlow'] = 'FinaneController/cashFlow';
$route['cashBook'] = 'FinaneController/cashBook';
$route['bankBook'] = 'FinaneController/bankBook';
$route['detailsLedger/(:any)'] = 'FinaneController/detailsLedger/$1';

//bill voucher
$route['billInvoice'] = 'FinaneController/billInvoice';
$route['billInvoice_add'] = 'FinaneController/billInvoice_add';
$route['billInvoicePayment/(:any)'] = 'FinaneController/billInvoicePayment/$1';
$route['billInvoice_view/(:any)'] = 'FinaneController/billInvoice_view/$1';
$route['billInvoice_edit/(:any)'] = 'FinaneController/billInvoice_edit/$1';

//Receive Voucher
$route['receiveVoucher'] = 'FinaneController/receiveVoucher';
$route['receiveVoucherAdd'] = 'FinaneController/receiveVoucherAdd';
$route['receiveVoucherAdd/(:any)'] = 'FinaneController/receiveVoucherAdd/$1';
$route['receiveVoucherEdit/(:any)'] = 'FinaneController/receiveVoucherEdit/$1';
$route['receiveVoucherView/(:any)'] = 'FinaneController/receiveVoucherView/$1';
//Journal Voucher

$route['journalVoucher'] = 'FinaneController/journalVoucher';
$route['journalVoucherAdd'] = 'FinaneController/journalVoucherAdd';
$route['journalVoucherEdit/(:any)'] = 'FinaneController/journalVoucherEdit/$1';
$route['journalVoucherView/(:any)'] = 'FinaneController/journalVoucherView/$1';

/* Pos routign */
$route['salesPos'] = 'PosController/salesPosAdd';
$route['customer_due_collection'] = 'lpg/SalesController/customer_due_collection';
$route['cus_due_coll_list'] = 'lpg/SalesController/cus_due_coll_list';
$route['customer_due_collection_inv/(:any)'] = 'lpg/SalesController/customer_due_collection_inv/$1';
$route['salesPosList'] = 'PosController/salesPosList';
/* Pos routign */



/* Product Type */
$route['productType'] = 'ProductController/productTypeList';
$route['addproductType'] = 'ProductController/productType';
$route['editproductType/(:any)'] = 'ProductController/editProductType/$1';
$route['deleteProductType/(:any)'] = 'ProductController/deleteProductType/$1';
/* Product Type  */

/*Product Package */
$route['productPackageList']='ProductPackageController/product_package_list';
$route['productPackageAdd']='ProductPackageController/product_package_add';
$route['productPackageEdit/(:any)']='ProductPackageController/product_package_edit/$1';
$route['productPackageView/(:any)']='ProductPackageController/product_package_view/$1';
$route['productPackageDelete/(:any)']='ProductPackageController/product_package_delete/$1';


/*Product Package */


/* Finance Account End */
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['testhelper'] = 'InventoryController/abstest';
