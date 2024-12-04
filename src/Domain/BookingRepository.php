<?php

namespace App\Domain;

interface BookingRepository
{
    public function save(FoodTruckBooking $booking): bool;
    public function hasBooked(FoodTruck $foodTruck): bool;
}