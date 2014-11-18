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

    //Get the date from the address bar
	if (isset($_GET['date'])) {
        //Asign the date retrieved to the variable getDate
		$getDate = $_GET['date'];

        //Check to see if the date has automatically assigned itself to the 1st of January 1970
        if ($getDate === '1970-01-01') {
            //If it has, echo an error message out to the user
            echo '
                <head>
                    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
                </head>
                <body>
                <br />
                <div class="container">
                    <div class="well">
                        <div class="alert alert-danger">
                            <p>
                                That Is not a valid date <br />
                                Click <b><a href="temp.php">here</a></b> to return
                            </p>
                        </div>
                    </div>
                </div>
                </body>
            ';
        } else {
        //If Not, continue
		
        //Asign a query to the variable resultGet
        //The query gets all results from the temperature table where the data matches the date retrieved from the address bar
		$resultGet = mysqli_query($dbc, "SELECT * FROM temperature WHERE `date` = '$getDate'") or die();

        //After the query has been declared, re-format the date to make it more user friendly.
        //The date displays in the following format - Friday 10th October 2014...
		$newGetDate = date("l jS F Y", strtotime($getDate));

?>

            <!doctype html>
    		<html lang="en">
    			<head>
    				<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
    				<title>Notcutts Web System</title>
    			</head>
    			<body>
    				
    				<div class="container">
    					<div class="jumbotron" style="margin-top: 20px;">
    						<legend>Results For: <b>
                            <?php 
                                //Output the re-formatted date
                                echo $newGetDate; 
                            ?>
                            </b></legend>
    						<table class="table table-bordered">
    							<thead>
    								<tr>
    									<th>ID</th>
    									<th>Fridge/Freezer</th>
    									<th>Temperature</th>
    								</tr>
    							</thead>
    							<tbody>
    								<?php

                                        //Get all objects using the query we previously defined 
    									while ($rowGet = mysqli_fetch_object($resultGet)) {
                                            //Output all the relevant information within the table
                                            //Output the temperature ID to the user
                                            //Output the appliance the temperature has been taken from
                                            //Output the temperature
    										echo '
    											<tr>
    												<td>' . $rowGet->id . '</td>
    												<td>' . $rowGet->appliance . '</td>
    												<td>' . $rowGet->temperature . ' (C)</td>
    											</tr>
    										';
    									}

    								?>
    							</tbody>
    						</table>
    						<a href="temperature.php" class="btn btn-danger">Back</a>
    					</div>
    				</div>

    				<!-- Javascript Includes -->
			        <script src="js/jquery.js"></script>
			        <script src="js/bootstrap.js"></script>

    			</body>
    		</html>

<?php
    }
    //If no date can be retrieved or does not appear in the address bar
    //E.g. /temperature.php
    //Compared to /temperature.php?date=date
	} else {

        header("location: temp.php");
?>

		<!-- Javascript Includes -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>

	</body>
</html>

<?php
	}

?>	