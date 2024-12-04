<?php

namespace App\Tests;

use App\Domain\AddFoodTruckBooked;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\UseCases\AddFoodTruckBooking;

class InMemoryBookingRepository implements BookingRepository
{
    public bool $isSaved;

    public function __construct()
    {
        $this->isSaved = false;
    }

    public function save(FoodTruckBooking $booking): bool
    {
        $this->isSaved = true;
        return $this->isSaved;
    }
}

class AddFoodTruckBookingTest extends ApplicationTestCase
{
    function test_it_succeeds_adding_a_booking()
    {
        $useCase = new AddFoodTruckBooking(new InMemoryBookingRepository(), $this->initializeLoggerMock());
        $booking = new FoodTruckBooking(new FoodTruck('food truck 1'));

        $result = $useCase->book($booking);

        $this->assertEquals(new AddFoodTruckBooked(), $result);
    }


    function test_it_stores_the_booking()
    {
        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $booking = new FoodTruckBooking(new FoodTruck('food truck 1'));

        $useCase->book($booking);

        $this->assertTrue($bookingRepository->isSaved);
    }
}
