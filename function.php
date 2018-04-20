<?php
include "mysqlClass.inc.php";


//return username 
function getUsernameFromMedia($id){
	 if($query = mysqli_prepare(db_connect_id(), "SELECT username FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $id);
        if(!mysqli_stmt_execute($query)) return null; 
        mysqli_stmt_bind_result($query, $fetchedUsername);
        $exists = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);

        if($exists)
            return $fetchedUsername; 
        else
            return null; 
    }
    else
    {
        return null; 
    }
	return null;
}

//return Title from Media with id
function getTitleFromMedia($id){
         if($query = mysqli_prepare(db_connect_id(), "SELECT title FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $id);
        if(!mysqli_stmt_execute($query)) return null; //Query failed
        mysqli_stmt_bind_result($query, $title);
        $exists = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);

        if($exists)
            return $title; 
        else
            return null; //User does not exist
    }
    else
    {
        return null; //Could not connect
    }
        return null;
}

function getDescriptionFromMedia($id){
         if($query = mysqli_prepare(db_connect_id(), "SELECT description FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $id);
        if(!mysqli_stmt_execute($query)) return null; 
        mysqli_stmt_bind_result($query, $fetchedDescription);
        $exists = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);

        if($exists)
            return $fetchedDescription; 
        else
            return null; 
    }
    else
    {
        return null; 
    }
        return null;
}

//returns category of media
function getCategoryFromMedia($id){
         if($query = mysqli_prepare(db_connect_id(), "SELECT category FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $id);
        if(!mysqli_stmt_execute($query)) return null; 
        mysqli_stmt_bind_result($query, $fetchedCat);
        $exists = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);

        if($exists)
            return $fetchedCat; 
        else
            return null; 
    }
    else
    {
        return null; 
    }
        return null;
}


function getAllowedCommentsFromMedia($id){
         if($query = mysqli_prepare(db_connect_id(), "SELECT allow_comments FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $id);
        if(!mysqli_stmt_execute($query)) return false; 
        mysqli_stmt_bind_result($query, $comments);
        $exists = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);

        if($exists)
            return $comments == 1; 
        else
            return false; 
    }
    else
    {
        return false; 
    }
        return false;
}


function array_from_keywords($keywords)
{
    $lower = strtolower($keywords);
    return array_filter(preg_split("/\s+/", $lower), function($elem) {return $elem != "";});
}


function get_matched_rows($link)
{
    return preg_match("!\d+!", mysqli_info($link));
}





function user_exist_check($username)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT username FROM account WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $username);
        if(!mysqli_stmt_execute($query)) return 3; //Query failed
        mysqli_stmt_bind_result($query, $fetchedUsername);
        $exists = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);

        if($exists)
            return 1;
        else
            return 0; 
    }
    else
    {
        return 2;
    }
}


function add_account_to_db($username, $password, $email)
{
    $userExists = user_exist_check($username);

    if ($userExists > 1)
    {
        return 0;
	}	
    else
    {
        if($userExists == 0)
        {
			$hash = password_hash($password, PASSWORD_DEFAULT);
			
            if($query = mysqli_prepare(db_connect_id(), "INSERT INTO account (username, password, email) VALUES (?, ?, ?)"))
            {
                mysqli_stmt_bind_param($query, "sss", $username, $hash, $email);
                $insert = mysqli_stmt_execute($query);
                mysqli_stmt_close($query);

                if($insert)
                    return 1;
                else
                    return 0;
            }
            else
            {
                return 0;
            }
        }
        else
        {
			return 2; 
		}
	}
}

function user_pass_check($username, $password)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT password FROM account WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $username);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $fetchedPassword);
    }
    else
    {
		die ("user_pass_check() failed. Could not query the database: <br />". mysqli_error());
    }
    
    if (!$result)
    {
        mysqli_stmt_close($query);
	    die ("user_pass_check() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
    }

    if(!mysqli_stmt_fetch($query))
    {
        mysqli_stmt_close($query);
        return 1;
    }
    else
    {
        mysqli_stmt_close($query);
		if(password_verify($password, $fetchedPassword))
			return 0; //Correct password
		else
			return 2; //Wrong
	}
}


