<?php

$conn = conn();
$db   = new Database($conn);

$pos_sess_id = $_POST['pos_sess_id'];
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

$insert_data = [
    'created_by'  => auth()->user->id,
    'total'    => $carts['total'],
    'status'   => 'finish',
    'code' => $inv_code,
];

if($customer_id) $insert_data['customer_id'] = $customer_id;

$invoice = $db->insert('invoices',$insert_data);
$transaction = $db->insert('transactions', [
    'invoice_id' => $invoice->id,
    'amount' => $_POST['paytotal'],
    'amount_total' => $carts['total'],
    'amount_return' => $_POST['paytotal']-$carts['total'],
    'payment_type' => $_POST['payment_type'],
    'created_by'  => auth()->user->id,
]);

foreach($carts['items'] as $product_id => $item)
{
    // check if product is already in items
    $product_item = $db->single('invoice_items',[
        'invoice_id' => $invoice_id,
        'product_id' => $product_id,
        'status'     => 'order'
    ]);

    if($product_item)
    {
        $qty = $product_item->qty + $item['qty'];
        $db->update('invoice_items',[
            'qty' => $qty,
            'subtotal' => $item['subtotal'] + $product_item->subtotal
        ],[
            'id' => $product_item->id
        ]);
    }
    else
    {
        $db->insert('invoice_items',[
            'invoice_id'     => $invoice->id,
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

$items = $db->all('invoice_items',[
    'invoice_id' => $invoice->id
]);
    
foreach($items as $index => $item)
{
    $product = $db->single('products',[
        'id' => $item->product_id
    ]);

    $items[$index]->product = $product;
}

$invoice->items = $items;
$invoice->creator  = $db->single('users',[
    'id' => $invoice->created_by
]);

$invoice->customer  = $db->single('customers',[
    'id' => $invoice->customer_id
]);

$invoice->transaction  = $transaction;
echo json_encode([
    'status' => 'success',
    'msg'    => 'payment success',
    'inv_code' => $inv_code,
    'invoice'=>$invoice
]);
die();