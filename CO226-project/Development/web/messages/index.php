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
include "../data/database.php";
$db = new database();
?>

<?php

$f = 0;
$l = 10;

if (isset($_GET['f'])) $f = $_GET['f'];

?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb">
                <li><a href="../home">Home</a></li>
                <li class="active">Messages</a></li>
            </ul>

            <br><br>

        </div>
    </div>
</div>


</body>
</html>