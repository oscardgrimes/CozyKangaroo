<?php
include "classes/customer.php";
include "classes/menuItem.php";
include "classes/table.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
} else {
    $admin = ($_SESSION['admin']);
}

?>

<?php

if (!isset($_GET['orderId'])) {
    header("Location: orderpage.php");
} else {
    $orderId = $_GET['orderId'];
}

// Create connection
$db = new Database();
$db->createConnection();

$sql = "SELECT * FROM `order` WHERE order_id = ?";

$prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

//Bind input variables to prepared statement
@mysqli_stmt_bind_param($prepared_stmt, 's', $orderId);

//Execute prepared statement
mysqli_stmt_execute($prepared_stmt);

// Get resultset
$queryResult =  mysqli_stmt_get_result($prepared_stmt)
    or die("<p>Unable to select from database table</p>");

// Close the prepared statement
@mysqli_stmt_close($prepared_stmt);

$row = mysqli_fetch_row($queryResult);

$item_count = 0;
while ($row) {

    // fetch the order record from the server and then store them in an object
    $order = new Order($row[0], $row[1], $row[2], $row[3], $row[4], $row[6]);

    // retrieve the ordered items and store them in items array
    $order->setItems(explode(",", $row[5]));
    $row = mysqli_fetch_row($queryResult);
    $item_count += 1;
}

$db->closeConnection();
?>

<?php
// Create array of orderedItems objects
$orderedItems = array();

// Create connection
$db = new Database();
$db->createConnection();

$sql = "SELECT * FROM menu_item";

$prepared_stmt = mysqli_prepare($db->getConnection(), $sql);

//Execute prepared statement
mysqli_stmt_execute($prepared_stmt);

// Get resultset
$queryResult =  mysqli_stmt_get_result($prepared_stmt)
    or die("<p>Unable to select from database table</p>");

// Close the prepared statement
@mysqli_stmt_close($prepared_stmt);

$row = mysqli_fetch_row($queryResult);

$item_count = 0;
while ($row) {
    for ($i = 0; $i < count($order->getItems()); $i++) {
        if ($order->getItems()[$i] == $row[0]) {
            // fetch the record from the server and then store them in an object
            $orderedItems[$item_count] = new MenuItem($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
            $item_count += 1;
        }
    }
    $row = mysqli_fetch_row($queryResult);
}

$db->closeConnection();
?>

<?php
// Create connection
$db = new Database();
$db->createConnection();

$sql = "SELECT * FROM `customer` WHERE cust_id = ?";

$prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

//Bind input variables to prepared statement
@mysqli_stmt_bind_param($prepared_stmt, 's', $order->getCustId());

//Execute prepared statement
mysqli_stmt_execute($prepared_stmt);

// Get resultset
$queryResult =  mysqli_stmt_get_result($prepared_stmt)
    or die("<p>Unable to select from database table</p>");

// Close the prepared statement
@mysqli_stmt_close($prepared_stmt);

$row = mysqli_fetch_row($queryResult);

$item_count = 0;
while ($row) {

    // fetch the order record from the server and then store them in an object
    $customer = new Customer($row[3], $row[1], $row[2], $row[0]);
    $row = mysqli_fetch_row($queryResult);
    $item_count += 1;
}

$db->closeConnection();
?>

<!DOCTYPE html>

<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Adrian Sim Huan Tze -->
<!-- Date: 13th May 2022 -->
<!-- Validated: #-->

<head>
    <title>Cosykangaroo - Order Details</title>
    <meta charset="utf-8">
    <meta name="author" content="Adrian Sim Huan Tze">
    <meta name="description" content="Assignment 3">
    <meta name="keywords" content="food, order, reservation">
    <link rel="icon" href="images/companylogo.png">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand"><img src='images/companylogo.png' alt='icon'><span class="company-name"><?php echo $admin ?></span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto mr-md-3">
                    <li class="nav-item"><a href="adminmenupage.php" class="nav-link">Menu</a></li>
                    <li class="nav-item active"><a href="adminorderpage.php" class="nav-link">Orders</a></li>
                    <li class="nav-item"><a href="adminreservationpage.php" class="nav-link">Reservation</a></li>
                    <li class="nav-item"><a href="adminpaymentpage.php" class="nav-link">Payment</a></li>
                    <li class="nav-item"><a href="adminstatisticpage.php" class="nav-link">Statistic</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>Order Detail</h1>

    <div class="friendlist-container">
        <table id="menu">
            <thead>
                <tr>
                    <td><strong>Order Id:</strong> </td>
                    <td><?php echo $order->getId() ?></td>
                    <td><strong>Order Date:</strong> </td>
                    <td><?php echo $order->getDate() ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Customer Name:</strong> </td>
                    <td><?php echo $customer->getName() ?></td>
                    <td><strong>Order Time:</strong> </td>
                    <td><?php echo $order->getTime() ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Order Type:</strong> </td>
                    <td><?php echo $order->getType() ?></td>
                    <?php
                    if ($order->getTableId() != null) {
                        echo "<td><strong>Table No:</strong> " . $order->getTableId() . "</td>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orderedItems as $key => $value) {
                    echo "<tr>
                <td colspan='4'>
                    <p><strong>" . $value->getName() . "</strong></p>
                    <p>" . $value->getDescription() . "</p>
                </td>  
                <td colspan='2'>
                    <p>" . $value->getPrice() . " $</p>
                </td>          
            </tr>";
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td colspan="4">
                        <strong>Total Estimated Waiting Time :</strong>
                        <?php
                        echo $order->CalculateTotalOrderTime($orderedItems)
                        ?>
                        mins
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
    <?php include "footer_login.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>