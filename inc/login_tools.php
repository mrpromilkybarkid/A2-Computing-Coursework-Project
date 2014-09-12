<?php

	function load($page = 'notcutts/production/index.php') {
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		$url = rtrim($url, '/\\');
		$url = '/' . $page;
		header('location:' . $url);
		exit();
	}

	function validate($dbc, $username = '', $password = '') {
		$errors = array();
		if (empty($username)) {
			$errors[] = "Enter your username";
		} else {
			$u = mysqli_real_escape_string($dbc, trim($username));
		}

		if (empty($password)) {
			$errors[] = "Enter your username";
		} else {
			$p = mysqli_real_escape_string($dbc, trim($password));
		}

		if (empty($errors)) {
			$q = "SELECT * FROM `users` WHERE `username` = '$u' AND `password` = $p";
			$r = mysqli_query($dbc, $q)or die(mysql_error());

			if (mysqli_num_rows($r) == 1) {
				$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
				return array(true, $row);
			} else {
				$errors[] = 'User not found';
			}
		}
		return array(false, $errors);
	}