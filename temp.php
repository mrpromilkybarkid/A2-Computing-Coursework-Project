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

?>
<!doctype html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
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
                <br />
                <br />
                <form method="POST">
                    Choose a Date: <input type="text" id="datepicker" class="form-control" name="datepicker" />
                    <br />
                    <input type="submit" class="btn btn-primary form-control" name="submitDate" value="View Data" />
                </form>
                <hr />
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
            </div>
        </div>

        <?php

            if (isset($_POST['submitDate'])) {
                $date = $_POST['datepicker'];

                $date = date('Y-m-d', strtotime($date));

                echo $date;

                header("location: temperature.php?date=" . $date);
            }

        ?>
        </select>

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
                        header('location: temp.php');
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
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

        <script>

            $("#datepicker").datepicker();

        </script>
    </body>
</html>
