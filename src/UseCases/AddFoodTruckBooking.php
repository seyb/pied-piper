<?php

namespace App\UseCases;

use App\Domain\BookingAdded;
use App\Domain\BookingRepository;
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

    public function book(FoodTruckBooking $booking): BookingAdded
    {
        $this->logger->info('Adding a booking for {foodtruck} on day {day}' , ['foodtruck' => $booking->foodTruck, 'day' => $booking->day]);
        $this->bookingRepository->save($booking);
        return new BookingAdded();
    }

}