	<?php

    include "inc/conx.php";
    //mysql_connect("localhost", "root", "");
    //mysql_select_db("notcutts");

    session_start();

    if (!isset($_SESSION['id'])) {
        require 'inc/login_tools.php';
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

        <nav class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a href="home.php" class="navbar-brand">Notcutts Web System</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li><p class="navbar-text">Logged In As: <b><?php echo ucfirst($_SESSION['name']); ?></b></p></li>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="logout.php">Logout</a></li>
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

            if (isset($_POST['itemSubmit'])) {
                $newItem = $_POST['itemName'];
                $newItemQuantity = $_POST['itemQuantity'];
                if (!empty($_POST['itemName'])) {
                    if (!empty($_POST['itemQuantity'])) {
                        mysqli_query($dbc, "INSERT INTO stock (item, quantity) VALUES('$newItem', '$newItemQuantity')");
                        header('location: stock.php');
                    } else {
                        echo '<div class="alert alert-danger">Please Fill In All Fields</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">Please Fill In All Fields</div>';
                    }
                }

        ?>

        <div class="container">
            <div class="jumbotron">
                <button class="btn btn-success pull-right" data-toggle="modal" data-target="#myModal">
                    Add Item
                </button>   
                <button id="showKey" class="btn btn-info">
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
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $result = mysqli_query($dbc, "SELECT * FROM `stock` ORDER BY `quantity`")or die(mysql_error());
                            
                            $checkNum = mysqli_num_rows($result);

                            if ($checkNum !== 0) {
                                while($row = mysqli_fetch_object($result)) {
                                    $class = '';

                                    if ($row->quantity <= 5) {
                                        $class = 'danger';
                                    }

                                    if ($row->quantity > 5) {
                                        $class = 'warning';
                                    }

                                    if ($row->quantity > 15) {
                                        $class = 'success';
                                    }

                                    echo '
                                        <tr>
                                            <td>' . $row->id . '</td>
                                            <td>' . $row->item . '</td>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Javascript Includes -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>

        <script type="text/javascript">

            $(document).ready(function() {
                $("#key").hide();
            });

            $("#showKey").click(function() {
                $("#key").toggle();
            });

        </script>

    </body>
</html>
