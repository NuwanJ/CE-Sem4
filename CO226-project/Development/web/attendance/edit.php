<?php include '../data/session.php'; ?>

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

define("FOLDER_NAME", "attendance");
include "../data/accessControl.php";

include "../data/database.php";
$db = new database();
?>

<div class="w3-row">
    <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
    <div class="w3-col s12 m8 l8">
        <br><br><br><br>
        <?php

        if (!isset($_GET['id'])) {
            echo "<h4>Invalid Access !!!</h4>";
            exit;

        }
        $id = $_GET['id'];
        $data = $db->getClassData($id);

        $courseId = $data['courseId'];
        $title = $db->getCourseData($courseId, 'courseTitle');
        $classType = $data['classType'];
        $classDate = $data['classDate'];
        $classTime = $data['classTime'];
        $duration = $data['duration'];
        $conductedBy = $data['conductedBy'];

        ?>

        <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li><a href="../attendance/?id=<?php echo $courseId ?>">Attendance (<?php echo $title ?>)</a></li>
            <li class="active">Edit Class</li>
        </ul>

        <br>

        <div>

            <form name="editClass" role="form" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
                  method="post" action="actions.php?act=update">

                <h2>Edit Class Details</h2>
                <br>

                <p><input name="id" type="hidden" value="<?= $id; ?>"></p>

                <p>
                    <label>Course</label>
                    <select class="w3-select w3-border w3-round" name="courseId" required>
                        <?php
                        // This should implement
                        $courses = $db->listCourses();
                        foreach ($courses as $course) {
                            if (($course['id'] == $courseId)) {
                                echo "<option value=" . $course['id'] . " selected>" . $course['courseTitle'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </p>

                <p>
                    <label>Class Date</label>
                    <input class="w3-input w3-border w3-round" name="classDate" type="date" value="<?= $classDate ?>"
                           required></p>

                <p>
                    <label>Class Time</label>
                    <input class="w3-input w3-border w3-round" name="classTime" type="time" min="0"
                           value="<?= $classTime ?>" required></p>

                <p>
                    <label>Duration</label>
                    <input class="w3-input w3-border w3-round" name="duration" type="number" min="0" step="0.1"
                           value="<?= $duration ?>" required></p>

                <p>
                    <label>Class Type</label>
                    <select class="w3-select w3-border w3-round" name="classType">
                        <option value="lecture" <?php if ($classType == "lecture") echo "selected"; ?>>Lecture</option>
                        <option value="lab" <?php if ($classType == "lab") echo "selected"; ?>>Lab</option>
                        <option value="tutorial" <?php if ($classType == "tutorial") echo "selected"; ?>>Tutorial
                        </option>
                    </select>
                </p>
                <p>
                    <label>Conducted by</label>
                    <select class="w3-select w3-border w3-round" name="conductedBy" required>
                        <?php
                        $lecturers = $db->listLecturers();
                        foreach ($lecturers as $lec) {
                            $selected = ($lec['id'] == $conductedBy) ? "selected" : "";
                            echo "<option value=" . $lec['id'] . " $selected >" . $salutation[$lec['salutation']] . " " . $lec['firstName'] . " " . $lec['lastName'] . "</option>";
                        }

                        $instructors = $db->listInstructors();
                        foreach ($instructors as $inst) {
                            $selected = ($inst['id'] == $conductedBy) ? "selected" : "";
                            echo "<option value=" . $inst['id'] . " $selected >" . $salutation[$inst['salutation']] . " " . $inst['firstName'] . " " . $inst['lastName'] . "</option>";
                        }
                        ?>
                    </select>
                </p>


                <p>
                    <button type="submit" class="w3-btn w3-theme w3-round">Update Class Details</button>
                </p>

            </form>
        </div>

        <br><br><br><br>

    </div>

</div>


<script>

</script>

</body>
</html>