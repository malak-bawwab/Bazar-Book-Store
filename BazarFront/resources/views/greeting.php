<!-- View stored in resources/views/greeting.php -->

<html>
    <body>
        <h2>Enter Your command</h2>
        <form action="http://192.168.209.132:80/command" method="post">
 <input type="text" name="command"><br>
<p></p>
<input type="submit" value="Run">
</form>
        <h3>Response</h3>
        <h4><?php echo $result; ?></h4>

    </body>
</html>

