<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$withdrawals = $db->all('withdrawals',[],[
    'id' => 'desc'
]);

$db->query = "select sum(amount) as total from withdrawals";

$total_withdrawal = $db->exec('single');

return compact('withdrawals','total_withdrawal','success_msg');