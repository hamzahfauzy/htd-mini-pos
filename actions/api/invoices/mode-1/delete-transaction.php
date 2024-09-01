<?php

$pos_sess_id = $_GET['pos_sess_id'];
$product_id  = $_GET['id'];

unset($_SESSION[$pos_sess_id]['items'][$product_id]);

$_SESSION[$pos_sess_id]['total'] = count_total($_SESSION[$pos_sess_id]['items']);
$_SESSION[$pos_sess_id]['total_format'] = number_format($_SESSION[$pos_sess_id]['total']);

echo json_encode($_SESSION[$pos_sess_id]);
die();