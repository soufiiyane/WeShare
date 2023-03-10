<?php 

    include("connect.php");
    extract($_POST);
    $date_time_now = date("Y-m-d H:i:s");
					
    $query = $database->prepare("insert into messages(user_id,user_to,text,seen,time) values (?,?,?,'false',?)");
    $query->execute([$userloggedin,$userto,$text,$date_time_now])


?>