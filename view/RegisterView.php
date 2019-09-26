<?php

class RegisterView {

    private static $register = 'RegisterView::Register';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $messageId = 'RegisterView::Message';
    
    private $message = '';
    private $database;
    private $registerBool = false;

    public function __construct($database) {
        $this->database = $database;
    }


    public function tryRegister() {
        //var_dump($_POST);
        $this->message = '';
        $retryBool = true;
        if (isset($_POST['RegisterView::Register'])) {
            $this->registerBool = true;
            if (strlen($this->getRequestUserName()) < 3) {
                    $this->message .= 'Username has too few characters, at least 3 characters.';                       
            }

            if (strlen($this->getRequestPassword()) < 6) {
                if($this->message !== '') {
                    $this->message .= '<br>';
                }
                $this->message .= 'Password has too few characters, at least 6 characters.';
            } else {
                if($this->getRequestPassword() !== $this->getRequestPasswordRepeat()) {
                    $this->message .= 'Passwords do not match.';
                }  
            }

            $tryStoreUser = $this->database->checkUsername($this->getRequestUserName(), $this->getRequestPassword(), $this->getRequestPasswordRepeat());

            if($tryStoreUser === 'exists') {
                if($this->message !== '') {
                    $this->message .= '<br>';
                }
              
                  
                $this->message .= 'User exists, pick another username.';
                    
                
                $_SESSION['registername'] = $this->getRequestUserName();
                //return true;
                
            } else if($tryStoreUser == 'success') {
                $this->message = 'Registered new user.';
                return false; 
               
            } else if ($tryStoreUser === 'fail') {
               // $this->message = 'other thing happened';
            }
            
           
                //$_SESSION['username'] = $this->getRequestUserName();
                
            //echo 'trying to register';
        }
        return true; 
        /*
        if(!$this->registerBool) {
            return true;
        } else {
            return false;
        } */
       
    }

    public function checkRegisterStatus() {
        $this->message = '';
        if (isset($_GET['register'])) {
            return true;
        } else {
            return false;
        }
    }



    public function response() {
        return $this->generateRegisterFormHTML($this->message);
    }

    private function generateRegisterFormHTML($message) {
        $storedUsername = $this->getRequestUserName();
        return '<form action="?register" method="post" enctype="multipart/form-data">
        <fieldset>
        <legend>Register a new user - Write username and password</legend>
            <p id="' . self::$messageId . '">' . $message . '</p>

            <label for="' . self::$name . '">Username :</label>
            <input type="text" size="20" name="' . self::$name . '" id="' . self::$name . '" value="' . $storedUsername . '">
            <br>
            <label for="' . self::$password . '">Password  :</label>
            <input type="password" size="20" name="' . self::$password . '" id="' . self::$password . '" value="">
            <br>
            <label for="' . self::$passwordRepeat . '">Repeat password  :</label>
            <input type="password" size="20" name="' . self::$passwordRepeat . '" id="' . self::$passwordRepeat . '" value="">
            <br>
            <input id="submit" type="submit" name="' . self::$register . '" value="Register">
            <br>
        </fieldset>
       </form>';
	
	}

    

    private function getRequestUserName() {
		if (isset($_POST['RegisterView::UserName'])) {
			return $_POST['RegisterView::UserName'];
        }
        return null;
		
    }

   private function getRequestPassword() {
    if (isset($_POST['RegisterView::Password'])) {
        return $_POST['RegisterView::Password'];
    }
    return null;
    
   }

   private function getRequestPasswordRepeat() {
    if (isset($_POST['RegisterView::PasswordRepeat'])) {
        return $_POST['RegisterView::PasswordRepeat'];
    }
    return null;
    
   }
    
}