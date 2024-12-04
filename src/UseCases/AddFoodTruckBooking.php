<?php

namespace App\UseCases;

use App\Domain\AddFoodTruckBooked;
use App\Domain\FoodTruck;
use Psr\Log\LoggerInterface;

class AddFoodTruckBooking
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function book(FoodTruck $foodtruck, \DateTime $date): AddFoodTruckBooked
    {
        $this->logger->info('Adding a booking for {foodtruck} on {date}' , ['foodtruck', $foodtruck, 'date', $date]);
        return new AddFoodTruckBooked();
    }

}