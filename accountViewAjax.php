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
		case 0://subscribe
			if(isset($_REQUEST['username']) && isset($_SESSION['username']) && add_subscription($_SESSION['username'], $_REQUEST['username']))
				echo "success";
			else
				echo "failed";
			break;
		
		case 1://unsubscribe
			if(isset($_REQUEST['username']) && isset($_SESSION['username']) && remove_subscription($_SESSION['username'], $_REQUEST['username']))
				echo "success";
			else 
				echo "failed";

			break;

		case 2://send message
			if(isset($_REQUEST['message']) && isset($_SESSION['username']) && isset($_REQUEST['username']))
			{
				$message = trim($_REQUEST['message']);
				
				
				if(strlen($message) == 0)
					echo "empty";
				elseif(strlen($message) < 10)
					echo "short";
				elseif(strlen($message) > 1000)
					echo "long";
				elseif(add_message($_SESSION['username'], $message, array($_REQUEST['username'])))
					echo "success";
				else
					echo "failed";
				 
			}
			break;
        case 3://delete media item
            if(isset($_REQUEST['mediaid']))
            {
                if(remove_media($_REQUEST['mediaid']))
                {
                    echo "success";
                }
                else
                {
                    echo "Error deleting media.";
                }
            }
            else
            {
                echo "The mediaid field is not properly set.";
            }
            break;
		default:
			echo "default";
			break;
	}
}
else
	echo "The action was not set correctly";

?>
