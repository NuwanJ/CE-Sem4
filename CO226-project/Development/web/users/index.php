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

define("FOLDER_NAME", "student");
include "../data/accessControl.php";

include "../data/database.php";
$db = new database();

$userRoles = array(0 => "Admin", 1 => "Student", 2 => "Lecturer", 3 => "Instructor", 4 => "Date Enter");

?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
                <li class="active">Users</a></li>
            </ul>

            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme navBarTitle">Portal Users</a></li>
                <li class="w3-right"><a href="add.php" class="tablink">Add New User</a></li>
            </ul>

            <br>

            <div class="w3-container">
                <div class="w3-responsive">
                    <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                        <tr>
                            <th>User ID</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Last Accessed Time</th>
                            <th>Actions</th>
                        </tr>
                        <?php

                        $ids = $db->listUsers("id");
                        $salutation = json_decode(file_get_contents("../data/salutations.json"), true);

                        for ($i = 0; $i < sizeof($ids); $i++) {
                            $firstName = $db->getUserData($ids[$i], "firstName");
                            $lastName = $db->getUserData($ids[$i], "lastName");
                            $sal = $salutation[$db->getUserData($ids[$i], "salutation")];
                            $email = $db->getUserData($ids[$i], "email");
                            $role = $userRoles[$db->getUserData($ids[$i], "role")];
                            $lastAccessed = $db->getUserData($ids[$i], "lastAccess");

                            print "<tr><td>$ids[$i]</td><td>$sal $firstName $lastName<br>($email)</td><td>$role</td><td>$lastAccessed</td>
                                <td><a href='edit.php?id=$ids[$i]'>Edit</a> | <a href='delete.php?id=$ids[$i]'>Delete</a></td></tr>";
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