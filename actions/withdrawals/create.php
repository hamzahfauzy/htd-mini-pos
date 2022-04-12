<?php

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $db->insert('withdrawals',$_POST['withdrawals']);

    set_flash_msg(['success'=>'Penarikan omset berhasil ditambahkan']);
    header('location:index.php?r=withdrawals/index');
}