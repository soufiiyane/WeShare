<?php

$name = "";
$lastname = "";
$email = "";
$password = "";
$confirmpassword = "";

$errors_array = [];

if(isset($_POST["register"])){
    
    $date = date("Y-m-d");

    $name = strip_tags($_POST["name"]);
    $name = str_replace(" ","",$name);
    $name = ucfirst(strtolower($name));

    $lastname = strip_tags($_POST["lastname"]);
    $lastname = str_replace(" ","",$lastname);
    $lastname = ucfirst(strtolower($lastname));

    $email = strip_tags($_POST["email"]);
    $email = str_replace(" ","",$email);
    $email = ucfirst(strtolower($email));

    $password = strip_tags($_POST["password"]);
    $password = str_replace(" ","",$password);
    $password = ucfirst(strtolower($password));

    $confirmpassword = strip_tags($_POST["confirmpassword"]);
    $confirmpassword = str_replace(" ","",$confirmpassword);
    $confirmpassword = ucfirst(strtolower($confirmpassword));

    
    if(strlen($name)>20 || strlen($name)<4){
        array_push($errors_array,"Your first name must be between 4 and 20 characters");
    }
   if(strlen($lastname)>30 || strlen($lastname) < 5){
    array_push($errors_array,"Your last name must be between 5 and 30 characters");
   }

   if(filter_var($email,FILTER_VALIDATE_EMAIL)){
       $email = filter_var($email,FILTER_VALIDATE_EMAIL);
       $checkemail = $database->prepare("select * from users where Email = ?");
       $checkemail->execute([$email]);
       if($checkemail->rowCount()!=0){
        array_push($errors_array,"This email is already used");
       }
   }
   else{
    array_push($errors_array,"Invalid email format");
   }

   if($password != $confirmpassword){
    array_push($errors_array,"Passwords do not match");
   }
   else{
    if(preg_match("/[^A-Za-z0-9]/",$password)){
        array_push($errors_array,"Your password can only contain english characters or numbers");
        }
   }
   if(strlen($password)>30 || strlen($password)<7){
    array_push($errors_array,"Your password must be between 7 and 30 characters");
   }

   if(empty($errors_array)){

       $password = md5($password);
       $query = $database->prepare("insert into users values('',?,?,?,?,?,'assets/images/default.png',',','false')");
       $query->execute([$name,$lastname,$email,$password,$date]);
   
    }

}

?>