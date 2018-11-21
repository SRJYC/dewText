<?php
function add($path, $content, $toAdd, $toDel, $fileContent)
{
    //check if this is in toDel array
    for($i=0; $i< count($toDel); $i++)
    {
        if($toDel[$i] == $path)//remove it from toDel
        {
            array_splice($array, $i, 1);
            break;
        }
    }

    //check if this is in toAdd array
    $new = true;
    for($i=0; $i< count($toAdd); $i++)
    {
        if($toAdd[$i] == $path)//it's already in toAdd, don't add it again
        {
            $new = false;
            break;
        }
    }
    if($new)//add it to toAdd
    {
        $toAdd[] = $path;
        echo "after add".count($toAdd)."<br>";
    }

    //then refresh content
    $fileContent[$path] = $content;

    return array($toAdd,$toDel,$fileContent);
}

function del($path, $toAdd, $toDel)
{
    //check if this is in toAdd array
    for($i=0; $i< count($toAdd); $i++)
    {
        if($toAdd[$i] == $path)//remove it from toAdd
        {
            array_splice($array, $i, 1);
            break;
        }
    }

    //check if this is in toDel array
    $new = true;
    for($i=0; $i< count($toDel); $i++)
    {
        if($toDel[$i] == $path)//it's already in toDel, don't add it again
        {
            $new = false;
            break;
        }
    }
    if($new)//add it to toDel
    {
        $toDel[] = $path;
    }

    return array($toAdd,$toDel);
}

function getlineArray($content)
{
    $lineArray = array();
    while($content != "")
    {
        $lineEnd = strpos($content,"\n");
        $line = substr($content,0,$lineEnd);//get line
        $lineArray[] = $line;
        $content = substr($content,$lineEnd+1);//rest of log
    }
    return $lineArray;
}

function getTime($line)
{
    $seprator1 = strpos($line,"|");//get first |
    $time = substr($line,0,$seprator1);
    return $time;
}

function getFlag($line)
{
    $seprator1 = strpos($line,"|");//get first |
    $flag = substr($line,$seprator1+1,3);
    return $flag;
}

function getTitle($line)
{
    $seprator2 = strrpos($line,"|");//get second |
    $title = substr($line,$seprator2+1);
    return $title;
}

$seris = $_POST["log"];
$logCloud = str_replace("\\n","\n",$seris);
echo "logcloud:".$logCloud."<br>";
$logDew = file_get_contents("log/log");

$fileContent = array();//file path => content
$toAdd = array();// file path
$toDel = array();// file path

$lineCloud = getlineArray($logCloud);//array of logCloud, each element is a line
$lineDew = getlineArray($logDew);

$indexCloud = 0;
$indexDew = 0;
$lengthCloud = count($lineCloud);
$lengthDew = count($lineDew);

$count = 0;
while($indexCloud < $lengthCloud && $indexDew < $lengthDew)//none of them reach end
{
    $line1 = $lineCloud[$indexCloud];
    $line2 = $lineDew[$indexDew];

    $time1 = getTime($line1);
    $time2 = getTime($line2);

    if((int)$time1<(int)$time2)//cloud is earlier
    {
        $flag = getFlag($line1);
        $title = getTitle($line1);
        $path = "text/".$title.".txt";
        if($flag == "del")
        {
            list($toAdd,$toDel) = del($path,$toAdd,$toDel);
        }
        else if($flag == "new")
        {
            $fc= $_POST["new$count"];//get content
            $count++;
            list($toAdd,$toDel,$fileContent) = add($path,$fc,$toAdd,$toDel,$fileContent);

        }
        $indexCloud ++;
    }
    else{//dew is earlier
        $flag = getFlag($line1);
        $title = getTitle($line1);
        $path = "text/".$title.".txt";
        if($flag == "del")
        {
            list($toAdd,$toDel) = del($path,$toAdd,$toDel);
        }
        else if($flag == "new")
        {
            if(file_exists($path))
            {
                $fc = file_get_contents($path);//get content
                list($toAdd,$toDel,$fileContent) = add($path,$fc,$toAdd,$toDel,$fileContent);
            }
        }
        $indexDew ++;
    }
}

for(; $indexCloud<$lengthCloud; $indexCloud++)//log cloud remains
{
    $line1 = $lineCloud[$indexCloud];
    $flag = getFlag($line1);
    $title = getTitle($line1);
    $path = "text/".$title.".txt";
    if($flag == "del")
    {
        list($toAdd,$toDel) = del($path,$toAdd,$toDel);
    }
    else if($flag == "new")
    {
        var_dump($_POST);
        $fc = $_POST["new$count"];//get content
        $count++;
        list($toAdd,$toDel,$fileContent) = add($path,$fc,$toAdd,$toDel,$fileContent);
    }
}

for(; $indexDew<$lengthDew; $indexDew++)
{
    $line2 = $lineDew[$indexDew];
    $flag = getFlag($line2);
    $title = getTitle($line2);
    $path = "text/".$title.".txt";
    if($flag == "del")
    {
        list($toAdd,$toDel) = del($path,$toAdd,$toDel);
    }
    else if($flag == "new")
    {
        if(file_exists($path))
        {
            $fc = file_get_contents($path);//get content
            list($toAdd,$toDel,$fileContent) = add($path,$fc,$toAdd,$toDel,$fileContent);
        }
    }
}

print("<form id=\"instruction\" method=\"post\" action = \"http://ec2-18-224-251-211.us-east-2.compute.amazonaws.com/dewText/synchCloud.php\">");

//del all files
$dnum = count($toDel);
print("<input name=\"delNum\" value=\"$dnum\" />");
for($i=0; $i< $dnum; $i++)
{
    $path = $toDel[$i];
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

    //prepare form
    print("<input name=\"del$i\" value=\"$path\" />");
}

//add all files
$anum = count($toAdd);
print("<input name=\"newNum\" value=\"$anum\" />");
for($i=0; $i< $anum; $i++)
{
    $path = $toAdd[$i];
    $content = $fileContent[$path];

    //save file
    $file = fopen($path,"w");
    fwrite($file,$content);
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

    //prepare form
    print("<input name=\"new$i\" value='$path' />");
    print("<input name=\"newC$i\" value='$content' />");
}

print("</form>");

//clear log
file_put_contents("log/log","");
?>

<script>
    document.getElementById("instruction").submit();
</script>