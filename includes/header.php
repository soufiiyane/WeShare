<?php 
  session_start();

  if(isset($_SESSION["user"])){
      $userloggedin = $_SESSION["user"];
  }
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShareIT World</title>
    <link rel="icon" type="image/x-icon" href="assets/images/ancestors.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top " style="margin-bottom: 65px;">
  <div class="container">
    <a class="navbar-brand" href="Index.php">WeShare</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="Index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="profile.php" >Profile</a>
        </li>

      </ul>
      <div  class="d-flex">
          <?php
            if(!isset($_SESSION["user"])){
              echo '
                <a href="login.php" class="nav-link  text-white">Login</a>
                <a href="register.php" class="nav-link text-white">Register</a>
              ';
            }
            else{
              echo '
                <span class="nav-link text-white"><strong class="text-info">ShareIT</strong> '.$userloggedin->Name.'</span> <br>
              ';
              echo '<a href="chat.php" class="nav-link text-white"><i class="fa-brands fa-facebook-messenger"></i></a>';
              echo '<a href="includes/logout.php" class="nav-link text-white"><i class="fa-solid fa-right-from-bracket"></i></a>';
            }
            ?>
            
      </div>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>