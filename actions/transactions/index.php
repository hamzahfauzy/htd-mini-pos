<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$transactions = $db->all('transactions');

foreach($transactions as $index => $transaction)
{
    $transaction->customer = $db->single('customers',[
        'id' => $transaction->customer_id
    ]);

    $transaction->user = $db->single('users',[
        'id' => $transaction->user_id
    ]);
}

return compact('transactions','success_msg');