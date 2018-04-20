<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/edit_account_page.js"></script>
    <title>MeTube | User Settings</title>
</head>

<body>
<?php
    if(!isset($_SESSION['username']) || user_exist_check($_SESSION['username']) != 1)
    {
        $_SESSION['prevpage'] = "editaccount.php";
        echo "<meta http-equiv='refresh' content='0;url=login.php'>";
    }

	include "header.php";
?>

    <div class="account-edit-sidenav-container">
        <h3 style="text-align: center">User Settings</h3>
        <div id="editProfileTabButton" class='account-edit-sidenav-button active'>
            Update Profile Info 🡺 
           
        </div>
        <div id="changePasswordBtn" class='account-edit-sidenav-button bottom'>
            Change Password 🡺 
            
        </div>
    </div>

    <div id="editAccountMeta" class="account-edit-form">
        <?php include "editprofileinfo.php"; ?>
    </div>
</body>
</html>
