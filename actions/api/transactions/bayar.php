<?php

$conn = conn();
$db   = new Database($conn);

$pos_sess_id = $_POST['pos_sess_id'];
$carts = $_SESSION[$pos_sess_id];

$customer_id = 0;
if(isset($_POST['customer_code']))
{
    $customer = $db->single('customers',[
        'code' => $_POST['customer_code']
    ]);

    if($customer) $customer_id = $customer->id;
}

$inv_code = str_replace('pos_sess_id_','',$pos_sess_id);

$insert_data = [
    'user_id'  => auth()->user->id,
    'total'    => $carts['total'],
    'status'   => 'pay',
    'inv_code' => $inv_code,
    'paytotal' => $_POST['paytotal'],
    'return_total' => $_POST['paytotal']-$carts['total']
];

if($customer_id) $insert_data['customer_id'] = $customer_id;

$transaction = $db->insert('transactions',$insert_data);

foreach($carts['items'] as $product_id => $item)
{
    $db->insert('transaction_items',[
        'transaction_id' => $transaction->id,
        'product_id'     => $product_id,
        'price'          => $item['price'],
        'qty'            => $item['qty'],
        'subtotal'       => $item['subtotal'],
        'status'         => 'pay',
    ]);

    $db->insert('product_stocks',[
        'product_id' => $product_id,
        'qty'        => (-1 * $item['qty']),
    ]);
}
unset($_SESSION[$pos_sess_id]);

$items = $db->all('transaction_items',[
    'transaction_id' => $transaction->id
]);
    
foreach($items as $index => $item)
{
    $product = $db->single('products',[
        'id' => $item->product_id
    ]);

    $items[$index]->product = $product;
}

$transaction->items = $items;
$transaction->user  = $db->single('users',[
    'id' => $transaction->user_id
]);

$transaction->customer  = $db->single('customers',[
    'id' => $transaction->customer_id
]);
echo json_encode([
    'status' => 'success',
    'msg'    => 'payment success',
    'inv_code' => $inv_code,
    'transaction'=>$transaction
]);
die();