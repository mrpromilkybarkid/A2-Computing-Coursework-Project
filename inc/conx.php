<?php

	//Define a new variable and set it to the parameters needed to login in to a database
	//The end of the line stops all PHP code from being run and outputs an error to the user if there is a problem connecting
	$dbc = mysqli_connect("localhost", "root", "mysql", "notcutts")or die(mysqli_connect_error());
	//This selects the database being used. The first bit used the string just defined to define which network to find the database from
	mysqli_select_db($dbc, "notcutts");

?>