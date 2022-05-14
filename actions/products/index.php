<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

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
    $data->stock = $data->default_stock == 'stock' ? number_format($db->exec('single')->stock) : ucwords($data->default_stock);

    $data->unit = $unit;
    $data->categories = $cats;
    $data->price = $product_price;
}

return compact('datas','success_msg');