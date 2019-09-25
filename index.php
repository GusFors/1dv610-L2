<?php



//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


//session_destroy();
session_start();

//CREATE OBJECTS OF THE VIEWS
$v = new LoginView();
$dtv = new DateTimeView();
$lv = new LayoutView();


    //$_SESSION['username'] = $_POST['LoginView::UserName'];
//echo $v->isLoggedIn() ? 'true' : 'false';

$lv->render($v, $dtv, $v->checkLoginStatus());
//echo $_SESSION['username'];

