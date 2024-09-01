<?php

$conn = conn();
$db   = new Database($conn);

$invoice_id = $_GET['invoice_id'];
$invoice = $db->single('invoices',['id'=>$invoice_id]);
$remaining = $invoice->remaining_payment-$_POST['total'];

$db->update('invoices',[
    'status'       => $remaining <= 0 ? 'finish' : $invoice->status,
    'remaining_payment' => $remaining <= 0 ? 0 : $remaining
],['id'=>$invoice_id]);

$db->insert('transactions', [
    'invoice_id' => $invoice_id,
    'amount' => $_POST['total'],
    'amount_total' => $invoice->remaining_payment,
    'amount_return' => $remaining <= 0 ? abs($remaining) : 0,
    'payment_type' => $_POST['payment_type'],
    'created_by'  => auth()->user->id,
    'notes'  => $_POST['notes'],
]);

if($remaining <= 0)
{
    $db->query = "UPDATE invoice_items SET status = 'pay' WHERE invoice_id = $invoice_id";
    $db->exec();
}

set_flash_msg(['success'=>'Transaksi berhasil dibayar']);
header('location:index.php?r=invoices/view&id='.$invoice->id);