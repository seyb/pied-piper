<?php

namespace App\Tests\UseCases;

use App\Domain\BookingDay;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\Infrastructure\InMemoryBookingRepository;
use App\Tests\ApplicationTestCase;
use App\UseCases\AddFoodTruckBooking;
use App\UseCases\ListFoodTruckBooking;

class ListFoodTruckBookingTest extends ApplicationTestCase
{
    function test_it_lists_food_truck_that_booked_a_given_day()
    {
        $foodTruck1 = new FoodTruck('Food truck 1');
        $foodTruck2 = new FoodTruck('Food truck 2');
        $foodTruck3 = new FoodTruck('Food truck 3');
        $mondayBooking = new FoodTruckBooking($foodTruck1, BookingDay::Monday);
        $tuesdayBooking = new FoodTruckBooking($foodTruck2, BookingDay::Tuesday);
        $wednesdayBooking = new FoodTruckBooking($foodTruck3, BookingDay::Wednesday);

        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $useCase->call($mondayBooking);
        $useCase->call($tuesdayBooking);
        $useCase->call($wednesdayBooking);

        $mondayFoodTrucks = (new ListFoodTruckBooking($bookingRepository))->foodTrucksByDay(BookingDay::Monday);
        $this->assertEquals($mondayFoodTrucks, [$foodTruck1]);
    }

    function test_it_gives_the_planning()
    {
        $foodTruck1 = new FoodTruck('Food truck 1');
        $foodTruck2 = new FoodTruck('Food truck 2');
        $foodTruck3 = new FoodTruck('Food truck 3');
        $mondayBooking = new FoodTruckBooking($foodTruck1, BookingDay::Monday);
        $tuesdayBooking = new FoodTruckBooking($foodTruck2, BookingDay::Tuesday);
        $wednesdayBooking = new FoodTruckBooking($foodTruck3, BookingDay::Wednesday);

        $bookingRepository = new InMemoryBookingRepository();
        $useCase = new AddFoodTruckBooking($bookingRepository, $this->initializeLoggerMock());
        $useCase->call($mondayBooking);
        $useCase->call($tuesdayBooking);
        $useCase->call($wednesdayBooking);

        $mondayFoodTrucks = (new ListFoodTruckBooking($bookingRepository))->planning();
        $this->assertEquals($mondayFoodTrucks, [
                BookingDay::Monday->toString() => [$foodTruck1],
                BookingDay::Tuesday->toString() => [$foodTruck2],
                BookingDay::Wednesday->toString() => [$foodTruck3],
                BookingDay::Thursday->toString() => [],
                BookingDay::Friday->toString() => []
            ]
        );
    }
}
