<?php
    if(session_id() == '')
    {
        ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";

    //A PHP file to handle all ajax requests supplied by the playlist viewing and modification pages
    if(isset($_REQUEST['action']))
    {
        switch($_REQUEST['action'])
        {
            case 0:
                //PHP code for handling AJAX request to delete a playlist
                if(isset($_REQUEST['playlistid']))
                {
                    if(remove_playlist($_REQUEST['playlistid']))
                        echo "success";
                    else
                        echo "Failed to delete playlist.";
                }
                else if(!isset($_REQUEST['playlistid']))
                {
                    echo "The playlistid is not set correctly.";
                }
                break;
            case 1:
                //PHP code for handling AJAX request to add a playlist
                if(isset($_REQUEST['playlistName']) && isset($_SESSION['username']))
                {
                    if(create_playlist($_SESSION['username'], $_REQUEST['playlistName']))
                        echo "success";
                    else
                        echo "Failed to create playlist.";
                }
                else if(!isset($_REQUEST['playlistName']))
                {
                    echo "The playlist name field is not set correctly.";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to create a playlist.";
                }
                break;
            default:
                echo "Invalid AJAX action supplied.";
                break;
        }
    }
    else
    {
        echo "The action was not set correctly.";
    }
?>
