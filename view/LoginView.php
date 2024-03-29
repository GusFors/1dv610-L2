<?php

class LoginView {

    private static $login = 'LoginView::Login';
    private static $logout = 'LoginView::Logout';
    private static $name = 'LoginView::UserName';
    private static $password = 'LoginView::Password';
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $keep = 'LoginView::KeepMeLoggedIn';
    private static $messageId = 'LoginView::Message';
    private $message = '';
    private $isNotLoggedIn = true;
    private $database;
    private $register;
    private $storedUsername = '';

    public function __construct($database, $register) {
        $this->database = $database;
        $this->register = $register;
    }



    /**
     * Create HTTP response
     *
     * Should be called after a login attempt has been determined
     *
     * @return  void BUT writes to standard output and cookies!
     */
    public function response() {

        $response = '';



        if (!$this->tryLogin()) {
            $response = $this->generateLoginFormHTML($this->message);
        } else {
            $response .= $this->generateLogoutButtonHTML($this->message);
        }


        return $response;
    }

    public function checkDbUserMatch(DatabaseHandler $database, $username, $password) {

        return $database->findDbMatch($username, $password);
    }

    public function tryLogin() {
        if ($this->checkLogoutPost()) {

            if ($this->isLoggedIn()) {
                $this->message = 'Bye bye!';
            }
            $this->logout();


            return false;
        }
        $this->storedUsername = $this->getRequestUserName();
        $this->checkNewRegister();

        /* TODO: make login possible with cookies.
    if($this->checkDbUserMatch($this->database, $_COOKIE[self::$cookieName], password_verify('Password', 'sde')($_COOKIE[self::$cookiePassword]))) { 
      return true;
    } */

        if ($this->checkLoginPost()) {

            if (strlen($this->getRequestUserName()) < 1) {
                $this->message = 'Username is missing';
            } else if (strlen($this->getRequestUserPassword()) < 1) {
                $this->message = 'Password is missing';
            } else {
                $userMatchResult = $this->checkDbUserMatch($this->database, $this->getRequestUserName(), $this->getRequestUserPassword());
                if ($userMatchResult) {

                    $this->isNotLoggedIn = false;

                    if (!$this->isLoggedIn()) {
                        $this->message = 'Welcome';
                        if ($this->checkKeepLogin()) {
                            $this->message = 'Welcome and you will be remembered';

                            $this->setcookies();
                        }
                    }

                    $this->setLogin($this->getRequestUserName());
                    //header('Location: index.php');
                    return true;
                } else {
                    $this->message = 'Wrong name or password';
                    return false;
                }
            }
        } else if (!$this->isLoggedIn()) {
            return false;
        } else if ($this->isLoggedIn()) {
            return true;
        }
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return  void, BUT writes to standard output!
     */
    private function generateLogoutButtonHTML($message) {

        return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return  void, BUT writes to standard output!
     */
    private function generateLoginFormHTML($message) {


        if ($this->checkNewRegister()) {
            $message = 'Registered new user.';  // TODO: make this a function or such and not written here
            $this->storedUsername = $_SESSION['registrationname'];
            $_SESSION['registrationname'] = null;
        }

        return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->storedUsername . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
    }

    //CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
    private function getRequestUserName() {
        if (isset($_POST[self::$name])) {
            return $_POST[self::$name];
        }
        return null;
        //RETURN REQUEST VARIABLE: USERNAME
    }

    private function getRequestUserPassword() {
        if (isset($_POST[self::$password])) {
            return $_POST[self::$password];
        }
        return null;
    }

    public function isLoggedIn() {
        if (isset($_SESSION['username'])) {
            return true;
        } else {
            return false;
        }
    }

    private function checkNewRegister() {
        if (isset($_SESSION['registrationname'])) {
            return true;
        }
        return false;
    }

    private function setLogin($username) {
        $_SESSION['username'] = $username;
    }

    private function logout() {
        $_SESSION = [];
        $_COOKIE[self::$cookieName] = 'notLogged';
    }

    private function checkLogoutPost() {
        return isset($_POST[self::$logout]);
    }

    private function checkLoginPost() {
        return isset($_POST[self::$login]);
    }

    private function checkKeepLogin() {
        return isset($_POST[self::$keep]);
    }

    private function setCookies() {
        setcookie(self::$cookieName, $this->getRequestUserName(), time() + 600, '/');
        setcookie(self::$cookiePassword, password_hash($this->getRequestUserPassword(),  PASSWORD_BCRYPT), time() + 600, '/');
    }
}
