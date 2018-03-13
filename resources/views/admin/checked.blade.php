<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>401</title>
    <style>
        .container {
            padding-top: 10%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <p style="text-align: center;"><span
                    style="font-family:arial,helvetica,sans-serif;"><span
                    style="color: rgb(0, 136, 194);"><span
                    style="font-size: 64px;">401</span></span></span>
            </p>
            <p style="text-align: center;"><span
                    style="font-size:28px;"><span
                    style="color: rgb(51, 51, 51);">您的人事信息已经审核通过！请到个人中心查看！</span></span>
            </p>
            <p style="text-align: center;"><span
                    style="font-size:28px;"><span
                    style="color: rgb(51, 51, 51);">系统将在 <span id="time" style="color: red;">8</span> 秒钟后自动跳转至您的个人信息页面，如果未能跳转，<a href="/admin/completeinfouser/detail" title="点击访问">请点击</a>。</span></span>
            </p>
        </div>
    </div>
    <script type="text/javascript">  
    delayURL();    
    function delayURL() { 
        var delay = document.getElementById("time").innerHTML;
        var t = setTimeout("delayURL()", 1000);
        if (delay > 0) {
            delay--;
            document.getElementById("time").innerHTML = delay;
        } else {
            clearTimeout(t); 
            window.location.href = "/admin/completeinfouser/detail";
        }        
    } 
</script>
</body>
</html>