<?php

	$dbc = mysqli_connect("localhost", "root", "mysql", "notcutts")or die(mysqli_connect_error());
	mysqli_select_db($dbc, "notcutts");

?>