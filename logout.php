<?php

	//No script on this page will run until the connection script has been included within the page
	require "inc/conx.php";

	//Start a new session so that the user remains logged in and the user's login data is remembered whilst on the site
	session_start();

	//Check whether the session has been set using the user ID
    if (!isset($_SESSION['id'])) {
    	//If the session has not been set(user is not logged in), include the login tools script
        require 'inc/login_tools.php';
        //Use the load function within the login tools script
        //This will redirect the user back to the index page where they will need to login 
        load();
    }

    //Set the session equal to an arrat
	$_SESSION = array();
	
	//Destroy the session and all cookies for the current user
	session_destroy();

	//Redirect back to the index page
	header('location: index.php');

?>