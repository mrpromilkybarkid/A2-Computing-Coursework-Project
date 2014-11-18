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
                    <li><p class="navbar-text"><span class="glyphicon glyphicon-user"></span> Logged In As: <b><?php echo ucfirst($_SESSION['name']); ?></b></p></li>
                    <li class="active"><a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <ol class="breadcrumb">
                <li><a href="home.php">Home</a></li>
                <li>Stock List</li>
            </ol>
        </div>

        <?php

            //Check to see if the add item form has been submitted
            if (isset($_POST['itemSubmit'])) {
                //If it has, create a variable to store the item name which has been escaped
                $newItem = mysql_real_escape_string($_POST['itemName']);
                //Also create a variable to store the item quantity which has been escaped
                $newItemQuantity = mysql_real_escape_string($_POST['itemQuantity']);
                //Check to see if the item name field is empty
                if (!empty($_POST['itemName'])) {
                    //Check to see if the item quantity is empty
                    if (!empty($_POST['itemQuantity'])) {
                        //If both are not empty...
                        //Run this query to insert the new data into the table
                        mysqli_query($dbc, "INSERT INTO stock (item, quantity) VALUES('$newItem', '$newItemQuantity')");
                        //Redirect back to the stock page
                        header('location: stock.php');
                    } else {
                        //If the item quantity field is empty, output an error
                        echo '<div class="container"><div class="alert alert-danger">Please Fill In All Fields</div></div>';
                    }
                } else {
                    //If the item naem field is empty, output an error
                    echo '<div class="container"><div class="alert alert-danger">Please Fill In All Fields</div></div>';
                    }
                }

        ?>

        <div class="container">
            <div class="jumbotron">
                <button class="btn btn-success pull-right" data-toggle="modal" data-target="#myModal">
                    Add Item
                </button>
                <button id="showKey" class="btn btn-primary">
                    Show Colour Key
                </button>
                <table id="key" class="table table-striped table-borderer">
                <br />
                <br />
                    <tbody>
                        <tr>
                            <td class="danger">Very Low</td>
                        </tr>
                        <tr>
                            <td class="warning">Mid</td>
                        </tr>
                        <tr>
                            <td class="success">High</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        <tr><td><input type="text" id="search" class="form-control" placeholder="Search..." /></td></tr>
                    </tbody>
                </table>

                <table id="stock" style="margin-top: -20px;" class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            //Set a variable equel to a query to get everything from the stock table
                            //Order by the amount of the item there is
                            $result = mysqli_query($dbc, "SELECT * FROM `stock` ORDER BY `quantity`")or die(mysql_error());

                            //Check how many rows match the query just run
                            $checkNum = mysqli_num_rows($result);

                            //Check to see if there are more than 0 results found
                            if ($checkNum !== 0) {
                                //If yes...
                                //Create the row array to contain all the items grabbed from the table
                                while($row = mysqli_fetch_object($result)) {
                                    //Set the class variable equal to an empty string
                                    $class = '';

                                    //Check to see if the amount of the item is less than or equal to 5
                                    if ($row->quantity <= 5) {
                                        //Set the class to danger
                                        $class = 'danger';
                                    }

                                    //Check to see if the amount of the item is greater than 5
                                    if ($row->quantity > 5) {
                                        //Set the class to warning
                                        $class = 'warning';
                                    }

                                    //Check to see if the amount of the item is greater than 15
                                    if ($row->quantity > 15) {
                                        //Set the class to success
                                        $class = 'success';
                                    }

                                    //Output...
                                    //The name of the item as well as the quantity
                                    //In the quantity table item, set the class to the class variables we defined before
                                    //This will change the background of the table item based on the quantity of the item
                                    //Also output an update button to change the values
                                    //As well as a delete button to delete the values
                                    //These buttons are based off the item id
                                    echo '
                                        <tr>
                                            <td id="stockItem">' . $row->item . '</td>
                                            <td class=' . $class . '>' . $row->quantity . '</td>
                                            <td style="width: 100px;">
                                                <form method="POST" action="edit.php?id=' . $row->id . '" role="form">
                                                    <input type="submit" name="editItem" class="btn btn-primary form-control" value="Update" />
                                                </form>
                                            </td>
                                            <td style="width: 100px;">
                                                <form method="POST" action="delete.php?id=' . $row->id . '" role="form">
                                                    <input type="submit" name="deleteItem" class="btn btn-danger form-control" value="Delete" />
                                                </form>
                                            </td>
                                        </tr>
                                    ';
                                }
                            } else {
                                //If there are 0 rows return
                                //Output an error message to the user
                                echo '
                                    <tr>
                                        <td class="danger">No Items Found</td>
                                        <td class="danger"></td>
                                        <td class="danger"></td>
                                    </tr>
                                    ';
                            }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Item</h4>
              </div>
              <div class="modal-body">
                <table class="table">
                        <tbody>
                            <form role="form" method="POST">
                                <tr>
                                    <td><input type="text" class="form-control" name="itemName" placeholder="Item Name..." /></td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" name="itemQuantity" placeholder="Item Quantity..." /></td>
                                </tr>
                                <tr>
                                    <td><input type="submit" class="form-control btn btn-success" name="itemSubmit" value="Add Item" /></td>
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

        <script type="text/javascript">

            //Hide Key On Page Load
            $(document).ready(function() {
                $("#key").hide();
            });

            //Key Toggle
            $("#showKey").click(function() {
                $("#key").toggle();
            });

            //Search
            $("#search").on("keyup", function() {
                //Set the variable value equal to the search input box
                var value = $(this).val();

                //Run through each table row within the stock table
                $("#stock tr").each(function(index) {
                    //If the search returns more than 0 results
                    if (index !== 0) {
                        //Set the row variable equal to the values within the stock table
                        $row = $(this);

                        //Set the id variable to find anything that matches the row variable in the first table row
                        var id = $row.find("td:first").text();

                        //Check to see if this returns more than 0
                        if (id.indexOf(value) !== 0) {
                            //If it does, fade out all items that do not match the search
                            $row.fadeOut(250);
                        } else {
                            //If not, fade in all items
                            $row.fadeIn(250);
                        }
                    }
                });
            });

        </script>

    </body>
</html>
