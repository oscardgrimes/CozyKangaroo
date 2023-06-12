<?php
include "classes/database.php";
include "classes/order.php";

class Customer
{
    // Properties
    public $name;
    public $email;
    public $password;
    public $id;

    function __construct($name, $email, $password, $id)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    // Methods
    function AddOrder($order)
    {
        // Add order to database

        // Create connection
        $db = new Database();
        $db->createConnection();

        $sql = "INSERT INTO `order`(`table_id`, `type`, `date`, `time`, `items`, `customer_id`) VALUES (?,?,?,?,?,?)";

        $prepared_stmt = mysqli_prepare($db->getConnection(), $sql);

        //Bind input variables to prepared statement
        $prepared_stmt->bind_param("issssi", $orderid, $ordertype, $orderdate, $ordertime, $ordereditems, $orderCustId);

        $orderid  = $order->getTableId();
        $ordertype = $order->getType();
        $orderdate = $order->getDate();
        $ordertime =  $order->getTime();
        $ordereditems = implode(",", $order->getItems());
        $orderCustId = $order->getCustId();

        //Execute prepared statement
        $status = $prepared_stmt->execute();

        $prepared_stmt->close();

        $db->closeConnection();

        return $status;
    }

    function AddReservation()
    {
    }
}
