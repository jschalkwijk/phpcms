<?php
    $dbc = mysqli_connect('localhost','root','root','nerdcms_db') or die("Erro connecting to server");
    $timestamp = date('Y-m-d H:i:s',strtotime("+5 seconds"));
    $sql = "UPDATE posts SET locked_till = '".$timestamp."' WHERE post_id = ".$_POST['post_id'];
//    die($sql);
    mysqli_query($dbc,$sql) or die('Error connecting to database');
    mysqli_close($dbc);
?>