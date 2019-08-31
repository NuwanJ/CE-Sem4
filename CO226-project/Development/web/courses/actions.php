<?php

include_once '../data/session.php';
include_once '../data/database.php';

define("FOLDER_NAME", "course");
include "../data/accessControl.php";

$db = new database();

$act = $_GET['act'];

if ($act == "new") {

    $courseTitle = $_POST['courseTitle'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $lecId = $_POST['lecId'];
    $instId = $_POST['instId'];
    $contactHours = $_POST['contactHours'];
    $labHours = $_POST['labHours'];

    if ($db->existsCourseTitle($courseTitle, $year)) {
        echo "<script>alert('Sorry, Course has been already created!');</script>";
        echo "<script>history.go(-1);</script>";

    } else {

        $d = $db->newCourse($courseTitle, $year, $semester, $lecId, $instId, $contactHours, $labHours);
        echo "<script>alert('Course Created Successfully');</script>";
        echo "<script>window.location.href='index.php'</script>";

    }
} else if ($act == "update") {

    $courseId = $_POST['courseId'];
    $result = 0;
    $result += $db->setCourseData($courseId, "courseTitle", $_POST['courseTitle']);
    $result += $db->setCourseData($courseId, "academicYear", $_POST['year']);
    $result += $db->setCourseData($courseId, "semester", $_POST['semester']);
    $result += $db->setCourseData($courseId, "lecId", $_POST['lecId']);
    $result += $db->setCourseData($courseId, "instId", $_POST['instId']);
    $result += $db->setCourseData($courseId, "contactHours", $_POST['contactHours']);
    $result += $db->setCourseData($courseId, "contactHours", $_POST['contactHours']);

    if ($result != 7) {
        echo "<script>alert('Sorry, an unknown error occurred !);</script>";
        echo "<script>history.go(-1);</script>";

    } else {
        echo "<script>alert('Course Details Has Been Updated');</script>";
        echo "<script>window.location.href='./index.php';</script>";
    };

} else if ($act == "delete") {
    $id = $_POST['courseId'];

    if ($db->deleteCourse($id)) {
        redirect("index.php");
        // exit;
    } else {
        echo "<script>alert('Sorry, an unknown error occurred !');</script>";
        echo "<script>history.go(-1);</script>";
    }
}








