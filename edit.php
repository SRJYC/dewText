<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Page Title</title>
    <style type="text/css">
    textarea.title  {
        width: 100%;
        height:30px;
        margin-bottom: 1%;
        font-size:22px;
    }
    textarea.text {
        height:500px;
        width:100%;
        font-size:18px;
    }   
    .button {
        background-color: #555555;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 22px;
        margin: 4px 2px;
    }
    </style>
</head>
<body>
<?php
    $title = $_GET["title"];
    $content = "";
    $truetitle = "Unnamed";
    if($title != "new")
    {
        $path = "text/".$title.".txt";
        $content = file_get_contents($path);

        //remove .txt
        $truetitle = rtrim($title, '.txt');
    }

    $content = json_decode($content);
    print("<textarea class=\"title\" id=\"title\" row=\"1\">$truetitle</textarea><br>
    <textarea class=\"text\" id=\"content\">".$content."</textarea><br>
    ");
?>
<button class="button" onclick="save()"> Save </button>
<form id="form" method="post" action="save.php">
        <input id="p_title" name="title" type="hidden">
        <input id="p_content" name="content" type="hidden">
</form>
<script>
    function save()
    {
        var title = document.getElementById("title").value;
        var content = document.getElementById("content").value;

        var form = document.getElementById("form");

        var p_title = document.getElementById("p_title");
        var p_content = document.getElementById("p_content");

        p_title.value = title;
        p_content.value = content;
        
        form.submit();
    }
</script>
</body>
</html>