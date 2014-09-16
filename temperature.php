<?php

	include "inc/conx.php";

	session_start();

	if (!isset($_SESSION['id'])) {
		require "inc/login_tools.php";
		load();
	}

	if (isset($_GET['date'])) {
		$getDate = $_GET['date'];
		
		$resultGet = mysqli_query($dbc, "SELECT * FROM temperature WHERE `date` = '$getDate'") or die();

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
    						<legend>Results For: <b><?php echo $newGetDate; ?></b></legend>
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

    									while ($rowGet = mysqli_fetch_object($resultGet)) {
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

        					$result = mysqli_query($dbc, "SELECT DISTINCT date FROM temperature ORDER BY date DESC");

        					$checkRow = mysqli_num_rows($result);

        					if ($checkRow !== 0) {
        						while ($row = mysqli_fetch_object($result)) {
        							$id = $row->id;
        							$date = $row->date;

        							$newDate = date("l jS  F Y", strtotime($date));

        							echo '
        								<tr>
        									<td><a href=temperature.php?date=' . $date . ' class="btn btn-primary form-control">' . $newDate  . '</a></td>
        								</tr>
        							';
        						}
        					} else {
        						echo '
        							<tr>
        								<td class="danger">No Items Found</td>
        							</tr>
        						';
        					}

        				?>
        			</tbody>
        		</table>
        	</div>
        </div>

        <?php

        	if (isset($_POST['tempSubmit'])) {
        		if (!empty($_POST['tempName'])) {
        			if (!empty($_POST['temp'])) {
        				$tempName = $_POST['tempName'];
        				$temp = $_POST['temp'];

        				mysqli_query($dbc, "INSERT INTO temperature (appliance, temperature, `date`) VALUES('$tempName', '$temp', now())")or die(mysql_error());
        				header('location: temperature.php');
        			} else {
 	 					echo '<div class="alert alert-danger">Please Fill In All Fields</div>';
        			}
        		} else {
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
                        <form role="form" method="POST">
                            <tr>
                                <td><input type="text" class="form-control" name="tempName" placeholder="Fridge/Freezer..." /></td>
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