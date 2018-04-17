<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }

include_once "function.php";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/register_page.js"></script>
</head>
<body>
<?php
    include "header.php";
?>

<div id='bodyContent' class='body-content' style='text-align: center'>
    <h3 class='registration-title'>Register for MeTube:</h3>
    <form id="registrationForm" method="post">
    <table class='registration-form' width='50%'>
        <tr>
            <td width="25%" class="registration-label">Username:</td>
            <td width="50%"><input type="text" id="username" maxlength="30" name="username" class="form-control"><br></td>
            <td width="25%" id="usernameValidation" class="registration-validation"></td>
        </tr>
        <tr>
            <td width="25%" class="registration-label">Email:</td>
            <td width="50%"><input type="text" id="email" maxlength="255" name="email" class="form-control"><br></td>
            <td width="25%" id="emailValidation" class="registration-validation"></td>
        </tr>
        <tr>
            <td width="25%" class="registration-label">Create Password:</td>
            <td width="50%"><input type="password" id="password1" name="password1" class="form-control"><br></td>
            <td width="25%" id="password1Validation" class="registration-validation"></td>
        </tr>
        <tr>
            <td width="25%" class="registration-label">Repeat Password:</td>
            <td width="50%"><input type="password" id="password2" name="password2" class="form-control"><br></td>
            <td width="25%" id="password2Validation" class="registration-validation"></td>
        </tr>
        <tr>
            <td width="25%"></td>
            <td width="50%"><input name="submit" type="submit" value="Submit" class="btn btn-primary"></td>
        </tr>
    </table>
    </form>
</div>

</body>
</html>
