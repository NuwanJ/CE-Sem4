<?php
header("Access-Control-Allow-Origin: *");

include_once '../data/session.php';
include_once '../data/database.php';

define("FOLDER_NAME", "attendance");
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
        echo "<script>history.go(-2);</script>";
        //redirect("index.php?id=");
    }

} else if ($act == "update") {
    $id = $_POST['id'];

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
        echo "<script>history.go(-2);</script>";
        //redirect("index.php?id=");
    };

} else if ($act == "delete") {
    $id = $_POST['courseId'];

    if ($db->deleteClass($id)) {
        echo "<script>history.go(-2);</script>";
        //redirect("index.php?id=");
        // exit;
    } else {
        echo "<script>alert('Sorry, an unknown error occurred !');</script>";
        echo "<script>history.go(-1);</script>";
    }

} else if ($act == "attendance") {
    header('Content-Type: application/json');

    $studId = $_POST['studId'];
    $classId = $_POST['classId'];
    $status = $_POST['statusId'];
    $courseId = $db->getClassData($classId)['courseId'];
    // update attendance too

    if ($db->isAttendanceExists($classId, $studId) == 0) {
        $db->newAttendance($classId, $studId, $status);
    } else {
        $db->updateAttendance($classId, $studId, $status);
    }

    $p = $db->getAttendanceData($courseId, $studId, 1);
    $a = $db->getAttendanceData($courseId, $studId, 0);

    if (($p + $a) == 0) {
        $percentage = 0;
    } else {
        $percentage = round(($p / ($a + $p)) * 100, 2);
    }

    $db->setCourseAttendance($courseId, $studId, $percentage);

    $resp = array("statusCode" => "S1000", "statusDetails" => "Success", "return" => "", "percentage" => $percentage);
    $resp = json_encode($resp);

    echo $resp;

}

function redirect($url)
{
    header("Location: $url");
}



