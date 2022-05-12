<?php



$transactions = [];

if(
    isset($_GET['from']) && !empty($_GET['from']) &&
    isset($_GET['to']) && !empty($_GET['to'])
)
{
    $conn = conn();
    $db   = new Database($conn);
    $db->query = "SELECT * FROM transactions WHERE created_at BETWEEN '$_GET[from]' AND '$_GET[to]'";
    $transactions = $db->exec('all');
    $transactions = array_map(function($transaction) use ($db) {
        $transaction->customer = $db->single('customers',[
            'id' => $transaction->customer_id
        ]);
        
        $items = $db->all('transaction_items',[
            'transaction_id' => $transaction->id
        ]);

        $items = array_map(function($item) use ($db){
            $item->product = $db->single('products',[
                'id' => $item->product_id
            ]);
            
            return $item;
        }, $items);

        $transaction->items = $items;
    
        $transaction->user = $db->single('users',[
            'id' => $transaction->user_id
        ]);

        return $transaction;
    }, $transactions);
}

return compact('transactions');