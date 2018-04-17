<?php
if(session_id() == '')
{
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
}
include_once "function.php";

if(isset($_SESSION['username']) && isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 0://the send button was clicked
			$sendliststring = $_REQUEST['recipients'];
			$message = trim($_REQUEST['message']);
			
			$sendlist = array_from_keywords($sendliststring);
			$allexist = TRUE;

			$sendnumber = count($sendlist);

			foreach($sendlist as $recipient)
			{
				if(user_exist_check($recipient) != 1)
				{
					$allexist = FALSE;
					$nonexistent[] = $recipient;
				}
			}

			if($allexist && $sendnumber > 0)
			{
				if(strlen($message) == 0)
					echo "empty";
				elseif(strlen($message) < 10)
					echo "short";
				elseif(strlen($message) > 1000)
					echo "long";
				elseif(add_message($_SESSION['username'], $message, $sendlist))
					echo "success";
				else
					echo "failed";
			}
			elseif($sendnumber == 0)
			{
				echo "nousers";
			}
			else
			{
				$errormessage = "The following users do not exist:\n";
				foreach($nonexistent as $notaperson)
				{
					$errormessage .= $notaperson."\n";
				}
				echo $errormessage;
			}

			break;
		default:
			break;
	}

}
else
	echo "The action was not set correctly";
?>
