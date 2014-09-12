<?php

	require "inc/conx.php";

	session_start();

    if (!isset($_SESSION['id'])) {
        require 'inc/login_tools.php';
        load();
    }

	if (isset($_GET['id'])) {
		$id = $_GET['id']; 

		$result = mysqli_query($dbc, "SELECT * FROM stock WHERE id = $id");

		$row = mysqli_fetch_object($result);

		if (isset($_POST['updateItemSubmit'])) {
			if (!empty($_POST['updateItemQuantity'])) {
				$newItemQuantity = $_POST['updateItemQuantity'];

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