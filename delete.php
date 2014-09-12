<?php

	require "inc/conx.php";

	session_start();

    if (!isset($_SESSION['id'])) {
        require 'inc/login_tools.php';
        load();
    }

	if (isset($_GET['id'])) {
		$id = $_GET['id'];

		if (isset($_POST['confirm'])) {
			mysqli_query($dbc, "DELETE FROM stock WHERE id = $id");
			header('location: stock.php');
		}

		$result = mysqli_query($dbc, "SELECT * FROM stock WHERE id = $id");

		$row = mysqli_fetch_object($result);

?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
		<title>Notcutts Web System</title>
	</head>
	<body>
		
		<div class="container">
			<div class="well" style="margin-top: 20px;">
			Are you sure you want to delete: <b><?php echo $row->item; ?></b>
			<br />
			<hr />
				<form method="POST" role="form">
					<input type="submit" name="confirm"	class="btn btn-danger pull-right" value="Delete" />
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