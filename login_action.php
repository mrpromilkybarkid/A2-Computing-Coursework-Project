<?php

	if (isset($_POST['loginSubmit'])) {
		require 'inc/conx.php';
		require 'inc/login_tools.php';

		list($check, $data) = validate($dbc, $_POST['loginUsername'], $_POST['loginPassword']);

		if ($check) {
			session_start();

			$_SESSION['id'] = $data['id'];
			$_SESSION['username'] = $data['username'];
			$_SESSION['name'] = $data['name'];

			//Set Cookie For 12 Hours
			$hour = time() + 43200;
			setCookie('rememberMe', $_SESSION['username'], $hour);

			load('notcutts/production/home.php');
		} else {
			$errors = $data;
		}
		mysqli_close($dbc);
		include "index.php";
	}

?>