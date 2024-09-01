<?php

$conn = conn();
$db   = new Database($conn);
$customers = $db->all('customers');

echo json_encode([
    'message' => 'Data berhasil diambil',
    'data'    => $customers,
    'status'  => 'success'
]);
die();