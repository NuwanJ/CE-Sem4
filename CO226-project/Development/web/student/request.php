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

define("FOLDER_NAME", "student");
include "../data/accessControl.php";

include "../data/database.php";
$db = new database();



?>



<div class="w3-row">
    </div>
    <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
    <div class="w3-col s12 m8 l8">
        <br><br><br><br>

        <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li><a href="../users/">Users</a></li>
            <li class="active">Add User</a></li>
        </ul>

        <br>

        <div>
            <form name="newRequest" role="form" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
                  method="post" action="">

                <h2>Request for Rescheduling</h2>
                <br>

                <p>
                    <label>Class</label>
                    <select class="w3-select w3-border w3-round" name="salutation" required>
                        <option value="" disabled selected>Select the Class</option>
                        <?php
                        $classes=$db->classByCourse($courseId);
                        foreach ($classes as $class) {
                            $classID=$class['id'];
                            $classTime=$class['classTime'];
                            $classDate=$class['classDate'];
                            echo "<option value='$classID'>Lab on $classDate at $classTime</option>";
                        }
                        ?>
                    </select>
                <p>
                    <label>Subject</label>
                    <input class="w3-input w3-border w3-round" name="subject" type="text" required></p>

                <p>
                    <label>Message</label>
                    <textarea class="w3-input w3-border w3-round" name="message"  required></textarea></p>

                <p>
                    <button type="submit" class="w3-btn w3-theme w3-round">Send Request</button>
                </p>

            </form>
        </div>

        <br><br><br><br>

    </div>

</div>

<script>
    $(document).ready(function () {

        $("#role").change(function () {

            if ($("#role option:selected").text() == "Student") {
                $("#student").removeClass("w3-hide");
            } else {
                $("#student").addClass("w3-hide");
            }
        })
    });
</script>


</body>
</html>