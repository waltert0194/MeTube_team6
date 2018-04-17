<?php
if(session_id() == '')
{
        ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
}
include_once "function.php";

if(!isset($_SESSION['username']) 
	|| (isset($_SESSION['username']) && isset($_GET['id']) && getUsernameFromMedia($_GET['id']) !== $_SESSION['username'] )
	|| (!isset($_GET['id']))
){
?>
<head><meta http-equiv="refresh" content="0; url=index.php" /></head>


<?php

}else{
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/default.css">
        <script src="js/jquery-3.2.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/editmedia.js"></script>	
<title>Edit Media</title>
</head>
<body>
<?php include "header.php";
?>
<div style="margin-left: 30px">
<?php echo "<h3>Editing \"", getTitleFromMedia($_GET['id']), "\"</h3>";
?>
<h4 style="margin-bottom:0px; margin-top: 20px;">Give a title to your media</h4>

<form class="form-horizontal" method="post" action="media_edit_process.php" enctype="multipart/form-data" >
		<input type="hidden" name="mediaid" value="<?php echo $_GET['id'];?>">

             <?php echo  "<input value=\"", getTitleFromMedia($_GET['id']), "\" maxlength=\"40\" id=\"title\" name=\"title\" type=\"text\" class=\"form-control\" style=\"width: 550px;\">"; ?>

                <h4 style="margin-bottom:0px; margin-top: 20px;">Write a Description</h4>
                <textarea id="description" name="description" class="form-control" rows="5" style="resize: none; width: 550px;" maxlength="998"><?php echo getDescriptionFromMedia($_GET['id']); ?></textarea>

                <h4 style="margin-bottom:0px; margin-top: 20px;">Category</h4>
                <select name="category"class="form-control" style="width: 175px;">
<?php $category = getCategoryFromMedia($_GET['id']);?>
                        <option <?php if($category === "Comedy") echo "selected"; ?>>Comedy</option>
                        <option <?php if($category === "Games") echo "selected"; ?>>Games</option>
                        <option <?php if($category === "Travel") echo "selected"; ?>>Travel</option>
                        <option <?php if($category === "Music") echo "selected"; ?>>Music</option>
                        <option <?php if($category === "Sports") echo "selected"; ?>>Sports</option>
                        <option <?php if($category === "Educational") echo "selected"; ?>>Educational</option>
                        <option <?php if($category === "Other") echo "selected"; ?>>Other</option>
                </select>


                <h4 style="margin-bottom:0px; margin-top: 20px;">Keywords</h4>
                <h6 style="margin-bottom:0px; margin-top: 0px;">Separate with a space</h6>
                <input value="<?php echo get_media_keywords($_GET['id']); ?>"  name="keywords" type="text" id="keywords" class="form-control" style="width: 550px; margin-bottom: 20px">

        <!--<h4 style="margin-bottom:0px; margin-top: 20px;">Allow Comments?</h4>
                <input name="allowComments" type="checkbox" id="allowComments" class="form-control" style="float: left; width: 20px; height: 20px;" <?php if(getAllowedCommentsFromMedia($_GET['id'])) echo "checked value=\"checked\">";?>
<br><br> -->
<input id="submitBtn" style="margin-left: 0px; margin-bottom 20px" value="Save Changes" name="submit" type="submit" class="btn btn-primary"/> 

</form>
</div>
</body>



<?php
}
?>
