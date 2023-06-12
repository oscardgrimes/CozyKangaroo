<?php
include "classes/menu.php";
include "classes/table.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
} else {
    $admin = $_SESSION['admin'];
}

?>

<?php
$menuObject = new Menu();

// Create array of Menu objects
$menu = array();

// Create connection
$db = new Database();
$db->createConnection();

$sql = "SELECT * FROM `menu_item`";

$prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

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
    $menu[$item_count] =
        new MenuItem($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    $row = mysqli_fetch_row($queryResult);
    $item_count += 1;
}

$db->closeConnection();
?>

<?php
$update = false;
$updateItem = null;
$btn_clicked = false;
$updateValidate = false;

if (!isset($_GET['updateItemId'])) {
    $update = false;
} else {
    $update = true;
    foreach ($menu as $key => $value) {
        if (strcmp($value->getId(), $_GET['updateItemId']) == 0) {
            $updateItem = $value;
        }
    }
}

if (!empty($_POST['update']) && !empty($_POST['id'])) {
    $btn_clicked = true;

    foreach ($menu as $key => $value) {
        if (strcmp($value->getId(), $_POST['id']) == 0) {
            $updateItem = $value;
        }
    }

    if (!empty($_POST['name']) && !empty($_POST['desc']) && !empty($_POST['price']) && !empty($_POST['cost']) && !empty($_POST['time'])) {
        $updateValidate = true;
        $updateItem->setName($_POST['name']);
        $updateItem->setDescription($_POST['desc']);
        $updateItem->setPrice($_POST['price']);
        $updateItem->setCost($_POST['cost']);
        $updateItem->setTime($_POST['time']);
    } else {
        echo "<script type='text/javascript'>alert('All inputs must be given');</script>";
    }
    if ($updateValidate) {
        if ($menuObject->UpdateItem($updateItem)) {
            echo "<script type='text/javascript'>alert('Update Success');</script>";
            $updateItem = null;
        } else {
            echo "<script type='text/javascript'>alert('Update Failed');</script>";
        }
    }
}

?>

<?php

$delete = isset($_GET['deleteItemId']);

if ($delete) {
    if ($menuObject->DeleteItem($_GET['deleteItemId'])) {
        echo "<script type='text/javascript'>alert('Item Deleted Successfully');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Delete Failed');</script>";
    }
}

?>

<?php
$add = false;
$addItem = null;
$addValidate = false;
$addbtn_clicked = false;

$add = isset($_GET['AddItem']);

if (!empty($_POST['add'])) {
    $addbtn_clicked = true;
    $add = true;

    if (!empty($_POST['newname']) && !empty($_POST['newdesc']) && !empty($_POST['newprice']) && !empty($_POST['newcost']) && !empty($_POST['newtime'])) {
        $addValidate = true;
        $addItem = new MenuItem(null, $_POST['newname'], $_POST['newdesc'], $_POST['newprice'], $_POST['newcost'], $_POST['newtime']);
    } else {
        echo "<script type='text/javascript'>alert('All inputs must be given');</script>";
    }
    if ($addValidate) {
        if ($menuObject->AddItem($addItem)) {
            echo "<script type='text/javascript'>alert('New Item Added Successfully');</script>";
            $addItem = null;
            $add = false;
        } else {
            echo "<script type='text/javascript'>alert('Add Failed');</script>";
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Adrian Sim Huan Tze -->
<!-- Date: 13th May 2022 -->
<!-- Validated: #-->

<head>
    <title>Cosykangaroo - Admin Menu</title>
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
                    <li class="nav-item active"><a href="adminmenupage.php" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="adminorderpage.php" class="nav-link">Orders</a></li>
                    <li class="nav-item"><a href="adminreservationpage.php" class="nav-link">Reservation</a></li>
                    <li class="nav-item"><a href="adminpaymentpage.php" class="nav-link">Payment</a></li>
                    <li class="nav-item"><a href="adminstatisticpage.php" class="nav-link">Statistic</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>Menu</h1>

    <div class="friendlist-container">
        <table id="menu">
            <tr>
                <td colspan="2"><a class='unfriend' href='adminmenupage.php?AddItem=true'>Add New Item</a></td>
            </tr>
            <?php
            foreach ($menu as $key => $value) {
                echo "<tr>
                <td>
                    <p>" . $value->getId() . " - <strong>" . $value->getName() . "</strong></p>
                    <p>" . $value->getDescription() . "</p>
                    <p>Time: " . $value->getTime() . "</p>
                    <p>Cost: " . $value->getCost() . "$</p>
                    <p>" . $value->getPrice() . " $</p>
                </td>  
                <td>
                    <a class = 'unfriend' href='adminmenupage.php?updateItemId=" . $value->getId() . "'>Update</a></br></br>
                    <a class = 'unfriend' href='adminmenupage.php?deleteItemId=" . $value->getId() . "'>Delete</a>
                </td>          
            </tr>";
            }
            ?>
        </table>
    </div>

    <!-- <form method="post" action="menupage.php" novalidate="novalidate">
        <fieldset>
            <legend>New Item</legend>

            <label for="name">Name :</label>
            <input type="text" name="name" id="name" value="Take In" /><br />

            <label for="desc">Description:</label>
            <input type="text" name="desc" id="desc" value="Take Out" /><br />

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" /><br />

            <label for="cost">Cost:</label>
            <input type="number" name="cost" id="cost" /><br />

            <label for="time">Time:</label>
            <input type="number" name="time" id="time" />

            </br>
        </fieldset>

        <input type="submit" value="Add" name="login" />
    </form> -->

    <?php
    if ($add) {
        echo '<form method="post" action="adminmenupage.php" novalidate="novalidate">
        <fieldset>
            <legend>New Item</legend>

            <label for="name">Name :</label>
            <input type="text" name="newname" id="name" value="" /><br />

            <label for="desc">Description:</label>
            <input type="text" name="newdesc" id="desc" value="" /><br />

            <label for="price">Price:</label>
            <input type="number" name="newprice" id="price" value="" /><br />

            <label for="cost">Cost:</label>
            <input type="number" name="newcost" id="cost" value="" /><br />

            <label for="time">Time:</label>
            <input type="number" name="newtime" id="time" value="" />

            ';

        if ($add && !$addValidate && $addbtn_clicked) {
            echo "<p>Each Input cannot be empty</p>";
        }

        echo '

            </br>
        </fieldset>

        <input type="submit" value="Add" name="add" />
    </form>';
    }
    ?>

    <?php
    if ($updateItem != null) {
        echo '<form method="post" action="adminmenupage.php" novalidate="novalidate">
        <fieldset>
            <legend>' . $updateItem->getId() . '</legend>
            <input type="hidden" name="id" id="id" value="' . $updateItem->getId() . '" /><br />

            <label for="name">Name :</label>
            <input type="text" name="name" id="name" value="' . $updateItem->getName() . '" /><br />

            <label for="desc">Description:</label>
            <input type="text" name="desc" id="desc" value="' . $updateItem->getDescription() . '" /><br />

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="' . $updateItem->getPrice() . '" /><br />

            <label for="cost">Cost:</label>
            <input type="number" name="cost" id="cost" value="' . $updateItem->getCost() . '" /><br />

            <label for="time">Time:</label>
            <input type="number" name="time" id="time" value="' . $updateItem->getTime() . '" />

            ';

        if (!$updateValidate && $btn_clicked) {
            echo "<p>Each Input cannot be empty</p>";
        }

        echo '

            </br>
        </fieldset>

        <input type="submit" value="Update" name="update" />
    </form>';
    }
    ?>
    <?php include "footer_login.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>