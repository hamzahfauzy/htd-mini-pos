<?php

$conn = conn();
$db   = new Database($conn);

$db->query = "SELECT id, name FROM users";
$customers = $db->exec('all');

echo json_encode([
    'message' => 'Data berhasil diambil',
    'data'    => $customers,
    'status'  => 'success'
]);
die();