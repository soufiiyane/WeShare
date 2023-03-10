<?php 

        include("connect.php");
        include("includes/classes/Post.php");
        include("includes/classes/User.php");
        if(isset($_GET["userloggedin"])){
            extract($_GET);

                $querymessages = $database->prepare("select * from messages where (user_id= ? and user_to= ?) or (user_id= ? and user_to= ?)order by Id desc LIMIT 1");
                $querymessages->execute([$userloggedin,$userdata,$userdata,$userloggedin]);
                $querymessages = $querymessages->fetchObject();
                
                if($userloggedin !=$querymessages->user_id){
                    if($querymessages->seen=="false"){
                        $updatequery = $database->prepare("update messages set seen = 'true' where Id = ?");
                        $updatequery->execute([$querymessages->Id]);
                        $val = "true";
                        }
                } 
        }
        


?>

