<?php 
    include("includes/header.php");
    include("connect.php");
    include("includes/classes/Post.php");
    include("includes/classes/User.php");

    if(isset($_SESSION["user"])){
        $userloggedin = $_SESSION["user"];

        $getnumlikes = $database->prepare("select count(Id) as number from likes WHERE user_id = ? GROUP by user_id");
        $getnumlikes->execute([$userloggedin->Id]);
        if($getnumlikes->rowCount()>0){
          $getnumlikes = $getnumlikes->fetchObject();
          $likes = $getnumlikes->number;
        }
        else{
          $likes = "0";
        }


        
        $getnumposts = $database->prepare("select count(post_body) as number from posts where user_id = ? GROUP by user_id");
        $getnumposts->execute([$userloggedin->Id]);
        if($getnumposts->rowCount()>0){
          $getnumposts = $getnumposts->fetchObject();
          $pubs = $getnumposts->number;
        }
        else{
          $pubs = "0";
        }

        $getnumcomments = $database->prepare("select COUNT(Id) as number from comment where user_id = ? GROUP by user_id");
        $getnumcomments->execute([$userloggedin->Id]);
        if($getnumcomments->rowCount()>0){
          $getnumcomments = $getnumcomments->fetchObject();
          $comments = $getnumcomments->number;
        }
        else{
          $comments = "0";
        }
        
    }
    else{
      header("Location:login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>

    <div style="margin-top: 65px;" class="container profile-container">

       <div class="top-profile">

            <div class="left-side">
                <div class="image-container">
                  <img draggable="false" src="<?php echo $userloggedin->Profile_pic ?>"/>
                </div>
            </div>

            <div class="right-side">
              
              <div class="user-details">
                    <div><h3><?php echo $userloggedin->Name ?></h3></div>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      Edit Profile <i class="fa-solid fa-user-pen"></i>
                    </button>
              </div>

              <div class="posts_details">
                <p><strong><?php echo $pubs ?></strong> <small>Publication</small></p>
                <p><strong><?php echo $likes ?></strong> <small>Likes</small></p>
                <p><strong><?php echo $comments ?></strong> <small>Comments</small></p>
              </div>
            
            </div>

       </div>

       <div class="bottom-profile">

           <div class="card text-center" style="height: 100%;">
             <div class="card-header">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Last Activities</button>
                </li>
              </ul>
            </div>
            
            <div class="card-body ">
              <div class="tab-content" id="myTabContent" style="height: 100%;">
                <div class="tab-pane fade show active" style=" height:100%" id="home" role="tabpanel" aria-labelledby="home-tab">
              
                  <div class="wrapper-home">

                    <?php 
                      $post = new Post();
                      $post->SetcurrenId($userloggedin->Id);
                      $post->GetProfilePosts();
                    ?> 
                  </div>
          
              </div>
              <div class="tab-pane fade" id="profile" style=" height:100%" role="tabpanel" aria-labelledby="profile-tab">
                
              <ul class="list-group">

                <?php 
                  $post->GetLastestActives();
                ?>
            </ul>
              
              
              </div>
              
              </div>
            </div>
          </div>
          

       </div>

    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="POST" action="profile.php" enctype="multipart/form-data">
            <div class="form-group mb-2">
                <label  for="file" class="form-text mb-1">Profile picture</label>
                <input  type="file" id="file" name="file" class="form-control" required />
            </div>
            <div class="form-group mb-2">
                <label  for="password" class="form-text mb-1">Password</label>
                <input type="password" id="password" name="password" class="form-control" required />
            </div>
            <div class="form-group mb-2">
                <label  for="password2" class="form-text mb-1">Confirm Password</label>
                <input type="password" id="password2" name="confirmpassword" class="form-control" required />
            </div>
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="change"  class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
  </div>
</div>
<?php 
  if(isset($_POST["change"])){
    $password  = $_POST["password"];
    $confirm = $_POST["confirmpassword"];
    if($password==$confirm){
        $file = $_FILES["file"]["tmp_name"];
        $filename = $_FILES["file"]["name"];
        move_uploaded_file($file,"assets/images/".$filename);
        $position = "assets/images/".$filename;
        $query = $database->prepare("update users set Profile_pic = ? where Id = ? and Password = ?");
        $query->execute([$position,$userloggedin->Id,md5($password)]);

        $user = $database->prepare("select * from users where Id = ?");
        $user->execute([$userloggedin->Id]);
        $user = $user->fetchObject();
        $_SESSION["user"] = $user;
        $userloggedin = $_SESSION["user"];
        ?>
         <script>
            window.location.href="http://localhost/projects/WeShare/profile.php";
        </script>
        <?php
      }
  }
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>