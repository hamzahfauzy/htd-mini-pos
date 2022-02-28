<?php

$pos_sess_id = 'pos_sess_id_'.strtotime('now');

$transaction_id = $_GET['transaction_id'] ?? 0;

return compact('pos_sess_id','transaction_id');