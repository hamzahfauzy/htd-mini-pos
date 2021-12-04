<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('customers',[
    'id' => $_GET['id']
]);

if(request() == 'POST')
{
    $db->update('customers',$_POST['customers'],[
        'id' => $_GET['id']
    ]);

    set_flash_msg(['success'=>'Kustomer berhasil diupdate']);
    header('location:index.php?r=customers/index');
}

return compact('data');