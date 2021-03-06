<?php

$route = get_route();

if(startWith($route,'app/db-')) return true;
// if(stringContains(url(),"localhost"))
//     return true;
// else
//     return false;

if(startWith($route,'api'))
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
    header("Content-Type: application/json");
}

// check if installation is exists
$conn  = conn();
$db    = new Database($conn);

$installation = $db->single('application');
if(!$installation && $route != "installation")
{
    header("location:index.php?r=installation");
    die();
}

$auth = auth();
if(!isset($auth->user) && !in_array($route, ['auth/login','installation']))
{
    header("location:index.php?r=auth/login");
    die();
}

// check if route is allowed
if(isset($auth->user) && !is_allowed($route, $auth->user->id) && $route != 'auth/logout')
{
    return false;
}

return true;