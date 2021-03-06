<?php

$conn = conn();
$db   = new Database($conn);

$item = $db->single('transaction_items',[
    'id' => $_GET['id']
]);

$transaction = $db->single('transactions',[
    'id' => $item->transaction_id
]);

$db->delete('transaction_items',[
    'id' => $_GET['id'] 
]);

$db->update('transactions',[
    'total' => $transaction->total - $item->subtotal
],[
    'id' => $item->transaction_id
]);

set_flash_msg(['success'=>'Transaksi berhasil dibayar']);
header('location:index.php?r=transactions/view&id='.$transaction->id);