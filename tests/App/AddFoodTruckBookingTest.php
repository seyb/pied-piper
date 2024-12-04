<?php

namespace App;

use App\App\UseCases\AddFoodTruckBooking;
use App\Domain\AddFoodTruckBooked;
use PHPUnit\Framework\TestCase;

class AddFoodTruckBookingTest extends TestCase
{
    function test_calling_adding_a_booking_succeeds()
    {
        $useCase = new AddFoodTruckBooking();
        $this->assertEquals(new AddFoodTruckBooked(), $useCase->book());
    }
}
