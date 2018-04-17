<?php
if(session_id() == '')
{
    ini_set('session.save_path', getcwd(). '/tmp');
    session_start();
}
include_once "function.php";

if(isset($_REQUEST['category']))
{
    echo "<h3>Recently Added {$_REQUEST['category']} Media</h3>";
    echo "<div class='browse-media-items-container'>";
    if($query = mysqli_prepare(db_connect_id(), "SELECT title, type, mediaid, upload_date,
        username FROM media WHERE category=? ORDER BY upload_date DESC LIMIT 40"))
    {
		mysqli_stmt_bind_param($query, "s", $_REQUEST['category']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $mediatitle, $mediatype, $mediaid, $mediadate, $mediauser);
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
                echo "</div>";
            } while(mysqli_stmt_fetch($query));
        }
        else
        {
            echo "<h4>No recent uploads.<h4>";
        }
        mysqli_stmt_close($query);
    }
    else
    {
        echo "<h4>No recent uploads.<h4>";
    }
    echo "</div>";
}
else
{
    echo "<h3>No category specified.<h3>";
}
?>
