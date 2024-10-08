<?php
ini_set('display_errors',1);
session_start();
date_default_timezone_set('Asia/Jakarta');
if(file_exists('../vendor/autoload.php'))
    require '../vendor/autoload.php';
require '../functions.php';

// do before action
$beforeAction = require '../before-actions/index.php';
if($beforeAction)
{
    $page = config('default_page');

    if(isset($_GET['r'])) // r stand for route
    {
        $page = $_GET['r'];
    }
    
    load_page($page);
}
else
{
    load_page('errors/403');
}
die();