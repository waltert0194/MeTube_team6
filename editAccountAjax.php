<?php
    if(session_id() == '')
    {
        ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";

    //A PHP file to handle all ajax requests supplied by the edit account page
    if(isset($_REQUEST['action']))
    {
        switch($_REQUEST['action'])
        {
            case 0:
                //PHP code for updating profile information
                if(isset($_REQUEST['email']) && isset($_REQUEST['biography']) 
                && isset($_SESSION['username']))
                {
                    if(update_account_info($_SESSION['username'], $_REQUEST['biography'], $_REQUEST['email']))
                        echo "success";
                    else
                        echo "Failed to update profile.";
                }
                else if(!isset($_REQUEST['email']))
                {
                    echo "The email field is not set correctly.";
                }
                else if(!isset($_REQUEST['biography']))
                {
                    echo "The biography field is not set correctly";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to edit your profile.";
                }
                break;
            case 1:
                //PHP code for updating account password
                if(isset($_REQUEST['currentPassword']) && isset($_REQUEST['newPassword1']) 
                && isset($_SESSION['username']))
                {
                    switch(update_user_pass($_SESSION['username'], $_REQUEST['currentPassword'],
                        $_REQUEST['newPassword1']))
                    {
                        case 0:
                            echo "success";
                            break;
                        case 1:
                            echo "Your account is set incorrectly.";
                            break;
                        case 2:
                            echo "Incorrect current password entered.";
                            break;
                        default:
                            break;
                    }
                }
                else if(!isset($_REQUEST['currentPassword']))
                {
                    echo "The current password is not set correctly.";
                }
                else if(!isset($_REQUEST['newPassword1']))
                {
                    echo "The new password is not set correctly";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to change your password.";
                }
                break;
            case 2:
                //PHP code for registering an account
                if(isset($_REQUEST['username']) && isset($_REQUEST['password1']) 
                && isset($_REQUEST['email']))
                {
                    switch(add_account_to_db($_REQUEST['username'], $_REQUEST['password1'],
                                             $_REQUEST['email']))
                    {
                        case 0:
                            echo "Add account operation failed.";
                            break;
                        case 1:
                            //Log the user in if successful
                            $_SESSION['username'] = $_REQUEST['username'];
                            echo "success";
                            break;
                        case 2:
                            echo "Username already exists.";
                            break;
                        default:
                            echo "Add account operation failed.";
                            break;
                    }
                }
                else if(!isset($_REQUEST['username']))
                {
                    echo "The username field is not set correctly.";
                }
                else if(!isset($_REQUEST['password1']))
                {
                    echo "The password is not set correctly";
                }
                else if(!isset($_REQUEST['email']))
                {
                    echo "You email field is not set correctly.";
                }
                break;
            case 3: 
                //PHP code for checking if a username already exists
                if(isset($_REQUEST['username']))
                {
                    switch(user_exist_check($_REQUEST['username']))
                    {
                        case 0:
                            echo "success";
                            break;
                        case 1:
                            echo "That username already exists.";
                            break;
                        case 2:
                            echo "Error checking if username already exists.";
                            break;
                        case 3:
                            echo "Error checking if username already exists.";
                            break;
                        default:
                            echo "Error checking if username already exists.";
                            break;
                    }
                }
                else if(!isset($_REQUEST['username']))
                {
                    echo "The username field is not set correctly.";
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
