<?php
session_start();

if (!(isset($_SESSION['user']))) {
    //sign out
    header("location: ../index.php");
    exit;
}
$userName = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>CO226</title>

    <!--
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    -->

    <script type="application/javascript" src="../js/jquery.min.js"></script>
    <script type="application/javascript" src="../js/bootstrap.min.js"></script>
    <script type="application/javascript" src="../js/jquery.validate.min.js"></script>
    <script type="application/javascript" src="../js/cr.js"></script>

    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/index.css">
    <link rel="shortcut icon" href="../img/fav.ico">

    <script type="application/javascript" src="../js/index.js"></script>


    <style>
        td {
            vertical-align: middle;
        }

        input {
            margin: 2px;
        }

        .btn {
            width: 100px;
            margin-right: 10px;;
        }
    </style>

</head>
<body>

<a name="top"></a>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <div class="navbar-brand" id="page-title">Register</div>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php print $userName;?><span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../login/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>