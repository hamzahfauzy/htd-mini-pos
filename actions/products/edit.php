<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('products',[
    'id' => $_GET['id']
]);

if(request() == 'POST')
{
    if(!empty($_FILES['products']['name']['pic']))
    {
        $pic  = $_FILES['products'];
        $ext  = pathinfo($pic['name']['pic'], PATHINFO_EXTENSION);
        $name = strtotime('now').'.'.$ext;
        $file = 'uploads/products/'.$name;
        copy($pic['tmp_name']['pic'],$file);
        $_POST['products']['pic'] = $file;
    }
    else
        $_POST['products']['pic'] = $data->pic;

    $db->update('products',$_POST['products'],[
        'id' => $_GET['id']
    ]);

    $db->update('product_categories',[
        'category_id' => $_POST['category']
    ],[
        'product_id' => $_GET['id'],
    ]);

    set_flash_msg(['success'=>'Produk berhasil diupdate']);
    header('location:index.php?r=products/view&id='.$_GET['id']);
}

$units = $db->all('units');
$categories = $db->all('categories');
$product_categories = $db->all('product_categories',[
    'product_id' => $_GET['id']
]);
$cats = [];
foreach($product_categories as $category)
    $cats[] = $category->category_id;

return compact('data','units','categories','cats');