<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
?>

<div id="headerContainer" class="container-fluid header-container">
    <div class="row">
        <div id="logoContainer" class="col-md-1">
            <a href="index.php"><img src="images/logo.png"/></a>
        </div>

        <div class="col-md-2">
        </div>
        
        <form id="searchForm" method="GET" action="search.php">
            <div class="col-md-4" style="padding-top: 8px">
                    <input type="text" id="query" name="query" class="form-control pull-right" placeholder="Search">
            </div>
            <div class="col-md-1" style="padding-top: 8px">
                    <input type="submit" id="searchSubmit" class="btn btn-primary pull-left" value="Submit">
            </div>
        </form>

        <div id="welcomeContainer" class="col-md-2">
            <p style="padding-top: 15px; text-align: right;">
                <?php if(isset($_SESSION['username'])) echo 'Welcome, '.$_SESSION['username'].'!'; ?>
            </p>
        </div>

        <div id="logInContainer" class="col-md-2" style="padding-top: 7px">
            <a class='header-btn' <?php
                if(isset($_SESSION['username']))
                    echo "href='logout.php'";
                else
                    echo "href='login.php'"; ?>>
                <div id="logInButton" class="btn btn-primary">
                    <?php if(isset($_SESSION['username'])) echo 'Sign-out'; else echo 'Sign-in';?>
                </div>
            </a>

            <?php
                //If a user is logged in, add an account button
                if(isset($_SESSION['username']))
                {
                    echo "<a href='account.php?username=".urlencode($_SESSION['username']);
                    echo "' id='headerAccountBtn' class='btn btn-primary header-btn'>Account</a>";

                    echo "<a href='media_upload.php?username=".urlencode($_SESSION['username']);
                    echo "' id='headerAccountBtn' class='btn btn-primary header-btn'>Upload</a>";
                }
            ?>
        </div>
    </div>
    <hr style="margin: 10px 0px 20px 0px;"/>
</div>
