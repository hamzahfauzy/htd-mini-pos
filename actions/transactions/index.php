<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$from = isset($_GET['from']) ? $_GET['from'] : date('Y-m-01');
$to = isset($_GET['to']) ? $_GET['to'] : date('Y-m-t');
$db->query = "SELECT transactions.*, invoices.code, invoices.id invoice_id, users.name cashier, customers.name customer_name FROM transactions JOIN invoices ON invoices.id = transactions.invoice_id LEFT JOIN customers ON customers.id = invoices.customer_id JOIN users ON users.id = transactions.created_by WHERE DATE_FORMAT(transactions.created_at, '%Y-%m-%d') BETWEEN '$from' AND '$to' ORDER BY transactions.id DESC";
$transactions = $db->exec('all');

$badge = [
    'order' => 'warning',
    'pay'   => 'success',
    'retur' => 'danger',
];

return compact('transactions','success_msg','badge');