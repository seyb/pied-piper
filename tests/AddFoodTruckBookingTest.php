<?php

namespace App\Tests;

use App\Domain\BookedTwiceError;
use App\Domain\BookingAdded;
use App\Domain\BookingDay;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\UseCases\AddFoodTruckBooking;

class InMemoryBookingRepository implements BookingRepository
{
    /** @var FoodTruckBooking[]  */
    public array $bookings = [];

    public function save(FoodTruckBooking $booking): void
    {
        $this->bookings[$booking->foodTruck->name] = $booking;
    }

    public function hasBooked(FoodTruck $foodTruck): bool
    {
        return isset($this->bookings[$foodTruck->name]);
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

    function test_it_fails_if_food_truck_already_booked()
    {
        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $foodTruck = new FoodTruck('food truck 1');
        $booking1 = new FoodTruckBooking($foodTruck, BookingDay::Monday);
        $booking2 = new FoodTruckBooking($foodTruck, BookingDay::Tuesday);

        $useCase->book($booking1);
        $result = $useCase->book($booking2);

        $this->assertEquals(new BookedTwiceError(), $result);
    }

    function test_it_succeeds_booking_with_different_food_truck()
    {
        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $foodTruck1 = new FoodTruck('food truck 1');
        $foodTruck2 = new FoodTruck('food truck 2');
        $booking1 = new FoodTruckBooking($foodTruck1, BookingDay::Monday);
        $booking2 = new FoodTruckBooking($foodTruck2, BookingDay::Tuesday);

        $useCase->book($booking1);
        $result = $useCase->book($booking2);

        $this->assertEquals(new BookingAdded(), $result);
    }
}
