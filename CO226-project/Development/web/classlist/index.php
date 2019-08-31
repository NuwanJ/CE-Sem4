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

define("FOLDER_NAME", "classlist");
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
                <li class="active">ClassList</a></li>
            </ul>
            <?php

            if (!isset($_GET['id'])) {
                echo "<h4>Incomplete Access !!!</h4>";
                exit;

            }
            $courseId = $_GET['id'];
            ?>


            <div class="w3-container">
                <br>

                <h3>Class list of <?= $db->getCourseData($courseId, 'courseTitle') ?>
                    (<?= $db->getCourseData($courseId, 'academicYear') ?>)</h3>


                <div class="w3-row">
                    <div class="w3-col s12 m6 l6">
                        <div class="w3-responsive">
                            <h4>Students not in the ClassList</h4>

                            <p>
                                <button id="save" class="w3-btn w3-theme w3-round">Add to ClassList ></button>
                            </p>

                            <table class="w3-table w3-bordered w3-striped w3-border">
                                <thead>
                                <tr>
                                    <th><a href="javascript:void()" onclick="selectAll('NotIn')"
                                           class="w3-hover-opacity">Select</a></th>
                                    <th>ENumber</th>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $students = $db->listStudentsNotInClassList($courseId);
                                foreach ($students as $student) {
                                    print "<tr id=" . $student['id'] . "><td><input class='NotIn' type='checkbox'/> </td>
                                            <td>" . $student['eNum'] . "</td><td>" . $db->getName_byUserId($student['id']) . "</td>";
                                }
                                ?>

                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="w3-col s12 m6 l6">
                        <div class="w3-responsive">
                            <h4>Students in the ClassList</h4>

                            <p>
                                <button id="remove" class="w3-btn w3-theme w3-round">< Remove from ClassList</button>
                            </p>

                            <table class="w3-table w3-bordered w3-striped w3-border">
                                <thead>
                                <tr>
                                    <th><a href="javascript:void()" onclick="selectAll('In')"
                                           class="w3-hover-opacity">Select</a></th>
                                    <th>ENumber</th>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $students = $db->listStudentsInClassList($courseId);

                                foreach ($students as $student) {
                                    print "<tr id=" . $student['id'] . "><td><input class='In' type='checkbox'/> </td>
                                        <td>" . $student['eNum'] . "</td><td>" . $db->getName_byUserId($student['id']) . "</td>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <br><br><br>
</div>

</body>
</html>

<script>

    function selectAll(t) {

        if (t == 'NotIn') {
            $('.NotIn').trigger('click');
        } else if (t == 'In') {
            $('.In').trigger('click');
        }

    }
    $('#save').click(function () {
        var selectedRowsHTML = [];
        var i = 0;
        let courseId =<?=$courseId?>;
        if ($('table input:checked').length > 0) {
            $('table input:checked').each(function () {
                selectedRowsHTML [i] = $(this).closest('tr').attr('id');
                i++;
            });

            $.ajax({
                type: 'POST',
                url: 'addStudents.php',
                data: {
                    action: 'save',
                    details: selectedRowsHTML,
                    courseId: courseId
                },
                success: function () {
                    alert('Entries have been added successfully');
                    window.location.reload(true);
                },
                error: function () {
                    alert("some error has occurred");
                    window.location.reload(true);

                }
            });
        }

    });

    $('#remove').click(function () {
        var selectedRowsHTML = [];
        var i = 0;
        var courseId =<?=$courseId?>;
        if ($('table input:checked').length > 0) {
            $('table input:checked').each(function () {
                selectedRowsHTML [i] = $(this).closest('tr').attr('id');
                i++;
            });
            $.ajax({
                type: 'POST',
                url: 'addStudents.php',
                data: {
                    action: 'remove',
                    details: selectedRowsHTML,
                    courseId: courseId
                },
                success: function () {
                    alert('Removed entries form the classList Successfully');
                    window.location.reload(true);
                },
                error: function () {
                    alert("some error has occurred");
                    window.location.reload(true);

                }
            });
        }

    })

</script>