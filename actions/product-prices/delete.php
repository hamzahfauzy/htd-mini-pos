<?php

$conn = conn();
$db   = new Database($conn);
$tbl  = "product_prices";

$product_price = $db->single($tbl,[
    'id' => $_GET['id']
]);

$db->delete($tbl,[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Harga Produk berhasil dihapus']);
header('location:index.php?r=products/view&id='.$product_price->product_id);
die();