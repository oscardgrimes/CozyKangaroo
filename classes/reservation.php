<?php

include "classes/table.php";
class Reservation
{
	public $reservationId;
	public $customerId;
	public $reservationDate;
	public $reservationTime;
	public $tableId;
	
	function __construct($id,$customerId, $reservationDate, $reservationTime, $tableId)
    {
		$this->reservationId = $id;
        $this->customerId = $customerId;
        $this->reservationDate = $reservationDate;
        $this->reservationTime = $reservationTime;
		$this->tableId =$tableId;
    }
	
	function getId(){
		return $this->reservationId;
	}
	
	function setId($reservationId){
		 $this->reservationId = $reservationId;
	}
	
	
	function getcustId(){
		return $this->customerId;
	}
	
	function setcustId($customerId){
		$this->customerId = $customerId;
	}
	
	function getDate(){
		return $this->reservationDate;
	}
	
	function setDate($reservationDate){
		$this->reservationDate = $reservationDate;
	}
	
	function getTime(){
		return $this->reservationTime;
	}
	
	function setTime($reservationTime){
		$this->reservationTime = $reservationTime;
	}
	
	function gettableId(){
		return $this->tableId;
	}
	
	function settableid($tableId){
		$this->tableId = $tableId;
	}
	
	
	function createReservation($customerId,$date,$time,$tableId){
		 // Create connection
        $db = new Database();
        $db->createConnection();

        $sql = "INSERT INTO `reservation`(`customer_id`, `reservation_date`, `reservation_time`, `table_id`) VALUES ($customerId,'$date','$time',$tableId)";

        $prepared_stmt = mysqli_prepare($db->getConnection(), $sql);

        //Execute prepared statement
        $status = $prepared_stmt->execute();

        $prepared_stmt->close();

        $db->closeConnection();

        return $status;
	}
	
	function cancelResvation($reservationId){
		$db = new Database();
        $db->createConnection();
		
        $sql = "Delete FROM `reservation` WHERE `reservation`.`reservation_id` = $reservationId";
		$prepared_stmt = mysqli_prepare($db->getConnection(), $sql);
		$status = $prepared_stmt->execute();
      
		$db->closeConnection();
		return $status;
	}
	
	function updateResvation($date,$time,$tableId,$reservationId){
		$db = new Database();
        $db->createConnection();
        $sql = "UPDATE `reservation` SET `reservation_date` = '$date', `reservation_time` = '$time', `tableId` = '$tableId' where `reservation_id` = '$reservationId'";
		$prepared_stmt = mysqli_prepare($db->getConnection(), $sql);
		$status = $prepared_stmt->execute();
      
		$db->closeConnection();
		return $status;
	}
	
	function getAviableTable($date,$time){
			// Create connection
		$db = new Database();
		$db->createConnection();

		$sql = "SELECT `table_id` FROM `reservation` WHERE reservation_date = '$date' AND reservation_time = '$time' ";

		$prepared_stmt = @mysqli_prepare($db->getConnection(), $sql);
		//Execute prepared statement
		mysqli_stmt_execute($prepared_stmt);
		
		// Get resultset
		$queryResult =  mysqli_stmt_get_result($prepared_stmt)
			or die("<p>Unable to select from database table</p>");

		// Close the prepared statement
		@mysqli_stmt_close($prepared_stmt);

		$row = mysqli_fetch_row($queryResult);
		
		$tableIds = array();
		$count = 0;
		while ($row) {
			$tableIds[$count] = $row[0];
			$row = mysqli_fetch_row($queryResult);
			$count += 1;
		}
		
		if($count == 0){
			$sql = "SELECT * FROM `table`";

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
			while ($row) {
				$tables[$count] = new Table($row[0],$row[2]);
				$row = mysqli_fetch_row($queryResult);
				$count += 1;
			}
			return $tables;
		}
		$sql = "SELECT * FROM `table` WHERE table_num != $tableIds[0] ";
		
		$i = 1;
		$count = count($tableIds);
		
		while ($i < $count){
			
			$sql = $sql."AND table_num != $tableIds[$i] ";
			$i+=1;
		}
		
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
		while ($row) {
			$tables[$count] = new Table($row[0],$row[2]);
			$row = mysqli_fetch_row($queryResult);
			$count += 1;
		}
		
		$db->closeConnection();
		
		if($count==0){
			return "null";
		}else{
			return $tables;
		}
		
	}
}
?>