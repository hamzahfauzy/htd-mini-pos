<?php

$conn = conn();
$db   = new Database($conn);

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
    $product = $db->single('products',[
        'id' => $item->product_id
    ]);

    $product_category = $db->single('product_categories',[
        'product_id' => $product->id
    ]);

    $product->category = $db->single('categories',[
        'id' => $product_category->category_id
    ]);

    $item->product = $product;
}

usort($items,function($a, $b){
    return $a->product->category->id - $b->product->category->id;
});

$transaction->items = $items;

echo json_encode([
    'status'  => 'success',
    'message' => 'transaction detail retrieved.',
    'data'    => $transaction
]);
die();