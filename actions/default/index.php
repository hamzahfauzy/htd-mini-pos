<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$db->query = "SELECT transactions.*, invoices.id invoice_id, invoices.code FROM transactions JOIN invoices ON invoices.id = transactions.invoice_id ORDER BY id DESC LIMIT 0,10";
$transactions = $db->exec('all');

$omset = 0;

$period = date('Y-m');

$db->query = "select sum(amount-amount_return) as omset from transactions where created_at like '$period%'";

$omset = $db->exec('single');
$omset = $omset->omset;

$db->query = "select sum(total) as inv_total from invoices WHERE created_at like '$period%'";

$piutang = $db->exec('single');
$piutang = $piutang->inv_total - $omset;

foreach($transactions as $index => $transaction)
{
    $transaction->customer = $db->single('customers',[
        'id' => $transaction->customer_id
    ]);

    $transaction->user = $db->single('users',[
        'id' => $transaction->user_id
    ]);
}

$badge = [
    'on going' => 'warning',
    'finish'   => 'success'
];

return compact('transactions','success_msg','badge','omset','piutang');
