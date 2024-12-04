<?php

namespace App\UseCases;

use App\Domain\BookingDeleted;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
use App\Domain\NoBookingError;
use Psr\Log\LoggerInterface;

class DeleteFoodTruckBooking
{
    private BookingRepository $bookingRepository;
    function __construct(BookingRepository $bookingRepository, LoggerInterface $logger)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function call(FoodTruck $foodTruck): BookingDeleted|NoBookingError
    {
        if (!$this->bookingRepository->hasBooked($foodTruck))
            return new NoBookingError();
        return new BookingDeleted();
    }

}