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
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MeTube | Media</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/media_view.js"></script>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body>
<?php
    include 'header.php';

if(isset($_GET['id']))
{
	$_SESSION['prevpage'] = "media.php?id=".$_GET['id'];
    echo "<div id='bodyContent' class='body-content'>";
    //Get the media's information from the database
    if($query = mysqli_prepare(db_connect_id(), "SELECT username, title, type, path, upload_date, description,
        category, allow_comments FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $_GET['id']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $username, $title, $type, $filepath, $date, $description, $category, $allowcomments);
        $result = $result && mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);
    }
    else
    {
        $result = false;
    }

    $keywords = get_media_keywords($_GET['id']);

    //If the media was found, display it for the user, otherwise show a warning.
    if($result)
    {
        echo "<h3 class='media-title'>$title</h3><br>";
?>
        <div id='similarMediaContainer' class='similar-media-container'>
<?php
            //Give other media in same playlist or category
            if(isset($_REQUEST['playlistid']))
            {
                if($playlistCheckQuery = mysqli_prepare(db_connect_id(), "SELECT name FROM playlist WHERE playlist_id=?"))
                {
                    mysqli_stmt_bind_param($playlistCheckQuery, "i", $_REQUEST['playlistid']);
                    $playlistCheckResult = mysqli_stmt_execute($playlistCheckQuery);
                    mysqli_stmt_bind_result($playlistCheckQuery, $playlistName);
                    $playlistCheckResult = $playlistCheckResult && mysqli_stmt_fetch($playlistCheckQuery);
                    mysqli_stmt_close($playlistCheckQuery);
                    
                    //Get other videos from the same playlist
                    if($playlistCheckResult)
                    {
                        echo "<h5>More Media in '<a href='playlist.php?id={$_REQUEST['playlistid']}'>$playlistName</a>':</h5>";
                        if($similarQuery = mysqli_prepare(db_connect_id(), "SELECT media.title, media.type, media.mediaid, 
                            media.upload_date, media.username FROM playlist_media INNER JOIN media ON 
                            playlist_media.mediaid=media.mediaid WHERE playlist_media.playlist_id=? AND media.mediaid!=? 
                            ORDER BY media.upload_date DESC"))
                        {
                            mysqli_stmt_bind_param($similarQuery, "ii", $_REQUEST['playlistid'], $_GET['id']);
                            if(mysqli_stmt_execute($similarQuery))
                            {
                                mysqli_stmt_bind_result($similarQuery, $simTitle, $simType, $simID, $simDate, $simUser);
                                if(!mysqli_stmt_fetch($similarQuery))
                                {
                                    echo "<h6>No other media in same playlist.</h6>";
                                }
                                else
                                {    
                                    do
                                    {
                                        echo "<div class='similar-media-details-container'>";
                                        switch(substr($simType,0,5))
                                        {
                                            case "video":
                                            echo "🎬";
                                            break;
                                        case "audio":
                                            echo "🎼";
                                            break;
                                        case "image":
                                            echo "📸";
                                            break;
                                            default: echo substr($simType,0,5);
                                        }

                                        echo "<a href='media.php?id=$simID&playlistid={$_REQUEST['playlistid']}'>$simTitle</a><br>";
                                        echo "From: <a href='account.php?username=".urlencode($simUser)."'>$simUser</a><br>";
                                        echo "Uploaded: $simDate<br>";
                                        echo "</div>";
                                    } while(mysqli_stmt_fetch($similarQuery));
                                }
                            }
                            else
                                echo "<h6>No other media in same playlist.</h6>";
                            mysqli_stmt_close($similarQuery);
                        }
                        else
                        {
                            echo "<h6>No other media in same playlist.</h6>";
                        }

                    }
                    else
                    {
                        echo "<h6>Could not retrieve playlist info.</h6>";
                    }
                }
                else
                {
                    echo "<h6>Could not retrieve playlist info.</h6>";
                }

            }
            else
            {
                echo "<h5>More $category Media:</h5>";
                if($similarQuery = mysqli_prepare(db_connect_id(), "SELECT title, type, mediaid, upload_date,
                    username FROM media WHERE category=? AND mediaid != ? ORDER BY upload_date DESC LIMIT 10"))
                {
                    mysqli_stmt_bind_param($similarQuery, "si", $category, $_GET['id']);
                    if(mysqli_stmt_execute($similarQuery))
                    {
                        mysqli_stmt_bind_result($similarQuery, $simTitle, $simType, $simID, $simDate, $simUser);
                        if(!mysqli_stmt_fetch($similarQuery))
                        {
                            echo "<h6>No other media in same category.</h6>";
                        }
                        else
                        {    
                            do
                            {
                                echo "<div class='similar-media-details-container'>";
                                switch(substr($simType,0,5))
                                {
                                   
                                    case "video":
                                    echo "🎬";
                                    break;
                                case "audio":
                                    echo "🎼";
                                    break;
                                case "image":
                                    echo "📸";
                                    break;
                                    default: echo substr($mediatype,0,5);
                                }

                                echo "<a href='media.php?id=$simID'>$simTitle</a><br>";
                                echo "From: <a href='account.php?username=".urlencode($simUser)."'>$simUser</a><br>";
                                echo "Uploaded: $simDate<br>";
                                echo "</div>";
                            } while(mysqli_stmt_fetch($similarQuery));
                        }
                    }
                    else
                        echo "<h6>No media in same category</h6>";
                    mysqli_stmt_close($similarQuery);
                }
                else
                {
                    echo "<h6>No media in same category</h6>";
                }
            }
?>
        </div>
<?php
        echo "<div id='mediaContainer' class='media-container'>";

        if(substr($type,0,5)=="image") //view image
        {
            echo "<img class='media-item' src='".$filepath."'/>";
        }
        else if(substr($type,0,5)=="audio")
        {
            echo 	"<audio controls>
                        <source class='media-item' src='".$filepath."' type='".$type."'>
                    </audio>";
        }
        else if(substr($type,0,5)=="video")
        {	
            echo	"<video class='media-item' width='".'854'."' height='".'480'."' controls>
                        <source src='".$filepath."' type='".$type."'>
                    </video>";
        }
?>
        </div>

        <div id='mediaDetailsContainer' class='media-details-container'>
<?php
            if(isset($_SESSION['username']))
            {
                if($favQuery = mysqli_prepare(db_connect_id(), "SELECT username FROM favorited_media WHERE username=? AND mediaid=?"))
                {
                    mysqli_stmt_bind_param($favQuery, "si", $_SESSION['username'], $_GET['id']);
                    $favResult = mysqli_stmt_execute($favQuery);
                    mysqli_stmt_bind_result($favQuery, $favUser);
                    $favResult = $favResult && mysqli_stmt_fetch($favQuery);
                    mysqli_stmt_close($favQuery);
                }
                else
                {
                    $favResult = false;
                }

                if($favResult)
                    echo "<span id='favoriteMediaButton' title='Unfavorite Media' class='glyphicon glyphicon-star btn-favorite-media'></span>";
                else
                    echo "<span id='favoriteMediaButton' title='Favorite Media' class='glyphicon glyphicon-star-empty btn-favorite-media'></span>";
                    
            }
?>
            <a href='<?php echo $filepath;?>' target='_blank' id='downloadMediaBtn' class='btn btn-primary download-media-btn' download>
                Download
            </a>
            <div id='playlistDropdownContainer' class='playlist-dropdown-container'>
                <?php include 'playlistDropdown.php'; ?>
            </div>

            <p class='media-description-value'>
                <strong>User:</strong>
                <a href="account.php?username=<?php echo urlencode($username); ?>">
                    <?php echo $username; ?>
                </a>
            </p>
            <p class='media-description-value'>
                <strong>Date:</strong> <?php echo $date; ?>
            </p>
            <p class='media-description-value'>
                <strong>Description:</strong>
                <?php
                    if($description != NULL)
                        echo $description;
                    else
                        echo "No description.";
                ?>
            </p>
            <p class='media-description-value'>
                <strong>Category:</strong>
                <?php
                    if($category != NULL)
                        echo $category;
                    else
                        echo "No category.";
                ?>
            </p>
            <p class='media-description-value'>
                <strong>Keywords:</strong>
                <?php
                    if($keywords != NULL)
                        echo $keywords;
                    else
                        echo "No keywords.";
                ?>
            </p>
        </div>
        <br>
        <input type="hidden" id="mediaidJS" name="mediaidJS" value="<?php echo $_GET['id']; ?>">
        <?php
       
?>
        <h3 class='media-title'>Comments</h3>
        <br>
        <?php
        if(isset($_SESSION['username']))
        {
            ?>
            <div id="submitCommentContainer" class="submit-comment-container">
                <form id="makeCommentForm" class="submit-comment-form">
                    <h5>Write a comment:<h5>
                    <textarea rows="2" maxlength="750" id="commentText" name="commentText" class="form-control comment-text"></textarea>
                    <br>
                    <input type="hidden" id="mediaidField" name="mediaidField" value="<?php echo $_GET['id']; ?>">
                    <input type="submit" id="commentSubmit" name="commentSubmit" class="btn btn-primary btn-right-align" value="Submit">
                    <p id="makeCommentValidation" class="comment-validation"></p>
                </form>
            </div>
            <br>
            <?php
        
        
        ?>
        <?php
        echo "<div id='commentSection'>";
        include "comments.php"; 
        echo "</div><br>";
        }
        else
        {
            echo "<h3 class='media-title'>Comments disabled for this media item.</h3>";
        }
    }
    else
    {
?>
        <div class="alert alert-danger" style="text-align: center">
            <strong>Error:</strong> The selected media item could not be found.
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
