<?php

namespace App\Tests\UseCases;

use App\Domain\BookingDay;
use App\Domain\BookingDeleted;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\Domain\NoBookingError;
use App\Infrastructure\InMemoryBookingRepository;
use App\Tests\ApplicationTestCase;
use App\UseCases\AddFoodTruckBooking;
use App\UseCases\DeleteFoodTruckBooking;

class DeleteFoodTruckBookingTest extends ApplicationTestCase
{
    function test_it_deletes_a_food_truck_booking_when_it_exists()
    {
        $foodTruckName = 'food truck';
        $foodTruck = new FoodTruck($foodTruckName);
        $bookingRepository = new InMemoryBookingRepository();
        $booking = new FoodTruckBooking($foodTruck, BookingDay::Monday);
        (new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock()))->call($booking);

        $useCase = new DeleteFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());

        $this->assertEquals(new BookingDeleted(), $useCase->call($foodTruck));
    }

    function test_it_does_not_delete_a_food_truck_booking_when_it_does_not_exist() {
        $foodTruck = new FoodTruck('food truck');
        $bookingRepository = new InMemoryBookingRepository();

        $useCase = new DeleteFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $this->assertEquals(new NoBookingError(), $useCase->call($foodTruck));
    }
}
