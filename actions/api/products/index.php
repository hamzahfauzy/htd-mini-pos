<?php

$conn = conn();
$db   = new Database($conn);

$datas = $db->all('products',[],[
    'id' => 'DESC'
]);

foreach($datas as $data)
{
    $unit = $db->single('units',[
        'id' => $data->unit_id
    ]);
    
    $product_categories = $db->all('product_categories',[
        'product_id' => $data->id
    ]);
    
    $cats = [];
    foreach($product_categories as $category)
    {
        $cat = $db->single('categories',[
            'id' => $category->category_id
        ]);
        $cats[] = $cat;
    }
    
    $product_price = $db->single('product_prices',[
        'product_id' => $data->id
    ],[
        'id' => 'DESC'
    ]);

    $db->query = "SELECT sum(qty) as stock FROM product_stocks WHERE product_id=$data->id";
    $data->stock = $db->exec('single')->stock;

    $data->unit = $unit;
    $data->categories = $cats;
    $data->price = $product_price;
}

echo json_encode([
    'status'  => 'success',
    'message' => 'products data retrieved.',
    'data'    => $datas
]);
die();