<?php
use Spipu\Html2Pdf\Html2Pdf;

$inv_code = $_GET['inv_code'];
$file_dest = __DIR__ .'/../../public/inv_print/'.$inv_code.'.pdf';
if(!file_exists('inv_print/'.$inv_code.'.pdf'))
{
    $conn = conn();
    $db   = new Database($conn);
    
    
    $transaction = $db->single('transactions',[
        'inv_code' => $inv_code
    ]);
    
    if($transaction)
    {
        $items = $db->all('transaction_items',[
            'transaction_id' => $transaction->id
        ]);
    
        foreach($items as $index => $item)
        {
            $product = $db->single('products',[
                'id' => $item->product_id
            ]);
    
            $items[$index]->product = $product;
        }
    
        $transaction->items = $items;
        $transaction->user  = $db->single('users',[
            'id' => $transaction->user_id
        ]);
    
        $transaction->customer  = $db->single('customers',[
            'id' => $transaction->customer_id
        ]);
    
        $height = (count($transaction->items) * 3) + 55;
        if($height > 290) $height = 290;
    
        $html = load_templates('print/invoice',compact('transaction'),1);
    
        $html2pdf = new Html2Pdf('P',[
            '57',
            $height
        ]);
        $html2pdf->writeHTML($html);
        // $html2pdf->output();
        $html2pdf->output($file_dest,'F');
    
    }
}

// printer command
// print $file_dest

die();