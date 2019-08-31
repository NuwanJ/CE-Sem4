<?php include '../data/session.php'; ?>

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

define("FOLDER_NAME", "attendance");
include "../data/accessControl.php";

include "../data/database.php";
$db = new database();

?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

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

            } else if (!($db->getCourseData($courseId, "lecId") == $userId || $db->getCourseData($courseId, "instId") == $userId || $role == 0)) {
                echo "<h4>You are not authorized to access this page  !!!</h4>";
                exit;
            }

            ?>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
                <li>Attendance</li>
                <li class="active"><?php echo $courseTitle ?></a></li>
            </ul>


            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme navBarTitle">Classes</a></li>
                <li class="w3-right"><a href="add.php?id=<?php echo $courseId ?>" class="tablink">Add New Class</a></li>
            </ul>

            <br>

            <div class="w3-container">

                <?php
                $course = $db->getCourse($courseId);
                $lecId = $course["lecId"];
                $instId = $course["instId"];
                $year = $course["academicYear"];
                $sem = $course["semester"];
                $lecName = $db->getName_byUserId($lecId);
                $instName = $db->getName_byUserId($instId);

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

                            // TODO: need to add a method to check the attendance mark status
                            // TODO: need to show from list, how many students were attended to the class

                            print "<tr>
                                <td>$classType</td>
                                <td>$classDate $classTime<br>($duration mins)</td>
                                <td><a href='#'></a>$conductedByName</td>
                                <td>
                                    <a href='attendance.php?id=$ids[$i]'>Mark Attendance</a>
                                </td>
                                <td>
                                    <a href='edit.php?id=$ids[$i]'><i class='fa fa-pencil-square-o'></i></a>
                                    <a href='delete.php?id=$ids[$i]'><i class='fa fa-trash'></i></a>
                                </td></tr>";

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