<?php

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $db->insert('customers',$_POST['customers']);

    set_flash_msg(['success'=>'Kustomer berhasil ditambahkan']);
    header('location:index.php?r=customers/index');
}