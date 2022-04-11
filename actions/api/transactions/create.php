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

$carts  = $_POST['items'];
$status = $_GET['status'] == 'bayar' ? 'pay' : 'order';

$customer_id = 0;
if(isset($_POST['customer_code']))
{
    $customer = $db->single('customers',[
        'code' => $_POST['customer_code']
    ]);

    if($customer) $customer_id = $customer->id;
}

$inv_code = strtotime('now');
$total    = 0;
foreach($carts as $item)
{
    $total += ($item['price']*$item['qty']);
}

$insert_data = [
    'user_id'  => $auth->id,
    'total'    => $total,
    'status'   => $status == 'pay' ? 'finish' : 'on going',
    'inv_code' => $inv_code,
    'paytotal' => $status == 'pay' ? $_POST['paytotal'] : 0,
    'notes'    => $_POST['notes'],
    'return_total' => $status == 'pay' ? $_POST['paytotal']-$total : 0
];

if($customer_id) $insert_data['customer_id'] = $customer_id;

$transaction = $db->insert('transactions',$insert_data);

foreach($carts as $item)
{
    $db->insert('transaction_items',[
        'transaction_id' => $transaction->id,
        'product_id'     => $item['product_id'],
        'price'          => $item['price'],
        'qty'            => $item['qty'],
        'subtotal'       => ($item['price']*$item['qty']),
        'status'         => $status,
    ]);

    $db->insert('product_stocks',[
        'product_id' => $item['product_id'],
        'qty'        => (-1 * $item['qty']),
    ]);
}


echo json_encode([
    'status'   => 'success',
    'message'  => 'make order success.',
    'data'     => []
]);
die();