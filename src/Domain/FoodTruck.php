<?php

namespace App\Domain;

readonly class FoodTruck
{
    public string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}