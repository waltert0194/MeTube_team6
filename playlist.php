<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
	include_once "function.php";
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MeTube | Playlist</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/playlist_view.js"></script>
</head>

<body>
<?php
    include 'header.php';

if(isset($_GET['id']))
{
    $_SESSION['prevpage'] = "playlist.php?id=".$_GET['id'];
    echo "<div id='bodyContent' class='body-content'>";
    //Get the media's information from the database
    if($query = mysqli_prepare(db_connect_id(), "SELECT username, creation_date, name, description FROM playlist WHERE playlist_id=?"))
    {
        mysqli_stmt_bind_param($query, "i", $_GET['id']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $username, $date, $name, $description);
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
?>
        <div id='playlistDetailsContainer' class='playlist-details-container'>
<?php
            if(isset($_SESSION['username']) && ($_SESSION['username'] === $username))
            {
                echo "<span title='Delete Playlist' class='glyphicon glyphicon-remove btn-delete-playlist'></span>";
                echo "<a href='editplaylist.php?id={$_GET['id']}'><span title='Edit Playlist Details' class='glyphicon glyphicon-pencil btn-edit-playlist'></span></a>";
            }
?>
            <h3 class='playlist-title'><?php echo $name ?></h3>

            <p class='playlist-description-value'>
                <strong>Created By:</strong>
                <a href="account.php?username=<?php echo urlencode($username); ?>">
                    <?php echo $username; ?>
                </a>
            </p>
            <p class='playlist-description-value'>
                <strong>Creation Time:</strong> <?php echo $date; ?>
            </p>
            <p class='playlist-description-value'>
                <strong>Description:</strong>
                <?php
                    if($description != NULL)
                        echo $description;
                    else
                        echo "No description.";
                ?>
            </p>
            <p class='playlist-description-value'>
                <strong>Keywords:</strong>
                <?php
                    if($keywords != NULL)
                        echo $keywords;
                    else
                        echo "No keywords.";
                ?>
            </p>
        </div>

        <div id='playlistMediaContainer' class='playlist-media-container'>
        <div style='height: 5px'></div>
<?php
            //Get and display all media in the playlist
            if($mediaQuery = mysqli_prepare(db_connect_id(), "SELECT media.title, media.type, media.mediaid, 
                media.upload_date, media.username, media.category FROM playlist_media INNER JOIN media ON 
                playlist_media.mediaid=media.mediaid WHERE playlist_media.playlist_id=?
                ORDER BY media.upload_date DESC"))
            {
                mysqli_stmt_bind_param($mediaQuery, "i", $_GET['id']);
                if(mysqli_stmt_execute($mediaQuery))
                {
                    mysqli_stmt_bind_result($mediaQuery, $mediaTitle, $mediaType, $mediaID, $mediaDate, $mediaUser, $mediaCategory);
                    if(!mysqli_stmt_fetch($mediaQuery))
                    {
                        echo "<h5>There is no media in this playlist yet.</h5>";
                    }
                    else
                    {    
                        do
                        {
                            echo "<div class='playlist-media-details-container'>"; 
                            echo "<input type='hidden' name='mediaidField' value='$mediaID'>";
                            
                            if(isset($_SESSION['username']) && ($_SESSION['username'] === $username))
                            {
                                echo "<span title='Delete Media From Playlist' class='glyphicon ".
                                     "glyphicon-remove btn-delete-playlist-media'></span>";
                            }

                            switch(substr($mediaType,0,5))
                            {
                                case "video":
                                    echo "<span class=\"glyphicon glyphicon-film\"></span> ";
                                    break;
                                case "audio":
                                    echo "<span class=\"glyphicon glyphicon-music\"></span> ";
                                    break;
                                case "image":
                                    echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
                                    break;
                                default: echo substr($mediaType,0,5);
                            }

                            echo "<a href='media.php?id=$mediaID&playlistid={$_GET['id']}'>$mediaTitle</a><br>";
                            echo "From: <a href='account.php?username=$mediaUser'>$mediaUser</a><br>";
                            echo "Uploaded: $mediaDate<br>";
                            echo "Category: $mediaCategory<br>";
                            echo "</div>";
                        } while(mysqli_stmt_fetch($mediaQuery));
                    }
                }
                else
                    echo "<h5>There is no media in this playlist yet.</h5>";
                mysqli_stmt_close($mediaQuery);
            }
            else
            {
                echo "<h5>There is no media in this playlist yet.</h5>";
            }
?>
        </div>
        <br>
        <input type="hidden" id="playlistidJS" name="playlistidJS" value="<?php echo $_GET['id']; ?>">
<?php
    }
    else
    {
?>
        <div class="alert alert-danger" style="text-align: center">
            <strong>Error:</strong> The selected playlist could not be found.
        </div>
<?php
    }
    echo "</div>";
}
else
{
?>
<meta http-equiv="refresh" content="0;url=index.php">
<?php
}
?>
</body>
</html>
