<?php

    include("connect.php");
    if(isset($_POST["id"]) and isset($_POST["text"])){
        extract($_POST);

        $delete = $database->prepare("update comment set comment_body = ? where Id = ? ");
        $delete->execute([$text,$id]);

    }

?>