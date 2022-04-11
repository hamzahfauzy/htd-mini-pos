<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('application');

echo json_encode([
    'status'  => 'success',
    'message' => 'application data retrieved.',
    'data'    => $data
]);
die();