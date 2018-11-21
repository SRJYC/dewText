<?php
//clear log
file_put_contents("log/log","");
//clear index
file_put_contents("log/index","");
//delete rest files
$files = glob('text/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}

//read
$index = $_POST['index'];
echo $index."<br>";

$content = str_replace("\\n","\n",$index);
echo $content."<br>";
file_put_contents("log/index",$content);
$count = 0;
while($content != "")
{
    $lineEnd = strpos($content,"\n");
    $line = substr($content,0,$lineEnd);//get line

    $content = substr($content,$lineEnd+1);//rest of log
    
    $title = $line;//get title
    echo $title."<br>";

    $fc = $_POST["new$count"];
    $count++;
    echo $fc."<br>";

    $path = "text/".$title.".txt";

    //save file
    file_put_contents($path,$fc);
}
?>