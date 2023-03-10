<?php 
    include("includes/header.php");
    include("connect.php");
    include("includes/classes/Post.php");
    include("includes/classes/User.php");

    if(isset($_SESSION["user"])){
        $userloggedin = $_SESSION["user"];
    }
    else{
      header("Location:login.php");
    }
    $post = new Post();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="assets/css/read.css">
    <link rel="stylesheet" href="assets/css/postcard.css">

    
</head>
<body>

  <div class="container">
       <div class="read_wrapper">

            <div class="left_side">
                <?php 
                    if(isset($_GET["post_id"])){
                        $post->ReadMore($_GET["post_id"]);
                    }
                ?>
            </div>

            <div class="right_side">
                <ul class="list-group" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;">
                    <li class="list-group-item  bg-success active">
                    ShareIT BlogIT's
                    </li>
                    <?php 
                        $post->GetRecentleyPosts();
                    ?>
                </ul>
            </div>

       </div>
  </div>

</body>
</html>