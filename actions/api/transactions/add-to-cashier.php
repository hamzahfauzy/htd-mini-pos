<?php

if(app('pos_mode') == 'Mode 1')
    require 'mode-1/add-to-cashier.php';
else
    require 'mode-2/add-to-cashier.php';