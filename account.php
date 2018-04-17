<?php
if(session_id() == '')
{
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
}
include_once "function.php";

if(isset($_GET['username'])) $_SESSION['prevpage'] = "account.php?username=".$_GET['username'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MeTube | Account</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/account_page.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>

<body>
<?php
    include 'header.php';

if(isset($_GET['username']))
{
    $passedUsername = $_GET['username'];
	$decodedUsername = urldecode($passedUsername);
	if($query = mysqli_prepare(db_connect_id(), "SELECT username FROM account WHERE username = ?"))
	{
		mysqli_stmt_bind_param($query, "s", $decodedUsername);
		$result = mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $casecorrectedusername);
		$result = mysqli_stmt_fetch($query);
		mysqli_stmt_close($query);
	}
	$decodedUsername = $casecorrectedusername;
    if(user_exist_check($decodedUsername) != 1)
    {
        ?>
        <div class="alert alert-danger" style="text-align: center; margin-bottom: 5px">
           <strong>That account doesn't exist.</strong>
        </div>
        <?php
    }
    else
    {
		echo "<div style=\"margin-left: 15px\">";
        
        ?>
        <div class="container-fluid">
			<div class="row">
				<div class="col-sm-3" style="height: 90.4vh; overflow-y: auto">
					<br/>
					<?php
					echo "<input type=\"hidden\" id=\"username\" value=\"".$decodedUsername."\">";
					if(isset($_SESSION['username']))
						echo "<input type=\"hidden\" id=\"viewer\" value=\"".$_SESSION['username']."\">";

					echo "<h3 id=\"userheader\" class=\"media-title\">";
					
					echo $decodedUsername."'s Profile";
					
					$issubbed = 0;//not subbed
					if($query = mysqli_prepare(db_connect_id(), "SELECT * FROM subscription WHERE subscriber_username=? AND subscribee_username=?"))
					{
						mysqli_stmt_bind_param($query, "ss", $_SESSION['username'], $decodedUsername);
						$result = mysqli_stmt_execute($query);
						$result = mysqli_stmt_fetch($query);
						mysqli_stmt_close($query);
					}
					if($result)
						$issubbed = 1;//subbed
					elseif(!isset($_SESSION['username']))
						$issubbed = 2;//login
					elseif($_SESSION['username'] == $decodedUsername)
						$issubbed = 3;//edit account

					echo "<br/><br/><br/></h3>";
					echo "<button type=\"button\" id=\"editsub\" class=\"btn btn-primary\" value=".$issubbed.">";
					if(isset($_SESSION['username']))
					{
						if($decodedUsername == $_SESSION['username'])
						{
							echo "Edit Profile";
						}
						else
						{
							if(!$issubbed)
								echo "Subscribe";
							else
								echo "Unsubscribe";
						}
					}
					else
					{
						echo "Login to subscribe";
					}

					echo "</button>&nbsp;";
					if(isset($_SESSION['username']) && $decodedUsername == $_SESSION['username'])
						echo "<button type=\"button\" id=\"viewmessages\" class=\"btn btn-primary\" onclick=\"window.location.href='./messages.php'\">Messages</button>";
					echo"<br/><br/>";
					?>
					<h4>
						About me:
					</h4>
					<div style="font-size: 15px">
						<?php
						$email = "";
						$biography = "";
						if($query = mysqli_prepare(db_connect_id(), "SELECT email, biography FROM account WHERE username=?"))
						{
							mysqli_stmt_bind_param($query, "s", $decodedUsername);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $email, $biography);
							$result = $result && mysqli_stmt_fetch($query);
							mysqli_stmt_close($query);
						}
						echo "Email: ";
						echo $email;
						echo "<br/>";
						echo "biography:";
						echo "<br/>";
						echo "<div class=\"account-details-container\">";
						echo "<p style=\"margin: 10px 10px 10px 10px\">";
						if(strlen($biography) > 0)
							echo $biography;
						else
							echo "No biography given";
						echo "</p>";
						echo "</div><br/><br/>";

						if(isset($_SESSION['username']) && $_SESSION['username'] != $decodedUsername)
						{
							echo "Send " . $decodedUsername . " a message:<br/>";
							?>
                            <textarea id="messagebox" rows="4" maxlength="1000" class="form-control commment-text" style="resize: none" placeholder="Type your message here."></textarea>
							<br/>
							<button type="button" class="btn btn-primary" id="messagesend">Send</button>
							<br/><br/>
							<?php
						}
						?>
						<div id="messageerror" style="color: red"></div>
						<div id="messagesuccess" style="color: blue"></div>
						<br/>
						<?php	
						echo "<h4>My subscriptions:</h4><br/>";
						$subbeduser = "";
						if($query = mysqli_prepare(db_connect_id(), "SELECT subscribee_username FROM subscription WHERE subscriber_username=?"))
						{
							mysqli_stmt_bind_param($query, "s", $decodedUsername);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $subbeduser);
							while(mysqli_stmt_fetch($query))
							{
								echo "<a href=\"account.php?username=".$subbeduser."\">".$subbeduser."</a><br/>";
							}
							mysqli_stmt_close($query);
						}
						?>
					</div>
				</div>
                <div class="col-sm-9">
                    <div class="row" style="height: 28vh; overflow-y: auto; margin-bottom: 10px">
						<h4>Uploads
						<?php
						if($_SESSION['username'] == $decodedUsername)
							echo "<a href=\"media_upload.php\" class=\"btn btn-primary\" id=\"newupload\">New Upload</a></h4>";
						else
							echo "</h4>";

						if($query = mysqli_prepare(db_connect_id(), "SELECT title, type, mediaid, upload_date, category FROM media WHERE username=? ORDER BY upload_date DESC"))
						{
							mysqli_stmt_bind_param($query, "s", $decodedUsername);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $mediatitle, $mediatype, $mediaid, $mediadate, $mediacat);
							while(mysqli_stmt_fetch($query))
							{
								echo "<div class=\"account-media-details-container\">";
                                echo "<input type='hidden' name='mediaidField' value='$mediaid'>";


                                if(isset($_SESSION['username']) && $_SESSION['username'] === $decodedUsername)
                                {
                                    echo "<span title='Delete Media' class='glyphicon glyphicon-remove btn-delete-media'></span>";
                                    echo "<a href='editmedia.php?id=$mediaid'><span title='Edit Media Metadata' class='glyphicon glyphicon-pencil btn-edit-media-metadata'></span></a>";
                                }

								switch(substr($mediatype,0,5))
								{
									case "video":
										echo "<span class=\"glyphicon glyphicon-film\"></span> ";
										break;
									case "audio":
										echo "<span class=\"glyphicon glyphicon-music\"></span> ";
										break;
									case "image":
										echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
										break;
									default: echo substr($mediatype,0,5);
								}
								echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
								echo "Uploaded: ".$mediadate."<br/>";
								echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
								echo "</div>";
							}
							mysqli_stmt_close($query);
						}

						?>
                    </div>
                    <div class="row" style="height: 30.3vh; overflow-y: auto; margin-bottom: 10px; border-top: solid grey">
						<h4>Playlists
						<?php
                        if($_SESSION['username'] == $decodedUsername)
                        {
                            echo "<button data-toggle=\"modal\" data-target=\"#addPlaylistPopup\" class=\"btn btn-primary\" id=\"newplaylist\">Add Playlist</a></h4>";
?>
                            <div class='modal' id='addPlaylistPopup' role='dialog'>
                                <div class='modal-dialog modal-sm'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h4 class='modal-title'>Add Playlist</h4>
                                        </div>
                                        <div class='modal-body'>
                                            <h5>Playlist Name:</h5>
                                            <input type='text' maxlength='40' class='form-control' id='playlistName' name='playlistName'>
                                            <h6 style='color:red' id='playlistNameValidation'></h6>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-primary' data-dismiss='modal' id='cancelAddPlaylist' naem='cancelAddPlaylist'>Cancel</button>
                                            <button type='button' class='btn btn-primary' id='submitAddPlaylist' name='submitAddPlaylist'>Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php
                        }
						else
							echo "</h4>";

						if($query = mysqli_prepare(db_connect_id(), "SELECT name, playlist_id FROM playlist WHERE playlist.username = ? ORDER BY creation_date DESC"))
						{
							mysqli_stmt_bind_param($query, "s", $decodedUsername);
							$result = mysqli_stmt_execute($query);
							$query->store_result();
							mysqli_stmt_bind_result($query, $listname, $listid);
							while(mysqli_stmt_fetch($query))
                            {
								echo "<div style=\"float: left; margin-bottom: 10px; width: 100%\">";
								echo "<a href=\"playlist.php?id=".$listid."\" style=\"font-size: 16px\">".$listname."</a><br/>";
								if($query1 = mysqli_prepare(db_connect_id(), "SELECT title, username, media.mediaid, upload_date, type, category FROM playlist_media LEFT JOIN media ON playlist_media.mediaid = media.mediaid WHERE playlist_id = ? ORDER BY upload_date DESC"))	
								{
									mysqli_stmt_bind_param($query1, "i", $listid);
									$result = mysqli_stmt_execute($query1);
									$query1->store_result();
									mysqli_stmt_bind_result($query1, $mediatitle, $mediauser, $mediaid, $mediadate, $mediatype, $mediacat);
									while(mysqli_stmt_fetch($query1))
									{
										echo "<div class=\"account-media-details-container\">";
                                        echo "<input type='hidden' name='mediaidField' value='$mediaid'>";
                                        echo "<input type='hidden' name='playlistidField' value='$listid'>";

                                        if(isset($_SESSION['username']) && $_SESSION['username'] === $decodedUsername)
                                        {
                                            echo "<span title='Delete From Playlist' class='glyphicon glyphicon-remove btn-remove-playlist-media'></span>";
                                        }

										switch(substr($mediatype,0,5))
										{
											case "video":
												echo "<span class=\"glyphicon glyphicon-film\"></span> ";
												break;
											case "audio":
												echo "<span class=\"glyphicon glyphicon-music\"></span> ";
												break;
											case "image":
												echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
												break;
											default: echo substr($mediatype,0,5);
										}
										echo "<a href=\"media.php?id=".$mediaid."&playlistid=".$listid."\">".$mediatitle."</a><br/>";
                                        echo "Uploader: <a href='account.php?username=".urlencode($mediauser)."'>$mediauser</a><br>";
										echo "Uploaded: ".$mediadate."<br/>";
										echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
										echo "</div>";
									}
									
								}
								echo "</div>";
							}
							mysqli_stmt_close($query);

						}


						?>

                    </div>
                    <div class="row" style="height: 30vh; overflow-y: auto; border-top: solid grey">
						<h4>Favorites</h4>
						<?php
						
						if($query = mysqli_prepare(db_connect_id(), "SELECT title, media.username, type, media.mediaid, upload_date, category FROM media JOIN favorited_media ON media.mediaid = favorited_media.mediaid WHERE favorited_media.username=? ORDER BY upload_date DESC"))
						{
							mysqli_stmt_bind_param($query, "s", $decodedUsername);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $mediatitle, $mediauser, $mediatype, $mediaid, $mediadate, $mediacat);
							while(mysqli_stmt_fetch($query))
							{
								echo "<div class=\"account-media-details-container\">";
                                echo "<input type='hidden' name='mediaidField' value='$mediaid'>";

                                if(isset($_SESSION['username']) && $_SESSION['username'] === $decodedUsername)
                                {
                                    echo "<span title='Delete From Favorites' class='glyphicon glyphicon-remove btn-account-remove-favorite'></span>";
                                }


								switch(substr($mediatype,0,5))
								{
									case "video":
										echo "<span class=\"glyphicon glyphicon-film\"></span> ";
										break;
									case "audio":
										echo "<span class=\"glyphicon glyphicon-music\"></span> ";
										break;
									case "image":
										echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
										break;
									default: echo substr($mediatype,0,5);
								}
								echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
                                echo "Uploader: <a href='account.php?username=".urlencode($mediauser)."'>$mediauser</a><br>";
								echo "Uploaded: ".$mediadate."<br/>";
								echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
								echo "</div>";
							}
							mysqli_stmt_close($query);
						}

						?>

                    </div>
                </div>
            </div>
        </div>
        <?php

        echo "</div>";
    }
}
elseif(isset($_SESSION['username']))
{

    echo "<meta http-equiv=\"refresh\" content=\"0;url=account.php?username=".$_SESSION['username']."\">";
}
else
{
?>
<meta http-equiv="refresh" content="0;url=index.php">
<?php
}
?>
</body>
</html>
