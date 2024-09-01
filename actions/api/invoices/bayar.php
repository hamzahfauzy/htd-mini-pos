<?php

$conn = conn();
$db   = new Database($conn);

$pos_sess_id = $_POST['pos_sess_id'];
$carts = $_SESSION[$pos_sess_id];

$status = $_GET['status'] == 'bayar' ? 'pay' : 'order';
$remaining = $carts['total'] - $_POST['paytotal'];
$invoiceStatus = isset($_GET['invoice_status']) ? $_GET['invoice_status'] : ($remaining <= 0 ? 'finish' : 'on going');
$notes = isset($_POST['notes']) ? $_POST['notes'] : '';

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
    'remaining_payment' => $remaining > 0 ? $remaining : 0,
    'status'   => $invoiceStatus,
    'code' => $inv_code,
    'notes' => $notes
];

if($customer_id) $insert_data['customer_id'] = $customer_id;

$invoice = $db->insert('invoices',$insert_data);
$transaction = null;

if(isset($_POST['paytotal']) && $_POST['paytotal'] > 0)
{
    $returning = $_POST['paytotal']-$carts['total'];
    $transaction = $db->insert('transactions', [
        'invoice_id' => $invoice->id,
        'amount' => $_POST['paytotal'],
        'amount_total' => $carts['total'],
        'amount_return' => $returning < 0 ? 0 : $returning,
        'payment_type' => $_POST['payment_type'],
        'created_by'  => auth()->user->id,
        'notes' => $_POST['notes']
    ]);
}

if(!isset($carts['items']))
{
    $cart_items = [];
    foreach($carts as $cart)
    {
        if(!is_array($cart)) continue;
        $cart_items = array_merge($cart_items, $cart['items']);
    }

    $carts['items'] = $cart_items;
}

foreach($carts['items'] as $product_id => $item)
{
    // check if product is already in items
    $product_item = $db->single('invoice_items',[
        'invoice_id' => $invoice_id,
        'product_id' => $item['id'],
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
            'product_id'     => $item['id'],
            'price'          => $item['price'],
            'qty'            => $item['qty'],
            'subtotal'       => $item['subtotal'],
            'status'         => $status,
        ]);
    }

    $db->insert('product_stocks',[
        'product_id' => $item['id'],
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

$invoice->paytotal = 0;
$invoice->return_total = 0;

$transactions = $db->all('transactions',[
    'invoice_id' => $invoice->id
]);

foreach($transactions as $transaction)
{
    $invoice->paytotal += $transaction->amount;
    $invoice->return_total += $transaction->amount_return;
}

echo json_encode([
    'status' => 'success',
    'msg'    => 'payment success',
    'inv_code' => $inv_code,
    'invoice'=>$invoice
]);
die();