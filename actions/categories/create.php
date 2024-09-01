<?php

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $_POST['categories']['created_by'] = auth()->user->id;
    $db->insert('categories',$_POST['categories']);

    set_flash_msg(['success'=>'Kategori berhasil ditambahkan']);
    header('location:index.php?r=categories/index');
}