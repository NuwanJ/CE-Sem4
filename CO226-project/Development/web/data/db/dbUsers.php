<?php

include "../data/db/dbClasses.php";

class dbUsers extends dbClasses{


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

}