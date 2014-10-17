<?php

	//No script on this page will run until the connection script has been included within the page
	require "inc/conx.php";


?>
<!doctype html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
		<title>Notcutts Web System</title>
	</head>
	<body>

		<div class="container">
			<div class="center-block well" style="max-width: 550px; margin-top: 20px;">
				<legend>Please Log In</legend>
				<?php

					//Check to see if the errors array exists and it is not empty
					if (isset($errors) && !empty($errors)) {
						//If the errors array is not empty, output an error message within a red alert box
						echo '<div class="alert alert-danger"><p id="err_msg">Oops! There was a problem:<br />';
						//Go through each error and make them individual variables
						foreach ($errors as $msg) {
							//Output each error with a line break
							echo $msg . '<br />';
						}
						//Output try again message touser
						echo 'Please try again</p></div>';
					}

				?>
				<form method="POST" role="form" action="login_action.php">
					<input type="text" name="loginUsername" id="loginUsername" class="form-control" placeholder="Username" /> <br />
					<input type="password" name="loginPassword" id="loginPassword" class="form-control" placeholder="Password" /> <br />
					<input type="submit" name="loginSubmit" id="loginSubmit" class="btn btn-primary form-control" value="Log In" />
				</form>
				<br />
				<hr />
				<p>Notcutts Web System <b>Version 0.1</b></p>
			</div>
		</div>

		<!-- Javascript Includes -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>

	</body>
</html>
