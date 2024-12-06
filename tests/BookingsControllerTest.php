<?php

namespace App\Tests;

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
}
