<?php

namespace App\Domain;

readonly class FoodTruckBooking
{
    public FoodTruck $foodTruck;
    public BookingDay $day;

    function __construct(FoodTruck $foodTruck, BookingDay $day)
    {
        $this->foodTruck = $foodTruck;
        $this->day = $day;
    }
}