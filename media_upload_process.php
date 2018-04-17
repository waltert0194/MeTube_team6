<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
include_once "function.php";

/******************************************************
*
* upload document from user
*
*******************************************************/

$username=$_SESSION['username'];


//Create Directory if doesn't exist
if(!file_exists('uploads/'))
    mkdir('uploads/', 0757);
$dirfile = 'uploads/'.$username.'/';
if(!file_exists($dirfile))
    mkdir($dirfile,0755);
chmod($dirfile,0755);

if($_FILES["file"]["error"] > 0 )
{
    $result="File error ".$_FILES["file"]["error"]; //error from 1-4
}
else
{
    //Get the parts of the filepath to construct a new one
    $pathinfo = pathinfo($_FILES['file']['name']);
    $upfile = $dirfile.uniqid().".".$pathinfo['extension'];
    if(is_uploaded_file($_FILES['file']['tmp_name']))
    {
        if(!move_uploaded_file($_FILES['file']['tmp_name'],$upfile))
        {
            $result="Failed to move file from temporary directory"; //Failed to move file from temporary directory
        }
        else /*Successfully upload file*/
        {
                
            //insert into media table
            if($query = mysqli_prepare(db_connect_id(), "INSERT INTO media(mediaid,
                title, username, type, path, size, upload_date, description, category, allow_comments) VALUES(NULL, ?, ?,
                ?, CONCAT(?,(SELECT AUTO_INCREMENT FROM information_schema.tables
                WHERE table_name='media'), '.', ?), ?, NOW(), ?, ?, ?)"))
            {
                $title = $_POST["title"];
		        $description = $_POST["description"];
		        $category = $_POST["category"];
		        if(isset($_POST["allowComments"]) && $_POST["allowComments"] == "checked")$allowcomments = 1;
			else $allowcomments = 0;
                mysqli_stmt_bind_param($query, "sssssissi", $title, $username,
                    $_FILES['file']['type'],$dirfile, $pathinfo['extension'],
                    $_FILES['file']['size'], $description, $category, $allowcomments);
                $insert = mysqli_stmt_execute($query)
                    or (unlink($upfile) and die("Insert into media error in media_upload_process.php " .mysqli_error(db_connect_id())))
                    or die("Insert into media error and delete file error in media_upload_process.php " .mysqli_error(db_connect_id()));
                mysqli_stmt_close($query);
		
		//add keywords to media_keyword table
		$mediaid = mysqli_insert_id(db_connect_id());
		$keywords = array_from_keywords($_POST["keywords"]);
		foreach($keywords as $current){
			add_media_keyword($mediaid, $current);
		}	
		chmod($upfile, 0644);
            	rename($upfile, $dirfile.$mediaid.".".$pathinfo['extension']);
            }
            else
            {
                die("Insert into media error in media_upload_process.php " .mysqli_error(db_connect_id()));
            }

            $result="0";
            
        }
    }
    else  
    {
	
        $result= "File Error"; 
    }
}

//You can process the error code of the $result here.
?>

<meta http-equiv="refresh" content="0;url=index.php?result=<?php if($result != "0") echo $result;?>">
