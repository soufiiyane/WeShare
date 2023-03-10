<?php 

    include("includes/header.php");
    include("connect.php");
    include("includes/classes/User.php");

    if(isset($_SESSION["user"])){
        $userloggedin = $_SESSION["user"];

        if(isset($_GET["id"])){
            
            function showincase($idlogged,$array){
                $str = "";
                foreach($array as $message){
                if($message["user_id"]==$idlogged){
                    $str .= '<div class="clip sent">
                    <div class="text">'.$message["text"].'</div>
                    </div>';
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

            $str = "";
            $usertoo = new User();
            $usertodata = $usertoo->GetUser($_GET["id"]);
            if($usertodata->online=="true"){
                $str .= '
                    <div class="top">
                    <div class="box">
                        <div class="image"> <img style="object-fit:cover;" src="'.$usertodata->Profile_pic.'" width="35px" height="35px" alt=""> </div>
                        <div class="online"></div>
                    </div>
                    <div class="information">
                        <div class="username"> <a href="'.$usertodata->Id.'">'.$usertodata->Name.' '.$usertodata->Last_Name.'</a></div>
                        <div class="name">Active now</div>
                    </div>
                    <div class="options">
                        <button class="info">&bull;&bull;&bull;</button>
                    </div>
                    </div>
                ';
            }
            else{
                $str .= '
                <div class="top">
                <div class="box">
                    <div class="image"> <img style="object-fit:cover;" src="'.$usertodata->Profile_pic.'" width="35px" height="35px" alt=""> </div>
                    <div class="offline"></div>
                </div>
                <div class="information">
                <div class="username"> <a href="'.$usertodata->Id.'">'.$usertodata->Name.' '.$usertodata->Last_Name.'</a></div>
                <div class="name">Offline</div>
                </div>
                <div class="options">
                    <button class="info">&bull;&bull;&bull;</button>
                </div>
                </div>
            ';
            }
            
            $querymessages = $database->prepare("select * from messages where user_id = ? and user_to = ? or user_id = ? and user_to = ?");
            $querymessages->execute([$userloggedin->Id,$_GET["id"],$_GET["id"],$userloggedin->Id]);
                $str .= '
                <div class="middle">
                    <div class="tumbler">
                        <div class="messages">
                            '.showincase($userloggedin->Id,$querymessages).'
                        </div>
                        <div class="seen">Seen</div>
                    </div>
                </div>
                    ';
          
            $str .= '
                <div class="bottom">
                <div class="cup">
                    <textarea id="message" cols="30" rows="1" placeholder="Message..."></textarea>
                    <button onclick="sendmessage('.$_GET["id"].')" class="send">Send</button>
                </div>
                </div> 
            ';
            echo $str;
        }
    }
    else{
      header("Location:login.php");
    }
    
    
?>