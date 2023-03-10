<?php

class User{

    private $databasehost="localhost";
    private $databaseuser = "admin";
    private $databasepassword="admin";
    private $database="weshare";

    protected function connect(){
        $databasequery  = "mysql:host=" . $this->databasehost . ";dbname=" . $this->database;
        $pdo = new PDO($databasequery,$this->databaseuser,$this->databasepassword);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        return $pdo;
    }

    public function GetUser($id){
        $getuser = $this->connect()->prepare("select * from users where Id = ?");
        $getuser->execute([$id]);
        $getuser = $getuser->FetchObject();
        return $getuser;
    }

}

?>