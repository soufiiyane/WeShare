<?php 
    include("includes/header.php");
    include("connect.php");
    include("includes/classes/Post.php");
    include("includes/classes/User.php");
    if(isset($_SESSION["user"])){
        $userloggedin = $_SESSION["user"];
        $number  = new Post(); 
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
    <title>BlogIt World</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/postcard.css">
</head>
<body>

<div class="container">

    <div class="wrapper">
        
        <div class="left-side">
     
            <div class="left-side-inner">

                <div class="left-side-inner-categorie">
                    <div class="first-face">
                          <span class="btn btn-sm btn-light"><i class="fa-solid fa-arrow-pointer"></i> &nbsp; Hover me</span>
                          <p class="hover-back">Hover</p>
                    </div>
                    <div class="second-face" >
                        <ul class="list-group">
                            <li class="list-group-item text-center active">
                                Categories
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a class="nav-link" href="#">Travel</a>
                                <span style="background-color: #0D6EFD;" class="badge badge-pill"><?php $number->GetCategoriesNumber("travel")  ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a class="nav-link" href="#">Sport</a>
                                <span style="background-color: #0D6EFD;" class="badge badge-pill"><?php $number->GetCategoriesNumber("sport") ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a class="nav-link" href="#">Fashion</a>
                                <span style="background-color: #0D6EFD;" class="badge badge-pill"><?php $number->GetCategoriesNumber("fashion")  ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a class="nav-link" href="#">Politic</a>
                                <span style="background-color: #0D6EFD;" class="badge badge-pill"><?php $number->GetCategoriesNumber("politics")  ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                
            </div>

        </div>


        <div class="middle-side">
            <form method="POST" action="Index.php" enctype="multipart/form-data">
                <hr style="background-color: #0d6efd;">
                <textarea id="text" class="form-control mb-2" style="min-height: 150px; max-height:150px;" name="post_body" required></textarea>
                <div class="row">
                    <div class="col">
                    <select id="category" class="form-select form-select-sm"" name="category" >
                            <option selected disabled>Select Category</option>
                            <option value="travel">Travel</option>
                            <option  value="sport">Sport</option>
                            <option value="fashion">Fashion</option>
                            <option value="politics">Politic</option>
                    </select>
                    </div>
                    <div class="col">
                    <input type="file" accept="image/png, image/gif, image/jpeg" name="file" id="file" class="form-control form-control-sm" >
                    </div>
                </div>
                <button type="submit" name="blogit" class="btn btn-sm btn-primary mt-2">ShareIt</button>
                <hr style="background-color: #0d6efd;">
            </form>
    
            <?php 
                $post = new Post();
                $post->SetcurrenId($userloggedin->Id);
                if(isset($_POST["blogit"])){
                    $postbody = $_POST["post_body"];

                   if(isset($_POST["category"])){
                    $category = $_POST["category"];
                   }
                   else{
                    $category = "";
                   }

                $file = $_FILES["file"]["tmp_name"];
                $filename = $_FILES["file"]["name"];
                move_uploaded_file($file,"assets/images/".$filename);
                
                if($filename != null){
                    $position = "assets/images/".$filename;
                }
                else{
                    $position = "";
                }

                if($post->InsertPost($postbody,$userloggedin->Id,$category,$position)){
                    echo '
                    <div class="alert alert-success" role="alert">
                        BlogIT successfully .
                    </div>
                    ';
                }
                else{
                    echo '
                        <div class="alert alert-danger" role="alert">
                            Error Occured !! please try later.
                        </div>
                        ';
                    }
                }
            ?>

            <div class="loadhereposts">
                
          
            </div>


        </div>
      
        <div class="right-side">
        <ul class="list-group">
            <li class="list-group-item  bg-success active">
                Recently ShareIT's
            </li>
            
            <div class="lastactives">
                
            </div>
        </ul>
        </div>
 
    </div>
    
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var $window = $(window);
        $window.on('load', function () {
            $('#preloader').fadeOut('slow', function () { $(this).remove();}); });
            

        $(document).ready(function(){
            loadposts();
            loadlastactivities();
        })
       
       function loadposts(){
        var userloggedin = "<?php echo  $userloggedin->Id ?>";
            $.ajax({
                url:"loadposts.php",
                type:"get",
                data:{
                    userloggedin : userloggedin
                },
                success : function(data,status){
                   if(status){
                       $(".loadhereposts").html(data);
                   }
                }
            })
       }

       
       function deletepost(id){
         var userloggedin = "<?php echo  $userloggedin->Id ?>";
            $.ajax({
                url:"deletepost.php",
                type:"post",
                data:{
                    id:id,
                    userloggedin:userloggedin   
                },
                success : function(data,status){
                   if(status){
                     loadposts();
                   }
                }
            })
       }
        
       function insertcomment(id){
            var text = $("#commenttext"+id).val();
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            var postid = id;
            $.ajax({
                url:"insertcomment.php",
                type:"post",
                data:{
                    text : text,
                    userloggedin : userloggedin,
                    postid : postid
                },
                success:function(data,status){
                    if(status){
                        $("#commenttext"+id).val("");
                        loadcomment(id);
                    }
                }
            })
        
       }

       function loadcomment(id){
            var userloggedin = "<?php echo  $userloggedin->Id ?>";
            postid = id;
            $.ajax({
                url:"loadcomment.php",
                type:"get",
                data:{
                    userloggedin : userloggedin,
                    postid : postid
                },
                success:function(data,status){
                    if(status){
                       $("#loadhere"+id).html(data);
                    }
                }
            })
       }

       function loadlastactivities(){
        var userloggedin = "<?php echo  $userloggedin->Id ?>";
           $.ajax({
               url:"lastpostsadded.php",
               type:"get",
               data:{
                    userloggedin : userloggedin
               },
               success:function(data,status){
                    if(status){
                        $(".lastactives").html(data);
                    }
               }
           })
       }

       function deletecomment(idcomment,idpost){
            $.ajax({
                url:"deletecomment.php",
                type:"post",
                data:{
                    cmnt:idcomment,
                    post:idpost
                },
                success:function(data,status){
                    if(status){
                       loadcomment(idpost);
                    }
                }
            })
       }
       function editable(id,postid){
            $("#editme"+id).attr("contenteditable",true);
            $("#editme"+id).focus();
            $("#btnedit"+id).css("display", "none");
            $("#apply"+id).css("display", "block");
       }
       function applyedit(id){
            $("#editme"+id).attr("contenteditable",false);
            $("#btnedit"+id).css("display", "block");
            $("#apply"+id).css("display", "none");
            var text =$("#editme"+id).text();
            $.ajax({
                url:"updatecomment.php",
                type:"post",
                data:{
                    text:text,
                    id:id
                },
                success:function(data,status){
                    if(status){
                        loadcomment(postid);
                    }
                }
            })
       }
    </script>
</body>
</html>
