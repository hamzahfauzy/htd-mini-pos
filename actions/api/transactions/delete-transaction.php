<?php

if(app('pos_mode') == 'Mode 1')
    require 'mode-1/delete-transaction.php';
else
    require 'mode-2/delete-transaction.php';