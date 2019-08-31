<?php

define("DB_HOST", "localhost");
define("DB_DATABASE", "co226");

define("DB_USER", "co226user");
define("DB_PASS", "c02jguS3r?");


class database
{
    private $mysqli;

    function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    function __destruct()
    {
        $this->mysqli->close();
    }

    function get_SiteData($key)
    {
        $sql = "SELECT * FROM `site_data` WHERE `dKey` LIKE '$key'";

        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        return $row['dValue'];

    }

    function set_SiteData($key, $val)
    {

        $sql = "UPDATE `site_data` SET `dValue` = '$val' WHERE `dKey` = $key;";
        if ($result = $this->mysqli->query($sql)) {
            return 1;
        } else {
            return 0;
        }

    }

    // Login Related Functions ----------------------------------------------------------------------------------------
    function existsEmail($email)
    {
        $email = mysqli_real_escape_string($this->mysqli, $email);

        $sql = "SELECT * FROM `users` WHERE `email` LIKE '$email'";

        if ($result = $this->mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function existsUser($email, $password)
    {
        $email = mysqli_real_escape_string($this->mysqli, $email);
        $pwdmd5 = md5($password);

        $sql = "SELECT * FROM `users` WHERE `email` LIKE '$email' AND `password` LIKE '$pwdmd5';";

        if ($result = $this->mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function existsUserId($userId)
    {
        $userId = mysqli_real_escape_string($this->mysqli, $userId);

        $sql = "SELECT * FROM `users` WHERE `id` LIKE '$userId';";

        if ($result = $this->mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    // User Related Functions -----------------------------------------------------------------------------------------
    function newUser($firstName, $lastName, $salutation, $email, $password, $role, $lastAccess)
    {
        $firstName = $this->sqlSafe($firstName);
        $lastName = $this->sqlSafe($lastName);
        $email = $this->sqlSafe($email);

        $sql = "INSERT INTO `users` (`firstName`, `lastName`, `salutation`, `email`, `password`, `role`, `lastAccess`)
              VALUES ( '$firstName', '$lastName', '$salutation','$email', '$password', '$role', '$lastAccess');";
        return $this->mysqli->query($sql);
    }

    function getUserId_byEmail($email)
    {
        $sql = "SELECT `id` FROM `users` WHERE `email` LIKE '$email'";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $res = $row['id'];
        return $res;
    }

    function getUserData($userId, $field)
    {
        $sql = "SELECT * FROM `users` WHERE `id` LIKE $userId";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $res = $row[$field];
        return $res;
    }

    function setUserData($userId, $field, $value)
    {
        $sql = "UPDATE `users` SET `$field` = '$value' WHERE `id` = '$userId';";
        return $this->mysqli->query($sql);
    }

    function listUsers($field)
    {
        $sql = "SELECT * FROM `users` WHERE 1";
        return $this->listRows($sql, $field);
    }

    function deleteUser($userId)
    {
        $sql = "DELETE FROM `users` WHERE `id` LIKE '$userId';";
        if ($this->mysqli->query($sql) == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    function getName_byUserId($userId)
    {
        $salutation = json_decode(file_get_contents("../data/salutations.json"), true);

        $sql = "SELECT * FROM `users` WHERE `id` LIKE $userId";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        return $salutation[$row['salutation']] . " " . $row['firstName'] . " " . $row['lastName'];
    }

    // Student Related Functions ---------------------------------------------------------------------------------------
    function newStudent($id, $eNum, $dept)
    {
        $eNum = $this->sqlSafe($eNum);
        $dept = $this->sqlSafe($dept);

        $sql = "INSERT INTO `co226`.`userstudent` (`eNum`, `id`, `dept`)
                VALUES ('$eNum', '$id', '$dept');";
        return $this->mysqli->query($sql);
    }

    function existStudent($id)
    {
        $sql = "SELECT * FROM `userstudent` WHERE `id` = $id";

        if ($result = $this->mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function getStudentData($userId, $field)
    {
        $sql = "SELECT * FROM `users`, `userstudent` WHERE (`users`.`id` LIKE $userId) AND (`userstudent`.`id` LIKE $userId);";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $res = $row[$field];
        return $res;
    }

    function setStudentData($userId, $field, $value)
    {
        $sql = "UPDATE `userstudent` SET `$field` = '$value' WHERE `id` = '$userId';";
        return $this->mysqli->query($sql);
    }

    function deleteStudent($userId)
    {
        $sql = "DELETE FROM `userstudent` WHERE `id` LIKE '$userId';";
        if ($this->mysqli->query($sql) == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    function listLecturers()
    {
        $sql = "SELECT * FROM `users` WHERE `role` LIKE 2;";
        return $this->listWholeRows($sql);
    }

    function listInstructors()
    {
        $sql = "SELECT * FROM `users` WHERE `role` LIKE 3;";
        return $this->listWholeRows($sql);
    }

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

    // Attendance Related Functions ---------------------------------------------------------------------------------------

    function isAttendanceExists($classId, $studentId)
    {
        $sql = "SELECT * FROM `co226`.`attendance` WHERE `classId` = $classId AND `studId` = $studentId;";
        return $this->exists($sql);
    }

    function newAttendance($classId, $studentId, $status)
    {
        $sql = "INSERT INTO `co226`.`attendance` (`classId`, `studId`, `attendance`)
              VALUES ('$classId', '$studentId', '$status');";
        return ($this->mysqli->query($sql) == TRUE);
    }

    function updateAttendance($classId, $studentId, $status)
    {
        $sql = "UPDATE `attendance` SET `attendance` = '$status' WHERE `classId` = $classId AND `studId` = $studentId";
        return ($this->mysqli->query($sql) == TRUE);
    }

    function getAttendance($classId, $studentId)
    {
        $sql = "SELECT * FROM `co226`.`attendance` WHERE `classId` = $classId AND `studId` = $studentId;";

        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $res = $row['attendance'];
        return $res;
    }

    function getAttendanceData($courseId, $studentId, $status)
    {
        $sql = "SELECT COUNT(*) as c FROM attendance
                WHERE (classId IN (SELECT id FROM class WHERE courseId = $courseId) )
                AND (studId LIKE $studentId)
                AND (attendance LIKE $status);
";
        return $this->getDataRow($sql)['c'];
    }

    function setCourseAttendance($courseId, $studentId, $value)
    {
        $sql = "UPDATE `classlist` SET `attendance` = '$value' WHERE `courseId` = $courseId AND `studId` = $studentId";
        return ($this->mysqli->query($sql) == TRUE);
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
    // Class Related Functions ---------------------------------------------------------------------------------------


    // Utility Functions -----------------------------------------------------------------------------------------------
    private
    function sqlSafe($text)
    {
        $text = str_replace("'", "\"", $text);
        $text = str_replace("`", "\"", $text);

        return $text;
    }

    // Super Functions -----------------------------------------------------------------------------------------------

    function exists($sql)
    {
        if ($result = $this->mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function getData($sql, $field)
    {
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $res = $row[$field];
        return $res;
    }

    function getDataRow($sql)
    {
        $result = $this->mysqli->query($sql);
        return $result->fetch_assoc();
    }

    function listRows($sql, $field)
    {
        if ($result = $this->mysqli->query($sql)) {
            $j = 0;
            $arAdd = array();

            while ($row = mysqli_fetch_array($result)) {
                $arAdd[$j] = $row[$field];
                $j++;
            }
            return $arAdd;
        } else {
            return 0;
        }
    }

    function listWholeRows($sql)
    {

        if ($result = $this->mysqli->query($sql)) {
            $j = 0;
            $arAdd = array();

            while ($row = mysqli_fetch_array($result)) {
                $arAdd[$j] = $row;
                $j++;
            }
            return $arAdd;
        } else {
            return 0;
        }
    }

    function deleteRow($sql)
    {
        if ($this->mysqli->query($sql) == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    function q_Update($table, $key, $value, $field, $new)
    {
        $sql = "UPDATE `$table` SET `$field` = '$new' WHERE `$key` = '$value';";
        if ($this->mysqli->query($sql) == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    function q_Select($table, $key, $value, $field)
    {
        $sql = "SELECT * FROM `$table` WHERE `$key`.`id` = '$value';";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();

        $res = $row[$field];
        return $res;
    }

    function q_Delete($table, $field, $value)
    {
        $sql = "DELETE FROM `$table` WHERE `$field` = '$value';";
        if ($this->mysqli->query($sql) == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    function q_Exist($table, $field, $value)
    {
        $sql = "SELECT * FROM `$table` WHERE `$field` LIKE '$value';";

        if ($result = $this->mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function q_List($table, $field, $option)
    {

        $sql = "SELECT * FROM `$table` WHERE $option";
        if ($result = $this->mysqli->query($sql)) {
            $j = 0;
            $arAdd = array();

            while ($row = mysqli_fetch_array($result)) {
                $arAdd[$j] = $row[$field];
                $j++;
            }
            return $arAdd;
        } else {
            return 0;
        }
    }

}