<?php
$title = $_POST["title"];

//get file path
$path = "text/".$title.".txt";
if(file_exists($path))
{
    //delete file
    unlink($path);
}

//remove from index
$contents = file_get_contents("log/index");
$contents = str_replace($title."\n", '', $contents);
file_put_contents("log/index", $contents);


//add to log
$file = fopen("log/log","a");
$log = time()."|del|" . $title."\n";
fwrite($file,$log);
fclose($file);

header("Location: index.php");
?>