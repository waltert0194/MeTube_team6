<?php
if(session_id() == '')
{
    ini_set('session.save_path', getcwd(). '/tmp');
    session_start();
}
include_once "function.php";

if(isset($_SESSION['username']) && user_exist_check($_SESSION['username']) == 1)
{
?>
    <h3>Your Subscriptions</h3>
    <div class="browse-media-items-container">
<?php
    if($query = mysqli_prepare(db_connect_id(), "SELECT title, type, mediaid, upload_date, category,
        username FROM subscription INNER JOIN media ON subscription.subscribee_username = media.username
        WHERE subscriber_username=? ORDER BY upload_date DESC LIMIT 10"))
    {
        mysqli_stmt_bind_param($query, "s", $_SESSION['username']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $mediatitle, $mediatype, $mediaid, $mediadate, $mediacat, $mediauser);
        $result = $result && mysqli_stmt_fetch($query);
        if($result)
        {
            do
            {
                echo "<div class=\"browse-media-details-container\">";
                echo "<input type='hidden' name='mediaidField' value='$mediaid'>";

                switch(substr($mediatype,0,5))
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
                echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
                echo "User: <a href='account.php?username=".urlencode($mediauser)."'>$mediauser</a><br>";
                echo "Date: ".$mediadate."<br/>";
                echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
                echo "</div>";
            } while(mysqli_stmt_fetch($query));
        }
        else
        {
            echo "<h4>No recent subscription activity found.<h4>";
        }
        mysqli_stmt_close($query);
    }
    else
    {
        echo "<h4>No recent subscription activity found.<h4>";
    }
    echo "</div>";
}
?>
<h3>Recently Uploaded</h3>
<div class="browse-media-items-container">
<?php
if($query = mysqli_prepare(db_connect_id(), "SELECT title, type, mediaid, upload_date, category,
    username FROM media ORDER BY upload_date DESC LIMIT 20"))
{
    $result = mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $mediatitle, $mediatype, $mediaid, $mediadate, $mediacat, $mediauser);
    $result = $result && mysqli_stmt_fetch($query);
    if($result)
    {
        do
        {
            echo "<div class=\"browse-media-details-container\">";
            echo "<input type='hidden' name='mediaidField' value='$mediaid'>";

            switch(substr($mediatype,0,5))
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
            echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
            echo "User: <a href='account.php?username=".urlencode($mediauser)."'>$mediauser</a><br>";
            echo "Date: ".$mediadate."<br/>";
            echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
            echo "</div>";
        } while(mysqli_stmt_fetch($query));
    }
    else
    {
        echo "<h4>No media recently uploaded.<h4>";
    }
    mysqli_stmt_close($query);
}
else
{
    echo "<h4>No media recently uploaded.<h4>";
}
echo "</div>";
?>
<h3>Recent Playlists</h3>
<div class="browse-media-items-container">
<?php
if($query = mysqli_prepare(db_connect_id(), "SELECT name, playlist_id, creation_date, username
    FROM playlist ORDER BY creation_date DESC LIMIT 10"))
{
    $result = mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $listname, $listid, $listdate, $listuser);
    $result = $result && mysqli_stmt_fetch($query);
    if($result)
    {
        do
        {
            echo "<div class=\"browse-media-details-container\">";
            echo "<input type='hidden' name='playlistidField' value='$listid'>";

            echo "<a href=\"playlist.php?id=".$listid."\">".$listname."</a><br/>";
            echo "Creator: <a href='account.php?username=".urlencode($listuser)."'>$listuser</a><br>";
            echo "Created: ".$listdate."<br/>";
            echo "</div>";
        } while(mysqli_stmt_fetch($query));
    }
    else
    {
        echo "<h4>No recent playlists.<h4>";
    }
    mysqli_stmt_close($query);
}
else
{
    echo "<h4>No recent playlists.<h4>";
}
echo "</div>";
?>
