<?php 

    include("connect.php"); 
    include("includes/classes/Post.php");
    include("includes/classes/User.php");

    if(isset($_GET["postid"])){
        extract($_GET);

        $post = new Post();
        $post->SetcurrenId($userloggedin);
        
       echo $post->LoadComments($postid);
        
    }

?>