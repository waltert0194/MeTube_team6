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
    <script src="js/edit_playlist_page.js"></script>
    <title>Edit Playlist</title>
</head>

<body>
<?php
    if(isset($_GET['id']))
    {
        if(!isset($_SESSION['username']) || user_exist_check($_SESSION['username']) != 1)
        {
            $_SESSION['prevpage'] = "editplaylist.php?id={$_GET['id']}";
            echo "<meta http-equiv='refresh' content='0;url=login.php'>";
        }
        include "header.php"; 
        
        //Get the playlist's information from the database
        if($query = mysqli_prepare(db_connect_id(), "SELECT username, name, description FROM
            playlist WHERE playlist_id=?"))
        {
            mysqli_stmt_bind_param($query, "i", $_GET['id']);
            $result = mysqli_stmt_execute($query);
            mysqli_stmt_bind_result($query, $username, $name, $description);
            $result = $result && mysqli_stmt_fetch($query);
            mysqli_stmt_close($query);
        }
        else
        {
            $result = false;
        }

        $keywords = get_playlist_keywords($_GET['id']);

        if($result)
        {
            if($_SESSION['username'] === $username)
            {
?>
                <div style="margin-left: 30px;">
                    <form id="editPlaylistMeta" method="post"> 
                        <input type="hidden" id="playlistid" name="playlistid" value="<?php echo $_GET['id']; ?>">
                        <h3>Edit Playlist</h3>
    
                        <h4 style="margin-bottom:0px; margin-top: 20px;">Name Your Playlist</h4>
                        <input id="name" name="name" type="text" class="form-control" maxlength="40"
                            value="<?php echo $name;?>"style="width: 550px;">
                        <p id="nameValidation" class="edit-playlist-validation"></p>

                        <h4 style="margin-bottom:0px; margin-top: 20px;">Playlist Description</h4>
                        <textarea id="description" name="description" class="form-control"
                            maxlength="1000" rows="5" style="resize: none; width: 550px;"
                            ><?php echo $description;?></textarea>
                        <p id="descriptionValidation" class="edit-playlist-validation"></p>

                        <h4 style="margin-bottom:0px; margin-top: 20px;">Keyword</h4>
                        <h6 style="margin-bottom:0px; margin-top: 0px;">Separate with a space</h6>
                        <input name="keywords" type="text" id="keywords" class="form-control"
                            style="width: 550px;" value="<?php echo $keywords;?>">

                        <a href="playlist.php?id=<?php echo $_GET['id']; ?>">
                            <input id="cancelBtn" style="margin-top: 20px;" value="Cancel" name="cancel"
                                type="button" class="btn btn-primary"/>
                        </a>
                        <input id="submitBtn" style="margin-top: 20px;" value="Submit" name="submit"
                            type="submit" class="btn btn-primary"/>
	                </form>
                </div>
<?php 
            }
        }
        else
        {
?>
            <div class="body-content">
                <div class="alert alert-danger" style="text-align: center">
                    <strong>Error:</strong> The selected playlist could not be found.
                </div>
            </div>
<?php
        }
    }
    else
    {
        include "header.php";
?>
        <div class="body-content">
            <div class="alert alert-danger" style="text-align: center">
                <strong>Error:</strong> No playlist id was given.
            </div>
        </div>
<?php
    }
?>
</body>
</html>

