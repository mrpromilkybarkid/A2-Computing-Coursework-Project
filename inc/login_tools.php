<?php

	//Page load function - When called, loads a specified page given as a parameter
	function load($page = 'notcutts/production/index.php') {
		//Defines the base URL
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		//Trims the parameter of all /s
		$url = rtrim($url, '/\\');
		//Concatenates the parameter onto the end of a slash to make it a valid URL
		$url = '/' . $page;
		//Loads the given url variable
		header('location:' . $url);
		//Exits the Load
		exit();
	}

	//Validation function to check input of username and password when logging In
	function validate($dbc, $username = '', $password = '') {
		//Creates an errors array which will store all errors 
		$errors = array();
		//Checks if the username field is empty
		if (empty($username)) {
			//If empty, this string is added to the errors array
			$errors[] = "Enter your username";
		} else {
			//If not, the username is defined to a variable where it escapes the string and trims of tags
			$u = mysqli_real_escape_string($dbc, trim($username));
		}

		//Check if the password field is empty
		if (empty($password)) {
			//If empty, this string is added to the errors array
			$errors[] = "Enter your username";
		} else {
			//If not, the password is definaed to a variable where it escapes the string and trims the tags
			$p = mysqli_real_escape_string($dbc, trim($password));
		}

		//Checks if the erorrs array is empty
		if (empty($errors)) {
			//If not, a query is creeated to gather the username and password from the database which match the inputted values
			$q = "SELECT * FROM `users` WHERE `username` = '$u' AND `password` = $p";
			//Runs the query, if it fails an error is output
			$r = mysqli_query($dbc, $q)or die(mysql_error());

			//Checks if the values match anything in the database
			if (mysqli_num_rows($r) == 1) {
				//Creates an array of the matches
				$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
				//Returns the matched values
				return array(true, $row);
			} else {
				//If no values match any in database, add this string to the errors array
				$errors[] = 'User not found';
			}
		}
		//Return the errors given in the errors array
		return array(false, $errors);
	}