<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CO226</title>

    <link rel="stylesheet" href="../css/w3.css">
    <link rel="stylesheet" href="../css/w3-theme.css">
    <link rel="stylesheet" href="../css/login-window.css">

    <script src="../js/jquery.min.js"></script>
    <link rel="shortcut icon" href="../img/fav.ico">

</head>
<body>


<div class="w3-container">
    <div id="id01" class="w3-modal" style="display: block">
        <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:500px">

            <div class="w3-center"><br>
                <img src="../img/login/logo.png" alt="Logo" style="width:60%" class="w3-margin-4">
            </div>

            <form class="w3-container" action="./submit" method="post" style="padding-top: 0!important;">
                <input type='hidden' name='submitted' id='submitted' value='1'/>
                <input type='hidden' name='service' id='service' value='portal'/>
                <?php

                if (isset($_GET['rd'])) {
                    $rd = $_GET['rd'];
                    print "<input type='hidden' name='rd' id='rd' value='$rd'>";
                }

                if (isset($_GET['app'])) {
                    $service = $_GET['app'];
                    print "<input type='hidden' name='app' id='app' value='$service'>";
                }
                ?>

                <div class="w3-section w3-padding-32 w3-padding">
                    <label><b>Email</b></label>
                    <input class="w3-input w3-border w3-margin-bottom" type="text" name="username" required
                           placeholder="Enter your email here" value="<?php if (isset($_GET['un'])) echo $_GET['un'] ?>">
                    <label><b>Password</b></label>
                    <input class="w3-input w3-border" type="password"
                           placeholder="Enter your password here" name="password" required>

                    <div class="w3-center w3-padding-4 w3-padding-top">
                        <span class="w3-text-red "><?php echo GetErrorMessage(); ?></span>
                    </div>

                    <button class="w3-button w3-block w3-hover-blue w3-theme-d2 w3-section w3-padding" type="submit">
                        Login
                    </button>

                    <div class="w3-small w3-text-dark-gray">
                        <div class="w3-hide"><input class="w3-check w3-margin-top" type="checkbox" checked="checked">
                            Remember me
                        </div>

                        <!--<span class="w3-left w3-padding w3-hide-small">Haven't <a href="#">Account</a> ?</span>-->
                        <!--<span class="w3-right w3-padding w3-hide-small">Forgot <a href="#">Password</a> ?</span>-->
                    </div>
                </div>
            </form>

            <div class="w3-hide w3-container w3-border-top w3-padding-16 w3-light-grey">
                <div class="w3-center">
                    <p class="w3-small">Or connect with:</p>

                    <div>
                        <button class="loginBtn loginBtn-facebook" onClick="logInWithFacebook()">
                            Facebook
                        </button>

                        <button class="loginBtn loginBtn-google" onClick="logInWithGoogle()">
                            Google
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function logInWithFacebook() {
        alert("This service is currently unavailable");
    }

    function logInWithGoogle() {
        alert("This service is currently unavailable");
    }
</script>

</body>
</html>