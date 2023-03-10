<?php 
    include("connect.php"); 
    include("includes/classes/Post.php");
    include("includes/classes/User.php");

    if(isset($_POST["text"])){
        extract($_POST);

        $post = new Post();
        $post->SetcurrenId($userloggedin);
        $post->Insertcomment($text,$userloggedin,$postid);
        
    }
  

?>