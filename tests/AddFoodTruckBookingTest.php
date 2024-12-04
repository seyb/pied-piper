<?php

namespace App\Tests;

use App\Domain\BookedTwiceError;
use App\Domain\BookingAdded;
use App\Domain\BookingDay;
use App\Domain\BookingRepository;
use App\Domain\DayQuotaError;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\UseCases\AddFoodTruckBooking;

class InMemoryBookingRepository implements BookingRepository
{
    /** @var FoodTruckBooking[] */
    public array $bookings = [];

    public function save(FoodTruckBooking $booking): void
    {
        $this->bookings[$booking->foodTruck->name] = $booking;
    }

    public function hasBooked(FoodTruck $foodTruck): bool
    {
        return isset($this->bookings[$foodTruck->name]);
    }

    public function hasReachedDayQuota(BookingDay $day): bool
    {
        $dayBookings = array_filter($this->bookings, function (FoodTruckBooking $booking) use ($day) {
            return ($booking->day === $day);
        });
        return match ($day) {
            BookingDay::Friday => (count($dayBookings) >= 7),
            default => (count($dayBookings) >= 8),
        };
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

    /**
     * @dataProvider dayQuotaProvider
     */
    function test_it_fails_if_day_quota_is_reached(BookingDay $day, int $expectedQuota)
    {
        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());

        $days = [BookingDay::Monday,
            BookingDay::Tuesday,
            BookingDay::Wednesday,
            BookingDay::Thursday,
            BookingDay::Friday];
        foreach ($days as $i => $d) {
            $foodTruck = new FoodTruck('food truck of day ' . $i);
            $this->assertEquals(new BookingAdded(), $useCase->book(new FoodTruckBooking($foodTruck, $d)));
        }

        for ($i = 1; $i < $expectedQuota; $i++) {
            $foodTruck = new FoodTruck('food truck ' . $i);
            $result = $useCase->book(new FoodTruckBooking($foodTruck, $day));
            $this->assertEquals(new BookingAdded(), $result);
        }

        $foodTruck = new FoodTruck('food truck ' . $expectedQuota + 1);
        $result = $useCase->book(new FoodTruckBooking($foodTruck, $day));
        $this->assertEquals(new DayQuotaError(), $result);
    }

    function dayQuotaProvider(): array
    {
        return [
            'Monday quota' => [BookingDay::Monday, 8],
            'Tuesday quota' => [BookingDay::Tuesday, 8],
            'Wednesday quota' => [BookingDay::Wednesday, 8],
            'Thursday quota' => [BookingDay::Thursday, 8],
            'Friday quota' => [BookingDay::Friday, 7],
        ];
    }
}