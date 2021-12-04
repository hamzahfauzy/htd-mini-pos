<?php

$conn = conn();
$db   = new Database($conn);

$db->delete('customers',[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Kustomer berhasil dihapus']);
header('location:index.php?r=customers/index');
die();