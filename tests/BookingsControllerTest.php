<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingsControllerTest extends WebTestCase
{
    public function test_list_bookings(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/bookings');

        $this->assertResponseIsSuccessful();
    }
}
