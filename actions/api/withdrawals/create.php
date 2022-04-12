<?php

if(request() == 'GET')
{
    http_response_code(400);
    echo json_encode(['message'=>'This method is not allowed. Use POST instead']);
    die();
}

$conn = conn();
$db   = new Database($conn);

$data = $db->insert('withdrawals',[
    'amount' => $_POST['amount']
]);

echo json_encode([
    'status'  => 'success',
    'message' => 'withdrawal data created.',
    'data'    => $data
]);
die();