<?php
class Payment{
    //attributes
    //database column
    private $orderId;
    private $customerId;
    private $paymentDate;
    private $paymentTime;
    private $paymentAmount;
    private $paymentPaid;
    private $paymentChanges;
    
    
    //constructor
    function __construct($orderId,$customerId,$paymentDate,$paymentTime,$paymentAmount,$paymentPaid,$paymentChanges){
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->paymentDate = $paymentDate;
        $this->paymentTime = $paymentTime;
        $this->paymentAmount = $paymentAmount;
        $this->paymentPaid = $paymentPaid;
        $this->paymentChanges = $paymentChanges;
    }
    
    // Accessors: getters/setters
    function getorderId()
    {
        return $this->orderId;
    }

    function setId($id)
    {
        $this->$orderId = $id;
    }

    function getcustomerID()
    {
        return $this->customerId;
    }

    function setcustomerID($customerId)
    {
        $this->customerId = $customerId;
    }
    
    function getpaymentDate()
    {
        return $this->paymentDate;
    }

    function setpaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;
    }

    function getpaymentTime()
    {
        return $this->paymentTime;
    }

    function setpaymentTime($paymentTime)
    {
        $this->paymentTime = $paymentTime;
    }

    function getpaymentAmount()
    {
        return $this->paymentAmount;
    }

    function setpaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
    }

    function getpaymentPaid()
    {
        return $this->paymentPaid;
    }

    function setpaymentPaid($paymentPaid)
    {
        $this->paymentPaid = $paymentPaid;
    }

    function getpaymentChanges()
    {
        return $this->paymentChanges;
    }

    function setpaymentChanges($paymentChanges)
    {
        $this->paymentChanges = $paymentChanges;
    }


    //method
    //calculate customer payment changes
    function calcChanges($paymentPaid){
        if ($this->$paymentAmount > $paymentPaid) {
            return false;
        }
        $paymentChanges = $paymentAmount - $paymentPaid;
        insertPaymenttodb();
        return true;
        
    }

    //update database
    function insertPaymenttodb() {
        // Create connection
        $db = new Database();
        $db->createConnection();
        
        $sql = "INSERT INTO payment (orderId, customerId, paymentDate, paymentTime, paymentAmount, paymentPaid, paymentChanges) VALUES (?,?,?,?,?,?,?)";

        $prepared_stmt = mysqli_prepare($db->getConnection(), $sql);

        //Bind input variables to prepared statement
        $prepared_stmt->bind_param("iissddd", $orderId, $customerId, $paymentDate, $paymentTime, $paymentAmount, $paymentPaid, $paymentChanges);
        
        $orderId  = $this->orderId;
        $customerId = $this->customerId;
        $paymentDate =  $this->paymentDate;
        $paymentTime = $this->paymentTime;
        $paymentAmount = $this->paymentAmount;
        $paymentPaid = $this->paymentPaid;
        $paymentChanges = $this->paymentChanges;

        //Execute prepared statement
        $status = $prepared_stmt->execute();
        echo $prepared_stmt->affected_rows;
        $prepared_stmt->close();

        $db->closeConnection();
    }

}

//factory method to create object
//https://phptherightway.com/pages/Design-Patterns.html
class PaymentFactory{
    public static function createPaymentObj($orderId,$customerId,$paymentDate,$paymentTime,$paymentAmount,$paymentPaid,$paymentChanges){
        return new Payment($orderId,$customerId,$paymentDate,$paymentTime,$paymentAmount,$paymentPaid,$paymentChanges);
    }
}
?>