<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$transactions = $db->all('transactions',[],[
    'id' => 'desc',
],10);

$omset = 0;

$period = date('Y-m');

$db->query = "select sum(total) as omset from transactions";

$omset = $db->exec('single');
$omset = $omset->omset;

$db->query = "select sum(amount) as total from withdrawals";

$withdrawal = $db->exec('single');

$omset = $omset - ($withdrawal->total??0);

foreach($transactions as $index => $transaction)
{
    $transaction->customer = $db->single('customers',[
        'id' => $transaction->customer_id
    ]);

    $transaction->user = $db->single('users',[
        'id' => $transaction->user_id
    ]);
}

$badge = [
    'order' => 'warning',
    'pay'   => 'success',
    'retur' => 'danger',
];

return compact('transactions','success_msg','badge','omset');