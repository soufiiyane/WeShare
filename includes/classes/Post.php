<?php

class Post{

    private $databasehost="localhost";
    private $databaseuser = "admin";
    private $databasepassword="admin";
    private $database="weshare";
    private $currentuserID;
    
    protected function connect(){
        $databasequery  = "mysql:host=" . $this->databasehost . ";dbname=" . $this->database;
        $pdo = new PDO($databasequery,$this->databaseuser,$this->databasepassword);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        return $pdo;
    }

    public function InsertPost($body,$userid,$categorie,$image=''){
        $body = str_replace("\r\n","\n",$body);
        $body = nl2br($body);
       
      if($body != ""){
        $insertquery = $this->connect()->prepare("insert into posts(post_body,user_id,categorie,image) values(?,?,?,?)");
        if($insertquery->execute([$body,$userid,$categorie,$image])){
            return true;
        }
        else{
            return false;
        }
      }
      else{
          return false;
      }
    }

    // setting the id of the current user so we can get his details;
    public function SetcurrenId($id){
        $this->currentuserID = $id;
        return $this->currentuserID;
    }

    public function Insertcomment($body,$user_id,$post_id){
        $commentquery = $this->connect()->prepare("insert into comment(comment_body,user_id,post_id) values(?,?,?)");
        $commentquery->execute([$body,$user_id,$post_id]);
    }

    public function LoadComments($postid){
        $loadquery = $this->connect()->prepare("select * from comment where post_id=?");
        $loadquery->execute([$postid]);
        $str = " ";
       if($loadquery->rowCount()!=0){
            foreach($loadquery as $query){
                $user = new User();
                $userdetails = $user->GetUser($query["user_id"]);
                if($userdetails->Id==$this->currentuserID){
                    $str .= '
                    <div class="comments_posted" style="display: flex;gap:15px;  padding:5px; margin:5px 0; width:100%; box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px; " >
                        <a href="user.php?profile_username='.$query["user_id"].'"><img style="object-fit:cover;border-radius:50%" src="'.$userdetails->Profile_pic.'" width="40" height="40"/> </a>    
                        <div style="display: flex;flex-direction:column; position: relative; padding-right: 15px; min-width: 200px;  background-color:#eee;padding:5px;border-radius:15px;padding-right:15px;">
                            <small>'.$userdetails->Name." ".$userdetails->Last_Name.'</small>
                            <p contenteditable="false" class="editmezone" id="editme'.$query["Id"].'" style="font-size: 15px; color:grey;margin:0;padding:0px;">'.$query["comment_body"].'</p>
                            <button onclick="deletecomment('.$query["Id"].','.$postid.')" class="deletecomment color-danger"><i class="fa-solid fa-trash"></i></button>
                            <button id="btnedit'.$query["Id"].'" onclick="editable('.$query["Id"].')"  class="editcomment color-secondary"><i class="fa-solid fa-edit"></i></button>
                            <button style="display:none;color:green" id="apply'.$query["Id"].'" onclick="applyedit('.$query["Id"].','.$postid.')"  class="editcomment color-info"><i class="fa-solid fa-check"></i></button>
                            </div>
                    </div>
                    ';
                }
                else{
                    $str .= '
                    <div class="comments_posted" style="display: flex;gap:15px;  padding:5px; margin:5px 0; width:100%; box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px; " >
                        <a href="user.php?profile_username='.$query["user_id"].'"><img style="object-fit:cover;border-radius:50%" src="'.$userdetails->Profile_pic.'" width="40" height="40"/> </a>    
                        <div style="display: flex;flex-direction:column; position: relative; padding-right: 15px; min-width: 200px;  background-color:#eee;padding:5px;border-radius:15px;padding-right:15px;">
                            <small>'.$userdetails->Name." ".$userdetails->Last_Name.'</small>
                            <p contenteditable="false" style="font-size: 15px; color:grey;margin:0;padding:0px;">'.$query["comment_body"].'</p>
                        </div>
                    </div>
                    ';
                }
            }
            return $str;
        }
        else{
            
            return  '<center style="display:flex;align-items:center;justify-content:center;" class="text alert text-secondary">No comments available for this post now</center>';
        }
    }

