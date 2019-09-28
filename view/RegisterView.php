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


    public function tryRegister() {

        $this->message = '';

        if ($this->checkRequestRegister()) {

            if (strlen($this->getRequestUserName()) < 3) {
                $this->message .= 'Username has too few characters, at least 3 characters.';
            }

            if (strlen($this->getRequestPassword()) < 6) {
                if ($this->message !== '') {
                    $this->message .= '<br>';
                }
                $this->message .= 'Password has too few characters, at least 6 characters.';
            } else {
                if ($this->getRequestPassword() !== $this->getRequestPasswordRepeat()) {
                    $this->message .= 'Passwords do not match.';
                }
            }

            if ($this->getRequestUserName() != strip_tags($this->getRequestUserName())) {
                if ($this->message !== '') {
                    $this->message .= '<br>';
                }
                $this->message .= 'Username contains invalid characters.';
                return false;
            }

            $tryStoreUser = $this->database->checkUsername($this->getRequestUserName(), $this->getRequestPassword(), $this->getRequestPasswordRepeat());

            if ($tryStoreUser === 'exists') {
                if ($this->message !== '') {
                    $this->message .= '<br>';
                }


                $this->message .= 'User exists, pick another username.';


                $_SESSION['registername'] = $this->getRequestUserName();
                //return true;

            } else if ($tryStoreUser == 'success') {
                if ($this->message !== '') {
                    $this->message .= '<br>';
                }
                $this->message = 'Registered new user.';
                //return true; TODO: If success redirect in some way to index.php without header
                header('Location:https://gusfors-l2.herokuapp.com/index.php');
                $_SESSION['registrationname'] = $this->getRequestUserName();
            } else if ($tryStoreUser === 'fail') { }
        }
        return false;
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

    private function stripHTML($string) {
        return strip_tags($string);
    }

    private function generateRegisterFormHTML($message) {
        $storedUsername = $this->stripHTML($this->getRequestUserName());
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

        if ($this->checkRequestUserName()) {
            return $_POST[self::$name];
        }
        return null;
    }

    private function checkRequestUserName() {
        return isset($_POST[self::$name]);
    }

    private function checkRequestPassword() {
        return isset($_POST[self::$password]);
    }

    private function getRequestPassword() {
        if ($this->checkRequestPassword()) {
            return $_POST[self::$password];
        }
        return null;
    }

    private function getRequestPasswordRepeat() {
        if (isset($_POST[self::$passwordRepeat])) {
            return $_POST[self::$passwordRepeat];
        }
        return null;
    }

    private function checkRequestRegister() {
        return isset($_POST[self::$register]);
    }
}
