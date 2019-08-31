<<<<<<< HEAD
<?php include '../data/session.php'; ?>
=======
<?php //include '../data/session.php'; ?>
>>>>>>> 5dcb4ffe3a6705623fa2fa0b289ae1dc2a8ad933

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../data/meta.php'; ?>
    <?php include '../data/scripts.php'; ?>

</head>
<body>

<a name="top"></a>
<?php include '../data/navibar.php'; ?>

<?php

<<<<<<< HEAD
define("FOLDER_NAME", "student");
=======
define("FOLDER_NAME", "classes");
>>>>>>> 5dcb4ffe3a6705623fa2fa0b289ae1dc2a8ad933
include "../data/accessControl.php";

include "../data/database.php";
$db = new database();
<<<<<<< HEAD
=======
//$userId = $_SESSION['userId'];

$userID=100000;
>>>>>>> 5dcb4ffe3a6705623fa2fa0b289ae1dc2a8ad933

?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

<<<<<<< HEAD
            <?php

            if (!isset($_GET['id'])) {
                echo "<h4>Invalid Access !!!</h4>";
                exit;
            }
            $courseId = $_GET['id'];
            $courseTitle = $db->getCourseData($courseId, "courseTitle");

            if (!$db->existsCourseId($courseId)) {
                echo "<h4>Invalid Course Id !!!</h4>";
                exit;
            }

            ?>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
                <li>Course Attendance</li>
                <li class="active"><?php echo $courseTitle ?></a></li>
=======
            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
            </ul>

            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme">View</a></li>
>>>>>>> 5dcb4ffe3a6705623fa2fa0b289ae1dc2a8ad933
            </ul>

            <br>

            <div class="w3-container">
<<<<<<< HEAD

                <?php

                $course = $db->getCourse($courseId);
                $lecId = $course["lecId"];
                $instId = $course["instId"];
                $year = $course["academicYear"];
                $sem = $course["semester"];
                $lecName = $db->getName_byUserId($lecId);
                $instName = $db->getName_byUserId($instId);

                $studId = $userId;
                //print "present=$p, absent=$a studentId=$studId courseId=$courseId";
                ?>
                <h3><?php echo $courseTitle ?></h3>

                <p>
                    Lecturer In-charge: <?php echo $lecName ?><br>
                    Instructor In-charge: <?php echo $instName ?>
                </p>
                <br>

                <div class="w3-responsive">
                    <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                        <tr>
                            <th>Type</th>
                            <th>Date and Time</th>
                            <th>Conducted by</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php

                        $ids = $db->listClass_byCourse($courseId, "id");
                        $ids = array_reverse($ids);

                        for ($i = 0; $i < sizeof($ids); $i++) {

                            $class = $db->getClassData($ids[$i]);

                            $classType = ucfirst($class["classType"]);
                            $classDate = $class["classDate"];
                            $classTime = $class["classTime"];
                            $duration = $class["duration"];
                            $conductedBy = $class["conductedBy"];

                            $conductedByName = $db->getName_byUserId($conductedBy);

                            $statusMsg = array(0 => "Absent", 1 => "Present", "" => "N/A");
                            $statusColor = array(0 => "w3-red", 1 => "w3-green", "" => "w3-orange");

                            $status = $db->getAttendanceStatus($ids[$i], $studId);

                            print "<tr>
                                <td>$classType</td>
                                <td>$classDate $classTime<br>($duration mins)</td>
                                <td><a href='#'></a>$conductedByName</td>
                                <td><span style='width: 100px;' class='w3-tag $statusColor[$status]'>$statusMsg[$status]</span></td></tr>";
=======
                <div class="w3-responsive">
                    <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                        <tr>
                            <th>Course</th>
                            <th>Attendance</th>
                            <th>Grade</th>
                            <th>Mid Result</th>
                            <th>End Result</th>
                            <th>Rescheduling Request</th>

                        </tr>
                        <?php
                        $courses = $db->coursesWithAttendance($userID);

                        foreach ($courses as $course) {

                            $courseId=$course['courseId'];
                            $course_title = $db->getCourseData($courseId, "courseTitle").' ('.$db->getCourseData($course['courseId'], "academicYear").')';
                            $attendance = $course['attendance'] ? $course['attendance'] : '---';
                            $grade = $course['grade'] ? $course['grade']: '---' ;
                            $midResult = $course['midResult'] ? $course['midResult'] : '---' ;
                            $endResult = $course['endResult'] ? $course['endResult']: '----';
                            print "<tr><td>$course_title</td>
                                <td>$attendance</td>
                                <td>$grade</td>
                                <td>$midResult</td>
                                <td>$endResult</td>
                                <td><a href='request.php?courseId=$courseId'>Request</a></td>
                                </tr>";
>>>>>>> 5dcb4ffe3a6705623fa2fa0b289ae1dc2a8ad933

                        }

                        ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
</div>

</body>
</html>