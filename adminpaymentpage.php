<?php
include "classes/customer.php";
include "classes/menuItem.php";
include "classes/table.php";
include "classes/payment.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
} else {
    $admin = $_SESSION['admin'];
}
?>

<!--back-end work-Retrieve whole order table from db-->
<?php
// Create array of Order objects
$allOrderList = array();

// Create connection
$db = new Database();
$db->createConnection();

$sql = "SELECT * FROM `order`";

$prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

//Execute prepared statement
mysqli_stmt_execute($prepared_stmt);

// Get resultset
$queryResult =  mysqli_stmt_get_result($prepared_stmt)
    or die("<p>Unable to select from database table</p>");

// Close the prepared statement
@mysqli_stmt_close($prepared_stmt);

//store query result into $row variable
$row = mysqli_fetch_row($queryResult);

//loop the $row (depends on the order table rows number)
$item_count = 0;
while ($row) {

    // fetch the record from the server and then store them in an object
    $allOrderList[$item_count] = new Order($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    $row = mysqli_fetch_row($queryResult);
    $item_count += 1;
}
$db->closeConnection();
?>

<!--back-end work-Retrieve certain order from order table db-->
<?php
//default value for first time loading page
$order = new Order("","","","","","","");

//get the input field name and assign in $orderId
if (isset($_GET['orderId'])) {
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

$checkValidNum = mysqli_num_rows($queryResult);

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

<!--back-end work-Retrieve all menu_item list from db-->
<?php

//default value
$orderedItems = array();

//only execute these statement when get the orderId
if (isset($_GET['orderId'])) {
    if(!empty($_GET['orderId'])){

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
    
    //only process to create ordereditem MenuItem object list when there have fetch result (line 81) 
    if ($checkValidNum == 1) {
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
    }
       
    $db->closeConnection();
    }
}
?>

<!--back-end work-Retrieve all menu_item list from db-->
<!--create payment object-->


<!DOCTYPE html>

<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Jun Wee Tan -->
<!-- Date: 29th May 2022 -->
<!-- Validated: #-->

<head>
    <title>Cosykangaroo - Admin Payment</title>
    <meta charset="utf-8">
    <meta name="author" content="Jun Wee Tan">
    <meta name="description" content="Assignment 3">
    <meta name="keywords" content="payment">
    <link rel="icon" href="images/companylogo.png">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <!--navigation bar-->
    <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand"><img src='images/companylogo.png' alt='icon'><span class="company-name"><?php echo $admin ?></span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto mr-md-3">
                    <li class="nav-item"><a href="adminmenupage.php" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="adminorderpage.php" class="nav-link">Orders</a></li>
                    <li class="nav-item"><a href="adminreservationpage.php" class="nav-link">Reservation</a></li>
                    <li class="nav-item active"><a href="adminpaymentpage.php" class="nav-link">Payment</a></li>
                    <li class="nav-item"><a href="adminstatisticpage.php" class="nav-link">Statistic</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!--page body-payment-->
    <h1>Payment</h1>

    <form action="adminpaymentpage.php" id="myForm1" method="get">
            <label style="color:black;" for="fname">Order ID: </label>
            <input type="number" id="fname" name="orderId" placeholder="e.g. 0000">
            <input type="submit" value="Submit" >
            <input type="reset" value="Reset" onclick="location.href = 'adminpaymentpage.php';">
    </form>

    <div class="friendlist-container">
        <table id="menu">
            <?php
                echo "
                    <tr>
                        <td>
                            <p><strong>Order ID: " . $order->getId() . "</p>
                            <p><strong>Order Date: " . $order->getDate() . "</p>                            
                            <p><strong>Order Time: " . $order->getTime() . "</p>
                            <p><strong>Order Amount: ". $order->CalculateTotalPrice($orderedItems)."</p>
                        </td>
                        <td>
                            <a class = 'unfriend' href='admininvoicedetailpage.php?orderId=" . $order->getId() . "'>Show Details</a>
                        </td>
                    </tr>
                ";    
            ?>
            
        </table>
    </div>
    
    <?php
    //prompt the payment form if there is no issue in orderId
        if (isset($_GET['orderId'])) {
            if (!empty($_GET['orderId'])) {  
            echo"
            <form method=\"get\">
            <!--set hidden text box to store GET element (orderID)-->
           
            <!--inline if else-->
            <input type=\"hidden\" name=\"orderId\" value=$orderId>


            <label style=\"color:black;\" for=\"fname\">Amount Paid: $</label>
            <input type=\"number\" id=\"fname\" name=\"amountPaid\" placeholder=\"e.g. 00.00\">

            <input type=\"submit\" value=\"Pay\">
            <input type=\"reset\" value=\"Reset\">
            </form>
            ";
            }
        }
    ?>


    <?php
    //if the amount paid and order Id has no problem
    //prompt the receipt table
    //create payment object and store in database

        if (isset($_GET["amountPaid"]) && isset($_GET['orderId'])) {
            if (!empty($_GET["amountPaid"]) && !empty($_GET['orderId'])) {  //make sure user have input value
                $amountPaid = $_GET["amountPaid"];
                $orderAmount = $order->CalculateTotalPrice($orderedItems);
                
                //if customer payment is sufficient
                if ($amountPaid >= $orderAmount) {
                    $paymentChanges = $amountPaid - $orderAmount;    
                    
                    //create payment object and insert      
                    $paymentObj = PaymentFactory::createPaymentObj($orderId ,$order->getCustId(),date("Y-m-d"), date("h:i:sa"),$orderAmount,$amountPaid,$paymentChanges);
                    $paymentObj->insertPaymenttodb();

                    //output receipt table
                    echo"
                    <h1>Receipt</h1>;
                        <div class=\"friendlist-container\">
                        <table id=\"menu\">
                            <tr>
                                <td>
                                    <p><strong>Order ID : ".$orderId."</p>
                                    <p><strong>Customer ID : ".$paymentObj->getcustomerID()."</p>   
                                    <p><strong>Payment Date :  ".$paymentObj->getpaymentDate()."</p>                      
                                    <p><strong>Payment Time :  ".$paymentObj->getpaymentTime()."</p>
                                    <p><strong>Payment Amount : ".$paymentObj->getpaymentAmount()."</p>
                                    <p><strong>Payment Paid : ".$paymentObj->getpaymentPaid()."</p>                            
                                    <p><strong>Payment Changes : ".$paymentObj->getpaymentChanges()."</p>                            
                                </td>
                                <td><td>
                                <td></td>
                            </tr>
                        </table>
                        <button style=\"color:white;\" onclick=\"window.print()\">Print receipt</button>
                    </div>
                    ";
                }
                //if customer pay no sufficient
                else if($orderAmount > $amountPaid) { 
                    $paymentChanges = $orderAmount - $amountPaid;
                    echo "<script type='text/javascript'>alert('The amount paid is not sufficient');</script>";
                }
            }    
       }
    ?>
    
    
    <!--<table id="menu">-->
        <?php
            // $reverse_order_list = array_reverse($allOrderList);
            // foreach ($reverse_order_list as $value){
            //     echo "
            //         <tr>
            //             <td>
            //                 <p><strong>Order ID: " . $value->getId() . "</p>
            //                 <p><strong>Order Date: " . $value->getDate() . "</p>                            
            //                 <p><strong>Order Time:" . $value->getTime() . "</p>
            //             </td>
            //             <td>
            //                 <p><strong>Order Amount: </p>
            //                 <a class = 'unfriend' href='admininvoicedetailpage.php?orderId=" . $value->getId() . "'>Show Invoice</a>
            //                 </td>
            //         </tr>
            //     ";    
            // }
        ?>
    <!--</table>-->

    <?php include "footer_login.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>