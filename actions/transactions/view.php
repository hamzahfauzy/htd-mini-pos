<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$transaction = $db->single('transactions',[
    'id' => $_GET['id']
]);

$transaction->customer = $db->single('customers',[
    'id' => $transaction->customer_id
]);

$transaction->user = $db->single('users',[
    'id' => $transaction->user_id
]);

$items = $db->all('transaction_items',[
    'transaction_id' => $transaction->id
]);

foreach($items as $index => $item)
{
    $item->product = $db->single('products',[
        'id' => $item->product_id
    ]);
}

$transaction->items = $items;

return compact('transaction','success_msg');