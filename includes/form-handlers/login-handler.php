<?php
    if(isset($_POST["login"])){
        $query = $database->prepare("select * from users where Email = ? and Password = ?");
        $query->execute([$_POST["email"],md5($_POST["password"])]);
        if($query->rowCount()!=0){
    
            $query = $query->fetchObject();
            $_SESSION["user"] = $query;

            
            $updatestatus = $database->prepare("update users set online = 'true' where Id = ?");
            $updatestatus->execute([$query->Id]);

            header("Location:Index.php");

        }
        else{
            $nothinghappen = "Login faild";
        }
    }
?>