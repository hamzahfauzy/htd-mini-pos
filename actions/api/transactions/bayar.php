<?php

if(app('pos_mode') == 'Mode 1')
    require 'mode-1/bayar.php';
else
    require 'mode-2/bayar.php';