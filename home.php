<?php

	include "inc/conx.php";

	session_start();

	if (!isset($_SESSION['id'])) {
		require 'inc/login_tools.php';
		load();
	}

	//echo $_SESSION['username'];

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
					<li><p class="navbar-text"><span class="glyphicon glyphicon-user"></span> Logged In As: <b><?php echo ucfirst($_SESSION['name']); ?></b></p></li>
					<li class="active"><a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
				</ul>
			</div>
		</nav>

		<div class="container">
			<div class="center-block well " style="max-width: 900px; margin-top: 20px; padding-top: 10px;">
				<legend>Dashboard</legend>
				<div class="list-group">
					<a href="stock.php" class="list-group-item"><span class="glyphicon glyphicon-cutlery"></span> Stock List</a>
					<a href="wastage.php" class="list-group-item"><span class="glyphicon glyphicon-trash"></span> Wastage Sheets</a>
					<a href="temperature.php" class="list-group-item"><span class="glyphicon glyphicon-copyright-mark"></span> Temperature Sheets</a>
				</div>
				<hr />
				<p>Notcutts Web System <b>Version 0.1</b></p>
			</div>
		</div>

		<!-- Javascript Includes -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>


	</body>
</html>
