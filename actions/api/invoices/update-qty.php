<?php

if(app('pos_mode') == 'Mode 1')
    require 'mode-1/update-qty.php';
else
    require 'mode-2/update-qty.php';