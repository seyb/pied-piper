<?php

namespace App\UseCases;

use App\Domain\BookingDay;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;

class ListFoodTruckBooking
{
    private BookingRepository $bookingRepository;
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**  @return FoodTruck[] */
    public function foodTrucksByDay(BookingDay $day): array
    {
        $foodTruckBookings = $this->bookingRepository->getBookingsByDay($day);
        return array_map(function (FoodTruckBooking $booking) {
            return $booking->foodTruck;
        }, array_values($foodTruckBookings));
    }

    public function planning(): array {
        return [
            BookingDay::Monday->toString() => $this->foodTrucksByDay(BookingDay::Monday),
            BookingDay::Tuesday->toString() => $this->foodTrucksByDay(BookingDay::Tuesday),
            BookingDay::Wednesday->toString() => $this->foodTrucksByDay(BookingDay::Wednesday),
            BookingDay::Thursday->toString() => $this->foodTrucksByDay(BookingDay::Thursday),
            BookingDay::Friday->toString() => $this->foodTrucksByDay(BookingDay::Friday),
        ];
    }
}