<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('application');
$success_msg = get_flash_msg('success');

if(request() == 'POST')
{
    if(!empty($_FILES['app']['name']['icon_url']))
    {
        $icon_url  = $_FILES['app'];
        $ext  = pathinfo($icon_url['name']['icon_url'], PATHINFO_EXTENSION);
        $name = strtotime('now').'.'.$ext;
        $file = 'uploads/icons/'.$name;
        copy($icon_url['tmp_name']['icon_url'],$file);
        $_POST['app']['icon_url'] = $file;
    }
    else
        $_POST['app']['icon_url'] = $data->icon_url;

    $db->update('application',$_POST['app'],[
        'id' => $data->id
    ]);

    set_flash_msg(['success'=>'Detail Aplikasi berhasil diupdate']);
    header('location:index.php?r=application/index');
    die();
}

return compact('data','success_msg');