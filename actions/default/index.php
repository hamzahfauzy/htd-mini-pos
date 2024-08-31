<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$transactions = $db->all('transactions',[],[
    'id' => 'desc',
],10);

$omset = 0;

$period = date('Y-m');

$db->query = "select sum(total) as omset from transactions where status = 'finish' and created_at like '$period%'";

$omset = $db->exec('single');
$omset = $omset->omset;

$db->query = "select sum(total) as piutang from transactions where status = 'on going' and created_at like '$period%'";

$piutang = $db->exec('single');
$piutang = $piutang->piutang;

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
