<?php

	include 'inc/conx.php';

	if (isset($_GET['appliance'])) {

		$appliance = $_GET['appliance'];

		$check = mysqli_query($dbc, "SELECT * FROM temperature WHERE appliance = '$appliance'");
		$checknum = mysqli_num_rows($check);

		if ($checknum == 0) {
			echo '

				<!doctype html>
				<html lang="en">
			    <head>
			        <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
			        <title>Notcutts Web System</title>
			    </head>
			    <body>

			        <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
			            <div class="container">
			                <div class="navbar-header">
			                    <a href="home.php" class="navbar-brand">Notcutts Web System</a>
			                </div>
			                <ul class="nav navbar-nav navbar-right">
			                    <li><p class="navbar-text"><span class="glyphicon glyphicon-user"></span> Logged In As: <b><?php echo ucfirst($_SESSION["name"]); ?></b></p></li>
			                    <li class="active"><a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
			                    <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
			                </ul>
			            </div>
			        </nav>

			        <div class="container">
			        	<div class="alert alert-danger">
			        		That Appliance Does Not Exist
			        	</div>
			        	<hr />
			        	<a href="temperature.php" class="btn btn-danger">Back</a>
			        </div>

			        </body>
			        </html>
					';
		} else {

?>

<!doctype html>
<html lang="en">
    <head>
    		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
    		<title>Notcutts Web System</title>
    </head>
    			
	<style>

		#placeholder {
			width: 85%;
			height: 450px;
			padding: 20px;
			margin: 0 auto;
		}

	</style>

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
			<a href="temperature.php" class="btn btn-danger">Back</a>
			<hr />
			<h1>Temperatures for <?php echo $appliance; ?></h1>
		</div>

		<div class="container well">
			<div id="placeholder">
			</div>
		</div>

		<?php

			mysql_connect('localhost', 'root', 'mysql') or die("Connection Error");
			mysql_select_db('notcutts') or die("Could Not Find Database");

			$result = mysql_query("SELECT * FROM temperature WHERE appliance = '$appliance' ORDER BY date ASC");

			$count = 0;
			while ($row = mysql_fetch_assoc($result)) {

				$dataset[] = array($count, intval($row['temperature']));
				$dataset2[] = array($count, date("l jS F Y", strtotime($row['date'])));

				$count++;

			}

		?>

		<!--[if lte IE 8]><script type="text/javascript" language="javascript" src="excanvas.min.js"></script><![endif]-->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="flot/jquery.flot.js"></script>
		<script type="text/javascript" language="javascript" src="js/axislabel.js"></script>
		<script type="text/javascript" language="javascript" src="js/flotresize.js"></script>

		<script type="text/javascript">

			var plotdata = [<?php echo $d2 . $d; ?>];

			var data = <?php echo json_encode($dataset); ?>;

    		$.plot($("#placeholder"), [data], {
    			axisLabels: {
    				show: true
    			},
    			xaxes: [{
    				axisLabel: 'Date',
    			}],
    			yaxes: [{
            		position: 'left',
            		axisLabel: 'Temperature (C)',
       	 		}],
    			xaxis: { ticks: <?php echo json_encode($dataset2); ?> },
    			yaxis: { tickDecimals: 0 },
    			series: { lines: { show: true }, points: { show: true } },
    		});

		</script>

		<div class="container">
			<hr />
			<div class="well well-sm">
				Notcutts Web System - FLOT Prototype - 0.1
			</div>
		</div>
	
	</body>
</html>

<?php
		}

	}

?>