<?php
if(request() == 'GET')
{
    http_response_code(400);
    echo json_encode(['message'=>'This method is not allowed. Use POST instead']);
    die();
}

$conn = conn();
$db   = new Database($conn);
$auth = JwtAuth::get_rest_session();
$carts = $_POST['items'];
$transaction_id = $_GET['id'];

$total = 0;
foreach($carts as $item)
{
    $total += ($item['price']*$item['qty']);

    // check if product is already in items
    $product_item = $db->single('transaction_items',[
        'transaction_id' => $transaction_id,
        'product_id' => $item['product_id'],
        'status'     => 'order'
    ]);

    if($product_item)
    {
        $qty = $product_item->qty + $item['qty'];
        $db->update('transaction_items',[
            'qty' => $qty,
            'subtotal' => ($item['price']*$item['qty']) + $product_item->subtotal
        ],[
            'id' => $product_item->id
        ]);
    }
    else
    {
        $db->insert('transaction_items',[
            'transaction_id' => $transaction_id,
            'product_id'     => $item['product_id'],
            'price'          => $item['price'],
            'qty'            => $item['qty'],
            'subtotal'       => ($item['price']*$item['qty']),
            'status'         => 'order',
        ]);
    }

    $db->insert('product_stocks',[
        'product_id' => $item['product_id'],
        'qty'        => (-1 * $item['qty']),
    ]);
}

$transaction = $db->single('transactions',[
    'id' => $transaction_id
]);

$db->update('transactions',[
    'total'    => $transaction->total + $total,
],['id'=>$transaction_id]);

echo json_encode([
    'status'   => 'success',
    'message'  => 'add order item success.',
    'data'     => []
]);
die();