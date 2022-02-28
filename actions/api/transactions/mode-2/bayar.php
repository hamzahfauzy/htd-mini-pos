<?php

$conn = conn();
$db   = new Database($conn);

$pos_sess_id = $_POST['pos_sess_id'];
$transaction_id = $_POST['transaction_id'];
$carts = $_SESSION[$pos_sess_id];
$status = $_GET['status'] == 'bayar' ? 'pay' : 'order';

$customer_id = 0;
if(isset($_POST['customer_code']))
{
    $customer = $db->single('customers',[
        'code' => $_POST['customer_code']
    ]);

    if($customer) $customer_id = $customer->id;
}

$inv_code = str_replace('pos_sess_id_','',$pos_sess_id);

if(!$transaction_id)
{
    $insert_data = [
        'user_id'  => auth()->user->id,
        'total'    => $carts['total'],
        'status'   => $status == 'pay' ? 'finish' : 'on going',
        'inv_code' => $inv_code,
        'paytotal' => $status == 'pay' ? $_POST['paytotal'] : 0,
        'notes'    => $_POST['notes'],
        'return_total' => $status == 'pay' ? $_POST['paytotal']-$carts['total'] : 0
    ];
    
    if($customer_id) $insert_data['customer_id'] = $customer_id;

    $transaction = $db->insert('transactions',$insert_data);
}
else
{
    $transaction = $db->single('transactions',['id'=>$transaction_id]);
    $db->update('transactions',[
        'total'    => $transaction->total + $carts['total'],
    ],['id'=>$transaction_id]);
}

foreach($carts['items'] as $product_id => $item)
{
    // check if product is already in items
    $product_item = $db->single('transaction_items',[
        'transaction_id' => $transaction_id,
        'product_id' => $product_id,
        'status'     => 'order'
    ]);

    if($product_item)
    {
        $qty = $product_item->qty + $item['qty'];
        $db->update('transaction_items',[
            'qty' => $qty,
            'subtotal' => $item['subtotal'] + $product_item->subtotal
        ],[
            'id' => $product_item->id
        ]);
    }
    else
    {
        $db->insert('transaction_items',[
            'transaction_id' => $transaction->id,
            'product_id'     => $product_id,
            'price'          => $item['price'],
            'qty'            => $item['qty'],
            'subtotal'       => $item['subtotal'],
            'status'         => $status,
        ]);
    }

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

    $product_category = $db->single('product_categories',[
        'product_id' => $product->id
    ]);

    $product->category = $db->single('categories',[
        'id' => $product_category->category_id
    ]);

    $items[$index]->product = $product;
}

usort($items,function($a, $b){
    return $a->product->category->id - $b->product->category->id;
});

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