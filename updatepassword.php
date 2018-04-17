<?php
if(session_id() == '')
{
    ini_set('session.save_path', getcwd(). '/tmp');
    session_start();
}
include_once "function.php";

if(isset($_SESSION['username']) && user_exist_check($_SESSION['username']) == 1)
{
?>
    <h3>Update Password</h3>
    <form id="updatePasswordForm" method="post">
        <h4 style="margin-bottom:0px; margin-top: 20px;">Current Password</h4>

        <table>
            <tr>
                <td>
                    <input id="currentPassword" name="currentPassword" type="password" maxlength="100" class="form-control" style="width: 300px;">
                </td> 
                <td>
                    <h5 id="currentPasswordValidation" style="padding-left:10px; color: red"></h5>
                </td>
            </tr>
        </table>

        <h4 style="margin-bottom:0px; margin-top: 20px;">New Password</h4>
        <table>
            <tr>
                <td>
                    <input id="newPassword1" name="newPassword1" type="password" maxlength="100" class="form-control" style="width: 300px;">
                </td> 
                <td>
                    <h5 id="newPassword1Validation" style="padding-left:10px; color: red"></h5>
                </td>
            </tr>
        </table>
        
        <h4 style="margin-bottom:0px; margin-top: 20px;">Confirm New Password</h4>
         <table>
            <tr>
                <td>
                    <input id="newPassword2" name="newPassword2" type="password" class="form-control" maxlength="100" style="width: 300px;">
                </td> 
                <td>
                    <h5 id="newPassword2Validation" style="padding-left:10px; color: red"></h5>
                </td>
            </tr>
        </table>
        
        <input style="margin-top: 20px;" value="Submit" name="submit" type="submit" class="btn btn-primary"/>
            
        <h5 id="updatePasswordValidation" style="color: blue"></h5>
    </form>
<?php
}
else
{
    echo "<h3>Unable to retrieve account.</h3>";
}
?>
