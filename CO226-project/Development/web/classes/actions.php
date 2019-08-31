<?php

include_once '../data/session.php';
include_once '../data/database.php';

define("FOLDER_NAME", "classes");
include "../data/accessControl.php";

$db = new database();

$userId = $_SESSION['userId'];
$userName = $_SESSION['user'];

$action = 0;

$act = $_GET['act'];

if ($act == "new") {
    $courseId = $_POST['courseId'];
    $classType = $_POST['classType'];
    $classDate = $_POST['classDate'];
    $classTime = $_POST['classTime'];
    $duration = $_POST['duration'];
    $conductedBy = $_POST['conductedBy'];

    if ($db->newClass($courseId, $classType, $classDate, $classTime, $duration, $conductedBy) == 0) {
        echo "<script>alert('something has happened unexpectedly');</script>";
        echo "<script>history.go(-1);</script>";

    } else {
        redirect("index.php");
    }


} else if ($act == "update") {
    // Array ( [id] => 100015 [classDate] => 2019-01-06 [classTime] => 08:00:00 [duration] => 120 [courseId] => 10001 [classType] => lecture [conductedBy] => 100013 )
    $id = $_POST['id'];
    print_r($_POST);

    $result = 0;
    $result += $db->setClassData($id, "courseId", $_POST['courseId']);
    $result += $db->setClassData($id, "classType", $_POST['classType']);
    $result += $db->setClassData($id, "classDate", $_POST['classDate']);
    $result += $db->setClassData($id, "classTime", $_POST['classTime']);
    $result += $db->setClassData($id, "duration", $_POST['duration']);
    $result += $db->setClassData($id, "conductedBy", $_POST['conductedBy']);

    if ($result != 6) {
        echo "<script>alert('Something happened unexpectedly');</script>";
        echo "<script>history.go(-1);</script>";

    } else {
        redirect("index.php");
    };

} else if ($act == "delete") {
    $id = $_POST['courseId'];

    if ($db->deleteClass($id)) {
        redirect("index.php");
        // exit;
    } else {
        echo "<script>alert('Sorry, an unknown error occurred !');</script>";
        echo "<script>history.go(-1);</script>";
    }
}


function redirect($url)
{
    header("Location: $url");
}



