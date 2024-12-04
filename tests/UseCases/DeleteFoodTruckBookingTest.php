<?php

namespace App\Tests\UseCases;

use App\Domain\BookingDay;
use App\Domain\BookingDeleted;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\Infrastructure\InMemoryBookingRepository;
use App\Tests\ApplicationTestCase;
use App\UseCases\DeleteFoodTruckBooking;

class DeleteFoodTruckBookingTest extends ApplicationTestCase
{
    function test_it_deletes_a_food_truck_booking_when_it_exists()
    {
        $foodTruck = new FoodTruck('food truck');
        $bookingRepository = new InMemoryBookingRepository();
        $bookingRepository->bookings[] = new FoodTruckBooking($foodTruck, BookingDay::Monday);

        $useCase = new DeleteFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());

        $this->assertEquals(new BookingDeleted(), $useCase->call($foodTruck));
    }
}
