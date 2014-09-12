<?php
	
	session_start();

	if (!isset($_SESSION['id'])) {
		require 'inc/login_tools.php';
		load();
	}

	$_SESSION = array();
	
	session_destroy();

	header('location: index.php');

?>