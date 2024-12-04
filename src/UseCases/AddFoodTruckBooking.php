<?php

namespace App\UseCases;

use App\Domain\AddFoodTruckBooked;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
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

    public function book(FoodTruck $foodtruck, \DateTime $date): AddFoodTruckBooked
    {
        $this->logger->info('Adding a booking for {foodtruck} on {date}' , ['foodtruck', $foodtruck, 'date', $date]);
        $this->bookingRepository->save(new FoodTruckBooking());
        return new AddFoodTruckBooked();
    }

}