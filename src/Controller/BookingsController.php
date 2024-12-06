<?php

namespace App\Controller;

use App\Domain\BookingDay;
use App\Domain\FoodTruck;
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
        $foodTrucks = $this->listFoodTruckBooking->foodTrucksByDay(BookingDay::Monday);

        $responseCode = count($foodTrucks) > 0 ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
        return $this->json(array_map(function (FoodTruck $foodTruck) {
            return $foodTruck->name;
        }, $foodTrucks), $responseCode);
    }
}

