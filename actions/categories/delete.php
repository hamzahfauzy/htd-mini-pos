<?php

$conn = conn();
$db   = new Database($conn);

$db->delete('categories',[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Kategori berhasil dihapus']);
header('location:index.php?r=categories/index');
die();