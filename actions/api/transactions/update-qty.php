<?php

$pos_sess_id = $_GET['pos_sess_id'];
$product_id  = $_GET['id'];

$_SESSION[$pos_sess_id]['items'][$product_id]['qty'] = $_GET['qty'];
$_SESSION[$pos_sess_id]['items'][$product_id]['subtotal'] = $_SESSION[$pos_sess_id]['items'][$product_id]['qty'] * $_SESSION[$pos_sess_id]['items'][$product_id]['price'];
$_SESSION[$pos_sess_id]['items'][$product_id]['subtotal_format'] = number_format($_SESSION[$pos_sess_id]['items'][$product_id]['subtotal']);

$_SESSION[$pos_sess_id]['total'] = count_total($_SESSION[$pos_sess_id]['items']);
$_SESSION[$pos_sess_id]['total_format'] = number_format($_SESSION[$pos_sess_id]['total']);

echo json_encode($_SESSION[$pos_sess_id]);
die();