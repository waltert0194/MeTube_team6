<?php
if(session_id() == '')
{
    ini_set('session.save_path', getcwd(). '/tmp');
    session_start();
}
include_once "function.php";
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Results</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>

<body>
<?php
include "header.php";

$page = 1;
if(isset($_REQUEST['page']) && $_REQUEST['page']>=1)
{
    $page=$_REQUEST['page'];
}
$prevPage = $page - 1;
$nextPage = $page + 1;

echo "<div class='search-body-content'>";
if(isset($_REQUEST['query']))
{
    $keywords = urldecode($_REQUEST['query']);
    $keywordsArray = array_from_keywords($keywords); 
    echo "<h3 style='float:left'>Search results for: $keywords</h3>";
    
    foreach($keywordsArray as $media_key)
    {
        $thisKey = "%$media_key%";
        if($query = mysqli_prepare(db_connect_id(), "SELECT mediaid FROM media_keyword
            WHERE media_key LIKE ?"))
        {
            mysqli_stmt_bind_param($query, "s", $thisKey);
            mysqli_stmt_execute($query);
            mysqli_stmt_bind_result($query, $mediaid);

            while(mysqli_stmt_fetch($query))
            {
                if(isset($dictionary["$mediaid"])) $dictionary["$mediaid"]++;
                else $dictionary["$mediaid"] = 1;
            }
        }
    }

    foreach($keywordsArray as $media_key)
    {
        $thisKey = "%$media_key%";
        if($query = mysqli_prepare(db_connect_id(), "SELECT mediaid FROM media
            WHERE title LIKE ? OR username LIKE ?"))
        {
            mysqli_stmt_bind_param($query, "ss", $thisKey, $thisKey);
            mysqli_stmt_execute($query);
            mysqli_stmt_bind_result($query, $mediaid);

            while(mysqli_stmt_fetch($query))
            {
                if(isset($dictionary["$mediaid"])) $dictionary["$mediaid"]++;
                else $dictionary["$mediaid"] = 1;
            }
        }
    }

    if(isset($dictionary) && count($dictionary) - ($prevPage * 40) > 40)
    echo "<a href='search.php?query={$_REQUEST['query']}&page=$nextPage'><div class='btn btn-primary' style='float:right; margin:5px'>Next</div></a>";
    echo "<h5 style='float: right'>Page: $page</h5>";
    if($page > 1)
    {
        echo "<a href='search.php?query={$_REQUEST['query']}&page=$prevPage'><div class='btn btn-primary' style='float:right; margin:5px'>Prev</div></a>";
    }


    echo "<div class='browse-media-items-container'>";
    if(isset($dictionary) && count($dictionary) > 0)
    {
        array_reverse($dictionary, true);
        $index = 0;
        foreach($dictionary as $currid => $count)
        {
            if(($index >= (40 * $prevPage)) && ($index < (40 * $page)))
            {
                if($query = mysqli_prepare(db_connect_id(), "SELECT title, type, mediaid, upload_date,
                    category, username FROM media WHERE mediaid=? ORDER BY upload_date"))
                {
                    mysqli_stmt_bind_param($query, "s", $currid);
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
                                    echo "<span class=\"glyphicon glyphicon-film\"></span> ";
                                    break;
                                case "audio":
                                    echo "<span class=\"glyphicon glyphicon-music\"></span> ";
                                    break;
                                case "image":
                                    echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
                                    break;
                                default: echo substr($mediatype,0,5);
                            }
                            echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
                            echo "Uploader: <a href='account.php?username=".urlencode($mediauser)."'>$mediauser</a><br>";
                            echo "Uploaded: ".$mediadate."<br/>";
                            echo "Category: ".$mediacat."<br/>";
                            echo "</div>";
                        } while(mysqli_stmt_fetch($query));
                    }
                    mysqli_stmt_close($query);
                }
            }
            $index++;
        }
    }
    else
    {
        echo "<h4>No search results were found.<h4>";
    }
    echo "</div>";
}
else
{
?>
        <div class="alert alert-danger" style="text-align: center">
            <strong>Error:</strong> No keywords were specified for search.
        </div>
<?php
}
echo "</div>";
?>
</body>
</html>
