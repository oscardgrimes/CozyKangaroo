<?php
include "classes/customer.php";
include "classes/menuItem.php";

class Menu
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Methods: getters / setters
    function AddItem($newItem)
    {
        // Add new Item to database

        // Create connection
        $db = new Database();
        $db->createConnection();

        $sql = "INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES (?,?,?,?,?)";

        $prepared_stmt = mysqli_prepare($db->getConnection(), $sql);

        //Bind input variables to prepared statement
        $prepared_stmt->bind_param("ssdds", $itemName, $itemDescription, $itemPrice, $itemCost, $itemTime);

        $itemName  = $newItem->getName();
        $itemDescription = $newItem->getDescription();
        $itemPrice = $newItem->getPrice();
        $itemCost =  $newItem->getCost();
        $itemTime = $newItem->getTime();

        //Execute prepared statement
        $status = $prepared_stmt->execute();

        $prepared_stmt->close();

        $db->closeConnection();

        return $status;
    }

    function UpdateItem($updateItem)
    {
        $this->db->createConnection();

        $sql = "UPDATE `menu_item` SET `item_name`=?,`item_desc`=?,`item_price`=?,`item_cost`=?,`item_time`=? WHERE `item_id` = ?";

        $prepared_stmt = mysqli_prepare($this->db->getConnection(), $sql);

        //Bind input variables to prepared statement
        $prepared_stmt->bind_param("ssddsi", $itemName, $itemDescription, $itemPrice, $itemCost, $itemTime, $itemId);

        $itemName  = $updateItem->getName();
        $itemDescription = $updateItem->getDescription();
        $itemPrice = $updateItem->getPrice();
        $itemCost =  $updateItem->getCost();
        $itemTime = $updateItem->getTime();
        $itemId = $updateItem->getId();

        //Execute prepared statement
        $status = $prepared_stmt->execute();

        $prepared_stmt->close();

        $this->db->closeConnection();

        return $status;
    }

    function DeleteItem($itemId)
    {
        // Delete Item from database

        // Create connection
        $db = new Database();
        $db->createConnection();

        $sql = "DELETE FROM `menu_item` WHERE item_id = ?";

        $prepared_stmt = mysqli_prepare($db->getConnection(), $sql);

        //Bind input variables to prepared statement
        $prepared_stmt->bind_param("i", $itemId);

        //Execute prepared statement
        $status = $prepared_stmt->execute();

        $prepared_stmt->close();

        $db->closeConnection();

        return $status;
    }
}
