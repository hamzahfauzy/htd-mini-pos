<?php

$conn = conn();
$db   = new Database($conn);



$item = $db->single('transaction_items',[
    'id' => $_GET['id']
]);

$db->insert('product_stocks',[
    'qty' => $item->qty,
    'product_id' => $item->product_id
]);

$db->update('transaction_items',[
    'status' => 'retur'
],[
    'id' => $_GET['id'] 
]);

$transaction = $db->single('transactions',[
    'id' => $item->transaction_id
]);

$db->update('transactions',[
    'total' => $transaction->total - $item->subtotal,
    'return_total' => $transaction->total + $transaction->return_total
],[
    'id' => $transaction->id
]);

set_flash_msg(['success'=>'Transaksi berhasil di kembalikan']);
header('location:index.php?r=transactions/view&id='.$transaction->id);