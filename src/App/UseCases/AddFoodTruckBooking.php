<?php

namespace App\App\UseCases;

use App\Domain\AddFoodTruckBooked;

class AddFoodTruckBooking
{
    public function __construct()
    {
        return true;
    }

    public function book()
    {
        return new AddFoodTruckBooked();
    }

}