<?php
use Spipu\Html2Pdf\Html2Pdf;

$inv_code = $_GET['code'];
// $file_dest = __DIR__ .'/../../public/inv_print/'.$inv_code.'.pdf';
// if(!file_exists('inv_print/'.$inv_code.'.pdf'))
// {
    $conn = conn();
    $db   = new Database($conn);
    
    
    $invoice = $db->single('invoices',[
        'code' => $inv_code
    ]);
    
    if($invoice)
    {
        $items = $db->all('invoice_items',[
            'invoice_id' => $invoice->id
        ]);
    
        foreach($items as $index => $item)
        {
            $product = $db->single('products',[
                'id' => $item->product_id
            ]);
    
            $items[$index]->product = $product;
        }
    
        $invoice->items = $items;
        $invoice->creator  = $db->single('users',[
            'id' => $invoice->created_by
        ]);
    
        $invoice->customer  = $db->single('customers',[
            'id' => $invoice->customer_id
        ]);
        $invoice->paytotal = 0;
        $invoice->return_total = 0;
        
        $transactions = $db->all('transactions',[
            'invoice_id' => $invoice->id
        ]);

        foreach($transactions as $transaction)
        {
            $invoice->paytotal += $transaction->amount;
            $invoice->return_total += $transaction->amount_return;
        }

    
        $height = (count($invoice->items) * 3) + 55;
        if($height > 290) $height = 290;
    
        $html = load_templates('print/invoice',compact('invoice'),1);

        echo $html;

        die;
    
        $html2pdf = new Html2Pdf('P',[
            '57',
            $height
        ]);
        $html2pdf->writeHTML($html);
        // $html2pdf->output();
        $html2pdf->output($file_dest,'F');
    
    }
// }

// printer command
// print $file_dest

die();