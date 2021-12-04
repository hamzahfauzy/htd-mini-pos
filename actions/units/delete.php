<?php

$conn = conn();
$db   = new Database($conn);

$db->delete('units',[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Satuan berhasil dihapus']);
header('location:index.php?r=units/index');
die();