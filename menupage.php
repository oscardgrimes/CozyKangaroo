<?php
include "classes/menuItem.php";
include "classes/customer.php";
include "classes/table.php";

session_start();
if (!isset($_SESSION['profile'])) {
    header("Location: index.php");
} else {
    $profile = unserialize($_SESSION['profile']);
}

if (!isset($_SESSION['cart'])) {
    // Create Cart to store MenuItem objects
    $cart = array();
    $_SESSION['cart'] = implode(",", $cart);
} else {
    $cart = explode(",", $_SESSION['cart']);
}

date_default_timezone_set("Australia/Melbourne");

?>

<!-- retrieve all tables records from database using database object -->
<?php
// Create array of Table objects
$tables = array();
$tablesId = array();

// Create connection
$db = new Database();
$db->createConnection();

$sql = "SELECT * FROM `table`";

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

    // fetch the record from the server and then store them in an object
    $tables[$item_count] = new Table($row[0], $row[2], $row[1]);
    $tablesId[$item_count] = $row[0];
    $row = mysqli_fetch_row($queryResult);
    $item_count += 1;
}

$db->closeConnection();
?>

<!-- retrieve all menu items records from database using database object -->
<?php
// Create an array to store Items as Cart
$cart_array = array();

// Create array of MenuItem objects
$menuItems = array();

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

    // fetch the record from the server and then store them in an object
    $menuItems[$item_count] = new MenuItem($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    $row = mysqli_fetch_row($queryResult);
    $item_count += 1;
}

$db->closeConnection();
?>

<?php
// check if any item is chosen to be added to the cart
if (isset($_GET['newCartItemName'])) {
    // get the chosen item
    $newItemName = $_GET['newCartItemName'];

    // search for the correct item based on the name
    foreach ($menuItems as $key => $value) {
        if (
            strcmp($value->getName(), $newItemName) == 0
        ) {
            // add new item into the cart
            array_push($cart, $newItemName);
        }
    }

    $cart_array = $cart;
    // set the session again to be used for the next chosen item
    $_SESSION['cart'] = implode(",", $cart);
}

// check if any item is chosen to be deleted from the cart
if (isset($_GET['deleteCartItemName'])) {
    // get the chosen item
    $deleteItemName = $_GET['deleteCartItemName'];

    // search for the correct item based on the name
    $index = 0;
    foreach ($cart as $key => $value) {
        if (
            strcmp($value, $deleteItemName) == 0
        ) {
            // delete item from the cart
            array_splice($cart, $index, 1);
        }
        $index++;
    }

    $cart_array = $cart;
    // set the session again to be used for the next chosen item
    $_SESSION['cart'] = implode(",", $cart);
}

?>

<?php
$btnclicked = false;
$tableNo = null;
$ordertype = null;
if (!empty($_POST["login"])) {
    $btnclicked = true;
    $cart_array = $cart;
    // set the session again to be used for the next chosen item
    $_SESSION['cart'] = implode(",", $cart);

    // check if the cart is empty before ordering
    if (count($cart_array) == 1) {
        $cartEmpty = true;
    } else {
        $cartEmpty = false;
    }

    if (!$cartEmpty) {
        if (!empty($_POST['ordertype'])) {
            $ordertype = $_POST['ordertype'];
            if ($ordertype == 'Take In') {
                if (!empty($_POST['tableNo'])) {
                    $tableNo = $_POST['tableNo'];
                    if (in_array($tableNo, $tablesId, false)) {
                        $newOrder = new Order(null, $tableNo, $ordertype, date("Y-m-d"), date("h:i:sa"), $profile->getId());
                        $orderedItems = array();
                        foreach ($cart_array as $key => $value) {
                            foreach ($menuItems as $key => $value2) {
                                if (strcmp($value2->getName(), $value) == 0) {
                                    array_push($orderedItems, $value2->getId());
                                }
                            }
                        }
                        $newOrder->setItems($orderedItems);

                        // clear the cart once the order is successful
                        array_splice($cart, 1, (count($cart) - 1));
                        $cart_array = $cart;
                        // set the session again to be used for the next chosen item
                        $_SESSION['cart'] = implode(",", $cart);

                        if ($profile->AddOrder($newOrder)) {
                            header("Location: orderpage.php");
                        }
                    } else {
                        $tableNo = null;
                    }
                }
            } else {
                $newOrder = new Order(null, $tableNo, $ordertype, date("Y-m-d"), date("h:i:sa"), $profile->getId());
                $orderedItems = array();
                foreach ($cart_array as $key => $value) {
                    foreach ($menuItems as $key => $value2) {
                        if (strcmp($value2->getName(), $value) == 0) {
                            array_push($orderedItems, $value2->getId());
                        }
                    }
                }
                $newOrder->setItems($orderedItems);

                // clear the cart once the order is successful
                array_splice($cart, 1, (count($cart) - 1));
                $cart_array = $cart;
                // set the session again to be used for the next chosen item
                $_SESSION['cart'] = implode(",", $cart);

                if ($profile->AddOrder($newOrder)) {
                    header("Location: orderpage.php");
                }
            }
        }
    }
} else {
    $btnclicked = false;
}
?>

