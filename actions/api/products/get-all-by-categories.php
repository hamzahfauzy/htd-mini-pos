<?php

$conn = conn();
$db   = new Database($conn);

$keyword = $_GET['keyword'] ?? '';

$categories = array_map(function($category) use ($keyword, $db){
    $product_categories = $db->all('product_categories',[
        'category_id' => $category->id
    ]);

    $category->products = [];

    if($product_categories)
    {
        $ids = [];
        foreach($product_categories as $cat)
            $ids[] = $cat->product_id;
    
        $ids = implode(',',$ids);
        $db->query = "SELECT * FROM products WHERE id IN ($ids)";

        if($keyword)
            $db->query .= " AND name LIKE '%$keyword%'";
        $category->products = array_map(function($product) use ($db){
            $price = $db->single('product_prices',[
                'product_id' => $product->id
            ],[
                'id' => 'DESC'
            ]);
            $discount = $price ? ($price->discount_type == 'fixed' ? $price->discount_price : $price->discount_price*$price->base_price/100) : 0;
            $price = $price ? $price->base_price - $discount : 0;
            $product->price = number_format($price);
            $db->query = "SELECT sum(qty) as stock FROM product_stocks WHERE product_id=$product->id";
            $product->stock = $product->default_stock != 'stock' ? $product->default_stock : $db->exec('single')->stock;

            return $product;
        },$db->exec('all'));
    }

    return $category;

}, $db->all('categories'));

echo json_encode([
    'message' => 'Data berhasil diambil',
    'data'    => $categories,
    'keyword' => $keyword,
    'status'  => 'success'
]);
die();