
<form id="instruction" method="post" action = "http://localhost/dewText/synchDew.php">
<?php
    $log= file_get_contents("log/log");//get log
    $enc = json_encode($log);
    print("<input name=\"log\" value=$enc />");//send log to dew
    //echo $log;
    $content = $log;
    $count = 0;
    while($content != "")
    {
        $lineEnd = strpos($content,"\n");
        $line = substr($content,0,$lineEnd);//get line

        $content = substr($content,$lineEnd+1);//rest of log

        $seprator1 = strpos($line,"|");//get first |
        $seprator2 = strrpos($line,"|");//get second |

        $flag = substr($line,$seprator1+1,3);//get flag, del or new
        
        if($flag == "new")//ignore del
        {
            $title = substr($line,$seprator2+1);//get title
            $path = "text/".$title.".txt";//get path
            if(file_exists($path))
            {
                $file= file_get_contents($path);//get file content
    
                print("<input name='new$count' value='$file'>");
                $count++;
            }
        }
    }

?>
</form>
<script>
    var form = document.getElementById("instruction");
    form.submit();
</script>