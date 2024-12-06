<?php

namespace App\Domain;

interface BookingRepository
{
    public function save(FoodTruckBooking $booking): void;

    public function hasBooked(FoodTruck $foodTruck): bool;

    public function hasReachedDayQuota(BookingDay $day): bool;

    /**
     * @param BookingDay $day
     * @return FoodTruckBooking[]
     */
    public function getBookingsByDay(BookingDay $day): array;
}