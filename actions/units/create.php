<?php

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $db->insert('units',$_POST['units']);

    set_flash_msg(['success'=>'Satuan berhasil ditambahkan']);
    header('location:index.php?r=units/index');
}