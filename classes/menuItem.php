<?php
class MenuItem
{
    // Properties

    private $id;
    private $name;
    private $description;
    private $price;
    private $cost;
    private $time;

    function __construct($id, $name, $description, $price, $cost, $time)
    {
        // initialize the attributes with values passed from the parameters
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->cost = $cost;
        $this->time = $time;
    }

    // Methods: getters / setters
    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getPrice()
    {
        return $this->price;
    }

    function getCost()
    {
        return $this->cost;
    }

    function getTime()
    {
        return $this->time;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }

    function setCost($cost)
    {
        $this->cost = $cost;
    }

    function setTime($time)
    {
        $this->time = $time;
    }
}
