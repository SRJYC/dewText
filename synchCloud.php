<?php

$dnum = $_POST['delNum'];
$nd = (int)$dnum;
//del all files
for($i=0; $i< $nd; $i++)
{
    $k = "del".$i;
    $path = $_POST[$k];
    if(file_exists($path))
    {
        //delete file
        unlink($path);
    }

    //remove from index
    $s = strpos($path,"/");
    $d = strrpos($path,".");
    $l = $d - $s - 1;
    $title = substr($path,$s+1,$l);

    $contents = file_get_contents("log/index");
    $contents = str_replace($title."\n", '', $contents);
    file_put_contents("log/index", $contents);
}

$anum = $_POST['newNum'];
$na = (int)$anum;
//add all files
for($i=0; $i< $na; $i++)
{
    $k = "new".$i;
    $path = $_POST[$k];
    $k = "newC".$i;
    $seris = $_POST[$k];
    
    //save file
    $file = fopen($path,"w");
    fwrite($file,$seris);
    fclose($file);

    //add to index
    $s = strpos($path,"/");
    $d = strrpos($path,".");
    $l = $d - $s - 1;
    $title = substr($path,$s+1,$l);

    //check if it exist
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
}

//clear log
file_put_contents("log/log","");
?>