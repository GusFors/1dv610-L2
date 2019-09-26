<?php

class DatabaseHandler {

    private $conn = false;

    public function findDbMatch($username, $password) {
        //Heroku db connection
        if(count(parse_url(getenv("CLEARDB_DATABASE_URL"))) > 1) {
            $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
          
            $server = $url["host"];
            $dbusername = $url["user"];
            $dbpassword = $url["pass"];
            $db = substr($url["path"], 1);
      
            $conn = mysqli_connect($server, $dbusername, $dbpassword, $db);
          } else { // local db connection
            $localServer = 'localhost';
            $dbUsername = 'root';
            $dbPass = '';
            $dbName = 'phplogin';
            $conn = mysqli_connect($localServer, $dbUsername, $dbPass, $dbName); 
          } 
          
          if (!$conn) {
            die('failed db connection'.mysqli_connect_error());
            echo 'failed dbconn'; 
          }
      
          $sql = "SELECT id FROM users WHERE BINARY username = '$username' AND BINARY password = '$password'";
         
          $result = mysqli_query($conn,$sql);
          if(empty($result)) {
            // for development purposes if there is no table to store users, saves a standard admin user
            $this->createUserTable($conn);
          }
         
          //$sql = "DROP TABLE users";
          //$result = mysqli_query($conn,$sql);
          $count = mysqli_num_rows($result);
          
       
        
          if ($count == 1) {
             //echo 'correct login?';
             return true;
             
          } else {
             //echo 'no user or wrong pass';
             return false;
          }
    }

    public function checkUsername($username, $password, $passwordRepeat) {
        //Heroku db connection
        if(count(parse_url(getenv("CLEARDB_DATABASE_URL"))) > 1) {
            $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
          
            $server = $url["host"];
            $dbusername = $url["user"];
            $dbpassword = $url["pass"];
            $db = substr($url["path"], 1);
            if($this->conn == false) {
                $this->conn = mysqli_connect($server, $dbusername, $dbpassword, $db);
            }
            
          } else { // local db connection
            $localServer = 'localhost';
            $dbUsername = 'root';
            $dbPass = '';
            $dbName = 'phplogin';
            if($this->conn == false) {
                $this->conn = mysqli_connect($localServer, $dbUsername, $dbPass, $dbName); 
            }
            
          } 
          
          if (!$this->conn) {
            die('failed db connection'.mysqli_connect_error());
            echo 'failed dbconn'; 
          }
      
          $sql = "SELECT id FROM users WHERE BINARY username = '$username' ";
         
          $result = mysqli_query($this->conn,$sql);
          
          $count = mysqli_num_rows($result);
          
          
        
          if ($count == 1) {
             
             return 'exists';
             
          } else if($count == 0 ) {
              if($password === $passwordRepeat && strlen($password) > 5 && strlen($username) > 2) {
                
                 $this->registerUser($username, $password);
                 return 'success';
              } else {
                  return 'fail';
              }
           
             //return false;
          }
    }

    public function registerUser($username, $password) {
        
            $sql = "INSERT INTO users (username, password) VALUES ('$username', $password)";
            $result = mysqli_query($this->conn,$sql);       
            return $result;
    }

    private function createUserTable($conn) {
        $sql = "CREATE TABLE users (
            id int(10) AUTO_INCREMENT,
            username varchar(20) NOT NULL,
            password varchar(20) NOT NULL,
            PRIMARY KEY  (id)
            )";
            $result = mysqli_query($conn,$sql);
          $sql = "INSERT INTO users (username, password) VALUES ('Admin', 'Password')";
          $result = mysqli_query($conn,$sql);
    }
}