<?php

namespace App\Tests;

use App\Domain\AddFoodTruckBooked;
use App\Domain\FoodTruck;
use App\UseCases\AddFoodTruckBooking;

class AddFoodTruckBookingTest extends ApplicationTestCase
{
    function test_calling_adding_a_booking_succeeds()
    {
        $logger = $this->initializeLoggerMock();
        $useCase = new AddFoodTruckBooking($logger);
        $foodtruck = new FoodTruck();
        $date = new \DateTime();

        $this->assertEquals(new AddFoodTruckBooked(), $useCase->book($foodtruck, $date));
    }



}
