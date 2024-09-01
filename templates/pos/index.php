<?php

$mode = config('pos_mode');

require $mode[app('pos_mode')].'.php';
?>