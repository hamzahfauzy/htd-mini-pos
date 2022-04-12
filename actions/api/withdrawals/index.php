<?php

$conn = conn();
$db   = new Database($conn);

$withdrawals = $db->all('withdrawals',[],[
    'id' => 'desc'
]);

echo json_encode([
    'status'  => 'success',
    'message' => 'withdrawals data retrieved.',
    'data'    => $withdrawals,
]);
die();