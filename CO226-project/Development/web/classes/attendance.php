<?php //include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../data/meta.php'; ?>
    <?php include '../data/scripts.php'; ?>

    <style>
        label {
            margin: 5px !important;
        }
    </style>
</head>
<body>

<a name="top"></a>
<?php include '../data/navibar.php'; ?>

<?php
include "../data/database.php";
$db = new database();

//if (!($_SESSION['role'] == 0 || $_SESSION['role'] == 4)) {
//    include_once '../404.shtml';
//    exit;
//}

?>

<div class="w3-row">
    <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
    <div class="w3-col s12 m8 l8">
        <br><br><br><br>

        <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li><a href="../classes/index.php">Classes</a></li>
            <li class="active">Mark Attendance</a></li>
        </ul>

        <br>

        <div>
            <?php

            if (!isset($_GET['id'])) {
                echo "<h4>Invalid Access !!!</h4>";
                exit;

            }
            $id = $_GET['id'];
            //            $data = $db->getClass($id);
            //            var_dump($data);
            $courseId=$db->getClassData($id, "courseId");
            $title = $db->getCourseData($courseId, "courseTitle");
            $classType = $db->getClassData($id, "classType");
            $classDate = $db->getClassData($id, "classDate");
            $classTime = $db->getClassData($id, "classTime");
            $duration = $db->getClassData($id, "duration");
            $conductedBy = $db->getClassData($id, "conductedBy");

            $lecName = $db->getName_byUserId($conductedBy);

            ?>
            <form name="editClass" role="form" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
                  method="post" action="actions.php?act=update">

                <h2>Class Details</h2>
                <p><input name="id" type="hidden" value="<?= $id; ?>"></p>
                <p>Date :  <?= $classDate ?></p>
                <p>Started At : <?= $classTime ?></p>
                <p>Duration :  <?= $duration ?> mins</p>
                <p>Course Title : <?= $title?></p>
                <p> Type : <?= $classType ?></p>
                <p>
                Conducted By : <?= $lecName?>
                </p>


            </form>
        </div>

        <h2>Class list of <?= $db->getCourseData($courseId, 'courseTitle') ?> (<?=$db->getCourseData($courseId, 'academicYear') ?>)</h2>
        <div class="w3-container">
            <div class="w3-responsive">
                <h3>Students In ClassList</h3>
                <p>
                    <button id="mark" class="w3-btn w3-theme w3-round">Mark Attendance</button>
                </p>
                <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>ENumber</th>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $students = $db->listStudentsAttendanceNotMarked($id,$courseId);
                    foreach ($students as $student) {

                        print "<tr id=" . $student['id'] . "><td><input type='radio' value='0' name=" . $student['id'] . "/>  <input type='radio' value='1' name=" . $student['id'] . "/> </td>
                                <td>" . $student['eNum'] . "</td><td>" . $db->getName_byUserId($student['id']) . "</td>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br><br><br><br>

    </div>

</div>


<script>
</script>

</body>
</html>