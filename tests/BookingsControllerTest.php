<?php

namespace App\Tests;

use App\Domain\BookingDay;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\Infrastructure\InMemoryBookingRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BookingsControllerTest extends WebTestCase
{
    public function test_it_lists_bookings_of_the_week_by_default(): void
    {
        $client = static::createClient();
        $bookingRepository = new InMemoryBookingRepository();

        $mondayBooking1 = new FoodTruckBooking(new FoodTruck('Monday truck 1'), BookingDay::Monday);
        $mondayBooking2 = new FoodTruckBooking(new FoodTruck('Monday truck 2'), BookingDay::Monday);
        $mondayBooking3 = new FoodTruckBooking(new FoodTruck('Monday truck 3'), BookingDay::Monday);

        $wednesdayBooking1 = new FoodTruckBooking(new FoodTruck('Wednesday truck 1'), BookingDay::Wednesday);
        $wednesdayBooking2 = new FoodTruckBooking(new FoodTruck('Wednesday truck 2'), BookingDay::Wednesday);
        $wednesdayBooking3 = new FoodTruckBooking(new FoodTruck('Wednesday truck 3'), BookingDay::Wednesday);

        $bookingRepository->save($mondayBooking1);
        $bookingRepository->save($mondayBooking2);
        $bookingRepository->save($mondayBooking3);
        $bookingRepository->save($wednesdayBooking1);
        $bookingRepository->save($wednesdayBooking2);
        $bookingRepository->save($wednesdayBooking3);

        $client->getContainer()->set(BookingRepository::class, $bookingRepository);

        $client->request('GET', '/api/bookings');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals([
            BookingDay::Monday->toString() => ['Monday truck 1', 'Monday truck 2', 'Monday truck 3'],
            BookingDay::Tuesday->toString() => [],
            BookingDay::Wednesday->toString() => ['Wednesday truck 1', 'Wednesday truck 2', 'Wednesday truck 3'],
            BookingDay::Thursday->toString() => [],
            BookingDay::Friday->toString() => [],
        ], json_decode($client->getResponse()->getContent(), true));
    }

    public function test_it_adds_a_booking(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/bookings', [
            'foodTruck' => 'food truck',
            'day' => BookingDay::Monday->toString()
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function test_it_add_stores_bookings(): void {
        $client = static::createClient();
        $bookingRepository = new InMemoryBookingRepository();
        $client->getContainer()->set(BookingRepository::class, $bookingRepository);

        $foodtruck = 'food truck';
        $client->request('POST', '/api/bookings', [
            'foodTruck' => $foodtruck,
            'day' => BookingDay::Monday->toString()
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertTrue($bookingRepository->hasBooked(new FoodTruck($foodtruck)));
    }
}
