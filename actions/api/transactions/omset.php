<?php

$conn = conn();
$db   = new Database($conn);

$db->query = "select sum(total) as omset from transactions";

$omset = $db->exec('single');
$omset = $omset->omset;

$db->query = "select sum(amount) as total from withdrawals";

$withdrawal = $db->exec('single');

$omset = $omset - ($withdrawal->total??0);

echo json_encode([
    'status'   => 'success',
    'message'  => 'add order item success.',
    'data'     => $omset
]);
die();