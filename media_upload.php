<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/default.css">
 	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/media_upload.js"></script>
<title>MeTube | Upload</title>
</head>

<body>
<?php
	include "header.php";
if(!isset($_SESSION['username']))
{?>
<meta http-equiv="refresh" content="0;url=index.php">
<?php    
}
else
{
?>
<div style="margin-left: 30px;">


	<form class="form-horizontal" method="post" action="media_upload_process.php" enctype="multipart/form-data" >
		
   		
        	
        	<h3>Upload a File (50 MiB max):</h3>
		
	<label class="btn btn-primary btn-file" for="fileInput">
				
    			Browse Local Files 
		<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
		<input name="file" id="fileInput" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
		</label>
		
		<h4 style="margin-bottom:0px; margin-top: 20px;">Give a title to your media</h4>
		<input maxlength="40" id="title" name="title" type="text" class="form-control" style="width: 550px;">

		<h4 style="margin-bottom:0px; margin-top: 20px;">Write a Description</h4>
  		<textarea id="description" name="description" class="form-control" rows="5" style="resize: none; width: 550px;" maxlength="998"></textarea>

		<h4 style="margin-bottom:0px; margin-top: 20px;">Category</h4>
		<select name="category"class="form-control" style="width: 175px;">
			<option>Comedy</option>
			<option>Games</option>
			<option>Travel</option>
			<option>Music</option> 
			<option>Sports</option> 
			<option>Educational</option> 
			<option>Other</option> 
		</select>			

        	
		<h4 style="margin-bottom:0px; margin-top: 20px;">Keywords</h4>
		<h6 style="margin-bottom:0px; margin-top: 0px;">Separate with a space</h6>
                <input name="keywords" type="text" id="keywords" class="form-control" style="width: 550px;">

		<input id="submitBtn" style="margin-top: 20px;" value="Upload" name="submit" type="submit" class="btn btn-primary"/>
    		
 		
                
	</form>

<h5 style="margin-top: 20px">Supports File Types:<br> .ico .wav .mp3 .mp4 .webm .ogg .gif .png .jpg .jpeg .bmp</h5>
</div>
</body>
</html>
<?php }?>
