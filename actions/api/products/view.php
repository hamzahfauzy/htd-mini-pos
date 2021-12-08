<?php

$conn = conn();
$db   = new Database($conn);

$clause = [];
if(isset($_GET['code']))
    $clause = ['code' => $_GET['code']];
elseif(isset($_GET['name']))
    $clause = ['name' => ['LIKE','%'.$_GET['name'].'%']];

$data = $db->single('products',$clause);

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

$product_prices = $db->single('product_prices',[
    'product_id' => $data->id
],[
    'id' => 'DESC'
]);

$stock_query = "SELECT SUM(qty) as stock FROM product_stocks WHERE product_id=$data->id";
$db->query   = $stock_query;
$stock = $db->exec('single')->stock;

$data->unit = $unit;
$data->categories = $cats;
$data->price = $product_prices;
$data->stock = $stock;

echo json_encode($data);
die();