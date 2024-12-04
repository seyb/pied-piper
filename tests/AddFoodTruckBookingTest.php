<?php

namespace App\Tests;

use App\Domain\BookingAdded;
use App\Domain\BookingDay;
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
        return false;
    }

    public function hasBooked(FoodTruck $foodTruck): bool
    {
        return $this->isSaved;
    }
}

class AddFoodTruckBookingTest extends ApplicationTestCase
{
    function test_it_succeeds()
    {
        $useCase = new AddFoodTruckBooking(new InMemoryBookingRepository(), $this->initializeLoggerMock());
        $booking = new FoodTruckBooking(new FoodTruck('food truck 1'), BookingDay::Monday);

        $result = $useCase->book($booking);

        $this->assertEquals(new BookingAdded(), $result);
    }


    function test_it_stores_the_booking()
    {
        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $foodTruck = new FoodTruck('food truck 1');
        $booking = new FoodTruckBooking($foodTruck, BookingDay::Monday);


        $this->assertFalse($bookingRepository->hasBooked($foodTruck));

        $useCase->book($booking);

        $this->assertTrue($bookingRepository->hasBooked($foodTruck));
    }
}
