<?php 
    include("includes/header.php");
    include("connect.php");
    include("includes/classes/User.php");
    if(isset($_SESSION["user"])){
        $userloggedin = $_SESSION["user"];
    }
    else{
      header("Location:login.php");
    }

    $getlist = $database->prepare("select list from users where Id = ?");
    $getlist->execute([$userloggedin->Id]);
    $getlist = $getlist->fetchObject();

     $list = explode(",",$getlist->list);
     
     $str = "";

     
    foreach($list as $user){
        $userinfo = new User();
        $userdata = $userinfo->GetUser($user);
       if($userdata!=null){

        $querymessages = $database->prepare("select * from messages where (user_id= ? and user_to= ?) or (user_id= ? and user_to= ?)order by Id desc LIMIT 1");
        $querymessages->execute([$userloggedin->Id,$userdata->Id,$userdata->Id,$userloggedin->Id]);
        $querymessages = $querymessages->fetchObject();
        $seen = "";
        if($querymessages!=null){
           if($querymessages->user_id==$userloggedin->Id){
            $messagedisplay = "You : ".substr($querymessages->text,0,6)."...";
           }
           else{
            $messagedisplay = substr($querymessages->text,0,6)."...";
           }
            if($userloggedin->Id!=$querymessages->user_id){
                if($querymessages->seen=="false"){
                    $seen = '
                                        <div class="point"></div>
                                ';
                 }
            }  
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($querymessages->time); //Time of post
            $end_date = new DateTime($date_time_now); //Current time
            $interval = $start_date->diff($end_date); //Difference between dates 
            if($interval->y >= 1) {
                if($interval == 1)
                    $time_message = $interval->y . " year ago"; //1 year ago
                else 
                    $time_message = $interval->y . " years ago"; //1+ year ago
            }
            else if ($interval->m >= 1) {
                if($interval->d == 0) {
                    $days = " ago";
                }
                else if($interval->d == 1) {
                    $days = $interval->d . " day ago";
                }
                else {
                    $days = $interval->d . " days ago";
                }


                if($interval->m == 1) {
                    $time_message = $interval->m . " month". $days;
                }
                else {
                    $time_message = $interval->m . " months". $days;
                }

            }
            else if($interval->d >= 1) {
                if($interval->d == 1) {
                    $time_message = "Yesterday";
                }
                else {
                    $time_message = $interval->d . " days ago";
                }
            }
            else if($interval->h >= 1) {
                if($interval->h == 1) {
                    $time_message = $interval->h . " hour ago";
                }
                else {
                    $time_message = $interval->h . " hours ago";
                }
            }
            else if($interval->i >= 1) {
                if($interval->i == 1) {
                    $time_message = $interval->i . " minute ago";
                }
                else {
                    $time_message = $interval->i . " minutes ago";
                }
            }
            else {
                if($interval->s < 30) {
                    $time_message = "Just now";
                }
                else {
                    $time_message = $interval->s . " seconds ago";
                }
            }

            
            if($userdata->online=="true"){
                $str .= '
                <div class="person" onclick="showmessages('.$userdata->Id.')">
                            <div class="box">
                                <div class="image"> <img style="object-fit:cover;" src="'.$userdata->Profile_pic.'" width="50px" height="50px" alt=""> </div>
                                <div class="online"></div>
                            </div>
                            <input type="hidden" value="'.$userdata->Id.'"/>
                            <div class="information">
                                <div class="username">'.$userdata->Name.' '. $userdata->Last_Name.'</div>
                                <div class="content"> <div class="message ">'.$messagedisplay.' </div> <div class="time"> &bull; '.$time_message.' </div> </div>
                            </div>
                            
                            <div class="updatemediv'.$querymessages->Id.' status">
                                <input class="updateme" value="'.$querymessages->Id.'" hidden/>
                               
                                '.$seen.'
                            </div>
             </div>';
            }
            else{
                $str .= '
                <div class="person" onclick="showmessages('.$userdata->Id.')">
                            <div class="box">
                                <div class="image"> <img style="object-fit:cover;" src="'.$userdata->Profile_pic.'" width="50px" height="50px" alt=""> </div>
                                <div class="offline"></div>
                            </div>
                            <input type="hidden" value="'.$userdata->Id.'"/>
                            <div class="information">
                                <div class="username">'.$userdata->Name.' '. $userdata->Last_Name.'</div>
                                <div class="content"> <div class="message ">'.$messagedisplay.' </div> <div class="time"> &bull; '.$time_message.' </div> </div>
                            </div>
                       
                            <div class="updatemediv'.$querymessages->Id.' status">
                                <input class="updateme" value="'.$querymessages->Id.'" hidden/>
                                '.$seen.'
                            </div>
             </div>';
            }

        }
        else{
            if($userdata->online=="true"){
                $str .= '
                <div class="person" onclick="showmessages('.$userdata->Id.')">
                            <div class="box">
                                <div class="image"> <img style="object-fit:cover;" src="'.$userdata->Profile_pic.'" width="50px" height="50px" alt=""> </div>
                                <div class="online"></div>
                            </div>
                            <input type="hidden" value="'.$userdata->Id.'"/>
                            <div class="information">
                                <div class="username">'.$userdata->Name.' '. $userdata->Last_Name.'</div>
                                <div class="content"> <div class="message ">Write '.$userdata->Name.' a message</div> <div class="time"> &bull; </div> </div>
                            </div>
                            
                            <div  status">
                                '.$seen.'
                            </div>
             </div>';
            }
            else{
                $str .= '
                <div class="person" onclick="showmessages('.$userdata->Id.')">
                            <div class="box">
                                <div class="image"> <img style="object-fit:cover;" src="'.$userdata->Profile_pic.'" width="50px" height="50px" alt=""> </div>
                                <div class="offline"></div>
                            </div>
                            <input type="hidden" value="'.$userdata->Id.'"/>
                            <div class="information">
                                <div class="username">'.$userdata->Name.' '. $userdata->Last_Name.'</div>
                                <div class="content"> <div class="message ">Write '.$userdata->Name.' a message</div> <div class="time"> &bull; </div> </div>
                            </div>
                       
                            <div  status">  
                                '.$seen.'
                            </div>
             </div>';
            }
        }  
       }
       
    }
    
    echo $str;

?>

