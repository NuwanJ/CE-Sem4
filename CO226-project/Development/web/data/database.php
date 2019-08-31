<?php

define("DB_HOST", "localhost");
define("DB_DATABASE", "co226");
<<<<<<< HEAD

define("DB_USER", "co226user");
define("DB_PASS", "c02jguS3r?");
=======
//
//define("DB_USER", "co226user");
//define("DB_PASS", "c02jguS3r?");
//
define("DB_USER", "root");
define("DB_PASS", "password");
>>>>>>> 5dcb4ffe3a6705623fa2fa0b289ae1dc2a8ad933

include "../data/db/dbUsers.php";

class database extends dbUsers
{
    public $mysqli;

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

<<<<<<< HEAD
    function getAttendanceData($courseId, $studentId, $status)
=======
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
    function listStudentsAttendanceNotMarked($classId ,$courseId )
    {
        $sql = "SELECT * FROM `userstudent` WHERE `id` IN (select `studId` from classlist  where courseId=$courseId and studId not in (select studId from attendance where classId=$classId) ) ;";
        return $this->listWholeRows($sql);
    }

    function newAttendance($classId, $studId,$attendance)
    {
        $sql = "INSERT INTO `co226`.`attendance` (`classId`, `studId`,`attendance`)
                  VALUES ('$classId', '$studId','$attendance');";
        return $this->mysqli->query($sql);
    }

    function coursesWithAttendance( $studId)
    {
        $sql = "SELECT * FROM `classlist` WHERE `studId` LIKE $studId;";
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

    function classByCourse($courseId)
    {
        $sql = "SELECT * FROM `co226`.`class` WHERE `courseId` LIKE $courseId AND `classType`='lab'";
        return $this->listWholeRows($sql);
    }



    function getClassData($courseId, $field)
    {
        $sql = "SELECT * FROM `co226`.`class` WHERE `id` LIKE $courseId;";
        return $this->getData($sql, $field);
    }

    // ClassList Related Functions ------------------------------------------------------------------------------------
    function newClassList($courseId, $studentId)
    {
        $sql = "INSERT INTO `co226`.`classlist` (`courseId`, `studId`, `grade`, `attendance`, `midResult`, `endResult`)
                  VALUES ('$courseId', '$studentId', NULL, NULL, NULL, NULL);";
        return $this->mysqli->query($sql);
    }
    function deleteClassList($courseId, $studentId)
    {
        $sql = "delete from `co226`.`classlist` where `courseId`=$courseId  and  `studId`=$studentId;";
        return $this->mysqli->query($sql);
    }

    function listClassList_byCourse($courseId, $field)
    {
        $sql = "SELECT * FROM `co226`.`classlist` WHERE `courseId` LIKE $courseId";
        return $this->listRows($sql, $field);
    }

    function listClassList_byStudent($stuId, $field)
>>>>>>> 5dcb4ffe3a6705623fa2fa0b289ae1dc2a8ad933
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



    // Utility Functions -----------------------------------------------------------------------------------------------
    private function sqlSafe($text)
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