<?php 
    session_start();
   include("connect.php");
   include("includes/classes/Post.php");
   include("includes/classes/User.php");
    if(isset($_SESSION["user"])){
        $userloggedin = $_SESSION["user"];
    }
    else{
    header("Location:login.php");
    }

        if(isset($_GET["post_id"])){
             $postid = $_GET["post_id"];
        }
      
        if(isset($_POST["special"])){
            $postid = $_POST["special"];
        }
    $get_likes = $database->prepare("select * from posts where Id = ?");
    $get_likes->execute([$postid]);
    $get_likes = $get_likes->fetchObject();
    
    $likes = $get_likes->Num_Likes;
    $user_liked = $get_likes->user_id;

    $userdetails = $database->prepare("select * from users where Id = ? ");
    $userdetails->execute([$user_liked]);


    //like handler
    if(isset($_POST["like"])){
        $likes++;
        $update_query = $database->prepare("update posts set Num_Likes = ? where Id = ?");
        $update_query->execute([$likes,$postid]);

        $insertlike = $database->prepare("insert into likes(user_id, post_id) values(?,?)");
        $insertlike->execute([$userloggedin->Id,$postid]);
    }

    if(isset($_POST["unlike"])){
        $likes--;
        $update_query = $database->prepare("update posts set Num_Likes = ? where Id = ?");
        $update_query->execute([$likes,$postid]);

        $insertlike = $database->prepare("delete from likes where user_id = ? and post_id = ?");
        $insertlike->execute([$userloggedin->Id,$postid]);
    }


    //check for previos likes;
    $check_query  = $database->prepare("select * from likes where post_id = ? and user_id = ?");
    $check_query->execute([$postid,$userloggedin->Id]);

    if($check_query->rowCount()>0){
        echo '
                <form action="like.php?post_id?='.$postid.'" method="POST">
                    <div>
                        <button name="unlike" type="submit" style="width: 100%;position:relative;" class="btn btn-sm btn-secondary"><i class="fa-solid fa-thumbs-down"></i> Unlike
                        <small>'.$likes.' Likes</small>
                        </button> 
                        <input type="text" value="'.$postid.'" hidden name="special" />
                    </div>
                </form>
            ';   
    }
    else{
        echo '
                <form action="like.php?post_id?="'.$postid.'" method="POST" >
                    <div >
                        <button name="like"  type="submit" style="width: 100%;position:relative;" class="btn btn-sm btn-primary"><i class="fa-solid fa-thumbs-up"></i> Like 
                        <input type="text" value="'.$postid.'" hidden name="special"/>
                        <small>'.$likes.' Likes</small>
                        </button> 
                    </div>
                </form>
            ';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
</body>
</html>