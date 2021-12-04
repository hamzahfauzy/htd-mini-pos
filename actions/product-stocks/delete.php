<?php

$conn = conn();
$db   = new Database($conn);
$tbl  = "product_stocks";

$product_stock = $db->single($tbl,[
    'id' => $_GET['id']
]);

$db->delete($tbl,[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Stok Produk berhasil dihapus']);
header('location:index.php?r=products/view&id='.$product_stock->product_id);
die();