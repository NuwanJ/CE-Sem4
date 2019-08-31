<?php

session_start();
date_default_timezone_set('Asia/Colombo');
ini_set('session.cookie_domain', '.ceykod.com');
define("MAX_LOGIN_ATTEMPTS", 5);

include_once 'files/functions.php';
include_once '../data/database.php';

if (!isset($_SERVER['HTTPS']) && $_SERVER['HTTP_HOST'] != "localhost") {
    // https redirect
    // header("location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

$login = (isset($_SESSION['userAccess']) && $_SESSION['userAccess'] == $_SESSION[GetLoginSessionVar()]) ? 1 : 0;


if (isset($_GET['page'])) {
    $page = $_GET['page'];

    if ($page == "logout") {
        logOut();

    } else if ($page == "submit") {
        if ((isset($_POST['submitted'])) && loginToSite()) { //|| $login == 1
            echo "Redirecting...";
            redirectTo("../home/");
        } else {
            // error
            include_once 'files/loginWindow.php';
        }
    } else {
        include_once 'files/loginWindow.php';
    }
} else {
    include_once 'files/loginWindow.php';
}


/*
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    if ($page == "logout") {
        logOut();

    } elseif ($page == "submit") {

        if ((isset($_POST['submitted'])) && loginToSite()) { //|| $login == 1

            echo "Redirecting...";
            print_r($_SESSION);

            $userId = $_SESSION['userId'];


            $userStatus = $_SESSION['userStatus'];

            if ($userStatus == "CREATED" || $userStatus == "PENDING") {
                // need to confirm email
                redirectTo("../register/pendingConfirm");
                exit;
            } else if ($userStatus == "CONFIRMED") {
                // need to agree
                redirectTo("../register/agreement");
                exit;
            } else if ($userStatus == "AGREE") {
                // need to get user details
                redirectTo("../register/details");
                exit;
            } else if ($userStatus == "DETAILS") {
                // need to mobile connect
                redirect("../register/mobile");
                exit;
            }
            //redirectTo("../home/redirect.php");
        } else {
            //redirectTo("./");
        }

    } else {
        include_once 'files/default.php';
    }

} else {
    //redirectTo("/");
    include_once 'files/default.php';
}*/
