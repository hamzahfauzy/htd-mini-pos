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

$db->delete('transaction_items',[
    'id' => $_GET['id'] 
]);

$db->update('transactions',[
    'total' => $transaction->total - $item->subtotal
],[
    'id' => $item->transaction_id
]);

echo json_encode([
    'status'  => 'success',
    'message' => 'transaction item data deleted.',
    'data'    => []
]);
die();