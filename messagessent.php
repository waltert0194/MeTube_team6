<?php
if(session_id() == '')
{
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
}
include_once "function.php";
?>

<h3 style="margin-left: 30px; float: left">Sent Messages</h3><br/><br/><br/>
<?php
if(isset($_SESSION['username']) && $query = mysqli_prepare(db_connect_id(), "SELECT message_id, send_date, message_contents FROM message WHERE sender_username = ? ORDER BY send_date DESC"))
{
    mysqli_stmt_bind_param($query, "s", $_SESSION['username']);
    $result = mysqli_stmt_execute($query);
    $query->store_result();
    $rows = 0;
    mysqli_stmt_bind_result($query, $messageid, $messagedate, $message);
    while($result = mysqli_stmt_fetch($query))
    {
        $rows++;
    ?>
        <div class="inbox-message-pane">
        <?php
        echo "<div style='font-size: 16px'>Sent to: ";
        if($query1 = mysqli_prepare(db_connect_id(), "SELECT recipient_username FROM message_recipient WHERE message_id = ?"))
        {
            mysqli_stmt_bind_param($query1, "i", $messageid);
            $result = mysqli_stmt_execute($query1);
            $query1->store_result();
            mysqli_stmt_bind_result($query1, $recipient);
            while($result = mysqli_stmt_fetch($query1))
            {
                echo "<a href=account.php?username=".$recipient.">".$recipient."</a>; ";
            }
            mysqli_stmt_close($query1);
        }
        echo "<br>on: ".$messagedate."</div><br/>";
        echo "<div class='inbox-message'>".$message."</div>";
        ?>	
        </div>

    <?php
    }
    mysqli_stmt_close($query);
    if($rows == 0)
        echo "<h3 style='margin-left: 30px'>No messages.</h3>";
}

