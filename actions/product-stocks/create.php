<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST')
{
    $db->insert('product_stocks',$_POST['stocks']);

    set_flash_msg(['success'=>'Stok Produk berhasil ditambahkan']);
    header('location:index.php?r=products/view&id='.$_GET['id']);
    die();
}

$data = $db->single('products',[
    'id'=>$_GET['id']
]);

return compact('data');