    public function LoadTools($id,$userid,$travel,$postid){
            $id= $this->currentuserID;
            if($id==$userid){
                return '
                <div class="tools" style="display: flex;align-items:center;">
                    <small>'.$travel.'</small>
                    <div >
                        <li class="nav-item dropdown" style="list-style: none;">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            </a>
                                <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li style="padding: 10px 0;">
                                    <input value="'.$postid.'" hidden name="postdelete"/>
                                    <button onclick="deletepost('.$postid.')" class="dropdown-item"><i class="fa-solid fa-trash text-danger"></i> Move to Trash</button>
                                    </li>
                                </ul>
                        </li>
                    </div>
                </div>
                ';
            }
            else{
                return '
                    <small>'.$travel.'</small>
                ';
            }
    }


    public function GetCategoriesNumber($catg){
        $querry = $this->connect()->prepare("select COUNT(post_body) as number from posts group by categorie having categorie = ?");
        $querry->execute([$catg]);
        if($querry->rowCount()>0){
            $querry = $querry->fetchObject();
            echo $querry->number;
        }
        else{
            echo "0";
        }
    }

    public function LoadPosts(){
        $getposts = $this->connect()->prepare("select * from posts order by Id DESC");
        $getposts->execute();
        $post = new Post();
        $connecteduser = new User();
        $connecteduser_obj = $connecteduser->GetUser($this->currentuserID);
        foreach($getposts as $post){

            ?>

                <script>

                function toggel<?php echo $post["Id"] ?>(){
                var element = document.getElementById("togglecomment<?php echo $post["Id"] ?>");
                    if(element.style.display=="block"){
                        element.style.display = "none"
                    }
                    else{
                        element.style.display = "block"
                    }
                } 

                </script>
        
            <?php

            $user = new User();
            $userdetails = $user->GetUser($post["user_id"]);
            $post_id = $post["Id"];
            $desc = $post["post_body"];
            if(strlen($desc)>150){
                $strcut = substr($desc,0,150);
                $desc = $strcut . '...<a href="read.php?post_id='.$post["Id"].'" class="nav-item" style="text-decoration:none;">Read More</a>';
            }
            
            $userlist = new User();
            $userlistdetails = $userlist->GetUser($this->currentuserID);
            $list = explode(",",$userlistdetails->list);
            $postuserid = $post["user_id"];
            
           if(in_array($postuserid,$list) or $postuserid == $this->currentuserID){
            if($post["image"]!=""){
                echo '
                <div class="post_card" ">
                    <div class="first_section">
                        <div>
                       <a href="user.php?profile_username='.$postuserid.'"><img src="'.$userdetails->Profile_pic.'"/></a>
                       <a style="text-decoration:none" href="user.php?profile_username='.$postuserid.'"> <p>'.$userdetails->Name ." " .$userdetails->Last_Name .'</p></a>
                        </div>
                        '.$this->LoadTools($this->currentuserID,$post["user_id"],$post["categorie"],$post["Id"]).'
                    </div>
                    <div class="second_section">
                        <p>'.$desc.'</p>
                        <img src="'.$post["image"].'"/>
                    </div>
                    <div class="comment_section" style="margin: 5px 0;">
                    <div  style=" display: flex;align-items: center;justify-content: space-between;">
                        <img src="'.$connecteduser_obj->Profile_pic .'" width="40" height="40" style="border-radius:2px;object-fit:cover"/>
                        <input hidden name="postid" value="'.$post["Id"].'"/>
                        <input name="comment_body" id="commenttext'.$post["Id"].'" style="width: 500px;" autocomplete="off" type="text" class="form-control form-control-sm" placeholder="Say something..." />
                        <button  onclick="insertcomment('.$post["Id"].')" name="comment" class="btn btn-sm btn-secondary">ShareIT</button>
                    </div>
                    </div>
                    <div class="toggle" style="width: 100%;position:relative;display:flex; align-items: center;justify-content: space-between;">

                    <iframe scrolling="no" frameborder="0" style="height: 30px;" src="like.php?post_id='.$post_id.'"></iframe>
    
                    <button  onClick="javascript:toggel'.$post["Id"].'()" style="width: 50%;position:relative;background-color:#dfdff6" class="btn btn-sm "><i class="fa-solid fa-comment"></i> Comments</button>
                    </div>
                    <div id="togglecomment'.$post["Id"].'" style="display:none;max-height:200px;overflow-Y:auto;">
                      
                        <div id="loadhere'.$post["Id"].'">
                         '. $this->LoadComments($post["Id"]) .'
                        </div>
                    
                    </div>
                </div>
                ';
        }
        else{
            echo '
            <div class="post_card" ">
                <div class="first_section">
                    <div>
                    <a href="user.php?profile_username='.$postuserid.'"><img src="'.$userdetails->Profile_pic.'"/></a>
                    <a style="text-decoration:none" href="user.php?profile_username='.$postuserid.'"> <p>'.$userdetails->Name ." " .$userdetails->Last_Name .'</p></a>
                    </div>
                    '.$this->LoadTools($this->currentuserID,$post["user_id"],$post["categorie"],$post["Id"]).'
                </div>
                <div class="second_section">
                    <p>'.$post["post_body"].'</p>
                </div>
                <div class="comment_section" style="margin: 5px 0;">
                <div  style=" display: flex;align-items: center;justify-content: space-between;">
                    <img src="'.$connecteduser_obj->Profile_pic .'" width="40" height="40" style="border-radius:2px;object-fit:cover"/>
                    <input hidden name="postid" value="'.$post["Id"].'"/>
                    <input name="comment_body" id="commenttext'.$post["Id"].'" style="width: 500px;" autocomplete="off" type="text" class="form-control form-control-sm" placeholder="Say something..." />
                    <button  onclick="insertcomment('.$post["Id"].')" name="comment" class="btn btn-sm btn-secondary">ShareIT</button>
             </div>
                </div>
                <div class="toggle" style="width: 100%;position:relative;display:flex; align-items: center;justify-content: space-between;">
                    <iframe scrolling="no" frameborder="0" style="height: 30px;" src="like.php?post_id='.$post_id.'"></iframe>
                    <button onClick="javascript:toggel'.$post["Id"].'()" style="width: 50%;position:relative; background-color:#dfdff6" class="btn btn-sm"><i class="fa-solid fa-comment"></i> Comments</button>
                </div>
                <div id="togglecomment'.$post["Id"].'" style="display:none;max-height:200px;overflow-Y:auto;">
                
                    <div id="loadhere'.$post["Id"].'">
                    '. $this->LoadComments($post["Id"]) .'
                    </div>

                </div>
            
          </div>
        
            ';
        }
    }
           }

    }



