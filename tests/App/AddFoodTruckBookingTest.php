<?php

namespace App;

use App\App\UseCases\AddFoodTruckBooking;
use App\Domain\AddFoodTruckBooked;
use App\Domain\FoodTruck;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;


class AddFoodTruckBookingTest extends TestCase
{
    function test_calling_adding_a_booking_succeeds()
    {
        $logger = $this->initializeLoggerMock();
        $useCase = new AddFoodTruckBooking($logger);
        $foodtruck = new FoodTruck();

        $this->assertEquals(new AddFoodTruckBooked(), $useCase->book($foodtruck, new \DateTime()));
    }


    public function initializeLoggerMock(): MockObject|LoggerInterface
    {
        return $this->createMock(LoggerInterface::class);
    }
}
