<?php
    if(session_id() == '')
    {
        ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";

    //A PHP file to handle all ajax requests supplied by the media viewing page
    if(isset($_REQUEST['action']))
    {
        switch($_REQUEST['action'])
        {
            case 0:
                //PHP code for handling AJAX request to submit comments
                if(isset($_REQUEST['commentText']) && isset($_REQUEST['mediaidField']) 
                && isset($_SESSION['username']))
                {
                    if(add_comment($_SESSION['username'], $_REQUEST['mediaidField'], $_REQUEST['commentText']))
                        echo "success";
                    else
                        echo "Failed to submit comment.";
                }
                else if(!isset($_REQUEST['commentText']))
                {
                    echo "The comment text is not set correctly.";
                }
                else if(!isset($_REQUEST['mediaidField']))
                {
                    echo "The media id is not set correctly.";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to submit a comment.";
                }
                break;
            case 1:
                //PHP code for handling AJAX request to delete comments
                if(isset($_REQUEST['commentid']))
                {
                    if(remove_comment($_REQUEST['commentid']))
                        echo "success";
                    else
                        echo "Failed to delete comment.";
                }
                else if(!isset($_REQUEST['commentid']))
                {
                    echo "The comment id is not set correctly.";
                }
                break;
            case 2:
                //PHP code to handle AJAX requests for the edit comment form
                if(isset($_REQUEST['commentid']))
                {
                    if($query = mysqli_prepare(db_connect_id(), "SELECT com_id, message FROM comment WHERE com_id=?"))
                    {
                        mysqli_stmt_bind_param($query, "i", $_REQUEST['commentid']);
                        $result = mysqli_stmt_execute($query);
                        mysqli_stmt_bind_result($query, $com_id, $message);
                    }
                    else
                    {
                        $result = false;
                    }

                    if($result && mysqli_stmt_fetch($query))
                    {
                        ?>
                        <form class="edit-comment-form">
                            <h5>Edit your comment:<h5>
                            <textarea rows="2" maxlength="750" name="commentText" class="form-control comment-text"><?php echo $message; ?></textarea>
                            <br>
                            <input type="hidden" name="commentidField" value="<?php echo $com_id; ?>">
                            <input type="submit" name="commentEditSubmit" class="btn btn-primary btn-right-align btn-comment-edit-submit" value="Submit">
                            <input type="button" name="commentEditCancel" class="btn btn-primary btn-right-align editCancel-comment" value="Cancel">
                            <p class="comment-validation"></p>
                        </form>
                        <?php

                        mysqli_stmt_close($query);
                    }
                    else
                    {
                        if($query) mysqli_stmt_close($query);
                        ?>      
                        <h4>Error load comment edit window.</h4>      
                        <?php
                    }
                }
                else
                {
                    ?>      
                    <h4>Error load comment edit window.</h4>      
                    <?php
                }
                break;
            case 3:
                //PHP code for handling AJAX request to edit comment
                if(isset($_REQUEST['commentidField']) && isset($_REQUEST['commentText']))
                {
                    if(update_comment($_REQUEST['commentidField'], $_REQUEST['commentText']))
                        echo "success";
                    else
                        echo "Failed to update comment.";
                }
                else if(!isset($_REQUEST['commentidField']))
                {
                    echo "The comment id is not set correctly.";
                }
                else if(!isset($_REQUEST['commentText']))
                {
                    echo "The comment text is not set correctly.";
                }
                break;
            case 4:
                //PHP code for handling AJAX request to unfavorite media
                if(isset($_REQUEST['mediaid']) && isset($_SESSION['username']))
                {
                    if(remove_favorited_media($_SESSION['username'], $_REQUEST['mediaid']))
                        echo "success";
                    else
                        echo "Failed to unfavorite media.";
                }
                else if(!isset($_REQUEST['mediaid']))
                {
                    echo "The media id is not set correctly.";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to unfavorite a media item.";
                }
                break;
            case 5:
                //PHP code for handling AJAX request to favorite media
                if(isset($_REQUEST['mediaid']) && isset($_SESSION['username']))
                {
                    if(add_favorited_media($_SESSION['username'], $_REQUEST['mediaid']))
                        echo "success";
                    else
                        echo "Failed to favorite media.";
                }
                else if(!isset($_REQUEST['mediaid']))
                {
                    echo "The media id is not set correctly.";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to favorite a media item.";
                }
                break;
            case 6:
                //PHP code for handling AJAX request to add media to playlist
                if(isset($_REQUEST['mediaid']) && isset($_REQUEST['playlistid']))
                {
                    if(add_playlist_media($_REQUEST['playlistid'], $_REQUEST['mediaid']))
                        echo "success";
                    else
                        echo "Failed to add media to playlist.";
                }
                else if(!isset($_REQUEST['mediaid']))
                {
                    echo "The media id is not set correctly.";
                }
                else if(!isset($_REQUEST['playlistid']))
                {
                    echo "The playlist id is not set correctly.";
                }
                break;
            case 7:
                //PHP code for handling AJAX request to remove media from a playlist
                if(isset($_REQUEST['mediaid']) && isset($_REQUEST['playlistid']))
                {
                    if(remove_playlist_media($_REQUEST['playlistid'], $_REQUEST['mediaid']))
                        echo "success";
                    else
                        echo "Failed to remove media from playlist.";
                }
                else if(!isset($_REQUEST['mediaid']))
                {
                    echo "The media id is not set correctly.";
                }
                else if(!isset($_REQUEST['playlistid']))
                {
                    echo "The playlist id is not set correctly.";
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
