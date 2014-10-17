<?php

	//Check to see if the login form has been submitted
	if (isset($_POST['loginSubmit'])) {
		//If it has, include the connection file
		require 'inc/conx.php';
		//As well as the login tools file to validate the data
		require 'inc/login_tools.php';

		//Create a list of all the data and validate the username and password based on the code from login_tools
		list($check, $data) = validate($dbc, $_POST['loginUsername'], $_POST['loginPassword']);

		//Check to see if the check was successful and the data is valid 
		if ($check) {
			//Start the session
			session_start();

			//Set the session id to the current user's id
			$_SESSION['id'] = $data['id'];
			//Set the session username to the current user's username
			$_SESSION['username'] = $data['username'];
			//Set the session name to the current user's name
			$_SESSION['name'] = $data['name'];

			//Define 12 hours in variable
			$hour = time() + 43200;
			//Set cookie for 12 hours so the user stays logged in
			setCookie('rememberMe', $_SESSION['username'], $hour);

			//Load the home page which is only accesible if someone is logged in.
			load('notcutts/production/home.php');
		} else {
			//If the check was unsuccessful, output the errors to the user
			$errors = $data;
		}
		//Close the connection to the database
		mysqli_close($dbc);
		//Redirect back to the index page
		include "index.php";
	}

?>