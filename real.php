<?php 
     include("includes/header.php");
     include("connect.php");
     include("includes/classes/User.php");
     if(isset($_GET["id"])and isset($_GET["userloggedin"])){
         extract($_GET);
         $str = "";

         function showincase($userloggedin,$array){
            $str = "";
            foreach($array as $message){
            if($message["user_id"]==$userloggedin){
                $str .= '
                <div class="clip sent">
                <div class="text">'.$message["text"].'</div>
                </div>
             ';
            }
            else{
                $str .= '
                <div class="clip received">
                <div class="text">'.$message["text"].'</div>
                </div>';
            } 
        }
        return $str;
        }

        $querymessages = $database->prepare("select * from messages where user_id = ? and user_to = ? or user_id = ? and user_to = ?");
        $querymessages->execute([$userloggedin,$id,$id,$userloggedin]); 
        $str =  showincase($userloggedin,$querymessages) ; 
        echo $str ;


     }


?>