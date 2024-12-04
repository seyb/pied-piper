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
        $foodtruck = new FoodTruck();
        $date = new \DateTime();

        $result = $useCase->book($foodtruck, $date);

        $this->assertEquals(new AddFoodTruckBooked(), $result);
    }


    function test_it_stores_the_booking()
    {
        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $foodtruck = new FoodTruck();
        $date = new \DateTime();

        $useCase->book($foodtruck, $date);

        $this->assertTrue($bookingRepository->isSaved);
    }
}
