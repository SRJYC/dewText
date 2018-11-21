<?php
$title = $_POST["title"];
$content = $_POST["content"];
//echo $content."<br>";
$content = json_encode($content);
//echo $content."<br>";
//save file
$path = "text/".$title.".txt";
file_put_contents($path,$content);

//check if $path exist in index
$file = fopen("log/index","r+");
$new = true;
while(!feof($file))
{
    $line = substr(fgets($file),0,-1);//remove \n at the end
    if($line == $title)
    {
        $new=false;
        break;
    }
}
if($new)
{
    fwrite($file,$title);
    fwrite($file,"\n");
}
fclose($file);

//add to log
$file = fopen("log/log","a");
$log = time()."|new|" . $title."\n";
fwrite($file,$log);
fclose($file);

header("Location: index.php");
?>