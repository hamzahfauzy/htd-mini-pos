<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST')
{
    if(isset($_FILES['file']) && !empty($_FILES['file']))
    {
        $pic  = $_FILES['file'];
        $ext  = pathinfo($pic['name'], PATHINFO_EXTENSION);
        $name = strtotime('now').'.'.$ext;
        $file = 'uploads/products/'.$name;
        copy($pic['tmp_name'],$file);
        $_POST['products']['pic'] = $file;
    }

    $product = $db->insert('products',$_POST['products']);
    $db->insert('product_categories',[
        'product_id' => $product->id,
        'category_id' => $_POST['category']
    ]);

    set_flash_msg(['success'=>'Produk berhasil ditambahkan']);
    header('location:index.php?r=products/view&id='.$product->id);
}

$units = $db->all('units');
$categories = $db->all('categories');

return compact('units','categories');