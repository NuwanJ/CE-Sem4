<?php

//include_once '../data/session.php';
include_once '../data/database.php';

$db = new database();

//$userId = $_SESSION['userId'];
//$userName = $_SESSION['user'];

$action = 0;

$act = $_GET['act'];

//if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 4)) {
//    include_once '../404.shtml';
//    exit;
//}


if ($act == "new") {
    $courseId = $_POST['courseId'];
    $classType = $_POST['classType'];
    $classDate = $_POST['classDate'];
    $classTime= $_POST['classTime'];
    $duration= $_POST['duration'];
    $conductedBy= $_POST['conductedBy'];
    echo "<h2>$conductedBy</h2>";
        $d=$db->newClass($courseId, $classType, $classDate, $classTime, $duration, $conductedBy);
        if($d==0) {
            echo "<script>alert('something has happened unexpectedly');</script>";

        }else {
            echo "<script>alert('class created successfully');</script>";
            echo "<script>window.location.href='index.php'</script>";
        }



} else if ($act == "update") {

    $id = $_POST['id'];

    $result=$db->setClassData($id,$_POST);
    if(!$result){
        echo "<script>alert('Something happened unexpectedly');</script>";
        echo "<script>history.go(-1);</script>";

   echo "<h2>".$result."</h2>";

    }else{
        echo "<script>alert('Class Details Has Been Updated');</script>";
        echo  "<script>window.location.href='./index.php';</script>";
    };

}






