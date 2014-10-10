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

		//Define the result variable as a query. This query selects all items from the database where the ID matches the id variable
		$result = mysqli_query($dbc, "SELECT * FROM stock WHERE id = $id");

		//The matches are then fetched and stored in the variable, row
		$row = mysqli_fetch_object($result);

		//Check if the Update button has been clicked/submitted
		if (isset($_POST['updateItemSubmit'])) {
			//Check to see if the quantity input box is empty
			if (!empty($_POST['updateItemQuantity'])) {
				//If the input is not empty, define what ever is in that input box to the variable, newItemQuantity
				$newItemQuantity = $_POST['updateItemQuantity'];

				//
				mysqli_query($dbc, "UPDATE stock SET quantity = '$newItemQuantity' WHERE id = $id")or die(mysqli_error($dbc));
				header('location: stock.php');
			} else {
				echo '<div class="alert alert-danger">Please Fill In The Quantity Field</div>';
			}
		}

		if (isset($_POST['cancel'])) {
			header('location: stock.php');
		}

?>

<!doctype html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
		<title>Notcutts Web System</title>
	</head>
	<body>

		<div class="container">
			<div class="jumbotron" style="margin-top: 20px;">
				<legend>Update <b><?php echo $row->item; ?></b></legend>
				<form method="POST" role="form">
					<table class="table tabled-striped table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Item</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $row->id; ?></td>
								<td><?php echo $row->item; ?></td>
								<td><input type="text" name="updateItemQuantity" class="form-control" value="<?php echo $row->quantity; ?>" /></td>
							</tr>
						</tbody>
					</table>
					<a href="stock.php" class="btn btn-danger">Cancel</a>
					<input type="submit" name="updateItemSubmit" class="btn btn-success pull-right" value="Update" />
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