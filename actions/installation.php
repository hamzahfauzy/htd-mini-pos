<?php

if(request() == 'POST')
{
    $conn  = conn();
    $db    = new Database($conn);

    // save application installation
    $db->insert('application',$_POST['app']);

    // create user login
    $_POST['users']['name'] = "Admin ".$_POST['app']['name'];
    $_POST['users']['password'] = md5($_POST['users']['password']);
    $user = $db->insert('users',$_POST['users']);

    // create roles
    $role = $db->insert('roles',[
        'name' => 'administrator'
    ]);

    // assign role to user
    $db->insert('user_roles',[
        'user_id' => $user->id,
        'role_id' => $role->id
    ]);

    // create roles route
    $role = $db->insert('role_routes',[
        'role_id' => $role->id,
        'route_path' => '*'
    ]);

    if(!file_exists('inv_print')) mkdir('inv_print');
    if(!file_exists('uploads')) mkdir('uploads');
    if(!file_exists('uploads/icons')) mkdir('uploads/icons');
    if(!file_exists('uploads/products')) mkdir('uploads/products');

    set_flash_msg(['success'=>'Instalasi Berhasil']);
    header('location:index.php?r=auth/login');
    die();

}