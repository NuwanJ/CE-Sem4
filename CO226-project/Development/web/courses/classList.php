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
                <li class="active">Class List</a></li>
            </ul>

            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme">View</a></li>
                <li><a href="add.php" class="tablink">Add New Class</a></li>
                <li><a href="#" class="tablink">Other</a></li>
            </ul>

            <br>
            <?php

            if (!isset($_GET['id'])) {
                exit;
            }
            $courseId = $_GET['id']

            ?>
            <div class="w3-container">
                <?php
                $course = $db->getCourse($courseId);
                $title = $course["courseTitle"];
                $lecId = $course["lecId"];
                $instId = $course["instId"];
                $year = $course["academicYear"];
                $sem = $course["semester"];
                $lecName = $db->getName_byUserId($lecId);
                $instName = $db->getName_byUserId($instId);

                ?>
                <h3><?php echo $title ?></h3>

                <p>
                    Lecturer In-charge: <?php echo $lecName ?><br>
                    Instructor In-charge: <?php echo $instName ?>
                </p>
                <br><br>

                <div class="w3-container">
                    <div class="w3-responsive">
                        <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                            <tr>
                                <th>User ID</th>
                                <th>User</th>
                                <th>Current Attendance</th>
                                <th>Actions</th>
                            </tr>
                            <?php

                            $ids = $db->listClassList_byCourse($courseId, "studId");
                            $salutation = json_decode(file_get_contents("../data/salutations.json"), true);

                            for ($i = 0; $i < sizeof($ids); $i++) {
                                $firstName = $db->getUserData($ids[$i], "firstName");
                                $lastName = $db->getUserData($ids[$i], "lastName");
                                $sal = $salutation[$db->getUserData($ids[$i], "salutation")];
                                $email = $db->getUserData($ids[$i], "email");
                                //$lastAccessed = $db->getUserData($ids[$i], "lastAccess");

                                $attendance = $db->getClassListDataRow($courseId, $ids[$i])['attendance'];
                                print "<tr><td>$ids[$i]</td><td>$sal $firstName $lastName<br>($email)</td><td>$attendance %</td>
                                <td><a href='edit.php?id=$ids[$i]'>Edit</a> | <a href='delete.php?id=$ids[$i]'>Delete</a></td></tr>";
                            }

                            ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
</div>

</body>
</html>