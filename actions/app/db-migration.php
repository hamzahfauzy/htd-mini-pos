<?php

$conn  = conn();
$db    = new Database($conn);

$db->get_error = true;
$installation = $db->single('migrations');
if(stringContains($installation,"doesn't exist"))
{
    $myfile = fopen("../migrations/migration.sql", "r") or die("Unable to open file!");
    $query  = fread($myfile,filesize("../migrations/migration.sql"));
    fclose($myfile);
    
    $db->query = $query;
    $db->exec('multi_query');
    
    echo "Create migration table Success";
    die();
}
