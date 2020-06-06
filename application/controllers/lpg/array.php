<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 5/17/2020
 * Time: 2:36 PM
 */


$sales_invoice_array[0] = array
(
    'Field' => 'sales_details_id',
    'Type' => 'int(11)',
    'Null' => 'NO',
    'Key' => 'PRI',
    'Default' => '',
    'Extra' => 'auto_increment'
);

$sales_invoice_array[1] = array
(
    'Field' => 'sales_invoice_id',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[2] = array
(
    'Field' => 'customer_id',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[3] = array
(
    'Field' => 'is_package',
    'Type' => 'tinyint(1)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[4] = array
(
    'Field' => 'show_in_invoice',
    'Type' => 'int(2)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '1',
    'Extra' => '',
);

$sales_invoice_array[5] = array
(
    'Field' => 'product_id',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[6] = array
(
    'Field' => 'ime_no',
    'Type' => 'text',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[7] = array
(
    'Field' => 'quantity',
    'Type' => 'decimal(10, 2)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[8] = array
(
    'Field' => 'unit_price',
    'Type' => 'decimal(10, 2)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[9] = array
(
    'Field' => 'last_purchase_price',
    'Type' => 'decimal(10, 2)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0.00',
    'Extra' => '',
);

$sales_invoice_array[10] = array
(
    'Field' => 'insert_by',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[11] = array
(
    'Field' => 'insert_date',
    'Type' => 'timestamp',
    'Null' => 'YES',
    'Key' => '',
    'Default' => 'CURRENT_TIMESTAMP',
    'Extra' => 'on update CURRENT_TIMESTAMP',
);

$sales_invoice_array[12] = array
(
    'Field' => 'returnable_quantity',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[13] = array
(
    'Field' => 'return_quentity',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[14] = array
(
    'Field' => 'customer_due',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[15] = array
(
    'Field' => 'branch_id',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[16] = array
(
    'Field' => 'property_1',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[17] = array
(
    'Field' => 'property_2',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[18] = array
(
    'Field' => 'property_3',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[19] = array
(
    'Field' => 'property_4',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[20] = array
(
    'Field' => 'property_5',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[21] = array
(
    'Field' => 'customer_advance',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '0',
    'Extra' => '',
);

$sales_invoice_array[22] = array
(
    'Field' => 'update_by',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[23] = array
(
    'Field' => 'update_date',
    'Type' => 'datetime',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[24] = array
(
    'Field' => 'delete_by',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[25] = array
(
    'Field' => 'delete_date',
    'Type' => 'datetime',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

$sales_invoice_array[26] = array
(
    'Field' => 'is_active',
    'Type' => "enum('Y', 'N')",
    'Null' => 'YES',
    'Key' => '',
    'Default' => 'Y',
    'Extra' => '',
);

$sales_invoice_array[27] = array
(
    'Field' => 'is_delete',
    'Type' => "enum('Y', 'N')",
    'Null' => 'YES',
    'Key' => '',
    'Default' => 'N',
    'Extra' => '',
);

$sales_invoice_array[28] = array
(
    'Field' => 'product_details',
    'Type' => 'text',
    'Null' => 'YES',
    'Key' => '',
    'Default' => '',
    'Extra' => '',
);

