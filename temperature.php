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
    //If no date can be retrieved or does not appear in the address bar
    //E.g. /temperature.php
    //Compared to /temperature.php?date=date
	} else {

?>

<!doctype html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css">
		<title>Notcutts Web System</title>
	</head>
	<body>
		
		<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a href="home.php" class="navbar-brand">Notcutts Web System</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li><p class="navbar-text"><span class="glyphicon glyphicon-user"></span> Logged In As: <b><?php echo ucfirst($_SESSION['name']); ?></b></p></li>
                    <li class="active"><a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
                </ul>
            </div>
        </nav>

		<div class="container">
            <ol class="breadcrumb">
                <li><a href="home.php">Home</a></li>
                <li>Temperature Sheets</li>
            </ol>
        </div>

        <div class="container">
        	<div class="jumbotron">
        		<button class="btn btn-success pull-right" data-toggle="modal" data-target="#myModal">
                    Add New Sheet
                </button>  
        		<table class="table tabled-strieped tabled-bordered">
        			<thead>
        				<tr>
        					<th><p>Temperature Sheets - <b>(Newest First)</b></p></th>
        				</tr>
        			</thead>
        			<tbody>

        				<?php

                            //Declare a query as  variable
                            //This query SELECTS a DISTINCT date from the temperature table
                            //This means if there is more than 1 result with the same date, it will only display the date once
                            //The results are ordered by date in DESC order
        					$result = mysqli_query($dbc, "SELECT DISTINCT date FROM temperature ORDER BY date DESC");

                            //Use the query just defined and check how many rows are being returned by the query
        					$checkRow = mysqli_num_rows($result);

                            //Check to see if the query returns more than 0 results
        					if ($checkRow !== 0) {
                                //If yes, get all objetcs using the query and asign them to the array, row
        						while ($row = mysqli_fetch_object($result)) {
                                    //Define the id from the table as a variable
        							$id = $row->id;
                                    //Define the date from the table as a variable
        							$date = $row->date;

                                    //Re-formate the date to make it more user friendly
        							$newDate = date("l jS  F Y", strtotime($date));

                                    //Output screen-width buttons with each invididual date on
                                    //When each button is clicked it will add the date into the address bar
        							echo '
        								<tr>
        									<td><a href=temperature.php?date=' . $date . ' class="btn btn-primary form-control">' . $newDate  . '</a></td>
        								</tr>
        							';
        						}
        					} else {
                                //If there are 0 results returned, output an error to the user
        						echo '
        							<tr>
        								<td class="danger">No Items Found</td>
        							</tr>
        						';
        					}

        				?>

        			</tbody>
        		</table>
                <hr />
                <table>
                    <thead>
                        <tr>
                            <th><p>Graphs</p></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="col-md-6">
                            <select name="applianceGraph" class="form-control" onchange="location = this.options[this.selectedIndex].value;">
                                <option value="" disabled selected>Select your option</option>

                <?php

                    //Define the query as a variable
                    //This query SELECTS a DISTINCT appliance from the temperature table
                    //This means if there are multiple rows with the same appliance, it will only output the appliance name once to the user
                    $result1 = mysqli_query($dbc, "SELECT DISTINCT appliance FROM temperature");

                    //Check how many rows are turned by the query and asign it to a variable
                    $checkRow1 = mysqli_num_rows($result1);
                    //Define a basic count integer variable and set it to 1
                    $count = 1;

                    //As long as the count variable is less than or equal to the amount of objects returned by the query
                    //Loop...
                    while($count <= $checkRow1) {
                        //Output each appliance as a selection option within the select tabs
                        //Because of some JS added in the select tags, when an item is selected from the select, the page will load the link set as the value in the option
                        echo '
                            <option value="temperature_flot.php?appliance=Fridge ' . $count . '">Fridge ' . $count . '</option>
                        ';

                        //Increment the count variable
                        $count++;
                    }

                ?>

                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <?php

            //Check to see if the submit button within the form on the modal has been pressed
        	if (isset($_POST['tempSubmit'])) {
                //Check to make sure the appliance input field is not empty
        		if (!empty($_POST['appliance'])) {
                    //Check to make sure the temperature input field is not empty
        			if (!empty($_POST['temp'])) {
                        //Declare the inputted appliance as a variable
        				$tempName = $_POST['appliance'];
                        //Declare the inputted temperature as a variable
        				$temp = $_POST['temp'];

                        //Run a query that inserts the inputted data into the table
                        //If the query fails, it returns a MYSQL ERROR
        				mysqli_query($dbc, "INSERT INTO temperature (appliance, temperature, `date`) VALUES('$tempName', '$temp', now())")or die(mysql_error());
        				//Redirect back to temperature.php if all is successful
                        header('location: temperature.php');
                    //If the temperature field is empty
        			} else {
                        //Output an error
 	 					echo '<div class="alert alert-danger">Please Fill In All Fields</div>';
        			}
                    //If the appliance input field is empty
        		} else {
                    //Output an error
        			echo '<div class="alert alert-danger">Please Fill In All Fields</div>';
        		}
        	}

        ?>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Item To Sheet</h4>
              </div>
              <div class="modal-body">
                <table class="table">
                    <tbody>
                        <form role="form" method="POST" id="temperature">
                            <tr>
                                <td>
                                    <select name="appliance" class="form-control" form="temperature">
                                        <option value="Fridge 1">Fridge 1</option>
                                        <option value="Fridge 2">Fridge 2</option>
                                        <option value="Fridge 3">Fridge 3</option>
                                        <option value="Fridge 4">Fridge 4</option>
                                        <option value="Fridge 5">Fridge 5</option>
                                        <option value="Fridge 6">Fridge 6</option>
                                        <option value="Fridge 7">Fridge 7</option>
                                        <option value="Fridge 8">Fridge 8</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="temp" placeholder="Temperature (C)..." /></td>
                            </tr>
                            <tr>
                               	<td><input type="submit" class="form-control btn btn-success" name="tempSubmit" value="Add To Sheet" /></td>
                            </tr>
                        </form>
                    </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

		<!-- Javascript Includes -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>

	</body>
</html>

<?php

	}

?>	