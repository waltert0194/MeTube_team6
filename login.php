<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
include_once "function.php";

if(isset($_POST['submit'])) {
		if($_POST['username'] == "" || $_POST['password'] == "") {
			$login_error = "One or more fields are missing.";
		}
		else {
			$check = user_pass_check($_POST['username'],$_POST['password']); // Call functions from function.php
			if($check == 1) {
				$login_error = "User ".$_POST['username']." not found.";
			}
			elseif($check==2) {
				$login_error = "Incorrect username or password.";
			}
			else if($check==0){
				if($query = mysqli_prepare(db_connect_id(), "SELECT username FROM account WHERE username=?"))
    			{
        			mysqli_stmt_bind_param($query, "s", $_POST['username']);
        			if(mysqli_stmt_execute($query)){ //Query failed
        			    mysqli_stmt_bind_result($query, $fetchedUsername);
        			    $exists = mysqli_stmt_fetch($query);
       				    mysqli_stmt_close($query);

                        if($exists){
                            $_SESSION['username'] = $fetchedUsername;
                            if(isset($_SESSION['prevpage']) && $_SESSION['prevpage'] != "")
                            {
                                $returnto = $_SESSION['prevpage'];
                                $_SESSION['prevpage'] = "";
                                header('Location: '.$returnto);
                            }
                            else
                                header('Location: index.php');
                        }
                        else
                            $login_error = "Could not retrieve account.";
				    }
    			}
                else
                {
                    $login_error = "Could not retrieve account."; //Could not connect
                }

				

			}		
		}
}


 
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
<?php
    include "header.php";
?>
<div id='bodyContent' class='body-content' style='text-align: center'>
    <h3 class='login-title'>Login to MeTube:</h3>
    <form method="post" action="<?php echo "login.php"; ?>">
	<table class='login-form' width="50%">
		<tr>
            <td width="20%" class="login-label">Username:</td>
			<td width="80%"><input class="form-control" type="text" name="username"><br /></td>
		</tr>
		<tr>
			<td  width="20%" class="login-label">Password:</td>
			<td width="80%"><input class="form-control"  type="password" name="password"><br /></td>
		</tr>
        <tr>
            <td></td>
            <td style="position:relative"><input class="btn btn-primary" name="submit" type="submit" value="Login">
            <input class="btn btn-primary" name="reset" type="reset" value="Reset">
<?php
        if(isset($login_error))
            echo "<div id='passwd_result'>".$login_error."</div>";
?>
            </td>
            <td></td>
        </tr>
    </table>
    </form>
    <h6>Don't have an account? Register <a href="register.php">here</a>!</h6>
</div>
</body>
