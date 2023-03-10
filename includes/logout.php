<?php
        session_start();
        include("../connect.php");

        if(isset($_SESSION["user"])){
                $userloggedin = $_SESSION["user"];
                
                $updatestatus = $database->prepare("update users set online = 'false' where Id = ?");
                $updatestatus->execute([$userloggedin->Id]);

                session_unset();
                session_destroy();
                header("Location:/projects/WeShare/login.php");
        }
        else{
                header("Location:/projects/WeShare/login.php");
        }
     
?>