<?php 
 include("connect.php");
 include("includes/classes/Post.php");
 include("includes/classes/User.php");

if(isset($_GET["userloggedin"]) and isset($_GET["uservisit"])){
    extract($_GET);

        $friendquery = $database->prepare("select list from users where Id = ?");
        $friendquery->execute([$userloggedin]);
        $friendquery = $friendquery->fetchObject();
        $list = explode(",",$friendquery->list);
        $post2 = new Post();
        $user = new User();
        $userdetails = $user->GetUser($uservisit);
            $str ="";
            if(!in_array($uservisit,$list) and $uservisit != $userloggedin){
                $str = '
                    <div class="container mt-3">
                        <div class="user-content">
                            <div class="user-prive">
                                <strong>You are not friend with '.$userdetails->Name.'</strong>
                                <p>Follow to see their ShareIT\'s </p>
                            </div>
                        </div>
                    </div>
                    ';
            }
            if(in_array($uservisit,$list) or $uservisit==$userloggedin){
                if($uservisit==$userloggedin){
                    $post2->SetcurrenId($userloggedin);
                    }
                else{
                    $post2->SetcurrenId($uservisit);
                }
                $str = '
                    <div class="container mt-4 ">
                            '.$post2->GetProfilePosts().'
                    </div>
                ' ;
            }

            echo $str;

      }
    


?>