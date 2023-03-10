<?php 
    include("includes/header.php");
    include("connect.php");
    include("includes/classes/Post.php");
    include("includes/classes/User.php");

    if(isset($_SESSION["user"])){
        $userloggedin = $_SESSION["user"];
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
    <link rel="stylesheet" href="assets/css/chat.css">
</head>
<body>
<div class="chat-big-container">
    
<div class="container-chat">
        
        <div class="left">

            <div class="top">
                <div class="tub"> <div class="username">Messages</div> </div>
            </div>

            <div class="conversations">

            </div>

        </div>
        
        <div class="right">
        <?php if(isset($_GET["chatme"])){
            ?>
                    <script>
                       $(document).ready(function(){
                        showmessages("<?php echo $_GET["chatme"] ?>");
                       })
                    </script>
            <?php
        } 
        else{
            ?>
             <div class="nomessage">
                <img draggable="false" src="assets/images/mailbox.png" alt="">
                <h4> it's nice to chat with friends !</h4>
                <p>Pick a person from left menu <br> and start your conversation</p>
            </div>
            <?php
        }
        ?>
           
        </div>


    </div>

</div>

    <input type="text" class="active" value="" hidden>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
       

      function sendmessage(id){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var userto = id;
            var text = $("#message").val();      
            $.ajax({
                url:"sendmessage.php",
                type:"post",
                data:{
                    userloggedin : userloggedin,
                    userto : userto ,
                    text : text
                },
                success : function(data,status){
                    showmessages(id);
                }
            })
        }


        function displayusers(){
                $.ajax({
                    url:"displayusers.php",
                    type:"get",
                    success:function(data,status){
                        $(".conversations").html(data);
                       
                    }
                })
        }

        function updatestatus(userdata){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
                $.ajax({
                    url:"updatestatus.php",
                    type:"get",
                    data:{
                        userloggedin : userloggedin,
                        userdata,userdata
                    },
                   
                })
        }

        function showmessages(id){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            $.ajax({
                url:"showmessages.php",
                type:"get",
                data:{
                   id:id,
                   userlogged : userloggedin
                },
                success : function(data,status){
                    displayusers();
                    $(".right").html(data);
                    $(".active").val(id);
                    updatestatus(id);
                }
            })
        }

        $(document).ready(function(){
            displayusers();
            realtime();
        })
        
        setInterval(() => {
            displayusers();
        }, 2000);


        function realtime(){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var userto = $(".active").val();     
            $.ajax({
                url:"real.php",
                type:"get",
                data:{
                    userloggedin : userloggedin,
                    id : userto
                },
                success : function(data,status){
                    $(".messages").html("");
                    $(".messages").html(data);
                    updatestatus(userto);
                }
            })
        }
        
        setInterval(() => {
            realtime();
            
        }, 2000);
        
    </script>
    
</body>
</html>