function update_user_pass($username, $currpassword, $newpassword)
{
    $passcheck = user_pass_check($username, $currpassword);
    if($passcheck != 0)
        return $passcheck;
    
    //Hash the password before storing it so it is not in plaintext
	$hash = password_hash($newpassword, PASSWORD_DEFAULT);
        
    if($query = mysqli_prepare(db_connect_id(), "UPDATE account SET password=? WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "ss", $hash, $username);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
	    {
	        die("update_user_pass() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
        }
        else
        {
            return 0;
        }
    }
    else
    {
	    die("update_user_pass() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
    }

}

function updateMediaTime($mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE media SET lastaccesstime=NOW() WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $mediaid);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_close($query);
        if (!$result)
	    {
	        die ("updateMediaTime() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
        }
    }
    else
    {
		die ("updateMediaTime() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
    }
}

function upload_error($result)
{
	
	switch ($result){
	case 1:
		return "UPLOAD_ERR_INI_SIZE";
	case 2:
		return "UPLOAD_ERR_FORM_SIZE";
	case 3:
		return "UPLOAD_ERR_PARTIAL";
	case 4:
		return "UPLOAD_ERR_NO_FILE";
	case 5:
		return "File has already been uploaded";
	case 6:
		return  "Failed to move file from temporary directory";
	case 7:
		return  "Upload file failed";
	}
}

function update_account_info($username, $biography, $email)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE account SET biography=?, email=? WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "sss", $biography, $email, $username);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function remove_media($mediaid)
{
   
    if($query = mysqli_prepare(db_connect_id(), "SELECT path FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $mediaid);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $filepath);
        $fetched = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);
        if (!$result || !$fetched)
	    {
	        return false;
        }
    }
    else
    {
		return false;
    }
    
    //Delete the media item from the database
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $mediaid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
	    {
	        return false;
        }
    }
    else
    {
		return false;
    }

    //Delete the media item from the filesystem
    if(!unlink($filepath))
        return false;
    return true;
}


function update_media_metadata($mediaid, $title, $description, $category, $keywords, $allow_comments)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE media SET title=?, description=?,
        category=?, allow_comments=? WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "sssii", $title, $description, $category,
            $allow_comments, $mediaid);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
	    {
	        return false;
        }
    }
    else
    {
		return false;
    }

    if(!remove_media_keywords($mediaid))
        return false;

    $success = true;
    foreach($keywords as $currkeyword)
    {
        if(!add_media_keyword($mediaid, $currkeyword))
            $success = false;
    }

    return $success;
}

function add_media_keyword($mediaid, $media_key)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO media_keyword (mediaid, media_key) VALUES (?, ?)"))
    {
        mysqli_stmt_bind_param($query, "is", $mediaid, $media_key);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $errno = mysqli_errno(db_connect_id()); //Report success on error if it was just a duplicate entry warning
        mysqli_stmt_close($query);
        if ((!$result || $affected < 1) && ($errno != 1062))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false;
    }
}



function remove_media_keywords($mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM media_keyword WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $mediaid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 0)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

function get_media_keywords($mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT media_key FROM media_keyword WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $mediaid);
        $result = mysqli_stmt_execute($query);
        if (!$result)
        {
            mysqli_stmt_close($query);
	        return NULL;
        }
        
        mysqli_stmt_bind_result($query, $media_key);

        $keywordstring = "";
        while(mysqli_stmt_fetch($query))
            $keywordstring = $keywordstring.$media_key." ";

        mysqli_stmt_close($query);

        return $keywordstring;
    }
    else
    {
		return NULL;
    }
}

