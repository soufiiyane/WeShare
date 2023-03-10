<?php 
include("connect.php");
include("includes/classes/User.php");

if(isset($_GET["userloggedin"]) and isset($_GET["uservisit"])){
        extract($_GET);
        $user = new User();
        $userdetails = $user->GetUser($uservisit);

        $getnumlikes = $database->prepare("select count(Id) as number from likes WHERE user_id = ? GROUP by user_id");
        $getnumlikes->execute([$userdetails->Id]);
        if($getnumlikes->rowCount()>0){
          $getnumlikes = $getnumlikes->fetchObject();
          $likes = $getnumlikes->number;
        }
        else{
          $likes = "0";
        }

        $getnumposts = $database->prepare("select count(post_body) as number from posts where user_id = ? GROUP by user_id");
        $getnumposts->execute([$userdetails->Id]);
        if($getnumposts->rowCount()>0){
          $getnumposts = $getnumposts->fetchObject();
          $pubs = $getnumposts->number;
        }
        else{
          $pubs = "0";
        }


        $getnumcomments = $database->prepare("select COUNT(Id) as number from comment where user_id = ? GROUP by user_id");
        $getnumcomments->execute([$userdetails->Id]);
        if($getnumcomments->rowCount()>0){
          $getnumcomments = $getnumcomments->fetchObject();
          $comments = $getnumcomments->number;
        }
        else{
          $comments = "0";
        }

        $friendquery = $database->prepare("select list from users where Id = ?");
        $friendquery->execute([$userloggedin]);
        $friendquery = $friendquery->fetchObject();
        $list = explode(",",$friendquery->list);

        $str = '
                <div class="left-side">
                    <div class="image-container">
                    <img src="'.$userdetails->Profile_pic.'"/>
                    </div>
                </div>
            
        ';
        if($userloggedin!=$uservisit){
          if(in_array($uservisit,$list)){
            $str .='
            <div class="right-side">
                <div class="user-details">
                <div><h3>'.$userdetails->Name.'</h3></div>
                        <div>
                            <button id="unfollow" onclick="unfollow('.$userdetails->Id.')" class="btn btn-sm btn-secondary">
                                 Unfollow <i class="fa-solid fa-user"></i>
                            </button>
                            <a id="message" class="btn btn-sm btn-primary" href="chat.php?chatme='.$uservisit.'">
                                 Message <i class="fa-brands fa-facebook-messenger color-primary lapa"></i>
                            </a>
                        </div>  
                </div>
                <div class="posts_details">
                    <p><strong>'.$pubs.'</strong> <small>Publication</small></p>
                    <p><strong>'.$likes.'</strong> <small>Likes</small></p>
                    <p><strong>'.$comments.'</strong> <small>Comments</small></p>
                </div>
          </div>
            ';
        }
        else{
            $str .='
            <div class="right-side">
                <div class="user-details">
                    <div><h3>'.$userdetails->Name.'</h3></div>
                        <div>
                            <button id="follow" onclick="follow('.$userdetails->Id.')" class="btn btn-sm btn-primary">
                                Follow <i class="fa-solid fa-user"></i>
                            </button>
                        </div>  
                </div>
                
                <div class="posts_details">
                    <p><strong>'.$pubs.'</strong> <small>Publication</small></p>
                    <p><strong>'.$likes.'</strong> <small>Likes</small></p>
                    <p><strong>'.$comments.'</strong> <small>Comments</small></p>
                </div>
            </div> 
            ';
        }
        }
        else{
          $str .='
          <div class="right-side">
              <div class="user-details">
               <div><h3>'.$userdetails->Name.'</h3></div> 
              </div>
              <div class="posts_details">
                  <p><strong>'.$pubs.'</strong> <small>Publication</small></p>
                  <p><strong>'.$likes.'</strong> <small>Likes</small></p>
                  <p><strong>'.$comments.'</strong> <small>Comments</small></p>
              </div>
          </div> 
          ';
        }
  echo $str;
}


?>