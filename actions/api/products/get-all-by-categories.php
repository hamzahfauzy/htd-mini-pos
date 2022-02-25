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
            ])->base_price ?? 0;
            $product->price = number_format($price);

            $db->query = "SELECT sum(qty) as stock FROM product_stocks WHERE product_id=$product->id";
            $product->stock = $db->exec('single')->stock ?? 0;

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