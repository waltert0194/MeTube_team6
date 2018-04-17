<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
include_once "function.php";

/******************************************************
*
* download by username
*
*******************************************************/

$username=$_SESSION['username'];
$mediaid=$_REQUEST['id'];

//insert into download table
if($query = mysqli_prepare(db_connect_id(), "INSERT INTO download(dl_id, username, mediaid, dl_date, dl_ip) VALUES (NULL, ?, ?, NOW(), ?)"))
{
    mysqli_stmt_bind_param($query, "sis", $username, $mediaid, $_SERVER['REMOTE_ADDR']);
    mysqli_stmt_execute($query);
    mysqli_stmt_close($query);
}
?>


