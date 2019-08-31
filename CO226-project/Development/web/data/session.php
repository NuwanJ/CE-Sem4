<?php
date_default_timezone_set('Asia/Colombo');
session_start();
ini_set('session.cookie_lifetime', 3600);
ini_set('session.gc_maxlifetime', 3660);

$time = $_SERVER['REQUEST_TIME'];
$timeout_duration = 3600;

if (isset($_SESSION['LAST_ACTIVITY']) &&
    ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration
) {
    session_unset();
    session_destroy();
    session_start();
}

$_SESSION['LAST_ACTIVITY'] = $time;

$currentURL = ""; //(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SESSION['userAccess'] != GetLoginSessionVar()) {
    // Sign Out
    header('location: https://co224.tk/login/');
}

$_SESSION['current - url'] = $currentURL;

$userName = $_SESSION['user'];
$userId = $_SESSION['userId'];
$date = date("Y-m-d");
$time = date("Y-m-d h:m:s");
$viewMode = $_SESSION['viewMode'];
$role = $_SESSION['role'];

if ($viewMode == "desktop") {
    //$logoutText = "<a href='https://account.ceykod.com/login/logout.php' class='w3-bar-item w3-button'><i class='fa fa-sign-out'></i> Logout</a>";
    $logoutText = "<a href='../login/logout' class='w3-bar-item w3-button'><i class='fa fa-sign-out'></i> Logout</a>";
} else {
    $logoutText = "";
}

function GetLoginSessionVar()
{
    $rand_key = "0iQx5oBk66oVZep";
    $retvar = md5($rand_key);
    $retvar = 'usr_' . substr($retvar, 0, 10);
    return $retvar;
}

?>
