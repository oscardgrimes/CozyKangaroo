<?php
class Table
{
    // Properties
    public $tablenum;
    public $numOfSeats;

    function __construct($tablenum, $numOfSeats)
    {
        $this->tablenum = $tablenum;
        $this->numOfSeats = $numOfSeats;
    }

    // Methods: getters / setters
    function getTableNum()
    {
        return $this->tablenum;
    }

    function getTableSeats()
    {
        return $this->numOfSeats;
    }

    function setTableNum($tablenum)
    {
        $this->tablenum = $tablenum;
    }

    function setTableSeats($numOfSeats)
    {
        $this->numOfSeats = $numOfSeats;
    }

    function setReservations($reservations)
    {
        $this->reservations = $reservations;
    }


}
