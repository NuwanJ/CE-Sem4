<?php

include_once '../data/session.php';
include_once '../data/database.php';

$db = new database();

$userId = $_SESSION['userId'];
$userName = $_SESSION['user'];

$action = 0;

$act = $_GET['act'];

if ($act == "basic") {
    /*$userName = $_POST['userName'];
    $birthDay = $_POST['dob'];
    $nic = $_POST['nic'];
    $address = $_POST['address'];
    $tele = $_POST['tele'];

    $res = 0;
    $res += $db->set_userData($userId, "uName", $userName);
    $res += $db->set_userData($userId, "uAddress", $address);
    $res += $db->set_userData($userId, "uTele", $tele);
    $res += $db->set_userData($userId, "uNIC", $nic);
    $res += $db->set_userData($userId, "uBirthday", $birthDay);
*/
    //if ($res == 5) {
    if (1) {
        redirect("index.php?resp=1000");
        exit;
    } else {
        print "<br><br>Sorry, unknown error occurred.<br><br><a href='index.php'>Back</a>";
    }

} else if ($act == "login") {
    $cp = $_POST['cp'];
    $np = $_POST['np'];
    $rp = $_POST['rp'];

    if (md5($cp) != $db->getUserData($userId, "password")) {
        redirect("index.php?resp=2001");

    } else if ($np != $rp) {
        redirect("index.php?resp=2002");
    } else {
        if ($db->setUserData($userId, "password", md5($np))) {
            // success
            redirect("index.php?resp=2000");
        } else {
            // error
            redirect("index.php?resp=2003");
        }

    }

}

function redirect($url)
{
    header("Location: $url");
}


?>






