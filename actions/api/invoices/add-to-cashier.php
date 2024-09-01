<?php
$mode = [
    'Mode 1' => 'mode-1',
    'Mode 2' => 'mode-2',
];

if(isset($mode[app('pos_mode')])){
    require $mode[app('pos_mode')] . '/add-to-cashier.php';
}