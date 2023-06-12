<?php
include "classes/customer.php";
include "classes/menuItem.php";
include "classes/reservation.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
} else {
    $admin = $_SESSION['admin'];
}

?>

<?php


	// Create connection
		$db = new Database();
		$db->createConnection();
		//get all current reser
		$sql = "SELECT *,DATE_FORMAT(reservation_time, '%H:%i')as time FROM `reservation` WHERE reservation_date>= CURDATE() ORDER BY `reservation_date` ASC";

		$prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

		//Execute prepared statement
		mysqli_stmt_execute($prepared_stmt);

		// Get resultset
		$queryResult =  mysqli_stmt_get_result($prepared_stmt)
			or die("<p>Unable to select from database table</p>");

		// Close the prepared statement
		@mysqli_stmt_close($prepared_stmt);

		$row = mysqli_fetch_row($queryResult);

		$count = 0;
		$reservations = array();
		while ($row) {

			// fetch the record from the server and then store them in an object
			$reservations[$count] = new Reservation($row[0], $row[1], $row[2], $row[5],$row[4]);
			$row = mysqli_fetch_row($queryResult);
			$count += 1;
		}
		
		$sql = "SELECT *,DATE_FORMAT(reservation_time, '%H:%i')as time FROM `reservation` WHERE reservation_date < CURDATE() ORDER BY `reservation_date` ASC";

		$prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);

		//Execute prepared statement
		mysqli_stmt_execute($prepared_stmt);

		// Get resultset
		$queryResult =  mysqli_stmt_get_result($prepared_stmt)
			or die("<p>Unable to select from database table</p>");

		// Close the prepared statement
		@mysqli_stmt_close($prepared_stmt);

		$row = mysqli_fetch_row($queryResult);

		$count = 0;
		$pastReservations= array();
		while ($row) {

			// fetch the record from the server and then store them in an object
			$pastReservations[$count] = new Reservation($row[0], $row[1], $row[2], $row[5],$row[4]);
			$row = mysqli_fetch_row($queryResult);
			$count += 1;
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
    <title>Cosykangaroo - Admin Reservation</title>
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
                    <li class="nav-item"><a href="adminorderpage.php" class="nav-link">Orders</a></li>
                    <li class="nav-item active"><a href="adminreservationpage.php" class="nav-link">Reservation</a></li>
                    <li class="nav-item"><a href="adminpaymentpage.php" class="nav-link">Payment</a></li>
                    <li class="nav-item"><a href="adminstatisticpage.php" class="nav-link">Statistic</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>Current Reservation</h1>

    <div class="friendlist-container">
        <table id="menu">
		  <?php
			if (count($reservations)==0){
				
				echo "<tr><td><p>You did not have any reservations</p></td></tr>";
			}else{
				
				foreach ($reservations as $key => $value) {
					echo '
							<tr>
							<td>
								<p><strong>Reservation ID: ' . $value->getId() . '</strong></p>
								<p>Table: ' . $value->gettableId() . '</p>
							</td>
							<td>
								<p>' . $value->getDate() . '</p>
								<p>' . $value->getTime() . '</p>
							</td>
							</tr>
				';
				}
            }
         ?>
        </table>
    </div>
	
	<h1>Past Reservation</h1>

    <div class="friendlist-container">
        <table id="menu">
		  <?php
            foreach ($pastReservations as $key => $value) {
                echo "<tr>
					<td>
						<p><strong>Reservation ID: " . $value->getId() . "</strong></p>
						<p>CustId: " . $value->getcustId() . "</p>
						<p>Table: " . $value->gettableId() . "</p>
					</td>
					<td>
						<p>" . $value->getDate() . "</p>
						<p>" . $value->getTime() . "</p>
					</td>
            </tr>";
            }
         ?>
        </table>
    </div>
    <?php include "footer_login.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>