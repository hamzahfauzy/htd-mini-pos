<?php

$conn = conn();
$db   = new Database($conn);

$transactions = $db->all('transactions',[],[
    'id' => 'desc'
]);

foreach($transactions as $index => $transaction)
{
    $transaction->customer = $db->single('customers',[
        'id' => $transaction->customer_id
    ]);

    $transaction->user = $db->single('users',[
        'id' => $transaction->user_id
    ]);
}

echo json_encode([
    'status'  => 'success',
    'message' => 'transactions data retrieved.',
    'data'    => $transactions
]);
die();