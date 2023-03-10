<?php

    include("connect.php");
    if(isset($_POST["cmnt"]) and isset($_POST["post"])){
        extract($_POST);

        $delete = $database->prepare("delete from comment where Id= ?");
        $delete->execute([$cmnt]);

    }

?>