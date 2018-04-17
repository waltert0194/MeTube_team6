<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
	include_once "function.php";
    
if(isset($_SESSION['username']) && isset($_REQUEST['id']))
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT playlist_id, name FROM playlist WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $_SESSION['username']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $playlist_id, $name);
        mysqli_stmt_store_result($query);
    }
    else
    {
        $result = false;
    }
    echo "<div id='playlistDropdown' class='dropdown'>";
    echo "<button class='btn btn-primary dropdown-toggle playlist-dropdown-toggle' type='button' data-toggle='dropdown'>";
    echo "Playlists ";
    echo "<span class='caret'></span>";
    echo "</button>";
    echo "<ul class='dropdown-menu' id='playlistDropdownMenu'>";
    if($result && mysqli_stmt_fetch($query))
    {
        do
        {
            if(get_playlist_contains_media($playlist_id, $_REQUEST['id']))
                $inPlaylist = true;
            else
                $inPlaylist = false;
?>
    <li class='playlist-dropdown-button <?php if($inPlaylist) echo "remove"; else echo "add";?>'>
                <input type="hidden" name="playlistidField" value="<?php echo $playlist_id; ?>">
<?php 
                if($inPlaylist)
                    echo 'Remove from "'.$name.'"';
                else
                    echo 'Add to "'.$name.'"';
?>
            </li>
            <?php
        }while(mysqli_stmt_fetch($query));

        mysqli_stmt_close($query);
    }
    else if($result)
    {
        echo "<li class='playlist-dropdown-text'>No playlists found.</li>";
    }
    echo "</ul></div>";
    
}
?>
