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
        $string = $friendquery->list;
        $string = str_replace(",".$uservisit,"",$string);

        $updatequery = $database->prepare("update users set list = ? where Id = ?");
        $updatequery->execute([$string,$userloggedin]);
        $user = $database->prepare("select * from users where Id = ?");
        $user->execute([$userloggedin]);
        $user = $user->fetchObject();
        $_SESSION["user"] = $user;
        $userloggedin = $_SESSION["user"];
    }
?>





          
       