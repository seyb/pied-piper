<?php

namespace App\Infrastructure;

use App\Domain\BookingDay;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;

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