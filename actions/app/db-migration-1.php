<?php

$conn  = conn();
$db    = new Database($conn);

$db->get_error = true;
$installation = $db->single('migrations',[
    'filename' => 'migration-1.sql'
]);
if(empty($installation))
{

    $myfile = fopen("../migrations/migration-1.sql", "r") or die("Unable to open file!");
    $query  = fread($myfile,filesize("../migrations/migration-1.sql"));
    fclose($myfile);

    $db->query = $query;
    $db->exec('multi_query');

    echo "Migration 1 Success";
    die();

}
