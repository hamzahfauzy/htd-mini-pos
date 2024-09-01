<?php

$conn  = conn();
$db    = new Database($conn);

$db->get_error = true;
$migrations = $db->single('migrations');
if(is_string($migrations) && stringContains($migrations,"doesn't exist"))
{
    $query = 'CREATE TABLE migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(100) NOT NULL,
                execute_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )';

    $db->query = $query;
    $db->exec('multi_query');
    
}

$folder = "../migrations";
if(!file_exists($folder)) die('no migration folder');
$files = preg_grep('~^.*.sql$~', scandir($folder));

$isRun = false;

if(!empty($files))
{
    try {
        $db->query = 'SELECT * FROM migrations WHERE filename IN ("'.implode('","',$files).'")';
        $all_migrations = $db->exec('all');
        $all_migrations = array_map(function($migration){
            return $migration->filename;
        }, $all_migrations);

        foreach($files as $file)
        {
            if(in_array($file, $all_migrations)) continue;
            
            $actualFile = $folder .'/'. $file;
            $myfile = fopen($actualFile, "r") or die("Unable to open file!");
            $query  = fread($myfile, filesize($actualFile));
            fclose($myfile);
            
            $db->query = "INSERT INTO migrations (filename) VALUES ('$file');" . $query;
            $db->exec('multi_query');

            $isRun = true;
            
            echo "File $file: Migration Success\n";
        }
    } catch (\Throwable $th) {
        throw $th;
    }

}

if(!$isRun)
{
    echo "Nothing to migrate\n";
}

die();
