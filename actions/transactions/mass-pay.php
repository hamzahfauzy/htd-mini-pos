<?php

$conn = conn();
$db   = new Database($conn);

$transaction_id = $_GET['transaction_id'];
$transaction = $db->single('transactions',['id'=>$transaction_id]);

$db->update('transactions',[
    'status'       => 'finish',
    'paytotal'     => $_POST['total'],
    'return_total' => $_POST['total'] - $transaction->total,
],['id'=>$transaction_id]);

$transaction_items = $db->all('transaction_items',[
    'transaction_id' => $transaction_id
]);

foreach($transaction_items as $item)
{
    $db->update('transaction_items',[
        'status' => 'pay'
    ],[
        'id' => $item->id
    ]);
}

set_flash_msg(['success'=>'Transaksi berhasil dibayar']);
header('location:index.php?r=transactions/view&id='.$transaction->id);