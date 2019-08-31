<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../data/meta.php'; ?>
    <?php include '../data/scripts.php'; ?>

    <link href="../css/home.css" rel="stylesheet"/>
</head>
<body>

<?php include '../data/navibar.php'; ?>

<?php
define("FOLDER_NAME", "home");
include "../data/accessControl.php";

include "../data/database.php";
$db = new database();
?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <div class="w3-container">
                <br><br><br>
                <?php
                if ($_SESSION['role'] == 0 || $_SESSION['role'] == 4) {
                    // Admin || Data Enter

                    print "<div class='w3-row' style='padding-top: 20px;'><h3 class='home-group'>&nbsp;</h3></div>";
                    print " <div class='w3-row'>";
                    printTile("Users", "../users/index.php", "contact.png", "w3-metro-purple");
                    printTile("Courses", "../courses/index.php", "admin.png", "w3-metro-light-green");
                    printTile("Classes", "../classes/index.php", "admin.png", "w3-metro-dark-orange ");
                    print "</div>";

                    //print " </div><br><div class='w3-row'>";
                    //printTile("Add New User", "../users/add.php", "new.png", "w3-metro-dark-orange");
                    //print "</div>";
                }

                if ($_SESSION['role'] == 0) {
                    // admin
                    $semester = 4;
                    print "<div class='w3-row' style='padding-top: 20px;'><h3 class='home-group'>Courses (Semester $semester, Admin View)</h3></div>";
                    print " <div class='w3-row'>";
                    //  $db->get_SiteData("currentSem")
                    $courseIds = $db->listCourses_bySemester($db->get_SiteData("currentAcYear"), $semester, "id");

                    foreach ($courseIds as $courseId) {
                        $title = $db->getCourseData($courseId, "courseTitle");
                        printCourseManage($courseId, $title);

                    }
                    print "</div>";


                }

                if ($_SESSION['role'] == 2 || $_SESSION['role'] == 3) {
                    // lecturer, instructor
                    $semester = 4;
                    print "<div class='w3-row' style='padding-top: 20px;'><h3 class='home-group'>Attendance (Semester $semester, Lecturer/Instructor View)</h3></div>";
                    print " <div class='w3-row'>";
                    $courseIds = $db->listCourses_byIncharge($userId, $db->get_SiteData("currentAcYear"), $semester, "id");

                    foreach ($courseIds as $courseId) {
                        $title = $db->getCourseData($courseId, "courseTitle");
                        printCourseManage($courseId, $title);

                    }
                    print "</div>";


                }

                if ($db->existStudent($_SESSION['userId'])) {
                    print "<div class='w3-row' style='padding-top: 20px;'><h3 class='home-group'>My Courses (Semester $semester)</h3></div>";
                    print " <div class='w3-row'>";

                    $list = $db->listClassList_byStudent($_SESSION['userId']);

                    foreach ($list as $course) {
                        //echo $course['courseId'] . "<br>";
                        $title = $db->getCourseData($course['courseId'], "courseTitle");
                        $data = $db->getClassListDataRow($course['courseId'], $_SESSION['userId'])['attendance'];
                        printCourse($title, $data, $course['courseId']);
                    }
                    print "</div>";

                    print "<div class='w3-row' style='padding-top: 20px;'><h3 class='home-group'>Services</h3></div>";
                    print "<div class='w3-col s12 m6 l4 w3-padding-8 w3-animate-opacity ' style='padding: 4px!important;'>
                                <div class='w3-card-2 w3-border w3-metro-dark-purple'>
                                    <a href='#' style='text-decoration: none;'>
                                        <div class='w3-container w3-padding-12'>Submit Attendance Review<br></div>
                                    </a>
                                </div>
                            </div>";

                    print "<div class='w3-col s12 m6 l4 w3-padding-8 w3-animate-opacity ' style='padding: 4px!important;'>
                                <div class='w3-card-2 w3-border w3-metro-blue'>
                                    <a href='#' style='text-decoration: none;'>
                                        <div class='w3-container w3-padding-12'>Submit a Medical<br></div>
                                    </a>
                                </div>
                            </div>";

                    print "<div class='w3-col s12 m6 l4 w3-padding-8 w3-animate-opacity ' style='padding: 4px!important;'>
                                <div class='w3-card-2 w3-border w3-metro-green'>
                                    <a href='#' style='text-decoration: none;'>
                                        <div class='w3-container w3-padding-12'>Reschedule a Lab Class<br></div>
                                    </a>
                                </div>
                            </div>";
                    print "</div>";
                }

                ?>


            </div>
        </div>
    </div>
    <br><br><br>
</div>

<script>
    function navigate(url) {
        window.location.href = url;
    }
</script>


<?php
//include_once '../data/footer.php';

/*if (isset($_GET['welcome'])) {
    $db->set_userData($userId, "status", "ACTIVE");
    include_once 'welcome.html';
}
*/

function newRow($c)
{
    if ($c == 4) {
        $c = 0;
        print "</div><br><br><div class='row'>";
    }
    return $c;
}

function printCourseManage($id, $title)
{

    echo "<div class='w3-col s12 m6 l6 w3-padding-8' style='padding: 4px!important;'>
        <div class='w3-card-2 w3-border w3-animate-opacity'>
            <div class='w3-container w3-padding-8'>$title<br>

                <div class='w3-right'>
                    <a href='../classlist/?id=$id' class='w3-btn w3-theme w3-small' >Class List</a>
                    <a href='../attendance/?id=$id' class='w3-btn w3-theme w3-small' >Attendance</a>
                </div>
            </div>
            </div>
        </div>";
}

function printCourse($title, $attendance, $courseId)
{
    if ($attendance > 85) {
        $color = "w3-green";
        $colorBg = "w3-gray";
    } else if ($attendance >= 80) {
        $color = "w3-orange";
        $colorBg = "w3-gray";
    } else {
        $color = "w3-red";
        $colorBg = "w3-gray";
    }
    echo "<a href='../student/?id=$courseId' style='text-decoration: none;'><div class='w3-col s12 m6 l6 w3-padding-8' style='padding: 4px!important;'>
        <div class='w3-card-2 w3-border w3-animate-opacity'>
            <div class='w3-container w3-padding-8'>$title<br></div>

            <div class='w3-progress-container $colorBg'>
                <div id='myBar' class='w3-progressbar $color' style='width:$attendance%'>
                    <div class='w3-center w3-text-white'>$attendance%</div>
                </div>
            </div>
        </div>
    </div></a>";
}

function printTile($title, $href, $img, $color)
{
    print "
        <div class='w3-col s6 m4 l3' style='padding: 4px!important;'>
            <a href='$href' class='w3-center' style='text-decoration: none;'>
            <div class='$color w3-center homeTile'>
                <img class='w3-animate-opacity' style='width: 60%;' src='../img/iconsHome/$img'>
                <div class='w3-responsive homeTileName'>$title</div>
            </div>
            </a>
        </div>";
}

?>

</body>
</html>