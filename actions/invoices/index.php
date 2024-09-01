<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$invoices = $db->all('invoices',[],[
    'id' => 'desc'
]);

foreach($invoices as $index => $invoice)
{
    $invoice->customer = $db->single('customers',[
        'id' => $invoice->customer_id
    ]);
    
    $invoice->sales = $db->single('users',[
        'id' => $invoice->sales_id
    ]);

    $invoice->creator = $db->single('users',[
        'id' => $invoice->created_by
    ]);
}

$badge = [
    'order' => 'warning',
    'pay'   => 'success',
    'retur' => 'danger',
];

return compact('invoices','success_msg','badge');