<!DOCTYPE html>

<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Adrian Sim Huan Tze -->
<!-- Date: 13th May 2022 -->
<!-- Validated: #-->

<head>
    <title>Cosykangaroo - Menu</title>
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
            <a class="navbar-brand"><img src='images/companylogo.png' alt='icon'><span class="company-name"><?php echo $profile->getName() ?></span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto mr-md-3">
                    <li class="nav-item active"><a href="" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="orderpage.php" class="nav-link">Orders</a></li>
                    <li class="nav-item"><a href="reservationpage.php" class="nav-link">Reservation</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>Menu</h1>

    <div class="friendlist-container">
        <table id="menu">
            <?php
            foreach ($menuItems as $key => $value) {
                echo "<tr>
                <td>
                    <p><strong>" . $value->getName() . "</strong></p>
                    <p>" . $value->getDescription() . "</p>
                </td>  
                <td>
                    <p>" . $value->getPrice() . " $</p>
                    <a class = 'unfriend' href='menupage.php?newCartItemName=" . $value->getName() . "'>Add to Cart</a>
                </td>          
            </tr>";
            }
            ?>
        </table>
    </div>

    <h2>Cart</h2>

    <div class="friendlist-container">
        <table id="menu">
            <?php
            echo "<tr>
                        <td>
                            Name of Item
                        </td>
                        <td>
                            Price
                        </td>
                    </tr>";
            $cartTotal = 0;
            foreach ($cart_array as $key => $value) {
                foreach ($menuItems as $key => $value2) {
                    if (strcmp($value2->getName(), $value) == 0) {
                        $cartTotal += ($value2->getPrice());
                        echo "<tr>
                                <td>
                                    <p><strong>" . $value2->getName() . "</strong></p>
                                    <p>" . $value2->getDescription() . "</p>
                                </td>  
                                <td>
                                    <p>" . $value2->getPrice() . " $</p>
                                    <a class = 'unfriend' href='menupage.php?deleteCartItemName=" . $value2->getName() . "'>Delete</a>
                                </td>          
                            </tr>";
                    }
                }
            }
            echo "<tr>
                        <td>
                            Total
                        </td>
                        <td>
                            " . $cartTotal . " $
                        </td>
                    </tr>";
            ?>
        </table>

        <form method="post" action="menupage.php" novalidate="novalidate">
            <fieldset>
                <legend>Checkout</legend>

                <label for="takein">Take In:</label>
                <input type="radio" name="ordertype" id="takein" value="Take In" selected="selected" />

                <label for="takeout">Take Out:</label>
                <input type="radio" name="ordertype" id="takeout" value="Take Out" /> </br>

                <?php
                if ($btnclicked && $cartEmpty) {
                    echo "<script type='text/javascript'>alert('Cart is empty');</script>";
                }
                ?>

                <?php if ($btnclicked && !$cartEmpty && $ordertype == null) {
                    echo "<script type='text/javascript'>alert('Order Type must be specified');</script>";
                } ?>

                <label for="tableNo">Table No:</label> </br>
                <input type="number" name="tableNo" id="tableNo" placeholder="Enter your Table number" required="required" />

                <?php if ($btnclicked && !$cartEmpty && $ordertype == 'Take In' && $tableNo == null) {
                    echo "<script type='text/javascript'>alert('Valid Table Number must be given');</script>";
                } ?>

                </br>
            </fieldset>

            <input type="submit" value="Order" name="login" />
        </form>
    </div>
    <?php include "footer_login.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>