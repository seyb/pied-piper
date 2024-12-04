<?php

namespace App\Domain;

readonly class FoodTruckBooking
{
    public FoodTruck $foodTruck;

    function __construct(FoodTruck $foodTruck)
    {
        $this->foodTruck = $foodTruck;
    }
}