<?php 

    include("connect.php"); 
    include("includes/classes/Post.php");
    include("includes/classes/User.php");

    if(isset($_POST["id"])){
        extract($_POST);

        $deletelike = $database->prepare("delete from likes where post_id = ?");
        $deletelike->execute([$id]);

        $post = new Post();
        $post->SetcurrenId($userloggedin);
        $post->DeletePost($id);
        
    }

?>