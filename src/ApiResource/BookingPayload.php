<?php

namespace App\ApiResource;

use App\Domain\BookingDay;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

class BookingPayload
{
    public function __construct(
        #[Assert\NotBlank]
        public string $foodTruck,

        #[Assert\NotBlank]
        public string $day,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function toBooking(): FoodTruckBooking
    {
        $day = match ($this->day) {
            BookingDay::Monday->toString() => BookingDay::Monday,
            BookingDay::Tuesday->toString() => BookingDay::Tuesday,
            BookingDay::Wednesday->toString() => BookingDay::Wednesday,
            BookingDay::Thursday->toString() => BookingDay::Thursday,
            BookingDay::Friday->toString() => BookingDay::Friday,
            default => throw new Exception('Invalid day'),
        };

        return new FoodTruckBooking(
            new FoodTruck($this->foodTruck),
            $day
        );
    }
}