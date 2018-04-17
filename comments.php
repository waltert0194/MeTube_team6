<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
	include_once "function.php";
    
    
    
if($query = mysqli_prepare(db_connect_id(), "SELECT com_id, username, com_date, message FROM comment WHERE mediaid=? ORDER BY com_date DESC"))
{
    mysqli_stmt_bind_param($query, "i", $_REQUEST['id']);
    $result = mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $com_id, $commenter, $com_date, $message);
}
else
{
    $result = false;
}

if($result && mysqli_stmt_fetch($query))
{
    echo "<div id='currentCommentsContainer' class='current-comments-container'><br>";
    
    do
    {
        ?>
        <div class='existing-comment-container'>
            <input type="hidden" name="commentidField" value="<?php echo $com_id; ?>">
            <?php if(isset($_SESSION['username']) && $commenter === $_SESSION['username'])
            {
                echo "<span title='Delete Comment' class='glyphicon glyphicon-remove btn-delete-comment'></span>";
                echo "<span title='Edit Comment' class='glyphicon glyphicon-pencil btn-edit-comment'></span>";
            }
            ?>

            <p style='padding: 4px 5px 0px 5px'>
                <a href="account.php?username=<?php echo urlencode($commenter); ?>"><?php
                echo $commenter; ?></a> at <?php echo $com_date; ?>:
            </p>

            
            <p class='comment-message'><?php echo $message; ?></p>
        </div><br>
        <?php
    }while(mysqli_stmt_fetch($query));

    mysqli_stmt_close($query);
    echo "</div>";
}
else
{
    if($query) mysqli_stmt_close($query);
    echo "<h4>No comments.</h4>";
}
?>
