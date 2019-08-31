<?php

function loginToSite()
{
    // Validating section
    if (empty($_POST['username'])) {
        HandleError("UserName is empty!");
        return false;
    }

    if (empty($_POST['password'])) {
        HandleError("Password is empty!");
        return false;
    }

    $userName = trim($_POST['username']);
    $password = trim($_POST['password']);
    $service = trim(($_POST['service']));

    $db = new database();

    $result = ($db->existsUser($userName, $password));

    // 3 attempt block

    // Invalid login timeout section
    /*if (isset($_SESSION['reg-danie-time'])) {

        if ($_SESSION['reg-danie-time'] > time() && $result == 0) {
            HandleError("You have exceeded the maximum login attempts. Please try later");
            return false;

        } else {
            // it is ok
            $_SESSION['reg-attempt'] = 0;
            $_SESSION['reg-danie-time'] = time() - 180;
        }
    }
    */
    // An invalid attempt
    if ($result == 0) {
        /*if (isset($_SESSION['reg-attempt'])) {
            $_SESSION['reg-attempt'] += 1;

            if ($_SESSION['reg-attempt'] >= MAX_LOGIN_ATTEMPTS) {
                $_SESSION['reg-danie-time'] = time() + 180;
                HandleError("You have exceeded the maximum login attempts. Please try again after 3 minutes.");
            } else {
                $r = MAX_LOGIN_ATTEMPTS - $_SESSION['reg-attempt'];
                HandleError("Error: The username or password does not match (Remaining only $r attempt)");
            }

        } else {
            $_SESSION['reg-attempt'] = 1;
            HandleError("Error: The user name or password does not match.");
        }*/

        HandleError("Error: The user name or password does not match.");

        return false;

    } else {
        // Valid login status
        $userId = $db->getUserId_byEmail($userName);

        // Check account status ------------------------------------------------------

        $role = $db->getUserData($userId, "role");

        $sal = json_decode(file_get_contents("../data/salutations.json"), true);
        $userNameString = $sal[$db->getUserData($userId, "salutation")] . " " . $db->getUserData($userId, "firstName") . " " . $db->getUserData($userId, "lastName");
        $userEmailString = $db->getUserData($userId, "email");
        $time = date("Y-m-d H:i:s");

        session_set_cookie_params(1800, '/', '.co224.tk');

        /*
        $ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        */
        $db->setUserData($userId, "lastAccess", $time);
        //$db->setLastLoginIP($userId, $ip);

        //$_SESSION['reg-danie-time'] = NULL;

        //session_start();
        $_SESSION['user'] = nameFormat($userName);
        $_SESSION['userId'] = $userId;
        $_SESSION['userName'] = $userName;
        $_SESSION['role'] = $db->getUserData($userId, "role");

        $_SESSION['userNameString'] = $userNameString;
        $_SESSION['userEmailString'] = $userEmailString;
        $_SESSION['viewMode'] = "desktop";
        $_SESSION['userAccess'] = GetLoginSessionVar();
        //$_SESSION['reg-attempt'] = 0;


        $_SESSION[GetLoginSessionVar()] = GetLoginSessionVar();

        return true;
    }
}

function logOut()
{
    session_start();
    $viewMode = $_SESSION['viewMode'];

    $sessionvar = GetLoginSessionVar();
    $_SESSION[$sessionvar] = NULL;
    unset($_SESSION[$sessionvar]);

    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]);

    session_unset();
    session_destroy();

    if ($viewMode == "mobileApp") {
        print "<script>window.close();</script>";
    } else {
        header("location: ./");
    }
    exit;
}

function HandleError($err)
{
    global $error_message;
    $error_message = $err . "\r\n";
}

function GetErrorMessage()
{
    global $error_message;

    if (empty ($error_message)) {
        return '';
    }
    $errormsg = nl2br(htmlentities($error_message));
    return $errormsg;
}

function GetLoginSessionVar()
{
    $rand_key = "0iQx5oBk66oVZep";
    $retvar = md5($rand_key);
    $retvar = 'usr_' . substr($retvar, 0, 10);
    return $retvar;
}

function nameFormat($str)
{
    if (strlen($str) > 0) {
        return $str;
    } else {
        return "Null";
    }
}

function redirectTo($url)
{
    header("location: " . $url);
}

?>


