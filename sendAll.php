
<form id="instruction" method="post" action = "http://localhost/dewText/restoreAll.php">
<?php
    $fileindex = file_get_contents("log/index");//get log
    $enc = json_encode($fileindex);
    print("<input name=\"index\" value=$enc />");//send log to dew

    $content = $fileindex;
    $count = 0;
    while($content != "")
    {
        $lineEnd = strpos($content,"\n");
        $line = substr($content,0,$lineEnd);//get line

        $content = substr($content,$lineEnd+1);//rest of log
        
        $title = $line;//get title
        echo $title."<br>";
        $path = "text/".$title.".txt";//get path
        if(file_exists($path))
        {
            $file= file_get_contents($path);//get file content
            //echo $file."<br>";
            print("<input name='new$count' value = '$file'>");
            $count++;
        }
    }
?>
</form>
<script>
    var form = document.getElementById("instruction");
    form.submit();
</script>