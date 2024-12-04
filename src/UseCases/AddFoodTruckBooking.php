<?php

namespace App\UseCases;

use App\Domain\BookedTwiceError;
use App\Domain\BookingAdded;
use App\Domain\BookingRepository;
use App\Domain\DayQuotaError;
use App\Domain\FoodTruckBooking;
use Psr\Log\LoggerInterface;

class AddFoodTruckBooking
{
    private LoggerInterface $logger;
    private BookingRepository $bookingRepository;
    public function __construct(BookingRepository $bookingRepository, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->bookingRepository = $bookingRepository;
    }

    public function book(FoodTruckBooking $booking): BookingAdded|BookedTwiceError|DayQuotaError
    {
        $this->logger->info('Adding a booking for {foodtruck} on day {day}' , ['foodtruck' => $booking->foodTruck, 'day' => $booking->day]);
        if ($this->bookingRepository->hasBooked($booking->foodTruck))
            return new BookedTwiceError();
        if ($this->bookingRepository->hasReachedDayQuota($booking->day))
            return new DayQuotaError();
        $this->bookingRepository->save($booking);
        return new BookingAdded();
    }

}