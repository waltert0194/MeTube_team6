<?php
if(session_id() == '')
{
    ini_set('session.save_path', getcwd(). '/tmp');
    session_start();
}
include_once "function.php";

if(isset($_SESSION['username']) && user_exist_check($_SESSION['username']) == 1)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT email, biography FROM account WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $_SESSION['username']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $email, $biography);
        $result = $result && mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);
    }
    else
    {
        $result = false;
    }

    if($result)
    {
?>
        <h3>Edit User Info</h3>
        <form id="updateProfileForm" method="post">
            <h4 style="margin-bottom:0px; margin-top: 20px;">Email Address</h4>
            <table>
                <tr>
                    <td>
                        <input id="updateEmail" name="email" type="text" class="form-control" style="width: 300px;" maxlength="255" value="<?php echo $email; ?>">
                    </td> 
                    <td>
                        <h5 id="updateEmailValidation" style="padding-left:10px; color: red"></h5>
                    </td>
                </tr>
            </table>

            <h4 style="margin-bottom:0px; margin-top: 20px;">Bio</h4>
            <table>
                <tr>
                    <td>
                        <textarea id="updateSummary" name="biography" class="form-control" rows="3" style="width: 300px; resize: none" maxlength="750"><?php echo $biography; ?></textarea>
                    </td> 
                    <td>
                        <h5 id="updateSummaryValidation" style="padding-left:10px; color: red"></h5>
                    </td>
                </tr>
            </table>
            
            <input style="margin-top: 20px;" value="Submit" name="submit" type="submit" class="btn btn-primary"/>
            <h5 id="updateProfileValidation" style="color: blue"></h5>
        </form>
<?php
    }
    else
    {
        echo "<h3>Unable to retrieve account.</h3>";
    }
}
else
{
    echo "<h3>Unable to retrieve account.</h3>";
}
?>
