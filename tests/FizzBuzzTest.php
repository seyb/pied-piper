<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

function fizzbuzz(int $number) : string {
    return (string)$number;
}

class FizzBuzzTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertEquals('1', fizzbuzz(1));
    }
}
