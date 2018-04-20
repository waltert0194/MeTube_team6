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
    <form id="updatePasswordinfo" method="post">
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
                    <input id="passwordMatch1" name="passwordMatch1" type="password" maxlength="100" class="form-control" style="width: 300px;">
                </td> 
                <td>
                    <h5 id="validatePasswordMatch1" style="padding-left:10px; color: red"></h5>
                </td>
            </tr>
        </table>
        
        <h4 style="margin-bottom:0px; margin-top: 20px;">Confirm New Password</h4>
         <table>
            <tr>
                <td>
                    <input id="passwordMatch2" name="passwordMatch2" type="password" class="form-control" maxlength="100" style="width: 300px;">
                </td> 
                <td>
                    <h5 id="validatePasswordMatch2" style="padding-left:10px; color: red"></h5>
                </td>
            </tr>
        </table>
        
        <input style="margin-top: 20px;" value="Submit" name="submit" type="submit" class="btn btn-primary"/>
            
        <h5 id="validateUpdatedPassword" style="color: blue"></h5>
    </form>
<?php
}
else
{
    echo "<h3>Unable to fetch account.</h3>";
}
?>
