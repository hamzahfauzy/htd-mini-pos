<?php

$conn = conn();
$db   = new Database($conn);

$db->delete('withdrawals',[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Data penarikan omset berhasil dihapus']);
header('location:index.php?r=withdrawals/index');
die();