    public function GetRecentleyPosts(){

        $recentlyquery = $this->connect()->prepare("select * from posts ORDER by Id DESC LIMIT 5");
        $recentlyquery->execute();
        foreach($recentlyquery as $data){
            $user2 = new User();
            $userdetails2 = $user2->GetUser($data["user_id"]);
            
            $desc = $data["post_body"];
            $strcut = substr($desc,0,15);
            $desc = $strcut . '...<a href="read.php?post_id='.$data["Id"].'" class="nav-item" style="text-decoration:none;">Read More</a>';
            if($data["image"]!=null){
                echo '
                <li class="list-group-item d-flex justify-content-between align-items-center" style="display: flex;flex-direction:column;position:relative;height:70px">
                <div style="display: flex; justify-content:space-between;align-items:center;width:100%">
                  <img src="'.$data["image"].'" style="width: 30px; height:30px;object-fit:cover;"/>
                      <span>'.$desc.'</span>
                </div>
                 <small style="color: grey;position:absolute;bottom:5px;left:15px;" >ShareIT By : '. $userdetails2->Name ." ".$userdetails2->Last_Name .'</small>
                 </li>
                ';
            }
        }

    }

    public function DeletePost($postid){
        $querydelete = $this->connect()->prepare("delete from posts where Id = ?");
        $querydelete->execute([$postid]);
        $deletecomments = $this->connect()->prepare("delete from comment where post_id = ?");
        $deletecomments->execute([$postid]);
    }

