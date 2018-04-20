<?php
    if(session_id() == '')
    {
        ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";

   
    if(isset($_REQUEST['action']))
    {
        switch($_REQUEST['action'])
        {
            case 0:
              n
                if(isset($_REQUEST['playlistid']) && isset($_REQUEST['name']) &&
                    isset($_REQUEST['description']) && isset($_REQUEST['keywords']))
                {
                    $keywordArray = array_from_keywords($_REQUEST['keywords']); 
                    if(update_playlist_metadata($_REQUEST['playlistid'], $_REQUEST['name'],
                                                $_REQUEST['description'], $keywordArray))
                        echo "success";
                    else
                        echo "Failed playlist update.";
                }
                else if(!isset($_REQUEST['playlistid']))
                {
                    echo "playlistid field error.";
                }
                else if(!isset($_REQUEST['name']))
                {
                    echo "name field error";
                }
                else if(!isset($_REQUEST['description']))
                {
                    echo "description field error.";
                }
                else if(!isset($_REQUEST['keywords']))
                {
                    echo "keywords field error";
                }
                break;
            default:
                echo "Invalid AJAX not connected.";
                break;
        }
    }
    else
    {
        echo "The action was not set correctly.";
    }
?>
