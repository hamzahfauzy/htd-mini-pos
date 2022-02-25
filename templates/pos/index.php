<?php

$mode = [
    'Mode 1' => 'mode_1',
    'Mode 2' => 'mode_2',
];

require $mode[app('pos_mode')].'.php';
?>