<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$data = $db->single('products',[
    'id' => $_GET['id']
]);

$unit = $db->single('units',[
    'id' => $data->unit_id
]);

$product_categories = $db->all('product_categories',[
    'product_id' => $_GET['id']
]);

$cats = [];
foreach($product_categories as $category)
{
    $cat = $db->single('categories',[
        'id' => $category->category_id
    ]);
    $cats[] = $cat;
}

$product_prices = $db->all('product_prices',[
    'product_id' => $_GET['id']
],[
    'id' => 'DESC'
]);

$product_stocks = $db->all('product_stocks',[
    'product_id' => $_GET['id']
],[
    'id' => 'DESC'
]);

$data->unit = $unit;
$data->categories = $cats;
$data->prices = $product_prices;
$data->stocks = $product_stocks;

return compact('data','success_msg');