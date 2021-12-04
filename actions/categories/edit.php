<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('categories',[
    'id' => $_GET['id']
]);

if(request() == 'POST')
{
    $db->update('categories',$_POST['categories'],[
        'id' => $_GET['id']
    ]);

    set_flash_msg(['success'=>'Kategori berhasil diupdate']);
    header('location:index.php?r=categories/index');
}

return compact('data');