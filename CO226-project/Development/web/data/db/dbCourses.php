<?php

class dbCourses
{

    // Course Related Functions ---------------------------------------------------------------------------------------
    function newCourse($courseTitle, $year, $semester, $lecId, $instId, $contactHours, $labHours)
    {
        $sql = "INSERT INTO `co226`.`courses` (`id`, `courseTitle`, `academicYear`, `semester`, `lecId`, `instId`, `contactHours`, `labHours`) VALUES
            (NULL, '$courseTitle', '$year', '$semester', '$lecId', '$instId', '$contactHours', '$labHours');";
        return $this->mysqli->query($sql);
    }

    function getCourse($id)
    {
        $sql = "SELECT * FROM `co226`.`courses` WHERE `id` LIKE $id;";
        return $this->getDataRow($sql);
    }

    function getCourseData($courseId, $field)
    {
        $sql = "SELECT * FROM `co226`.`courses` WHERE `id` LIKE $courseId;";
        return $this->getData($sql, $field);
    }

    function setCourseData($courseId, $field, $value)
    {
        $sql = "UPDATE `co226`.`courses` SET `$field` = '$value' WHERE `id` = '$courseId';";
        return $this->mysqli->query($sql);
    }

    function existsCourseTitle($courseTitle, $year)
    {
        $sql = "SELECT * FROM `co226`.`courses` WHERE `courseTitle` LIKE '$courseTitle' AND  `academicYear` LIKE '$year'";
        return $this->exists($sql);
    }

    function deleteCourse($id)
    {
        $sql = "DELETE FROM `co226`.`courses` WHERE `id` = '$id';";
        return $this->deleteRow($sql);
    }

    function listCourses()
    {
        $sql = "SELECT * FROM `co226`.`courses` WHERE 1;";
        return $this->listWholeRows($sql);
    }

    function listCourses_bySemester($year, $semester, $field)
    {
        $sql = "SELECT * FROM `co226`.`courses` WHERE `academicYear` LIKE '$year' AND `semester` LIKE '$semester';";
        return $this->listRows($sql, $field);
    }

    function listCourses_byIncharge($userId, $year, $semester, $field)
    {
        $sql = "SELECT * FROM `co226`.`courses` WHERE `academicYear` LIKE '$year' AND `semester` LIKE '$semester' AND (`lecId` LIKE $userId OR `instId` LIKE $userId);";
        return $this->listRows($sql, $field);
    }

    function existsCourseId($courseId)
    {
        $courseId = mysqli_real_escape_string($this->mysqli, $courseId);
        $sql = "SELECT * FROM  `co226`.`courses`  WHERE `id` LIKE '$courseId';";
        return $this->exists($sql);
    }


    function listStudentsNotInClassList($courseId)
    {
        $sql = "SELECT * FROM `userstudent` WHERE `id` NOT IN (select `studId` from classlist where `courseId`=$courseId) ;";
        return $this->listWholeRows($sql);
    }

    function listStudentsInClassList($courseId)
    {
        $sql = "SELECT * FROM `userstudent` WHERE `id` IN (select `studId` from classlist where `courseId`=$courseId) ;";
        return $this->listWholeRows($sql);
    }

} 