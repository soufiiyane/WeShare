<?php 
	session_start();
	
	if(isset($_SESSION["user"])){
		$userloggedin = $_SESSION["user"];
	}
  
   include("connect.php"); 
   include("includes/form-handlers/login-handler.php");
   include("includes/form-handlers/register-handler.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/logingpage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.17/sweetalert2.css" integrity="sha512-p06JAs/zQhPp/dk821RoSDTtxZ71yaznVju7IHe85CPn9gKpQVzvOXwTkfqCyWRdwo+e6DOkEKOWPmn8VE9Ekg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>

</head>
<body>
<div class="form">
	<form class="form-horizontal signin" method="POST">
		<div class="form-wrap" style="position: relative;">
		  <h2>Login</h2>
		  <div class="form-group">
			  <!-- <label for="email">Username:</label> -->
			  <div class="relative">
				  <input class="form-control" name="email" id="name" type="text" required placeholder="Email">
				  <i class="fa-solid fa-envelope fa"></i>
			  </div>
		  </div>
		  <div class="form-group">
		  	<!-- <label for="email">Password:</label> -->
		  	<div class="relative">
			  	<input class="form-control" name="password"  type="password" required="" placeholder="Password">
			  	<i class="fa fa-key"></i>
		  	</div>
		  </div> 
		  <div class="login-btn">
		  	<a><button type="submit" name="login" class="movebtn movebtnsu" type="Submit">Login <i class="fa fa-fw fa-lock"></i></button></a>
		  </div>
		  
		</div>
		<div class="sign-up">
	  	<a class="signbtn"><small>Not a member? Sign Up <i>(Click me)</i></small></a>
	  </div>
	</form>

	<form class="form-horizontal signup" method="POST">
		<div class="form-wrap" style="position: relative;">
		  <h2>Sign Up</h2>

          <div style="display:flex;flex-deriction:align-items;gap:5px;">
          <div class="form-group">
			  <div class="relative">
				  <input class="form-control"  name="name" id="name" type="text" required="" autofocus="" title="" autocomplete="" placeholder="First Name">
                  <i class="fa-solid fa-feather fa"></i>
			  </div>
		  </div>
            <div class="form-group">
			  <div class="relative">
				  <input class="form-control" name="lastname" id="name" type="text" required="" autofocus="" title="" autocomplete="" placeholder="Last Name">
				  <i class="fa-solid fa-feather fa"></i>
			  </div>
		  </div>
            </div>
		  <div class="form-group">
			  <div class="relative">
				  <input class="form-control" name="email" id="name" type="email" required="" autofocus="" title="" autocomplete="" placeholder="Username">
                  <i class="fa-solid fa-envelope fa"></i>
			  </div>
		  </div>
		
          <div style="display:flex;flex-deriction:align-items;gap:5px;">
          <div class="form-group">
		  	<div class="relative">
			  	<input id="myinput" class="form-control myinput"  name="password" type="password" required="" placeholder="Password">
			  	<i class="fa fa-key"></i>
		  	</div>
		  	<span class="pull-right"><small><a href="#" id="showhide">show / hide</a></small></span>
		  </div> 
            <div class="form-group">
                <div class="relative">
                    <input id="myinput" class="form-control myinput" name="confirmpassword" type="password" required="" placeholder="Password">
                    <i class="fa fa-key"></i>
                </div>
            </div> 
            </div>
            
		  <div class="login-btn">
		  	<a><button type="submit" name="register" class="movebtn movebtnsu" type="Submit">Submit <i class="fa fa-fw fa-paper-plane"></i></button></a>
		  </div>
		  
		</div>
		<div class="sign-up">
	  	<a  class="signbtn"><small>Already member? Sign in <i>(Click me)</i></small></a>
	  </div>
	</form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.17/sweetalert2.min.js" integrity="sha512-Kyb4n9EVHqUml4QZsvtNk6NDNGO3+Ta1757DSJqpxe7uJlHX1dgpQ6Sk77OGoYA4zl7QXcOK1AlWf8P61lSLfQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

<script>
        $(".signup").css("display", "none");
        $(".signbtn").click(function(){
            $("form").animate({ height:"toggle", opacity: "toggle"}, "slow");
        });

        $("#showhide").click(function(){
            var pass = $(".myinput");
            if (pass.attr("type") == "password") {
            pass.attr("type", "text");
            } else {
            pass.attr("type", "password");
            }
        })
</script>

		<script>
			toastr.options = {
				"closeButton": true,
				"debug": false,
				"newestOnTop": true,
				"progressBar": true,
				"positionClass": "toast-bottom-right",
				"preventDuplicates": false,
				"onclick": null,
				"showDuration": "500",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			}
		</script>

<?php 
    if(isset($_POST["register"]))
        {		
            if(count($errors_array)!=0){
				
				?>
				<script>
					$("form").animate({ height:"toggle", opacity: "toggle"}, "slow");
				</script>
			
				<?php
                foreach($errors_array as $error){
						?>
						<script>
							Command: toastr["info"]("<?php echo $error ?>")
						</script>
						<?php
                }
            }
			if(isset($query)){
                ?>
				<script>
					Swal.fire({
							toast: true,
							icon: 'success',
							title: 'Signed up Successfully',
							animation: true,
							showCloseButton: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 4000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})
				</script>
				<?php
        	}
        }
       
?>

</body>
</html>