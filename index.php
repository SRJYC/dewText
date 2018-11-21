<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8" />
    <title>Dew Text</title>
    <style  type="text/css">
    .title{
        text-align: center;
        font-size: 36px;
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
        width:80%;
        margin: 4px 2px;
    }
    .del{
        background-color: #FF0000;
        width:15%;
    }
    .new {
        background-color: #527a7a;
    }
    .syn{
        background-color: #00BFFF;
        width:15%;
        padding: 17px 2px;
        font-size: 20px;
    }
    </style>
</head>
<body>
    <div>
        <h1 class="title">Notebook</h1>
    </div>
    <?php
        $index = fopen("log/index", "r");
        while(!feof($index))
        {
            $line = substr(fgets($index),0,-1);//remove \n at the end
            if($line!="")
            {
                print("<button class=\"button\" onclick=\"showfun('$line')\">$line</button>
                <button class=\"button del\" onclick=\"del('$line')\">Delete</button>
                <br>");
            }
        }
        fclose($index);
        print("<button class=\"button new\" onclick=\"showfun('new')\"> New </button>
        <button class=\"button syn\" onclick=\"synchronize()\"> synchronize </button><br>");
        print("<button class=\"button syn\" onclick=\"restore()\"> Restore </button>");
    ?>
	<a href="http://ec2-18-224-251-211.us-east-2.compute.amazonaws.com/dewText.zip">Download</a>
    <form id="form" method="get" action="edit.php">
        <input id="message" name="title" type="hidden" />
    </form>
    <form id="form2" method="post" action="delete.php">
        <input id="message2" name="title" type="hidden" />
    </form>
    <form id="form3" method="post" target="frame" action="http://ec2-18-224-251-211.us-east-2.compute.amazonaws.com/dewText/sendLog.php"></form>
    <form id="form4" method="post" target="frame" action="http://ec2-18-224-251-211.us-east-2.compute.amazonaws.com/dewText/sendAll.php"></form>
    
    <script>
        function showfun(title)
        {
            var message = document.getElementById("message");
            message.value = title;
            var form = document.getElementById("form");
            form.submit();
        }

        function del(t)
        {
            var message2 = document.getElementById("message2");
            message2.value = t;
            var form2 = document.getElementById("form2");
            form2.submit();
        }

        function synchronize()
        {
            var form3 = document.getElementById("form3");
            form3.submit();
            //location.reload();
            //style="display: none"
        }

        function restore()
        {
            var form4 = document.getElementById("form4");
            form4.submit();
        }

        var auto = setTimeout(function(){ synchronize(); }, 10000);
    </script>
    <iframe name="frame" style="display: none"></iframe>
</body>
</html>
    