<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('units',[
    'id' => $_GET['id']
]);

if(request() == 'POST')
{
    $db->update('units',$_POST['units'],[
        'id' => $_GET['id']
    ]);

    set_flash_msg(['success'=>'Satuan berhasil diupdate']);
    header('location:index.php?r=units/index');
}

return compact('data');