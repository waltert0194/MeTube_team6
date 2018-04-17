<?php
    if(session_id() == '')
    {
        ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";

    //A PHP file to handle all ajax requests supplied by the edit playlist page
    if(isset($_REQUEST['action']))
    {
        switch($_REQUEST['action'])
        {
            case 0:
                //PHP code for updating profile information
                if(isset($_REQUEST['playlistid']) && isset($_REQUEST['name']) &&
                    isset($_REQUEST['description']) && isset($_REQUEST['keywords']))
                {
                    $keywordArray = array_from_keywords($_REQUEST['keywords']); 
                    if(update_playlist_metadata($_REQUEST['playlistid'], $_REQUEST['name'],
                                                $_REQUEST['description'], $keywordArray))
                        echo "success";
                    else
                        echo "Failed to update playlist metadata.";
                }
                else if(!isset($_REQUEST['playlistid']))
                {
                    echo "The playlistid field is not set correctly.";
                }
                else if(!isset($_REQUEST['name']))
                {
                    echo "The name field is not set correctly";
                }
                else if(!isset($_REQUEST['description']))
                {
                    echo "The description field is not set correctly.";
                }
                else if(!isset($_REQUEST['keywords']))
                {
                    echo "The keywords field is not set correctly";
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
