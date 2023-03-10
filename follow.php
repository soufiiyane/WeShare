<?php 
    include("connect.php");
    include("includes/classes/Post.php");
    include("includes/classes/User.php");
    include("includes/header.php");
    if(isset($_POST["userloggedin"]) and isset($_POST["uservisit"])){
        extract($_POST);

            $friendquery = $database->prepare("select * from users where Id = ?");
            $friendquery->execute([$userloggedin]);
            $friendquery = $friendquery->fetchObject();
            $list = explode(",",$friendquery->list);

            $liststring = $friendquery->list.",".$uservisit;
              $updatequery = $database->prepare("update users set list = ? where Id = ?");
              $updatequery->execute([$liststring,$userloggedin]);
              $user = $database->prepare("select * from users where Id = ?");
              $user->execute([$userloggedin]);
              $user = $user->fetchObject();
              $_SESSION["user"] = $user;
              $userloggedin = $_SESSION["user"];
    }

?>