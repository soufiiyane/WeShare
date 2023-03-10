<?php 
    include("connect.php"); 
    include("includes/classes/Post.php");
    include("includes/classes/User.php");

    if(isset($_GET["userloggedin"])){
        extract($_GET);

        $post = new Post();
        $post->SetcurrenId($userloggedin);
        echo $post->GetRecentleyPosts();

    }
    
?>