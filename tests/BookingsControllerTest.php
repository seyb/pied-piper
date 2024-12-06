<?php

namespace App\Tests;

use App\Domain\BookingDay;
use App\Domain\BookingRepository;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\Infrastructure\InMemoryBookingRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BookingsControllerTest extends WebTestCase
{

    public function test_it_returns_empty_list_bookings_when_no_data(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/bookings');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function test_it_lists_bookings_of_the_week_by_default(): void
    {
        $client = static::createClient();
        $expectedName = 'Food Truck';
        $booking = new FoodTruckBooking(new FoodTruck($expectedName), BookingDay::Monday);

        $bookingRepository = new InMemoryBookingRepository();
        $bookingRepository->save($booking);
        $client->getContainer()->set(BookingRepository::class, $bookingRepository);

        $client->request('GET', '/api/bookings');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertContains($expectedName, json_decode($client->getResponse()->getContent(), true));
    }
}
