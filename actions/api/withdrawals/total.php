<?php

$conn = conn();
$db   = new Database($conn);

$db->query = "select sum(amount) as total from withdrawals";

$total_withdrawal = $db->exec('single');

echo json_encode([
    'status'  => 'success',
    'message' => 'total data withdrawals retrieved.',
    'data'    => $total_withdrawal,
]);
die();