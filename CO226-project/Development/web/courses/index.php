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

define("FOLDER_NAME", "course");
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
                <li class="active">Courses</a></li>
            </ul>

            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme navBarTitle">Courses</a></li>
                <li class="w3-right"><a href="add.php" class="tablink" >Add New Course</a></li>

            </ul>

            <br>

            <div class="w3-container">
                <div class="w3-responsive">
                    <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                        <tr>
                            <th>Course ID</th>
                            <th>Title</th>
                            <th>L. In-charge</th>
                            <th>I. In-charge</th>
                            <th>Actions</th>
                        </tr>
                        <?php

                        $courses = $db->listCourses();// = $db->listCourses("id");

                        //print_r($courses[0]);

                        for ($i = 0; $i < sizeof($courses); $i++) {
                            $id = $courses[$i]["id"];
                            $title = $courses[$i]["courseTitle"];
                            $lecId = $courses[$i]["lecId"];
                            $instId = $courses[$i]["instId"];
                            $year = $courses[$i]["academicYear"];
                            $sem = $courses[$i]["semester"];

                            $lecName = $db->getName_byUserId($lecId);
                            $instName = $db->getName_byUserId($instId);

                            print "<tr><td>$id</td><td><a href='classList.php?id=$id'>$title</a></td>
                                <td><a href='#$lecId'>$lecName</a></td><td><a href='#$instId'>$instName</a></td>
                                <td>
                                    <a href='edit.php?id=$id'><i class='fa fa-pencil-square-o'></i></a>
                                    <a href='../classlist/?id=$id'><i class='fa fa-gear'></i></a>
                                    <a href='delete.php?id=$id'><i class='fa fa-trash'></i></a>
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