<?php //include '../data/session.php'; ?>

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

define("FOLDER_NAME", "classes");
include "../data/accessControl.php";

include "../data/database.php";
$db = new database();

?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
                <li class="active">Classes</a></li>
            </ul>

            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme navBarTitle">Classes</a></li>
                <li class="w3-right"><a href="add.php" class="tablink">Add New Class</a></li>
            </ul>

            <br>

            <div class="w3-container">
                <div class="w3-responsive">
                    <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                        <tr>
                            <th>Course</th>
                            <th>Type</th>
                            <th>Date and Time</th>
                            <th>Conduct by</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php

                        $ids = $db->listClass("id");
                        $ids = array_reverse($ids);

                        for ($i = 0; $i < sizeof($ids); $i++) {

                            $class = $db->getClassData($ids[$i]);

                            $title = $db->getCourseData($class["courseId"], "courseTitle");
                            $classType = ucfirst($class["classType"]);
                            $classDate = $class["classDate"];
                            $classTime = $class["classTime"];
                            $duration = $class["duration"];
                            $conductedBy = $class["conductedBy"];

                            $conductedByName = $db->getName_byUserId($conductedBy);

                            print "<tr><td><a href='../attendance/attendance.php?id=$ids[$i]'>$title</a></td>
                                <td>$classType</td>
                                <td>$classDate $classTime<br>($duration mins)</td>
                                <td><a href='#'></a>$conductedByName</td>
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