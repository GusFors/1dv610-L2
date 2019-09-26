<?php

class RegisterView {

    private static $register = 'RegisterView::Register';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $messageId = 'RegisterView::Message';

    private $message = '';
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }


    public function checkRegisterStatus() {
        //var_dump($_POST);
        $this->message = '';
        if (isset($_POST['RegisterView::Register'])) {
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
            
                //$_SESSION['username'] = $this->getRequestUserName();
                
            //echo 'trying to register';
        }
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

    public function registerUser() {

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