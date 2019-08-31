<div class="w3-display-container w3-top w3-card-2">
    <div class="w3-bar w3-theme w3-xlarge">
        <a href="../home/" class="w3-bar-item w3-button w3-hover-theme">
            <i class="fa fa-home"></i></a>
        <span class="w3-bar-item w3-large w3-padding-12 w3-hide-small">Online Attendance Portal</span>
        <span class="w3-bar-item w3-large w3-padding-12 w3-small w3-hide-medium w3-hide-large">Online Attendance</span>

        <div class="w3-right w3-dropdown-click">
            <div id="userPanel" class="w3-dropdown-content w3-white w3-card-4 w3-right w3-center w3-medium"
                 style="right:0!important;top:52px!important; width: 350px;">

                <div class="w3-container">
                    <div class="w3-row">
                        <div class="w3-col s4">
                            <img src="../img/userIcon.png" class="w3-responsive w3-padding-12" style="width: 80%;">
                        </div>
                        <div class="w3-col s8 w3-padding-16 w3-left-align">
                            <p class="w3-medium"><?php echo $_SESSION['userNameString'] ?>
                                <span
                                    class="w3-small w3-text-gray"><?php echo $_SESSION['userEmailString'] ?></span><br>
                            </p>

                            <p>
                                <a href="../settings/" class="app-link">Settings</a>
                                <a href="../messages/" class="app-link">Messages</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="w3-padding-0 w3-btn-group w3-center w3-light-gray">
                    <a href="../settings/" class="w3-btn w3-white w3-border w3-margin-8 w3-hide"
                       style="width: 30%; box-shadow:0 0 0 0!important;">
                        <i class="fa fa-gear" style="font-size:24px;"></i></a>

                    <a href="../login/logout" class="w3-btn w3-white w3-border w3-margin-8 w3-right"
                       style="width: 40%; box-shadow:0 0 0 0!important;">
                        <i class="fa fa-sign-out" style="font-size:24px;"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <a href="#" id="userPanelBtn" class="w3-bar-item w3-button w3-hover-theme w3-animate-opacity w3-right"
           style="margin-right: 25px!important; border: 1px solid #000000l;" onclick="toggleUserPanel()"><i
                class="fa fa-user"></i></a>

        <a href="../messages/"
           class="w3-bar-item w3-button w3-hover-theme w3-animate-opacity w3-right w3-hide">
            <i class="fa fa-bell"></i></a>

        <div class="w3-clear"></div>
    </div>

    <div class="w3-bar w3-theme" id="naviBottom">
    </div>
</div>