<?php

	//No script on this page will run until the connection script has been included within the page
	require "inc/conx.php";

	//Start a new session so that the user remains logged in and the user's login data is remembered whilst on the site
	session_start();

	//Check whether the session has been set using the user ID
    if (!isset($_SESSION['id'])) {
    	//If the session has not been set(user is not logged in), include the login tools script
        require 'inc/login_tools.php';
        //Use the load function within the login tools script
        //This will redirect the user back to the index page where they will need to login
        load();
    }

?>
<!doctype html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
		<title>Notcutts Web System</title>
	</head>
	<body>

		<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a href="home.php" class="navbar-brand">Notcutts Web System</a>
				</div>
				<ul class="nav navbar-nav navbar-right">
					<li><p class="navbar-text"><span class="glyphicon glyphicon-user"></span> Logged In As: <b>
						<?php
							//Output the name of the person logged In
							//First letter in the is capitilised
							echo ucfirst($_SESSION['name']);
						?>
					</b></p></li>
					<li class="active"><a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
				</ul>
			</div>
		</nav>

		<div class="container">
			<div class="well" style="margin-top: 20px; padding-top: 10px;">
				<legend>Dashboard</legend>
				<div class="list-group">
					<a href="stock.php" class="list-group-item"><span class="glyphicon glyphicon-cutlery"></span> Stock List</a>
					<a href="wastage.php" class="list-group-item"><span class="glyphicon glyphicon-trash"></span> Wastage Sheets</a>
					<a href="temp.php" class="list-group-item"><span class="glyphicon glyphicon-copyright-mark"></span> Temperature Sheets</a>
				</div>
				<hr />
				<p>Notcutts Web System - Created By: <b><a href="http://benpowley.co.uk">Ben Powley</a></b></p>
			</div>
		</div>

		<!-- Javascript Includes -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>

	</body>
</html>
