<?php

namespace App\Controller;

use App\Domain\BookingDay;
use App\Domain\FoodTruck;
use App\Domain\FoodTruckBooking;
use App\UseCases\ListFoodTruckBooking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingsController extends AbstractController
{
    private ListFoodTruckBooking $listFoodTruckBooking;
    public function __construct(ListFoodTruckBooking $listFoodTruckBooking) {
        $this->listFoodTruckBooking = $listFoodTruckBooking;
    }
    #[Route('/api/bookings', name: 'app_bookings', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $planning = $this->listFoodTruckBooking->planning();

        $planningResource = array_map(function (array $bookings) {
            return $this->extractFoodTruckNames($bookings);
        }, $planning);
        return $this->json($planningResource, Response::HTTP_OK);
    }

    /**
     * @param FoodTruck[] $foodTrucks
     * @return array|string[]
     */
    private function extractFoodTruckNames(array $foodTrucks): array
    {
        return array_map(function (FoodTruck $foodTruck) {
            return $foodTruck->name;
        }, $foodTrucks);
    }
}

