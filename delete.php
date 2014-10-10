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

    //Check to see if an ID has been passed through as a GET request
	if (isset($_GET['id'])) {
		//If yes, define the variable id equel to the given ID of the item being deleted from the stock table
		$id = $_GET['id'];

		//Check to see if the confirm button has been pressed
		if (isset($_POST['confirm'])) {
			//If yes, establish a connection to the database and delete the item from the database where the ID matches the id variable
			//(The id variable is the id passed through as a GET request which is the item the user selected to delete)
			mysqli_query($dbc, "DELETE FROM stock WHERE id = $id");
			//Redirect the user back to the stock page
			header('location: stock.php');
		}

		//Define the result variable as a query. This query selects all items from the database where the ID matches the id variable
		$result = mysqli_query($dbc, "SELECT * FROM stock WHERE id = $id");

		//The matches are then fetched and stored in the variable, row
		$row = mysqli_fetch_object($result);

?>

<!doctype html>
<html>
	<head>
		<!-- Include Bootstrap stylesheet -->
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
		<title>Notcutts Web System</title>
	</head>
	<body>
		
		<div class="container">
			<div class="well" style="margin-top: 20px;">
			Are you sure you want to delete: 
			<b>
				<?php
					//Output the the match that was found within the database from out result and row variables 
					echo $row->item; 
				?>
			</b>
			<br />
			<hr />
				<form method="POST" role="form">
					<!-- Confirm Delete Button -->
					<input type="submit" name="confirm"	class="btn btn-danger pull-right" value="Delete" />
					<!-- Cancel Button -->
					<a href="stock.php" class="btn btn-success">Cancel</a>
				</form>
			</div>		
		</div>

		<!-- Javascript Includes -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>

	</body>
</html>

<?php

	}

?>