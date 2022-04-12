<?php

$conn = conn();
$db   = new Database($conn);

$db->delete('withdrawals',[
    'id' => $_GET['id']
]);

echo json_encode([
    'status'  => 'success',
    'message' => 'withdrawal data deleted.',
    'data'    => []
]);
die();