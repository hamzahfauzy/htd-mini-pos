<?php

if(request() == 'GET')
{
    http_response_code(400);
    echo json_encode(['message'=>'This method is not allowed. Use POST instead']);
    die();
}

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

echo json_encode([
    'status'  => 'success',
    'message' => 'transaction payment success.',
    'data'    => []
]);
die();