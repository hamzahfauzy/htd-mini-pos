<?php

/*
    start of transaction properties
    customer_id -> nullable
    user_id     -> cashier_id
    total
    paytotal
    return_total
    status
    inv_code

    start of transaction item properties
    product_id
    transaction_id
    price
    qty
    subtotal
    status
*/

$conn = conn();
$db   = new Database($conn);

$pos_sess_id = $_GET['pos_sess_id'];
$category_id = $_GET['category_id'];

$category = $db->single('categories',[
    'id' => $category_id
]);

if(!isset($_SESSION[$pos_sess_id]))
    $_SESSION[$pos_sess_id] = []; 

if(!isset($_SESSION[$pos_sess_id][$category_id]))
    $_SESSION[$pos_sess_id][$category_id] = ['name'=>$category->name,'items'=>[],'total'=>0];

$clause = [];
if(isset($_GET['code']))
    $clause = ['code' => $_GET['code']];
elseif(isset($_GET['name']))
    $clause = ['name' => ['LIKE','%'.$_GET['name'].'%']];

$data = $db->single('products',$clause);

// get stok
$db->query = "SELECT SUM(qty) as stock FROM product_stocks WHERE product_id=$data->id";
$stock = $db->exec('single');
if($stock->stock <= 0)
{
    echo json_encode(['error'=>'stock not found']);
    die();
}

if(empty($data))
{
    echo json_encode(['error'=>'not found']);
    die();
}

// check if item is already on cashier
if(isset($_SESSION[$pos_sess_id][$category_id]['items'][$data->id]))
{
    $_SESSION[$pos_sess_id][$category_id]['items'][$data->id]['qty'] += 1;
    $_SESSION[$pos_sess_id][$category_id]['items'][$data->id]['subtotal'] = $_SESSION[$pos_sess_id][$category_id]['items'][$data->id]['qty'] * $_SESSION[$pos_sess_id][$category_id]['items'][$data->id]['price'];
    $_SESSION[$pos_sess_id][$category_id]['items'][$data->id]['subtotal_format'] = number_format($_SESSION[$pos_sess_id][$category_id]['items'][$data->id]['subtotal']);
}
// if item not already on cashier
else
{
    $product_price = $db->single('product_prices',[
        'product_id' => $data->id
    ],[
        'id' => 'DESC'
    ]);

    $_SESSION[$pos_sess_id][$category_id]['items'][$data->id] = [
        'id'   => $data->id,
        'code' => $data->code,
        'name' => $data->name,
        'qty'  => 1,
        'price'  => $product_price->base_price,
        'price_format'  => number_format($product_price->base_price),
        'subtotal' => $product_price->base_price,
        'subtotal_format' => number_format($product_price->base_price)
    ];
}

$_SESSION[$pos_sess_id]['total'] = count_total_2($_SESSION[$pos_sess_id]);
$_SESSION[$pos_sess_id]['total_format'] = number_format($_SESSION[$pos_sess_id]['total']);
$_SESSION[$pos_sess_id]['pic'] = $data->pic;

echo json_encode($_SESSION[$pos_sess_id]);
die();