<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }

    //Unset the username session variable to log the user out
    if(isset($_SESSION['username']))
        session_unset();

    //Redirect the user to the index page
    header("Location: ./index.php");
    exit();
?>

