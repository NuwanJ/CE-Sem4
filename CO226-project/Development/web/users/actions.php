<?php

include_once '../data/session.php';
include_once '../data/database.php';

$db = new database();

$userId = $_SESSION['userId'];
$userName = $_SESSION['user'];

$action = 0;

$act = $_GET['act'];

define("FOLDER_NAME", "student");
include "../data/accessControl.php";


if ($act == "new") {
    $salutation = $_POST['salutation'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if ($db->existsEmail($email)) {
        echo "<script>alert('Sorry, email is already assigned to another user !');</script>";
        echo "<script>history.go(-1);</script>";

    } else if ($db->newUser($firstName, $lastName, $salutation, $email, md5($lastName), $role, $time)) {
        if ($role == 1) {
            // Student
            $eNum = $_POST['eNum'];
            $dept = $_POST['dept'];
            $id = $db->getUserId_byEmail($email);

            if ($db->existStudent($id) == false) {
                $id = $db->getUserId_byEmail($email);

                if ($db->newStudent($id, $eNum, $dept)) {
                    redirect("index.php");

                    // Send an email of acknowledgement
                    // exit;
                } else {
                    echo "<script>alert('Sorry, an unknown error occurred while adding the users !');</script>";
                    echo "<script>history.go(-1);</script>";
                }
            } else {
                echo "<script>alert('Sorry, eNumber is already assigned for another users !');</script>";
                echo "<script>history.go(-1);</script>";
            }
        } else {
            redirect("index.php");
            // exit;
        }

    } else {
        echo "<script>alert('Sorry, an unknown error occurred !');</script>";
        echo "<script>history.go(-1);</script>";
    }

} else if ($act == "update") {

    $id = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $salutation = $_POST['salutation'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $res = 0;
    $res += $db->setUserData($id, "firstName", $firstName);
    $res += $db->setUserData($id, "lastName", $lastName);
    $res += $db->setUserData($id, "salutation", $salutation);
    $res += $db->setUserData($id, "email", $email);
    $res += $db->setUserData($id, "role", $role);

    //print_r($_POST);

    if ($res == 5) {

        if ($role == 1) {
            // Student
            $eNum = $_POST['eNum'];
            $dept = $_POST['dept'];

            echo $db->existStudent($id) . " " . $id;

            if ($db->existStudent($id) == false) {
                $db->newStudent($id, $eNum, $dept);
                redirect("index.php");

            } else {
                $res += $db->setStudentData($id, "eNum", $eNum);
                $res += $db->setStudentData($id, "dept", $dept);

                echo $res;

                if ($res == 7) {
                    redirect("index.php");

                } else {
                    echo "<script>alert('Sorry, an unknown error occurred while editing the users !');</script>";
                    echo "<script>history.go(-1);</script>";
                }
            }
        } else {
            redirect("index.php");
        }

    } else {
        echo "<script>alert('Sorry, an unknown error occurred !');</script>";
        echo "<script>history.go(-1);</script>";
    }

} else if ($act == "delete") {
    $id = $_POST['userId'];

    if ($id == $_SESSION['userId']) {
        echo "<script>alert('You can not delete your account !');</script>";
        echo "<script>history.go(-1);</script>";

    } else {
        if ($db->getUserData($id, "role") == 1) {
            $db->deleteStudent($id);
        }

        if ($db->deleteUser($id)) {
            redirect("index.php");
            // exit;
        } else {
            echo "<script>alert('Sorry, an unknown error occurred !');</script>";
            echo "<script>history.go(-1);</script>";
        }
    }
}


function redirect($url)
{
    header("Location: $url");
}


?>






