<?php 
include("connect.php");
include("includes/classes/Post.php");
include("includes/classes/User.php");

if(isset($_GET["userloggedin"]) and isset($_GET["uservisit"])){
   extract($_GET);
    $user  = new User();
   $usersquey = $database->prepare("select * from users where Id != ?");
   $usersquey->execute([$userloggedin]);
   $str = '
   <div class="container">
   <div class="suggestions-container">

   
   ';

   $loggedquery = $database->prepare("select * from users where Id = ?");
   $loggedquery->execute([$userloggedin]);
   $loggedquery = $loggedquery->fetchObject();

     foreach($usersquey as $data){
         $datauser = $user->GetUser($data["Id"]);
         $connectedlist =explode(",",$loggedquery->list);
        if(!in_array($data["Id"],$connectedlist)){
            $str .='
             <div class="suggestions-box">
                 <center>
                 <div class="image-box">
                 <a href="'.$datauser->Id.'"> <img src="'.$datauser->Profile_pic.'" ></a>
                 </div>
                 </center>
                 <center> <p>'.$datauser->Name.' '.$datauser->Last_Name.'</p></center>
                 <div >
                     <button onclick="follow('.$datauser->Id.')"  class="btn btn-sm btn-primary">
                             Follow <i class="fa-solid fa-user"></i>
                     </button>
                 </div>
             </div>
            ';
        }
     }
     $str .= '
        </div>
        </div>
     ';
   echo $str;


  
}
?>

