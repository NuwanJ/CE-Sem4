<?php

include "../data/db/dbCourses.php";

class dbClasses extends dbCourses
{

    // Class Related Functions ---------------------------------------------------------------------------------------
    function newClass($courseId, $classType, $date, $time, $duration, $conductBy)
    {
        $sql = "INSERT INTO `co226`.`class` (`courseId`, `classType`, `classDate`, `classTime`, `duration`, `conductedBy`)
              VALUES ('$courseId', '$classType', '$date', '$time', '$duration', '$conductBy');";
        return $this->mysqli->query($sql);
    }

    function listClass($field)
    {
        $sql = "SELECT * FROM `co226`.`class` WHERE 1;";
        return $this->listRows($sql, $field);
    }

    function listClass_byCourse($courseId, $field)
    {
        $sql = "SELECT * FROM `co226`.`class` WHERE `courseId` LIKE $courseId";
        return $this->listRows($sql, $field);
    }

    function getClassData($classId)
    {
        $sql = "SELECT * FROM `co226`.`class` WHERE `class`.`id` LIKE '$classId';";
        return $this->getDataRow($sql);
    }

    function setClassData($classId, $field, $value)
    {
        $sql = "UPDATE `co226`.`class` SET `$field` = '$value' WHERE `id` = '$classId';";
        return $this->mysqli->query($sql);
    }

    function deleteClass($id)
    {
        $sql = "DELETE FROM `co226`.`class` WHERE `id` = '$id';";
        return $this->deleteRow($sql);
    }

    function existsClassId($classId)
    {
        $sql = "SELECT * FROM  `co226`.`class`  WHERE `id` LIKE '$classId';";
        return $this->exists($sql);
    }

    // ClassList Related Functions ------------------------------------------------------------------------------------
    function newClassList($courseId, $studentId)
    {
        $sql = "INSERT INTO `co226`.`classlist` (`courseId`, `studId`, `grade`, `attendance`, `midResult`, `endResult`)
                  VALUES ('$courseId', '$studentId', NULL, 100, NULL, NULL);";
        return $this->mysqli->query($sql);
    }

    function deleteClassList($courseId, $studentId)
    {
        $sql = "DELETE FROM `co226`.`classlist` WHERE `courseId`=$courseId  AND `studId`=$studentId;";
        return $this->mysqli->query($sql);
    }

    function listClassList_byCourse($courseId, $field)
    {
        $sql = "SELECT * FROM `co226`.`classlist` WHERE `courseId` LIKE $courseId";
        return $this->listRows($sql, $field);
    }

    function getClassListDataRow($courseId, $studentId)
    {
        $sql = "SELECT * FROM `co226`.`classlist` WHERE `studId` LIKE '$studentId' AND `courseId` LIKE '$courseId'";
        return $this->getDataRow($sql);
    }

    function listClassList_byStudent($stuId)
    {
        $sql = "SELECT * FROM `co226`.`classlist` WHERE `studId` LIKE $stuId";
        return $this->listWholeRows($sql);
    }

    function getAttendanceStatus($classId, $studId)
    {
        $sql = "SELECT * FROM `attendance` WHERE `classId` = $classId AND `studId` = $studId";

        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $res = $row['attendance'];
        return $res;

    }

}