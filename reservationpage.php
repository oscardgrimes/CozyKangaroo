<?php
include "classes/customer.php";
include "classes/reservation.php";

session_start();
if (!isset($_SESSION['profile'])) {
    header("Location: index.php");
} else {
    $profile = unserialize($_SESSION['profile']);
}

?>
<?php

//create reservation
if(isset($_GET['tableId'])){
	$tableId = $_GET['tableId'];
	$custId = $profile->getId();
	$date = $_GET['date'];
	$time = $_GET['time'];
	$reservation = new Reservation("?",$custId,$date,$time,$tableId);
	$reservation->createReservation($custId,$date,$time,$tableId);
}

//cancel reservation
if(isset($_GET['cancel'])){
	$id = $_GET['cancel'];
	$reservation = new Reservation("?","?","?","?","?");
	$reservation->cancelResvation($id);
}

?>
<!DOCTYPE html>

<html lang="en">
<!-- Description: Assignment 3 -->
<!-- Author: Jianlin Yu -->
<!-- Date: 26th May 2022 -->
<!-- Validated: #-->

<head>
    <title>Cosykangaroo - Reservation</title>
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
                    <li class="nav-item"><a href="menupage.php" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="orderpage.php" class="nav-link">Orders</a></li>
                    <li class="nav-item active"><a href="" class="nav-link">Reservation</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
	
	<h1>Create Reservation</h1>
    <div class="friendlist-container">
		<?php
			//get all aviable tables
			if(isset($_POST['Confirm'])){
				$date = $_POST['date'];
				$time = $_POST['time'];
				
				$reservation = new Reservation("1","2","3","4","5");
				
				$tables = $reservation->getAviableTable($date,$time);
				
				if($tables =="null"){
					
					echo ' <table id="menu"><tr><td><p>All tables have be booked during the select time</p></td>
							<td><a class = "unfriend" href="reservationpage.php">Return</a></td></tr>
							</table>';
				}else{
					foreach ($tables as $key => $value) {
					echo "
						<table id='menu'>
						<tr>
						<td>
						<p><strong>Table Number: " . $value->getTableNum() . "</strong></p>
						</td>
						<td>
						<p>Number of seats: " . $value->getTableSeats() . "</p>
						</td>
						<td>
						<a class = 'unfriend' href='reservationpage.php?date=$date&time=$time&tableId=".$value->getTableNum()."'>Book Table</a>
						</td>
						</tr>
						<table>
						";
					}
				}
				
				
				?>
		<?php
			}else{
		?>
        <table id="menu">
			
            <form action="reservationpage.php" method="post">
				<fieldset>
					<td>
					<legend>Select date and time</legend>
					
					
					<label for="date">Booking date:</label>
					<input type="date" name="date" min="".date('d/m/y') id="date" />
					
					
					</br>
					
					
					<label for="time">Booking time:</label>
					<select name="time" id="time">
						<option value="10:00">10:00am</option>
						<option value="10:30">10:30am</option>
						<option value="11:00">11:00am</option>
						<option value="11:30">11:30am</option>
						<option value="12:00">12:00pm</option>
						<option value="12:30">12:30pm</option>
						<option value="13:30">01:00pm</option>
						<option value="13:00">01:30pm</option>
						<option value="14:00">02:00pm</option>
						<option value="14:30">02:30am</option>
						<option value="15:00">03:00pm</option>
						<option value="15:30">03:30pm</option>
						<option value="16:00">04:00pm</option>
						<option value="16:30">04:30pm</option>
						<option value="17:00">05:00pm</option>
						<option value="17:30">05:30pm</option>
						<option value="18:00">06:00pm</option>
						<option value="18:30">06:30pm</option>
						<option value="19:00">07:00pm</option>
						<option value="19:30">07:30pm</option>
						<option value="20:00">08:00pm</option>
						<option value="20:30">08:30pm</option>
						<option value="21:00">09:00pm</option>
						<option value="22:30">09:30pm</option>
						<option value="22:00">10:00pm</option>
					</select>
					</br>
					
					
					<input type="submit" value="Confirm" name="Confirm" />
					</br>
					
					</br>
					</td>
				</fieldset>
			</form>
			<?php }?>
        </table>
    </div>
	
    <h1>Current Reservations</h1>
	<?php 
		
		// Create connection
		$db = new Database();
		$db->createConnection();
		$custId = $profile->getId();
		$sql = "SELECT *,DATE_FORMAT(reservation_time, '%H:%i')as time FROM `reservation` WHERE customer_id=$custId AND reservation_date>= CURDATE() ORDER BY `reservation_date` ASC";

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

		$db->closeConnection();
	?>
	<div class="friendlist-container">
        <table id="menu">
		  <?php
			if (count($reservations)==0){
				
				echo "<tr><td><p>You dont have any reservations</p></td></tr>";
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
							<td>
								 <a class = "unfriend" href="reservationpage.php?cancel='. $value->getId() . '">Cancel</a>
							</td>
							</tr>
				';
				}
            }
			
         ?>
        </table>
    </div>
	
	<h1>Past Reservations</h1>
	<?php 
		
		// Create connection
		$db = new Database();
		$db->createConnection();
		$custId = $profile->getId();
		$sql = "SELECT *,DATE_FORMAT(reservation_time, '%H:%i')as time FROM `reservation` WHERE customer_id=$custId AND reservation_date < CURDATE() ORDER BY `reservation_date` ASC";

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

		$db->closeConnection();
	?>
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
	
	
    <?php include "footer_login.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>