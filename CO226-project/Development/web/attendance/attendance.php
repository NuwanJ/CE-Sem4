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
                exit;
            }
            $classId = $_GET['id'];
            $courseId = $db->getClassData($classId)['courseId'];
            $courseTitle = $db->getCourseData($courseId, "courseTitle");

            ?>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home/">Home</a></li>
                <li><a href="../attendance/?id=<?php echo $courseId ?>">Attendance (<?php echo $courseTitle ?>)</a></li>
                <li class="active">Mark Attendance</a></li>
            </ul>

            <ul class="w3-hide w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme">View</a></li>
                <li><a href="add.php" class="tablink">Add New Class</a></li>
                <li><a href="#" class="tablink">Other</a></li>
            </ul>

            <br>

            <div class="w3-container">
                <?php
                $classId = $_GET['id'];
                $class = $db->getClassData($classId);

                $courseId = $class['courseId'];

                $classType = $class["classType"];
                $classDate = $class["classDate"];
                $classTime = $class["classTime"];
                $duration = $class["duration"];
                $conductedBy = $class["conductedBy"];

                $course = $db->getCourse($courseId);
                $title = $course["courseTitle"];
                $lecId = $course["lecId"];
                $instId = $course["instId"];
                $year = $course["academicYear"];
                $sem = $course["semester"];

                $conductedByName = $db->getName_byUserId($conductedBy);

                ?>
                <h3><?php echo $title ?></h3>

                <p>
                    Conducted by: <?php echo $conductedByName ?> |
                    <?php echo $classDate . " " . $classTime ?><br>
                    Started At : <?= $classTime ?> |
                    Duration :  <?= $duration ?> mins</p>
                <br>

                <div class="w3-container w3-right">
                    <button class="w3-button w3-small w3-theme-l2" onclick="markAll()">Mark All Present</button>
                    <button class="w3-button w3-small w3-theme-l2" onclick="unmarkAll()">Mark All Absent</button>
                </div>

                </p>
                <br><br>

                <div class="w3-container">
                    <div class="w3-responsive">
                        <table class="w3-table w3-bordered w3-striped w3-border ">
                            <tr>
                                <th>User ID</th>
                                <th>User</th>
                                <th>Actions</th>
                                <th>Current Attendance</th>
                            </tr>
                            <?php

                            $ids = $db->listClassList_byCourse($courseId, "studId");
                            $salutation = json_decode(file_get_contents("../data/salutations.json"), true);

                            for ($i = 0; $i < sizeof($ids); $i++) {
                                $firstName = $db->getStudentData($ids[$i], "firstName");
                                $lastName = $db->getStudentData($ids[$i], "lastName");
                                $sal = $salutation[$db->getStudentData($ids[$i], "salutation")];
                                $email = $db->getStudentData($ids[$i], "email");
                                $eNum = $db->getStudentData($ids[$i], "eNum");

                                $status = $db->getAttendance($classId, $ids[$i]);

                                if ($status == "") {
                                    $present = "w3-gray";
                                    $absent = "w3-gray";
                                } else if ($status == 1) {
                                    $present = "w3-blue";
                                    $absent = "w3-gray";
                                } else if ($status == 0) {
                                    $present = "w3-gray";
                                    $absent = "w3-blue";
                                } else {
                                    $present = "w3-gray";
                                    $absent = "w3-gray";
                                }
                                //echo "*$status $absent " . ($status == "") . "<br>";

                                $p = $db->getAttendanceData($courseId, $ids[$i], 1);
                                $a = $db->getAttendanceData($courseId, $ids[$i], 0);

                                if (($p + $a) == 0) {
                                    $percentage = 0;
                                } else {
                                    $percentage = round(($p / ($a + $p)) * 100, 2);
                                }

                                $percentage = $db->getClassListDataRow($courseId, $ids[$i])['attendance'] . " %";

                                $html = "<div class='w3-btn-group'>
                                      <button class='w3-btn present $present $ids[$i]-present' onclick='update($ids[$i], 1)'>Present</button>
                                      <button class='w3-btn absent $absent $ids[$i]-absent' onclick='update($ids[$i], 0)'>Absent</button></div>";

                                print "<tr><td>$eNum</td><td>$sal $firstName $lastName<br>($email)</td>
                                        <td>$html</td><td><span id='$ids[$i]-val'>$percentage</span></td></tr>";
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

<script>


    function markAll() {
        $('.present').trigger('click');
    }
    function unmarkAll() {
        $('.absent').trigger('click');
    }

    function update(studId, statusId) {
        // status 2=N/A, 1=present, 0=absent

        var postURL = "<?php
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        echo str_replace("attendance.php?id=$classId", "actions.php?act=attendance", $url);?>";

        postData = {
            "studId": studId, "statusId": statusId, "classId": "<?php echo $classId ?>"
        };

        console.log(postData);

        $.ajax({
            type: "POST",
            url: postURL,
            data: postData,
            success: function (data) {
                //alert(studId + " " + statusId + " " + data);

                if (statusId == 1) {
                    // present
                    $("." + studId + "-present").removeClass('w3-gray').addClass('w3-blue');
                    $("." + studId + "-absent").removeClass('w3-blue').addClass('w3-gray');
                } else {
                    // absent
                    $("." + studId + "-absent").removeClass('w3-gray').addClass('w3-blue');
                    $("." + studId + "-present").removeClass('w3-blue').addClass('w3-gray');
                }
                $("#" + studId + "-val").html(data.percentage);
            },
            error: function (request, status, err) {
                alert("Error:\n" + status + ": " + err);
                console.log(request);
            },
            dataType: "json"
        });

    }

</script>
</body>
</html>