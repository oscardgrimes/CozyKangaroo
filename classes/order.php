<?php
class Order
{
    // Properties
    public $orderId;
    public $tableId;
    public $type;
    public $date;
    public $time;
    public $custId;
    public $id;
    public $items;

    function __construct($id, $tableId, $type, $date, $time, $custId)
    {
        $this->tableId = $tableId;
        $this->type = $type;
        $this->date = $date;
        $this->time = $time;
        $this->custId = $custId;
        $this->id = $id;
    }

    // Accessors: getters/setters
    function getId()
    {
        return $this->id;
    }

    function getorderId(){
        return $this->orderId;
    }

    function getTableId()
    {
        return $this->tableId;
    }

    function getType()
    {
        return $this->type;
    }

    function getDate()
    {
        return $this->date;
    }

    function getCustId()
    {
        return $this->custId;
    }

    function getTime()
    {
        return $this->time;
    }

    function getItems()
    {
        return $this->items;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setTableId($tableId)
    {
        $this->tableId = $tableId;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function setPrice($date)
    {
        $this->date = $date;
    }

    function setCustId($custId)
    {
        $this->custId = $custId;
    }

    function setTime($time)
    {
        $this->time = $time;
    }

    function setItems($items)
    {
        $this->items = $items;
    }

    // Methods
    function CalculateTotalPrice($orderedItems)
    {
        $total = 0;
        foreach ($orderedItems as $key => $value) {
            $total += ($value->getPrice());
        }

        return $total;
    }

    // Methods
    function CalculateTotalOrderTime($orderedItems)
    {
        $total = 0;
        foreach ($orderedItems as $key => $value) {
            $total += ($value->getTime());
        }

        return $total;
    }
}
