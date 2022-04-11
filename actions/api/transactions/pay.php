<?php

if(request() == 'GET')
{
    http_response_code(400);
    echo json_encode(['message'=>'This method is not allowed. Use POST instead']);
    die();
}

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

echo json_encode([
    'status'  => 'success',
    'message' => 'transaction item payment success.',
    'data'    => []
]);
die();