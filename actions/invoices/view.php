<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$invoice = $db->single('invoices',[
    'id' => $_GET['id']
]);

$invoice->customer = $db->single('customers',[
    'id' => $invoice->customer_id
]);

$invoice->creator = $db->single('users',[
    'id' => $invoice->created_by
]);

$items = $db->all('invoice_items',[
    'invoice_id' => $invoice->id
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

$invoice->items = $items;

$db->query = "SELECT transactions.*, users.name as cashier FROM transactions JOIN users ON users.id = transactions.created_by WHERE invoice_id = $invoice->id";
$invoice->transactions = $db->exec('all');

$badge = [
    'order' => 'warning',
    'pay'   => 'success',
    'retur' => 'danger',
];

return compact('invoice','success_msg','badge');