//Adds a comment from the given username on the given media with the given
//message. Returns true on success, false on failure.
function add_comment($username, $mediaid, $message)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO comment (com_id,
        username, mediaid, com_date, message) VALUES (NULL, ?, ?, NOW(), ?)"))
    {
        mysqli_stmt_bind_param($query, "sis", $username, $mediaid, $message);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

function remove_comment($commentid)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM comment WHERE com_id=?"))
    {
        mysqli_stmt_bind_param($query, "i", $commentid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function update_comment($commentid, $message)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE comment SET message=? WHERE com_id=?"))
    {
        mysqli_stmt_bind_param($query, "si", $message, $commentid);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function add_subscription($subscriber_username, $subscribee_username)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO subscription
        (subscriber_username, subscribee_username) VALUES (?,?)"))
    {
        mysqli_stmt_bind_param($query, "ss", $subscriber_username, $subscribee_username);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $errno = mysqli_errno(db_connect_id()); 
        mysqli_stmt_close($query);
        if ((!$result || $affected < 1) && ($errno != 1062))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function remove_subscription($subscriber_username, $subscribee_username)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM subscription
        WHERE subscriber_username=? AND subscribee_username=?"))
    {
        mysqli_stmt_bind_param($query, "ss", $subscriber_username, $subscribee_username);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

function add_favorited_media($username, $mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO favorited_media
        (username, mediaid) VALUES (?,?)"))
    {
        mysqli_stmt_bind_param($query, "si", $username, $mediaid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $errno = mysqli_errno(db_connect_id()); 
        mysqli_stmt_close($query);
        if ((!$result || $affected < 1) && ($errno != 1062))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function remove_favorited_media($username, $mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM favorited_media
        WHERE username=? AND mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "si", $username, $mediaid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

function create_playlist($username, $playlist_name)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO playlist
        (playlist_id, username, creation_date, name) VALUES (NULL, ?, NOW(), ?)"))
    {
        mysqli_stmt_bind_param($query, "ss", $username, $playlist_name);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function remove_playlist($playlist_id)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM playlist
        WHERE playlist_id=?"))
    {
        mysqli_stmt_bind_param($query, "i", $playlist_id);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

function get_playlist_contains_media($playlist_id, $mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT playlist_id FROM playlist_media
        WHERE playlist_id=? AND mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "ii", $playlist_id, $mediaid);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $id);
        $result = $result && mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);
        if (!$result)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function add_playlist_keyword($playlist_id, $keyword)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO playlist_keyword 
        (playlist_id, keyword) VALUES (?, ?)"))
    {
        mysqli_stmt_bind_param($query, "is", $playlist_id, $media_key);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $errno = mysqli_errno(db_connect_id()); 
        mysqli_stmt_close($query);
        if ((!$result || $affected < 1) && ($errno != 1062))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false;
    }
}



function remove_playlist_keywords($playlist_id)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM playlist_keyword
        WHERE playlist_id=?"))
    {
        mysqli_stmt_bind_param($query, "i", $playlist_id);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 0)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function get_playlist_keywords($playlist_id)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT media_key FROM playlist_keyword WHERE playlist_id=?"))
    {
        mysqli_stmt_bind_param($query, "i", $playlist_id);
        $result = mysqli_stmt_execute($query);
        if (!$result)
        {
            mysqli_stmt_close($query);
	        return NULL;
        }
        
        mysqli_stmt_bind_result($query, $media_key);

        $keywordstring = "";
        while(mysqli_stmt_fetch($query))
            $keywordstring = $keywordstring.$media_key." ";

        mysqli_stmt_close($query);

        return $keywordstring;
    }
    else
    {
		return NULL;
    }
}

function add_playlist_media($playlist_id, $mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO playlist_media 
        (playlist_id, mediaid) VALUES (?, ?)"))
    {
        mysqli_stmt_bind_param($query, "ii", $playlist_id, $mediaid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $errno = mysqli_errno(db_connect_id()); 
        mysqli_stmt_close($query);
        if ((!$result || $affected < 1) && ($errno != 1062))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false;
    }
}


function remove_playlist_media($playlist_id, $mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM playlist_media 
        WHERE playlist_id=? AND mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "ii", $playlist_id, $mediaid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false;
    }
}

function update_playlist_metadata($playlist_id, $name, $description, $keywords)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE playlist SET name=?, description=?
       WHERE playlist_id=?"))
    {
        mysqli_stmt_bind_param($query, "ssi", $name, $description, $playlist_id);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
	    {
	        return false;
        }
    }
    else
    {
		return false;
    }

    if(!remove_playlist_keywords($playlist_id))
        return false;

    $success = true;
    foreach($keywords as $currkeyword)
    {
        if(!add_playlist_keyword($playlist_id, $currkeyword))
            $success = false;
    }

    return $success;
}


function add_message($sender_username, $message_contents, $recipient_usernames)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO message (message_id,
        sender_username, send_date, message_contents) VALUES (NULL, ?, NOW(), ?)"))
    {
        mysqli_stmt_bind_param($query, "ss", $sender_username, $message_contents);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $insert_id = mysqli_insert_id(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
	    {
	        return false;
        }
    }
    else
    {
		return false;
    }

    $success = true;
    foreach($recipient_usernames as $currusername)
    {
        if(!add_message_recipient($insert_id, $currusername))
            $success = false;
    }

    return $success;
}


function add_message_recipient($message_id, $recipient_username)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO message_recipient 
        (message_id, recipient_username) VALUES (?, ?)"))
    {
        mysqli_stmt_bind_param($query, "is", $message_id, $recipient_username);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $errno = mysqli_errno(db_connect_id()); 
        mysqli_stmt_close($query);
        if ((!$result || $affected < 1) && ($errno != 1062))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false;
    }
}

function other()
{
	
}
	
?>
