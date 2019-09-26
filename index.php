<?php



//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


//session_destroy();
session_start();

//CREATE OBJECTS OF THE VIEWS
$loginView = new LoginView();
$dateView = new DateTimeView();
$layoutView = new LayoutView();
$registerView = new RegisterView();


    //$_SESSION['username'] = $_POST['LoginView::UserName'];
//echo $loginView->isLoggedIn() ? 'true' : 'false';
if(!$registerView->checkRegisterStatus()) {
    $layoutView->render($loginView, $dateView, $loginView->checkLoginStatus());
} else {
    $layoutView->render($registerView, $dateView, $loginView->checkLoginStatus(), $registerView->checkRegisterStatus());
}

//echo $_SESSION['username'];

