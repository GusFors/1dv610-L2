<?php



//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('DatabaseHandler.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


//session_destroy();
session_start();

//database
$database = new DatabaseHandler();

//CREATE OBJECTS OF THE VIEWS

$dateView = new DateTimeView();
$layoutView = new LayoutView();
$registerView = new RegisterView($database);
$loginView = new LoginView($database, $registerView);


    //$_SESSION['username'] = $_POST['LoginView::UserName'];
//echo $loginView->isLoggedIn() ? 'true' : 'false';
if (!$registerView->checkRegisterStatus()) {
    $layoutView->render($loginView, $dateView, $loginView->tryLogin());
} else {
    $layoutView->render($registerView, $dateView, $loginView->tryLogin(), !$registerView->tryRegister());
}

//echo $_SESSION['username'];

