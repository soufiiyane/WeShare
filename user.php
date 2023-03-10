<?php 
include("includes/header.php");
include("connect.php");

include("includes/classes/Post.php");
include("includes/classes/User.php");

if(isset($_SESSION["user"])){
    $userloggedin = $_SESSION["user"];
    if(isset($_GET["profile_username"])){
        $id  = $_GET["profile_username"];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>

    <div style="margin-top: 65px;" class="container user-container">

        <div class="top-profile">
    
        </div>

    </div>

   <div class="container" id="content">
       
   </div>

  <div class="upiy">
    
  </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

     
      $(document).ready(function(){
            showheader();
            showconetnt();
            showfriends();
        })

      function showheader(){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var uservisit = "<?php echo  $_GET["profile_username"] ?>";
            $.ajax({
                url:"getuser.php",
                type:"get",
                data:{
                    userloggedin : userloggedin,
                    uservisit : uservisit
                },
                success : function(data,status){
                    $(".top-profile").html(data);
                }
            })
        }
        

        function showconetnt(){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var uservisit = "<?php echo  $_GET["profile_username"] ?>";
            $.ajax({
                url:"getcontent.php",
                type:"get",
                data:{
                    userloggedin : userloggedin,
                    uservisit : uservisit
                    
                },
                success : function(data,status){
                  if(status){
                    $("#content").html(data)
                  }
                }
            })
        }

        function showfriends(){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var uservisit = "<?php echo  $_GET["profile_username"] ?>";
            $.ajax({
                url:"friends.php",
                type:"get",
                data:{
                    userloggedin : userloggedin,
                    uservisit : uservisit
                },
                success : function(data,status){
                    if(status){
                        $(".upiy").html(data);
                        destroyCarousel();
                        slickslider();
                    }
                }
            })
        }
        
        
        function follow(id){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var uservisit = id;
            $.ajax({
                url:"follow.php",
                type:"post",
                data:{
                    userloggedin : userloggedin,
                    uservisit : id
                },
                success:function(data,status){
                        if(status){
                            showheader();
                            showconetnt();
                            showfriends();
                        }
                }
            })
        }

        function unfollow(id){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var uservisit = id;
            $.ajax({
                url:"unfollow.php",
                type:"post",
                data:{
                    userloggedin : userloggedin,
                    uservisit : id
                },
                success:function(data,status){
                        if(status){
                            showheader();
                            showconetnt();
                            showfriends();
                        }
                }
            })
        }

        function destroyCarousel() {
            if ($('.suggestions-container').hasClass('slick-initialized')) {
                $('.suggestions-container').slick('destroy');
            }      
        }

        function slickslider(){
            $('.suggestions-container').slick({
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 4,
                prevArrow: '<div class="slick-prev"><i  class="fa-solid fa-angle-left fa-1x"></i></div>',
                nextArrow: '<div class="slick-next"><i class="fa-solid fa-angle-right fa-1x"></i></div>',
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                infinite: true,
                                slidesToShow: 3,
                                slidesToScroll: 3,
                            }
                        },
                        {
                            breakpoint: 700,
                            settings: {
                                infinite: true,
                                slidesToShow: 2,
                                slidesToScroll: 2,
                            }
                    }
                ]
             });
        }
    </script>
</body>
</html>
