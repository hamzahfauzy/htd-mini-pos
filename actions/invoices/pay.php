<?php

$conn = conn();
$db   = new Database($conn);

$item = $db->single('transaction_items',[
    'id' => $_GET['id']
]);

$transaction = $db->single('transactions',[
    'id' => $item->transaction_id
]);

$db->update('transaction_items',[
    'status' => 'pay'
],[
    'id' => $_GET['id'] 
]);

$items = $db->all('transaction_items',[
    'id' => $_GET['id'],
    'status' => 'order'
]);

if(empty($items))
    $db->update('transactions',[
        'status' => 'finish'
    ],[
        'id' => $item->transaction_id
    ]);

set_flash_msg(['success'=>'Transaksi berhasil dibayar']);
header('location:index.php?r=transactions/view&id='.$transaction->id);