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

	

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
  
    $response = '';

    if(!$this->setLogin()) {
      session_destroy();
      $response = $this->generateLoginFormHTML($this->message);
    } else {
      $response .= $this->generateLogoutButtonHTML($this->message);
    }

		
		return $response;
  }
  

  public function setLogin() {
    if (isset($_POST[LoginView::$logout])) {
      //echo 'trying to destroy';
     
      return false;
    }
    if (isset($_POST[LoginView::$login])) {

      if (strlen($this->getRequestUserName()) < 1) {
       
        //$_SESSION['username'] = $this->getRequestUserName();
        $this->message = 'Username is missing';
      } else if (strlen($this->getRequestUserPassword()) < 1) {
        $this->message = 'Password is missing';
      } else {
        if(!isset($_SESSION['username'])) {
          //session_start();
          $this->isNotLoggedIn = false;
          //echo 'session started!???';
          $this->message = 'Welcome';
          $_SESSION['username'] = $_POST['LoginView::UserName']; // $this->getRequestUserName();
          //echo $_SESSION['username'];
        }
        return true;
      }
      
    } else if(!isset($_SESSION['username'])) {
      //echo 'session not started...';
        return false;
    } else if(isset($_SESSION['username'])) {
      //echo 'session started...';
   
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
				<p id="' . self::$messageId . '">' . $message .'</p>
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
        
        return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="" />

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
		if (isset($_POST['LoginView::UserName'])) {
			return $_POST['LoginView::UserName'];
    }
    return null;
		//RETURN REQUEST VARIABLE: USERNAME
  }
  
  private function getRequestUserPassword() {
		if (isset($_POST['LoginView::Password'])) {
			return $_POST['LoginView::Password'];
    }
    return null;
		//RETURN REQUEST VARIABLE: USERNAME
	}

	public function isLoggedIn() {
		if (isset($_SESSION['username'])) {
			return true;
		} else {
			return false;
		}
	}
	
}