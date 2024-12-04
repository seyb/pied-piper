<?php

namespace App\Domain;

readonly class FoodTruckBooking
{
    public FoodTruck $foodTruck;
    public \DateTime $date;

    function __construct(FoodTruck $foodTruck, \DateTime $date)
    {
        $this->foodTruck = $foodTruck;
        $this->date = $date;
    }
}