    public function ReadMore($id){

        $query = $this->connect()->prepare("select * from posts where Id = ?");
        $query->execute([$id]);
        $query = $query->fetchObject();

        $user  = new User();
        $getuser = $user->GetUser($query->user_id);

        if($query->image != null){
        echo '
            <div class="card mb-3" style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;">
            <img src="'.$query->image.'" style="width:100%;" class="card-img-top img-fluid" >
            <div class="card-body">
                <p class="card-text">'.$query->post_body.'</p>
                <p class="card-text"><small class="text-muted">ShareIT By : '.$getuser->Name.  " " . $getuser->Last_Name .'</small></p>
            </div>
            </div>
        ';
        }
        else{
            echo '
                <div class="card mb-3" >
                <div class="card-body">
                    <p class="card-text">'.$query->post_body.'</p>
                    <p class="card-text"><small class="text-muted">ShareIT By : '.$getuser->Name.  " " . $getuser->Last_Name .'</small></p>
                </div>
                </div>
            ';
        }
    }
    

    
    public function GetProfilePosts(){
        $query = $this->connect()->prepare("select * from posts where user_id = ? order by Id DESC");
        $query->execute([$this->currentuserID]);
        if($query->rowCount()>0){
            foreach($query as $data){

                $user = new User();
                $getname = $user->GetUser($data["user_id"]);

                $numcomment = $this->connect()->prepare("select count(id) as number from comment GROUP BY post_id HAVING post_id = ?");
                $numcomment->execute([$data["Id"]]);
                if($numcomment->rowCount()>0){
                    $numcomment = $numcomment->fetchObject();
                    $number  = $numcomment->number;
                }
                else{
                    $number = 0;
                }


                if($data["image"]!=null){
                    echo '
                    <div class="big-container">
                        <div class="post-container">
                            <div class="left-post">
                                <img src="'.$data["image"].'"/>
                            </div>
                            <div class="right-post">
                                <h5>'.$getname->Name." ".$getname->Last_Name.'</h5>
                                <p>'.$data["post_body"].'</p>
                                </div>
                        </div> 
                        <div class="face2">
                            <p><i class="fa-solid fa-thumbs-up"></i> '.$data["Num_Likes"].'</p>
                            <p><i class="fa-solid fa-comment"></i> '.$number.'</p>
                        </div>
                    </div>
                        ';
                }
                else{
                    echo '
                    <div class="big-container">
                    <div class="post-container-text">
                       <h5>'.$getname->Name." ".$getname->Last_Name.'</h5>
                       <hr>
                       <p>'.$data["post_body"].'</p>
                     </div> 
                     <div class="face2">
                         <p><i class="fa-solid fa-thumbs-up"></i> '.$data["Num_Likes"].'</p>
                         <p><i class="fa-solid fa-comment"></i> '.$number.'</p>
                   </div> 
                     </div >
                    ';
                }
            }
        }
        else{
            ?>
                <div class="nopost">
                <i class="fa-solid fa-camera fa-4x"></i>
                 <p>No Posts Available yet.</p>
                </div>
            <?php
        }


    }

    public function GetLastestActives(){

        $recentlyquery = $this->connect()->prepare("select * from likes where user_id = ? order by Id DESC ");
        $recentlyquery->execute([$this->currentuserID]);
        
        foreach($recentlyquery as $data){

            $posts =$this->connect()->prepare("select * from posts where Id = ? ");
            $posts->execute([$data["post_id"]]);
            $posts = $posts->fetchObject();

            $user2 = new User();
            $userdetails2 = $user2->GetUser($posts->user_id);
            
            $desc = $posts->post_body;
            $strcut = substr($desc,0,15);
            $desc = $strcut . '...<a href="read.php?post_id='.$posts->Id.'" class="nav-item" style="text-decoration:none;">Read More</a>';
              if($posts->user_id != $this->currentuserID){
                echo '
                <li class="list-group-item d-flex justify-content-between align-items-center" style="display: flex;flex-direction:column;position:relative;height:70px">
                <div style="display: flex; justify-content:space-between;align-items:center;width:100%">
                  <img src="'.$posts->image.'" style="width: 30px; height:30px;object-fit:cover;"/>
                      <span>'.$desc.'</span>
                </div>
                 <small style="color: grey;position:absolute;bottom:5px;left:15px;" >BlogIT By : '. $userdetails2->Name ." ".$userdetails2->Last_Name .'</small>
                 </li>
                ';
              }
            
        }

    }

}
?>