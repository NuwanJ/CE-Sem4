<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../data/meta.php'; ?>
    <?php include '../data/scripts.php'; ?>

    <script>
        $(document).ready(function () {
            $("#er-close").click(function () {
                window.location = "index.php";
            });
        });
    </script>

    <style>
        .error {
            color: red;
            font-size: small;
        }

        .tabBody {
            display: none;
        }

        label {
            padding-top: 10px !important;
        }
    </style>
</head>
<body>

<a name="top"></a>
<?php include '../data/navibar.php'; ?>

<?php
include "../data/database.php";
$db = new database();
?>


<?php

$userName = "";
$userAddress = "";
$userTele = "";
$userEmail = "";
$userNIC = "";
$userBirthday = "";

?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb">
                <li><a href="../home">Home</a></li>
                <li class="active">Settings</a></li>
            </ul>

            <br>

            <div>
                <?php
                if (isset($_GET['resp'])) {
                    $response = $_GET['resp'];

                    $res_text = "";

                    if ($response == 1000) {
                        $res_text = "<strong>Success!</strong>  Details updated success !";
                        $res_type = "alert-success";

                    } else if ($response == 2000) {
                        $res_text = "<strong>Success!</strong>  Password updated success !";
                        $res_type = "alert-success";
                    } else if ($response == 2001) {
                        $res_text = "<strong>Error!</strong>  Current password that entered is invalid";
                        $res_type = "alert-danger";
                    } else if ($response == 2002) {
                        $res_text = "<strong>Error!</strong>  New password mismatched. Please type in the same password in each password field.";
                        $res_type = "alert-danger";
                    } else if ($response == 2003) {
                        $res_text = "<strong>Warning!</strong>  Something went wrong. Please try again later";
                        $res_type = "alert-warning";
                    } else if ($response == 2004) {
                        $res_text = "<strong>Warning!</strong>  Token not exist. Please try again.";
                        $res_type = "alert-warning";
                    } else if ($response == 2005) {
                        $res_text = "<strong>Warning!</strong>  Token already assigned with another account.";
                        $res_type = "alert-warning";
                    }


                    print "<div class='alert $res_type fade in'>
                    <a id='er-close' href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    $res_text
                    </div>";
                }
                ?>
            </div>

            <ul class="w3-navbar w3-theme-l2">
                <li><a href="#" class="tablink w3-theme" onclick="openTab(event, 'GeneralTab');">General</a></li>
                <li><a href="#" class="tablink" onclick="openTab(event, 'AccountTab');">Account</a></li>
                <li><a href="#" class="tablink" onclick="openTab(event, 'OtherTab');">Other</a></li>
            </ul>

            <div>
                <div id="GeneralTab" class="w3-container tabBody" style="display: block;">
                    <form name="details" role="form" action="actions.php?act=basic" method="post">
                        <div class="w3-container w3-padding-12">

                            <input type="hidden" name="post" value="1">

                            <label class="" for="userName">User Name</label>
                            <input class="w3-input w3-border" type="text" id="userName" name="userName" maxlength="64"
                                   value="<?php echo $userName; ?>">

                            <label class="" for="dob">Date of Birth</label>
                            <input class="w3-input w3-border" type="date" id="dob" name="dob"
                                   value="<?php echo $userBirthday; ?>">

                            <label class="" for="address">Address</label>

                            <textarea rows="3" class="form-control" required id="address"
                                      name="address"><?php echo $userAddress; ?></textarea>


                            <label class="" for="userEmail">Email</label>
                            <input class="w3-input w3-border" type="text" id="userEmail" name="userEmail"
                                   maxlength="64" value="<?php echo $userEmail; ?>" readonly>

                            <label class="" for="tele">Telephone</label>

                            <input class="w3-input w3-border" type="tel" required id="tele" name="tele"
                                   placeholder="Ex: 0771234567" value="<?php echo $userTele; ?>">

                            <br>

                            <div class="w3-right">
                                <button type="reset" class="w3-btn w3-light-gray w3-padding-8 w3-theme-btn">Reset
                                </button>
                                <button type="submit" class="w3-btn w3-blue w3-padding-8 w3-theme-btn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="AccountTab" class="w3-container tabBody">
                    <form name="login" class="" role="form" action="actions.php?act=login" method="post">
                        <div class="w3-container w3-padding-12">
                            <input type="hidden" name="post" value="1">

                            <label class="" for="cp">Current Password</label>
                            <input class="w3-input w3-border" type="password" id="cp" name="cp" maxlength="64">

                            <label class="" for="np">New Password</label>
                            <input class="w3-input w3-border" type="password" id="np" name="np" maxlength="64">

                            <label class="" for="rp">Repeat New Password</label>
                            <input class="w3-input w3-border" type="password" id="rp" name="rp" maxlength="64">

                            <br>

                            <div class="w3-right">
                                <button type="reset" class="w3-btn w3-light-gray w3-padding-8 w3-theme-btn">Reset
                                </button>
                                <button type="submit" class="w3-btn w3-blue w3-padding-8 w3-theme-btn">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <br><br><br><br>

        </div>
    </div>
</div>

</body>
</html>