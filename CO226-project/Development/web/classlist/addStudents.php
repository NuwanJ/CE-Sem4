<?php

include_once '../data/session.php';
include_once '../data/database.php';

$db = new database();

$userId = $_SESSION['userId'];
$userName = $_SESSION['user'];

$action = 0;

$data = $_POST['details'];
$courseId = $_POST['courseId'];

if ($_POST['action'] == 'save') {
    foreach ($data as $studId) {
        if (!$db->newClassList($courseId, $studId)) {
            return http_response_code(422);
            break;
        }
    }
} else if ($_POST['action'] == 'remove') {

    foreach ($data as $studId) {
        if (!$db->deleteClassList($courseId, $studId)) {
            return http_response_code(422);
            break;
        }
    }
}





